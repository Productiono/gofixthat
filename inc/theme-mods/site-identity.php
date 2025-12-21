<?php
/**
 * Site Identity
 *
 * @package Apparel
 */

MBF_Customizer::add_field(
	array(
		'type'        => 'image',
		'settings'    => 'logo',
		'label'       => esc_html__( 'Main Logo', 'apparel' ),
		'description' => esc_html__( 'The main logo is used in the navigation bar and mobile view of your website. Logo image will be displayed in its original image dimensions. Please upload the 2x version of your logo via Media Library with ', 'apparel' ) . '<code>@2x</code>' . esc_html__( ' suffix for supporting Retina screens. For example ', 'apparel' ) . '<code>logo@2x.png</code>' . esc_html__( '. Recommended maximum height is 40px (80px for Retina version).', 'apparel' ),
		'section'     => 'title_tagline',
		'default'     => '',
	)
);

MBF_Customizer::add_field(
	array(
		'type'            => 'image',
		'settings'        => 'logo_dark',
		'label'           => esc_html__( 'Main Logo for Dark Mode', 'apparel' ),
		'section'         => 'title_tagline',
		'default'         => '',
		'active_callback' => array(
			array(
				'setting'  => 'logo',
				'operator' => '!=',
				'value'    => '',
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'        => 'image',
		'settings'    => 'footer_logo',
		'label'       => esc_html__( 'Footer Logo', 'apparel' ),
		'description' => esc_html__( 'The footer logo is used in the site footer in desktop and mobile view. Similar to the main logo, upload the 2x version of your logo via Media Library with ', 'apparel' ) . '<code>@2x</code>' . esc_html__( ' suffix for supporting Retina screens. For example ', 'apparel' ) . '<code>logo-footer@2x.png</code>' . esc_html__( '. Recommended maximum height is 80px (160px for Retina version).', 'apparel' ),
		'section'     => 'title_tagline',
		'default'     => '',
	)
);

MBF_Customizer::add_field(
	array(
		'type'            => 'image',
		'settings'        => 'footer_logo_dark',
		'label'           => esc_html__( 'Footer Logo for Dark Mode', 'apparel' ),
		'section'         => 'title_tagline',
		'default'         => '',
		'active_callback' => array(
			array(
				'setting'  => 'footer_logo',
				'operator' => '!=',
				'value'    => '',
			),
		),
	)
);
