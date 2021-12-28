<?php
/**
 * The header for our theme
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 * @package FoodPress
 * @author Taufik Hidayat <taufik@fiqhidayat.com>
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
    <div class="container">
		<header>
		    <div class="site-header">
		        <div class="wrapper">
		            <div class="headerbox">
		                <?php if( is_singular('product') ): ?>
		                    <?php
		                    $terms = get_the_terms( get_the_ID(), 'product-store' );
		                    $store_link = site_url();
		                    if ( $terms && !is_wp_error( $terms ) ){
		                        $store_link = get_term_link($terms[0]);
		                    }
		                    ?>
		                    <div class="back">
		                        <a href="<?php echo $store_link; ?>"><i class="lni lni-arrow-left"></i></a>
		                    </div>
		                <?php else: ?>
		                    <?php
		                    $logo = get_theme_mod('logo');
		                    if($logo):
		                        ?>
		                        <div class="logo-img">
		                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
		                                <img class="lazy" data-src="<?php echo $logo; ?>" alt="<?php bloginfo( 'name' ); ?>" width="auto" height="auto">
		                            </a>
		                        </div>
		                        <?php
		                    else:
		                        ?>
		                        <h1 class="logo-text"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
		                        <?php
		                    endif;
		                    ?>
		                <?php endif; ?>
		                <div class="searchbox">
		                    <form method="get" action="<?php echo home_url(); ?>/product">
		                        <input type="search" name="s" placeholder="<?php _e( 'Search', 'foodpress' ); ?>" value="<?php echo get_search_query(); ?>">
		                        <button type="submit">
		                            <i class="lni lni-search"></i>
		                        </button>
		                    </form>
		                </div>
		            </div>
		            <nav id="navigation" class="navigation">
		                <i id="nav-menu-toggle" class="lni lni-grid-alt"></i>
		                <?php
		                wp_nav_menu(
		                    array(
		                        'theme_location'  => 'primary',
		                        'menu_id'         => false,
		                        'container_class' => 'nav-menu',
		                        'container_id'    => 'nav-menu',
		                        'menu_id'         => 'menu-header',
		                        'menu_class'      => 'menu-header'
		                    )
		                );
		                ?>
		            </nav>
		        </div>
		    </div>
		</header>
