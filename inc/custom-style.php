<?php 
function kilka_custom_css() {
    wp_enqueue_style('kilka-custom', get_template_directory_uri() . '/assets/css/custom-style.css' );
    $header_text_color = get_header_textcolor();
    $kilka_custom_css = '';
    $kilka_custom_css .= '
        .site-title a,
        .site-description,
        .site-title a:hover {
            color: #'.esc_attr( $header_text_color ).' ;
        }
    ';
    wp_add_inline_style( 'kilka-custom', $kilka_custom_css );
}
add_action( 'wp_enqueue_scripts', 'kilka_custom_css' );