<?php
/**
 * Colors
 *
 * @package Apparel
 */

MBF_Customizer::add_panel(
	'colors',
	array(
		'title' => esc_html__( 'Colors', 'apparel' ),
	)
);

MBF_Customizer::add_field(
	array(
		'type'     => 'radio',
		'settings' => 'color_scheme',
		'label'    => esc_html__( 'Site Color Scheme', 'apparel' ),
		'section'  => 'colors',
		'default'  => 'system',
		'choices'  => array(
			'system' => esc_html__( 'User’s system preference', 'apparel' ),
			'light'  => esc_html__( 'Light', 'apparel' ),
			'dark'   => esc_html__( 'Dark', 'apparel' ),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'     => 'checkbox',
		'settings' => 'color_scheme_toggle',
		'label'    => esc_html__( 'Enable dark/light mode toggle', 'apparel' ),
		'section'  => 'colors',
		'default'  => true,
	)
);

MBF_Customizer::add_field(
	array(
		'type'     => 'divider',
		'settings' => wp_unique_id( 'divider' ),
		'section'  => 'colors',
	)
);

MBF_Customizer::add_field(
	array(
		'type'     => 'color-alpha',
		'settings' => 'color_site_background',
		'label'    => esc_html__( 'Site Background', 'apparel' ),
		'section'  => 'colors',
		'default'  => '#FFFFFF',
		'alpha'    => true,
		'output'   => array(
			array(
				'element'  => ':root',
				'property' => '--mbf-light-site-background',
				'context'  => array( 'editor', 'front' ),
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'     => 'color-alpha',
		'settings' => 'color_site_background_is_dark',
		'label'    => esc_html__( 'Site Background Dark', 'apparel' ),
		'section'  => 'colors',
		'default'  => '#1c1c1c',
		'alpha'    => true,
		'output'   => array(
			array(
				'element'  => ':root',
				'property' => '--mbf-dark-site-background',
				'context'  => array( 'editor', 'front' ),
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'     => 'color-alpha',
		'settings' => 'color_layout_background',
		'label'    => esc_html__( 'Layout Background', 'apparel' ),
		'section'  => 'colors',
		'default'  => '#F7F7F7',
		'alpha'    => true,
		'output'   => array(
			array(
				'element'  => ':root',
				'property' => '--mbf-light-layout-background',
				'context'  => array( 'editor', 'front' ),
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'     => 'color-alpha',
		'settings' => 'color_layout_background_is_dark',
		'label'    => esc_html__( 'Layout Background Dark', 'apparel' ),
		'section'  => 'colors',
		'default'  => '#232323',
		'alpha'    => true,
		'output'   => array(
			array(
				'element'  => ':root',
				'property' => '--mbf-dark-layout-background',
				'context'  => array( 'editor', 'front' ),
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'     => 'color-alpha',
		'settings' => 'color_primary',
		'label'    => esc_html__( 'Primary Color', 'apparel' ),
		'section'  => 'colors',
		'default'  => '#202025',
		'alpha'    => true,
		'output'   => array(
			array(
				'element'  => ':root',
				'property' => '--mbf-light-primary-color',
				'context'  => array( 'editor', 'front' ),
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'     => 'color-alpha',
		'settings' => 'color_primary_color_is_dark',
		'label'    => esc_html__( 'Primary Color Dark', 'apparel' ),
		'section'  => 'colors',
		'default'  => '#FFFFFF',
		'alpha'    => true,
		'output'   => array(
			array(
				'element'  => ':root',
				'property' => '--mbf-dark-primary-color',
				'context'  => array( 'editor', 'front' ),
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'     => 'color-alpha',
		'settings' => 'color_secondary',
		'label'    => esc_html__( 'Secondary Color', 'apparel' ),
		'section'  => 'colors',
		'default'  => '#7E7E84',
		'alpha'    => true,
		'output'   => array(
			array(
				'element'  => ':root',
				'property' => '--mbf-light-secondary-color',
				'context'  => array( 'editor', 'front' ),
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'     => 'color-alpha',
		'settings' => 'color_secondary_color_is_dark',
		'label'    => esc_html__( 'Secondary Color Dark', 'apparel' ),
		'section'  => 'colors',
		'default'  => '#c4c4c4',
		'alpha'    => true,
		'output'   => array(
			array(
				'element'  => ':root',
				'property' => '--mbf-dark-secondary-color',
				'context'  => array( 'editor', 'front' ),
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'     => 'checkbox',
		'settings' => 'color_advanced_settings',
		'label'    => esc_html__( 'Display advanced color settings', 'apparel' ),
		'section'  => 'colors',
		'default'  => false,
	)
);

MBF_Customizer::add_field(
	array(
		'type'            => 'divider',
		'settings'        => wp_unique_id( 'divider' ),
		'section'         => 'colors',
		'active_callback' => array(
			array(
				'setting'  => 'color_advanced_settings',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'            => 'color-alpha',
		'settings'        => 'color_button_background',
		'label'           => esc_html__( 'Button Background', 'apparel' ),
		'section'         => 'colors',
		'default'         => '',
		'alpha'           => true,
		'output'          => array(
			array(
				'element'  => ':root',
				'property' => '--mbf-light-button-background',
				'context'  => array( 'editor', 'front' ),
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'color_advanced_settings',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'            => 'color-alpha',
		'settings'        => 'color_button_background_is_dark',
		'label'           => esc_html__( 'Button Background Dark', 'apparel' ),
		'section'         => 'colors',
		'default'         => '',
		'alpha'           => true,
		'output'          => array(
			array(
				'element'  => ':root',
				'property' => '--mbf-dark-button-background',
				'context'  => array( 'editor', 'front' ),
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'color_advanced_settings',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'            => 'color-alpha',
		'settings'        => 'color_button_border',
		'label'           => esc_html__( 'Button Border', 'apparel' ),
		'section'         => 'colors',
		'default'         => '#202025',
		'alpha'           => true,
		'output'          => array(
			array(
				'element'  => ':root',
				'property' => '--mbf-light-button-border',
				'context'  => array( 'editor', 'front' ),
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'color_advanced_settings',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'            => 'color-alpha',
		'settings'        => 'color_button_border_is_dark',
		'label'           => esc_html__( 'Button Border Dark', 'apparel' ),
		'section'         => 'colors',
		'default'         => '#929292',
		'alpha'           => true,
		'output'          => array(
			array(
				'element'  => ':root',
				'property' => '--mbf-dark-button-border',
				'context'  => array( 'editor', 'front' ),
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'color_advanced_settings',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'            => 'color-alpha',
		'settings'        => 'color_button',
		'label'           => esc_html__( 'Button Color', 'apparel' ),
		'section'         => 'colors',
		'default'         => '#202025',
		'alpha'           => true,
		'output'          => array(
			array(
				'element'  => ':root',
				'property' => '--mbf-light-button-color',
				'context'  => array( 'editor', 'front' ),
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'color_advanced_settings',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'            => 'color-alpha',
		'settings'        => 'color_button_color_is_dark',
		'label'           => esc_html__( 'Button Color Dark', 'apparel' ),
		'section'         => 'colors',
		'default'         => '#FFFFFF',
		'alpha'           => true,
		'output'          => array(
			array(
				'element'  => ':root',
				'property' => '--mbf-dark-button-color',
				'context'  => array( 'editor', 'front' ),
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'color_advanced_settings',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'            => 'color-alpha',
		'settings'        => 'color_button_background_hover',
		'label'           => esc_html__( 'Button Hover Background', 'apparel' ),
		'section'         => 'colors',
		'default'         => '#202025',
		'alpha'           => true,
		'output'          => array(
			array(
				'element'  => ':root',
				'property' => '--mbf-light-button-hover-background',
				'context'  => array( 'editor', 'front' ),
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'color_advanced_settings',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'            => 'color-alpha',
		'settings'        => 'color_button_background_hover_is_dark',
		'label'           => esc_html__( 'Button Hover Background Dark', 'apparel' ),
		'section'         => 'colors',
		'default'         => '#FFFFFF',
		'alpha'           => true,
		'output'          => array(
			array(
				'element'  => ':root',
				'property' => '--mbf-dark-button-hover-background',
				'context'  => array( 'editor', 'front' ),
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'color_advanced_settings',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'            => 'color-alpha',
		'settings'        => 'color_button_border_hover',
		'label'           => esc_html__( 'Button Hover Border', 'apparel' ),
		'section'         => 'colors',
		'default'         => '#202025',
		'alpha'           => true,
		'output'          => array(
			array(
				'element'  => ':root',
				'property' => '--mbf-light-button-hover-border',
				'context'  => array( 'editor', 'front' ),
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'color_advanced_settings',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'            => 'color-alpha',
		'settings'        => 'color_button_border_hover_is_dark',
		'label'           => esc_html__( 'Button Hover Border Dark', 'apparel' ),
		'section'         => 'colors',
		'default'         => '#FFFFFF',
		'alpha'           => true,
		'output'          => array(
			array(
				'element'  => ':root',
				'property' => '--mbf-dark-button-hover-border',
				'context'  => array( 'editor', 'front' ),
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'color_advanced_settings',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'            => 'color-alpha',
		'settings'        => 'color_button_hover',
		'label'           => esc_html__( 'Button Hover Color', 'apparel' ),
		'section'         => 'colors',
		'default'         => '#FFFFFF',
		'alpha'           => true,
		'output'          => array(
			array(
				'element'  => ':root',
				'property' => '--mbf-light-button-hover-color',
				'context'  => array( 'editor', 'front' ),
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'color_advanced_settings',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'            => 'color-alpha',
		'settings'        => 'color_button_hover_color_is_dark',
		'label'           => esc_html__( 'Button Hover Color Dark', 'apparel' ),
		'section'         => 'colors',
		'default'         => '#202025',
		'alpha'           => true,
		'output'          => array(
			array(
				'element'  => ':root',
				'property' => '--mbf-dark-button-hover-color',
				'context'  => array( 'editor', 'front' ),
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'color_advanced_settings',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'            => 'color-alpha',
		'settings'        => 'color_border',
		'label'           => esc_html__( 'Border Color', 'apparel' ),
		'description'     => esc_html__( 'Used on Form Inputs, Separators etc.', 'apparel' ),
		'section'         => 'colors',
		'default'         => '#D9D7DD',
		'alpha'           => true,
		'output'          => array(
			array(
				'element'  => ':root',
				'property' => '--mbf-light-border-color',
				'context'  => array( 'editor', 'front' ),
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'color_advanced_settings',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'            => 'color-alpha',
		'settings'        => 'color_border_color_is_dark',
		'label'           => esc_html__( 'Border Color Dark', 'apparel' ),
		'section'         => 'colors',
		'default'         => '#393939',
		'alpha'           => true,
		'output'          => array(
			array(
				'element'  => ':root',
				'property' => '--mbf-dark-border-color',
				'context'  => array( 'editor', 'front' ),
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'color_advanced_settings',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'            => 'color-alpha',
		'settings'        => 'color_overlay',
		'label'           => esc_html__( 'Overlay Background', 'apparel' ),
		'section'         => 'colors',
		'default'         => 'rgba(32, 32, 37, 0.2)',
		'alpha'           => true,
		'output'          => array(
			array(
				'element'  => ':root',
				'property' => '--mbf-light-overlay-background',
				'context'  => array( 'editor', 'front' ),
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'color_advanced_settings',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'            => 'color-alpha',
		'settings'        => 'color_overlay_color_is_dark',
		'label'           => esc_html__( 'Overlay Background Dark', 'apparel' ),
		'section'         => 'colors',
		'default'         => 'rgba(0,0,0, 0.25)',
		'alpha'           => true,
		'output'          => array(
			array(
				'element'  => ':root',
				'property' => '--mbf-dark-overlay-background',
				'context'  => array( 'editor', 'front' ),
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'color_advanced_settings',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'     => 'checkbox',
		'settings' => 'color_advanced_border_settings',
		'label'    => esc_html__( 'Display border radius settings', 'apparel' ),
		'section'  => 'colors',
		'default'  => false,
	)
);

MBF_Customizer::add_field(
	array(
		'type'            => 'divider',
		'settings'        => wp_unique_id( 'divider' ),
		'section'         => 'color_border_settings',
		'active_callback' => array(
			array(
				'setting'  => 'color_advanced_border_settings',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'              => 'dimension',
		'settings'          => 'color_layout elements_border_radius',
		'label'             => esc_html__( 'Layout Elements Border Radius', 'apparel' ),
		'description'       => esc_html__( 'Used on Form Elements, Blockquotes, Block Groups etc.', 'apparel' ),
		'section'           => 'colors',
		'default'           => '5px',
		'sanitize_callback' => 'esc_html',
		'output'            => array(
			array(
				'element'  => ':root',
				'property' => '--mbf-layout-elements-border-radius',
				'context'  => array( 'editor', 'front' ),
			),
		),
		'active_callback'   => array(
			array(
				'setting'  => 'color_advanced_border_settings',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'              => 'dimension',
		'settings'          => 'color_thumbnail_border_radius',
		'label'             => esc_html__( 'Thumbnail Border Radius', 'apparel' ),
		'section'           => 'colors',
		'default'           => '5px',
		'sanitize_callback' => 'esc_html',
		'output'            => array(
			array(
				'element'  => ':root',
				'property' => '--mbf-thumbnail-border-radius',
				'context'  => array( 'editor', 'front' ),
			),
		),
		'active_callback'   => array(
			array(
				'setting'  => 'color_advanced_border_settings',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

MBF_Customizer::add_field(
	array(
		'type'              => 'dimension',
		'settings'          => 'color_button_border_radius',
		'label'             => esc_html__( 'Button Border Radius', 'apparel' ),
		'section'           => 'colors',
		'default'           => '10px',
		'sanitize_callback' => 'esc_html',
		'output'            => array(
			array(
				'element'  => ':root',
				'property' => '--mbf-button-border-radius',
				'context'  => array( 'editor', 'front' ),
			),
		),
		'active_callback'   => array(
			array(
				'setting'  => 'color_advanced_border_settings',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);
