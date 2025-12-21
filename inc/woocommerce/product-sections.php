<?php
/**
 * WooCommerce product sections.
 *
 * @package Apparel
 */

/**
 * Retrieve sanitized array meta for a product.
 *
 * @param int    $product_id Product ID.
 * @param string $key        Meta key.
 *
 * @return array
 */
function mbf_wc_get_product_array_meta( $product_id, $key ) {
	$values = get_post_meta( $product_id, $key, true );

	if ( ! is_array( $values ) ) {
		return array();
	}

	return array_values(
		array_filter(
			array_map(
				static function ( $value ) {
					if ( is_array( $value ) ) {
						$question = isset( $value['question'] ) ? sanitize_text_field( $value['question'] ) : '';
						$answer   = isset( $value['answer'] ) ? wp_kses_post( $value['answer'] ) : '';

						if ( '' === $question && '' === $answer ) {
							return null;
						}

						return array(
							'question' => $question,
							'answer'   => $answer,
						);
					}

					return sanitize_text_field( $value );
				},
				$values
			),
			static function ( $value ) {
				return '' !== $value && ! is_null( $value );
			}
		)
	);
}

/**
 * Sanitize a simple repeatable text field list.
 *
 * @param array $values Raw values from $_POST.
 *
 * @return array
 */
function mbf_wc_sanitize_repeatable_text_values( $values ) {
	if ( empty( $values ) || ! is_array( $values ) ) {
		return array();
	}

	return array_values(
		array_filter(
			array_map(
				static function ( $value ) {
					$value = is_string( $value ) ? sanitize_text_field( wp_unslash( $value ) ) : '';

					return '' !== $value ? $value : null;
				},
				$values
			),
			static function ( $value ) {
				return '' !== $value && ! is_null( $value );
			}
		)
	);
}

/**
 * Check whether the product sections save routine should run.
 *
 * @param int $product_id Product ID.
 *
 * @return bool
 */
function mbf_wc_should_save_product_sections( $product_id ) {
	if ( ! isset( $_POST['mbf_product_sections_nonce'] ) ) {
		return false;
	}

	$nonce = sanitize_text_field( wp_unslash( $_POST['mbf_product_sections_nonce'] ) );

	if ( ! wp_verify_nonce( $nonce, 'mbf_save_product_sections' ) ) {
		return false;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return false;
	}

	if ( wp_is_post_revision( $product_id ) ) {
		return false;
	}

	return current_user_can( 'edit_product', $product_id );
}

/**
 * Add custom tab for service details.
 *
 * @param array $tabs Tabs.
 *
 * @return array
 */
function mbf_wc_register_product_sections_tab( $tabs ) {
	$tabs['mbf_product_sections'] = array(
		'label'    => esc_html__( 'Service Details', 'apparel' ),
		'target'   => 'mbf_product_sections_data',
		'class'    => array(
			'show_if_simple',
			'show_if_variable',
			'show_if_grouped',
			'show_if_external',
		),
		'priority' => 55,
	);

	return $tabs;
}
add_filter( 'woocommerce_product_data_tabs', 'mbf_wc_register_product_sections_tab' );

/**
 * Render product sections panel.
 */
