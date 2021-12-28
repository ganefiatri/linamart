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

			<div class="wrapper clear">
				<?php
				while ( have_posts() ) :
					the_post();

					?>
					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						<?php
						the_title( '<h1 class="entry-title">', '</h1>' );
						?>

						<div class="entry clear">
							<?php
							the_content(
								sprintf(
									wp_kses(
										/* translators: %s: Name of current post. Only visible to screen readers */
										__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'foodpress' ),
										array(
											'span' => array(
												'class' => array(),
											),
										)
									),
									wp_kses_post( get_the_title() )
								)
							);

							wp_link_pages(
								array(
									'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'foodpress' ),
									'after'  => '</div>',
								)
							);
							?>
						</div><!-- .entry-content -->

						<?php
						if ( comments_open() || get_comments_number() ) :
							comments_template();
						endif;
						?>

					</article>
					<?php

				endwhile;

				get_sidebar();
				?>
			</div>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
