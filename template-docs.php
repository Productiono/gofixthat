<?php
/**
 * Template for the custom docs landing page.
 *
 * @package Apparel
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
	<?php wp_head(); ?>
	<style>
		:root {
			--docs-text: #1f1f1f;
			--docs-muted: #4f4f4f;
			--docs-border: #d9d9d9;
			--docs-surface: #f7f7f7;
			--docs-card: #fbfbfb;
			--docs-accent: #222;
		}

		* {
			box-sizing: border-box;
		}

		body.docs-landing-page {
			margin: 0;
			background: #f8f8f8;
			font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
			color: var(--docs-text);
			-webkit-font-smoothing: antialiased;
		}

		.docs-page {
			min-height: 100vh;
			display: flex;
			flex-direction: column;
			background: linear-gradient(180deg, #f8f8f8 0%, #f4f4f4 32%, #fdfdfd 72%, #ffffff 100%);
		}

		.docs-header {
			position: sticky;
			top: 0;
			z-index: 50;
			background: #f7f7f7;
			border-bottom: 1px solid #eaeaea;
			box-shadow: 0 1px 0 rgba(0, 0, 0, 0.02);
		}

		.docs-header-inner {
			max-width: 1320px;
			margin: 0 auto;
			padding: 14px 28px 12px;
			display: grid;
			grid-template-columns: auto 1fr auto;
			align-items: center;
			gap: 20px;
		}

		.docs-brand-nav {
			display: flex;
			align-items: center;
			min-width: 0;
			gap: 14px;
		}

		.docs-brand-nav .mbf-logo {
			display: flex;
			align-items: center;
		}

		.docs-brand-nav .mbf-header__logo img {
			max-height: 36px;
			width: auto;
		}

		.docs-search {
			display: flex;
			justify-content: center;
			align-items: center;
		}

		.docs-search .mbf-header__search-toggle {
			display: inline-flex;
			align-items: center;
			gap: 8px;
			font-weight: 600;
			color: #1f1f1f;
			padding: 10px 12px;
			border: 1px solid #e5e5e5;
			border-radius: 10px;
			background: #fff;
			box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
		}

		.docs-search .mbf-header__search-toggle .mbf-header__search-label {
			font-size: 14px;
			color: #1f1f1f;
		}

		.docs-utility {
			display: flex;
			justify-content: flex-end;
			align-items: center;
			gap: 16px;
			font-size: 13px;
			color: #3f3f3f;
			font-weight: 600;
		}

		.docs-utility a {
			color: inherit;
			text-decoration: none;
		}

		.docs-utility .mbf-site-scheme-toggle {
			display: inline-flex;
			align-items: center;
			gap: 8px;
			padding: 8px 10px;
			border-radius: 12px;
			border: 1px solid #e5e5e5;
			background: #fff;
			box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
			cursor: pointer;
		}

		.docs-utility .mbf-site__scheme-toggle-element {
			background: #0f0f0f;
			color: #fff;
		}

		.docs-hero {
			position: relative;
			padding: 80px 24px 70px;
			overflow: hidden;
		}

		.docs-hero::before {
			content: '';
			position: absolute;
			inset: 0;
			background:
				linear-gradient(90deg, rgba(0, 0, 0, 0.03) 1px, transparent 1px),
				linear-gradient(0deg, rgba(0, 0, 0, 0.03) 1px, transparent 1px),
				radial-gradient(closest-side at 80% 35%, rgba(0, 0, 0, 0.12), rgba(0, 0, 0, 0) 62%),
				linear-gradient(180deg, #f6f6f6 0%, #efefef 55%, #ffffff 100%);
			background-size: 80px 80px, 80px 80px, 100% 100%, 100% 100%;
			background-position: center;
			z-index: 1;
			opacity: 0.9;
		}

		.docs-hero-inner {
			position: relative;
			max-width: 760px;
			margin: 0 auto;
			text-align: center;
			z-index: 2;
		}

		.docs-hero h1 {
			margin: 0 0 16px;
			font-size: clamp(26px, 3vw + 12px, 32px);
			font-weight: 700;
			color: #1a1a1a;
			letter-spacing: -0.2px;
		}

		.docs-hero p {
			margin: 0;
			font-size: 16px;
			color: #505050;
			line-height: 1.6;
			max-width: 720px;
			margin-left: auto;
			margin-right: auto;
		}

		.docs-card-section {
			position: relative;
			padding: 0 24px 88px;
			background: radial-gradient(circle at 70% 10%, rgba(0, 0, 0, 0.06), rgba(255, 255, 255, 0) 45%);
		}

		.docs-card-grid {
			max-width: 1160px;
			margin: -6px auto 0;
			display: grid;
			grid-template-columns: repeat(2, minmax(0, 1fr));
			gap: 22px;
		}

		.docs-card {
			background: #fff;
			border: 1px solid var(--docs-border);
			border-radius: 14px;
			padding: 22px 24px;
			display: grid;
			grid-template-columns: auto 1fr;
			gap: 16px;
			align-items: center;
			box-shadow:
				0 12px 32px rgba(0, 0, 0, 0.03),
				inset 0 1px 0 rgba(255, 255, 255, 0.8);
		}

		.docs-card h3 {
			margin: 0 0 6px;
			font-size: 15px;
			font-weight: 700;
			color: #202020;
			letter-spacing: -0.1px;
		}

		.docs-card p {
			margin: 0;
			font-size: 14px;
			color: #555;
			line-height: 1.55;
		}

		.docs-card .docs-icon {
			width: 38px;
			height: 38px;
			border-radius: 12px;
			border: 1px solid #e2e2e2;
			display: inline-flex;
			align-items: center;
			justify-content: center;
			background: #fafafa;
		}

		.docs-card svg {
			width: 20px;
			height: 20px;
			color: #181818;
		}

		.docs-section-gap {
			height: 72px;
		}

		.docs-cta-section {
			padding: 0 24px 110px;
			background: radial-gradient(circle at 25% 20%, rgba(0, 0, 0, 0.02), transparent 45%);
		}

		.docs-cta-inner {
			max-width: 1120px;
			margin: 0 auto;
			padding: 42px 46px;
			background: linear-gradient(135deg, #0f172a, #1f2937);
			color: #f5f7ff;
			border: 1px solid rgba(255, 255, 255, 0.08);
			border-radius: 18px;
			box-shadow: 0 18px 36px rgba(0, 0, 0, 0.18), inset 0 1px 0 rgba(255, 255, 255, 0.06);
			display: grid;
			grid-template-columns: 1.2fr auto;
			align-items: center;
			gap: 28px;
		}

		.docs-cta-title {
			margin: 0 0 12px;
			font-size: clamp(22px, 3vw + 10px, 28px);
			letter-spacing: -0.3px;
		}

		.docs-cta-text {
			margin: 0;
			font-size: 15px;
			line-height: 1.65;
			color: #d8deef;
		}

		.docs-cta-actions {
			display: flex;
			align-items: center;
			gap: 14px;
		}

		.docs-cta-primary,
		.docs-cta-secondary {
			display: inline-flex;
			align-items: center;
			justify-content: center;
			gap: 8px;
			padding: 12px 18px;
			border-radius: 12px;
			font-weight: 700;
			font-size: 14px;
			text-decoration: none;
			transition: transform 0.15s ease, box-shadow 0.15s ease, background 0.15s ease, color 0.15s ease;
			border: 1px solid transparent;
		}

		.docs-cta-primary {
			background: #3b82f6;
			color: #fff;
			box-shadow: 0 10px 28px rgba(59, 130, 246, 0.35);
		}

		.docs-cta-primary:hover {
			transform: translateY(-1px);
			box-shadow: 0 12px 30px rgba(59, 130, 246, 0.42);
		}

		.docs-cta-secondary {
			color: #f5f7ff;
			border-color: rgba(255, 255, 255, 0.24);
			background: rgba(255, 255, 255, 0.08);
		}

		.docs-cta-secondary:hover {
			background: rgba(255, 255, 255, 0.12);
			transform: translateY(-1px);
		}

		@media (max-width: 1024px) {
			.docs-header-inner {
				grid-template-columns: 1fr;
				grid-template-rows: auto auto auto;
				text-align: left;
				gap: 12px;
			}

			.docs-brand-nav {
				justify-content: flex-start;
			}

			.docs-search {
				justify-content: flex-start;
			}

			.docs-utility {
				justify-content: flex-start;
			}
		}

		@media (max-width: 768px) {
			.docs-header-inner {
				padding: 14px 16px 10px;
			}

			.docs-hero {
				padding: 64px 18px 58px;
			}

			.docs-card-section {
				padding: 0 18px 64px;
			}

			.docs-card-grid {
				grid-template-columns: 1fr;
			}

			.docs-card {
				padding: 18px 18px;
			}

			.docs-cta-inner {
				grid-template-columns: 1fr;
				padding: 32px 28px;
			}

			.docs-cta-actions {
				flex-wrap: wrap;
				justify-content: flex-start;
			}
		}
	</style>
</head>
<body <?php body_class( 'docs-landing-page' ); ?> <?php mbf_site_scheme(); ?>>
<?php
if ( function_exists( 'wp_body_open' ) ) {
	wp_body_open();
}
?>

<div class="docs-page">
	<header class="docs-header">
		<div class="docs-header-inner">
			<div class="docs-brand-nav">
				<?php mbf_component( 'header_logo' ); ?>
			</div>
			<div class="docs-search">
				<?php mbf_component( 'header_search_toggle' ); ?>
			</div>
			<div class="docs-utility">
				<a href="#"><?php esc_html_e( 'Support', 'apparel' ); ?></a>
				<a href="#"><?php esc_html_e( 'Log in', 'apparel' ); ?></a>
				<a href="#"><?php esc_html_e( 'Request Trial', 'apparel' ); ?></a>
				<?php mbf_component( 'header_scheme_toggle' ); ?>
			</div>
		</div>
	</header>

	<?php mbf_site_search(); ?>

	<section class="docs-hero" aria-labelledby="docs-hero-title">
		<div class="docs-hero-inner">
			<h1 id="docs-hero-title"><?php esc_html_e( 'Welcome to Plivo Documentation', 'apparel' ); ?></h1>
			<p><?php esc_html_e( 'Explore comprehensive guides, API references, and discussions to integrate, manage, and optimize your Plivo solutions.', 'apparel' ); ?></p>
		</div>
	</section>

	<section class="docs-card-section" aria-label="<?php esc_attr_e( 'Documentation categories', 'apparel' ); ?>">
		<div class="docs-card-grid">
			<article class="docs-card">
				<div class="docs-icon" aria-hidden="true">
					<svg viewBox="0 0 24 24" fill="none">
						<path d="M12 4c.5 0 2 1.2 2 2.7 0 .9-.6 1.6-.6 2.1 0 .4.3.7.6 1 .4.4.8.9 1 1.5.5 1.6-.8 3.4-3 3.4-2.3 0-3.7-1.9-3.1-3.6.2-.6.6-1 1-1.3.3-.3.5-.6.5-1 0-.6-.6-1.2-.6-2C9.8 5.2 11.3 4 12 4Zm-4.8 7.5H5.5c-.8 0-1.5.7-1.5 1.5V16c0 .8.7 1.5 1.5 1.5h1.7M16.8 11.5h1.7c.8 0 1.5.7 1.5 1.5V16c0 .8-.7 1.5-1.5 1.5h-1.7" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
					</svg>
				</div>
				<div>
					<h3><?php esc_html_e( 'AI Agents', 'apparel' ); ?></h3>
					<p><?php esc_html_e( 'Create and deploy advanced voice and chat AI agents to sell, market and support your customers', 'apparel' ); ?></p>
				</div>
			</article>

			<article class="docs-card">
				<div class="docs-icon" aria-hidden="true">
					<svg viewBox="0 0 24 24" fill="none">
						<path d="M12 3.5 5 7v4.5c0 4.5 3 8.2 7 9 4-0.8 7-4.5 7-9V7l-7-3.5Z" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
						<path d="M9 12.2 11 14l4-4.5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
					</svg>
				</div>
				<div>
					<h3><?php esc_html_e( 'Verify', 'apparel' ); ?></h3>
					<p><?php esc_html_e( 'Securely authenticate users with multi-channel OTP delivery and Fraud Protection', 'apparel' ); ?></p>
				</div>
			</article>

			<article class="docs-card">
				<div class="docs-icon" aria-hidden="true">
					<svg viewBox="0 0 24 24" fill="none">
						<path d="M6 16V8c0-.6.4-1 1-1h7.5c.6 0 1.1.4 1.1 1v8c0 .6-.5 1-1.1 1H7c-.6 0-1-.4-1-1Zm0 0H4.5M16.7 16H18c.6 0 1.1-.4 1.1-1V9c0-.6-.5-1-1.1-1h-1.3" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
						<path d="M10 10.5h4M10 13h2.8" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" />
						<path d="M8.5 10.5c.4 0 .7-.3.7-.7 0-.4-.3-.8-.7-.8-.4 0-.7.4-.7.8 0 .4.3.7.7.7Z" fill="currentColor" />
					</svg>
				</div>
				<div>
					<h3><?php esc_html_e( 'Programmable APIs', 'apparel' ); ?></h3>
					<p><?php esc_html_e( 'Suite of APIs and SDKs to integrate real-time communication features into your applications', 'apparel' ); ?></p>
				</div>
			</article>

			<article class="docs-card">
				<div class="docs-icon" aria-hidden="true">
					<svg viewBox="0 0 24 24" fill="none">
						<path d="M7 5h10c.6 0 1 .4 1 1v12c0 .6-.4 1-1 1H7c-.6 0-1-.4-1-1V6c0-.6.4-1 1-1Z" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
						<path d="M10 8h6.2M10 11h6.2M10 14H14" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" />
						<path d="m6 9.5-1.6-1.6a.6.6 0 0 1 0-.8L6 5.5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
						<path d="m18 15 1.6 1.6a.6.6 0 0 1 0 .8L18 19" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
					</svg>
				</div>
				<div>
					<h3><?php esc_html_e( 'Go to Plivo Platform', 'apparel' ); ?></h3>
					<p><?php esc_html_e( 'Get started with Plivo and transform your communication and customer engagement across multiple channels.', 'apparel' ); ?></p>
				</div>
			</article>
		</div>
	</section>

	<div class="docs-section-gap" aria-hidden="true"></div>

	<section class="docs-cta-section" aria-labelledby="docs-cta-title">
		<div class="docs-cta-inner">
			<div>
				<h2 class="docs-cta-title" id="docs-cta-title"><?php esc_html_e( 'Build with confidence on Plivo', 'apparel' ); ?></h2>
				<p class="docs-cta-text"><?php esc_html_e( 'Get expert guidance, production-ready examples, and the latest best practices to ship reliable experiences faster.', 'apparel' ); ?></p>
			</div>
			<div class="docs-cta-actions">
				<a class="docs-cta-primary" href="#"><?php esc_html_e( 'Start building', 'apparel' ); ?></a>
				<a class="docs-cta-secondary" href="#"><?php esc_html_e( 'Talk to an expert', 'apparel' ); ?></a>
			</div>
		</div>
	</section>
</div>

<?php wp_footer(); ?>
</body>
</html>
