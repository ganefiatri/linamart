<?php

add_action('foodpress_feature_option_page_ongkir_custom', 'foodpress_ongkir_options_page_custom');
/**
 * customongkir options page
 * @return [type] [description]
 */
function foodpress_ongkir_options_page_custom()
{
    $lists = get_option('foodpress_customongkir_lists');

    ob_start();
?>
<div class="foodpress-customongkir">
    <table>
        <tr>
            <th scope="row">
                <label><?php _e('Custom Shipping Label', 'foodpress'); ?></label>
            </th>
            <td>
                <input type="text" class="regular-text" name="foodpress_customshipping_label"
                    value="<?php echo get_option('foodpress_customshipping_label', 'Lokasi pengiriman ( Di perlukan untuk hitung ongkir) :'); ?>">
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label><?php _e('Custom Shipping Info', 'foodpress'); ?></label>
            </th>
            <td>
                <div style="font-style: italic; font-weight: 300;">
                    [location] => Shipping location<br />[cost] => Shipping Cost
                </div>
                <textarea class="regular-text"
                    name="foodpress_customshipping_info"><?php echo get_option('foodpress_customshipping_info', 'Pesanan Anda ke [location] di kenakan tarif sebesar [cost]'); ?></textarea>
            </td>
        </tr>

        <tr>
            <th scope="row">
                <label><?php _e('Custom Shipping Name', 'foodpress'); ?></label>
            </th>
            <td>
                <input class="regular-text" name="foodpress_customshipping_name"
                    value="<?php echo get_option('foodpress_customshipping_name', 'Kurir Toko'); ?>" />
            </td>
        </tr>

        <tr>
            <th scope="row">
                <label><?php _e('Custom Shipping Options', 'foodpress'); ?></label>
            </th>
            <td>
                <table class="foodpress-input-box">
                    <tbody class="append">
                        <?php foreach ((array)$lists as $key => $val) : ?>
                        <?php if (empty($val)) continue; ?>
                        <tr class="foodpress-shortcoe-field wpapgfield">
                            <td><input type="text" name="foodpress_customongkir_lists[<?php echo $key; ?>][location]"
                                    value="<?php echo $val['location']; ?>"></td>
                            <td><input type="text" name="foodpress_customongkir_lists[<?php echo $key; ?>][cost]"
                                    value="<?php echo $val['cost']; ?>"></td>
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

                <button class="button button-primary" type="submit" name="submit">Save Options</button>

                <script type="text/javascript">
                jQuery(document).ready(function($) {

                    jQuery(".add-more").click(function() {
                        let field_length = jQuery('.foodpress-input-box').find('tr.wpapgfield').length,
                            field_number = field_length + 1;

                        let html = '<tr class="foodpress-shortcoe-field wpapgfield">';
                        html += '<td><input type="text" name="foodpress_customongkir_lists[' +
                            field_number +
                            '][location]" placeholder="Location"></td>';
                        html += '<td><input type="text" name="foodpress_customongkir_lists[' +
                            field_number +
                            '][cost]" placeholder="Cost"></td>';
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
            </td>
        </tr>
    </table>
</div>
<?php
    $content = ob_get_contents();
    ob_end_clean();

    echo $content;
}

add_action('foodpress_cart_customer_detail', 'foodpress_custom_frontend_customer_district');
function foodpress_custom_frontend_customer_district()
{

    if (get_option('foodpress_feature_ongkir_enable') !== 'yes') return;

    if (get_option('foodpress_feature_ongkir_provider') !== 'custom') return;

    $lists = get_option('foodpress_customongkir_lists');

    ob_start();
?>
<div class="customer-field">
    <label><?php echo get_option('foodpress_customshipping_label', 'Lokasi pengiriman ( Di perlukan untuk hitung ongkir) :'); ?></label>
    <select class="choose-shipping-location"
        style="padding: 7px 10px;font-size: 14px;color: #999;border: 1px solid #ccc;border-radius: 5px;">
        <?php
            foreach ((array)$lists as $key => $val) {
                echo '<option data-cost="' . $val['cost'] . '" data-location="' . $val['location'] . '">' . $val['location'] . '</option>';
            }
            ?>
    </select>
</div>
<?php
    $content = ob_get_contents();
    ob_end_clean();

    echo $content;
}