function mbf_wc_render_product_sections_panel() {
	global $post;

	$product_id = $post->ID;

	$what_we_fix = mbf_wc_get_product_array_meta( $product_id, '_mbf_product_what_we_fix' );
	$what_you_get = mbf_wc_get_product_array_meta( $product_id, '_mbf_product_what_you_get' );
	$faqs         = mbf_wc_get_product_array_meta( $product_id, '_mbf_product_faqs' );
	?>
	<div id="mbf_product_sections_data" class="panel woocommerce_options_panel">
		<?php wp_nonce_field( 'mbf_save_product_sections', 'mbf_product_sections_nonce' ); ?>
		<div class="mbf-product-sections-admin">
			<p class="form-field">
				<strong><?php esc_html_e( 'Guide your buyers with concise details.', 'apparel' ); ?></strong>
				<br />
				<?php esc_html_e( 'Use these lists to outline what is fixed, what is delivered, and to answer common questions. This does not replace the default product description.', 'apparel' ); ?>
			</p>

			<div class="mbf-repeatable-group" data-template="mbf-what-we-fix-template">
				<label class="mbf-repeatable-label" for="mbf-what-we-fix"><?php esc_html_e( 'What We Fix in This Order', 'apparel' ); ?></label>
				<div class="mbf-repeatable" id="mbf-what-we-fix">
					<?php
					if ( ! empty( $what_we_fix ) ) :
						foreach ( $what_we_fix as $value ) :
							?>
							<div class="mbf-repeatable-row">
								<input type="text" name="mbf_product_what_we_fix[]" value="<?php echo esc_attr( $value ); ?>" placeholder="<?php esc_attr_e( 'Add a quick bullet', 'apparel' ); ?>" />
								<button type="button" class="button-link-delete mbf-remove-row"><?php esc_html_e( 'Remove', 'apparel' ); ?></button>
							</div>
							<?php
						endforeach;
					endif;
					?>
				</div>
				<button type="button" class="button mbf-add-row" data-template="mbf-what-we-fix-template"><?php esc_html_e( 'Add bullet', 'apparel' ); ?></button>
			</div>

			<div class="mbf-repeatable-group" data-template="mbf-what-you-get-template">
				<label class="mbf-repeatable-label" for="mbf-what-you-get"><?php esc_html_e( 'What You Get', 'apparel' ); ?></label>
				<div class="mbf-repeatable" id="mbf-what-you-get">
					<?php
					if ( ! empty( $what_you_get ) ) :
						foreach ( $what_you_get as $value ) :
							?>
							<div class="mbf-repeatable-row">
								<input type="text" name="mbf_product_what_you_get[]" value="<?php echo esc_attr( $value ); ?>" placeholder="<?php esc_attr_e( 'Add a quick bullet', 'apparel' ); ?>" />
								<button type="button" class="button-link-delete mbf-remove-row"><?php esc_html_e( 'Remove', 'apparel' ); ?></button>
							</div>
							<?php
						endforeach;
					endif;
					?>
				</div>
				<button type="button" class="button mbf-add-row" data-template="mbf-what-you-get-template"><?php esc_html_e( 'Add bullet', 'apparel' ); ?></button>
			</div>

			<div class="mbf-repeatable-group" data-template="mbf-faq-template">
				<label class="mbf-repeatable-label" for="mbf-faqs"><?php esc_html_e( 'FAQs', 'apparel' ); ?></label>
				<div class="mbf-repeatable mbf-repeatable-faq" id="mbf-faqs" data-next-index="<?php echo esc_attr( count( $faqs ) ); ?>">
					<?php
					if ( ! empty( $faqs ) ) :
						foreach ( $faqs as $index => $faq ) :
							$question = isset( $faq['question'] ) ? $faq['question'] : '';
							$answer   = isset( $faq['answer'] ) ? $faq['answer'] : '';
							?>
							<div class="mbf-repeatable-row" data-index="<?php echo esc_attr( $index ); ?>">
								<input type="text" name="mbf_product_faqs[<?php echo esc_attr( $index ); ?>][question]" value="<?php echo esc_attr( $question ); ?>" placeholder="<?php esc_attr_e( 'Question', 'apparel' ); ?>" />
								<textarea name="mbf_product_faqs[<?php echo esc_attr( $index ); ?>][answer]" rows="3" placeholder="<?php esc_attr_e( 'Answer', 'apparel' ); ?>"><?php echo esc_textarea( $answer ); ?></textarea>
								<button type="button" class="button-link-delete mbf-remove-row"><?php esc_html_e( 'Remove', 'apparel' ); ?></button>
							</div>
							<?php
						endforeach;
					endif;
					?>
				</div>
				<button type="button" class="button mbf-add-row" data-template="mbf-faq-template"><?php esc_html_e( 'Add FAQ', 'apparel' ); ?></button>
			</div>
		</div>

		<script type="text/html" id="mbf-what-we-fix-template">
			<div class="mbf-repeatable-row">
				<input type="text" name="mbf_product_what_we_fix[]" placeholder="<?php esc_attr_e( 'Add a quick bullet', 'apparel' ); ?>" />
				<button type="button" class="button-link-delete mbf-remove-row"><?php esc_html_e( 'Remove', 'apparel' ); ?></button>
			</div>
		</script>

		<script type="text/html" id="mbf-what-you-get-template">
			<div class="mbf-repeatable-row">
				<input type="text" name="mbf_product_what_you_get[]" placeholder="<?php esc_attr_e( 'Add a quick bullet', 'apparel' ); ?>" />
				<button type="button" class="button-link-delete mbf-remove-row"><?php esc_html_e( 'Remove', 'apparel' ); ?></button>
			</div>
		</script>

		<script type="text/html" id="mbf-faq-template">
			<div class="mbf-repeatable-row" data-index="__index__">
				<input type="text" name="mbf_product_faqs[__index__][question]" placeholder="<?php esc_attr_e( 'Question', 'apparel' ); ?>" />
				<textarea name="mbf_product_faqs[__index__][answer]" rows="3" placeholder="<?php esc_attr_e( 'Answer', 'apparel' ); ?>"></textarea>
				<button type="button" class="button-link-delete mbf-remove-row"><?php esc_html_e( 'Remove', 'apparel' ); ?></button>
			</div>
		</script>
	</div>
	<?php
}
add_action( 'woocommerce_product_data_panels', 'mbf_wc_render_product_sections_panel' );

