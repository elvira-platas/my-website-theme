<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Doyel
 */

get_header();
?>
<section class="breadcrumbs-area">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<h2><?php esc_html_e('404 Error Page','doyel'); ?></h2>
			</div>
		</div>
	</div>
</section>

<section id="blog" class="section page error-page">
	<div class="container">
		<div class="row">
			<div class="col-lg-12 text-center">
				<h2><?php esc_html_e('Page not found!','doyel'); ?></h2>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="button"><?php esc_html_e('Back To Home','doyel'); ?></a>
			</div>
		</div>
	</div>
</section>
<?php 
get_footer();
