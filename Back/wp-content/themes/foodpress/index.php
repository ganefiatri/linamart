<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package FoodPress
 */

get_header();
?>

	<div id="primary" class="content-area">

		<div class="index">
			<div class="wrapper">
				<?php get_template_part( 'template-parts/slider'); ?>
			</div>
		</div>

		<?php
		$show = get_theme_mod('slide_category_on_home', 1);
		?>
		<?php if( $show ): ?>
			<div class="index">
				<div class="wrapper">
					<?php get_template_part( 'template-parts/slide-category'); ?>
				</div>
			</div>
		<?php endif; ?>

		<?php do_action('foodpress_home'); ?>
	</div>
<?php get_footer();
