<?php
/**
 * Typography
 *
 * @package Apparel
 */

MBF_Customizer::add_panel(
	'typography',
	array(
		'title' => esc_html__( 'Typography', 'apparel' ),
	)
);

MBF_Customizer::add_section(
	'typography_general',
	array(
		'title' => esc_html__( 'General', 'apparel' ),
		'panel' => 'typography',
	)
);

MBF_Customizer::add_field(
	array(
		'type'     => 'typography',
		'settings' => 'font_base',
		'label'    => esc_html__( 'Base Font', 'apparel' ),
		'section'  => 'typography_general',
		'default'  => array(
			'font-family'    => 'Manrope',
			'variant'        => '600',
			'subsets'        => array( 'latin' ),
			'font-size'      => '1rem',
			'letter-spacing' => 'normal',
			'line-height'    => '1.5',
		),
		'choices'  => array(
			'variant' => array(
				'regular',
				'italic',
				'500italic',
				'500',
				'600',
				'700',
				'700italic',
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'        => 'typography',
		'settings'    => 'font_primary',
		'label'       => esc_html__( 'Primary Font', 'apparel' ),
		'description' => esc_html__( 'Used for buttons, image captions and tags and other elements.', 'apparel' ),
		'section'     => 'typography_general',
		'default'     => array(
			'font-family'    => 'Manrope',
			'variant'        => '700',
			'subsets'        => array( 'latin' ),
			'font-size'      => '0.75rem',
			'letter-spacing' => 'normal',
			'text-transform' => 'none',
		),
		'choices'     => array(
			'variant' => array(
				'regular',
				'italic',
				'500',
				'500italic',
				'700',
				'700italic',
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'        => 'typography',
		'settings'    => 'font_secondary',
		'label'       => esc_html__( 'Secondary Font', 'apparel' ),
		'section'     => 'typography_general',
		'default'     => array(
			'font-family'    => 'Manrope',
			'variant'        => '700',
			'subsets'        => array( 'latin' ),
			'font-size'      => '0.875rem',
			'letter-spacing' => 'normal',
			'text-transform' => 'none',
		),
		'choices'     => array(
			'variant' => array(
				'regular',
				'italic',
				'500',
				'500italic',
				'700',
				'700italic',
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'     => 'typography',
		'settings' => 'font_category',
		'label'    => esc_html__( 'Category Font', 'apparel' ),
		'section'  => 'typography_general',
		'default'  => array(
			'font-family'    => 'Manrope',
			'variant'        => '600',
			'subsets'        => array( 'latin' ),
			'font-size'      => '0.625rem',
			'letter-spacing' => 'normal',
			'text-transform' => 'none',
		),
		'choices'  => array(),
	)
);

MBF_Customizer::add_field(
	array(
		'type'     => 'typography',
		'settings' => 'font_post_meta',
		'label'    => esc_html__( 'Post Meta Font', 'apparel' ),
		'section'  => 'typography_general',
		'default'  => array(
			'font-family'    => 'Manrope',
			'variant'        => '700',
			'subsets'        => array( 'latin' ),
			'font-size'      => '0.75rem',
			'letter-spacing' => 'normal',
			'text-transform' => 'none',
		),
		'choices'  => array(
			'variant' => array(
				'regular',
				'italic',
				'500',
				'500italic',
				'700',
				'700italic',
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'     => 'typography',
		'settings' => 'font_input',
		'label'    => esc_html__( 'Input Font', 'apparel' ),
		'section'  => 'typography_general',
		'default'  => array(
			'font-family'    => 'Manrope',
			'variant'        => '700',
			'subsets'        => array( 'latin' ),
			'font-size'      => '0.75rem',
			'line-height'    => '1.625rem',
			'letter-spacing' => 'normal',
			'text-transform' => 'none',
		),
		'choices'  => array(),
	)
);

MBF_Customizer::add_field(
	array(
		'type'     => 'typography',
		'settings' => 'font_post_subtitle',
		'label'    => esc_html__( 'Post Subtitle Font', 'apparel' ),
		'section'  => 'typography_general',
		'default'  => array(
			'font-family'    => 'inherit',
			'subsets'        => array( 'latin' ),
			'font-size'      => '1.25rem',
			'letter-spacing' => 'normal',
		),
		'choices'  => array(
			'variant' => array(
				'regular',
				'italic',
				'500italic',
				'500',
				'600',
				'600italic',
				'700',
				'700italic',
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'     => 'typography',
		'settings' => 'font_post_content',
		'label'    => esc_html__( 'Post Content Font', 'apparel' ),
		'section'  => 'typography_general',
		'default'  => array(
			'font-family'    => 'Manrope',
			'subsets'        => array( 'latin' ),
			'font-size'      => '1rem',
			'letter-spacing' => 'normal',
			'line-height'    => '1.6',
		),
		'choices'  => array(
			'variant' => array(
				'regular',
				'italic',
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'     => 'typography',
		'settings' => 'font_excerpt',
		'label'    => esc_html__( 'Entry Excerpt Font', 'apparel' ),
		'section'  => 'typography_general',
		'default'  => array(
			'font-family'    => 'Manrope',
			'subsets'        => array( 'latin' ),
			'font-size'      => '0.75rem',
			'variant'        => '700',
			'letter-spacing' => 'normal',
			'line-height'    => '1.4',
		),
		'choices'  => array(
			'variant' => array(
				'700',
				'700italic',
			),
		),
	)
);

MBF_Customizer::add_section(
	'typography_logos',
	array(
		'title' => esc_html__( 'Logos', 'apparel' ),
		'panel' => 'typography',
	)
);

MBF_Customizer::add_field(
	array(
		'type'            => 'typography',
		'settings'        => 'font_main_logo',
		'label'           => esc_html__( 'Main Logo', 'apparel' ),
		'description'     => esc_html__( 'The main logo is used in the navigation bar and mobile view of your website.', 'apparel' ),
		'section'         => 'typography_logos',
		'default'         => array(
			'font-family'    => 'Manrope',
			'font-size'      => '1.375rem',
			'variant'        => '600',
			'subsets'        => array( 'latin' ),
			'letter-spacing' => '-0.04em',
			'text-transform' => 'none',
		),
		'choices'         => array(),
		'active_callback' => array(
			array(
				'setting'  => 'logo',
				'operator' => '==',
				'value'    => '',
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'            => 'typography',
		'settings'        => 'font_footer_logo',
		'label'           => esc_html__( 'Footer Logo', 'apparel' ),
		'description'     => esc_html__( 'The footer logo is used in the site footer in desktop and mobile view.', 'apparel' ),
		'section'         => 'typography_logos',
		'default'         => array(
			'font-family'    => 'Manrope',
			'font-size'      => '1.375rem',
			'variant'        => '600',
			'subsets'        => array( 'latin' ),
			'letter-spacing' => '-0.04em',
			'text-transform' => 'none',
		),
		'choices'         => array(),
		'active_callback' => array(
			array(
				'setting'  => 'footer_logo',
				'operator' => '==',
				'value'    => '',
			),
		),
	)
);

MBF_Customizer::add_section(
	'typography_headings',
	array(
		'title' => esc_html__( 'Headings', 'apparel' ),
		'panel' => 'typography',
	)
);

MBF_Customizer::add_field(
	array(
		'type'     => 'typography',
		'settings' => 'font_headings',
		'label'    => esc_html__( 'Headings', 'apparel' ),
		'section'  => 'typography_headings',
		'default'  => array(
			'font-family'    => 'Manrope',
			'variant'        => '500',
			'subsets'        => array( 'latin' ),
			'letter-spacing' => 'normal',
			'text-transform' => 'none',
			'line-height'    => '1.2',
		),
		'choices'  => array(),
	)
);

MBF_Customizer::add_section(
	'typography_navigation',
	array(
		'title' => esc_html__( 'Navigation', 'apparel' ),
		'panel' => 'typography',
	)
);

MBF_Customizer::add_field(
	array(
		'type'        => 'typography',
		'settings'    => 'font_menu',
		'label'       => esc_html__( 'Menu Font', 'apparel' ),
		'description' => esc_html__( 'Used for main top level menu elements.', 'apparel' ),
		'section'     => 'typography_navigation',
		'default'     => array(
			'font-family'    => 'Manrope',
			'variant'        => '700',
			'subsets'        => array( 'latin' ),
			'font-size'      => '0.75rem',
			'letter-spacing' => 'normal',
			'text-transform' => 'none',
		),
		'choices'     => array(),
	)
);

MBF_Customizer::add_field(
	array(
		'type'        => 'typography',
		'settings'    => 'font_submenu',
		'label'       => esc_html__( 'Submenu Font', 'apparel' ),
		'description' => esc_html__( 'Used for submenu elements.', 'apparel' ),
		'section'     => 'typography_navigation',
		'default'     => array(
			'font-family'    => 'Manrope',
			'subsets'        => array( 'latin' ),
			'variant'        => '700',
			'font-size'      => '0.75rem',
			'letter-spacing' => 'normal',
			'text-transform' => 'none',
		),
		'choices'     => array(),
	)
);
