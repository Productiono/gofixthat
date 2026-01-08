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
$order_id             = 0;
$order_status         = '';
$payment_status       = '';
$payment_intent       = null;
$payment_intent_state = '';
$line_items           = array();

if ( $session_id && 0 === strpos( $session_id, 'cs_' ) ) {
	$order_id = apparel_service_get_order_id_by_session_id( $session_id );

	if ( $order_id ) {
		$order_status = get_post_meta( $order_id, '_status', true );
		if ( 'paid' === $order_status ) {
			$payment_state = 'confirmed';
		} elseif ( 'refunded' === $order_status ) {
			$payment_state = 'refunded';
		} elseif ( 'failed' === $order_status ) {
			$payment_state = 'invalid';
		} else {
			$payment_state = 'processing';
		}
	}

	if ( ! $order_id ) {
		$session = apparel_service_get_stripe_checkout_session( $session_id );
	}

	if ( $session ) {
		$payment_state  = 'unconfirmed';
		$payment_status = isset( $session['payment_status'] ) ? $session['payment_status'] : '';
		$secret_key     = apparel_service_get_stripe_secret_key();

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

		if ( $secret_key ) {
			$line_items = apparel_service_get_checkout_line_items( $secret_key, $session_id );
		}
	} else {
		$payment_state = 'invalid';
	}
} elseif ( $session_id ) {
	$payment_state = 'invalid';
}

$support_url = home_url( '/contact' );

if ( ! function_exists( 'apparel_service_get_download_link' ) ) {
	/**
	 * Get the download link for a service or its variation.
	 *
	 * @param int    $service_id   Service post ID.
	 * @param string $variation_id Variation ID.
	 * @return string
	 */
	function apparel_service_get_download_link( $service_id, $variation_id ) {
		$service_id = absint( $service_id );
		if ( ! $service_id ) {
			return '';
		}

		if ( $variation_id ) {
			$variations = get_post_meta( $service_id, '_service_variations', true );
			if ( is_array( $variations ) ) {
				foreach ( $variations as $variation ) {
					if ( ! empty( $variation['variation_id'] ) && $variation['variation_id'] === $variation_id && ! empty( $variation['download_link'] ) ) {
						return esc_url_raw( $variation['download_link'] );
					}
				}
			}
		}

		$service_download_link = get_post_meta( $service_id, '_service_download_link', true );
		if ( $service_download_link ) {
			return esc_url_raw( $service_download_link );
		}

		return '';
	}
}

switch ( $payment_state ) {
	case 'confirmed':
		$thank_you_heading = __( 'Payment confirmed ✅ Thank you!', 'apparel' );
		$thank_you_message = __( 'We’ve received your payment and our team will be in touch as soon as possible to help you get started.', 'apparel' );
		break;
	case 'processing':
		$thank_you_heading = __( 'Payment processing', 'apparel' );
		$thank_you_message = __( 'Your payment is still processing. Please refresh this page in a moment to see the confirmed status.', 'apparel' );
		break;
	case 'refunded':
		$thank_you_heading = __( 'Payment refunded', 'apparel' );
		$thank_you_message = __( 'Your payment was refunded. If you have questions, please contact support for assistance.', 'apparel' );
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
$download_link = '';

if ( $order_id && in_array( $payment_state, array( 'confirmed', 'refunded', 'processing' ), true ) ) {
	$order_amount   = get_post_meta( $order_id, '_amount_total', true );
	$order_currency = get_post_meta( $order_id, '_currency', true );
	if ( '' !== $order_amount && '' !== $order_currency ) {
		$details[] = sprintf(
			/* translators: 1: formatted amount, 2: currency code */
			__( 'Amount: %1$s %2$s', 'apparel' ),
			number_format_i18n( (float) $order_amount, 2 ),
			strtoupper( $order_currency )
		);
	}

	$order_email = get_post_meta( $order_id, '_customer_email', true );
	if ( $order_email ) {
		$details[] = sprintf(
			/* translators: %s: customer email */
			__( 'Email: %s', 'apparel' ),
			sanitize_email( $order_email )
		);
	}

	$order_line_items = get_post_meta( $order_id, '_stripe_line_items', true );
	if ( is_array( $order_line_items ) ) {
		foreach ( $order_line_items as $line_item ) {
			$description = $line_item['description'] ?? '';
			$quantity    = isset( $line_item['quantity'] ) ? absint( $line_item['quantity'] ) : 1;
			if ( $description ) {
				$details[] = sprintf(
					/* translators: 1: item description, 2: item quantity */
					__( 'Item: %1$s × %2$d', 'apparel' ),
					sanitize_text_field( $description ),
					$quantity
				);
			}
		}
	}

	$order_variation = get_post_meta( $order_id, '_variation_name', true );
	if ( $order_variation ) {
		$details[] = sprintf(
			/* translators: %s: service name */
			__( 'Service: %s', 'apparel' ),
			sanitize_text_field( $order_variation )
		);
	} else {
		$order_service_id = absint( get_post_meta( $order_id, '_service_id', true ) );
		if ( $order_service_id ) {
			$details[] = sprintf(
				/* translators: %s: service name */
				__( 'Service: %s', 'apparel' ),
				sanitize_text_field( get_the_title( $order_service_id ) )
			);
		}
	}
}

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

	if ( ! empty( $line_items ) ) {
		foreach ( $line_items as $line_item ) {
			$description = $line_item['description'] ?? '';
			$quantity    = isset( $line_item['quantity'] ) ? absint( $line_item['quantity'] ) : 1;
			if ( $description ) {
				$details[] = sprintf(
					/* translators: 1: item description, 2: item quantity */
					__( 'Item: %1$s × %2$d', 'apparel' ),
					sanitize_text_field( $description ),
					$quantity
				);
			}
		}
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

if ( 'confirmed' === $payment_state ) {
	$download_service_id   = 0;
	$download_variation_id = '';

	if ( $order_id ) {
		$download_service_id   = absint( get_post_meta( $order_id, '_service_id', true ) );
		$download_variation_id = sanitize_text_field( get_post_meta( $order_id, '_variation_id', true ) );
	} elseif ( $session ) {
		$download_service_id   = ! empty( $session['metadata']['service_id'] ) ? absint( $session['metadata']['service_id'] ) : 0;
		$download_variation_id = ! empty( $session['metadata']['variation_id'] ) ? sanitize_text_field( $session['metadata']['variation_id'] ) : '';
	}

	if ( $download_service_id ) {
		$download_link = apparel_service_get_download_link( $download_service_id, $download_variation_id );
	}
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
							<?php if ( $download_link ) : ?>
								<a class="mbf-button mbf-button--solid" href="<?php echo esc_url( $download_link ); ?>" target="_blank" rel="noopener noreferrer">
									<?php esc_html_e( 'Download Now', 'apparel' ); ?>
								</a>
							<?php endif; ?>
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
