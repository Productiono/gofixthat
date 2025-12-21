<?php
/**
 * WooCommerce setup
 *
 * @package Apparel
 */

/**
 * Add support WooCommerce.
 */

switch ( get_theme_mod( 'woocommerce_product_gallery_layout', 'default' ) ) {
	case 'carousel-1':
		$gallery_thumbnail_image_width = 860;
		break;
	case 'carousel-2':
		$gallery_thumbnail_image_width = 1720;
		break;
	default:
		$gallery_thumbnail_image_width = 712;
		break;
}

add_theme_support(
	'woocommerce',
	array(
		'thumbnail_image_width'         => 712,
		'single_image_width'            => 1720,
		'gallery_thumbnail_image_width' => $gallery_thumbnail_image_width,
		'product_grid'                  => array(
			'default_rows'    => 3,
			'default_columns' => 3,
		),
	)
);

add_theme_support( 'wc-product-gallery-lightbox' );

if ( 'default' === get_theme_mod( 'woocommerce_product_gallery_layout', 'default' ) ) {
	add_theme_support( 'wc-product-gallery-slider' );
}

/**
 * Register WooCommerce Sidebar
 */
function mbf_wc_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Shop', 'apparel' ),
			'id'            => 'shop-sidebar',
			'before_widget' => '<div class="widget %1$s %2$s">',
			'after_widget'  => '</div>',
		)
	);
}
add_action( 'widgets_init', 'mbf_wc_widgets_init' );
