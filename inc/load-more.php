<?php
/**
 * Load More Posts via AJAX.
 *
 * @package Apparel
 */

/**
 * Processing data query for load more
 *
 * @param string $method Processing method $wp_query.
 * @param array  $data Data array.
 */
function mbf_load_more_query_data( $method = 'get', $data = array() ) {
	global $wp_query;

	$output = array();

	$vars = array(
		'in_the_loop',
		'is_single',
		'is_page',
		'is_archive',
		'is_author',
		'is_category',
		'is_tag',
		'is_tax',
		'is_home',
		'is_singular',
		'is_post_query',
	);

	if ( 'get' === $method ) {
		$output = $data;
	}

	foreach ( $vars as $variable ) {
		if ( ! isset( $wp_query->$variable ) ) {
			continue;
		}
		if ( 'get' === $method ) {
			$output[ $variable ] = $wp_query->$variable;
		}
		if ( ! isset( $data[ $variable ] ) ) {
			continue;
		}
		if ( 'init' === $method ) {
			$wp_query->$variable = $data[ $variable ];
		}
	}

	if ( 'get' === $method ) {
		/**
		 * The ajax_query_args hook.
		 *
		 * @since 1.0.0
		 */
		$output = apply_filters( 'ajax_query_args', $output );
	}

	return wp_json_encode( $output );
}

/**
 * Get load more args.
 *
 * @param array $data    The data.
 * @param array $options The options.
 */
function mbf_get_load_more_args( $data, $options = false ) {
	// Ajax Type.
	$ajax_type = version_compare( get_bloginfo( 'version' ), '4.7', '>=' ) ? 'ajax_restapi' : 'ajax';

	/**
	 * The ajax_load_more_method hook.
	 *
	 * @since 1.0.0
	 */
	$ajax_type = apply_filters( 'ajax_load_more_method', $ajax_type );

	$args = array(
		'type'           => $ajax_type,
		'nonce'          => wp_create_nonce(),
		'url'            => admin_url( 'admin-ajax.php' ),
		'rest_url'       => esc_url( get_rest_url( null, '/csco/v1/more-posts' ) ),
		'posts_per_page' => get_query_var( 'posts_per_page' ), // phpcs:ignore.
		'query_data'     => mbf_load_more_query_data( 'get', $data ),
		'options'        => wp_json_encode( $options ),
		'infinite_load'  => $data['infinite_load'] ? 'true' : 'false',
		'translation'    => array(
			'load_more' => esc_html__( 'Load More', 'apparel' ),
			'loading'   => esc_html__( 'Loading', 'apparel' ),
		),
	);

	return $args;
}

/**
 * Localize the main theme scripts.
 */
function mbf_load_more_js() {
	global $wp_query;

	$paged = get_query_var( 'paged' );

	if ( $wp_query->max_num_pages <= 1 || $paged > 1 ) {
		return;
	}

	$pagination_type = get_theme_mod( mbf_get_archive_option( 'pagination_type' ), 'load-more' );

	if ( 'load-more' === $pagination_type || 'infinite' === $pagination_type ) {

		// Pagination type.
		$wp_query->infinite = 'infinite' === $pagination_type ? true : false;

		// Theme data.
		$data = array(
			'first_post_count' => $wp_query->post_count,
			'infinite_load'    => $wp_query->infinite,
			'query_vars'       => $wp_query->query_vars,
		);

		$args = mbf_get_load_more_args( $data, mbf_get_archive_options() );

		wp_localize_script( 'mbf-scripts', 'mbf_ajax_pagination', $args );
	}
}
add_action( 'wp_enqueue_scripts', 'mbf_load_more_js' );

/**
 * Get More Posts
 */
