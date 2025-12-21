<?php
/**
 * WooCommerce assets
 *
 * @package Apparel
 */

/**
 * Enqueues WooCommerce assets.
 */
function mbf_wc_enqueue_scripts() {
	$theme = wp_get_theme();

	$version = $theme->get( 'Version' );

	// Register WooCommerce styles.
	wp_register_style( 'mbf_css_wc', mbf_style( get_template_directory_uri() . '/assets/css/woocommerce.css' ), array(), $version );

	wp_add_inline_style( 'mbf_css_wc', ':root {
		--mbf-wc-label-products: "' . esc_html__( 'Products', 'apparel' ) . '";
		--mbf-wc-label-cart: "' . esc_html__( 'Cart', 'apparel' ) . '";
		--mbf-wc-label-cart-totals: "' . esc_html__( 'Cart totals', 'apparel' ) . '";
		--mbf-wc-label-delete: "' . esc_html__( 'Delete', 'apparel' ) . '";
		--mbf-wc-label-general: "' . esc_html__( 'General', 'apparel' ) . '";
		--mbf-wc-label-shipping-address: "' . esc_html__( 'Shipping Address', 'apparel' ) . '";
		--mbf-wc-label-order-notes: "' . esc_html__( 'Order Notes', 'apparel' ) . '";
		--mbf-wc-label-your-orders: "' . esc_html__( 'Your Orders', 'apparel' ) . '";
		--mbf-wc-label-your-downloads: "' . esc_html__( 'Your Downloads', 'apparel' ) . '";
		--mbf-wc-label-account-details: "' . esc_html__( 'Account Details', 'apparel' ) . '";
		--mbf-wc-label-quantity: "' . esc_html__( 'Quantity', 'apparel' ) . '";
	}' );

	// Enqueue WooCommerce styles.
	wp_enqueue_style( 'mbf_css_wc' );

	// Add RTL support.
	wp_style_add_data( 'mbf_css_wc', 'rtl', 'replace' );

	// Remove selectWoo.
	wp_dequeue_style( 'selectWoo' );
	wp_dequeue_script( 'selectWoo' );
}
add_action( 'wp_enqueue_scripts', 'mbf_wc_enqueue_scripts' );
