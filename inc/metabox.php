<?php
/**
 * Adding Custom Meta Boxes.
 *
 * @package Apparel
 */


/**
 * Add fields to Category
 *
 * @param string $taxonomy The taxonomy slug.
 */
function mbf_mb_product_cat_options_add( $taxonomy ) {
	wp_nonce_field( 'product_cat_options', 'mbf_mb_product_cat_options' );
	?>
		<div class="form-field">
			<label><?php esc_html_e( 'Hero Image', 'apparel' ); ?></label>
			<div class="category-upload-image upload-img-container" data-frame-title="<?php esc_html_e( 'Select or upload image', 'apparel' ); ?>" data-frame-btn-text="<?php esc_html_e( 'Set image', 'apparel' ); ?>">
				<p class="uploaded-img-box">
					<span class="uploaded-image"></span>
					<input id="mbf_hero_image" class="uploaded-img-id" name="mbf_hero_image" type="hidden"/>
				</p>
				<p class="hide-if-no-js">
					<a class="upload-img-link button button-primary" href="#"><?php esc_html_e( 'Upload image', 'apparel' ); ?></a>
					<a class="delete-img-link button button-secondary hidden" href="#"><?php esc_html_e( 'Remove image', 'apparel' ); ?></a>
				</p>
			</div>
		</div>
		<br><br>
	<?php
}
add_action( 'product_cat_add_form_fields', 'mbf_mb_product_cat_options_add', 10 );

/**
 * Edit fields from Category
 *
 * @param object $term     Current taxonomy term object.
 * @param string $taxonomy Current taxonomy slug.
 */
function mbf_mb_product_cat_options_edit( $term, $taxonomy ) {
	wp_nonce_field( 'product_cat_options', 'mbf_mb_product_cat_options' );

	$mbf_hero_image = get_term_meta( $term->term_id, 'mbf_hero_image', true );

	$mbf_hero_image_url = wp_get_attachment_image_url( $mbf_hero_image, 'large' );
	?>

	<tr class="form-field">
		<th scope="row" valign="top"><label for="mbf_hero_image"><?php esc_html_e( 'Hero Image', 'apparel' ); ?></label></th>
		<td>
			<div class="category-upload-image upload-img-container" data-frame-title="<?php esc_html_e( 'Select or upload image', 'apparel' ); ?>" data-frame-btn-text="<?php esc_html_e( 'Set image', 'apparel' ); ?>">
				<p class="uploaded-img-box">
					<span class="uploaded-image">
						<?php if ( $mbf_hero_image_url ) : ?>
							<img src="<?php echo esc_url( $mbf_hero_image_url ); ?>" style="max-width:100%;" />
						<?php endif; ?>
					</span>

					<input id="mbf_hero_image" class="uploaded-img-id" name="mbf_hero_image" type="hidden" value="<?php echo esc_attr( $mbf_hero_image ); ?>" />
				</p>
				<p class="hide-if-no-js">
					<a class="upload-img-link button button-primary <?php echo esc_attr( $mbf_hero_image_url ? 'hidden' : '' ); ?>" href="#"><?php esc_html_e( 'Upload image', 'apparel' ); ?></a>
					<a class="delete-img-link button button-secondary <?php echo esc_attr( ! $mbf_hero_image_url ? 'hidden' : '' ); ?>" href="#"><?php esc_html_e( 'Remove image', 'apparel' ); ?></a>
				</p>
			</div>
		</td>
	</tr>
	<?php
}
add_action( 'product_cat_edit_form_fields', 'mbf_mb_product_cat_options_edit', 10, 2 );

/**
 * Save meta box
 *
 * @param int    $term_id  ID of the term about to be edited.
 * @param string $taxonomy Taxonomy slug of the related term.
 */
