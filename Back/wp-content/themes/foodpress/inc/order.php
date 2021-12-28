<?php

/**
 * FoodPress order function
 * @package FoodPress/inc
 * @author Taufik Hidayat <taufik@fiqhidayat.com>
 */

add_action('init', 'foodpress_order');
/**
 * register order post type
 * @return [type] [description]
 */
function foodpress_order()
{
    $lic = new Foodpress_License();
    $data = $lic->data();

    if ($data['status'] !== 'ACTIVE') return;

    register_post_type(
        'order', // Register Custom Post Type
        array(
            'labels' => array(
                'name'               => __('Order', 'foodpress'), // Rename these to suit
                'singular_name'      => __('Order', 'foodpress'),
                'add_new'            => __('Add New', 'foodpress'),
                'add_new_item'       => __('Add New Order', 'foodpress'),
                'edit'               => __('Edit', 'foodpress'),
                'edit_item'          => __('Edit Order', 'foodpress'),
                'new_item'           => __('New Order', 'foodpress'),
                'view'               => __('View Order', 'foodpress'),
                'view_item'          => __('View Order', 'foodpress'),
                'search_items'       => __('Search Order', 'foodpress'),
                'not_found'          => __('No Orders found', 'foodpress'),
                'not_found_in_trash' => __('No Orders found in Trash', 'foodpress')
            ),
            'public' => false,
            'show_ui' => true,
            'publicly_queryable' => false,
            'exclude_from_search' => true,
            'hierarchical' => false,
            'has_archive' => false,
            'supports' => array(
                'title',
            ),
            'can_export' => false,
            'capability_type' => 'post',
            'capabilities' => array(
                //'create_posts' => false,
            ),
            'menu_icon' => 'dashicons-cart',
        )
    );


    register_post_status('new_order', array(
        'label'                     => _x('New Order', 'post status label', 'foodpress'),
        'public'                    => true,
        'label_count'               => _n_noop('New Order <span class="count">(%s)</span>', 'New Order <span class="count">(%s)</span>', 'plugin-domain'),
        'post_type'                 => array('order'), // Define one or more post types the status can be applied to.
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'show_in_metabox_dropdown'  => true,
        'show_in_inline_dropdown'   => true,
        'dashicon'                  => 'dashicons-yes',
    ));

    register_post_status('on_hold', array(
        'label'                     => _x('On Hold', 'post status label', 'foodpress'),
        'public'                    => true,
        'label_count'               => _n_noop('On Hold <span class="count">(%s)</span>', 'On Hold <span class="count">(%s)</span>', 'plugin-domain'),
        'post_type'                 => array('order'), // Define one or more post types the status can be applied to.
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'show_in_metabox_dropdown'  => true,
        'show_in_inline_dropdown'   => true,
        'dashicon'                  => 'dashicons-dismiss',
    ));

    register_post_status('on_shipping', array(
        'label'                     => _x('On Shipping', 'post status label', 'foodpress'),
        'public'                    => true,
        'label_count'               => _n_noop('On Shipping <span class="count">(%s)</span>', 'On Shipping <span class="count">(%s)</span>', 'plugin-domain'),
        'post_type'                 => array('order'), // Define one or more post types the status can be applied to.
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'show_in_metabox_dropdown'  => true,
        'show_in_inline_dropdown'   => true,
        'dashicon'                  => 'dashicons-dismiss',
    ));

    register_post_status('completed', array(
        'label'                     => _x('Completed', 'post status label', 'foodpress'),
        'public'                    => true,
        'label_count'               => _n_noop('Completed <span class="count">(%s)</span>', 'Completed <span class="count">(%s)</span>', 'plugin-domain'),
        'post_type'                 => array('order'), // Define one or more post types the status can be applied to.
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'show_in_metabox_dropdown'  => true,
        'show_in_inline_dropdown'   => true,
        'dashicon'                  => 'dashicons-businessman',
    ));

    register_post_status('canceled', array(
        'label'                     => _x('Canceled', 'post status label', 'foodpress'),
        'public'                    => true,
        'label_count'               => _n_noop('Canceled <span class="count">(%s)</span>', 'Canceled <span class="count">(%s)</span>', 'plugin-domain'),
        'post_type'                 => array('order'), // Define one or more post types the status can be applied to.
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'show_in_metabox_dropdown'  => true,
        'show_in_inline_dropdown'   => true,
        'dashicon'                  => 'dashicons-businessman',
    ));

    register_post_status('refunded', array(
        'label'                     => _x('Refunded', 'post status label', 'foodpress'),
        'public'                    => true,
        'label_count'               => _n_noop('Refunded <span class="count">(%s)</span>', 'Refunded <span class="count">(%s)</span>', 'plugin-domain'),
        'post_type'                 => array('order'), // Define one or more post types the status can be applied to.
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'show_in_metabox_dropdown'  => true,
        'show_in_inline_dropdown'   => true,
        'dashicon'                  => 'dashicons-businessman',
    ));
}

