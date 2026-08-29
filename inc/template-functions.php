<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package Kilka
 */

/**
 * Get the widget area for the current blog context.
 *
 * @return string Sidebar ID.
 */
function kilka_get_contextual_sidebar_id() {
	if ( function_exists( 'kilka_is_second_blog_context' ) && kilka_is_second_blog_context() ) {
		return 'sidebar-second-blog';
	}

	return 'sidebar-1';
}

/**
 * Check whether the current blog context has an active widget area.
 *
 * @return bool
 */
function kilka_has_contextual_sidebar() {
	// Exhibitions use the full content width and never inherit blog widgets.
	if ( is_singular( 'kilka_exhibition' ) ) {
		return false;
	}

	return is_active_sidebar( kilka_get_contextual_sidebar_id() );
}

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function kilka_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	if ( is_singular( 'kilka_exhibition' ) ) {
		$classes[] = has_nav_menu( 'menu-1' ) ? 'kilka-exhibition-has-menu' : 'kilka-exhibition-no-menu';
	}

	if ( function_exists( 'kilka_is_second_blog_context' ) && kilka_is_second_blog_context() ) {
		$classes[] = 'kilka-second-blog-context';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! kilka_has_contextual_sidebar() ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'kilka_body_classes' );

/**
 * Disable the XML-RPC methods used by pingbacks.
 *
 * @param array $methods XML-RPC methods exposed by WordPress.
 * @return array
 */
function kilka_disable_pingbacks( $methods ) {
	unset( $methods['pingback.ping'], $methods['pingback.extensions.getPingbacks'] );

	return $methods;
}
add_filter( 'xmlrpc_methods', 'kilka_disable_pingbacks' );