/**
 * Save product section data.
 *
 * @param WC_Product $product Product object.
 */
function mbf_wc_save_product_sections( $product ) {
	$product_id = $product->get_id();

	if ( ! mbf_wc_should_save_product_sections( $product_id ) ) {
		return;
	}

	$what_we_fix = isset( $_POST['mbf_product_what_we_fix'] ) ? mbf_wc_sanitize_repeatable_text_values( $_POST['mbf_product_what_we_fix'] ) : array();
	$what_you_get = isset( $_POST['mbf_product_what_you_get'] ) ? mbf_wc_sanitize_repeatable_text_values( $_POST['mbf_product_what_you_get'] ) : array();

	$faqs = array();

	if ( isset( $_POST['mbf_product_faqs'] ) && is_array( $_POST['mbf_product_faqs'] ) ) {
		foreach ( $_POST['mbf_product_faqs'] as $faq ) {
			if ( ! is_array( $faq ) ) {
				continue;
			}

			$question = isset( $faq['question'] ) ? sanitize_text_field( wp_unslash( $faq['question'] ) ) : '';
			$answer   = isset( $faq['answer'] ) ? wp_kses_post( wp_unslash( $faq['answer'] ) ) : '';

			if ( '' === $question && '' === $answer ) {
				continue;
			}

			$faqs[] = array(
				'question' => $question,
				'answer'   => $answer,
			);
		}
	}

	$product->update_meta_data( '_mbf_product_what_we_fix', $what_we_fix );
	$product->update_meta_data( '_mbf_product_what_you_get', $what_you_get );
	$product->update_meta_data( '_mbf_product_faqs', $faqs );
}
add_action( 'woocommerce_admin_process_product_object', 'mbf_wc_save_product_sections' );

/**
 * Enqueue admin assets for product sections.
 *
 * @param string $hook Screen hook.
 */
