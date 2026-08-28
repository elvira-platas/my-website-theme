<?php
/**
 * Plugin Name: Kilka Exhibitions
 * Plugin URI:  https://github.com/elvira-platas/my-website-theme
 * Description: Provides portable exhibition content and editor foundations for the Kilka ecosystem.
 * Version:     0.1.0
 * Author:      Elvira
 * License:     GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: kilka-exhibitions
 *
 * Development of this plugin was carried out with substantial assistance from
 * OpenAI Codex and Google Gemini under Elvira's direction.
 *
 * @package Kilka_Exhibitions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( defined( 'KILKA_EXHIBITIONS_VERSION' ) ) {
	return;
}

define( 'KILKA_EXHIBITIONS_VERSION', '0.1.0' );

if ( ! function_exists( 'kilka_exhibitions_load_textdomain' ) ) :
	/**
	 * Load plugin translations.
	 */
	function kilka_exhibitions_load_textdomain() {
		load_plugin_textdomain( 'kilka-exhibitions', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}
endif;
add_action( 'plugins_loaded', 'kilka_exhibitions_load_textdomain' );

if ( ! function_exists( 'kilka_exhibitions_get_rewrite_slug' ) ) :
	/**
	 * Get the public rewrite slug for individual exhibitions.
	 *
	 * @return string
	 */
	function kilka_exhibitions_get_rewrite_slug() {
		/**
		 * Filter the public exhibition rewrite slug.
		 *
		 * @param string $slug Default rewrite slug.
		 */
		$slug = apply_filters( 'kilka_exhibitions_rewrite_slug', 'exhibitions' );
		$slug = sanitize_title( $slug );

		return '' === $slug ? 'exhibitions' : $slug;
	}
endif;

if ( ! function_exists( 'kilka_exhibitions_register_post_type' ) ) :
	/**
	 * Register the Exhibition post type.
	 */
	function kilka_exhibitions_register_post_type() {
		$labels = array(
			'name'                  => _x( 'Exhibitions', 'Post type general name', 'kilka-exhibitions' ),
			'singular_name'         => _x( 'Exhibition', 'Post type singular name', 'kilka-exhibitions' ),
			'menu_name'             => __( 'Exhibitions', 'kilka-exhibitions' ),
			'name_admin_bar'        => __( 'Exhibition', 'kilka-exhibitions' ),
			'add_new'               => __( 'Add New', 'kilka-exhibitions' ),
			'add_new_item'          => __( 'Add New Exhibition', 'kilka-exhibitions' ),
			'new_item'              => __( 'New Exhibition', 'kilka-exhibitions' ),
			'edit_item'             => __( 'Edit Exhibition', 'kilka-exhibitions' ),
			'view_item'             => __( 'View Exhibition', 'kilka-exhibitions' ),
			'all_items'             => __( 'All Exhibitions', 'kilka-exhibitions' ),
			'search_items'          => __( 'Search Exhibitions', 'kilka-exhibitions' ),
			'parent_item_colon'     => __( 'Parent Exhibitions:', 'kilka-exhibitions' ),
			'not_found'             => __( 'No exhibitions found.', 'kilka-exhibitions' ),
			'not_found_in_trash'    => __( 'No exhibitions found in Trash.', 'kilka-exhibitions' ),
			'archives'              => __( 'Exhibition Archives', 'kilka-exhibitions' ),
			'attributes'            => __( 'Exhibition Attributes', 'kilka-exhibitions' ),
			'insert_into_item'      => __( 'Insert into exhibition', 'kilka-exhibitions' ),
			'uploaded_to_this_item' => __( 'Uploaded to this exhibition', 'kilka-exhibitions' ),
			'featured_image'        => __( 'Exhibition Preview Image', 'kilka-exhibitions' ),
			'set_featured_image'    => __( 'Set exhibition preview image', 'kilka-exhibitions' ),
			'remove_featured_image' => __( 'Remove exhibition preview image', 'kilka-exhibitions' ),
			'use_featured_image'    => __( 'Use as exhibition preview image', 'kilka-exhibitions' ),
		);

		register_post_type(
			'kilka_exhibition',
			array(
				'labels'              => $labels,
				'public'              => true,
				'publicly_queryable'  => true,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'show_in_nav_menus'   => true,
				'show_in_rest'        => true,
				'rest_base'           => 'exhibitions',
				'query_var'           => true,
				'rewrite'             => array(
					'slug'       => kilka_exhibitions_get_rewrite_slug(),
					'with_front' => false,
				),
				'has_archive'         => false,
				'hierarchical'        => false,
				'exclude_from_search' => false,
				'menu_position'       => 6,
				'menu_icon'           => 'dashicons-format-gallery',
				'capability_type'     => 'post',
				'map_meta_cap'        => true,
				'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'author', 'revisions', 'custom-fields' ),
			)
		);
	}
endif;
add_action( 'init', 'kilka_exhibitions_register_post_type' );

if ( ! function_exists( 'kilka_exhibitions_sanitize_boolean' ) ) :
	/**
	 * Sanitize a REST or metadata boolean value.
	 *
	 * @param mixed $value Raw value.
	 * @return bool
	 */
	function kilka_exhibitions_sanitize_boolean( $value ) {
		if ( is_string( $value ) ) {
			return ! in_array( strtolower( $value ), array( '', '0', 'false', 'no', 'off' ), true );
		}

		return (bool) $value;
	}
endif;

if ( ! function_exists( 'kilka_exhibitions_sanitize_wall_tone' ) ) :
	/**
	 * Restrict wall tone values to supported presentation presets.
	 *
	 * @param mixed $value Raw wall tone.
	 * @return string
	 */
	function kilka_exhibitions_sanitize_wall_tone( $value ) {
		$value   = sanitize_key( $value );
		$allowed = array( 'inherit', 'warm', 'light', 'dark' );

		return in_array( $value, $allowed, true ) ? $value : 'inherit';
	}
endif;

if ( ! function_exists( 'kilka_exhibitions_register_post_meta' ) ) :
	/**
	 * Register portable exhibition-level metadata.
	 */
	function kilka_exhibitions_register_post_meta() {
		$string_schema = array(
			'type'    => 'string',
			'context' => array( 'view', 'edit' ),
		);

		register_post_meta(
			'kilka_exhibition',
			'kilka_exhibition_creator',
			array(
				'type'              => 'string',
				'single'            => true,
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
				'show_in_rest'      => array( 'schema' => $string_schema ),
			)
		);

		register_post_meta(
			'kilka_exhibition',
			'kilka_exhibition_copyright_notice',
			array(
				'type'              => 'string',
				'single'            => true,
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
				'show_in_rest'      => array( 'schema' => $string_schema ),
			)
		);

		register_post_meta(
			'kilka_exhibition',
			'kilka_exhibition_information_panel',
			array(
				'type'              => 'boolean',
				'single'            => true,
				'default'           => true,
				'sanitize_callback' => 'kilka_exhibitions_sanitize_boolean',
				'show_in_rest'      => array(
					'schema' => array(
						'type'    => 'boolean',
						'context' => array( 'view', 'edit' ),
					),
				),
			)
		);

		register_post_meta(
			'kilka_exhibition',
			'kilka_exhibition_wall_tone',
			array(
				'type'              => 'string',
				'single'            => true,
				'default'           => 'inherit',
				'sanitize_callback' => 'kilka_exhibitions_sanitize_wall_tone',
				'show_in_rest'      => array(
					'schema' => array(
						'type'    => 'string',
						'enum'    => array( 'inherit', 'warm', 'light', 'dark' ),
						'context' => array( 'view', 'edit' ),
					),
				),
			)
		);

		register_post_meta(
			'kilka_exhibition',
			'kilka_exhibition_search_summary',
			array(
				'type'              => 'string',
				'single'            => true,
				'default'           => '',
				'sanitize_callback' => 'sanitize_textarea_field',
				'show_in_rest'      => array( 'schema' => $string_schema ),
			)
		);
	}
endif;
add_action( 'init', 'kilka_exhibitions_register_post_meta', 11 );

if ( ! function_exists( 'kilka_exhibitions_activate' ) ) :
	/**
	 * Register exhibition rewrites when the plugin is activated.
	 */
	function kilka_exhibitions_activate() {
		kilka_exhibitions_register_post_type();
		flush_rewrite_rules();
	}
endif;
register_activation_hook( __FILE__, 'kilka_exhibitions_activate' );

if ( ! function_exists( 'kilka_exhibitions_deactivate' ) ) :
	/**
	 * Flush exhibition rewrites when the plugin is deactivated.
	 */
	function kilka_exhibitions_deactivate() {
		flush_rewrite_rules();
	}
endif;
register_deactivation_hook( __FILE__, 'kilka_exhibitions_deactivate' );
