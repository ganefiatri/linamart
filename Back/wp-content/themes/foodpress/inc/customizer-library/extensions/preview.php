<?php
/**
 * Customizer Utility Functions
 *
 * @package 	Customizer_Library
 * @author		Devin Price, The Theme Foundry
 */

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function customizer_library_customize_preview_jss() {

	$path = str_replace( wp_normalize_path( WP_CONTENT_DIR ), WP_CONTENT_URL, wp_normalize_path( dirname( dirname( __FILE__ ) ) ) );

	$te = wp_enqueue_script( 'customizer-library-customizer', $path . '/js/customizer.js', array( 'customize-preview-widgets' ), strtotime('now'), true );

}
function customizer_library_customize_preview_js() {

	add_action( 'wp_enqueue_scripts', 'customizer_library_customize_preview_jss' );

}
add_action( 'customize_preview_init', 'customizer_library_customize_preview_js' );

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function customizer_library_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
}
add_action( 'customize_register', 'customizer_library_customize_register' );
