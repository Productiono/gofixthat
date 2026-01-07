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

	$live_preview_url = $live_preview_url ? $live_preview_url : $checkout_url;

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

	$service_price_display = apparel_service_format_price( $service_price );
	$service_sale_display  = apparel_service_format_price( $service_sale );
	$has_sale              = '' !== $service_sale && '' !== $service_price && (float) $service_sale < (float) $service_price;
	$author_id             = (int) get_post_field( 'post_author', $service_id );
	$author_url            = get_author_posts_url( $author_id );
	$author_name           = get_the_author_meta( 'display_name', $author_id );
	$modified_label        = get_the_modified_date();
	$comments_open         = comments_open( $service_id ) || get_comments_number( $service_id );
	$gallery_json          = wp_json_encode( $gallery_images );
	$content               = apply_filters( 'the_content', get_the_content() );
	$has_h2                = false !== stripos( $content, '<h2' );
	$pricing_data_attrs    = array(
		'data-price'      => $service_price,
		'data-sale'       => $service_sale,
		'data-checkout'   => $checkout_url,
		'data-base-price' => $service_price,
		'data-base-sale'  => $service_sale,
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
					<?php if ( $preview_image ) : ?>
						<?php echo $preview_image; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php else : ?>
						<div class="service-preview-placeholder">
							<span><?php esc_html_e( 'Preview not available', 'apparel' ); ?></span>
						</div>
					<?php endif; ?>
				</div>

				<div class="service-actions">
					<?php if ( $live_preview_url ) : ?>
						<a class="service-button service-button-primary" href="<?php echo esc_url( $live_preview_url ); ?>" target="_blank" rel="noopener noreferrer">
							<span class="service-button-icon" aria-hidden="true">
								<svg viewBox="0 0 24 24" role="presentation" focusable="false">
									<path d="M3 5h18a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1h-7v2h3a1 1 0 1 1 0 2H7a1 1 0 1 1 0-2h3v-2H3a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1zm1 2v8h16V7H4z"/>
								</svg>
							</span>
							<?php esc_html_e( 'Live Preview', 'apparel' ); ?>
						</a>
					<?php endif; ?>
					<button class="service-button service-button-secondary" type="button" data-service-screenshots <?php echo empty( $gallery_images ) ? 'disabled' : ''; ?>>
						<span class="service-button-icon" aria-hidden="true">
							<svg viewBox="0 0 24 24" role="presentation" focusable="false">
								<path d="M4 5h4l1.2 2H20a1 1 0 0 1 1 1v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6a1 1 0 0 1 1-1zm1 4v8h14V9H5zm3 6l2-2 2 2 3-3 3 3H8z"/>
							</svg>
						</span>
						<?php esc_html_e( 'Screenshots', 'apparel' ); ?>
					</button>
				</div>
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
					<div class="service-quantity">
						<label for="service-quantity-input"><?php esc_html_e( 'Quantity', 'apparel' ); ?></label>
						<div class="service-quantity-control">
							<button type="button" class="service-quantity-btn" data-quantity="decrease">−</button>
							<input id="service-quantity-input" type="number" min="1" value="1" />
							<button type="button" class="service-quantity-btn" data-quantity="increase">+</button>
						</div>
					</div>
					<button class="service-button service-button-cta" type="button" data-service-buy <?php echo empty( $checkout_url ) ? 'disabled' : ''; ?>>
						<span class="service-button-icon" aria-hidden="true">
							<svg viewBox="0 0 24 24" role="presentation" focusable="false">
								<path d="M7 6h-2l-1 2v2h2l2.7 8.4a2 2 0 0 0 1.9 1.4h7.8a2 2 0 0 0 1.9-1.4L22 10H8.4L7 6zm3 14a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm9 0a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
							</svg>
						</span>
						<?php esc_html_e( 'Buy Now', 'apparel' ); ?>
					</button>
					<?php if ( empty( $checkout_url ) ) : ?>
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

		<?php if ( ! empty( $variations ) ) : ?>
			<section class="service-variations">
				<h2><?php esc_html_e( 'Variations', 'apparel' ); ?></h2>
				<div class="service-variations-list">
					<?php foreach ( $variations as $variation ) : ?>
						<?php
							$name       = isset( $variation['name'] ) ? $variation['name'] : '';
							$price      = isset( $variation['price'] ) ? $variation['price'] : '';
							$sale_price = isset( $variation['sale_price'] ) ? $variation['sale_price'] : '';
							$var_id     = isset( $variation['variation_id'] ) ? $variation['variation_id'] : '';
							$has_sale   = '' !== $sale_price && '' !== $price && (float) $sale_price < (float) $price;
							?>
							<div class="service-variation">
								<div class="service-variation-info">
									<h3><?php echo esc_html( $name ); ?></h3>
									<div class="service-variation-price">
										<?php if ( $has_sale ) : ?>
											<span class="service-price-original"><?php echo esc_html( apparel_service_format_price( $price ) ); ?></span>
											<span class="service-price-current"><?php echo esc_html( apparel_service_format_price( $sale_price ) ); ?></span>
										<?php else : ?>
											<span class="service-price-current"><?php echo esc_html( apparel_service_format_price( $price ) ); ?></span>
										<?php endif; ?>
									</div>
								</div>
								<button class="service-button service-button-ghost" type="button" data-variation-select data-variation-id="<?php echo esc_attr( $var_id ); ?>" data-variation-price="<?php echo esc_attr( $price ); ?>" data-variation-sale="<?php echo esc_attr( $sale_price ); ?>">
									<?php esc_html_e( 'Select', 'apparel' ); ?>
								</button>
							</div>
					<?php endforeach; ?>
				</div>
			</section>
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
					<?php if ( $support_url ) : ?>
						<a class="service-support-tile" href="<?php echo esc_url( $support_url ); ?>" target="_blank" rel="noopener noreferrer">
							<h3><?php esc_html_e( 'Get Support', 'apparel' ); ?></h3>
							<p><?php esc_html_e( 'Reach out for help with setup and troubleshooting.', 'apparel' ); ?></p>
						</a>
					<?php endif; ?>
					<?php if ( $docs_url ) : ?>
						<a class="service-support-tile" href="<?php echo esc_url( $docs_url ); ?>" target="_blank" rel="noopener noreferrer">
							<h3><?php esc_html_e( 'Documentation', 'apparel' ); ?></h3>
							<p><?php esc_html_e( 'Browse guides, FAQs, and helpful resources.', 'apparel' ); ?></p>
						</a>
					<?php endif; ?>
					<?php if ( $presales_url ) : ?>
						<a class="service-support-tile" href="<?php echo esc_url( $presales_url ); ?>" target="_blank" rel="noopener noreferrer">
							<h3><?php esc_html_e( 'Pre-Sales Questions', 'apparel' ); ?></h3>
							<p><?php esc_html_e( 'Ask about features, licensing, or delivery.', 'apparel' ); ?></p>
						</a>
					<?php endif; ?>
					<?php if ( $hire_url ) : ?>
						<a class="service-support-tile" href="<?php echo esc_url( $hire_url ); ?>" target="_blank" rel="noopener noreferrer">
							<h3><?php esc_html_e( 'Hire Us', 'apparel' ); ?></h3>
							<p><?php esc_html_e( 'Schedule custom work or ongoing support.', 'apparel' ); ?></p>
						</a>
					<?php endif; ?>
					<?php if ( ! $support_url && ! $docs_url && ! $presales_url && ! $hire_url ) : ?>
						<p><?php esc_html_e( 'Support resources are not available.', 'apparel' ); ?></p>
					<?php endif; ?>
				</div>
			</div>
		</section>
	</div>

	<div class="service-gallery" data-service-gallery data-images="<?php echo esc_attr( $gallery_json ); ?>" aria-hidden="true">
		<div class="service-gallery-overlay" data-gallery-close></div>
		<div class="service-gallery-content" role="dialog" aria-modal="true">
			<button class="service-gallery-close" type="button" data-gallery-close aria-label="<?php esc_attr_e( 'Close gallery', 'apparel' ); ?>">×</button>
			<button class="service-gallery-nav" type="button" data-gallery-prev aria-label="<?php esc_attr_e( 'Previous screenshot', 'apparel' ); ?>">‹</button>
			<img class="service-gallery-image" alt="" src="" />
			<button class="service-gallery-nav" type="button" data-gallery-next aria-label="<?php esc_attr_e( 'Next screenshot', 'apparel' ); ?>">›</button>
		</div>
	</div>
<?php endwhile; ?>

<?php
get_footer();
