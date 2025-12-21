<?php
/**
 * Typography
 *
 * @package Apparel
 */

?>

:root {
	/* Base Font */
	--mbf-font-base-family: <?php mbf_typography( 'font_base', 'font-family', 'Inter' ); ?>;
	--mbf-font-base-size: <?php mbf_typography( 'font_base', 'font-size', 'clamp(1rem, 0.98rem + 0.35vw, 1.125rem)' ); ?>;
	--mbf-font-base-weight: <?php mbf_typography( 'font_base', 'font-weight', '400' ); ?>;
	--mbf-font-base-style: <?php mbf_typography( 'font_base', 'font-style', 'normal' ); ?>;
	--mbf-font-base-letter-spacing: <?php mbf_typography( 'font_base', 'letter-spacing', '0.01em' ); ?>;
	--mbf-font-base-line-height: <?php mbf_typography( 'font_base', 'line-height', '1.6' ); ?>;

	/* Primary Font */
	--mbf-font-primary-family: <?php mbf_typography( 'font_primary', 'font-family', 'Inter' ); ?>;
	--mbf-font-primary-size: <?php mbf_typography( 'font_primary', 'font-size', 'clamp(0.95rem, 0.9rem + 0.25vw, 1.05rem)' ); ?>;
	--mbf-font-primary-weight: <?php mbf_typography( 'font_primary', 'font-weight', '600' ); ?>;
	--mbf-font-primary-style: <?php mbf_typography( 'font_primary', 'font-style', 'normal' ); ?>;
	--mbf-font-primary-letter-spacing: <?php mbf_typography( 'font_primary', 'letter-spacing', '0.01em' ); ?>;
	--mbf-font-primary-text-transform: <?php mbf_typography( 'font_primary', 'text-transform', 'none' ); ?>;

	/* Secondary Font */
	--mbf-font-secondary-family: <?php mbf_typography( 'font_secondary', 'font-family', 'Inter' ); ?>;
	--mbf-font-secondary-size: <?php mbf_typography( 'font_secondary', 'font-size', 'clamp(1rem, 0.95rem + 0.25vw, 1.1rem)' ); ?>;
	--mbf-font-secondary-weight: <?php mbf_typography( 'font_secondary', 'font-weight', '500' ); ?>;
	--mbf-font-secondary-style: <?php mbf_typography( 'font_secondary', 'font-style', 'normal' ); ?>;
	--mbf-font-secondary-letter-spacing: <?php mbf_typography( 'font_secondary', 'letter-spacing', '0.01em' ); ?>;
	--mbf-font-secondary-text-transform: <?php mbf_typography( 'font_secondary', 'text-transform', 'none' ); ?>;

	/* Category Font */
	--mbf-font-category-family: <?php mbf_typography( 'font_category', 'font-family', 'Inter' ); ?>;
	--mbf-font-category-size: <?php mbf_typography( 'font_category', 'font-size', 'clamp(0.85rem, 0.8rem + 0.15vw, 0.95rem)' ); ?>;
	--mbf-font-category-weight: <?php mbf_typography( 'font_category', 'font-weight', '600' ); ?>;
	--mbf-font-category-style: <?php mbf_typography( 'font_category', 'font-style', 'normal' ); ?>;
	--mbf-font-category-letter-spacing: <?php mbf_typography( 'font_category', 'letter-spacing', '0.02em' ); ?>;
	--mbf-font-category-text-transform: <?php mbf_typography( 'font_category', 'text-transform', 'none' ); ?>;

	/* Post Meta Font */
	--mbf-font-post-meta-family: <?php mbf_typography( 'font_post_meta', 'font-family', 'Inter' ); ?>;
	--mbf-font-post-meta-size: <?php mbf_typography( 'font_post_meta', 'font-size', 'clamp(0.9rem, 0.85rem + 0.2vw, 1rem)' ); ?>;
	--mbf-font-post-meta-weight: <?php mbf_typography( 'font_post_meta', 'font-weight', '500' ); ?>;
	--mbf-font-post-meta-style: <?php mbf_typography( 'font_post_meta', 'font-style', 'normal' ); ?>;
	--mbf-font-post-meta-letter-spacing: <?php mbf_typography( 'font_post_meta', 'letter-spacing', '0.02em' ); ?>;
	--mbf-font-post-meta-text-transform: <?php mbf_typography( 'font_post_meta', 'text-transform', 'none' ); ?>;

	/* Input Font */
	--mbf-font-input-family: <?php mbf_typography( 'font_input', 'font-family', 'Inter' ); ?>;
	--mbf-font-input-size: <?php mbf_typography( 'font_input', 'font-size', 'clamp(0.95rem, 0.9rem + 0.25vw, 1.05rem)' ); ?>;
	--mbf-font-input-weight: <?php mbf_typography( 'font_input', 'font-weight', '500' ); ?>;
	--mbf-font-input-style: <?php mbf_typography( 'font_input', 'font-style', 'normal' ); ?>;
	--mbf-font-input-line-height: <?php mbf_typography( 'font_input', 'line-height', 'clamp(1.5rem, 1.1rem + 0.7vw, 1.8rem)' ); ?>;
	--mbf-font-input-letter-spacing: <?php mbf_typography( 'font_input', 'letter-spacing', '0.01em' ); ?>;
	--mbf-font-input-text-transform: <?php mbf_typography( 'font_input', 'text-transform', 'none' ); ?>;

	/* Post Subbtitle */
	--mbf-font-post-subtitle-family: <?php mbf_typography( 'font_post_subtitle', 'font-family', 'inherit' ); ?>;
	--mbf-font-post-subtitle-size: <?php mbf_typography( 'font_post_subtitle', 'font-size', 'clamp(1.25rem, 1.15rem + 0.3vw, 1.5rem)' ); ?>;
	--mbf-font-post-subtitle-letter-spacing: <?php mbf_typography( 'font_post_subtitle', 'letter-spacing', 'normal' ); ?>;

	/* Post Content */
	--mbf-font-post-content-family: <?php mbf_typography( 'font_post_content', 'font-family', 'Inter' ); ?>;
	--mbf-font-post-content-size: <?php mbf_typography( 'font_post_content', 'font-size', 'clamp(1.0625rem, 1rem + 0.35vw, 1.2rem)' ); ?>;
	--mbf-font-post-content-letter-spacing: <?php mbf_typography( 'font_post_content', 'letter-spacing', '0.01em' ); ?>;
	--mbf-font-post-content-line-height: <?php mbf_typography( 'font_post_content', 'line-height', '1.7' ); ?>;

	/* Entry Excerpt */
	--mbf-font-entry-excerpt-family: <?php mbf_typography( 'font_excerpt', 'font-family', 'Inter' ); ?>;
	--mbf-font-entry-excerpt-size: <?php mbf_typography( 'font_excerpt', 'font-size', 'clamp(0.95rem, 0.9rem + 0.25vw, 1.05rem)' ); ?>;
	--mbf-font-entry-excerpt-weight: <?php mbf_typography( 'font_excerpt', 'font-weight', '600' ); ?>;
	--mbf-font-entry-excerpt-letter-spacing: <?php mbf_typography( 'font_excerpt', 'letter-spacing', '0.01em' ); ?>;
	--mbf-font-entry-excerpt-line-height: <?php mbf_typography( 'font_excerpt', 'line-height', '1.6' ); ?>;

	/* Logos --------------- */

	/* Main Logo */
	--mbf-font-main-logo-family: <?php mbf_typography( 'font_main_logo', 'font-family', 'Inter' ); ?>;
	--mbf-font-main-logo-size: <?php mbf_typography( 'font_main_logo', 'font-size', 'clamp(1.5rem, 1.3rem + 0.3vw, 1.8rem)' ); ?>;
	--mbf-font-main-logo-weight: <?php mbf_typography( 'font_main_logo', 'font-weight', '700' ); ?>;
	--mbf-font-main-logo-style: <?php mbf_typography( 'font_main_logo', 'font-style', 'normal' ); ?>;
	--mbf-font-main-logo-letter-spacing: <?php mbf_typography( 'font_main_logo', 'letter-spacing', '-0.02em' ); ?>;
	--mbf-font-main-logo-text-transform: <?php mbf_typography( 'font_main_logo', 'text-transform', 'none' ); ?>;

	/* Footer Logo */
	--mbf-font-footer-logo-family: <?php mbf_typography( 'font_footer_logo', 'font-family', 'Inter' ); ?>;
	--mbf-font-footer-logo-size: <?php mbf_typography( 'font_footer_logo', 'font-size', 'clamp(1.5rem, 1.3rem + 0.3vw, 1.8rem)' ); ?>;
	--mbf-font-footer-logo-weight: <?php mbf_typography( 'font_footer_logo', 'font-weight', '700' ); ?>;
	--mbf-font-footer-logo-style: <?php mbf_typography( 'font_footer_logo', 'font-style', 'normal' ); ?>;
	--mbf-font-footer-logo-letter-spacing: <?php mbf_typography( 'font_footer_logo', 'letter-spacing', '-0.02em' ); ?>;
	--mbf-font-footer-logo-text-transform: <?php mbf_typography( 'font_footer_logo', 'text-transform', 'none' ); ?>;

	/* Headings --------------- */

	/* Headings */
	--mbf-font-headings-family: <?php mbf_typography( 'font_headings', 'font-family', 'Inter' ); ?>;
	--mbf-font-headings-weight: <?php mbf_typography( 'font_headings', 'font-weight', '600' ); ?>;
	--mbf-font-headings-style: <?php mbf_typography( 'font_headings', 'font-style', 'normal' ); ?>;
	--mbf-font-headings-line-height: <?php mbf_typography( 'font_headings', 'line-height', '1.15' ); ?>;
	--mbf-font-headings-letter-spacing: <?php mbf_typography( 'font_headings', 'letter-spacing', '-0.01em' ); ?>;
	--mbf-font-headings-text-transform: <?php mbf_typography( 'font_headings', 'text-transform', 'none' ); ?>;

	/* Menu Font --------------- */

	/* Menu */
	/* Used for main top level menu elements. */
	--mbf-font-menu-family: <?php mbf_typography( 'font_menu', 'font-family', 'Inter' ); ?>;
	--mbf-font-menu-size: <?php mbf_typography( 'font_menu', 'font-size', 'clamp(0.95rem, 0.9rem + 0.25vw, 1.05rem)' ); ?>;
	--mbf-font-menu-weight: <?php mbf_typography( 'font_menu', 'font-weight', '600' ); ?>;
	--mbf-font-menu-style: <?php mbf_typography( 'font_menu', 'font-style', 'normal' ); ?>;
	--mbf-font-menu-letter-spacing: <?php mbf_typography( 'font_menu', 'letter-spacing', '0.01em' ); ?>;
	--mbf-font-menu-text-transform: <?php mbf_typography( 'font_menu', 'text-transform', 'none' ); ?>;

	/* Submenu Font */
	/* Used for submenu elements. */
	--mbf-font-submenu-family: <?php mbf_typography( 'font_submenu', 'font-family', 'Inter' ); ?>;
	--mbf-font-submenu-size: <?php mbf_typography( 'font_submenu', 'font-size', 'clamp(0.95rem, 0.9rem + 0.25vw, 1.05rem)' ); ?>;
	--mbf-font-submenu-weight: <?php mbf_typography( 'font_submenu', 'font-weight', '500' ); ?>;
	--mbf-font-submenu-style: <?php mbf_typography( 'font_submenu', 'font-style', 'normal' ); ?>;
	--mbf-font-submenu-letter-spacing: <?php mbf_typography( 'font_submenu', 'letter-spacing', '0.01em' ); ?>;
	--mbf-font-submenu-text-transform: <?php mbf_typography( 'font_submenu', 'text-transform', 'none' ); ?>;
}
