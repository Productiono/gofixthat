<?php
/**
 * Post Settings
 *
 * @package Apparel
 */

MBF_Customizer::add_section(
	'post_settings',
	array(
		'title' => esc_html__( 'Post Settings', 'apparel' ),
	)
);

MBF_Customizer::add_field(
	array(
		'type'        => 'collapsible',
		'settings'    => 'post_collapsible_common',
		'section'     => 'post_settings',
		'label'       => esc_html__( 'Common', 'apparel' ),
		'input_attrs' => array(
			'collapsed' => true,
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'     => 'multicheck',
		'settings' => 'post_meta',
		'label'    => esc_html__( 'Post Meta', 'apparel' ),
		'section'  => 'post_settings',
		'default'  => array( 'date', 'author', 'category' ),
		'choices'  => array(
			'category' => esc_html__( 'Category', 'apparel' ),
			'date'     => esc_html__( 'Date', 'apparel' ),
			'author'   => esc_html__( 'Author', 'apparel' ),
			'comments' => esc_html__( 'Comments', 'apparel' ),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'     => 'radio',
		'settings' => 'post_header_type',
		'label'    => esc_html__( 'Default Page Header Type', 'apparel' ),
		'section'  => 'post_settings',
		'default'  => 'standard',
		'choices'  => array(
			'standard' => esc_html__( 'Standard', 'apparel' ),
			'title'    => esc_html__( 'Page Title Only', 'apparel' ),
			'none'     => esc_html__( 'None', 'apparel' ),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'     => 'checkbox',
		'settings' => 'post_subtitle',
		'label'    => esc_html__( 'Display excerpt as post subtitle', 'apparel' ),
		'section'  => 'post_settings',
		'default'  => true,
	)
);

MBF_Customizer::add_field(
	array(
		'type'     => 'checkbox',
		'settings' => 'post_tags',
		'label'    => esc_html__( 'Display tags', 'apparel' ),
		'section'  => 'post_settings',
		'default'  => true,
	)
);

MBF_Customizer::add_field(
	array(
		'type'     => 'checkbox',
		'settings' => 'post_footer',
		'label'    => esc_html__( 'Display post footer', 'apparel' ),
		'section'  => 'post_settings',
		'default'  => true,
	)
);

MBF_Customizer::add_field(
	array(
		'type'     => 'collapsible',
		'settings' => 'post_collapsible_prev_next',
		'section'  => 'post_settings',
		'label'    => esc_html__( 'Prev Next Links', 'apparel' ),
	)
);

MBF_Customizer::add_field(
	array(
		'type'     => 'checkbox',
		'settings' => 'post_prev_next',
		'label'    => esc_html__( 'Display prev next links', 'apparel' ),
		'section'  => 'post_settings',
		'default'  => true,
	)
);

MBF_Customizer::add_field(
	array(
		'type'            => 'select',
		'settings'        => 'post_prev_next_type_image_orientation',
		'label'           => esc_html__( 'Image Orientation', 'apparel' ),
		'section'         => 'post_settings',
		'default'         => 'square',
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
				'setting'  => 'post_prev_next',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'            => 'select',
		'settings'        => 'post_prev_next_type_image_size',
		'label'           => esc_html__( 'Image Size', 'apparel' ),
		'section'         => 'post_settings',
		'default'         => 'mbf-small',
		'choices'         => mbf_get_list_available_image_sizes(),
		'active_callback' => array(
			array(
				'setting'  => 'post_prev_next',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'            => 'dimension',
		'settings'        => 'post_prev_next_image_border_radius',
		'label'           => esc_html__( 'Image Border Radius', 'apparel' ),
		'section'         => 'post_settings',
		'default'         => '3px',
		'output'          => array(
			array(
				'element'  => '.mbf-entry__prev-next',
				'property' => '--mbf-thumbnail-border-radius',
				'suffix'   => '!important',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'post_prev_next',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'            => 'multicheck',
		'settings'        => 'post_prev_next_meta',
		'label'           => esc_html__( 'Post Meta', 'apparel' ),
		'section'         => 'post_settings',
		'default'         => array( 'author' ),
		'choices'         => array(
			'date'     => esc_html__( 'Date', 'apparel' ),
			'author'   => esc_html__( 'Author', 'apparel' ),
			'comments' => esc_html__( 'Comments', 'apparel' ),
		),
		'active_callback' => array(
			array(
				'setting'  => 'post_prev_next',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);
