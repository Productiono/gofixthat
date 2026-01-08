<?php
/**
 * Service preview viewer helpers.
 *
 * @package Apparel
 */

if ( ! function_exists( 'apparel_service_format_price' ) ) {
	/**
	 * Format service price for display.
	 *
	 * @param mixed $price Price string.
	 * @return string
	 */
	function apparel_service_format_price( $price ) {
		if ( '' === $price || null === $price ) {
			return '';
		}

		$price_value = (float) $price;

		return '$' . number_format_i18n( $price_value, $price_value === floor( $price_value ) ? 0 : 2 );
	}
}

if ( ! function_exists( 'apparel_service_preview_query_vars' ) ) {
	/**
	 * Register query vars for service preview viewer.
	 *
	 * @param array $vars Query vars.
	 * @return array
	 */
	function apparel_service_preview_query_vars( $vars ) {
		$vars[] = 'service_preview';
		$vars[] = 'service_id';
		$vars[] = 'service_preview_token';
		$vars[] = 'service_preview_frame';
		$vars[] = 'service_preview_url';
		return $vars;
	}
}
add_filter( 'query_vars', 'apparel_service_preview_query_vars' );

if ( ! function_exists( 'apparel_service_preview_template_route' ) ) {
	/**
	 * Route service preview viewer to the custom template.
	 *
	 * @param string $template Template path.
	 * @return string
	 */
	function apparel_service_preview_template_route( $template ) {
		if ( ! get_query_var( 'service_preview' ) ) {
			return $template;
		}

		$template_path = get_theme_file_path( '/service-preview-viewer.php' );

		if ( file_exists( $template_path ) ) {
			global $wp_query;

			if ( $wp_query instanceof WP_Query ) {
				$wp_query->is_404 = false;
			}

			status_header( 200 );

			return $template_path;
		}

		return $template;
	}
}
add_filter( 'template_include', 'apparel_service_preview_template_route', 99 );

if ( ! function_exists( 'apparel_service_preview_token_ttl' ) ) {
	/**
	 * Get the preview token expiration window.
	 *
	 * @return int
	 */
	function apparel_service_preview_token_ttl() {
		return (int) apply_filters( 'apparel_service_preview_token_ttl', 10 * MINUTE_IN_SECONDS );
	}
}

if ( ! function_exists( 'apparel_service_preview_store_token' ) ) {
	/**
	 * Store a service preview token mapping.
	 *
	 * @param int    $service_id  Service ID.
	 * @param string $preview_url Preview URL.
	 * @return string
	 */
	function apparel_service_preview_store_token( $service_id, $preview_url ) {
		$service_id  = absint( $service_id );
		$preview_url = esc_url_raw( $preview_url );

		if ( ! $service_id || empty( $preview_url ) ) {
			return '';
		}

		$token = wp_generate_password( 20, false, false );

		set_transient(
			"apparel_service_preview_{$token}",
			array(
				'service_id'  => $service_id,
				'preview_url' => $preview_url,
			),
			apparel_service_preview_token_ttl()
		);

		return $token;
	}
}

if ( ! function_exists( 'apparel_service_preview_get_token' ) ) {
	/**
	 * Fetch preview token data without consuming it.
	 *
	 * @param string $token Token string.
	 * @return array|false
	 */
	function apparel_service_preview_get_token( $token ) {
		if ( empty( $token ) ) {
			return false;
		}

		$data = get_transient( "apparel_service_preview_{$token}" );

		if ( empty( $data['service_id'] ) || empty( $data['preview_url'] ) ) {
			return false;
		}

		return $data;
	}
}

if ( ! function_exists( 'apparel_service_preview_consume_token' ) ) {
	/**
	 * Consume a preview token and return the preview URL.
	 *
	 * @param string $token      Token string.
	 * @param int    $service_id Service ID.
	 * @return string
	 */
	function apparel_service_preview_consume_token( $token, $service_id ) {
		$service_id = absint( $service_id );
		$data       = apparel_service_preview_get_token( $token );

		if ( ! $data || (int) $data['service_id'] !== $service_id ) {
			return '';
		}

		delete_transient( "apparel_service_preview_{$token}" );

		return (string) $data['preview_url'];
	}
}

if ( ! function_exists( 'apparel_service_preview_frame_url' ) ) {
	/**
	 * Build the iframe URL for a preview token.
	 *
	 * @param int    $service_id Service ID.
	 * @param string $token      Token string.
	 * @param string $target_url Optional preview URL override.
	 * @return string
	 */
	function apparel_service_preview_frame_url( $service_id, $token, $target_url = '' ) {
		if ( empty( $token ) ) {
			return '';
		}

		$args = array(
			'service_preview_frame' => 1,
			'service_id'            => absint( $service_id ),
			'service_preview_token' => $token,
		);

		if ( ! empty( $target_url ) ) {
			$args['service_preview_url'] = esc_url_raw( $target_url );
		}

		return add_query_arg( $args, home_url( '/' ) );
	}
}