function mbf_wc_enqueue_product_sections_admin_assets( $hook ) {
	if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
		return;
	}

	if ( 'product' !== get_post_type() ) {
		return;
	}

	$theme   = wp_get_theme();
	$version = $theme->get( 'Version' );

	wp_enqueue_style( 'mbf-product-sections-admin', get_template_directory_uri() . '/assets/css/admin-product-sections.css', array(), $version );
	wp_enqueue_script( 'mbf-product-sections-admin', get_template_directory_uri() . '/assets/js/admin-product-sections.js', array( 'jquery' ), $version, true );
}
add_action( 'admin_enqueue_scripts', 'mbf_wc_enqueue_product_sections_admin_assets' );

/**
 * Render product sections on the frontend.
 */
function mbf_wc_render_product_sections() {
	if ( ! is_product() ) {
		return;
	}

	global $product;

	if ( ! $product instanceof WC_Product ) {
		return;
	}

	$product_id = $product->get_id();

	$what_we_fix  = mbf_wc_get_product_array_meta( $product_id, '_mbf_product_what_we_fix' );
	$what_you_get = mbf_wc_get_product_array_meta( $product_id, '_mbf_product_what_you_get' );
	$faqs         = mbf_wc_get_product_array_meta( $product_id, '_mbf_product_faqs' );

	if ( empty( $what_we_fix ) && empty( $what_you_get ) && empty( $faqs ) ) {
		return;
	}

	?>
	<section class="mbf-product-detail-sections" aria-label="<?php esc_attr_e( 'Service overview', 'apparel' ); ?>">
		<div class="mbf-product-detail-grid">
			<?php if ( ! empty( $what_we_fix ) ) : ?>
				<div class="mbf-product-detail-card">
					<h2 class="mbf-product-detail-title"><?php esc_html_e( 'What We Fix in This Order', 'apparel' ); ?></h2>
					<ul class="mbf-product-detail-list">
						<?php foreach ( $what_we_fix as $item ) : ?>
							<li><?php echo esc_html( $item ); ?></li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $what_you_get ) ) : ?>
				<div class="mbf-product-detail-card">
					<h2 class="mbf-product-detail-title"><?php esc_html_e( 'What You Get', 'apparel' ); ?></h2>
					<ul class="mbf-product-detail-list">
						<?php foreach ( $what_you_get as $item ) : ?>
							<li><?php echo esc_html( $item ); ?></li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>
		</div>

		<?php if ( ! empty( $faqs ) ) : ?>
			<div class="mbf-product-faqs">
				<h2 class="mbf-product-detail-title"><?php esc_html_e( 'FAQs', 'apparel' ); ?></h2>
				<div class="mbf-product-faqs-list" role="list">
					<?php foreach ( $faqs as $index => $faq ) : ?>
						<?php
							$question = isset( $faq['question'] ) ? $faq['question'] : '';
							$answer   = isset( $faq['answer'] ) ? $faq['answer'] : '';

							if ( '' === $question && '' === $answer ) {
								continue;
							}

							$control_id = 'mbf-faq-answer-' . ( $index + 1 );
						?>
						<div class="mbf-product-faq" role="listitem">
							<button class="mbf-faq-toggle" type="button" aria-expanded="false" aria-controls="<?php echo esc_attr( $control_id ); ?>">
								<span class="mbf-faq-question"><?php echo esc_html( $question ); ?></span>
								<span class="mbf-faq-icon" aria-hidden="true"></span>
							</button>
							<div class="mbf-faq-answer" id="<?php echo esc_attr( $control_id ); ?>" hidden>
								<?php echo wp_kses_post( wpautop( $answer ) ); ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>
	</section>
	<?php
}
add_action( 'woocommerce_after_single_product_summary', 'mbf_wc_render_product_sections', 40 );

/**
 * Add accordion behaviour.
 */
function mbf_wc_enqueue_product_sections_script() {
	if ( ! is_product() ) {
		return;
	}

	$theme   = wp_get_theme();
	$version = $theme->get( 'Version' );

	wp_enqueue_script(
		'mbf-product-sections',
		get_template_directory_uri() . '/assets/js/product-sections.js',
		array( 'jquery' ),
		$version,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'mbf_wc_enqueue_product_sections_script', 30 );
