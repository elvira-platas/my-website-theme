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
	<header class="entry-header">
		<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

		<?php if ( 'post' === get_post_type() ) : ?>
		<div class="entry-meta">
			<?php
			kilka_posted_on();
			echo '<span class="sep"> | </span>';
			kilka_posted_by();
			?>
		</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<?php kilka_post_thumbnail(); ?>

	<div class="entry-summary">
		<?php the_excerpt(); ?>
		<?php
		if ( 'post' === get_post_type() ) {
			$tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'kilka' ) );
			if ( $tags_list ) {
				printf( '<div class="tags-links text-right">' . esc_html__( '%1$s', 'kilka' ) . '</div>', $tags_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}
		?>
	</div><!-- .entry-summary -->

	<footer class="entry-footer">
		<?php kilka_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
