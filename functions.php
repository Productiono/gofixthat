<?php
/**
 * Apparel functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Apparel
 */

if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', true ); // Log errors for debugging.
}

if ( ! defined( 'WP_DEBUG_LOG' ) ) {
	define( 'WP_DEBUG_LOG', WP_CONTENT_DIR . '/debug.log' ); // Persist logs to wp-content/debug.log.
}

if ( ! defined( 'WP_DEBUG_DISPLAY' ) ) {
	define( 'WP_DEBUG_DISPLAY', false ); // Hide error output from visitors.
}

if ( function_exists( 'ini_set' ) ) {
	@ini_set( 'display_errors', '0' );
}

if ( ! class_exists( 'Apparel' ) ) {
	/**
	 * Main Core Class
	 */
	class Apparel {

		/**
		 * __construct
		 *
		 * This function will initialize the initialize
		 */
		public function __construct() {
			$this->init();
			$this->theme_files();
			$this->woocommerce_files();
		}

		/**
		 * Init
		 */
		public function init() {
			add_action( 'after_setup_theme', array( $this, 'theme_setup' ) );

			// Remove woocommerce wizard.
			add_filter( 'woocommerce_prevent_automatic_wizard_redirect', '__return_false' );
			add_filter( 'woocommerce_enable_setup_wizard', '__return_false' );
		}

		/**
		 * Theme support
		 */
		public function theme_support() {
			add_theme_support( 'wp-block-styles' );
			add_theme_support( 'custom-logo' );
			add_theme_support( 'custom-header' );
			add_theme_support( 'custom-background' );
			add_editor_style();
		}

		/**
		 * Sets up theme defaults and registers support for various WordPress features.
		 *
		 * Note that this function is hooked into the after_setup_theme hook, which
		 * runs before the init hook. The init hook is too late for some features, such
		 * as indicating support for post thumbnails.
		 */
		public function theme_setup() {
			/*
			* Make theme available for translation.
			* Translations can be filed in the /languages/ directory.
			* If you're building a theme based on Apparel, use a find and replace
			* to change 'apparel' to the name of your theme in all the template files.
			*/
			load_theme_textdomain( 'apparel', get_template_directory() . '/languages' );

			// Add default posts and comments RSS feed links to head.
			add_theme_support( 'automatic-feed-links' );

			/*
			* Let WordPress manage the document title.
			* By adding theme support, we declare that this theme does not use a
			* hard-coded <title> tag in the document head, and expect WordPress to
			* provide it for us.
			*/
			add_theme_support( 'title-tag' );

			// This theme uses wp_nav_menu() in one location.
			register_nav_menus(
				array(
					'primary'    => esc_html__( 'Primary', 'apparel' ),
					'mobile'     => esc_html__( 'Mobile', 'apparel' ),
					'secondary'  => esc_html__( 'Secondary', 'apparel' ),
					'additional' => esc_html__( 'Additional', 'apparel' ),
					'footer'     => esc_html__( 'Footer', 'apparel' ),
				)
			);

			/*
			* Switch default core markup for search form, comment form, comments, etc.
			* to output valid HTML5.
			*/
			add_theme_support(
				'html5',
				array(
					'search-form',
					'comment-form',
					'comment-list',
					'gallery',
					'caption',
					'script',
					'style',
				)
			);

			// Add support for responsive embeds.
			add_theme_support( 'responsive-embeds' );

			// Add support for custom line height controls.
			add_theme_support( 'custom-line-height' );

			// Add support for block spacing.
			add_theme_support( 'custom-spacing' );

			// Supported Formats.
			add_theme_support( 'post-formats', array( 'gallery', 'video', 'audio' ) );

			// Add theme support for selective refresh for widgets.
			add_theme_support( 'customize-selective-refresh-widgets' );

			// Add support for full and wide align images.
			add_theme_support( 'align-wide' );

			/*
			* Enable support for Post Thumbnails on posts and pages.
			*
			* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
			*/
			add_theme_support( 'post-thumbnails' );

			// Register custom thumbnail sizes.
			add_image_size( 'mbf-small', 100, 100, true );
			add_image_size( 'mbf-small-2x', 200, 200, true );
			add_image_size( 'mbf-thumbnail', 306, 204, true );
			add_image_size( 'mbf-thumbnail-uncropped', 306, 0, false );
			add_image_size( 'mbf-thumbnail-2x', 612, 408, true );
			add_image_size( 'mbf-thumbnail-uncropped-2x', 306, 0, false );
			add_image_size( 'mbf-medium', 856, 570, true );
			add_image_size( 'mbf-medium-uncropped', 856, 0, true );
			add_image_size( 'mbf-large', 1296, 864, true );
			add_image_size( 'mbf-large-2x', 2592, 1728, true );
			add_image_size( 'mbf-large-uncropped', 1296, 0, true );
			add_image_size( 'mbf-large-uncropped-2x', 2592, 0, true );
			add_image_size( 'mbf-extra-large', 1920, 1024, true );
		}

		/**
		 * Include theme files
		 */
		public function theme_files() {
			require_once get_theme_file_path( '/inc/theme-setup.php' );
			require_once get_theme_file_path( '/inc/block-styles.php' );
			require_once get_theme_file_path( '/inc/block-patterns.php' );
			require_once get_theme_file_path( '/core/theme-demos/class-theme-demos.php' );
			require_once get_theme_file_path( '/core/customizer/class-customizer.php' );
			require_once get_theme_file_path( '/core/promo-banner/class-promo-banner.php' );
			require_once get_theme_file_path( '/inc/assets.php' );
			require_once get_theme_file_path( '/inc/widgets-init.php' );
			require_once get_theme_file_path( '/inc/theme-functions.php' );
			require_once get_theme_file_path( '/inc/theme-demos.php' );
			require_once get_theme_file_path( '/inc/theme-mods.php' );
			require_once get_theme_file_path( '/inc/filters.php' );
			require_once get_theme_file_path( '/inc/gutenberg.php' );
			require_once get_theme_file_path( '/inc/actions.php' );
			require_once get_theme_file_path( '/inc/partials.php' );
			require_once get_theme_file_path( '/inc/metabox.php' );
			require_once get_theme_file_path( '/inc/theme-tags.php' );
			require_once get_theme_file_path( '/inc/post-meta.php' );
			require_once get_theme_file_path( '/inc/load-more.php' );
			require_once get_theme_file_path( '/inc/deprecated.php' );
			require_once get_theme_file_path( '/inc/docs.php' );
		}

		/**
		 * Include theme files
		 */
		public function woocommerce_files() {
			if ( ! class_exists( 'WooCommerce' ) ) {
				return;
			}

			require_once get_theme_file_path( '/inc/woocommerce/setup.php' );
			require_once get_theme_file_path( '/inc/woocommerce/assets.php' );
			require_once get_theme_file_path( '/inc/woocommerce/minicart.php' );
			require_once get_theme_file_path( '/inc/woocommerce/filters.php' );
			require_once get_theme_file_path( '/inc/woocommerce/theme-mods.php' );
			require_once get_theme_file_path( '/inc/woocommerce/product-faqs.php' );

			// WooCommerce parts.
			require_once get_theme_file_path( '/inc/woocommerce/parts/header.php' );
			require_once get_theme_file_path( '/inc/woocommerce/parts/offcanvas.php' );
			require_once get_theme_file_path( '/inc/woocommerce/parts/breadcrumbs.php' );
			require_once get_theme_file_path( '/inc/woocommerce/parts/search.php' );
			require_once get_theme_file_path( '/inc/woocommerce/parts/shop.php' );
			require_once get_theme_file_path( '/inc/woocommerce/parts/single-product.php' );
			require_once get_theme_file_path( '/inc/woocommerce/parts/cart.php' );
			require_once get_theme_file_path( '/inc/woocommerce/parts/myaccount.php' );
		}
	}

	// Initialize.
	new Apparel();
}

/**
 * Disable the block editor for Doc Articles only.
 *
 * @param bool   $use_block_editor Whether to enable block editor.
 * @param string $post_type        Current post type.
 * @return bool
 */
function mbf_disable_gutenberg_for_docs( $use_block_editor, $post_type ) {
	if ( 'doc_article' === $post_type ) {
		return false;
	}

	return $use_block_editor;
}
add_filter( 'use_block_editor_for_post_type', 'mbf_disable_gutenberg_for_docs', 10, 2 );
add_filter( 'gutenberg_can_edit_post_type', 'mbf_disable_gutenberg_for_docs', 10, 2 );

/**
 * Automatically route the /docs page to the custom template.
 *
 * @param string $template Path to the template.
 * @return string
 */
function mbf_docs_page_template( $template ) {
	if ( is_page( 'docs' ) ) {
		$new_template = locate_template( 'page-docs.php' );
		if ( $new_template ) {
			return $new_template;
		}
	}

	return $template;
}
add_filter( 'template_include', 'mbf_docs_page_template' );
