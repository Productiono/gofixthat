<?php
/**
 * The template part for displaying single promo block.
 *
 * @package Apparel
 */

if ( get_theme_mod( 'woocommerce_product_page_promo', false ) ) {

	$promo_image = get_theme_mod( 'woocommerce_product_page_promo_image' );

	$promo_image_id = attachment_url_to_postid( $promo_image );
	?>
	<div class="mbf-wc-promo__block-wrapper" data-scheme="inverse">
		<?php
		if ( $promo_image_id  ) {
			echo wp_get_attachment_image( $promo_image_id, 'mbf-large-uncropped' );
		}

		$promo_subheading = get_theme_mod( 'woocommerce_product_page_promo_subheading', esc_html__( 'Basic Collection', 'apparel' ) );
		$promo_heading    = get_theme_mod( 'woocommerce_product_page_promo_heading' );
		$promo_more_label = get_theme_mod( 'woocommerce_product_page_promo_more_label', esc_html__( 'Shop More', 'apparel' ) );
		$promo_more_link  = get_theme_mod( 'woocommerce_product_page_promo_more_link', '' );
		?>

		<div class="mbf-wc-promo__block-content">
			<?php if ( $promo_subheading ) { ?>
				<div class="mbf-wc-promo__block-subheading">
					<?php echo esc_html( $promo_subheading ); ?>
				</div>
			<?php } ?>

			<?php if ( $promo_heading ) { ?>
				<h2 class="mbf-wc-promo__block-heading">
					<?php echo esc_html( $promo_heading ); ?>
				</h2>
			<?php } ?>

			<?php if ( $promo_more_label && $promo_more_link ) { ?>
				<div class="mbf-wc-promo__block-more">
					<a href="<?php echo esc_attr( $promo_more_link  ); ?>">
						<?php echo esc_html( $promo_more_label ); ?>
					</a>
				</div>
			<?php } ?>
		</div>
	</div>
	<?php
}
