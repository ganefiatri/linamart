<?php

add_action('foodpress_feature_option_page_ongkir_rajaongkir', 'foodpress_ongkir_options_page_rajaongkir');
/**
 * rajaongkir options page
 * @return [type] [description]
 */
function foodpress_ongkir_options_page_rajaongkir()
{

    $url = admin_url('admin-ajax.php') . '?action=get_sub_district&nonce=' . wp_create_nonce('foodpress');

    ob_start();
?>
<table>
    <tr>
        <th scope="row">
            <label><?php _e('Api Key', 'foodpress'); ?></label>
        </th>
        <td>
            <input name="foodpress_rajaongkir_api_key" type="text"
                value="<?php echo get_option('foodpress_rajaongkir_api_key'); ?>" class="regular-text" />
            <div class="foodpress-options-desc"><a href="https://rajaongkir.com/akun/panel" target="_blank">Click
                    Here</a> to get rajaongkir api key</div>
        </td>
    </tr>
    <tr>
        <th scope="row">
            <label><?php _e('Origin', 'foodpress'); ?></label>
        </th>
        <td>
            <?php
                $origin_id = get_option('foodpress_rajaongkir_origin_id');
                $origin_name = get_option('foodpress_rajaongkir_origin_name');
                ?>
            <input type="hidden" name="foodpress_rajaongkir_origin_id" value="<?php echo $origin_id; ?>" />
            <input type="hidden" name="foodpress_rajaongkir_origin_name" value="<?php echo $origin_name; ?>" />
            <select class="regular-text get_sub_district" style="height:30px;">
                <?php if ($origin_id & $origin_name) : ?>
                <option selected="selected" value="<?php echo $origin_id; ?>"><?php echo $origin_name; ?></option>
                <?php else : ?>
                <option hidden="hidden" selected="selected" value="">Choose Origin District</option>
                <?php endif; ?>

            </select>
            <script>
            new SlimSelect({
                select: '.get_sub_district',
                placeholder: 'Kecamatan',
                searchingText: 'Searching . . .',
                ajax: function(search, callback) {

                    if (search.length < 1) {
                        callback('Ktik minimal 3 huruf');
                        return;
                    }

                    fetch('<?php echo $url; ?>&s=' + search)
                        .then((respons) => respons.json())
                        .then(function(json) {

                            let data = [];
                            for (let i = 0; i < json.length; i++) {
                                let city = json[i].type + ' ' + json[i].city;
                                if (json[i].type == 'Kabupaten') {
                                    city = 'Kab. ' + json[i].city;
                                }
                                let label = json[i].subdistrict_name + ', ' + city + ', ' + json[i]
                                    .province;
                                let value = json[i].subdistrict_id + '-' + json[i].city_id;
                                data.push({
                                    text: label,
                                    value: value
                                });
                            }

                            setTimeout(() => {
                                callback(data);
                            }, 100);
                        })
                        .catch(function(error) {
                            callback(error);
                        });
                },
                onChange: (info) => {
                    document.querySelector('[name="foodpress_rajaongkir_origin_id"]').value = info.value;
                    document.querySelector('[name="foodpress_rajaongkir_origin_name"]').value = info.text;
                }
            });
            </script>
        </td>
    </tr>
    <tr>
        <th scope="row">
            <label><?php _e('Type Akun', 'foodpress'); ?></label>
        </th>
        <td>
            <select name="foodpress_rajaongkir_account_type" class="regular-text rajaongkirtype">
                <option value="starter" <?php if (get_option('foodpress_rajaongkir_account_type') == 'starter') {
                                                echo 'selected';
                                            } ?>>Starter</option>
                <option value="basic" <?php if (get_option('foodpress_rajaongkir_account_type') == 'basic') {
                                                echo 'selected';
                                            } ?>>Basic</option>
                <option value="pro" <?php if (get_option('foodpress_rajaongkir_account_type') == 'pro') {
                                            echo 'selected';
                                        } ?>>Pro</option>
            </select>
        </td>
    </tr>
    <tr>
        <th scope="row">
            <label><?php _e('Kurir', 'foodpress'); ?></label>
        </th>
        <td style="position: relative;" class="foodpress-cleafix">
            <?php
                $rajaongkir_account_type = get_option('foodpress_rajaongkir_account_type');
                $rajaongkir_account_type = empty($rajaongkir_account_type) ? 'starter' : $rajaongkir_account_type;

                $kurir = get_option('foodpress_rajaongkir_kurir') ? get_option('foodpress_rajaongkir_kurir') : array();

                ?>
            <label class="kurir">
                <input name="foodpress_rajaongkir_kurir[]" type="checkbox" value="pos"
                    <?php echo (in_array('pos', $kurir)) ? 'checked="chekced"' : ''; ?> />
                <?php echo __('Pos') ?>
            </label>
            <label class="kurir">
                <input name="foodpress_rajaongkir_kurir[]" type="checkbox" value="jne"
                    <?php echo (in_array('jne', $kurir)) ? 'checked="chekced"' : ''; ?> />
                <?php echo __('JNE') ?>
            </label>
            <label class="kurir">
                <input name="foodpress_rajaongkir_kurir[]" type="checkbox" value="tiki"
                    <?php echo (in_array('tiki', $kurir)) ? 'checked="chekced"' : ''; ?> />
                <?php echo __('Tiki') ?>
            </label>

            <br />
            <br />
            <?php
                $basic = array(
                    array(
                        'id' => 'pcp',
                        'name' => 'Priority Cargo and Package',
                    ),
                    array(
                        'id' => 'esl',
                        'name' => 'Eka Sari Lorena',
                    ),
                    array(
                        'id' => 'rpx',
                        'name' => 'RPX Holding',
                    )
                )
                ?>

            <?php foreach ((array)$basic as $courier) : ?>
            <label class="kurir">
                <input class="basic" name="foodpress_rajaongkir_kurir[]" type="checkbox"
                    value="<?php echo $courier['id']; ?>"
                    <?php echo (in_array($courier['id'], $kurir)) ? 'checked="chekced"' : ''; ?>
                    <?php if ($rajaongkir_account_type == 'starter') {
                                                                                                                                                                                                                        echo 'disabled="disabled"';
                                                                                                                                                                                                                    } ?> />
                <?php echo $courier['name']; ?>
            </label>
            <?php endforeach; ?>


            <br />
            <br />
            <?php
                $pro = array(
                    array(
                        'id' => 'pandu',
                        'name' => 'Pandu Logistics',
                    ),
                    array(
                        'id' => 'wahana',
                        'name' => 'Wahana Prestasi Logistik'
                    ),
                    array(
                        'id' => 'sicepat',
                        'name' => 'SiCepat Express',
                    ),
                    array(
                        'id' => 'jnt',
                        'name' => 'JnT Express',
                    ),
                    array(
                        'id' => 'pahala',
                        'name' => 'PAHALA KENCANA',
                    ),
                    array(
                        'id' => 'sap',
                        'name' => 'SAP Express',
                    ),
                    array(
                        'id' => 'jet',
                        'name' => 'JET Express',
                    ),
                    array(
                        'id' => 'slis',
                        'name' => 'Solusi Express',
                    ),
                    array(
                        'id' => 'dse',
                        'name' => '21 Express'
                    ),
                    array(
                        'id' => 'first',
                        'name' => 'First Express',
                    ),
                    array(
                        'id' => 'ncs',
                        'name' => 'Nusantara Card Semesta',
                    ),
                    array(
                        'id' => 'star',
                        'name' => 'Star Cargo',
                    ),
                    array(
                        'id' => 'lion',
                        'name' => 'Lion Parcel',
                    ),
                    array(
                        'id' => 'ninja',
                        'name' => 'Ninja Express',
                    ),
                    array(
                        'id' => 'idl',
                        'name' => 'IDL Cargo',
                    ),
                    array(
                        'id' => 'rex',
                        'name' => 'Royal Express Indonesia'
                    )
                );
                ?>
            <?php foreach ((array)$pro as $courier) : ?>
            <label class="kurir">
                <input class="pro" name="foodpress_rajaongkir_kurir[]" type="checkbox"
                    value="<?php echo $courier['id']; ?>"
                    <?php echo (in_array($courier['id'], $kurir)) ? 'checked="chekced"' : ''; ?>
                    <?php if ($rajaongkir_account_type !== 'pro') {
                                                                                                                                                                                                                        echo 'disabled="disabled"';
                                                                                                                                                                                                                    } ?> />
                <?php echo $courier['name']; ?>
            </label>
            <?php endforeach; ?>
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

add_action('foodpress_footer_product', 'foodpress_rajaongkir_frontend_ongkir_template');
function foodpress_rajaongkir_frontend_ongkir_template()
{

    if (get_option('foodpress_feature_ongkir_enable') !== 'yes') return;

    if (get_option('foodpress_feature_ongkir_provider') !== 'rajaongkir') return;

    ob_start();
?>
<script type="text/template" id="shipping-rajaongkir-service-template">
    <div class="rajaongkir-list-box clear" data-name="{{data-name}}" data-etd="{{data-etd}}" data-cost="{{data-cost}}">
        <div class="shipping-name">{{name}}</div>
        <div class="shipping-etd">{{etd}}</div>
        <div class="shipping-cost">{{cost}}</div>
    </div>
</script>
<?php
    $content = ob_get_contents();
    ob_end_clean();

    echo $content;
}

add_action('foodpress_cart_customer_detail', 'foodpress_rajaongkir_frontend_customer_district');
function foodpress_rajaongkir_frontend_customer_district()
{

    if (get_option('foodpress_feature_ongkir_enable') !== 'yes') return;

    if (get_option('foodpress_feature_ongkir_provider') !== 'rajaongkir') return;

    ob_start();
?>
<div class="customer-field">
    <label>Kecamatan ( Di perlukan untuk hitung ongkir) :</label>
    <input id="autoComplete" type="text" tabindex="1" placeholder="Cari Kecamatan">
</div>
<?php
    $content = ob_get_contents();
    ob_end_clean();

    echo $content;
}