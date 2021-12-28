<?php
/**
 * Customize for textarea, extend the WP customizer
 *
 * @package 	Customizer_Library
 * @author		Devin Price, The Theme Foundry
 */

if ( ! class_exists( 'WP_Customize_Control' ) ) {
	return NULL;
}

class Customizer_Library_Icon_Picker_Control extends WP_Customize_Control {

	/**
	 * Control Type
	 */
	public $type = 'icon-picker';

	/**
	 * Enqueue Scripts
	 */
	public function enqueue() {
		wp_enqueue_script( 'select2', 'https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js', array( 'jquery' ), rand(), true );
		wp_enqueue_script( 'customizer-icon-picker-control', $this->abs_path_to_url( dirname( __FILE__ ) . '/js/customizer-icon-picker-control.js' ), array( 'jquery' ), rand(), true );

		wp_enqueue_style( 'lineicons', 'https://cdn.lineicons.com/2.0/LineIcons.css', array(), rand() );
		wp_enqueue_style( 'select2', 'https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css', array(), rand() );
		wp_enqueue_style( 'customizer-icon-picker-control', $this->abs_path_to_url( dirname( __FILE__ ) . '/css/customizer-icon-picker-control.css' ), array(), rand() );
	}

	/**
	 * Render Settings
	 */
	public function render_content() {

		$value = $this->value();
		?>

		<?php if ( !empty( $this->label ) ){ ?>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
		<?php } // add label if needed. ?>

		<?php if ( !empty( $this->description ) ){ ?>
			<span class="description customize-control-description"><?php echo $this->description; ?></span>
		<?php } // add desc if needed. ?>

		<select id="<?php echo esc_attr( $this->id ); ?>" class="customizer-select-2" <?php $this->link(); ?>>
			<?php foreach( $this->icons() as $key=>$val ){?>
				<option value="<?php echo $key; ?>" <?php if( $key == $value ){ echo 'selected="selected"'; };?>><?php echo $key; ?></option>
			<?php } ?>
		</select>


	<?php
	}

	/**
	 * Plugin / theme agnostic path to URL
	 *
	 * @see https://wordpress.stackexchange.com/a/264870/14546
	 * @param string $path  file path
	 * @return string       URL
	 */
	private function abs_path_to_url( $path = '' ) {
		$url = str_replace(
			wp_normalize_path( untrailingslashit( ABSPATH ) ),
			site_url(),
			wp_normalize_path( $path )
		);
		return esc_url_raw( $url );
	}

