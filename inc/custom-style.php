<?php
function kilka_custom_css() {
    wp_enqueue_style( 'kilka-custom', get_template_directory_uri() . '/assets/css/custom-style.css' );

    $header_text_color = sanitize_hex_color_no_hash( get_header_textcolor() );
    if ( ! $header_text_color ) {
        $header_text_color = '000000';
    }

    $site_title_font = get_theme_mod( 'kilka_site_title_font', 'Roboto' );
    if ( function_exists( 'kilka_sanitize_site_title_font' ) ) {
        $site_title_font = kilka_sanitize_site_title_font( $site_title_font );
    }

    $site_title_size = min( 100, max( 14, absint( get_theme_mod( 'kilka_site_title_size', 25 ) ) ) );

    $continue_reading_color = sanitize_hex_color( get_theme_mod( 'kilka_continue_reading_color', '#000000' ) );
    if ( ! $continue_reading_color ) {
        $continue_reading_color = '#000000';
    }

    $continue_reading_weight = get_theme_mod( 'kilka_continue_reading_weight', '400' );
    if ( function_exists( 'kilka_sanitize_continue_reading_weight' ) ) {
        $continue_reading_weight = kilka_sanitize_continue_reading_weight( $continue_reading_weight );
    }

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
    
    // Apply the appropriate generic fallback for local serif fonts.
    if ( in_array( $site_title_font, array( 'Playfair Display', 'Merriweather' ), true ) ) {
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

        /* Long arrow style */
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

        /* Thickness adjustment */
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
        /* Hide standard menu and area */
        .mainmenu-area { display: none !important; }
        .mainmenu { display: none !important; }
        
        /* Container for single-line alignment */
        .header-main-flex {
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            width: 100%;
            --kilka-site-title-size: '.esc_attr( $site_title_size ).'px;
            overflow: visible;
        }

        /* Center the title */
        .site-branding {
            flex: 1;
        }

        /* Align menu icon with the center of the sidebar column on desktop */
        .kilka-responsive-menu { 
            display: block !important;
            position: absolute;
            left: 83.333333%;
            right: auto;
            top: calc(30px + (var(--kilka-site-title-size) * 0.55));
            transform: translate(-50%, -50%);
            z-index: 11000;
            margin: 0 !important;
        }

        /* Keep right edge alignment on medium screens where columns stack differently */
        @media (max-width: 991px) {
            .kilka-responsive-menu {
                left: auto;
                right: 0;
                transform: translateY(-50%);
            }
        }

        /* Reduce header paddings */
        header#masthead {
            padding-bottom: 10px !important;
            margin-bottom: 0 !important;
        }

        .header-margin-top {
            margin-top: 20px !important;
        }

        /* Reduce distance to content */
        #content {
            margin-top: 0 !important;
            padding-top: 5px !important;
        }

        /* Slicknav button styling */
        .slicknav_menu {
            display: block !important;
            background: transparent !important;
            margin: 0 !important;
            padding: 0 !important;
            position: relative;
            width: 42px;
            z-index: 11000;
        }

        .slicknav_btn {
            align-items: center;
            background-color: #fff !important;
            border: 1px solid #d7d7d7 !important;
            border-radius: 50%;
            box-sizing: border-box;
            cursor: pointer;
            display: flex !important;
            float: none !important;
            height: 42px;
            justify-content: center;
            left: auto !important;
            margin: 0 !important;
            padding: 0 !important;
            text-shadow: none !important;
            transition: background-color 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
            width: 42px;
        }

        .slicknav_btn:hover,
        .slicknav_btn:focus-visible {
            background-color: #f2f2f2 !important;
            border-color: #aaa !important;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.1);
        }

        .slicknav_btn:focus-visible {
            outline: 2px solid #444;
            outline-offset: 3px;
        }

        /* Remove MENU text */
        .slicknav_btn::before {
            display: none !important;
        }

        .slicknav_menu .slicknav_icon {
            display: flex !important;
            flex-direction: column;
            gap: 4px;
            height: 18px !important;
            justify-content: center;
            margin: 0 !important;
            width: 18px !important;
        }

        .slicknav_menu .slicknav_icon-bar {
            background-color: #666 !important;
            border-radius: 2px;
            display: block !important;
            height: 2px !important;
            margin: 0 !important;
            transform-origin: center;
            transition: opacity 0.2s ease, transform 0.2s ease;
            width: 18px !important;
        }

        .slicknav_btn.slicknav_open span.slicknav_icon-bar:first-child {
            transform: translateY(6px) rotate(45deg) !important;
            transform-origin: center !important;
        }

        .slicknav_btn.slicknav_open span.slicknav_icon-bar:nth-child(2) {
            display: block !important;
            opacity: 0;
        }

        .slicknav_btn.slicknav_open span.slicknav_icon-bar:last-child {
            transform: translateY(-6px) rotate(-45deg) !important;
            transform-origin: center !important;
        }

        /* Dropdown menu */
        .slicknav_nav {
            background: #fff !important;
            border: 1px solid #e4e4e4;
            border-radius: 10px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
            display: none;
            left: auto !important;
            max-width: calc(100vw - 30px);
            min-width: 0;
            padding: 6px !important;
            position: absolute;
            right: 0 !important;
            text-align: left !important;
            top: calc(100% + 10px);
            transform: none !important;
            width: 280px;
            z-index: 11010;
        }
        
        .slicknav_open .slicknav_nav {
            display: block !important;
        }
        
        .slicknav_nav ul, .slicknav_nav li {
            display: block !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .slicknav_nav li + li {
            border-top: 1px solid #eee;
        }

        .slicknav_nav a {
            border: 0;
            border-radius: 6px;
            color: #333 !important;
            display: block !important;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: normal;
            line-height: 1.4;
            padding: 12px 14px !important;
            text-transform: none;
        }

        .slicknav_nav a:hover,
        .slicknav_nav a:focus {
            background-color: #f2f2f2 !important;
            color: #444 !important;
            text-decoration: none;
        }

        /* Keep the menu label available to screen readers. */
        .slicknav_menu .slicknav_menutxt {
            border: 0 !important;
            clip: rect(1px, 1px, 1px, 1px) !important;
            clip-path: inset(50%) !important;
            height: 1px !important;
            margin: -1px !important;
            overflow: hidden !important;
            padding: 0 !important;
            position: absolute !important;
            width: 1px !important;
        }

        /* Mobile responsiveness */
        @media (max-width: 767px) {
            .site-branding-main {
                align-items: center;
                box-sizing: border-box;
                display: grid;
                grid-template-columns: 42px minmax(0, 1fr) 42px;
                column-gap: 8px;
                width: 100%;
            }

            .site-branding-main::before {
                content: "";
                grid-column: 1;
            }

            .site-branding-identity {
                grid-column: 2;
                min-width: 0;
            }

            .kilka-responsive-menu {
                align-self: center;
                display: block !important;
                grid-column: 3;
                justify-self: end;
                left: auto;
                margin: 0 !important;
                position: relative;
                right: auto;
                top: auto;
                transform: none;
                width: 42px;
            }
        }
    ';

    wp_add_inline_style( 'kilka-custom', $kilka_custom_css );
}
add_action( 'wp_enqueue_scripts', 'kilka_custom_css' );
