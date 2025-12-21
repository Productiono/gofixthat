<?php
/**
 * Theme Functions
 *
 * Utility functions.
 *
 * @package Apparel
 */

if ( ! function_exists( 'mbf_doing_request' ) ) {
	/**
	 * Determines whether the current request is a WordPress REST or Ajax request.
	 */
	function mbf_doing_request() {
		if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
			return true;
		}
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return true;
		}
	}
}

if ( ! function_exists( 'mbf_is_context_editor' ) ) {
	/**
	 * Determines whether the current request is from WordPress Editor.
	 */
	function mbf_is_context_editor() {
		// nosemgrep: scanner.php.wp.security.csrf.verify-nonce-not-in-statement
		wp_verify_nonce( null );

		if ( isset( $_REQUEST['context'] ) && 'edit' === $_REQUEST['context'] ) { // Input var ok; sanitization ok.
			return true;
		}
	}
}

if ( ! function_exists( 'mbf_style' ) ) {
	/**
	 * Processing path of style.
	 *
	 * @param string $path URL to the stylesheet.
	 */
	function mbf_style( $path ) {
		// Check RTL.
		if ( is_rtl() ) {
			return $path;
		}

		// Check Dev.
		$dev = get_theme_file_path( 'style-dev.css' );

		if ( file_exists( $dev ) ) {
			return str_replace( '.css', '-dev.css', $path );
		}

		return $path;
	}
}

if ( ! function_exists( 'mbf_typography' ) ) {
	/**
	 * Output typography style.
	 *
	 * @param string $field   The field name of kirki.
	 * @param string $type    The type of typography.
	 * @param string $default The default value.
	 */
	function mbf_typography( $field, $type, $default ) {
		$value = $default;

		$field_value = get_theme_mod( $field );

		if ( is_array( $field_value ) && $field_value ) {
			if ( isset( $field_value[ $type ] ) ) {
				$value = $field_value[ $type ];
			}
		}

		echo wp_kses( $value, 'content' );
	}
}

if ( ! function_exists( 'mbf_enqueue_typography_styles' ) ) {
	/**
	 * Includes typography styles.
	 *
	 * @param string $handle Handle of the style to which typography styles will be added.
	 */
	function mbf_enqueue_typography_styles( $handle ) {
		ob_start();

		require get_template_directory() . '/inc/typography.php';

		$typography_css = ob_get_clean();

		if ( $typography_css ) {
			wp_add_inline_style( $handle, $typography_css );
		}
	}
}

if ( ! function_exists( 'mbf_component' ) ) {
	/**
	 * Display or return the component from the theme
	 *
	 * @param string $name     The name of component.
	 * @param bool   $output   Output or return.
	 * @param array  $settings The advanced settings.
	 */
	function mbf_component( $name, $output = true, $settings = array() ) {

		global $mbf_components;

		$func_name = sprintf( 'mbf_%s', $name );

		// Set cache key.
		$cache_key = sprintf( '%s_%s', $name, md5( maybe_serialize( $settings ) ) );

		// Get component from object cache.
		$markup = isset( $mbf_components[ $cache_key ] ) ? $mbf_components[ $cache_key ] : null;

		// Call component.
		if ( empty( $markup ) && function_exists( $func_name ) ) {
			ob_start();
			/**
			 * The mbf_component_before hook.
			 *
			 * @since 1.0.0
			 */
			do_action( 'mbf_component_before', $name, $settings );

			call_user_func( $func_name, $settings );

			/**
			 * The mbf_component_after hook.
			 *
			 * @since 1.0.0
			 */
			do_action( 'mbf_component_after', $name, $settings );

			$markup = ob_get_clean();

			if ( ! $mbf_components ) {
				$mbf_components = array();
			}

			$mbf_components[ $cache_key ] = $markup;
		}

		// If there is no markup.
		if ( ! $markup ) {
			return;
		}

		// If output is enabled.
		if ( $output ) {
			return call_user_func( 'printf', '%s', $markup );
		}

		return $markup;
	}
}

if ( ! function_exists( 'mbf_get_theme_data' ) ) {
	/**
	 * Get data about the theme.
	 *
	 * @param mixed $name The name of param.
	 */
	function mbf_get_theme_data( $name ) {
		$theme = wp_get_theme( get_template() );

		return $theme->get( $name );
	}
}