add_action('admin_footer', 'foodpress_order_admin_footer');
/**
 * admin order footer script
 * @return [type] [description]
 */
function foodpress_order_admin_footer()
{
    $current_screen = get_current_screen();
    if ($current_screen->parent_file == 'edit.php?post_type=order') :

        $ajax_url = admin_url('admin-ajax.php');
        $nonce = wp_create_nonce('foodpress');
        $status = isset($_GET['post_status']) ? sanitize_text_field($_GET['post_status']) : 'all ';
?>
<script>
jQuery('.order_name .row-actions').hide();
jQuery(jQuery(".wrap h1")[0]).append("<a  id='export-xl' class='add-new-h2'>Export To Excel</a>");
jQuery('#export-xl').on('click', function() {
    jQuery(this).html('Proses..');

    jQuery.ajax({
        type: "POST",
        url: '<?php echo $ajax_url; ?>',
        dataType: "json",
        data: {
            action: 'export_orders',
            nonce: '<?php echo $nonce; ?>',
            status: '<?php echo $status; ?>',
        },
        success: function(data) {
            jQuery('#export-xl').html('Export To Excel');
            console.log(data);
            window.open(data.url, '_blank');
        }
    });
})
</script>
<?php
    endif;
}

add_action('wp_ajax_export_orders', 'foodpress_ajax_export_orders');
/**
 * ajax export orders
 * @return [type] [description]
 */
function foodpress_ajax_export_orders()
{
    $nonce = isset($_REQUEST['nonce']) ? $_REQUEST['nonce'] : '';
    if (!wp_verify_nonce($nonce, 'foodpress')) exit;

    $args = array(
        'post_type' => 'order',
        'posts_per_page' => -1,
        'fields' => 'ids',
    );

    if ($_POST['status'] !== 'all') :
        $args['post_status'] = 'new_order'; //sanitize_text_field($_POST['status']);
    endif;

    $posts = new WP_Query($args);

    $list = array();

    $header = array(
        'Title',
        'Date',
        'Customer',
        'Phone',
        'Address',
        'Product',
        'Sub Total',
        'Ongkir',
        'Total',
    );

    foreach ((array)$posts->posts as $id) :

        $order_items = get_post_meta($id, 'order_items', true);

        $product = '';
        foreach ((array)$order_items as $item) {
            $itm = $item['item_name'] . ' (' . $item['qty'] . ') @' . number_format($item['item_price'], 0, '.', '.');
            $product .= $itm . ', ';
        }


        $list[] = array(
            get_the_title($id),
            get_the_date('Y-m-d H:i:s', $id),
            get_post_meta($id, 'customer_name', true),
            get_post_meta($id, 'customer_phone', true),
            get_post_meta($id, 'customer_address', true),
            $product,
            get_post_meta($id, 'order_subtotal', true),
            get_post_meta($id, 'order_ongkir', true),
            get_post_meta($id, 'order_total', true),
        );
    endforeach;

    $upload_dir   = wp_upload_dir();
    $filename = date('y-m-d-H-i-s') . '-order.xlsx';

    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $i = 'A';
    foreach ($header as $key => $val) {
        $sheet->setCellValue($i . '1', $val);
        $i++;
    }

    $i = 2;
    foreach ((array)$list as $key => $val) :
        $ii = 'A';
        foreach ((array) $val as $k => $v) {
            $sheet->setCellValue($ii . $i, $v);
            $ii++;
        }
        $i++;
    endforeach;

    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $writer->save($upload_dir['basedir'] . '/' . $filename);

    $spreadsheet->disconnectWorksheets();
    unset($spreadsheet);

    $response['url'] = site_url() . '/wp-content/uploads/' . $filename;
    echo json_encode($response);
    exit;
}


add_action('wp_ajax_order_create', 'foodpress_order_create');
add_action('wp_ajax_nopriv_order_create', 'foodpress_order_create');
/**
 * foodpress ajax create order
 * @return [type] [description]
 */
