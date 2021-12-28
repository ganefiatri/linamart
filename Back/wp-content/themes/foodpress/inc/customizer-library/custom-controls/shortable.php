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

class Customizer_Library_Shortable_Control extends WP_Customize_Control {

	/**
	 * Control Type
	 */
	public $type = 'shortable';

	/**
	 * Enqueue Scripts
	 */
	public function enqueue() {
		wp_enqueue_script( 'customizer-shortable-control', $this->abs_path_to_url( dirname( __FILE__ ) . '/js/customizer-shortable-control.js' ), array( 'jquery' ), rand(), true );
		wp_enqueue_style( 'customizer-shortable-control', $this->abs_path_to_url( dirname( __FILE__ ) . '/css/customizer-shortable-control.css' ), array(), rand() );
	}

	/**
	 * Render Settings
	 */
	public function render_content() {

		/* if no choices, bail. */
		if ( empty( $this->choices ) ){
			return;
		} ?>

		<?php if ( !empty( $this->label ) ){ ?>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
		<?php } // add label if needed. ?>

		<?php if ( !empty( $this->description ) ){ ?>
			<span class="description customize-control-description"><?php echo $this->description; ?></span>
		<?php } // add desc if needed. ?>

		<?php
		/* Data */
		$values = explode( ',', $this->value() );
		$choices = $this->choices;

		/* If values exist, use it. */
		$options = array();
		if( $values ){

			/* get individual item */
			foreach( $values as $value ){

				/* separate item with option */
				$value = explode( ':', $value );

				/* build the array. remove options not listed on choices. */
				if ( array_key_exists( $value[0], $choices ) ){
					$options[$value[0]] = $value[1] ? '1' : '0';
				}
			}
		}
		/* if there's new options (not saved yet), add it in the end. */
		foreach( $choices as $key => $val ){

			/* if not exist, add it in the end. */
			if ( ! array_key_exists( $key, $options ) ){
				$options[$key] = '0'; // use zero to deactivate as default for new items.
			}
		}
		?>

		<ul class="sortable-list">

			<?php foreach ( $options as $key => $value ){ ?>

				<li>
					<label>
						<input name="<?php echo esc_attr( $key ); ?>" class="sortable-item" type="checkbox" value="<?php echo esc_html( $value ); ?>" <?php checked( $value ); ?> />
						<?php echo esc_html( $choices[$key] ); ?>
					</label>
					<i class="dashicons dashicons-menu sortable-handle"></i>
				</li>

			<?php } // end choices. ?>

				<li class="sortable-list-hide">
					<input type="hidden" class="sortable" <?php $this->link(); ?> value="<?php echo esc_attr( $this->value() ); ?>" />
				</li>

		</ul>


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
}
