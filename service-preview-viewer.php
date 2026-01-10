<?php
/**
 * Service preview viewer template.
 *
 * @package Apparel
 */

$service_id = absint( get_query_var( 'service_id' ) );
$preview_token = (string) get_query_var( 'service_preview_token' );
$preview_data  = $preview_token ? apparel_service_preview_get_token( $preview_token ) : false;
$preview_url   = $preview_data && (int) $preview_data['service_id'] === $service_id ? $preview_data['preview_url'] : '';

if ( ! $preview_url && $service_id ) {
	$preview_url = get_post_meta( $service_id, '_service_live_preview_url', true );
	if ( $preview_url ) {
		$preview_token = apparel_service_preview_store_token( $service_id, $preview_url );
	}
}

$service_title = $service_id ? get_the_title( $service_id ) : '';
$service_link  = $service_id ? get_permalink( $service_id ) : home_url( '/' );

$service_price   = $service_id ? get_post_meta( $service_id, '_service_price', true ) : '';
$service_sale    = $service_id ? get_post_meta( $service_id, '_service_sale_price', true ) : '';
$checkout_url    = $service_id ? get_post_meta( $service_id, '_service_checkout_url', true ) : '';
$variations      = $service_id ? get_post_meta( $service_id, '_service_variations', true ) : array();
$variation_data  = is_array( $variations ) ? $variations : array();
$has_sale        = '' !== $service_sale && '' !== $service_price && (float) $service_sale < (float) $service_price;
$price_display   = apparel_service_format_price( $has_sale ? $service_sale : $service_price );
$price_display   = $price_display ? $price_display : __( 'Price unavailable', 'apparel' );
$service_heading = $service_title ? $service_title : __( 'Service Preview', 'apparel' );

$default_variation    = null;
$default_variation_id = '';
$default_price_id     = '';
$default_price        = $service_price;
$default_sale         = $service_sale;
$default_checkout_url = $checkout_url;

if ( $checkout_url && function_exists( 'apparel_service_maybe_update_payment_link_redirect' ) ) {
	apparel_service_maybe_update_payment_link_redirect( $checkout_url );
}

foreach ( $variation_data as $variation ) {
	$price          = isset( $variation['price'] ) ? $variation['price'] : '';
	$sale_price     = isset( $variation['sale_price'] ) ? $variation['sale_price'] : '';
	$checkout_link  = isset( $variation['stripe_payment_link'] ) ? $variation['stripe_payment_link'] : '';
	$price_id       = isset( $variation['stripe_price_id'] ) ? $variation['stripe_price_id'] : '';
	if ( $checkout_link && function_exists( 'apparel_service_maybe_update_payment_link_redirect' ) ) {
		apparel_service_maybe_update_payment_link_redirect( $checkout_link );
	}
	$effective      = ( '' !== $sale_price && '' !== $price && (float) $sale_price < (float) $price ) ? $sale_price : $price;
	$effective_value = is_numeric( $effective ) ? (float) $effective : PHP_FLOAT_MAX;
	if ( null === $default_variation || $effective_value < $default_variation['effective'] ) {
		$default_variation = array(
			'id'            => $variation['variation_id'] ?? '',
			'price'         => $price,
			'sale_price'    => $sale_price,
			'checkout_link' => $checkout_link,
			'price_id'      => $price_id,
			'effective'     => $effective_value,
		);
	}
}

if ( $default_variation ) {
	$default_variation_id = $default_variation['id'];
	$default_price        = $default_variation['price'];
	$default_sale         = $default_variation['sale_price'];
	$default_checkout_url = $default_variation['checkout_link'] ? $default_variation['checkout_link'] : $checkout_url;
	$default_price_id     = $default_variation['price_id'] ?? '';
}

$checkout_available = ! empty( $default_checkout_url ) || ! empty( $default_price_id );
$pricing_data_attrs = array(
	'data-service-id'       => $service_id,
	'data-price'            => $default_price,
	'data-sale'             => $default_sale,
	'data-checkout'         => $default_checkout_url,
	'data-price-id'         => $default_price_id,
	'data-base-price'       => $service_price,
	'data-base-sale'        => $service_sale,
	'data-base-checkout'    => $checkout_url,
	'data-base-price-id'    => $default_price_id,
	'data-variation'        => $default_variation_id,
	'data-checkout-endpoint' => rest_url( 'apparel/v1/stripe/checkout-session' ),
);

$frame_url = apparel_service_preview_frame_url( $service_id, $preview_token );
$frame_base_url = apparel_service_preview_frame_url( $service_id, $preview_token );
$preview_origin = '';

if ( $preview_url ) {
	$preview_parts = wp_parse_url( $preview_url );
	if ( ! empty( $preview_parts['scheme'] ) && ! empty( $preview_parts['host'] ) ) {
		$preview_origin = $preview_parts['scheme'] . '://' . $preview_parts['host'];
		if ( ! empty( $preview_parts['port'] ) ) {
			$preview_origin .= ':' . $preview_parts['port'];
		}
	}
}

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
			<?php if ( $preview_url && $frame_url ) : ?>
				<iframe class="service-preview-viewer__frame" title="<?php echo esc_attr( $service_heading ); ?>" src="<?php echo esc_url( $frame_url ); ?>" loading="lazy"></iframe>
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
			<div class="service-pricing-card" <?php foreach ( $pricing_data_attrs as $attr => $value ) : ?><?php echo esc_attr( $attr ); ?>="<?php echo esc_attr( $value ); ?>" <?php endforeach; ?>></div>
			<button class="service-button service-button-cta service-preview-viewer__buy" type="button" data-service-buy <?php echo $checkout_available ? '' : 'disabled'; ?>>
				<?php esc_html_e( 'Buy Now', 'apparel' ); ?>
			</button>
		</footer>
	</div>
	<?php wp_footer(); ?>
	<?php if ( $preview_url && $frame_base_url ) : ?>
		<script>
			(function() {
				const iframe = document.querySelector('.service-preview-viewer__frame');
				if (!iframe) {
					return;
				}

				const frameBaseUrl = <?php echo wp_json_encode( $frame_base_url ); ?>;
				const previewOrigin = <?php echo wp_json_encode( $preview_origin ); ?>;

				if (!frameBaseUrl || !previewOrigin) {
					return;
				}

				function rewriteLinks() {
					let doc;
					try {
						doc = iframe.contentDocument || iframe.contentWindow.document;
					} catch (error) {
						return;
					}

					if (!doc) {
						return;
					}

					const links = doc.querySelectorAll('a[href]');
					links.forEach((link) => {
						const href = link.getAttribute('href');
						if (!href || href.startsWith('#') || href.startsWith('mailto:') || href.startsWith('tel:') || href.startsWith('javascript:')) {
							return;
						}

						let resolved;
						try {
							resolved = new URL(link.href);
						} catch (error) {
							return;
						}

						if (resolved.origin !== previewOrigin) {
							return;
						}

						const frameUrl = new URL(frameBaseUrl);
						frameUrl.searchParams.set('service_preview_url', resolved.toString());
						link.setAttribute('href', frameUrl.toString());
					});
				}

				iframe.addEventListener('load', rewriteLinks);
			})();
		</script>
	<?php endif; ?>
</body>
</html>
