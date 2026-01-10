<?php
/**
 * Template Name: Form Submission
 * Template Post Type: page
 *
 * @package Apparel
 */

get_header();

// Remove default title outputs for this template.
remove_action( 'mbf_main_before', 'mbf_entry_header', 10 );
remove_action( 'mbf_main_before', 'mbf_page_header', 100 );

$post_id = get_queried_object_id();

$button_label = get_post_meta( $post_id, 'form_submission_button_label', true );
$button_url   = get_post_meta( $post_id, 'form_submission_button_url', true );
$next_title   = get_post_meta( $post_id, 'form_submission_next_title', true );
$next_content = get_post_meta( $post_id, 'form_submission_next_content', true );

if ( $button_url && ! $button_label ) {
	$button_label = __( 'Back to homepage', 'apparel' );
}

if ( ! $next_title ) {
	$next_title = __( "What's next", 'apparel' );
}

if ( ! $next_content ) {
	$next_content = __( 'Our team will review your submission and follow up shortly.', 'apparel' );
}

$heading = get_the_title( $post_id );
$heading = $heading ? $heading : __( 'Thank you for your submission', 'apparel' );
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
							<?php echo esc_html( $heading ); ?>
						</h1>

						<div class="mbf-thank-you__message">
							<?php the_content(); ?>
						</div>

						<?php if ( $button_url ) : ?>
							<div class="mbf-thank-you__actions">
								<a class="mbf-button mbf-button--solid" href="<?php echo esc_url( $button_url ); ?>">
									<?php echo esc_html( $button_label ); ?>
								</a>
							</div>
						<?php endif; ?>

						<?php if ( $next_title || $next_content ) : ?>
							<h2 class="mbf-thank-you__headline">
								<?php echo esc_html( $next_title ); ?>
							</h2>
							<?php if ( $next_content ) : ?>
								<div class="mbf-thank-you__message">
									<?php echo wp_kses_post( wpautop( $next_content ) ); ?>
								</div>
							<?php endif; ?>
						<?php endif; ?>
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

<?php
get_footer();
?>
