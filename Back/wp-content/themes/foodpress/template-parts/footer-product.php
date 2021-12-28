<div id="basketbox" class="basketbox">
    <div class="wrapper">
        <div class="basket">
            <div class="basket-cart">
                <i class="lni lni-shopping-basket"></i>
                <div class="basket-cart-item-count">0</div>
            </div>
            <div class="basket-detail">
                <div class="basket-items-label">
                    Total Belanja
                </div>
                <div class="basket-items-total">
                </div>
            </div>
            <div class="basket-next">
                <button type="button">Lanjut</button>
            </div>
        </div>
    </div>
</div>
<div id="cart" class="cart">
    <div class="cart-inner">
        <div class="cart-head">
            <div class="wrapper-cart">
                <div class="back">
                    <i class="lni lni-arrow-left"></i>
                </div>
            </div>
        </div>
        <div class="cart-content">
            <div class="wrapper-cart">
                <div class="cart-content-items">
                    <div class="cart-content-items-head">
                        <div class="cart-content-items-head-title">
                            Order Item(s)
                        </div>
                        <div class="cart-content-items-head-add">
                            + Tambah
                        </div>
                    </div>
                    <div class="cart-content-items-list">
                    </div>
                </div>
                <div class="cart-content-form">
                    <div class="cart-content-form-head">
                        <div class="cart-content-form-head-title">
                            <?php echo get_theme_mod('cart_customer_form_title', 'Data Pemesan'); ?>
                        </div>
                    </div>
                    <div class="cart-content-form-list">
                        <div class="customer">
                            <div class="customer-field">
                                <label>Nama :</label>
                                <input type="text" name="name" value="" />
                            </div>
                            <div class="customer-field">
                                <label>Nomor Handphone :</label>
                                <input type="number" name="phone" value="" />
                            </div>
                            <div class="customer-field">
                                <label>Alamat Lengkap :</label>
                                <textarea name="address"></textarea>
                            </div>
                            <?php do_action('foodpress_cart_customer_detail'); ?>
                        </div>
                    </div>
                </div>
                <div class="cart-content-shipping">
                    <div class="cart-content-shipping-head">
                        <div class="cart-content-shipping-head-title">
                            Pengiriman
                        </div>
                    </div>
                    <div class="cart-content-shipping-list">
                        <?php
                        $shipping_enable = get_option('foodpress_feature_ongkir_enable', 'yes');
                        $shipping_provider = get_option('foodpress_feature_ongkir_provider', 'flatongkir');
                        ?>
                        <?php if ($shipping_enable == 'yes') : ?>
                        <div class="shipping-method">
                            <?php if ($shipping_provider == 'byadmin') {
                                    echo '<div class="shipping-method-flatongkir">';
                                    echo get_option('foodpress_byadmin_info', 'Ongkos kirim akan di infokan oleh admin melalui whatsapp.');
                                    echo '</div>';
                                } ?>
                            <?php if ($shipping_provider == 'flatongkir') {
                                    echo '<div class="shipping-method-flatongkir">';
                                    echo get_option('foodpress_flatongkir_info', 'Pesanan Anda akan di kirim dengan tarif Flat sebesar Rp. 25.000;');
                                    echo '</div>';
                                } ?>
                            <?php if ($shipping_provider == 'custom') {
                                    $customongkir_message = get_option('foodpress_customongkir_info', 'Pesanan Anda ke [location] di kenakan tarif sebesar [cost]');
                                    $customongkir_message = str_replace('[location]', '<span id="custom-ongkir-location"></span>', $customongkir_message);
                                    $customongkir_message = str_replace('[cost]', '<span id="custom-ongkir-cost"></span>', $customongkir_message);

                                    echo '<div class="shipping-method-customongkir">';
                                    echo $customongkir_message;
                                    echo '</div>';
                                } ?>
                            <?php if ($shipping_provider == 'rajaongkir') {
                                    echo '<div class="shipping-method-rajaongkir">';
                                    echo '<div class="rajaongkir clear">';
                                    echo '<div class="shipping-name shipping-show-name"></div>';
                                    echo '<div class="shipping-etd shipping-show-etd"></div>';
                                    echo '<div class="shipping-arrow"></div>';
                                    echo '<div class="shipping-cost shipping-show-cost"></div>';
                                    echo '<div class="rajaongkir-list">';
                                    echo '</div>';
                                    echo '</div>';
                                    echo '<div class="shipping-loader"><svg xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink" version="1.0" width="160px" height="20px"
                                viewBox="0 0 128 16" xml:space="preserve"><rect x="0" y="0" width="100%" height="100%" fill="#FFFFFF" />
                                <path fill="#949494" fill-opacity="0.42" d="M6.4,4.8A3.2,3.2,0,1,1,3.2,8,3.2,3.2,0,0,1,6.4,4.8Zm12.8,0A3.2,3.2,0,1,1,16,8,3.2,3.2,0,0,1,19.2,4.8ZM32,4.8A3.2,3.2,0,1,1,28.8,8,3.2,3.2,0,0,1,32,4.8Zm12.8,0A3.2,3.2,0,1,1,41.6,8,3.2,3.2,0,0,1,44.8,4.8Zm12.8,0A3.2,3.2,0,1,1,54.4,8,3.2,3.2,0,0,1,57.6,4.8Zm12.8,0A3.2,3.2,0,1,1,67.2,8,3.2,3.2,0,0,1,70.4,4.8Zm12.8,0A3.2,3.2,0,1,1,80,8,3.2,3.2,0,0,1,83.2,4.8ZM96,4.8A3.2,3.2,0,1,1,92.8,8,3.2,3.2,0,0,1,96,4.8Zm12.8,0A3.2,3.2,0,1,1,105.6,8,3.2,3.2,0,0,1,108.8,4.8Zm12.8,0A3.2,3.2,0,1,1,118.4,8,3.2,3.2,0,0,1,121.6,4.8Z" /><g><path fill="#000000" fill-opacity="1" d="M-42.7,3.84A4.16,4.16,0,0,1-38.54,8a4.16,4.16,0,0,1-4.16,4.16A4.16,4.16,0,0,1-46.86,8,4.16,4.16,0,0,1-42.7,3.84Zm12.8-.64A4.8,4.8,0,0,1-25.1,8a4.8,4.8,0,0,1-4.8,4.8A4.8,4.8,0,0,1-34.7,8,4.8,4.8,0,0,1-29.9,3.2Zm12.8-.64A5.44,5.44,0,0,1-11.66,8a5.44,5.44,0,0,1-5.44,5.44A5.44,5.44,0,0,1-22.54,8,5.44,5.44,0,0,1-17.1,2.56Z" /> <animateTransform attributeName="transform" type="translate" values="23 0;36 0;49 0;62 0;74.5 0;87.5 0;100 0;113 0;125.5 0;138.5 0;151.5 0;164.5 0;178 0" calcMode="discrete" dur="1170ms" repeatCount="indefinite" /> </g> </svg> <br /> Loading data pengiriman </div>';
                                    echo '</div>';
                                } ?>
                        </div>
                        <?php endif; ?>
                        <?php
                        $list = get_option('foodpress_shipping_devlivery_hour_lists');
                        if (get_option('foodpress_feature_shipping_devlivery_hour_enable', 'no') == 'yes' && count($list) > 0) {
                            echo '<div class="shipping-delivery-hours clear">';
                            foreach ((array)$list as $key => $val) {
                                echo '<div class="shipping-delivery-hour-option clear">';
                                echo '<div class="labele">' . $val . '</div>';
                                echo '<div class="radios"></div>';
                                echo '</div>';
                            }
                            echo '</div>';
                        } ?>
                    </div>
                </div>
                <div class="cart-content-coupon">
                    <div class="cart-content-coupon-head">
                        <div class="cart-content-coupon-head-title">
                            Voucher Diskon
                        </div>
                    </div>
                    <div class="cart-content-coupon-list">
                        <div class="coupon-box">
                            <input type="text">
                            <button>Gunakan kode voucher</button>
                        </div>
                        <div class="coupon-box-notice">

                        </div>
                    </div>
                </div>
                <div class="cart-content-note">
                    <div class="cart-content-note-head">
                        <div class="cart-content-note-head-title">
                            Catatan untuk penjual
                        </div>
                    </div>
                    <div class="cart-content-note-list">
                        <div class="note-box">
                            <textarea name="note"></textarea>
                        </div>
                    </div>
                </div>
                <div class="cart-content-detail">
                    <div class="cart-content-detail-head">
                        <div class="cart-content-detail-head-title">
                            Order Detail
                        </div>
                    </div>
                    <div class="cart-content-detail-list">
                    </div>
                </div>
                <div class="cart-content-submit">
                    <button type="button" id="submit">Pesan</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/html" id="template-productcart">
<div class="productcart">
    <div class="content">
        <div class="image">
            <img src="{item_image}">
        </div>

        <div class="detail">
            <h3>{item_name}</h3>

            <div class="pricing">{item_price}</div>
            <div class="atc clear">
                <div class="qty qty-selector clear" data-item-id="{item_id}">
                    <button type="button" class="minus cart-button-qty" data-qty-action="minus">-</button>
                    <input min="0" type="number" value="{item_qty}" name="qty">
                    <button type="button" class="plus cart-button-qty" data-qty-action="plus">+</button>
                </div>
            </div>
        </div>
    </div>
</div>
</script>
<template id="error-field-template">
    <div class="error-field">
    </div>
</template>
<?php do_action('foodpress_footer_product'); ?>