<?php
/**
 * WooCommerce minicart helpers.
 *
 * Opens the theme side cart automatically after add to cart.
 *
 * @package Apparel
 */

if ( ! function_exists( 'mbf_minicart_set_open_flag' ) ) {
	/**
	 * Flag minicart to open on next page load (non-AJAX add to cart).
	 *
	 * @param int   $cart_item_key Cart item key.
	 * @param int   $product_id    Product ID.
	 * @param int   $quantity      Quantity.
	 * @param int   $variation_id  Variation ID.
	 * @param array $variation     Variation data.
	 * @param array $cart_item_data Cart item data.
	 */
	function mbf_minicart_set_open_flag( $cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data ) {
		if ( null === WC()->session ) {
			return;
		}

		WC()->session->set( 'mbf_open_minicart', true );
	}
}
add_action( 'woocommerce_add_to_cart', 'mbf_minicart_set_open_flag', 10, 6 );

if ( ! function_exists( 'mbf_minicart_enqueue_script' ) ) {
	/**
	 * Enqueue inline script to open minicart after add to cart.
	 */
	function mbf_minicart_enqueue_script() {
		if ( wp_script_is( 'mbf-scripts', 'enqueued' ) ) {
			$open_on_load = false;

			if ( null !== WC()->session ) {
				$open_on_load = (bool) WC()->session->get( 'mbf_open_minicart', false );

				if ( $open_on_load ) {
					WC()->session->set( 'mbf_open_minicart', false );
				}
			}

			wp_localize_script(
				'mbf-scripts',
				'mbfMiniCartData',
				array(
					'openOnLoad' => $open_on_load,
				)
			);

			$inline = "(function($, body){\n\tvar activateMiniCart = function(){\n\t\tif(!body.hasClass('mbf-shop-minicart-active')){\n\t\t\tbody.addClass('mbf-shop-minicart-transition');\n\t\t\tbody.addClass('mbf-shop-minicart-active');\n\t\t\tsetTimeout(function(){ body.removeClass('mbf-shop-minicart-transition'); }, 400);\n\t\t}\n\t};\n\n\t$(document.body).on('added_to_cart', function(){\n\t\tactivateMiniCart();\n\t});\n\n\t$(function(){\n\t\tif (window.mbfMiniCartData && window.mbfMiniCartData.openOnLoad){\n\t\t\tactivateMiniCart();\n\t\t}\n\t});\n})(jQuery, jQuery('body'))";

			wp_add_inline_script( 'mbf-scripts', $inline );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'mbf_minicart_enqueue_script', 20 );
