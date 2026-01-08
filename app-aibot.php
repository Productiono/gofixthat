<?php
/* Template Name: App AiBoot */

get_header(); ?>

<style>
.app-aibot-page {
    font-family: "Inter", "Helvetica Neue", Arial, sans-serif;
    color: #1f1f22;
    background: #f7f6f3;
}

.app-aibot-page .section-wrap {
    background: #f7f6f3;
}

.app-aibot-page .inner {
    max-width: 1200px;
    margin: 0 auto;
    padding: 72px 24px;
}

.app-aibot-page .hero {
    padding-top: 48px;
    background: linear-gradient(180deg, #f7f6f3 0%, #f3f1ee 100%);
}

.app-aibot-page .hero-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 32px;
    align-items: center;
}

.app-aibot-page .badge-row {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 12px;
}

.app-aibot-page .badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    padding: 6px 12px;
    border-radius: 999px;
    background: #e4e1dc;
    color: #44414c;
    text-transform: uppercase;
    letter-spacing: 0.02em;
    font-weight: 600;
}

.app-aibot-page .badge svg,
.app-aibot-page .badge img {
    width: 16px;
    height: 16px;
}

.app-aibot-page h1 {
    font-size: 32px;
    line-height: 1.28;
    margin: 10px 0 16px;
    color: #221f23;
    letter-spacing: -0.01em;
}

.app-aibot-page .hero p.lead {
    font-size: 16px;
    line-height: 1.7;
    color: #4c4b52;
    margin-bottom: 18px;
}

.app-aibot-page .hero .small-label {
    font-size: 13px;
    font-weight: 600;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    color: #8f8c96;
}

.app-aibot-page .hero .cta-row {
    display: flex;
    align-items: center;
    gap: 14px;
}

.app-aibot-page .btn-primary {
    background: #18191d;
    color: #fdfbf7;
    padding: 14px 22px;
    border-radius: 10px;
    border: none;
    text-decoration: none;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 12px 28px rgba(0,0,0,0.12);
}

.app-aibot-page .btn-link {
    color: #343037;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
}

.app-aibot-page .hero-illustration {
    background: #ffffff;
    border-radius: 18px;
    padding: 24px;
    border: 1px solid #e3e0da;
    box-shadow: 0 20px 60px rgba(0,0,0,0.08);
}

.app-aibot-page .hero-illustration img {
    width: 100%;
    border-radius: 12px;
    display: block;
}

.app-aibot-page .quote {
    text-align: center;
    padding: 52px 24px 60px;
    background: #f2f0ec;
    font-size: 18px;
    color: #1e1c22;
    line-height: 1.7;
    font-weight: 600;
    border-top: 1px solid #e6e1db;
    border-bottom: 1px solid #e6e1db;
}

.app-aibot-page .quote span {
    display: block;
    margin-top: 12px;
    font-size: 14px;
    font-weight: 600;
    color: #7b7781;
}

.app-aibot-page .dark-section {
    background: radial-gradient(circle at 20% 20%, rgba(255,255,255,0.06), transparent 35%),
                radial-gradient(circle at 80% 10%, rgba(255,255,255,0.04), transparent 35%),
                #0f1115;
    color: #f7f6f3;
}

.app-aibot-page .dark-section .inner {
    padding: 80px 24px 90px;
}

.app-aibot-page .section-title {
    text-align: center;
    font-size: 22px;
    letter-spacing: 0.02em;
    color: #f5f3ef;
    margin-bottom: 12px;
}

.app-aibot-page .section-subtitle {
    text-align: center;
    color: #b5b2bc;
    font-size: 15px;
    max-width: 720px;
    margin: 0 auto 42px;
    line-height: 1.7;
}

.app-aibot-page .feature-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 18px;
}

.app-aibot-page .feature-card {
    border-radius: 18px;
    padding: 22px;
    background: linear-gradient(145deg, rgba(255,255,255,0.04), rgba(255,255,255,0.02));
    border: 1px solid rgba(255,255,255,0.06);
    box-shadow: 0 14px 36px rgba(0,0,0,0.35);
}

.app-aibot-page .feature-card img.icon {
    width: 48px;
    height: 48px;
    margin-bottom: 14px;
}

.app-aibot-page .feature-card h3 {
    font-size: 16px;
    margin: 0 0 8px;
    color: #f9f7f3;
}

.app-aibot-page .feature-card p {
    margin: 0 0 10px;
    color: #b7b4be;
    line-height: 1.6;
    font-size: 14px;
}

.app-aibot-page .tag-list {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin-top: 10px;
}

.app-aibot-page .tag {
    padding: 8px 12px;
    border-radius: 10px;
    background: rgba(255,255,255,0.06);
    color: #d8d6de;
    font-size: 13px;
}

.app-aibot-page .live-demos {
    background: #f7f6f3;
}

.app-aibot-page .demos-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 22px;
    align-items: start;
}

.app-aibot-page .demo-card {
    background: #fff;
    border-radius: 18px;
    padding: 18px;
    box-shadow: 0 16px 44px rgba(0,0,0,0.08);
    border: 1px solid #e3e0da;
}

.app-aibot-page .demo-card .chrome-bar {
    display: flex;
    gap: 6px;
    margin-bottom: 12px;
}

.app-aibot-page .demo-card .dot {
    width: 11px;
    height: 11px;
    border-radius: 50%;
    background: #d05c56;
}

