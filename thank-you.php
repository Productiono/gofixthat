<?php
/**
 * Template Name: Thank You
 * Template Post Type: page
 *
 * @package Apparel
 */

if ( ! headers_sent() ) {
	nocache_headers();
	header( 'Cache-Control: no-store, no-cache, must-revalidate, max-age=0' );
	header( 'Pragma: no-cache' );
}

get_header();

// Remove default title outputs for this template.
remove_action( 'mbf_main_before', 'mbf_entry_header', 10 );
remove_action( 'mbf_main_before', 'mbf_page_header', 100 );

$session_id           = isset( $_GET['session_id'] ) ? sanitize_text_field( wp_unslash( $_GET['session_id'] ) ) : '';
$payment_state        = 'missing';
$session              = null;
$payment_status       = '';
$payment_intent       = null;
$payment_intent_state = '';

if ( $session_id ) {
	$session = apparel_service_get_stripe_checkout_session( $session_id );

	if ( $session ) {
		$payment_state  = 'unconfirmed';
		$payment_status = isset( $session['payment_status'] ) ? $session['payment_status'] : '';

		if ( 'paid' === $payment_status || 'no_payment_required' === $payment_status ) {
			$payment_state = 'confirmed';
		} elseif ( ! empty( $session['payment_intent'] ) ) {
			$payment_intent = apparel_service_get_stripe_payment_intent( $session['payment_intent'] );

			if ( $payment_intent && isset( $payment_intent['status'] ) ) {
				$payment_intent_state = $payment_intent['status'];

				if ( 'succeeded' === $payment_intent_state ) {
					$payment_state = 'confirmed';
				} elseif ( 'processing' === $payment_intent_state || 'requires_capture' === $payment_intent_state ) {
					$payment_state = 'processing';
				}
			}
		} elseif ( 'unpaid' === $payment_status || 'processing' === $payment_status ) {
			$payment_state = 'processing';
		}
	} else {
		$payment_state = 'invalid';
	}
}

$support_url = home_url( '/contact' );

switch ( $payment_state ) {
	case 'confirmed':
		$thank_you_heading = __( 'Payment confirmed ✅ Thank you!', 'apparel' );
		$thank_you_message = __( 'We’ve received your payment and our team will be in touch as soon as possible to help you get started.', 'apparel' );
		break;
	case 'processing':
		$thank_you_heading = __( 'Payment processing', 'apparel' );
		$thank_you_message = __( 'Your payment is still processing. Please refresh this page in a moment to see the confirmed status.', 'apparel' );
		break;
	case 'invalid':
		$thank_you_heading = __( 'Payment session not found', 'apparel' );
		$thank_you_message = sprintf(
			/* translators: %s: support URL */
			__( 'We could not find your payment session. Please contact support for help: <a href="%s">Get support</a>.', 'apparel' ),
			esc_url( $support_url )
		);
		break;
	case 'missing':
		$thank_you_heading = __( 'Missing session details', 'apparel' );
		$thank_you_message = sprintf(
			/* translators: %s: support URL */
			__( 'We could not confirm your payment without a session ID. Please use the link from your receipt or <a href="%s">contact support</a>.', 'apparel' ),
			esc_url( $support_url )
		);
		break;
	default:
		$thank_you_heading = __( 'Payment not confirmed', 'apparel' );
		$thank_you_message = sprintf(
			/* translators: %s: support URL */
			__( 'We could not confirm your payment. If you believe this is an error, please <a href="%s">contact support</a>.', 'apparel' ),
			esc_url( $support_url )
		);
		break;
}

$details = array();

if ( $session && 'confirmed' === $payment_state ) {
	if ( isset( $session['amount_total'], $session['currency'] ) ) {
		$details[] = sprintf(
			/* translators: 1: formatted amount, 2: currency code */
			__( 'Amount: %1$s %2$s', 'apparel' ),
			number_format_i18n( $session['amount_total'] / 100, 2 ),
			strtoupper( $session['currency'] )
		);
	}

	if ( ! empty( $session['customer_details']['email'] ) ) {
		$details[] = sprintf(
			/* translators: %s: customer email */
			__( 'Email: %s', 'apparel' ),
			sanitize_email( $session['customer_details']['email'] )
		);
	}

	$metadata_labels = array( 'service_name', 'order_name', 'service' );
	foreach ( $metadata_labels as $label ) {
		if ( ! empty( $session['metadata'][ $label ] ) ) {
			$details[] = sprintf(
				/* translators: %s: service name */
				__( 'Service: %s', 'apparel' ),
				sanitize_text_field( $session['metadata'][ $label ] )
			);
			break;
		}
	}
}

if ( $session && 'confirmed' === $payment_state && function_exists( 'apparel_service_process_checkout_session' ) ) {
	apparel_service_process_checkout_session( $session );
}
?>

<div id="primary" class="mbf-content-area">
	<?php
	/**
	 * The mbf_main_before hook.
	 *
	 * @since 1.0.0
	 */
	do_action( 'mbf_main_before' );
	?>

	<?php while ( have_posts() ) : the_post(); ?>
		<section class="mbf-thank-you" aria-labelledby="mbf-thank-you-heading">
			<div class="mbf-container">
				<div class="mbf-thank-you__card">
					<div class="mbf-thank-you__inner">
						<span class="mbf-thank-you__icon" aria-hidden="true">
							<svg viewBox="0 0 24 24" role="presentation" focusable="false">
								<path d="M12 2a10 10 0 1 0 10 10A10.011 10.011 0 0 0 12 2Zm0 18a8 8 0 1 1 8-8 8.009 8.009 0 0 1-8 8Zm4.207-11.207a1 1 0 0 1 0 1.414l-5 5a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L10.5 13.086l4.293-4.293a1 1 0 0 1 1.414 0Z" />
							</svg>
						</span>

						<h1 id="mbf-thank-you-heading" class="mbf-thank-you__headline">
							<?php echo esc_html( $thank_you_heading ); ?>
						</h1>

						<p class="mbf-thank-you__message">
							<?php echo wp_kses_post( $thank_you_message ); ?>
						</p>

						<?php if ( ! empty( $details ) ) : ?>
							<ul class="mbf-thank-you__details">
								<?php foreach ( $details as $detail ) : ?>
									<li><?php echo esc_html( $detail ); ?></li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>

						<div class="mbf-thank-you__actions">
							<a class="mbf-button mbf-button--solid" href="<?php echo esc_url( home_url( '/' ) ); ?>">
								<?php esc_html_e( 'Go to Home', 'apparel' ); ?>
							</a>
						</div>
					</div>
				</div>
			</div>
		</section>
	<?php endwhile; ?>

	<?php
	/**
	 * The mbf_main_after hook.
	 *
	 * @since 1.0.0
	 */
	do_action( 'mbf_main_after' );
	?>
</div>

<?php get_footer(); ?>
