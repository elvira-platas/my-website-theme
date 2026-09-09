<?php
/**
 * Template Name: Reading
 * Template Post Type: page
 *
 * Continuous reading of a portable, core-block document.
 *
 * @package Kilka
 */

get_header();
?>
<main id="content" class="kilka-reading" tabindex="-1">
	<?php while ( have_posts() ) : ?>
		<?php the_post(); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'kilka-reading__document' ); ?> aria-labelledby="kilka-reading-title">
			<header class="kilka-reading__header">
				<h1 id="kilka-reading-title" class="kilka-reading__title"><?php the_title(); ?></h1>
			</header>
			<div class="kilka-reading__content">
				<?php the_content(); ?>
				<?php
				// Preserve existing manually paginated content without adding pagination.
				wp_link_pages( array(
					'before' => '<nav class="page-links" aria-label="' . esc_attr__( 'Document pages', 'kilka' ) . '">',
					'after'  => '</nav>',
				) );
				?>
			</div>
		</article>
	<?php endwhile; ?>
</main>
<?php get_footer(); ?>