function mbf_mb_product_cat_options_save( $term_id, $taxonomy ) {

	if ( ! isset( $_POST['mbf_mb_product_cat_options'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['mbf_mb_product_cat_options'] ) ), 'product_cat_options' ) ) {
		return;
	}

	if ( ! current_user_can( 'manage_product_terms' ) ) {
		return;
	}

	if ( isset( $_POST['mbf_hero_image'] ) ) {
		$mbf_hero_image = absint( wp_unslash( $_POST['mbf_hero_image'] ) );

		update_term_meta( $term_id, 'mbf_hero_image', $mbf_hero_image );
	}
}
add_action( 'created_product_cat', 'mbf_mb_product_cat_options_save', 10, 2 );
add_action( 'edited_product_cat', 'mbf_mb_product_cat_options_save', 10, 2 );

/**
 * Meta box Enqunue Scripts
 *
 * @param string $page Current page.
 */
function mbf_mb_product_cat_enqueue_scripts( $page ) {
	$screen = get_current_screen();

	if ( null !== $screen && 'edit-product_cat' !== $screen->id ) {
		return;
	}

	wp_enqueue_script( 'jquery' );

	// Init Media Control.
	wp_enqueue_media();

	ob_start();
	?>
	<script>
	jQuery( document ).ready(function( $ ) {

		var powerkitMediaFrame;
		/* Set all variables to be used in scope */
		var metaBox = '.category-upload-image';

		/* Add Image Link */
		$( metaBox ).find( '.upload-img-link' ).on( 'click', function( event ){
			event.preventDefault();

			var parentContainer = $( this ).parents( metaBox );

			// Options.
			var options = {
				title: parentContainer.data( 'frame-title' ) ? parentContainer.data( 'frame-title' ) : 'Select or Upload Media',
				button: {
					text: parentContainer.data( 'frame-btn-text' ) ? parentContainer.data( 'frame-btn-text' ) : 'Use this media',
				},
				library : { type : 'image' },
				multiple: false // Set to true to allow multiple files to be selected.
			};

			// Create a new media frame
			powerkitMediaFrame = wp.media( options );

			// When an image is selected in the media frame...
			powerkitMediaFrame.on( 'select', function() {

				// Get media attachment details from the frame state.
				var attachment = powerkitMediaFrame.state().get('selection').first().toJSON();

				// Send the attachment URL to our custom image input field.
				parentContainer.find( '.uploaded-image' ).html( '<img src="' + attachment.url + '" style="max-width:100%;"/>' );
				parentContainer.find( '.uploaded-img-id' ).val( attachment.id ).change();
				parentContainer.find( '.upload-img-link' ).addClass( 'hidden' );
				parentContainer.find( '.delete-img-link' ).removeClass( 'hidden' );

				powerkitMediaFrame.close();
			});

			// Finally, open the modal on click.
			powerkitMediaFrame.open();
		});


		/* Delete Image Link */
		$( metaBox ).find( '.delete-img-link' ).on( 'click', function( event ){
			event.preventDefault();

			$( this ).parents( metaBox ).find( '.uploaded-image' ).html( '' );
			$( this ).parents( metaBox ).find( '.upload-img-link' ).removeClass( 'hidden' );
			$( this ).parents( metaBox ).find( '.delete-img-link' ).addClass( 'hidden' );
			$( this ).parents( metaBox ).find( '.uploaded-img-id' ).val( '' ).change();
		});
	});

	jQuery( document ).ajaxSuccess(function(e, request, settings){
		let action   = settings.data.indexOf( 'action=add-tag' );
		let screen   = settings.data.indexOf( 'screen=edit-category' );
		let taxonomy = settings.data.indexOf( 'taxonomy=category' );

		if( action > -1 && screen > -1 && taxonomy > -1 ){
			$( '.delete-img-link' ).click();
		}
	});
	</script>
	<?php
	wp_add_inline_script( 'jquery', str_replace( array( '<script>', '</script>' ), '', ob_get_clean() ) );
}
add_action( 'admin_enqueue_scripts', 'mbf_mb_product_cat_enqueue_scripts' );
