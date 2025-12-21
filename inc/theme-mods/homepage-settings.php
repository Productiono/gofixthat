<?php
/**
 * Homepage Settings
 *
 * @package Apparel
 */

/**
 * Removes default WordPress Static Front Page section
 * and re-adds it in our own panel with the same parameters.
 *
 * @param object $wp_customize Instance of the WP_Customize_Manager class.
 */
function mbf_reorder_customizer_settings( $wp_customize ) {

	// Get current front page section parameters.
	$static_front_page = $wp_customize->get_section( 'static_front_page' );

	// Remove existing section, so that we can later re-add it to our panel.
	$wp_customize->remove_section( 'static_front_page' );

	// Re-add static front page section with a new name, but same description.
	$wp_customize->add_section(
		'static_front_page',
		array(
			'title'           => esc_html__( 'Static Front Page', 'apparel' ),
			'description'     => $static_front_page->description,
			'panel'           => 'home_panel',
			'active_callback' => $static_front_page->active_callback,
		)
	);
}
add_action( 'customize_register', 'mbf_reorder_customizer_settings' );

MBF_Customizer::add_panel(
	'home_panel',
	array(
		'title' => esc_html__( 'Front Page Settings', 'apparel' ),
	)
);

MBF_Customizer::add_section(
	'home_settings',
	array(
		'title' => esc_html__( 'Latest Posts Layout', 'apparel' ),
		'panel' => 'home_panel',
	)
);

