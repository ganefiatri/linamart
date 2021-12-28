<?php

/**
 * Foodpress coupon function
 * @package Foodpress/inc
 * @author Taufik Hidayat <taufik@fiqhidayat.com>
 */

add_action('init', 'foodpress_coupon');
/**
 * register Coupon post type
 * @return [type] [description]
 */
function foodpress_coupon()
{
    register_post_type(
        'foodpress-coupon', // Register Custom Post Type
        array(
            'labels' => array(
                'name'               => __('Coupon', 'foodpress'), // Rename these to suit
                'singular_name'      => __('Coupon', 'foodpress'),
                'add_new'            => __('Add New', 'foodpress'),
                'add_new_item'       => __('Add New Coupon', 'foodpress'),
                'edit'               => __('Edit', 'foodpress'),
                'edit_item'          => __('Edit Coupon', 'foodpress'),
                'new_item'           => __('New Coupon', 'foodpress'),
                'view'               => __('View Coupon', 'foodpress'),
                'view_item'          => __('View Coupon', 'foodpress'),
                'search_items'       => __('Search Coupon', 'foodpress'),
                'not_found'          => __('No Coupons found', 'foodpress'),
                'not_found_in_trash' => __('No Coupons found in Trash', 'foodpress')
            ),
            'public' => true,
            'hierarchical' => false,
            'has_archive' => false,
            'publicly_queryable' => false,
            'supports' => array(
                'title',
                'editor'
            ),
            'can_export' => false,
            'exclude_from_search' => true,
            'menu_icon' => 'dashicons-tag',
        )
    );
}

add_action('admin_print_scripts-post-new.php', 'foodpress_coupon_admin_script', 11);
add_action('admin_print_scripts-post.php', 'foodpress_coupon_admin_script', 11);
/**
 * tweak admin Coupon ui
 * @return [type] [description]
 */
function foodpress_coupon_admin_script()
{
    global $post_type;

    if ('foodpress-coupon' == $post_type) :
?>
<script type="text/javascript">
setTimeout(function() {
    document.getElementById('title-prompt-text').innerHTML = 'Add Coupon Code';
}, 100);
</script>
<?php
    endif;
}


add_filter('manage_foodpress-coupon_posts_columns', 'foodpress_coupon_column');
/**
 * register Coupon custom column
 * @param  [type] $columns [description]
 * @return [type]          [description]
 */
function foodpress_coupon_column($columns)
{

    $columns['title'] = 'Code';
    $columns['description'] = 'Description';
    $columns['coupon_type'] = 'Type';
    $columns['coupon_discount'] = 'Discount';
    $columns['coupon_expired'] = 'Expired';
    $columns['coupon_limit'] = 'Usage';

    unset($columns['date']);

    return $columns;
}

add_action('manage_foodpress-coupon_posts_custom_column', 'foodpress_coupon_content_column', 10, 2);
/**
 * Coupon custom column value
 * @param  [type] $column  [description]
 * @param  [type] $post_id [description]
 * @return [type]          [description]
 */
function foodpress_coupon_content_column($column, $post_id)
{

    switch ($column):

        case 'description':
            echo get_the_content($post_id);
            break;

        case 'coupon_type':
            $type = get_post_meta($post_id, 'coupon_type', true);
            echo $type == 'fixed' ? 'Fixed Discount' : 'Percentage Discount';
            break;

        case 'coupon_discount':
            echo number_format(get_post_meta($post_id, 'coupon_discount', true), 0, ',', '.');
            break;

        case 'coupon_expired':
            echo get_post_meta($post_id, 'coupon_expired', true);
            break;

        case 'coupon_limit':
            $amount = get_post_meta($post_id, 'coupon_amount', true);
            $usage = intval(get_post_meta($post_id, 'coupon_usage', true));
            echo $usage . '/' . $amount;
            break;
    endswitch;
}

add_filter('user_can_richedit', function ($default) {
    global $post;
    if ($post && $post->post_type === 'foodpress-coupon')  return false;
    return $default;
});


add_action('admin_footer', 'foodpress_coupon_admin_footer');
/**
 * admin coupon footer script
 * @return [type] [description]
 */
function foodpress_coupon_admin_footer()
{
    $current_screen = get_current_screen();
    if ($current_screen->parent_file == 'edit.php?post_type=foodpress-coupon') :
    ?>
<script>
jQuery('.row-actions .inline').hide();
</script>
<?php
    endif;
}


add_action('wp_ajax_apply_coupon', 'foodpress_apply_coupon');
add_action('wp_ajax_nopriv_apply_coupon', 'foodpress_apply_coupon');
/**
 * Foodpress ajax capply coupon
 * @return [type] [description]
 */
function foodpress_apply_coupon()
{

    $input = file_get_contents("php://input");

    /*
    no input ? return.
     */
    if (empty($input)) exit;
    $data = json_decode($input, true);

    $data_default = array(
        'nonce' => '',
        'code' => '',
        'cart' => array(),
    );

    $data = wp_parse_args($data, $data_default);

    $response = array(
        'status'   => 'invalid',
        'message'  => 'Kode voucher tidak valid',
    );

    $return = false;

    /*
    Check nonce.
     */
    if (isset($data['nonce'])  && wp_verify_nonce($data['nonce'], 'foodpress')) :
        $code_coupon = sanitize_text_field($data['code']);

        $coupon = foodpress_get_coupon($code_coupon);
        if ($coupon) {

            $total = 0;
            foreach ((array) $data['cart'] as $item) {
                $price = foodpress_get_item_price(intval($item['item_id']));

                $subtotal = intval($item['qty']) * intval($price);

                $total = $total + intval($subtotal);
            }

            if (!$return && $coupon->expired < strtotime('now')) {
                $response['message'] = 'Kode voucher sudah kadaluarsa';
                $return = true;
            }

            if (!$return && $total < $coupon->minimum_cart) {
                $response['message'] = 'Total belanja Anda belum mencapai batas minimum, batas minimum total belanja untuk dapat menggunakan voucher ini adalah Rp ' . number_format($coupon->minimum_cart, 0, '.', '.');
                $return = true;
            }

            if (!$return && $coupon->amount <= $coupon->usage) {
                $response['message'] = 'Kode voucher telah habis di gunakan';
                $return = true;
            }

            if (!$return) {

                if ($coupon->type == 'percent') {
                    $discount = ($coupon->discount * $total) / 100;
                } else {
                    $discount = $coupon->discount;
                }

                $response['status'] = 'valid';
                $response['message'] = 'Kode voucher valid';
                $response['discount'] = $discount;
            }
        }

        echo json_encode($response);
        exit;
    endif;
}