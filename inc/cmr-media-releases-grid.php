<?php
/**
 * CMR Media Releases Grid Component
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! function_exists( 'cmr_get_unique_media_releases' ) ) {
    /**
     * Fetch media releases ensuring NO duplicate articles (across post and cmr_news clones)
     */
    function cmr_get_unique_media_releases( $paged = 1, $year = '', $search = '', $per_page = 6 ) {
        $paged    = max( 1, intval( $paged ) );
        $per_page = max( 1, intval( $per_page ) );
        $terms    = array( 'media-release', 'media-releases', 'press-release', 'press-releases', 'pressreleases', 'press-release-2', 'press-releases-2' );

        $raw_args = array(
            'post_type'              => array( 'cmr_news', 'post' ),
            'posts_per_page'         => -1,
            'post_status'            => 'publish',
            'orderby'                => 'date',
            'order'                  => 'DESC',
            'no_found_rows'          => true,
            'update_post_meta_cache' => false,
            'update_post_term_cache' => false,
            'tax_query'              => array(
                'relation' => 'OR',
                array(
                    'taxonomy' => 'cmr_news_category',
                    'field'    => 'slug',
                    'terms'    => $terms,
                ),
                array(
                    'taxonomy' => 'category',
                    'field'    => 'slug',
                    'terms'    => $terms,
                ),
            ),
        );

        if ( ! empty( $year ) ) {
            $raw_args['date_query'] = array(
                array(
                    'year' => $year,
                ),
            );
        }

        if ( ! empty( $search ) ) {
            $raw_args['s'] = $search;
        }

        $all_query = new WP_Query( $raw_args );

        $unique_ids = array();
        $seen_keys  = array();

        if ( ! empty( $all_query->posts ) ) {
            foreach ( $all_query->posts as $p ) {
                $title_key = sanitize_title( $p->post_title );
                if ( empty( $title_key ) ) {
                    $title_key = sanitize_title( $p->post_name );
                }
                if ( ! isset( $seen_keys[ $title_key ] ) ) {
                    $seen_keys[ $title_key ] = true;
                    $unique_ids[] = $p->ID;
                }
            }
        }

        $total_posts = count( $unique_ids );
        $max_pages   = max( 1, (int) ceil( $total_posts / $per_page ) );

        $offset   = ( $paged - 1 ) * $per_page;
        $page_ids = array_slice( $unique_ids, $offset, $per_page );

        if ( ! empty( $page_ids ) ) {
            $final_query = new WP_Query( array(
                'post_type'      => array( 'cmr_news', 'post' ),
                'post__in'       => $page_ids,
                'orderby'        => 'post__in',
                'posts_per_page' => $per_page,
            ) );
        } else {
            $final_query = new WP_Query( array(
                'post_type' => 'post',
                'post__in'  => array( 0 ),
            ) );
        }

        $final_query->found_posts   = $total_posts;
        $final_query->max_num_pages = $max_pages;

        return $final_query;
    }
}

add_shortcode( 'cmr_media_releases_grid', 'cmr_media_releases_grid_shortcode' );