if ( ! function_exists( 'mbf_encode_data' ) ) {
	/**
	 * Encode data
	 *
	 * @param  mixed  $content    The content.
	 * @param  string $secret_key The key.
	 * @return string
	 */
	function mbf_encode_data( $content, $secret_key = 'apparel' ) {

		$content = wp_json_encode( $content );

		return call_user_func( sprintf( 'base64_%s', 'encode' ), $content );
	}
}

if ( ! function_exists( 'mbf_decode_data' ) ) {
	/**
	 * Decode data
	 *
	 * @param  string $content    The content.
	 * @param  string $secret_key The key.
	 * @return string
	 */
	function mbf_decode_data( $content, $secret_key = 'apparel' ) {

		$content = call_user_func( sprintf( 'base64_%s', 'decode' ), $content );

		return json_decode( $content, true );
	}
}

if ( ! function_exists( 'mbf_hex2rgba' ) ) {
	/**
	 * Convert hex to rgb.
	 *
	 * @param mixed $hex    Color.
	 * @param bool  $format Format.
	 */
	function mbf_hex2rgba( $hex, $format = true ) {
		$hex = trim( $hex, ' #' );

		$size = strlen( $hex );
		if ( 3 === $size || 4 === $size ) {
			$parts = str_split( $hex, 1 );
			$hex   = '';
			foreach ( $parts as $row ) {
				$hex .= $row . $row;
			}
		}

		$dec = hexdec( $hex );
		$rgb = array();

		if ( 3 === $size || 6 === $size ) {
			$rgb['red']   = 0xFF & ( $dec >> 0x10 );
			$rgb['green'] = 0xFF & ( $dec >> 0x8 );
			$rgb['blue']  = 0xFF & $dec;

			$output = implode( ',', $rgb );

			if ( $format ) {
				$output = sprintf( 'rgba(%s, 1)', $output );
			}

			return $output;

		} elseif ( 5 === $size || 8 === $size ) {
			$rgb['red']   = 0xFF & ( $dec >> 0x16 );
			$rgb['green'] = 0xFF & ( $dec >> 0x10 );
			$rgb['blue']  = 0xFF & ( $dec >> 0x8 );

			$output = implode( ',', $rgb );

			if ( $format ) {
				$alpha = 0xFF & $dec;

				$output = sprintf( 'rgba(%s, %s)', $output, round( ( $alpha / ( 255 / 100 ) ) / 100, 2 ) );
			}

			return $output;
		}
	}
}

if ( ! function_exists( 'mbf_rgba2hex' ) ) {
	/**
	 * Convert rgba to hex.
	 *
	 * @param mixed $color Color.
	 */
	function mbf_rgba2hex( $color ) {
		if ( isset( $color[0] ) && '#' === $color[0] ) {
			return $color;
		}

		$rgba = array();

		if ( preg_match_all( '#\((([^()]+|(?R))*)\)#', $color, $matches ) ) {
			$rgba = explode( ',', implode( ' ', $matches[1] ) );
		} else {
			$rgba = explode( ',', $color );
		}

		$rr = dechex( $rgba['0'] );
		$gg = dechex( $rgba['1'] );
		$bb = dechex( $rgba['2'] );

		if ( array_key_exists( '3', $rgba ) ) {
			$aa = dechex( $rgba['3'] * 255 );

			return strtoupper( "#$aa$rr$gg$bb" );
		} else {
			return strtoupper( "#$rr$gg$bb" );
		}
	}
}

if ( ! function_exists( 'mbf_get_round_number' ) ) {
	/**
	 * Get rounded number.
	 *
	 * @param int $number    Input number.
	 * @param int $min_value Minimum value to round number.
	 * @param int $decimal   How may decimals shall be in the rounded number.
	 */
	function mbf_get_round_number( $number, $min_value = 1000, $decimal = 1 ) {
		if ( $number < $min_value ) {
			return number_format_i18n( $number );
		}
		$alphabets = array(
			1000000000 => esc_html__( 'B', 'apparel' ),
			1000000    => esc_html__( 'M', 'apparel' ),
			1000       => esc_html__( 'K', 'apparel' ),
		);
		foreach ( $alphabets as $key => $value ) {
			if ( $number >= $key ) {
				return number_format_i18n( round( $number / $key, $decimal ), $decimal ) . $value;
			}
		}
	}
}

if ( ! function_exists( 'mbf_the_round_number' ) ) {
	/**
	 * Echo rounded number.
	 *
	 * @param int $number    Input number.
	 * @param int $min_value Minimum value to round number.
	 * @param int $decimal   How may decimals shall be in the rounded number.
	 */
	function mbf_the_round_number( $number, $min_value = 1000, $decimal = 1 ) {
		echo esc_html( mbf_get_round_number( $number, $min_value, $decimal ) );
	}
}

