<?php
/**
 * Header Settings
 *
 * @package Apparel
 */

MBF_Customizer::add_section(
	'header',
	array(
		'title' => esc_html__( 'Header Settings', 'apparel' ),
	)
);

MBF_Customizer::add_field(
	array(
		'type'        => 'collapsible',
		'settings'    => 'header_collapsible_common',
		'section'     => 'header',
		'label'       => esc_html__( 'Common', 'apparel' ),
		'input_attrs' => array(
			'collapsed' => true,
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'     => 'dimension',
		'settings' => 'header_initial_height',
		'label'    => esc_html__( 'Header Initial Height', 'apparel' ),
		'section'  => 'header',
		'default'  => '73px',
		'output'   => array(
			array(
				'element'  => ':root',
				'property' => '--mbf-header-initial-height',
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'     => 'dimension',
		'settings' => 'header_height',
		'label'    => esc_html__( 'Header Height', 'apparel' ),
		'section'  => 'header',
		'default'  => '73px',
		'output'   => array(
			array(
				'element'  => ':root',
				'property' => '--mbf-header-height',
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'     => 'dimension',
		'settings' => 'header_border_width',
		'label'    => esc_html__( 'Header Border Width', 'apparel' ),
		'section'  => 'header',
		'default'  => '1px',
		'output'   => array(
			array(
				'element'  => ':root',
				'property' => '--mbf-header-border-width',
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'        => 'checkbox',
		'settings'    => 'navbar_sticky',
		'label'       => esc_html__( 'Make navigation bar sticky', 'apparel' ),
		'description' => esc_html__( 'Enabling this option will make navigation bar visible when scrolling.', 'apparel' ),
		'section'     => 'header',
		'default'     => true,
	)
);

MBF_Customizer::add_field(
	array(
		'type'            => 'checkbox',
		'settings'        => 'navbar_smart_sticky',
		'label'           => esc_html__( 'Enable the smart sticky feature', 'apparel' ),
		'description'     => esc_html__( 'Enabling this option will reveal navigation bar when scrolling up and hide it when scrolling down.', 'apparel' ),
		'section'         => 'header',
		'default'         => true,
		'active_callback' => array(
			array(
				'setting'  => 'navbar_sticky',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'     => 'checkbox',
		'settings' => 'header_offcanvas',
		'label'    => esc_html__( 'Display offcanvas toggle button', 'apparel' ),
		'section'  => 'header',
		'default'  => false,
	)
);

MBF_Customizer::add_field(
	array(
		'type'     => 'checkbox',
		'settings' => 'header_navigation_menu',
		'label'    => esc_html__( 'Display navigation menu', 'apparel' ),
		'section'  => 'header',
		'default'  => true,
	)
);

MBF_Customizer::add_field(
	array(
		'type'     => 'collapsible',
		'settings' => 'header_collapsible_search',
		'section'  => 'header',
		'label'    => esc_html__( 'Search', 'apparel' ),
	)
);

MBF_Customizer::add_field(
	array(
		'type'     => 'checkbox',
		'settings' => 'header_search_button',
		'label'    => esc_html__( 'Display search button', 'apparel' ),
		'section'  => 'header',
		'default'  => true,
	)
);

if ( class_exists( 'WooCommerce' ) ) {

	MBF_Customizer::add_field(
		array(
			'type'            => 'divider',
			'settings'        => wp_unique_id( 'divider' ),
			'section'         => 'header',
			'active_callback' => array(
				array(
					'setting'  => 'header_search_button',
					'operator' => '==',
					'value'    => true,
				),
			),
		)
	);

	MBF_Customizer::add_field(
		array(
			'type'            => 'checkbox',
			'settings'        => 'header_search_categories_of_products',
			'label'           => esc_html__( 'Display search categories of products', 'apparel' ),
			'description'     => esc_html__( 'Display categories in popup search form.', 'apparel' ),
			'section'         => 'header',
			'default'         => true,
			'active_callback' => array(
				array(
					'setting'  => 'header_search_button',
					'operator' => '==',
					'value'    => true,
				),
			),
		)
	);

	MBF_Customizer::add_field(
		array(
			'type'            => 'text',
			'settings'        => 'header_search_categories_of_products_slugs',
			'label'           => esc_html__( 'Filter categories', 'apparel' ),
			'description'     => esc_html__( 'Add comma-separated list of category slugs. For example: &laquo;travel, lifestyle, food&raquo;. Leave empty for all categories.', 'apparel' ),
			'section'         => 'header',
			'default'         => '',
			'active_callback' => array(
				array(
					'setting'  => 'header_search_button',
					'operator' => '==',
					'value'    => true,
				),
				array(
					'setting'  => 'header_search_categories_of_products',
					'operator' => '==',
					'value'    => true,
				),
			),
		)
	);

	MBF_Customizer::add_field(
		array(
			'type'            => 'divider',
			'settings'        => wp_unique_id( 'divider' ),
			'section'         => 'header',
			'active_callback' => array(
				array(
					'setting'  => 'header_search_button',
					'operator' => '==',
					'value'    => true,
				),
			),
		)
	);

	MBF_Customizer::add_field(
		array(
			'type'            => 'checkbox',
			'settings'        => 'header_search_products',
			'label'           => esc_html__( 'Display search products', 'apparel' ),
			'description'     => esc_html__( 'Display products in popup search form.', 'apparel' ),
			'section'         => 'header',
			'default'         => false,
			'active_callback' => array(
				array(
					'setting'  => 'header_search_button',
					'operator' => '==',
					'value'    => true,
				),
			),
		)
	);

	MBF_Customizer::add_field(
		array(
			'type'            => 'text',
			'settings'        => 'header_search_products_heading',
			'label'           => esc_html__( 'Heading of Products', 'apparel' ),
			'section'         => 'header',
			'default'         => esc_html__( 'You May Be Interested', 'apparel' ),
			'active_callback' => array(
				array(
					'setting'  => 'header_search_button',
					'operator' => '==',
					'value'    => true,
				),
				array(
					'setting'  => 'header_search_products',
					'operator' => '==',
					'value'    => true,
				),
			),
		)
	);

	MBF_Customizer::add_field(
		array(
			'type'            => 'radio',
			'settings'        => 'header_search_products_orderby',
			'label'           => esc_html__( 'Order products by', 'apparel' ),
			'section'         => 'header',
			'default'         => 'date',
			'choices'         => array(
				'date' => esc_html__( 'Date', 'apparel' ),
				'rand' => esc_html__( 'Random', 'apparel' ),
				'name' => esc_html__( 'Name', 'apparel' ),
				'id'   => esc_html__( 'ID', 'apparel' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'header_search_button',
					'operator' => '==',
					'value'    => true,
				),
				array(
					'setting'  => 'header_search_products',
					'operator' => '==',
					'value'    => true,
				),
			),
		)
	);

	MBF_Customizer::add_field(
		array(
			'type'            => 'radio',
			'settings'        => 'header_search_products_order',
			'label'           => esc_html__( 'Order products', 'apparel' ),
			'section'         => 'header',
			'default'         => 'DESC',
			'choices'         => array(
				'ASC'  => esc_html__( 'ASC', 'apparel' ),
				'DESC' => esc_html__( 'DESC', 'apparel' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'header_search_button',
					'operator' => '==',
					'value'    => true,
				),
				array(
					'setting'  => 'header_search_products',
					'operator' => '==',
					'value'    => true,
				),
			),
		)
	);

	MBF_Customizer::add_field(
		array(
			'type'            => 'text',
			'settings'        => 'header_search_products_filter_categories',
			'label'           => esc_html__( 'Filter by Categories', 'apparel' ),
			'description'     => esc_html__( 'Add comma-separated list of category slugs. For example: &laquo;travel, lifestyle, food&raquo;. Leave empty for all categories.', 'apparel' ),
			'section'         => 'header',
			'default'         => '',
			'active_callback' => array(
				array(
					'setting'  => 'header_search_button',
					'operator' => '==',
					'value'    => true,
				),
				array(
					'setting'  => 'header_search_products',
					'operator' => '==',
					'value'    => true,
				),
			),
		)
	);

	MBF_Customizer::add_field(
		array(
			'type'            => 'text',
			'settings'        => 'header_search_products_filter_tags',
			'label'           => esc_html__( 'Filter by Tags', 'apparel' ),
			'description'     => esc_html__( 'Add comma-separated list of tag slugs. For example: &laquo;worth-reading, top-5, playlists&raquo;. Leave empty for all tags.', 'apparel' ),
			'section'         => 'header',
			'default'         => '',
			'active_callback' => array(
				array(
					'setting'  => 'header_search_button',
					'operator' => '==',
					'value'    => true,
				),
				array(
					'setting'  => 'header_search_products',
					'operator' => '==',
					'value'    => true,
				),
			),
		)
	);

	MBF_Customizer::add_field(
		array(
			'type'            => 'text',
			'settings'        => 'header_search_products_filter_ids',
			'label'           => esc_html__( 'Filter by Products', 'apparel' ),
			'description'     => esc_html__( 'Add comma-separated list of product IDs. For example: 12, 34, 145. Leave empty for all products.', 'apparel' ),
			'section'         => 'header',
			'default'         => '',
			'active_callback' => array(
				array(
					'setting'  => 'header_search_button',
					'operator' => '==',
					'value'    => true,
				),
				array(
					'setting'  => 'header_search_products',
					'operator' => '==',
					'value'    => true,
				),
			),
		)
	);
}
