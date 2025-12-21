<?php
/**
 * Checkout form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.5.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout.
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo wp_kses_post( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'apparel' ) ) );
	return;
}
?>

<div class="mbf-checkout">
	<form name="checkout" method="post" class="checkout woocommerce-checkout mbf-checkout__form" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">
		<div class="mbf-checkout__grid">
			<?php if ( $checkout->get_checkout_fields() ) : ?>
				<div class="mbf-checkout__details" id="customer_details">
					<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

					<div class="mbf-checkout__billing">
						<?php do_action( 'woocommerce_checkout_billing' ); ?>
					</div>

					<div class="mbf-checkout__shipping">
						<?php do_action( 'woocommerce_checkout_shipping' ); ?>
					</div>

					<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>
				</div>
			<?php endif; ?>

			<div class="mbf-checkout__summary">
				<?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>

				<h3 id="order_review_heading" class="mbf-checkout__heading"><?php esc_html_e( 'Order summary', 'apparel' ); ?></h3>

				<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

				<div id="order_review" class="woocommerce-checkout-review-order mbf-checkout__order-review">
					<?php do_action( 'woocommerce_checkout_order_review' ); ?>
				</div>

				<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
			</div>
		</div>
	</form>
</div>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
