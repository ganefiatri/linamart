<?php

/**
 * FoodPress template function
 * @package FoodPress/inc
 * @author Taufik Hidayat <taufik@fiqhidayat.com>
 */


/**
 * get product page
 * @return [type] [description]
 */
function foodpress_page()
{

	$page = 'all';

	if (get_post_type() == 'product' || is_home() || is_tax('product-store')) {
		$page = 'product';
	}

	return $page;
}

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function foodpress_body_classes($classes)
{
	// Adds a class of hfeed to non-singular pages.
	if (!is_singular()) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if (!is_active_sidebar('sidebar-1')) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter('body_class', 'foodpress_body_classes');

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function foodpress_pingback_header()
{
	if (is_singular() && pings_open()) {
		printf('<link rel="pingback" href="%s">', esc_url(get_bloginfo('pingback_url')));
	}
}
add_action('wp_head', 'foodpress_pingback_header');

/**
 * pagination for post
 * @param  [type]  $query [description]
 * @param  string  $pages [description]
 * @param  integer $range [description]
 * @return [type]         [description]
 */
function foodpress_post_pagination($query, $pages = '', $range = 1)
{
	global $wp_query, $paged;

	$showitems = ($range * 2) + 1;

	if (empty($paged)) $paged = 1;

	if ($pages == '') {   // paged is not defined than its first page. just assign it first page.
		$pages = $query === false ? $wp_query->max_num_pages : $query->max_num_pages;
		if (!$pages)
			$pages = 1;
	}

	if (1 != $pages) { //For other pages, make the pagination work on other page queries
		echo '<div class="pagination">';
		//posts_nav_link(' ', __('Previous Page', 'pngtree'), __('Next Page', 'pngtree'));
		//if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<a href='".get_pagenum_link(1)."'>&laquo;</a>";
		if ($paged > 1 && $showitems < $pages) echo '<a href="' . get_pagenum_link($paged - 1) . '" class="prevnextlink">Sebelumnya</a>';

		for ($i = 1; $i <= $pages; $i++) {
			if (1 != $pages && (!($i >= $paged + $range + 1 || $i <= $paged - $range - 1) || $pages <= $showitems))
				echo ($paged == $i) ? "<span class='current color-scheme-background'>" . $i . "</span>" : "<a href='" . get_pagenum_link($i) . "' class='inactive' >" . $i . "</a>";
		}

		if ($paged < $pages && $showitems < $pages) echo '<a href="' . get_pagenum_link($paged + 1) . '" class="prevnextlink">Selanjutnya</a>';
		//if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($pages)."'>&raquo;</a>";
		echo '</div>';
	}
}

add_filter('next_posts_link_attributes', 'foodpress_next_posts_link');
function foodpress_next_posts_link()
{
	return 'class="next"';
}

add_filter('previous_posts_link_attributes', 'foodpress_prev_posts_link');
function foodpress_prev_posts_link()
{
	return 'class="prev"';
}


/**
 * modify content output
 * @param  [type] $content [description]
 * @return [type]          [description]
 */
function foodpress_the_content($content)
{

	if (is_feed() || is_search() || is_archive()) {
		return $content;
	}


	$img_pattern = '/<img\s+[^>]*>/si';

	$iframe_pattern = '/<iframe.*?s*src="(.*?)".*?<\/iframe>/si';

	$content = preg_replace_callback($img_pattern, 'foodpress_content_img', $content);

	$content = preg_replace_callback($iframe_pattern, 'foodpress_content_iframe', $content);

	return $content;
}
add_filter('the_content', 'foodpress_the_content', 9999);

/**
 * make image on content ready for lazyload
 * @param  [type] $img [description]
 * @return [type]      [description]
 */
function foodpress_content_img($img)
{

	//preg_match_all('/(\w+)=["\']([a-zA-Z0-9-\/_.:\'"]+)["\']/', $img_tag, $matches, PREG_SET_ORDER, 0);

	$img_tag = isset($img[0]) ? $img[0] : '';

	if (!$img_tag) return $img_tag;

	$noscript = '<noscript>' . $img_tag . '</noscript>';

	preg_match('/class/', $img_tag, $match);
	if (empty($match)) {
		$img_tag = str_replace('<img', '<img class="lazy"', $img_tag);
	}

	preg_match('/lazy/', $img_tag, $match);
	if (empty($match)) {
		$img_tag = str_replace('class="', 'class="lazy ', $img_tag);
	}

	preg_match('/data-src="/', $img_tag, $match);
	if (empty($match)) {
		$img_tag = str_replace(' src="', ' data-src="', $img_tag);
	}

	return $img_tag . $noscript;
}

/**
 * make iframe on content ready for lazyload
 * @param  [type] $iframe [description]
 * @return [type]         [description]
 */
function foodpress_content_iframe($iframe)
{

	$iframe_tag = isset($iframe[0]) ? $iframe[0] : '';

	if (empty($iframe_tag)) return $iframe_tag;

	$src   = isset($iframe[1]) ? $iframe[1] : '';

	preg_match('/youtu/', $src, $match);
	if ($match) {
		$parts = parse_url($src);
		if (isset($parts['path'])) {
			$path = explode('/', trim($parts['path'], '/'));
			$youtube_id = $path[count($path) - 1];

			$iframe_tag = str_replace('></iframe>', ' poster="https://i3.ytimg.com/vi/' . $youtube_id . '/hqdefault.jpg"></iframe>', $iframe_tag);
		}
	}

	preg_match('/class/', $iframe_tag, $match);
	if (empty($match)) {
		$iframe_tag = str_replace('<iframe', '<iframe class="lazy"', $iframe_tag);
	}

	preg_match('/lazy/', $iframe_tag, $match);
	if (empty($match)) {
		$iframe_tag = str_replace('class="', 'class="lazy ', $iframe_tag);
	}

	preg_match('/data-src="/', $iframe_tag, $match);
	if (empty($match)) {
		$iframe_tag = str_replace(' src="', ' data-src="', $iframe_tag);
	}
	return $iframe_tag;
}


function foodpress_facebook_pixel_tracking()
{
	$order_pixel_event = get_theme_mod('fbpixel_button_order_event', '');
	$atc_pixel_event = get_theme_mod('fbpixel_button_atc_event');

	$pixel_id_1 = get_theme_mod('fb_pixel_id_1');
	$pixel_id_2 = get_theme_mod('fb_pixel_id_2');
	$pixel_id_3 = get_theme_mod('fb_pixel_id_3');
	$pixel_id_4 = get_theme_mod('fb_pixel_id_4');
	$pixel_id_5 = get_theme_mod('fb_pixel_id_5');

	if ($pixel_id_1) {
		$pixels[] = $pixel_id_1;
	}

	if ($pixel_id_2) {
		$pixels[] = $pixel_id_2;
	}

	if ($pixel_id_3) {
		$pixels[] = $pixel_id_3;
	}

	if ($pixel_id_4) {
		$pixels[] = $pixel_id_4;
	}

	if ($pixel_id_5) {
		$pixels[] = $pixel_id_5;
	}



	if (empty($pixels)) return;

	$pixel_script = array();
	$pixel_noscript = array();

	foreach ((array) $pixels as $pixel) {
		$pixel_script[] = 'fbq("init", "' . $pixel . '");';
		$pixel_noscript[] = '<img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=' . $pixel . '&ev=PageView&noscript=1" />';
	}
	$code = '';
	ob_start();
?>
<!-- Facebook Pixel Code -->
<script>
! function(f, b, e, v, n, t, s) {
    if (f.fbq) return;
    n = f.fbq = function() {
        n.callMethod ?
            n.callMethod.apply(n, arguments) : n.queue.push(arguments)
    };
    if (!f._fbq) f._fbq = n;
    n.push = n;
    n.loaded = !0;
    n.version = '2.0';
    n.queue = [];
    t = b.createElement(e);
    t.async = !0;
    t.src = v;
    s = b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t, s)
}(window, document, 'script',
    'https://connect.facebook.net/en_US/fbevents.js');
</script>
<?php echo '<script>' . implode(' ', $pixel_script) . 'fbq("track", "PageView");</script>'; ?>
<?php if ($order_pixel_event == 'InitiateCheckout' || $order_pixel_event == 'Lead') : ?>
<script>
var button = document.getElementById('submit');
if (typeof(button) != 'undefined' && button != null) {
    button.addEventListener(
        'click',
        function() {
            fbq('track', '<?php echo $order_pixel_event; ?>');
        },
        false
    );
}
</script>
<?php endif; ?>
<?php if ($order_pixel_event == 'Purchase') : ?>
<script>
var button = document.getElementById('submit');
if (typeof(button) != 'undefined' && button != null) {
    button.addEventListener(
        'click',
        function() {
            let $total = 0;

            if (localStorage.getItem('foodpress_cart_detail')) {
                let $detail = JSON.parse(localStorage.getItem('foodpress_cart_detail'));
                $total = $detail.total;
            }
            fbq('track', 'Purchase', {
                value: $total,
                currency: 'IDR',
            });
        },
        false
    );
}
</script>
<?php endif; ?>
<?php if ($atc_pixel_event) : ?>
<script>
let $atc_buttons = document.querySelectorAll('.button-add');
for (var i = 0, length = $atc_buttons.length; i < length; i++) {
    $atc_buttons[i].addEventListener(
        'click',
        function() {
            let $atc = this.parentNode,
                $qty = $atc.querySelector('.qty'),
                $inputs = $atc.querySelectorAll('input'),
                $item = {};
            $totale = 0;

            $qty.querySelector('input').value = 1;
            this.style.display = 'none';
            $qty.style.display = 'block';

            for (var i = 0, length = $inputs.length; i < length; i++) {
                let $name = $inputs[i].name;
                $item[$name] = $inputs[i].value;
            }

            $totale = parseInt($item.qty) * parseInt($item.item_price);

            console.log($item);

            fbq('track', 'AddToCart', {
                content_name: $item.item_name,
                content_ids: [$item.item_id],
                content_type: 'product',
                value: parseInt($totale),
                currency: 'IDR'
            });
        },
        false
    );
}
</script>
<?php endif; ?>
<noscript>
    <?php echo implode(' ', $pixel_noscript); ?>
</noscript>
<!-- End Facebook Pixel Code -->
<?php
}
add_action('wp_footer', 'foodpress_facebook_pixel_tracking', 98);


