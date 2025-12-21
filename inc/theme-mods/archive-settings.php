<?php
/**
 * Archive Settings
 *
 * @package Apparel
 */

MBF_Customizer::add_section(
	'archive_settings',
	array(
		'title' => esc_html__( 'Archive Settings', 'apparel' ),
	)
);

MBF_Customizer::add_field(
	array(
		'type'        => 'collapsible',
		'settings'    => 'archive_collapsible_common',
		'section'     => 'archive_settings',
		'label'       => esc_html__( 'Common', 'apparel' ),
		'input_attrs' => array(
			'collapsed' => true,
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'     => 'radio',
		'settings' => 'archive_layout',
		'label'    => esc_html__( 'Layout', 'apparel' ),
		'section'  => 'archive_settings',
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
		'settings'        => 'archive_excerpt',
		'label'           => esc_html__( 'Display excerpt', 'apparel' ),
		'section'         => 'archive_settings',
		'default'         => true,
		'active_callback' => array(
			array(
				array(
					'setting'  => 'archive_layout',
					'operator' => '==',
					'value'    => 'list',
				),
				array(
					'setting'  => 'archive_layout',
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
		'settings'        => 'archive_image_orientation',
		'label'           => esc_html__( 'Image Orientation', 'apparel' ),
		'section'         => 'archive_settings',
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
					'setting'  => 'archive_layout',
					'operator' => '==',
					'value'    => 'list',
				),
				array(
					'setting'  => 'archive_layout',
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
		'settings'        => 'archive_image_size',
		'label'           => esc_html__( 'Image Size', 'apparel' ),
		'section'         => 'archive_settings',
		'default'         => 'mbf-thumbnail',
		'choices'         => mbf_get_list_available_image_sizes(),
		'active_callback' => array(
			array(
				array(
					'setting'  => 'archive_layout',
					'operator' => '==',
					'value'    => 'list',
				),
				array(
					'setting'  => 'archive_layout',
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
		'settings'        => 'archive_media_preview',
		'label'           => esc_html__( 'Post Preview Image Size', 'apparel' ),
		'section'         => 'archive_settings',
		'default'         => 'uncropped',
		'choices'         => array(
			'cropped'   => esc_html__( 'Display Cropped Image', 'apparel' ),
			'uncropped' => esc_html__( 'Display Preview in Original Ratio', 'apparel' ),
		),
		'active_callback' => array(
			array(
				array(
					'setting'  => 'archive_layout',
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
		'settings'        => 'archive_summary',
		'label'           => esc_html__( 'Full Post Summary', 'apparel' ),
		'section'         => 'archive_settings',
		'default'         => 'summary',
		'choices'         => array(
			'summary' => esc_html__( 'Use Excerpts', 'apparel' ),
			'content' => esc_html__( 'Use Read More Tag', 'apparel' ),
		),
		'active_callback' => array(
			array(
				array(
					'setting'  => 'archive_layout',
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
		'settings' => 'archive_pagination_type',
		'label'    => esc_html__( 'Pagination', 'apparel' ),
		'section'  => 'archive_settings',
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
		'settings'    => 'archive_collapsible_post_meta',
		'section'     => 'archive_settings',
		'label'       => esc_html__( 'Post Meta', 'apparel' ),
		'input_attrs' => array(
			'collapsed' => false,
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'     => 'multicheck',
		'settings' => 'archive_post_meta',
		'label'    => esc_html__( 'Post Meta', 'apparel' ),
		'section'  => 'archive_settings',
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
		'settings'        => 'archive_collapsible_number_of_olumns',
		'section'         => 'archive_settings',
		'label'           => esc_html__( 'Number of Columns', 'apparel' ),
		'input_attrs'     => array(
			'collapsed' => false,
		),
		'active_callback' => array(
			array(
				'setting'  => 'archive_layout',
				'operator' => '==',
				'value'    => 'grid',
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'            => 'number',
		'settings'        => 'archive_columns_desktop',
		'label'           => esc_html__( 'Desktop', 'apparel' ),
		'section'         => 'archive_settings',
		'default'         => 4,
		'input_attrs'     => array(
			'min'  => 1,
			'max'  => 3,
			'step' => 1,
		),
		'output'          => array(
			array(
				'element'  => '.mbf-posts-area__archive.mbf-posts-area__grid',
				'property' => '--mbf-posts-area-grid-columns',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'archive_layout',
				'operator' => '==',
				'value'    => 'grid',
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'            => 'number',
		'settings'        => 'archive_columns_notebook',
		'label'           => esc_html__( 'Notebook', 'apparel' ),
		'section'         => 'archive_settings',
		'default'         => 3,
		'input_attrs'     => array(
			'min'  => 1,
			'max'  => 4,
			'step' => 1,
		),
		'output'          => array(
			array(
				'element'     => '.mbf-posts-area__archive.mbf-posts-area__grid',
				'property'    => '--mbf-posts-area-grid-columns',
				'media_query' => '@media (max-width: 1199.98px)',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'archive_layout',
				'operator' => '==',
				'value'    => 'grid',
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'            => 'number',
		'settings'        => 'archive_columns_tablet',
		'label'           => esc_html__( 'Tablet', 'apparel' ),
		'section'         => 'archive_settings',
		'default'         => 2,
		'input_attrs'     => array(
			'min'  => 1,
			'max'  => 4,
			'step' => 1,
		),
		'output'          => array(
			array(
				'element'     => '.mbf-posts-area__archive.mbf-posts-area__grid',
				'property'    => '--mbf-posts-area-grid-columns',
				'media_query' => '@media (max-width: 991.98px)',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'archive_layout',
				'operator' => '==',
				'value'    => 'grid',
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'            => 'number',
		'settings'        => 'archive_columns_mobile',
		'label'           => esc_html__( 'Mobile', 'apparel' ),
		'section'         => 'archive_settings',
		'default'         => 1,
		'input_attrs'     => array(
			'min'  => 1,
			'max'  => 4,
			'step' => 1,
		),
		'output'          => array(
			array(
				'element'     => '.mbf-posts-area__archive.mbf-posts-area__grid',
				'property'    => '--mbf-posts-area-grid-columns',
				'media_query' => '@media (max-width: 575.98px)',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'archive_layout',
				'operator' => '==',
				'value'    => 'grid',
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'        => 'collapsible',
		'settings'    => 'archive_collapsible_gap_between_rows',
		'section'     => 'archive_settings',
		'label'       => esc_html__( 'Gap between Rows', 'apparel' ),
		'input_attrs' => array(
			'collapsed' => false,
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'     => 'dimension',
		'settings' => 'archive_gap_between_rows_desktop',
		'label'    => esc_html__( 'Desktop', 'apparel' ),
		'section'  => 'archive_settings',
		'default'  => '60px',
		'output'   => array(
			array(
				'element'  => '.mbf-posts-area__archive',
				'property' => '--mbf-posts-area-grid-row-gap',
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'     => 'dimension',
		'settings' => 'archive_gap_between_rows_notebook',
		'label'    => esc_html__( 'Notebook', 'apparel' ),
		'section'  => 'archive_settings',
		'default'  => '60px',
		'output'   => array(
			array(
				'element'     => '.mbf-posts-area__archive',
				'property'    => '--mbf-posts-area-grid-row-gap',
				'media_query' => '@media (max-width: 1199.98px)',
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'     => 'dimension',
		'settings' => 'archive_gap_between_rows_tablet',
		'label'    => esc_html__( 'Tablet', 'apparel' ),
		'section'  => 'archive_settings',
		'default'  => '40px',
		'output'   => array(
			array(
				'element'     => '.mbf-posts-area__archive',
				'property'    => '--mbf-posts-area-grid-row-gap',
				'media_query' => '@media (max-width: 991.98px)',
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'     => 'dimension',
		'settings' => 'archive_gap_between_rows_mobile',
		'label'    => esc_html__( 'Mobile', 'apparel' ),
		'section'  => 'archive_settings',
		'default'  => '40px',
		'output'   => array(
			array(
				'element'     => '.mbf-posts-area__archive',
				'property'    => '--mbf-posts-area-grid-row-gap',
				'media_query' => '@media (max-width: 575.98px)',
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'            => 'collapsible',
		'settings'        => 'archive_collapsible_gap_between_columns',
		'section'         => 'archive_settings',
		'label'           => esc_html__( 'Gap between Columns', 'apparel' ),
		'input_attrs'     => array(
			'collapsed' => false,
		),
		'active_callback' => array(
			array(
				'setting'  => 'archive_layout',
				'operator' => '==',
				'value'    => 'grid',
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'            => 'dimension',
		'settings'        => 'archive_gap_between_columns_desktop',
		'label'           => esc_html__( 'Desktop', 'apparel' ),
		'section'         => 'archive_settings',
		'default'         => '24px',
		'output'          => array(
			array(
				'element'  => '.mbf-posts-area__archive.mbf-posts-area__grid',
				'property' => '--mbf-posts-area-grid-column-gap',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'archive_layout',
				'operator' => '==',
				'value'    => 'grid',
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'            => 'dimension',
		'settings'        => 'archive_gap_between_columns_notebook',
		'label'           => esc_html__( 'Notebook', 'apparel' ),
		'section'         => 'archive_settings',
		'default'         => '24px',
		'output'          => array(
			array(
				'element'     => '.mbf-posts-area__archive.mbf-posts-area__grid',
				'property'    => '--mbf-posts-area-grid-column-gap',
				'media_query' => '@media (max-width: 1199.98px)',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'archive_layout',
				'operator' => '==',
				'value'    => 'grid',
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'            => 'dimension',
		'settings'        => 'archive_gap_between_columns_tablet',
		'label'           => esc_html__( 'Tablet', 'apparel' ),
		'section'         => 'archive_settings',
		'default'         => '24px',
		'output'          => array(
			array(
				'element'     => '.mbf-posts-area__archive.mbf-posts-area__grid',
				'property'    => '--mbf-posts-area-grid-column-gap',
				'media_query' => '@media (max-width: 991.98px)',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'archive_layout',
				'operator' => '==',
				'value'    => 'grid',
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'            => 'dimension',
		'settings'        => 'archive_gap_between_columns_mobile',
		'label'           => esc_html__( 'Mobile', 'apparel' ),
		'section'         => 'archive_settings',
		'default'         => '24px',
		'output'          => array(
			array(
				'element'     => '.mbf-posts-area__archive.mbf-posts-area__grid',
				'property'    => '--mbf-posts-area-grid-column-gap',
				'media_query' => '@media (max-width: 575.98px)',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'archive_layout',
				'operator' => '==',
				'value'    => 'grid',
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'        => 'collapsible',
		'settings'    => 'archive_collapsible_title_size',
		'section'     => 'archive_settings',
		'label'       => esc_html__( 'Title Font Size', 'apparel' ),
		'input_attrs' => array(
			'collapsed' => false,
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'     => 'dimension',
		'settings' => 'archive_title_size_desktop',
		'label'    => esc_html__( 'Desktop', 'apparel' ),
		'section'  => 'archive_settings',
		'output'   => array(
			array(
				'element'  => '.mbf-posts-area__archive',
				'property' => '--mbf-entry-title-font-size',
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'     => 'dimension',
		'settings' => 'archive_title_size_notebook',
		'label'    => esc_html__( 'Notebook', 'apparel' ),
		'section'  => 'archive_settings',
		'output'   => array(
			array(
				'element'     => '.mbf-posts-area__archive',
				'property'    => '--mbf-entry-title-font-size',
				'media_query' => '@media (max-width: 1199.98px)',
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'     => 'dimension',
		'settings' => 'archive_title_size_tablet',
		'label'    => esc_html__( 'Tablet', 'apparel' ),
		'section'  => 'archive_settings',
		'output'   => array(
			array(
				'element'     => '.mbf-posts-area__archive',
				'property'    => '--mbf-entry-title-font-size',
				'media_query' => '@media (max-width: 991.98px)',
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'     => 'dimension',
		'settings' => 'archive_title_size_mobile',
		'label'    => esc_html__( 'Mobile', 'apparel' ),
		'section'  => 'archive_settings',
		'output'   => array(
			array(
				'element'     => '.mbf-posts-area__archive',
				'property'    => '--mbf-entry-title-font-size',
				'media_query' => '@media (max-width: 575.98px)',
			),
		),
	)
);
