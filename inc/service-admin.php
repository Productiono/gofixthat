<?php
/**
 * Service CPT admin functionality.
 *
 * @package Apparel
 */

/**
 * Register Service custom post type.
 */
function apparel_register_service_cpt() {
	$labels = array(
		'name'               => __( 'Services', 'apparel' ),
		'singular_name'      => __( 'Service', 'apparel' ),
		'add_new'            => __( 'Add New', 'apparel' ),
		'add_new_item'       => __( 'Add New Service', 'apparel' ),
		'edit_item'          => __( 'Edit Service', 'apparel' ),
		'new_item'           => __( 'New Service', 'apparel' ),
		'view_item'          => __( 'View Service', 'apparel' ),
		'search_items'       => __( 'Search Services', 'apparel' ),
		'not_found'          => __( 'No services found.', 'apparel' ),
		'not_found_in_trash' => __( 'No services found in Trash.', 'apparel' ),
		'menu_name'          => __( 'Services', 'apparel' ),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'exclude_from_search' => false,
		'show_in_admin_bar'  => true,
		'show_in_nav_menus'  => true,
		'capability_type'    => 'post',
		'map_meta_cap'       => true,
		'supports'           => array( 'title', 'editor', 'thumbnail', 'comments' ),
		'menu_position'      => 20,
		'menu_icon'          => 'dashicons-admin-generic',
		'has_archive'        => true,
		'rewrite'            => array(
			'slug'       => 'service',
			'with_front' => false,
		),
		'query_var'          => true,
		'show_in_rest'       => false,
	);

	register_post_type( 'service', $args );
}
add_action( 'init', 'apparel_register_service_cpt' );

/**
 * Add service metaboxes.
 */
function apparel_service_add_metaboxes() {
	add_meta_box(
		'apparel-service-details',
		__( 'Service Details', 'apparel' ),
		'apparel_service_details_metabox',
		'service',
		'normal',
		'default'
	);

	add_meta_box(
		'apparel-service-support',
		__( 'Support Section', 'apparel' ),
		'apparel_service_support_metabox',
		'service',
		'normal',
		'default'
	);

	add_meta_box(
		'apparel-service-live-preview',
		__( 'Live Preview Link', 'apparel' ),
		'apparel_service_live_preview_metabox',
		'service',
		'side',
		'default'
	);

	add_meta_box(
		'apparel-service-screenshots',
		__( 'Screenshots', 'apparel' ),
		'apparel_service_screenshots_metabox',
		'service',
		'normal',
		'default'
	);

	add_meta_box(
		'apparel-service-variations',
		__( 'Variations', 'apparel' ),
		'apparel_service_variations_metabox',
		'service',
		'normal',
		'default'
	);
}
add_action( 'add_meta_boxes', 'apparel_service_add_metaboxes' );

/**
 * Service details metabox.
 *
 * @param WP_Post $post Current post.
 */
function apparel_service_details_metabox( $post ) {
	wp_nonce_field( 'apparel_service_save_meta', 'apparel_service_meta_nonce' );

	$service_price      = get_post_meta( $post->ID, '_service_price', true );
	$service_sale_price = get_post_meta( $post->ID, '_service_sale_price', true );
	$checkout_url       = get_post_meta( $post->ID, '_service_checkout_url', true );
	?>
	<p>
		<label for="apparel-service-price"><strong><?php esc_html_e( 'Service Price', 'apparel' ); ?></strong></label>
		<input type="number" id="apparel-service-price" name="apparel_service_price" class="regular-text" step="0.01" min="0" value="<?php echo esc_attr( $service_price ); ?>" />
	</p>
	<p>
		<label for="apparel-service-sale-price"><strong><?php esc_html_e( 'Service Sale Price', 'apparel' ); ?></strong></label>
		<input type="number" id="apparel-service-sale-price" name="apparel_service_sale_price" class="regular-text" step="0.01" min="0" value="<?php echo esc_attr( $service_sale_price ); ?>" />
	</p>
	<p>
		<label for="apparel-service-checkout-url"><strong><?php esc_html_e( 'Checkout Link', 'apparel' ); ?></strong></label>
		<input type="url" id="apparel-service-checkout-url" name="apparel_service_checkout_url" class="widefat" value="<?php echo esc_url( $checkout_url ); ?>" placeholder="https://example.com/checkout" />
	</p>
	<?php
}

/**
 * Support section metabox.
 *
 * @param WP_Post $post Current post.
 */