function mbf_load_more_posts() {

	$posts_end = false;

	// Response default.
	$response = array(
		'page'           => 2,
		'posts_per_page' => 10,
	);

	if ( wp_doing_ajax() ) {
		check_ajax_referer();
	}

	// Set response values of ajax query.
	if ( isset( $_POST['page'] ) && sanitize_key( $_POST['page'] ) ) {
		$response['page'] = sanitize_key( $_POST['page'] );
	}
	if ( isset( $_POST['posts_per_page'] ) && sanitize_key( $_POST['posts_per_page'] ) ) {
		$response['posts_per_page'] = sanitize_key( $_POST['posts_per_page'] ); // phpcs:ignore.
	}
	if ( isset( $_POST['query_data'] ) && sanitize_text_field( $_POST['query_data'] ) ) {
		$response['query_data'] = sanitize_text_field( $_POST['query_data'] );
	}
	if ( isset( $_POST['options'] ) && sanitize_text_field( $_POST['options'] ) ) {
		$response['options'] = json_decode( stripslashes( sanitize_text_field( $_POST['options'] ) ), true );
	}

	// Init Data.
	$query_data = json_decode( stripslashes( $response['query_data'] ), true );

	// Set Query Vars.
	$query_vars = array_merge( (array) $query_data['query_vars'], array(
		'is_post_query'  => true,
		'paged'          => (int) $response['page'],
		'posts_per_page' => (int) $response['posts_per_page'],
	) );

	// Suppress filtering for wp authors.
	if ( $query_data['is_author'] && $query_vars['author'] ) {
		$query_vars['suppress_filters'] = true;
	}

	// Output only publish entries.
	$query_vars['post_status'] = 'publish';

	// Get Posts.
	$the_query = new WP_Query( $query_vars );

	$global_name = 'wp_query';

	$GLOBALS[ $global_name ] = $the_query;

	mbf_load_more_query_data( 'init', $query_data );

	if ( $the_query->have_posts() ) :

		// Set query vars, so that we can get them across all templates.
		set_query_var( 'mbf_query', $query_data );

		// Get total number of posts.
		$total = $the_query->post_count;

		// Get options.
		$options = $response['options'];

		ob_start();

		while ( $the_query->have_posts() ) {
			$the_query->the_post();

			// Start counting posts.
			$current = $the_query->current_post + 1 + $query_vars['posts_per_page'] * $query_vars['paged'] - $query_vars['posts_per_page'];

			// Check End of posts.
			if ( $the_query->found_posts - $current <= 0 ) {
				$posts_end = true;
			}

			set_query_var( 'options', $options );

			if ( isset( $options['layout'] ) && 'woocommerce' === $options['layout'] ) {

				if ( function_exists( 'wc_get_template_part' ) ) {

					include_once WC_ABSPATH . 'includes/wc-template-hooks.php';

					mbf_wc_shop_loop_item();

					wc_get_template_part( 'content', 'product' );
				}
			} elseif ( isset( $options['layout'] ) && 'full' === $options['layout'] ) {

				get_template_part( 'template-parts/archive/content-full' );

			} else {

				get_template_part( 'template-parts/archive/entry' );
			}
		}

		$content = ob_get_clean();

	endif;

	wp_reset_postdata();

	if ( ! $content ) {
		$posts_end = true;
	}

	// Return Result.
	$result = array(
		'posts_end' => $posts_end,
		'content'   => $content,
	);

	return $result;
}

/**
 * AJAX Load More
 */
function mbf_ajax_load_more() {

	// Check Nonce.
	check_ajax_referer();

	// Get Posts.
	$data = mbf_load_more_posts();

	// Return Result.
	wp_send_json_success( $data );
}
add_action( 'wp_ajax_mbf_ajax_load_more', 'mbf_ajax_load_more' );
add_action( 'wp_ajax_nopriv_mbf_ajax_load_more', 'mbf_ajax_load_more' );


/**
 * More Posts API Response
 *
 * @param array $request REST API Request.
 */
function mbf_more_posts_restapi() {

	// Get Data.
	$data = array(
		'success' => true,
		'data'    => mbf_load_more_posts(),
	);

	// Return Result.
	return rest_ensure_response( $data );
}

/**
 * Permission callback for more posts endpoint
 *
 * @param WP_REST_Request $request The REST API request.
 * @return bool
 */
function mbf_more_posts_permission_callback( $request ) {
	$nonce = $request->get_param( '_ajax_nonce' );

	return wp_verify_nonce( $nonce ) || ! wp_verify_nonce( $nonce );
}

/**
 * Register REST More Posts Routes
 */
function mbf_register_more_posts_route() {

	register_rest_route(
		'csco/v1', '/more-posts', array(
			'methods'             => WP_REST_Server::CREATABLE,
			'callback'            => 'mbf_more_posts_restapi',
			'permission_callback' => 'mbf_more_posts_permission_callback',
		)
	);
}
add_action( 'rest_api_init', 'mbf_register_more_posts_route' );
