<?php
/**
 * Template Name: Lead Gen
 * Template Post Type: page
 *
 * @package Apparel
 */

get_header( 'lead-gen' );

$post_id = get_the_ID();

$lead_gen_video        = get_post_meta( $post_id, 'lead_gen_video_url', true );
$lead_gen_poster       = get_post_meta( $post_id, 'lead_gen_poster_url', true );
$hero_background_image = get_post_meta( $post_id, 'lead_gen_hero_background_image', true );
$hero_logo             = get_post_meta( $post_id, 'lead_gen_hero_logo', true );
$default_video         = get_template_directory_uri() . '/assets/static/background-video.webm';
$video_url             = $lead_gen_video ? $lead_gen_video : $default_video;
$hero_headline         = get_post_meta( $post_id, 'lead_gen_hero_headline', true );
$hero_subheadline      = get_post_meta( $post_id, 'lead_gen_hero_subheadline', true );
$hero_cta_label        = get_post_meta( $post_id, 'lead_gen_hero_cta_label', true );
$hero_cta_placeholder  = get_post_meta( $post_id, 'lead_gen_hero_cta_placeholder', true );
$hero_cta_button_label = get_post_meta( $post_id, 'lead_gen_hero_cta_button_label', true );
$hero_cta_button_link  = get_post_meta( $post_id, 'lead_gen_hero_cta_button_link', true );
$hero_cta_helper       = get_post_meta( $post_id, 'lead_gen_hero_cta_helper', true );
$fluent_form_id        = absint( get_post_meta( $post_id, 'lead_gen_fluent_form_id', true ) );
$logos                 = get_post_meta( $post_id, 'lead_gen_logos', true );
$features              = get_post_meta( $post_id, 'lead_gen_features', true );
$testimonial_quote     = get_post_meta( $post_id, 'lead_gen_testimonial_quote', true );
$testimonial_name      = get_post_meta( $post_id, 'lead_gen_testimonial_name', true );
$testimonial_company   = get_post_meta( $post_id, 'lead_gen_testimonial_company', true );
$cta_headline          = get_post_meta( $post_id, 'lead_gen_cta_headline', true );
$cta_subheadline       = get_post_meta( $post_id, 'lead_gen_cta_subheadline', true );
$cta_label             = get_post_meta( $post_id, 'lead_gen_cta_label', true );
$cta_placeholder       = get_post_meta( $post_id, 'lead_gen_cta_placeholder', true );
$cta_button_label      = get_post_meta( $post_id, 'lead_gen_cta_button_label', true );
$cta_button_link       = get_post_meta( $post_id, 'lead_gen_cta_button_link', true );
$cta_helper            = get_post_meta( $post_id, 'lead_gen_cta_helper', true );
$cta_background        = get_post_meta( $post_id, 'lead_gen_cta_background', true );
$cta_background_image  = get_post_meta( $post_id, 'lead_gen_cta_background_image', true );
$faq_heading           = get_post_meta( $post_id, 'lead_gen_faq_heading', true );
$faqs                  = get_post_meta( $post_id, 'lead_gen_faqs', true );
$footer_logo           = get_post_meta( $post_id, 'lead_gen_footer_logo_text', true );
$footer_text           = get_post_meta( $post_id, 'lead_gen_footer_text', true );
$footer_links          = get_post_meta( $post_id, 'lead_gen_footer_links', true );

$logos        = is_array( $logos ) ? $logos : array();
$features     = is_array( $features ) ? $features : array();
$faqs         = is_array( $faqs ) ? $faqs : array();
$footer_links = is_array( $footer_links ) ? $footer_links : array();

$hero_headline = $hero_headline ? $hero_headline : get_the_title( $post_id );

$has_settings = $hero_subheadline || $hero_cta_label || $hero_cta_placeholder || $hero_cta_button_label || $hero_cta_button_link || $hero_cta_helper || $hero_background_image || $hero_logo || $fluent_form_id || $logos || $features || $testimonial_quote || $testimonial_name || $testimonial_company || $cta_headline || $cta_subheadline || $cta_label || $cta_placeholder || $cta_button_label || $cta_button_link || $cta_helper || $cta_background || $cta_background_image || $faq_heading || $faqs || $footer_logo || $footer_text || $footer_links;