function apparel_service_support_metabox( $post ) {
	wp_nonce_field( 'apparel_service_save_meta', 'apparel_service_meta_nonce' );

	$support_title   = get_post_meta( $post->ID, '_service_support_title', true );
	$support_content = get_post_meta( $post->ID, '_service_support_content', true );
	?>
	<p>
		<label for="apparel-service-support-title"><strong><?php esc_html_e( 'Support Title', 'apparel' ); ?></strong></label>
		<input type="text" id="apparel-service-support-title" name="apparel_service_support_title" class="widefat" value="<?php echo esc_attr( $support_title ); ?>" />
	</p>
	<p>
		<label for="apparel-service-support-content"><strong><?php esc_html_e( 'Support Content', 'apparel' ); ?></strong></label>
	</p>
	<?php
	wp_editor(
		$support_content,
		'apparel_service_support_content',
		array(
			'textarea_name' => 'apparel_service_support_content',
			'textarea_rows' => 6,
		)
	);
}

/**
 * Live preview link metabox.
 *
 * @param WP_Post $post Current post.
 */
function apparel_service_live_preview_metabox( $post ) {
	wp_nonce_field( 'apparel_service_save_meta', 'apparel_service_meta_nonce' );

	$live_preview_url = get_post_meta( $post->ID, '_service_live_preview_url', true );
	?>
	<p>
		<label for="apparel-service-live-preview-url"><strong><?php esc_html_e( 'Live Preview URL', 'apparel' ); ?></strong></label>
		<input type="url" id="apparel-service-live-preview-url" name="apparel_service_live_preview_url" class="widefat" value="<?php echo esc_url( $live_preview_url ); ?>" placeholder="https://example.com" />
	</p>
	<?php
}

/**
 * Screenshots metabox.
 *
 * @param WP_Post $post Current post.
 */
function apparel_service_screenshots_metabox( $post ) {
	wp_nonce_field( 'apparel_service_save_meta', 'apparel_service_meta_nonce' );

	$screenshot_ids = get_post_meta( $post->ID, '_service_screenshot_ids', true );
	if ( ! is_array( $screenshot_ids ) ) {
		$screenshot_ids = array();
	}
	?>
	<div id="apparel-service-screenshots" class="apparel-service-screenshots">
		<p>
			<button type="button" class="button apparel-service-add-screenshot"><?php esc_html_e( 'Add Screenshots', 'apparel' ); ?></button>
		</p>
		<ul class="apparel-service-screenshot-list">
			<?php foreach ( $screenshot_ids as $attachment_id ) : ?>
				<?php $image = wp_get_attachment_image( $attachment_id, array( 120, 120 ), false, array( 'class' => 'apparel-service-screenshot-image' ) ); ?>
				<?php if ( $image ) : ?>
					<li class="apparel-service-screenshot-item" data-attachment-id="<?php echo esc_attr( $attachment_id ); ?>">
						<?php echo $image; ?>
						<button type="button" class="button-link delete apparel-service-remove-screenshot"><?php esc_html_e( 'Remove', 'apparel' ); ?></button>
					</li>
				<?php endif; ?>
			<?php endforeach; ?>
		</ul>
		<input type="hidden" name="apparel_service_screenshot_ids" class="apparel-service-screenshot-ids" value="<?php echo esc_attr( implode( ',', array_map( 'absint', $screenshot_ids ) ) ); ?>" />
	</div>
	<?php
}

/**
 * Variations metabox.
 *
 * @param WP_Post $post Current post.
 */
