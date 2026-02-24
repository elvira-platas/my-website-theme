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
        $font_weight = '400'; 
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
            padding: 15px 0; 
            min-width: 40px; 
            background: transparent;
            border: none;
        }

        /* Стиль длинной стрелки */
        .kilka-button-arrow {
            display: inline-block;
            position: relative;
            width: 40px; 
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

        /* Подстройка толщины */
        '.( (int)$continue_reading_weight >= 600 ? '
        .kilka-button-arrow { height: 3px; }
        .kilka-button-arrow::after { border-width: 3px; }
        ' : '' ).'
        
        '.( (int)$continue_reading_weight <= 300 ? '
        .kilka-button-arrow { height: 1px; }
        .kilka-button-arrow::after { border-width: 1px; }
        ' : '' ).'

        .entry-content a.button:hover .kilka-button-arrow {
            width: 50px; 
        }
    ';

    $kilka_custom_css .= '
        /* Скрытие стандартного меню и области */
        .mainmenu-area { display: none !important; }
        .mainmenu { display: none !important; }
        
        /* Контейнер для выравнивания в одну строку */
        .header-main-flex {
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            width: 100%;
        }

        /* Название по центру */
        .site-branding {
            flex: 1;
        }

        /* Принудительно показываем контейнер меню справа */
        .kilka-responsive-menu { 
            display: block !important;
            position: absolute;
            right: 15px; /* Совпадает с padding-right колонок Bootstrap */
            top: 50%;
            transform: translateY(-50%);
            margin: 0 !important;
        }

        /* Уменьшаем отступы шапки */
        header#masthead {
            padding-bottom: 10px !important;
            margin-bottom: 0 !important;
        }

        .header-margin-top {
            margin-top: 20px !important;
        }

        /* Сокращаем расстояние до контента */
        #content {
            margin-top: 0 !important;
            padding-top: 5px !important;
        }

        /* Стилизация кнопки slicknav */
        .slicknav_menu {
            display: block !important; 
            background: transparent !important;
            padding: 0 !important;
        }

        .slicknav_btn {
            background-color: transparent !important;
            text-shadow: none !important;
            padding: 0 !important;
            display: block !important;
            cursor: pointer;
            margin: 0 !important;
        }

        /* Убираем текст MENU */
        .slicknav_btn::before {
            display: none !important;
        }

        .slicknav_icon {
            margin: 0 !important;
            width: 24px;
        }

        .slicknav_icon-bar {
            background-color: #000 !important; 
            width: 24px !important;
            height: 2px !important;
            margin: 5px 0 !important;
            display: block;
        }

        /* Выпадающее меню */
        .slicknav_nav {
            display: none;
            position: absolute;
            right: 0 !important;
            left: auto !important;
            transform: none !important;
            top: 100%;
            background: #fff !important;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            min-width: 200px;
            text-align: left !important;
            padding: 10px 0 !important;
            border: 1px solid #eee;
            z-index: 9999;
        }
        
        .slicknav_open .slicknav_nav {
            display: block !important;
        }
        
        .slicknav_nav ul, .slicknav_nav li {
            display: block !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .slicknav_nav a {
            color: #333 !important;
            padding: 12px 25px !important;
            text-transform: uppercase;
            font-size: 12px;
            font-weight: 700;
            display: block !important;
            border-bottom: 1px solid #f5f5f5;
        }

        .slicknav_nav a:hover {
            background: #000 !important;
            color: #fff !important;
        }

        /* Скрываем штатную иконку slicknav */
        .slicknav_menu .slicknav_menutxt { display: none !important; }

        /* Адаптивность для мобильных */
        @media (max-width: 768px) {
            .kilka-responsive-menu {
                display: none !important;
            }
        }
    ';

    wp_add_inline_style( 'kilka-custom', $kilka_custom_css );
}
add_action( 'wp_enqueue_scripts', 'kilka_custom_css' );