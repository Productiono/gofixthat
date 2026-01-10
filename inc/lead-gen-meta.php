<?php
/**
 * Lead Gen page template meta boxes.
 *
 * @package Apparel
 */

/**
 * Register the Lead Gen settings meta box.
 *
 * @param string   $post_type Post type.
 * @param WP_Post  $post      Current post object.
 */
function apparel_register_lead_gen_meta_box( $post_type, $post ) {
	if ( 'page' !== $post_type ) {
		return;
	}

	if ( 'lead-gen.php' !== get_page_template_slug( $post ) ) {
		return;
	}

	add_meta_box(
		'apparel-lead-gen-settings',
		esc_html__( 'Lead Gen Page Settings', 'apparel' ),
		'apparel_render_lead_gen_meta_box',
		'page',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'apparel_register_lead_gen_meta_box', 10, 2 );

/**
 * Render the Lead Gen settings meta box.
 *
 * @param WP_Post $post Current post object.
 */
function apparel_render_lead_gen_meta_box( $post ) {
	wp_nonce_field( 'apparel_lead_gen_meta', 'apparel_lead_gen_meta_nonce' );

	$video_url        = get_post_meta( $post->ID, 'lead_gen_video_url', true );
	$poster_url       = get_post_meta( $post->ID, 'lead_gen_poster_url', true );
	$default_video    = get_template_directory_uri() . '/assets/static/background-video.webm';
	$hero_headline    = get_post_meta( $post->ID, 'lead_gen_hero_headline', true );
	$hero_subheadline = get_post_meta( $post->ID, 'lead_gen_hero_subheadline', true );
	$hero_cta_label   = get_post_meta( $post->ID, 'lead_gen_hero_cta_label', true );
	$hero_placeholder = get_post_meta( $post->ID, 'lead_gen_hero_cta_placeholder', true );
	$hero_button      = get_post_meta( $post->ID, 'lead_gen_hero_cta_button_label', true );
	$hero_link        = get_post_meta( $post->ID, 'lead_gen_hero_cta_button_link', true );
	$hero_helper      = get_post_meta( $post->ID, 'lead_gen_hero_cta_helper', true );
	$hero_background  = get_post_meta( $post->ID, 'lead_gen_hero_background_image', true );
	$hero_main_logo   = get_post_meta( $post->ID, 'lead_gen_main_landing_logo', true );
	$hero_logo        = get_post_meta( $post->ID, 'lead_gen_hero_logo', true );
	$fluent_form_id   = get_post_meta( $post->ID, 'lead_gen_fluent_form_id', true );
	$stripe_payment_link = get_post_meta( $post->ID, 'lead_gen_stripe_payment_link', true );
	$logos            = get_post_meta( $post->ID, 'lead_gen_logos', true );
	$features         = get_post_meta( $post->ID, 'lead_gen_features', true );
	$testimonial      = get_post_meta( $post->ID, 'lead_gen_testimonial_quote', true );
	$testimonial_name = get_post_meta( $post->ID, 'lead_gen_testimonial_name', true );
	$testimonial_co   = get_post_meta( $post->ID, 'lead_gen_testimonial_company', true );
	$cta_headline     = get_post_meta( $post->ID, 'lead_gen_cta_headline', true );
	$cta_subheadline  = get_post_meta( $post->ID, 'lead_gen_cta_subheadline', true );
	$cta_label        = get_post_meta( $post->ID, 'lead_gen_cta_label', true );
	$cta_placeholder  = get_post_meta( $post->ID, 'lead_gen_cta_placeholder', true );
	$cta_button       = get_post_meta( $post->ID, 'lead_gen_cta_button_label', true );
	$cta_link         = get_post_meta( $post->ID, 'lead_gen_cta_button_link', true );
	$cta_helper       = get_post_meta( $post->ID, 'lead_gen_cta_helper', true );
	$cta_background   = get_post_meta( $post->ID, 'lead_gen_cta_background', true );
	$cta_bg_image     = get_post_meta( $post->ID, 'lead_gen_cta_background_image', true );
	$faq_heading      = get_post_meta( $post->ID, 'lead_gen_faq_heading', true );
	$faqs             = get_post_meta( $post->ID, 'lead_gen_faqs', true );
	$footer_logo      = get_post_meta( $post->ID, 'lead_gen_footer_logo_text', true );
	$footer_text      = get_post_meta( $post->ID, 'lead_gen_footer_text', true );
	$footer_links     = get_post_meta( $post->ID, 'lead_gen_footer_links', true );

	$logos        = is_array( $logos ) ? $logos : array();
	$features     = is_array( $features ) ? $features : array();
	$faqs         = is_array( $faqs ) ? $faqs : array();
	$footer_links = is_array( $footer_links ) ? $footer_links : array();
	?>
	<div class="lead-gen-meta">
		<h3><?php esc_html_e( 'Hero', 'apparel' ); ?></h3>
		<p>
			<label for="lead-gen-hero-headline"><strong><?php esc_html_e( 'Headline', 'apparel' ); ?></strong></label>
			<input
				type="text"
				id="lead-gen-hero-headline"
				name="lead_gen_hero_headline"
				class="widefat"
				maxlength="70"
				value="<?php echo esc_attr( $hero_headline ); ?>"
			/>
			<span class="description"><?php esc_html_e( 'Recommended max 70 characters.', 'apparel' ); ?></span>
		</p>
		<p>
			<label for="lead-gen-hero-subheadline"><strong><?php esc_html_e( 'Subheadline', 'apparel' ); ?></strong></label>
			<textarea
				id="lead-gen-hero-subheadline"
				name="lead_gen_hero_subheadline"
				class="widefat"
				rows="3"
				maxlength="160"
			><?php echo esc_textarea( $hero_subheadline ); ?></textarea>
			<span class="description"><?php esc_html_e( 'Recommended max 160 characters.', 'apparel' ); ?></span>
		</p>
		<p>
			<label for="lead-gen-hero-cta-label"><strong><?php esc_html_e( 'CTA label', 'apparel' ); ?></strong></label>
			<input
				type="text"
				id="lead-gen-hero-cta-label"
				name="lead_gen_hero_cta_label"
				class="widefat"
				maxlength="40"
				value="<?php echo esc_attr( $hero_cta_label ); ?>"
			/>
			<span class="description"><?php esc_html_e( 'Short label above the email field.', 'apparel' ); ?></span>
		</p>
		<p>
			<label for="lead-gen-hero-cta-placeholder"><strong><?php esc_html_e( 'Email placeholder', 'apparel' ); ?></strong></label>
			<input
				type="text"
				id="lead-gen-hero-cta-placeholder"
				name="lead_gen_hero_cta_placeholder"
				class="widefat"
				maxlength="60"
				value="<?php echo esc_attr( $hero_placeholder ); ?>"
			/>
			<span class="description"><?php esc_html_e( 'Example: Work email address', 'apparel' ); ?></span>
		</p>
		<p>
			<label for="lead-gen-hero-cta-button"><strong><?php esc_html_e( 'Button label', 'apparel' ); ?></strong></label>
			<input
				type="text"
				id="lead-gen-hero-cta-button"
				name="lead_gen_hero_cta_button_label"
				class="widefat"
				maxlength="10"
				value="<?php echo esc_attr( $hero_button ); ?>"
			/>
			<span class="description"><?php esc_html_e( 'Short text or symbol (example: →).', 'apparel' ); ?></span>
		</p>
		<p>
			<label for="lead-gen-hero-cta-link"><strong><?php esc_html_e( 'Button link', 'apparel' ); ?></strong></label>
			<input
				type="url"
				id="lead-gen-hero-cta-link"
				name="lead_gen_hero_cta_button_link"
				class="widefat"
				value="<?php echo esc_attr( $hero_link ); ?>"
			/>
			<span class="description"><?php esc_html_e( 'Optional form action URL.', 'apparel' ); ?></span>
		</p>
		<p>
			<label for="lead-gen-hero-cta-helper"><strong><?php esc_html_e( 'Helper text', 'apparel' ); ?></strong></label>
			<input
				type="text"
				id="lead-gen-hero-cta-helper"
				name="lead_gen_hero_cta_helper"
				class="widefat"
				maxlength="80"
				value="<?php echo esc_attr( $hero_helper ); ?>"
			/>
			<span class="description"><?php esc_html_e( 'Small note shown below the CTA.', 'apparel' ); ?></span>
		</p>
		<p>
			<label for="lead-gen-fluent-form-id"><strong><?php esc_html_e( 'Fluent Form ID', 'apparel' ); ?></strong></label>
			<input
				type="number"
				id="lead-gen-fluent-form-id"
				name="lead_gen_fluent_form_id"
				class="widefat"
				min="1"
				step="1"
				value="<?php echo esc_attr( $fluent_form_id ); ?>"
			/>
			<span class="description"><?php esc_html_e( 'Single form ID used in both email capture areas.', 'apparel' ); ?></span>
		</p>
		<p>
			<label for="lead-gen-stripe-payment-link"><strong><?php esc_html_e( 'Stripe Payment Link URL', 'apparel' ); ?></strong></label>
			<input
				type="url"
				id="lead-gen-stripe-payment-link"
				name="lead_gen_stripe_payment_link"
				class="widefat"
				value="<?php echo esc_attr( $stripe_payment_link ); ?>"
			/>
			<span class="description"><?php esc_html_e( 'Redirects to this payment link after a successful form submit.', 'apparel' ); ?></span>
		</p>
		<p>
			<label for="lead-gen-video-url"><strong><?php esc_html_e( 'Background video URL', 'apparel' ); ?></strong></label>
			<input
				type="url"
				id="lead-gen-video-url"
				name="lead_gen_video_url"
				class="widefat"
				value="<?php echo esc_attr( $video_url ); ?>"
				placeholder="<?php echo esc_attr( $default_video ); ?>"
			/>
			<span class="description"><?php esc_html_e( 'MP4/WebM URL. Leave blank to use the default theme video.', 'apparel' ); ?></span>
		</p>
		<p>
			<label for="lead-gen-poster-url"><strong><?php esc_html_e( 'Poster/fallback image URL', 'apparel' ); ?></strong></label>
			<input
				type="url"
				id="lead-gen-poster-url"
				name="lead_gen_poster_url"
				class="widefat"
				value="<?php echo esc_attr( $poster_url ); ?>"
			/>
			<span class="description"><?php esc_html_e( 'Recommended 1600×900.', 'apparel' ); ?></span>
		</p>
		<p>
			<label for="lead-gen-hero-background"><strong><?php esc_html_e( 'Background image URL', 'apparel' ); ?></strong></label>
			<input
				type="url"
				id="lead-gen-hero-background"
				name="lead_gen_hero_background_image"
				class="widefat"
				value="<?php echo esc_attr( $hero_background ); ?>"
			/>
			<span class="description"><?php esc_html_e( 'Collage/background image used behind the hero content.', 'apparel' ); ?></span>
		</p>
		<p>
			<label for="lead-gen-main-landing-logo"><strong><?php esc_html_e( 'Main Landingpage Logo', 'apparel' ); ?></strong></label>
			<input
				type="text"
				id="lead-gen-main-landing-logo"
				name="lead_gen_main_landing_logo"
				class="widefat"
				value="<?php echo esc_attr( $hero_main_logo ); ?>"
			/>
			<span class="description"><?php esc_html_e( 'Logo URL or attachment ID shown above the hero content.', 'apparel' ); ?></span>
		</p>
		<p>
			<label for="lead-gen-hero-logo"><strong><?php esc_html_e( 'Hero logo URL', 'apparel' ); ?></strong></label>
			<input
				type="url"
				id="lead-gen-hero-logo"
				name="lead_gen_hero_logo"
				class="widefat"
				value="<?php echo esc_attr( $hero_logo ); ?>"
			/>
			<span class="description"><?php esc_html_e( 'Optional logo image shown above the hero content.', 'apparel' ); ?></span>
		</p>

		<hr />
		<h3><?php esc_html_e( 'Logos', 'apparel' ); ?></h3>
		<p class="description"><?php esc_html_e( 'Add partner logos. Recommended logo height: 32–40px.', 'apparel' ); ?></p>
		<div class="lead-gen-repeatable" data-lead-gen-repeatable data-name="lead_gen_logos">
			<div class="lead-gen-repeatable__items">
				<?php foreach ( $logos as $index => $logo ) : ?>
					<div class="lead-gen-repeatable__item">
						<p>
							<label><strong><?php esc_html_e( 'Logo image URL', 'apparel' ); ?></strong></label>
							<input type="url" class="widefat" data-field="image" value="<?php echo esc_attr( $logo['image'] ?? '' ); ?>" />
						</p>
						<p>
							<label><strong><?php esc_html_e( 'Logo link URL', 'apparel' ); ?></strong></label>
							<input type="url" class="widefat" data-field="url" value="<?php echo esc_attr( $logo['url'] ?? '' ); ?>" />
						</p>
						<p>
							<label><strong><?php esc_html_e( 'Logo label/alt text', 'apparel' ); ?></strong></label>
							<input type="text" class="widefat" data-field="label" value="<?php echo esc_attr( $logo['label'] ?? '' ); ?>" />
						</p>
						<p class="lead-gen-repeatable__actions">
							<button type="button" class="button lead-gen-repeatable__move-up"><?php esc_html_e( 'Move up', 'apparel' ); ?></button>
							<button type="button" class="button lead-gen-repeatable__move-down"><?php esc_html_e( 'Move down', 'apparel' ); ?></button>
							<button type="button" class="button link-delete lead-gen-repeatable__remove"><?php esc_html_e( 'Remove', 'apparel' ); ?></button>
						</p>
					</div>
				<?php endforeach; ?>
			</div>
			<p><button type="button" class="button button-secondary lead-gen-repeatable__add"><?php esc_html_e( 'Add logo', 'apparel' ); ?></button></p>
			<template class="lead-gen-repeatable__template">
				<div class="lead-gen-repeatable__item">
					<p>
						<label><strong><?php esc_html_e( 'Logo image URL', 'apparel' ); ?></strong></label>
						<input type="url" class="widefat" data-field="image" value="" />
					</p>
					<p>
						<label><strong><?php esc_html_e( 'Logo link URL', 'apparel' ); ?></strong></label>
						<input type="url" class="widefat" data-field="url" value="" />
					</p>
					<p>
						<label><strong><?php esc_html_e( 'Logo label/alt text', 'apparel' ); ?></strong></label>
						<input type="text" class="widefat" data-field="label" value="" />
					</p>
					<p class="lead-gen-repeatable__actions">
						<button type="button" class="button lead-gen-repeatable__move-up"><?php esc_html_e( 'Move up', 'apparel' ); ?></button>
						<button type="button" class="button lead-gen-repeatable__move-down"><?php esc_html_e( 'Move down', 'apparel' ); ?></button>
						<button type="button" class="button link-delete lead-gen-repeatable__remove"><?php esc_html_e( 'Remove', 'apparel' ); ?></button>
					</p>
				</div>
			</template>
		</div>

		<hr />
		<h3><?php esc_html_e( 'Features', 'apparel' ); ?></h3>
		<p class="description"><?php esc_html_e( 'Add up to 4 feature cards. Recommended image size: 600×400.', 'apparel' ); ?></p>
		<div class="lead-gen-repeatable" data-lead-gen-repeatable data-name="lead_gen_features">
			<div class="lead-gen-repeatable__items">
				<?php foreach ( $features as $index => $feature ) : ?>
					<div class="lead-gen-repeatable__item">
						<p>
							<label><strong><?php esc_html_e( 'Tag/label', 'apparel' ); ?></strong></label>
							<input type="text" class="widefat" data-field="tag" value="<?php echo esc_attr( $feature['tag'] ?? '' ); ?>" />
						</p>
						<p>
							<label><strong><?php esc_html_e( 'Title', 'apparel' ); ?></strong></label>
							<input type="text" class="widefat" data-field="title" value="<?php echo esc_attr( $feature['title'] ?? '' ); ?>" />
						</p>
						<p>
							<label><strong><?php esc_html_e( 'Description', 'apparel' ); ?></strong></label>
							<textarea class="widefat" data-field="description" rows="3"><?php echo esc_textarea( $feature['description'] ?? '' ); ?></textarea>
						</p>
						<p>
							<label><strong><?php esc_html_e( 'Image URL', 'apparel' ); ?></strong></label>
							<input type="url" class="widefat" data-field="image" value="<?php echo esc_attr( $feature['image'] ?? '' ); ?>" />
						</p>
						<p>
							<label><strong><?php esc_html_e( 'Link URL', 'apparel' ); ?></strong></label>
							<input type="url" class="widefat" data-field="url" value="<?php echo esc_attr( $feature['url'] ?? '' ); ?>" />
						</p>
						<p class="lead-gen-repeatable__actions">
							<button type="button" class="button lead-gen-repeatable__move-up"><?php esc_html_e( 'Move up', 'apparel' ); ?></button>
							<button type="button" class="button lead-gen-repeatable__move-down"><?php esc_html_e( 'Move down', 'apparel' ); ?></button>
							<button type="button" class="button link-delete lead-gen-repeatable__remove"><?php esc_html_e( 'Remove', 'apparel' ); ?></button>
						</p>
					</div>
				<?php endforeach; ?>
			</div>
			<p><button type="button" class="button button-secondary lead-gen-repeatable__add"><?php esc_html_e( 'Add feature', 'apparel' ); ?></button></p>
			<template class="lead-gen-repeatable__template">
				<div class="lead-gen-repeatable__item">
					<p>
						<label><strong><?php esc_html_e( 'Tag/label', 'apparel' ); ?></strong></label>
						<input type="text" class="widefat" data-field="tag" value="" />
					</p>
					<p>
						<label><strong><?php esc_html_e( 'Title', 'apparel' ); ?></strong></label>
						<input type="text" class="widefat" data-field="title" value="" />
					</p>
					<p>
						<label><strong><?php esc_html_e( 'Description', 'apparel' ); ?></strong></label>
						<textarea class="widefat" data-field="description" rows="3"></textarea>
					</p>
					<p>
						<label><strong><?php esc_html_e( 'Image URL', 'apparel' ); ?></strong></label>
						<input type="url" class="widefat" data-field="image" value="" />
					</p>
					<p>
						<label><strong><?php esc_html_e( 'Link URL', 'apparel' ); ?></strong></label>
						<input type="url" class="widefat" data-field="url" value="" />
					</p>
					<p class="lead-gen-repeatable__actions">
						<button type="button" class="button lead-gen-repeatable__move-up"><?php esc_html_e( 'Move up', 'apparel' ); ?></button>
						<button type="button" class="button lead-gen-repeatable__move-down"><?php esc_html_e( 'Move down', 'apparel' ); ?></button>
						<button type="button" class="button link-delete lead-gen-repeatable__remove"><?php esc_html_e( 'Remove', 'apparel' ); ?></button>
					</p>
				</div>
			</template>
		</div>

		<hr />
		<h3><?php esc_html_e( 'Testimonial', 'apparel' ); ?></h3>
		<p>
			<label for="lead-gen-testimonial-quote"><strong><?php esc_html_e( 'Quote', 'apparel' ); ?></strong></label>
			<textarea id="lead-gen-testimonial-quote" name="lead_gen_testimonial_quote" class="widefat" rows="3"><?php echo esc_textarea( $testimonial ); ?></textarea>
		</p>
		<p>
			<label for="lead-gen-testimonial-name"><strong><?php esc_html_e( 'Name', 'apparel' ); ?></strong></label>
			<input type="text" id="lead-gen-testimonial-name" name="lead_gen_testimonial_name" class="widefat" value="<?php echo esc_attr( $testimonial_name ); ?>" />
		</p>
		<p>
			<label for="lead-gen-testimonial-company"><strong><?php esc_html_e( 'Company', 'apparel' ); ?></strong></label>
			<input type="text" id="lead-gen-testimonial-company" name="lead_gen_testimonial_company" class="widefat" value="<?php echo esc_attr( $testimonial_co ); ?>" />
		</p>

		<hr />
		<h3><?php esc_html_e( 'CTA', 'apparel' ); ?></h3>
		<p>
			<label for="lead-gen-cta-headline"><strong><?php esc_html_e( 'Headline', 'apparel' ); ?></strong></label>
			<input type="text" id="lead-gen-cta-headline" name="lead_gen_cta_headline" class="widefat" value="<?php echo esc_attr( $cta_headline ); ?>" />
		</p>
		<p>
			<label for="lead-gen-cta-subheadline"><strong><?php esc_html_e( 'Subheadline', 'apparel' ); ?></strong></label>
			<textarea id="lead-gen-cta-subheadline" name="lead_gen_cta_subheadline" class="widefat" rows="3"><?php echo esc_textarea( $cta_subheadline ); ?></textarea>
		</p>
		<p>
			<label for="lead-gen-cta-label"><strong><?php esc_html_e( 'CTA label', 'apparel' ); ?></strong></label>
			<input type="text" id="lead-gen-cta-label" name="lead_gen_cta_label" class="widefat" value="<?php echo esc_attr( $cta_label ); ?>" />
			<span class="description"><?php esc_html_e( 'Short label above the CTA email field (optional).', 'apparel' ); ?></span>
		</p>
		<p>
			<label for="lead-gen-cta-placeholder"><strong><?php esc_html_e( 'Email placeholder', 'apparel' ); ?></strong></label>
			<input type="text" id="lead-gen-cta-placeholder" name="lead_gen_cta_placeholder" class="widefat" value="<?php echo esc_attr( $cta_placeholder ); ?>" />
		</p>
		<p>
			<label for="lead-gen-cta-button"><strong><?php esc_html_e( 'Button label', 'apparel' ); ?></strong></label>
			<input type="text" id="lead-gen-cta-button" name="lead_gen_cta_button_label" class="widefat" value="<?php echo esc_attr( $cta_button ); ?>" />
		</p>
		<p>
			<label for="lead-gen-cta-link"><strong><?php esc_html_e( 'Button link', 'apparel' ); ?></strong></label>
			<input type="url" id="lead-gen-cta-link" name="lead_gen_cta_button_link" class="widefat" value="<?php echo esc_attr( $cta_link ); ?>" />
		</p>
		<p>
			<label for="lead-gen-cta-helper"><strong><?php esc_html_e( 'Helper text', 'apparel' ); ?></strong></label>
			<input type="text" id="lead-gen-cta-helper" name="lead_gen_cta_helper" class="widefat" value="<?php echo esc_attr( $cta_helper ); ?>" />
		</p>
		<p>
			<label for="lead-gen-cta-background"><strong><?php esc_html_e( 'CTA background (color/gradient)', 'apparel' ); ?></strong></label>
			<input type="text" id="lead-gen-cta-background" name="lead_gen_cta_background" class="widefat" value="<?php echo esc_attr( $cta_background ); ?>" />
			<span class="description"><?php esc_html_e( 'Example: linear-gradient(180deg, #3f0bb6 0%, #2a0788 100%).', 'apparel' ); ?></span>
		</p>
		<p>
			<label for="lead-gen-cta-bg-image"><strong><?php esc_html_e( 'CTA background image URL', 'apparel' ); ?></strong></label>
			<input type="url" id="lead-gen-cta-bg-image" name="lead_gen_cta_background_image" class="widefat" value="<?php echo esc_attr( $cta_bg_image ); ?>" />
			<span class="description"><?php esc_html_e( 'Optional image layered behind the CTA gradient.', 'apparel' ); ?></span>
		</p>

		<hr />
		<h3><?php esc_html_e( 'FAQ', 'apparel' ); ?></h3>
		<p>
			<label for="lead-gen-faq-heading"><strong><?php esc_html_e( 'FAQ heading', 'apparel' ); ?></strong></label>
			<input type="text" id="lead-gen-faq-heading" name="lead_gen_faq_heading" class="widefat" value="<?php echo esc_attr( $faq_heading ); ?>" />
		</p>
		<div class="lead-gen-repeatable" data-lead-gen-repeatable data-name="lead_gen_faqs">
			<div class="lead-gen-repeatable__items">
				<?php foreach ( $faqs as $index => $faq ) : ?>
					<div class="lead-gen-repeatable__item">
						<p>
							<label><strong><?php esc_html_e( 'Question', 'apparel' ); ?></strong></label>
							<input type="text" class="widefat" data-field="question" value="<?php echo esc_attr( $faq['question'] ?? '' ); ?>" />
						</p>
						<p>
							<label><strong><?php esc_html_e( 'Answer', 'apparel' ); ?></strong></label>
							<textarea class="widefat" data-field="answer" rows="3"><?php echo esc_textarea( $faq['answer'] ?? '' ); ?></textarea>
						</p>
						<p class="lead-gen-repeatable__actions">
							<button type="button" class="button lead-gen-repeatable__move-up"><?php esc_html_e( 'Move up', 'apparel' ); ?></button>
							<button type="button" class="button lead-gen-repeatable__move-down"><?php esc_html_e( 'Move down', 'apparel' ); ?></button>
							<button type="button" class="button link-delete lead-gen-repeatable__remove"><?php esc_html_e( 'Remove', 'apparel' ); ?></button>
						</p>
					</div>
				<?php endforeach; ?>
			</div>
			<p><button type="button" class="button button-secondary lead-gen-repeatable__add"><?php esc_html_e( 'Add FAQ', 'apparel' ); ?></button></p>
			<template class="lead-gen-repeatable__template">
				<div class="lead-gen-repeatable__item">
					<p>
						<label><strong><?php esc_html_e( 'Question', 'apparel' ); ?></strong></label>
						<input type="text" class="widefat" data-field="question" value="" />
					</p>
					<p>
						<label><strong><?php esc_html_e( 'Answer', 'apparel' ); ?></strong></label>
						<textarea class="widefat" data-field="answer" rows="3"></textarea>
					</p>
					<p class="lead-gen-repeatable__actions">
						<button type="button" class="button lead-gen-repeatable__move-up"><?php esc_html_e( 'Move up', 'apparel' ); ?></button>
						<button type="button" class="button lead-gen-repeatable__move-down"><?php esc_html_e( 'Move down', 'apparel' ); ?></button>
						<button type="button" class="button link-delete lead-gen-repeatable__remove"><?php esc_html_e( 'Remove', 'apparel' ); ?></button>
					</p>
				</div>
			</template>
		</div>

		<hr />
		<h3><?php esc_html_e( 'Footer', 'apparel' ); ?></h3>
		<p>
			<label for="lead-gen-footer-logo"><strong><?php esc_html_e( 'Footer logo text', 'apparel' ); ?></strong></label>
			<input type="text" id="lead-gen-footer-logo" name="lead_gen_footer_logo_text" class="widefat" value="<?php echo esc_attr( $footer_logo ); ?>" />
			<span class="description"><?php esc_html_e( 'Short initials or wordmark (example: A).', 'apparel' ); ?></span>
		</p>
		<p>
			<label for="lead-gen-footer-text"><strong><?php esc_html_e( 'Footer text', 'apparel' ); ?></strong></label>
			<input type="text" id="lead-gen-footer-text" name="lead_gen_footer_text" class="widefat" value="<?php echo esc_attr( $footer_text ); ?>" />
		</p>
		<div class="lead-gen-repeatable" data-lead-gen-repeatable data-name="lead_gen_footer_links">
			<div class="lead-gen-repeatable__items">
				<?php foreach ( $footer_links as $index => $link ) : ?>
					<div class="lead-gen-repeatable__item">
						<p>
							<label><strong><?php esc_html_e( 'Link label', 'apparel' ); ?></strong></label>
							<input type="text" class="widefat" data-field="label" value="<?php echo esc_attr( $link['label'] ?? '' ); ?>" />
						</p>
						<p>
							<label><strong><?php esc_html_e( 'Link URL', 'apparel' ); ?></strong></label>
							<input type="url" class="widefat" data-field="url" value="<?php echo esc_attr( $link['url'] ?? '' ); ?>" />
						</p>
						<p class="lead-gen-repeatable__actions">
							<button type="button" class="button lead-gen-repeatable__move-up"><?php esc_html_e( 'Move up', 'apparel' ); ?></button>
							<button type="button" class="button lead-gen-repeatable__move-down"><?php esc_html_e( 'Move down', 'apparel' ); ?></button>
							<button type="button" class="button link-delete lead-gen-repeatable__remove"><?php esc_html_e( 'Remove', 'apparel' ); ?></button>
						</p>
					</div>
				<?php endforeach; ?>
			</div>
			<p><button type="button" class="button button-secondary lead-gen-repeatable__add"><?php esc_html_e( 'Add link', 'apparel' ); ?></button></p>
			<template class="lead-gen-repeatable__template">
				<div class="lead-gen-repeatable__item">
					<p>
						<label><strong><?php esc_html_e( 'Link label', 'apparel' ); ?></strong></label>
						<input type="text" class="widefat" data-field="label" value="" />
					</p>
					<p>
						<label><strong><?php esc_html_e( 'Link URL', 'apparel' ); ?></strong></label>
						<input type="url" class="widefat" data-field="url" value="" />
					</p>
					<p class="lead-gen-repeatable__actions">
						<button type="button" class="button lead-gen-repeatable__move-up"><?php esc_html_e( 'Move up', 'apparel' ); ?></button>
						<button type="button" class="button lead-gen-repeatable__move-down"><?php esc_html_e( 'Move down', 'apparel' ); ?></button>
						<button type="button" class="button link-delete lead-gen-repeatable__remove"><?php esc_html_e( 'Remove', 'apparel' ); ?></button>
					</p>
				</div>
			</template>
		</div>
	</div>
	<script>
		document.addEventListener('DOMContentLoaded', function () {
			document.querySelectorAll('[data-lead-gen-repeatable]').forEach(function (wrapper) {
				const itemsContainer = wrapper.querySelector('.lead-gen-repeatable__items');
				const addButton = wrapper.querySelector('.lead-gen-repeatable__add');
				const template = wrapper.querySelector('.lead-gen-repeatable__template');
				const name = wrapper.dataset.name;

				const renumber = function () {
					const items = Array.from(itemsContainer.querySelectorAll('.lead-gen-repeatable__item'));
					items.forEach(function (item, index) {
						item.querySelectorAll('[data-field]').forEach(function (field) {
							field.name = name + '[' + index + '][' + field.dataset.field + ']';
						});
					});
				};

				const addItem = function () {
					if (!template) {
						return;
					}
					const clone = template.content.cloneNode(true);
					itemsContainer.appendChild(clone);
					renumber();
				};

				if (addButton) {
					addButton.addEventListener('click', function () {
						addItem();
					});
				}

				itemsContainer.addEventListener('click', function (event) {
					const button = event.target.closest('button');
					if (!button) {
						return;
					}
					const item = button.closest('.lead-gen-repeatable__item');
					if (!item) {
						return;
					}
					if (button.classList.contains('lead-gen-repeatable__remove')) {
						item.remove();
						renumber();
					}
					if (button.classList.contains('lead-gen-repeatable__move-up')) {
						const previous = item.previousElementSibling;
						if (previous) {
							itemsContainer.insertBefore(item, previous);
							renumber();
						}
					}
					if (button.classList.contains('lead-gen-repeatable__move-down')) {
						const next = item.nextElementSibling;
						if (next) {
							itemsContainer.insertBefore(next, item);
							renumber();
						}
					}
				});

				renumber();
			});
		});
	</script>
	<?php
}

/**
 * Save Lead Gen hero media meta box values.
 *
 * @param int $post_id Post ID.
 */
function apparel_save_lead_gen_meta_box( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! isset( $_POST['apparel_lead_gen_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['apparel_lead_gen_meta_nonce'] ) ), 'apparel_lead_gen_meta' ) ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	if ( 'lead-gen.php' !== get_page_template_slug( $post_id ) ) {
		return;
	}

	$video_url        = isset( $_POST['lead_gen_video_url'] ) ? esc_url_raw( wp_unslash( $_POST['lead_gen_video_url'] ) ) : '';
	$poster_url       = isset( $_POST['lead_gen_poster_url'] ) ? esc_url_raw( wp_unslash( $_POST['lead_gen_poster_url'] ) ) : '';
	$hero_headline    = isset( $_POST['lead_gen_hero_headline'] ) ? sanitize_text_field( wp_unslash( $_POST['lead_gen_hero_headline'] ) ) : '';
	$hero_subheadline = isset( $_POST['lead_gen_hero_subheadline'] ) ? sanitize_textarea_field( wp_unslash( $_POST['lead_gen_hero_subheadline'] ) ) : '';
	$hero_cta_label   = isset( $_POST['lead_gen_hero_cta_label'] ) ? sanitize_text_field( wp_unslash( $_POST['lead_gen_hero_cta_label'] ) ) : '';
	$hero_placeholder = isset( $_POST['lead_gen_hero_cta_placeholder'] ) ? sanitize_text_field( wp_unslash( $_POST['lead_gen_hero_cta_placeholder'] ) ) : '';
	$hero_button      = isset( $_POST['lead_gen_hero_cta_button_label'] ) ? sanitize_text_field( wp_unslash( $_POST['lead_gen_hero_cta_button_label'] ) ) : '';
	$hero_link        = isset( $_POST['lead_gen_hero_cta_button_link'] ) ? esc_url_raw( wp_unslash( $_POST['lead_gen_hero_cta_button_link'] ) ) : '';
	$hero_helper      = isset( $_POST['lead_gen_hero_cta_helper'] ) ? sanitize_text_field( wp_unslash( $_POST['lead_gen_hero_cta_helper'] ) ) : '';
	$hero_background  = isset( $_POST['lead_gen_hero_background_image'] ) ? esc_url_raw( wp_unslash( $_POST['lead_gen_hero_background_image'] ) ) : '';
	$hero_main_logo   = isset( $_POST['lead_gen_main_landing_logo'] ) ? sanitize_text_field( wp_unslash( $_POST['lead_gen_main_landing_logo'] ) ) : '';
	$hero_logo        = isset( $_POST['lead_gen_hero_logo'] ) ? esc_url_raw( wp_unslash( $_POST['lead_gen_hero_logo'] ) ) : '';
	$fluent_form_id   = isset( $_POST['lead_gen_fluent_form_id'] ) ? absint( wp_unslash( $_POST['lead_gen_fluent_form_id'] ) ) : 0;
	$stripe_payment_link = isset( $_POST['lead_gen_stripe_payment_link'] ) ? untrailingslashit( esc_url_raw( wp_unslash( $_POST['lead_gen_stripe_payment_link'] ) ) ) : '';
	$testimonial      = isset( $_POST['lead_gen_testimonial_quote'] ) ? sanitize_textarea_field( wp_unslash( $_POST['lead_gen_testimonial_quote'] ) ) : '';
	$testimonial_name = isset( $_POST['lead_gen_testimonial_name'] ) ? sanitize_text_field( wp_unslash( $_POST['lead_gen_testimonial_name'] ) ) : '';
	$testimonial_co   = isset( $_POST['lead_gen_testimonial_company'] ) ? sanitize_text_field( wp_unslash( $_POST['lead_gen_testimonial_company'] ) ) : '';
	$cta_headline     = isset( $_POST['lead_gen_cta_headline'] ) ? sanitize_text_field( wp_unslash( $_POST['lead_gen_cta_headline'] ) ) : '';
	$cta_subheadline  = isset( $_POST['lead_gen_cta_subheadline'] ) ? sanitize_textarea_field( wp_unslash( $_POST['lead_gen_cta_subheadline'] ) ) : '';
	$cta_label        = isset( $_POST['lead_gen_cta_label'] ) ? sanitize_text_field( wp_unslash( $_POST['lead_gen_cta_label'] ) ) : '';
	$cta_placeholder  = isset( $_POST['lead_gen_cta_placeholder'] ) ? sanitize_text_field( wp_unslash( $_POST['lead_gen_cta_placeholder'] ) ) : '';
	$cta_button       = isset( $_POST['lead_gen_cta_button_label'] ) ? sanitize_text_field( wp_unslash( $_POST['lead_gen_cta_button_label'] ) ) : '';
	$cta_link         = isset( $_POST['lead_gen_cta_button_link'] ) ? esc_url_raw( wp_unslash( $_POST['lead_gen_cta_button_link'] ) ) : '';
	$cta_helper       = isset( $_POST['lead_gen_cta_helper'] ) ? sanitize_text_field( wp_unslash( $_POST['lead_gen_cta_helper'] ) ) : '';
	$cta_background   = isset( $_POST['lead_gen_cta_background'] ) ? sanitize_text_field( wp_unslash( $_POST['lead_gen_cta_background'] ) ) : '';
	$cta_bg_image     = isset( $_POST['lead_gen_cta_background_image'] ) ? esc_url_raw( wp_unslash( $_POST['lead_gen_cta_background_image'] ) ) : '';
	$faq_heading      = isset( $_POST['lead_gen_faq_heading'] ) ? sanitize_text_field( wp_unslash( $_POST['lead_gen_faq_heading'] ) ) : '';
	$footer_logo      = isset( $_POST['lead_gen_footer_logo_text'] ) ? sanitize_text_field( wp_unslash( $_POST['lead_gen_footer_logo_text'] ) ) : '';
	$footer_text      = isset( $_POST['lead_gen_footer_text'] ) ? sanitize_text_field( wp_unslash( $_POST['lead_gen_footer_text'] ) ) : '';
	$logos_raw        = isset( $_POST['lead_gen_logos'] ) ? (array) wp_unslash( $_POST['lead_gen_logos'] ) : array();
	$features_raw     = isset( $_POST['lead_gen_features'] ) ? (array) wp_unslash( $_POST['lead_gen_features'] ) : array();
	$faqs_raw         = isset( $_POST['lead_gen_faqs'] ) ? (array) wp_unslash( $_POST['lead_gen_faqs'] ) : array();
	$footer_links_raw = isset( $_POST['lead_gen_footer_links'] ) ? (array) wp_unslash( $_POST['lead_gen_footer_links'] ) : array();

	$logos = array();
	foreach ( $logos_raw as $logo ) {
		if ( ! is_array( $logo ) ) {
			continue;
		}
		$image = isset( $logo['image'] ) ? esc_url_raw( $logo['image'] ) : '';
		$url   = isset( $logo['url'] ) ? esc_url_raw( $logo['url'] ) : '';
		$label = isset( $logo['label'] ) ? sanitize_text_field( $logo['label'] ) : '';
		if ( ! $image && ! $label ) {
			continue;
		}
		$logos[] = array(
			'image' => $image,
			'url'   => $url,
			'label' => $label,
		);
	}

	$features = array();
	foreach ( $features_raw as $feature ) {
		if ( ! is_array( $feature ) ) {
			continue;
		}
		$tag         = isset( $feature['tag'] ) ? sanitize_text_field( $feature['tag'] ) : '';
		$title       = isset( $feature['title'] ) ? sanitize_text_field( $feature['title'] ) : '';
		$description = isset( $feature['description'] ) ? sanitize_textarea_field( $feature['description'] ) : '';
		$image       = isset( $feature['image'] ) ? esc_url_raw( $feature['image'] ) : '';
		$url         = isset( $feature['url'] ) ? esc_url_raw( $feature['url'] ) : '';
		if ( ! $title && ! $description && ! $image ) {
			continue;
		}
		$features[] = array(
			'tag'         => $tag,
			'title'       => $title,
			'description' => $description,
			'image'       => $image,
			'url'         => $url,
		);
	}

	$faqs = array();
	foreach ( $faqs_raw as $faq ) {
		if ( ! is_array( $faq ) ) {
			continue;
		}
		$question = isset( $faq['question'] ) ? sanitize_text_field( $faq['question'] ) : '';
		$answer   = isset( $faq['answer'] ) ? sanitize_textarea_field( $faq['answer'] ) : '';
		if ( ! $question && ! $answer ) {
			continue;
		}
		$faqs[] = array(
			'question' => $question,
			'answer'   => $answer,
		);
	}

	$footer_links = array();
	foreach ( $footer_links_raw as $link ) {
		if ( ! is_array( $link ) ) {
			continue;
		}
		$label = isset( $link['label'] ) ? sanitize_text_field( $link['label'] ) : '';
		$url   = isset( $link['url'] ) ? esc_url_raw( $link['url'] ) : '';
		if ( ! $label && ! $url ) {
			continue;
		}
		$footer_links[] = array(
			'label' => $label,
			'url'   => $url,
		);
	}

	if ( $video_url ) {
		update_post_meta( $post_id, 'lead_gen_video_url', $video_url );
	} else {
		delete_post_meta( $post_id, 'lead_gen_video_url' );
	}

	if ( $poster_url ) {
		update_post_meta( $post_id, 'lead_gen_poster_url', $poster_url );
	} else {
		delete_post_meta( $post_id, 'lead_gen_poster_url' );
	}

	if ( $hero_headline ) {
		update_post_meta( $post_id, 'lead_gen_hero_headline', $hero_headline );
	} else {
		delete_post_meta( $post_id, 'lead_gen_hero_headline' );
	}

	if ( $hero_subheadline ) {
		update_post_meta( $post_id, 'lead_gen_hero_subheadline', $hero_subheadline );
	} else {
		delete_post_meta( $post_id, 'lead_gen_hero_subheadline' );
	}

	if ( $hero_cta_label ) {
		update_post_meta( $post_id, 'lead_gen_hero_cta_label', $hero_cta_label );
	} else {
		delete_post_meta( $post_id, 'lead_gen_hero_cta_label' );
	}

	if ( $hero_placeholder ) {
		update_post_meta( $post_id, 'lead_gen_hero_cta_placeholder', $hero_placeholder );
	} else {
		delete_post_meta( $post_id, 'lead_gen_hero_cta_placeholder' );
	}

	if ( $hero_button ) {
		update_post_meta( $post_id, 'lead_gen_hero_cta_button_label', $hero_button );
	} else {
		delete_post_meta( $post_id, 'lead_gen_hero_cta_button_label' );
	}

	if ( $hero_link ) {
		update_post_meta( $post_id, 'lead_gen_hero_cta_button_link', $hero_link );
	} else {
		delete_post_meta( $post_id, 'lead_gen_hero_cta_button_link' );
	}

	if ( $hero_helper ) {
		update_post_meta( $post_id, 'lead_gen_hero_cta_helper', $hero_helper );
	} else {
		delete_post_meta( $post_id, 'lead_gen_hero_cta_helper' );
	}

	if ( $hero_background ) {
		update_post_meta( $post_id, 'lead_gen_hero_background_image', $hero_background );
	} else {
		delete_post_meta( $post_id, 'lead_gen_hero_background_image' );
	}

	if ( $hero_main_logo ) {
		update_post_meta( $post_id, 'lead_gen_main_landing_logo', $hero_main_logo );
	} else {
		delete_post_meta( $post_id, 'lead_gen_main_landing_logo' );
	}

	if ( $hero_logo ) {
		update_post_meta( $post_id, 'lead_gen_hero_logo', $hero_logo );
	} else {
		delete_post_meta( $post_id, 'lead_gen_hero_logo' );
	}

	if ( $fluent_form_id ) {
		update_post_meta( $post_id, 'lead_gen_fluent_form_id', $fluent_form_id );
	} else {
		delete_post_meta( $post_id, 'lead_gen_fluent_form_id' );
	}
	if ( $stripe_payment_link ) {
		update_post_meta( $post_id, 'lead_gen_stripe_payment_link', $stripe_payment_link );
	} else {
		delete_post_meta( $post_id, 'lead_gen_stripe_payment_link' );
	}

	if ( $stripe_payment_link && function_exists( 'apparel_service_maybe_update_payment_link_redirect' ) ) {
		$thank_you_url = apparel_lead_gen_get_thank_you_url( $post_id );
		apparel_service_maybe_update_payment_link_redirect( $stripe_payment_link, $thank_you_url );
	}

	if ( $logos ) {
		update_post_meta( $post_id, 'lead_gen_logos', $logos );
	} else {
		delete_post_meta( $post_id, 'lead_gen_logos' );
	}

	if ( $features ) {
		update_post_meta( $post_id, 'lead_gen_features', $features );
	} else {
		delete_post_meta( $post_id, 'lead_gen_features' );
	}

	if ( $testimonial ) {
		update_post_meta( $post_id, 'lead_gen_testimonial_quote', $testimonial );
	} else {
		delete_post_meta( $post_id, 'lead_gen_testimonial_quote' );
	}

	if ( $testimonial_name ) {
		update_post_meta( $post_id, 'lead_gen_testimonial_name', $testimonial_name );
	} else {
		delete_post_meta( $post_id, 'lead_gen_testimonial_name' );
	}

	if ( $testimonial_co ) {
		update_post_meta( $post_id, 'lead_gen_testimonial_company', $testimonial_co );
	} else {
		delete_post_meta( $post_id, 'lead_gen_testimonial_company' );
	}

	if ( $cta_headline ) {
		update_post_meta( $post_id, 'lead_gen_cta_headline', $cta_headline );
	} else {
		delete_post_meta( $post_id, 'lead_gen_cta_headline' );
	}

	if ( $cta_subheadline ) {
		update_post_meta( $post_id, 'lead_gen_cta_subheadline', $cta_subheadline );
	} else {
		delete_post_meta( $post_id, 'lead_gen_cta_subheadline' );
	}

	if ( $cta_label ) {
		update_post_meta( $post_id, 'lead_gen_cta_label', $cta_label );
	} else {
		delete_post_meta( $post_id, 'lead_gen_cta_label' );
	}

	if ( $cta_placeholder ) {
		update_post_meta( $post_id, 'lead_gen_cta_placeholder', $cta_placeholder );
	} else {
		delete_post_meta( $post_id, 'lead_gen_cta_placeholder' );
	}

	if ( $cta_button ) {
		update_post_meta( $post_id, 'lead_gen_cta_button_label', $cta_button );
	} else {
		delete_post_meta( $post_id, 'lead_gen_cta_button_label' );
	}

	if ( $cta_link ) {
		update_post_meta( $post_id, 'lead_gen_cta_button_link', $cta_link );
	} else {
		delete_post_meta( $post_id, 'lead_gen_cta_button_link' );
	}

	if ( $cta_helper ) {
		update_post_meta( $post_id, 'lead_gen_cta_helper', $cta_helper );
	} else {
		delete_post_meta( $post_id, 'lead_gen_cta_helper' );
	}

	if ( $cta_background ) {
		update_post_meta( $post_id, 'lead_gen_cta_background', $cta_background );
	} else {
		delete_post_meta( $post_id, 'lead_gen_cta_background' );
	}

	if ( $cta_bg_image ) {
		update_post_meta( $post_id, 'lead_gen_cta_background_image', $cta_bg_image );
	} else {
		delete_post_meta( $post_id, 'lead_gen_cta_background_image' );
	}

	if ( $faq_heading ) {
		update_post_meta( $post_id, 'lead_gen_faq_heading', $faq_heading );
	} else {
		delete_post_meta( $post_id, 'lead_gen_faq_heading' );
	}

	if ( $faqs ) {
		update_post_meta( $post_id, 'lead_gen_faqs', $faqs );
	} else {
		delete_post_meta( $post_id, 'lead_gen_faqs' );
	}

	if ( $footer_logo ) {
		update_post_meta( $post_id, 'lead_gen_footer_logo_text', $footer_logo );
	} else {
		delete_post_meta( $post_id, 'lead_gen_footer_logo_text' );
	}

	if ( $footer_text ) {
		update_post_meta( $post_id, 'lead_gen_footer_text', $footer_text );
	} else {
		delete_post_meta( $post_id, 'lead_gen_footer_text' );
	}

	if ( $footer_links ) {
		update_post_meta( $post_id, 'lead_gen_footer_links', $footer_links );
	} else {
		delete_post_meta( $post_id, 'lead_gen_footer_links' );
	}
}
add_action( 'save_post_page', 'apparel_save_lead_gen_meta_box' );

/**
 * Get the thank-you URL for a Lead Gen page.
 *
 * @param int $page_id Lead Gen page ID.
 * @return string
 */
function apparel_lead_gen_get_thank_you_url( $page_id ) {
	$page_id = absint( $page_id );
	if ( ! $page_id ) {
		return '';
	}

	$thank_you_pages = get_posts(
		array(
			'post_type'              => 'page',
			'posts_per_page'         => 1,
			'fields'                 => 'ids',
			'post_parent'            => $page_id,
			'meta_key'               => '_wp_page_template',
			'meta_value'             => 'thank-you.php',
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
		)
	);

	if ( ! empty( $thank_you_pages ) ) {
		$thank_you_url = get_permalink( $thank_you_pages[0] );
		if ( $thank_you_url ) {
			return add_query_arg( 'session_id', '{CHECKOUT_SESSION_ID}', $thank_you_url );
		}
	}

	if ( function_exists( 'apparel_service_get_checkout_success_url' ) ) {
		return apparel_service_get_checkout_success_url();
	}

	return '';
}
