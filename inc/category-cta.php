<?php
/**
 * Category-based CTA management and rendering helpers.
 *
 * @package Apparel
 */

/**
 * Return CTA meta fields definitions for post categories.
 *
 * @return array
 */
function mbf_get_category_cta_fields() {
	return array(
		'headline'        => array(
			'label'   => __( 'CTA Headline', 'apparel' ),
			'key'     => 'mbf_cta_headline',
			'sanitize' => 'sanitize_text_field',
		),
		'subheadline'     => array(
			'label'   => __( 'CTA Sub-headline', 'apparel' ),
			'key'     => 'mbf_cta_subheadline',
			'sanitize' => 'sanitize_text_field',
		),
		'description'     => array(
			'label'   => __( 'CTA Description', 'apparel' ),
			'key'     => 'mbf_cta_description',
			'sanitize' => 'sanitize_textarea_field',
		),
		'primary_label'   => array(
			'label'   => __( 'Primary Button Text', 'apparel' ),
			'key'     => 'mbf_cta_primary_label',
			'sanitize' => 'sanitize_text_field',
		),
		'primary_url'     => array(
			'label'   => __( 'Primary Button URL', 'apparel' ),
			'key'     => 'mbf_cta_primary_url',
			'sanitize' => 'esc_url_raw',
		),
		'secondary_label' => array(
			'label'   => __( 'Secondary Link Text', 'apparel' ),
			'key'     => 'mbf_cta_secondary_label',
			'sanitize' => 'sanitize_text_field',
		),
		'secondary_url'   => array(
			'label'   => __( 'Secondary Link URL', 'apparel' ),
			'key'     => 'mbf_cta_secondary_url',
			'sanitize' => 'esc_url_raw',
		),
	);
}

/**
 * Display CTA fields on category add form.
 *
 * @param string $taxonomy The taxonomy slug.
 */
function mbf_category_cta_add_fields( $taxonomy ) {
	wp_nonce_field( 'mbf_category_cta_fields', 'mbf_category_cta_nonce' );
	?>
	<div class="form-field">
		<label for="mbf_cta_headline"><?php esc_html_e( 'CTA Headline', 'apparel' ); ?></label>
		<input name="mbf_cta_headline" id="mbf_cta_headline" type="text" />
	</div>
	<div class="form-field">
		<label for="mbf_cta_subheadline"><?php esc_html_e( 'CTA Sub-headline', 'apparel' ); ?></label>
		<input name="mbf_cta_subheadline" id="mbf_cta_subheadline" type="text" />
	</div>
	<div class="form-field">
		<label for="mbf_cta_description"><?php esc_html_e( 'CTA Description', 'apparel' ); ?></label>
		<textarea name="mbf_cta_description" id="mbf_cta_description" rows="4"></textarea>
	</div>
	<div class="form-field">
		<label for="mbf_cta_primary_label"><?php esc_html_e( 'Primary Button Text', 'apparel' ); ?></label>
		<input name="mbf_cta_primary_label" id="mbf_cta_primary_label" type="text" />
	</div>
	<div class="form-field">
		<label for="mbf_cta_primary_url"><?php esc_html_e( 'Primary Button URL', 'apparel' ); ?></label>
		<input name="mbf_cta_primary_url" id="mbf_cta_primary_url" type="url" />
	</div>
	<div class="form-field">
		<label for="mbf_cta_secondary_label"><?php esc_html_e( 'Secondary Link Text', 'apparel' ); ?></label>
		<input name="mbf_cta_secondary_label" id="mbf_cta_secondary_label" type="text" />
	</div>
	<div class="form-field">
		<label for="mbf_cta_secondary_url"><?php esc_html_e( 'Secondary Link URL', 'apparel' ); ?></label>
		<input name="mbf_cta_secondary_url" id="mbf_cta_secondary_url" type="url" />
	</div>
	<?php
}
add_action( 'category_add_form_fields', 'mbf_category_cta_add_fields' );

/**
 * Display CTA fields on category edit form.
 *
 * @param WP_Term $term     Current term object.
 * @param string  $taxonomy Current taxonomy slug.
 */
