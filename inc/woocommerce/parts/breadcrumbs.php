<?php
/**
 * WooCommerce theme breadcrumbs
 *
 * @package Apparel
 */

/**
 * Change the breadcrumb delimiter
 *
 * @param array $defaults The defaults.
 */
function wcc_change_breadcrumb_delimiter( $defaults ) {

	$defaults['delimiter'] = ' <span class="mbf-separator"></span> ';

	return $defaults;
}
add_filter( 'woocommerce_breadcrumb_defaults', 'wcc_change_breadcrumb_delimiter' );

/**
 * Add support WC provider to breadcrumbs
 *
 * @param string $output The html breadcrumbs.
 */
function mbf_wc_provider_breadcrumbs( $output ) {

	if ( ! $output && function_exists( 'woocommerce_breadcrumb' ) ) {

		ob_start();

		woocommerce_breadcrumb();

		$output = ob_get_clean();
	}

	return $output;
}
add_filter( 'mbf_breadcrumbs', 'mbf_wc_provider_breadcrumbs' );

/**
 * Reassign default breadcrumbs
 */
function mbf_wc_reassign_breadcrumbs() {
	remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
}
add_action( 'template_redirect', 'mbf_wc_reassign_breadcrumbs' );
