<?php
/**
 * WooCommerce single product
 *
 * @package Apparel
 */

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
remove_action( 'woocommerce_review_before', 'woocommerce_review_display_gravatar', 10 );
remove_action( 'woocommerce_review_meta', 'woocommerce_review_display_meta', 10 );

add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 10 );
add_action( 'woocommerce_review_after_comment_text', 'woocommerce_review_display_meta', 70 );

/**
 * Single Wrapper Start
 */
function mbf_wc_single_wrapper_start() {
	?>
	<div class="mbf-single-product">
	<?php
}
add_action( 'woocommerce_before_single_product', 'mbf_wc_single_wrapper_start', 1 );

/**
 * Single Wrapper End
 */
function mbf_wc_single_wrapper_end() {
	?>
	</div>
	<?php
}
add_action( 'woocommerce_after_single_product', 'mbf_wc_single_wrapper_end', 999 );

/**
 * Single Product Summary Inner Start
 */
function mbf_wc_single_product_summary_inner_start() {
	?>
	<div class="entry-summary-inner">
	<?php
}
add_action( 'woocommerce_before_single_product_summary', 'mbf_wc_single_product_summary_inner_start', 25 );

/**
 * Single Product Summary Inner End
 */
function mbf_wc_single_product_summary_inner_end() {
	?>
	</div>
	<?php
}
add_action( 'woocommerce_after_single_product_summary', 'mbf_wc_single_product_summary_inner_end', 998 );

/**
 * Single Product Summary Start
 */
function mbf_wc_single_product_summary_start() {
	?>
	<div class="mbf-single-product-summary-wrap">
		<div class="mbf-single-product-summary">
	<?php
}
add_action( 'woocommerce_before_single_product_summary', 'mbf_wc_single_product_summary_start', 1 );

/**
 * Single Product Media Start
 */
function mbf_wc_single_product_media_start() {
	if ( ! is_product() ) {
		return;
	}
	?>
	<div class="mbf-single-product-media">
	<?php
}
add_action( 'woocommerce_before_single_product_summary', 'mbf_wc_single_product_media_start', 9 );

/**
 * Get persisted cart add count for social proof widget.
 *
 * @param int $product_id Product ID.
 */
function mbf_wc_social_proof_get_cart_count( $product_id ) {
	$product_id = absint( $product_id );

	if ( ! $product_id ) {
		return 0;
	}

	return absint( get_post_meta( $product_id, '_mbf_social_proof_cart_count', true ) );
}

/**
 * Seed viewing count for social proof widget.
 */
function mbf_wc_social_proof_get_viewing_seed() {
	static $seed = null;

	if ( null === $seed ) {
		$seed = wp_rand( 2, 6 );
	}

	return $seed;
}

/**
 * Safely retrieve the current product object.
 *
 * @return WC_Product|null
 */
function mbf_wc_social_proof_get_current_product() {
	global $product;

	if ( $product instanceof WC_Product ) {
		return $product;
	}

	if ( is_numeric( $product ) ) {
		$product_object = wc_get_product( absint( $product ) );

		if ( $product_object instanceof WC_Product ) {
			$product = $product_object;
			return $product_object;
		}
	}

	return null;
}

/**
 * Render social proof widget next to the gallery.
 */
