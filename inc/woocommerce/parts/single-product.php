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