function apparel_service_variations_metabox( $post ) {
	wp_nonce_field( 'apparel_service_save_meta', 'apparel_service_meta_nonce' );

	$variations = get_post_meta( $post->ID, '_service_variations', true );
	if ( ! is_array( $variations ) ) {
		$variations = array();
	}
	?>
	<div class="apparel-service-variations">
		<p>
			<button type="button" class="button apparel-service-add-variation"><?php esc_html_e( 'Add Variation', 'apparel' ); ?></button>
		</p>
		<table class="widefat striped apparel-service-variations-table">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Name', 'apparel' ); ?></th>
					<th><?php esc_html_e( 'Price', 'apparel' ); ?></th>
					<th><?php esc_html_e( 'Sale Price', 'apparel' ); ?></th>
					<th><?php esc_html_e( 'Currency', 'apparel' ); ?></th>
					<th><?php esc_html_e( 'Stripe Product ID', 'apparel' ); ?></th>
					<th><?php esc_html_e( 'Stripe Price ID', 'apparel' ); ?></th>
					<th><?php esc_html_e( 'Stripe Payment Link', 'apparel' ); ?></th>
					<th><?php esc_html_e( 'Active', 'apparel' ); ?></th>
					<th><?php esc_html_e( 'Actions', 'apparel' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ( $variations as $index => $variation ) : ?>
					<?php
					$variation_id       = isset( $variation['variation_id'] ) ? $variation['variation_id'] : '';
					$name               = isset( $variation['name'] ) ? $variation['name'] : '';
					$price_amount       = isset( $variation['price_amount'] ) ? $variation['price_amount'] : '';
					$price              = isset( $variation['price'] ) ? $variation['price'] : '';
					$sale_price         = isset( $variation['sale_price'] ) ? $variation['sale_price'] : '';
					$currency           = isset( $variation['currency'] ) ? $variation['currency'] : '';
					$stripe_product_id  = isset( $variation['stripe_product_id'] ) ? $variation['stripe_product_id'] : '';
					$stripe_price_id    = isset( $variation['stripe_price_id'] ) ? $variation['stripe_price_id'] : '';
					$stripe_payment_link = isset( $variation['stripe_payment_link'] ) ? $variation['stripe_payment_link'] : '';
					$active             = ! empty( $variation['active'] );
					?>
					<tr class="apparel-service-variation-row">
						<td>
							<input type="hidden" name="apparel_service_variations[<?php echo esc_attr( $index ); ?>][variation_id]" value="<?php echo esc_attr( $variation_id ); ?>" />
							<input type="text" class="widefat" name="apparel_service_variations[<?php echo esc_attr( $index ); ?>][name]" value="<?php echo esc_attr( $name ); ?>" />
						</td>
						<td>
							<input type="number" class="small-text" min="0" step="0.01" name="apparel_service_variations[<?php echo esc_attr( $index ); ?>][price]" value="<?php echo esc_attr( $price ); ?>" />
							<input type="number" class="small-text" min="0" step="1" name="apparel_service_variations[<?php echo esc_attr( $index ); ?>][price_amount]" value="<?php echo esc_attr( $price_amount ); ?>" />
						</td>
						<td>
							<input type="number" class="small-text" min="0" step="0.01" name="apparel_service_variations[<?php echo esc_attr( $index ); ?>][sale_price]" value="<?php echo esc_attr( $sale_price ); ?>" />
						</td>
						<td>
							<input type="text" class="small-text" name="apparel_service_variations[<?php echo esc_attr( $index ); ?>][currency]" value="<?php echo esc_attr( $currency ); ?>" />
						</td>
						<td>
							<input type="text" class="widefat" name="apparel_service_variations[<?php echo esc_attr( $index ); ?>][stripe_product_id]" value="<?php echo esc_attr( $stripe_product_id ); ?>" />
						</td>
						<td>
							<input type="text" class="widefat" name="apparel_service_variations[<?php echo esc_attr( $index ); ?>][stripe_price_id]" value="<?php echo esc_attr( $stripe_price_id ); ?>" />
						</td>
						<td>
							<input type="url" class="widefat" name="apparel_service_variations[<?php echo esc_attr( $index ); ?>][stripe_payment_link]" value="<?php echo esc_url( $stripe_payment_link ); ?>" />
						</td>
						<td>
							<label>
								<input type="checkbox" name="apparel_service_variations[<?php echo esc_attr( $index ); ?>][active]" value="1" <?php checked( $active ); ?> />
							</label>
						</td>
						<td>
							<button type="button" class="button-link delete apparel-service-remove-variation"><?php esc_html_e( 'Remove', 'apparel' ); ?></button>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<script type="text/html" id="tmpl-apparel-service-variation-row">
		<tr class="apparel-service-variation-row">
			<td>
				<input type="hidden" name="apparel_service_variations[{{data.index}}][variation_id]" value="" />
				<input type="text" class="widefat" name="apparel_service_variations[{{data.index}}][name]" value="" />
			</td>
			<td>
				<input type="number" class="small-text" min="0" step="0.01" name="apparel_service_variations[{{data.index}}][price]" value="" />
				<input type="number" class="small-text" min="0" step="1" name="apparel_service_variations[{{data.index}}][price_amount]" value="" />
			</td>
			<td>
				<input type="number" class="small-text" min="0" step="0.01" name="apparel_service_variations[{{data.index}}][sale_price]" value="" />
			</td>
			<td>
				<input type="text" class="small-text" name="apparel_service_variations[{{data.index}}][currency]" value="" />
			</td>
			<td>
				<input type="text" class="widefat" name="apparel_service_variations[{{data.index}}][stripe_product_id]" value="" />
			</td>
			<td>
				<input type="text" class="widefat" name="apparel_service_variations[{{data.index}}][stripe_price_id]" value="" />
			</td>
			<td>
				<input type="url" class="widefat" name="apparel_service_variations[{{data.index}}][stripe_payment_link]" value="" />
			</td>
			<td>
				<label>
					<input type="checkbox" name="apparel_service_variations[{{data.index}}][active]" value="1" />
				</label>
			</td>
			<td>
				<button type="button" class="button-link delete apparel-service-remove-variation"><?php esc_html_e( 'Remove', 'apparel' ); ?></button>
			</td>
		</tr>
	</script>
	<?php
}

/**
 * Save service meta fields.
 *
 * @param int     $post_id Post ID.
 * @param WP_Post $post    Post object.
 */
function apparel_service_save_meta( $post_id, $post ) {
	if ( $post->post_type !== 'service' ) {
		return;
	}

	if ( ! isset( $_POST['apparel_service_meta_nonce'] ) || ! wp_verify_nonce( $_POST['apparel_service_meta_nonce'], 'apparel_service_save_meta' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$service_price = apparel_service_sanitize_price(
		isset( $_POST['apparel_service_price'] ) ? wp_unslash( $_POST['apparel_service_price'] ) : ''
	);
	$service_sale_price = apparel_service_sanitize_price(
		isset( $_POST['apparel_service_sale_price'] ) ? wp_unslash( $_POST['apparel_service_sale_price'] ) : ''
	);

	if ( '' !== $service_price ) {
		update_post_meta( $post_id, '_service_price', $service_price );
	} else {
		delete_post_meta( $post_id, '_service_price' );
	}

	if ( '' !== $service_sale_price && '' !== $service_price && (float) $service_sale_price <= (float) $service_price ) {
		update_post_meta( $post_id, '_service_sale_price', $service_sale_price );
	} else {
		delete_post_meta( $post_id, '_service_sale_price' );
	}

	$checkout_url = isset( $_POST['apparel_service_checkout_url'] ) ? esc_url_raw( wp_unslash( $_POST['apparel_service_checkout_url'] ) ) : '';
	if ( $checkout_url && filter_var( $checkout_url, FILTER_VALIDATE_URL ) ) {
		update_post_meta( $post_id, '_service_checkout_url', $checkout_url );
		apparel_service_maybe_update_payment_link_redirect( $checkout_url );
	} else {
		delete_post_meta( $post_id, '_service_checkout_url' );
	}

	$support_title = isset( $_POST['apparel_service_support_title'] ) ? sanitize_text_field( wp_unslash( $_POST['apparel_service_support_title'] ) ) : '';
	update_post_meta( $post_id, '_service_support_title', $support_title );

	$support_content = isset( $_POST['apparel_service_support_content'] ) ? wp_kses_post( wp_unslash( $_POST['apparel_service_support_content'] ) ) : '';
	update_post_meta( $post_id, '_service_support_content', $support_content );

	$live_preview_url = isset( $_POST['apparel_service_live_preview_url'] ) ? esc_url_raw( wp_unslash( $_POST['apparel_service_live_preview_url'] ) ) : '';
	if ( $live_preview_url ) {
		update_post_meta( $post_id, '_service_live_preview_url', $live_preview_url );
	} else {
		delete_post_meta( $post_id, '_service_live_preview_url' );
	}

	$screenshot_ids = array();
	if ( isset( $_POST['apparel_service_screenshot_ids'] ) ) {
		$raw_ids = explode( ',', sanitize_text_field( wp_unslash( $_POST['apparel_service_screenshot_ids'] ) ) );
		foreach ( $raw_ids as $raw_id ) {
			$raw_id = absint( $raw_id );
			if ( $raw_id ) {
				$screenshot_ids[] = $raw_id;
			}
		}
	}
	update_post_meta( $post_id, '_service_screenshot_ids', $screenshot_ids );

	$variations_input = isset( $_POST['apparel_service_variations'] ) ? (array) wp_unslash( $_POST['apparel_service_variations'] ) : array();
	$existing_variations = get_post_meta( $post_id, '_service_variations', true );
	if ( ! is_array( $existing_variations ) ) {
		$existing_variations = array();
	}

	$variations = apparel_service_sanitize_variations( $variations_input );
	$variations = apparel_service_sync_variations_with_stripe( $variations, $existing_variations, $post_id );

	update_post_meta( $post_id, '_service_variations', $variations );
}
add_action( 'save_post_service', 'apparel_service_save_meta', 10, 2 );

/**
 * Sanitize price values.
 *
 * @param string $value Raw price value.
 * @return string
 */
function apparel_service_sanitize_price( $value ) {
	$value = trim( sanitize_text_field( $value ) );
	if ( '' === $value ) {
		return '';
	}

	if ( ! is_numeric( $value ) ) {
		return '';
	}

	return (string) $value;
}

/**
 * Sanitize variations input.
 *
 * @param array $variations_input Raw variations input.
 * @return array
 */
function apparel_service_sanitize_variations( $variations_input ) {
	$variations = array();
	$index      = 0;
	$default_currency = get_option( 'default_currency', '' );

	foreach ( $variations_input as $variation ) {
		$has_any_value = ! empty( $variation['name'] ) || ! empty( $variation['price'] ) || ! empty( $variation['sale_price'] ) || ! empty( $variation['price_amount'] ) || ! empty( $variation['stripe_product_id'] ) || ! empty( $variation['stripe_price_id'] ) || ! empty( $variation['stripe_payment_link'] );
		if ( ! $has_any_value ) {
			continue;
		}

		$variation_id = isset( $variation['variation_id'] ) ? sanitize_text_field( $variation['variation_id'] ) : '';
		if ( ! $variation_id ) {
			$variation_id = wp_generate_uuid4();
		}

		$price = isset( $variation['price'] ) ? apparel_service_sanitize_price( $variation['price'] ) : '';
		$sale_price = isset( $variation['sale_price'] ) ? apparel_service_sanitize_price( $variation['sale_price'] ) : '';
		if ( '' !== $sale_price && '' !== $price && (float) $sale_price > (float) $price ) {
			$sale_price = '';
		}

		$variations[ $index ] = array(
			'variation_id'        => $variation_id,
			'name'                => isset( $variation['name'] ) ? sanitize_text_field( $variation['name'] ) : '',
			'price'               => $price,
			'sale_price'          => $sale_price,
			'price_amount'        => isset( $variation['price_amount'] ) ? absint( $variation['price_amount'] ) : 0,
			'currency'            => isset( $variation['currency'] ) && $variation['currency'] ? strtolower( sanitize_text_field( $variation['currency'] ) ) : strtolower( sanitize_text_field( $default_currency ) ),
			'stripe_product_id'   => isset( $variation['stripe_product_id'] ) ? sanitize_text_field( $variation['stripe_product_id'] ) : '',
			'stripe_price_id'     => isset( $variation['stripe_price_id'] ) ? sanitize_text_field( $variation['stripe_price_id'] ) : '',
			'stripe_payment_link' => isset( $variation['stripe_payment_link'] ) ? esc_url_raw( $variation['stripe_payment_link'] ) : '',
			'active'              => ! empty( $variation['active'] ) ? 1 : 0,
		);

		$index++;
	}

	return $variations;
}

/**
 * Sync variations with Stripe.
 *
 * @param array $variations          Sanitized variations.
 * @param array $existing_variations Existing variations from DB.
 * @param int   $post_id             Post ID.
 * @return array
 */
function apparel_service_sync_variations_with_stripe( $variations, $existing_variations, $post_id ) {
	$secret_key = get_option( 'stripe_secret_key' );
	if ( ! $secret_key ) {
		return $variations;
	}

	$existing_map = array();
	foreach ( $existing_variations as $existing_variation ) {
		if ( ! empty( $existing_variation['variation_id'] ) ) {
			$existing_map[ $existing_variation['variation_id'] ] = $existing_variation;
		}
	}

	$errors = array();

	foreach ( $variations as $index => $variation ) {
		$existing = isset( $existing_map[ $variation['variation_id'] ] ) ? $existing_map[ $variation['variation_id'] ] : array();
		$variation_failed = false;

		$variation['stripe_product_id']   = $variation['stripe_product_id'] ? $variation['stripe_product_id'] : ( $existing['stripe_product_id'] ?? '' );
		$variation['stripe_price_id']     = $variation['stripe_price_id'] ? $variation['stripe_price_id'] : ( $existing['stripe_price_id'] ?? '' );
		$variation['stripe_payment_link'] = $variation['stripe_payment_link'] ? $variation['stripe_payment_link'] : ( $existing['stripe_payment_link'] ?? '' );

		if ( ! $variation['stripe_product_id'] ) {
			$product_response = apparel_service_stripe_request(
				$secret_key,
				'https://api.stripe.com/v1/products',
				array(
					'name' => $variation['name'],
				)
			);

			if ( $product_response && ! empty( $product_response['id'] ) ) {
				$variation['stripe_product_id'] = sanitize_text_field( $product_response['id'] );
			} else {
				$errors[] = sprintf( __( 'Stripe product creation failed for variation "%s".', 'apparel' ), $variation['name'] );
				$variation_failed = true;
			}
		} elseif ( isset( $existing['name'] ) && $existing['name'] !== $variation['name'] ) {
			$product_update = apparel_service_stripe_request(
				$secret_key,
				sprintf( 'https://api.stripe.com/v1/products/%s', rawurlencode( $variation['stripe_product_id'] ) ),
				array(
					'name' => $variation['name'],
				)
			);

			if ( ! $product_update ) {
				$errors[] = sprintf( __( 'Stripe product update failed for variation "%s".', 'apparel' ), $variation['name'] );
				$variation_failed = true;
			}
		}

		$price_changed = isset( $existing['price_amount'], $existing['currency'] ) &&
			( (int) $existing['price_amount'] !== (int) $variation['price_amount'] || $existing['currency'] !== $variation['currency'] );

		if ( ! $variation['stripe_price_id'] || $price_changed ) {
			if ( $variation['stripe_product_id'] && $variation['price_amount'] && $variation['currency'] ) {
				$price_response = apparel_service_stripe_request(
					$secret_key,
					'https://api.stripe.com/v1/prices',
					array(
						'product'     => $variation['stripe_product_id'],
						'unit_amount' => $variation['price_amount'],
						'currency'    => $variation['currency'],
					)
				);

				if ( $price_response && ! empty( $price_response['id'] ) ) {
					$variation['stripe_price_id'] = sanitize_text_field( $price_response['id'] );
					$variation['stripe_payment_link'] = '';
				} else {
					$errors[] = sprintf( __( 'Stripe price creation failed for variation "%s".', 'apparel' ), $variation['name'] );
					$variation_failed = true;
				}
			}
		}

		if ( $variation['stripe_price_id'] && ! $variation['stripe_payment_link'] ) {
			$thank_you_url = esc_url_raw(
				add_query_arg( 'session_id', '{CHECKOUT_SESSION_ID}', home_url( '/thank-you/' ) )
			);
			$payment_link_response = apparel_service_stripe_request(
				$secret_key,
				'https://api.stripe.com/v1/payment_links',
				array(
					'line_items[0][price]'    => $variation['stripe_price_id'],
					'line_items[0][quantity]' => 1,
					'after_completion[type]'  => 'redirect',
					'after_completion[redirect][url]' => $thank_you_url,
				)
			);

			if ( $payment_link_response && ! empty( $payment_link_response['url'] ) ) {
				$variation['stripe_payment_link'] = esc_url_raw( $payment_link_response['url'] );
			} else {
				$errors[] = sprintf( __( 'Stripe payment link creation failed for variation "%s".', 'apparel' ), $variation['name'] );
				$variation_failed = true;
			}
		} elseif ( $variation['stripe_payment_link'] ) {
			apparel_service_maybe_update_payment_link_redirect( $variation['stripe_payment_link'] );
		}

		if ( $variation_failed && $existing ) {
			if ( ! $variation['stripe_product_id'] ) {
				$variation['stripe_product_id'] = $existing['stripe_product_id'] ?? '';
			}
			if ( ! $variation['stripe_price_id'] ) {
				$variation['stripe_price_id'] = $existing['stripe_price_id'] ?? '';
			}
			if ( ! $variation['stripe_payment_link'] ) {
				$variation['stripe_payment_link'] = $existing['stripe_payment_link'] ?? '';
			}
		}

		$variations[ $index ] = $variation;
	}

	if ( ! empty( $errors ) ) {
		set_transient( 'apparel_service_stripe_errors', $errors, 30 );
		foreach ( $errors as $error_message ) {
			error_log( $error_message );
		}
	}

	return $variations;
}

/**
 * Maybe update a Stripe payment link to redirect after completion.
 *
 * @param string $payment_link_url Stripe payment link URL.
 */
function apparel_service_maybe_update_payment_link_redirect( $payment_link_url ) {
	$secret_key = get_option( 'stripe_secret_key' );
	if ( ! $secret_key || ! $payment_link_url ) {
		return;
	}

	$host = wp_parse_url( $payment_link_url, PHP_URL_HOST );
	if ( ! $host || false === strpos( $host, 'stripe.com' ) ) {
		return;
	}

	$payment_link = apparel_service_find_payment_link_by_url( $secret_key, $payment_link_url );
	if ( ! $payment_link || empty( $payment_link['id'] ) ) {
		return;
	}

	$thank_you_url = esc_url_raw(
		add_query_arg( 'session_id', '{CHECKOUT_SESSION_ID}', home_url( '/thank-you/' ) )
	);

	$after_completion = $payment_link['after_completion'] ?? array();
	$redirect_url     = $after_completion['redirect']['url'] ?? '';
	$type             = $after_completion['type'] ?? '';

	if ( 'redirect' === $type && $redirect_url === $thank_you_url ) {
		return;
	}

	apparel_service_stripe_request(
		$secret_key,
		sprintf( 'https://api.stripe.com/v1/payment_links/%s', rawurlencode( $payment_link['id'] ) ),
		array(
			'after_completion[type]'          => 'redirect',
			'after_completion[redirect][url]' => $thank_you_url,
		)
	);
}

/**
 * Find a Stripe payment link by its URL.
 *
 * @param string $secret_key Stripe secret key.
 * @param string $payment_link_url Stripe payment link URL.
 * @return array|false
 */
function apparel_service_find_payment_link_by_url( $secret_key, $payment_link_url ) {
	$endpoint = 'https://api.stripe.com/v1/payment_links';
	$limit    = 100;
	$attempts = 0;
	$starting_after = '';

	while ( $attempts < 5 ) {
		$query = array(
			'limit' => $limit,
		);
		if ( $starting_after ) {
			$query['starting_after'] = $starting_after;
		}

		$response = apparel_service_stripe_get_request( $secret_key, $endpoint, $query );
		if ( ! $response || empty( $response['data'] ) ) {
			return false;
		}

		foreach ( $response['data'] as $payment_link ) {
			if ( ! empty( $payment_link['url'] ) && $payment_link['url'] === $payment_link_url ) {
				return $payment_link;
			}
		}

		if ( empty( $response['has_more'] ) ) {
			break;
		}

		$last = end( $response['data'] );
		$starting_after = $last['id'] ?? '';
		if ( ! $starting_after ) {
			break;
		}

		$attempts++;
	}

	return false;
}

/**
 * Stripe API helper.
 *
 * @param string $secret_key Stripe secret key.
 * @param string $endpoint   API endpoint.
 * @param array  $body       Request body.
 * @return array|false
 */
function apparel_service_stripe_request( $secret_key, $endpoint, $body ) {
	$response = wp_remote_post(
		$endpoint,
		array(
			'headers' => array(
				'Authorization' => 'Bearer ' . $secret_key,
			),
			'body'    => $body,
			'timeout' => 20,
		)
	);

	if ( is_wp_error( $response ) ) {
		return false;
	}

	$code = wp_remote_retrieve_response_code( $response );
	$body = json_decode( wp_remote_retrieve_body( $response ), true );

	if ( $code < 200 || $code >= 300 ) {
		return false;
	}

	return $body;
}

/**
 * Stripe API GET helper.
 *
 * @param string $secret_key Stripe secret key.
 * @param string $endpoint   API endpoint.
 * @param array  $query      Request query args.
 * @return array|false
 */
function apparel_service_stripe_get_request( $secret_key, $endpoint, $query = array() ) {
	$url = $endpoint;
	if ( ! empty( $query ) ) {
		$url = add_query_arg( $query, $endpoint );
	}

	$response = wp_remote_get(
		$url,
		array(
			'headers' => array(
				'Authorization' => 'Bearer ' . $secret_key,
			),
			'timeout' => 20,
		)
	);

	if ( is_wp_error( $response ) ) {
		return false;
	}

	$code = wp_remote_retrieve_response_code( $response );
	$body = json_decode( wp_remote_retrieve_body( $response ), true );

	if ( $code < 200 || $code >= 300 || ! is_array( $body ) ) {
		return false;
	}

	return $body;
}

/**
 * Restrict service comments to admins.
 *
 * @param array $commentdata Comment data.
 * @return array
 */
function apparel_service_restrict_comments( $commentdata ) {
	$post_id = isset( $commentdata['comment_post_ID'] ) ? (int) $commentdata['comment_post_ID'] : 0;
	if ( $post_id && 'service' === get_post_type( $post_id ) && ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You are not allowed to add comments for services.', 'apparel' ) );
	}

	return $commentdata;
}
add_filter( 'preprocess_comment', 'apparel_service_restrict_comments' );

/**
 * Add Stripe settings page.
 */
function apparel_service_add_stripe_settings_page() {
	add_options_page(
		__( 'Service Stripe Settings', 'apparel' ),
		__( 'Service Stripe', 'apparel' ),
		'manage_options',
		'apparel-service-stripe-settings',
		'apparel_service_render_stripe_settings_page'
	);
}
add_action( 'admin_menu', 'apparel_service_add_stripe_settings_page' );

/**
 * Render Stripe settings page.
 */
function apparel_service_render_stripe_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	if ( isset( $_POST['apparel_service_stripe_settings_nonce'] ) && wp_verify_nonce( $_POST['apparel_service_stripe_settings_nonce'], 'apparel_service_save_stripe_settings' ) ) {
		$secret_key       = isset( $_POST['stripe_secret_key'] ) ? sanitize_text_field( wp_unslash( $_POST['stripe_secret_key'] ) ) : '';
		$webhook_secret   = isset( $_POST['stripe_webhook_secret'] ) ? sanitize_text_field( wp_unslash( $_POST['stripe_webhook_secret'] ) ) : '';
		$default_currency = isset( $_POST['default_currency'] ) ? strtolower( sanitize_text_field( wp_unslash( $_POST['default_currency'] ) ) ) : '';

		update_option( 'stripe_secret_key', $secret_key );
		update_option( 'stripe_webhook_secret', $webhook_secret );
		update_option( 'default_currency', $default_currency );

		echo '<div class="updated"><p>' . esc_html__( 'Stripe settings saved.', 'apparel' ) . '</p></div>';
	}

	$secret_key       = get_option( 'stripe_secret_key', '' );
	$webhook_secret   = get_option( 'stripe_webhook_secret', '' );
	$default_currency = get_option( 'default_currency', '' );
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Service Stripe Settings', 'apparel' ); ?></h1>
		<form method="post">
			<?php wp_nonce_field( 'apparel_service_save_stripe_settings', 'apparel_service_stripe_settings_nonce' ); ?>
			<table class="form-table">
				<tr>
					<th scope="row">
						<label for="stripe_secret_key"><?php esc_html_e( 'Stripe Secret Key', 'apparel' ); ?></label>
					</th>
					<td>
						<input name="stripe_secret_key" id="stripe_secret_key" type="text" class="regular-text" value="<?php echo esc_attr( $secret_key ); ?>" />
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="stripe_webhook_secret"><?php esc_html_e( 'Stripe Webhook Secret', 'apparel' ); ?></label>
					</th>
					<td>
						<input name="stripe_webhook_secret" id="stripe_webhook_secret" type="text" class="regular-text" value="<?php echo esc_attr( $webhook_secret ); ?>" />
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="default_currency"><?php esc_html_e( 'Default Currency', 'apparel' ); ?></label>
					</th>
					<td>
						<input name="default_currency" id="default_currency" type="text" class="regular-text" value="<?php echo esc_attr( $default_currency ); ?>" />
					</td>
				</tr>
			</table>
			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}

/**
 * Admin notices for Stripe errors.
 */
function apparel_service_stripe_admin_notices() {
	$errors = get_transient( 'apparel_service_stripe_errors' );
	if ( ! $errors ) {
		return;
	}
	delete_transient( 'apparel_service_stripe_errors' );
	?>
	<div class="notice notice-error">
		<p><?php echo esc_html( implode( ' ', $errors ) ); ?></p>
	</div>
	<?php
}
add_action( 'admin_notices', 'apparel_service_stripe_admin_notices' );

/**
 * Enqueue admin scripts for service metaboxes.
 *
 * @param string $hook Current admin page.
 */
function apparel_service_admin_enqueue_scripts( $hook ) {
	$screen = get_current_screen();
	if ( ! $screen || 'service' !== $screen->post_type ) {
		return;
	}

	if ( 'post.php' !== $hook && 'post-new.php' !== $hook ) {
		return;
	}

	wp_enqueue_media();
	wp_enqueue_script( 'jquery-ui-sortable' );
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'wp-util' );

	ob_start();
	?>
	(function($){
		$(function(){
		function updateScreenshotInput(container) {
			var ids = [];
			container.find('.apparel-service-screenshot-item').each(function(){
				ids.push($(this).data('attachment-id'));
			});
			container.find('.apparel-service-screenshot-ids').val(ids.join(','));
		}

		$(document).on('click', '.apparel-service-add-screenshot', function(e){
			e.preventDefault();
			var container = $(this).closest('.apparel-service-screenshots');
			var frame = wp.media({
				title: '<?php echo esc_js( __( 'Select Screenshots', 'apparel' ) ); ?>',
				button: { text: '<?php echo esc_js( __( 'Use selected', 'apparel' ) ); ?>' },
				multiple: true
			});

			frame.on('select', function(){
				var selection = frame.state().get('selection');
				selection.each(function(attachment){
					var data = attachment.toJSON();
					var thumbnail = data.sizes && data.sizes.thumbnail ? data.sizes.thumbnail.url : data.url;
					var item = $('<li class="apparel-service-screenshot-item" data-attachment-id="' + data.id + '"></li>');
					item.append('<img class="apparel-service-screenshot-image" src="' + thumbnail + '" />');
					item.append('<button type="button" class="button-link delete apparel-service-remove-screenshot"><?php echo esc_js( __( 'Remove', 'apparel' ) ); ?></button>');
					container.find('.apparel-service-screenshot-list').append(item);
				});
				updateScreenshotInput(container);
			});

			frame.open();
		});

		$(document).on('click', '.apparel-service-remove-screenshot', function(e){
			e.preventDefault();
			var container = $(this).closest('.apparel-service-screenshots');
			$(this).closest('.apparel-service-screenshot-item').remove();
			updateScreenshotInput(container);
		});

		$('.apparel-service-screenshot-list').sortable({
			update: function(){
				updateScreenshotInput($(this).closest('.apparel-service-screenshots'));
			}
		});

		$(document).on('click', '.apparel-service-add-variation', function(e){
			e.preventDefault();
			var table = $(this).closest('.apparel-service-variations').find('.apparel-service-variations-table tbody');
			var index = table.find('tr').length;
			var template = wp.template('apparel-service-variation-row');
			table.append(template({ index: index }));
		});

		$(document).on('click', '.apparel-service-remove-variation', function(e){
			e.preventDefault();
			$(this).closest('.apparel-service-variation-row').remove();
		});
		});
	})(jQuery);
	<?php
	wp_add_inline_script( 'jquery', str_replace( array( '<script>', '</script>' ), '', ob_get_clean() ) );
}
add_action( 'admin_enqueue_scripts', 'apparel_service_admin_enqueue_scripts' );
