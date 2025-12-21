<?php
/**
 * Footer Settings
 *
 * @package Apparel
 */

MBF_Customizer::add_section(
	'footer',
	array(
		'title' => esc_html__( 'Footer Settings', 'apparel' ),
	)
);

MBF_Customizer::add_field(
	array(
		'type'        => 'collapsible',
		'settings'    => 'footer_collapsible_common',
		'label'       => esc_html__( 'Common', 'apparel' ),
		'section'     => 'footer',
		'input_attrs' => array(
			'collapsed' => true,
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'              => 'textarea',
		'settings'          => 'footer_text',
		'label'             => esc_html__( 'Footer Text', 'apparel' ),
		'section'           => 'footer',
		'sanitize_callback' => function( $val ) {
			return wp_kses( $val, 'content' );
		},
	)
);

MBF_Customizer::add_field(
	array(
		'type'              => 'textarea',
		'settings'          => 'footer_copyright',
		'label'             => esc_html__( 'Footer Copyright', 'apparel' ),
		'section'           => 'footer',
		'default'           => '©️ 2023 - All Rights Reserved.',
		'sanitize_callback' => function( $val ) {
			return wp_kses( $val, 'content' );
		},
	)
);

MBF_Customizer::add_field(
	array(
		'type'        => 'image',
		'settings'    => 'footer_promo_image',
		'label'       => esc_html__( 'Footer Promo Image', 'apparel' ),
		'description' => esc_html__( 'Please upload the 2x version of your logo via Media Library with ', 'apparel' ) . '<code>@2x</code>' . esc_html__( ' suffix for supporting Retina screens. For example ', 'apparel' ) . '<code>promo@2x.png</code>' . esc_html__( '. Recommended width and height is 45px (90px for Retina version).', 'apparel' ),
		'section'     => 'footer',
		'default'     => '',
	)
);

MBF_Customizer::add_field(
	array(
		'type'        => 'collapsible',
		'settings'    => 'footer_collapsible_subscribe',
		'label'       => esc_html__( 'Subscribe', 'apparel' ),
		'section'     => 'footer',
		'input_attrs' => array(
			'collapsed' => false,
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'     => 'checkbox',
		'settings' => 'footer_subscribe',
		'label'    => esc_html__( 'Display subscribe section', 'apparel' ),
		'section'  => 'footer',
		'default'  => false,
	)
);

MBF_Customizer::add_field(
	array(
		'type'            => 'text',
		'settings'        => 'footer_subscribe_title',
		'label'           => esc_html__( 'Title', 'apparel' ),
		'section'         => 'footer',
		'active_callback' => array(
			array(
				'setting'  => 'footer_subscribe',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'            => 'text',
		'settings'        => 'footer_subscribe_mailchimp',
		'label'           => esc_html__( 'Mailchimp Form Link', 'apparel' ),
		'section'         => 'footer',
		'active_callback' => array(
			array(
				'setting'  => 'footer_subscribe',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);
