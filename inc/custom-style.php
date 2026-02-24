<?php 
function kilka_custom_css() {
    wp_enqueue_style('kilka-custom', get_template_directory_uri() . '/assets/css/custom-style.css' );
    $header_text_color = get_header_textcolor();
    
    $site_title_font = get_theme_mod( 'kilka_site_title_font', 'Roboto' );
    $site_title_size = get_theme_mod( 'kilka_site_title_size', '25' );
    
    $continue_reading_color = get_theme_mod( 'kilka_continue_reading_color', '#000000' );
    $continue_reading_weight = get_theme_mod( 'kilka_continue_reading_weight', '400' );

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

    $kilka_custom_css .= '
        .entry-content a.button {
            color: '.esc_attr( $continue_reading_color ).' !important;
            font-weight: '.esc_attr( $continue_reading_weight ).' !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-decoration: none;
            transition: all 0.3s ease;
            padding: 15px 0; /* Увеличиваем область нажатия по вертикали */
            min-width: 40px; /* Минимальная ширина для режима только стрелки */
        }

        /* Убираем стандартные рамки/фоны если они мешают центровке (опционально) */
        .entry-content a.button {
            background: transparent;
            border: none;
        }

        /* Стиль длинной стрелки */
        .kilka-button-arrow {
            display: inline-block;
            position: relative;
            width: 40px; /* Удвоенная длина */
            height: 2px;
            background-color: currentColor;
            vertical-align: middle;
        }

        .kilka-button-arrow::after {
            content: "";
            position: absolute;
            right: 0;
            top: 50%;
            width: 8px;
            height: 8px;
            border-top: 2px solid currentColor;
            border-right: 2px solid currentColor;
            transform: translateY(-50%) rotate(45deg);
        }

        /* Подстройка толщины в зависимости от жирности текста */
        '.( (int)$continue_reading_weight >= 600 ? '
        .kilka-button-arrow { height: 3px; }
        .kilka-button-arrow::after { border-width: 3px; }
        ' : '' ).'
        
        '.( (int)$continue_reading_weight <= 300 ? '
        .kilka-button-arrow { height: 1px; }
        .kilka-button-arrow::after { border-width: 1px; }
        ' : '' ).'

        .entry-content a.button:hover .kilka-button-arrow {
            width: 50px; /* Эффект при наведении */
        }
    ';

    wp_add_inline_style( 'kilka-custom', $kilka_custom_css );
}
add_action( 'wp_enqueue_scripts', 'kilka_custom_css' );