function foodpress_order_create()
{

    $input = file_get_contents("php://input");
    $respons = array(
        'status' => 'error',
        'message' => 'Lengkapi formulir pembelian',
        'data' => array()
    );

    /*
    no input ? return.
     */
    if (empty($input)) exit;
    $data = json_decode($input, true);

    /*
    Check nonce.
     */
    if (isset($data['nonce'])  && wp_verify_nonce($data['nonce'], 'foodpress')) :

        if (!isset($data['customer']) || !is_array($data['customer'])) {
            $respons['message'] = 'Data customer tidak valid';
            echo json_encode($respons);
            exit;
        }

        if (!isset($data['detail']) || !is_array($data['detail'])) {
            $respons['message'] = 'Data detail order tidak valid';
            echo json_encode($respons);
            exit;
        }

        $customer = $data['customer'];
        $detail = $data['detail'];
        $items = $data['items'];

        $invoice_last_number = get_option('invoice_number', 0);
        $invoice_number = intval($invoice_last_number) + 1;

        $post_data = array(
            'post_title'   => 'Order ' . $invoice_number,
            'post_name'    => 'order-' . $invoice_number,
            'post_content' => '',
            'post_status'  => 'new_order',
            'post_author'  => 1,
            'post_type'    => 'order',
        );
        $post_id = wp_insert_post($post_data);
        if (is_wp_error($post_id)) {
            echo json_encode([
                'status' => 'error',
                'message' => $post_id->get_error_message()
            ]);
        } else {
            update_option('invoice_number', $invoice_number);

            foreach ((array) $customer as $key => $val) {
                update_post_meta($post_id, 'customer_' . $key, sanitize_text_field($val));
            }

            foreach ((array) $detail as $key => $val) {
                update_post_meta($post_id, $key, sanitize_text_field($val));
            }

            update_post_meta($post_id, 'order_items', $items);
            update_post_meta($post_id, 'status', 'new_order');
            update_post_meta($post_id, 'version', 2);


            $respons['status'] = 'success';
            $respons['message'] = 'Order created';
            $respons['data'] = array(
                'items' => $items,
                'customer' => $customer,
                'detail' => $detail,
            );
            echo json_encode($respons);
        }
        exit;
    endif;
}

add_filter('manage_order_posts_columns', 'foodpress_order_column');
add_action('manage_order_posts_custom_column', 'foodpress_order_content_column', 10, 2);
/**
 * order set custom column
 * @param  [type] $columns [description]
 * @return [type]          [description]
 */
function foodpress_order_column($columns)
{

    $new_columns['cb'] = '<input type="checkbox"/>';
    $new_columns['order_name'] = __('Title', 'foodpress');
    $new_columns['order_date'] = __('Date', 'foodpress');
    $new_columns['order_customer'] = __('Customer', 'foodpress');
    //$new_columns['order_product'] = __('Product', 'foodpress');
    $new_columns['order_status'] = __('Status', 'foodpress');
    $new_columns['order_followup'] = __('Follow Up', 'foodpress');
    $new_columns['order_action'] = __('&nbsp;', 'foodpress');

    return $new_columns;
}

/**
 * order manage custom column
 * @param  [type] $column  [description]
 * @param  [type] $post_id [description]
 * @return [type]          [description]
 */
function foodpress_order_content_column($column, $post_id)
{

    $phone = get_post_meta($post_id, 'customer_phone', true);

    $phone = preg_replace('/[^0-9]/', '', $phone);
    $phone = preg_replace('/^620/', '62', $phone);
    $phone = preg_replace('/^0/', '62', $phone);

    $product_id = get_post_meta($post_id, 'order_product_id', true);

    switch ($column):

        case 'order_name':
            echo '<span style="font-weight: bold">' . get_the_title($post_id) . '</span>';
            break;

        case 'order_date':
            echo get_the_date('Y-m-d H:i:s', $post_id);
            break;

        case 'order_customer':
            echo '<span>' . get_post_meta($post_id, 'customer_name', true) . '</span><br/>';
            echo '<span>( ' . $phone . ' )</span><br/>';
            break;

        case 'order_status':
            $statuse = get_post_status($post_id);
            $statuses = array(
                'new_order'   => 'New Order',
                'on_hold'     => 'On Hold',
                'on_shipping' => 'On Shipping',
                'completed'   => 'Completed',
                'refunded'    => 'Refunded',
                'canceled'   => 'Canceled',
            );

            $statuse = isset($statuses[$statuse]) ? $statuse : 'new_order';
            $status = isset($statuses[$statuse]) ? $statuses[$statuse] : 'New Order';
            echo '<div class="order-status-' . $statuse . '">' . $status . '</div>';
            break;

        case 'order_followup':
            echo '<button type="button" class="button button-primary" onclick="customerFollowUp(\'' . $phone . '\');">Follow Up</button>';
            break;

        case 'order_action':
            echo '<div style="text-align:right">';
            echo '<a href="' . get_edit_post_link($post_id) . '" class="button">View Order</a>&nbsp';
            //echo '<a href="'.get_delete_post_link( $post_id ).'" class="button">Delete</a>';
            echo '</div>';
            break;

    endswitch;
}