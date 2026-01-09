<?php
/**
 * Lead Gen page template meta boxes.
 *
 * @package Apparel
 */

/**
 * Register the Lead Gen hero media meta box.
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
		'apparel-lead-gen-hero-media',
		esc_html__( 'Lead Gen Hero Media', 'apparel' ),
		'apparel_render_lead_gen_meta_box',
		'page',
		'side',
		'default'
	);
}
add_action( 'add_meta_boxes', 'apparel_register_lead_gen_meta_box', 10, 2 );

/**
 * Render the Lead Gen hero media meta box.
 *
 * @param WP_Post $post Current post object.
 */
function apparel_render_lead_gen_meta_box( $post ) {
	wp_nonce_field( 'apparel_lead_gen_meta', 'apparel_lead_gen_meta_nonce' );

	$video_url  = get_post_meta( $post->ID, 'lead_gen_video_url', true );
	$poster_url = get_post_meta( $post->ID, 'lead_gen_poster_url', true );
	$default    = get_template_directory_uri() . '/assets/static/background-video.webm';
	?>
	<p>
		<label for="lead-gen-video-url"><strong><?php esc_html_e( 'Background video URL', 'apparel' ); ?></strong></label>
		<input
			type="url"
			id="lead-gen-video-url"
			name="lead_gen_video_url"
			class="widefat"
			value="<?php echo esc_attr( $video_url ); ?>"
			placeholder="<?php echo esc_attr( $default ); ?>"
		/>
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
	</p>
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

	$video_url  = isset( $_POST['lead_gen_video_url'] ) ? esc_url_raw( wp_unslash( $_POST['lead_gen_video_url'] ) ) : '';
	$poster_url = isset( $_POST['lead_gen_poster_url'] ) ? esc_url_raw( wp_unslash( $_POST['lead_gen_poster_url'] ) ) : '';

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
}
add_action( 'save_post_page', 'apparel_save_lead_gen_meta_box' );
