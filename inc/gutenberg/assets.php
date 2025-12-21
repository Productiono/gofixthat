<?php
/**
 * Assets
 *
 * All enqueues of scripts and styles.
 *
 * @package Apparel
 */

if ( ! function_exists( 'mbf_editor_style' ) ) {
	/**
	 * Add callback for custom editor stylesheets.
	 */
	function mbf_editor_style() {
		// Add support for editor styles.
		add_theme_support( 'editor-styles' );
	}
}
add_action( 'current_screen', 'mbf_editor_style' );

if ( ! function_exists( 'mbf_enqueue_block_editor_assets' ) ) {
	/**
	 * Enqueue block editor specific scripts.
	 */
	function mbf_enqueue_block_editor_assets() {

		if ( ! ( is_admin() && ! is_customize_preview() ) ) {
			return;
		}

		$version = mbf_get_theme_data( 'Version' );

		// Register theme scripts.
		wp_register_script( 'mbf-scripts', get_template_directory_uri() . '/assets/js/scripts.js', array( 'jquery', 'imagesloaded' ), $version, true );

		// Localization array.
		$localize = array(
			'siteSchemeMode'   => 'light',
			'siteSchemeToogle' => false,
		);

		// Localize the main theme scripts.
		wp_localize_script( 'mbf-scripts', 'csLocalize', $localize );

		// Enqueue theme scripts.
		wp_enqueue_script( 'mbf-scripts' );

		// Register theme styles.
		wp_register_style( 'mbf-editor', mbf_style( get_template_directory_uri() . '/assets/css/editor-style.css' ), false, $version );

		// Add RTL support.
		wp_style_add_data( 'mbf-editor', 'rtl', 'replace' );

		// Enqueue typography styles.
		mbf_enqueue_typography_styles( 'mbf-editor' );

		// Enqueue theme styles.
		wp_enqueue_style( 'mbf-editor' );
	}
	add_action( 'enqueue_block_assets', 'mbf_enqueue_block_editor_assets' );
}
