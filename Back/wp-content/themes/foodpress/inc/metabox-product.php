<?php
/**
 * FoodPress metabox function
 * @package FoodPress/inc
 * @author Taufik Hidayat <taufik@fiqhidayat.com>
 */

add_action( 'cmb2_admin_init', 'foodpress_product_metabox' );
/**
 * Hook in and add a demo metabox. Can only happen on the 'cmb2_admin_init' or 'cmb2_init' hook.
 */
function foodpress_product_metabox() {
 	/**
 	 * Sample metabox to demonstrate each field type included
 	 */
 	$cmb = new_cmb2_box( array(
 		'id'            => 'foodpress_product_metabox',
 		'title'         => esc_html__( 'Product Attribution', 'foodpress' ),
 		'object_types'  => array( 'product' ), // Post type
 		// 'show_on_cb' => 'yourprefix_show_if_front_page', // function should return a bool value
 		// 'context'    => 'normal',
 		// 'priority'   => 'high',
 		// 'show_names' => true, // Show field names on the left
 		// 'cmb_styles' => false, // false to disable the CMB stylesheet
 		// 'closed'     => true, // true to keep the metabox closed by default
 		// 'classes'    => 'extra-class', // Extra cmb2-wrap classes
 		// 'classes_cb' => 'yourprefix_add_some_classes', // Add classes through a callback.

 		/*
 		 * The following parameter is any additional arguments passed as $callback_args
 		 * to add_meta_box, if/when applicable.
 		 *
 		 * CMB2 does not use these arguments in the add_meta_box callback, however, these args
 		 * are parsed for certain special properties, like determining Gutenberg/block-editor
 		 * compatibility.
 		 *
 		 * Examples:
 		 *
 		 * - Make sure default editor is used as metabox is not compatible with block editor
 		 *      [ '__block_editor_compatible_meta_box' => false/true ]
 		 *
 		 * - Or declare this box exists for backwards compatibility
 		 *      [ '__back_compat_meta_box' => false ]
 		 *
 		 * More: https://wordpress.org/gutenberg/handbook/extensibility/meta-box/
 		 */
 		// 'mb_callback_args' => array( '__block_editor_compatible_meta_box' => false ),
 	) );

 	$cmb->add_field( array(
 		'name'       => esc_html__( 'Price', 'foodpress' ),
 		'desc'       => esc_html__( 'example : 20000 for (dua puluh ribu)', 'foodpress' ),
 		'id'         => 'product_price',
 		'type'       => 'text',
 		'attributes' => array(
 			'type' => 'number',
 			'pattern' => '\d*',
 		),
 		'sanitization_cb' => 'absint',
 		'escape_cb'       => 'absint',
 	) );

    $cmb->add_field( array(
        'name'       => esc_html__( 'Weight', 'foodpress' ),
        'desc'       => esc_html__( 'Grams Unit', 'foodpress' ),
        'id'         => 'product_weight',
        'type'       => 'text',
        'attributes' => array(
            'type' => 'number',
            'pattern' => '\d*',
        ),
        'default' => 1000,
        // 'sanitization_cb' => 'absint',
        // 'escape_cb'       => 'absint',
    ) );

 	$cmb->add_field( array(
 		'name' => esc_html__( 'Promo', 'foodpress' ),
 		'desc' => esc_html__( 'check if this is promo product', 'foodpress' ),
 		'id'   => 'product_promo',
 		'type' => 'checkbox',
 	) );

 	$cmb->add_field( array(
 		'name'       => esc_html__( 'Promo Price', 'foodpress' ),
 		'desc'       => esc_html__( 'example : 15000 for (Lima belas ribu)', 'foodpress' ),
 		'id'         => 'product_promo_price',
 		'type'       => 'text',
 		'attributes' => array(
 			'type' => 'number',
 			'pattern' => '\d*',
 			'data-conditional-id' => 'product_promo',
 		),
 		'sanitization_cb' => 'absint',
 		'escape_cb'       => 'absint',
 	) );

 	$cmb->add_field( array(
 		'name' => esc_html__( 'Is Out of Stock', 'foodpress' ),
 		'desc' => esc_html__( 'check if this product is out of stock', 'foodpress' ),
 		'id'   => 'product_is_out_of_stock',
 		'type' => 'checkbox',
 	) );

}



add_action( 'cmb2_admin_init', 'foodpress_product_catgeory_register_taxonomy_metabox' );
function foodpress_product_catgeory_register_taxonomy_metabox() {

	/**
	 * Metabox to add fields to categories and tags
	 */
	$cmb = new_cmb2_box( array(
		'id'               => 'foodpress_product_store',
		'title'            => esc_html__( 'Category Metabox', 'foodpress' ), // Doesn't output for term boxes
		'object_types'     => array( 'term' ), // Tells CMB2 to use term_meta vs post_meta
		'taxonomies'       => array( 'product-category'), // Tells CMB2 which taxonomies should have these fields
		// 'new_term_section' => true, // Will display in the "Add New Category" section
	) );

	$cmb->add_field( array(
		'name' => esc_html__( 'Category Image', 'foodpress' ),
		'id'   => 'foodpress_category_image',
		'description' => 'Image must be 200px X 64 size',
		'type' => 'file',
		'query_args' => array(
			'type' => array(
				'image/gif',
				'image/jpeg',
				'image/png',
			),
		),
	) );

}
