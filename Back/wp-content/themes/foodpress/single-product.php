<?php
/**
 * The single for our theme
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 * @package FoodPress
 * @author Taufik Hidayat <taufik@fiqhidayat.com>
 */
get_header();
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">
			<div class="wrapper">
				<?php
				while ( have_posts() ) :
					the_post();

					get_template_part( 'template-parts/product');

				endwhile; // End of the loop.
				?>
			</div>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
