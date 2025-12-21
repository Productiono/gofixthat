<?php
/**
 * Description tab
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/description.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 2.0.0
 */

defined( 'ABSPATH' ) || exit;

global $post;

/**
 * The woocommerce_product_description_heading hook.
 *
 * @since 1.0.0
 */
$heading = apply_filters( 'woocommerce_product_description_heading', __( 'Description', 'apparel' ) );

$page_dm = get_theme_mod( 'woocommerce_product_page_dm', false );
?>

<div class="woocommerce-tabs-desc-wrap <?php echo esc_html( $page_dm ? 'woocommerce-tabs-desc-wrap-dm' : null ); ?>">
	<?php if ( $heading ) : ?>
		<h2 class="woocommerce-panel-subheading"><?php echo esc_html( $heading ); ?></h2>
	<?php endif; ?>

	<div class="woocommerce-tabs-desc-inner">
		<div class="woocommerce-tabs-desc-content">
			<?php the_content(); ?>
		</div>
	</div>

	<?php if ( $page_dm ) { ?>
		<div class="woocommerce-tabs-desc-info">
			<?php
			for ( $index = 1; $index <= 4; $index++ ) {
				$dm_image   = get_theme_mod( "woocommerce_product_page_dm_image_{$index}" );
				$dm_heading = get_theme_mod( "woocommerce_product_page_dm_heading_{$index}" );
				$dm_text    = get_theme_mod( "woocommerce_product_page_dm_desc_{$index}" );
				?>

				<div class="woocommerce-tabs-desc-info-item">
					<?php
					if ( $dm_image ) {
						$dm_image_id = attachment_url_to_postid( $dm_image );
						?>
						<div class="woocommerce-tabs-desc-info-item-img">
							<span>
								<?php echo wp_get_attachment_image( $dm_image_id, 'full' ); ?>
							</span>
						</div>
					<?php } ?>

					<div class="woocommerce-tabs-desc-info-item-meta">
						<?php if ( $dm_heading ) { ?>
							<h4 class="woocommerce-tabs-desc-info-item-heading">
								<?php echo esc_html( $dm_heading ); ?>
							</h4>
						<?php } ?>

						<?php if ( $dm_text ) { ?>
							<div class="woocommerce-tabs-desc-info-item-text">
								<?php echo esc_html( $dm_text ); ?>
							</div>
						<?php } ?>
					</div>
				</div>
			<?php } ?>
		</div>
	<?php } ?>
</div>
