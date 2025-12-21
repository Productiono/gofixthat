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
		'font-family'    => 'Inter',
		'variant'        => '400',
		'subsets'        => array( 'latin' ),
		'font-size'      => 'clamp(1rem, 0.98rem + 0.35vw, 1.125rem)',
		'letter-spacing' => '0.01em',
		'line-height'    => '1.6',
	),
	'choices'  => array(
		'variant' => array(
			'300',
			'300italic',
			'400',
			'400italic',
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
		'font-family'    => 'Inter',
		'variant'        => '600',
		'subsets'        => array( 'latin' ),
		'font-size'      => 'clamp(0.95rem, 0.9rem + 0.25vw, 1.05rem)',
		'letter-spacing' => '0.01em',
		'text-transform' => 'none',
	),
	'choices'     => array(
		'variant' => array(
			'300',
			'300italic',
			'400',
			'400italic',
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
		'font-family'    => 'Inter',
		'variant'        => '500',
		'subsets'        => array( 'latin' ),
		'font-size'      => 'clamp(1rem, 0.95rem + 0.25vw, 1.1rem)',
		'letter-spacing' => '0.01em',
		'text-transform' => 'none',
	),
	'choices'     => array(
		'variant' => array(
			'300',
			'300italic',
			'400',
			'400italic',
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
		'font-family'    => 'Inter',
		'variant'        => '600',
		'subsets'        => array( 'latin' ),
		'font-size'      => 'clamp(0.85rem, 0.8rem + 0.15vw, 0.95rem)',
		'letter-spacing' => '0.02em',
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
		'font-family'    => 'Inter',
		'variant'        => '500',
		'subsets'        => array( 'latin' ),
		'font-size'      => 'clamp(0.9rem, 0.85rem + 0.2vw, 1rem)',
		'letter-spacing' => '0.02em',
		'text-transform' => 'none',
	),
	'choices'  => array(
		'variant' => array(
			'300',
			'300italic',
			'400',
			'400italic',
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
		'font-family'    => 'Inter',
		'variant'        => '500',
		'subsets'        => array( 'latin' ),
		'font-size'      => 'clamp(0.95rem, 0.9rem + 0.25vw, 1.05rem)',
		'line-height'    => 'clamp(1.5rem, 1.1rem + 0.7vw, 1.8rem)',
		'letter-spacing' => '0.01em',
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
		'font-size'      => 'clamp(1.25rem, 1.15rem + 0.3vw, 1.5rem)',
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
		'font-family'    => 'Inter',
		'subsets'        => array( 'latin' ),
		'font-size'      => 'clamp(1.0625rem, 1rem + 0.35vw, 1.2rem)',
		'variant'        => '400',
		'letter-spacing' => '0.01em',
		'line-height'    => '1.7',
	),
	'choices'  => array(
		'variant' => array(
			'regular',
			'italic',
			'500',
			'500italic',
			'300',
			'300italic',
			'400',
			'400italic',
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
		'font-family'    => 'Inter',
		'subsets'        => array( 'latin' ),
		'font-size'      => 'clamp(0.95rem, 0.9rem + 0.25vw, 1.05rem)',
		'variant'        => '600',
		'letter-spacing' => '0.01em',
		'line-height'    => '1.6',
	),
	'choices'  => array(
		'variant' => array(
			'300',
			'300italic',
			'400',
			'400italic',
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
		'font-family'    => 'Inter',
		'font-size'      => 'clamp(1.5rem, 1.3rem + 0.3vw, 1.8rem)',
		'variant'        => '700',
		'subsets'        => array( 'latin' ),
		'letter-spacing' => '-0.02em',
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
		'font-family'    => 'Inter',
		'font-size'      => 'clamp(1.5rem, 1.3rem + 0.3vw, 1.8rem)',
		'variant'        => '700',
		'subsets'        => array( 'latin' ),
		'letter-spacing' => '-0.02em',
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
		'font-family'    => 'Inter',
		'variant'        => '600',
		'subsets'        => array( 'latin' ),
		'letter-spacing' => '-0.01em',
		'text-transform' => 'none',
		'line-height'    => '1.15',
	),
	'choices'  => array(
		'variant' => array(
			'300',
			'300italic',
			'400',
			'400italic',
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
		'font-family'    => 'Inter',
		'variant'        => '600',
		'subsets'        => array( 'latin' ),
		'font-size'      => 'clamp(0.95rem, 0.9rem + 0.25vw, 1.05rem)',
		'letter-spacing' => '0.01em',
		'text-transform' => 'none',
	),
	'choices'     => array(
		'variant' => array(
			'300',
			'300italic',
			'400',
			'400italic',
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
		'type'        => 'typography',
		'settings'    => 'font_submenu',
	'label'       => esc_html__( 'Submenu Font', 'apparel' ),
	'description' => esc_html__( 'Used for submenu elements.', 'apparel' ),
	'section'     => 'typography_navigation',
	'default'     => array(
		'font-family'    => 'Inter',
		'subsets'        => array( 'latin' ),
		'variant'        => '500',
		'font-size'      => 'clamp(0.95rem, 0.9rem + 0.25vw, 1.05rem)',
		'letter-spacing' => '0.01em',
		'text-transform' => 'none',
	),
	'choices'     => array(
		'variant' => array(
			'300',
			'300italic',
			'400',
			'400italic',
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
