<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Kilka
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php kilka_post_thumbnail(); ?>

	<?php if ( 'post' === get_post_type() ) : ?>
		<div class="entry-meta">
			<?php
			kilka_posted_on();
			echo '<span class="sep"> | </span>';
			kilka_posted_by();
			?>
		</div><!-- .entry-meta -->
	<?php endif; ?>

	<header class="entry-header">
		<?php
		if ( is_singular() ) :
			the_title( '<h1 class="entry-title">', '</h1>' );
		else :
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		endif;
		?>
	</header><!-- .entry-header -->

	

	<div class="entry-content">
		<?php

		if(is_single( )){
			the_content(
				sprintf(
					wp_kses(
						/* translators: %s: Name of current post. Only visible to screen readers */
						__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'kilka' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					wp_kses_post( get_the_title() )
				)
			);
		}else{
			the_excerpt();
			$continue_reading_text   = get_theme_mod( 'kilka_continue_reading_text', esc_html__('Continue Reading','kilka') );
			$continue_reading_format = get_theme_mod( 'kilka_continue_reading_format', 'text' );
			
			$button_content = '';
			$arrow_html = '<span class="kilka-button-arrow"></span>';
			
			if ( 'arrow' === $continue_reading_format ) {
				$button_content = $arrow_html;
			} elseif ( 'text_arrow' === $continue_reading_format ) {
				$button_content = '<span class="button-text">' . esc_html( $continue_reading_text ) . '</span>' . $arrow_html;
			} else {
				$button_content = esc_html( $continue_reading_text );
			}

			echo'<a href="'.esc_url ( get_the_permalink( $post->ID ) ).'" class="button format-'.esc_attr($continue_reading_format).'">'. $button_content .'</a>';
		}

		if ( ! is_singular() && 'post' === get_post_type() ) {
			$tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'kilka' ) );
			if ( $tags_list ) {
				printf( '<div class="entry-footer"><span class="tags-links">' . esc_html__( '%1$s', 'kilka' ) . '</span></div>', $tags_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}

		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'kilka' ),
				'after'  => '</div>',
			)
		);
		?>
	</div><!-- .entry-content -->

	<?php if ( is_singular() ) : ?>
		<footer class="entry-footer">
			<?php kilka_entry_footer(); ?>
		</footer><!-- .entry-footer -->
	<?php endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->
