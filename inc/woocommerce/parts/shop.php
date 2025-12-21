<?php
/**
 * WooCommerce common
 *
 * @package Apparel
 */

remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );

/**
 * Shop Minicart
 */
function mbf_shop_minicart() {
	get_template_part( 'woocommerce/shop-minicart' );
}
add_action( 'mbf_site_before', 'mbf_shop_minicart' );

/**
 * Shop Off-canvas
 */
function mbf_shop_offcanvas() {
	if ( is_active_sidebar( 'shop-sidebar' ) ) {
		?>
			<div class="mbf-shop-sidebar-overlay"></div>
		<?php
	}
}
add_action( 'mbf_site_before', 'mbf_shop_offcanvas' );

/**
 * Shop Archive Featured Image
 */
function mbf_wc_archive_featured_image() {
	$shop_id = wc_get_page_id( 'shop' );

	if ( is_shop() && ! is_paged() && $shop_id && has_post_thumbnail( $shop_id ) ) {
		?>
			<div class="woocommerce-products-header-media">
				<div class="woocommerce-products-header-media-inner">
					<?php echo wp_get_attachment_image( get_post_thumbnail_id( $shop_id ), 'mbf-large-uncropped' ); ?>
				</div>
			</div>
		<?php
	}

	if ( is_tax( 'product_cat' ) && ! is_paged() ) {
		$mbf_hero_image = get_term_meta( get_queried_object_id(), 'mbf_hero_image', true );

		if ( $mbf_hero_image ) {
			?>
			<div class="woocommerce-products-header-media">
				<div class="woocommerce-products-header-media-inner">
					<?php echo wp_get_attachment_image( $mbf_hero_image, 'mbf-large-uncropped' ); ?>
				</div>
			</div>
			<?php
		}
	}
}
add_action( 'woocommerce_archive_description', 'mbf_wc_archive_featured_image', 20 );

/**
 * Wrapper Start
 */
function mbf_wc_wrapper_start() {
	?>
	<div id="primary" class="mbf-content-area">

		<?php
		/**
		 * The mbf_wc_main_before hook.
		 *
		 * @since 1.0.0
		 */
		do_action( 'mbf_wc_main_before' );
		?>

		<div class="woocommerce-area">
	<?php
}
add_action( 'woocommerce_before_main_content', 'mbf_wc_wrapper_start', 1 );

/**
 * Wrapper End
 */
function mbf_wc_wrapper_end() {
	?>
		</div>
	</div>
	<?php
}
add_action( 'woocommerce_after_main_content', 'mbf_wc_wrapper_end', 999 );

/**
 * Add wrap to products - Start
 */
function mbf_wc_products_wrap_start() {
	if ( ! is_shop() && ! is_product_category() && ! is_product_tag() ) {
		return;
	}

	$wrap_class = __return_empty_string();

	if ( is_active_sidebar( 'shop-sidebar' ) ) {
		$wrap_class .= ' woocommerce-products-wrap__with-sidebar';
	}
	?>
	<div class="woocommerce-products-wrap <?php echo esc_attr( $wrap_class ); ?>">
		<?php if ( is_active_sidebar( 'shop-sidebar' ) ) { ?>
			<aside class="woocommerce-products-wrap__sidebar">
				<div class="woocommerce-products-wrap__sidebar-header">
					<?php
					/**
					 * The mbf_shop_offcanvas_header_start hook.
					 *
					 * @since 1.0.0
					 */
					do_action( 'mbf_shop_offcanvas_header_start' );
					?>

					<nav class="woocommerce-products-wrap__sidebar-nav">
						<span class="woocommerce-products-wrap__sidebar-nav-headline">
							<span class="woocommerce-products-wrap__sidebar-nav-headline-label">
								<?php echo esc_html( get_theme_mod( 'woocommerce_product_catalog_offcanvas_label', esc_html__( 'Filters', 'apparel' ) ) ); ?>
							</span>
						</span>

						<span class="woocommerce-products-wrap__sidebar-toggle" role="button"><i class="mbf-icon mbf-icon-x"></i></span>
					</nav>

					<?php
					/**
					 * The mbf_shop_offcanvas_header_end hook.
					 *
					 * @since 1.0.0
					 */
					do_action( 'mbf_shop_offcanvas_header_end' );
					?>
				</div>

				<div class="woocommerce-products-wrap__sidebar-outer">
					<div class="woocommerce-products-wrap__sidebar-inner mbf-widget-area">
						<?php dynamic_sidebar( 'shop-sidebar' ); ?>
					</div>
				</div>
			</aside>
		<?php } ?>

		<div class="woocommerce-products-wrap__content">
	<?php
}
add_action( 'woocommerce_before_shop_loop', 'mbf_wc_products_wrap_start' );
add_action( 'woocommerce_no_products_found', 'mbf_wc_products_wrap_start', 0 );

