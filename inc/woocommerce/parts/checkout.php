<?php
/**
 * WooCommerce checkout customizations.
 *
 * @package Apparel
 */

/**
 * Always return the theme's checkout template when available.
 *
 * This defends the custom layout against third-party template loaders and
 * ensures WooCommerce updates continue to use the theme override.
 *
 * @param string $located       The resolved template path.
 * @param string $template_name The requested template name.
 * @param string $template_path The template folder path.
 *
 * @return string
 */
function mbf_wc_locate_checkout_template( $located, $template_name, $template_path ) {

	if ( 'checkout/form-checkout.php' !== $template_name ) {
		return $located;
	}

	$custom_template = get_theme_file_path( '/woocommerce/checkout/form-checkout.php' );

	if ( file_exists( $custom_template ) ) {
		return $custom_template;
	}

	return $located;
}
add_filter( 'woocommerce_locate_template', 'mbf_wc_locate_checkout_template', 20, 3 );
