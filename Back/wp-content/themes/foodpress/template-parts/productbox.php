<?php

/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package FoodPress
 */
$thumb = get_the_post_thumbnail_url(get_the_ID());
$regular_price = intval(get_post_meta(get_the_ID(), 'product_price', true));
$weight = intval(get_post_meta(get_the_ID(), 'product_weight', true));
if (empty($weight)) {
    $weight = 1000;
}
$promo = get_post_meta(get_the_ID(), 'product_promo', true);
$promo_price = intval(get_post_meta(get_the_ID(), 'product_promo_price', true));
$is_out_of_stock = get_post_meta(get_the_ID(), 'product_is_out_of_stock', true);
if ($promo == 'on') {
    $prices = sprintf(
        '<span class="price">Rp %s</span> <span class="price-slik">Rp %s</span>',
        number_format($promo_price, 0, ',', '.'),
        number_format($regular_price, 0, ',', '.')
    );
} else {
    $prices = sprintf(
        '<span class="price">Rp %s</span>',
        number_format($regular_price, 0, ',', '.')
    );
}

$price = ($promo == 'on') ? $promo_price : $regular_price;
?>

<article id="product-<?php the_ID(); ?>" class="productbox">

    <div class="content">
        <div class="image productbox-image lazy" data-bg="url(<?php echo $thumb; ?>)">
            <?php if ($promo == 'on') : ?>
            <span class="ribbon">Promo</span>
            <?php endif; ?>
        </div>

        <div class="detail">
            <?php
            the_title('<h3><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h3>');
            ?>
            <div class="desc">
                <?php the_content(); ?>
            </div>
            <div class="pricing">
                <?php
                echo $prices;
                ?>
            </div>
            <div class="atc atc-item-<?php echo get_the_ID(); ?> clear">
                <input type="hidden" name="store_id" value="<?php echo foodpress_get_product_store_id(); ?>" />
                <input type="hidden" name="item_id" value="<?php echo get_the_ID(); ?>" />
                <input type="hidden" name="item_image" value="<?php echo $thumb; ?>" />
                <input type="hidden" name="item_name" value="<?php echo get_the_title(); ?>" />
                <input type="hidden" name="item_price" value="<?php echo $price; ?>" />
                <input type="hidden" name="item_weight" value="<?php echo $weight; ?>" />
                <input type="hidden" name="item_price_slik" value="<?php echo $regular_price; ?>" />
                <input type="hidden" name="note" value="" />
                <?php
                if (foodpress_current_is_open()) {
                    if ($is_out_of_stock == 'on') :
                        echo '<button class="is-out-stock" disabled="disabled">Habis</button>';
                    else :
                        echo '<button class="add button-add">Tambah</button>';
                    endif;
                } else {
                    echo '<p>' . foodpress_closed_message() . '</p>';
                    //echo '<button class="is-out-stock" disabled="disabled">Tutup</button>';
                }
                ?>
                <div class="qty qty-selector clear">
                    <button type="button" class="minus button-qty" data-qty-action="minus">-</button>
                    <input min="0" type="number" value="0" name="qty">
                    <button type="button" class="plus button-qty" data-qty-action="plus">+</button>
                </div>
            </div>
        </div>
    </div>
</article>