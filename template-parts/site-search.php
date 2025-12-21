<?php
/**
 * The template part for displaying site section.
 *
 * @package Apparel
 */

?>

<div class="mbf-search">
	<div class="mbf-container">
		<h3 class="mbf-search__nav-form-heading">
			<?php echo esc_html__( 'Search', 'apparel' ); ?>
		</h3>

		<form method="get" class="mbf-search__nav-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<div class="mbf-search__group">
				<input required class="mbf-search__input" type="search" value="<?php the_search_query(); ?>" name="s" placeholder="<?php echo esc_attr( esc_html__( 'What are You Looking for?', 'apparel' ) ); ?>">

				<button class="mbf-search__submit">
					<i class="mbf-icon mbf-icon-search"></i>
				</button>
			</div>
		</form>

		<?php
		if ( class_exists( 'WooCommerce' ) ) {

			if ( get_theme_mod( 'header_search_categories_of_products', true ) ) {

				$product_cat_args = array(
					'taxonomy'   => 'product_cat',
					'hide_empty' => true,
				);

				// Filter by categories.
				$filter_categories = get_theme_mod( 'header_search_categories_of_products_slugs' );

				if ( $filter_categories ) {

					$filter_categories = array_map( 'trim', explode( ',', $filter_categories ) );

					$product_cat_args['slug']    = $filter_categories;
					$product_cat_args['orderby'] = 'slug__in';
					$product_cat_args['order']   = 'ASC';
				}

				$product_cat = get_terms( $product_cat_args );

				if ( $product_cat && ! is_wp_error( $product_cat ) ) {
					?>
					<div class="mbf-search__categories-products">
						<?php
						foreach ( $product_cat as $product_cat_term ) {
							?>
							<div class="mbf-search__categories-products-item">
								<a href="<?php echo esc_attr( get_term_link( $product_cat_term->term_id ) ); ?>">
									<?php echo esc_html( $product_cat_term->name ); ?>
								</a>
							</div>
							<?php
						}
						?>
					</div>
					<?php
				}
			}

			if ( get_theme_mod( 'header_search_products', false ) ) {
				?>
				<div class="mbf-search__products-wrapper">
					<?php
					$args = array(
						'post_type'           => 'product',
						'order'               => get_theme_mod( 'header_search_products_order', 'DESC' ),
						'orderby'             => get_theme_mod( 'header_search_products_orderby', 'date' ),
						'posts_per_page'      => 4,
						'ignore_sticky_posts' => true,
						'suppress_filters'    => false,
					);

					// Filter by categories.
					$filter_categories = get_theme_mod( 'header_search_products_filter_categories' );

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
					$filter_tags = get_theme_mod( 'header_search_products_filter_tags' );

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
					$products_ids = get_theme_mod( 'header_search_products_filter_ids' );

					if ( $products_ids ) {
						$args['post__in'] = explode( ',', str_replace( ' ', '', $products_ids ) );
					}

					$search_products = new WP_Query( $args );

					if ( $search_products->have_posts() ) {

						$products_heading = get_theme_mod( 'header_search_products_heading', esc_html__( 'You May Be Interested', 'apparel' ) );
						?>
						<div class="mbf-search__products-content">
							<?php if ( $products_heading ) { ?>
								<h3 class="mbf-search__products-heading">
									<?php echo esc_html( $products_heading ); ?>
								</h3>
							<?php } ?>

							<div class="mbf-search__products-area">
								<div class="mbf-search__products-area__outer">
									<div class="mbf-search__products-area__main woocommerce">
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
		}
		?>
	</div>
</div>
