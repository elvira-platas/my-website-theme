<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package Kilka
 */

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

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
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
