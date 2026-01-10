<?php
/**
 * Template Name: Form Submission
 * Template Post Type: page
 *
 * @package Apparel
 */

get_header( 'lead-gen' );

$post_id = get_the_ID();

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

$hero_background = 'linear-gradient(135deg, #3f0bb6 0%, #2a0788 100%)';
$heading         = get_the_title( $post_id );
$heading         = $heading ? $heading : __( 'Thank you for your submission', 'apparel' );
?>

<div class="lead-gen-page">
	<section class="lead-gen-hero">
		<div class="lead-gen-hero__media" aria-hidden="true">
			<div class="lead-gen-hero__image" style="background-image: <?php echo esc_attr( $hero_background ); ?>;"></div>
			<div class="lead-gen-hero__overlay"></div>
		</div>
		<div class="lead-gen-hero__content">
			<div class="lead-gen-hero__stack">
				<div class="lead-gen-hero__card">
					<h1><?php echo esc_html( $heading ); ?></h1>
					<?php while ( have_posts() ) : the_post(); ?>
						<?php the_content(); ?>
					<?php endwhile; ?>
				</div>
				<?php if ( $button_url ) : ?>
					<div class="lead-gen-hero__cta">
						<a class="mbf-button mbf-button--solid" href="<?php echo esc_url( $button_url ); ?>">
							<?php echo esc_html( $button_label ); ?>
						</a>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</section>

	<?php if ( $next_title || $next_content ) : ?>
		<section class="lead-gen-cta">
			<div class="lead-gen-cta__panel" style="background: <?php echo esc_attr( $hero_background ); ?>;">
				<h2><?php echo esc_html( $next_title ); ?></h2>
				<?php if ( $next_content ) : ?>
					<?php echo wp_kses_post( wpautop( $next_content ) ); ?>
				<?php endif; ?>
			</div>
		</section>
	<?php endif; ?>
</div>

<?php
get_footer();
?>