if ( ! function_exists( 'mbf_str_truncate' ) ) {
	/**
	 * Truncates string with specified length
	 *
	 * @param  string $string      Text string.
	 * @param  int    $length      Letters length.
	 * @param  string $etc         End truncate.
	 * @param  bool   $break_words Break words or not.
	 * @return string
	 */
	function mbf_str_truncate( $string, $length = 80, $etc = '&hellip;', $break_words = false ) {
		if ( 0 === $length ) {
			return '';
		}

		if ( function_exists( 'mb_strlen' ) ) {

			// MultiBite string functions.
			if ( mb_strlen( $string ) > $length ) {
				$length -= min( $length, mb_strlen( $etc ) );
				if ( ! $break_words ) {
					$string = preg_replace( '/\s+?(\S+)?$/', '', mb_substr( $string, 0, $length + 1 ) );
				}

				return mb_substr( $string, 0, $length ) . $etc;
			}
		} else {

			// Default string functions.
			if ( strlen( $string ) > $length ) {
				$length -= min( $length, strlen( $etc ) );
				if ( ! $break_words ) {
					$string = preg_replace( '/\s+?(\S+)?$/', '', substr( $string, 0, $length + 1 ) );
				}

				return substr( $string, 0, $length ) . $etc;
			}
		}

		return $string;
	}
}

if ( ! function_exists( 'mbf_get_retina_image' ) ) {
	/**
	 * Get retina image.
	 *
	 * @param int    $attachment_id Image attachment ID.
	 * @param array  $attr          Attributes for the image markup. Default empty.
	 * @param string $type          The tag of type.
	 */
	function mbf_get_retina_image( $attachment_id, $attr = array(), $type = 'img' ) {
		$attachment_url = wp_get_attachment_url( $attachment_id );

		// Retina image.
		$attached_file = get_attached_file( $attachment_id );

		if ( $attached_file ) {
			$uriinfo  = pathinfo( $attachment_url );
			$pathinfo = pathinfo( $attached_file );

			$retina_uri  = sprintf( '%s/%s@2x.%s', $uriinfo['dirname'], $uriinfo['filename'], $uriinfo['extension'] );
			$retina_file = sprintf( '%s/%s@2x.%s', $pathinfo['dirname'], $pathinfo['filename'], $pathinfo['extension'] );

			if ( file_exists( $retina_file ) ) {
				$attr['srcset'] = sprintf( '%s 1x, %s 2x', $attachment_url, $retina_uri );
			}
		}

		// Sizes.
		if ( 'amp-img' === $type ) {
			$data = wp_get_attachment_image_src( $attachment_id, 'full' );

			if ( isset( $data[1] ) ) {
				$attr['width'] = $data[1];
			}
			if ( isset( $data[2] ) ) {
				$attr['height'] = $data[2];
			}

			// Calc max height and set new width depending on proportion.
			if ( isset( $attr['width'] ) && isset( $attr['height'] ) ) {
				/**
				 * The mbf_amp_navbar_height hook.
				 *
				 * @since 1.0.0
				 */
				$max_height = apply_filters( 'mbf_amp_navbar_height', 60 ) - 20;

				if ( $max_height > 0 && $attr['height'] > $max_height ) {
					$attr['width'] = $attr['width'] / $attr['height'] * $max_height;

					$attr['height'] = $max_height;
				}
			}
		}

		// Attr.
		$output = __return_null();

		foreach ( $attr as $name => $value ) {
			$output .= sprintf( ' %s="%s" ', esc_attr( $name ), esc_attr( $value ) );
		}

		// Image output.
		call_user_func( 'printf', '<%1$s src="%2$s" %3$s>', esc_attr( $type ), esc_url( $attachment_url ), $output );
	}
}

if ( ! function_exists( 'mbf_offcanvas_exists' ) ) {
	/**
	 * Check if offcanvas exists.
	 */
	function mbf_offcanvas_exists() {
		$locations = get_nav_menu_locations();

		if ( isset( $locations['primary'] ) || isset( $locations['mobile'] ) || is_active_sidebar( 'sidebar-offcanvas' ) ) {
			return true;
		}
	}
}

