<?php
/**
 * Footer action
 * @package Kilka
 */

function kilka_footer_style_1(){ ?>
<footer class="footer-area">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<div class="copyright">
					<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'kilka' ) ); ?>">
						<?php
						/* translators: %s: CMS name, i.e. WordPress. */
						printf( esc_html__( 'Proudly powered by %s', 'kilka' ), 'WordPress' );
						?>
					</a>
					<span class="sep"> | </span>
						<?php
						/* translators: 1: Theme name, 2: Theme author. */
						printf( esc_html__( 'Theme: %1$s by %2$s.', 'kilka' ), 'kilka', 'ashathemes' );
						?>
				</div>
			</div>
		</div>
	</div>
</footer>
<?php }
add_action('kilka_footer_style','kilka_footer_style_1');