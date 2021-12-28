<?php

add_action('foodpress_feature_option_page_ongkir_flatongkir', 'foodpress_ongkir_options_page_flatongkir');
/**
 * flatongkir options page
 * @return [type] [description]
 */
function foodpress_ongkir_options_page_flatongkir()
{

    ob_start();
?>
<table>
    <tr>
        <th scope="row">
            <label><?php _e('Flat Shipping Info', 'foodpress'); ?></label>
        </th>
        <td>
            <textarea class="regular-text"
                name="foodpress_flatongkir_info"><?php echo get_option('foodpress_flatongkir_info', 'Pesanan Anda akan di kirim dengan tarif ongkir Flat sebesar Rp. 25.000;'); ?></textarea>
        </td>
    </tr>
    <tr>
        <th scope="row">
            <label><?php _e('Flat Shipping Name', 'foodpress'); ?></label>
        </th>
        <td>
            <input class="regular-text" name="foodpress_flatongkir_name"
                value="<?php echo get_option('foodpress_flatongkir_name', 'Kurir Toko'); ?>" />
        </td>
    </tr>
    <tr>
        <th scope="row">
            <label><?php _e('Flat Shipping Cost', 'foodpress'); ?></label>
        </th>
        <td>
            <input name="foodpress_flatongkir_cost" type="text"
                value="<?php echo get_option('foodpress_flatongkir_cost', '25000'); ?>" class="regular-text"
                placeholder="25000" />
        </td>
    </tr>
    <tr>
        <th scope="row">
        </th>
        <td>
            <button class="button button-primary" type="submit"
                name="submit"><?php echo __('Save Options', 'foodpress'); ?></button>
        </td>
    </tr>
</table>
<?php
    $content = ob_get_contents();
    ob_end_clean();

    echo $content;
}