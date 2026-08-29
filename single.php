<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
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

<section class="single-area <?php if ( ! $kilka_has_sidebar ) : ?>block-content-css<?php endif; ?>" id="content">
	<div class="container">
		<div class="row">
			<div class="col-lg-<?php echo esc_attr( $kilka_column ); ?>">
				<?php
					while ( have_posts() ) :
						the_post();

						get_template_part( 'template-parts/content', get_post_type() );
						the_post_navigation();
						// If comments are open or we have at least one comment, load up the comment template.
						if ( comments_open() || get_comments_number() ) :
							comments_template();
						endif;

					endwhile; // End of the loop.
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
<?php if ( function_exists( 'kilka_is_second_blog_context' ) && kilka_is_second_blog_context() ) : ?>
	<?php get_template_part( 'template-parts/second-blog-source-note' ); ?>
<?php endif; ?>
<?php
get_footer();
