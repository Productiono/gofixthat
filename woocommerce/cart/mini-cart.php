<?php
/**
 * Mini-cart
 *
 * Contains the markup for the mini-cart, used by the cart widget.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/mini-cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 10.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * The woocommerce_before_mini_cart hook.
 *
 * @since 1.0.0
 */
do_action( 'woocommerce_before_mini_cart' );
?>

<?php if ( WC()->cart && ! WC()->cart->is_empty() ) : ?>

	<ul class="woocommerce-mini-cart cart_list product_list_widget <?php echo esc_attr( $args['list_class'] ); ?>">
		<?php
		/**
		 * The woocommerce_before_mini_cart_contents hook.
		 *
		 * @since 1.0.0
		 */
		do_action( 'woocommerce_before_mini_cart_contents' );

		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
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

			/**
			 * The woocommerce_widget_cart_item_visible hook.
			 *
			 * @since 1.0.0
			 */
			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
				/**
				 * This filter is documented in woocommerce/templates/cart/cart.php.
				 *
				 * @since 2.1.0
				 */
				$product_name = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );

				/**
				 * The woocommerce_cart_item_thumbnail hook.
				 *
				 * @since 1.0.0
				 */
				$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

				/**
				 * The woocommerce_cart_item_price hook.
				 *
				 * @since 1.0.0
				 */
				$product_price = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );

				/**
				 * The woocommerce_cart_item_permalink hook.
				 *
				 * @since 1.0.0
				 */
				$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );

				/**
				 * The woocommerce_mini_cart_item_class hook.
				 *
				 * @since 1.0.0
				 */
				$cart_item_class = apply_filters( 'woocommerce_mini_cart_item_class', 'mini_cart_item', $cart_item, $cart_item_key );
				?>
				<li class="woocommerce-mini-cart-item <?php echo esc_attr( apply_filters( 'woocommerce_mini_cart_item_class', 'mini_cart_item', $cart_item, $cart_item_key ) ); ?>">
					<?php if ( empty( $product_permalink ) ) { ?>
						<div class="woocommerce-mini-cart-item-thumbnail">
							<?php call_user_func( 'printf', '%s', $thumbnail ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</div>
					<?php } else { ?>
						<div class="woocommerce-mini-cart-item-thumbnail">
							<a href="<?php echo esc_url( $product_permalink ); ?>">
								<?php call_user_func( 'printf', '%s', $thumbnail ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</a>
						</div>
					<?php } ?>

					<div class="woocommerce-mini-cart-item-content">
						<div class="woocommerce-mini-cart-item-product-name">
							<?php if ( empty( $product_permalink ) ) { ?>
								<span><?php call_user_func( 'printf', '%s', wp_kses_post( $product_name ) ); ?></span>
							<?php } else { ?>
								<a href="<?php echo esc_url( $product_permalink ); ?>">
									<span><?php call_user_func( 'printf', '%s', wp_kses_post( $product_name ) ); ?></span>
								</a>
							<?php } ?>
						</div>

						<div class="woocommerce-mini-cart-item-product-quantity">
							<?php call_user_func( 'printf', '%s', wc_get_formatted_cart_item_data( $cart_item ) ); ?>

							<?php
							/**
							 * The woocommerce_widget_cart_item_quantity hook.
							 *
							 * @since 1.0.0
							 */
							call_user_func( 'printf', '%s', apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="quantity">' . sprintf( '%s &times; %s', $cart_item['quantity'], $product_price ) . '</span>', $cart_item, $cart_item_key ) );
							?>
						</div>

						<?php
						echo sprintf(
								'<a href="%s" class="remove remove_from_cart_button remove_from_cart_button-link" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s">%s</a>',
								esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
								esc_attr__( 'Remove this item', 'apparel' ),
								esc_attr( $product_id ),
								esc_attr( $cart_item_key ),
								esc_attr( $_product->get_sku() ),
								esc_html__( 'Delete', 'apparel' )
						);
						?>
					</div>
				</li>
				<?php
			}
		}

		/**
		 * The woocommerce_mini_cart_contents hook.
		 *
		 * @since 1.0.0
		 */
		do_action( 'woocommerce_mini_cart_contents' );
		?>
	</ul>

	<p class="woocommerce-mini-cart__total total">
		<?php
		/**
		 * Hook: woocommerce_widget_shopping_cart_total.
		 *
		 * @hooked woocommerce_widget_shopping_cart_subtotal - 10
		 *
		 * @since 1.0.0
		 */
		do_action( 'woocommerce_widget_shopping_cart_total' );
		?>
	</p>

	<?php
	/**
	 * The woocommerce_widget_shopping_cart_before_buttons hook.
	 *
	 * @since 1.0.0
	 */
	do_action( 'woocommerce_widget_shopping_cart_before_buttons' );
	?>

	<p class="woocommerce-mini-cart__buttons buttons">
		<?php
		/**
		 * The woocommerce_widget_shopping_cart_buttons hook.
		 *
		 * @since 1.0.0
		 */
		do_action( 'woocommerce_widget_shopping_cart_buttons' );
		?>
	</p>

	<?php
	/**
	 * The woocommerce_widget_shopping_cart_after_buttons hook.
	 *
	 * @since 1.0.0
	 */
	do_action( 'woocommerce_widget_shopping_cart_after_buttons' );
	?>

<?php else : ?>

	<p class="woocommerce-mini-cart__empty-message"><?php esc_html_e( 'No products in the cart.', 'apparel' ); ?></p>

<?php endif; ?>

<?php
/**
 * The woocommerce_after_mini_cart hook.
 *
 * @since 1.0.0
 */
do_action( 'woocommerce_after_mini_cart' );
?>
