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

		$args = array(
			'service_preview'     => 1,
			'service_id'          => absint( $service_id ),
			'service_preview_url' => $preview_url,
		);

		return add_query_arg( $args, home_url( '/' ) );
	}
}
