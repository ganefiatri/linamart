<?php get_header(); ?>

<div id="primary" class="content-area">

	<main id="main" class="site-main">
		<div class="wrapper clear">
            <div class="labelbox">
                <div class="newest">
                    <h3>&quot;<?php single_cat_title(); ?>&quot;</h3>
                </div>
            </div>

			<?php
            if ( have_posts() ) :
				?>
				<div class="boxcontainer clear">

					<?php while ( have_posts() ) : the_post(); ?>
						<?php get_template_part('template-parts/postbox'); ?>
					<?php endwhile; ?>

				</div>
				<?php
				echo foodpress_post_pagination(false);
			else:
                ?>
                <div class="boxcontainer clear">

					<?php get_template_part('template-parts/404'); ?>

				</div>
                <?php
			endif;
			?>

		</div>
	</main>
</div>

<?php get_footer(); ?>
