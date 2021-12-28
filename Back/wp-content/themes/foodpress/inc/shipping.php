<?php

add_filter('foodpress_feature_tab_section_ongkir', 'foodpress_ongkir_tab_section', 10, 2);
/**
 * ongkir feature sub tab
 * @param  array $sections  default section "general"
 * @return array            section list
 */
function foodpress_ongkir_tab_section($sections)
{

    $sections['flatongkir'] = 'Flat Ongkir';
    $sections['rajaongkir'] = 'Raja Ongkir';
    $sections['custom'] = 'Custom Ongkir';
    $sections['byadmin'] = 'By Admin';


    return $sections;
}

add_action('foodpress_feature_option_page_ongkir_general', 'foodpress_ongkir_options_page_general');
function foodpress_ongkir_options_page_general()
{

    $ongkir_provider = array(
        'flatongkir' => 'Flat Ongkir',
        'rajaongkir' => 'Raja Ongkir API',
        'custom' => 'Custom Ongkir',
        'byadmin' => 'By Admin',
    );

    $ongkir_provider = apply_filters('foodpress_feature_ongkir_provider', $ongkir_provider);
    ob_start();
?>
<table>
    <tr>
        <th scope="row">
            <label><?php _e('Enable Shipping', 'foodpress'); ?></label>
        </th>
        <td>
            <input name="foodpress_feature_ongkir_enable" type="hidden" value="no" />
            <input name="foodpress_feature_ongkir_enable" type="checkbox" value="yes"
                <?php echo ('yes' == get_option('foodpress_feature_ongkir_enable')) ? 'checked="chekced"' : ''; ?> />
            <?php echo __('Checked this if enable') ?>
        </td>
    </tr>
    <tr>
        <th scope="row">
            <label><?php _e('Provider', 'foodpress'); ?></label>
        </th>
        <td>
            <select name="foodpress_feature_ongkir_provider" class="regular-text">

                <?php foreach ((array) $ongkir_provider as $key => $val) : ?>
                <option value="<?php echo $key; ?>" <?php if (get_option('foodpress_feature_ongkir_provider') == $key) {
                                                                echo 'selected';
                                                            } ?>><?php echo $val; ?></option>
                <?php endforeach; ?>
            </select>
        </td>
    </tr>
    <tr>
        <th scope="row">
            <label><?php _e('Text Label', 'foodpress'); ?></label>
        </th>
        <td>
            <input name="foodpress_ongkir_text" type="text"
                value="<?php echo get_option('foodpress_ongkir_text', 'Ongkos Kirim'); ?>" class="regular-text"
                placeholder="Ongkir Kirim" />
        </td>
    </tr>
    <tr>
        <th scope="row">
            <label><?php _e('Enable Delivery Hour', 'foodpress'); ?></label>
        </th>
        <td>
            <input name="foodpress_feature_shipping_devlivery_hour_enable" type="hidden" value="no" />
            <input name="foodpress_feature_shipping_devlivery_hour_enable" type="checkbox" value="yes"
                <?php echo ('yes' == get_option('foodpress_feature_shipping_devlivery_hour_enable')) ? 'checked="chekced"' : ''; ?> />
            <?php echo __('Checked this if enable') ?>
        </td>
    </tr>
    <tr>
        <th scope="row">
            <label><?php _e('Delivery Hour Options', 'foodpress'); ?></label>
        </th>
        <td>
            <table class="foodpress-input-box">
                <tbody class="append">
                    <?php
                        $lists = get_option('foodpress_shipping_devlivery_hour_lists');
                        ?>
                    <?php foreach ((array)$lists as $key => $val) : ?>
                    <?php if (empty($val)) continue; ?>
                    <tr class="foodpress-shortcoe-field wpapgfield">
                        <td><input type="text" name="foodpress_shipping_devlivery_hour_lists[<?php echo $key; ?>]"
                                value="<?php echo $val; ?>"></td>
                        <td>
                            <div style="text-align:center;height: 30px;line-height: 30px;">
                                <span class="dashicons dashicons-trash foodpress-shortcoe-field-remove"
                                    style="cursor:pointer;margin-top: 4px;"></span>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td>
                            <button class="button add-more" type="button">Add Options</button>
                        <td>
                        </td>
                        <td></td>
                    <tr>
                </tfoot>
            </table>
        </td>
    </tr>
    <tr>
        <th scope="row">
        </th>
        <td>
            <button class="button button-primary" type="submit"
                name="submit"><?php echo __('Save Settings', 'foodpress'); ?></button>
        </td>
    </tr>
</table>
<script type="text/javascript">
jQuery(document).ready(function($) {

    jQuery(".add-more").click(function() {
        let field_length = jQuery('.foodpress-input-box').find('tr.wpapgfield').length,
            field_number = field_length + 1;

        let html = '<tr class="foodpress-shortcoe-field wpapgfield">';
        html += '<td><input type="text" name="foodpress_shipping_devlivery_hour_lists[' + field_number +
            ']" placeholder="Option"></td>';
        html += '<td>';
        html += '<div style="text-align:center;height: 30px;line-height: 30px;">';
        html +=
            '<span class="dashicons dashicons-trash foodpress-shortcoe-field-remove" style="cursor:pointer;margin-top: 4px;"></span>';
        html += '</div></td>';
        html += '</tr>';
        jQuery('tbody.append').append(html);
    });

    jQuery("body").on("click", ".foodpress-shortcoe-field-remove", function() {
        jQuery(this).parents(".foodpress-shortcoe-field").remove();
    });

});

function wpapgCopy(ini) {
    let input = jQuery(ini).parent().find('input');
    input.select();
    document.execCommand('copy');
}
</script>
<?php
    $content = ob_get_contents();
    ob_end_clean();

    echo $content;
}

