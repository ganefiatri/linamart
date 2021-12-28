<main id="main" class="query">
    <?php
    $categories = get_theme_mod('homepage_category_list','all:1');
    if( $categories ):
        $cats = explode(',', $categories);
        foreach( (array)$cats as $key=>$val ):
            $cc = explode(':', $val);
            if( $cc[1] == '1' || $cc[1] == 1 ):
                foodpress_query_product($cc[0]);
            endif;
        endforeach;
    endif;
    ?>
</main><!-- #main -->
