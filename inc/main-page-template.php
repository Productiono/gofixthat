<?php
/**
 * Main Page template fields.
 *
 * @package Apparel
 */

/**
 * Register Main Page template metabox.
 *
 * @param string  $post_type Current post type.
 * @param WP_Post $post      Current post.
 */
function apparel_main_page_add_metaboxes( $post_type, $post ) {
	if ( 'page' !== $post_type ) {
		return;
	}

	if ( 'main-page.php' !== get_page_template_slug( $post ) ) {
		return;
	}

	add_meta_box(
		'apparel-main-page-hero',
		__( 'Main Page Hero', 'apparel' ),
		'apparel_main_page_hero_metabox',
		'page',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'apparel_main_page_add_metaboxes', 10, 2 );

/**
 * Main Page hero metabox output.
 *
 * @param WP_Post $post Current post.
 */
function apparel_main_page_hero_metabox( $post ) {
	wp_nonce_field( 'apparel_main_page_save_meta', 'apparel_main_page_meta_nonce' );

	$hero = apparel_main_page_get_hero_data( $post->ID );
	?>
	<p>
		<label for="apparel-main-page-headline"><strong><?php esc_html_e( 'Headline', 'apparel' ); ?></strong></label>
		<input type="text" id="apparel-main-page-headline" name="apparel_main_page_headline" class="widefat" value="<?php echo esc_attr( $hero['headline'] ); ?>" />
	</p>
	<p>
		<label for="apparel-main-page-subheadline"><strong><?php esc_html_e( 'Subheadline', 'apparel' ); ?></strong></label>
		<textarea id="apparel-main-page-subheadline" name="apparel_main_page_subheadline" class="widefat" rows="3"><?php echo esc_textarea( $hero['subheadline'] ); ?></textarea>
	</p>
	<hr />
	<?php foreach ( $hero['cards'] as $index => $card ) : ?>
		<?php
		$prefix = 'development' === $index ? __( 'Development', 'apparel' ) : ( 'marketing' === $index ? __( 'Marketing', 'apparel' ) : __( 'Automation', 'apparel' ) );
		?>
		<h4><?php echo esc_html( $prefix ); ?></h4>
		<p>
			<label for="apparel-main-page-<?php echo esc_attr( $index ); ?>-title"><strong><?php esc_html_e( 'Card Title', 'apparel' ); ?></strong></label>
			<input type="text" id="apparel-main-page-<?php echo esc_attr( $index ); ?>-title" name="apparel_main_page_<?php echo esc_attr( $index ); ?>_title" class="widefat" value="<?php echo esc_attr( $card['title'] ); ?>" />
		</p>
		<p>
			<label for="apparel-main-page-<?php echo esc_attr( $index ); ?>-text"><strong><?php esc_html_e( 'Card Text', 'apparel' ); ?></strong></label>
			<textarea id="apparel-main-page-<?php echo esc_attr( $index ); ?>-text" name="apparel_main_page_<?php echo esc_attr( $index ); ?>_text" class="widefat" rows="2"><?php echo esc_textarea( $card['text'] ); ?></textarea>
		</p>
		<p>
			<label for="apparel-main-page-<?php echo esc_attr( $index ); ?>-link"><strong><?php esc_html_e( 'Card Link URL', 'apparel' ); ?></strong></label>
			<input type="url" id="apparel-main-page-<?php echo esc_attr( $index ); ?>-link" name="apparel_main_page_<?php echo esc_attr( $index ); ?>_link" class="widefat" value="<?php echo esc_url( $card['link'] ); ?>" placeholder="https://example.com" />
		</p>
		<hr />
	<?php endforeach; ?>
	<p>
		<label for="apparel-main-page-cta-label"><strong><?php esc_html_e( 'CTA Button Label', 'apparel' ); ?></strong></label>
		<input type="text" id="apparel-main-page-cta-label" name="apparel_main_page_cta_label" class="widefat" value="<?php echo esc_attr( $hero['cta']['label'] ); ?>" />
	</p>
	<p>
		<label for="apparel-main-page-cta-link"><strong><?php esc_html_e( 'CTA Button Link', 'apparel' ); ?></strong></label>
		<input type="url" id="apparel-main-page-cta-link" name="apparel_main_page_cta_link" class="widefat" value="<?php echo esc_url( $hero['cta']['link'] ); ?>" placeholder="https://example.com" />
	</p>
	<?php
}

/**
 * Save Main Page meta.
 *
 * @param int $post_id Post ID.
 */
function apparel_main_page_save_meta( $post_id ) {
	if ( ! isset( $_POST['apparel_main_page_meta_nonce'] ) ) {
		return;
	}

	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['apparel_main_page_meta_nonce'] ) ), 'apparel_main_page_save_meta' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$headline = isset( $_POST['apparel_main_page_headline'] ) ? sanitize_text_field( wp_unslash( $_POST['apparel_main_page_headline'] ) ) : '';
	update_post_meta( $post_id, '_apparel_main_page_headline', $headline );

	$subheadline = isset( $_POST['apparel_main_page_subheadline'] ) ? sanitize_textarea_field( wp_unslash( $_POST['apparel_main_page_subheadline'] ) ) : '';
	update_post_meta( $post_id, '_apparel_main_page_subheadline', $subheadline );

	$card_keys = array( 'development', 'marketing', 'automation' );
	foreach ( $card_keys as $card_key ) {
		$title_key = 'apparel_main_page_' . $card_key . '_title';
		$text_key  = 'apparel_main_page_' . $card_key . '_text';
		$link_key  = 'apparel_main_page_' . $card_key . '_link';

		$title = isset( $_POST[ $title_key ] ) ? sanitize_text_field( wp_unslash( $_POST[ $title_key ] ) ) : '';
		$text  = isset( $_POST[ $text_key ] ) ? sanitize_textarea_field( wp_unslash( $_POST[ $text_key ] ) ) : '';
		$link  = isset( $_POST[ $link_key ] ) ? esc_url_raw( wp_unslash( $_POST[ $link_key ] ) ) : '';

		update_post_meta( $post_id, '_apparel_main_page_' . $card_key . '_title', $title );
		update_post_meta( $post_id, '_apparel_main_page_' . $card_key . '_text', $text );
		if ( $link ) {
			update_post_meta( $post_id, '_apparel_main_page_' . $card_key . '_link', $link );
		} else {
			delete_post_meta( $post_id, '_apparel_main_page_' . $card_key . '_link' );
		}
	}

	$cta_label = isset( $_POST['apparel_main_page_cta_label'] ) ? sanitize_text_field( wp_unslash( $_POST['apparel_main_page_cta_label'] ) ) : '';
	$cta_link  = isset( $_POST['apparel_main_page_cta_link'] ) ? esc_url_raw( wp_unslash( $_POST['apparel_main_page_cta_link'] ) ) : '';

	update_post_meta( $post_id, '_apparel_main_page_cta_label', $cta_label );
	if ( $cta_link ) {
		update_post_meta( $post_id, '_apparel_main_page_cta_link', $cta_link );
	} else {
		delete_post_meta( $post_id, '_apparel_main_page_cta_link' );
	}
}
add_action( 'save_post_page', 'apparel_main_page_save_meta' );

