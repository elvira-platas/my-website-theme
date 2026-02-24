<?php
/**
 * Header action
 * @package Kilka
 */

function kilka_header_style_1(){ ?>
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'kilka' ); ?></a>
	<header id="masthead" class="header-area <?php if(has_header_image() && is_front_page()): ?>kilka-header-img<?php endif; ?>">
		<?php if(has_header_image() && is_front_page()): ?>
	        <div class="header-img"> 
	        	<?php the_header_image_tag(); ?>
	        </div>
        <?php endif; ?>
		<div class="container">
			<div class="row align-items-center">
				<div class="col-lg-12">
					<div class="header-main-flex">
						<div class="site-branding text-center">
							<?php
							the_custom_logo();
							?>
							<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
							<?php
							$kilka_description = get_bloginfo( 'description', 'display' );
							if ( $kilka_description || is_customize_preview() ) :
								?>
								<p class="site-description"><?php echo esc_html($kilka_description); ?></p>
							<?php endif; ?>
						</div><!-- .site-branding -->
						
						<?php if ( has_nav_menu( 'menu-1' ) ) : ?>
							<div class="kilka-responsive-menu"></div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</header><!-- #masthead -->
	<section class="mainmenu-area" style="display:none;">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="mainmenu">
						<?php
							wp_nav_menu( array(
								'theme_location' => 'menu-1',
								'menu_id'        => 'primary-menu',
							) );
						?>
					</div>
				</div>
			</div>
		</div>
	</section>
<?php }
add_action('kilka_header_style','kilka_header_style_1');