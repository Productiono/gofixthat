<?php
/**
 * Service Settings
 *
 * @package Apparel
 */

MBF_Customizer::add_section(
	'service_settings',
	array(
		'title' => esc_html__( 'Service Settings', 'apparel' ),
	)
);

$service_form_fields = array(
	'service_support_form_documentation' => esc_html__( 'Documentation — Fluent Form ID', 'apparel' ),
	'service_support_form_presales'      => esc_html__( 'Pre-Sales Questions? — Fluent Form ID', 'apparel' ),
	'service_support_form_hire'          => esc_html__( 'Hire Us — Fluent Form ID', 'apparel' ),
	'service_support_form_support'       => esc_html__( 'Get Support — Fluent Form ID', 'apparel' ),
);

foreach ( $service_form_fields as $setting => $label ) {
	MBF_Customizer::add_field(
		array(
			'type'              => 'number',
			'settings'          => $setting,
			'label'             => $label,
			'section'           => 'service_settings',
			'default'           => '',
			'sanitize_callback' => 'absint',
			'input_attrs'       => array(
				'min'  => 1,
				'step' => 1,
			),
		)
	);
}
