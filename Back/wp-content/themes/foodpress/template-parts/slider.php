<?php
$sliders = get_option('foodpress_sliders');
?>
<?php if ($sliders) : ?>
<div id="splide1" class="splide">
    <div class="splide__track">
        <ul class="splide__list">
            <?php
                foreach ((array) $sliders as $key => $val) :
                    $slider_link = get_post_meta($key, 'slider_link', true);
                ?>
            <li class="splide__slide">
                <?php if ($slider_link) : ?>
                <a href="<?php echo esc_url($slider_link); ?>" target="_blank">
                    <img data-splide-lazy="<?php echo $val; ?>">
                </a>
                <?php else : ?>
                <img data-splide-lazy="<?php echo $val; ?>">
                <?php endif; ?>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
<?php endif; ?>