if ( ! function_exists( 'mbf_site_content_class' ) ) {
	/**
	 * Display the classes for the mbf-site-content element.
	 *
	 * @param array $class Classes to add to the class list.
	 */
	function mbf_site_content_class( $class = array() ) {
		$class[] = 'mbf-site-content';

		/**
		 * The mbf_site_content_class hook.
		 *
		 * @since 1.0.0
		 */
		$class = apply_filters( 'mbf_site_content_class', $class );

		// Separates classes with a single space, collates classes.
		echo sprintf( 'class="%s"', esc_attr( join( ' ', $class ) ) );
	}
}

if ( ! function_exists( 'mbf_site_submenu_class' ) ) {
	/**
	 * Display the classes for the site-submenu element.
	 *
	 * @param array $class Classes to add to the class list.
	 */
	function mbf_site_submenu_class( $class = array() ) {
		$class[] = 'mbf-site-submenu';

		/**
		 * The mbf_site_submenu_class hook.
		 *
		 * @since 1.0.0
		 */
		$class = apply_filters( 'mbf_site_submenu_class', $class );

		// Separates classes with a single space, collates classes.
		echo sprintf( 'class="%s"', esc_attr( join( ' ', $class ) ) );
	}
}

if ( ! function_exists( 'mbf_site_scheme_data' ) ) {
	/**
	 * Get site scheme data
	 */
	function mbf_site_scheme_data() {

		// Get options.
		$color_scheme = get_theme_mod( 'color_scheme', 'system' ); // Field. User’s system preference.
		$color_toggle = get_theme_mod( 'color_scheme_toggle', true ); // Field. Enable dark/light mode toggle.

		// Set site scheme.
		$site_scheme = __return_empty_string();

		switch ( $color_scheme ) {
			case 'dark':
				$site_scheme = 'dark';
				break;
			case 'light':
				$site_scheme = 'light';
				break;
			case 'system':
				$site_scheme = 'auto';
				break;
		}

		if ( $color_toggle ) {
			if ( isset( $_COOKIE['_color_schema'] ) && 'light' === $_COOKIE['_color_schema'] ) {
				$site_scheme = 'light';
			}
			if ( isset( $_COOKIE['_color_schema'] ) && 'dark' === $_COOKIE['_color_schema'] ) {
				$site_scheme = 'dark';
			}
		}

		return $site_scheme;
	}
}

if ( ! function_exists( 'mbf_get_the_excerpt' ) ) {
	/**
	 * Filters the number of words in an excerpt.
	 */
	function mbf_get_the_excerpt_length() {
		return 5000;
	}

	/**
	 * Get excerpt of post.
	 *
	 * @param int    $length      Letters length.
	 * @param string $etc         End truncate.
	 * @param bool   $break_words Break words or not.
	 */
	function mbf_get_the_excerpt( $length = 80, $etc = '&hellip;', $break_words = false ) {
		add_filter( 'excerpt_length', 'mbf_get_the_excerpt_length' );

		$excerpt = get_the_excerpt();

		call_user_func( 'remove_filter', 'excerpt_length', 'mbf_get_the_excerpt_length' );

		return mbf_str_truncate( $excerpt, $length, $etc, $break_words );
	}
}

if ( ! function_exists( 'mbf_get_archive_location' ) ) {
	/**
	 * Returns Archive Location.
	 */
	function mbf_get_archive_location() {

		global $wp_query;

		if ( isset( $wp_query->query_vars['mbf_query']['location'] ) ) {

			return $wp_query->query_vars['mbf_query']['location'];
		}

		if ( is_home() ) {

			return 'home';

		} else {

			return 'archive';
		}
	}
}

if ( ! function_exists( 'mbf_get_archive_option' ) ) {
	/**
	 * Returns Archive Option Name.
	 *
	 * @param string $option_name The customize option name.
	 */
	function mbf_get_archive_option( $option_name ) {

		return mbf_get_archive_location() . '_' . $option_name;
	}
}

if ( ! function_exists( 'mbf_get_archive_options' ) ) {
	/**
	 * Returns Archive Options.
	 */
	function mbf_get_archive_options() {

		$options = array(
			'location'          => mbf_get_archive_location(),
			'meta'              => mbf_get_archive_option( 'post_meta' ),
			'layout'            => get_theme_mod( mbf_get_archive_option( 'layout' ), 'list' ),
			'columns'           => get_theme_mod( mbf_get_archive_option( 'columns_desktop' ), 4 ),
			'image_orientation' => get_theme_mod( mbf_get_archive_option( 'image_orientation' ), 'original' ),
			'image_size'        => get_theme_mod( mbf_get_archive_option( 'image_size' ), 'mbf-thumbnail' ),
			'summary_type'      => get_theme_mod( mbf_get_archive_option( 'summary' ), 'summary' ),
			'excerpt'           => get_theme_mod( mbf_get_archive_option( 'excerpt' ), 'excerpt', true ),
		);

		/**
		 * The mbf_get_archive_options hook.
		 *
		 * @since 1.0.0
		 */
		$options = apply_filters( 'mbf_get_archive_options', $options );

		return $options;
	}
}

