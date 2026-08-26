<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Kilka
 */

$kilka_has_sidebar = kilka_has_contextual_sidebar();

if ( $kilka_has_sidebar ) {
	$kilka_column = 8;
} else {
	$kilka_column = 12;
}
get_header();
?>
<section class="blog-area <?php if ( ! $kilka_has_sidebar ) : ?>block-content-css<?php endif; ?>" id="content">
	<div class="container">
		<div class="row">
				<div class="col-lg-<?php echo esc_attr ($kilka_column); ?> text-center">
				<?php if ( have_posts() ) : ?>
					<?php
					/* Start the Loop */
					while ( have_posts() ) :
						the_post();

						/*
						 * Include the Post-Type-specific template for the content.
						 * If you want to override this in a child theme, then include a file
						 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
						 */
						get_template_part( 'template-parts/content', get_post_type() );

					endwhile;

					the_posts_navigation();

				else :

					get_template_part( 'template-parts/content', 'none' );

				endif;
				?>
			</div>
			<?php if ( $kilka_has_sidebar ) : ?>
			<div class="col-lg-4">
				<?php get_sidebar(); ?>
			</div>
			<?php endif; ?>
		</div>
	</div>
</section>	

<?php
get_footer();
