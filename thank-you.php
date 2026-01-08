<?php
/**
 * Template Name: Thank You
 * Template Post Type: page
 *
 * @package Apparel
 */

get_header();

// Remove default title outputs for this template.
remove_action( 'mbf_main_before', 'mbf_entry_header', 10 );
remove_action( 'mbf_main_before', 'mbf_page_header', 100 );

$session_id        = isset( $_GET['session_id'] ) ? sanitize_text_field( wp_unslash( $_GET['session_id'] ) ) : '';
$payment_confirmed = true;

if ( $session_id ) {
	$session = apparel_service_get_stripe_checkout_session( $session_id );

	$payment_confirmed = $session && (
		( isset( $session['payment_status'] ) && 'paid' === $session['payment_status'] ) ||
		( isset( $session['status'] ) && 'complete' === $session['status'] )
	);
}

$thank_you_heading = $payment_confirmed
	? __( 'Thank you — your payment is confirmed', 'apparel' )
	: __( 'Payment not confirmed', 'apparel' );

$thank_you_message = $payment_confirmed
	? __( 'We’ve received your payment and our team will be in touch as soon as possible to help you get started.', 'apparel' )
	: __( 'We could not confirm your payment. If you believe this is an error, please contact support.', 'apparel' );
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
							<?php echo esc_html( $thank_you_message ); ?>
						</p>

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