add_action('wp_ajax_get_sub_district', 'foodpress_ajax_get_sub_district');
add_action('wp_ajax_nopriv_get_sub_district', 'foodpress_ajax_get_sub_district');
function foodpress_ajax_get_sub_district()
{
    global $wp_filesystem;

    $nonce = isset($_REQUEST['nonce']) ? $_REQUEST['nonce'] : '';
    if (!wp_verify_nonce($nonce, 'foodpress')) exit;

    $search = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';

    $file_url  = FOODPRESS_URL . '/data/subdistrict.json';
    $file_path = FOODPRESS_PATH . '/data/subdistrict.json';

    try {
        require_once ABSPATH . 'wp-admin/includes/file.php';

        if (is_null($wp_filesystem)) {
            WP_Filesystem();
        }

        if (!$wp_filesystem instanceof WP_Filesystem_Base || (is_wp_error($wp_filesystem->errors) && $wp_filesystem->errors->get_error_code())) {
            throw new Exception('WordPress Filesystem Abstraction classes is not available', 1);
        }

        if (!$wp_filesystem->exists($file_path)) {
            throw new Exception('JSON file is not exists or unreadable', 1);
        }

        $json = $wp_filesystem->get_contents($file_path);
    } catch (Exception $e) {
        // Get JSON data by HTTP if the WP_Filesystem API procedure failed.
        $json = wp_remote_retrieve_body(wp_remote_get(esc_url_raw($file_url)));
    }

    if (!$json) {
        return false;
    }

    $data = json_decode($json, true);

    if ('No error' !== json_last_error_msg() || !$data) {
        return false;
    }

    if ($search) :
        $subdistricts = array();
        foreach ($data as $row) :
            if (stripos($row['subdistrict_name'], $search) !== false || stripos($row['city'], $search) !== false) :
                $subdistricts[] = $row;
            endif;
        endforeach;

        echo json_encode($subdistricts);
        exit;

    endif;

    echo json_encode($data);
    exit;
}