/**
 * handle custom script on head
 * @return [type] [description]
 */
function foodpress_head_custom_script()
{
	$code = get_theme_mod('custom_script_head');
	$singular_code = get_post_meta(get_the_ID(), 'head_script_code', true);

	if (!$code && !$singular_code) return;

	echo $code . $singular_code;
}
add_action('wp_head', 'foodpress_head_custom_script', 99);

/**
 * handle custom script on footer
 * @return [type] [description]
 */
function foodpress_footer_custom_script()
{
	$code = get_theme_mod('custom_script_footer');
	$singular_code = get_post_meta(get_the_ID(), 'footer_script_code', true);

	if (!$code && !$singular_code) return;

	echo $code . $singular_code;
}
add_action('wp_footer', 'foodpress_footer_custom_script', 99);


add_action('pre_get_posts', 'foodpress_query_filter', 10);
/**
 * modifi homepage query
 * @param  [type] $query [description]
 * @return [type]        [description]
 */
function foodpress_query_filter($query)
{

	if (!is_home() || !is_search()) return;

	if ('nav_menu_item' == $query->get('post_type')) return;

	$query->set('post_type', 'product');
}

/**
 * get store id
 * @return [type] [description]
 */
function foodpress_get_product_store_id()
{

	$store_id = 999999;

	return apply_filters('foodpress_get_product_store_id', $store_id);
}

