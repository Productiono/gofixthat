<?php
/**
 * Single Service Template.
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

while ( have_posts() ) :
	the_post();
	$service_id        = get_the_ID();
	$service_price     = get_post_meta( $service_id, '_service_price', true );
	$service_sale      = get_post_meta( $service_id, '_service_sale_price', true );
	$checkout_url      = get_post_meta( $service_id, '_service_checkout_url', true );
	$live_preview_url  = get_post_meta( $service_id, '_service_live_preview_url', true );
	$screenshot_ids    = get_post_meta( $service_id, '_service_screenshot_ids', true );
	$variations        = get_post_meta( $service_id, '_service_variations', true );
	$service_sales     = get_post_meta( $service_id, '_service_sales', true );
	$support_url       = get_post_meta( $service_id, '_service_support_url', true );
	$docs_url          = get_post_meta( $service_id, '_service_docs_url', true );
	$presales_url      = get_post_meta( $service_id, '_service_presales_url', true );
	$hire_url          = get_post_meta( $service_id, '_service_hire_url', true );
	$service_rating    = get_post_meta( $service_id, '_service_rating', true );
	$service_reviews   = get_post_meta( $service_id, '_service_review_count', true );

	if ( ! is_array( $screenshot_ids ) ) {
		$screenshot_ids = array_filter( array_map( 'absint', explode( ',', (string) $screenshot_ids ) ) );
	}

	$preview_image = '';
	if ( has_post_thumbnail() ) {
		$preview_image = get_the_post_thumbnail( $service_id, 'mbf-large', array( 'class' => 'service-preview-image' ) );
	} elseif ( ! empty( $screenshot_ids ) ) {
		$preview_image = wp_get_attachment_image( $screenshot_ids[0], 'mbf-large', false, array( 'class' => 'service-preview-image' ) );
	}

	$gallery_images = array();
	foreach ( $screenshot_ids as $attachment_id ) {
		$src = wp_get_attachment_image_src( $attachment_id, 'large' );
		if ( $src ) {
			$gallery_images[] = array(
				'url' => $src[0],
				'alt' => get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ),
			);
		}
	}

	if ( ! is_array( $variations ) ) {
		$variations = array();
	}

	$variation_options     = array();
	$default_variation     = null;
	$default_variation_id  = '';
	$default_price_id      = '';
	$default_price         = $service_price;
	$default_sale          = $service_sale;
	$default_checkout_url  = $checkout_url;

	foreach ( $variations as $variation ) {
		$variation_id       = isset( $variation['variation_id'] ) ? $variation['variation_id'] : '';
		$name               = isset( $variation['name'] ) ? $variation['name'] : '';
		$price              = isset( $variation['price'] ) ? $variation['price'] : '';
		$sale_price         = isset( $variation['sale_price'] ) ? $variation['sale_price'] : '';
		$checkout_link      = isset( $variation['stripe_payment_link'] ) ? $variation['stripe_payment_link'] : '';
		$price_id           = isset( $variation['stripe_price_id'] ) ? $variation['stripe_price_id'] : '';
		$effective_price    = ( '' !== $sale_price && '' !== $price && (float) $sale_price < (float) $price ) ? $sale_price : $price;
		$effective_value    = is_numeric( $effective_price ) ? (float) $effective_price : PHP_FLOAT_MAX;
		$variation_options[] = array(
			'id'            => $variation_id,
			'name'          => $name,
			'price'         => $price,
			'sale_price'    => $sale_price,
			'checkout_link' => $checkout_link,
			'price_id'      => $price_id,
			'effective'     => $effective_value,
		);

		if ( null === $default_variation || $effective_value < $default_variation['effective'] ) {
			$default_variation = end( $variation_options );
		}
	}

	if ( null === $default_variation && ! empty( $variation_options ) ) {
		$default_variation = $variation_options[0];
	}

	if ( $default_variation ) {
		$default_variation_id = $default_variation['id'];
		$default_price        = $default_variation['price'];
		$default_sale         = $default_variation['sale_price'];
		$default_checkout_url = $default_variation['checkout_link'] ? $default_variation['checkout_link'] : $checkout_url;
		$default_price_id     = $default_variation['price_id'] ?? '';
	}

	$service_price_display = apparel_service_format_price( $default_price );
	$service_sale_display  = apparel_service_format_price( $default_sale );
	$has_sale              = '' !== $default_sale && '' !== $default_price && (float) $default_sale < (float) $default_price;
	$author_id             = (int) get_post_field( 'post_author', $service_id );
	$author_url            = get_author_posts_url( $author_id );
	$author_name           = get_the_author_meta( 'display_name', $author_id );
	$modified_label        = get_the_modified_date();
	$comments_open         = comments_open( $service_id ) || get_comments_number( $service_id );
	$gallery_json          = wp_json_encode( $gallery_images );
	$content               = apply_filters( 'the_content', get_the_content() );
	$has_h2                = false !== stripos( $content, '<h2' );
	$pricing_data_attrs    = array(
		'data-service-id'   => $service_id,
		'data-price'         => $default_price,
		'data-sale'          => $default_sale,
		'data-checkout'      => $default_checkout_url,
		'data-price-id'      => $default_price_id,
		'data-base-price'    => $service_price,
		'data-base-sale'     => $service_sale,
		'data-base-checkout' => $checkout_url,
		'data-base-price-id' => $default_price_id,
		'data-variation'     => $default_variation_id,
		'data-checkout-endpoint' => rest_url( 'apparel/v1/stripe/checkout-session' ),
	);
	?>
	<div class="service-page">
		<nav class="service-breadcrumbs" aria-label="Breadcrumb">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'apparel' ); ?></a>
			<span class="service-breadcrumbs-sep">›</span>
			<a href="<?php echo esc_url( get_post_type_archive_link( 'service' ) ); ?>"><?php esc_html_e( 'Services', 'apparel' ); ?></a>
			<span class="service-breadcrumbs-sep">›</span>
			<span><?php the_title(); ?></span>
		</nav>

		<header class="service-header">
			<h1 class="service-title"><?php the_title(); ?></h1>
			<div class="service-meta">
				<span><?php esc_html_e( 'By', 'apparel' ); ?> <a href="<?php echo esc_url( $author_url ); ?>"><?php echo esc_html( $author_name ); ?></a></span>
				<?php if ( '' !== $service_sales ) : ?>
					<span class="service-meta-divider">|</span>
					<span><?php echo esc_html( $service_sales ); ?> <?php esc_html_e( 'sales', 'apparel' ); ?></span>
				<?php endif; ?>
				<span class="service-meta-divider">|</span>
				<span class="service-meta-updated">
					<span class="service-meta-icon" aria-hidden="true">✔</span>
					<?php esc_html_e( 'Recently Updated', 'apparel' ); ?>
					<span class="service-meta-date"><?php echo esc_html( $modified_label ); ?></span>
				</span>
			</div>
		</header>

		<section class="service-main">
			<div class="service-main-left">
				<div class="service-preview">
					<div class="service-preview-media">
						<?php if ( $preview_image ) : ?>
							<?php echo $preview_image; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<?php else : ?>
							<div class="service-preview-placeholder">
								<span><?php esc_html_e( 'Preview not available', 'apparel' ); ?></span>
							</div>
						<?php endif; ?>
					</div>

					<div class="service-preview-actions">
						<?php if ( $live_preview_url ) : ?>
							<?php $preview_viewer_url = apparel_service_get_preview_viewer_url( $service_id, $live_preview_url ); ?>
							<a class="service-button service-button-primary" href="<?php echo esc_url( $preview_viewer_url ); ?>" target="_blank" rel="noopener noreferrer">
								<?php esc_html_e( 'Live Preview', 'apparel' ); ?>
								<span class="service-button-icon" aria-hidden="true">
									<svg viewBox="0 0 24 24" role="presentation" focusable="false">
										<path d="M14 3h7v7h-2V6.4l-9.3 9.3-1.4-1.4 9.3-9.3H14V3z"/>
										<path d="M5 5h6v2H7v10h10v-4h2v6H5V5z"/>
									</svg>
								</span>
							</a>
						<?php endif; ?>
						<button class="service-button service-button-secondary" type="button" data-service-screenshots <?php echo empty( $gallery_images ) ? 'disabled' : ''; ?>>
							<?php esc_html_e( 'Screenshots', 'apparel' ); ?>
							<span class="service-button-icon" aria-hidden="true">
								<svg viewBox="0 0 24 24" role="presentation" focusable="false">
									<path d="M4 6h5l1.5 2H20a1 1 0 0 1 1 1v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7a1 1 0 0 1 1-1zm1 4v8h14v-8H5zm3 6l2-2 2 2 3-3 3 3H8z"/>
								</svg>
							</span>
						</button>
					</div>
				</div>

				<?php
				$support_tiles = array(
					'documentation' => array(
						'label'       => __( 'Documentation', 'apparel' ),
						'form_id'     => absint( get_theme_mod( 'service_support_form_documentation' ) ),
						'description' => __( 'Browse guides, FAQs, and helpful resources.', 'apparel' ),
						'icon'        => '<svg viewBox="0 0 24 24" role="presentation" focusable="false"><path d="M6 4h10a3 3 0 0 1 3 3v11a2 2 0 0 1-2 2H6a3 3 0 0 1-3-3V7a3 3 0 0 1 3-3zm0 2a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11V7a1 1 0 0 0-1-1H6z"/></svg>',
					),
					'presales'       => array(
						'label'       => __( 'Pre-Sales Questions?', 'apparel' ),
						'form_id'     => absint( get_theme_mod( 'service_support_form_presales' ) ),
						'description' => __( 'Ask about features, licensing, or delivery.', 'apparel' ),
						'icon'        => '<svg viewBox="0 0 24 24" role="presentation" focusable="false"><path d="M12 3a7 7 0 0 1 0 14h-1v3l-4-3H7a7 7 0 0 1 5-14zm0 4a2 2 0 0 0-2 2H8a4 4 0 1 1 6 3.5V14h-4v-2h2a2 2 0 0 0 0-4zm-1 9h2v2h-2v-2z"/></svg>',
					),
					'hire'           => array(
						'label'       => __( 'Hire Us', 'apparel' ),
						'form_id'     => absint( get_theme_mod( 'service_support_form_hire' ) ),
						'description' => __( 'Schedule custom work or ongoing support.', 'apparel' ),
						'icon'        => '<svg viewBox="0 0 24 24" role="presentation" focusable="false"><path d="M7 11h10l4 2v5h-2v-3H5v3H3v-5l4-2zm5-7a4 4 0 0 1 4 4h-2a2 2 0 0 0-4 0H8a4 4 0 0 1 4-4z"/></svg>',
					),
					'support'        => array(
						'label'       => __( 'Get Support', 'apparel' ),
						'form_id'     => absint( get_theme_mod( 'service_support_form_support' ) ),
						'description' => __( 'Reach out for help with setup and troubleshooting.', 'apparel' ),
						'icon'        => '<svg viewBox="0 0 24 24" role="presentation" focusable="false"><path d="M5 6h10a3 3 0 0 1 3 3v8h-2v-8a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v6a1 1 0 0 0 1 1h5v2H5a3 3 0 0 1-3-3V9a3 3 0 0 1 3-3zm13 10l4 3v-3h-4z"/></svg>',
					),
				);

				$available_support_tiles = array_filter(
					$support_tiles,
					function( $tile ) {
						return ! empty( $tile['form_id'] );
					}
				);

				?>

				<div class="service-support-tiles service-support-tiles-compact" aria-label="<?php esc_attr_e( 'Support links', 'apparel' ); ?>">
					<?php foreach ( $available_support_tiles as $tile_key => $tile ) : ?>
						<button
							class="service-support-tile"
							type="button"
							data-support-trigger
							data-support-form="<?php echo esc_attr( $tile_key ); ?>"
							data-support-title="<?php echo esc_attr( $tile['label'] ); ?>"
						>
							<span class="service-support-icon" aria-hidden="true">
								<?php echo $tile['icon']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</span>
							<span class="service-support-label"><?php echo esc_html( $tile['label'] ); ?></span>
						</button>
					<?php endforeach; ?>
				</div>

				<?php if ( ! empty( $available_support_tiles ) ) : ?>
					<div class="service-support-modal" data-support-modal aria-hidden="true" aria-modal="true" role="dialog">
						<div class="service-support-modal__overlay" data-support-modal-close aria-hidden="true"></div>
						<div class="service-support-modal__content" role="document">
							<button class="service-support-modal__close" type="button" data-support-modal-close aria-label="<?php esc_attr_e( 'Close support form', 'apparel' ); ?>">×</button>
							<h3 class="service-support-modal__title" data-support-modal-title></h3>
							<div class="service-support-modal__body" data-support-modal-body></div>
						</div>
					</div>
					<div class="service-support-forms" data-support-form-container hidden>
						<?php foreach ( $available_support_tiles as $tile_key => $tile ) : ?>
							<div class="service-support-form" data-support-form-template="<?php echo esc_attr( $tile_key ); ?>" hidden>
								<?php echo do_shortcode( '[fluentform id="' . (int) $tile['form_id'] . '"]' ); ?>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<section class="service-tabs">
					<div class="service-tab-list" role="tablist">
						<button class="service-tab is-active" type="button" data-tab="details" role="tab" aria-selected="true"><?php esc_html_e( 'Item Details', 'apparel' ); ?></button>
						<button class="service-tab" type="button" data-tab="reviews" role="tab" aria-selected="false"><?php esc_html_e( 'Reviews', 'apparel' ); ?></button>
						<button class="service-tab" type="button" data-tab="comments" role="tab" aria-selected="false"><?php esc_html_e( 'Comments', 'apparel' ); ?></button>
						<button class="service-tab" type="button" data-tab="support" role="tab" aria-selected="false"><?php esc_html_e( 'Support', 'apparel' ); ?></button>
					</div>
					<div class="service-tab-panel is-active" data-tab-panel="details" role="tabpanel">
						<?php if ( ! $has_h2 ) : ?>
							<h2><?php esc_html_e( 'New Features & Improvements', 'apparel' ); ?></h2>
						<?php endif; ?>
						<div class="service-content">
							<?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</div>
					</div>
					<div class="service-tab-panel" data-tab-panel="reviews" role="tabpanel">
						<?php if ( '' !== $service_rating ) : ?>
							<div class="service-rating">
								<strong><?php echo esc_html( $service_rating ); ?>/5</strong>
								<?php if ( '' !== $service_reviews ) : ?>
									<span><?php echo esc_html( $service_reviews ); ?> <?php esc_html_e( 'reviews', 'apparel' ); ?></span>
								<?php endif; ?>
							</div>
						<?php else : ?>
							<p><?php esc_html_e( 'Reviews not available.', 'apparel' ); ?></p>
						<?php endif; ?>
					</div>
					<div class="service-tab-panel" data-tab-panel="comments" role="tabpanel">
						<?php if ( $comments_open ) : ?>
							<?php comments_template(); ?>
						<?php else : ?>
							<p><?php esc_html_e( 'Comments are closed.', 'apparel' ); ?></p>
						<?php endif; ?>
					</div>
					<div class="service-tab-panel" data-tab-panel="support" role="tabpanel">
						<div class="service-support-tiles">
							<?php foreach ( $available_support_tiles as $tile_key => $tile ) : ?>
								<button
									class="service-support-tile"
									type="button"
									data-support-trigger
									data-support-form="<?php echo esc_attr( $tile_key ); ?>"
									data-support-title="<?php echo esc_attr( $tile['label'] ); ?>"
								>
									<h3><?php echo esc_html( $tile['label'] ); ?></h3>
									<p><?php echo esc_html( $tile['description'] ); ?></p>
								</button>
							<?php endforeach; ?>
							<?php if ( empty( $available_support_tiles ) ) : ?>
								<p><?php esc_html_e( 'Support resources are not available.', 'apparel' ); ?></p>
							<?php endif; ?>
						</div>
					</div>
				</section>
			</div>

			<aside class="service-main-right">
				<div class="service-pricing-card" <?php foreach ( $pricing_data_attrs as $attr => $value ) : ?><?php echo esc_attr( $attr ); ?>="<?php echo esc_attr( $value ); ?>" <?php endforeach; ?>>
					<div class="service-license">
						<span><?php esc_html_e( 'Regular License', 'apparel' ); ?></span>
						<span class="service-license-icon" aria-hidden="true">
							<svg viewBox="0 0 24 24" role="presentation" focusable="false">
								<path d="M7 10l5 5 5-5H7z"/>
							</svg>
						</span>
					</div>
					<div class="service-price">
						<span class="service-price-original<?php echo $has_sale ? '' : ' is-hidden'; ?>">
							<?php echo esc_html( $has_sale ? $service_price_display : '' ); ?>
						</span>
						<span class="service-price-current"><?php echo esc_html( $has_sale ? $service_sale_display : $service_price_display ); ?></span>
					</div>
					<ul class="service-checklist">
						<li><?php esc_html_e( 'Quality checked', 'apparel' ); ?></li>
						<li><?php esc_html_e( 'Future updates', 'apparel' ); ?></li>
						<li><?php esc_html_e( 'Support', 'apparel' ); ?></li>
					</ul>
					<?php if ( ! empty( $variation_options ) ) : ?>
						<div class="service-quantity">
							<label for="service-variation-select"><?php esc_html_e( 'Variation', 'apparel' ); ?></label>
							<div class="service-quantity-control">
								<select id="service-variation-select" class="service-variation-select" data-service-variation>
									<?php foreach ( $variation_options as $variation_option ) : ?>
										<option value="<?php echo esc_attr( $variation_option['id'] ); ?>" data-variation-price="<?php echo esc_attr( $variation_option['price'] ); ?>" data-variation-sale="<?php echo esc_attr( $variation_option['sale_price'] ); ?>" data-variation-checkout="<?php echo esc_url( $variation_option['checkout_link'] ); ?>" data-variation-price-id="<?php echo esc_attr( $variation_option['price_id'] ?? '' ); ?>" <?php selected( $variation_option['id'], $default_variation_id ); ?>>
											<?php echo esc_html( $variation_option['name'] ); ?>
										</option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
					<?php endif; ?>
					<button class="service-button service-button-cta" type="button" data-service-buy <?php echo empty( $default_checkout_url ) ? 'disabled' : ''; ?>>
						<span class="service-button-icon" aria-hidden="true">
							<svg viewBox="0 0 24 24" role="presentation" focusable="false">
								<path d="M7 6h-2l-1 2v2h2l2.7 8.4a2 2 0 0 0 1.9 1.4h7.8a2 2 0 0 0 1.9-1.4L22 10H8.4L7 6zm3 14a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm9 0a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
							</svg>
						</span>
						<?php esc_html_e( 'Buy Now', 'apparel' ); ?>
					</button>
					<?php if ( empty( $default_checkout_url ) ) : ?>
						<p class="service-checkout-note"><?php esc_html_e( 'Checkout link not set.', 'apparel' ); ?></p>
					<?php endif; ?>
				</div>

				<div class="service-vendor-card">
					<div class="service-vendor-avatar">
						<?php echo get_avatar( $author_id, 64 ); ?>
					</div>
					<div class="service-vendor-info">
						<h3><?php echo esc_html( $author_name ); ?></h3>
						<a class="service-button service-button-secondary" href="<?php echo esc_url( $author_url ); ?>">
							<?php esc_html_e( 'View Portfolio', 'apparel' ); ?>
						</a>
					</div>
				</div>
			</aside>
		</section>

	</div>

	<div class="service-sticky-buy" data-service-sticky aria-hidden="true">
		<button class="service-button service-button-cta" type="button" data-service-buy-sticky <?php echo empty( $default_checkout_url ) ? 'disabled' : ''; ?>>
			<span class="service-button-icon" aria-hidden="true">
				<svg viewBox="0 0 24 24" role="presentation" focusable="false">
					<path d="M7 6h-2l-1 2v2h2l2.7 8.4a2 2 0 0 0 1.9 1.4h7.8a2 2 0 0 0 1.9-1.4L22 10H8.4L7 6zm3 14a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm9 0a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
				</svg>
			</span>
			<?php esc_html_e( 'Buy Now', 'apparel' ); ?>
		</button>
	</div>

	<div class="service-gallery" data-service-gallery data-images="<?php echo esc_attr( $gallery_json ); ?>" aria-hidden="true">
		<div class="service-gallery-overlay" data-gallery-close></div>
		<div class="service-gallery-content" role="dialog" aria-modal="true">
			<button class="service-gallery-close" type="button" data-gallery-close aria-label="<?php esc_attr_e( 'Close gallery', 'apparel' ); ?>">×</button>
			<button class="service-gallery-nav" type="button" data-gallery-prev aria-label="<?php esc_attr_e( 'Previous screenshot', 'apparel' ); ?>">‹</button>
			<div class="service-gallery-media">
				<img class="service-gallery-image" alt="" src="" />
			</div>
			<button class="service-gallery-nav" type="button" data-gallery-next aria-label="<?php esc_attr_e( 'Next screenshot', 'apparel' ); ?>">›</button>
		</div>
	</div>
<?php endwhile; ?>

<?php
get_footer();
