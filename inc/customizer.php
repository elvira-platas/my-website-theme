<?php
/**
 * Kilka Theme Customizer
 *
 * @package Kilka
 */

/**
 * Get the site-title fonts supported by the theme.
 *
 * @return array
 */
function kilka_get_site_title_font_choices() {
	return array(
		'Roboto'           => __( 'Roboto (Default)', 'kilka' ),
		'system-ui'        => __( 'System UI (Fastest)', 'kilka' ),
		'Montserrat'       => __( 'Montserrat', 'kilka' ),
		'Oswald'           => __( 'Oswald', 'kilka' ),
		'Playfair Display' => __( 'Playfair Display', 'kilka' ),
		'Merriweather'     => __( 'Merriweather', 'kilka' ),
	);
}

/**
 * Limit the site-title font to fonts supported by the theme.
 *
 * @param string $value Requested font name.
 * @return string
 */
function kilka_sanitize_site_title_font( $value ) {
	$fonts = kilka_get_site_title_font_choices();

	return array_key_exists( $value, $fonts ) ? $value : 'Roboto';
}

/**
 * Limit the Continue Reading button format to supported values.
 *
 * @param string $value Requested button format.
 * @return string
 */
function kilka_sanitize_continue_reading_format( $value ) {
	$allowed_formats = array( 'text', 'arrow', 'text_arrow' );

	return in_array( $value, $allowed_formats, true ) ? $value : 'text';
}

/**
 * Limit the Continue Reading font weight to supported values.
 *
 * @param string $value Requested font weight.
 * @return string
 */