function mbf_category_cta_edit_fields( $term, $taxonomy ) {
	wp_nonce_field( 'mbf_category_cta_fields', 'mbf_category_cta_nonce' );
	$fields = mbf_get_category_cta_fields();
	?>
	<tr class="form-field">
		<th colspan="2"><h3><?php esc_html_e( 'Category CTA', 'apparel' ); ?></h3></th>
	</tr>
	<?php foreach ( $fields as $field ) :
		$value = get_term_meta( $term->term_id, $field['key'], true );
		?>
	<tr class="form-field">
		<th scope="row"><label for="<?php echo esc_attr( $field['key'] ); ?>"><?php echo esc_html( $field['label'] ); ?></label></th>
		<td>
			<?php if ( 'mbf_cta_description' === $field['key'] ) : ?>
				<textarea name="<?php echo esc_attr( $field['key'] ); ?>" id="<?php echo esc_attr( $field['key'] ); ?>" rows="4" class="large-text"><?php echo esc_textarea( $value ); ?></textarea>
			<?php else : ?>
				<input name="<?php echo esc_attr( $field['key'] ); ?>" id="<?php echo esc_attr( $field['key'] ); ?>" type="text" value="<?php echo esc_attr( $value ); ?>" class="regular-text" />
			<?php endif; ?>
		</td>
	</tr>
	<?php endforeach; ?>
	<?php
}
add_action( 'category_edit_form_fields', 'mbf_category_cta_edit_fields', 10, 2 );

/**
 * Save CTA data for categories.
 *
 * @param int    $term_id  Term ID.
 * @param string $taxonomy Taxonomy slug.
 */
function mbf_category_cta_save_fields( $term_id, $taxonomy ) {
	if ( 'category' !== $taxonomy ) {
		return;
	}

	if ( ! isset( $_POST['mbf_category_cta_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['mbf_category_cta_nonce'] ), 'mbf_category_cta_fields' ) ) {
		return;
	}

	$fields = mbf_get_category_cta_fields();

	foreach ( $fields as $field ) {
		$key       = $field['key'];
		$sanitizer = isset( $field['sanitize'] ) && is_callable( $field['sanitize'] ) ? $field['sanitize'] : 'sanitize_text_field';

		if ( ! isset( $_POST[ $key ] ) ) {
			continue;
		}

		$value = call_user_func( $sanitizer, wp_unslash( $_POST[ $key ] ) );

		if ( '' === $value ) {
			delete_term_meta( $term_id, $key );
			continue;
		}

		update_term_meta( $term_id, $key, $value );
	}
}
add_action( 'created_category', 'mbf_category_cta_save_fields', 10, 2 );
add_action( 'edited_category', 'mbf_category_cta_save_fields', 10, 2 );

/**
 * Get CTA data for a category.
 *
 * @param int $term_id Category term ID.
 *
 * @return array
 */
function mbf_get_category_cta_data( $term_id ) {
	$fields   = mbf_get_category_cta_fields();
	$cta_data = array();

	foreach ( $fields as $key => $field ) {
		$value = get_term_meta( $term_id, $field['key'], true );
		if ( '' !== $value ) {
			$cta_data[ $key ] = $value;
		}
	}

	$cta_data = apply_filters( 'mbf_category_cta_data', $cta_data, $term_id );

	$has_values = array_filter( $cta_data );

	return $has_values ? $cta_data : array();
}

/**
 * Get primary category ID for a post.
 *
 * Uses Yoast SEO primary category when available.
 *
 * @param int $post_id Post ID.
 *
 * @return int
 */
function mbf_get_primary_category_id( $post_id ) {
	$post_id = absint( $post_id );

	if ( ! $post_id ) {
		return 0;
	}

	$primary_category_id = 0;

	if ( class_exists( 'WPSEO_Primary_Term' ) ) {
		$wpseo_primary_term = new WPSEO_Primary_Term( 'category', $post_id );
		$primary_term_id    = $wpseo_primary_term->get_primary_term();

		if ( ! is_wp_error( $primary_term_id ) ) {
			$primary_category_id = absint( $primary_term_id );
		}
	}

	if ( $primary_category_id ) {
		return $primary_category_id;
	}

	$categories = get_the_category( $post_id );

	if ( empty( $categories ) || is_wp_error( $categories ) ) {
		return 0;
	}

	return absint( $categories[0]->term_id );
}

/**
 * Get the CTA data for the active post category.
 *
 * @param int|null $post_id Optional post ID.
 *
 * @return array
 */
function mbf_get_active_category_cta_data( $post_id = null ) {
	$post_id = $post_id ? absint( $post_id ) : get_queried_object_id();

	if ( ! $post_id || 'post' !== get_post_type( $post_id ) ) {
		return array();
	}

	$category_id = mbf_get_primary_category_id( $post_id );

	if ( ! $category_id ) {
		return array();
	}

	return mbf_get_category_cta_data( $category_id );
}