function mbf_wc_social_proof_widget() {
	if ( ! is_product() ) {
		return;
	}

	$product = mbf_wc_social_proof_get_current_product();

	if ( ! $product ) {
		return;
	}

	$product_id        = $product->get_id();
	$viewing_seed      = mbf_wc_social_proof_get_viewing_seed();
	$cart_adds_24h     = mbf_wc_social_proof_get_cart_count( $product_id );
	$viewing_label     = esc_html__( 'people are viewing this item', 'apparel' );
	$added_label       = esc_html__( 'people have added this item to cart', 'apparel' );
	$viewing_icon_path = 'M12 5a3 3 0 1 0 0 6 3 3 0 0 0 0-6Zm0-2c5.25 0 9.75 3.5 11 6-1.25 2.5-5.75 6-11 6S2.25 11.5 1 9c1.25-2.5 5.75-6 11-6Z';
	$cart_icon_path    = 'M3 3h2l1 2h11.6a1 1 0 0 1 .97 1.242l-1.5 6A1 1 0 0 1 16.1 13H7.9l-.4 2H17v2H6a1 1 0 0 1-.97-1.242L5.6 11H4V9h1.2l1-5H3V3Zm4.4 4-1 5h9.3l1.1-5H7.4Z';
	?>
	<div class="mbf-social-proof" data-product-id="<?php echo esc_attr( $product_id ); ?>" aria-live="polite">
		<div class="mbf-social-proof-badge mbf-social-proof-badge--viewing">
			<span class="mbf-social-proof-icon" aria-hidden="true">
				<svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" role="presentation">
					<path d="<?php echo esc_attr( $viewing_icon_path ); ?>" fill="currentColor" />
				</svg>
			</span>
			<span class="mbf-social-proof-text">
				<span class="mbf-social-proof-count" data-social-proof-viewing><?php echo esc_html( $viewing_seed ); ?></span>
				<?php echo esc_html( $viewing_label ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</span>
		</div>
		<div class="mbf-social-proof-badge mbf-social-proof-badge--added">
			<span class="mbf-social-proof-icon" aria-hidden="true">
				<svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" role="presentation">
					<path d="<?php echo esc_attr( $cart_icon_path ); ?>" fill="currentColor" />
				</svg>
			</span>
			<span class="mbf-social-proof-text">
				<span class="mbf-social-proof-count" data-social-proof-added><?php echo esc_html( $cart_adds_24h ); ?></span>
				<?php echo esc_html( $added_label ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</span>
		</div>
	</div>
	<?php
}
add_action( 'woocommerce_before_single_product_summary', 'mbf_wc_social_proof_widget', 15 );

/**
 * Increment cart add count when product is added to cart.
 *
 * @param string $cart_item_key   Cart item key.
 * @param int    $product_id      Product ID.
 * @param int    $quantity        Quantity.
 * @param int    $variation_id    Variation ID.
 * @param array  $cart_item_data  Cart item data.
 * @param int    $cart_id         Cart ID.
 */
function mbf_wc_social_proof_increment_cart_count( $cart_item_key, $product_id, $quantity, $variation_id, $cart_item_data, $cart_id ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
	unset( $cart_item_key, $cart_item_data, $cart_id );

	$product_id = absint( $product_id );
	$quantity   = absint( $quantity );

	if ( ! $product_id || $quantity < 1 ) {
		return;
	}

	$current = mbf_wc_social_proof_get_cart_count( $product_id );

	update_post_meta( $product_id, '_mbf_social_proof_cart_count', $current + $quantity );
}
add_action( 'woocommerce_add_to_cart', 'mbf_wc_social_proof_increment_cart_count', 10, 6 );

/**
 * Enqueue inline scripts for social proof widget.
 */
function mbf_wc_social_proof_inline_scripts() {
	if ( ! is_product() ) {
		return;
	}

	$product = mbf_wc_social_proof_get_current_product();

	if ( ! $product ) {
		return;
	}

	wp_enqueue_script( 'mbf-scripts' );

	$product_id = $product->get_id();

	$settings = array(
		'productId'   => $product_id,
		'viewingSeed' => mbf_wc_social_proof_get_viewing_seed(),
		'viewingMin'  => 2,
		'viewingMax'  => 6,
		'intervalMin' => 5000,
		'intervalMax' => 10000,
		'addedCount'  => mbf_wc_social_proof_get_cart_count( $product_id ),
	);

	$settings_script = 'window.mbfSocialProof = ' . wp_json_encode( $settings ) . ';';

	wp_add_inline_script( 'mbf-scripts', $settings_script, 'before' );

	$script = <<<JS
(function($){
	var data = window.mbfSocialProof || {};
	var $widget = $('.mbf-social-proof[data-product-id="' + data.productId + '"]');

	if (!$widget.length) {
		return;
	}

	var $viewing = $widget.find('[data-social-proof-viewing]');
	var $added = $widget.find('[data-social-proof-added]');

	var viewingMin = parseInt(data.viewingMin, 10) || 2;
	var viewingMax = parseInt(data.viewingMax, 10) || 6;
	var intervalMin = parseInt(data.intervalMin, 10) || 5000;
	var intervalMax = parseInt(data.intervalMax, 10) || 10000;

	var viewing = parseInt(data.viewingSeed, 10);
	if (isNaN(viewing)) {
		viewing = viewingMin;
	}

	var added = parseInt(data.addedCount, 10);
	if (isNaN(added)) {
		added = 0;
	}

	function scheduleViewingUpdate() {
		var delay = Math.floor(Math.random() * (intervalMax - intervalMin + 1)) + intervalMin;
		setTimeout(function() {
			var deltas = [-1, 0, 1];
			var delta = deltas[Math.floor(Math.random() * deltas.length)];
			var next = viewing + delta;

			if (next < viewingMin) {
				next = viewing + 1;
			} else if (next > viewingMax) {
				next = viewing - 1;
			}

			if (next === viewing) {
				next = Math.min(viewingMax, Math.max(viewingMin, viewing + (Math.random() > 0.5 ? 1 : -1)));
			}

			viewing = Math.min(viewingMax, Math.max(viewingMin, next));
			$viewing.text(viewing);

			scheduleViewingUpdate();
		}, delay);
	}

	scheduleViewingUpdate();

	function incrementAdded(qty) {
		var quantity = parseInt(qty, 10);
		if (isNaN(quantity) || quantity < 1) {
			quantity = 1;
		}
		added += quantity;
		$added.text(added);
	}

	$(document.body).on('added_to_cart', function(event, fragments, cart_hash, \$button){
		var buttonProductId = parseInt($button && $button.data('product_id'), 10);
		if (buttonProductId && buttonProductId === parseInt(data.productId, 10)) {
			var qty = $button.data('quantity') || 1;
			incrementAdded(qty);
		}
	});
})(jQuery);
JS;

	wp_add_inline_script( 'mbf-scripts', $script );
}
add_action( 'wp_enqueue_scripts', 'mbf_wc_social_proof_inline_scripts', 25 );

/**
 * Single Product Media End
 */
function mbf_wc_single_product_media_end() {
	if ( ! is_product() ) {
		return;
	}
	?>
	</div>
	<?php
}
add_action( 'woocommerce_before_single_product_summary', 'mbf_wc_single_product_media_end', 24 );

/**
 * Single Product Summary End
 */
function mbf_wc_single_product_summary_end() {
	?>
		</div>
	</div>
	<?php
	woocommerce_output_product_data_tabs();
	woocommerce_upsell_display();

	get_template_part( 'woocommerce/single-promo-block' );

	woocommerce_output_related_products();
}
add_action( 'woocommerce_after_single_product_summary', 'mbf_wc_single_product_summary_end', 999 );

/**
 * Enqueue Convai widget on single product pages.
 */
function mbf_wc_enqueue_convai_widget() {
	if ( ! is_product() ) {
		return;
	}

	wp_enqueue_script( 'mbf-elevenlabs-convai', 'https://unpkg.com/@elevenlabs/convai-widget-embed', array(), null, true );
	wp_script_add_data( 'mbf-elevenlabs-convai', 'async', true );
}
add_action( 'wp_enqueue_scripts', 'mbf_wc_enqueue_convai_widget' );

/**
 * Render Convai widget on single product pages.
 */
function mbf_wc_render_convai_widget() {
	if ( ! is_product() ) {
		return;
	}
	?>
	<div class="mbf-convai-widget">
		<elevenlabs-convai agent-id="agent_5101kd03cw4kfmvv6eq5qhdkgb05"></elevenlabs-convai>
	</div>
	<?php
}
add_action( 'woocommerce_after_single_product_summary', 'mbf_wc_render_convai_widget', 50 );

/**
 * Output related products subheading
 */
function mbf_wc_product_related_products_subheading() {

	$related_subheading = get_theme_mod( 'woocommerce_product_related_subheading', esc_html__( 'You May Be Interested', 'apparel' ) );
	?>
	<div class="related-subheading">
		<?php echo esc_html( $related_subheading ); ?>
	</div>
	<?php
}
add_action( 'woocommerce_product_related_products_heading', 'mbf_wc_product_related_products_subheading' );

/**
 * Change related products heading
 *
 * @param string $heading The heading.
 */
function mbf_wc_product_related_products_heading( $heading ) {

	$heading = get_theme_mod( 'woocommerce_product_related_heading', esc_html__( 'Related Products', 'apparel' ) );

	return $heading;
}
add_filter( 'woocommerce_product_related_products_heading', 'mbf_wc_product_related_products_heading' );

/**
 * Change upsells products heading
 *
 * @param string $heading The heading.
 */
function mbf_wc_product_upsells_products_heading( $heading ) {

	$heading = esc_html__( 'Similar Products', 'apparel' );

	return $heading;
}
add_filter( 'woocommerce_product_upsells_products_heading', 'mbf_wc_product_upsells_products_heading' );

/**
 * Change dropdown variation attribute options_args
 *
 * @param array $args The args.
 */
function mbf_wc_dropdown_variation_attribute_options_args( $args ) {

	$args['show_option_none'] = esc_html__( 'Choose', 'apparel' );

	return $args;
}
add_filter( 'woocommerce_dropdown_variation_attribute_options_args', 'mbf_wc_dropdown_variation_attribute_options_args' );

/**
 * Change review comment form args
 *
 * @param array $args The args.
 */
function mbf_wc_change_review_comment_form_args( $args ) {

	$args['comment_field']    = str_replace( 'name="comment"', sprintf( 'name="comment" placeholder="%s"', esc_html__( 'Your review *', 'apparel' ) ), $args['comment_field'] );
	$args['fields']['author'] = str_replace( 'name="author"', sprintf( 'name="author" placeholder="%s"', esc_html__( 'Name *', 'apparel' ) ), $args['fields']['author'] );
	$args['fields']['email']  = str_replace( 'name="email"', sprintf( 'name="email" placeholder="%s"', esc_html__( 'Email *', 'apparel' ) ), $args['fields']['email'] );

	return $args;
}
add_filter( 'woocommerce_product_review_comment_form_args', 'mbf_wc_change_review_comment_form_args' );
