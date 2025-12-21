<?php
/**
 * Miscellaneous Settings
 *
 * @package Apparel
 */

MBF_Customizer::add_section(
	'miscellaneous',
	array(
		'title' => esc_html__( 'Miscellaneous Settings', 'apparel' ),
	)
);

MBF_Customizer::add_field(
	array(
		'type'              => 'textarea',
		'settings'          => 'misc_notification_bar',
		'label'             => esc_html__( 'Notification Bar', 'apparel' ),
		'section'           => 'miscellaneous',
		'sanitize_callback' => function( $val ) {
			return wp_kses( $val, 'content' );
		},
	)
);

MBF_Customizer::add_field(
	array(
		'type'     => 'checkbox',
		'settings' => 'misc_sticky_sidebar',
		'label'    => esc_html__( 'Sticky Default Sidebar', 'apparel' ),
		'section'  => 'miscellaneous',
		'default'  => true,
	)
);

MBF_Customizer::add_field(
	array(
		'type'            => 'radio',
		'settings'        => 'misc_sticky_sidebar_method',
		'label'           => esc_html__( 'Sticky Method', 'apparel' ),
		'section'         => 'miscellaneous',
		'default'         => 'mbf-stick-to-top',
		'choices'         => array(
			'mbf-stick-to-top'    => esc_html__( 'Sidebar top edge', 'apparel' ),
			'mbf-stick-to-bottom' => esc_html__( 'Sidebar bottom edge', 'apparel' ),
			'mbf-stick-last'      => esc_html__( 'Last widget top edge', 'apparel' ),
		),
		'active_callback' => array(
			array(
				'setting'  => 'misc_sticky_sidebar',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);
