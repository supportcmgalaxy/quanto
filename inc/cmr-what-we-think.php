<?php
/**
 * Shortcode for What We Think Section
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! function_exists( 'cmr_what_we_think_shortcode' ) ) {
    function cmr_what_we_think_shortcode( $atts ) {
        $atts = shortcode_atts( array(
            'section_label'  => 'What We Think',
            'section_title'  => 'Insights That Drive Strategic<br> Business Decisions',
        ), $atts );

        $slides = array();
        
        $categories_to_fetch = array(
            'market-updates'    => 'Market Updates',
            'industry-insights' => 'Industry Insights',
            'research-reports'  => 'Research Reports'
        );

        $used_post_ids = array();

        $default_fallback_cards = array(
            'market-updates' => array(
                array( 'title' => 'What the UAE Is Doing With Agentic AI — And What India Can Learn?', 'link' => '#', 'image' => 'https://images.unsplash.com/photo-1677442136019-21780ecad995?w=600&q=80', 'cat' => 'Market Updates' ),
                array( 'title' => 'The Philippines: Charting a Course as Southeast Asia\'s Digital Economy Leader', 'link' => '#', 'image' => 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=600&q=80', 'cat' => 'Market Updates' ),
            ),
            'industry-insights' => array(
                array( 'title' => 'How AI Is Reshaping Supply Chain Management Across Asia-Pacific', 'link' => '#', 'image' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=600&q=80', 'cat' => 'Industry Insights' ),
                array( 'title' => 'Digital Transformation in Healthcare: Lessons from Leading Markets', 'link' => '#', 'image' => 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=600&q=80', 'cat' => 'Industry Insights' ),
            ),
            'research-reports' => array(
                array( 'title' => 'Global Cloud Infrastructure Spending Report Q1 2026', 'link' => '#', 'image' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=600&q=80', 'cat' => 'Research Reports' ),
                array( 'title' => 'The Future of Work: Hybrid Models and Their Impact on Productivity', 'link' => '#', 'image' => 'https://images.unsplash.com/photo-1497215842964-222b430dc094?w=600&q=80', 'cat' => 'Research Reports' ),
            ),
        );

        foreach ( $categories_to_fetch as $slug => $label ) {
            $posts = array();

            if ( $slug === 'research-reports' && class_exists( 'WooCommerce' ) ) {
                // For Research Reports, query WooCommerce products
                $product_args = array(
                    'post_type'      => 'product',
                    'posts_per_page' => 2,
                    'post_status'    => 'publish',
                    'orderby'        => 'date',
                    'order'          => 'DESC',
                    'post__not_in'   => $used_post_ids,
                    'meta_query'     => array(
                        array( 'key' => '_thumbnail_id', 'compare' => 'EXISTS' )
                    )
                );
                $posts = get_posts( $product_args );
            } else {
                // Determine matching category terms
                $category_slugs = array( $slug );
                if ( $slug === 'industry-insights' ) {
                    $category_slugs = array( 'industry-insights', 'industry-insight', 'insights', 'industry', 'viewpoints', 'viewpoint', 'industry-connect' );
                } elseif ( $slug === 'market-updates' ) {
                    $category_slugs = array( 'market-updates', 'market-update' );
                }

                // 1. Try querying standard posts matching categories
                $query_args = array(
                    'post_type'      => 'post',
                    'posts_per_page' => 2,
                    'post_status'    => 'publish',
                    'orderby'        => 'date',
                    'order'          => 'DESC',
                    'post__not_in'   => $used_post_ids,
                    'tax_query'      => array(
                        array(
                            'taxonomy' => 'category',
                            'field'    => 'slug',
                            'terms'    => $category_slugs,
                        ),
                    ),
                    'meta_query'     => array(
                        array( 'key' => '_thumbnail_id', 'compare' => 'EXISTS' )
                    )
                );
                $posts = get_posts( $query_args );

                // Fallback to category_name if tax_query returned nothing
                if ( empty( $posts ) ) {
                    $cat_name_args = array(
                        'post_type'      => 'post',
                        'posts_per_page' => 2,
                        'post_status'    => 'publish',
                        'orderby'        => 'date',
                        'order'          => 'DESC',
                        'post__not_in'   => $used_post_ids,
                        'category_name'  => implode( ',', $category_slugs ),
                        'meta_query'     => array(
                            array( 'key' => '_thumbnail_id', 'compare' => 'EXISTS' )
                        )
                    );
                    $posts = get_posts( $cat_name_args );
                }

                // 2. If we don't have 2 posts, check cmr_news
                if ( count( $posts ) < 2 ) {
                    $exclude_ids = array_unique( array_merge( $used_post_ids, wp_list_pluck( $posts, 'ID' ) ) );
                    $news_args = array(
                        'post_type'      => 'cmr_news',
                        'posts_per_page' => 2 - count( $posts ),
                        'post_status'    => 'publish',
                        'orderby'        => 'date',
                        'order'          => 'DESC',
                        'post__not_in'   => $exclude_ids,
                        'tax_query'      => array(
                            'relation' => 'OR',
                            array(
                                'taxonomy' => 'cmr_news_category',
                                'field'    => 'slug',
                                'terms'    => $category_slugs,
                            ),
                            array(
                                'taxonomy' => 'category',
                                'field'    => 'slug',
                                'terms'    => $category_slugs,
                            ),
                        ),
                        'meta_query'     => array(
                            array( 'key' => '_thumbnail_id', 'compare' => 'EXISTS' )
                        )
                    );
                    $news_posts = get_posts( $news_args );
                    if ( ! empty( $news_posts ) ) {
                        $posts = array_merge( $posts, $news_posts );
                    }
                }
            }

            // Deduplicate collected posts by ID and Title
            $deduped_posts = array();
            $seen_ids = array();
            $seen_titles = array();
            foreach ( $posts as $p ) {
                if ( ! is_object( $p ) || empty( $p->ID ) ) {
                    continue;
                }
                $t = trim( get_the_title( $p ) );
                if ( in_array( $p->ID, $seen_ids ) || ( ! empty( $t ) && in_array( $t, $seen_titles ) ) ) {
                    continue;
                }
                $seen_ids[] = $p->ID;
                if ( ! empty( $t ) ) {
                    $seen_titles[] = $t;
                }
                $deduped_posts[] = $p;
            }
            $posts = $deduped_posts;

            // 3. Fallback: If still not enough, grab any latest posts not yet used or seen
            if ( count( $posts ) < 2 ) {
                $exclude_ids = array_unique( array_merge( $used_post_ids, $seen_ids ) );
                $fallback_args = array(
                    'post_type'      => array( 'post', 'cmr_news' ),
                    'posts_per_page' => 2 - count( $posts ),
                    'post_status'    => 'publish',
                    'orderby'        => 'date',
                    'order'          => 'DESC',
                    'post__not_in'   => $exclude_ids,
                    'meta_query'     => array(
                        array( 'key' => '_thumbnail_id', 'compare' => 'EXISTS' )
                    )
                );
                $fallback_posts = get_posts( $fallback_args );

                // If still not enough, try without the thumbnail requirement
                if ( ( count( $posts ) + count( $fallback_posts ) ) < 2 ) {
                    $exclude_ids_2 = array_unique( array_merge( $exclude_ids, wp_list_pluck( $fallback_posts, 'ID' ) ) );
                    $fallback_args2 = array(
                        'post_type'      => array( 'post', 'cmr_news' ),
                        'posts_per_page' => 2 - count( $posts ) - count( $fallback_posts ),
                        'post_status'    => 'publish',
                        'orderby'        => 'date',
                        'order'          => 'DESC',
                        'post__not_in'   => $exclude_ids_2,
                    );
                    $fallback_posts2 = get_posts( $fallback_args2 );
                    $fallback_posts = array_merge( $fallback_posts, $fallback_posts2 );
                }

                foreach ( $fallback_posts as $fp ) {
                    if ( ! is_object( $fp ) || empty( $fp->ID ) ) {
                        continue;
                    }
                    $t = trim( get_the_title( $fp ) );
                    if ( in_array( $fp->ID, $seen_ids ) || ( ! empty( $t ) && in_array( $t, $seen_titles ) ) ) {
                        continue;
                    }
                    $seen_ids[] = $fp->ID;
                    if ( ! empty( $t ) ) {
                        $seen_titles[] = $t;
                    }
                    $posts[] = $fp;
                    if ( count( $posts ) >= 2 ) {
                        break;
                    }
                }
            }

            // Build slide cards from posts
            $slide_data = array();
            foreach ( $posts as $post_obj ) {
                $post_title = get_the_title( $post_obj );
                $post_link  = get_permalink( $post_obj->ID );
                $thumbnail_url = get_the_post_thumbnail_url( $post_obj->ID, 'full' );
                if ( ! $thumbnail_url ) {
                    $thumbnail_url = get_the_post_thumbnail_url( $post_obj->ID, 'large' );
                }
                if ( ! $thumbnail_url ) {
                    $thumbnail_url = 'https://images.unsplash.com/photo-1677442136019-21780ecad995?w=600&q=80';
                }

                $slide_data[] = array(
                    'title' => $post_title,
                    'link'  => $post_link,
                    'image' => $thumbnail_url,
                    'cat'   => $label,
                );

                // Add to used_post_ids so subsequent tabs don't repeat the same posts either
                $used_post_ids[] = $post_obj->ID;
            }

            // If still under 2 items, top up with default fallback cards so cards are NEVER identical
            if ( count( $slide_data ) < 2 && isset( $default_fallback_cards[ $slug ] ) ) {
                foreach ( $default_fallback_cards[ $slug ] as $def_card ) {
                    $is_duplicate = false;
                    foreach ( $slide_data as $sd ) {
                        if ( trim( $sd['title'] ) === trim( $def_card['title'] ) ) {
                            $is_duplicate = true;
                            break;
                        }
                    }
                    if ( ! $is_duplicate ) {
                        $slide_data[] = $def_card;
                    }
                    if ( count( $slide_data ) >= 2 ) {
                        break;
                    }
                }
            }

            $slides[] = $slide_data;
        }
        
        // If no posts found, provide some dummy slides to match the original design layout exactly
        if (empty($slides)) {
            $slides = [
                [
                    ['title' => 'What the UAE Is Doing With Agentic AI — And What India Can Learn?', 'cat' => 'Market Updates', 'image' => 'https://images.unsplash.com/photo-1677442136019-21780ecad995?w=600&q=80', 'link' => '#'],
                    ['title' => 'The Philippines: Charting a Course as Southeast Asia\'s Digital Economy Leader', 'cat' => 'Market Updates', 'image' => 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=600&q=80', 'link' => '#'],
                ],
                [
                    ['title' => 'How AI Is Reshaping Supply Chain Management Across Asia-Pacific', 'cat' => 'Industry Insights', 'image' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=600&q=80', 'link' => '#'],
                    ['title' => 'Digital Transformation in Healthcare: Lessons from Leading Markets', 'cat' => 'Industry Insights', 'image' => 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=600&q=80', 'link' => '#'],
                ],
                [
                    ['title' => 'Global Cloud Infrastructure Spending Report Q1 2026', 'cat' => 'Research Reports', 'image' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=600&q=80', 'link' => '#'],
                    ['title' => 'The Future of Work: Hybrid Models and Their Impact on Productivity', 'cat' => 'Research Reports', 'image' => 'https://images.unsplash.com/photo-1497215842964-222b430dc094?w=600&q=80', 'link' => '#'],
                ]
            ];
        }

        ob_start();
        ?>
        <style>
        .cmr-wwt-wrap, .cmr-wwt-wrap * { box-sizing:border-box; }

        .cmr-wwt-wrap {
            position: relative;
            background: #ffffff;
            color: #1a1a2e;
            overflow: visible;
            margin-bottom: 70px;
            padding-top: 70px;
        }

        .cmr-wwt-panel {
            width: 100%;
            min-height: auto;
            height: auto;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            z-index: 2;
            padding-top: 10px;
            padding-bottom: 80px;
        }

        .cmr-wwt-inner {
            display: flex;
            flex-direction: column;
            width: 100%;
            max-width: 1280px;
            padding: 0 20px;
            margin: 0 auto;
        }

        .cmr-wwt-header { margin-bottom: 12px; }

        .cmr-wwt-section-label {
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #000;
            margin-bottom: 8px;
            font-family: 'Instrument Sans', sans-serif;
        }

        .cmr-wwt-heading {
            font-size: 38px; /* Compact height to leave plenty of room for card titles */
            font-weight: 600;
            line-height: 1.2;
            color: #1a1a2e;
            max-width: 100%;
            font-family: 'Instrument Sans', sans-serif;
            margin: 0;
            letter-spacing: -1px;
        }

        .cmr-wwt-card { 
            flex: 1; 
            min-width: 0; 
            max-width: 433px;
            display: flex;
            flex-direction: column;
        }

        .cmr-wwt-card-img {
            width: 100%;
            max-width: 433px;
            aspect-ratio: 16 / 10;
            overflow: hidden;
            margin-bottom: 12px;
            background: #f4f5f8;
            border-radius: 0 !important;
            display: block;
        }
        .cmr-wwt-card-img a {
            display: block;
            width: 100%;
            height: 100%;
        }
        .cmr-wwt-card-img img {
            width: 100%;
            height: 100%;
            object-fit: cover; 
            object-position: center center;
            display: block; 
            border-radius: 0 !important;
            transform: scale(1);
            transition: transform 0.4s cubic-bezier(0.25, 1, 0.5, 1) !important;
        }
        .cmr-wwt-card:hover .cmr-wwt-card-img img,
        .cmr-wwt-card-img:hover img {
            transform: scale(1.06) !important;
        }
        /* For Research Reports: Use 1:1 aspect-ratio / width: 100% with object-fit: cover and center alignment so the image fills the card cleanly with no side whitespace/bars and no text cut */
        .cmr-wwt-card-research-reports .cmr-wwt-card-img,
        .cmr-wwt-slide[data-s="2"] .cmr-wwt-card-img {
            height: auto !important;
            aspect-ratio: 1 / 1 !important;
            width: 100% !important;
            max-width: 433px !important;
            background: transparent !important;
            border-radius: 0 !important;
            padding: 0 !important;
            box-sizing: border-box;
            margin-bottom: 12px;
            overflow: hidden;
        }
        .cmr-wwt-card-research-reports .cmr-wwt-card-img a,
        .cmr-wwt-slide[data-s="2"] .cmr-wwt-card-img a {
            width: 100% !important;
            height: 100% !important;
            display: block !important;
        }
        .cmr-wwt-card-research-reports .cmr-wwt-card-img img,
        .cmr-wwt-slide[data-s="2"] .cmr-wwt-card-img img {
            object-fit: cover !important;
            object-position: center top !important;
            width: 100% !important;
            height: 100% !important;
            max-width: 100% !important;
            max-height: 100% !important;
            border-radius: 0 !important;
            display: block;
            transform: scale(1);
            transition: transform 0.4s cubic-bezier(0.25, 1, 0.5, 1) !important;
        }
        .cmr-wwt-card-research-reports:hover .cmr-wwt-card-img img,
        .cmr-wwt-slide[data-s="2"] .cmr-wwt-card:hover .cmr-wwt-card-img img {
            transform: scale(1.06) !important;
        }

        .cmr-wwt-card-cat { display:flex; align-items:center; gap:8px; margin-bottom:8px; cursor:pointer; }
        .cmr-wwt-card-cat-line { width:20px; height:2px; background:#6B3FA0; flex-shrink:0; }
        .cmr-wwt-card-cat-text { font-size:12px; font-weight:400; color:#6B3FA0; letter-spacing:0.3px; text-transform: uppercase; }

        .cmr-wwt-card-title {
            font-size: 17px; font-weight: 600; line-height: 1.35;
            color: #1a1a2e; margin-bottom: 10px; min-height: 38px;
            font-family: 'Instrument Sans', sans-serif;
            transition: color 0.3s ease !important;
        }
        .cmr-wwt-card-title a {
            text-decoration: none;
            color: inherit;
            transition: color 0.3s ease !important;
        }
        .cmr-wwt-card:hover .cmr-wwt-card-title,
        .cmr-wwt-card:hover .cmr-wwt-card-title a,
        .cmr-wwt-card-title:hover,
        .cmr-wwt-card-title a:hover {
            color: #4820B0 !important;
        }

        .cmr-wwt-card-link {
            display: inline-flex !important;
            align-items: center;
            gap: 6px;
            width: fit-content !important;
            max-width: max-content !important;
            font-family: "Instrument Sans", sans-serif;
            font-size: 13px;
            font-weight: 600;
            line-height: 1.2;
            color: #111;
            text-decoration: none !important;
            background: none;
            border: none;
            padding: 0 0 4px 0;
            border-bottom: 2px solid #111 !important;
            margin-top: 40px !important;
            transition: color 0.3s ease, border-color 0.3s ease;
        }
        .cmr-wwt-card-link:hover { 
            color: #4625A9 !important; 
            border-bottom-color: #4625A9 !important; 
        }
        .cmr-wwt-card-link::after {
            display: none !important;
        }
        .cmr-wwt-card-link svg {
            width: 13px;
            height: 11px;
            fill: currentColor;
            display: inline-block;
            vertical-align: middle;
            margin-left: 6px;
            transition: transform 0.3s ease, fill 0.3s ease;
        }
        .cmr-wwt-card-link:hover svg {
            transform: translate(2px, -2px);
        }

        .cmr-wwt-menu-item {
            display: flex; align-items: center; gap: 12px;
            padding: 8px 0; cursor: pointer;
            color: #d1d1d1;
            transition: color 0.3s ease;
        }
        .cmr-wwt-menu-item:hover, .cmr-wwt-menu-item.cmr-wwt-on { color: #401083; }
        
        .cmr-wwt-bullet {
            width: 10px; height: 10px; border-radius: 50%;
            background: #d1d1d1; transition: background 0.3s ease;
        }
        .cmr-wwt-menu-label { font-size: 30px; font-weight: 600; letter-spacing: -1px; line-height: 1.2; }

        .cmr-wwt-menu-item:hover .cmr-wwt-bullet,
        .cmr-wwt-menu-item.cmr-wwt-on .cmr-wwt-bullet { background: #401083; }

        .cmr-wwt-right-col { 
            flex: 1; 
            min-width: 0; 
            position: relative; 
            min-height: 600px;
        }

        .cmr-wwt-slide {
            position: absolute; top: 0; left: 0; width: 100%;
            display: flex; gap: 24px;
            opacity: 0;
            transform: translateY(40px);
            transition: opacity 1.5s ease, transform 1.5s ease;
            pointer-events: none;
        }
        .cmr-wwt-slide:first-child { position: relative; }
        .cmr-wwt-slide.cmr-wwt-show { 
            opacity: 1; 
            transform: translateY(0);
            pointer-events: auto; 
        }

        @media (max-width: 1024px) {
            .cmr-wwt-heading { font-size: 32px; }
            .cmr-wwt-left-col { flex: 0 0 190px; }
            .cmr-wwt-menu-label { font-size: 20px; }
            .cmr-wwt-desktop-content { display: flex; align-items: flex-start; gap: 24px; margin-top: 12px; }
            .cmr-wwt-mobile-content { display: none; }
        }
        
        @media (min-width: 1025px) {
            .cmr-wwt-desktop-content { display: flex; align-items: flex-start; gap: 80px; margin-top: 12px; }
            .cmr-wwt-mobile-content { display: none; }
        }

        @media (max-width: 768px) {
            .cmr-wwt-wrap { height: auto !important; padding-top: 40px !important; margin-bottom: 40px !important; }
            .cmr-wwt-panel { position: relative !important; height: auto !important; padding: 10px 0 20px 0 !important; }
            .cmr-wwt-heading { font-size: 28px; }
            .cmr-wwt-header { margin-bottom: 20px; }
            .cmr-wwt-desktop-content { display: none; }
            .cmr-wwt-mobile-content { display: block; margin-top: 20px; }
            
            .cmr-wwt-acc-item { margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px; }
            .cmr-wwt-acc-item:last-child { border-bottom: none; }
            
            .cmr-wwt-acc-header { 
                display: flex; align-items: center; justify-content: space-between; 
                padding: 15px 0; cursor: pointer; 
            }
            .cmr-wwt-acc-title-wrap { display: flex; align-items: center; gap: 12px; }
            .cmr-wwt-acc-bullet { width: 10px; height: 10px; border-radius: 50%; background: #111; transition: background 0.3s; }
            .cmr-wwt-acc-title { font-size: 20px; font-weight: 600; color: #111; margin: 0; font-family: 'Instrument Sans', sans-serif; letter-spacing: -0.5px; transition: color 0.3s; }
            
            .cmr-wwt-acc-icon { 
                width: 24px; height: 24px; display: flex; align-items: center; justify-content: center;
                transition: transform 0.3s ease; color: #6B3FA0;
            }
            .cmr-wwt-acc-icon svg { width: 16px; height: 16px; fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
            
            .cmr-wwt-acc-content { 
                max-height: 0; overflow: hidden; transition: max-height 0.4s ease; 
            }
            .cmr-wwt-acc-inner { padding: 10px 0 20px 22px; display: flex; flex-direction: column; gap: 24px; }
            
            /* Active State */
            .cmr-wwt-acc-item.active .cmr-wwt-acc-bullet { background: #6B3FA0; }
            .cmr-wwt-acc-item.active .cmr-wwt-acc-title { color: #6B3FA0; }
            .cmr-wwt-acc-item.active .cmr-wwt-acc-icon { transform: rotate(90deg); }
            
            .cmr-wwt-card-img { width: 100%; max-width: 100%; height: auto; aspect-ratio: 16 / 10; }
            .cmr-wwt-card-research-reports .cmr-wwt-card-img {
                height: auto !important;
                aspect-ratio: 1 / 1;
                background: transparent !important;
                padding: 0 !important;
                box-sizing: border-box;
                overflow: hidden;
            }
            .cmr-wwt-card-research-reports .cmr-wwt-card-img img {
                object-fit: cover !important;
                object-position: center top !important;
                width: 100% !important;
                height: 100% !important;
                display: block;
            }
            .cmr-wwt-card-title { font-size: 16px; min-height: auto; }
        }
        </style>

        <div class="cmr-wwt-wrap">
            <div class="cmr-wwt-panel">
                <div class="cmr-wwt-inner">
                    <div class="cmr-wwt-header">
                        <div class="cmr-wwt-section-label"><?php echo esc_html( $atts['section_label'] ); ?></div>
                        <h2 class="cmr-wwt-heading"><?php echo wp_kses_post( $atts['section_title'] ); ?></h2>
                    </div>
                    
                    <div class="cmr-wwt-desktop-content">
                        <div class="cmr-wwt-left-col">
                            <?php 
                                $preset_labels = ['Market Updates', 'Industry Insights', 'Research Reports'];
                                foreach($slides as $index => $slide): 
                                $menu_label = isset($preset_labels[$index]) ? $preset_labels[$index] : (isset($slide[0]['cat']) ? $slide[0]['cat'] : 'Insights');
                            ?>
                                <div class="cmr-wwt-menu-item <?php echo $index === 0 ? 'cmr-wwt-on' : ''; ?>" data-i="<?php echo esc_attr($index); ?>">
                                    <span class="cmr-wwt-bullet"></span>
                                    <span class="cmr-wwt-menu-label"><?php echo esc_html($menu_label); ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="cmr-wwt-right-col">
                            <?php foreach($slides as $index => $slide): 
                                $is_research_reports = ($index === 2);
                            ?>
                                <div class="cmr-wwt-slide <?php echo $index === 0 ? 'cmr-wwt-show' : ''; ?>" data-s="<?php echo esc_attr($index); ?>">
                                    <?php foreach($slide as $post): ?>
                                        <div class="cmr-wwt-card <?php echo $is_research_reports ? 'cmr-wwt-card-research-reports' : ''; ?>">
                                            <div class="cmr-wwt-card-img">
                                                <a href="<?php echo esc_url($post['link']); ?>">
                                                    <img src="<?php echo esc_url($post['image']); ?>" alt="<?php echo esc_attr($post['title']); ?>">
                                                </a>
                                            </div>
                                            <div class="cmr-wwt-card-cat" data-i="<?php echo esc_attr($index); ?>">
                                                <span class="cmr-wwt-card-cat-line"></span>
                                                <?php $card_cat = isset($preset_labels[$index]) ? $preset_labels[$index] : $post['cat']; ?>
                                                <span class="cmr-wwt-card-cat-text"><?php echo esc_html($card_cat); ?></span>
                                            </div>
                                            <div class="cmr-wwt-card-title">
                                                <a href="<?php echo esc_url($post['link']); ?>" style="text-decoration: none; color: inherit;">
                                                    <?php echo esc_html($post['title']); ?>
                                                </a>
                                            </div>
                                            <a class="cmr-wwt-card-link" href="<?php echo esc_url($post['link']); ?>">More Details <svg class="cmr-wwt-arrow" xmlns="http://www.w3.org/2000/svg" width="13" height="11" viewBox="0 0 16 14" fill="none"><path d="M2.04895 4.52205V3.54978C2.07104 3.12993 2.4025 2.79848 2.80025 2.79848L11.396 2.77638C11.8159 2.79848 12.1473 3.12993 12.1473 3.52768V12.1455C12.1473 12.5433 11.8159 12.8747 11.396 12.8968H10.4237C10.0039 12.8747 9.67244 12.5433 9.65035 12.1234L9.78293 6.90853L3.44106 13.2504C3.1317 13.5598 2.68976 13.5598 2.3804 13.2504L1.6733 12.5433C1.38603 12.256 1.36394 11.792 1.6733 11.4826L8.01516 5.14077L2.82234 5.29545C2.4025 5.27335 2.04895 4.96399 2.04895 4.52205Z" fill="currentColor"></path></svg></a>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="cmr-wwt-mobile-content">
                        <?php foreach($slides as $index => $slide): 
                            $menu_label = isset($preset_labels[$index]) ? $preset_labels[$index] : (isset($slide[0]['cat']) ? $slide[0]['cat'] : 'Insights');
                            $is_research_reports = ($index === 2);
                        ?>
                            <div class="cmr-wwt-acc-item <?php echo $index === 0 ? 'active' : ''; ?>">
                                <div class="cmr-wwt-acc-header">
                                    <div class="cmr-wwt-acc-title-wrap">
                                        <span class="cmr-wwt-acc-bullet"></span>
                                        <h3 class="cmr-wwt-acc-title"><?php echo esc_html($menu_label); ?></h3>
                                    </div>
                                    <div class="cmr-wwt-acc-icon">
                                        <svg viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"></path></svg>
                                    </div>
                                </div>
                                <div class="cmr-wwt-acc-content" style="<?php echo $index === 0 ? 'max-height: 2000px;' : ''; ?>">
                                    <div class="cmr-wwt-acc-inner">
                                        <?php foreach($slide as $post): ?>
                                            <div class="cmr-wwt-card <?php echo $is_research_reports ? 'cmr-wwt-card-research-reports' : ''; ?>">
                                                <div class="cmr-wwt-card-img">
                                                    <a href="<?php echo esc_url($post['link']); ?>">
                                                        <img src="<?php echo esc_url($post['image']); ?>" alt="<?php echo esc_attr($post['title']); ?>">
                                                    </a>
                                                </div>
                                                <div class="cmr-wwt-card-cat">
                                                    <span class="cmr-wwt-card-cat-line"></span>
                                                    <?php $card_cat = isset($preset_labels[$index]) ? $preset_labels[$index] : $post['cat']; ?>
                                                    <span class="cmr-wwt-card-cat-text"><?php echo esc_html($card_cat); ?></span>
                                                </div>
                                                <div class="cmr-wwt-card-title">
                                                    <a href="<?php echo esc_url($post['link']); ?>" style="text-decoration: none; color: inherit;">
                                                        <?php echo esc_html($post['title']); ?>
                                                    </a>
                                                </div>
                                                <a class="cmr-wwt-card-link" href="<?php echo esc_url($post['link']); ?>">More Details <svg class="cmr-wwt-arrow" xmlns="http://www.w3.org/2000/svg" width="13" height="11" viewBox="0 0 16 14" fill="none"><path d="M2.04895 4.52205V3.54978C2.07104 3.12993 2.4025 2.79848 2.80025 2.79848L11.396 2.77638C11.8159 2.79848 12.1473 3.12993 12.1473 3.52768V12.1455C12.1473 12.5433 11.8159 12.8747 11.396 12.8968H10.4237C10.0039 12.8747 9.67244 12.5433 9.65035 12.1234L9.78293 6.90853L3.44106 13.2504C3.1317 13.5598 2.68976 13.5598 2.3804 13.2504L1.6733 12.5433C1.38603 12.256 1.36394 11.792 1.6733 11.4826L8.01516 5.14077L2.82234 5.29545C2.4025 5.27335 2.04895 4.96399 2.04895 4.52205Z" fill="currentColor"></path></svg></a>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Failsafe wait for GSAP
            function initWWTScroll() {
                if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') {
                    setTimeout(initWWTScroll, 50);
                    return;
                }
                
                // If on mobile, ignore GSAP pinning entirely for better UX
                if (window.innerWidth <= 768) {
                    initMobileWWT();
                    return;
                }
                
                gsap.registerPlugin(ScrollTrigger);
                
                const wraps = document.querySelectorAll('.cmr-wwt-wrap');
                
                wraps.forEach(wrap => {
                    const panel = wrap.querySelector('.cmr-wwt-panel');
                    const rightCol = wrap.querySelector('.cmr-wwt-right-col');
                    const slides = wrap.querySelectorAll('.cmr-wwt-slide');
                    const menuItems = wrap.querySelectorAll('.cmr-wwt-menu-item');
                    const totalSlides = slides.length;
                    
                    if (totalSlides <= 1) return; // No need to pin if only 1 slide

                    // Dynamically calculate tallest slide height
                    function syncColHeight() {
                        let maxHeight = 0;
                        slides.forEach(s => {
                            if (s.scrollHeight > maxHeight) maxHeight = s.scrollHeight;
                        });
                        if (maxHeight > 0 && rightCol) {
                            rightCol.style.minHeight = (maxHeight + 20) + 'px';
                        }
                    }
                    syncColHeight();
                    window.addEventListener('resize', syncColHeight);
                    
                    // Set scroll duration (amount of pinning)
                    const scrollDuration = totalSlides * window.innerHeight;
                    
                    ScrollTrigger.create({
                        trigger: wrap,
                        start: "top top+=80", // Account for sticky headers if any
                        end: "+=" + scrollDuration,
                        pin: panel,
                        pinSpacing: true,
                        scrub: true,
                        onUpdate: self => {
                            // Calculate current active slide index based on progress
                            let rawIndex = self.progress * totalSlides;
                            let idx = Math.min(totalSlides - 1, Math.floor(rawIndex));
                            
                            // Prevent precision issues at the very end
                            if (self.progress > 0.99) idx = totalSlides - 1;
                            
                            // Update active classes
                            menuItems.forEach((m, i) => m.classList.toggle("cmr-wwt-on", i === idx));
                            slides.forEach((s, i) => s.classList.toggle("cmr-wwt-show", i === idx));
                        }
                    });
                    
                    // Click handlers for menu items to scroll smoothly to their respective points
                    menuItems.forEach((item, i) => {
                        item.addEventListener('click', function() {
                            // Find the ScrollTrigger instance associated with this wrap
                            const st = ScrollTrigger.getAll().find(t => t.trigger === wrap);
                            if (st) {
                                // Calculate pixel offset for the targeted slide
                                const targetY = st.start + (i / totalSlides) * (st.end - st.start) + 10;
                                window.scrollTo({ top: targetY, behavior: 'smooth' });
                            }
                        });
                    });
                });
            }
            
            function initMobileWWT() {
                const wraps = document.querySelectorAll('.cmr-wwt-wrap');
                wraps.forEach(wrap => {
                    const accItems = wrap.querySelectorAll('.cmr-wwt-acc-item');
                    
                    accItems.forEach((item) => {
                        const header = item.querySelector('.cmr-wwt-acc-header');
                        const content = item.querySelector('.cmr-wwt-acc-content');
                        
                        if(header && content) {
                            header.addEventListener('click', function() {
                                const isActive = item.classList.contains('active');
                                
                                // Close all others
                                accItems.forEach(acc => {
                                    acc.classList.remove('active');
                                    const accContent = acc.querySelector('.cmr-wwt-acc-content');
                                    if(accContent) {
                                        accContent.style.maxHeight = '0px';
                                    }
                                });
                                
                                // Open this one if it wasn't active
                                if(!isActive) {
                                    item.classList.add('active');
                                    content.style.maxHeight = content.scrollHeight + 'px';
                                }
                            });
                        }
                    });
                    
                    // On initial load, calculate height for the already active item
                    const activeItem = wrap.querySelector('.cmr-wwt-acc-item.active');
                    if(activeItem) {
                        const content = activeItem.querySelector('.cmr-wwt-acc-content');
                        if(content) {
                            // setTimeout to ensure DOM is fully rendered for scrollHeight
                            setTimeout(() => {
                                content.style.maxHeight = content.scrollHeight + 'px';
                            }, 100);
                        }
                    }
                });
            }
            
            initWWTScroll();
        });
        </script>
        <?php
        return ob_get_clean();
    }
}
add_shortcode( 'cmr_what_we_think', 'cmr_what_we_think_shortcode' );

