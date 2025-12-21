<?php
/**
 * WooCommerce theme mods
 *
 * @package Apparel
 */

/**
 * Add fields to WooCommerce.
 */
function mbf_wc_add_fields_customizer() {
	MBF_Customizer::add_section(
		'woocommerce_common_settings',
		array(
			'title' => esc_html__( 'Common Settings', 'apparel' ),
			'panel' => 'woocommerce',
		)
	);

	MBF_Customizer::add_field(
		array(
			'type'     => 'text',
			'settings' => 'woocommerce_product_catalog_offcanvas_label',
			'label'    => esc_html__( 'Off-canvas Label', 'apparel' ),
			'section'  => 'woocommerce_product_catalog',
			'default'  => esc_html__( 'Filters', 'apparel' ),
		)
	);

	MBF_Customizer::add_field(
		array(
			'type'     => 'checkbox',
			'settings' => 'woocommerce_product_catalog_cart',
			'label'    => esc_html__( 'Display add to cart bottom', 'apparel' ),
			'section'  => 'woocommerce_product_catalog',
			'default'  => false,
		)
	);

	MBF_Customizer::add_section(
		'woocommerce_product_page',
		array(
			'title' => esc_html__( 'Product Page', 'apparel' ),
			'panel' => 'woocommerce',
		)
	);

	MBF_Customizer::add_field(
		array(
			'type'        => 'collapsible',
			'settings'    => 'woocommerce_product_page_collapsible_common',
			'section'     => 'woocommerce_product_page',
			'label'       => esc_html__( 'Common', 'apparel' ),
			'input_attrs' => array(
				'collapsed' => true,
			),
		)
	);

	MBF_Customizer::add_field(
		array(
			'type'     => 'radio',
			'settings' => 'woocommerce_product_gallery_layout',
			'label'    => esc_html__( 'Product Gallery Layout', 'apparel' ),
			'section'  => 'woocommerce_product_page',
			'default'  => 'default',
			'choices'  => array(
				'default'    => esc_html__( 'Default', 'apparel' ),
				'carousel-1' => esc_html__( 'Carousel 1', 'apparel' ),
				'carousel-2' => esc_html__( 'Carousel 2', 'apparel' ),
			),
		)
	);

	MBF_Customizer::add_field(
		array(
			'type'            => 'select',
			'settings'        => 'woocommerce_product_gallery_orientation',
			'label'           => esc_html__( 'Image Orientation', 'apparel' ),
			'section'         => 'woocommerce_product_page',
			'default'         => 'original',
			'choices'         => array(
				'original'  => esc_html__( 'Original', 'apparel' ),
				'landscape' => esc_html__( 'Landscape', 'apparel' ),
				'portrait'  => esc_html__( 'Portrait', 'apparel' ),
				'square'    => esc_html__( 'Square', 'apparel' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'woocommerce_product_gallery_layout',
					'operator' => '!=',
					'value'    => 'default',
				),
			),
		)
	);

	MBF_Customizer::add_field(
		array(
			'type'        => 'collapsible',
			'settings'    => 'woocommerce_product_page_collapsible_desc_meta',
			'section'     => 'woocommerce_product_page',
			'label'       => esc_html__( 'Description Meta', 'apparel' ),
			'input_attrs' => array(
				'collapsed' => false,
			),
		)
	);

	MBF_Customizer::add_field(
		array(
			'type'            => 'checkbox',
			'settings'        => 'woocommerce_product_page_dm',
			'label'           => esc_html__( 'Display description meta', 'apparel' ),
			'description'     => '',
			'section'         => 'woocommerce_product_page',
			'default'         => false,
			'active_callback' => array(),
		)
	);

	MBF_Customizer::add_field(
		array(
			'type'            => 'image',
			'settings'        => 'woocommerce_product_page_dm_image_1',
			'label'           => esc_html__( 'Image 1', 'apparel' ),
			'section'         => 'woocommerce_product_page',
			'active_callback' => array(
				array(
					'setting'  => 'woocommerce_product_page_dm',
					'operator' => '==',
					'value'    => true,
				),
			),
		)
	);


	MBF_Customizer::add_field(
		array(
			'type'            => 'text',
			'settings'        => 'woocommerce_product_page_dm_heading_1',
			'label'           => esc_html__( 'Heading 1', 'apparel' ),
			'section'         => 'woocommerce_product_page',
			'default'         => '',
			'active_callback' => array(
				array(
					'setting'  => 'woocommerce_product_page_dm',
					'operator' => '==',
					'value'    => true,
				),
			),
		)
	);

	MBF_Customizer::add_field(
		array(
			'type'            => 'text',
			'settings'        => 'woocommerce_product_page_dm_desc_1',
			'label'           => esc_html__( 'Desc 1', 'apparel' ),
			'section'         => 'woocommerce_product_page',
			'default'         => '',
			'active_callback' => array(
				array(
					'setting'  => 'woocommerce_product_page_dm',
					'operator' => '==',
					'value'    => true,
				),
			),
		)
	);

	MBF_Customizer::add_field(
		array(
			'type'            => 'image',
			'settings'        => 'woocommerce_product_page_dm_image_2',
			'label'           => esc_html__( 'Image 2', 'apparel' ),
			'section'         => 'woocommerce_product_page',
			'active_callback' => array(
				array(
					'setting'  => 'woocommerce_product_page_dm',
					'operator' => '==',
					'value'    => true,
				),
			),
		)
	);

	MBF_Customizer::add_field(
		array(
			'type'            => 'text',
			'settings'        => 'woocommerce_product_page_dm_heading_2',
			'label'           => esc_html__( 'Heading 2', 'apparel' ),
			'section'         => 'woocommerce_product_page',
			'default'         => '',
			'active_callback' => array(
				array(
					'setting'  => 'woocommerce_product_page_dm',
					'operator' => '==',
					'value'    => true,
				),
			),
		)
	);

	MBF_Customizer::add_field(
		array(
			'type'            => 'text',
			'settings'        => 'woocommerce_product_page_dm_desc_2',
			'label'           => esc_html__( 'Desc 2', 'apparel' ),
			'section'         => 'woocommerce_product_page',
			'default'         => '',
			'active_callback' => array(
				array(
					'setting'  => 'woocommerce_product_page_dm',
					'operator' => '==',
					'value'    => true,
				),
			),
		)
	);

	MBF_Customizer::add_field(
		array(
			'type'            => 'image',
			'settings'        => 'woocommerce_product_page_dm_image_3',
			'label'           => esc_html__( 'Image 3', 'apparel' ),
			'section'         => 'woocommerce_product_page',
			'active_callback' => array(
				array(
					'setting'  => 'woocommerce_product_page_dm',
					'operator' => '==',
					'value'    => true,
				),
			),
		)
	);

	MBF_Customizer::add_field(
		array(
			'type'            => 'text',
			'settings'        => 'woocommerce_product_page_dm_heading_3',
			'label'           => esc_html__( 'Heading 3', 'apparel' ),
			'section'         => 'woocommerce_product_page',
			'default'         => '',
			'active_callback' => array(
				array(
					'setting'  => 'woocommerce_product_page_dm',
					'operator' => '==',
					'value'    => true,
				),
			),
		)
	);

	MBF_Customizer::add_field(
		array(
			'type'            => 'text',
			'settings'        => 'woocommerce_product_page_dm_desc_3',
			'label'           => esc_html__( 'Desc 3', 'apparel' ),
			'section'         => 'woocommerce_product_page',
			'default'         => '',
			'active_callback' => array(
				array(
					'setting'  => 'woocommerce_product_page_dm',
					'operator' => '==',
					'value'    => true,
				),
			),
		)
	);

	MBF_Customizer::add_field(
		array(
			'type'            => 'image',
			'settings'        => 'woocommerce_product_page_dm_image_4',
			'label'           => esc_html__( 'Image 4', 'apparel' ),
			'section'         => 'woocommerce_product_page',
			'active_callback' => array(
				array(
					'setting'  => 'woocommerce_product_page_dm',
					'operator' => '==',
					'value'    => true,
				),
			),
		)
	);

	MBF_Customizer::add_field(
		array(
			'type'            => 'text',
			'settings'        => 'woocommerce_product_page_dm_heading_4',
			'label'           => esc_html__( 'Heading 4', 'apparel' ),
			'section'         => 'woocommerce_product_page',
			'default'         => '',
			'active_callback' => array(
				array(
					'setting'  => 'woocommerce_product_page_dm',
					'operator' => '==',
					'value'    => true,
				),
			),
		)
	);

	MBF_Customizer::add_field(
		array(
			'type'            => 'text',
			'settings'        => 'woocommerce_product_page_dm_desc_4',
			'label'           => esc_html__( 'Desc 4', 'apparel' ),
			'section'         => 'woocommerce_product_page',
			'default'         => '',
			'active_callback' => array(
				array(
					'setting'  => 'woocommerce_product_page_dm',
					'operator' => '==',
					'value'    => true,
				),
			),
		)
	);

	MBF_Customizer::add_field(
		array(
			'type'        => 'collapsible',
			'settings'    => 'woocommerce_product_page_collapsible_promo',
			'section'     => 'woocommerce_product_page',
			'label'       => esc_html__( 'Promo Block', 'apparel' ),
			'input_attrs' => array(
				'collapsed' => false,
			),
		)
	);

	MBF_Customizer::add_field(
		array(
			'type'            => 'checkbox',
			'settings'        => 'woocommerce_product_page_promo',
			'label'           => esc_html__( 'Display promo block', 'apparel' ),
			'description'     => '',
			'section'         => 'woocommerce_product_page',
			'default'         => false,
			'active_callback' => array(),
		)
	);

	MBF_Customizer::add_field(
		array(
			'type'            => 'image',
			'settings'        => 'woocommerce_product_page_promo_image',
			'label'           => esc_html__( 'Backround Image', 'apparel' ),
			'section'         => 'woocommerce_product_page',
			'active_callback' => array(
				array(
					'setting'  => 'woocommerce_product_page_promo',
					'operator' => '==',
					'value'    => true,
				),
			),
		)
	);

	MBF_Customizer::add_field(
		array(
			'type'            => 'text',
			'settings'        => 'woocommerce_product_page_promo_subheading',
			'label'           => esc_html__( 'Subeading', 'apparel' ),
			'section'         => 'woocommerce_product_page',
			'default'         => esc_html__( 'Basic Collection', 'apparel' ),
			'active_callback' => array(
				array(
					'setting'  => 'woocommerce_product_page_promo',
					'operator' => '==',
					'value'    => true,
				),
			),
		)
	);

	MBF_Customizer::add_field(
		array(
			'type'            => 'text',
			'settings'        => 'woocommerce_product_page_promo_heading',
			'label'           => esc_html__( 'Heading', 'apparel' ),
			'section'         => 'woocommerce_product_page',
			'default'         => '',
			'active_callback' => array(
				array(
					'setting'  => 'woocommerce_product_page_promo',
					'operator' => '==',
					'value'    => true,
				),
			),
		)
	);

	MBF_Customizer::add_field(
		array(
			'type'            => 'text',
			'settings'        => 'woocommerce_product_page_promo_more_label',
			'label'           => esc_html__( 'More Label', 'apparel' ),
			'section'         => 'woocommerce_product_page',
			'default'         => esc_html__( 'Shop More', 'apparel' ),
			'active_callback' => array(
				array(
					'setting'  => 'woocommerce_product_page_promo',
					'operator' => '==',
					'value'    => true,
				),
			),
		)
	);

	MBF_Customizer::add_field(
		array(
			'type'            => 'text',
			'settings'        => 'woocommerce_product_page_promo_more_link',
			'label'           => esc_html__( 'More Link', 'apparel' ),
			'section'         => 'woocommerce_product_page',
			'default'         => '',
			'active_callback' => array(
				array(
					'setting'  => 'woocommerce_product_page_promo',
					'operator' => '==',
					'value'    => true,
				),
			),
		)
	);

	MBF_Customizer::add_field(
		array(
			'type'        => 'collapsible',
			'settings'    => 'woocommerce_product_page_collapsible_related',
			'section'     => 'woocommerce_product_page',
			'label'       => esc_html__( 'Related Products', 'apparel' ),
			'input_attrs' => array(
				'collapsed' => false,
			),
		)
	);

	MBF_Customizer::add_field(
		array(
			'type'     => 'text',
			'settings' => 'woocommerce_product_related_subheading',
			'label'    => esc_html__( 'Subeading', 'apparel' ),
			'section'  => 'woocommerce_product_page',
			'default'  => esc_html__( 'You May Be Interested', 'apparel' ),
		)
	);

	MBF_Customizer::add_field(
		array(
			'type'     => 'text',
			'settings' => 'woocommerce_product_related_heading',
			'label'    => esc_html__( 'Heading', 'apparel' ),
			'section'  => 'woocommerce_product_page',
			'default'  => esc_html__( 'Related Products', 'apparel' ),
		)
	);

	MBF_Customizer::add_section(
		'woocommerce_cart',
		array(
			'title' => esc_html__( 'Cart', 'apparel' ),
			'panel' => 'woocommerce',
		)
	);

	MBF_Customizer::add_field(
		array(
			'type'            => 'checkbox',
			'settings'        => 'woocommerce_cart_related_products',
			'label'           => esc_html__( 'Display related products', 'apparel' ),
			'description'     => '',
			'section'         => 'woocommerce_cart',
			'default'         => true,
			'active_callback' => array(),
		)
	);

	MBF_Customizer::add_field(
		array(
			'type'            => 'text',
			'settings'        => 'woocommerce_cart_related_products_subheading',
			'label'           => esc_html__( 'Subeading', 'apparel' ),
			'section'         => 'woocommerce_cart',
			'default'         => esc_html__( 'You May Be Interested', 'apparel' ),
			'active_callback' => array(
				array(
					'setting'  => 'woocommerce_cart_related_products',
					'operator' => '==',
					'value'    => true,
				),
			),
		)
	);

	MBF_Customizer::add_field(
		array(
			'type'            => 'text',
			'settings'        => 'woocommerce_cart_related_products_heading',
			'label'           => esc_html__( 'Heading', 'apparel' ),
			'section'         => 'woocommerce_cart',
			'default'         => esc_html__( 'Related Products', 'apparel' ),
			'active_callback' => array(
				array(
					'setting'  => 'woocommerce_cart_related_products',
					'operator' => '==',
					'value'    => true,
				),
			),
		)
	);

	MBF_Customizer::add_field(
		array(
			'type'            => 'text',
			'settings'        => 'woocommerce_cart_related_products_more_label',
			'label'           => esc_html__( 'More Label', 'apparel' ),
			'section'         => 'woocommerce_cart',
			'default'         => esc_html__( 'Discover More', 'apparel' ),
			'active_callback' => array(
				array(
					'setting'  => 'woocommerce_cart_related_products',
					'operator' => '==',
					'value'    => true,
				),
			),
		)
	);

	MBF_Customizer::add_field(
		array(
			'type'            => 'text',
			'settings'        => 'woocommerce_cart_related_products_more_link',
			'label'           => esc_html__( 'More Link', 'apparel' ),
			'section'         => 'woocommerce_cart',
			'default'         => '',
			'active_callback' => array(
				array(
					'setting'  => 'woocommerce_cart_related_products',
					'operator' => '==',
					'value'    => true,
				),
			),
		)
	);

	MBF_Customizer::add_field(
		array(
			'type'            => 'radio',
			'settings'        => 'woocommerce_cart_related_products_orderby',
			'label'           => esc_html__( 'Order products by', 'apparel' ),
			'section'         => 'woocommerce_cart',
			'default'         => 'date',
			'choices'         => array(
				'date' => esc_html__( 'Date', 'apparel' ),
				'rand' => esc_html__( 'Random', 'apparel' ),
				'name' => esc_html__( 'Name', 'apparel' ),
				'id'   => esc_html__( 'ID', 'apparel' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'woocommerce_cart_related_products',
					'operator' => '==',
					'value'    => true,
				),
			),
		)
	);

	MBF_Customizer::add_field(
		array(
			'type'            => 'radio',
			'settings'        => 'woocommerce_cart_related_products_order',
			'label'           => esc_html__( 'Order products', 'apparel' ),
			'section'         => 'woocommerce_cart',
			'default'         => 'DESC',
			'choices'         => array(
				'ASC'  => esc_html__( 'ASC', 'apparel' ),
				'DESC' => esc_html__( 'DESC', 'apparel' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'woocommerce_cart_related_products',
					'operator' => '==',
					'value'    => true,
				),
			),
		)
	);

	MBF_Customizer::add_field(
		array(
			'type'            => 'text',
			'settings'        => 'woocommerce_cart_related_products_filter_categories',
			'label'           => esc_html__( 'Filter by Categories', 'apparel' ),
			'description'     => esc_html__( 'Add comma-separated list of category slugs. For example: &laquo;travel, lifestyle, food&raquo;. Leave empty for all categories.', 'apparel' ),
			'section'         => 'woocommerce_cart',
			'default'         => '',
			'active_callback' => array(
				array(
					'setting'  => 'woocommerce_cart_related_products',
					'operator' => '==',
					'value'    => true,
				),
			),
		)
	);

	MBF_Customizer::add_field(
		array(
			'type'            => 'text',
			'settings'        => 'woocommerce_cart_related_products_filter_tags',
			'label'           => esc_html__( 'Filter by Tags', 'apparel' ),
			'description'     => esc_html__( 'Add comma-separated list of tag slugs. For example: &laquo;worth-reading, top-5, playlists&raquo;. Leave empty for all tags.', 'apparel' ),
			'section'         => 'woocommerce_cart',
			'default'         => '',
			'active_callback' => array(
				array(
					'setting'  => 'woocommerce_cart_related_products',
					'operator' => '==',
					'value'    => true,
				),
			),
		)
	);

	MBF_Customizer::add_field(
		array(
			'type'            => 'text',
			'settings'        => 'woocommerce_cart_related_products_filter_ids',
			'label'           => esc_html__( 'Filter by Products', 'apparel' ),
			'description'     => esc_html__( 'Add comma-separated list of product IDs. For example: 12, 34, 145. Leave empty for all products.', 'apparel' ),
			'section'         => 'woocommerce_cart',
			'default'         => '',
			'active_callback' => array(
				array(
					'setting'  => 'woocommerce_cart_related_products',
					'operator' => '==',
					'value'    => true,
				),
			),
		)
	);

	MBF_Customizer::add_section(
		'woocommerce_product_misc',
		array(
			'title' => esc_html__( 'Miscellaneous', 'apparel' ),
			'panel' => 'woocommerce',
		)
	);

	MBF_Customizer::add_field(
		array(
			'type'     => 'checkbox',
			'settings' => 'woocommerce_hide_my_account_button',
			'label'    => esc_html__( 'Hide Account Button', 'apparel' ),
			'section'  => 'woocommerce_product_misc',
			'default'  => false,
		)
	);

	MBF_Customizer::add_field(
		array(
			'type'     => 'checkbox',
			'settings' => 'woocommerce_header_hide_icon',
			'label'    => esc_html__( 'Hide Cart Icon in Header', 'apparel' ),
			'section'  => 'woocommerce_product_misc',
			'default'  => false,
		)
	);
}
add_action( 'init', 'mbf_wc_add_fields_customizer' );