/**
 * get store name
 * @return [type] [description]
 */
function foodpress_get_product_store_name()
{

	$store_name = get_theme_mod('store_name', 'FoodPress');

	return apply_filters('foodpress_get_product_store_name', $store_name);
}

/**
 * get store link
 * @return [type] [description]
 */
function foodpress_get_product_store_link()
{

	$store_link = site_url();

	return apply_filters('foodpress_get_product_store_link', $store_link);
}

/**
 * get store ongkir
 * @return [type] [description]
 */
function foodpress_get_product_store_ongkir()
{

	$store_ongkir = get_option('foodpress_flatongkir_cost', '0');

	return apply_filters('foodpress_get_product_store_ongkir', $store_ongkir);
}


/**
 * get store admin phone
 * @return [type] [description]
 */
function foodpress_get_product_store_admin_phone()
{

	$phone = get_theme_mod('store_admin_phone', '628123456789');

	$phone = apply_filters('foodpress_get_product_store_admin_phone', $phone);

	$phones = explode(',', $phone);

	$key = array_rand($phones, 1);

	$wa = isset($phones[$key]) ? $phones[$key] : '';

	$wa = preg_replace('/[^0-9]/', '', $wa);
	$wa = preg_replace('/^620/', '62', $wa);
	$wa = preg_replace('/^0/', '62', $wa);

	return $wa;
}