.app-aibot-page .demo-card .dot.yellow { background: #e2b04a; }
.app-aibot-page .demo-card .dot.green { background: #5aa565; }

.app-aibot-page .demo-card img {
    width: 100%;
    border-radius: 14px;
}

.app-aibot-page .demo-text {
    background: #fff;
    border-radius: 18px;
    padding: 22px;
    border: 1px solid #e5e0da;
    box-shadow: 0 12px 40px rgba(0,0,0,0.07);
}

.app-aibot-page .demo-text h4 {
    font-size: 15px;
    color: #a0a4ae;
    margin: 0 0 10px;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.app-aibot-page .demo-text h3 {
    margin: 0 0 10px;
    font-size: 18px;
    color: #1f1e23;
}

.app-aibot-page .demo-text p {
    margin: 0;
    color: #5b5960;
    line-height: 1.6;
    font-size: 14px;
}

.app-aibot-page .demos-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 20px;
    margin-top: 22px;
}

.app-aibot-page .demo-card.gradient {
    background: linear-gradient(135deg, #f9b3c5, #f1c0d7, #b6f3be);
    padding: 20px;
}

.app-aibot-page .demo-card.gradient .demo-inner {
    background: #fff;
    border-radius: 16px;
    padding: 16px;
}

.app-aibot-page .latest-posts {
    background: #f7f6f3;
    text-align: center;
}

.app-aibot-page .post-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 18px;
    margin-top: 22px;
}

.app-aibot-page .post-card {
    background: #fff;
    border-radius: 14px;
    overflow: hidden;
    border: 1px solid #e3e0da;
    box-shadow: 0 12px 32px rgba(0,0,0,0.06);
    text-align: left;
}

.app-aibot-page .post-card img {
    width: 100%;
    display: block;
}

.app-aibot-page .post-card .body {
    padding: 14px 16px 16px;
}

.app-aibot-page .post-card .tag-line {
    font-size: 13px;
    color: #a5a2ab;
    font-weight: 700;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    margin-bottom: 6px;
}

.app-aibot-page .post-card h5 {
    margin: 0 0 6px;
    font-size: 16px;
    color: #27252b;
}

.app-aibot-page .post-card .date {
    font-size: 13px;
    color: #8e8b95;
}

.app-aibot-page .faq {
    background: #f7f6f3;
}

.app-aibot-page .faq-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 24px;
}

.app-aibot-page .faq-item {
    background: #fff;
    padding: 18px 20px;
    border-radius: 14px;
    border: 1px solid #e3e0da;
    box-shadow: 0 12px 30px rgba(0,0,0,0.05);
}

.app-aibot-page .faq-item summary {
    font-weight: 700;
    font-size: 15px;
    color: #25232a;
    cursor: pointer;
}

.app-aibot-page .faq-item p {
    color: #5d5a64;
    line-height: 1.6;
    margin: 10px 0 0;
    font-size: 14px;
}

.app-aibot-page .cta-dark {
    background: #0f1115;
    color: #f7f6f3;
    text-align: center;
    padding: 72px 24px 64px;
    border-top: 1px solid rgba(255,255,255,0.06);
}

.app-aibot-page .cta-dark h2 {
    font-size: 24px;
    margin-bottom: 10px;
    letter-spacing: -0.01em;
}

.app-aibot-page .cta-dark p {
    color: #b7b5be;
    max-width: 620px;
    margin: 0 auto 20px;
    line-height: 1.7;
}

@media (max-width: 768px) {
    .app-aibot-page .inner {
        padding: 60px 18px;
    }

    .app-aibot-page h1 {
        font-size: 28px;
    }

    .app-aibot-page .hero-illustration {
        padding: 16px;
    }
}
</style>

<div id="primary" class="mbf-content-area">

	<?php
	/**
	 * The mbf_main_before hook.
	 *
	 * @since 1.0.0
	 */
	do_action( 'mbf_main_before' );
	?>

	<?php
	while ( have_posts() ) :
		the_post();
		?>

		<?php
		/**
		 * The mbf_page_before hook.
		 *
		 * @since 1.0.0
		 */
		do_action( 'mbf_page_before' );
		?>

		<main class="app-aibot-page">
			<div class="mbf-entry__wrap">

				<?php
				/**
				 * The mbf_entry_wrap_start hook.
				 *
				 * @since 1.0.0
				 */
				do_action( 'mbf_entry_wrap_start' );
				?>

				<div class="mbf-entry__container">

					<?php
					/**
					 * The mbf_entry_container_start hook.
					 *
					 * @since 1.0.0
					 */
					do_action( 'mbf_entry_container_start' );
					?>

					<?php
					/**
					 * The mbf_entry_content_before hook.
					 *
					 * @since 1.0.0
					 */
					do_action( 'mbf_entry_content_before' );
					?>

					<div class="entry-content">
						<?php the_content(); ?>
					</div>

					<?php
					/**
					 * The mbf_entry_content_after hook.
					 *
					 * @since 1.0.0
					 */
					do_action( 'mbf_entry_content_after' );
					?>

					<?php
					/**
					 * The mbf_entry_container_end hook.
					 *
					 * @since 1.0.0
					 */
					do_action( 'mbf_entry_container_end' );
					?>

				</div>

				<?php
				/**
				 * The mbf_entry_wrap_end hook.
				 *
				 * @since 1.0.0
				 */
				do_action( 'mbf_entry_wrap_end' );
				?>
			</div>
		</main>

		<?php
		/**
		 * The mbf_page_after hook.
		 *
		 * @since 1.0.0
		 */
		do_action( 'mbf_page_after' );
		?>

	<?php endwhile; ?>

	<?php
	/**
	 * The mbf_main_after hook.
	 *
	 * @since 1.0.0
	 */
	do_action( 'mbf_main_after' );
	?>

</div>

<?php get_sidebar(); ?>
<?php get_footer();
