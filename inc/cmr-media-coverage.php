<?php
/**
 * CMR Media Coverage Page Template Shortcode
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// 1. Register Shortcode
add_shortcode( 'cmr_media_coverage', 'cmr_render_media_coverage_shortcode' );

function cmr_render_media_coverage_shortcode( $atts ) {
    // Enqueue scripts and styles with filemtime versions for proper browser/CDN caching
    $mc_css_path = get_template_directory() . '/assets/css/cmr-media-coverage.css';
    $mc_js_path  = get_template_directory() . '/assets/js/cmr-media-coverage.js';
    $mc_css_ver  = file_exists( $mc_css_path ) ? filemtime( $mc_css_path ) : '1.0.0';
    $mc_js_ver   = file_exists( $mc_js_path ) ? filemtime( $mc_js_path ) : '1.0.0';
    wp_enqueue_style( 'cmr-media-coverage-style', get_template_directory_uri() . '/assets/css/cmr-media-coverage.css', array(), $mc_css_ver );
    wp_enqueue_script( 'cmr-media-coverage-script', get_template_directory_uri() . '/assets/js/cmr-media-coverage.js', array('jquery'), $mc_js_ver, true );
    
    // Pass ajax_url to JS
    wp_localize_script( 'cmr-media-coverage-script', 'cmr_mc_ajax', array(
        'ajax_url' => admin_url( 'admin-ajax.php' )
    ) );

    // Fetch distinct publishers (cached via transient to avoid N+1 WP_Query calls on every page load)
    $publishers = get_transient( 'cmr_mc_valid_publishers_v1' );
    if ( false === $publishers || ! is_array( $publishers ) ) {
        global $wpdb;
        $raw_publishers = $wpdb->get_col("
            SELECT pm.meta_value 
            FROM {$wpdb->postmeta} pm
            INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
            WHERE pm.meta_key = '_cmr_news_publisher_name' 
            AND TRIM(pm.meta_value) != ''
            AND p.post_type = 'cmr_news'
            AND p.post_status = 'publish'
            AND p.ID NOT IN (
                SELECT tr.object_id FROM {$wpdb->term_relationships} tr
                INNER JOIN {$wpdb->term_taxonomy} tt ON tt.term_taxonomy_id = tr.term_taxonomy_id
                INNER JOIN {$wpdb->terms} t ON t.term_id = tt.term_id
                WHERE tt.taxonomy IN ('cmr_news_category', 'category')
                AND (
                    t.slug IN ('media-releases', 'media-release', 'media_releases', 'media_release', 'press-releases', 'press-release', 'pressreleases', 'press-releases-2', 'press-release-2', 'quarterly-results')
                    OR t.name LIKE '%Media Release%'
                    OR t.name LIKE '%Press Release%'
                    OR t.name LIKE '%Quarterly%'
                )
            )
            GROUP BY pm.meta_value
            ORDER BY COUNT(p.ID) DESC, pm.meta_value ASC
        ");

        $publishers = array();
        if ( ! empty( $raw_publishers ) ) {
            foreach ( $raw_publishers as $r_pub ) {
                $r_pub = trim( $r_pub );
                if ( empty( $r_pub ) || in_array( $r_pub, $publishers, true ) ) continue;

                $chk = new WP_Query( array(
                    'post_type'      => 'cmr_news',
                    'post_status'    => 'publish',
                    'posts_per_page' => 1,
                    'fields'         => 'ids',
                    'no_found_rows'  => true,
                    'tax_query'      => array(
                        'relation' => 'AND',
                        array(
                            'taxonomy' => 'cmr_news_category',
                            'field'    => 'slug',
                            'terms'    => array('media-releases', 'media-release', 'media_releases', 'media_release', 'press-releases', 'press-release', 'pressreleases', 'press-releases-2', 'press-release-2', 'quarterly-results'),
                            'operator' => 'NOT IN'
                        ),
                    ),
                    'meta_query'     => array(
                        array(
                            'key'     => '_cmr_news_publisher_name',
                            'value'   => $r_pub,
                            'compare' => '='
                        )
                    )
                ) );

                if ( $chk->have_posts() ) {
                    $publishers[] = $r_pub;
                }
            }
        }
        set_transient( 'cmr_mc_valid_publishers_v1', $publishers, HOUR_IN_SECONDS );
    }

    $initial_data = cmr_get_media_coverage_grid_data( 1, '', '' );

    ob_start();
    ?>
    <style>
    /* Zero Border Radius on all media/news images & cards across the board */
    .cmr-mc-wrapper img,
    .cmr-mc-wrapper .cmr-mc-card,
    .cmr-mc-wrapper .cmr-mc-image-wrap,
    .cmr-mc-wrapper .cmr-mc-featured-image,
    .cmr-mc-wrapper .cmr-mc-card-image,
    .cmr-mc-wrapper .cmr-mc-bg,
    .cmr-media-coverage-wrapper img,
    .cmr-media-coverage-wrapper .cmr-mc-card,
    .cmr-media-coverage-wrapper .cmr-mc-featured-image,
    .cmr-media-coverage-wrapper .cmr-mc-card-image,
    .cmr-media-coverage-wrapper .cmr-mc-bg,
    .cmr-card,
    .cmr-card-image-wrap,
    .cmr-card-bg,
    .cmr-nc-card,
    .cmr-nc-card img {
        border-radius: 0 !important;
    }

    /* Media Coverage Card Title Hover - Purple #4820B0 */
    .cmr-mc-card:hover .cmr-mc-title,
    .cmr-mc-card:hover .cmr-mc-title a,
    .cmr-mc-card .cmr-mc-link-wrapper:hover .cmr-mc-title,
    .cmr-mc-link-wrapper:hover .cmr-mc-title,
    .cmr-mc-card-inner:hover .cmr-mc-title,
    .cmr-mc-featured:hover .cmr-mc-title,
    .cmr-mc-featured-inner:hover .cmr-mc-title,
    .cmr-mc-featured .cmr-mc-link-wrapper:hover .cmr-mc-title,
    .cmr-mc-standard:hover .cmr-mc-title,
    .cmr-mc-standard .cmr-mc-link-wrapper:hover .cmr-mc-title,
    .cmr-mc-wrapper .cmr-mc-card:hover .cmr-mc-title,
    .cmr-mc-wrapper .cmr-mc-featured:hover .cmr-mc-title,
    .cmr-mc-wrapper .cmr-mc-standard:hover .cmr-mc-title,
    .cmr-media-coverage-wrapper .cmr-mc-card:hover .cmr-mc-title,
    .cmr-media-coverage-wrapper .cmr-mc-featured:hover .cmr-mc-title,
    .cmr-media-coverage-wrapper .cmr-mc-standard:hover .cmr-mc-title,
    .cmr-mc-title:hover,
    .cmr-mc-title a:hover {
        color: #4820B0 !important;
    }

    .cmr-mc-title,
    .cmr-mc-title a,
    h2.cmr-mc-title,
    h3.cmr-mc-title {
        transition: color 0.3s ease !important;
    }
    /* Sticky Intel Nav Bar for Media Coverage - Desktop */
    @media (min-width: 769px) {
        .cmr-mc-wrapper .intel-nav-bar,
        .intel-nav-bar.cmr-mc-top-nav,
        .intel-nav-bar.cmr-mc-top-nav.intel-nav-fixed-js {
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            width: 100% !important;
            min-height: 52px !important;
            background: #ffffff !important;
            border-bottom: 1px solid #eeeeee !important;
            margin-bottom: 35px !important;
            padding: 0 !important;
            box-sizing: border-box !important;
        }

        .cmr-mc-wrapper .intel-nav-title,
        .intel-nav-bar.cmr-mc-top-nav .intel-nav-title {
            font-size: 14px !important;
            font-weight: 600 !important;
            color: #111111 !important;
            white-space: nowrap !important;
            margin: 0 !important;
            flex-shrink: 0 !important;
        }

        .cmr-mc-wrapper .intel-nav-links,
        .intel-nav-bar.cmr-mc-top-nav .intel-nav-links {
            display: flex !important;
            gap: 25px !important;
            align-items: center !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .cmr-mc-wrapper .intel-nav-links a,
        .intel-nav-bar.cmr-mc-top-nav .intel-nav-links a {
            text-decoration: none !important;
            color: #333333 !important;
            font-size: 14px !important;
            font-weight: 500 !important;
            transition: color 0.2s ease !important;
            white-space: nowrap !important;
        }

        .cmr-mc-wrapper .intel-nav-links a:hover,
        .cmr-mc-wrapper .intel-nav-links a.active,
        .intel-nav-bar.cmr-mc-top-nav .intel-nav-links a:hover,
        .intel-nav-bar.cmr-mc-top-nav .intel-nav-links a.active {
            color: #5842c3 !important;
        }
    }

    /* Filters Row & Scrollable Chips */
    .cmr-mc-filters-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 35px;
        gap: 20px;
        width: 100%;
        box-sizing: border-box;
    }

    .cmr-mc-pills {
        display: flex;
        gap: 12px;
        align-items: center;
        flex-wrap: wrap;
    }

    .cmr-mc-pill {
        background: #ffffff;
        border: 1px solid #E2E8F0;
        border-radius: 40px;
        padding: 8px 24px;
        font-size: 14px;
        font-weight: 500;
        color: #111111;
        cursor: pointer;
        transition: all 0.2s ease;
        outline: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        line-height: 1.2;
    }

    .cmr-mc-pill:hover {
        border-color: #5842c3;
        color: #5842c3;
    }

    .cmr-mc-pill.active {
        background: #ffffff !important;
        border-color: #5842c3 !important;
        color: #5842c3 !important;
        font-weight: 600 !important;
        box-shadow: 0 0 0 1px #5842c3;
    }

    /* Dropdown */
    .cmr-mc-filter-dropdown {
        position: relative;
        display: inline-block;
    }

    .cmr-mc-dropdown-toggle svg {
        transition: transform 0.2s ease;
    }

    .cmr-mc-filter-dropdown.open .cmr-mc-dropdown-toggle svg {
        transform: rotate(180deg);
    }

    .cmr-mc-dropdown-menu {
        display: none;
        position: absolute;
        top: calc(100% + 8px);
        left: 0;
        background: #ffffff;
        border: 1px solid #E2E8F0;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
        padding: 6px;
        min-width: 170px;
        z-index: 100;
        max-height: 260px;
        overflow-y: auto;
    }

    .cmr-mc-filter-dropdown.open .cmr-mc-dropdown-menu {
        display: block;
    }

    .cmr-mc-dropdown-item {
        display: block;
        width: 100%;
        text-align: left;
        padding: 8px 14px;
        border: none;
        background: transparent;
        font-size: 13.5px;
        font-weight: 500;
        color: #333333;
        border-radius: 6px;
        cursor: pointer;
        transition: background 0.15s ease, color 0.15s ease;
        box-sizing: border-box;
    }

    .cmr-mc-dropdown-item:hover {
        background: #f1effd;
        color: #5842c3;
    }

    .cmr-mc-dropdown-item.active {
        background: #f1effd;
        color: #5842c3;
        font-weight: 600;
    }

    /* Search Bar */
    .cmr-mc-search {
        position: relative;
        width: 320px;
        flex-shrink: 0;
    }

    .cmr-mc-search input {
        width: 100%;
        height: 46px;
        border-radius: 40px;
        border: 1px solid #E2E8F0;
        padding: 10px 48px 10px 20px;
        font-size: 14px;
        color: #111111;
        background: #ffffff;
        box-sizing: border-box;
        outline: none;
        transition: border-color 0.2s ease;
    }

    .cmr-mc-search input:focus {
        border-color: #5842c3;
    }

    .cmr-mc-search-icon {
        position: absolute;
        right: 5px;
        top: 50%;
        transform: translateY(-50%);
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #5842c3;
        display: flex;
        justify-content: center;
        align-items: center;
        pointer-events: none;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .cmr-mc-wrapper {
            padding-left: 16px !important;
            padding-right: 16px !important;
            box-sizing: border-box !important;
        }

        .cmr-mc-wrapper .intel-nav-bar:not(.intel-nav-fixed-js) {
            margin-left: -16px !important;
            margin-right: -16px !important;
            width: calc(100% + 32px) !important;
            padding: 0 16px !important;
            margin-bottom: 25px !important;
        }

        .cmr-mc-header {
            text-align: center !important;
            margin-bottom: 25px !important;
        }

        .cmr-mc-header .cmr-mc-title {
            font-size: 28px !important;
            line-height: 1.25 !important;
            letter-spacing: -0.5px !important;
            margin-bottom: 10px !important;
            text-align: center !important;
        }

        .cmr-mc-subtitle {
            font-size: 14px !important;
            line-height: 1.5 !important;
            color: #475569 !important;
            text-align: center !important;
            margin: 0 auto !important;
        }

        .cmr-mc-filters-row {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 16px !important;
            margin-bottom: 25px !important;
            width: 100% !important;
        }

        /* Horizontal Scrollable Chips on Mobile */
        .cmr-mc-pills {
            display: flex !important;
            flex-wrap: nowrap !important;
            overflow-x: auto !important;
            -webkit-overflow-scrolling: touch !important;
            scrollbar-width: none !important;
            gap: 8px !important;
            width: 100% !important;
            padding-bottom: 4px !important;
            margin: 0 !important;
        }

        .cmr-mc-pills::-webkit-scrollbar {
            display: none !important;
        }

        .cmr-mc-pill {
            flex-shrink: 0 !important;
            white-space: nowrap !important;
            padding: 5px 14px !important;
            font-size: 12.5px !important;
            line-height: 1.2 !important;
            border-radius: 30px !important;
        }

        /* Full Width Search Bar on Mobile */
        .cmr-mc-search {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
        }

        .cmr-mc-search input {
            width: 100% !important;
            height: 48px !important;
            border-radius: 40px !important;
            padding: 10px 50px 10px 22px !important;
        }

        /* Grid & Cards on Mobile */
        .cmr-mc-wrapper .cmr-mc-grid,
        .cmr-media-coverage-wrapper .cmr-mc-grid,
        .cmr-media-coverage-wrapper .cmr-mc-grid-inner {
            display: flex !important;
            flex-direction: column !important;
            gap: 24px !important;
            width: 100% !important;
        }

        /* Featured Card Mobile */
        .cmr-mc-wrapper .cmr-mc-featured,
        .cmr-media-coverage-wrapper .cmr-mc-featured {
            grid-column: span 12 !important;
            width: 100% !important;
            margin-bottom: 0 !important;
        }

        .cmr-mc-wrapper .cmr-mc-featured .cmr-mc-link-wrapper,
        .cmr-media-coverage-wrapper .cmr-mc-featured-inner {
            display: flex !important;
            flex-direction: column !important;
            gap: 16px !important;
            width: 100% !important;
        }

        .cmr-mc-wrapper .cmr-mc-featured .cmr-mc-image-wrap,
        .cmr-media-coverage-wrapper .cmr-mc-featured-image {
            flex: none !important;
            width: 100% !important;
            max-width: 100% !important;
            height: 274px !important;
            min-height: 0 !important;
            border-radius: 0 !important;
            position: relative !important;
            overflow: hidden !important;
        }

        .cmr-mc-wrapper .cmr-mc-featured .cmr-mc-image-wrap .cmr-mc-bg,
        .cmr-media-coverage-wrapper .cmr-mc-featured-image .cmr-mc-bg {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
        }

        .cmr-mc-wrapper .cmr-mc-featured .cmr-mc-content,
        .cmr-media-coverage-wrapper .cmr-mc-featured-content {
            flex: none !important;
            width: 100% !important;
            padding: 0 !important;
            display: flex !important;
            flex-direction: column !important;
        }

        .cmr-mc-wrapper .cmr-mc-featured .cmr-mc-meta,
        .cmr-media-coverage-wrapper .cmr-mc-featured .cmr-mc-meta {
            font-size: 12px !important;
            color: #64748b !important;
            margin-bottom: 8px !important;
            display: flex !important;
            align-items: center !important;
            gap: 6px !important;
        }

        .cmr-mc-wrapper .cmr-mc-featured .cmr-mc-title,
        .cmr-media-coverage-wrapper .cmr-mc-featured .cmr-mc-title,
        .cmr-mc-featured .cmr-mc-title {
            font-size: 18px !important;
            font-weight: 600 !important;
            line-height: 1.35 !important;
            color: #111111 !important;
            margin: 0 0 10px 0 !important;
            text-align: left !important;
        }

        .cmr-mc-wrapper .cmr-mc-featured .cmr-mc-excerpt,
        .cmr-media-coverage-wrapper .cmr-mc-featured .cmr-mc-excerpt {
            font-size: 13px !important;
            line-height: 1.5 !important;
            color: #475569 !important;
            margin: 0 0 14px 0 !important;
        }

        .cmr-mc-wrapper .cmr-mc-featured .cmr-mc-read-coverage,
        .cmr-media-coverage-wrapper .cmr-mc-featured .cmr-mc-view-btn {
            font-size: 13.5px !important;
            font-weight: 600 !important;
            color: #111111 !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 6px !important;
            text-decoration: none !important;
            border-bottom: 1px solid #111111 !important;
            padding-bottom: 2px !important;
            align-self: flex-start !important;
            margin-top: 4px !important;
        }

        /* Standard Cards Mobile */
        .cmr-mc-wrapper .cmr-mc-standard,
        .cmr-media-coverage-wrapper .cmr-mc-card {
            grid-column: span 12 !important;
            width: 100% !important;
            display: flex !important;
            flex-direction: column !important;
        }

        .cmr-mc-wrapper .cmr-mc-standard .cmr-mc-link-wrapper,
        .cmr-media-coverage-wrapper .cmr-mc-card-inner {
            display: flex !important;
            flex-direction: column !important;
            gap: 14px !important;
            width: 100% !important;
        }

        .cmr-mc-wrapper .cmr-mc-standard .cmr-mc-image-wrap,
        .cmr-media-coverage-wrapper .cmr-mc-card-image {
            width: 100% !important;
            height: 274px !important;
            aspect-ratio: auto !important;
            border-radius: 0 !important;
            position: relative !important;
            overflow: hidden !important;
            margin-bottom: 0 !important;
        }

        .cmr-mc-wrapper .cmr-mc-standard .cmr-mc-image-wrap .cmr-mc-bg,
        .cmr-media-coverage-wrapper .cmr-mc-card-image .cmr-mc-bg {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
        }

        .cmr-mc-wrapper .cmr-mc-standard .cmr-mc-content,
        .cmr-media-coverage-wrapper .cmr-mc-card-content {
            width: 100% !important;
            padding: 0 !important;
            display: flex !important;
            flex-direction: column !important;
        }

        .cmr-mc-wrapper .cmr-mc-standard .cmr-mc-meta,
        .cmr-media-coverage-wrapper .cmr-mc-card .cmr-mc-meta-row {
            font-size: 11.5px !important;
            color: #64748b !important;
            margin-bottom: 8px !important;
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
        }

        .cmr-mc-wrapper .cmr-mc-standard .cmr-mc-title,
        .cmr-media-coverage-wrapper .cmr-mc-card .cmr-mc-title,
        .cmr-mc-standard .cmr-mc-title {
            font-size: 15.5px !important;
            font-weight: 600 !important;
            line-height: 1.4 !important;
            color: #111111 !important;
            margin: 0 0 14px 0 !important;
            text-align: left !important;
        }

        .cmr-mc-wrapper .cmr-mc-standard .cmr-mc-read-coverage,
        .cmr-media-coverage-wrapper .cmr-mc-card .cmr-mc-view-btn {
            font-size: 13px !important;
            font-weight: 600 !important;
            color: #111111 !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 6px !important;
            text-decoration: none !important;
            border-bottom: 1px solid #111111 !important;
            padding-bottom: 2px !important;
            align-self: flex-start !important;
            margin-top: auto !important;
        }

        .cmr-mc-card:hover .cmr-mc-title,
        .cmr-mc-card:hover .cmr-mc-title a,
        .cmr-mc-link-wrapper:hover .cmr-mc-title,
        .cmr-mc-featured:hover .cmr-mc-title,
        .cmr-mc-standard:hover .cmr-mc-title {
            color: #4820B0 !important;
        }
    }
    </style>

    <div class="cmr-mc-wrapper" id="cmr-in-news">
        <div class="intel-nav-bar cmr-mc-top-nav" style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
            <div class="intel-nav-title" style="white-space: nowrap;">
                CMR in News
            </div>
            <div class="intel-nav-links" style="display: flex; align-items: center; gap: 25px;">
                <a href="#featured">Featured</a>
                <a href="#latest-updates">Latest Updates</a>
                <a href="#press-release">Press Release</a>
                <a href="#cmr-live">CMR Live</a>
                <a href="#reports">Reports</a>
                <a href="#media-contacts">Media Contacts</a>
            </div>
        </div>

        <div class="cmr-mc-header">
            <h1 class="cmr-mc-title">CMR Media Coverage</h1>
            <p class="cmr-mc-subtitle">Track emerging shifts, growth signals, and market movements in real time.</p>
        </div>

        <div class="cmr-mc-filters-row">
            <div class="cmr-mc-pills">
                <button class="cmr-mc-pill active" data-publisher="">All</button>
                <?php 
                $top_publishers = array_slice( $publishers, 0, 4 );
                $more_publishers = array_slice( $publishers, 4 );

                foreach ( $top_publishers as $pub ) {
                    echo '<button class="cmr-mc-pill" data-publisher="' . esc_attr( $pub ) . '">' . esc_html( $pub ) . '</button>';
                }

                if ( ! empty( $more_publishers ) ) : ?>
                    <div class="cmr-mc-filter-dropdown">
                        <button class="cmr-mc-pill cmr-mc-dropdown-toggle" type="button">
                            <span>More</span>
                            <svg width="10" height="6" viewBox="0 0 10 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                        <div class="cmr-mc-dropdown-menu">
                            <?php foreach ( $more_publishers as $pub ) : ?>
                                <button class="cmr-mc-dropdown-item" data-publisher="<?php echo esc_attr( $pub ); ?>"><?php echo esc_html( $pub ); ?></button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <div class="cmr-mc-search">
                <input type="text" id="cmr-mc-search-input" placeholder="Search by name">
                <div class="cmr-mc-search-icon">
                    <svg width="15" height="15" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="7" cy="7" r="5" stroke="white" stroke-width="2"/>
                        <path d="M11 11L14 14" stroke="white" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="cmr-mc-grid" id="cmr-mc-grid-container">
            <?php echo $initial_data['html']; ?>
        </div>

        <div class="cmr-mc-footer">
            <button id="cmr-mc-load-more" class="cmr-mc-load-more"<?php echo empty( $initial_data['has_more'] ) ? ' style="display:none;"' : ''; ?>>Load More</button>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