/**
 * get store opened message
 * @return [type] [description]
 */
function foodpress_get_product_store_opened_message()
{

	$message = get_theme_mod('store_opened_message', 'Halo kak, saya mau order');

	return apply_filters('foodpress_get_product_store_opened_message', $message);
}


add_action('admin_menu',   'foodpress_admin_menu');
/**
 * foodpress menu
 * @return [type] [description]
 */
function foodpress_admin_menu()
{

	add_menu_page(
		__('Food Press', 'foodpress'),
		__('Food Press', 'foodpress'),
		'manage_options',
		'foodpress',
		'foodpress_admin_page',
		null,
		1
	);

	add_submenu_page(
		'foodpress',
		'Feature',
		'Feature',
		'manage_options',
		'foodpress-feature',
		'foodpress_feature_options_page'
	);
}

/**
 * foodpress page
 * @return [type] [description]
 */
function foodpress_admin_page()
{

	$readonly = 'required';
	$value = '';
	$value_code = '';
	$disable = '';
	$status = 'INACTIVE';
	$message = '';
	$button_text = 'Activate License';
	$license_reset = '';

	$lic = new Foodpress_License();

	if (isset($_POST['submit']) && isset($_POST['_wpnonce'])) {
		if (wp_verify_nonce($_POST['_wpnonce'], 'foodpress')) {

			$c = $_POST['credential'];

			$cred = array(
				'code'    => sanitize_text_field($c['code']),
				'email'   => sanitize_email($c['email']),
				'pass'    => sanitize_text_field($c['pass']),
				'request' => isset($_GET['action']) && $_GET['action'] == 'reset' ? 'delete' : 'register'
			);

			$lic->set_credential($cred);
			$lic->connect();
		}
	}

	$data = $lic->data();
	if (isset($_GET['action']) && $_GET['action'] == 'reset') {

		$license_reset = '&nbsp;&nbsp;<a href="' . admin_url() . 'admin.php?page=foodpress">Back To License Page</a>';
		$button_text = 'Reset License';

		$value_code = isset($data['code']) ? sanitize_text_field($data['code']) : '';
		$status = isset($data['status']) ? sanitize_text_field($data['status']) : '';

		if ($status == 'DELETED') {
			$message = isset($data['message']) ? sanitize_text_field($data['message']) : '';
		}

		$disable = $data['status'] == 'ACTIVE' ? '' : 'disabled="disabled"';
	} else {
		$domain = preg_replace("(^https?://)", "", site_url());
		$status = $data['status'];
		if ($data['status'] == 'ACTIVE' && $data['domain'] == $domain) {
			$readonly = 'readonly';
			$value = '***************************';
			$value_code = '***************************';
			$disable = 'disabled="disabled"';

			$license_reset = '&nbsp;&nbsp;<a href="' . admin_url() . 'admin.php?page=foodpress&action=reset">Reset License</a>';
		}
	}

?>
<div class="wrap">
    <h2>FoodPress License</h2>
    <form method="post" action="">
        <?php wp_nonce_field('foodpress', '_wpnonce'); ?>
        <h3>
            <? echo __('Your foodpress license code', 'foodpress'); ?>
        </h3>
        <p>
            License Status : <?php echo $status; ?>
        </p>
        <p>
            <?php echo $message; ?>
        </p>
        <p>
            <label>License Code</label><br />
            <input name="credential[code]" type="text" class="regular-text" value="<?php echo $value_code; ?>"
                placeholder="Insert your license key here" <?php echo $readonly; ?>>
        </p>
        <p>
            <label>Email that registered on foodpress.id</label><br />
            <input name="credential[email]" type="email" class="regular-text" value="<?php echo $value; ?>"
                placeholder="Insert your foodpress.id email" <?php echo $readonly; ?>>
        </p>
        <p>
            <label>Password on foodpress.id</label><br />
            <input name="credential[pass]" type="text" class="regular-text" value="<?php echo $value; ?>"
                placeholder="Insert your foodpress.id password" <?php echo $readonly; ?>>
        </p>
        <p>
            <input type="submit" class="button button-primary" name="submit" value="<?php echo $button_text; ?>"
                <?php echo $disable; ?>>
            <?php echo $license_reset; ?>
        </p>
        <p></p>
    </form>
    <div class="clear"></div>
</div>
<?php

}

