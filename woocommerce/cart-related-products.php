<?php
/**
 * The template part for displaying shop cart related products.
 *
 * @package Apparel
 */

if ( get_theme_mod( 'woocommerce_cart_related_products', true ) ) {
	?>
	<div class="mbf-wc-cart-related__products-wrapper">
		<?php
		$args = array(
			'post_type'           => 'product',
			'order'               => get_theme_mod( 'woocommerce_cart_related_products_order', 'DESC' ),
			'orderby'             => get_theme_mod( 'woocommerce_cart_related_products_orderby', 'date' ),
			'posts_per_page'      => 4,
			'ignore_sticky_posts' => true,
			'suppress_filters'    => false,
		);

		// Filter by categories.
		$filter_categories = get_theme_mod( 'woocommerce_cart_related_products_filter_categories' );

		if ( $filter_categories ) {
			$filter_categories = array_map( 'trim', explode( ',', $filter_categories ) );

			$args['tax_query'] = isset( $args['tax_query'] ) ? $args['tax_query'] : array();

			$args['tax_query'][] = array(
				'taxonomy'         => 'product_cat',
				'field'            => 'slug',
				'terms'            => $filter_categories,
				'include_children' => true,
			);
		}

		// Filter by tags.
		$filter_tags = get_theme_mod( 'woocommerce_cart_related_products_filter_tags' );

		if ( $filter_tags ) {
			$filter_tags = array_map( 'trim', explode( ',', $filter_tags ) );

			$args['tax_query'] = isset( $args['tax_query'] ) ? $args['tax_query'] : array();

			$args['tax_query'][] = array(
				'taxonomy'         => 'product_tag',
				'field'            => 'slug',
				'terms'            => $filter_tags,
				'include_children' => true,
			);
		}

		// Filter by products.
		$products_ids = get_theme_mod( 'woocommerce_cart_related_products_filter_ids' );

		if ( $products_ids ) {
			$args['post__in'] = explode( ',', str_replace( ' ', '', $products_ids ) );
		}

		$search_products = new WP_Query( $args );

		if ( $search_products->have_posts() ) {

			$products_subheading = get_theme_mod( 'woocommerce_cart_related_products_subheading', esc_html__( 'You May Be Interested', 'apparel' ) );
			$products_heading    = get_theme_mod( 'woocommerce_cart_related_products_heading', esc_html__( 'Related Products', 'apparel' ) );
			$products_more_label = get_theme_mod( 'woocommerce_cart_related_products_more_label', esc_html__( 'Discover More', 'apparel' ) );
			$products_more_link  = get_theme_mod( 'woocommerce_cart_related_products_more_link', '' );
			?>
			<div class="mbf-wc-cart-related__products-content">
				<?php if ( $products_subheading || $products_heading || ( $products_more_label && $products_more_link ) ) { ?>
					<div class="mbf-wc-cart-related__products-header">
						<?php if ( $products_subheading || $products_heading ) { ?>
							<div class="mbf-wc-cart-related__products-header-primary">
								<?php if ( $products_subheading ) { ?>
									<div class="mbf-wc-cart-related__products-subheading">
										<?php echo esc_html( $products_subheading ); ?>
									</div>
								<?php } ?>

								<?php if ( $products_heading ) { ?>
									<h2 class="mbf-wc-cart-related__products-heading">
										<?php echo esc_html( $products_heading ); ?>
									</h2>
								<?php } ?>
							</div>
						<?php } ?>

						<?php if ( $products_more_label && $products_more_link ) { ?>
							<div class="mbf-wc-cart-related__products-more">
								<a href="<?php echo esc_attr( $products_more_link  ); ?>">
									<?php echo esc_html( $products_more_label ); ?>
								</a>
							</div>
						<?php } ?>
					</div>
				<?php } ?>

				<div class="mbf-wc-cart-related__products-area">
					<div class="mbf-wc-cart-related__products-area__outer">
						<div class="mbf-wc-cart-related__products-area__main woocommerce">
							<?php
							wc_set_loop_prop( 'columns', 4 );

							woocommerce_product_loop_start();

							while ( $search_products->have_posts() ) {
								$search_products->the_post();
								/**
								 * The woocommerce_shop_loop hook.
								 *
								 * @since 1.0.0
								 */
								do_action( 'woocommerce_shop_loop' );

								wc_get_template_part( 'content', 'product' );
							}

							woocommerce_product_loop_end();
							?>
						</div>
					</div>
				</div>
			</div>
			<?php
		}

		wp_reset_postdata();

		wc_reset_loop();
		?>
	</div>
	<?php
}
