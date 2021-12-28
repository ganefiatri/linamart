<?php
/**
 * The footer for our theme
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 * @package FoodPress
 * @author Taufik Hidayat <taufik@fiqhidayat.com>
 */

?>
        <footer>
            <div class="site-footer">
                <div class="wrapper">
                    <div class="address">
                        <?php echo get_theme_mod('address_text', 'Jl. HM Ardans No. 6, Satimpo<br>
        Bontang Selatang, Ka. Bontang, Kaltim
        Telp: +62 811 545 115'); ?>
                    </div>
                    <div class="social">
                        <a href="<?php echo get_theme_mod('social_facebook_link', '#'); ?>" target="_blank">
                            <i class="lni lni-facebook"></i>
                        </a>
                        <a href="<?php echo get_theme_mod('social_instagram_link', '#'); ?>" target="_blank">
                            <i class="lni lni-instagram"></i>
                        </a>
                    </div>
                    <div class="copyright">
                        <?php echo get_theme_mod('copyright_text', 'Copyright @ 2020 foodpress.id'); ?>
                    </div>
                </div>
            </div>
            <?php get_template_part( 'template-parts/footer', foodpress_page() ); ?>
            <?php wp_footer(); ?>
        </footer>
    </div>
</body>
</html>