function foodpress_feature_options_page()
{

	$tabs = array(
		'ongkir' => __('Shipping', 'foodpress'),
	);

	$tabs = apply_filters('foodpress_feature_tabs', $tabs);

	$current_tab = (isset($_GET['tab']) && array_key_exists($_GET['tab'], $tabs)) ? trim($_GET['tab']) : 'ongkir';

	$sections = array(
		'general' => 'General',
	);

	$sections = apply_filters('foodpress_feature_tab_section_' . $current_tab, $sections);

	$current_section = (isset($_GET['section']) && array_key_exists($_GET['section'], $sections)) ? trim($_GET['section']) : 'general';

?>
<div class="wrap">
    <h1 class="wp-heading-inline"><?php echo esc_html(__('Foodpress Feature', 'foodpress')); ?></h1>

    <h2 class="nav-tab-wrapper wp-clearfix">
        <?php foreach ($tabs as $url => $title) : ?>
        <a href="<?php echo add_query_arg('tab', $url); ?>"
            class="nav-tab <?php echo ($current_tab === $url) ? 'nav-tab-active' : '' ?>">
            <?php echo $title ?>
        </a>
        <?php endforeach; ?>
    </h2>

    <div class="foodpress-clearfix" style="position:relative;width: 100%;margin-bottom: 10px;">
        <ul class="subsubsub">
            <?php foreach ($sections as $key => $value) : ?>
            <li>
                <a href="<?php echo add_query_arg('section', $key); ?>"
                    <?php echo ($current_section === $key) ? 'class="current"' : ''; ?>><?php echo $value; ?></a> |
            </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <hr class="wp-header-end">
    <div class="foodpress-feature">
        <form action="" method="post" enctype="multipart/form-data">
            <?php wp_nonce_field('noncenonce', '__foodpressfeaturenonce', false); ?>
            <?php do_action('foodpress_feature_option_page_' . $current_tab . '_' . $current_section); ?>
        </form>
    </div>
</div>
<?php
}

add_action('admin_init', 'foodpress_periodic_check');
/**
 * periodic license check
 * @return [type] [description]
 */
function foodpress_periodic_check()
{

	$domain = preg_replace("(^https?://)", "", site_url());
	$lic = new Foodpress_License();
	$data = $lic->data();

	if ($data['status'] == 'ACTIVE' && $data['domain'] == $domain && empty(get_transient('d64866e326d996foodpresser')) && $data['code']) {
		$cred = array(
			'code' => $data['code'],
			'request' => 'check',
		);

		$lic->set_credential($cred);
		$lic->connect();
	}
}

add_action('admin_init', 'foodpress_validate_domain');
/**
 * validate domain check
 * @return [type] [description]
 */
