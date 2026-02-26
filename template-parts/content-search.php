<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Kilka
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php kilka_post_thumbnail(); ?>

	<?php if ( in_array( get_post_type(), array( 'post', 'world_note' ), true ) ) : ?>
		<div class="entry-meta">
			<?php
			kilka_posted_on();
			echo '<span class="sep"> | </span>';
			kilka_posted_by();
			?>
		</div><!-- .entry-meta -->
	<?php endif; ?>

	<header class="entry-header">
		<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php
		the_excerpt();

		$continue_reading_text   = get_theme_mod( 'kilka_continue_reading_text', esc_html__( 'Continue Reading', 'kilka' ) );
		$continue_reading_format = get_theme_mod( 'kilka_continue_reading_format', 'text' );
		$button_content = '';
		$arrow_html     = '<span class="kilka-button-arrow"></span>';

		if ( 'arrow' === $continue_reading_format ) {
			$button_content = $arrow_html;
		} elseif ( 'text_arrow' === $continue_reading_format ) {
			$button_content = '<span class="button-text">' . esc_html( $continue_reading_text ) . '</span>' . $arrow_html;
		} else {
			$button_content = esc_html( $continue_reading_text );
		}

		echo '<a href="' . esc_url( get_permalink() ) . '" class="button format-' . esc_attr( $continue_reading_format ) . '">' . $button_content . '</a>';

		if ( in_array( get_post_type(), array( 'post', 'world_note' ), true ) ) {
			if ( 'post' === get_post_type() ) {
				$tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'kilka' ) );
			} else {
				$tags_list = function_exists( 'kilka_get_world_note_term_links' )
					? kilka_get_world_note_term_links( get_the_ID(), 'world_note_tag', esc_html_x( ', ', 'list item separator', 'kilka' ) )
					: get_the_term_list( get_the_ID(), 'world_note_tag', '', esc_html_x( ', ', 'list item separator', 'kilka' ) );
			}

			if ( $tags_list ) {
				printf( '<div class="entry-footer text-right"><span class="tags-links">' . esc_html__( '%1$s', 'kilka' ) . '</span></div>', $tags_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}
		?>
	</div><!-- .entry-content -->
</article><!-- #post-<?php the_ID(); ?> -->
