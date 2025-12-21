<?php
/**
 * WooCommerce theme search
 *
 * @package Apparel
 */

/**
 * Search Results
 */
function mbf_wc_search_results() {
	if ( ! is_search() ) {
		return;
	}

	$args = array(
		'post_type'           => 'product',
		'posts_per_page'      => (int) get_option( 'posts_per_page', 10 ),
		'ignore_sticky_posts' => true,
		'suppress_filters'    => false,
		's'                   => get_search_query(),
	);

	$posts = new WP_Query( $args );

	if ( $posts->have_posts() ) {

		set_query_var( 'mbf_have_search', true );

		// Load more data.
		$load_more_data = __return_null();

		if ( $posts->max_num_pages > 1 ) {

			// Theme data.
			$data = array(
				'infinite_load' => __return_false(),
				'query_vars'    => $posts->query,
			);

			$query_args = mbf_get_load_more_args( $data, array(
				'layout' => 'woocommerce',
			) );

			// Set area count.
			$query_args['posts_per_page'] = $args['posts_per_page'];

			$load_more_data = mbf_encode_data( $query_args );
		}
		?>
			<div class="mbf-posts-area-woocommerce">
				<div class="mbf-posts-area-header">
					<div class="mbf-posts-area-header-label">
						<?php esc_html_e( 'Items', 'apparel' ); ?>
					</div>
				</div>

				<div class="mbf-posts-area" data-posts-area="<?php echo esc_attr( $load_more_data ); ?>">
					<div class="mbf-posts-area__outer">
						<div class="mbf-posts-area__main woocommerce">
							<?php
							wc_set_loop_prop( 'columns', 4 );

							woocommerce_product_loop_start();

							while ( $posts->have_posts() ) {
								$posts->the_post();
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
	?>
	<?php
	wp_reset_postdata();

	wc_reset_loop();
}
add_action( 'mbf_main_before', 'mbf_wc_search_results', 100 );
