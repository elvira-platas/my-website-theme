<?php
/**
 * Header action
 * @package Doyel
 */

function doyel_header_style_1(){ ?>
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'doyel' ); ?></a>
	<header id="masthead" class="header-area <?php if(has_header_image() && is_front_page()): ?>doyel-header-img<?php endif; ?> <?php if( ! is_front_page ()): ?>header-margin-top<?php endif; ?>">
		<?php if(has_header_image() && is_front_page()): ?>
	        <div class="header-img"> 
	        	<?php the_header_image_tag(); ?>
	        </div>
        <?php endif; ?>
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="site-branding text-center">
						<?php
						the_custom_logo();
						if ( is_front_page() && is_home() ) :
							?>
							<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
							<?php
						else :
							?>
							<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
							<?php
						endif;
						$doyel_description = get_bloginfo( 'description', 'display' );
						if ( $doyel_description || is_customize_preview() ) :
							?>
							<p class="site-description"><?php echo esc_html($doyel_description); ?></p>
						<?php endif; ?>
					</div><!-- .site-branding -->
				</div>
			</div>
		</div>
	</header><!-- #masthead -->
	<section class="mainmenu-area">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="doyel-responsive-menu"></div>
					<button class="screen-reader-text menu-close"><?php esc_html_e( 'Close Menu', 'doyel' ); ?></button>
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
add_action('doyel_header_style','doyel_header_style_1');