/**
 * Add wrap to products - End
 */
function mbf_wc_products_wrap_end() {
	if ( ! is_shop() && ! is_product_category() && ! is_product_tag() ) {
		return;
	}
	?>
		</div>
	</div>
	<?php
}
add_action( 'woocommerce_after_shop_loop', 'mbf_wc_products_wrap_end' );
add_action( 'woocommerce_no_products_found', 'mbf_wc_products_wrap_end', 99 );

/**
 * Add subheader to products - Start
 */
function mbf_wc_products_subheader_start() {
	?>
	<div class="woocommerce-products-subheader">
		<?php if ( is_active_sidebar( 'shop-sidebar' ) ) { ?>
			<a class="mbf-shop-sidebar__toggle">
				<?php echo esc_html( get_theme_mod( 'woocommerce_product_catalog_offcanvas_label', esc_html__( 'Filters', 'apparel' ) ) ); ?>
			</a>
		<?php } ?>
	<?php
}
add_action( 'woocommerce_before_shop_loop', 'mbf_wc_products_subheader_start', 15 );

/**
 * Add subheader to products - End
 */
function mbf_wc_products_subheader_end() {
	?>
	</div>
	<?php
}
add_action( 'woocommerce_before_shop_loop', 'mbf_wc_products_subheader_end', 999 );

/**
 * The product image itself is hooked in at priority 10 using woocommerce_template_loop_product_thumbnail(),
 * so priority 9 and 11 are used to open and close the div.
 */
add_action( 'woocommerce_before_shop_loop_item_title', function() {
	echo '<div class="woocommerce-thumbnail">';
}, 9 );

add_action( 'woocommerce_before_shop_loop_item_title', function() {
	echo '</div>';
}, 11 );

/**
 * Woocommerce loop add to cart
 */
function mbf_wc_shop_loop_item() {
	if ( ! get_theme_mod( 'woocommerce_product_catalog_cart', false ) ) {
		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart' );
	}
}
add_action( 'template_redirect', 'mbf_wc_shop_loop_item' );

if ( ! function_exists( 'woocommerce_template_loop_product_title' ) ) {
	/**
	 * Show the product title in the product loop. By default this is an H2.
	 */
	function woocommerce_template_loop_product_title() {
		global $product;

		ob_start();
		if ( $product && taxonomy_exists( 'pa_color' ) ) {
			$terms = wc_get_product_terms( $product->get_id(), 'pa_color', array(
				'fields' => 'all',
			) );

			if ( method_exists( $product, 'get_variation_attributes' ) && count( $terms ) > 1 ) {
				$attribute_keys   = array_keys( $product->get_variation_attributes() );
				$variations_json  = wp_json_encode( $product->get_available_variations()  );
				$variations_attr  = function_exists( 'wc_esc_json' ) ? wc_esc_json( $variations_json ) : _wp_specialchars( $variations_json, ENT_QUOTES, 'UTF-8', true );
				$dafault_pa_color = $product->get_variation_default_attribute( 'pa_color' );
				?>
				<span class="woocommerce-loop-product__color_variations" data-product_variations="<?php call_user_func( 'printf', '%s', $variations_attr ); ?>">
					<?php
					foreach ( $terms as $term ) {
						$color = get_term_meta( $term->term_id, 'product_attribute_color', true );

						$variation_class = ( $dafault_pa_color && $term->slug === $dafault_pa_color ) ? 'is-variation-default is-active' : '';
						?>
							<span class="woocommerce-loop-product__color_variation <?php echo esc_attr( $variation_class ); ?>" style="--mbf-color: <?php echo esc_attr( $color ? $color : 'transparent' ); ?>" title="<?php echo esc_attr( $term->name ); ?>" data-color="<?php echo esc_attr( $term->slug ); ?>"></span>
						<?php
					}
					?>
				</span>
				<?php
			}
		}
		$color_variations = ob_get_clean();

		/**
		 * The woocommerce_product_loop_title_classes hook.
		 *
		 * @since 1.0.0
		 */
		$woocommerce_product_loop_title_classes = apply_filters( 'woocommerce_product_loop_title_classes', 'woocommerce-loop-product__title' );
		?>
		<h2 class="<?php echo esc_attr( $woocommerce_product_loop_title_classes ); ?>">
			<span class="woocommerce-loop-product__title-span"><?php echo wp_kses( get_the_title(), 'post' ); ?></span>

			<?php call_user_func( 'printf', '%s', $color_variations ); ?>
		</h2>
		<?php
	}
}
