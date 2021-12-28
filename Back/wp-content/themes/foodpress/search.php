<?php
/**
 * The search template file
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package FoodPress
 */

get_header();
?>

	<div id="primary" class="content-area" style="background: #F7F7F9;">

		<div class="index">
			<div class="wrapper">
				<?php get_template_part( 'template-parts/slider'); ?>
			</div>
		</div>

		<?php
		$show = get_theme_mod('slide_category_on_search', 1);
		?>
		<?php if( $show ): ?>
			<div class="index">
				<div class="wrapper">
					<?php get_template_part( 'template-parts/slide-category'); ?>
				</div>
			</div>
		<?php endif; ?>

		<?php foodpress_query_product(); ?>
	</div>
<?php get_footer();
