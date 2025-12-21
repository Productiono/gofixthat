<?php
/**
 * The template for displaying the header mobile
 *
 * @package Apparel
 */

?>

<div class="mbf-header__inner mbf-header__inner-mobile">
	<div class="mbf-header__col mbf-col-left">
		<?php mbf_component( 'header_offcanvas_toggle', true, array( 'mobile' => true ) ); ?>
	</div>
	<div class="mbf-header__col mbf-col-center">
		<?php mbf_component( 'header_logo' ); ?>
	</div>
	<div class="mbf-header__col mbf-col-right">
		<?php mbf_component( 'wc_header_cart' ); ?>

		<?php
		if ( ! class_exists( 'WooCommerce' ) || get_theme_mod( 'woocommerce_header_hide_icon', false ) ) {
			mbf_component( 'header_search_toggle' );
		}
		?>
	</div>

	<?php mbf_site_search(); ?>
</div>
