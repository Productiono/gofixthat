<?php
/**
 * Template Name: Lead Gen
 * Template Post Type: page
 *
 * @package Apparel
 */

get_header();
?>

<div class="lead-gen-page">
	<header class="lead-gen-header">
		<div class="lead-gen-header__logo" aria-label="GoFixThat">
			<svg viewBox="0 0 40 40" role="img" aria-hidden="true">
				<circle cx="20" cy="20" r="18" fill="#ffffff" opacity="0.9" />
				<path d="M20 10l8 4v12l-8 4-8-4V14z" fill="#111" />
			</svg>
			<span>GoFixThat</span>
		</div>
	</header>

	<section class="lead-gen-hero">
		<div class="lead-gen-hero__collage">
			<span class="lead-gen-hero__tile lead-gen-hero__tile--one"></span>
			<span class="lead-gen-hero__tile lead-gen-hero__tile--two"></span>
			<span class="lead-gen-hero__tile lead-gen-hero__tile--three"></span>
			<span class="lead-gen-hero__tile lead-gen-hero__tile--four"></span>
			<span class="lead-gen-hero__tile lead-gen-hero__tile--five"></span>
		</div>
		<div class="lead-gen-hero__card">
			<h1>Your business starts with GoFixThat</h1>
			<p>Start for free, keep building for 1€/month.<br />Plus, earn up to 8 000 € in credits as you sell.</p>
			<div class="lead-gen-hero__cta">
				<span>Start for free</span>
				<small>You agree to receive marketing emails.</small>
				<form class="lead-gen-form" action="#">
					<label class="screen-reader-text" for="lead-gen-email">Email</label>
					<input type="email" id="lead-gen-email" name="lead-gen-email" placeholder="Enter your email" />
					<button type="submit" aria-label="Submit email">
						<span aria-hidden="true">→</span>
					</button>
				</form>
			</div>
		</div>
	</section>

	<div class="mbf-cookie-consent lead-gen-cookie" hidden>
		<p>We use cookies to improve your experience and for analytics. See our <a href="#">Cookie Policy</a>.</p>
		<div class="lead-gen-cookie__actions">
			<button type="button" class="lead-gen-cookie__button is-secondary" data-cookie-consent="reject">Reject all</button>
			<button type="button" class="lead-gen-cookie__button" data-cookie-consent="accept">Accept cookies</button>
		</div>
	</div>

	<section class="lead-gen-logos">
		<div class="lead-gen-logos__inner">
			<span>allbirds</span>
			<span>GYMSHARK</span>
			<span>brooklinen</span>
			<span>Leesa</span>
			<span>KYLIE</span>
			<span>Crate&amp;Barrel</span>
			<span>MONOS</span>
		</div>
	</section>

	<section class="lead-gen-features">
		<div class="lead-gen-features__grid">
			<article class="lead-gen-feature">
				<span class="lead-gen-feature__tag">CUSTOMIZABLE THEMES</span>
				<div class="lead-gen-feature__media lead-gen-feature__media--themes"></div>
				<h3>Create a stunning store in seconds</h3>
				<p>Pre-built designs make it fast and easy to kickstart your brand.</p>
			</article>
			<article class="lead-gen-feature">
				<span class="lead-gen-feature__tag">GET REWARDED</span>
				<div class="lead-gen-feature__media lead-gen-feature__media--rewards"></div>
				<h3>Your plan can pay for itself</h3>
				<p>Turn sales into savings with 0.5% back as subscription credits.</p>
			</article>
			<article class="lead-gen-feature">
				<span class="lead-gen-feature__tag">MEET SIDEBOT</span>
				<div class="lead-gen-feature__media lead-gen-feature__media--assistant"></div>
				<h3>Level up with an AI assistant</h3>
				<p>Selling is easy with a built-in business partner who can help scale your vision.</p>
			</article>
			<article class="lead-gen-feature">
				<span class="lead-gen-feature__tag">ALL-IN-ONE</span>
				<div class="lead-gen-feature__media lead-gen-feature__media--pos"></div>
				<h3>Getting stuff done? Done.</h3>
				<p>GoFixThat handles everything from secure payments to marketing and hardware.</p>
			</article>
		</div>
	</section>

	<section class="lead-gen-testimonial">
		<blockquote>
			“We've tripled in size since we first started on GoFixThat. It gives us the tools we need to keep pushing forward.”
		</blockquote>
		<p class="lead-gen-testimonial__author">Clare Jerome, NEOM Wellbeing</p>
	</section>

	<section class="lead-gen-cta">
		<div class="lead-gen-cta__panel">
			<div class="lead-gen-cta__icon">
				<svg viewBox="0 0 32 32" role="img" aria-hidden="true">
					<path d="M6 10l10-6 10 6v14l-10 6-10-6z" fill="#fff" opacity="0.9" />
					<path d="M16 14l6 3v7l-6 3-6-3v-7z" fill="#3c1bb7" />
				</svg>
			</div>
			<h2>No risk, all rewards.<br />Try GoFixThat for 1€/month.</h2>
			<p>Plus, earn up to 8 000 € in credits as you sell.</p>
			<form class="lead-gen-form" action="#">
				<label class="screen-reader-text" for="lead-gen-email-secondary">Email</label>
				<input type="email" id="lead-gen-email-secondary" name="lead-gen-email-secondary" placeholder="Enter your email" />
				<button type="submit" aria-label="Submit email">
					<span aria-hidden="true">→</span>
				</button>
			</form>
			<small>You agree to receive GoFixThat marketing emails.</small>
		</div>
	</section>

	<section class="lead-gen-faq">
		<h2>Questions?</h2>
		<div class="lead-gen-faq__list">
			<div class="lead-gen-faq__item">
				<button type="button" class="lead-gen-faq__trigger" aria-expanded="false">
					<span>What is GoFixThat and how does it work?</span>
					<span class="lead-gen-faq__icon" aria-hidden="true"></span>
				</button>
				<div class="lead-gen-faq__content">
					<p>GoFixThat helps you launch and grow your business with storefront, payments, and marketing tools built in.</p>
				</div>
			</div>
			<div class="lead-gen-faq__item">
				<button type="button" class="lead-gen-faq__trigger" aria-expanded="false">
					<span>How much does GoFixThat cost?</span>
					<span class="lead-gen-faq__icon" aria-hidden="true"></span>
				</button>
				<div class="lead-gen-faq__content">
					<p>Start free, then pay 1€/month after your trial period. Earn credits as you grow.</p>
				</div>
			</div>
			<div class="lead-gen-faq__item">
				<button type="button" class="lead-gen-faq__trigger" aria-expanded="false">
					<span>Can I use my own domain name with GoFixThat?</span>
					<span class="lead-gen-faq__icon" aria-hidden="true"></span>
				</button>
				<div class="lead-gen-faq__content">
					<p>Yes. Connect your existing domain or buy a new one in minutes.</p>
				</div>
			</div>
			<div class="lead-gen-faq__item">
				<button type="button" class="lead-gen-faq__trigger" aria-expanded="false">
					<span>Do I need to be a designer or developer to use GoFixThat?</span>
					<span class="lead-gen-faq__icon" aria-hidden="true"></span>
				</button>
				<div class="lead-gen-faq__content">
					<p>No. Choose a template, add your products, and publish with zero code.</p>
				</div>
			</div>
		</div>
	</section>

	<footer class="lead-gen-footer">
		<div class="lead-gen-footer__logo">G</div>
		<nav class="lead-gen-footer__links">
			<a href="#">Terms of Service</a>
			<a href="#">Privacy Policy</a>
			<a href="#">Sitemap</a>
			<a href="#">Your Privacy Choices</a>
		</nav>
	</footer>
</div>

<script>
	document.querySelectorAll('.lead-gen-faq__trigger').forEach((trigger) => {
		trigger.addEventListener('click', () => {
			const item = trigger.closest('.lead-gen-faq__item');
			const isOpen = item.classList.contains('is-open');
			document.querySelectorAll('.lead-gen-faq__item').forEach((other) => {
				other.classList.remove('is-open');
				const button = other.querySelector('.lead-gen-faq__trigger');
				button.setAttribute('aria-expanded', 'false');
			});
			if (!isOpen) {
				item.classList.add('is-open');
				trigger.setAttribute('aria-expanded', 'true');
			}
		});
	});
</script>

<?php
get_footer();
?>
