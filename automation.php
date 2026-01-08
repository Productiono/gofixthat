<?php
/**
 * Template Name: Funnel - Automation
 * Template Post Type: page
 *
 * @package Apparel
 */

get_header();

// Remove default title outputs for this template.
remove_action( 'mbf_main_before', 'mbf_entry_header', 10 );
remove_action( 'mbf_main_before', 'mbf_page_header', 100 );
?>

<div id="primary" class="mbf-content-area">
	<?php
	/**
	 * The mbf_main_before hook.
	 *
	 * @since 1.0.0
	 */
	do_action( 'mbf_main_before' );
	?>
	<?php while ( have_posts() ) : the_post(); ?>
		<section class="mbf-funnel" aria-labelledby="mbf-funnel-heading">
			<div class="mbf-container">
				<div class="mbf-funnel__grid">
					<div class="mbf-funnel__content">
						<h1 id="mbf-funnel-heading" class="mbf-funnel__headline">
							<?php esc_html_e( 'Automate workflows with AI agents that save hours every week', 'apparel' ); ?>
						</h1>

						<ul class="mbf-funnel__benefits" aria-label="<?php esc_attr_e( 'Product benefits', 'apparel' ); ?>">
							<li class="mbf-funnel__benefit">
								<span class="mbf-funnel__icon" aria-hidden="true">
									<svg viewBox="0 0 24 24" role="presentation" focusable="false">
										<path d="M4 4h16a1 1 0 0 1 1 1v11.5a1 1 0 0 1-1 1h-6.25l-1.45 2.43a1 1 0 0 1-1.7 0L9.1 17.5H4a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1Zm1 2v9.5h4.6a1 1 0 0 1 .85.47l.55.93.55-.93a1 1 0 0 1 .86-.47H19V6H5Z" />
									</svg>
								</span>
								<p><?php esc_html_e( 'Deploy pre-built automation agents to streamline recurring tasks fast.', 'apparel' ); ?></p>
							</li>
							<li class="mbf-funnel__benefit">
								<span class="mbf-funnel__icon" aria-hidden="true">
									<svg viewBox="0 0 24 24" role="presentation" focusable="false">
										<path d="M4.5 6A1.5 1.5 0 0 1 6 4.5h12A1.5 1.5 0 0 1 19.5 6v3A1.5 1.5 0 0 1 18 10.5H6A1.5 1.5 0 0 1 4.5 9V6Zm1.5.5v2h12v-2H6Zm-1.5 8A1.5 1.5 0 0 1 6 13h6a1.5 1.5 0 0 1 1.5 1.5v3A1.5 1.5 0 0 1 12 19H6a1.5 1.5 0 0 1-1.5-1.5v-3ZM6 14.5v2h6v-2H6Zm9.75-.75a.75.75 0 0 1 .75-.75h2.5a.75.75 0 0 1 .75.75V18a.75.75 0 0 1-.75.75h-2.5a.75.75 0 0 1-.75-.75v-3.25Z" />
									</svg>
								</span>
								<p><?php esc_html_e( 'Design agents across voice, chat, SMS, and WhatsApp without manual setup.', 'apparel' ); ?></p>
							</li>
							<li class="mbf-funnel__benefit">
								<span class="mbf-funnel__icon" aria-hidden="true">
									<svg viewBox="0 0 24 24" role="presentation" focusable="false">
										<path d="M6.5 3A2.5 2.5 0 0 0 4 5.5V9H3a1 1 0 0 0-.96 1.28l1.5 5A1 1 0 0 0 4.5 16H5v2.5A2.5 2.5 0 0 0 7.5 21h9a2.5 2.5 0 0 0 2.5-2.5v-13A2.5 2.5 0 0 0 16.5 3h-10ZM16 5a.5.5 0 0 1 .5.5v13a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V17h3.5a1 1 0 0 0 .96-.72L12 12.72l1.04 3.56a1 1 0 0 0 .96.72H16V5Z" />
									</svg>
								</span>
								<p><?php esc_html_e( 'Connect knowledge bases and systems so every workflow stays context-aware.', 'apparel' ); ?></p>
							</li>
							<li class="mbf-funnel__benefit">
								<span class="mbf-funnel__icon" aria-hidden="true">
									<svg viewBox="0 0 24 24" role="presentation" focusable="false">
										<path d="M9 4a1 1 0 0 1 1 1v6.586l1.293-1.293a1 1 0 0 1 1.414 0L17 14.586V15a1 1 0 0 1-1 1h-6a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1Zm6 6V5a1 1 0 1 1 2 0v5.586l1.293-1.293a1 1 0 0 1 1.414 0L22 11.586V12a1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1Zm-8 3a1 1 0 0 1 1 1v1.586l1.293-1.293a1 1 0 0 1 1.414 0L13 17.586V18a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1Z" />
									</svg>
								</span>
								<p><?php esc_html_e( 'Trigger updates and integrations automatically with fewer manual handoffs.', 'apparel' ); ?></p>
							</li>
						</ul>

						<hr class="mbf-funnel__divider" />

						<div class="mbf-funnel__trust">
							<p class="mbf-funnel__trust-label"><?php esc_html_e( 'Trusted by operations teams worldwide', 'apparel' ); ?></p>
							<div class="mbf-funnel__logos" aria-label="<?php esc_attr_e( 'Customer logos', 'apparel' ); ?>">
								<span class="mbf-funnel__logo">Discord</span>
								<span class="mbf-funnel__logo">Trip.com</span>
								<span class="mbf-funnel__logo">Meta</span>
								<span class="mbf-funnel__logo">Uber</span>
								<span class="mbf-funnel__logo">GoDaddy</span>
								<span class="mbf-funnel__logo">DocuSign</span>
							</div>
						</div>
					</div>

					<aside class="mbf-funnel__form" aria-label="<?php esc_attr_e( 'Get access to your trial account', 'apparel' ); ?>">
						<div class="mbf-funnel__form-card">
							<h2 class="mbf-funnel__form-title"><?php esc_html_e( 'Get access to your automation trial', 'apparel' ); ?></h2>
							<?php echo do_shortcode( '[fluentform id="FORM_ID_AUTOMATION"]' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<p class="mbf-funnel__form-footnote">
								<?php
								printf(
									/* translators: 1: Terms of Service URL, 2: Privacy Policy URL */
									wp_kses(
										__( 'By creating an account, you agree to the site <a href="%1$s">Terms of Service</a> and <a href="%2$s">Privacy Policy</a>.', 'apparel' ),
										array(
											'a' => array(
												'href' => array(),
												'target' => array(),
												'rel'   => array(),
											),
										)
									)
								,
									esc_url( home_url( '/terms-of-service' ) ),
									esc_url( function_exists( 'get_privacy_policy_url' ) ? get_privacy_policy_url() : home_url( '/privacy-policy' ) )
								);
								?>
							</p>
						</div>
					</aside>
				</div>
			</div>
		</section>
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

<?php get_footer(); ?>
