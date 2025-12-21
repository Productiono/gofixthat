<?php
/**
 * WooCommerce theme cart
 *
 * @package Apparel
 */

remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cart_totals', 10 );
add_action( 'woocommerce_cart_collaterals', 'woocommerce_cart_totals', 10 );
add_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display', 20 );

/**
 * Add location for update mini cart
 *
 * @param array $fragments The cart fragments.
 */
function mbf_wc_update_mini_cart( $fragments ) {

	ob_start();

	$quantity = intval( WC()->cart->get_cart_contents_count() );
	?>
		<span class="mbf-shop-minicart__nav-headline">
			<span class="mbf-shop-minicart__nav-headline-label">
				<?php esc_html_e( 'Cart', 'apparel' ); ?>
			</span>

			<?php if ( $quantity ) { ?>
				<span class="mbf-shop-minicart__nav-headline-val">
					<span class="mbf-shop-minicart__nav-count"><?php echo esc_html( $quantity ); ?></span> <?php esc_html_e( 'Items', 'apparel' ); ?>
				</span>
			<?php } ?>
		</span>
	<?php

	$fragments['span.mbf-shop-minicart__nav-headline'] = ob_get_clean();

	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'mbf_wc_update_mini_cart', 10, 1 );

/**
 * Add colspan to product name.
 */
add_action( 'woocommerce_before_cart', function() {
	ob_start(
		function( $html ) {
			$html = str_replace( '<th class="product-name">', '<th class="product-name" colspan="2">', $html );
			return $html;
		}
	);
} );

add_action( 'woocommerce_after_cart', function() {
	ob_end_flush();
}, 0 );

/**
 * Add title to cart
 */
function mbf_wc_cart_title() {
	?>
		<h1><?php the_title(); ?></h1>
	<?php
}
add_action( 'woocommerce_before_cart_table', 'mbf_wc_cart_title', 10 );

/**
 * Add wrap start to cart
 */
function mbf_wc_cart_wrap_start() {
	?>
		<div class="woocommerce-cart-wrap">
	<?php
}
add_action( 'woocommerce_before_cart', 'mbf_wc_cart_wrap_start', 99 );

/**
 * Add wrap end to cart
 */
function mbf_wc_cart_wrap_end() {
	?>
		</div>
	<?php
}
add_action( 'woocommerce_after_cart', 'mbf_wc_cart_wrap_end', 0 );

/**
 * Add related products
 */
function mbf_wc_cart_related_products() {
	get_template_part( 'woocommerce/cart-related-products' );
}
add_action( 'woocommerce_after_cart', 'mbf_wc_cart_related_products' );
add_action( 'woocommerce_cart_is_empty_end', 'mbf_wc_cart_related_products' );

/**
 * Add delete link to cart item name
 *
 * @param array  $cart_item     The cart item.
 * @param string $cart_item_key The cart item key.
 */
function mbf_wc_cart_delete( $cart_item, $cart_item_key ) {
	/**
	 * The woocommerce_cart_item_product hook.
	 *
	 * @since 1.0.0
	 */
	$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

	/**
	 * The woocommerce_cart_item_product_id hook.
	 *
	 * @since 1.0.0
	 */
	$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
	?>
		<span class="product-delete">
			<?php
				echo sprintf(
					'<a href="%s" class="delete" aria-label="%s" data-product_id="%s" data-product_sku="%s">%s</a>',
					esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
					esc_html__( 'Delete this item', 'apparel' ),
					esc_attr( $product_id ),
					esc_attr( $_product->get_sku() ),
					esc_html__( 'Delete', 'apparel' )
				);
			?>
		</span>
	<?php
}
add_action( 'woocommerce_after_cart_item_name', 'mbf_wc_cart_delete', 20, 2 );
