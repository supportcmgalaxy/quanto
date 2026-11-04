<?php
/**
 * CMR Enterprise Connect Grid Component
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'cmr_enterprise_connect_grid', 'cmr_enterprise_connect_grid_shortcode' );

function cmr_enterprise_connect_grid_shortcode( $atts ) {
    $atts = shortcode_atts( array(
        'link_enterprise'   => '#enterprise-connect',
        'link_featured'     => '#featured',
        'link_latest'       => '#latest',
        'link_market'       => '#cmr-market-updates',
        'link_reports'      => '#reports',
        'link_cmr_news'     => '#cmr-in-news'
    ), $atts, 'cmr_enterprise_connect_grid' );

    ob_start();

    $unique_ids = cmr_get_unique_enterprise_post_ids();
    $sliced_ids = array_slice( $unique_ids, 0, 6 );

    $query = new WP_Query(); // Empty default
    if ( ! empty( $sliced_ids ) ) {
        $args = array(
            'post_type'      => array( 'post', 'cmr_news', 'cmr_media' ),
            'post__in'       => $sliced_ids,
            'orderby'        => 'post__in', // Maintain the correct date order from SQL
            'posts_per_page' => 6,
        );
        $query = new WP_Query( $args );
    }
    
    // Override max_num_pages so pagination knows exactly how many pages remain
    $query->max_num_pages = ceil( max( 0, count( $unique_ids ) ) / 6 );
    $query->found_posts = count( $unique_ids );

    ?>
    <style>
        .cmr-enterprisecgd-wrapper {
            font-family: 'Instrument Sans', sans-serif;
            max-width: 1280px;
            margin: 0 auto;
            padding: 40px 20px;
            color: #111;
            box-sizing: border-box;
        }

        /* Top Navigation Style */
        .cmr-enterprisecgd-top-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 0;
            margin-bottom: 40px;
            background: #fff;
            position: relative;
            z-index: 99999;
        }
        .cmr-enterprisecgd-nav-title {
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
        .cmr-enterprisecgd-header {
            margin-bottom: 40px;
        }
        .cmr-enterprisecgd-header h1 {
            font-size: 45px;
            font-weight: 600;
            margin: 40px 0 15px 0;
            letter-spacing: -1px;
            color: #111;
        }
        .cmr-enterprisecgd-header p {
            font-size: 16px;
            color: #555;
            margin: 0;
            line-height: 1.5;
        }

        /* Filters and Search */
        .cmr-enterprisecgd-filters-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            flex-wrap: wrap;
            gap: 20px;
        }
        .cmr-enterprisecgd-years {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }
        .cmr-enterprisecgd-year-btn {
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
        .cmr-enterprisecgd-year-btn:hover {
            border-color: #6B3FA0;
            color: #6B3FA0;
        }
        .cmr-enterprisecgd-year-btn.active {
            border-color: #6B3FA0;
            color: #6B3FA0;
        }
        .cmr-enterprisecgd-more-dropdown {
            position: relative;
            display: inline-block;
        }
        .cmr-enterprisecgd-more-btn {
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
        }
        .cmr-enterprisecgd-search {
            position: relative;
            width: 300px;
        }
        .cmr-enterprisecgd-search form {
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
        .cmr-enterprisecgd-search form:focus-within {
            border-color: #6A35FF;
            box-shadow: 0 0 0 3px rgba(106, 53, 255, 0.08);
        }
        .cmr-enterprisecgd-search input {
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
        .cmr-enterprisecgd-search input::placeholder {
            color: #888;
        }
        .cmr-enterprisecgd-search-btn {
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
        .cmr-enterprisecgd-search-btn:hover {
            background: #5322d6;
            transform: translateY(-50%) scale(1.05);
        }

        /* Grid */
        .cmr-enterprisecgd-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 40px;
            margin-bottom: 60px;
        }
        
        .cmr-enterprisecgd-card {
            display: flex;
            flex-direction: column;
        }
        
        .cmr-enterprisecgd-card-img-wrap {
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
        .cmr-enterprisecgd-card-img-wrap img {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
            transition: transform 0.5s ease;
        }
        .cmr-enterprisecgd-card:hover .cmr-enterprisecgd-card-img-wrap img {
            transform: scale(1.05);
        }

        .cmr-enterprisecgd-card-meta {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            color: #888;
            margin-bottom: 12px;
            align-items: center;
        }
        .cmr-enterprisecgd-card-label {
            color: #888;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .cmr-enterprisecgd-card-label::before {
            content: "";
            width: 16px;
            height: 1px;
            background: #888;
            display: inline-block;
        }
        .cmr-enterprisecgd-card-label span { margin: 0 4px; }

        .cmr-enterprisecgd-card-title {
            font-size: 18px;
            font-weight: 600;
            line-height: 1.4;
            margin: 0 0 12px 0;
            color: #111;
            transition: color 0.3s ease;
        }
        .cmr-enterprisecgd-card-title a {
            color: inherit;
            text-decoration: none;
            letter-spacing: 1px;
            transition: color 0.3s ease;
        }
        .cmr-enterprisecgd-card:hover .cmr-enterprisecgd-card-title,
        .cmr-enterprisecgd-card:hover .cmr-enterprisecgd-card-title a,
        .cmr-enterprisecgd-card-img-wrap:hover ~ .cmr-enterprisecgd-card-title,
        .cmr-enterprisecgd-card-img-wrap:hover ~ .cmr-enterprisecgd-card-title a,
        .cmr-enterprisecgd-card-title a:hover {
            color: #4820B0 !important;
        }

        .cmr-enterprisecgd-card-excerpt {
            font-size: 14px;
            color: #555;
            line-height: 1.6;
            margin: 0 0 20px 0;
            flex-grow: 1;
        }

        .cmr-enterprisecgd-card-link {
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
        .cmr-enterprisecgd-card-link:hover {
            color: #6B3FA0;
            border-color: #6B3FA0;
        }

        /* Load More Button */
        .cmr-enterprisecgd-load-more-wrap {
            text-align: center;
        }
        .cmr-enterprisecgd-load-more {
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
        .cmr-enterprisecgd-load-more:hover {
            border-color: #6B3FA0 !important;
            color: #6B3FA0 !important;
            transform: scale(1.05) !important;
        }

        
        .cmr-enterprisecgd-loading {
            opacity: 0.5;
            pointer-events: none;
        }

        @media (max-width: 992px) {
            .cmr-enterprisecgd-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        @media (max-width: 768px) {
            .cmr-enterprisecgd-wrapper {
                padding: 16px 16px 40px 16px !important;
                box-sizing: border-box !important;
                max-width: 100% !important;
            }
            .cmr-enterprisecgd-wrapper .intel-nav-bar:not(.intel-nav-fixed-js) {
                padding-left: 0 !important;
                padding-right: 0 !important;
            }
            .cmr-enterprisecgd-top-nav-wrap {
                margin-bottom: 24px !important;
            }
            .cmr-enterprisecgd-grid {
                grid-template-columns: 1fr !important;
                gap: 25px !important;
            }
            .cmr-enterprisecgd-header {
                margin-bottom: 24px !important;
            }
            .cmr-enterprisecgd-header h1 {
                font-size: 28px !important;
                margin: 10px 0 10px 0 !important;
                letter-spacing: -0.5px !important;
            }
            .cmr-enterprisecgd-header p {
                font-size: 15px !important;
                line-height: 1.5 !important;
            }
            .cmr-enterprisecgd-filters-row {
                flex-direction: column !important;
                align-items: stretch !important;
                gap: 14px !important;
                margin-bottom: 28px !important;
                width: 100% !important;
            }
            .cmr-enterprisecgd-years {
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
            .cmr-enterprisecgd-years::-webkit-scrollbar {
                display: none !important;
            }
            .cmr-enterprisecgd-year-btn,
            .cmr-enterprisecgd-more-dropdown {
                flex-shrink: 0 !important;
                white-space: nowrap !important;
            }
            .cmr-enterprisecgd-year-btn {
                padding: 7px 16px !important;
                font-size: 13.5px !important;
                background: #fff;
            }
            .cmr-enterprisecgd-more-btn {
                padding: 7px 16px !important;
                font-size: 13.5px !important;
                background: #fff;
            }
            .cmr-enterprisecgd-search {
                width: 100% !important;
                margin: 0 !important;
            }
            .cmr-enterprisecgd-search form {
                height: 48px !important;
                padding: 4px 6px 4px 18px !important;
                border-radius: 40px !important;
                border: 1px solid #EAEAEA !important;
                box-sizing: border-box !important;
                width: 100% !important;
                background: #fff !important;
            }
            .cmr-enterprisecgd-search input {
                font-size: 14px !important;
                height: 100% !important;
                border: none !important;
                outline: none !important;
                box-shadow: none !important;
                background: transparent !important;
                padding: 0 46px 0 0 !important;
            }
            .cmr-enterprisecgd-search-btn {
                width: 36px !important;
                height: 36px !important;
                border-radius: 50% !important;
                background: #6A35FF !important;
                flex-shrink: 0 !important;
                right: 6px !important;
            }
            .cmr-enterprisecgd-search-btn svg {
                width: 15px !important;
                height: 15px !important;
            }
        }
            .intel-numeric-pagination .page-numbers {
            padding: 0;
            width: 40px;
            height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            border-radius: 50%;
            text-decoration: none;
            color: #333;
            font-size: 16px;
            font-weight: 500;
            background: transparent;
        }
        .intel-numeric-pagination .page-numbers.current {
            background: #6A35FF;
            color: #fff;
        }
        .intel-numeric-pagination .page-numbers.prev, 
        .intel-numeric-pagination .page-numbers.next {
            color: #6A35FF;
        }
        .intel-numeric-pagination .page-numbers.dots {
            width: auto;
        }
    </style>

    <div class="cmr-enterprisecgd-wrapper">
        
        <!-- Top Nav -->
        <div class="cmr-enterprisecgd-top-nav-wrap" style="margin-bottom: 40px;">
            <div class="cmr-enterprisecgd-top-nav intel-nav-bar">
                <div class="cmr-enterprisecgd-nav-title intel-nav-title">Enterprise Connect</div>
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
        <div class="cmr-enterprisecgd-header">
            <h1>Enterprise Connect</h1>
            <p>Explore expert analysis, research reports, and real-time market signals shaping industries and business strategy.</p>
        </div>

        <!-- Filters & Search -->
        <div class="cmr-enterprisecgd-filters-row">
            <div class="cmr-enterprisecgd-years" id="cmr-enterprisecgd-years">
                <button class="cmr-enterprisecgd-year-btn active" data-year="">All</button>
                <button class="cmr-enterprisecgd-year-btn" data-year="2026">2026</button>
                <button class="cmr-enterprisecgd-year-btn" data-year="2025">2025</button>
                <button class="cmr-enterprisecgd-year-btn" data-year="2024">2024</button>
                <button class="cmr-enterprisecgd-year-btn" data-year="2023">2023</button>
                <button class="cmr-enterprisecgd-year-btn" data-year="2022">2022</button>
                <button class="cmr-enterprisecgd-year-btn" data-year="2021">2021</button>
                <div class="cmr-enterprisecgd-more-dropdown" style="position: relative;">
                    <button class="cmr-enterprisecgd-more-btn" id="cmr-enterprisecgd-more-btn">More <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg></button>
                    <div class="cmr-enterprisecgd-more-content" id="cmr-enterprisecgd-more-content" style="display: none; position: absolute; top: 100%; left: 0; background: #fff; border: 1px solid #eaeaea; border-radius: 8px; padding: 10px; z-index: 100; min-width: 120px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); margin-top: 5px;">
                        <button class="cmr-enterprisecgd-year-btn" data-year="2020" style="display:block; width:100%; text-align:left; border:none; border-radius:4px; padding:8px 12px; margin-bottom:4px; background:transparent;">2020</button>
                        <button class="cmr-enterprisecgd-year-btn" data-year="2019" style="display:block; width:100%; text-align:left; border:none; border-radius:4px; padding:8px 12px; margin-bottom:4px; background:transparent;">2019</button>
                        <button class="cmr-enterprisecgd-year-btn" data-year="2018" style="display:block; width:100%; text-align:left; border:none; border-radius:4px; padding:8px 12px; margin-bottom:4px; background:transparent;">2018</button>
                        <button class="cmr-enterprisecgd-year-btn" data-year="2017" style="display:block; width:100%; text-align:left; border:none; border-radius:4px; padding:8px 12px; margin-bottom:4px; background:transparent;">2017</button>
                        <button class="cmr-enterprisecgd-year-btn" data-year="2016" style="display:block; width:100%; text-align:left; border:none; border-radius:4px; padding:8px 12px; background:transparent;">2016</button>
                    </div>
                </div>
            </div>
            <div class="cmr-enterprisecgd-search">
                <form id="cmr-enterprisecgd-search-form" onsubmit="return false;">
                    <input type="text" id="cmr-enterprisecgd-search-input" placeholder="Search by name">
                    <button type="submit" class="cmr-enterprisecgd-search-btn" aria-label="Search">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    </button>
                </form>
            </div>
        </div>

        <!-- Grid -->
        <div class="cmr-enterprisecgd-grid" id="cmr-enterprisecgd-grid">
            <?php
            if ( $query->have_posts() ) {
                while ( $query->have_posts() ) {
                    $query->the_post();
                    $post_id = get_the_ID();
                    $title = get_the_title();
                    $link = get_permalink();
                    $excerpt = wp_trim_words( get_the_excerpt(), 18 );
                    if ( empty($excerpt) ) {
                        $excerpt = wp_trim_words( get_post_field('post_content', $post_id), 18 );
                    }
                    $bg_image = get_the_post_thumbnail_url( $post_id, 'medium_large' );
                    
                    $content = get_post_field( 'post_content', $post_id );
                    $word_count = str_word_count( strip_tags( $content ) );
                    $read_time = ceil( $word_count / 200 );
                    if ($read_time < 1) $read_time = 1;
                    $date = get_the_date('d M Y');
                    ?>
                    <div class="cmr-enterprisecgd-card">
                        <div class="cmr-enterprisecgd-card-img-wrap">
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
                        <div class="cmr-enterprisecgd-card-meta">
                            <div class="cmr-enterprisecgd-card-label">Enterprise Connect <span>|</span> <?php echo esc_html($date); ?></div>
                            <div class="cmr-enterprisecgd-card-time"><?php echo esc_html($read_time); ?> min read</div>
                        </div>
                        <h3 class="cmr-enterprisecgd-card-title">
                            <a href="<?php echo esc_url($link); ?>"><?php echo esc_html($title); ?></a>
                        </h3>
                        <p class="cmr-enterprisecgd-card-excerpt"><?php echo esc_html($excerpt); ?></p>
                        <a href="<?php echo esc_url($link); ?>" class="cmr-enterprisecgd-card-link">
                            Read full Release 
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg>
                        </a>
                    </div>
                    <?php
                }
            } else {
                echo '<p>No Enterprise Connect found.</p>';
            }
            $has_more = $query->max_num_pages > 1;
            wp_reset_postdata();
            ?>
        </div>

        <!-- Load More -->
        <?php
        $ec_cat_obj = get_category_by_slug('enterprise-connect');
        if ( !$ec_cat_obj ) $ec_cat_obj = get_category_by_slug('enterprise');
        if ( !$ec_cat_obj ) $ec_cat_obj = get_category_by_slug('enterprise_connect');
        if ( !$ec_cat_obj ) $ec_cat_obj = get_term_by('name', 'Enterprise Connect', 'category');
        if ( !$ec_cat_obj ) $ec_cat_obj = get_term_by('name', 'Enterprise', 'category');
        $ec_cat_url = $ec_cat_obj ? get_category_link($ec_cat_obj->term_id) : home_url('/category/enterprise-connect/');
        ?>
        <div class="cmr-enterprisecgd-load-more-wrap">
            <a href="<?php echo esc_url($ec_cat_url); ?>" class="cmr-enterprisecgd-load-more" style="display: inline-flex; align-items: center; justify-content: center; text-decoration: none;">View All</a>
        </div>
        <!-- Pagination -->
        <div class="cmr-enterprisecgd-pagination-wrap" style="display: none;"></div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        let currentPage = 1;
        let currentYear = '';
        let currentSearch = '';
        
        const grid = document.getElementById('cmr-enterprisecgd-grid');
        const loadMoreBtn = document.getElementById('cmr-enterprisecgd-load-more-btn');
        const yearBtns = document.querySelectorAll('.cmr-enterprisecgd-year-btn');
        const searchForm = document.getElementById('cmr-enterprisecgd-search-form');
        const searchInput = document.getElementById('cmr-enterprisecgd-search-input');

        function fetchPosts(isLoadMore = false, resetPage = false) {
            if (resetPage) {
                currentPage = 1;
            }
            if (!isLoadMore) {
                grid.innerHTML = '<p>Loading...</p>';
            }
            
            if (loadMoreBtn) loadMoreBtn.classList.add('cmr-enterprisecgd-loading');
            
            const data = new FormData();
            data.append('action', 'cmr_load_more_enterprise_connect');
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
                        grid.innerHTML = response.data.html || '<p>No Enterprise Connect found.</p>';
                    } else {
                        grid.insertAdjacentHTML('beforeend', response.data.html);
                    }
                    
                    const paginationWrap = document.querySelector('.cmr-enterprisecgd-pagination-wrap');
                    if (response.data.pagination) {
                        loadMoreBtn.parentElement.style.display = 'none';
                        if (paginationWrap) {
                            paginationWrap.innerHTML = '<div class="intel-numeric-pagination" style="text-align: center; margin-top: 30px; display: flex; justify-content: center; gap: 10px;">' + response.data.pagination + '</div>';
                            paginationWrap.style.display = 'block';
                        }
                    } else if (response.data.has_more) {
                        loadMoreBtn.parentElement.style.display = 'block';
                        if (paginationWrap) paginationWrap.style.display = 'none';
                    } else {
                        loadMoreBtn.parentElement.style.display = 'none';
                        if (paginationWrap) paginationWrap.style.display = 'none';
                    }
                }
                if (loadMoreBtn) loadMoreBtn.classList.remove('cmr-enterprisecgd-loading');
            })
            .catch(err => {
                console.error(err);
                if (loadMoreBtn) loadMoreBtn.classList.remove('cmr-enterprisecgd-loading');
            });
        }

        // Year Filter
        yearBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                yearBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                currentYear = this.getAttribute('data-year');
                
                const viewAllLink = document.querySelector('.cmr-enterprisecgd-load-more');
                if (viewAllLink) {
                    let baseUrl = viewAllLink.getAttribute('href').split('?')[0];
                    if (currentYear) {
                        viewAllLink.setAttribute('href', baseUrl + '?y=' + currentYear);
                    } else {
                        viewAllLink.setAttribute('href', baseUrl);
                    }
                }
                
                const moreContent = document.getElementById('cmr-enterprisecgd-more-content');
                if (moreContent) {
                    moreContent.style.display = 'none';
                }
                
                fetchPosts(false, true);
            });
        });

        // More Dropdown
        const moreBtn = document.getElementById('cmr-enterprisecgd-more-btn');
        const moreContent = document.getElementById('cmr-enterprisecgd-more-content');
        if (moreBtn && moreContent) {
            moreBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                if (moreContent.style.display === 'none') {
                    moreContent.style.display = 'block';
                } else {
                    moreContent.style.display = 'none';
                }
            });
            document.addEventListener('click', function(e) {
                if (!moreContent.contains(e.target) && e.target !== moreBtn) {
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

        // Load More
        if (loadMoreBtn) {
            loadMoreBtn.addEventListener('click', function() {
                currentPage++;
                fetchPosts(true);
            });
        }

        // AJAX Pagination
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
