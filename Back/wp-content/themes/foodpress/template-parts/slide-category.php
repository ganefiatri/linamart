<?php
$terms = get_terms( array(
    'taxonomy' => 'product-category',
    'hide_empty' => true,
) );


$categories = get_theme_mod('slide_category_list');
if( $categories ):
    ?>
    <div id="category-slide" class="slide-categorybox">
        <div class="arrow arrow-left">
            <svg viewBox="64 64 896 896" focusable="false" class="" data-icon="left" width="1em" height="1em" fill="currentColor" aria-hidden="true"><path d="M724 218.3V141c0-6.7-7.7-10.4-12.9-6.3L260.3 486.8a31.86 31.86 0 0 0 0 50.3l450.8 352.1c5.3 4.1 12.9.4 12.9-6.3v-77.3c0-4.9-2.3-9.6-6.1-12.6l-360-281 360-281.1c3.8-3 6.1-7.7 6.1-12.6z"></path></svg>
        </div>
        <div class="slide-category">
            <?php
            $cats = explode(',', $categories);
            foreach( (array)$cats as $key=>$val ):
                $cc = explode(':', $val);
                if( $cc[1] == '1' || $cc[1] == 1 ):
                    $t = get_term($cc[0]);
                    if( isset($t->term_id) && isset($t->name) ):
                        ?>
                        <div class="categorybox lazy" data-bg=url(<?php echo get_term_meta($t->term_id, 'foodpress_category_image', true); ?>)>
                            <a href="<?php echo get_term_link($t->term_id);?>">
                                <?php echo $t->name; ?>
                            </a>
                        </div>
                        <?php
                    endif;
                endif;
            endforeach;
            ?>
        </div>
        <div class="arrow arrow-right">
            <svg viewBox="64 64 896 896" focusable="false" class="" data-icon="right" width="1em" height="1em" fill="currentColor" aria-hidden="true"><path d="M765.7 486.8L314.9 134.7A7.97 7.97 0 0 0 302 141v77.3c0 4.9 2.3 9.6 6.1 12.6l360 281.1-360 281.1c-3.9 3-6.1 7.7-6.1 12.6V883c0 6.7 7.7 10.4 12.9 6.3l450.8-352.1a31.96 31.96 0 0 0 0-50.4z"></path></svg>
        </div>
    </div>

    <?php
endif;
?>