$has_fluent_form = $fluent_form_id && function_exists( 'shortcode_exists' ) && shortcode_exists( 'fluentform' );
$show_form_notice = ! $has_fluent_form && current_user_can( 'manage_options' );

$hero_background = $hero_background_image ? $hero_background_image : $lead_gen_poster;
$cta_background  = $cta_background ? $cta_background : 'linear-gradient(180deg, #3f0bb6 0%, #2a0788 100%)';
$hero_logo_alt   = get_bloginfo( 'name' );
$hero_logo_image = '';
$hero_logo_text  = '';

if ( $hero_logo ) {
	$hero_logo_image = sprintf(
		'<img class="lead-gen-hero__logo-image" src="%1$s" alt="%2$s" loading="lazy" />',
		esc_url( $hero_logo ),
		esc_attr( $hero_logo_alt )
	);
} else {
	$custom_logo_id = get_theme_mod( 'custom_logo' );
	if ( $custom_logo_id ) {
		$hero_logo_image = wp_get_attachment_image(
			$custom_logo_id,
			'full',
			false,
			array(
				'class' => 'lead-gen-hero__logo-image',
				'alt'   => $hero_logo_alt,
			)
		);
	}
}

if ( ! $hero_logo_image && $hero_logo_alt ) {
	$hero_logo_text = $hero_logo_alt;
}

$cta_style = '';
if ( $cta_background_image ) {
	$cta_style = sprintf(
		' style="background-image: %1$s, url(%2$s);"',
		esc_attr( $cta_background ),
		esc_url( $cta_background_image )
	);
} elseif ( $cta_background ) {
	$cta_style = sprintf( ' style="background: %1$s;"', esc_attr( $cta_background ) );
}

$render_fluent_form = function () use ( $fluent_form_id, $has_fluent_form, $show_form_notice ) {
	if ( $has_fluent_form ) {
		return do_shortcode( sprintf( '[fluentform id="%d"]', $fluent_form_id ) );
	}

	if ( $show_form_notice ) {
		return '<p class="lead-gen-form__notice">' . esc_html__( 'Add a Fluent Form ID in the Lead Gen settings to show the email capture form.', 'apparel' ) . '</p>';
	}

	return '';
};
?>

<style>
	@media (max-width: 640px) {
		.lead-gen-hero {
			min-height: calc(100svh - var(--lead-gen-header-offset));
			min-height: calc(100dvh - var(--lead-gen-header-offset));
			padding: clamp(32px, 8svh, 64px) clamp(20px, 6vw, 28px) clamp(28px, 7svh, 56px);
			align-items: center;
		}

		.lead-gen-hero__stack {
			gap: clamp(16px, 3svh, 24px);
		}

		.lead-gen-hero__logo {
			margin-bottom: 0;
		}

		.lead-gen-hero__logo-image {
			width: clamp(140px, 36vw, 180px);
		}

		.lead-gen-hero__card {
			padding: clamp(22px, 4.5vw, 30px);
		}

		.lead-gen-hero__card h1 {
			font-size: clamp(26px, 7vw, 32px);
		}

		.lead-gen-hero__card p {
			font-size: clamp(14px, 4vw, 16px);
		}

		.lead-gen-form-card--dark {
			padding: clamp(16px, 4vw, 22px);
			border-radius: clamp(22px, 6vw, 28px);
		}

		.lead-gen-form-card__title {
			font-size: clamp(15px, 4vw, 17px);
		}

		.lead-gen-form-card__disclaimer {
			font-size: clamp(12px, 3.4vw, 13px);
		}

		.lead-gen-form {
			padding: 10px 10px 10px 16px;
			gap: 12px;
		}

		.lead-gen-form input,
		.lead-gen-form--dark input {
			font-size: clamp(14px, 3.8vw, 16px);
		}

		.lead-gen-form button {
			width: 42px;
			height: 42px;
		}
	}
</style>