/**
 * Get Main Page hero data.
 *
 * @param int $post_id Post ID.
 * @return array
 */
function apparel_main_page_get_hero_data( $post_id ) {
	$defaults = array(
		'headline'    => __( 'Power your next growth step with our experts.', 'apparel' ),
		'subheadline' => __( 'Choose a specialized team to build, market, or automate your next initiative.', 'apparel' ),
		'cards'       => array(
			'development' => array(
				'title' => __( 'Development', 'apparel' ),
				'text'  => __( 'Custom builds that ship fast and scale with confidence.', 'apparel' ),
				'link'  => '#',
			),
			'marketing' => array(
				'title' => __( 'Marketing', 'apparel' ),
				'text'  => __( 'Campaigns and content designed to convert high-intent leads.', 'apparel' ),
				'link'  => '#',
			),
			'automation' => array(
				'title' => __( 'Automation', 'apparel' ),
				'text'  => __( 'Workflows and integrations that keep revenue moving.', 'apparel' ),
				'link'  => '#',
			),
		),
		'cta'         => array(
			'label' => __( 'Start a project', 'apparel' ),
			'link'  => '',
		),
	);

	$headline = get_post_meta( $post_id, '_apparel_main_page_headline', true );
	$subheadline = get_post_meta( $post_id, '_apparel_main_page_subheadline', true );

	$cards = array();
	foreach ( array( 'development', 'marketing', 'automation' ) as $key ) {
		$cards[ $key ] = array(
			'title' => get_post_meta( $post_id, '_apparel_main_page_' . $key . '_title', true ),
			'text'  => get_post_meta( $post_id, '_apparel_main_page_' . $key . '_text', true ),
			'link'  => get_post_meta( $post_id, '_apparel_main_page_' . $key . '_link', true ),
		);
	}

	$cta = array(
		'label' => get_post_meta( $post_id, '_apparel_main_page_cta_label', true ),
		'link'  => get_post_meta( $post_id, '_apparel_main_page_cta_link', true ),
	);

	$hero = $defaults;

	if ( $headline ) {
		$hero['headline'] = $headline;
	}

	if ( $subheadline ) {
		$hero['subheadline'] = $subheadline;
	}

	foreach ( $cards as $key => $card ) {
		if ( ! empty( $card['title'] ) ) {
			$hero['cards'][ $key ]['title'] = $card['title'];
		}
		if ( ! empty( $card['text'] ) ) {
			$hero['cards'][ $key ]['text'] = $card['text'];
		}
		if ( ! empty( $card['link'] ) ) {
			$hero['cards'][ $key ]['link'] = $card['link'];
		}
	}

	if ( ! empty( $cta['label'] ) ) {
		$hero['cta']['label'] = $cta['label'];
	}
	if ( ! empty( $cta['link'] ) ) {
		$hero['cta']['link'] = $cta['link'];
	}

	return $hero;
}
