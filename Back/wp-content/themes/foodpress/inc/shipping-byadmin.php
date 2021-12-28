<?php

add_action('foodpress_feature_option_page_ongkir_byadmin', 'foodpress_ongkir_options_page_byadmin');
/**
 * byadmin options page
 * @return [type] [description]
 */
function foodpress_ongkir_options_page_byadmin()
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
                name="foodpress_byadmin_info"><?php echo get_option('foodpress_byadmin_info', 'Ongkos kirim akan di infokan oleh admin melalui whatsapp.'); ?></textarea>
        </td>
    </tr>
</table>
<?php
    $content = ob_get_contents();
    ob_end_clean();

    echo $content;
}