<?php
/**
 * Blog category-based CTA section.
 *
 * @package Apparel
 */

$cta_data = mbf_get_active_category_cta_data();

if ( empty( $cta_data ) ) {
	return;
}

$headline         = isset( $cta_data['headline'] ) ? $cta_data['headline'] : '';
$subheadline      = isset( $cta_data['subheadline'] ) ? $cta_data['subheadline'] : '';
$description      = isset( $cta_data['description'] ) ? $cta_data['description'] : '';
$primary_label    = isset( $cta_data['primary_label'] ) ? $cta_data['primary_label'] : '';
$primary_url      = isset( $cta_data['primary_url'] ) ? $cta_data['primary_url'] : '';
$secondary_label  = isset( $cta_data['secondary_label'] ) ? $cta_data['secondary_label'] : '';
$secondary_url    = isset( $cta_data['secondary_url'] ) ? $cta_data['secondary_url'] : '';

if ( ! $headline && ! $subheadline && ! $description ) {
	return;
}
?>

<section class="mbf-blog-cta" aria-labelledby="mbf-blog-cta-title">
	<div class="mbf-blog-cta__inner">
		<div class="mbf-blog-cta__content">
			<?php if ( $headline ) : ?>
				<p class="mbf-blog-cta__eyebrow"><?php echo esc_html( $headline ); ?></p>
			<?php endif; ?>

			<?php if ( $subheadline ) : ?>
				<h2 id="mbf-blog-cta-title" class="mbf-blog-cta__title">
					<?php echo esc_html( $subheadline ); ?>
				</h2>
			<?php endif; ?>

			<?php if ( $description ) : ?>
				<p class="mbf-blog-cta__description">
					<?php echo esc_html( $description ); ?>
				</p>
			<?php endif; ?>

			<?php if ( $primary_label || $secondary_label ) : ?>
				<div class="mbf-blog-cta__actions">
					<?php if ( $primary_label && $primary_url ) : ?>
						<a class="mbf-button mbf-button--solid" href="<?php echo esc_url( $primary_url ); ?>">
							<?php echo esc_html( $primary_label ); ?>
						</a>
					<?php endif; ?>

					<?php if ( $secondary_label && $secondary_url ) : ?>
						<a class="mbf-button mbf-button--ghost" href="<?php echo esc_url( $secondary_url ); ?>">
							<span class="mbf-blog-cta__icon" aria-hidden="true">►</span>
							<?php echo esc_html( $secondary_label ); ?>
						</a>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
