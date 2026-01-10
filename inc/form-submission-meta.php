<?php
/**
 * Form Submission page template meta boxes.
 *
 * @package Apparel
 */

/**
 * Register the Form Submission settings meta box.
 *
 * @param string  $post_type Post type.
 * @param WP_Post $post      Current post object.
 */
function apparel_register_form_submission_meta_box( $post_type, $post ) {
	if ( 'page' !== $post_type ) {
		return;
	}

	if ( 'form-submission.php' !== get_page_template_slug( $post ) ) {
		return;
	}

	add_meta_box(
		'apparel-form-submission-settings',
		esc_html__( 'Form Submission Settings', 'apparel' ),
		'apparel_render_form_submission_meta_box',
		'page',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'apparel_register_form_submission_meta_box', 10, 2 );

/**
 * Render the Form Submission settings meta box.
 *
 * @param WP_Post $post Current post object.
 */
function apparel_render_form_submission_meta_box( $post ) {
	wp_nonce_field( 'apparel_form_submission_meta', 'apparel_form_submission_meta_nonce' );

	$button_label = get_post_meta( $post->ID, 'form_submission_button_label', true );
	$button_url   = get_post_meta( $post->ID, 'form_submission_button_url', true );
	$next_title   = get_post_meta( $post->ID, 'form_submission_next_title', true );
	$next_content = get_post_meta( $post->ID, 'form_submission_next_content', true );
	?>
	<p>
		<label for="form-submission-button-label"><strong><?php esc_html_e( 'Button label', 'apparel' ); ?></strong></label>
		<input
			type="text"
			id="form-submission-button-label"
			name="form_submission_button_label"
			class="widefat"
			value="<?php echo esc_attr( $button_label ); ?>"
		/>
	</p>
	<p>
		<label for="form-submission-button-url"><strong><?php esc_html_e( 'Button URL', 'apparel' ); ?></strong></label>
		<input
			type="url"
			id="form-submission-button-url"
			name="form_submission_button_url"
			class="widefat"
			value="<?php echo esc_url( $button_url ); ?>"
			placeholder="https://example.com"
		/>
	</p>
	<hr />
	<p>
		<label for="form-submission-next-title"><strong><?php esc_html_e( 'What\'s next title', 'apparel' ); ?></strong></label>
		<input
			type="text"
			id="form-submission-next-title"
			name="form_submission_next_title"
			class="widefat"
			value="<?php echo esc_attr( $next_title ); ?>"
		/>
	</p>
	<p>
		<label for="form-submission-next-content"><strong><?php esc_html_e( 'What\'s next content', 'apparel' ); ?></strong></label>
		<textarea
			id="form-submission-next-content"
			name="form_submission_next_content"
			class="widefat"
			rows="5"
		><?php echo esc_textarea( $next_content ); ?></textarea>
		<span class="description"><?php esc_html_e( 'Optional short description shown beneath the title.', 'apparel' ); ?></span>
	</p>
	<?php
}

/**
 * Save Form Submission meta box values.
 *
 * @param int $post_id Post ID.
 */
function apparel_save_form_submission_meta_box( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! isset( $_POST['apparel_form_submission_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['apparel_form_submission_meta_nonce'] ) ), 'apparel_form_submission_meta' ) ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	if ( 'form-submission.php' !== get_page_template_slug( $post_id ) ) {
		return;
	}

	$button_label = isset( $_POST['form_submission_button_label'] ) ? sanitize_text_field( wp_unslash( $_POST['form_submission_button_label'] ) ) : '';
	$button_url   = isset( $_POST['form_submission_button_url'] ) ? esc_url_raw( wp_unslash( $_POST['form_submission_button_url'] ) ) : '';
	$next_title   = isset( $_POST['form_submission_next_title'] ) ? sanitize_text_field( wp_unslash( $_POST['form_submission_next_title'] ) ) : '';
	$next_content = isset( $_POST['form_submission_next_content'] ) ? wp_kses_post( wp_unslash( $_POST['form_submission_next_content'] ) ) : '';

	if ( $button_label ) {
		update_post_meta( $post_id, 'form_submission_button_label', $button_label );
	} else {
		delete_post_meta( $post_id, 'form_submission_button_label' );
	}

	if ( $button_url ) {
		update_post_meta( $post_id, 'form_submission_button_url', $button_url );
	} else {
		delete_post_meta( $post_id, 'form_submission_button_url' );
	}

	if ( $next_title ) {
		update_post_meta( $post_id, 'form_submission_next_title', $next_title );
	} else {
		delete_post_meta( $post_id, 'form_submission_next_title' );
	}

	if ( $next_content ) {
		update_post_meta( $post_id, 'form_submission_next_content', $next_content );
	} else {
		delete_post_meta( $post_id, 'form_submission_next_content' );
	}
}
add_action( 'save_post', 'apparel_save_form_submission_meta_box' );
