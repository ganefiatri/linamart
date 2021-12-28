<?php

/**
 * FoodPress functions and definitions
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 * @package FoodPress
 * @author Taufik Hidayat <taufik@fiqhidayat.com>
 */

define('FOODPRESS_VERSION', '2.2.5');
define('FOODPRESS_PATH', get_template_directory());
define('FOODPRESS_URL', get_template_directory_uri());

if (!function_exists('__debug')) {
	function __debug()
	{
		$bt     = debug_backtrace();
		$caller = array_shift($bt);

		$result = array(
			"file"  => $caller["file"],
			"line"  => $caller["line"],
			"args"  => func_get_args()
		);

		echo '<pre>';
		print_r($result);
		echo '</pre>';
	}
}

add_action('admin_init', function () {
	if (file_exists(__DIR__ . '/vendor/autoload.php')) {
		require(__DIR__ . '/vendor/autoload.php');
	}
});

require 'update-checker/plugin-update-checker.php';
$UpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://bitbucket.org/fiqhid24/foodpress',
	__FILE__,
	'foodpress'
);

$UpdateChecker->setAuthentication(array(
	'consumer_key' => '7pRFXTX4UeUL7YsxQc',
	'consumer_secret' => 'eWaYZhVSsrW44FSkauPJGwpRWtuTJPAs',
));


if (!function_exists('foodpress_setup')) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function foodpress_setup()
	{
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on FoodPress, use a find and replace
		 * to change 'foodpress' to the name of your theme in all the template files.
		 */
		load_theme_textdomain('foodpress', get_template_directory() . '/languages');

		// Add default posts and comments RSS feed links to head.
		add_theme_support('automatic-feed-links');

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support('title-tag');

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support('post-thumbnails');

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'primary' => esc_html__('Primary', 'foodpress'),
			)
		);
	}
endif;
add_action('after_setup_theme', 'foodpress_setup');


