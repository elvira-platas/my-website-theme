<?php
/**
 * Search form template.
 *
 * @package Kilka
 */

$search_post_type = function_exists( 'kilka_get_contextual_search_post_type' ) ? kilka_get_contextual_search_post_type() : 'post';
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label>
		<span class="screen-reader-text"><?php echo esc_html_x( 'Search for:', 'label', 'kilka' ); ?></span>
		<input
			type="search"
			class="search-field"
			placeholder="<?php echo esc_attr_x( 'Search...', 'placeholder', 'kilka' ); ?>"
			value="<?php echo esc_attr( get_search_query() ); ?>"
			name="s"
		/>
	</label>
	<input type="hidden" name="post_type" value="<?php echo esc_attr( $search_post_type ); ?>" />
	<input type="submit" class="search-submit" value="<?php echo esc_attr_x( 'Search', 'submit button', 'kilka' ); ?>" />
</form>