if ( ! function_exists( 'mbf_get_page_preview' ) ) {
	/**
	 * Returns Page Preview.
	 */
	function mbf_get_page_preview() {

		if ( is_home() ) {
			/**
			 * The mbf_page_media_preview hook.
			 *
			 * @since 1.0.0
			 */
			return apply_filters( 'mbf_page_media_preview', get_theme_mod( 'home_media_preview', 'uncropped' ) );
		}

		if ( is_archive() ) {
			/**
			 * The mbf_page_media_preview hook.
			 *
			 * @since 1.0.0
			 */
			return apply_filters( 'mbf_page_media_preview', get_theme_mod( 'archive_media_preview', 'uncropped' ) );
		}

		if ( is_404() ) {
			/**
			 * The mbf_page_media_preview hook.
			 *
			 * @since 1.0.0
			 */
			return apply_filters( 'mbf_page_media_preview', 'uncropped' );
		}

		/**
		 * The mbf_page_media_preview hook.
		 *
		 * @since 1.0.0
		 */
		return apply_filters( 'mbf_page_media_preview', 'uncropped' );
	}
}

if ( ! function_exists( 'mbf_get_page_sidebar' ) ) {
	/**
	 * Returns Page Sidebar: right, left or disabled.
	 *
	 * @param int    $post_id The ID of post.
	 * @param string $layout  The layout of post.
	 */
	function mbf_get_page_sidebar( $post_id = false, $layout = false ) {
		/**
		 * The mbf_sidebar hook.
		 *
		 * @since 1.0.0
		 */
		$location = apply_filters( 'mbf_sidebar', 'sidebar-main' );

		if ( ! is_active_sidebar( $location ) ) {
			return 'disabled';
		}

		if ( 'template-with-sidebar.php' === get_post_meta( $post_id ? $post_id : get_queried_object_id(), '_wp_page_template', true ) ) {
			/**
			 * The mbf_page_sidebar hook.
			 *
			 * @since 1.0.0
			 */
			return apply_filters( 'mbf_page_sidebar', 'right' );
		}

		/**
		 * The mbf_page_sidebar hook.
		 *
		 * @since 1.0.0
		 */
		return apply_filters( 'mbf_page_sidebar', 'disabled' );
	}
}

if ( ! function_exists( 'mbf_get_page_header_type' ) ) {
	/**
	 * Returns Page Header
	 */
	function mbf_get_page_header_type() {

		$allow = array( 'none', 'standard', 'title' );

		if ( is_singular( array( 'post', 'page' ) ) ) {
			$page_header_type = get_post_meta( get_queried_object_id(), 'mbf_page_header_type', true );

			if ( ! in_array( $page_header_type, $allow, true ) || 'default' === $page_header_type ) {

				$post_type = get_post_type( get_queried_object_id() );

				/**
				 * The mbf_page_header_type hook.
				 *
				 * @since 1.0.0
				 */
				return apply_filters( 'mbf_page_header_type', get_theme_mod( $post_type . '_header_type', 'standard' ) );
			}

			/**
			 * The mbf_page_header_type hook.
			 *
			 * @since 1.0.0
			 */
			return apply_filters( 'mbf_page_header_type', $page_header_type );
		}

		/**
		 * The mbf_page_header_type hook.
		 *
		 * @since 1.0.0
		 */
		return apply_filters( 'mbf_page_header_type', 'standard' );
	}
}

if ( ! function_exists( 'mbf_get_page_id_by_title' ) ) {
	/**
	 * Get page id by title
	 *
	 * @param string $title Page title.
	 */
	function mbf_get_page_id_by_title( $title ) {
		$query = new WP_Query();

		$pages = $query->query( array(
			'post_type' => 'page',
			'title'     => $title,
		) );

		if ( $pages ) {
			foreach ( $pages as $find_page ) {
				return $find_page->ID;
			}
		}
	}
}

