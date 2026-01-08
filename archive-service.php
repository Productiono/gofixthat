<?php
/**
 * Archive template for Services.
 *
 * @package Apparel
 */

get_header();

if ( ! function_exists( 'apparel_service_format_price' ) ) {
	/**
	 * Format service price for display.
	 *
	 * @param string $price Raw price.
	 * @return string
	 */
	function apparel_service_format_price( $price ) {
		if ( '' === $price || null === $price ) {
			return '';
		}

		return '$' . number_format_i18n( (float) $price, 2 );
	}
}
?>
<main id="primary" class="site-main content-area">
	<div class="entry-content service-archive">
	<header class="service-archive-header">
		<h1><?php esc_html_e( 'Services', 'apparel' ); ?></h1>
		<?php if ( get_the_archive_description() ) : ?>
			<div class="service-archive-description">
				<?php echo wp_kses_post( get_the_archive_description() ); ?>
			</div>
		<?php endif; ?>
	</header>

	<div class="service-archive-grid">
		<?php if ( have_posts() ) : ?>
			<?php while ( have_posts() ) : ?>
				<?php
					the_post();
					$service_id     = get_the_ID();
					$service_price  = get_post_meta( $service_id, '_service_price', true );
					$service_sale   = get_post_meta( $service_id, '_service_sale_price', true );
					$screenshot_ids = get_post_meta( $service_id, '_service_screenshot_ids', true );
					$has_sale       = '' !== $service_sale && '' !== $service_price && (float) $service_sale < (float) $service_price;
					if ( ! is_array( $screenshot_ids ) ) {
						$screenshot_ids = array_filter( array_map( 'absint', explode( ',', (string) $screenshot_ids ) ) );
					}
					$card_image = '';
					if ( has_post_thumbnail() ) {
						$card_image = get_the_post_thumbnail( $service_id, 'mbf-thumbnail', array( 'class' => 'service-card-image' ) );
					} elseif ( ! empty( $screenshot_ids ) ) {
						$card_image = wp_get_attachment_image( $screenshot_ids[0], 'mbf-thumbnail', false, array( 'class' => 'service-card-image' ) );
					}
					$author_id   = (int) get_post_field( 'post_author', $service_id );
					$author_name = get_the_author_meta( 'display_name', $author_id );
					?>
					<article class="service-card">
						<a class="service-card-media" href="<?php the_permalink(); ?>">
							<?php if ( $card_image ) : ?>
								<?php echo $card_image; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<?php else : ?>
								<div class="service-card-placeholder">
									<span><?php esc_html_e( 'No preview', 'apparel' ); ?></span>
								</div>
							<?php endif; ?>
						</a>
						<div class="service-card-body">
							<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
							<p class="service-card-author"><?php echo esc_html( $author_name ); ?></p>
							<div class="service-card-price">
								<?php if ( $has_sale ) : ?>
									<span class="service-price-original"><?php echo esc_html( apparel_service_format_price( $service_price ) ); ?></span>
									<span class="service-price-current"><?php echo esc_html( apparel_service_format_price( $service_sale ) ); ?></span>
								<?php else : ?>
									<span class="service-price-current"><?php echo esc_html( apparel_service_format_price( $service_price ) ); ?></span>
								<?php endif; ?>
							</div>
							<a class="service-button service-button-primary" href="<?php the_permalink(); ?>">
								<?php esc_html_e( 'View Service', 'apparel' ); ?>
							</a>
						</div>
					</article>
				<?php endwhile; ?>
		<?php else : ?>
			<p><?php esc_html_e( 'No services found.', 'apparel' ); ?></p>
		<?php endif; ?>
	</div>
	</div>
</main>

<?php
get_footer();