add_action('wp_ajax_get_ongkir', 'foodpress_ajax_get_ongkir');
add_action('wp_ajax_nopriv_get_ongkir', 'foodpress_ajax_get_ongkir');
function foodpress_ajax_get_ongkir()
{

    $nonce = isset($_REQUEST['nonce']) ? $_REQUEST['nonce'] : '';
    if (!wp_verify_nonce($nonce, 'foodpress')) exit;

    $api_key      = get_option('foodpress_rajaongkir_api_key');
    $account_type = get_option('foodpress_rajaongkir_account_type');
    $couriers     = get_option('foodpress_rajaongkir_kurir');
    $origin_id    = explode('-', get_option('foodpress_rajaongkir_origin_id'));

    if ($account_type == 'pro') :
        $origin = isset($origin_id[0]) ? $origin_id[0] : '';
    else :
        $origin = isset($origin_id[1]) ? $origin_id[1] : '';
    endif;

    $destination_id = isset($_GET['destination']) ? sanitize_text_field($_GET['destination']) : '';
    $weight = isset($_GET['weight']) ? intval($_GET['weight']) : 1000;

    if (!$destination_id) exit;

    $destination_id = explode('-', $destination_id);

    if ($account_type == 'pro') :
        $destination = isset($destination_id[0]) ? $destination_id[0] : '';
    else :
        $destination = isset($destination_id[1]) ? $destination_id[1] : '';
    endif;


    $datas = array();

    foreach ($couriers as $key => $val) :

        $data = foodpress_get_rajaongkir_ongkir($api_key, $account_type, $origin, $destination, $weight, $val);

        if (is_array($data)) :
            $datas = array_merge((array)$data, $datas);
        endif;

    endforeach;

    if (!$datas) :
        echo 404;
        exit;
    endif;

    echo json_encode($datas);
    exit;
}

function foodpress_rajaongkir_api_url()
{

    $type = get_option('foodpress_rajaongkir_account_type');

    $url = 'https://api.rajaongkir.com/starter/cost';

    if ($type == 'pro') :
        $url = 'https://pro.rajaongkir.com/api/cost';
    elseif ($type == 'basic') :
        $url = 'https://api.rajaongkir.com/basic/cost';
    endif;

    return $url;
}

function foodpress_get_rajaongkir_ongkir($api_key, $account_type, $origin, $destination, $weight, $courier)
{

    if (empty($api_key)) return false;

    if (empty($origin) || empty($destination) || empty($weight) || empty($courier)) return false;

    $url = foodpress_rajaongkir_api_url();

    $args = array(
        'headers' => array(
            'Content-Type' => 'application/x-www-form-urlencoded',
            'key' => $api_key,
        ),
        'body' => array(
            'origin'      => $origin,
            'destination' => $destination,
            'weight'      => $weight,
            'courier'     => $courier,
        )
    );

    if ($account_type == 'pro') :
        $args['body']['originType'] = 'subdistrict';
        $args['body']['destinationType'] = 'subdistrict';
    endif;

    $res = wp_remote_post($url, $args);

    if (is_wp_error($res)) return false;

    $result = json_decode($res['body'], true);

    if (isset($result['rajaongkir']['status']['code']) && $result['rajaongkir']['status']['code'] == 200) :

        if (isset($result['rajaongkir']['results'][0]['costs'])) :

            $ongkirs = array();

            foreach ((array) $result['rajaongkir']['results'][0]['costs'] as $ongkir) :
                $courier = strtoupper($result['rajaongkir']['results'][0]['code']);
                $courier = str_replace('&', 'n', $courier);
                $ongkirs[] = array(
                    'courier' => $courier,
                    'service' => $ongkir['service'],
                    'value'   => $ongkir['cost'][0]['value'],
                    'etd'     => $ongkir['cost'][0]['etd'],
                );
            endforeach;

            return $ongkirs;

        endif;

    endif;

    return false;
}