/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function foodpress_widgets_init()
{
	register_sidebar(
		array(
			'name'          => esc_html__('Sidebar', 'foodpress'),
			'id'            => 'sidebar-1',
			'description'   => esc_html__('Add widgets here.', 'foodpress'),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action('widgets_init', 'foodpress_widgets_init');

/**
 * Enqueue scripts and styles.
 */

function foodpress_admin_scripts()
{

	wp_enqueue_style('foodpress-admin', get_template_directory_uri() . '/css/admin.css', array(), strtotime('now'), 'all');

	wp_enqueue_script('slim-select', 'https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.23.0/slimselect.min.js', [], strtotime('now'), false);

	wp_enqueue_script('foodpress-admin', get_template_directory_uri() . '/js/admin.js', array('jquery', 'jquery-ui-core'), strtotime('now'), true);
}
add_action('admin_enqueue_scripts', 'foodpress_admin_scripts');

function foodpress_scripts()
{

	if (is_admin()) return;

	if (get_post_meta(get_the_ID(), '_elementor_edit_mode', true)) :
		wp_enqueue_script('jquery');
		wp_enqueue_script('wp-embed');
	else :
		wp_deregister_script('wp-embed');
		wp_deregister_script('jquery');
	endif;
}
add_action('wp_enqueue_scripts', 'foodpress_scripts');

/**
 * insert inline styles
 * @return [type] [description]
 */
function foodpress_head_style()
{
	echo '<style type="text/css">';
	include(get_template_directory() . '/css/style.min.css');
	include(get_template_directory() . '/css/style-productbox-' . get_theme_mod('layout_list_product', 'list') . '.min.css');
	echo '</style>';
}
add_action('wp_head', 'foodpress_head_style', 10);

/**
 * inline custom style
 * @return [type] [description]
 */
function foodpress_css_script()
{
	$css = '';
	$body_background = get_theme_mod('body_background');
	$default_font = get_theme_mod('default_font');

	$link_color = get_theme_mod('link_color');
	$link_hover_color = get_theme_mod('link_hover_color');
	$link_visited_color = get_theme_mod('link_visited_color');
	$color_gradient = get_theme_mod('color_gradient', '#50EB2C');

	if ($link_color) {
		$css .= 'a{color:' . $link_color . '}';
		$css .= '.productbox .content .detail .atc button.add{background:' . $link_color . ';border:' . $link_color . ';}';
		$css .= '.qty-selector{border-color:' . $link_color . ';}';
		$css .= '.qty-selector button{color:' . $link_color . ';}';
		$css .= '.qty-selector input{border-color:' . $link_color . ';}';
		$css .= '.products-list .products-list-next{color:' . $link_color . ';}';
		$css .= '.cart .cart-inner .cart-content .cart-content-items .cart-content-items-head .cart-content-items-head-add a{color:' . $link_color . ';}';
		$css .= '.cart .cart-inner .cart-content .cart-content-submit button{background:' . $link_color . ';border-color:' . $link_color . ';}';
		$css .= '.basket .basket-next button{background-image: linear-gradient(to top, ' . $color_gradient . ', ' . $link_color . ');}';
		$css .= '.basket .basket-detail{color:' . $link_color . ';}';
		$css .= '.basket .basket-cart i{color:' . $link_color . ';}';
		$css .= '.wa-support{color:' . $link_color . ';}';
	}
	if ($link_hover_color) {
		$css .= 'a:hover, a:focus, a:active{color:' . $link_hover_color . '}';
	}
	if ($link_visited_color) {
		$css .= 'a:visited{color:' . $link_visited_color . '}';
	}

	$body = '';
	if ($default_font) {
		$font = customizer_library_get_font_stack($default_font);
		$body .= 'font-family:' . $font . ';';
	}

	$css .= 'body{' . $body . '}';

	$page_width = get_theme_mod('layout_width');
	if ($page_width) {
		$css .= '.wrapper{max-width:' . $page_width . 'px}';
		if (intval($page_width) < 900) {
			$css .= '.postbox{max-width: 50%;min-width: 50%}';
		}
	}

	$header_bg = get_theme_mod('header_bg');
	if ($header_bg) {
		if ($header_bg == '#ffffff') {
			$header_border = 'border-bottom: 1px solid rgba(0,0,0,.09);';
		} else {
			$header_border = 'border:none;';
		}
		$css .= '.site-header{background: ' . $header_bg . ';' . $header_border . '}';
	}

	$header_color = get_theme_mod('header_color');
	if ($header_color) {
		$css .= '.site-header{color: ' . $header_color . ';}';
	}

	$logo_text_color = get_theme_mod('logo_text_color');
	$logo_text_size = get_theme_mod('logo_text_size');


	if ($logo_text_color) {
		$css .= '.site-header h1.logo-text a{color:' . $logo_text_color . '}';
	}
	if ($logo_text_size) {
		$css .= '.site-header h1.logo-text a{font-size:' . $logo_text_size . 'px}';
	}
	echo '<style type="text/css">';
	echo $css;
	echo '</style>';
}
add_action('wp_head', 'foodpress_css_script', 98);

/**
 * head manin inline js
 * @return [type] [description]
 */
function foodpress_head_js_main()
{

	$lic = new Foodpress_License();
	$data = $lic->data();

	if ($data['status'] !== 'ACTIVE') return;

	$fonts = array(
		get_theme_mod('default_font'),
		get_theme_mod('heading_font')
	);
	$font_uri = customizer_library_get_google_font_uri($fonts);

	$store_id = foodpress_get_product_store_id();
	$store_name = foodpress_get_product_store_name();
	$store_link = foodpress_get_product_store_link();
	$store_ongkir = foodpress_get_product_store_ongkir();
	$store_ongkir_name = foodpress_get_store_ongkir_name();
	$store_ongkir_enable = get_option('foodpress_feature_ongkir_enable', 'yes') == 'yes' ? 'true' : 'false';
	$store_ongkir_provider = get_option('foodpress_feature_ongkir_provider', 'flatongkir');
	$store_admin_phone = foodpress_get_product_store_admin_phone();
	$store_opened_message = foodpress_get_product_store_opened_message();
	$store_open = foodpress_current_is_open() == true ? 1 : 0;
	$store_delivery_hour = 'false';
	$list = get_option('foodpress_shipping_devlivery_hour_lists');
	if (get_option('foodpress_feature_shipping_devlivery_hour_enable', 'no') == 'yes' && count($list) > 0) {
		$store_delivery_hour = 'true';
	}

	$phpjs = array(
		'site_url' => site_url(),
		'ajax_url' => admin_url('admin-ajax.php'),
		'currency' => 'new Intl.NumberFormat("id-ID", {style: "currency", currency: "IDR",minimumFractionDigits: 0})',
		'nonce' => wp_create_nonce('foodpress'),
		'font_uri' => $font_uri ? '"' . $font_uri . '"' : 'false',
		'main_script' => FOODPRESS_URL . '/js/foodpress.min.js?v=' . strtotime('now'),
		'regions_source' => FOODPRESS_URL . '/data/regions/regions.json',
		'link_wa' => foodpress_get_link_wa(),
		'store_id' => $store_id,
		'store_name' => $store_name,
		'store_link' => $store_link,
		'store_ongkir' => $store_ongkir,
		'store_ongkir_name' => $store_ongkir_name,
		'store_ongkir_enable' => $store_ongkir_enable,
		'store_ongkir_provider' => $store_ongkir_provider,
		'store_delivery_hour' => $store_delivery_hour,
		'store_admin_phone' => $store_admin_phone,
		'store_opened_message' => $store_opened_message,
		'store_open' => $store_open,
	);

	echo '<script type="text/javascript">';
	echo 'const foodpress = {';
	foreach ($phpjs as $key => $value) {
		$val = '"' . $value . '"';

		$bools = array(
			'currency',
			'font_uri',
			'store_ongkir_enable',
			'store_open',
			'store_delivery_hour'
		);

		if (in_array($key, $bools)) {
			$val = $value;
		}
		echo '"' . $key . '" : ' . $val . ',';
	}
	echo '}';
	echo '</script>';
}
add_action('wp_head', 'foodpress_head_js_main', 99);

/**
 * footer inline js
 * @return [type] [description]
 */
function foodpress_js_script()
{
	$lic = new Foodpress_License();
	$data = $lic->data();

	if ($data['status'] !== 'ACTIVE') return;

	echo '<script type="text/javascript">';
	include(get_template_directory() . '/js/inline.min.js');
	echo '</script>';
}
add_action('wp_footer', 'foodpress_js_script', 11);


/**
 * include componenent
 */
require FOODPRESS_PATH . '/inc/customizer-library/customizer-library.php';
require FOODPRESS_PATH . '/inc/customizer.php';

require FOODPRESS_PATH . '/inc/cmb2/init.php';
require FOODPRESS_PATH . '/inc/cmb2-conditionals/cmb2-conditionals.php';
require FOODPRESS_PATH . '/inc/metabox-product.php';
require FOODPRESS_PATH . '/inc/metabox-order.php';

require FOODPRESS_PATH . '/inc/license.php';

require FOODPRESS_PATH . '/inc/product.php';
require FOODPRESS_PATH . '/inc/slider.php';
require FOODPRESS_PATH . '/inc/order.php';
require FOODPRESS_PATH . '/inc/shipping.php';
require FOODPRESS_PATH . '/inc/shipping-byadmin.php';
require FOODPRESS_PATH . '/inc/shipping-rajaongkir.php';
require FOODPRESS_PATH . '/inc/shipping-flatongkir.php';
require FOODPRESS_PATH . '/inc/shipping-custom.php';
require FOODPRESS_PATH . '/inc/coupon.php';
require FOODPRESS_PATH . '/inc/coupon-metabox.php';

require FOODPRESS_PATH . '/inc/template-functions.php';

remove_action('wp_head', 'feed_links_extra', 3); // Display the links to the extra feeds such as category feeds
remove_action('wp_head', 'feed_links', 2); // Display the links to the general feeds: Post and Comment Feed
remove_action('wp_head', 'rsd_link'); // Display the link to the Really Simple Discovery service endpoint, EditURI link
remove_action('wp_head', 'wlwmanifest_link'); // Display the link to the Windows Live Writer manifest file.
remove_action('wp_head', 'index_rel_link'); // Index link
remove_action('wp_head', 'parent_post_rel_link', 10, 0); // Prev link
remove_action('wp_head', 'start_post_rel_link', 10, 0); // Start link
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0); // Display relational links for the posts adjacent to the current post.
remove_action('wp_head', 'wp_generator'); // Display the XHTML generator that is generated on the wp_head hook, WP version
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
remove_action('wp_head', 'rel_canonical');
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);

remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('admin_print_styles', 'print_emoji_styles');

remove_action('wp_head', 'rest_output_link_wp_head', 10);

// Disable oEmbed Discovery Links
remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);

// Disable REST API link in HTTP headers
remove_action('template_redirect', 'rest_output_link_header', 11, 0);

// Remove the REST API endpoint.
remove_action('rest_api_init', 'wp_oembed_register_route');

// Turn off oEmbed auto discovery.
add_filter('embed_oembed_discover', '__return_false');

// Don't filter oEmbed results.
remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);

// Remove oEmbed discovery links.
remove_action('wp_head', 'wp_oembed_add_discovery_links');

// Remove oEmbed-specific JavaScript from the front-end and back-end.
remove_action('wp_head', 'wp_oembed_add_host_js');

add_filter('show_admin_bar', '__return_false');