<section class="error-404 not-found">
    <header class="page-header">
        <div class="page-title"><?php esc_html_e( 'Oops! No post can&rsquo;t be found.', 'foodpress' ); ?></div>
    </header><!-- .page-header -->

    <div class="page-content">

        <?php
        get_search_form();

        the_widget( 'WP_Widget_Recent_Posts' );
        ?>

    </div><!-- .page-content -->
</section><!-- .error-404 -->