if ( ! function_exists( 'mbf_breadcrumbs' ) ) {
	/**
	 * SEO Breadcrumbs
	 */
	function mbf_breadcrumbs() {

		if ( mbf_doing_request() ) {
			return;
		}

		ob_start();

		if ( function_exists( 'yoast_breadcrumb' ) ) {
			yoast_breadcrumb( '<div class="mbf-breadcrumbs" id="breadcrumbs">', '</div>' );
		}

		$output_breadcrumbs = ob_get_clean();

		/**
		 * The mbf_breadcrumbs hook.
		 *
		 * @since 1.0.0
		 */
		$output_breadcrumbs = apply_filters( 'mbf_breadcrumbs', $output_breadcrumbs );

		call_user_func( 'printf', '%s', $output_breadcrumbs );
	}
}

if ( ! function_exists( 'mbf_get_available_image_sizes' ) ) {
	/**
	 * Get the available image sizes
	 */
	function mbf_get_available_image_sizes() {
		$wais = & $GLOBALS['_wp_additional_image_sizes'];

		$sizes       = array();
		$image_sizes = get_intermediate_image_sizes();

		if ( is_array( $image_sizes ) && $image_sizes ) {
			foreach ( $image_sizes as $size ) {
				if ( in_array( $size, array( 'thumbnail', 'medium', 'medium_large', 'large' ), true ) ) {
					$sizes[ $size ] = array(
						'width'  => get_option( "{$size}_size_w" ),
						'height' => get_option( "{$size}_size_h" ),
						'crop'   => (bool) get_option( "{$size}_crop" ),
					);
				} elseif ( isset( $wais[ $size ] ) ) {
					$sizes[ $size ] = array(
						'width'  => $wais[ $size ]['width'],
						'height' => $wais[ $size ]['height'],
						'crop'   => $wais[ $size ]['crop'],
					);
				}

				// Size registered, but has 0 width and height.
				if ( 0 === (int) $sizes[ $size ]['width'] && 0 === (int) $sizes[ $size ]['height'] ) {
					unset( $sizes[ $size ] );
				}
			}
		}

		return $sizes;
	}
}

if ( ! function_exists( 'mbf_get_image_size' ) ) {
	/**
	 * Gets the data of a specific image size.
	 *
	 * @param string $size Name of the size.
	 */
	function mbf_get_image_size( $size ) {
		if ( ! is_string( $size ) ) {
			return;
		}

		$sizes = mbf_get_available_image_sizes();

		return isset( $sizes[ $size ] ) ? $sizes[ $size ] : false;
	}
}

if ( ! function_exists( 'mbf_get_list_available_image_sizes' ) ) {
	/**
	 * Get the list available image sizes
	 */
	function mbf_get_list_available_image_sizes() {

		$image_sizes = wp_cache_get( 'mbf_available_image_sizes' );

		if ( empty( $image_sizes ) ) {
			$image_sizes = array();

			$intermediate_image_sizes = get_intermediate_image_sizes();

			foreach ( $intermediate_image_sizes as $size ) {
				$image_sizes[ $size ] = $size;

				$data = mbf_get_image_size( $size );

				if ( isset( $data['width'] ) || isset( $data['height'] ) ) {

					$width  = '~';
					$height = '~';

					if ( isset( $data['width'] ) && $data['width'] ) {
						$width = $data['width'] . 'px';
					}
					if ( isset( $data['height'] ) && $data['height'] ) {
						$height = $data['height'] . 'px';
					}

					$image_sizes[ $size ] .= sprintf( ' [%s, %s]', $width, $height );
				}
			}

			wp_cache_set( 'mbf_available_image_sizes', $image_sizes );
		}

		return $image_sizes;
	}
}

if ( ! function_exists( 'mbf_get_page_by_title' ) ) {
	/**
	 * Retrieves a page given its title.
	 *
	 * @param string $page_title Page title.
	 * @param string $output     The required return type.
	 * @param string $post_type  Post type or array of post types
	 */
	function mbf_get_page_by_title( $page_title, $output = OBJECT, $post_type = 'page' ) {
		$page_query = new WP_Query(
			array(
				'post_type'              => $post_type,
				'title'                  => trim( $page_title ),
				'post_status'            => 'all',
				'posts_per_page'         => 1,
				'no_found_rows'          => true,
				'ignore_sticky_posts'    => true,
				'update_post_term_cache' => false,
				'update_post_meta_cache' => false,
				'orderby'                => 'post_date ID',
				'order'                  => 'ASC',
			)
		);

		if ( ! empty( $page_query->post ) ) {
			$page_got_by_title = $page_query->post;
		} else {
			$page_got_by_title = null;
		}

		wp_reset_postdata();

		return $page_got_by_title;
	}
}
