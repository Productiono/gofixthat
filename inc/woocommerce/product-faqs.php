<?php
/**
 * WooCommerce product FAQs.
 *
 * @package Apparel
 */

/**
 * Register the FAQs tab within the product data metabox.
 *
 * @param array $tabs Existing product data tabs.
 *
 * @return array
 */
function mbf_wc_product_faqs_tab( $tabs ) {
	$tabs['mbf_product_faqs'] = array(
		'label'    => esc_html__( 'FAQs', 'apparel' ),
		'target'   => 'mbf_product_faqs_data',
		'class'    => array(),
		'priority' => 80,
	);

	return $tabs;
}
add_filter( 'woocommerce_product_data_tabs', 'mbf_wc_product_faqs_tab' );

/**
 * Output the FAQs panel fields.
 */
function mbf_wc_product_faqs_panel() {
	global $post;

	$faqs = get_post_meta( $post->ID, '_mbf_product_faqs', true );

	if ( ! is_array( $faqs ) ) {
		$faqs = array();
	}
	?>
	<div id="mbf_product_faqs_data" class="panel woocommerce_options_panel">
		<?php wp_nonce_field( 'mbf_save_product_faqs', 'mbf_product_faqs_nonce' ); ?>
		<div class="options_group">
			<p class="form-field mbf-product-faqs-description">
				<span class="description">
					<?php esc_html_e( 'Add common questions and answers customers ask about this product. Drag and drop to reorder.', 'apparel' ); ?>
				</span>
			</p>
		</div>

		<?php
		$faq_list_classes = 'mbf-product-faqs-list';

		if ( empty( $faqs ) ) {
			$faq_list_classes .= ' is-empty';
		}
		?>
		<div class="<?php echo esc_attr( $faq_list_classes ); ?>" data-empty-label="<?php esc_attr_e( 'No FAQs added yet. Use the button below to create your first FAQ.', 'apparel' ); ?>">
			<?php
			foreach ( $faqs as $index => $faq ) {
				$question = isset( $faq['question'] ) ? $faq['question'] : '';
				$answer   = isset( $faq['answer'] ) ? $faq['answer'] : '';
				?>
				<div class="mbf-product-faq-row" data-index="<?php echo esc_attr( $index ); ?>">
					<button type="button" class="mbf-product-faq-row-handle" aria-label="<?php esc_attr_e( 'Reorder FAQ', 'apparel' ); ?>">
						<span aria-hidden="true">⋮⋮</span>
					</button>
					<div class="mbf-product-faq-row-fields">
						<p class="form-field">
							<label for="mbf-product-faq-question-<?php echo esc_attr( $index ); ?>">
								<?php esc_html_e( 'Question', 'apparel' ); ?>
							</label>
							<input
								type="text"
								class="short"
								name="mbf_product_faqs[<?php echo esc_attr( $index ); ?>][question]"
								id="mbf-product-faq-question-<?php echo esc_attr( $index ); ?>"
								value="<?php echo esc_attr( $question ); ?>"
								placeholder="<?php esc_attr_e( 'e.g. What materials is this made from?', 'apparel' ); ?>"
							/>
						</p>
						<p class="form-field">
							<label for="mbf-product-faq-answer-<?php echo esc_attr( $index ); ?>">
								<?php esc_html_e( 'Answer', 'apparel' ); ?>
							</label>
							<textarea
								class="short"
								name="mbf_product_faqs[<?php echo esc_attr( $index ); ?>][answer]"
								id="mbf-product-faq-answer-<?php echo esc_attr( $index ); ?>"
								rows="4"
								placeholder="<?php esc_attr_e( 'Provide a concise, helpful answer for shoppers.', 'apparel' ); ?>"
							><?php echo esc_textarea( $answer ); ?></textarea>
						</p>
					</div>
					<button type="button" class="button-link-delete mbf-remove-product-faq">
						<?php esc_html_e( 'Remove', 'apparel' ); ?>
					</button>
				</div>
				<?php
			}
			?>
		</div>

		<script type="text/html" id="tmpl-mbf-product-faq-row">
			<div class="mbf-product-faq-row" data-index="{{data.index}}">
				<button type="button" class="mbf-product-faq-row-handle" aria-label="<?php esc_attr_e( 'Reorder FAQ', 'apparel' ); ?>">
					<span aria-hidden="true">⋮⋮</span>
				</button>
				<div class="mbf-product-faq-row-fields">
					<p class="form-field">
						<label for="mbf-product-faq-question-{{data.index}}"><?php esc_html_e( 'Question', 'apparel' ); ?></label>
						<input type="text" class="short" name="mbf_product_faqs[{{data.index}}][question]" id="mbf-product-faq-question-{{data.index}}" placeholder="<?php esc_attr_e( 'e.g. What materials is this made from?', 'apparel' ); ?>" />
					</p>
					<p class="form-field">
						<label for="mbf-product-faq-answer-{{data.index}}"><?php esc_html_e( 'Answer', 'apparel' ); ?></label>
						<textarea class="short" name="mbf_product_faqs[{{data.index}}][answer]" id="mbf-product-faq-answer-{{data.index}}" rows="4" placeholder="<?php esc_attr_e( 'Provide a concise, helpful answer for shoppers.', 'apparel' ); ?>"></textarea>
					</p>
				</div>
				<button type="button" class="button-link-delete mbf-remove-product-faq">
					<?php esc_html_e( 'Remove', 'apparel' ); ?>
				</button>
			</div>
		</script>

		<p class="toolbar">
			<button type="button" class="button button-primary mbf-add-product-faq">
				<?php esc_html_e( 'Add FAQ', 'apparel' ); ?>
			</button>
		</p>
	</div>
	<?php
}
add_action( 'woocommerce_product_data_panels', 'mbf_wc_product_faqs_panel' );

