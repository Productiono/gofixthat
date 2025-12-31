<?php
/**
 * Assets
 *
 * All enqueues of scripts and styles.
 *
 * @package Apparel
 */

if ( ! function_exists( 'mbf_content_width' ) ) {
	/**
	 * Set the content width in pixels, based on the theme's design and stylesheet.
	 *
	 * Priority 0 to make it available to lower priority callbacks.
	 *
	 * @global int $content_width
	 */
	function mbf_content_width() {
		/**
		 * The mbf_content_width hook.
		 *
		 * @since 1.0.0
		 */
		$GLOBALS['content_width'] = apply_filters( 'mbf_content_width', 1200 );
	}
}
add_action( 'after_setup_theme', 'mbf_content_width', 0 );

if ( ! function_exists( 'mbf_enqueue_scripts' ) ) {
	/**
	 * Enqueue scripts and styles.
	 */
	function mbf_enqueue_scripts() {

		$version = mbf_get_theme_data( 'Version' );

		// Register theme scripts.
		wp_register_script( 'flickity', get_template_directory_uri() . '/assets/static/js/flickity.pkgd.min.js', array( 'jquery' ), $version, true );
		wp_register_script( 'jarallax', get_template_directory_uri() . '/assets/static/js/jarallax.min.js', array( 'jquery' ), $version, true );
		wp_register_script( 'mbf-scripts', get_template_directory_uri() . '/assets/js/scripts.js', array( 'jquery', 'imagesloaded', 'jarallax', 'flickity' ), $version, true );
		wp_register_script( 'mbf-blog-toc', get_template_directory_uri() . '/assets/js/blog-toc.js', array(), $version, true );

		// Localization array.
		$localize = array(
			'siteSchemeMode'   => get_theme_mod( 'color_scheme', 'system' ),
			'siteSchemeToogle' => get_theme_mod( 'color_scheme_toggle', true ),
		);

		// Localize the main theme scripts.
		wp_localize_script( 'mbf-scripts', 'csLocalize', $localize );

		// Enqueue theme scripts.
		wp_enqueue_script( 'mbf-scripts' );
		if ( is_singular( 'post' ) ) {
			wp_enqueue_script( 'mbf-blog-toc' );
		}

		// Enqueue comment reply script.
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		// Register theme styles.
		wp_register_style( 'mbf-styles', mbf_style( get_template_directory_uri() . '/style.css' ), array(), $version );

		// Enqueue theme styles.
		wp_enqueue_style( 'mbf-styles' );

		// Add RTL support.
		wp_style_add_data( 'mbf-styles', 'rtl', 'replace' );

		// Enqueue typography styles.
		mbf_enqueue_typography_styles( 'mbf-styles' );

		// Dequeue Contact Form 7 styles.
		wp_dequeue_style( 'contact-form-7' );
	}

}
add_action( 'wp_enqueue_scripts', 'mbf_enqueue_scripts' );