function foodpress_validate_domain()
{
	$domain = preg_replace("(^https?://)", "", site_url());
	$lic = new Foodpress_License();
	$data = $lic->data();

	if ($data['status'] == 'ACTIVE' && $data['domain'] !== $domain) {
		if (get_option('foodpress_license')) {
			delete_option('foodpress_license');
			wp_cache_delete('foodpress_license', 'options');
		}
	}
}

add_action('admin_init', 'foodpress_feature_save_options');
/**
 * feature save options
 * @return [type] [description]
 */
function foodpress_feature_save_options()
{
	if (!isset($_POST['__foodpressfeaturenonce'])) return;

	if (!wp_verify_nonce($_POST['__foodpressfeaturenonce'], 'noncenonce')) return;

	$data = $_POST;

	unset($data['__foodpressfeaturenonce']);

	foreach ((array)$data as $key => $value) {
		if (is_array($value)) :
			update_option($key, $value);
		else :
			update_option($key, wp_kses_post(stripcslashes($value)));
		endif;
	}
}


add_action('wp_footer', 'foodpress_wa_chat_support');
function foodpress_wa_chat_support()
{

	$is_help_enable = get_theme_mod('help_enable', 1);

	if (empty($is_help_enable)) return;
	ob_start();
?>
<div id="support-wa-toggle" class="wa-support">
    <i class="lni lni-whatsapp"></i>
</div>
<div id="support-wa" class="wa-support-box">
    <div class="wa-support-body">
        <div class="wa-support-heading clear">
            <b>Form</b> Bantuan Whatsapp!
        </div>
        <form class="wa-support-form" method="post" enctype="multipart/form-data">
            <div class="input">
                <i class="icon ion-md-person"></i>
                <input type="text" name="name" placeholder="Nama Lengkap" required=""
                    oninvalid="this.setCustomValidity('Input Nama Lengkap Anda')" oninput="this.setCustomValidity('')">
            </div>
            <div class="input">
                <textarea name="message" placeholder="Pesan Anda"></textarea>
            </div>
            <button type="submit">
                <i class="lni lni-whatsapp"></i> Kirim
            </button>
        </form>
    </div>
</div>
<?php
	$html = ob_get_contents();
	ob_end_clean();

	echo $html;
}

add_action('foodpress_home', 'foodpress_homepage_template');
/**
 * load homepage template
 * @return [type] [description]
 */
function foodpress_homepage_template()
{
	$template = FOODPRESS_PATH . '/template-parts/index.php';

	$template = apply_filters('foodpress_homepage_template', $template);

	if (file_exists($template)) {
		require_once $template;
	}
}


function foodpress_query_product($category_id = false)
{

	$is_exists = false;
	$title = 'Semua Produk';
	$store_id = 0;
	$category_id = $category_id == 'all' ? false : $category_id;

	if (is_tax('product-category')) {

		global $wp_query;

		$products = $wp_query;
		$category = get_queried_object();
		$title = $category->name;
		$category_id = $category->term_id;
	} else {
		$store = get_queried_object();
		$category = get_term($category_id);

		$args = array(
			'post_type'      => 'product',
			'posts_per_page' => get_option('posts_per_page'),
			'post_status'    => 'publish',
			'order'          => 'DESC',
			'orderby'        => 'date',
		);

		if (is_search()) {
			$args['s'] = get_search_query();
		}

		if (!is_wp_error($category)) {
			$args['tax_query'][] = array(
				'taxonomy' => 'product-category',
				'field' => 'term_id',
				'terms' => $category->term_id,
			);

			$title = $category->name;
		}

		if (isset($store->taxonomy) && $store->taxonomy == 'product-store') {
			$args['tax_query'][] = array(
				'taxonomy' => 'product-store',
				'field' => 'term_id',
				'terms' => $store->term_id,
			);
			$args['tax_query']['relation'] = 'AND';
			$store_id = $store->term_id;
		}

		$title = is_search() ? '"' . get_search_query() . '"' : $title;

		$products = new WP_Query($args);
	}

	if ($products && null !== $products->have_posts() && $products->have_posts()) :
		$is_exists = true;
	?>
<div class="wrapper products-list">
    <div class="products-list-title">
        <h2><?php echo $title ?></h2>
    </div>
    <div class="products clear">
        <?php
				while ($products->have_posts()) : $products->the_post();
					get_template_part(foodpress_list_product_template());
				endwhile;
				?>
    </div>
    <?php if ($products->max_num_pages > 1) : ?>
    <div class="products-list-next loadmore" data-paged="1" data-search="<?php echo get_search_query(); ?>"
        data-store-id="<?php echo $store_id; ?>" data-category-id="<?php echo $category_id; ?>">
        Tampilkan lebih banyak
    </div>
    <?php endif; ?>
</div>
<?php
	endif;

	if ($is_exists == false) {
	?>
<div class="wrapper products-list">
    <div class="product-list-title">
        <h2><?php echo $title; ?></h2>
    </div>
    <div class="products clear">
        <p>Ups, Tidak ada produk di temukan</p>
    </div>
</div>
<?php
	}
}

