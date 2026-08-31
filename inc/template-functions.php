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
 * Check whether the current page belongs to either blog search context.
 *
 * @return bool
 */
function kilka_is_blog_search_context() {
	if ( function_exists( 'kilka_is_second_blog_context' ) && kilka_is_second_blog_context() ) {
		return true;
	}

	if ( is_home() || is_singular( 'post' ) || is_category() || is_tag() || is_author() || is_date() ) {
		return true;
	}

	if ( is_search() ) {
		$post_type = get_query_var( 'post_type' );
		if ( is_array( $post_type ) ) {
			return in_array( 'post', $post_type, true );
		}

		return empty( $post_type ) || 'post' === $post_type;
	}

	return false;
}

/**
 * Add contextual search to the primary menu for both blogs.
 *
 * Search remains a theme presentation concern. The companion plugin only
 * supplies the post type context used by the shared search form.
 *
 * @param string   $items Menu items HTML.
 * @param stdClass $args  Menu arguments.
 * @return string
 */
function kilka_append_contextual_search_menu_item( $items, $args ) {
	if ( ! isset( $args->theme_location ) || 'menu-1' !== $args->theme_location || ! kilka_is_blog_search_context() || false !== strpos( $items, 'kilka-menu-search' ) ) {
		return $items;
	}

	return $items . '<li class="menu-item menu-item-search kilka-menu-search">' . get_search_form( false ) . '</li>';
}
add_filter( 'wp_nav_menu_items', 'kilka_append_contextual_search_menu_item', 11, 2 );

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

	$is_second_blog_context = function_exists( 'kilka_is_second_blog_context' ) && kilka_is_second_blog_context();
	if ( $is_second_blog_context ) {
		$classes[] = 'kilka-second-blog-context';
	} elseif ( kilka_is_blog_search_context() ) {
		$classes[] = 'kilka-main-blog-context';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! kilka_has_contextual_sidebar() ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'kilka_body_classes' );

/**
 * Register the optional floating presentation for standalone image blocks.
 */
function kilka_register_floating_image_block_style() {
	register_block_style(
		'core/image',
		array(
			'name'  => 'kilka-floating',
			'label' => __( 'Floating image', 'kilka' ),
		)
	);
}
add_action( 'init', 'kilka_register_floating_image_block_style' );

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
