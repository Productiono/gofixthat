<?php
/**
 * The template part for displaying shop mini cart area.
 *
 * @package Apparel
 */

if ( ! get_theme_mod( 'woocommerce_header_hide_icon', false ) ) {

	$quantity = intval( WC()->cart->get_cart_contents_count() );
	?>

	<div class="mbf-shop-minicart-overlay"></div>

	<div class="mbf-shop-minicart">
		<div class="mbf-shop-minicart__header">
			<?php
			/**
			 * The mbf_shop_minicart_header_start hook.
			 *
			 * @since 1.0.0
			 */
			do_action( 'mbf_shop_minicart_header_start' );
			?>

			<nav class="mbf-shop-minicart__nav">
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

				<span class="mbf-shop-minicart__toggle" role="button"><i class="mbf-icon mbf-icon-x"></i></span>
			</nav>

			<?php
			/**
			 * The mbf_shop_minicart_header_end hook.
			 *
			 * @since 1.0.0
			 */
			do_action( 'mbf_shop_minicart_header_end' );
			?>
		</div>
		<aside class="mbf-shop-minicart__sidebar">
			<div class="mbf-shop-minicart__inner">
				<div class="widget_shopping_cart_content">
					<?php woocommerce_mini_cart(); ?>
				</div>
			</div>
		</aside>
	</div>
	<?php
}
