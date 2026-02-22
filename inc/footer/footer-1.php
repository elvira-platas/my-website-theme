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
					&copy; <?php echo date('Y'); ?> ❤️ Elvira (Тема Kilka).
					<?php
					$footer_link_text = get_theme_mod( 'kilka_footer_link_text' );
					$footer_link_url  = get_theme_mod( 'kilka_footer_link_url' );
					
					if ( ! empty( $footer_link_text ) && ! empty( $footer_link_url ) ) {
						echo '<span class="sep"> | </span>';
						echo '<a href="' . esc_url( $footer_link_url ) . '" target="_blank" rel="noopener">' . esc_html( $footer_link_text ) . '</a>';
					}
					?>
				</div>
			</div>
		</div>
	</div>
</footer>
<?php }
add_action('kilka_footer_style','kilka_footer_style_1');