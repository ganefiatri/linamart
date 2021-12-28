<article class="postbox clear">
    <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
        <div class="thumb">
            <img class="lazy" data-src="<?php echo get_the_post_thumbnail_url(get_the_ID()); ?>" width="100%" height="auto" alt="<?php echo get_the_title(); ?>">
        </div>
        <div class="title">
            <h3><?php echo get_the_title(); ?></h3>
        </div>
    </a>
</article>
