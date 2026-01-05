<?php
/**
 * Template Name: App AiBoot
 * Template Post Type: page
 *
 * Custom landing page that mirrors the App AiBoot marketing layout.
 *
 * @package Apparel
 */

global $post;

get_header();
?>
<main id="primary" class="app-aibot">
	<style>
		:root {
			--aib-background: #f7f5f2;
			--aib-text: #1a1a1a;
			--aib-muted: #6f6f73;
			--aib-strong: #0f0f10;
			--aib-accent: #ff5c8d;
			--aib-primary: #1a1a1a;
			--aib-card: #111111;
			--aib-border: #e5e0dc;
			--aib-gradient: linear-gradient(90deg, #ff80b5 0%, #7857ff 50%, #35c9ff 100%);
			--aib-radius-lg: 24px;
			--aib-radius-md: 18px;
			--aib-radius-sm: 12px;
			--aib-shadow: 0 30px 80px rgba(15, 15, 16, 0.12);
			--aib-font: "Inter", "Helvetica Neue", Arial, sans-serif;
		}

		.app-aibot {
			background: var(--aib-background);
			color: var(--aib-text);
			font-family: var(--aib-font);
			line-height: 1.7;
		}

		.app-aibot a {
			color: inherit;
			text-decoration: none;
		}

		.app-aibot .aib-container {
			width: min(1280px, 92vw);
			margin: 0 auto;
		}

		.app-aibot .aib-topbar {
			display: flex;
			align-items: center;
			justify-content: space-between;
			padding: 28px 0 16px;
			font-size: 15px;
			gap: 18px;
		}

		.app-aibot .aib-brand {
			display: flex;
			align-items: center;
			gap: 12px;
			font-weight: 600;
			letter-spacing: 0.01em;
		}

		.app-aibot .aib-dot {
			width: 38px;
			height: 38px;
			border-radius: 12px;
			background: #1d1c1a;
			color: #fff;
			display: grid;
			place-items: center;
			font-weight: 700;
			font-size: 14px;
			box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.06);
		}

		.app-aibot .aib-nav-links {
			display: flex;
			align-items: center;
			gap: 26px;
			color: var(--aib-muted);
		}

		.app-aibot .aib-nav-links span {
			display: inline-flex;
			align-items: center;
			gap: 6px;
		}

		.app-aibot .aib-cta-group {
			display: flex;
			align-items: center;
			gap: 12px;
		}

		.app-aibot .aib-pill {
			padding: 9px 16px;
			border-radius: 999px;
			border: 1px solid var(--aib-border);
			background: #fff;
			font-weight: 600;
			box-shadow: 0 12px 26px rgba(0, 0, 0, 0.06);
		}

		.app-aibot .aib-pill.secondary {
			background: #0f0f10;
			color: #fff;
			border-color: #0f0f10;
		}

		.app-aibot .aib-hero {
			padding: 20px 0 40px;
		}

		.app-aibot .aib-hero-inner {
			background: #fff;
			border: 1px solid #e7e2dd;
			border-radius: var(--aib-radius-lg);
			padding: 48px 52px 32px;
			box-shadow: var(--aib-shadow);
			display: grid;
			grid-template-columns: 1.15fr 1fr;
			gap: 40px;
			align-items: center;
		}

		.app-aibot .aib-tagline {
			display: flex;
			align-items: center;
			gap: 10px;
			font-weight: 600;
			font-size: 13px;
			color: var(--aib-muted);
			text-transform: uppercase;
			letter-spacing: 0.04em;
		}

		.app-aibot .aib-title {
			font-size: 42px;
			line-height: 1.2;
			margin: 12px 0 18px;
			font-weight: 700;
			color: var(--aib-strong);
		}

		.app-aibot .aib-lead {
			font-size: 18px;
			color: var(--aib-muted);
			margin-bottom: 22px;
			max-width: 560px;
		}

		.app-aibot .aib-actions {
			display: flex;
			gap: 14px;
			margin-top: 8px;
		}

		.app-aibot .aib-btn {
			padding: 14px 18px;
			border-radius: 14px;
			font-weight: 700;
			border: 1px solid #0f0f10;
			background: #0f0f10;
			color: #fff;
			box-shadow: 0 14px 30px rgba(0, 0, 0, 0.16);
		}

		.app-aibot .aib-btn.secondary {
			background: #fff;
			color: #0f0f10;
		}

		.app-aibot .aib-window {
			background: linear-gradient(180deg, #fdfbf9 0%, #f5f1ed 100%);
			border: 1px solid #ebe6e0;
			border-radius: 20px;
			padding: 18px;
			box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.35), 0 18px 50px rgba(0, 0, 0, 0.08);
		}

		.app-aibot .aib-window-header {
			display: flex;
			align-items: center;
			justify-content: space-between;
			margin-bottom: 14px;
			font-weight: 600;
			color: var(--aib-muted);
		}

		.app-aibot .aib-window-dots {
			display: flex;
			gap: 6px;
		}

		.app-aibot .aib-window-dots span {
			width: 10px;
			height: 10px;
			border-radius: 50%;
		}

		.app-aibot .dot-red { background: #ff5f57; }
		.app-aibot .dot-yellow { background: #ffbd2e; }
		.app-aibot .dot-green { background: #28c840; }

		.app-aibot .aib-window-body {
			background: #fff;
			border: 1px solid #ebe6e0;
			border-radius: 14px;
			padding: 20px;
			font-size: 14px;
			color: #2a2a2d;
			line-height: 1.6;
		}

		.app-aibot .aib-caption {
			margin-top: 16px;
			font-size: 13px;
			color: var(--aib-muted);
			text-align: center;
		}

		.app-aibot .aib-quote {
			text-align: center;
			padding: 36px 0 24px;
			font-size: 26px;
			color: #222;
			font-weight: 700;
		}

		.app-aibot .aib-quote cite {
			display: block;
			margin-top: 12px;
			font-weight: 600;
			color: var(--aib-muted);
			font-size: 14px;
			letter-spacing: 0.02em;
		}

		.app-aibot .aib-dark-section {
			background: #0b0b0f;
			color: #f6f6f8;
			padding: 84px 0 74px;
			position: relative;
			overflow: hidden;
		}

		.app-aibot .aib-dark-section::before,
		.app-aibot .aib-dark-section::after {
			content: "";
			position: absolute;
			width: 520px;
			height: 520px;
			background: radial-gradient(circle at center, rgba(255, 255, 255, 0.07), transparent 60%);
			filter: blur(60px);
			z-index: 0;
		}

		.app-aibot .aib-dark-section::before { top: -200px; left: -120px; }
		.app-aibot .aib-dark-section::after { bottom: -260px; right: -120px; }

		.app-aibot .aib-dark-inner { position: relative; z-index: 1; }

		.app-aibot .aib-section-title {
			text-align: center;
			font-size: 32px;
			margin-bottom: 14px;
			font-weight: 700;
		}

		.app-aibot .aib-section-lead {
			text-align: center;
			color: #a5a5ad;
			margin-bottom: 48px;
			font-size: 16px;
		}

		.app-aibot .aib-grid {
			display: grid;
			gap: 18px;
			grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
		}

		.app-aibot .aib-card {
			background: #151519;
			border: 1px solid rgba(255, 255, 255, 0.04);
			border-radius: var(--aib-radius-md);
			padding: 22px 20px;
			box-shadow: 0 22px 50px rgba(0, 0, 0, 0.25);
		}

		.app-aibot .aib-card-header {
			display: flex;
			align-items: center;
			gap: 12px;
			margin-bottom: 12px;
		}

		.app-aibot .aib-icon {
			width: 40px;
			height: 40px;
			border-radius: 12px;
			background: rgba(255, 255, 255, 0.04);
			display: grid;
			place-items: center;
			color: #fff;
			font-weight: 700;
		}

		.app-aibot .aib-card h3 {
			margin: 0;
			font-size: 18px;
		}

		.app-aibot .aib-card p {
			margin: 0;
			color: #b6b6bf;
			font-size: 14px;
			line-height: 1.6;
		}

		.app-aibot .aib-demos {
			padding: 82px 0;
			background: #f5f2ef;
		}

		.app-aibot .aib-demo-grid {
			display: grid;
			grid-template-columns: repeat(auto-fit, minmax(520px, 1fr));
			gap: 28px;
		}

		.app-aibot .aib-demo-card {
			background: #fff;
			border-radius: var(--aib-radius-lg);
			padding: 18px;
			box-shadow: var(--aib-shadow);
			border: 1px solid #ece7e3;
			position: relative;
			overflow: hidden;
		}

		.app-aibot .aib-demo-card::before {
			content: "";
			position: absolute;
			inset: 0;
			padding: 10px;
			border-radius: inherit;
			background: var(--aib-gradient);
			z-index: 0;
		}

		.app-aibot .aib-demo-inner {
			position: relative;
			z-index: 1;
			background: #fff;
			border-radius: calc(var(--aib-radius-lg) - 10px);
			padding: 16px;
			min-height: 320px;
			border: 1px solid #ebe6e0;
			box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.6);
		}

		.app-aibot .aib-demo-meta {
			margin-top: 16px;
			color: var(--aib-muted);
			font-size: 14px;
		}

		.app-aibot .aib-latest {
			padding: 64px 0 32px;
			text-align: center;
		}

		.app-aibot .aib-post-grid {
			display: grid;
			grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
			gap: 18px;
			margin-top: 24px;
		}

		.app-aibot .aib-post {
			background: #fff;
			border: 1px solid #ece7e3;
			border-radius: 18px;
			overflow: hidden;
			text-align: left;
			box-shadow: 0 18px 40px rgba(0, 0, 0, 0.08);
		}

		.app-aibot .aib-post img {
			width: 100%;
			height: 180px;
			object-fit: cover;
		}

		.app-aibot .aib-post-body {
			padding: 16px 16px 18px;
		}

		.app-aibot .aib-post-title {
			font-weight: 700;
			margin: 0 0 6px;
		}

		.app-aibot .aib-post-date {
			color: var(--aib-muted);
			font-size: 13px;
		}

		.app-aibot .aib-faq {
			padding: 10px 0 64px;
		}

		.app-aibot .aib-faq-list {
			max-width: 860px;
			margin: 0 auto;
			border-top: 1px solid var(--aib-border);
		}

		.app-aibot details {
			padding: 16px 0;
			border-bottom: 1px solid var(--aib-border);
		}

		.app-aibot summary {
			cursor: pointer;
			font-weight: 700;
			display: flex;
			align-items: center;
			gap: 8px;
			font-size: 15px;
		}

		.app-aibot details p {
			margin: 10px 0 0;
			color: var(--aib-muted);
		}

		.app-aibot .aib-cta {
			background: #0e0d11;
			color: #f6f6f8;
			padding: 82px 0 76px;
			text-align: center;
		}

		.app-aibot .aib-cta .aib-section-title { color: #f6f6f8; }
		.app-aibot .aib-cta .aib-section-lead { color: #9a99a4; margin-bottom: 22px; }
		
		@media (max-width: 960px) {
			.app-aibot .aib-hero-inner {
				grid-template-columns: 1fr;
				padding: 36px 32px;
			}

			.app-aibot .aib-demo-grid {
				grid-template-columns: 1fr;
			}
		}

		@media (max-width: 720px) {
			.app-aibot .aib-topbar { flex-direction: column; align-items: flex-start; }
			.app-aibot .aib-nav-links { flex-wrap: wrap; }
			.app-aibot .aib-hero-inner { padding: 30px 24px; }
			.app-aibot .aib-title { font-size: 34px; }
		}
	</style>

	<section class="aib-container aib-topbar" aria-label="Top navigation">
		<div class="aib-brand">
			<span class="aib-dot">ai</span>
			<span>App AiBoot</span>
		</div>
		<div class="aib-nav-links">
			<span>Products</span>
			<span>Documentation</span>
			<span>Pricing</span>
			<span>Blog</span>
		</div>
		<div class="aib-cta-group">
			<button class="aib-pill secondary">Start free</button>
			<button class="aib-pill">Sign in</button>
		</div>
	</section>

	<section class="aib-container aib-hero">
		<div class="aib-hero-inner">
			<div>
				<div class="aib-tagline"><span>Desktop agents</span><span>Cloud scale</span></div>
				<h1 class="aib-title">Desktop agents that use computers like a human — at cloud scale.</h1>
				<p class="aib-lead">Desktop tasks feel like bespoke bots with sandboxed computer use and complex workflows that operate in the browser. Save time by bridging the gap to your desktop applications.</p>
				<div class="aib-actions">
					<button class="aib-btn">Get started</button>
					<button class="aib-btn secondary">Book a live demo</button>
				</div>
			</div>
			<div class="aib-window" aria-label="Preview panel">
				<div class="aib-window-header">
					<div class="aib-window-dots"><span class="dot-red"></span><span class="dot-yellow"></span><span class="dot-green"></span></div>
					<span>Preview (v0.9)</span>
				</div>
				<div class="aib-window-body">
					<p><strong>Prompt</strong><br>Checkout page scraped using Browser use.<br><br><strong>Observation</strong><br>This appears to be the payment step for the Checkout page, which requests card details. It lists the price breakdown and total amount in USD.<br><br><strong>Actions</strong><br>Proceed to the payment step by clicking Continue.<br><br><strong>Completion</strong><br>Demonstrated Browser-Use Desktop Agent in action.</p>
				</div>
				<p class="aib-caption">Browser Use running on a Mac data center</p>
			</div>
		</div>
	</section>

	<section class="aib-container aib-quote">
		<p>“Desktop agents are the missing link between LLMs and real work.”</p>
		<cite>— The Age of Use Desktop Agents blog</cite>
	</section>

	<section class="aib-dark-section">
		<div class="aib-container aib-dark-inner">
			<h2 class="aib-section-title">Why a Desktop Agent</h2>
			<p class="aib-section-lead">A desktop agent is the ideal automation solution because it works just like an employee, making it universally compatible with any software.</p>
			<div class="aib-grid">
				<div class="aib-card">
					<div class="aib-card-header">
						<span class="aib-icon">Fx</span>
						<h3>Firefox</h3>
					</div>
					<p>Need rich browser support for filling forms, loading complex apps, and managing tabs? Desktop agents handle multi-step browsing without custom builds.</p>
				</div>
				<div class="aib-card">
					<div class="aib-card-header">
						<span class="aib-icon">Kb</span>
						<h3>Fine-grained control</h3>
					</div>
					<p>Mouse movements and keystrokes let agents mimic how real users work with software on any OS, keeping compliance simple.</p>
				</div>
				<div class="aib-card">
					<div class="aib-card-header">
						<span class="aib-icon">Tx</span>
						<h3>Tasks &amp; routing</h3>
					</div>
					<p>Chain together complex actions: login sequences, file uploads, report extraction, and data-entry workflows across browser windows.</p>
				</div>
				<div class="aib-card">
					<div class="aib-card-header">
						<span class="aib-icon">Cr</span>
						<h3>History and logs</h3>
					</div>
					<p>Review every keystroke and screenshot to keep teams confident in how agents operate when processing sensitive workloads.</p>
				</div>
				<div class="aib-card">
					<div class="aib-card-header">
						<span class="aib-icon">Sec</span>
						<h3>Secure &amp; trackable</h3>
					</div>
					<p>Sandboxing, audit logs, and one-click approval flows make it easy to comply with SOC 2/GDPR without limiting productivity.</p>
				</div>
				<div class="aib-card">
					<div class="aib-card-header">
						<span class="aib-icon">🏢</span>
						<h3>Works with your stack</h3>
					</div>
					<p>Integrates with Google Cloud, AWS, Azure, and private data centers so agents run where you already operate.</p>
				</div>
			</div>
		</div>
	</section>

	<section class="aib-container aib-demos">
		<h2 class="aib-section-title">Live Demos</h2>
		<p class="aib-section-lead">Watch how desktop agents automate complex flows.</p>
		<div class="aib-demo-grid">
			<div class="aib-demo-card">
				<div class="aib-demo-inner">
					<div class="aib-window-header"><span class="aib-window-dots"><span class="dot-red"></span><span class="dot-yellow"></span><span class="dot-green"></span></span><span>Browser</span></div>
					<div class="aib-window-body" style="min-height:200px">Automating software downloads, filling forms, and navigating portals with real mouse interactions.</div>
				</div>
				<p class="aib-demo-meta">Building Engineering Development Workflows</p>
			</div>
			<div class="aib-demo-card">
				<div class="aib-demo-inner">
					<div class="aib-window-header"><span class="aib-window-dots"><span class="dot-red"></span><span class="dot-yellow"></span><span class="dot-green"></span></span><span>Secure Login</span></div>
					<div class="aib-window-body" style="min-height:200px">Handling secure logins with 2FA, OTP copying, and session verification while preserving compliance.</div>
				</div>
				<p class="aib-demo-meta">Work chat + Secure browsing to login with 2FA</p>
			</div>
			<div class="aib-demo-card">
				<div class="aib-demo-inner">
					<div class="aib-window-header"><span class="aib-window-dots"><span class="dot-red"></span><span class="dot-yellow"></span><span class="dot-green"></span></span><span>Docs</span></div>
					<div class="aib-window-body" style="min-height:200px">Use natural language to orchestrate complex scripts, compile code, and generate reports inside virtual desktops.</div>
				</div>
				<p class="aib-demo-meta">The Eyeboat core — Free Linux Container for Agent Control Runtime</p>
			</div>
			<div class="aib-demo-card">
				<div class="aib-demo-inner">
					<div class="aib-window-header"><span class="aib-window-dots"><span class="dot-red"></span><span class="dot-yellow"></span><span class="dot-green"></span></span><span>Research</span></div>
					<div class="aib-window-body" style="min-height:200px">Technical research &amp; summarization powered by desktop-grade browsers and accurate copy/paste capture.</div>
				</div>
				<p class="aib-demo-meta">Technical Research &amp; Summarization</p>
			</div>
		</div>
	</section>

	<section class="aib-container aib-latest">
		<h2 class="aib-section-title">Latest posts</h2>
		<p class="aib-section-lead">Feature and updates from the team.</p>
		<div class="aib-post-grid">
			<div class="aib-post">
				<img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=900&q=80" alt="Robot illustration">
				<div class="aib-post-body">
					<h3 class="aib-post-title">The Eyeboat core — Free Linux Container for Agent Control Runtime</h3>
					<p class="aib-post-date">Jan 14, 2025</p>
				</div>
			</div>
			<div class="aib-post">
				<img src="https://images.unsplash.com/photo-1504384308090-c894fdcc538d?auto=format&fit=crop&w=900&q=80" alt="Agent avatar">
				<div class="aib-post-body">
					<h3 class="aib-post-title">Why the Simplest Desktop Agent Abstraction Wins</h3>
					<p class="aib-post-date">Jan 12, 2025</p>
				</div>
			</div>
			<div class="aib-post">
				<img src="https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=900&q=80" alt="Laptop desk">
				<div class="aib-post-body">
					<h3 class="aib-post-title">The Age of the Desktop Agent Is Nearing</h3>
					<p class="aib-post-date">Aug 17, 2024</p>
				</div>
			</div>
		</div>
	</section>

	<section class="aib-container aib-faq">
		<h2 class="aib-section-title">Frequently Asked Questions</h2>
		<div class="aib-faq-list">
			<details open>
				<summary>What is Eyeboat?</summary>
				<p>Eyeboat is a desktop agent that uses computer interfaces like a human. It works across browsers, desktop apps, and sandboxed environments to automate complex workflows.</p>
			</details>
			<details>
				<summary>How is Eyeboat different from traditional RPA tools like UiPath?</summary>
				<p>Eyeboat relies on real browser control, keystrokes, and mouse actions, making it more adaptable than brittle DOM-based automation.</p>
			</details>
			<details>
				<summary>What can Eyeboat actually do?</summary>
				<p>Automate web portals, enterprise logins, form submissions, file uploads, data entry, and complex multi-step business workflows.</p>
			</details>
			<details>
				<summary>Do my agents scale with Eyeboat?</summary>
				<p>Yes. Run agents across cloud browsers or dedicated desktop instances with resource isolation.</p>
			</details>
			<details>
				<summary>How quickly can I get started?</summary>
				<p>Provision a desktop agent in minutes. Use our starter templates or import your own prompts to begin testing quickly.</p>
			</details>
			<details>
				<summary>Do I need coding skills to use Eyeboat?</summary>
				<p>No coding is required for basic automation. Advanced users can add custom scripts or API calls if needed.</p>
			</details>
			<details>
				<summary>What if I need offline desktop support?</summary>
				<p>Agents can run in isolated networks with strict ingress controls while still providing full audit logs.</p>
			</details>
			<details>
				<summary>Can Eyeboat handle authentication and 2FA?</summary>
				<p>Yes. Agents securely capture OTPs, handle authenticator apps, and keep credentials vaulted.</p>
			</details>
			<details>
				<summary>What applications can Eyeboat use?</summary>
				<p>Any software accessible through a browser or desktop UI, including VDI environments.</p>
			</details>
			<details>
				<summary>How much does Eyeboat cost?</summary>
				<p>Transparent pricing based on seats and runtime hours. Contact sales for enterprise discounts.</p>
			</details>
			<details>
				<summary>Does Eyeboat handle website changes?</summary>
				<p>Agents rely on real UI cues, so minor DOM changes won't break flows. Update prompts or actions as needed.</p>
			</details>
			<details>
				<summary>What if I already have automation infrastructure?</summary>
				<p>Eyeboat plugs into your existing orchestration via webhooks, APIs, and message queues.</p>
			</details>
			<details>
				<summary>Have does Eyeboat compare to other AI agents?</summary>
				<p>It focuses on reliability with human-like interaction, rather than code generation alone.</p>
			</details>
			<details>
				<summary>Is Eyeboat suitable for enterprise use?</summary>
				<p>Yes. SOC 2-ready logging, SSO, and RBAC are built-in for enterprise security.</p>
			</details>
			<details>
				<summary>What kind of support is available?</summary>
				<p>Hands-on onboarding, live chat, and dedicated success managers for qualified plans.</p>
			</details>
		</div>
	</section>

	<section class="aib-cta">
		<div class="aib-container">
			<h2 class="aib-section-title">Ready to Hire Your First Desktop Agent?</h2>
			<p class="aib-section-lead">Start automating while staying secure—workflows in minutes, not months.</p>
			<button class="aib-btn" style="box-shadow: 0 18px 42px rgba(0,0,0,0.25);">Get started →</button>
		</div>
	</section>
</main>
<?php
get_footer();
