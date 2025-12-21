<?php
/**
 * Page Settings
 *
 * @package Apparel
 */

MBF_Customizer::add_section(
	'page_settings',
	array(
		'title' => esc_html__( 'Page Settings', 'apparel' ),
	)
);

MBF_Customizer::add_field(
	array(
		'type'     => 'radio',
		'settings' => 'page_header_type',
		'label'    => esc_html__( 'Page Header Type', 'apparel' ),
		'section'  => 'page_settings',
		'default'  => 'standard',
		'choices'  => array(
			'standard' => esc_html__( 'Standard', 'apparel' ),
			'title'    => esc_html__( 'Page Title Only', 'apparel' ),
			'none'     => esc_html__( 'None', 'apparel' ),
		),
	)
);