MBF_Customizer::add_field(
	array(
		'type'        => 'collapsible',
		'settings'    => 'home_collapsible_common',
		'section'     => 'home_settings',
		'label'       => esc_html__( 'Common', 'apparel' ),
		'input_attrs' => array(
			'collapsed' => true,
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'     => 'radio',
		'settings' => 'home_layout',
		'label'    => esc_html__( 'Layout', 'apparel' ),
		'section'  => 'home_settings',
		'default'  => 'list',
		'choices'  => array(
			'grid' => esc_html__( 'Grid Layout', 'apparel' ),
			'list' => esc_html__( 'List Layout', 'apparel' ),
			'full' => esc_html__( 'Full Post Layout', 'apparel' ),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'            => 'checkbox',
		'settings'        => 'home_excerpt',
		'label'           => esc_html__( 'Display excerpt', 'apparel' ),
		'section'         => 'home_settings',
		'default'         => true,
		'active_callback' => array(
			array(
				array(
					'setting'  => 'home_layout',
					'operator' => '==',
					'value'    => 'list',
				),
				array(
					'setting'  => 'home_layout',
					'operator' => '==',
					'value'    => 'grid',
				),
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'            => 'select',
		'settings'        => 'home_image_orientation',
		'label'           => esc_html__( 'Image Orientation', 'apparel' ),
		'section'         => 'home_settings',
		'default'         => 'original',
		'choices'         => array(
			'original'        => esc_html__( 'Original', 'apparel' ),
			'landscape'       => esc_html__( 'Landscape 4:3', 'apparel' ),
			'landscape-3-2'   => esc_html__( 'Landscape 3:2', 'apparel' ),
			'landscape-16-9'  => esc_html__( 'Landscape 16:9', 'apparel' ),
			'landscape-16-10' => esc_html__( 'Landscape 16:10', 'apparel' ),
			'landscape-21-9'  => esc_html__( 'Landscape 21:9', 'apparel' ),
			'portrait'        => esc_html__( 'Portrait 3:4', 'apparel' ),
			'portrait-2-3'    => esc_html__( 'Portrait 2:3', 'apparel' ),
			'square'          => esc_html__( 'Square', 'apparel' ),
		),
		'active_callback' => array(
			array(
				array(
					'setting'  => 'home_layout',
					'operator' => '==',
					'value'    => 'list',
				),
				array(
					'setting'  => 'home_layout',
					'operator' => '==',
					'value'    => 'grid',
				),
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'            => 'select',
		'settings'        => 'home_image_size',
		'label'           => esc_html__( 'Image Size', 'apparel' ),
		'section'         => 'home_settings',
		'default'         => 'mbf-thumbnail',
		'choices'         => mbf_get_list_available_image_sizes(),
		'active_callback' => array(
			array(
				array(
					'setting'  => 'home_layout',
					'operator' => '==',
					'value'    => 'list',
				),
				array(
					'setting'  => 'home_layout',
					'operator' => '==',
					'value'    => 'grid',
				),
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'            => 'radio',
		'settings'        => 'home_media_preview',
		'label'           => esc_html__( 'Post Preview Image Size', 'apparel' ),
		'section'         => 'home_settings',
		'default'         => 'uncropped',
		'choices'         => array(
			'cropped'   => esc_html__( 'Display Cropped Image', 'apparel' ),
			'uncropped' => esc_html__( 'Display Preview in Original Ratio', 'apparel' ),
		),
		'active_callback' => array(
			array(
				array(
					'setting'  => 'home_layout',
					'operator' => '==',
					'value'    => 'full',
				),
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'            => 'radio',
		'settings'        => 'home_summary',
		'label'           => esc_html__( 'Full Post Summary', 'apparel' ),
		'section'         => 'home_settings',
		'default'         => 'summary',
		'choices'         => array(
			'summary' => esc_html__( 'Use Excerpts', 'apparel' ),
			'content' => esc_html__( 'Use Read More Tag', 'apparel' ),
		),
		'active_callback' => array(
			array(
				array(
					'setting'  => 'home_layout',
					'operator' => '==',
					'value'    => 'full',
				),
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'     => 'radio',
		'settings' => 'home_pagination_type',
		'label'    => esc_html__( 'Pagination', 'apparel' ),
		'section'  => 'home_settings',
		'default'  => 'load-more',
		'choices'  => array(
			'standard'  => esc_html__( 'Standard', 'apparel' ),
			'load-more' => esc_html__( 'Load More Button', 'apparel' ),
			'infinite'  => esc_html__( 'Infinite Load', 'apparel' ),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'        => 'collapsible',
		'settings'    => 'home_collapsible_post_meta',
		'section'     => 'home_settings',
		'label'       => esc_html__( 'Post Meta', 'apparel' ),
		'input_attrs' => array(
			'collapsed' => false,
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'     => 'multicheck',
		'settings' => 'home_post_meta',
		'label'    => esc_html__( 'Post Meta', 'apparel' ),
		'section'  => 'home_settings',
		'default'  => array( 'date', 'author' ),
		'choices'  => array(
			'date'     => esc_html__( 'Date', 'apparel' ),
			'author'   => esc_html__( 'Author', 'apparel' ),
			'category' => esc_html__( 'Category', 'apparel' ),
			'comments' => esc_html__( 'Comments', 'apparel' ),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'            => 'collapsible',
		'settings'        => 'home_collapsible_number_of_olumns',
		'section'         => 'home_settings',
		'label'           => esc_html__( 'Number of Columns', 'apparel' ),
		'input_attrs'     => array(
			'collapsed' => false,
		),
		'active_callback' => array(
			array(
				'setting'  => 'home_layout',
				'operator' => '==',
				'value'    => 'grid',
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'            => 'number',
		'settings'        => 'home_columns_desktop',
		'label'           => esc_html__( 'Desktop', 'apparel' ),
		'section'         => 'home_settings',
		'default'         => 4,
		'input_attrs'     => array(
			'min'  => 1,
			'max'  => 3,
			'step' => 1,
		),
		'output'          => array(
			array(
				'element'  => '.mbf-posts-area__home.mbf-posts-area__grid',
				'property' => '--mbf-posts-area-grid-columns',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'home_layout',
				'operator' => '==',
				'value'    => 'grid',
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'            => 'number',
		'settings'        => 'home_columns_notebook',
		'label'           => esc_html__( 'Notebook', 'apparel' ),
		'section'         => 'home_settings',
		'default'         => 3,
		'input_attrs'     => array(
			'min'  => 1,
			'max'  => 4,
			'step' => 1,
		),
		'output'          => array(
			array(
				'element'     => '.mbf-posts-area__home.mbf-posts-area__grid',
				'property'    => '--mbf-posts-area-grid-columns',
				'media_query' => '@media (max-width: 1199.98px)',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'home_layout',
				'operator' => '==',
				'value'    => 'grid',
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'            => 'number',
		'settings'        => 'home_columns_tablet',
		'label'           => esc_html__( 'Tablet', 'apparel' ),
		'section'         => 'home_settings',
		'default'         => 2,
		'input_attrs'     => array(
			'min'  => 1,
			'max'  => 4,
			'step' => 1,
		),
		'output'          => array(
			array(
				'element'     => '.mbf-posts-area__home.mbf-posts-area__grid',
				'property'    => '--mbf-posts-area-grid-columns',
				'media_query' => '@media (max-width: 991.98px)',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'home_layout',
				'operator' => '==',
				'value'    => 'grid',
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'            => 'number',
		'settings'        => 'home_columns_mobile',
		'label'           => esc_html__( 'Mobile', 'apparel' ),
		'section'         => 'home_settings',
		'default'         => 1,
		'input_attrs'     => array(
			'min'  => 1,
			'max'  => 4,
			'step' => 1,
		),
		'output'          => array(
			array(
				'element'     => '.mbf-posts-area__home.mbf-posts-area__grid',
				'property'    => '--mbf-posts-area-grid-columns',
				'media_query' => '@media (max-width: 575.98px)',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'home_layout',
				'operator' => '==',
				'value'    => 'grid',
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'        => 'collapsible',
		'settings'    => 'home_collapsible_gap_between_rows',
		'section'     => 'home_settings',
		'label'       => esc_html__( 'Gap between Rows', 'apparel' ),
		'input_attrs' => array(
			'collapsed' => false,
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'     => 'dimension',
		'settings' => 'home_gap_between_rows_desktop',
		'label'    => esc_html__( 'Desktop', 'apparel' ),
		'section'  => 'home_settings',
		'default'  => '60px',
		'output'   => array(
			array(
				'element'  => '.mbf-posts-area__home',
				'property' => '--mbf-posts-area-grid-row-gap',
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'     => 'dimension',
		'settings' => 'home_gap_between_rows_notebook',
		'label'    => esc_html__( 'Notebook', 'apparel' ),
		'section'  => 'home_settings',
		'default'  => '60px',
		'output'   => array(
			array(
				'element'     => '.mbf-posts-area__home',
				'property'    => '--mbf-posts-area-grid-row-gap',
				'media_query' => '@media (max-width: 1199.98px)',
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'     => 'dimension',
		'settings' => 'home_gap_between_rows_tablet',
		'label'    => esc_html__( 'Tablet', 'apparel' ),
		'section'  => 'home_settings',
		'default'  => '40px',
		'output'   => array(
			array(
				'element'     => '.mbf-posts-area__home',
				'property'    => '--mbf-posts-area-grid-row-gap',
				'media_query' => '@media (max-width: 991.98px)',
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'     => 'dimension',
		'settings' => 'home_gap_between_rows_mobile',
		'label'    => esc_html__( 'Mobile', 'apparel' ),
		'section'  => 'home_settings',
		'default'  => '40px',
		'output'   => array(
			array(
				'element'     => '.mbf-posts-area__home',
				'property'    => '--mbf-posts-area-grid-row-gap',
				'media_query' => '@media (max-width: 575.98px)',
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'            => 'collapsible',
		'settings'        => 'home_collapsible_gap_between_columns',
		'section'         => 'home_settings',
		'label'           => esc_html__( 'Gap between Columns', 'apparel' ),
		'input_attrs'     => array(
			'collapsed' => false,
		),
		'active_callback' => array(
			array(
				'setting'  => 'home_layout',
				'operator' => '==',
				'value'    => 'grid',
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'            => 'dimension',
		'settings'        => 'home_gap_between_columns_desktop',
		'label'           => esc_html__( 'Desktop', 'apparel' ),
		'section'         => 'home_settings',
		'default'         => '24px',
		'output'          => array(
			array(
				'element'  => '.mbf-posts-area__home.mbf-posts-area__grid',
				'property' => '--mbf-posts-area-grid-column-gap',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'home_layout',
				'operator' => '==',
				'value'    => 'grid',
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'            => 'dimension',
		'settings'        => 'home_gap_between_columns_notebook',
		'label'           => esc_html__( 'Notebook', 'apparel' ),
		'section'         => 'home_settings',
		'default'         => '24px',
		'output'          => array(
			array(
				'element'     => '.mbf-posts-area__home.mbf-posts-area__grid',
				'property'    => '--mbf-posts-area-grid-column-gap',
				'media_query' => '@media (max-width: 1199.98px)',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'home_layout',
				'operator' => '==',
				'value'    => 'grid',
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'            => 'dimension',
		'settings'        => 'home_gap_between_columns_tablet',
		'label'           => esc_html__( 'Tablet', 'apparel' ),
		'section'         => 'home_settings',
		'default'         => '24px',
		'output'          => array(
			array(
				'element'     => '.mbf-posts-area__home.mbf-posts-area__grid',
				'property'    => '--mbf-posts-area-grid-column-gap',
				'media_query' => '@media (max-width: 991.98px)',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'home_layout',
				'operator' => '==',
				'value'    => 'grid',
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'            => 'dimension',
		'settings'        => 'home_gap_between_columns_mobile',
		'label'           => esc_html__( 'Mobile', 'apparel' ),
		'section'         => 'home_settings',
		'default'         => '24px',
		'output'          => array(
			array(
				'element'     => '.mbf-posts-area__home.mbf-posts-area__grid',
				'property'    => '--mbf-posts-area-grid-column-gap',
				'media_query' => '@media (max-width: 575.98px)',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'home_layout',
				'operator' => '==',
				'value'    => 'grid',
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'        => 'collapsible',
		'settings'    => 'home_collapsible_title_size',
		'section'     => 'home_settings',
		'label'       => esc_html__( 'Title Font Size', 'apparel' ),
		'input_attrs' => array(
			'collapsed' => false,
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'     => 'dimension',
		'settings' => 'home_title_size_desktop',
		'label'    => esc_html__( 'Desktop', 'apparel' ),
		'section'  => 'home_settings',
		'output'   => array(
			array(
				'element'  => '.mbf-posts-area__home',
				'property' => '--mbf-entry-title-font-size',
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'     => 'dimension',
		'settings' => 'home_title_size_notebook',
		'label'    => esc_html__( 'Notebook', 'apparel' ),
		'section'  => 'home_settings',
		'output'   => array(
			array(
				'element'     => '.mbf-posts-area__home',
				'property'    => '--mbf-entry-title-font-size',
				'media_query' => '@media (max-width: 1199.98px)',
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'     => 'dimension',
		'settings' => 'home_title_size_tablet',
		'label'    => esc_html__( 'Tablet', 'apparel' ),
		'section'  => 'home_settings',
		'output'   => array(
			array(
				'element'     => '.mbf-posts-area__home',
				'property'    => '--mbf-entry-title-font-size',
				'media_query' => '@media (max-width: 991.98px)',
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'     => 'dimension',
		'settings' => 'home_title_size_mobile',
		'label'    => esc_html__( 'Mobile', 'apparel' ),
		'section'  => 'home_settings',
		'output'   => array(
			array(
				'element'     => '.mbf-posts-area__home',
				'property'    => '--mbf-entry-title-font-size',
				'media_query' => '@media (max-width: 575.98px)',
			),
		),
	)
);