/**
 * Save FAQs when the product is saved.
 *
 * @param WC_Product $product Product object being saved.
 */
function mbf_wc_save_product_faqs( $product ) {
	if ( ! isset( $_POST['mbf_product_faqs_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['mbf_product_faqs_nonce'] ) ), 'mbf_save_product_faqs' ) ) {
		return;
	}

	if ( empty( $_POST['mbf_product_faqs'] ) || ! is_array( $_POST['mbf_product_faqs'] ) ) {
		$product->delete_meta_data( '_mbf_product_faqs' );
		return;
	}

	$faqs = array();

	foreach ( (array) wp_unslash( $_POST['mbf_product_faqs'] ) as $faq ) {
		if ( empty( $faq['question'] ) && empty( $faq['answer'] ) ) {
			continue;
		}

		$faqs[] = array(
			'question' => isset( $faq['question'] ) ? sanitize_text_field( $faq['question'] ) : '',
			'answer'   => isset( $faq['answer'] ) ? wp_kses_post( $faq['answer'] ) : '',
		);
	}

	if ( empty( $faqs ) ) {
		$product->delete_meta_data( '_mbf_product_faqs' );
		return;
	}

	$product->update_meta_data( '_mbf_product_faqs', $faqs );
}
add_action( 'woocommerce_admin_process_product_object', 'mbf_wc_save_product_faqs' );

/**
 * Enqueue admin scripts for FAQ management.
 *
 * @param string $hook Current admin page hook.
 */
function mbf_wc_product_faqs_admin_assets( $hook ) {
	$screen = get_current_screen();

	if ( ! $screen || 'product' !== $screen->id ) {
		return;
	}

	$theme   = wp_get_theme();
	$version = $theme->get( 'Version' );

	wp_enqueue_style(
		'mbf-wc-product-faqs',
		get_template_directory_uri() . '/assets/css/admin-product-faqs.css',
		array( 'woocommerce_admin_styles' ),
		$version
	);

	wp_enqueue_script(
		'mbf-wc-product-faqs',
		get_template_directory_uri() . '/assets/js/admin-product-faqs.js',
		array( 'jquery', 'jquery-ui-sortable', 'wp-util' ),
		$version,
		true
	);
}
add_action( 'admin_enqueue_scripts', 'mbf_wc_product_faqs_admin_assets' );

/**
 * Get sanitized FAQs for a product.
 *
 * @param int $product_id Product ID.
 *
 * @return array
 */
function mbf_wc_get_product_faqs( $product_id = 0 ) {
	$product_id = $product_id ? absint( $product_id ) : get_the_ID();

	if ( ! $product_id ) {
		return array();
	}

	$faqs = get_post_meta( $product_id, '_mbf_product_faqs', true );

	if ( ! is_array( $faqs ) ) {
		return array();
	}

	$prepared_faqs = array();

	foreach ( $faqs as $faq ) {
		$question = isset( $faq['question'] ) ? sanitize_text_field( wp_unslash( $faq['question'] ) ) : '';
		$answer   = isset( $faq['answer'] ) ? wp_kses_post( $faq['answer'] ) : '';

		if ( '' === $question && '' === $answer ) {
			continue;
		}

		$prepared_faqs[] = array(
			'question' => $question,
			'answer'   => $answer,
		);
	}

	return $prepared_faqs;
}

/**
 * Render FAQs on the single product page.
 */
function mbf_wc_render_product_faqs() {
	if ( ! is_product() ) {
		return;
	}

	$product_id = get_the_ID();
	$faqs       = mbf_wc_get_product_faqs( $product_id );

	if ( empty( $faqs ) ) {
		return;
	}
	?>
	<section class="mbf-product-faqs" aria-label="<?php esc_attr_e( 'Product frequently asked questions', 'apparel' ); ?>">
		<div class="mbf-product-faqs__header">
			<h2 class="mbf-product-faqs__title"><?php esc_html_e( 'Frequently Asked Questions', 'apparel' ); ?></h2>
			<p class="mbf-product-faqs__subtitle">
				<?php esc_html_e( 'Find quick answers about this product.', 'apparel' ); ?>
			</p>
		</div>
		<div class="mbf-product-faqs__items">
			<?php foreach ( $faqs as $index => $faq ) : ?>
				<details class="mbf-product-faqs__item" <?php echo 0 === $index ? ' open' : ''; ?>>
					<summary class="mbf-product-faqs__question">
						<span class="mbf-product-faqs__question-text"><?php echo esc_html( $faq['question'] ); ?></span>
						<span class="mbf-product-faqs__toggle" aria-hidden="true">
							<svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" role="presentation">
								<path d="M12 5v14m-7-7h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
							</svg>
						</span>
					</summary>
					<div class="mbf-product-faqs__answer">
						<?php echo wp_kses_post( wpautop( $faq['answer'] ) ); ?>
					</div>
				</details>
			<?php endforeach; ?>
		</div>
	</section>
	<?php
}
