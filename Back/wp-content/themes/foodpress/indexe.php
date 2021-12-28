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

		<main id="main" class="site-main">
			<div class="wrapper">
				<div class="stores">
					<?php
					$terms = get_terms( array(
						'taxonomy' => 'product-store',
						'hide_empty' => false,
					) );
					?>
					<?php foreach((array)$terms as $term ): ?>
						<a href="<?php echo get_term_link($term->term_id); ?>">
							<div class="store-box">
								<?php $thumb = get_term_meta($term->term_id, 'foodpress_store_term_avatar', true); ?>
								<div class="store-thumb">
									<div class="store-thumb-img lazy" data-bg="url(<?php echo $thumb; ?>)">
									</div>
								</div>
								<div class="store-detail flexbox flexboxcenter">
									<div class="store-detail-title">
										<h3><?php echo $term->name; ?></h3>
									</div>
									<div class="store-detail-desc">
										<?php echo $term->description; ?>
									</div>
								</div>
							</div>
						</a>
					<?php endforeach; ?>
				</div>
			</div>
		</main>
	</div>
<?php get_footer();
