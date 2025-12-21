<?php
/**
 * Register block styles
 *
 * @package Apparel
 */

/**
 * Register block styles.
 */
function mbf_register_block_styles() {
	if ( function_exists( 'register_block_style' ) ) {

		/**
		 * Display
		 * -----------------------------
		 */

		register_block_style( 'core/group', array(
			'name'  => 'mbf-group-hide-tablet',
			'label' => esc_html__( 'Hide Tablet', 'apparel' ),
		) );

		register_block_style( 'core/group', array(
			'name'  => 'mbf-group-hide-mobile',
			'label' => esc_html__( 'Hide Mobile', 'apparel' ),
		) );

		register_block_style( 'core/group', array(
			'name'  => 'mbf-group-only-light',
			'label' => esc_html__( 'Only in Light', 'apparel' ),
		) );

		register_block_style( 'core/group', array(
			'name'  => 'mbf-group-only-dark',
			'label' => esc_html__( 'Only in Dark', 'apparel' ),
		) );

		/**
		 * Heading
		 * -----------------------------
		 */

		register_block_style( 'core/heading', array(
			'name'  => 'mbf-heading-large',
			'label' => esc_html__( 'Large', 'apparel' ),
		) );

		register_block_style( 'core/heading', array(
			'name'  => 'mbf-heading-medium',
			'label' => esc_html__( 'Medium', 'apparel' ),
		) );

		register_block_style( 'core/heading', array(
			'name'  => 'mbf-heading-title',
			'label' => esc_html__( 'Title', 'apparel' ),
		) );

		register_block_style( 'core/heading', array(
			'name'  => 'mbf-heading-without-margin',
			'label' => esc_html__( 'Without Margin', 'apparel' ),
		) );

		/**
		 * Group
		 * -----------------------------
		 */

		register_block_style( 'core/group', array(
			'name'  => 'mbf-group-fullwidth',
			'label' => esc_html__( 'Fullwidth', 'apparel' ),
		) );

		register_block_style( 'core/group', array(
			'name'  => 'mbf-group-container',
			'label' => esc_html__( 'Container', 'apparel' ),
		) );

		register_block_style( 'core/group', array(
			'name'  => 'mbf-group-fullheight',
			'label' => esc_html__( 'Fullheight', 'apparel' ),
		) );

		register_block_style( 'core/group', array(
			'name'  => 'mbf-group-side-spacer',
			'label' => esc_html__( 'Side Spacer', 'apparel' ),
		) );

		register_block_style( 'core/group', array(
			'name'  => 'mbf-group-double-columns',
			'label' => esc_html__( 'Double Columns', 'apparel' ),
		) );

		register_block_style('core/group', array(
			'name'  => 'mbf-group-post-meta',
			'label' => esc_html__( 'Post Meta', 'apparel')
			)
		);

		register_block_style( 'core/group', array(
			'name'  => 'mbf-group-post-content',
			'label' => esc_html__( 'Post Content', 'apparel' ),
		) );

		register_block_style( 'core/group', array(
			'name'  => 'mbf-group-max-400px',
			'label' => esc_html__( 'Max 400px', 'apparel' ),
		) );

		register_block_style( 'core/group', array(
			'name'  => 'mbf-group-max-500px',
			'label' => esc_html__( 'Max 500px', 'apparel' ),
		) );

		register_block_style( 'core/group', array(
			'name'  => 'mbf-group-max-600px',
			'label' => esc_html__( 'Max 600px', 'apparel' ),
		) );

		/**
		 * Columns
		 * -----------------------------
		 */

		register_block_style( 'core/columns', array(
			'name'  => 'mbf-columns-6-style',
			'label' => esc_html__( '6 Columns', 'apparel' ),
		) );

		register_block_style( 'core/columns', array(
			'name'  => 'mbf-columns-4-style',
			'label' => esc_html__( '4 Columns', 'apparel' ),
		) );

		register_block_style( 'core/columns', array(
			'name'  => 'mbf-columns-gap-0px',
			'label' => esc_html__( 'Gap 0', 'apparel' ),
		) );

		register_block_style( 'core/columns', array(
			'name'  => 'mbf-columns-gap-2px',
			'label' => esc_html__( 'Gap 2px', 'apparel' ),
		) );

		register_block_style( 'core/columns', array(
			'name'  => 'mbf-columns-gap-xs-1',
			'label' => esc_html__( 'Gap XS 1', 'apparel' ),
		) );

		register_block_style( 'core/columns', array(
			'name'  => 'mbf-columns-gap-xs-2',
			'label' => esc_html__( 'Gap XS 2', 'apparel' ),
		) );

		register_block_style( 'core/columns', array(
			'name'  => 'mbf-columns-gap-xs-3',
			'label' => esc_html__( 'Gap XS 3', 'apparel' ),
		) );

		/**
		 * Cover
		 * -----------------------------
		 */

		register_block_style( 'core/cover', array(
			'name'  => 'mbf-cover-wide',
			'label' => esc_html__( 'Wide', 'apparel' ),
		) );

		register_block_style( 'core/cover', array(
			'name'  => 'mbf-cover-landscape',
			'label' => esc_html__( 'Landscape', 'apparel' ),
		) );

		register_block_style( 'core/cover', array(
			'name'  => 'mbf-cover-featured',
			'label' => esc_html__( 'Featured', 'apparel' ),
		) );

		register_block_style( 'core/cover', array(
			'name'  => 'mbf-cover-promo',
			'label' => esc_html__( 'Promo', 'apparel' ),
		) );

		/**
		 * Posts
		 * -----------------------------
		 */

		register_block_style( 'core/query', array(
			'name'  => 'mbf-query-fullheight',
			'label' => esc_html__( 'Fullheight', 'apparel' ),
		) );

		register_block_style( 'core/post-template', array(
			'name'  => 'mbf-post-template-overlay-landscape',
			'label' => esc_html__( 'Overlay Landscape', 'apparel' ),
		) );

		register_block_style( 'core/post-template', array(
			'name'  => 'mbf-post-template-overlay-portrait',
			'label' => esc_html__( 'Overlay Portrait', 'apparel' ),
		) );

		register_block_style( 'core/post-template', array(
			'name'  => 'mbf-post-template-horizontal',
			'label' => esc_html__( 'Horizontal', 'apparel' ),
		) );

		register_block_style( 'core/post-featured-image', array(
			'name'  => 'mbf-post-featured-image-landscape',
			'label' => esc_html__( 'Landscape', 'apparel' ),
		) );

		register_block_style( 'core/post-featured-image', array(
			'name'  => 'mbf-post-featured-image-square',
			'label' => esc_html__( 'Square', 'apparel' ),
		) );

		register_block_style( 'core/post-featured-image', array(
			'name'  => 'mbf-post-featured-image-portrait',
			'label' => esc_html__( 'Portrait', 'apparel' ),
		) );

		register_block_style( 'core/post-title', array(
			'name'  => 'mbf-post-title-fill',
			'label' => esc_html__( 'Fill', 'apparel' ),
		) );

		/**
		 * Buttons
		 * -----------------------------
		 */

		register_block_style( 'core/buttons', array(
			'name'  => 'mbf-buttons-fullheight',
			'label' => esc_html__( 'Fullheight', 'apparel' ),
		) );

		/**
		 * Image
		 * -----------------------------
		 */

		register_block_style( 'core/image', array(
			'name'  => 'mbf-image-auto-scheme',
			'label' => esc_html__( 'Auto Scheme', 'apparel' ),
		) );

		register_block_style( 'core/image', array(
			'name'  => 'mbf-image-without-radius',
			'label' => esc_html__( 'Without Radius', 'apparel' ),
		) );

		/**
		 * Paragraph
		 * -----------------------------
		 */

		register_block_style( 'core/paragraph', array(
			'name'  => 'mbf-paragraph-label',
			'label' => esc_html__( 'Label', 'apparel' ),
		) );

		register_block_style( 'core/paragraph', array(
			'name'  => 'mbf-paragraph-without-margin',
			'label' => esc_html__( 'Without Margin', 'apparel' ),
		) );

		/**
		 * Separator
		 * -----------------------------
		 */

		 register_block_style( 'core/separator', array(
			'name'  => 'mbf-separator-easy-line',
			'label' => esc_html__( 'Easy Line', 'apparel' ),
		) );

		/**
		 * Elements
		 * -----------------------------
		 */

		// Top

		register_block_style( 'core/group', array(
			'name'  => 'mbf-layout-margin-1',
			'label' => esc_html__( 'Top 1', 'apparel' ),
		) );

		register_block_style( 'core/group', array(
			'name'  => 'mbf-layout-margin-2',
			'label' => esc_html__( 'Top 2', 'apparel' ),
		) );

		register_block_style( 'core/group', array(
			'name'  => 'mbf-layout-margin-3',
			'label' => esc_html__( 'Top 3', 'apparel' ),
		) );

		register_block_style( 'core/group', array(
			'name'  => 'mbf-layout-margin-4',
			'label' => esc_html__( 'Top 4', 'apparel' ),
		) );

		register_block_style( 'core/group', array(
			'name'  => 'mbf-layout-margin-5',
			'label' => esc_html__( 'Top 5', 'apparel' ),
		) );

		register_block_style( 'core/group', array(
			'name'  => 'mbf-layout-margin-6',
			'label' => esc_html__( 'Top 6', 'apparel' ),
		) );

		// Top small (minus)

		register_block_style( 'core/group', array(
			'name'  => 'mbf-layout-margin-minus-1',
			'label' => esc_html__( 'Top 1 small', 'apparel' ),
		) );

		register_block_style( 'core/group', array(
			'name'  => 'mbf-layout-margin-minus-2',
			'label' => esc_html__( 'Top 2 small', 'apparel' ),
		) );

		register_block_style( 'core/group', array(
			'name'  => 'mbf-layout-margin-minus-3',
			'label' => esc_html__( 'Top 3 small', 'apparel' ),
		) );

		// Bottom

		register_block_style( 'core/group', array(
			'name'  => 'mbf-layout-margin-bottom-1',
			'label' => esc_html__( 'Bottom 1', 'apparel' ),
		) );

		register_block_style( 'core/group', array(
			'name'  => 'mbf-layout-margin-bottom-2',
			'label' => esc_html__( 'Bottom 2', 'apparel' ),
		) );

		register_block_style( 'core/group', array(
			'name'  => 'mbf-layout-margin-bottom-3',
			'label' => esc_html__( 'Bottom 3', 'apparel' ),
		) );

		register_block_style( 'core/group', array(
			'name'  => 'mbf-layout-margin-bottom-4',
			'label' => esc_html__( 'Bottom 4', 'apparel' ),
		) );

		register_block_style( 'core/group', array(
			'name'  => 'mbf-layout-margin-bottom-5',
			'label' => esc_html__( 'Bottom 5', 'apparel' ),
		) );

		// Bottom small (minus)

		register_block_style( 'core/group', array(
			'name'  => 'mbf-layout-margin-bottom-minus-1',
			'label' => esc_html__( 'Bottom 1 small', 'apparel' ),
		) );

		register_block_style( 'core/group', array(
			'name'  => 'mbf-layout-margin-bottom-minus-2',
			'label' => esc_html__( 'Bottom 2 small', 'apparel' ),
		) );

		register_block_style( 'core/group', array(
			'name'  => 'mbf-layout-margin-bottom-minus-3',
			'label' => esc_html__( 'Bottom 3 small', 'apparel' ),
		) );

		/**
		 * Woocommerce
		 * -----------------------------
		 */

		register_block_style( 'woocommerce/featured-category', array(
			'name'  => 'mbf-featured-category-simple',
			'label' => esc_html__( 'Simple', 'apparel' ),
		) );

		register_block_style( 'woocommerce/product-categories', array(
			'name'  => 'mbf-product-categories-checkboxes',
			'label' => esc_html__( 'Checkboxes', 'apparel' ),
		) );

		register_block_style( 'woocommerce/attribute-filter', array(
			'name'  => 'mbf-attribute-filter-buttons',
			'label' => esc_html__( 'Buttons', 'apparel' ),
		) );
	}
}
add_action( 'init', 'mbf_register_block_styles' );
