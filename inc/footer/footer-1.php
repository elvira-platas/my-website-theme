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
					&copy; <?php echo date('Y'); ?> Мой сайт. Разработано с ❤️ Elvira (Тема Kilka).
				</div>
			</div>
		</div>
	</div>
</footer>
<?php }
add_action('kilka_footer_style','kilka_footer_style_1');