function foodpress_list_product_template()
{
	$template = 'template-parts/productbox';

	return apply_filters('foodpress_list_product_template', $template);
}

add_action('wp_ajax_loadmore-products', 'foodpress_ajax_load_next_product');
add_action('wp_ajax_nopriv_loadmore-products', 'foodpress_ajax_load_next_product');
/**
 * ajax handle next products load
 * @return [type] [description]
 */
function foodpress_ajax_load_next_product()
{

	$data = isset($_GET) ? $_GET : array();

	if (isset($data['nonce'])  && wp_verify_nonce($data['nonce'], 'foodpress')) :

		$category_id = isset($data['category_id']) ? intval($data['category_id']) : 0;
		$store_id = isset($data['store_id']) ? intval($data['store_id']) : 0;
		$search = isset($data['search']) ? sanitize_text_field($data['search']) : '';
		$paged = isset($data['paged']) ? intval($data['paged']) : 1;
		$offset = $paged * intval(get_option('posts_per_page'));

		$args = array(
			'post_type'      => 'product',
			'posts_per_page' => get_option('posts_per_page'),
			'offset'         => $offset,
			'post_status'    => 'publish',
			'order'          => 'DESC',
			'orderby'        => 'date',
		);

		if (!empty($search)) {
			$args['s'] = $search;
		}

		$category = get_term($category_id);
		$store = get_term($store_id);

		if (!is_wp_error($category)) {
			$args['tax_query'][] = array(
				'taxonomy' => 'product-category',
				'field' => 'term_id',
				'terms' => $category->term_id,
			);

			$title = $category->name;
		}

		if (!is_wp_error($store)) {
			$args['tax_query'][] = array(
				'taxonomy' => 'product-store',
				'field' => 'term_id',
				'terms' => $store->term_id,
			);
			$args['tax_query']['relation'] = 'AND';
		}

		$title = is_search() ? '"' . get_search_query() . '"' : $title;

		$products = new WP_Query($args);
		$next = $paged + 1;
		$next = $products->max_num_pages == $next ? false : $next;

		ob_start();
		if ($products && null !== $products->have_posts() && $products->have_posts()) :
			while ($products->have_posts()) : $products->the_post();
				get_template_part(foodpress_list_product_template());
			endwhile;
		endif;
		$products = ob_get_contents();
		ob_end_clean();

		echo json_encode(array(
			'products' => $products,
			'next' => $next,
		));

	endif;
	exit;
}

function foodpress_current_is_open()
{
	$is_open = false;

	$timezone = get_option('timezone_string', 'Asia/Jakarta');
	if (empty($timezone)) {
		$timezone = 'Asia/Jakarta';
	}
	date_default_timezone_set($timezone);
	$current_day = strtolower(date('l'));
	$current_hour = strtotime(date('H:i'));
	$current_start_hour = get_theme_mod('day_' . $current_day . '_hour_start', '08:00');
	$current_end_hour = get_theme_mod('day_' . $current_day . '_hour_end', '17:00');

	$start_hour = strtotime(date($current_start_hour));
	$end_hour = strtotime(date($current_end_hour));

	if (get_theme_mod('day_' . $current_day, 1)) {
		if ($current_hour > $start_hour && $current_hour < $end_hour) {
			$is_open = true;
		}
	}

	return $is_open;
}

