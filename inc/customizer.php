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
require get_template_directory() . '/inc/kilka-button/kilka-customize.php';