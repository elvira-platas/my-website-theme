<?php 
function kilka_custom_css() {
    wp_enqueue_style('kilka-custom', get_template_directory_uri() . '/assets/css/custom-style.css' );
    $header_text_color = get_header_textcolor();
    
    $site_title_font = get_theme_mod( 'kilka_site_title_font', 'Roboto' );
    $site_title_size = get_theme_mod( 'kilka_site_title_size', '25' );

    $kilka_custom_css = '';
    
    // Header Text Color
    $kilka_custom_css .= '
        .site-title a,
        .site-description,
        .site-title a:hover {
            color: #'.esc_attr( $header_text_color ).' !important;
        }
    ';

    // Site Title Typography
    $font_family = $site_title_font === 'system-ui' ? 'system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif' : '"' . esc_attr( $site_title_font ) . '", sans-serif';
    $font_weight = '700'; // Default bold for site title
    
    // Check for serif and cursive fonts to apply correct fallback
    if ( in_array( $site_title_font, array('Georgia', 'Times New Roman', 'Playfair Display', 'Merriweather') ) ) {
        $font_family = '"' . esc_attr( $site_title_font ) . '", serif';
        $font_weight = '400'; // Oswald тоже часто лучше смотрится без принудительного bold
    }

    $kilka_custom_css .= '
        .site-title a {
            font-family: '.$font_family.' !important;
            font-size: '.esc_attr( $site_title_size ).'px !important;
            font-weight: '.$font_weight.' !important;
        }
    ';

    wp_add_inline_style( 'kilka-custom', $kilka_custom_css );
}
add_action( 'wp_enqueue_scripts', 'kilka_custom_css' );