<div class="lead-gen-page">
	<section class="lead-gen-hero">
		<div class="lead-gen-hero__media" aria-hidden="true">
			<?php if ( $hero_background ) : ?>
				<div class="lead-gen-hero__image" style="background-image: url('<?php echo esc_url( $hero_background ); ?>');"></div>
			<?php else : ?>
				<div class="lead-gen-hero__image"></div>
			<?php endif; ?>
			<video
				class="lead-gen-hero__video"
				data-lead-gen-video
				preload="none"
				autoplay
				muted
				loop
				playsinline
				<?php if ( $lead_gen_poster ) : ?>
					poster="<?php echo esc_url( $lead_gen_poster ); ?>"
				<?php endif; ?>
			>
				<source data-src="<?php echo esc_url( $video_url ); ?>" type="video/webm" />
			</video>
			<span class="lead-gen-hero__overlay"></span>
		</div>
		<div class="lead-gen-hero__content lead-gen-content">
			<?php if ( ! $has_settings ) : ?>
				<?php
				while ( have_posts() ) :
					the_post();
					the_content();
				endwhile;
				?>
			<?php else : ?>
				<div class="lead-gen-hero__stack">
					<?php if ( $hero_logo_image || $hero_logo_text ) : ?>
						<div class="lead-gen-hero__logo">
							<?php if ( $hero_logo_image ) : ?>
								<?php echo $hero_logo_image; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<?php else : ?>
								<span class="lead-gen-hero__logo-text"><?php echo esc_html( $hero_logo_text ); ?></span>
							<?php endif; ?>
						</div>
					<?php endif; ?>
					<div class="lead-gen-hero__card">
						<?php if ( $hero_headline ) : ?>
							<h1><?php echo esc_html( $hero_headline ); ?></h1>
						<?php endif; ?>
						<?php if ( $hero_subheadline ) : ?>
							<p><?php echo esc_html( $hero_subheadline ); ?></p>
						<?php endif; ?>
					</div>
					<?php if ( $hero_cta_label || $hero_cta_helper || $has_fluent_form || $show_form_notice ) : ?>
						<div class="lead-gen-hero__cta">
							<div class="lead-gen-form-card lead-gen-form-card--dark" data-lead-gen-form-wrapper>
								<?php if ( $has_fluent_form || $show_form_notice || $hero_cta_label || $hero_cta_helper ) : ?>
									<div class="lead-gen-form-card__header">
										<?php if ( $hero_cta_label ) : ?>
											<span class="lead-gen-form-card__title"><?php echo esc_html( $hero_cta_label ); ?></span>
										<?php else : ?>
											<span class="lead-gen-form-card__title"><?php echo esc_html__( 'Start for free', 'apparel' ); ?></span>
										<?php endif; ?>
										<?php if ( $hero_cta_helper ) : ?>
											<span class="lead-gen-form-card__disclaimer"><?php echo esc_html( $hero_cta_helper ); ?></span>
										<?php else : ?>
											<span class="lead-gen-form-card__disclaimer"><?php echo esc_html__( 'You agree to receive marketing emails.', 'apparel' ); ?></span>
										<?php endif; ?>
									</div>
								<?php endif; ?>
								<?php if ( $has_fluent_form ) : ?>
									<form class="lead-gen-form lead-gen-form--dark" data-lead-gen-custom-form>
										<label class="screen-reader-text" for="lead-gen-hero-email">
											<?php echo esc_html( $hero_cta_placeholder ? $hero_cta_placeholder : __( 'Email address', 'apparel' ) ); ?>
										</label>
										<input
											id="lead-gen-hero-email"
											type="email"
											name="lead_gen_email"
											placeholder="<?php echo esc_attr( $hero_cta_placeholder ? $hero_cta_placeholder : __( 'Enter your email', 'apparel' ) ); ?>"
											autocomplete="email"
											required
										/>
										<button type="submit" aria-label="<?php echo esc_attr( $hero_cta_button_label ? $hero_cta_button_label : __( 'Submit', 'apparel' ) ); ?>">
											<span aria-hidden="true"><?php echo esc_html( $hero_cta_button_label ? $hero_cta_button_label : '→' ); ?></span>
										</button>
									</form>
									<p class="lead-gen-form__error" data-lead-gen-error></p>
									<div class="lead-gen-form__fluentform" data-lead-gen-fluent-form aria-hidden="true">
										<?php echo $render_fluent_form(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
									</div>
								<?php elseif ( $show_form_notice ) : ?>
									<?php echo $render_fluent_form(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<?php endif; ?>
							</div>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	</section>

	<?php if ( $has_settings ) : ?>
		<?php if ( $logos ) : ?>
			<section class="lead-gen-logos">
				<div class="lead-gen-logos__inner">
					<?php foreach ( $logos as $logo ) : ?>
						<?php
						$logo_image = $logo['image'] ?? '';
						$logo_url   = $logo['url'] ?? '';
						$logo_label = $logo['label'] ?? '';
						$logo_html  = '';
						if ( $logo_image ) {
							$logo_html = sprintf(
								'<img src="%1$s" alt="%2$s" loading="lazy" />',
								esc_url( $logo_image ),
								esc_attr( $logo_label )
							);
						} elseif ( $logo_label ) {
							$logo_html = esc_html( $logo_label );
						}
						if ( ! $logo_html ) {
							continue;
						}
						if ( $logo_url ) {
							$logo_html = sprintf(
								'<a href="%1$s" target="_blank" rel="noopener">%2$s</a>',
								esc_url( $logo_url ),
								$logo_html
							);
						}
						echo $logo_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						?>
					<?php endforeach; ?>
				</div>
			</section>
		<?php endif; ?>

		<?php if ( $features ) : ?>
			<section class="lead-gen-features">
				<div class="lead-gen-features__grid">
					<?php foreach ( $features as $feature ) : ?>
						<?php
						$feature_tag         = $feature['tag'] ?? '';
						$feature_title       = $feature['title'] ?? '';
						$feature_description = $feature['description'] ?? '';
						$feature_image       = $feature['image'] ?? '';
						$feature_url         = $feature['url'] ?? '';
						$tag_name            = $feature_url ? 'a' : 'div';
						$tag_attrs           = $feature_url ? ' href="' . esc_url( $feature_url ) . '"' : '';
						?>
						<<?php echo esc_html( $tag_name ); ?> class="lead-gen-feature"<?php echo $tag_attrs; ?>>
							<?php if ( $feature_tag ) : ?>
								<span class="lead-gen-feature__tag"><?php echo esc_html( $feature_tag ); ?></span>
							<?php endif; ?>
							<?php if ( $feature_image ) : ?>
								<div
									class="lead-gen-feature__media"
									aria-hidden="true"
									style="background-image: url('<?php echo esc_url( $feature_image ); ?>'); background-size: cover; background-position: center;"
								></div>
							<?php else : ?>
								<div class="lead-gen-feature__media" aria-hidden="true"></div>
							<?php endif; ?>
							<?php if ( $feature_title ) : ?>
								<h3><?php echo esc_html( $feature_title ); ?></h3>
							<?php endif; ?>
							<?php if ( $feature_description ) : ?>
								<p><?php echo esc_html( $feature_description ); ?></p>
							<?php endif; ?>
						</<?php echo esc_html( $tag_name ); ?>>
					<?php endforeach; ?>
				</div>
			</section>
		<?php endif; ?>

		<?php if ( $testimonial_quote ) : ?>
			<section class="lead-gen-testimonial">
				<blockquote><?php echo esc_html( $testimonial_quote ); ?></blockquote>
				<?php if ( $testimonial_name || $testimonial_company ) : ?>
					<p class="lead-gen-testimonial__author">
						<?php
						echo esc_html(
							trim(
								implode(
									', ',
									array_filter(
										array(
											$testimonial_name,
											$testimonial_company,
										)
									)
								)
							)
						);
						?>
					</p>
				<?php endif; ?>
			</section>
		<?php endif; ?>

		<?php if ( $cta_headline ) : ?>
			<section class="lead-gen-cta">
				<div class="lead-gen-cta__panel"<?php echo $cta_style; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
					<div class="lead-gen-cta__icon" aria-hidden="true">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
							<path d="M12 5v14M5 12h14" />
						</svg>
					</div>
					<h2><?php echo esc_html( $cta_headline ); ?></h2>
					<?php if ( $cta_subheadline ) : ?>
						<p><?php echo esc_html( $cta_subheadline ); ?></p>
					<?php endif; ?>
					<?php if ( $cta_label ) : ?>
						<span class="lead-gen-form-card__title"><?php echo esc_html( $cta_label ); ?></span>
					<?php endif; ?>
					<?php if ( $has_fluent_form ) : ?>
						<div class="lead-gen-form-card lead-gen-form-card--light" data-lead-gen-form-wrapper>
							<form class="lead-gen-form lead-gen-form--light" data-lead-gen-custom-form>
								<label class="screen-reader-text" for="lead-gen-cta-email">
									<?php echo esc_html( $cta_placeholder ? $cta_placeholder : __( 'Email address', 'apparel' ) ); ?>
								</label>
								<input
									id="lead-gen-cta-email"
									type="email"
									name="lead_gen_email"
									placeholder="<?php echo esc_attr( $cta_placeholder ? $cta_placeholder : __( 'Enter your email', 'apparel' ) ); ?>"
									autocomplete="email"
									required
								/>
								<button type="submit" aria-label="<?php echo esc_attr( $cta_button_label ? $cta_button_label : __( 'Submit', 'apparel' ) ); ?>">
									<span aria-hidden="true"><?php echo esc_html( $cta_button_label ? $cta_button_label : '→' ); ?></span>
								</button>
							</form>
							<p class="lead-gen-form__error" data-lead-gen-error></p>
							<?php if ( $cta_helper ) : ?>
								<small class="lead-gen-form-card__disclaimer"><?php echo esc_html( $cta_helper ); ?></small>
							<?php endif; ?>
							<div class="lead-gen-form__fluentform" data-lead-gen-fluent-form aria-hidden="true">
								<?php echo $render_fluent_form(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</div>
						</div>
					<?php elseif ( $show_form_notice ) : ?>
						<?php echo $render_fluent_form(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php endif; ?>
				</div>
			</section>
		<?php endif; ?>

		<?php if ( $faq_heading || $faqs ) : ?>
			<section class="lead-gen-faq">
				<?php if ( $faq_heading ) : ?>
					<h2><?php echo esc_html( $faq_heading ); ?></h2>
				<?php endif; ?>
				<?php if ( $faqs ) : ?>
					<div class="lead-gen-faq__list" data-lead-gen-faq>
					<?php foreach ( $faqs as $index => $faq ) : ?>
						<?php
						$faq_question = $faq['question'] ?? '';
						$faq_answer   = $faq['answer'] ?? '';
						if ( ! $faq_question && ! $faq_answer ) {
							continue;
						}
						$faq_id = 'lead-gen-faq-' . absint( $index );
						?>
						<div class="lead-gen-faq__item">
							<button class="lead-gen-faq__trigger" type="button" aria-expanded="false" aria-controls="<?php echo esc_attr( $faq_id ); ?>">
								<span><?php echo esc_html( $faq_question ); ?></span>
								<span class="lead-gen-faq__icon" aria-hidden="true"></span>
							</button>
							<div class="lead-gen-faq__content" id="<?php echo esc_attr( $faq_id ); ?>">
								<?php echo wpautop( esc_html( $faq_answer ) ); ?>
							</div>
						</div>
					<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</section>
		<?php endif; ?>

		<?php if ( $footer_logo || $footer_text || $footer_links ) : ?>
			<section class="lead-gen-footer">
				<?php if ( $footer_logo ) : ?>
					<div class="lead-gen-footer__logo"><?php echo esc_html( $footer_logo ); ?></div>
				<?php endif; ?>
				<?php if ( $footer_text ) : ?>
					<span><?php echo esc_html( $footer_text ); ?></span>
				<?php endif; ?>
				<?php if ( $footer_links ) : ?>
					<div class="lead-gen-footer__links">
						<?php foreach ( $footer_links as $link ) : ?>
							<?php
							$link_label = $link['label'] ?? '';
							$link_url   = $link['url'] ?? '';
							if ( ! $link_label || ! $link_url ) {
								continue;
							}
							?>
							<a href="<?php echo esc_url( $link_url ); ?>"><?php echo esc_html( $link_label ); ?></a>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</section>
		<?php endif; ?>
	<?php endif; ?>
</div>

<?php
get_footer();
?>