add_action( 'save_post_cmr_news', function() {
    delete_transient( 'cmr_mc_valid_publishers_v1' );
} );

/**
 * Shared renderer for Media Coverage grid items (used by SSR initial render and AJAX)
 */
function cmr_get_media_coverage_grid_data( $paged = 1, $publisher = '', $search = '' ) {
    $args = array(
        'post_type'      => 'cmr_news',
        'posts_per_page' => 7, // 1 featured + 6 standard (2 rows) per page
        'paged'          => $paged,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'post_status'    => 'publish',
        'tax_query'      => array(
            'relation' => 'AND',
            array(
                'taxonomy' => 'cmr_news_category',
                'field'    => 'slug',
                'terms'    => array('media-releases', 'media-release', 'media_releases', 'media_release', 'press-releases', 'press-release', 'pressreleases', 'press-releases-2', 'press-release-2'),
                'operator' => 'NOT IN'
            ),
        ),
    );

    if ( ! empty( $publisher ) ) {
        $args['meta_query'] = array(
            array(
                'key'     => '_cmr_news_publisher_name',
                'value'   => $publisher,
                'compare' => '='
            )
        );
    }

    if ( ! empty( $search ) ) {
        $args['s'] = $search;
    }

    $query = new WP_Query( $args );

    ob_start();

    if ( $query->have_posts() ) {
        $count = 0;
        while ( $query->have_posts() ) {
            $query->the_post();
            $post_id = get_the_ID();
            $thumb_id = get_post_thumbnail_id( $post_id );
            $is_featured_card = ( $paged === 1 && $count === 0 );
            $bg_img_html = '';
            if ( $thumb_id ) {
                $bg_img_html = wp_get_attachment_image( $thumb_id, 'medium_large', false, array(
                    'class'    => 'cmr-mc-bg',
                    'loading'  => 'lazy',
                    'decoding' => 'async',
                    'sizes'    => $is_featured_card ? '(max-width: 768px) 100vw, 680px' : '(max-width: 768px) 100vw, 420px',
                    'alt'      => esc_attr( get_the_title() ),
                ) );
            }
            if ( empty( $bg_img_html ) ) {
                $bg_image = get_the_post_thumbnail_url( $post_id, 'medium_large' );
                if ( $bg_image ) {
                    $bg_img_html = '<img src="' . esc_url( $bg_image ) . '" class="cmr-mc-bg" alt="' . esc_attr( get_the_title() ) . '" loading="lazy" decoding="async">';
                }
            }
            $logo_id = get_post_meta( $post_id, '_cmr_news_source_logo_id', true );
            $logo_url = $logo_id ? wp_get_attachment_url( $logo_id ) : '';
            $document_id = get_post_meta( $post_id, '_cmr_news_document_id', true );
            $ext_url = get_post_meta( $post_id, '_cmr_news_external_link', true );
            if ( $document_id ) {
                $link = wp_get_attachment_url( $document_id );
                $target = '_blank';
            } elseif ( $ext_url ) {
                $link = $ext_url;
                $target = '_blank';
            } else {
                $link = get_permalink( $post_id );
                $target = '_self';
            }
            $reading_time = get_post_meta( $post_id, '_cmr_news_reading_time', true );
            $publisher_name = get_post_meta( $post_id, '_cmr_news_publisher_name', true );
            $date = get_the_date( 'M j, Y' );

            if ( $is_featured_card ) {
                ?>
                <div class="cmr-mc-card cmr-mc-featured">
                    <a href="<?php echo esc_url( $link ); ?>" target="<?php echo esc_attr( $target ); ?>" class="cmr-mc-link-wrapper">
                        <div class="cmr-mc-image-wrap">
                            <?php echo $bg_img_html; ?>
                            <span class="cmr-mc-trending-tag">✦ TRENDING</span>
                            <?php if ( $logo_url ) : ?>
                                <img src="<?php echo esc_url( $logo_url ); ?>" class="cmr-mc-logo" alt="Source Logo" loading="lazy" decoding="async">
                            <?php endif; ?>
                        </div>
                        <div class="cmr-mc-content">
                            <div class="cmr-mc-meta">
                                <div class="cmr-mc-meta-left">
                                    <?php if ( $publisher_name ) : ?>
                                        <span class="cmr-mc-publisher"><?php echo esc_html( $publisher_name ); ?></span> <span class="cmr-mc-separator">|</span> 
                                    <?php endif; ?>
                                    <span class="cmr-mc-date">Published <?php echo esc_html( $date ); ?></span>
                                    <?php if ( $reading_time ) : ?>
                                        <span class="cmr-mc-separator">|</span>
                                        <span class="cmr-mc-read-time"><?php echo esc_html( $reading_time ); ?><?php echo is_numeric($reading_time) ? ' mins' : ''; ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <h2 class="cmr-mc-title" style="text-align: left !important;"><?php the_title(); ?></h2>
                            <?php if ( has_excerpt() ) : ?>
                                <p class="cmr-mc-excerpt"><?php echo wp_trim_words( get_the_excerpt(), 25 ); ?></p>
                            <?php endif; ?>
                            <span class="cmr-mc-read-coverage">View Coverage <img src="<?php echo esc_url( home_url( '/wp-content/uploads/2026/04/Symbol-1.svg' ) ); ?>" class="cmr-mc-arrow" alt="" loading="lazy" decoding="async"></span>
                        </div>
                    </a>
                </div>
                <?php
            } else {
                ?>
                <div class="cmr-mc-card cmr-mc-standard">
                    <a href="<?php echo esc_url( $link ); ?>" target="<?php echo esc_attr( $target ); ?>" class="cmr-mc-link-wrapper">
                        <div class="cmr-mc-image-wrap">
                            <?php echo $bg_img_html; ?>
                            <?php if ( $logo_url ) : ?>
                                <img src="<?php echo esc_url( $logo_url ); ?>" class="cmr-mc-logo" alt="Source Logo" loading="lazy" decoding="async">
                            <?php endif; ?>
                        </div>
                        <div class="cmr-mc-content">
                            <div class="cmr-mc-meta">
                                <div class="cmr-mc-meta-left">
                                    <?php if ( $publisher_name ) : ?>
                                        <span class="cmr-mc-publisher"><?php echo esc_html( $publisher_name ); ?></span> <span class="cmr-mc-separator">|</span> 
                                    <?php endif; ?>
                                    <span class="cmr-mc-date">Published <?php echo esc_html( $date ); ?></span>
                                    <?php if ( $reading_time ) : ?>
                                        <span class="cmr-mc-separator">|</span>
                                        <span class="cmr-mc-read-time"><?php echo esc_html( $reading_time ); ?><?php echo is_numeric($reading_time) ? ' mins' : ''; ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <h3 class="cmr-mc-title" style="text-align: left !important;"><?php the_title(); ?></h3>
                            <span class="cmr-mc-read-coverage">View Coverage <img src="<?php echo esc_url( home_url( '/wp-content/uploads/2026/04/Symbol-1.svg' ) ); ?>" class="cmr-mc-arrow" alt="" loading="lazy" decoding="async"></span>
                        </div>
                    </a>
                </div>
                <?php
            }
            $count++;
        }
    } else {
        if ( $paged === 1 ) {
            echo '<p class="cmr-mc-no-results">No articles found matching your criteria.</p>';
        }
    }

    $html = ob_get_clean();
    $has_more = ( $query->max_num_pages > $paged );
    wp_reset_postdata();

    return array(
        'html'     => $html,
        'has_more' => $has_more,
    );
}

// 2. AJAX Handler for Fetching Posts
add_action( 'wp_ajax_cmr_filter_media_coverage', 'cmr_ajax_filter_media_coverage' );
add_action( 'wp_ajax_nopriv_cmr_filter_media_coverage', 'cmr_ajax_filter_media_coverage' );

function cmr_ajax_filter_media_coverage() {
    $paged     = isset( $_POST['page'] ) ? intval( $_POST['page'] ) : 1;
    $publisher = isset( $_POST['publisher'] ) ? sanitize_text_field( $_POST['publisher'] ) : '';
    $search    = isset( $_POST['search'] ) ? sanitize_text_field( $_POST['search'] ) : '';

    $data = cmr_get_media_coverage_grid_data( $paged, $publisher, $search );

    wp_send_json_success( $data );
}