	private function icons(){

		$json = '{ "lni-500px": 59906, "lni-add-files": 59907, "lni-alarm-clock": 59908, "lni-alarm": 59909, "lni-airbnb": 59910, "lni-adobe": 59911, "lni-amazon-pay": 59912, "lni-amazon": 59913, "lni-amex": 59914, "lni-anchor": 59915, "lni-amazon-original": 59916, "lni-android-original": 59917, "lni-android": 59918, "lni-angellist": 59919, "lni-angle-double-down": 59920, "lni-angle-double-left": 59921, "lni-angle-double-right": 59922, "lni-angle-double-up": 59923, "lni-angular": 59924, "lni-apartment": 59925, "lni-app-store": 59926, "lni-apple-pay": 59927, "lni-apple": 59928, "lni-archive": 59929, "lni-arrow-down-circle": 59930, "lni-arrow-left-circle": 59931, "lni-arrow-left": 59932, "lni-arrow-right-circle": 59933, "lni-arrow-right": 59934, "lni-arrow-top-left": 59935, "lni-arrow-top-right": 59936, "lni-arrow-up-circle": 59937, "lni-arrow-up": 59938, "lni-arrows-horizontal": 59939, "lni-arrows-vertical": 59940, "lni-atlassian": 59941, "lni-aws": 59942, "lni-arrow-down": 59943, "lni-ambulance": 59944, "lni-agenda": 59945, "lni-backward": 59946, "lni-baloon": 59947, "lni-ban": 59948, "lni-bar-chart": 59949, "lni-behance-original": 59950, "lni-bitbucket": 59951, "lni-bitcoin": 59952, "lni-blackboard": 59953, "lni-blogger": 59954, "lni-bluetooth": 59955, "lni-bold": 59956, "lni-bolt-alt": 59957, "lni-bolt": 59958, "lni-book": 59959, "lni-bookmark-alt": 59960, "lni-bookmark": 59961, "lni-bootstrap": 59962, "lni-bricks": 59963, "lni-bridge": 59964, "lni-briefcase": 59965, "lni-brush-alt": 59966, "lni-brush": 59967, "lni-bubble": 59968, "lni-bug": 59969, "lni-bulb": 59970, "lni-bullhorn": 59971, "lni-burger": 59972, "lni-bus": 59973, "lni-cake": 59974, "lni-calculator": 59975, "lni-calendar": 59976, "lni-camera": 59977, "lni-candy-cane": 59978, "lni-candy": 59979, "lni-capsule": 59980, "lni-car-alt": 59981, "lni-car": 59982, "lni-caravan": 59983, "lni-cart-full": 59984, "lni-cart": 59985, "lni-certificate": 59986, "lni-checkbox": 59987, "lni-checkmark-circle": 59988, "lni-checkmark": 59989, "lni-chef-hat": 59990, "lni-chevron-down-circle": 59991, "lni-chevron-down": 59992, "lni-chevron-left-circle": 59993, "lni-chevron-left": 59994, "lni-chevron-right-circle": 59995, "lni-chevron-right": 59996, "lni-chevron-up-circle": 59997, "lni-chevron-up": 59998, "lni-chrome": 59999, "lni-circle-minus": 60000, "lni-circle-plus": 60001, "lni-clipboard": 60002, "lni-close": 60003, "lni-cloud-check": 60004, "lni-cloud-download": 60005, "lni-cloud-network": 60006, "lni-cloud-sync": 60007, "lni-cloud-upload": 60008, "lni-cloud": 60009, "lni-cloudy-sun": 60010, "lni-code-alt": 60011, "lni-code": 60012, "lni-codepen": 60013, "lni-coffee-cup": 60014, "lni-cog": 60015, "lni-cogs": 60016, "lni-coin": 60017, "lni-comments-alt": 60018, "lni-comments-reply": 60019, "lni-comments": 60020, "lni-compass": 60021, "lni-construction-hammer": 60022, "lni-construction": 60023, "lni-consulting": 60024, "lni-control-panel": 60025, "lni-cpanel": 60026, "lni-creative-commons": 60027, "lni-credit-cards": 60028, "lni-crop": 60029, "lni-cross-circle": 60030, "lni-crown": 60031, "lni-css3": 60032, "lni-cup": 60033, "lni-customer": 60034, "lni-cut": 60035, "lni-dashboard": 60036, "lni-database": 60037, "lni-delivery": 60038, "lni-dev": 60039, "lni-diamond-alt": 60040, "lni-diamond": 60041, "lni-diners-club": 60042, "lni-dinner": 60043, "lni-direction-alt": 60044, "lni-direction-ltr": 60045, "lni-direction-rtl": 60046, "lni-direction": 60047, "lni-discord": 60048, "lni-discover": 60049, "lni-display-alt": 60050, "lni-display": 60051, "lni-docker": 60052, "lni-dollar": 60053, "lni-domain": 60054, "lni-download": 60055, "lni-dribbble": 60056, "lni-drop": 60057, "lni-dropbox-original": 60058, "lni-dropbox": 60059, "lni-drupal-original": 60060, "lni-drupal": 60061, "lni-dumbbell": 60062, "lni-edge": 60063, "lni-emoji-cool": 60064, "lni-emoji-friendly": 60065, "lni-emoji-happy": 60066, "lni-emoji-sad": 60067, "lni-emoji-smile": 60068, "lni-emoji-speechless": 60069, "lni-emoji-suspect": 60070, "lni-emoji-tounge": 60071, "lni-empty-file": 60072, "lni-enter": 60073, "lni-envato": 60074, "lni-envelope": 60075, "lni-eraser": 60076, "lni-euro": 60077, "lni-exit-down": 60078, "lni-exit-up": 60079, "lni-exit": 60080, "lni-eye": 60081, "lni-facebook-filled": 60082, "lni-facebook-messenger": 60083, "lni-facebook-original": 60084, "lni-facebook-oval": 60085, "lni-facebook": 60086, "lni-figma": 60087, "lni-files": 60088, "lni-firefox-original": 60089, "lni-firefox": 60090, "lni-fireworks": 60091, "lni-first-aid": 60092, "lni-flag-alt": 60093, "lni-flag": 60094, "lni-flags": 60095, "lni-flickr": 60096, "lni-basketball": 60097, "lni-behance": 60098, "lni-forward": 60099, "lni-frame-expand": 60100, "lni-flower": 60101, "lni-full-screen": 60102, "lni-funnel": 60103, "lni-gallery": 60104, "lni-game": 60105, "lni-gift": 60106, "lni-git": 60107, "lni-github-original": 60108, "lni-github": 60109, "lni-goodreads": 60110, "lni-google-drive": 60111, "lni-google-pay": 60112, "lni-fresh-juice": 60113, "lni-folder": 60114, "lni-bi-cycle": 60115, "lni-graph": 60116, "lni-grid-alt": 60117, "lni-grid": 60118, "lni-google-wallet": 60119, "lni-grow": 60120, "lni-hammer": 60121, "lni-hand": 60122, "lni-handshake": 60123, "lni-harddrive": 60124, "lni-headphone-alt": 60125, "lni-headphone": 60126, "lni-heart-filled": 60127, "lni-heart-monitor": 60128, "lni-heart": 60129, "lni-helicopter": 60130, "lni-helmet": 60131, "lni-help": 60132, "lni-highlight-alt": 60133, "lni-highlight": 60134, "lni-home": 60135, "lni-hospital": 60136, "lni-hourglass": 60137, "lni-html5": 60138, "lni-image": 60139, "lni-inbox": 60140, "lni-indent-decrease": 60141, "lni-indent-increase": 60142, "lni-infinite": 60143, "lni-information": 60144, "lni-instagram-filled": 60145, "lni-instagram-original": 60146, "lni-instagram": 60147, "lni-invention": 60148, "lni-graduation": 60149, "lni-invest-monitor": 60150, "lni-island": 60151, "lni-italic": 60152, "lni-java": 60153, "lni-javascript": 60154, "lni-jcb": 60155, "lni-joomla-original": 60156, "lni-joomla": 60157, "lni-jsfiddle": 60158, "lni-juice": 60159, "lni-key": 60160, "lni-keyboard": 60161, "lni-keyword-research": 60162, "lni-hacker-news": 60163, "lni-google": 60164, "lni-laravel": 60165, "lni-layers": 60166, "lni-layout": 60167, "lni-leaf": 60168, "lni-library": 60169, "lni-licencse": 60170, "lni-life-ring": 60171, "lni-line-dashed": 60172, "lni-line-dotted": 60173, "lni-line-double": 60174, "lni-line-spacing": 60175, "lni-line": 60176, "lni-lineicons-alt": 60177, "lni-lineicons": 60178, "lni-link": 60179, "lni-linkedin-original": 60180, "lni-linkedin": 60181, "lni-list": 60182, "lni-lock-alt": 60183, "lni-lock": 60184, "lni-magnet": 60185, "lni-magnifier": 60186, "lni-mailchimp": 60187, "lni-map-marker": 60188, "lni-map": 60189, "lni-mashroom": 60190, "lni-mastercard": 60191, "lni-medall-alt": 60192, "lni-medall": 60193, "lni-medium": 60194, "lni-laptop": 60195, "lni-investment": 60196, "lni-laptop-phone": 60197, "lni-megento": 60198, "lni-mic": 60199, "lni-microphone": 60200, "lni-menu": 60201, "lni-microscope": 60202, "lni-money-location": 60203, "lni-minus": 60204, "lni-mobile": 60205, "lni-more-alt": 60206, "lni-mouse": 60207, "lni-move": 60208, "lni-music": 60209, "lni-network": 60210, "lni-night": 60211, "lni-nodejs-alt": 60212, "lni-nodejs": 60213, "lni-notepad": 60214, "lni-npm": 60215, "lni-offer": 60216, "lni-opera": 60217, "lni-package": 60218, "lni-page-break": 60219, "lni-pagination": 60220, "lni-paint-bucket": 60221, "lni-paint-roller": 60222, "lni-pallet": 60223, "lni-paperclip": 60224, "lni-more": 60225, "lni-pause": 60226, "lni-paypal-original": 60227, "lni-microsoft": 60228, "lni-money-protection": 60229, "lni-pencil": 60230, "lni-paypal": 60231, "lni-pencil-alt": 60232, "lni-patreon": 60233, "lni-phone-set": 60234, "lni-phone": 60235, "lni-pin": 60236, "lni-pinterest": 60237, "lni-pie-chart": 60238, "lni-pilcrow": 60239, "lni-plane": 60240, "lni-play": 60241, "lni-plug": 60242, "lni-plus": 60243, "lni-pointer-down": 60244, "lni-pointer-left": 60245, "lni-pointer-right": 60246, "lni-pointer-up": 60247, "lni-play-store": 60248, "lni-pizza": 60249, "lni-postcard": 60250, "lni-pound": 60251, "lni-power-switch": 60252, "lni-printer": 60253, "lni-producthunt": 60254, "lni-protection": 60255, "lni-pulse": 60256, "lni-pyramids": 60257, "lni-python": 60258, "lni-pointer": 60259, "lni-popup": 60260, "lni-quotation": 60261, "lni-radio-button": 60262, "lni-rain": 60263, "lni-quora": 60264, "lni-react": 60265, "lni-question-circle": 60266, "lni-php": 60267, "lni-reddit": 60268, "lni-reload": 60269, "lni-restaurant": 60270, "lni-road": 60271, "lni-rocket": 60272, "lni-rss-feed": 60273, "lni-ruler-alt": 60274, "lni-ruler-pencil": 60275, "lni-ruler": 60276, "lni-rupee": 60277, "lni-save": 60278, "lni-school-bench-alt": 60279, "lni-school-bench": 60280, "lni-scooter": 60281, "lni-scroll-down": 60282, "lni-search-alt": 60283, "lni-search": 60284, "lni-select": 60285, "lni-seo": 60286, "lni-service": 60287, "lni-share-alt": 60288, "lni-share": 60289, "lni-shield": 60290, "lni-shift-left": 60291, "lni-shift-right": 60292, "lni-ship": 60293, "lni-shopify": 60294, "lni-shopping-basket": 60295, "lni-shortcode": 60296, "lni-shovel": 60297, "lni-shuffle": 60298, "lni-signal": 60299, "lni-sketch": 60300, "lni-skipping-rope": 60301, "lni-skype": 60302, "lni-slack": 60303, "lni-slice": 60304, "lni-slideshare": 60305, "lni-slim": 60306, "lni-reply": 60307, "lni-sort-alpha-asc": 60308, "lni-remove-file": 60309, "lni-sort-amount-dsc": 60310, "lni-sort-amount-asc": 60311, "lni-soundcloud": 60312, "lni-souncloud-original": 60313, "lni-spiner-solid": 60314, "lni-revenue": 60315, "lni-spinner": 60316, "lni-spellcheck": 60317, "lni-spotify": 60318, "lni-spray": 60319, "lni-sprout": 60320, "lni-snapchat": 60321, "lni-stamp": 60322, "lni-star-empty": 60323, "lni-star-filled": 60324, "lni-star-half": 60325, "lni-star": 60326, "lni-stats-down": 60327, "lni-spinner-arrow": 60328, "lni-steam": 60329, "lni-stackoverflow": 60330, "lni-stop": 60331, "lni-strikethrough": 60332, "lni-sthethoscope": 60333, "lni-stumbleupon": 60334, "lni-sun": 60335, "lni-support": 60336, "lni-surf-board": 60337, "lni-swift": 60338, "lni-syringe": 60339, "lni-tab": 60340, "lni-tag": 60341, "lni-target-customer": 60342, "lni-target-revenue": 60343, "lni-target": 60344, "lni-taxi": 60345, "lni-stats-up": 60346, "lni-telegram-original": 60347, "lni-telegram": 60348, "lni-text-align-center": 60349, "lni-text-align-justify": 60350, "lni-text-align-left": 60351, "lni-text-format-remove": 60352, "lni-text-align-right": 60353, "lni-text-format": 60354, "lni-thought": 60355, "lni-thumbs-down": 60356, "lni-thumbs-up": 60357, "lni-thunder-alt": 60358, "lni-thunder": 60359, "lni-ticket-alt": 60360, "lni-ticket": 60361, "lni-timer": 60362, "lni-train-alt": 60363, "lni-train": 60364, "lni-trash": 60365, "lni-travel": 60366, "lni-tree": 60367, "lni-trees": 60368, "lni-trello": 60369, "lni-trowel": 60370, "lni-tshirt": 60371, "lni-tumblr": 60372, "lni-twitch": 60373, "lni-twitter-filled": 60374, "lni-twitter-original": 60375, "lni-twitter": 60376, "lni-ubuntu": 60377, "lni-underline": 60378, "lni-unlink": 60379, "lni-unlock": 60380, "lni-upload": 60381, "lni-user": 60382, "lni-users": 60383, "lni-ux": 60384, "lni-vector": 60385, "lni-video": 60386, "lni-vimeo": 60387, "lni-visa": 60388, "lni-vk": 60389, "lni-volume-high": 60390, "lni-volume-low": 60391, "lni-volume-medium": 60392, "lni-volume-mute": 60393, "lni-volume": 60394, "lni-wallet": 60395, "lni-warning": 60396, "lni-website-alt": 60397, "lni-website": 60398, "lni-wechat": 60399, "lni-weight": 60400, "lni-whatsapp": 60401, "lni-wheelbarrow": 60402, "lni-wheelchair": 60403, "lni-windows": 60404, "lni-wordpress-filled": 60405, "lni-wordpress": 60406, "lni-world-alt": 60407, "lni-world": 60408, "lni-write": 60409, "lni-yahoo": 60410, "lni-ycombinator": 60411, "lni-yen": 60412, "lni-youtube": 60413, "lni-zip": 60414, "lni-zoom-in": 60415, "lni-zoom-out": 60416, "lni-teabag": 60417, "lni-stripe": 60418, "lni-spotify-original": 60419}';

		return json_decode($json, true);
	}
}