function cmr_media_releases_grid_shortcode( $atts = array() ) {
    ob_start();

    $atts = shortcode_atts( array(
        'link_featured'     => '#featured',
        'link_latest'       => '#latest',
        'link_market'       => '#cmr-market-updates',
        'link_reports'      => '#reports',
        'link_cmr_news'     => '#cmr-in-news'
    ), $atts, 'cmr_media_releases_grid' );

    $paged = max(1, get_query_var('paged'), get_query_var('page'), isset($_GET['paged']) ? intval($_GET['paged']) : 1);
    $selected_year = isset($_GET['y']) ? sanitize_text_field($_GET['y']) : '';

    $query = cmr_get_unique_media_releases( $paged, $selected_year, '', 6 );

    ?>
    <style>
        .cmr-mrg-wrapper {
            font-family: 'Instrument Sans', sans-serif;
            max-width: 1280px;
            margin: 0 auto;
            padding: 40px 20px;
            color: #111;
            box-sizing: border-box;
        }

        /* Top Navigation Style */
        .cmr-mrg-top-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 0;
            margin-bottom: 40px;
            background: #fff;
            position: relative;
            z-index: 99999;
        }
        .cmr-mrg-nav-title {
            font-size: 14px;
            font-weight: 600;
            color: #111;
        }
        .intel-nav-links {
            display: flex;
            gap: 35px;
            align-items: center;
        }
        .intel-nav-links a {
            color: #111;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        .intel-nav-links a:hover {
            color: #6A35FF;
        }
        .intel-nav-links a.cmr-nav-btn-subscribe:hover {
            background: #111 !important;
            color: #fff !important;
        }

        /* Header Area */
        .cmr-mrg-header {
            margin-bottom: 40px;
        }
        .cmr-mrg-header h1 {
            font-size: 45px;
            font-weight: 600;
            margin: 40px 0 15px 0;
            letter-spacing: -1px;
            color: #111;
        }
        .cmr-mrg-header p {
            font-size: 16px;
            color: #555;
            margin: 0;
            line-height: 1.5;
        }

        /* Filters and Search */
        .cmr-mrg-filters-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            flex-wrap: wrap;
            gap: 20px;
        }
        .cmr-mrg-years {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }
        .cmr-mrg-year-btn {
            background: transparent;
            border: 1px solid #eaeaea;
            border-radius: 40px;
            padding: 8px 20px;
            font-size: 14px;
            color: #111;
            cursor: pointer;
            transition: all 0.3s;
            outline: none;
            font-family: inherit;
        }
        .cmr-mrg-year-btn:hover {
            border-color: #6B3FA0;
            color: #6B3FA0;
        }
        .cmr-mrg-year-btn.active {
            border-color: #6B3FA0;
            color: #6B3FA0;
        }
        .cmr-mrg-more-dropdown {
            position: relative;
            display: inline-block;
        }
        .cmr-mrg-more-btn {
            background: transparent;
            border: 1px solid #eaeaea;
            border-radius: 40px;
            padding: 8px 20px;
            font-size: 14px;
            color: #111;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            font-family: inherit;
            transition: all 0.3s;
        }
        .cmr-mrg-more-btn:hover,
        .cmr-mrg-more-btn.active {
            border-color: #6B3FA0;
            color: #6B3FA0;
        }
        .cmr-mrg-more-content .cmr-mrg-dropdown-item {
            display: block;
            width: 100%;
            text-align: left;
            border: none !important;
            border-radius: 6px !important;
            padding: 8px 14px !important;
            margin-bottom: 2px !important;
            background: transparent !important;
            font-size: 14px;
            color: #222;
            cursor: pointer;
            transition: background 0.2s ease, color 0.2s ease;
        }
        .cmr-mrg-more-content .cmr-mrg-dropdown-item:hover {
            background: #f4efff !important;
            color: #6B3FA0 !important;
        }
        .cmr-mrg-more-content .cmr-mrg-dropdown-item.active {
            background: #6B3FA0 !important;
            color: #fff !important;
            font-weight: 600;
        }
        .cmr-mrg-search {
            position: relative;
            width: 300px;
        }
        .cmr-mrg-search form {
            position: relative;
            display: flex;
            align-items: center;
            background: #fff;
            border: 1px solid #eaeaea;
            border-radius: 40px;
            padding: 4px 6px 4px 18px;
            width: 100%;
            height: 44px;
            box-sizing: border-box;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            margin: 0;
        }
        .cmr-mrg-search form:focus-within {
            border-color: #6A35FF;
            box-shadow: 0 0 0 3px rgba(106, 53, 255, 0.08);
        }
        .cmr-mrg-search input {
            flex: 1;
            width: 100%;
            height: 100%;
            border: none !important;
            outline: none !important;
            background: transparent !important;
            font-size: 14px !important;
            color: #111 !important;
            padding: 0 40px 0 0 !important;
            margin: 0 !important;
            font-family: inherit;
            box-shadow: none !important;
            line-height: normal;
        }
        .cmr-mrg-search input::placeholder {
            color: #888;
        }
        .cmr-mrg-search-btn {
            position: absolute;
            right: 6px;
            top: 50%;
            transform: translateY(-50%);
            background: #6A35FF;
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            padding: 0;
            margin: 0;
            transition: background-color 0.2s ease, transform 0.2s ease;
        }
        .cmr-mrg-search-btn:hover {
            background: #5322d6;
            transform: translateY(-50%) scale(1.05);
        }

        /* Grid */
        .cmr-mrg-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 40px;
            margin-bottom: 60px;
        }
        
        .cmr-mrg-card {
            display: flex;
            flex-direction: column;
        }
        
        .cmr-mrg-card-img-wrap {
            width: 100%;
            height: 240px !important;
            min-height: 240px !important;
            flex-shrink: 0;
            overflow: hidden;
            margin-bottom: 20px;
            background-color: #f4f4f4;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .cmr-mrg-card-img-wrap img {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
            transition: transform 0.5s ease;
        }
        .cmr-mrg-card:hover .cmr-mrg-card-img-wrap img {
            transform: scale(1.05);
        }

        .cmr-mrg-card-meta {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            color: #888;
            margin-bottom: 12px;
            align-items: center;
        }
        .cmr-mrg-card-label {
            color: #888;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .cmr-mrg-card-label::before {
            content: "";
            width: 16px;
            height: 1px;
            background: #888;
            display: inline-block;
        }
        .cmr-mrg-card-label span { margin: 0 4px; }

        .cmr-mrg-card-title {
            font-size: 18px;
            font-weight: 600;
            line-height: 1.4;
            margin: 0 0 12px 0;
            color: #111;
            transition: color 0.3s ease !important;
        }
        .cmr-mrg-card-title a {
            color: inherit;
            text-decoration: none;
            letter-spacing: 1px;
            transition: color 0.3s ease !important;
        }
        .cmr-mrg-card:hover .cmr-mrg-card-title,
        .cmr-mrg-card:hover .cmr-mrg-card-title a,
        .cmr-mrg-card-title a:hover {
            color: #4820B0 !important;
        }

        .cmr-mrg-card-excerpt {
            font-size: 14px;
            color: #555;
            line-height: 1.6;
            margin: 0 0 20px 0;
            flex-grow: 1;
        }

        .cmr-mrg-card-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 14px;
            font-weight: 600;
            color: #111;
            text-decoration: none;
            border-bottom: 1px solid #111;
            padding-bottom: 2px;
            align-self: flex-start;
            transition: color 0.3s, border-color 0.3s;
        }
        .cmr-mrg-card-link:hover {
            color: #6B3FA0;
            border-color: #6B3FA0;
        }

        /* Load More Button */
        .cmr-mrg-load-more-wrap {
            text-align: center;
        }
        .cmr-mrg-load-more {
            background: transparent;
            border: 1px solid #ccc;
            border-radius: 40px;
            width: 260px;
            height: 44px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 600;
            color: #111;
            cursor: pointer;
            transition: transform 0.3s cubic-bezier(0.25, 1, 0.5, 1), border-color 0.3s ease, color 0.3s ease, background 0.3s ease !important;
            font-family: inherit;
            box-sizing: border-box;
            transform: scale(1);
        }
        .cmr-mrg-load-more:hover {
            border-color: #6B3FA0 !important;
            color: #6B3FA0 !important;
            transform: scale(1.05) !important;
        }

        
        .cmr-mrg-loading {
            opacity: 0.5;
            pointer-events: none;
        }

        /* Pagination */
        .cmr-mrg-pagination {
            text-align: center;
            margin-top: 40px;
        }
        .cmr-mrg-pagination .page-numbers {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin: 0 4px;
            background: transparent;
            color: #111;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s;
            border: 1px solid transparent;
        }
        .cmr-mrg-pagination .page-numbers:hover {
            background: #f4f4f4;
        }
        .cmr-mrg-pagination .page-numbers.current {
            background: #6B3FA0;
            color: #fff;
        }

        @media (max-width: 992px) {
            .cmr-mrg-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        @media (max-width: 768px) {
            .cmr-mrg-wrapper {
                padding: 16px 16px 40px 16px !important;
                box-sizing: border-box !important;
                max-width: 100% !important;
            }
            .cmr-mrg-wrapper .intel-nav-bar:not(.intel-nav-fixed-js) {
                padding-left: 0 !important;
                padding-right: 0 !important;
            }
            .cmr-mrg-top-nav-wrap {
                margin-bottom: 24px !important;
            }
            .cmr-mrg-grid {
                grid-template-columns: 1fr !important;
                gap: 25px !important;
            }
            .cmr-mrg-header {
                margin-bottom: 24px !important;
            }
            .cmr-mrg-header h1 {
                font-size: 28px !important;
                margin: 10px 0 10px 0 !important;
                letter-spacing: -0.5px !important;
            }
            .cmr-mrg-header p {
                font-size: 15px !important;
                line-height: 1.5 !important;
            }
            .cmr-mrg-filters-row {
                flex-direction: column !important;
                align-items: stretch !important;
                gap: 14px !important;
                margin-bottom: 28px !important;
                width: 100% !important;
            }
            .cmr-mrg-years {
                display: flex !important;
                flex-wrap: nowrap !important;
                overflow-x: auto !important;
                white-space: nowrap !important;
                width: 100% !important;
                max-width: 100% !important;
                gap: 8px !important;
                padding: 2px 0 8px 0 !important;
                -webkit-overflow-scrolling: touch !important;
                scrollbar-width: none !important;
                -ms-overflow-style: none !important;
            }
            .cmr-mrg-years::-webkit-scrollbar {
                display: none !important;
            }
            .cmr-mrg-year-btn,
            .cmr-mrg-more-dropdown {
                flex-shrink: 0 !important;
                white-space: nowrap !important;
            }
            .cmr-mrg-year-btn {
                padding: 7px 16px !important;
                font-size: 13.5px !important;
                background: #fff;
            }
            .cmr-mrg-more-btn {
                padding: 7px 16px !important;
                font-size: 13.5px !important;
                background: #fff;
            }
            .cmr-mrg-search {
                width: 100% !important;
                margin: 0 !important;
            }
            .cmr-mrg-search form {
                height: 48px !important;
                padding: 4px 6px 4px 18px !important;
                border-radius: 40px !important;
                border: 1px solid #EAEAEA !important;
                box-sizing: border-box !important;
                width: 100% !important;
                background: #fff !important;
            }
            .cmr-mrg-search input {
                font-size: 14px !important;
                height: 100% !important;
                border: none !important;
                outline: none !important;
                box-shadow: none !important;
                background: transparent !important;
                padding: 0 46px 0 0 !important;
            }
            .cmr-mrg-search-btn {
                width: 36px !important;
                height: 36px !important;
                border-radius: 50% !important;
                background: #6A35FF !important;
                flex-shrink: 0 !important;
                right: 6px !important;
            }
            .cmr-mrg-search-btn svg {
                width: 15px !important;
                height: 15px !important;
            }
        }
    </style>

    <div class="cmr-mrg-wrapper" id="newsroom">
        
        <!-- Top Nav -->
        <div class="cmr-mrg-top-nav-wrap" style="margin-bottom: 40px;">
            <div class="cmr-mrg-top-nav intel-nav-bar">
                <div class="cmr-mrg-nav-title intel-nav-title">Media Connect</div>
                <div class="intel-nav-links">
                    <a href="<?php echo esc_attr($atts['link_featured']); ?>">Featured</a>
                    <a href="<?php echo esc_attr($atts['link_latest']); ?>">Latest</a>
                    <a href="<?php echo esc_attr($atts['link_market']); ?>">Market Updates</a>
                    <a href="<?php echo esc_attr($atts['link_reports']); ?>">Reports</a>
                    <a href="<?php echo esc_attr($atts['link_cmr_news']); ?>">CMR in news</a>
                </div>
            </div>
        </div>

        <!-- Header -->
        <div class="cmr-mrg-header">
            <h1>Media Releases</h1>
            <p>Explore expert analysis, research reports, and real-time market signals shaping industries and business strategy.</p>
        </div>

        <!-- Filters & Search -->
        <div class="cmr-mrg-filters-row">
            <div class="cmr-mrg-years" id="cmr-mrg-years">
                <button class="cmr-mrg-year-btn active" data-year="">All</button>
                <button class="cmr-mrg-year-btn" data-year="2026">2026</button>
                <button class="cmr-mrg-year-btn" data-year="2025">2025</button>
                <button class="cmr-mrg-year-btn" data-year="2024">2024</button>
                <button class="cmr-mrg-year-btn" data-year="2023">2023</button>
                <button class="cmr-mrg-year-btn" data-year="2022">2022</button>
                <button class="cmr-mrg-year-btn" data-year="2021">2021</button>
                <div class="cmr-mrg-more-dropdown" style="position: relative;">
                    <button type="button" class="cmr-mrg-more-btn" id="cmr-mrg-more-btn"><span id="cmr-mrg-more-label">More</span> <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg></button>
                    <div class="cmr-mrg-more-content" id="cmr-mrg-more-content" style="display: none; position: absolute; top: 100%; left: 0; background: #fff; border: 1px solid #eaeaea; border-radius: 8px; padding: 6px; z-index: 100; min-width: 110px; box-shadow: 0 4px 14px rgba(0,0,0,0.12); margin-top: 5px;">
                        <button type="button" class="cmr-mrg-year-btn cmr-mrg-dropdown-item" data-year="2020">2020</button>
                        <button type="button" class="cmr-mrg-year-btn cmr-mrg-dropdown-item" data-year="2019">2019</button>
                        <button type="button" class="cmr-mrg-year-btn cmr-mrg-dropdown-item" data-year="2018">2018</button>
                        <button type="button" class="cmr-mrg-year-btn cmr-mrg-dropdown-item" data-year="2017">2017</button>
                        <button type="button" class="cmr-mrg-year-btn cmr-mrg-dropdown-item" data-year="2016">2016</button>
                    </div>
                </div>
            </div>
            <div class="cmr-mrg-search">
                <form id="cmr-mrg-search-form" onsubmit="return false;">
                    <input type="text" id="cmr-mrg-search-input" placeholder="Search by name">
                    <button type="submit" class="cmr-mrg-search-btn" aria-label="Search">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    </button>
                </form>
            </div>
        </div>

        <!-- Grid -->
        <div class="cmr-mrg-grid" id="cmr-mrg-grid">
            <?php
            if ( $query->have_posts() ) {
                while ( $query->have_posts() ) {
                    $query->the_post();
                    $post_id = get_the_ID();
                    $title = get_the_title();
                    $link = get_permalink();
                    $excerpt = function_exists( 'quanto_get_clean_post_excerpt' ) ? quanto_get_clean_post_excerpt( $post_id, 18 ) : wp_trim_words( get_the_excerpt( $post_id ), 18, '...' );
                    $bg_image = get_the_post_thumbnail_url( $post_id, 'medium_large' );
                    
                    $content = get_post_field( 'post_content', $post_id );
                    $word_count = str_word_count( strip_tags( $content ) );
                    $read_time = ceil( $word_count / 200 );
                    if ($read_time < 1) $read_time = 1;
                    $date = get_the_date('d M Y');
                    ?>
                    <div class="cmr-mrg-card">
                        <div class="cmr-mrg-card-img-wrap">
                            <a href="<?php echo esc_url($link); ?>" style="display: block; width: 100%; height: 100%;">
                                <?php if ( $bg_image ) : ?>
                                    <img src="<?php echo esc_url($bg_image); ?>" alt="<?php echo esc_attr($title); ?>" style="width: 100% !important; height: 100% !important; object-fit: cover !important; margin: 0 !important; padding: 0 !important; display: block !important;">
                                <?php else : ?>
                                    <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #ccc;">
                                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                                    </div>
                                <?php endif; ?>
                            </a>
                        </div>
                        <div class="cmr-mrg-card-meta">
                            <div class="cmr-mrg-card-label">Press Release <span>|</span> <?php echo esc_html($date); ?></div>
                            <div class="cmr-mrg-card-time"><?php echo esc_html($read_time); ?> min read</div>
                        </div>
                        <h3 class="cmr-mrg-card-title">
                            <a href="<?php echo esc_url($link); ?>"><?php echo wp_kses_post($title); ?></a>
                        </h3>
                        <p class="cmr-mrg-card-excerpt"><?php echo wp_kses_post($excerpt); ?></p>
                        <a href="<?php echo esc_url($link); ?>" class="cmr-mrg-card-link">
                            Read full Release 
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg>
                        </a>
                    </div>
                    <?php
                }
            } else {
                echo '<p>No media releases found.</p>';
            }
            $total_pages = $query->max_num_pages;
            $has_more = $paged < $total_pages && $paged < 3;
            $show_pagination = $paged >= 3 || $paged >= $total_pages;

            $pagination = '';
            if ( $show_pagination ) {
                $pagination = paginate_links( array(
                    'base'    => @add_query_arg('paged','%#%'),
                    'format'  => '?paged=%#%',
                    'total'   => $total_pages,
                    'current' => $paged,
                    'prev_text' => '<svg width="12" height="18" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7 1L1 7L7 13" stroke="#6A35FF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
                    'next_text' => '<svg width="12" height="18" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 1L7 7L1 13" stroke="#6A35FF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
                ) );
            }
            
            wp_reset_postdata();
            ?>
        </div>

        <!-- Load More -->
        <?php
        $mr_cat_obj = get_category_by_slug('media-releases');
        if ( !$mr_cat_obj ) $mr_cat_obj = get_category_by_slug('media-release');
        if ( !$mr_cat_obj ) $mr_cat_obj = get_category_by_slug('press-releases');
        if ( !$mr_cat_obj ) $mr_cat_obj = get_category_by_slug('press-release');
        if ( !$mr_cat_obj ) $mr_cat_obj = get_term_by('name', 'Media Releases', 'category');
        if ( !$mr_cat_obj ) $mr_cat_obj = get_term_by('name', 'Press Releases', 'category');
        $mr_cat_url = $mr_cat_obj ? get_category_link($mr_cat_obj->term_id) : home_url('/media-releases/');
        ?>
        <div class="cmr-mrg-load-more-wrap">
            <a href="<?php echo esc_url($mr_cat_url); ?>" class="cmr-mrg-load-more" style="display: inline-flex; align-items: center; justify-content: center; text-decoration: none;">View All</a>
        </div>
        
        <!-- Pagination -->
        <div class="cmr-mrg-pagination" id="cmr-mrg-pagination" style="display: <?php echo $pagination ? 'block' : 'none'; ?>;">
            <?php echo $pagination; ?>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        let currentPage = 1;
        let currentYear = '';
        let currentSearch = '';
        
        const grid = document.getElementById('cmr-mrg-grid');
        const loadMoreBtn = document.getElementById('cmr-mrg-load-more-btn');
        const yearBtns = document.querySelectorAll('.cmr-mrg-year-btn');
        const searchForm = document.getElementById('cmr-mrg-search-form');
        const searchInput = document.getElementById('cmr-mrg-search-input');

        function fetchPosts(isLoadMore = false, resetPage = false) {
            if (resetPage) {
                currentPage = 1;
            }
            if (!isLoadMore) {
                grid.innerHTML = '<p>Loading...</p>';
            }
            
            if (loadMoreBtn) loadMoreBtn.classList.add('cmr-mrg-loading');
            
            const data = new FormData();
            data.append('action', 'cmr_load_more_media_releases');
            data.append('page', currentPage);
            data.append('year', currentYear);
            data.append('search', currentSearch);

            fetch('<?php echo admin_url("admin-ajax.php"); ?>', {
                method: 'POST',
                body: data
            })
            .then(res => res.json())
            .then(response => {
                if (response.success) {
                    if (!isLoadMore) {
                        grid.innerHTML = response.data.html || '<p>No media releases found.</p>';
                    } else {
                        grid.insertAdjacentHTML('beforeend', response.data.html);
                    }
                    
                    const paginationWrap = document.getElementById('cmr-mrg-pagination');
                    if (response.data.pagination) {
                        if (paginationWrap) {
                            paginationWrap.innerHTML = response.data.pagination;
                            paginationWrap.style.display = 'block';
                        }
                        if (loadMoreBtn) loadMoreBtn.parentElement.style.display = 'none';
                    } else {
                        if (paginationWrap) paginationWrap.style.display = 'none';
                        if (response.data.has_more) {
                            if (loadMoreBtn) loadMoreBtn.parentElement.style.display = 'block';
                        } else {
                            if (loadMoreBtn) loadMoreBtn.parentElement.style.display = 'none';
                        }
                    }
                }
                if (loadMoreBtn) loadMoreBtn.classList.remove('cmr-mrg-loading');
            })
            .catch(err => {
                console.error(err);
                if (loadMoreBtn) loadMoreBtn.classList.remove('cmr-mrg-loading');
            });
        }

        // Year Filter
        const moreBtn = document.getElementById('cmr-mrg-more-btn');
        const moreLabel = document.getElementById('cmr-mrg-more-label');
        const moreContent = document.getElementById('cmr-mrg-more-content');

        yearBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                yearBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                currentYear = this.getAttribute('data-year') || '';
                
                // If clicked item is from dropdown, update More button label with selected year
                if (this.classList.contains('cmr-mrg-dropdown-item')) {
                    if (moreLabel) {
                        moreLabel.textContent = this.textContent.trim();
                    }
                    if (moreBtn) {
                        moreBtn.classList.add('active');
                    }
                } else {
                    // Reset More button label if clicked outside dropdown
                    if (moreLabel) {
                        moreLabel.textContent = 'More';
                    }
                    if (moreBtn) {
                        moreBtn.classList.remove('active');
                    }
                }

                const viewAllLink = document.querySelector('.cmr-mrg-load-more');
                if (viewAllLink) {
                    let baseUrl = viewAllLink.getAttribute('href').split('?')[0];
                    if (currentYear) {
                        viewAllLink.setAttribute('href', baseUrl + '?y=' + currentYear);
                    } else {
                        viewAllLink.setAttribute('href', baseUrl);
                    }
                }
                
                if (moreContent) {
                    moreContent.style.display = 'none';
                }
                fetchPosts(false, true);
            });
        });

        // More Dropdown
        if (moreBtn && moreContent) {
            moreBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                if (moreContent.style.display === 'none' || moreContent.style.display === '') {
                    moreContent.style.display = 'block';
                } else {
                    moreContent.style.display = 'none';
                }
            });
            document.addEventListener('click', function(e) {
                if (!moreContent.contains(e.target) && e.target !== moreBtn && !moreBtn.contains(e.target)) {
                    moreContent.style.display = 'none';
                }
            });
        }

        // Search
        if (searchForm) {
            searchForm.addEventListener('submit', function(e) {
                e.preventDefault();
                currentSearch = searchInput.value.trim();
                fetchPosts(false, true);
            });
        }
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                if (this.value.trim() === '' && currentSearch !== '') {
                    currentSearch = '';
                    fetchPosts(false, true);
                }
            });
        }

        // Load More
        if (loadMoreBtn) {
            loadMoreBtn.addEventListener('click', function() {
                currentPage++;
                fetchPosts(true);
            });
        }

        // Fetch posts on click
        const navLinks = document.querySelectorAll('.intel-nav-links a:not(.cmr-nav-btn-subscribe)');
        if (navLinks.length > 0) {
            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    // Check if it's an anchor link
                    const href = link.getAttribute('href');
                    if (href && href.startsWith('#') && href !== '#') {
                        // Let cmr-sticky-nav-script handle the smooth scroll
                        return;
                    }
                    
                    if (href === '#' || !href) {
                        e.preventDefault();
                    } else {
                        e.preventDefault();
                        const match = href.match(/paged=(\d+)/);
                        if (match) {
                            currentPage = parseInt(match[1], 10);
                        } else {
                            const pathMatch = href.match(/\/page\/(\d+)/);
                            if (pathMatch) {
                                currentPage = parseInt(pathMatch[1], 10);
                            } else if (href.indexOf('?') === -1 && href.indexOf('page') === -1) {
                                currentPage = 1;
                            }
                        }
                        fetchPosts(true);
                    }
                });
            });
        }
    });
    </script>
    <?php
    return ob_get_clean();
}