function kilka_sanitize_continue_reading_weight( $value ) {
	$allowed_weights = array( '300', '400', '500', '600', '700', '800' );

	return in_array( (string) $value, $allowed_weights, true ) ? (string) $value : '400';
}

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

	// Keep the header text color with the other site identity controls.
	$header_text_color_control = $wp_customize->get_control( 'header_textcolor' );
	if ( $header_text_color_control ) {
		$header_text_color_control->label    = __( 'Site Title and Tagline Color', 'kilka' );
		$header_text_color_control->section  = 'title_tagline';
		$header_text_color_control->priority = 60;
	}

	// Combine the core background color and image controls into one section.
	$background_section       = $wp_customize->get_section( 'background_image' );
	$background_color_control = $wp_customize->get_control( 'background_color' );

	if ( $background_section && $background_color_control ) {
		$background_section->title            = __( 'Background', 'kilka' );
		$background_color_control->section     = 'background_image';
		$background_color_control->priority    = 5;
		$wp_customize->remove_section( 'colors' );
	}

	// Keep site-title typography with the related core identity controls.
	$wp_customize->add_setting( 'kilka_site_title_font', array(
		'default'           => 'Roboto',
		'sanitize_callback' => 'kilka_sanitize_site_title_font',
	) );
	$wp_customize->add_control( 'kilka_site_title_font', array(
		'label'    => __( 'Site Title Font Family', 'kilka' ),
		'section'  => 'title_tagline',
		'priority' => 50,
		'type'     => 'select',
		'choices'  => kilka_get_site_title_font_choices(),
	) );

	$wp_customize->add_setting( 'kilka_site_title_size', array(
		'default'           => 25,
		'sanitize_callback' => 'absint',
	) );
	$wp_customize->add_control( 'kilka_site_title_size', array(
		'label'       => __( 'Site Title Font Size (px)', 'kilka' ),
		'section'     => 'title_tagline',
		'priority'    => 55,
		'type'        => 'number',
		'input_attrs' => array(
			'min'  => 14,
			'max'  => 100,
			'step' => 1,
		),
	) );

	// Group theme-specific controls under one top-level Customizer item.
	$wp_customize->add_panel( 'kilka_theme_options_panel', array(
		'title'       => __( 'Kilka Theme Options', 'kilka' ),
		'description' => __( 'Customize post listings, the footer, and Second Blog content.', 'kilka' ),
		'priority'    => 130,
	) );

	// Add Footer Settings Section
	$wp_customize->add_section( 'kilka_footer_section', array(
		'title'    => __( 'Footer', 'kilka' ),
		'panel'    => 'kilka_theme_options_panel',
		'priority' => 20,
	) );

	// Add Footer Link Text Setting
	$wp_customize->add_setting( 'kilka_footer_link_text', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'kilka_footer_link_text', array(
		'label'    => __( 'First Link Text', 'kilka' ),
		'section'  => 'kilka_footer_section',
		'type'     => 'text',
	) );

	// Add Footer Link URL Setting
	$wp_customize->add_setting( 'kilka_footer_link_url', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( 'kilka_footer_link_url', array(
		'label'    => __( 'First Link URL', 'kilka' ),
		'section'  => 'kilka_footer_section',
		'type'     => 'url',
	) );

	// Add Footer Link Text 2 Setting
	$wp_customize->add_setting( 'kilka_footer_link_text_2', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'kilka_footer_link_text_2', array(
		'label'    => __( 'Second Link Text', 'kilka' ),
		'section'  => 'kilka_footer_section',
		'type'     => 'text',
	) );

	// Add Footer Link URL 2 Setting
	$wp_customize->add_setting( 'kilka_footer_link_url_2', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( 'kilka_footer_link_url_2', array(
		'label'    => __( 'Second Link URL', 'kilka' ),
		'section'  => 'kilka_footer_section',
		'type'     => 'url',
	) );

	// Add Post Listings Section
	$wp_customize->add_section( 'kilka_button_section', array(
		'title'       => __( 'Post Listings', 'kilka' ),
		'description' => __( 'Customize the Continue Reading link shown on post lists.', 'kilka' ),
		'panel'       => 'kilka_theme_options_panel',
		'priority'    => 10,
	) );

	// Continue Reading Text
	$wp_customize->add_setting( 'kilka_continue_reading_text', array(
		'default'           => __( 'Continue Reading', 'kilka' ),
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'kilka_continue_reading_text', array(
		'label'    => __( 'Button Text', 'kilka' ),
		'section'  => 'kilka_button_section',
		'type'     => 'text',
	) );

	// Continue Reading Format
	$wp_customize->add_setting( 'kilka_continue_reading_format', array(
		'default'           => 'text',
		'sanitize_callback' => 'kilka_sanitize_continue_reading_format',
	) );
	$wp_customize->add_control( 'kilka_continue_reading_format', array(
		'label'    => __( 'Format', 'kilka' ),
		'section'  => 'kilka_button_section',
		'type'     => 'select',
		'choices'  => array(
			'text'       => __( 'Text Only', 'kilka' ),
			'arrow'      => __( 'Arrow Only (→)', 'kilka' ),
			'text_arrow' => __( 'Text + Arrow (→)', 'kilka' ),
		),
	) );

	// Continue Reading Color
	$wp_customize->add_setting( 'kilka_continue_reading_color', array(
		'default'           => '#000000',
		'sanitize_callback' => 'sanitize_hex_color',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'kilka_continue_reading_color', array(
		'label'    => __( 'Color', 'kilka' ),
		'section'  => 'kilka_button_section',
	) ) );

	// Continue Reading Font Weight
	$wp_customize->add_setting( 'kilka_continue_reading_weight', array(
		'default'           => '400',
		'sanitize_callback' => 'kilka_sanitize_continue_reading_weight',
	) );
	$wp_customize->add_control( 'kilka_continue_reading_weight', array(
		'label'    => __( 'Font Weight', 'kilka' ),
		'section'  => 'kilka_button_section',
		'type'     => 'select',
		'choices'  => array(
			'300' => __( 'Light (300)', 'kilka' ),
			'400' => __( 'Normal (400)', 'kilka' ),
			'500' => __( 'Medium (500)', 'kilka' ),
			'600' => __( 'Semi-Bold (600)', 'kilka' ),
			'700' => __( 'Bold (700)', 'kilka' ),
			'800' => __( 'Extra-Bold (800)', 'kilka' ),
		),
	) );

	if ( function_exists( 'kilka_get_world_note_slug' ) ) {
		// Add Second Blog Intro Section only when the companion plugin is active.
		$wp_customize->add_section(
			'kilka_second_blog_intro_section',
			array(
				'title'       => __( 'Second Blog', 'kilka' ),
				'panel'       => 'kilka_theme_options_panel',
				'priority'    => 30,
				'description' => __( 'Shown under the site title on Second Blog pages.', 'kilka' ),
			)
		);

		// Second Blog Heading
		$wp_customize->add_setting(
			'kilka_second_blog_heading',
			array(
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			'kilka_second_blog_heading',
			array(
				'label'   => __( 'Second Blog Heading', 'kilka' ),
				'section' => 'kilka_second_blog_intro_section',
				'type'    => 'text',
			)
		);

		// Second Blog Description
		$wp_customize->add_setting(
			'kilka_second_blog_description',
			array(
				'default'           => '',
				'sanitize_callback' => 'sanitize_textarea_field',
			)
		);
		$wp_customize->add_control(
			'kilka_second_blog_description',
			array(
				'label'   => __( 'Second Blog Description', 'kilka' ),
				'section' => 'kilka_second_blog_intro_section',
				'type'    => 'textarea',
			)
		);
	}
}
add_action( 'customize_register', 'kilka_customize_register' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function kilka_customize_partial_blogname() {
	echo esc_html( get_bloginfo( 'name', 'display' ) );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function kilka_customize_partial_blogdescription() {
	echo esc_html( get_bloginfo( 'description', 'display' ) );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function kilka_customize_preview_js() {
	wp_enqueue_script( 'kilka-customizer', get_template_directory_uri() . '/assets/js/customizer.js', array( 'customize-preview' ), '20151215', true );
}
add_action( 'customize_preview_init', 'kilka_customize_preview_js' );

/**
 * Enqueue Customizer controls script for Second Blog intro helpers.
 *
 * @return void
 */
function kilka_customize_controls_js() {
	if ( ! function_exists( 'kilka_get_world_note_slug' ) ) {
		return;
	}

	$second_blog_url = '';

	if ( post_type_exists( 'world_note' ) ) {
		$second_blog_url = get_post_type_archive_link( 'world_note' );
	}

	if ( ! $second_blog_url && function_exists( 'kilka_get_world_note_slug' ) ) {
		$second_blog_url = home_url( '/' . trim( (string) kilka_get_world_note_slug(), '/' ) . '/' );
	}

	wp_enqueue_script(
		'kilka-customizer-controls',
		get_template_directory_uri() . '/assets/js/customizer-controls.js',
		array( 'customize-controls' ),
		KILKA_VERSION,
		true
	);

	wp_localize_script(
		'kilka-customizer-controls',
		'kilkaCustomizerControls',
		array(
			'secondBlogUrl' => esc_url_raw( (string) $second_blog_url ),
		)
	);
}
add_action( 'customize_controls_enqueue_scripts', 'kilka_customize_controls_js' );
