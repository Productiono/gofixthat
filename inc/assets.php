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
		wp_register_script( 'mbf-blog-popup', get_template_directory_uri() . '/assets/js/blog-popup.js', array( 'jquery' ), $version, true );

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
		if ( is_singular( 'post' ) || is_category() ) {
			wp_enqueue_script( 'mbf-blog-popup' );
		}

		// Enqueue comment reply script.
		if ( is_singular() && ! is_singular( 'post' ) && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		// Register theme styles.
		wp_register_style( 'mbf-styles', mbf_style( get_template_directory_uri() . '/style.css' ), array(), $version );

		// Enqueue theme styles.
		wp_enqueue_style( 'mbf-styles' );

		// Add RTL support.
		wp_style_add_data( 'mbf-styles', 'rtl', 'replace' );

		if ( is_singular( 'post' ) || is_category() ) {
			$popup_css = <<<CSS
.blog-exit-popup {
	position: fixed;
	inset: 0;
	z-index: 9999;
	display: flex;
	align-items: center;
	justify-content: center;
	padding: clamp(16px, 3vw, 40px);
	opacity: 0;
	visibility: hidden;
	pointer-events: none;
	transition: opacity 0.35s ease, visibility 0.35s ease;
	overflow: hidden;
}
.blog-exit-popup.is-active {
	opacity: 1;
	visibility: visible;
	pointer-events: auto;
}
.blog-exit-popup__backdrop {
	position: absolute;
	inset: 0;
	overflow: hidden;
}
.blog-exit-popup__video {
	position: absolute;
	inset: 0;
	width: 100%;
	height: 100%;
	object-fit: cover;
	object-position: center;
}
.blog-exit-popup__backdrop:after {
	content: "";
	position: absolute;
	inset: 0;
	background: linear-gradient(0deg, rgba(12, 12, 12, 0.5), rgba(12, 12, 12, 0.45));
	backdrop-filter: blur(4px);
}
.blog-exit-popup__content {
	position: relative;
	max-width: 960px;
	width: min(960px, 100%);
	display: grid;
	gap: 18px;
	justify-items: center;
	z-index: 1;
	transform: translateY(14px);
	transition: transform 0.35s ease;
}
.blog-exit-popup.is-active .blog-exit-popup__content {
	transform: translateY(0);
}
.blog-exit-popup__close {
	position: absolute;
	top: 4px;
	right: 8px;
	width: 42px;
	height: 42px;
	border: none;
	border-radius: 50%;
	background: rgba(0, 0, 0, 0.55);
	color: #fff;
	font-size: 1.35rem;
	line-height: 1;
	display: inline-flex;
	align-items: center;
	justify-content: center;
	cursor: pointer;
	transition: transform 0.25s ease, background 0.25s ease;
}
.blog-exit-popup__close:hover,
.blog-exit-popup__close:focus {
	background: rgba(0, 0, 0, 0.8);
	transform: translateY(-2px);
}
.blog-exit-popup__card {
	background: #fff;
	border-radius: 30px;
	padding: clamp(24px, 2vw + 12px, 36px);
	box-shadow: 0 24px 70px rgba(0, 0, 0, 0.28);
	display: grid;
	gap: 14px;
	text-align: center;
	width: min(860px, 100%);
}
.blog-exit-popup__logo {
	display: flex;
	justify-content: center;
	align-items: center;
}
.blog-exit-popup__logo img {
	max-height: 36px;
	width: auto;
}
.blog-exit-popup__logo .custom-logo-link {
	display: inline-flex;
}
.blog-exit-popup__logo-text {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	padding: 8px 12px;
	border-radius: 14px;
	background: rgba(0, 0, 0, 0.75);
	color: #fff;
	font-weight: 800;
	letter-spacing: 0.02em;
}
.blog-exit-popup__title {
	margin: 0;
	font-family: var(--mbf-font-headings-family), sans-serif;
	font-weight: 800;
	font-size: clamp(2rem, 3vw + 0.5rem, 2.4rem);
	line-height: 1.2;
	color: #0d0d0d;
}
.blog-exit-popup__subtitle {
	margin: 0;
	color: #333;
	font-size: 1rem;
	line-height: 1.6;
}
.blog-exit-popup__form-shell {
	width: min(720px, 100%);
	margin: -14px auto 0;
	background: #0d0d0d;
	color: #f5f5f5;
	border-radius: 26px;
	padding: clamp(20px, 2vw + 12px, 28px);
	box-shadow: 0 20px 60px rgba(0, 0, 0, 0.35);
	display: grid;
	gap: 8px;
}
.blog-exit-popup__form-title {
	margin: 0;
	font-family: var(--mbf-font-headings-family), sans-serif;
	font-weight: 700;
	font-size: 1.1rem;
}
.blog-exit-popup__form-note {
	margin: 0;
	color: #bdbdbd;
	font-size: 0.95rem;
}
.blog-exit-popup .fluentform {
	margin: 4px 0 0;
}
.blog-exit-popup .fluentform .ff-el-group,
.blog-exit-popup .fluentform .ff-el-input--content {
	width: 100%;
}
.blog-exit-popup .fluentform .ff-el-group label,
.blog-exit-popup .fluentform .ff-el-group .ff-el-label {
	color: #f5f5f5;
	font-weight: 700;
	margin-bottom: 6px;
}
.blog-exit-popup .fluentform .ff-el-form-control {
	width: 100%;
	border-radius: 999px;
	border: 1px solid #4a4a4a;
	background: #161616;
	color: #f5f5f5;
	padding: 14px 16px;
	min-height: 52px;
}
.blog-exit-popup .fluentform .ff-el-form-control:focus {
	border-color: #fff;
	box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.08);
}
.blog-exit-popup .fluentform .ff-btn-submit,
.blog-exit-popup .fluentform .ff-el-group .ff-btn {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	gap: 10px;
	border-radius: 999px;
	padding: 14px 48px 14px 22px;
	border: 1px solid #3c3c3c;
	background: #1f1f1f;
	color: #fff;
	font-weight: 700;
	position: relative;
	transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
}
.blog-exit-popup .fluentform .ff-btn-submit:after,
.blog-exit-popup .fluentform .ff-el-group .ff-btn:after {
	content: "➝";
	font-size: 1.35rem;
	position: absolute;
	right: 18px;
}
.blog-exit-popup .fluentform .ff-btn-submit:hover,
.blog-exit-popup .fluentform .ff-btn-submit:focus,
.blog-exit-popup .fluentform .ff-el-group .ff-btn:hover,
.blog-exit-popup .fluentform .ff-el-group .ff-btn:focus {
	transform: translateY(-1px);
	border-color: #ffffff;
	box-shadow: 0 12px 26px rgba(0, 0, 0, 0.35);
}
.blog-exit-popup .fluentform .ff-el-group .ff_submit_btn_wrapper {
	display: flex;
	justify-content: center;
}
.blog-exit-popup .fluentform .ff-el-group.ff_submit_btn_wrapper {
	margin-top: 10px;
}
.blog-exit-popup .fluentform .ff-el-group input::placeholder {
	color: #cfcfcf;
}
body.blog-exit-popup-open {
	overflow: hidden;
}
@media (max-width: 767.98px) {
	.blog-exit-popup__content {
		width: 100%;
	}
	.blog-exit-popup__card {
		padding: 22px 18px;
	}
	.blog-exit-popup__form-shell {
		margin-top: -6px;
	}
}
CSS;

			wp_add_inline_style( 'mbf-styles', $popup_css );
		}

		// Enqueue typography styles.
		mbf_enqueue_typography_styles( 'mbf-styles' );

		// Dequeue Contact Form 7 styles.
		wp_dequeue_style( 'contact-form-7' );
	}

}
add_action( 'wp_enqueue_scripts', 'mbf_enqueue_scripts' );
