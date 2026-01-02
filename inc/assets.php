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

		$cookie_js = <<<JS
(function () {
	const storageKey = 'mbfCookieConsent';
	const consentBar = document.querySelector('.mbf-cookie-consent');
	if (!consentBar) {
		return;
	}
	const hasStoredChoice = () => {
		try {
			if (window.localStorage.getItem(storageKey)) {
				return true;
			}
		} catch (error) {
			// Local storage not available.
		}
		return document.cookie.split('; ').some((entry) => entry.startsWith(storageKey + '='));
	};
	if (hasStoredChoice()) {
		return;
	}
	const rememberChoice = (value) => {
		const expiryDate = new Date();
		expiryDate.setFullYear(expiryDate.getFullYear() + 1);
		try {
			window.localStorage.setItem(storageKey, value);
		} catch (error) {
			// Local storage not available.
		}
		document.cookie = storageKey + '=' + value + '; expires=' + expiryDate.toUTCString() + '; path=/';
		consentBar.hidden = true;
	};
	const acceptButton = consentBar.querySelector('[data-cookie-consent=\"accept\"]');
	const rejectButton = consentBar.querySelector('[data-cookie-consent=\"reject\"]');
	if (acceptButton) {
		acceptButton.addEventListener('click', function () {
			rememberChoice('accepted');
		});
	}
	if (rejectButton) {
		rejectButton.addEventListener('click', function () {
			rememberChoice('rejected');
		});
	}
	consentBar.hidden = false;
})();
JS;

		wp_add_inline_script( 'mbf-scripts', $cookie_js );

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
	max-width: 880px;
	width: min(880px, 100%);
	display: grid;
	gap: 22px;
	justify-items: center;
	background: #fff;
	border-radius: 32px;
	padding: clamp(26px, 3vw + 16px, 42px);
	box-shadow: 0 28px 80px rgba(0, 0, 0, 0.32);
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
	display: grid;
	gap: 12px;
	text-align: center;
	width: 100%;
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
	width: 100%;
	margin: 0;
	color: #0d0d0d;
	display: grid;
	justify-items: center;
	gap: 10px;
}
.blog-exit-popup__form-card {
	width: 100%;
	background: transparent;
	color: #0d0d0d;
	border-radius: 0;
	padding: 0;
	box-shadow: none;
	display: grid;
	gap: 14px;
	align-items: start;
	text-align: center;
}
.blog-exit-popup__form-title {
	margin: 0;
	font-family: var(--mbf-font-headings-family), sans-serif;
	font-weight: 700;
	font-size: 1.2rem;
	line-height: 1.3;
}
.blog-exit-popup__form-note {
	margin: 0;
	color: #4a4a4a;
	font-size: 0.98rem;
	line-height: 1.5;
}
.blog-exit-popup .fluentform {
	margin: 6px 0 0;
}
.blog-exit-popup .fluentform .ff-el-group,
.blog-exit-popup .fluentform .ff-el-input--content {
	width: 100%;
}
.blog-exit-popup .fluentform .ff-el-group label,
.blog-exit-popup .fluentform .ff-el-group .ff-el-label {
	color: #0d0d0d;
	font-weight: 700;
	margin-bottom: 6px;
}
.blog-exit-popup .fluentform .ff-el-form-control {
	width: 100%;
	border-radius: 14px;
	border: 1px solid #2a2a2a;
	background: #fff;
	color: #0d0d0d;
	padding: 14px 16px;
	min-height: 52px;
	box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.04);
}
.blog-exit-popup .fluentform .ff-el-form-control:focus {
	border-color: #111;
	box-shadow: 0 0 0 3px rgba(17, 17, 17, 0.1);
}
.blog-exit-popup .fluentform .ff-btn-submit,
.blog-exit-popup .fluentform .ff-el-group .ff-btn {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	gap: 10px;
	border-radius: 14px;
	padding: 14px 48px 14px 22px;
	border: 1px solid #111;
	background: linear-gradient(135deg, #111, #1f1f1f);
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
	border-color: #0a0a0a;
	box-shadow: 0 12px 30px rgba(0, 0, 0, 0.26);
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
		padding: 24px 18px 28px;
	}
}
CSS;

			wp_add_inline_style( 'mbf-styles', $popup_css );
		}

		$cookie_css = <<<CSS
.mbf-cookie-consent {
	position: fixed;
	left: 0;
	right: 0;
	bottom: 0;
	z-index: 9998;
	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: 12px;
	padding: 12px 18px;
	background: #0f0f0f;
	color: #f5f5f5;
	border-top: 1px solid rgba(255, 255, 255, 0.08);
	box-shadow: 0 -8px 24px rgba(0, 0, 0, 0.08);
	font-size: 0.95rem;
	line-height: 1.5;
}
.mbf-cookie-consent[hidden] {
	display: none;
}
.mbf-cookie-consent__message {
	margin: 0;
	max-width: 780px;
}
.mbf-cookie-consent__actions {
	display: flex;
	gap: 10px;
	flex-shrink: 0;
	flex-wrap: wrap;
}
.mbf-cookie-consent__button {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	gap: 8px;
	padding: 8px 14px;
	border-radius: 8px;
	border: 1px solid rgba(255, 255, 255, 0.25);
	background: transparent;
	color: #f5f5f5;
	font-weight: 600;
	cursor: pointer;
	transition: color 0.15s ease, border-color 0.15s ease, background-color 0.15s ease;
}
.mbf-cookie-consent__button:hover,
.mbf-cookie-consent__button:focus-visible {
	border-color: rgba(255, 255, 255, 0.5);
	color: #ffffff;
}
.mbf-cookie-consent__button--primary {
	background: #f5f5f5;
	color: #0f0f0f;
	border-color: #f5f5f5;
}
.mbf-cookie-consent__button--primary:hover,
.mbf-cookie-consent__button--primary:focus-visible {
	background: #ffffff;
	border-color: #ffffff;
}
@media (max-width: 640px) {
	.mbf-cookie-consent {
		flex-direction: column;
		align-items: flex-start;
		text-align: left;
		gap: 10px;
	}
	.mbf-cookie-consent__actions {
		width: 100%;
		justify-content: flex-start;
	}
}
CSS;

		wp_add_inline_style( 'mbf-styles', $cookie_css );

		// Enqueue typography styles.
		mbf_enqueue_typography_styles( 'mbf-styles' );

		// Dequeue Contact Form 7 styles.
		wp_dequeue_style( 'contact-form-7' );
	}

}
add_action( 'wp_enqueue_scripts', 'mbf_enqueue_scripts' );
