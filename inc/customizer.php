<?php
/**
 * Kilka Theme Customizer
 *
 * @package Kilka
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function kilka_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			'blogname',
			array(
				'selector'        => '.site-title a',
				'render_callback' => 'kilka_customize_partial_blogname',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'blogdescription',
			array(
				'selector'        => '.site-description',
				'render_callback' => 'kilka_customize_partial_blogdescription',
			)
		);
	}

	// Add Typography Settings Section
	$wp_customize->add_section( 'kilka_typography_section', array(
		'title'    => __( 'Site Title Typography', 'kilka' ),
		'priority' => 30, // Show it near Site Identity
	) );

	// Font Family Setting
	$wp_customize->add_setting( 'kilka_site_title_font', array(
		'default'           => 'Roboto',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'kilka_site_title_font', array(
		'label'    => __( 'Site Title Font Family', 'kilka' ),
		'section'  => 'kilka_typography_section',
		'type'     => 'select',
		'choices'  => array(
			'Roboto'                 => 'Roboto (Default)',
			'Arial'                  => 'Arial',
			'Georgia'                => 'Georgia',
			'Verdana'                => 'Verdana',
			'Tahoma'                 => 'Tahoma',
			'Trebuchet MS'           => 'Trebuchet MS',
			'Times New Roman'        => 'Times New Roman',
			'Courier New'            => 'Courier New',
			'system-ui'              => 'System Font (Fastest)',
			'Montserrat'             => 'Montserrat',
			'Oswald'                 => 'Oswald',
			'Playfair Display'       => 'Playfair Display',
			'Merriweather'           => 'Merriweather',
			'Open Sans'              => 'Open Sans',
			'Lato'                   => 'Lato',
		),
	) );

	// Font Size Setting
	$wp_customize->add_setting( 'kilka_site_title_size', array(
		'default'           => 25,
		'sanitize_callback' => 'absint',
	) );
	$wp_customize->add_control( 'kilka_site_title_size', array(
		'label'    => __( 'Site Title Font Size (px)', 'kilka' ),
		'section'  => 'kilka_typography_section',
		'type'     => 'number',
		'input_attrs' => array(
			'min'  => 14,
			'max'  => 100,
			'step' => 1,
		),
	) );

	// Add Footer Settings Section
	$wp_customize->add_section( 'kilka_footer_section', array(
		'title'    => __( 'Footer Settings', 'kilka' ),
		'priority' => 120,
	) );

	// Add Footer Link Text Setting
	$wp_customize->add_setting( 'kilka_footer_link_text', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'kilka_footer_link_text', array(
		'label'    => __( 'Footer Custom Link Text', 'kilka' ),
		'section'  => 'kilka_footer_section',
		'type'     => 'text',
	) );

	// Add Footer Link URL Setting
	$wp_customize->add_setting( 'kilka_footer_link_url', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( 'kilka_footer_link_url', array(
		'label'    => __( 'Footer Custom Link URL', 'kilka' ),
		'section'  => 'kilka_footer_section',
		'type'     => 'url',
	) );

	// Add Footer Link Text 2 Setting
	$wp_customize->add_setting( 'kilka_footer_link_text_2', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'kilka_footer_link_text_2', array(
		'label'    => __( 'Footer Custom Link Text 2', 'kilka' ),
		'section'  => 'kilka_footer_section',
		'type'     => 'text',
	) );

	// Add Footer Link URL 2 Setting
	$wp_customize->add_setting( 'kilka_footer_link_url_2', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( 'kilka_footer_link_url_2', array(
		'label'    => __( 'Footer Custom Link URL 2', 'kilka' ),
		'section'  => 'kilka_footer_section',
		'type'     => 'url',
	) );

	// Add Button Settings Section
	$wp_customize->add_section( 'kilka_button_section', array(
		'title'    => __( 'Button Settings', 'kilka' ),
		'priority' => 130,
	) );

	// Continue Reading Text
	$wp_customize->add_setting( 'kilka_continue_reading_text', array(
		'default'           => __( 'Continue Reading', 'kilka' ),
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'kilka_continue_reading_text', array(
		'label'    => __( 'Continue Reading Button Text', 'kilka' ),
		'section'  => 'kilka_button_section',
		'type'     => 'text',
	) );

	// Continue Reading Format
	$wp_customize->add_setting( 'kilka_continue_reading_format', array(
		'default'           => 'text',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'kilka_continue_reading_format', array(
		'label'    => __( 'Continue Reading Button Format', 'kilka' ),
		'section'  => 'kilka_button_section',
		'type'     => 'select',
		'choices'  => array(
			'text'        => 'Text Only',
			'arrow'       => 'Arrow Only (→)',
			'text_arrow'  => 'Text + Arrow (→)',
		),
	) );

	// Continue Reading Color
	$wp_customize->add_setting( 'kilka_continue_reading_color', array(
		'default'           => '#000000',
		'sanitize_callback' => 'sanitize_hex_color',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'kilka_continue_reading_color', array(
		'label'    => __( 'Continue Reading Button Color', 'kilka' ),
		'section'  => 'kilka_button_section',
	) ) );

	// Continue Reading Font Weight
	$wp_customize->add_setting( 'kilka_continue_reading_weight', array(
		'default'           => '400',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'kilka_continue_reading_weight', array(
		'label'    => __( 'Continue Reading Font Weight', 'kilka' ),
		'section'  => 'kilka_button_section',
		'type'     => 'select',
		'choices'  => array(
			'300' => 'Light (300)',
			'400' => 'Normal (400)',
			'500' => 'Medium (500)',
			'600' => 'Semi-Bold (600)',
			'700' => 'Bold (700)',
			'800' => 'Extra-Bold (800)',
		),
	) );
}
add_action( 'customize_register', 'kilka_customize_register' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function kilka_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function kilka_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function kilka_customize_preview_js() {
	wp_enqueue_script( 'kilka-customizer', get_template_directory_uri() . '/assets/js/customizer.js', array( 'customize-preview' ), '20151215', true );
}
add_action( 'customize_preview_init', 'kilka_customize_preview_js' );