function foodpress_closed_message()
{
	if (is_admin()) return;

	$message = get_theme_mod('closed_message', 'Mohon maaf saat ini kami tutup, dan akan buka lagi pada waktu [next-open-hours]');

	$is_open_day = false;
	$timezone = get_option('timezone_string', 'Asia/Jakarta');
	date_default_timezone_set($timezone);
	$next_open_day = strtolower(date('l'));
	$i = 1;

	while ($i < 8 && $is_open_day == false) {
		if (get_theme_mod('day_' . $next_open_day, 1)) {
			$is_open_day = true;
		} else {
			$next_open_day = strtolower(date('l', strtotime('+' . $i . ' day')));
		}
		$i++;
	}

	$show_start_hour = get_theme_mod('day_' . $next_open_day . '_hour_start', '08:00');
	$show_open_day = '';

	$today = strtolower(date('l'));

	switch ($next_open_day) {
		case 'sunday':
			$show_open_day = "Hari Minggu";
			break;

		case 'monday':
			$show_open_day = "Hari Senin";
			break;

		case 'tuesday':
			$show_open_day = "Hari Selasa";
			break;

		case 'wednesday':
			$show_open_day = "Hari Rabu";
			break;

		case 'thursday':
			$show_open_day = "Hari Kamis";
			break;

		case 'friday':
			$show_open_day = "Hari Jumat";
			break;

		case 'saturday':
			$show_open_day = "Hari Sabtu";
			break;
	}

	if ($today == $next_open_day) {
		$show_open_day = 'Hari ini';
	}

	$replacer = $show_open_day . ' Pukul ' . $show_start_hour;
	$message = str_replace('[next-open-hours]', $replacer, $message);

	return $message;
}

function foodpress_get_store_ongkir_name()
{
	$name = 'Kurir Toko';

	$provider = get_option('foodpress_feature_ongkir_provider');
	if ($provider == 'flatongkir') {
		$name = get_option('foodpress_flatongkir_name', 'Kurir Toko');
	}

	if ($provider == 'custom') {
		$name = get_option('foodpress_customshipping_name', 'Kurir Toko');
	}

	return $name;
}

function foodpress_get_item_price($product_id)
{

	$regular_price = intval(get_post_meta($product_id, 'product_price', true));
	$promo = get_post_meta($product_id, 'product_promo', true);
	$promo_price = intval(get_post_meta($product_id, 'product_promo_price', true));

	$price = ($promo == 'on') ? $promo_price : $regular_price;

	return $price;
}

function foodpress_get_coupon($code)
{
	global $wpdb;

	$coupon = array();

	$code = sanitize_text_field($code);
	$post_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_title = '" . $code . "'");

	if ($post_id && get_post_type($post_id) == 'foodpress-coupon') {
		$coupon = array(
			'id'           => $post_id,
			'type'         => get_post_meta($post_id, 'coupon_type', true),
			'discount'     => intval(get_post_meta($post_id, 'coupon_discount', true)),
			'expired'      => strtotime(get_post_meta($post_id, 'coupon_expired', true)),
			'amount'       => intval(get_post_meta($post_id, 'coupon_amount', true)),
			'usage'        => intval(get_post_meta($post_id, 'coupon_usage', true)),
			'minimum_cart' => intval(get_post_meta($post_id, 'coupon_min_cart', true)),
		);
	}


	return $coupon ? (object) $coupon : false;
}

/**
 * get whatsapp link bot on mobile and desktop device
 * @return [type] [description]
 */
function foodpress_get_link_wa()
{
	$link_wa = 'https://web.whatsapp.com/send';
	if (wp_is_mobile()) {
		$link_wa = 'whatsapp://send';
	}

	return $link_wa . '?phone=' . foodpress_get_product_store_admin_phone() . '&';
}