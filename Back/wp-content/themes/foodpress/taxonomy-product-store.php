<?php
/**
 * The single for our theme
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 * @package FoodPress
 * @author Taufik Hidayat <taufik@fiqhidayat.com>
 */
get_header();

$term = get_queried_object();
?>

	<div id="primary" class="content-area">

		<div class="index">
			<div class="wrapper">
				<?php $thumb = get_term_meta($term->term_id, 'foodpress_store_term_avatar', true); ?>
				<div class="single-store-thumb">
					<img class="lazy" data-src="<?php echo $thumb; ?>">
				</div>
			</div>
		</div>
		<div class="index">
			<div class="wrapper">
				<div class="single-store-title">
					<h1><?php echo $term->name; ?></h1>
				</div>
			</div>
		</div>
		<div class="index">
			<div class="wrapper">
				<div class="single-store-desc">
					<?php echo $term->description; ?>
				</div>
			</div>
		</div>
		<div class="index">
			<div class="wrapper">
				<hr>
			</div>
		</div>

		<?php get_template_part( 'template-parts/index' ); ?>
	</div><!-- #primary -->

<?php
get_footer();
