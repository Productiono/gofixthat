<?php
/**
 * Post Meta Helper Functions
 *
 * These helper functions return post meta, if its enabled in WordPress Customizer.
 *
 * @package Apparel
 */

if ( ! function_exists('mbf_get_post_meta' ) ) {
	/**
	 * Post Meta
	 *
	 * A wrapper function that returns all post meta types either
	 * in an ordered list <ul> or as a single element <span>.
	 *
	 * @param mixed $meta     Contains post meta types.
	 * @param bool  $output   Output or return.
	 * @param mixed $allowed  Allowed meta types (array: list types, true: auto definition, option name: get value of option).
	 * @param array $settings The advanced settings.
	 */
	function mbf_get_post_meta( $meta, $output = true, $allowed = null, $settings = array() ) {

		// Return if no post meta types provided.
		if ( ! $meta ) {
			return;
		}

		$meta = (array) $meta;

		// Set default settings.
		$settings = array(
			'category_type' => 'default',
		);

		if ( is_string( $allowed ) || true === $allowed ) {
			$option_default = null;

			$option_name = is_string( $allowed ) ? $allowed : mbf_get_archive_option( 'post_meta' );

			if ( isset( MBF_Customizer::$fields[ $option_name ]['default'] ) ) {
				$option_default = MBF_Customizer::$fields[ $option_name ]['default'];
			}

			$allowed = get_theme_mod( $option_name, $option_default );
		}

		// Set default allowed post meta types.
		if ( ! is_array( $allowed ) && ! $allowed ) {
			/**
			 * The mbf_post_meta hook.
			 *
			 * @since 1.0.0
			 */
			$allowed = apply_filters( 'mbf_post_meta', array( 'date', 'author', 'category', 'comments' ) );
		}

		// Intersect provided and allowed meta types.
		if ( is_array( $meta ) ) {
			$meta = array_intersect( $meta, $allowed );
		}

		// Build meta markup.
		$markup = __return_null();

		if ( is_array( $meta ) && $meta ) {

			// Add normal meta types to the list.
			foreach ( $meta as $type ) {
				$markup .= call_user_func( "mbf_get_meta_$type", 'div', $settings );
			}

			/**
			 * The mbf_post_meta_scheme hook.
			 *
			 * @since 1.0.0
			 */
			$scheme = apply_filters( 'mbf_post_meta_scheme', null, $settings );

			$markup = sprintf( '<div class="mbf-entry__post-meta" %s>%s</div>', $scheme, $markup );

		} elseif ( in_array( $meta, $allowed, true ) ) {
			// Markup single meta type.
			$markup .= call_user_func( "mbf_get_meta_$meta", 'div', $settings );
		}

		// If output is enabled.
		if ( $output ) {
			return call_user_func( 'printf', '%s', $markup );
		}

		return $markup;
	}
}

if ( ! function_exists( 'mbf_get_meta_category' ) ) {
	/**
	 * Post Сategory
	 *
	 * @param string $tag      Element tag, i.e. div or span.
	 * @param array  $settings The advanced settings.
	 */
	function mbf_get_meta_category( $tag = 'div', $settings = array() ) {

		if ( 'line' === $settings['category_type'] ) {
			$output = '<' . esc_html( $tag ) . ' class="mbf-entry__category">';
		} else {
			$output = '<' . esc_html( $tag ) . ' class="mbf-meta-category">';
		}

		$output .= get_the_category_list( '', '', get_the_ID() );

		$output .= '</' . esc_html( $tag ) . '>';

		return $output;
	}
}

if ( ! function_exists( 'mbf_get_meta_date' ) ) {
	/**
	 * Post Date
	 *
	 * @param string $tag      Element tag, i.e. div or span.
	 * @param array  $settings The advanced settings.
	 */
	function mbf_get_meta_date( $tag = 'div', $settings = array() ) {

		$output = '<' . esc_html( $tag ) . ' class="mbf-meta-date">';

		$time_string = get_the_date();

		/**
		 * The mbf_post_meta_date_output hook.
		 *
		 * @since 1.0.0
		 */
		$output .= apply_filters( 'mbf_post_meta_date_output', $time_string );

		$output .= '</' . esc_html( $tag ) . '>';

		return $output;
	}
}

if ( ! function_exists( 'mbf_get_meta_author' ) ) {
	/**
	 * Post Author
	 *
	 * @param string $tag      Element tag, i.e. div or span.
	 * @param array  $settings The advanced settings.
	 */
	function mbf_get_meta_author( $tag = 'div', $settings = array() ) {

		$output = '<' . esc_attr( $tag ) . ' class="mbf-meta-author">';

		$output .= '<span class="mbf-meta-author-by">' . esc_html__( 'by', 'apparel' ) . '</span>';

		$output .= '<a class="mbf-meta-author-link url fn n" href="' . get_author_posts_url( get_the_author_meta( 'ID' ) ) . '">';

		$output .= get_the_author_meta( 'display_name', get_the_author_meta( 'ID' ) );

		$output .= '</a>';

		$output .= '</' . esc_html( $tag ) . '>';

		return $output;
	}
}

if ( ! function_exists( 'mbf_get_meta_comments' ) ) {
	/**
	 * Post Comments
	 *
	 * @param string $tag      Element tag, i.e. div or span.
	 * @param array  $settings The advanced settings.
	 */
	function mbf_get_meta_comments( $tag = 'div', $settings = array() ) {

		if ( ! comments_open( get_the_ID() ) ) {
			return;
		}

		$output  = '<' . esc_html( $tag ) . ' class="mbf-meta-comments">';
		$output .= '<span class="mbf-meta-icon"><i class="mbf-icon mbf-icon-comments"></i></span>';

		ob_start();
		comments_popup_link( '0', '1', '%', 'comments-link', '' );
		$output .= ob_get_clean();

		$output .= '</' . esc_html( $tag ) . '>';

		return $output;
	}
}