if ( ! function_exists( 'apparel_service_preview_is_internal_url' ) ) {
	/**
	 * Check if a preview URL is within the same origin as the base preview URL.
	 *
	 * @param string $target_url Target URL.
	 * @param string $base_url   Base preview URL.
	 * @return bool
	 */
	function apparel_service_preview_is_internal_url( $target_url, $base_url ) {
		$target_parts = wp_parse_url( $target_url );
		$base_parts   = wp_parse_url( $base_url );

		if ( empty( $target_parts['host'] ) ) {
			return true;
		}

		if ( empty( $base_parts['host'] ) ) {
			return false;
		}

		$target_host = strtolower( $target_parts['host'] );
		$base_host   = strtolower( $base_parts['host'] );
		$target_port = isset( $target_parts['port'] ) ? (int) $target_parts['port'] : null;
		$base_port   = isset( $base_parts['port'] ) ? (int) $base_parts['port'] : null;

		if ( $target_host !== $base_host ) {
			return false;
		}

		if ( null !== $target_port || null !== $base_port ) {
			return $target_port === $base_port;
		}

		return true;
	}
}

if ( ! function_exists( 'apparel_service_preview_handle_frame' ) ) {
	/**
	 * Handle iframe preview routing for tokenized requests.
	 */
	function apparel_service_preview_handle_frame() {
		if ( ! get_query_var( 'service_preview_frame' ) ) {
			return;
		}

		$service_id = absint( get_query_var( 'service_id' ) );
		$token      = (string) get_query_var( 'service_preview_token' );
		$preview    = apparel_service_preview_get_token( $token );

		if ( ! $preview || (int) $preview['service_id'] !== $service_id ) {
			wp_die( esc_html__( 'Preview link is invalid or expired.', 'apparel' ), esc_html__( 'Preview unavailable', 'apparel' ), array( 'response' => 403 ) );
		}

		$preview_url = (string) $preview['preview_url'];
		$target_url  = (string) get_query_var( 'service_preview_url' );

		if ( ! empty( $target_url ) ) {
			$target_url = esc_url_raw( $target_url );
			if ( $target_url && apparel_service_preview_is_internal_url( $target_url, $preview_url ) ) {
				$preview_url = $target_url;
			}
		}

		wp_safe_redirect( $preview_url );
		exit;
	}
}
add_action( 'template_redirect', 'apparel_service_preview_handle_frame' );

if ( ! function_exists( 'apparel_service_preview_redirect_legacy_url' ) ) {
	/**
	 * Redirect legacy preview URLs that expose the preview URL.
	 */
	function apparel_service_preview_redirect_legacy_url() {
		if ( ! get_query_var( 'service_preview' ) ) {
			return;
		}

		$legacy_url = (string) get_query_var( 'service_preview_url' );
		if ( empty( $legacy_url ) ) {
			return;
		}

		$service_id = absint( get_query_var( 'service_id' ) );
		$token      = apparel_service_preview_store_token( $service_id, $legacy_url );

		if ( empty( $token ) ) {
			return;
		}

		$redirect_url = add_query_arg(
			array(
				'service_preview'       => 1,
				'service_id'            => $service_id,
				'service_preview_token' => $token,
			),
			home_url( '/' )
		);

		wp_safe_redirect( $redirect_url );
		exit;
	}
}
add_action( 'template_redirect', 'apparel_service_preview_redirect_legacy_url', 1 );

if ( ! function_exists( 'apparel_is_service_preview' ) ) {
	/**
	 * Check if the current request is for the service preview viewer.
	 *
	 * @return bool
	 */
	function apparel_is_service_preview() {
		return (bool) get_query_var( 'service_preview' );
	}
}

if ( ! function_exists( 'apparel_service_get_preview_viewer_url' ) ) {
	/**
	 * Build a preview viewer URL for a service.
	 *
	 * @param int    $service_id  Service ID.
	 * @param string $preview_url Preview URL.
	 * @return string
	 */
	function apparel_service_get_preview_viewer_url( $service_id, $preview_url ) {
		if ( empty( $preview_url ) ) {
			return '';
		}

		$token = apparel_service_preview_store_token( $service_id, $preview_url );

		if ( empty( $token ) ) {
			return '';
		}

		$args = array(
			'service_preview'       => 1,
			'service_id'            => absint( $service_id ),
			'service_preview_token' => $token,
		);

		return add_query_arg( $args, home_url( '/' ) );
	}
}
