<?php
/**
 * Footer action
 * @package Doyel
 */

function doyel_footer_style_1(){ ?>
<footer class="footer-area">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<div class="copyright">
					<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'doyel' ) ); ?>">
						<?php
						/* translators: %s: CMS name, i.e. WordPress. */
						printf( esc_html__( 'Proudly powered by %s', 'doyel' ), 'WordPress' );
						?>
					</a>
					<span class="sep"> | </span>
						<?php
						/* translators: 1: Theme name, 2: Theme author. */
						printf( esc_html__( 'Theme: %1$s by %2$s.', 'doyel' ), 'doyel', 'ashathemes' );
						?>
				</div>
			</div>
		</div>
	</div>
</footer>
<?php }
add_action('doyel_footer_style','doyel_footer_style_1');