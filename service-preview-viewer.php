<?php
/**
 * Service preview viewer template.
 *
 * @package Apparel
 */

$service_id = absint( get_query_var( 'service_id' ) );
$preview_url = (string) get_query_var( 'service_preview_url' );

if ( empty( $preview_url ) && $service_id ) {
	$preview_url = (string) get_post_meta( $service_id, '_service_live_preview_url', true );
}

$preview_url = esc_url_raw( $preview_url );

$service_title = $service_id ? get_the_title( $service_id ) : '';
$service_link  = $service_id ? get_permalink( $service_id ) : home_url( '/' );

$service_price   = $service_id ? get_post_meta( $service_id, '_service_price', true ) : '';
$service_sale    = $service_id ? get_post_meta( $service_id, '_service_sale_price', true ) : '';
$checkout_url    = $service_id ? get_post_meta( $service_id, '_service_checkout_url', true ) : '';
$has_sale        = '' !== $service_sale && '' !== $service_price && (float) $service_sale < (float) $service_price;
$price_display   = apparel_service_format_price( $has_sale ? $service_sale : $service_price );
$price_display   = $price_display ? $price_display : __( 'Price unavailable', 'apparel' );
$service_heading = $service_title ? $service_title : __( 'Service Preview', 'apparel' );

wp_enqueue_style( 'apparel-service' );
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class( 'service-preview-viewer' ); ?>>
	<div class="service-preview-viewer__page">
		<header class="service-preview-viewer__header">
			<div class="service-preview-viewer__title">
				<span class="service-preview-viewer__label"><?php echo esc_html( $service_heading ); ?></span>
			</div>
			<a class="service-preview-viewer__back" href="<?php echo esc_url( $service_link ); ?>">
				<?php esc_html_e( 'Back to service', 'apparel' ); ?>
			</a>
		</header>
		<main class="service-preview-viewer__main">
			<?php if ( $preview_url ) : ?>
				<iframe class="service-preview-viewer__frame" title="<?php echo esc_attr( $service_heading ); ?>" src="<?php echo esc_url( $preview_url ); ?>" loading="lazy"></iframe>
			<?php else : ?>
				<div class="service-preview-viewer__empty">
					<?php esc_html_e( 'Preview unavailable.', 'apparel' ); ?>
				</div>
			<?php endif; ?>
		</main>
		<footer class="service-preview-viewer__footer">
			<div class="service-preview-viewer__meta">
				<span class="service-preview-viewer__name"><?php echo esc_html( $service_heading ); ?></span>
				<span class="service-preview-viewer__price"><?php echo esc_html( $price_display ); ?></span>
			</div>
			<?php if ( $checkout_url ) : ?>
				<a class="service-button service-button-cta service-preview-viewer__buy" href="<?php echo esc_url( $checkout_url ); ?>" target="_blank" rel="noopener noreferrer">
					<?php esc_html_e( 'Buy Now', 'apparel' ); ?>
				</a>
			<?php else : ?>
				<button class="service-button service-button-cta service-preview-viewer__buy" type="button" disabled>
					<?php esc_html_e( 'Buy Now', 'apparel' ); ?>
				</button>
			<?php endif; ?>
		</footer>
	</div>
	<?php wp_footer(); ?>
</body>
</html>
