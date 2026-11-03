<?php
/**
 * @Packge     : Quanto
 * @Version    : 1.0
 * @Author     : Mirrortheme
 * @Author URI : https://mirrortheme.com/
 *
 */

// Block direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Include File
 *
 */

// Constants
require_once get_parent_theme_file_path() . '/inc/quanto-constants.php';

//theme setup
require_once QUANTO_DIR_PATH_INC . 'theme-setup.php';

//essential scripts
require_once QUANTO_DIR_PATH_INC . 'essential-scripts.php';

//template helper
require_once QUANTO_DIR_PATH_INC . 'template-helper.php';

// plugin activation
require_once QUANTO_DIR_PATH_INC . 'Quanto-framework/plugins-activation/quanto-active-plugins.php';

// meta options
require_once QUANTO_DIR_PATH_INC . 'Quanto-framework/quanto-meta/quanto-config.php';

// page breadcrumbs
require_once QUANTO_DIR_PATH_INC . 'quanto-breadcrumbs.php';

// sidebar register
require_once QUANTO_DIR_PATH_INC . 'quanto-widgets-reg.php';

//essential functions
require_once QUANTO_DIR_PATH_INC . 'quanto-functions.php';

// theme dynamic css
require_once QUANTO_DIR_PATH_INC . 'quanto-commoncss.php';

// helper function
require_once QUANTO_DIR_PATH_INC . 'wp-html-helper.php';

// pagination
require_once QUANTO_DIR_PATH_INC . 'wp_bootstrap_pagination.php';

// Universal Form Validation Enforcer
require_once QUANTO_DIR_PATH_INC . 'cmr-form-validation.php';

// Universal Email OTP Verification System
require_once QUANTO_DIR_PATH_INC . 'cmr-email-otp-verification.php';

// quanto options
function quanto_setup_ab() { 
    require_once QUANTO_DIR_PATH_INC . 'Quanto-framework/quanto-options/quanto-options.php';
}
add_action( 'after_setup_theme', 'quanto_setup_ab', 20 );

// hooks
require_once QUANTO_DIR_PATH_HOOKS . 'hooks.php';

// hooks funtion
require_once QUANTO_DIR_PATH_HOOKS . 'hooks-functions.php';

// Enqueue footer CSS early
add_action( 'wp_enqueue_scripts', 'quanto_enqueue_footer_css_early', 10 );

// Force enable WooCommerce product reviews and ratings
add_action( 'init', 'quanto_force_enable_woocommerce_reviews' );
function quanto_force_enable_woocommerce_reviews() {
    if ( class_exists( 'WooCommerce' ) ) {
        if ( get_option( 'woocommerce_enable_reviews' ) !== 'yes' ) {
            update_option( 'woocommerce_enable_reviews', 'yes' );
        }
        if ( get_option( 'woocommerce_enable_review_rating' ) !== 'yes' ) {
            update_option( 'woocommerce_enable_review_rating', 'yes' );
        }
    }
}

// Custom Breadcrumb Shortcode
add_shortcode('cmr_breadcrumb', 'cmr_breadcrumb_shortcode');
function cmr_breadcrumb_shortcode() {
    $current_title = '';
    if ( is_archive() ) {
        $current_title = wp_strip_all_tags( get_the_archive_title() );
    } elseif ( is_search() ) {
        $current_title = 'Search Results';
    } else {
        $current_title = get_the_title();
    }
    
    ob_start();
    ?>
    <div class="breadcumb-menu-wrap" style="margin-bottom: 20px; padding: 0;">
        <div class="breadcumb-menu">
            <ul class="justify-content-center" style="margin:0; padding:0; display:flex; align-items:center; list-style:none; justify-content: center;">
                <li><a href="<?php echo esc_url( home_url('/') ); ?>" title="Home" style="color: #666; font-weight: 500; font-size: 12px; text-decoration:none;">Home</a></li>
                <span class="arrow" style="margin: 0 10px; color: #666; font-size:12px;"><i class="fa-solid fa-angle-right"></i></span>
                <li class="active" title="<?php echo esc_attr( $current_title ); ?>" style="color: #111; font-weight: 500; font-size: 12px;"><?php echo esc_html( $current_title ); ?></li>
            </ul>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

// CMR News & Media Releases Feature
require_once QUANTO_DIR_PATH_INC . 'cmr-news.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-news-automotive.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-media-coverage.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-news-carousel.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-press-releases.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-spotlight.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-media-contacts.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-press-releases.php';
require_once QUANTO_DIR_PATH_INC . 'author-meta.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-latest-insights.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-latest-insights-tech.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-latest-insights-consumer.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-latest-insights-supply.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-latest-insights-semiconductors.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-latest-insights-ai.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-latest-insights-msme.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-latest-insights-enterprise-tech.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-latest-insights-it-telecom.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-industry-intelligence.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-marketing-services.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-consulting-advisory.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-fix-msme-slug.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-explore-sectors.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-explore-sectors-custom.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-stay-updated.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-featured-insight.php';
require_once get_theme_file_path( 'inc/cmr-what-we-think.php' );
require_once get_theme_file_path( 'inc/cmr-slide-of-the-day.php' );
require_once get_theme_file_path( 'inc/cmr-team-scroll.php' );
require_once get_theme_file_path( 'inc/cmr-media-cpt.php' );
require_once get_theme_file_path( 'inc/cmr-gtm.php' );
require_once get_theme_file_path( 'inc/cmr-connect.php' );
require_once get_theme_file_path( 'inc/cmr-form-rate-limiter.php' );
function cmr_get_unique_smb_post_ids() {
    global $wpdb;
    $results = $wpdb->get_results("
        SELECT p.ID, p.post_title 
        FROM {$wpdb->posts} p
        INNER JOIN {$wpdb->term_relationships} tr ON p.ID = tr.object_id
        INNER JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
        INNER JOIN {$wpdb->terms} t ON tt.term_id = t.term_id
        WHERE p.post_type IN ('post', 'cmr_news', 'cmr_media') 
          AND p.post_status = 'publish' 
          AND (t.slug IN ('smb-connect', 'smb-connect-industry-connect', 'smb', 'smb_connect', 'smb-connects') OR t.name LIKE '%SMB Connect%' OR t.name LIKE '%SMB%' OR t.name LIKE '%Industry Connect%')
        ORDER BY p.post_date DESC
        LIMIT 500
    ");

    $unique_ids = array();
    $seen_titles = array();
    if ( $results ) {
        foreach ( $results as $row ) {
            $title = trim( $row->post_title );
            if ( ! isset( $seen_titles[ $title ] ) ) {
                $seen_titles[ $title ] = true;
                $unique_ids[] = $row->ID;
            }
        }
    }

    // Fallback to recent articles if fewer than 12 SMB posts found so grid/pagination always works
    if ( count( $unique_ids ) < 12 ) {
        $fallback = $wpdb->get_results("
            SELECT ID, post_title FROM {$wpdb->posts}
            WHERE post_type IN ('post', 'cmr_news', 'cmr_media') AND post_status = 'publish'
            ORDER BY post_date DESC
            LIMIT 30
        ");
        if ( $fallback ) {
            foreach ( $fallback as $row ) {
                $title = trim( $row->post_title );
                if ( ! isset( $seen_titles[ $title ] ) && ! in_array( $row->ID, $unique_ids ) ) {
                    $seen_titles[ $title ] = true;
                    $unique_ids[] = $row->ID;
                }
            }
        }
    }

    return $unique_ids;
}

function cmr_get_unique_enterprise_post_ids() {
    global $wpdb;
    $results = $wpdb->get_results("
        SELECT p.ID, p.post_title 
        FROM {$wpdb->posts} p
        INNER JOIN {$wpdb->term_relationships} tr ON p.ID = tr.object_id
        INNER JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
        INNER JOIN {$wpdb->terms} t ON tt.term_id = t.term_id
        WHERE p.post_type IN ('post', 'cmr_news', 'cmr_media') 
          AND p.post_status = 'publish' 
          AND (t.slug IN ('enterprise-connect', 'enterprise', 'enterprise_connect') OR t.name LIKE '%Enterprise Connect%' OR t.name LIKE '%Enterprise%')
        ORDER BY p.post_date DESC
        LIMIT 500
    ");

    $unique_ids = array();
    $seen_titles = array();
    if ( $results ) {
        foreach ( $results as $row ) {
            $title = trim( $row->post_title );
            if ( ! isset( $seen_titles[ $title ] ) ) {
                $seen_titles[ $title ] = true;
                $unique_ids[] = $row->ID;
            }
        }
    }

    if ( count( $unique_ids ) < 12 ) {
        $fallback = $wpdb->get_results("
            SELECT ID, post_title FROM {$wpdb->posts}
            WHERE post_type IN ('post', 'cmr_news', 'cmr_media') AND post_status = 'publish'
            ORDER BY post_date DESC
            LIMIT 30
        ");
        if ( $fallback ) {
            foreach ( $fallback as $row ) {
                $title = trim( $row->post_title );
                if ( ! isset( $seen_titles[ $title ] ) && ! in_array( $row->ID, $unique_ids ) ) {
                    $seen_titles[ $title ] = true;
                    $unique_ids[] = $row->ID;
                }
            }
        }
    }

    return $unique_ids;
}

function cmr_get_unique_channel_post_ids() {
    global $wpdb;
    $results = $wpdb->get_results("
        SELECT p.ID, p.post_title 
        FROM {$wpdb->posts} p
        INNER JOIN {$wpdb->term_relationships} tr ON p.ID = tr.object_id
        INNER JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
        INNER JOIN {$wpdb->terms} t ON tt.term_id = t.term_id
        WHERE p.post_type IN ('post', 'cmr_news', 'cmr_media') 
          AND p.post_status = 'publish' 
          AND (t.slug IN ('channel-connect', 'channel', 'channel_connect') OR t.name LIKE '%Channel Connect%' OR t.name LIKE '%Channel%')
        ORDER BY p.post_date DESC
        LIMIT 500
    ");

    $unique_ids = array();
    $seen_titles = array();
    if ( $results ) {
        foreach ( $results as $row ) {
            $title = trim( $row->post_title );
            if ( ! isset( $seen_titles[ $title ] ) ) {
                $seen_titles[ $title ] = true;
                $unique_ids[] = $row->ID;
            }
        }
    }

    if ( count( $unique_ids ) < 12 ) {
        $fallback = $wpdb->get_results("
            SELECT ID, post_title FROM {$wpdb->posts}
            WHERE post_type IN ('post', 'cmr_news', 'cmr_media') AND post_status = 'publish'
            ORDER BY post_date DESC
            LIMIT 30
        ");
        if ( $fallback ) {
            foreach ( $fallback as $row ) {
                $title = trim( $row->post_title );
                if ( ! isset( $seen_titles[ $title ] ) && ! in_array( $row->ID, $unique_ids ) ) {
                    $seen_titles[ $title ] = true;
                    $unique_ids[] = $row->ID;
                }
            }
        }
    }

    return $unique_ids;
}

require_once QUANTO_DIR_PATH_INC . 'cmr-media-releases-general.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-channel-connect-general.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-channel-connect-grid.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-enterprise-connect-general.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-enterprise-connect-grid.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-smb-connect-general.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-smb-connect-grid.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-smb-tabs.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-media-releases-grid.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-featured-intelligence-carousel.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-industry-intel-list.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-industry-intelligence-trends.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-industry-stack.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-market-updates-hero.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-viewpoints-hero.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-market-updates-insights.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-viewpoints-insights.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-insights-ajax.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-live.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-live-section.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-breadcrumbs.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-sticky-nav-script.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-ajax-handlers.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-dark-media-coverage.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-author-sync.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-post-sync.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-product-sync.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-research-reports-hero.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-product-badge.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-featured-reports.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-trending-now.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-custom-report-cta.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-latest-reports.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-custom-cart.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-press-release-sync.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-fix-downloads.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-checkout-ui.php';

require_once QUANTO_DIR_PATH_INC . 'cmr-mega-menu-who-we-are.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-mega-menu-who-we-serve.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-mega-menu-what-we-do.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-mega-menu-what-we-think.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-mega-menu-newsroom.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-mega-menu-connect.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-mobile-mega-menu.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-foundation-scroll.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-location-accordion.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-job-application-form.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-job-application-backend.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-smtp-config.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-team-grid.php';

require_once QUANTO_DIR_PATH_INC . 'cmr-quarterly-results.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-investor-banner.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-performance-results.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-hero-banner.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-intro-text.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-intro-supply-chain.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-intro-connected-tech.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-intro-semiconductors.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-intro-ai.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-intro-msme.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-intro-enterprise-tech.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-counter-fix.php';
require_once QUANTO_DIR_PATH_INC . 'cmr-browser-title-fix.php';
// Save rating meta for cmr_news comments
add_action('comment_post', 'cmr_save_comment_rating', 10, 2);
function cmr_save_comment_rating( $comment_id, $comment_approved ) {
    if ( isset( $_POST['rating'] ) && isset( $_POST['comment_post_ID'] ) ) {
        $post_id = intval( $_POST['comment_post_ID'] );
        if ( get_post_type( $post_id ) === 'cmr_news' ) {
            $rating = intval( $_POST['rating'] );
            if ( $rating >= 1 && $rating <= 5 ) {
                add_comment_meta( $comment_id, 'rating', $rating, true );
            }
        }
    }
}



require_once QUANTO_DIR_PATH_INC . 'cmr-intro-tech.php';

// CMR Market Updates Shortcode
add_shortcode('cmr_market_updates', 'cmr_market_updates_shortcode');
function cmr_market_updates_shortcode($atts) {
    $atts = shortcode_atts( array(
        'category' => ''
    ), $atts );
    ob_start(); ?>
    <style>
        .cmr-market-updates-section {
            display: flex;
            flex-wrap: wrap;
            gap: 60px;
            font-family: 'Instrument Sans', sans-serif !important;
            max-width: 1280px;
            margin: 0 auto;
            padding: 40px 16px;
            box-sizing: border-box;
            color: #111;
        }

        .cmr-mu-left {
            flex: 1;
            min-width: 300px;
        }

        .cmr-mu-title {
            font-size: 42px;
            font-weight: 600;
            margin-bottom: 20px;
            margin-top: 0;
            line-height: 1.1;
            letter-spacing: -1.5px;
            color: #111;
        }

        .cmr-mu-desc {
            font-size: 18px;
            color: #555;
            margin-bottom: 40px;
            line-height: 1.5;
        }

        .cmr-mu-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 12px 24px;
            border: 1px solid #111;
            border-radius: 30px;
            color: #111;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .cmr-mu-btn:hover {
            background: #111;
            color: #fff;
        }

        .cmr-mu-right {
            flex: 2;
            min-width: 300px;
            display: flex;
            flex-direction: column;
        }

        .cmr-mu-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 30px 0;
            border-bottom: 1px solid #eaeaea;
            text-decoration: none !important;
            color: inherit;
            transition: background 0.3s ease;
        }
        
        .cmr-mu-item:first-child {
            border-top: 1px solid #eaeaea;
            padding-top: 20px;
        }

        .cmr-mu-item-content {
            flex: 1;
            padding-right: 20px;
        }

        .cmr-mu-meta {
            display: flex;
            align-items: center;
            gap: 15px;
            font-size: 12px;
            margin-bottom: 15px;
        }

        .cmr-mu-date {
            color: #666;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .cmr-mu-date::before {
            content: "";
            display: inline-block;
            width: 20px;
            height: 1px;
            background: #ccc;
        }

        .cmr-mu-category {
            font-weight: 500;
            color: #00A8B5;
        }
        .cmr-mu-category.policy { color: #00A8B5; }
        .cmr-mu-category.investment { color: #00A8B5; }
        .cmr-mu-category.supply { color: #00A8B5; }

        .cmr-mu-item-title {
            font-size: 18px;
            font-weight: 600;
            letter-spacing: -0.5px;
            margin: 0;
            line-height: 1.4;
            color: #111;
            transition: color 0.3s ease;
        }

        .cmr-mu-arrow svg {
            transition: transform 0.3s ease, color 0.3s ease, stroke 0.3s ease;
            color: #111;
        }

        .cmr-mu-item:hover .cmr-mu-item-title {
            color: #4625A9 !important;
        }

        .cmr-mu-item:hover .cmr-mu-arrow svg {
            transform: translate(3px, -3px);
            color: #4625A9 !important;
            stroke: #4625A9 !important;
        }

        @media (max-width: 992px) {
            .cmr-market-updates-section {
                flex-direction: column;
                gap: 40px;
                padding: 40px 16px !important;
            }
            .cmr-mu-title {
                font-size: 28px !important;
                line-height: 1.2 !important;
                letter-spacing: -0.5px !important;
                margin-bottom: 16px !important;
            }
        }
    </style>

    <div id="cmr-market-updates" class="cmr-market-updates-section">
        <div class="cmr-mu-left">
            <h2 class="cmr-mu-title">Market Updates</h2>
            <p class="cmr-mu-desc">Need real-time market updates for your business?</p>
            <div class="elementor-element elementor-element-c4dcb9f download-btn animejs-disable elementor-widget elementor-widget-button" data-id="c4dcb9f" data-element_type="widget" data-e-type="widget" data-settings="{&quot;mas-animation&quot;:&quot;none&quot;}" data-widget_type="button.default" style="align-self: flex-start; width: 53%;">
                <a class="elementor-button elementor-button-link elementor-size-sm insights-cta-button secondary" href="<?php echo esc_url(home_url('/market-updates/')); ?>" style="justify-content: center; width: 100%; background-color: transparent !important; border: 1px solid #111 !important; display: flex; align-items: center; border-radius: 40px; padding: 12px 24px; text-decoration: none;">
                    <span class="elementor-button-content-wrapper" style="width: 100%; display: flex; align-items: center; justify-content: center;">
                        <span class="elementor-button-text" style="margin-right: 6px; font-size: 14px; font-weight: 600 !important; color: #111 !important; line-height: 1; white-space: nowrap;">View all</span>
                        <span class="elementor-button-icon" style="display: flex; align-items: center;">
                            <img src="https://cmrindia.com/wp-content/uploads/2026/04/Symbol-1.svg" alt="Icon" width="16" height="14" style="object-fit: contain;">
                        </span>
                    </span>
                </a>
            </div>
        </div>
        
        <div class="cmr-mu-right">
            <?php
            $mu_args = array(
                'post_type' => 'post',
                'posts_per_page' => 4,
                'category_name' => 'market-updates', // Default category
            );
            
            // Allow category filtering if the user adds category="slug" to the shortcode
            if ( isset($atts['category']) && !empty($atts['category']) ) {
                $mu_args['category_name'] = $atts['category'];
            }
            
            $mu_posts = get_posts($mu_args);
            
            if (!empty($mu_posts)) :
                foreach ($mu_posts as $post_obj) :
                    $date = get_the_time('M d | h:i A', $post_obj);
                    $categories = get_the_category($post_obj->ID);
                    $cat_name = '';
                    $cat_class = 'policy'; // Consistent default color class
                    if ( ! empty( $categories ) ) {
                        $cat_name = esc_html( $categories[0]->name );
                        // We removed random color classes so the same category always looks consistent.
                    }
            ?>
            <a href="<?php echo esc_url(get_permalink($post_obj->ID)); ?>" class="cmr-mu-item">
                <div class="cmr-mu-item-content">
                    <div class="cmr-mu-meta">
                        <span class="cmr-mu-date"><?php echo $date; ?></span>
                        <?php if ( $cat_name ) : ?>
                            <span class="cmr-mu-category <?php echo $cat_class; ?>"><?php echo $cat_name; ?></span>
                        <?php endif; ?>
                    </div>
                    <h3 class="cmr-mu-item-title"><?php echo get_the_title($post_obj); ?></h3>
                </div>
                <div class="cmr-mu-arrow">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg>
                </div>
            </a>
            <?php 
                endforeach; 
            else : 
                echo '<p>No updates found.</p>';
            endif; 
            ?>
        </div>
    </div>
    <?php return ob_get_clean();
}

// Apply Instrument Sans globally
add_action('wp_head', 'cmr_global_font_style', 999);
function cmr_global_font_style() {
    echo '<style>
        body, p, h1, h2, h3, h4, h5, h6, a, button, input, select, textarea, .elementor-button, .elementor-button-text {
            font-family: "Instrument Sans", sans-serif !important;
        }
    </style>';
}

// Ensure Ordered (Numbered) and Unordered (Bullet) lists display properly in Post / Article content
add_action('wp_head', 'cmr_post_content_lists_style', 999);
function cmr_post_content_lists_style() {
    ?>
    <style id="cmr-post-lists-style">
        .blog-body ol,
        .single-post .blog-body ol,
        .entry-content ol,
        .post-content ol,
        .blog-content ol,
        .page--content ol,
        .cmr-media-description ol,
        .cmr-media-content ol,
        .elementor-widget-text-editor ol,
        .wp-block-post-content ol,
        ol.wp-block-list {
            list-style-type: decimal !important;
            list-style-position: outside !important;
            padding-left: 28px !important;
            margin-left: 0 !important;
            margin-top: 15px !important;
            margin-bottom: 20px !important;
        }
        .blog-body ol li,
        .single-post .blog-body ol li,
        .entry-content ol li,
        .post-content ol li,
        .blog-content ol li,
        .page--content ol li,
        .cmr-media-description ol li,
        .cmr-media-content ol li,
        .elementor-widget-text-editor ol li,
        .wp-block-post-content ol li,
        ol.wp-block-list li {
            list-style-type: decimal !important;
            list-style: decimal !important;
            display: list-item !important;
            margin-bottom: 10px !important;
            line-height: 1.7 !important;
        }
        .blog-body ul:not(.custom-ul),
        .single-post .blog-body ul:not(.custom-ul),
        .entry-content ul:not(.custom-ul),
        .post-content ul:not(.custom-ul),
        .blog-content ul:not(.custom-ul),
        .page--content ul:not(.custom-ul),
        .cmr-media-description ul:not(.custom-ul),
        .cmr-media-content ul:not(.custom-ul),
        .elementor-widget-text-editor ul:not(.custom-ul),
        .wp-block-post-content ul:not(.custom-ul),
        ul.wp-block-list {
            list-style-type: disc !important;
            list-style-position: outside !important;
            padding-left: 28px !important;
            margin-left: 0 !important;
            margin-top: 15px !important;
            margin-bottom: 20px !important;
        }
        .blog-body ul:not(.custom-ul) li,
        .single-post .blog-body ul:not(.custom-ul) li,
        .entry-content ul:not(.custom-ul) li,
        .post-content ul:not(.custom-ul) li,
        .blog-content ul:not(.custom-ul) li,
        .page--content ul:not(.custom-ul) li,
        .cmr-media-description ul:not(.custom-ul) li,
        .cmr-media-content ul:not(.custom-ul) li,
        .elementor-widget-text-editor ul:not(.custom-ul) li,
        .wp-block-post-content ul:not(.custom-ul) li,
        ul.wp-block-list li {
            list-style-type: disc !important;
            list-style: disc !important;
            display: list-item !important;
            margin-bottom: 10px !important;
            line-height: 1.7 !important;
        }
        /* Nested lists */
        .blog-body ol ul,
        .blog-body ul ul,
        .single-post .blog-body ol ul,
        .single-post .blog-body ul ul,
        .entry-content ol ul,
        .entry-content ul ul,
        .post-content ol ul,
        .post-content ul ul,
        .blog-content ol ul,
        .blog-content ul ul,
        .page--content ol ul,
        .page--content ul ul,
        .cmr-media-description ol ul,
        .cmr-media-description ul ul,
        .cmr-media-content ol ul,
        .cmr-media-content ul ul,
        .elementor-widget-text-editor ol ul,
        ol.wp-block-list ul,
        ul.wp-block-list ul {
            list-style-type: disc !important;
            list-style-position: outside !important;
            padding-left: 24px !important;
            margin-top: 8px !important;
            margin-bottom: 8px !important;
        }
        .blog-body ol ul li,
        .blog-body ul ul li,
        .entry-content ol ul li,
        .entry-content ul ul li,
        .cmr-media-description ol ul li,
        .cmr-media-content ol ul li,
        .elementor-widget-text-editor ol ul li,
        ol.wp-block-list ul li,
        ul.wp-block-list ul li {
            list-style-type: disc !important;
            list-style: disc !important;
            display: list-item !important;
            margin-bottom: 8px !important;
        }
        /* Nested ordered lists */
        .blog-body ol ol,
        .blog-body ul ol,
        .entry-content ol ol,
        .entry-content ul ol,
        .cmr-media-description ol ol,
        .cmr-media-content ol ol,
        .elementor-widget-text-editor ol ol,
        ol.wp-block-list ol,
        ul.wp-block-list ol {
            list-style-type: lower-alpha !important;
            list-style-position: outside !important;
            padding-left: 24px !important;
            margin-top: 8px !important;
            margin-bottom: 8px !important;
        }
        .blog-body ol ol li,
        .blog-body ul ol li,
        .entry-content ol ol li,
        .entry-content ul ol li,
        .cmr-media-description ol ol li,
        .cmr-media-content ol ol li,
        .elementor-widget-text-editor ol ol li,
        ol.wp-block-list ol li,
        ul.wp-block-list ol li {
            list-style-type: lower-alpha !important;
            list-style: lower-alpha !important;
            display: list-item !important;
            margin-bottom: 8px !important;
        }
    </style>
    <?php
}

// Shortcode to display the Single Media CTA Banner section by rendering the global CTA banner
add_shortcode('cmr_single_media_cta', function() {
    return do_shortcode('[cmr_global_cta_banner]');
});

// Temporary endpoint to migrate Press Releases to CMR News
add_action( 'rest_api_init', function () {
    register_rest_route( 'cmr/v1', '/migrate-pr', array(
        'methods'             => 'GET',
        'callback'            => 'cmr_migrate_press_releases_callback',
        'permission_callback' => '__return_true',
    ) );
} );

function cmr_migrate_press_releases_callback() {
    $args = array(
        'post_type' => 'post',
        'category_name' => 'pressreleases', // The correct slug for Press Releases
        'posts_per_page' => -1,
    );
    $query = new WP_Query($args);
    
    $migrated = 0;
    $log = array();
    
    if ($query->have_posts()) {
        // Ensure Media Release category exists in cmr_news_category
        $term = term_exists('Media Release', 'cmr_news_category');
        if (!$term) {
            $term = wp_insert_term('Media Release', 'cmr_news_category');
        }
        $term_id = is_array($term) ? $term['term_id'] : $term;
        
        while ($query->have_posts()) {
            $query->the_post();
            $post_id = get_the_ID();
            $title = get_the_title();
            
            // Check if it's already migrated
            $existing = get_posts(array(
                'post_type' => 'cmr_news',
                'title' => $title,
                'posts_per_page' => 1,
            ));
            
            if (!empty($existing)) {
                $log[] = "Skipped (Already migrated): " . $title;
                continue;
            }
            
            // Duplicate the post
            $new_post = array(
                'post_title' => $title,
                'post_content' => get_post_field('post_content', $post_id),
                'post_excerpt' => get_post_field('post_excerpt', $post_id),
                'post_status' => 'publish',
                'post_type' => 'cmr_news',
                'post_date' => get_post_field('post_date', $post_id),
                'post_author' => get_post_field('post_author', $post_id),
            );
            
            $new_post_id = wp_insert_post($new_post);
            
            if (!is_wp_error($new_post_id)) {
                // Assign taxonomy term
                wp_set_object_terms($new_post_id, (int)$term_id, 'cmr_news_category');
                
                // Copy featured image
                $thumb_id = get_post_thumbnail_id($post_id);
                if ($thumb_id) {
                    set_post_thumbnail($new_post_id, $thumb_id);
                }
                
                $migrated++;
                $log[] = "Migrated successfully: " . $title;
            } else {
                $log[] = "Error migrating: " . $title . " - " . $new_post_id->get_error_message();
            }
        }
                }
    
    return new WP_REST_Response(array(
        'success' => true,
        'migrated_count' => $migrated,
        'log' => $log
    ), 200);
}

// Global Custom CSS for Menus (Main Header: Normal vs Sticky)
add_action('wp_head', function() {
    ?>
    <style id="cmr-header-load-fix">
        /* 0. Prevent White Header Flash on Homepage Load (Transparent Overlay before scroll) */
        body.home,
        body.home-page,
        body.page-id-14 {
            background-color: #0b132b !important;
        }

        body.home header.header,
        body.home #quanto-header-desktop,
        body.home .main-header-wrapper,
        body.home-page header.header,
        body.home-page #quanto-header-desktop,
        body.home-page .main-header-wrapper,
        body.page-id-14 header.header,
        body.page-id-14 #quanto-header-desktop,
        body.page-id-14 .main-header-wrapper {
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            width: 100% !important;
            z-index: 9999 !important;
            background: transparent !important;
            background-color: transparent !important;
            box-shadow: none !important;
        }

        body.home.admin-bar header.header,
        body.home.admin-bar #quanto-header-desktop,
        body.home.admin-bar .main-header-wrapper,
        body.home-page.admin-bar header.header,
        body.home-page.admin-bar #quanto-header-desktop,
        body.home-page.admin-bar .main-header-wrapper,
        body.page-id-14.admin-bar header.header,
        body.page-id-14.admin-bar #quanto-header-desktop,
        body.page-id-14.admin-bar .main-header-wrapper {
            top: 32px !important;
        }

        @media (max-width: 782px) {
            body.home.admin-bar header.header,
            body.home.admin-bar #quanto-header-desktop,
            body.home.admin-bar .main-header-wrapper,
            body.home-page.admin-bar header.header,
            body.home-page.admin-bar #quanto-header-desktop,
            body.home-page.admin-bar .main-header-wrapper,
            body.page-id-14.admin-bar header.header,
            body.page-id-14.admin-bar #quanto-header-desktop,
            body.page-id-14.admin-bar .main-header-wrapper {
                top: 46px !important;
            }
        }

        /* Ensure inner header container is strictly transparent before scroll (only turns white on .mas-sticky-header) */
        body.home .elementor-element-88e448f:not(.mas-sticky-header),
        body.home .elementor-element-88e448f:not(.mas-sticky-header) > .e-con-inner,
        body.home-page .elementor-element-88e448f:not(.mas-sticky-header),
        body.home-page .elementor-element-88e448f:not(.mas-sticky-header) > .e-con-inner,
        body.page-id-14 .elementor-element-88e448f:not(.mas-sticky-header),
        body.page-id-14 .elementor-element-88e448f:not(.mas-sticky-header) > .e-con-inner,
        body.home header .elementor-element-88e448f:not(.mas-sticky-header),
        body.home header .elementor-element-88e448f:not(.mas-sticky-header) > .e-con-inner,
        body.home #quanto-header-desktop .elementor-element-88e448f:not(.mas-sticky-header),
        body.home #quanto-header-desktop .elementor-element-88e448f:not(.mas-sticky-header) > .e-con-inner,
        body.home .main-header-wrapper .elementor-element-88e448f:not(.mas-sticky-header),
        body.home .main-header-wrapper .elementor-element-88e448f:not(.mas-sticky-header) > .e-con-inner {
            background: transparent !important;
            background-color: transparent !important;
            box-shadow: none !important;
        }

        /* 1. Normal Header (Blue/Transparent): light white (#d1d1d1) on hover */
        .main-header-wrapper .elementor-nav-menu--main .elementor-item:hover,
        .main-header-wrapper .elementor-nav-menu--main .elementor-item.elementor-item-active,
        .main-header-wrapper .elementor-nav-menu--main .elementor-item:focus,
        .main-header-wrapper .menu-item > a:hover,
        #quanto-header-desktop .elementor-nav-menu--main .elementor-item:hover,
        #quanto-header-desktop .elementor-nav-menu--main .elementor-item.elementor-item-active,
        #quanto-header-desktop .elementor-nav-menu--main .elementor-item:focus,
        #quanto-header-desktop .menu-item > a:hover,
        .main-header-wrapper .cmr-has-mega-menu:hover > a,
        .main-header-wrapper .cmr-has-mega-menu-do:hover > a,
        .main-header-wrapper .cmr-has-mega-menu-serve:hover > a,
        .main-header-wrapper .cmr-has-mega-menu-think:hover > a,
        .main-header-wrapper .cmr-has-mega-menu-newsroom:hover > a,
        .main-header-wrapper .cmr-has-mega-menu-connect:hover > a,
        #quanto-header-desktop .cmr-has-mega-menu:hover > a,
        #quanto-header-desktop .cmr-has-mega-menu-do:hover > a,
        #quanto-header-desktop .cmr-has-mega-menu-serve:hover > a,
        #quanto-header-desktop .cmr-has-mega-menu-think:hover > a,
        #quanto-header-desktop .cmr-has-mega-menu-newsroom:hover > a,
        #quanto-header-desktop .cmr-has-mega-menu-connect:hover > a {
            color: #d1d1d1 !important;
        }

        /* 2. Sticky Header (.mas-sticky-header - White background): purple (#6241ca) on hover */
        .mas-sticky-header .elementor-nav-menu--main .elementor-item:hover,
        .mas-sticky-header .elementor-nav-menu--main .elementor-item.elementor-item-active,
        .mas-sticky-header .elementor-nav-menu--main .elementor-item:focus,
        .mas-sticky-header .menu-item > a:hover,
        .mas-sticky-header.main-header-wrapper .elementor-nav-menu--main .elementor-item:hover,
        .mas-sticky-header.main-header-wrapper .elementor-nav-menu--main .elementor-item.elementor-item-active,
        .mas-sticky-header.main-header-wrapper .elementor-nav-menu--main .elementor-item:focus,
        .mas-sticky-header.main-header-wrapper .menu-item > a:hover,
        .main-header-wrapper .mas-sticky-header .elementor-nav-menu--main .elementor-item:hover,
        .main-header-wrapper .mas-sticky-header .elementor-nav-menu--main .elementor-item.elementor-item-active,
        .main-header-wrapper .mas-sticky-header .elementor-nav-menu--main .elementor-item:focus,
        .main-header-wrapper .mas-sticky-header .menu-item > a:hover,
        #quanto-header-desktop .mas-sticky-header .elementor-nav-menu--main .elementor-item:hover,
        #quanto-header-desktop .mas-sticky-header .elementor-nav-menu--main .elementor-item.elementor-item-active,
        #quanto-header-desktop .mas-sticky-header .elementor-nav-menu--main .elementor-item:focus,
        #quanto-header-desktop .mas-sticky-header .menu-item > a:hover,
        .mas-sticky-header .cmr-has-mega-menu:hover > a,
        .mas-sticky-header .cmr-has-mega-menu-do:hover > a,
        .mas-sticky-header .cmr-has-mega-menu-serve:hover > a,
        .mas-sticky-header .cmr-has-mega-menu-think:hover > a,
        .mas-sticky-header .cmr-has-mega-menu-newsroom:hover > a,
        .mas-sticky-header .cmr-has-mega-menu-connect:hover > a,
        .main-header-wrapper .mas-sticky-header .cmr-has-mega-menu:hover > a,
        .main-header-wrapper .mas-sticky-header .cmr-has-mega-menu-do:hover > a,
        .main-header-wrapper .mas-sticky-header .cmr-has-mega-menu-serve:hover > a,
        .main-header-wrapper .mas-sticky-header .cmr-has-mega-menu-think:hover > a,
        .main-header-wrapper .mas-sticky-header .cmr-has-mega-menu-newsroom:hover > a,
        .main-header-wrapper .mas-sticky-header .cmr-has-mega-menu-connect:hover > a,
        #quanto-header-desktop .mas-sticky-header .cmr-has-mega-menu:hover > a,
        #quanto-header-desktop .mas-sticky-header .cmr-has-mega-menu-do:hover > a,
        #quanto-header-desktop .mas-sticky-header .cmr-has-mega-menu-serve:hover > a,
        #quanto-header-desktop .mas-sticky-header .cmr-has-mega-menu-think:hover > a,
        #quanto-header-desktop .mas-sticky-header .cmr-has-mega-menu-newsroom:hover > a,
        #quanto-header-desktop .mas-sticky-header .cmr-has-mega-menu-connect:hover > a {
            color: #6241ca !important;
        }
    </style>
    <?php
});



// Fix 60 MIN / 38 MIN duration tag sizing, horizontal straight-line alignment, and oversized section headings
add_action('wp_head', function() {
    ?>
    <style id="cmr-typography-fixes">
        /* 1. Fix WEBINAR . 60 MIN / PODCAST . 38 MIN meta tag font-size and alignment */
        .cmr-duration-meta-tag,
        .cmr-duration-meta-tag * {
            font-size: 13px !important;
            line-height: 1.4 !important;
            font-weight: 700 !important;
            vertical-align: middle !important;
            letter-spacing: 0.5px !important;
        }
        .cmr-duration-meta-tag {
            display: inline-flex !important;
            align-items: center !important;
            gap: 4px !important;
            white-space: nowrap !important;
            margin-bottom: 8px !important;
            color: #475569 !important;
            text-transform: uppercase !important;
        }
        
        /* 2. Fix big text issue: balance oversized section main titles across pages */
        .elementor-widget-heading h2.elementor-heading-title:not(.cmr-duration-meta-tag),
        .elementor-widget-heading h3.elementor-heading-title:not(.cmr-duration-meta-tag) {
            font-size: clamp(26px, 3.2vw, 38px) !important;
            line-height: 1.25 !important;
            letter-spacing: -0.5px !important;
            word-spacing: normal !important;
        }
    </style>
    <?php
});

// Add custom class to CMR LIVE badge element to avoid Elementor CSS conflicts
add_action('wp_footer', function() {
    ?>
    <script id="cmr-live-badge-class">
    (function() {
        function addCmrLiveBadgeClass() {
            var badge = document.querySelector('[data-id="d155fe5"]');
            if (badge && !badge.classList.contains('cmr-live-badge')) {
                badge.classList.add('cmr-live-badge');
            }
        }
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', addCmrLiveBadgeClass);
        } else {
            addCmrLiveBadgeClass();
        }
        setTimeout(addCmrLiveBadgeClass, 500);
    })();
    </script>
    <?php
}, 99);

add_action('wp_footer', function() {
    ?>
    <script id="cmr-duration-meta-fix">
    (function() {
        function fixDurationMetaTags() {
            var elements = document.querySelectorAll('.elementor-widget-heading .elementor-heading-title, .elementor-widget-text-editor, .cmr-ls-meta');
            elements.forEach(function(el) {
                var text = el.textContent || el.innerText || '';
                if (text.indexOf('MIN') !== -1 && (text.indexOf('WEBINAR') !== -1 || text.indexOf('PODCAST') !== -1 || text.indexOf('.') !== -1)) {
                    el.classList.add('cmr-duration-meta-tag');
                    el.style.setProperty('font-size', '13px', 'important');
                    el.style.setProperty('line-height', '1.4', 'important');
                    el.style.setProperty('font-weight', '700', 'important');
                    el.style.setProperty('display', 'inline-flex', 'important');
                    el.style.setProperty('align-items', 'center', 'important');
                    el.style.setProperty('vertical-align', 'middle', 'important');
                    el.style.setProperty('white-space', 'nowrap', 'important');

                    var children = el.querySelectorAll('*');
                    for (var i = 0; i < children.length; i++) {
                        children[i].style.setProperty('font-size', '13px', 'important');
                        children[i].style.setProperty('line-height', '1.4', 'important');
                        children[i].style.setProperty('font-weight', '700', 'important');
                        children[i].style.setProperty('vertical-align', 'middle', 'important');
                    }
                }
            });
        }
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', fixDurationMetaTags);
        } else {
            fixDurationMetaTags();
        }
        setTimeout(fixDurationMetaTags, 300);
        setTimeout(fixDurationMetaTags, 1000);
    })();
    </script>
    <?php
});

// Clean Figma Style for Team Box (no arrow overlay, clean 1:1 photos)

// Move social icons from image thumb to below text, and link team image to member profile
add_action('wp_footer', function() {
    ?>
    <script>
    function setupQuantoTeamBoxes() {
        var teamBoxes = document.querySelectorAll(".quanto-team-box");
        teamBoxes.forEach(function(box) {
            // 1. Move social icons below text if still inside team-thumb
            var ul = box.querySelector(".custom-ul");
            var content = box.querySelector(".team-content");
            if (ul && content && !content.contains(ul)) {
                content.appendChild(ul);
            }

            // 2. Link the image/thumb to the team member's profile page
            var nameLink = box.querySelector(".team-member-name a");
            var thumb = box.querySelector(".team-thumb");
            if (nameLink && thumb) {
                var href = nameLink.getAttribute("href");
                var target = nameLink.getAttribute("target");
                if (href) {
                    thumb.style.cursor = "pointer";

                    if (!thumb.querySelector("a.team-thumb-link") && thumb.parentElement.tagName !== "A") {
                        var linkWrapper = document.createElement("a");
                        linkWrapper.href = href;
                        if (target) {
                            linkWrapper.target = target;
                        }
                        linkWrapper.className = "team-thumb-link";
                        linkWrapper.setAttribute("aria-label", nameLink.textContent.trim());

                        var imgs = Array.from(thumb.querySelectorAll("img"));
                        if (imgs.length > 0) {
                            imgs.forEach(function(img) {
                                linkWrapper.appendChild(img);
                            });
                            thumb.insertBefore(linkWrapper, thumb.firstChild);
                        }
                    }

                    if (!thumb.dataset.linkAttached) {
                        thumb.dataset.linkAttached = "true";
                        thumb.addEventListener("click", function(e) {
                            // Do not intercept clicks on social icons
                            if (e.target.closest(".custom-ul a")) return;
                            if (e.target.closest("a.team-thumb-link")) return; // handled natively
                            if (target === "_blank") {
                                window.open(href, "_blank");
                            } else {
                                window.location.href = href;
                            }
                        });
                    }
                }
            }
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener("DOMContentLoaded", setupQuantoTeamBoxes);
    } else {
        setupQuantoTeamBoxes();
    }
    setTimeout(setupQuantoTeamBoxes, 300);
    setTimeout(setupQuantoTeamBoxes, 1000);
    </script>
    <style id="quanto-team-social-css">
    /* Styling for the relocated social icons */
    html body .quanto-team-box .team-content .custom-ul {
        display: flex !important;
        justify-content: flex-start !important;
        gap: 8px !important;
        margin-top: 15px !important;
        padding: 0 !important;
        list-style: none !important;
    }
    html body .quanto-team-box .team-content .custom-ul li {
        margin: 0 !important;
        padding: 0 !important;
    }
    html body .quanto-team-box .team-content .custom-ul li a {
        display: flex !important;
        align-items: center !important;
        justify-content: flex-start !important;
        width: 24px !important;
        height: 24px !important;
        color: #111 !important;
        font-size: 18px !important;
        text-decoration: none !important;
        transition: all 0.3s !important;
        background: transparent !important;
    }
    html body .quanto-team-box .team-content .custom-ul li a:hover {
        color: #6A35FF !important;
    }
    html body .quanto-team-box .team-content .custom-ul li a svg {
        width: 18px !important;
        height: 18px !important;
        fill: currentColor !important;
    }
    </style>
    <?php
});

// Override the Elementor Services widget from the theme
add_action('elementor/widgets/register', function($widgets_manager) {
    // Unregister the plugin's original widget if it exists
    $widgets_manager->unregister('quanto_services');
    
    // Register our theme's overridden widget
    require_once get_template_directory() . '/inc/widgets/service.php';
    $widgets_manager->register(new \Quanto_Service_Theme());

    // Register new Logo Carousel widget
    require_once get_template_directory() . '/inc/widgets/logo-carousel.php';
    $widgets_manager->register(new \Quanto_Logo_Carousel_Widget());

    // Register Industry Intel List widget
    require_once get_template_directory() . '/inc/widgets/industry-intel-list.php';
    $widgets_manager->register(new \Quanto_Industry_Intel_List_Widget());

    // Register CMR Featured Video Insight widget
    require_once get_template_directory() . '/inc/widgets/featured-video-insight.php';
    $widgets_manager->register(new \Quanto_Featured_Video_Insight_Widget());

    // Register CMR Custom Image Carousel (with Width & Height controls)
    if ( file_exists( get_template_directory() . '/inc/widgets/custom-carousel.php' ) ) {
        require_once get_template_directory() . '/inc/widgets/custom-carousel.php';
        if ( class_exists( '\Quanto_Custom_Carousel_Widget' ) ) {
            $widgets_manager->register(new \Quanto_Custom_Carousel_Widget());
        }
    }
}, 20); // Priority 20 to run after the plugin registers its widgets
require_once get_template_directory() . '/inc/cmr-footer-css-fix.php';

// Shortcode to display the main Elementor footer by fetching the rendered URL
add_shortcode('cmr_footer', function() {
    if ( function_exists('cmr_is_elementor_active') && cmr_is_elementor_active() ) {
        return '<div style="padding:20px;text-align:center;background:#f5f5f5;color:#666;font-size:14px;">[Footer Section]</div>';
    }

    $transient_key = 'cmr_footer_html_cache';
    $cached_footer = get_transient( $transient_key );
    
    // Check if user is logged in (to force refresh) or if cache is empty
    if ( false === $cached_footer || ( is_user_logged_in() && isset($_GET['refresh_footer']) ) ) {
        $url = home_url( '/?quanto_footer=main' );
        $response = wp_remote_get( $url, array(
            'timeout'   => 10,
            'sslverify' => false,
            'headers'   => array( 'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36' ),
        ) );
        
        $body = ! is_wp_error( $response ) ? wp_remote_retrieve_body( $response ) : '';
        
        // Extract everything from <footer class="footer"> to </footer>
        if ( ! empty( $body ) && preg_match( '/<footer class="footer".*?<\/footer>/is', $body, $matches ) ) {
            $cached_footer = $matches[0];
            
            // Extract and inline all post CSS from disk or response
            $inline_css = '';
            $upload_dir = wp_upload_dir();
            $elementor_css_dir = trailingslashit( $upload_dir['basedir'] ) . 'elementor/css/';

            if ( preg_match_all( '/<link[^>]+href=[\'"]([^\'"]+post-(\d+)\.css[^\'"]*)[\'"][^>]*>/i', $body, $link_matches, PREG_SET_ORDER ) ) {
                foreach ( $link_matches as $lm ) {
                    $full_tag = $lm[0];
                    $css_id   = $lm[2];
                    $disk_file = $elementor_css_dir . 'post-' . $css_id . '.css';
                    
                    if ( file_exists( $disk_file ) ) {
                        $css_content = file_get_contents( $disk_file );
                        if ( ! empty( $css_content ) ) {
                            $inline_css .= '<style id="cmr-cached-footer-post-' . $css_id . '-css">' . $css_content . '</style>' . "\n";
                            $cached_footer = str_replace( $full_tag, '', $cached_footer );
                        }
                    }
                }
            }

            // Also capture any inline styles in the fetched body
            if ( preg_match_all( '/<style[^>]*id=[\'"]elementor-post-(\d+)-inline-css[\'"][^>]*>(.*?)<\/style>/is', $body, $style_matches, PREG_SET_ORDER ) ) {
                foreach ( $style_matches as $sm ) {
                    $css_id = $sm[1];
                    if ( strpos( $inline_css, 'cmr-cached-footer-post-' . $css_id ) === false ) {
                        $inline_css .= '<style id="cmr-cached-footer-post-' . $css_id . '-css">' . $sm[2] . '</style>' . "\n";
                    }
                }
            }

            // Guarantee footer's own post CSS is inlined
            $footer_id = function_exists( 'quanto_get_resolved_footer_id' ) ? quanto_get_resolved_footer_id() : 13499;
            if ( $footer_id ) {
                if ( strpos( $inline_css, 'post-' . $footer_id ) === false ) {
                    $footer_css_file = $elementor_css_dir . 'post-' . $footer_id . '.css';
                    if ( file_exists( $footer_css_file ) ) {
                        $f_content = file_get_contents( $footer_css_file );
                        if ( ! empty( $f_content ) ) {
                            $inline_css .= '<style id="cmr-cached-footer-post-' . $footer_id . '-css">' . $f_content . '</style>' . "\n";
                        }
                    }
                }
                // Also inline local atomic desktop and mobile CSS for footer
                $devices = array( 'desktop', 'mobile', 'tablet', 'laptop' );
                foreach ( $devices as $device ) {
                    $local_footer = $elementor_css_dir . 'local-' . $footer_id . '-frontend-' . $device . '.css';
                    if ( file_exists( $local_footer ) ) {
                        $lf_content = file_get_contents( $local_footer );
                        if ( ! empty( $lf_content ) ) {
                            $inline_css .= '<style id="cmr-cached-footer-local-' . $footer_id . '-' . $device . '-css">' . $lf_content . '</style>' . "\n";
                        }
                    }
                }
            }
            
            // Prepend the inlined CSS so it loads with the HTML
            $cached_footer = $inline_css . $cached_footer;
            
            // Cache for 7 days
            set_transient( $transient_key, $cached_footer, WEEK_IN_SECONDS );
        } else {
            // Direct in-process fallback if remote loopback fails (e.g. during heavy crawler runs at night)
            $footer_id = function_exists( 'quanto_get_resolved_footer_id' ) ? quanto_get_resolved_footer_id() : 13499;
            if ( $footer_id && class_exists( '\\Elementor\\Plugin' ) && isset( \Elementor\Plugin::$instance->frontend ) ) {
                $direct_content = \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $footer_id, true );
                if ( ! empty( $direct_content ) ) {
                    $inline_css = '';
                    $upload_dir = wp_upload_dir();
                    $elementor_css_dir = trailingslashit( $upload_dir['basedir'] ) . 'elementor/css/';
                    $footer_css_file = $elementor_css_dir . 'post-' . $footer_id . '.css';

                    // 1. Post CSS with DB backup and self-heal
                    $f_content = function_exists('cmr_get_or_restore_css')
                        ? cmr_get_or_restore_css( $footer_css_file, 'cmr_css_backup_' . $footer_id . '_post', $footer_id )
                        : ( file_exists( $footer_css_file ) ? @file_get_contents( $footer_css_file ) : '' );

                    if ( ! empty( $f_content ) ) {
                        $inline_css .= '<style id="cmr-cached-footer-post-' . $footer_id . '-css">' . $f_content . '</style>' . "\n";
                    } elseif ( function_exists( 'cmr_get_elementor_css_inline' ) ) {
                        $f_content = cmr_get_elementor_css_inline( $footer_id );
                        if ( ! empty( $f_content ) ) {
                            $inline_css .= '<style id="cmr-cached-footer-post-' . $footer_id . '-css">' . $f_content . '</style>' . "\n";
                        }
                    }

                    // 2. Responsive local device CSS with DB backup and self-heal
                    $devices = array( 'desktop', 'mobile', 'tablet', 'laptop' );
                    foreach ( $devices as $device ) {
                        $local_footer = $elementor_css_dir . 'local-' . $footer_id . '-frontend-' . $device . '.css';
                        $lf_content   = function_exists('cmr_get_or_restore_css')
                            ? cmr_get_or_restore_css( $local_footer, 'cmr_css_backup_' . $footer_id . '_' . $device, $footer_id, $device )
                            : ( file_exists( $local_footer ) ? @file_get_contents( $local_footer ) : '' );

                        if ( ! empty( $lf_content ) ) {
                            $inline_css .= '<style id="cmr-cached-footer-local-' . $footer_id . '-' . $device . '-css">' . $lf_content . '</style>' . "\n";
                        }
                    }

                    $cached_footer = $inline_css . '<footer class="footer">' . $direct_content . '</footer>';
                    // Never poison cache with empty CSS: if CSS is present, cache for 7 days; otherwise only 60s
                    $ttl = ( ! empty( $inline_css ) ) ? WEEK_IN_SECONDS : 60;
                    set_transient( $transient_key, $cached_footer, $ttl );
                }
            }
        }
    }
    
    return $cached_footer;
});

// Safe helper to check if Elementor editor or preview is active
if ( ! function_exists('cmr_is_elementor_active') ) {
    function cmr_is_elementor_active() {
        if ( ! class_exists( '\Elementor\Plugin' ) ) {
            return false;
        }
        if ( ! empty( $_GET['elementor-preview'] ) || ( isset( $_GET['action'] ) && $_GET['action'] === 'elementor' ) ) {
            return true;
        }
        if ( isset( \Elementor\Plugin::$instance ) ) {
            if ( isset( \Elementor\Plugin::$instance->editor ) && method_exists( \Elementor\Plugin::$instance->editor, 'is_edit_mode' ) ) {
                if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
                    return true;
                }
            }
            if ( isset( \Elementor\Plugin::$instance->preview ) && method_exists( \Elementor\Plugin::$instance->preview, 'is_preview_mode' ) ) {
                if ( \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
                    return true;
                }
            }
        }
        return false;
    }
}

// Persistent DB Backup & Self-Healing Helper for Elementor CSS
if ( ! function_exists( 'cmr_get_or_restore_css' ) ) {
    /**
     * Reads CSS from disk and keeps a persistent DB backup in wp_options.
     * If the disk file is ever deleted (e.g. nightly server maintenance),
     * it instantly self-heals by restoring from the DB backup and rewriting back to disk!
     */
    function cmr_get_or_restore_css( $file_path, $backup_key, $post_id = 0, $device = '' ) {
        $content = '';
        
        // 1. Try reading from disk
        if ( ! empty( $file_path ) && file_exists( $file_path ) ) {
            $content = @file_get_contents( $file_path );
            if ( ! empty( $content ) ) {
                // Keep persistent DB backup updated
                update_option( $backup_key, $content, false );
                return $content;
            }
        }
        
        // 2. If missing from disk or empty, restore from persistent DB backup!
        $backup = get_option( $backup_key, '' );
        if ( ! empty( $backup ) ) {
            $content = $backup;
            // Self-heal: Write file back to disk so any other process or browser finds it
            if ( ! empty( $file_path ) && is_writable( dirname( $file_path ) ) ) {
                @file_put_contents( $file_path, $backup );
            }
            return $content;
        }

        // 3. Fallback: If neither disk nor DB has it, try compiling via Elementor
        if ( $post_id && class_exists( '\\Elementor\\Core\\Files\\CSS\\Post' ) ) {
            try {
                $css_file = \Elementor\Core\Files\CSS\Post::create( $post_id );
                $css_file->update();
                if ( ! empty( $file_path ) && file_exists( $file_path ) ) {
                    $content = @file_get_contents( $file_path );
                    if ( ! empty( $content ) ) {
                        update_option( $backup_key, $content, false );
                        return $content;
                    }
                }
            } catch ( Exception $e ) {}
        }
        
        return $content;
    }
}

// Helper to force print Elementor CSS inline inside a shortcode with persistent self-healing
if ( ! function_exists('cmr_print_elementor_css') ) {
    function cmr_print_elementor_css($post_id) {
        if ( ! $post_id || cmr_is_elementor_active() ) {
            return;
        }

        $upload_dir = wp_upload_dir();
        $elementor_css_dir = ! empty( $upload_dir['basedir'] ) ? trailingslashit( $upload_dir['basedir'] ) . 'elementor/css/' : '';

        // 1. Post CSS with persistent DB backup and disk self-healing
        $css_path = $elementor_css_dir ? $elementor_css_dir . 'post-' . intval($post_id) . '.css' : '';
        if ( empty( $css_path ) && class_exists( '\\Elementor\\Core\\Files\\CSS\\Post' ) ) {
            $css_file = new \Elementor\Core\Files\CSS\Post( $post_id );
            $css_path = $css_file->get_path();
        }

        $post_css = cmr_get_or_restore_css( $css_path, 'cmr_css_backup_' . intval($post_id) . '_post', $post_id );
        if ( ! empty( $post_css ) ) {
            echo '<style id="elementor-post-' . intval($post_id) . '-inline-css">' . $post_css . '</style>' . "\n";
        } elseif ( class_exists( '\\Elementor\\Core\\Files\\CSS\\Post' ) ) {
            $css_file = new \Elementor\Core\Files\CSS\Post( $post_id );
            $raw_content = method_exists( $css_file, 'get_content' ) ? $css_file->get_content() : '';
            if ( ! empty( $raw_content ) ) {
                echo '<style id="elementor-post-' . intval($post_id) . '-inline-css">' . $raw_content . '</style>' . "\n";
            }
        }

        // 2. Local atomic desktop & mobile CSS (contains all .e-* atomic styles: font sizes, colors, margins, flex layouts)
        if ( ! empty( $elementor_css_dir ) ) {
            $devices = array( 'desktop', 'mobile', 'tablet', 'laptop' );
            foreach ( $devices as $device ) {
                $local_file = $elementor_css_dir . 'local-' . intval($post_id) . '-frontend-' . $device . '.css';
                $backup_key = 'cmr_css_backup_' . intval($post_id) . '_' . $device;
                $d_content  = cmr_get_or_restore_css( $local_file, $backup_key, $post_id, $device );

                if ( ! empty( $d_content ) ) {
                    echo '<style id="elementor-local-' . intval($post_id) . '-' . $device . '-inline-css">' . $d_content . '</style>' . "\n";
                    continue;
                }
                if ( in_array( $device, array( 'desktop', 'mobile' ), true ) && ! empty( $upload_dir['baseurl'] ) ) {
                    $local_url = trailingslashit( $upload_dir['baseurl'] ) . 'elementor/css/local-' . intval($post_id) . '-frontend-' . $device . '.css';
                    echo '<link rel="stylesheet" id="local-' . intval($post_id) . '-frontend-' . $device . '-css" href="' . esc_url($local_url) . '" type="text/css" media="all">' . "\n";
                }
            }
        }
    }
}

// Shortcode to display the Challenge section by rendering the quanto_tab_build post
add_shortcode('cmr_challenge', function() {
    ob_start();
    
    // Find the post by slug
    $posts = get_posts(array(
        'name' => 'your-challenge-our-research-your-advantage',
        'post_type' => 'quanto_tab_build',
        'posts_per_page' => 1,
        'post_status' => 'publish'
    ));
    
    if ( $posts && !empty($posts[0]) ) {
        $post_id = $posts[0]->ID;
        
        // Print CSS link inline
        cmr_print_elementor_css($post_id);
        
        // Render it
        if ( class_exists( '\\Elementor\\Plugin' ) ) {
            echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $post_id, true );
        }
    }
    
    return ob_get_clean();
});

add_action( 'wp_enqueue_scripts', function() {
    global $post;
    if ( (is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'cmr_similar_reports' )) || is_single() ) {
        if ( class_exists( '\Elementor\Plugin' ) ) {
            // Enqueue Elementor core frontend CSS
            \Elementor\Plugin::$instance->frontend->enqueue_styles();
            // Also ensure WooCommerce widget CSS is enqueued for the products grid
            wp_enqueue_style( 'widget-woocommerce-products' );
        }
    }
}, 99 );

// Shortcode to display the Similar Reports by Industry section by rendering the quanto_tab_build post
add_shortcode('cmr_similar_reports', function() {
    ob_start();
    
    // Find the post by slug
    $posts = get_posts(array(
        'name' => 'similar-reports-by-industry',
        'post_type' => 'quanto_tab_build',
        'posts_per_page' => 1,
        'post_status' => 'publish'
    ));
    
    if ( $posts && !empty($posts[0]) ) {
        $post_id = $posts[0]->ID;
        
        // Print CSS link inline
        cmr_print_elementor_css($post_id);
        
        // Render it
        if ( class_exists( '\Elementor\Plugin' ) ) {
            echo '<div id="cmr-similar-reports-section">';
            echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $post_id, true );
            echo '</div>';
        }
    }
    
    return ob_get_clean();
});

// Shortcode to display the Testimonials section by rendering the quanto_tab_build post
add_shortcode('cmr_testimonials', function() {
    ob_start();
    
    // Find the post by slug
    $posts = get_posts(array(
        'name' => 'testimonials',
        'post_type' => 'quanto_tab_build',
        'posts_per_page' => 1,
        'post_status' => 'publish'
    ));
    
    if ( $posts && !empty($posts[0]) ) {
        $post_id = $posts[0]->ID;
        
        // Print CSS link inline
        cmr_print_elementor_css($post_id);
        
        // Render it
        if ( class_exists( '\\Elementor\\Plugin' ) ) {
            echo '<div id="cmr-testimonials-section">';
            echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $post_id, true );
            echo '</div>';
        }
    }
    
    return ob_get_clean();
});

// Global CSS for Testimonials (Desktop 2-column wide layout matching Image 1 + Mobile centered layout)
add_action('wp_head', 'cmr_testimonials_global_mobile_css', 9999);
function cmr_testimonials_global_mobile_css() {
    if ( function_exists( 'is_cart' ) && ( is_cart() || is_checkout() || is_account_page() ) ) {
        return;
    }
    ?>
    <style id="cmr-testimonials-global-mobile-css">
    /* ==========================================
       TEAM SECTION (DESKTOP VISIBILITY & SPACING)
       ========================================== */
    .elementor-element.elementor-element-96edc13,
    .elementor-element-96edc13.elementor-hidden-desktop,
    .elementor-element-96edc13.elementor-hidden-tablet {
        display: block !important;
    }

    /* ==========================================
       DESKTOP VIEW (2-COLUMN WIDE LAYOUT - MATCHING IMAGE 1)
       ========================================== */
    @media (min-width: 1025px) {
        #cmr-testimonials-section,
        .elementor-23190 .elementor-element.elementor-element-a6ac41e,
        .elementor-14 .elementor-element.elementor-element-82ef444,
        .elementor-element-a6ac41e,
        .elementor-element-82ef444 {
            --padding-top: 0px !important;
            --padding-bottom: 0px !important;
            width: 100% !important;
            max-width: 1280px !important;
            margin: 0 auto 50px auto !important;
            padding: 20px 24px 0px !important;
            box-sizing: border-box !important;
            background-color: #FFFFFF !important;
        }

        #cmr-testimonials-section .e-con-inner,
        .elementor-23190 .elementor-element.elementor-element-283442d,
        .elementor-14 .elementor-element.elementor-element-748fff1,
        .elementor-element-283442d,
        .elementor-element-748fff1 {
            display: flex !important;
            flex-direction: row !important;
            flex-wrap: nowrap !important;
            align-items: flex-start !important;
            justify-content: space-between !important;
            width: 100% !important;
            max-width: 100% !important;
            gap: 0 !important;
            box-sizing: border-box !important;
        }

        /* Left Column: Subtitle, Big Quote, Heading with vertical separator line */
        .elementor-23190 .elementor-element.elementor-element-6647a0b,
        .elementor-14 .elementor-element.elementor-element-0807bbc,
        #cmr-testimonials-section .elementor-element-6647a0b,
        #cmr-testimonials-section .elementor-element-0807bbc,
        .elementor-element-6647a0b,
        .elementor-element-0807bbc {
            width: 360px !important;
            min-width: 360px !important;
            max-width: 360px !important;
            flex: 0 0 360px !important;
            --width: 360px !important;
            display: flex !important;
            flex-direction: column !important;
            align-items: flex-start !important;
            text-align: left !important;
            border-style: solid !important;
            border-width: 0 1px 0 0 !important;
            border-color: #E5E7EB !important;
            padding: 0 45px 0 0 !important;
            margin: 0 !important;
            box-sizing: border-box !important;
        }

        /* Subtitle: "CLIENT TESTIMONIALS" */
        .elementor-23190 .elementor-element.elementor-element-cd36bd5,
        .elementor-14 .elementor-element.elementor-element-d9679c4,
        #cmr-testimonials-section .elementor-element-cd36bd5,
        #cmr-testimonials-section .elementor-element-d9679c4 {
            margin: 0 0 20px 0 !important;
            padding: 0 !important;
            text-align: left !important;
            width: 100% !important;
        }

        .elementor-23190 .elementor-element.elementor-element-cd36bd5 p,
        .elementor-14 .elementor-element.elementor-element-d9679c4 p,
        #cmr-testimonials-section .elementor-element-cd36bd5 p,
        #cmr-testimonials-section .elementor-element-d9679c4 p {
            font-family: "Instrument Sans", sans-serif !important;
            font-size: 13px !important;
            font-weight: 600 !important;
            letter-spacing: 0.6px !important;
            text-transform: uppercase !important;
            color: #0F0F0F !important;
            margin: 0 !important;
            text-align: left !important;
        }

        /* Big Quote Mark Image */
        .elementor-23190 .elementor-element.elementor-element-1df5c74,
        .elementor-14 .elementor-element.elementor-element-7c38a95,
        #cmr-testimonials-section .elementor-widget-image {
            display: block !important;
            visibility: visible !important;
            margin: 0 0 30px 0 !important;
            text-align: left !important;
            width: auto !important;
        }

        .elementor-23190 .elementor-element.elementor-element-1df5c74 img,
        .elementor-14 .elementor-element.elementor-element-7c38a95 img,
        #cmr-testimonials-section .elementor-widget-image img {
            width: 64px !important;
            height: auto !important;
            display: block !important;
            margin: 0 !important;
        }

        /* Heading: "What our <br> clients say <br> about us" */
        .elementor-23190 .elementor-element.elementor-element-aecd77b,
        .elementor-14 .elementor-element.elementor-element-8da86a5,
        #cmr-testimonials-section .elementor-element-aecd77b,
        #cmr-testimonials-section .elementor-element-8da86a5 {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            text-align: left !important;
        }

        .elementor-23190 .elementor-element.elementor-element-aecd77b .elementor-heading-title,
        .elementor-14 .elementor-element.elementor-element-8da86a5 .elementor-heading-title,
        #cmr-testimonials-section .elementor-heading-title {
            font-family: "Instrument Sans", sans-serif !important;
            font-size: 46px !important;
            font-weight: 600 !important;
            line-height: 1.15 !important;
            letter-spacing: -1.2px !important;
            color: #0F0F0F !important;
            text-align: left !important;
            margin: 0 !important;
            white-space: normal !important;
        }

        .elementor-23190 .elementor-element.elementor-element-aecd77b .elementor-heading-title br,
        .elementor-14 .elementor-element.elementor-element-8da86a5 .elementor-heading-title br,
        #cmr-testimonials-section .elementor-heading-title br {
            display: inline !important;
        }

        /* Right Column: Testimonials Slider */
        .elementor-23190 .elementor-element.elementor-element-acc3da4,
        .elementor-14 .elementor-element.elementor-element-3b754b6,
        #cmr-testimonials-section .elementor-element-acc3da4,
        #cmr-testimonials-section .elementor-element-3b754b6,
        .elementor-element-acc3da4,
        .elementor-element-3b754b6,
        .elementor-widget-wcf--a-testimonial,
        .wcf__t_slider-wrapper {
            flex: 1 1 0% !important;
            width: calc(100% - 360px) !important;
            max-width: calc(100% - 360px) !important;
            padding-left: 55px !important;
            padding-right: 0 !important;
            margin: 0 !important;
            box-sizing: border-box !important;
            display: flex !important;
            flex-direction: column !important;
            justify-content: flex-start !important;
            align-items: flex-start !important;
        }

        .wcf__t_slider-wrapper,
        .aae--a-testimonial,
        .wcf__slider,
        .wcf__slider.swiper {
            width: 100% !important;
            max-width: 100% !important;
            overflow: hidden !important;
            position: relative !important;
            margin: 0 !important;
            padding: 0 !important;
            box-sizing: border-box !important;
        }

        #cmr-testimonials-section .swiper-wrapper,
        .elementor-widget-wcf--a-testimonial .swiper-wrapper,
        .wcf__slider .swiper-wrapper {
            display: flex !important;
            width: 100% !important;
            box-sizing: border-box !important;
        }

        #cmr-testimonials-section .swiper-slide,
        .elementor-widget-wcf--a-testimonial .swiper-slide,
        .wcf__slider .swiper-slide,
        .aae--a-testimonial .swiper-slide {
            width: 100% !important;
            min-width: 100% !important;
            max-width: 100% !important;
            flex: 0 0 100% !important;
            flex-shrink: 0 !important;
            box-sizing: border-box !important;
            margin-right: 0 !important;
            padding: 0 !important;
        }

        #cmr-testimonials-section .slide,
        #cmr-testimonials-section .aae--a-testimonial .slide,
        #cmr-testimonials-section .slide-content,
        #cmr-testimonials-section .content,
        .wcf__slider .slide {
            text-align: left !important;
            align-items: flex-start !important;
            justify-content: flex-start !important;
            display: flex !important;
            flex-direction: column !important;
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
            background: transparent !important;
        }

        /* Feedback quote text on desktop - Max-width 700px to match Image 1 line-wrap */
        .wcf__slider .feedback,
        .aae--a-testimonial .feedback,
        .elementor-element-acc3da4 .feedback,
        .elementor-element-3b754b6 .feedback,
        .elementor-23190 .elementor-element.elementor-element-acc3da4 .feedback,
        .elementor-14 .elementor-element.elementor-element-3b754b6 .feedback,
        #cmr-testimonials-section .feedback,
        #cmr-testimonials-section .aae--a-testimonial .feedback,
        #cmr-testimonials-section .wcf__slider .feedback,
        .elementor-widget-wcf--a-testimonial .feedback,
        p.feedback {
            text-align: left !important;
            font-family: "Instrument Sans", sans-serif !important;
            font-size: 21px !important;
            font-weight: 500 !important;
            line-height: 1.55 !important;
            letter-spacing: -0.3px !important;
            text-transform: none !important;
            color: #111111 !important;
            margin: 0 0 35px 0 !important;
            padding: 0 !important;
            width: 100% !important;
            max-width: 700px !important;
            box-sizing: border-box !important;
            display: block !important;
        }

        /* Author on desktop */
        .wcf__slider .wrap,
        .aae--a-testimonial .wrap,
        .wcf__slider .author,
        .aae--a-testimonial .author,
        .elementor-element-acc3da4 .wrap,
        .elementor-element-3b754b6 .wrap,
        .elementor-element-acc3da4 .author,
        .elementor-element-3b754b6 .author,
        #cmr-testimonials-section .wrap,
        #cmr-testimonials-section .aae--a-testimonial .wrap,
        #cmr-testimonials-section .author,
        #cmr-testimonials-section .aae--a-testimonial .author,
        .elementor-widget-wcf--a-testimonial .wrap,
        .elementor-widget-wcf--a-testimonial .author {
            display: inline-flex !important;
            flex-direction: row !important;
            align-items: center !important;
            justify-content: flex-start !important;
            gap: 20px !important;
            margin: 0 0 30px 0 !important;
            text-align: left !important;
            width: auto !important;
            box-sizing: border-box !important;
        }

        .wcf__slider .author .image img,
        .aae--a-testimonial .author .image img,
        .elementor-element-acc3da4 .author .image img,
        .elementor-element-3b754b6 .author .image img,
        #cmr-testimonials-section .author .image img,
        #cmr-testimonials-section .aae--a-testimonial .author .image img,
        .elementor-widget-wcf--a-testimonial .author .image img {
            max-height: 52px !important;
            max-width: 130px !important;
            width: auto !important;
            height: auto !important;
            object-fit: contain !important;
            border-radius: 0 !important;
        }

        .wcf__slider .author .info,
        .aae--a-testimonial .author .info,
        .elementor-element-acc3da4 .author .info,
        .elementor-element-3b754b6 .author .info,
        #cmr-testimonials-section .author .info,
        #cmr-testimonials-section .aae--a-testimonial .author .info,
        .elementor-widget-wcf--a-testimonial .author .info {
            display: flex !important;
            flex-direction: column !important;
            align-items: flex-start !important;
            justify-content: center !important;
            text-align: left !important;
        }

        .wcf__slider .author .name,
        .aae--a-testimonial .author .name,
        .elementor-element-acc3da4 .author .name,
        .elementor-element-3b754b6 .author .name,
        #cmr-testimonials-section .author .name,
        #cmr-testimonials-section .aae--a-testimonial .author .name,
        .elementor-widget-wcf--a-testimonial .author .name {
            font-family: "Instrument Sans", sans-serif !important;
            font-size: 22px !important;
            font-weight: 700 !important;
            line-height: 1.25 !important;
            color: #0F0F0F !important;
            margin: 0 0 4px 0 !important;
            text-align: left !important;
        }

        .wcf__slider .author .designation,
        .aae--a-testimonial .author .designation,
        .elementor-element-acc3da4 .author .designation,
        .elementor-element-3b754b6 .author .designation,
        #cmr-testimonials-section .author .designation,
        #cmr-testimonials-section .aae--a-testimonial .author .designation,
        .elementor-widget-wcf--a-testimonial .author .designation {
            font-family: "Instrument Sans", sans-serif !important;
            font-size: 14px !important;
            font-weight: 400 !important;
            color: #475569 !important;
            line-height: 1.4 !important;
            margin: 0 !important;
            text-align: left !important;
        }

        /* Navigation Arrows on desktop */
        .wcf__slider .ts-navigation,
        .aae--a-testimonial .ts-navigation,
        .elementor-element-acc3da4 .ts-navigation,
        .elementor-element-3b754b6 .ts-navigation,
        #cmr-testimonials-section .ts-navigation,
        #cmr-testimonials-section .aae--a-testimonial .ts-navigation,
        .elementor-widget-wcf--a-testimonial .ts-navigation {
            display: flex !important;
            flex-direction: row !important;
            justify-content: flex-start !important;
            align-items: center !important;
            gap: 12px !important;
            margin: 0 !important;
            width: auto !important;
            text-align: left !important;
        }

        .wcf__slider .wcf-arrow,
        .aae--a-testimonial .wcf-arrow,
        .elementor-element-acc3da4 .wcf-arrow,
        .elementor-element-3b754b6 .wcf-arrow,
        #cmr-testimonials-section .wcf-arrow,
        #cmr-testimonials-section .aae--a-testimonial .wcf-arrow,
        .elementor-widget-wcf--a-testimonial .wcf-arrow {
            background-color: #F5F5F5 !important;
            border-radius: 50% !important;
            width: 44px !important;
            height: 44px !important;
            min-width: 44px !important;
            min-height: 44px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            cursor: pointer !important;
        }
    }

    /* ==========================================
       MOBILE & TABLET VIEW (CENTERED SINGLE SLIDE)
       ========================================== */
    @media (max-width: 1024px) {
        /* Team section bottom spacing adjustment for exact 50px gap */
        .elementor-element-96edc13 .cmr-team-scroll-section,
        .cmr-team-scroll-section {
            padding-bottom: 0 !important;
        }
        .elementor-element-96edc13 .cmr-team-view-all,
        .cmr-team-view-all {
            margin-bottom: 0 !important;
        }

        /* Section containers: 50px top gap from View Team, exactly 20px edge margin from screen borders */
        #cmr-testimonials-section,
        .elementor-element-a6ac41e,
        .elementor-element-82ef444 {
            padding-top: 50px !important;
            padding-bottom: 40px !important;
            padding-left: 20px !important;
            padding-right: 20px !important;
            margin-left: 0 !important;
            margin-right: 0 !important;
            margin-top: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
            align-items: center !important;
            text-align: center !important;
        }

        #cmr-testimonials-section .elementor-element-a6ac41e,
        #cmr-testimonials-section .elementor-element-82ef444 {
            padding: 0 !important;
            margin: 0 !important;
        }

        #cmr-testimonials-section .e-con-inner,
        .elementor-element-a6ac41e .e-con-inner,
        .elementor-element-82ef444 .e-con-inner,
        #cmr-testimonials-section .elementor-element-283442d,
        #cmr-testimonials-section .elementor-element-748fff1,
        .elementor-element-a6ac41e .elementor-element-283442d,
        .elementor-element-82ef444 .elementor-element-748fff1 {
            width: 100% !important;
            max-width: 100% !important;
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            justify-content: center !important;
            text-align: center !important;
            padding: 0 !important;
            margin: 0 !important;
            box-sizing: border-box !important;
        }

        #cmr-testimonials-section .elementor-element-6647a0b,
        #cmr-testimonials-section .elementor-element-0807bbc,
        .elementor-element-a6ac41e .elementor-element-6647a0b,
        .elementor-element-82ef444 .elementor-element-0807bbc {
            width: 100% !important;
            max-width: 100% !important;
            border: none !important;
            border-right: none !important;
            padding: 0 !important;
            margin: 0 0 20px 0 !important;
            gap: 0 !important;
            align-items: center !important;
            text-align: center !important;
            box-sizing: border-box !important;
        }

        /* Subtitle: "CLIENT TESTIMONIALS" */
        #cmr-testimonials-section .elementor-element-cd36bd5,
        #cmr-testimonials-section .elementor-element-d9679c4,
        .elementor-element-a6ac41e .elementor-element-cd36bd5,
        .elementor-element-82ef444 .elementor-element-d9679c4,
        .elementor-23190 .elementor-element.elementor-element-cd36bd5,
        .elementor-14 .elementor-element.elementor-element-d9679c4 {
            width: 100% !important;
            text-align: center !important;
            align-self: center !important;
            margin: 0 0 12px 0 !important;
            padding: 0 !important;
        }

        #cmr-testimonials-section .elementor-element-cd36bd5 p,
        #cmr-testimonials-section .elementor-element-d9679c4 p,
        .elementor-element-a6ac41e .elementor-element-cd36bd5 p,
        .elementor-element-82ef444 .elementor-element-d9679c4 p,
        .elementor-23190 .elementor-element.elementor-element-cd36bd5 p,
        .elementor-14 .elementor-element.elementor-element-d9679c4 p {
            text-align: center !important;
            font-family: "Instrument Sans", sans-serif !important;
            font-size: 13px !important;
            font-weight: 600 !important;
            letter-spacing: 0.6px !important;
            text-transform: uppercase !important;
            color: #0F0F0F !important;
            margin: 0 auto !important;
        }

        /* Hide huge quote mark icon */
        #cmr-testimonials-section .elementor-element-1df5c74,
        #cmr-testimonials-section .elementor-element-7c38a95,
        .elementor-element-a6ac41e .elementor-element-1df5c74,
        .elementor-element-82ef444 .elementor-element-7c38a95,
        .elementor-23190 .elementor-element.elementor-element-1df5c74,
        .elementor-14 .elementor-element.elementor-element-7c38a95,
        #cmr-testimonials-section .elementor-widget-image,
        .elementor-element-a6ac41e .elementor-widget-image,
        .elementor-element-82ef444 .elementor-widget-image {
            display: none !important;
            visibility: hidden !important;
        }

        /* Heading: "What our clients say about us" - Font size 28px, Weight 600 */
        #cmr-testimonials-section .elementor-element-aecd77b,
        #cmr-testimonials-section .elementor-element-8da86a5,
        .elementor-element-a6ac41e .elementor-element-aecd77b,
        .elementor-element-82ef444 .elementor-element-8da86a5,
        .elementor-23190 .elementor-element.elementor-element-aecd77b,
        .elementor-14 .elementor-element.elementor-element-8da86a5 {
            width: 100% !important;
            max-width: 100% !important;
            text-align: center !important;
            align-self: center !important;
            margin: 0 0 20px 0 !important;
            padding: 0 !important;
            box-sizing: border-box !important;
        }

        #cmr-testimonials-section .elementor-element-aecd77b .elementor-heading-title,
        #cmr-testimonials-section .elementor-element-8da86a5 .elementor-heading-title,
        .elementor-element-a6ac41e .elementor-element-aecd77b .elementor-heading-title,
        .elementor-element-82ef444 .elementor-element-8da86a5 .elementor-heading-title,
        .elementor-23190 .elementor-element.elementor-element-aecd77b .elementor-heading-title,
        .elementor-14 .elementor-element.elementor-element-8da86a5 .elementor-heading-title {
            text-align: center !important;
            font-family: "Instrument Sans", sans-serif !important;
            font-size: 28px !important;
            font-weight: 600 !important;
            line-height: 1.25 !important;
            letter-spacing: -0.5px !important;
            color: #0F0F0F !important;
            margin: 0 auto !important;
            white-space: normal !important;
        }

        #cmr-testimonials-section .elementor-element-aecd77b .elementor-heading-title br,
        #cmr-testimonials-section .elementor-element-8da86a5 .elementor-heading-title br,
        .elementor-element-a6ac41e .elementor-element-aecd77b .elementor-heading-title br,
        .elementor-element-82ef444 .elementor-element-8da86a5 .elementor-heading-title br,
        .elementor-23190 .elementor-element.elementor-element-aecd77b .elementor-heading-title br,
        .elementor-14 .elementor-element.elementor-element-8da86a5 .elementor-heading-title br,
        .elementor-element-aecd77b .elementor-heading-title br,
        .elementor-element-8da86a5 .elementor-heading-title br {
            display: inline-block !important;
            width: 0.28em !important;
            height: 0 !important;
            overflow: hidden !important;
            vertical-align: baseline !important;
            content: " " !important;
        }

        /* Slider Widget & Wrapper */
        #cmr-testimonials-section .elementor-element-acc3da4,
        #cmr-testimonials-section .elementor-element-3b754b6,
        .elementor-element-a6ac41e .elementor-element-acc3da4,
        .elementor-element-82ef444 .elementor-element-3b754b6,
        .elementor-element-acc3da4,
        .elementor-element-3b754b6,
        .elementor-widget-wcf--a-testimonial,
        .wcf__t_slider-wrapper,
        #cmr-testimonials-section .elementor-widget-wcf--a-testimonial,
        #cmr-testimonials-section .wcf__t_slider-wrapper {
            width: 100% !important;
            max-width: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
            text-align: center !important;
            box-sizing: border-box !important;
        }

        /* Swiper container - exactly 100% width and strictly hidden overflow */
        .elementor-widget-wcf--a-testimonial .wcf__slider,
        .elementor-widget-wcf--a-testimonial .wcf__slider.swiper,
        .aae--a-testimonial .wcf__slider,
        .aae--a-testimonial .wcf__slider.swiper,
        .elementor-element-acc3da4 .wcf__slider,
        .elementor-element-3b754b6 .wcf__slider,
        #cmr-testimonials-section .wcf__slider,
        #cmr-testimonials-section .wcf__slider.swiper,
        .wcf__slider,
        .wcf__slider.swiper {
            width: 100% !important;
            max-width: 100% !important;
            overflow: hidden !important;
            position: relative !important;
            margin: 0 auto !important;
            padding: 0 !important;
            box-sizing: border-box !important;
        }

        /* Swiper track */
        .elementor-widget-wcf--a-testimonial .swiper-wrapper,
        .aae--a-testimonial .swiper-wrapper,
        .elementor-element-acc3da4 .swiper-wrapper,
        .elementor-element-3b754b6 .swiper-wrapper,
        #cmr-testimonials-section .swiper-wrapper,
        .wcf__slider .swiper-wrapper {
            width: 100% !important;
            display: flex !important;
            position: relative !important;
            box-sizing: border-box !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        /* EXACTLY ONE SLIDE FULL WIDTH AT A TIME - NO MARGIN SHIFT */
        .elementor-widget-wcf--a-testimonial .swiper-slide,
        .aae--a-testimonial .swiper-slide,
        .elementor-element-acc3da4 .swiper-slide,
        .elementor-element-3b754b6 .swiper-slide,
        #cmr-testimonials-section .swiper-slide,
        .wcf__slider .swiper-slide,
        .wcf__t_slider-wrapper .swiper-slide {
            width: 100% !important;
            min-width: 100% !important;
            max-width: 100% !important;
            flex: 0 0 100% !important;
            flex-shrink: 0 !important;
            box-sizing: border-box !important;
            padding: 0 !important;
            margin: 0 !important;
            margin-left: 0 !important;
            margin-right: 0 !important;
            overflow: hidden !important;
            display: flex !important;
            justify-content: center !important;
            align-items: center !important;
        }

        /* Slide item: Vertically stacked, centered */
        .elementor-widget-wcf--a-testimonial .slide,
        .aae--a-testimonial .slide,
        .elementor-element-acc3da4 .slide,
        .elementor-element-3b754b6 .slide,
        #cmr-testimonials-section .aae--a-testimonial .slide,
        .elementor-element-a6ac41e .aae--a-testimonial .slide,
        .elementor-element-82ef444 .aae--a-testimonial .slide,
        .wcf__slider .slide {
            width: 100% !important;
            max-width: 100% !important;
            padding: 0 12px !important;
            margin: 0 auto !important;
            text-align: center !important;
            box-sizing: border-box !important;
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            justify-content: center !important;
        }

        /* Feedback quote text: CENTERED, readable, matching Image 2 */
        .elementor-widget-wcf--a-testimonial .feedback,
        .aae--a-testimonial .feedback,
        #cmr-testimonials-section .aae--a-testimonial .feedback,
        #cmr-testimonials-section .wcf__slider .feedback,
        .elementor-element-a6ac41e .aae--a-testimonial .feedback,
        .elementor-element-82ef444 .aae--a-testimonial .feedback,
        .elementor-element-3b754b6 .feedback,
        .elementor-element-acc3da4 .feedback,
        .wcf__slider .feedback,
        .slide .feedback,
        p.feedback {
            text-align: center !important;
            font-family: "Instrument Sans", sans-serif !important;
            font-size: 18px !important;
            font-weight: 500 !important;
            line-height: 1.55 !important;
            letter-spacing: -0.3px !important;
            text-transform: none !important;
            color: #111111 !important;
            margin: 0 auto 28px auto !important;
            padding: 0 !important;
            width: 100% !important;
            max-width: 680px !important;
            box-sizing: border-box !important;
            display: block !important;
        }

        /* Author wrap & info: HORIZONTALLY CENTERED */
        .elementor-widget-wcf--a-testimonial .wrap,
        .aae--a-testimonial .wrap,
        #cmr-testimonials-section .aae--a-testimonial .wrap,
        .elementor-element-a6ac41e .aae--a-testimonial .wrap,
        .elementor-element-82ef444 .aae--a-testimonial .wrap,
        .elementor-element-3b754b6 .wrap,
        .elementor-element-acc3da4 .wrap,
        .slide .wrap,
        .wcf__slider .wrap {
            display: flex !important;
            justify-content: center !important;
            align-items: center !important;
            text-align: center !important;
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 auto !important;
            padding: 0 !important;
            box-sizing: border-box !important;
        }

        .elementor-widget-wcf--a-testimonial .author,
        .aae--a-testimonial .author,
        #cmr-testimonials-section .aae--a-testimonial .author,
        .elementor-element-a6ac41e .aae--a-testimonial .author,
        .elementor-element-82ef444 .aae--a-testimonial .author,
        .elementor-element-3b754b6 .author,
        .elementor-element-acc3da4 .author,
        .slide .author,
        .wcf__slider .author {
            display: inline-flex !important;
            flex-direction: row !important;
            justify-content: center !important;
            align-items: center !important;
            text-align: left !important;
            margin: 0 auto !important;
            gap: 16px !important;
            max-width: 100% !important;
        }

        .elementor-widget-wcf--a-testimonial .author .image,
        .aae--a-testimonial .author .image,
        #cmr-testimonials-section .aae--a-testimonial .author .image,
        .elementor-element-a6ac41e .aae--a-testimonial .author .image,
        .elementor-element-82ef444 .aae--a-testimonial .author .image,
        .elementor-element-3b754b6 .author .image,
        .elementor-element-acc3da4 .author .image {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            margin: 0 !important;
            flex-shrink: 0 !important;
            max-width: 90px !important;
            max-height: 60px !important;
            overflow: visible !important;
            border-radius: 0 !important;
        }

        .elementor-widget-wcf--a-testimonial .author .image img,
        .aae--a-testimonial .author .image img,
        #cmr-testimonials-section .aae--a-testimonial .author .image img,
        .elementor-element-a6ac41e .aae--a-testimonial .author .image img,
        .elementor-element-82ef444 .aae--a-testimonial .author .image img,
        .elementor-element-3b754b6 .author .image img,
        .elementor-element-acc3da4 .author .image img {
            max-height: 56px !important;
            max-width: 90px !important;
            width: auto !important;
            height: auto !important;
            object-fit: contain !important;
            border-radius: 50% !important;
        }

        .elementor-widget-wcf--a-testimonial .author .image img[src*="ibm"],
        .elementor-widget-wcf--a-testimonial .author .image img[src*="logo"],
        .elementor-widget-wcf--a-testimonial .author .image img[src*="Symbol"],
        .elementor-widget-wcf--a-testimonial .author .image img[src*=".svg"],
        .aae--a-testimonial .author .image img[src*="ibm"],
        .aae--a-testimonial .author .image img[src*="logo"],
        .aae--a-testimonial .author .image img[src*="Symbol"],
        .aae--a-testimonial .author .image img[src*=".svg"] {
            border-radius: 0 !important;
        }

        .elementor-widget-wcf--a-testimonial .author .info,
        .aae--a-testimonial .author .info,
        #cmr-testimonials-section .aae--a-testimonial .author .info,
        .elementor-element-a6ac41e .aae--a-testimonial .author .info,
        .elementor-element-82ef444 .aae--a-testimonial .author .info,
        .elementor-element-3b754b6 .author .info,
        .elementor-element-acc3da4 .author .info {
            display: flex !important;
            flex-direction: column !important;
            align-items: flex-start !important;
            justify-content: center !important;
            text-align: left !important;
        }

        .elementor-widget-wcf--a-testimonial .author .name,
        .aae--a-testimonial .author .name,
        #cmr-testimonials-section .aae--a-testimonial .author .name,
        .elementor-element-a6ac41e .aae--a-testimonial .author .name,
        .elementor-element-82ef444 .aae--a-testimonial .author .name,
        .elementor-element-3b754b6 .author .name,
        .elementor-element-acc3da4 .author .name {
            text-align: left !important;
            font-family: "Instrument Sans", sans-serif !important;
            font-size: 20px !important;
            font-weight: 700 !important;
            line-height: 1.25 !important;
            color: #0F0F0F !important;
            margin: 0 0 3px 0 !important;
        }

        .elementor-widget-wcf--a-testimonial .author .designation,
        .aae--a-testimonial .author .designation,
        #cmr-testimonials-section .aae--a-testimonial .author .designation,
        .elementor-element-a6ac41e .aae--a-testimonial .author .designation,
        .elementor-element-82ef444 .aae--a-testimonial .author .designation,
        .elementor-element-3b754b6 .author .designation,
        .elementor-element-acc3da4 .author .designation {
            text-align: left !important;
            font-family: "Instrument Sans", sans-serif !important;
            font-size: 14px !important;
            font-weight: 400 !important;
            color: #475569 !important;
            line-height: 1.4 !important;
            margin: 0 !important;
        }

        .elementor-widget-wcf--a-testimonial .ts-navigation,
        .aae--a-testimonial .ts-navigation,
        #cmr-testimonials-section .aae--a-testimonial .ts-navigation,
        .elementor-element-a6ac41e .aae--a-testimonial .ts-navigation,
        .elementor-element-82ef444 .aae--a-testimonial .ts-navigation,
        .elementor-element-3b754b6 .ts-navigation,
        .elementor-element-acc3da4 .ts-navigation,
        .wcf__t_slider-wrapper .ts-navigation {
            display: flex !important;
            justify-content: center !important;
            align-items: center !important;
            margin: 28px auto 0 auto !important;
            width: 100% !important;
            gap: 16px !important;
            position: relative !important;
            z-index: 5 !important;
        }

        .elementor-widget-wcf--a-testimonial .wcf-arrow,
        .aae--a-testimonial .wcf-arrow,
        #cmr-testimonials-section .aae--a-testimonial .wcf-arrow,
        .elementor-element-a6ac41e .aae--a-testimonial .wcf-arrow,
        .elementor-element-82ef444 .aae--a-testimonial .wcf-arrow,
        .wcf-arrow {
            background-color: #F5F5F5 !important;
            border-radius: 50% !important;
            width: 48px !important;
            height: 48px !important;
            min-width: 48px !important;
            min-height: 48px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            cursor: pointer !important;
        }
    }

    /* ==========================================================================
       Global Brands & Logo Carousel Styles (Desktop & Responsive)
       ========================================================================== */
    #cmr-global-brands-section,
    .elementor-element-7ec6a9b,
    .elementor-23198,
    .elementor-element-645a65a,
    .elementor-23198 .elementor-element-645a65a,
    .elementor-14 .elementor-element-c9502e0 {
        --padding-bottom: 0px !important;
        --padding-top: 0px !important;
        margin-top: 0 !important;
        margin-bottom: 40px !important;
        padding-top: 0 !important;
        padding-bottom: 0 !important;
    }

    /* Marketing Services v2 Page-Specific Spacing & Typography Fixes */
    .page-id-52409 .elementor-element-92f6b3c,
    .elementor-page-52409 .elementor-element-92f6b3c {
        margin-top: 60px !important;
        margin-bottom: 80px !important;
    }

    .page-id-52409 .elementor-element-654b811,
    .elementor-page-52409 .elementor-element-654b811 {
        margin-bottom: 40px !important;
    }

    /* Marketing Services v2 - WHO WE ARE & About Us typography */
    .page-id-52409 .elementor-element-8bae85b .elementor-heading-title,
    .elementor-page-52409 .elementor-element-8bae85b .elementor-heading-title,
    .elementor-element-8bae85b .elementor-heading-title {
        font-family: "Instrument Sans", sans-serif !important;
        font-size: 14px !important;
        font-weight: 500 !important;
        letter-spacing: 1px !important;
        text-transform: uppercase !important;
        color: #0F0F0F !important;
        margin-bottom: 8px !important;
    }

    .page-id-52409 .elementor-element-1c887cf .elementor-heading-title,
    .elementor-page-52409 .elementor-element-1c887cf .elementor-heading-title,
    .elementor-element-1c887cf .elementor-heading-title {
        font-family: "Instrument Sans", sans-serif !important;
        font-size: 52px !important;
        font-weight: 600 !important;
        line-height: 1.15 !important;
        letter-spacing: -1.5px !important;
        color: #000000 !important;
    }

    /* Marketing Services v2 - Brands Carousel & Bottom Footer Space Fix */
    .page-id-52409 .elementor-element-47fa9c5 > .e-con-inner,
    .elementor-page-52409 .elementor-element-47fa9c5 > .e-con-inner,
    .page-id-52409 .elementor-element-222f861 > .e-con-inner,
    .elementor-page-52409 .elementor-element-222f861 > .e-con-inner,
    .page-id-52409 .elementor-element-645a65a > .e-con-inner,
    .elementor-page-52409 .elementor-element-645a65a > .e-con-inner,
    .page-id-52409 .elementor-23198 > .elementor,
    .page-id-52409 .elementor-element-c9502e0 > .e-con-inner {
        margin-bottom: 0 !important;
        --margin-bottom: 0 !important;
        padding-bottom: 0 !important;
        --padding-bottom: 0 !important;
    }

    .page-id-52409 .elementor-element-47fa9c5,
    .elementor-page-52409 .elementor-element-47fa9c5,
    .page-id-52409 div[data-id="47fa9c5"],
    .page-id-52409 .elementor-element-222f861,
    .elementor-page-52409 .elementor-element-222f861,
    .page-id-52409 .elementor-element-645a65a,
    .elementor-page-52409 .elementor-element-645a65a,
    .page-id-52409 .elementor-23198,
    .elementor-page-52409 .elementor-23198,
    .page-id-52409 .elementor-element-c9502e0,
    .elementor-page-52409 .elementor-element-c9502e0 {
        margin-bottom: 25px !important;
        --margin-bottom: 25px !important;
        padding-bottom: 0 !important;
        --padding-bottom: 0 !important;
    }

    .page-id-52409 .elementor-element-96c4d86,
    .elementor-page-52409 .elementor-element-96c4d86,
    .page-id-52409 .elementor-23165,
    .elementor-page-52409 .elementor-23165,
    .page-id-52409 .elementor-element-66f0cc3,
    .elementor-page-52409 .elementor-element-66f0cc3,
    .page-id-52409 .footer,
    .elementor-page-52409 .footer,
    .page-id-52409 footer,
    .elementor-page-52409 footer,
    .page-id-52409 .elementor-13499,
    .page-id-52409 .elementor-element-ba501f5,
    .page-id-52409 .elementor-element-21bcbf2 {
        margin-top: 0 !important;
        --margin-top: 0 !important;
    }

    /* Marketing Services v2 - Our Community Section & Funfacts (Desktop & Mobile) */
    .elementor-element-1c353d5,
    .page-id-52409 .elementor-element-1c353d5,
    .elementor-page-52409 .elementor-element-1c353d5 {
        width: 100% !important;
        max-width: 1240px !important;
        margin-left: auto !important;
        margin-right: auto !important;
        padding: 10px 20px 40px 20px !important;
    }

    .elementor-element-1c353d5 > .e-con-inner {
        width: 100% !important;
        max-width: 100% !important;
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        gap: 0 !important;
    }

    /* Grids for Top and Bottom Rows (Desktop) */
    .elementor-element-56b6d08,
    .elementor-element-1faccff,
    .page-id-52409 .elementor-element-56b6d08,
    .page-id-52409 .elementor-element-1faccff {
        display: grid !important;
        grid-template-columns: repeat(3, 1fr) !important;
        gap: 24px !important;
        width: 100% !important;
        max-width: 1200px !important;
        margin: 0 auto !important;
        --width: 100% !important;
    }

    /* Card Styling for Each Funfact Widget (Desktop & Responsive) */
    .elementor-element-1c353d5 .elementor-widget-elementskit-funfact {
        background: #FFFFFF !important;
        border: 1px solid #E2E8F0 !important;
        border-radius: 16px !important;
        padding: 24px 20px !important;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03) !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        text-align: center !important;
        width: 100% !important;
        margin-bottom: 0 !important;
        transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease !important;
    }

    .elementor-element-1c353d5 .elementor-widget-elementskit-funfact:hover {
        transform: translateY(-4px) !important;
        box-shadow: 0 10px 28px rgba(0, 113, 188, 0.08) !important;
        border-color: #BFDBFE !important;
    }

    .elementor-element-1c353d5 .elementskit-funfact {
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        justify-content: center !important;
        width: 100% !important;
        text-align: center !important;
        padding: 0 !important;
    }

    .elementor-element-1c353d5 .elementskit-funfact .vertical-bar {
        display: none !important;
    }

    .elementor-element-1c353d5 .elementskit-funfact .elementskit-funfact-inner {
        padding: 0 !important;
        width: 100% !important;
        text-align: center !important;
    }

    .elementor-element-1c353d5 .elementskit-funfact .funfact-content {
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        justify-content: center !important;
        text-align: center !important;
        width: 100% !important;
    }

    /* Number Percentage Styling (Desktop) */
    .elementor-element-1c353d5 .number-percentage-wraper {
        font-family: "Instrument Sans", -apple-system, BlinkMacSystemFont, sans-serif !important;
        font-size: 44px !important;
        font-weight: 700 !important;
        line-height: 1.1 !important;
        letter-spacing: -1.5px !important;
        color: #0071BC !important;
        display: inline-flex !important;
        align-items: baseline !important;
        justify-content: center !important;
        margin-bottom: 8px !important;
        white-space: nowrap !important;
        width: 100% !important;
    }

    .elementor-element-1c353d5 .number-percentage-wraper .number-percentage {
        font-family: inherit !important;
        font-weight: inherit !important;
        font-size: inherit !important;
        color: inherit !important;
        line-height: inherit !important;
    }

    .elementor-element-1c353d5 .number-percentage-wraper .super {
        font-size: 26px !important;
        font-weight: 600 !important;
        color: #0071BC !important;
        margin-left: 3px !important;
        top: 0 !important;
        vertical-align: baseline !important;
        position: static !important;
    }

    /* Title Styling (Desktop) */
    .elementor-element-1c353d5 .funfact-title {
        font-family: "Instrument Sans", -apple-system, BlinkMacSystemFont, sans-serif !important;
        font-size: 16px !important;
        font-weight: 500 !important;
        line-height: 1.35 !important;
        color: #334155 !important;
        letter-spacing: -0.2px !important;
        margin: 0 !important;
        text-align: center !important;
    }

    /* Center Divider & Badge: "The CMR network" (Desktop & All Pages) */
    .cmr-network-widget-fullwidth,
    .elementor-element-e642823,
    .page-id-52409 .elementor-element-e642823 {
        width: 100% !important;
        max-width: 100% !important;
        flex: 0 0 100% !important;
        flex-basis: 100% !important;
        grid-column: 1 / -1 !important;
        box-sizing: border-box !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    .cmr-network-divider-bar {
        width: 100% !important;
        max-width: 1200px !important;
        margin: 36px auto !important;
        padding: 0 16px !important;
        display: flex !important;
        flex-direction: row !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 0 !important;
        box-sizing: border-box !important;
        position: relative !important;
        z-index: 5 !important;
        clear: both !important;
    }

    .cmr-network-divider-bar .cmr-network-line,
    .cmr-network-divider-bar .cmr-network-line-left,
    .cmr-network-divider-bar .cmr-network-line-right {
        flex: 1 1 0% !important;
        height: 1px !important;
        background: #E5E7EB !important;
        background-color: #E5E7EB !important;
        border: none !important;
        display: block !important;
        min-width: 20px !important;
    }

    .cmr-network-pill-btn,
    .cmr-network-badge-btn,
    .elementor-element-788846b,
    .elementor-element-788846b .elementor-button,
    .elementor-element-64a79c2 .elementor-button,
    a.cmr-network-pill-btn,
    a.cmr-network-badge-btn {
        flex-shrink: 0 !important;
        position: relative !important;
        z-index: 2 !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        background: #4820B0 !important;
        background-color: #4820B0 !important;
        color: #FFFFFF !important;
        fill: #FFFFFF !important;
        border-radius: 9999px !important;
        padding: 0 32px !important;
        width: auto !important;
        max-width: max-content !important;
        min-width: 190px !important;
        height: 46px !important;
        min-height: 46px !important;
        max-height: 46px !important;
        line-height: 46px !important;
        border: none !important;
        outline: none !important;
        margin: 0 !important;
        box-shadow: 0 4px 14px rgba(72, 32, 176, 0.25) !important;
        text-decoration: none !important;
        font-family: "Instrument Sans", -apple-system, BlinkMacSystemFont, sans-serif !important;
        font-size: 13.5px !important;
        font-weight: 600 !important;
        letter-spacing: 0.6px !important;
        text-transform: uppercase !important;
        white-space: nowrap !important;
        transition: all 0.25s ease !important;
        cursor: pointer !important;
        box-sizing: border-box !important;
        vertical-align: middle !important;
    }

    .cmr-network-pill-btn:hover,
    .cmr-network-badge-btn:hover,
    .elementor-element-788846b:hover,
    .elementor-element-788846b .elementor-button:hover,
    .elementor-element-64a79c2 .elementor-button:hover,
    a.cmr-network-pill-btn:hover {
        background: #381691 !important;
        background-color: #381691 !important;
        color: #FFFFFF !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 6px 20px rgba(72, 32, 176, 0.35) !important;
    }

    .cmr-network-pill-btn *,
    .cmr-network-pill-btn p,
    .cmr-network-pill-btn span,
    .cmr-network-badge-btn *,
    .cmr-network-badge-btn p,
    .cmr-network-badge-btn span,
    .elementor-element-788846b p,
    .elementor-element-788846b span,
    .elementor-element-788846b .elementor-button-text {
        color: #FFFFFF !important;
        font-family: "Instrument Sans", -apple-system, BlinkMacSystemFont, sans-serif !important;
        font-size: inherit !important;
        font-weight: 600 !important;
        letter-spacing: 0.6px !important;
        text-transform: uppercase !important;
        margin: 0 !important;
        line-height: inherit !important;
        white-space: nowrap !important;
    }

    /* Tablet adjustments */
    @media (max-width: 991.98px) {
        .elementor-element-56b6d08,
        .elementor-element-1faccff,
        .page-id-52409 .elementor-element-56b6d08,
        .page-id-52409 .elementor-element-1faccff {
            gap: 16px !important;
        }

        .elementor-element-1c353d5 .number-percentage-wraper {
            font-size: 36px !important;
        }

        .elementor-element-1c353d5 .number-percentage-wraper .super {
            font-size: 22px !important;
        }

        .elementor-element-1c353d5 .funfact-title {
            font-size: 14px !important;
        }
    }

    /* Mobile adjustments */
    @media (max-width: 768px) {
        .elementor-element-ebccdd8,
        .page-id-52409 .elementor-element-ebccdd8,
        .elementor-page-52409 .elementor-element-ebccdd8 {
            padding: 30px 16px 10px 16px !important;
            margin-top: 0 !important;
            margin-bottom: 0 !important;
        }

        .elementor-element-0977755,
        .elementor-element-0977755 .elementor-heading-title {
            font-family: "Instrument Sans", sans-serif !important;
            font-size: 28px !important;
            line-height: 36px !important;
            font-weight: 600 !important;
            letter-spacing: -0.8px !important;
            text-align: center !important;
            margin-bottom: 8px !important;
            padding-bottom: 0 !important;
        }

        .elementor-element-1c353d5,
        .page-id-52409 .elementor-element-1c353d5,
        .elementor-page-52409 .elementor-element-1c353d5 {
            padding: 10px 16px 30px 16px !important;
            margin-top: 0 !important;
            --margin-top: 0px !important;
            --width: 100% !important;
        }

        .elementor-element-56b6d08,
        .elementor-element-1faccff,
        .page-id-52409 .elementor-element-56b6d08,
        .page-id-52409 .elementor-element-1faccff {
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 12px !important;
        }

        .elementor-element-56b6d08 > .elementor-element:nth-child(3),
        .elementor-element-1faccff > .elementor-element:nth-child(3) {
            grid-column: 1 / -1 !important;
            max-width: 260px !important;
            margin-left: auto !important;
            margin-right: auto !important;
            width: 100% !important;
        }

        .elementor-element-1c353d5 .elementor-widget-elementskit-funfact {
            padding: 16px 10px !important;
            border-radius: 14px !important;
        }

        .elementor-element-1c353d5 .number-percentage-wraper {
            font-size: 32px !important;
            margin-bottom: 6px !important;
        }

        .elementor-element-1c353d5 .number-percentage-wraper .super {
            font-size: 18px !important;
        }

        .elementor-element-1c353d5 .funfact-title {
            font-size: 13px !important;
        }

        .cmr-network-divider-bar {
            margin: 28px auto !important;
            padding: 0 12px !important;
        }

        .cmr-network-pill-btn,
        .cmr-network-badge-btn,
        .elementor-element-788846b,
        .elementor-element-788846b .elementor-button,
        a.cmr-network-pill-btn {
            padding: 0 20px !important;
            min-width: 150px !important;
            height: 38px !important;
            min-height: 38px !important;
            max-height: 38px !important;
            line-height: 38px !important;
            font-size: 11.5px !important;
            letter-spacing: 0.5px !important;
        }

        .cmr-network-divider-bar .cmr-network-line {
            min-width: 15px !important;
        }
    }

    /* Global Brands Section Title */
    .elementor-23198 .elementor-element.elementor-element-2496f2f,
    .elementor-23198 .elementor-element-2496f2f,
    .elementor-element-2496f2f,
    div[data-id="2496f2f"],
    .elementor-widget-text-editor[data-id="2496f2f"],
    .elementor-14 .elementor-element-71dfb04,
    .elementor-14 .elementor-element.elementor-element-71dfb04,
    .elementor-element-71dfb04,
    div[data-id="71dfb04"],
    #cmr-global-brands-section .elementor-widget-text-editor {
        margin-top: 0 !important;
        margin-bottom: 1rem !important;
        --margin-bottom: 1rem !important;
        text-align: left !important;
    }

    .elementor-23198 .elementor-element.elementor-element-2496f2f,
    .elementor-23198 .elementor-element-2496f2f,
    .elementor-element-2496f2f,
    div[data-id="2496f2f"],
    .elementor-widget-text-editor[data-id="2496f2f"],
    .elementor-23198 .elementor-element-2496f2f p,
    .elementor-element-2496f2f p,
    div[data-id="2496f2f"] p,
    .elementor-widget-text-editor[data-id="2496f2f"] p,
    .elementor-14 .elementor-element-71dfb04,
    .elementor-14 .elementor-element-71dfb04 p,
    .elementor-element-71dfb04,
    .elementor-element-71dfb04 p,
    div[data-id="71dfb04"],
    div[data-id="71dfb04"] p,
    #cmr-global-brands-section .elementor-widget-text-editor,
    #cmr-global-brands-section p {
        text-align: left !important;
        font-family: "Instrument Sans", sans-serif !important;
        font-size: 20px !important;
        font-weight: 500 !important;
        line-height: 28px !important;
        letter-spacing: 0.4px !important;
        color: #0F0F0F !important;
        margin-left: 0 !important;
        margin-right: auto !important;
    }

    @media (max-width: 768px) {
        .elementor-23198 .elementor-element-2496f2f,
        .elementor-element-2496f2f,
        div[data-id="2496f2f"],
        .elementor-element-2496f2f p,
        div[data-id="2496f2f"] p,
        .elementor-14 .elementor-element-71dfb04,
        .elementor-element-71dfb04,
        div[data-id="71dfb04"],
        .elementor-14 .elementor-element-71dfb04 p,
        .elementor-element-71dfb04 p,
        div[data-id="71dfb04"] p,
        #cmr-global-brands-section .elementor-widget-text-editor,
        #cmr-global-brands-section p {
            font-size: 16px !important;
            line-height: 24px !important;
            text-align: left !important;
            margin-left: 0 !important;
        }
    }

    /* Compact Logo Carousel slide height and prominent desktop logo sizing */
    .quanto-logo-slide,
    .elementor-23198 .elementor-element-0e0abf8 .quanto-logo-slide,
    .elementor-widget-quanto_logo_carousel .quanto-logo-slide {
        height: 120px !important;
        min-height: 120px !important;
        padding: 8px 15px !important;
        overflow: hidden !important;
    }

    .quanto-logo-slide img {
        max-height: 96px !important;
        max-width: 90% !important;
        width: auto !important;
        height: auto !important;
        object-fit: contain !important;
        transform: scale(1.35) !important;
        transform-origin: center center !important;
        display: block !important;
    }

    /* Proportional scaling for wide and oversized logos to match VMware and Veeam presentation */
    .quanto-logo-slide img[src*="qoo" i],
    .quanto-logo-slide img[src*="iqoo" i],
    .quanto-logo-slide.logo-item-iqoo img {
        max-width: 68% !important;
        max-height: 48px !important;
        transform: scale(0.9) !important;
    }

    .quanto-logo-slide img[src*="scaler" i],
    .quanto-logo-slide.logo-item-scaler img {
        max-width: 72% !important;
        max-height: 38px !important;
        transform: scale(0.85) !important;
    }

    .quanto-logo-slide img[src*="epam" i],
    .quanto-logo-slide.logo-item-epam img {
        max-width: 74% !important;
        max-height: 42px !important;
        transform: scale(0.88) !important;
    }

    .quanto-logo-slide img[src*="vi" i],
    .quanto-logo-slide.logo-item-vi img {
        max-width: 55% !important;
        max-height: 52px !important;
        transform: scale(0.9) !important;
    }

    .elementor-14 .elementor-element.elementor-element-df14a6f .elementor-widget-image,
    div[data-id="df14a6f"] .elementor-widget-image {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        width: 100% !important;
        height: 100% !important;
        overflow: hidden !important;
    }

    .elementor-14 .elementor-element.elementor-element-df14a6f .elementor-widget-image img,
    div[data-id="df14a6f"] .elementor-widget-image img {
        max-width: 90% !important;
        width: auto !important;
        height: auto !important;
        transform: scale(1.35) !important;
        transform-origin: center center !important;
        display: block !important;
    }

    @media (max-width: 768px) {
        #cmr-global-brands-section,
        .elementor-element-7ec6a9b,
        .elementor-23198,
        .elementor-23198 .elementor-element-645a65a,
        .elementor-14 .elementor-element-c9502e0 {
            margin-top: 0 !important;
            margin-bottom: 0 !important;
            padding-top: 0 !important;
            padding-bottom: 0 !important;
        }

        .page-id-52409 .elementor-element-92f6b3c,
        .elementor-page-52409 .elementor-element-92f6b3c {
            margin-top: 40px !important;
            margin-bottom: 50px !important;
        }

        .page-id-52409 .elementor-element-654b811,
        .elementor-page-52409 .elementor-element-654b811 {
            margin-bottom: 30px !important;
        }

        .page-id-52409 .elementor-element-8bae85b .elementor-heading-title,
        .elementor-page-52409 .elementor-element-8bae85b .elementor-heading-title,
        .elementor-element-8bae85b .elementor-heading-title {
            font-size: 13px !important;
            font-weight: 500 !important;
            letter-spacing: 0.8px !important;
        }

        .page-id-52409 .elementor-element-1c887cf .elementor-heading-title,
        .elementor-page-52409 .elementor-element-1c887cf .elementor-heading-title,
        .elementor-element-1c887cf .elementor-heading-title {
            font-size: 36px !important;
            line-height: 44px !important;
            letter-spacing: -1px !important;
        }

        /* Marketing Services v2 - Mobile Brands Carousel & Bottom Footer Space Fix */
        .page-id-52409 .elementor-element-47fa9c5 > .e-con-inner,
        .elementor-page-52409 .elementor-element-47fa9c5 > .e-con-inner,
        .page-id-52409 .elementor-element-222f861 > .e-con-inner,
        .elementor-page-52409 .elementor-element-222f861 > .e-con-inner,
        .page-id-52409 .elementor-element-645a65a > .e-con-inner,
        .elementor-page-52409 .elementor-element-645a65a > .e-con-inner,
        .page-id-52409 .elementor-23198 > .elementor,
        .page-id-52409 .elementor-element-c9502e0 > .e-con-inner {
            margin-bottom: 0 !important;
            --margin-bottom: 0 !important;
            padding-bottom: 0 !important;
            --padding-bottom: 0 !important;
        }

        .page-id-52409 .elementor-element-47fa9c5,
        .elementor-page-52409 .elementor-element-47fa9c5,
        .page-id-52409 div[data-id="47fa9c5"],
        .page-id-52409 .elementor-element-222f861,
        .elementor-page-52409 .elementor-element-222f861,
        .page-id-52409 .elementor-element-645a65a,
        .elementor-page-52409 .elementor-element-645a65a,
        .page-id-52409 .elementor-23198,
        .elementor-page-52409 .elementor-23198,
        .page-id-52409 .elementor-element-c9502e0,
        .elementor-page-52409 .elementor-element-c9502e0 {
            margin-bottom: 20px !important;
            --margin-bottom: 20px !important;
            padding-bottom: 0 !important;
            --padding-bottom: 0 !important;
        }

        .page-id-52409 .elementor-element-96c4d86,
        .elementor-page-52409 .elementor-element-96c4d86,
        .page-id-52409 .elementor-23165,
        .elementor-page-52409 .elementor-23165,
        .page-id-52409 .elementor-element-66f0cc3,
        .elementor-page-52409 .elementor-element-66f0cc3,
        .page-id-52409 .footer,
        .elementor-page-52409 .footer,
        .page-id-52409 footer,
        .elementor-page-52409 footer,
        .page-id-52409 .elementor-13499,
        .page-id-52409 .elementor-element-ba501f5,
        .page-id-52409 .elementor-element-21bcbf2 {
            margin-top: 0 !important;
            --margin-top: 0 !important;
        }

        .elementor-14 .elementor-element.elementor-element-adc3adf,
        .elementor-14 .elementor-element-adc3adf,
        .elementor-element.elementor-element-adc3adf,
        .elementor-element-adc3adf,
        div[data-id="adc3adf"],
        .elementor-14 .elementor-element.elementor-element-e7ead58,
        .elementor-14 .elementor-element-e7ead58,
        .elementor-element.elementor-element-e7ead58,
        .elementor-element-e7ead58,
        div[data-id="e7ead58"] {
            --margin-top: 45px !important;
            margin-top: 45px !important;
        }

        .quanto-logo-slide,
        .elementor-23198 .elementor-element-0e0abf8 .quanto-logo-slide,
        .elementor-widget-quanto_logo_carousel .quanto-logo-slide {
            height: 105px !important;
            min-height: 105px !important;
            padding: 6px 12px !important;
            overflow: hidden !important;
        }

        .quanto-logo-slide img {
            max-height: 85px !important;
            max-width: 88% !important;
            width: auto !important;
            height: auto !important;
            object-fit: contain !important;
            transform: scale(1.3) !important;
            transform-origin: center center !important;
        }

        .elementor-14 .elementor-element.elementor-element-df14a6f .elementor-widget-image img,
        div[data-id="df14a6f"] .elementor-widget-image img {
            max-width: 88% !important;
            transform: scale(1.3) !important;
            transform-origin: center center !important;
        }
    }

    @media (max-width: 480px) {
        .quanto-logo-slide,
        .elementor-23198 .elementor-element-0e0abf8 .quanto-logo-slide,
        .elementor-widget-quanto_logo_carousel .quanto-logo-slide {
            height: 95px !important;
            min-height: 95px !important;
            padding: 5px 10px !important;
            overflow: hidden !important;
        }

        .quanto-logo-slide img {
            max-height: 75px !important;
            max-width: 88% !important;
            transform: scale(1.25) !important;
            transform-origin: center center !important;
        }

        .elementor-14 .elementor-element.elementor-element-df14a6f .elementor-widget-image img,
        div[data-id="df14a6f"] .elementor-widget-image img {
            transform: scale(1.25) !important;
            transform-origin: center center !important;
        }
    }

    /* Purple CTA Banner (Footer Card) - eliminate excessive top margin */
    #cmr-footer-card-section,
    .elementor-23165,
    .elementor-23165 .elementor-element-66f0cc3,
    .elementor-14 .elementor-element-c95991a,
    .elementor-14 .elementor-element-88332ab {
        margin-top: 0 !important;
        padding-top: 0 !important;
    }

    /* CMR Homepage Form #8573 "Get Expert Insights" Submit Button Solid Brand Color on Page Load */
    .cmr-form input[type="submit"],
    .cmr-form button[type="submit"],
    .cmr-form .wpcf7-submit,
    .elementor-23165 input[type="submit"],
    #wpcf7-f8573-p14-o1 input[type="submit"],
    input[value="Get Expert Insights"],
    input[type="submit"][value="Get Expert Insights"] {
        width: 100% !important;
        height: 56px !important;
        min-height: 56px !important;
        background: #6A42E5 !important;
        background-color: #6A42E5 !important;
        background-image: linear-gradient(135deg, #6A42E5 0%, #4B2DBF 100%) !important;
        color: #ffffff !important;
        opacity: 1 !important;
        visibility: visible !important;
        filter: none !important;
        border: none !important;
        border-radius: 60px !important;
        font-family: 'Instrument Sans', sans-serif !important;
        font-size: 16px !important;
        font-weight: 600 !important;
        cursor: pointer !important;
        margin-top: 18px !important;
        box-shadow: 0 4px 14px rgba(106, 66, 229, 0.3) !important;
        display: block !important;
        text-align: center !important;
        transition: all 0.25s ease !important;
    }

    .cmr-form input[type="submit"]:hover,
    .cmr-form button[type="submit"]:hover,
    .cmr-form .wpcf7-submit:hover,
    .elementor-23165 input[type="submit"]:hover,
    #wpcf7-f8573-p14-o1 input[type="submit"]:hover,
    input[value="Get Expert Insights"]:hover {
        background: #582cd7 !important;
        background-color: #582cd7 !important;
        background-image: linear-gradient(135deg, #582cd7 0%, #3e1fa8 100%) !important;
        box-shadow: 0 6px 20px rgba(106, 66, 229, 0.45) !important;
        transform: translateY(-1px) !important;
        opacity: 0.95 !important;
    }
    </style>
    <?php
}

// Global Mobile & Tablet Swiper Reconfiguration for Testimonials Slider (Single slide swipe, touch enabled, zero offset drift)
add_action('wp_footer', 'cmr_testimonials_global_mobile_js', 99999);
function cmr_testimonials_global_mobile_js() {
    if ( function_exists( 'is_cart' ) && ( is_cart() || is_checkout() || is_account_page() ) ) {
        return;
    }
    ?>
    <script id="cmr-testimonials-global-fix">
    (function() {
        function reconfigureTestimonialSliders() {
            // Remove hardcoded <br> tags from the testimonial section heading on mobile only (<= 1024px)
            if (window.innerWidth <= 1024) {
                var titles = document.querySelectorAll(
                    '#cmr-testimonials-section .elementor-heading-title, ' +
                    '.elementor-element-aecd77b .elementor-heading-title, ' +
                    '.elementor-element-8da86a5 .elementor-heading-title'
                );
                titles.forEach(function(t) {
                    if (t && t.innerHTML && /<br\s*[\/]?>/i.test(t.innerHTML)) {
                        t.innerHTML = t.innerHTML.replace(/<br\s*[\/]?>/gi, ' ');
                    }
                });
            }

            // Normalize testimonial feedback text to clean sentence case if entered in ALL CAPS
            var feedbacks = document.querySelectorAll(
                '#cmr-testimonials-section .feedback, .wcf__slider .feedback, .aae--a-testimonial .feedback, p.feedback'
            );
            feedbacks.forEach(function(fb) {
                if (fb && fb.textContent) {
                    var txt = fb.textContent.trim();
                    if (txt.length > 20 && /[A-Z]/.test(txt) && txt === txt.toUpperCase()) {
                        fb.textContent = txt.toLowerCase().replace(/(^\s*["'“]?|[.!?]\s*["'“]?)([a-z])/g, function(m, p, c) {
                            return p + c.toUpperCase();
                        });
                    }
                }
            });

            // 1. Pre-patch data-settings on the wrappers so Swiper reads slidesPerView: 1, spaceBetween: 0, slower delay, and pause on hover
            var wrappers = document.querySelectorAll('.wcf__t_slider-wrapper, .aae--a-testimonial');
            wrappers.forEach(function(w) {
                var ds = w.getAttribute('data-settings');
                if (ds) {
                    try {
                        var conf = JSON.parse(ds);
                        var mod = false;
                        if (conf.slidesPerView !== 1) {
                            conf.slidesPerView = 1;
                            conf.slidesPerGroup = 1;
                            mod = true;
                        }
                        if (conf.spaceBetween !== 0) {
                            conf.spaceBetween = 0;
                            mod = true;
                        }
                        if (conf.allowTouchMove !== true) {
                            conf.allowTouchMove = true;
                            mod = true;
                        }
                        if (conf.speed !== 800) {
                            conf.speed = 800;
                            mod = true;
                        }
                        if (conf.autoplay) {
                            if (typeof conf.autoplay === 'object') {
                                conf.autoplay.delay = 6000;
                                conf.autoplay.pauseOnMouseEnter = true;
                                conf.autoplay.disableOnInteraction = false;
                            } else {
                                conf.autoplay = {
                                    delay: 6000,
                                    pauseOnMouseEnter: true,
                                    disableOnInteraction: false
                                };
                            }
                            mod = true;
                        }
                        if (conf.breakpoints) {
                            for (var b in conf.breakpoints) {
                                conf.breakpoints[b].slidesPerView = 1;
                                conf.breakpoints[b].slidesPerGroup = 1;
                                conf.breakpoints[b].spaceBetween = 0;
                                mod = true;
                            }
                        }
                        if (mod) {
                            w.setAttribute('data-settings', JSON.stringify(conf));
                        }
                    } catch(e) {}
                }
            });

            // 2. Update all active Swiper instances
            var sliders = document.querySelectorAll('.wcf__slider, .elementor-widget-wcf--a-testimonial .swiper, .aae--a-testimonial .swiper');
            sliders.forEach(function(s) {
                if (s && s.swiper) {
                    var sw = s.swiper;
                    var changed = false;
                    if (sw.params.slidesPerView !== 1) {
                        sw.params.slidesPerView = 1;
                        sw.params.slidesPerGroup = 1;
                        changed = true;
                    }
                    if (sw.params.spaceBetween !== 0) {
                        sw.params.spaceBetween = 0;
                        changed = true;
                    }
                    if (!sw.params.centeredSlides) {
                        sw.params.centeredSlides = true;
                        changed = true;
                    }
                    if (!sw.params.allowTouchMove) {
                        sw.params.allowTouchMove = true;
                        sw.allowTouchMove = true;
                        changed = true;
                    }
                    if (sw.params.speed !== 800) {
                        sw.params.speed = 800;
                        changed = true;
                    }
                    if (sw.params.autoplay) {
                        if (typeof sw.params.autoplay === 'object') {
                            if (sw.params.autoplay.delay !== 6000) {
                                sw.params.autoplay.delay = 6000;
                                changed = true;
                            }
                            if (!sw.params.autoplay.pauseOnMouseEnter) {
                                sw.params.autoplay.pauseOnMouseEnter = true;
                                changed = true;
                            }
                            if (sw.params.autoplay.disableOnInteraction) {
                                sw.params.autoplay.disableOnInteraction = false;
                                changed = true;
                            }
                        } else {
                            sw.params.autoplay = {
                                delay: 6000,
                                pauseOnMouseEnter: true,
                                disableOnInteraction: false
                            };
                            changed = true;
                        }
                    }
                    // Explicit Pause on Hover fallback listeners
                    if (!s.dataset.cmrHoverBound) {
                        s.dataset.cmrHoverBound = 'true';
                        s.addEventListener('mouseenter', function() {
                            if (s.swiper && s.swiper.autoplay && s.swiper.autoplay.stop) {
                                s.swiper.autoplay.stop();
                            }
                        });
                        s.addEventListener('mouseleave', function() {
                            if (s.swiper && s.swiper.autoplay && s.swiper.autoplay.start) {
                                s.swiper.autoplay.start();
                            }
                        });
                    }
                    if (sw.params.breakpoints) {
                        for (var bp in sw.params.breakpoints) {
                            if (parseInt(bp, 10) <= 1024) {
                                sw.params.breakpoints[bp].slidesPerView = 1;
                                sw.params.breakpoints[bp].slidesPerGroup = 1;
                                sw.params.breakpoints[bp].spaceBetween = 0;
                                sw.params.breakpoints[bp].centeredSlides = true;
                                changed = true;
                            }
                        }
                    }
                    if (changed) {
                        if (sw.updateSize) sw.updateSize();
                        if (sw.updateSlides) sw.updateSlides();
                        if (sw.updateProgress) sw.updateProgress();
                        if (sw.update) sw.update();
                        if (sw.autoplay && sw.autoplay.running) {
                            sw.autoplay.stop();
                            sw.autoplay.start();
                        }
                        if (sw.slideToLoop) {
                            sw.slideToLoop(sw.realIndex || 0, 0);
                        } else if (sw.slideTo) {
                            sw.slideTo(sw.activeIndex || 0, 0);
                        }
                    }
                }
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', reconfigureTestimonialSliders);
        } else {
            reconfigureTestimonialSliders();
        }
        window.addEventListener('load', reconfigureTestimonialSliders);
        window.addEventListener('resize', reconfigureTestimonialSliders);
        setTimeout(reconfigureTestimonialSliders, 50);
        setTimeout(reconfigureTestimonialSliders, 150);
        setTimeout(reconfigureTestimonialSliders, 300);
        setTimeout(reconfigureTestimonialSliders, 600);
        setTimeout(reconfigureTestimonialSliders, 1000);
        setTimeout(reconfigureTestimonialSliders, 2000);
        setTimeout(reconfigureTestimonialSliders, 3500);

        if (window.jQuery) {
            jQuery(window).on('elementor/frontend/init', function() {
                if (window.elementorFrontend && elementorFrontend.hooks) {
                    elementorFrontend.hooks.addAction('frontend/element_ready/wcf--a-testimonial.default', function() {
                        setTimeout(reconfigureTestimonialSliders, 50);
                        setTimeout(reconfigureTestimonialSliders, 200);
                        setTimeout(reconfigureTestimonialSliders, 800);
                    });
                }
            });
        }
    })();
    </script>
    <?php
}// Shortcode to display the Footer Card section by rendering the quanto_tab_build post
add_shortcode('cmr_footer_card', function() {
    ob_start();
    
    // Find the post by slug
    $posts = get_posts(array(
        'name' => 'fotter-card',
        'post_type' => 'quanto_tab_build',
        'posts_per_page' => 1,
        'post_status' => 'publish'
    ));
    
    if ( $posts && !empty($posts[0]) ) {
        $post_id = $posts[0]->ID;
        
        // Print CSS link inline
        cmr_print_elementor_css($post_id);
        
        // Render it
        if ( class_exists( '\\Elementor\\Plugin' ) ) {
            echo '<div id="cmr-footer-card-section">';
            echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $post_id, true );
            echo '</div>';
        }
    }
    
    return ob_get_clean();
});

// Shortcode to display the Global Brands section by rendering the quanto_tab_build post
add_shortcode('cmr_global_brands', function() {
    ob_start();
    
    // Find the post by slug
    $posts = get_posts(array(
        'name' => 'we-worked-with-largest-global-brands',
        'post_type' => 'quanto_tab_build',
        'posts_per_page' => 1,
        'post_status' => 'publish'
    ));
    
    if ( $posts && !empty($posts[0]) ) {
        $post_id = $posts[0]->ID;
        
        // Print CSS link inline
        cmr_print_elementor_css($post_id);
        
        // Render it
        if ( class_exists( '\\Elementor\\Plugin' ) ) {
            echo '<div id="cmr-global-brands-section">';
            echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $post_id, true );
            echo '</div>';
        }
    }
    
    return ob_get_clean();
});

// Shortcode to display the Global CTA Banner section by rendering the quanto_tab_build post
function cmr_global_cta_banner_shortcode( $atts = array() ) {
    ob_start();
    
    // Find the post by slug or fallback to ID
    $posts = get_posts(array(
        'name'           => 'global-cta-banner',
        'post_type'      => 'quanto_tab_build',
        'posts_per_page' => 1,
        'post_status'    => 'publish'
    ));
    
    $post_id = ( $posts && !empty($posts[0]) ) ? $posts[0]->ID : 52927;
    $output  = '';
    
    if ( $post_id ) {
        // Print CSS link inline
        if ( function_exists('cmr_print_elementor_css') ) {
            cmr_print_elementor_css($post_id);
        }
        
        // Render via Elementor frontend
        if ( class_exists( '\\Elementor\\Plugin' ) && isset(\Elementor\Plugin::$instance) && isset(\Elementor\Plugin::$instance->frontend) ) {
            $builder_content = \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $post_id, true );
            if ( ! empty( $builder_content ) ) {
                $output = $builder_content;
            }
        }
    }
    
    // Fallback: If builder content is empty, fetch and cache rendered output
    if ( empty( $output ) ) {
        $transient_key = 'cmr_global_cta_banner_cache';
        $cached_html   = get_transient( $transient_key );
        
        if ( false === $cached_html || ( is_user_logged_in() && isset($_GET['refresh_cta']) ) ) {
            $url      = home_url( '/?quanto_tab_build=global-cta-banner' );
            $response = wp_remote_get( $url, array(
                'timeout'   => 10,
                'sslverify' => false,
                'headers'   => array( 'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36' ),
            ) );
            if ( ! is_wp_error( $response ) ) {
                $body = wp_remote_retrieve_body( $response );
                if ( preg_match( '/<div[^>]*class="[^"]*elementor-(?:52927|\d+)[^"]*"[^>]*>.*?<\/body>/is', $body, $matches ) ) {
                    if ( preg_match( '/<div[^>]*class="[^"]*elementor-(?:52927|\d+)[^"]*"[^>]*>.*<\/div>\s*<\/div>\s*<\/div>/is', $body, $inner_matches ) ) {
                        $cached_html = $inner_matches[0];
                        set_transient( $transient_key, $cached_html, WEEK_IN_SECONDS );
                    }
                }
            }
        }
        
        if ( ! empty( $cached_html ) ) {
            $output = $cached_html;
        }
    }
    
    if ( ! empty( $output ) ) {
        if ( $post_id && function_exists('cmr_print_elementor_css') ) {
            cmr_print_elementor_css($post_id);
        }
        echo '<style>
        @media (max-width: 768px) {
            #cmr-global-cta-banner-section h1,
            #cmr-global-cta-banner-section h2,
            #cmr-global-cta-banner-section h3,
            #cmr-global-cta-banner-section .elementor-heading-title,
            #cmr-global-cta-banner-section .e-heading-base,
            .elementor-52927 h1,
            .elementor-52927 h2,
            .elementor-52927 h3,
            .elementor-52927 .elementor-heading-title,
            .elementor-52927 .e-heading-base {
                font-size: 32px !important;
                line-height: 1.25 !important;
                letter-spacing: 0 !important;
                text-align: center !important;
            }
            #cmr-global-cta-banner-section p,
            .elementor-52927 p {
                text-align: center !important;
            }
            #cmr-global-cta-banner-section .elementor-element-644b09c,
            .elementor-52927 [data-id="644b09c"],
            .elementor-52927 .elementor-element-644b09c {
                align-items: center !important;
                text-align: center !important;
                justify-content: center !important;
            }
            #cmr-global-cta-banner-section .elementor-element-e8ce45f,
            .elementor-52927 [data-id="e8ce45f"],
            .elementor-52927 .elementor-element-e8ce45f {
                display: flex !important;
                justify-content: center !important;
                align-items: center !important;
                width: 100% !important;
                margin: 0 auto !important;
                text-align: center !important;
            }
            #cmr-global-cta-banner-section .elementor-element-d647871,
            #cmr-global-cta-banner-section [data-id="d647871"],
            .elementor-52927 [data-id="d647871"],
            .elementor-52927 .elementor-element-d647871 {
                margin: 0 auto !important;
                align-self: center !important;
                --align-self: center !important;
                text-align: center !important;
                display: inline-flex !important;
            }
            #cmr-global-cta-banner-section .elementor-button,
            .elementor-52927 .elementor-button {
                margin: 0 auto !important;
            }
        }
        </style>';
        echo '<div id="cmr-global-cta-banner-section">';
        echo $output;
        echo '</div>';
    }
    
    return ob_get_clean();
}
add_shortcode('cmr_global_cta_banner', 'cmr_global_cta_banner_shortcode');
add_shortcode('cmr_global_cta', 'cmr_global_cta_banner_shortcode');
add_shortcode('global_cta_banner', 'cmr_global_cta_banner_shortcode');

// Helper function to get the post thumbnail, or fallback to scraping the og:image from the media URL, or a hardcoded fallback
if ( ! function_exists( 'cmr_get_thumbnail_with_fallback' ) ) {
    function cmr_get_thumbnail_with_fallback($post_id, $size = 'full') {
        // 1. Check for standard WordPress Featured Image
        $thumbnail_url = get_the_post_thumbnail_url($post_id, $size);
        if ($thumbnail_url) {
            return $thumbnail_url;
        }

        $fallback_img = 'https://cmrindia.com/wp-content/uploads/2026/06/Why-Chipsets-are-the-New-Frontier-in-Smartphones1.jpg';

        // 2. Check if there's a custom media URL and try to fetch og:image
        $media_url = get_post_meta($post_id, '_cmr_media_url', true);
        if ($media_url && filter_var($media_url, FILTER_VALIDATE_URL)) {
            
            // Check transient cache to avoid slowing down page load (use v6 to bust cache)
            $cache_key = 'cmr_og_image_v6_' . md5($media_url);
            $cached_img = get_transient($cache_key);
            
            if ($cached_img) {
                if ($cached_img !== 'none') {
                    return $cached_img;
                }
            } else {
                // Fetch the URL
                $response = wp_remote_get($media_url, array('timeout' => 5));
                if (!is_wp_error($response)) {
                    $body = wp_remote_retrieve_body($response);
                    
                    $candidates = [];
                    
                    // Look for preload image first (best for getpodcast.com covers), then og:image, etc.
                    if (preg_match_all('/<link[^>]*rel="preload"[^>]*href="([^"]+)"[^>]*as="image"/i', $body, $m)) { $candidates = array_merge($candidates, $m[1]); }
                    if (preg_match_all('/<link[^>]*href="([^"]+)"[^>]*rel="preload"[^>]*as="image"/i', $body, $m)) { $candidates = array_merge($candidates, $m[1]); }
                    if (preg_match_all('/<meta property="og:image:secure_url" content="([^"]+)"/i', $body, $m)) { $candidates = array_merge($candidates, $m[1]); }
                    if (preg_match_all('/<meta property="og:image" content="([^"]+)"/i', $body, $m)) { $candidates = array_merge($candidates, $m[1]); }
                    if (preg_match_all('/<meta name="og:image" content="([^"]+)"/i', $body, $m)) { $candidates = array_merge($candidates, $m[1]); }
                    if (preg_match_all('/<meta property="twitter:image" content="([^"]+)"/i', $body, $m)) { $candidates = array_merge($candidates, $m[1]); }
                    
                    foreach ($candidates as $scraped_img) {
                        $scraped_img = trim($scraped_img);
                        
                        // Ignore tiny transparent square from getpodcast which breaks UI
                        if (strpos($scraped_img, 'square.png') !== false) {
                            continue;
                        }
                        
                        // Fix double scheme from some buggy websites (like getpodcast.com)
                        $scraped_img = str_replace('https://https://', 'https://', $scraped_img);
                        $scraped_img = str_replace('http://http://', 'http://', $scraped_img);
                        $scraped_img = str_replace('https://http://', 'https://', $scraped_img);
                        
                        // Handle relative URLs
                        if (strpos($scraped_img, 'http') !== 0) {
                            $parsed_url = parse_url($media_url);
                            $scheme = isset($parsed_url['scheme']) ? $parsed_url['scheme'] : 'https';
                            $host = isset($parsed_url['host']) ? $parsed_url['host'] : '';
                            if (strpos($scraped_img, '//') === 0) {
                                $scraped_img = $scheme . ':' . $scraped_img;
                            } else if (strpos($scraped_img, '/') === 0) {
                                $scraped_img = $scheme . '://' . $host . $scraped_img;
                            } else {
                                $scraped_img = $scheme . '://' . $host . '/' . $scraped_img;
                            }
                        }

                        if (filter_var($scraped_img, FILTER_VALIDATE_URL)) {
                            set_transient($cache_key, $scraped_img, 24 * HOUR_IN_SECONDS);
                            return $scraped_img;
                        }
                    }
                }
                // Cache the 'none' result so we don't fetch it every time
                set_transient($cache_key, 'none', 24 * HOUR_IN_SECONDS);
            }
        }

        // 3. Absolute fallback image
        return $fallback_img;
    }
}

// Bulk update WooCommerce downloadable files
add_action('init', 'cmr_bulk_update_woo_downloads');
function cmr_bulk_update_woo_downloads() {
    if (isset($_GET['cmr_update_reports']) && current_user_can('manage_options')) {
        if (!function_exists('wc_get_products')) { die('WooCommerce not active.'); }
        $products = wc_get_products(['limit' => -1]);
        $count = 0;
        $base_url = isset($_GET['base_url']) ? esc_url_raw($_GET['base_url']) : 'https://cmrindia.com/report/';
        foreach ($products as $product) {
            $slug = $product->get_slug();
            // Assumes file is named as {product_slug}.pdf in the /report/ directory
            $file_url = rtrim($base_url, '/') . '/' . $slug . '.pdf';
            
            $download_id = md5($file_url);
            $file = new WC_Product_Download();
            $file->set_id($download_id);
            $file->set_name($product->get_title());
            $file->set_file($file_url);
            
            $downloads = []; 
            $downloads[$download_id] = $file;
            
            $product->set_downloads($downloads);
            $product->set_downloadable(true);
            $product->set_virtual(true);
            
            $product->save();
            $count++;
        }
        die("Successfully updated $count WooCommerce products. Used base URL: " . esc_html($base_url));
    }
}

// Bulk delete duplicate WooCommerce products
add_action('init', 'cmr_bulk_delete_duplicate_woo_products');
function cmr_bulk_delete_duplicate_woo_products() {
    if (isset($_GET['cmr_delete_duplicates']) && current_user_can('manage_options')) {
        if (!function_exists('wc_get_products')) { die('WooCommerce not active.'); }
        
        // Get all products from oldest to newest
        $products = wc_get_products([
            'limit' => -1,
            'status' => ['publish', 'draft', 'pending', 'private'],
            'orderby' => 'date',
            'order' => 'ASC'
        ]);
        
        $seen_titles = [];
        $deleted_count = 0;
        
        foreach ($products as $product) {
            // Normalize title for matching
            $title = trim(strtolower($product->get_name()));
            
            // Clean up trailing '- copy' or '- 1' sometimes added by duplicators
            $title = preg_replace('/ - copy( \d+)?$/i', '', $title);
            
            if (empty($title)) continue;
            
            if (isset($seen_titles[$title])) {
                // This is a duplicate, move it to trash
                wp_trash_post($product->get_id());
                $deleted_count++;
            } else {
                // First time seeing this title (the original/oldest)
                $seen_titles[$title] = true;
            }
        }
        
        die("Successfully moved $deleted_count duplicate WooCommerce products to the Trash (kept the oldest original for each title).");
    }
}

// Bulk replace domain in WooCommerce downloadable files
add_action('init', 'cmr_bulk_replace_domain_woo_downloads');
function cmr_bulk_replace_domain_woo_downloads() {
    if (isset($_GET['cmr_fix_domains']) && current_user_can('manage_options')) {
        if (!function_exists('wc_get_products')) { die('WooCommerce not active.'); }
        
        $products = wc_get_products(['limit' => -1, 'status' => 'any']);
        $count = 0;
        
        foreach ($products as $product) {
            $downloads = $product->get_downloads();
            $updated = false;
            
            if ($downloads) {
                foreach ($downloads as $download_id => $file) {
                    $old_url = $file->get_file();
                    if (strpos($old_url, 'qai8358l95-staging.onrocket.site') !== false) {
                        $new_url = str_replace(
                            ['https://qai8358l95-staging.onrocket.site', 'http://qai8358l95-staging.onrocket.site'], 
                            'https://cmrindia.com', 
                            $old_url
                        );
                        $file->set_file($new_url);
                        $updated = true;
                    }
                }
                
                if ($updated) {
                    $product->set_downloads($downloads);
                    $product->save();
                    $count++;
                }
            }
        }
        
        die("Successfully updated domains from staging to cmrindia.com in $count WooCommerce products.");
    }
}

// Custom WooCommerce Checkout Fields
add_filter('woocommerce_checkout_fields', 'cmr_custom_checkout_fields');
function cmr_custom_checkout_fields($fields) {
    // Modify Billing Fields
    $fields['billing']['billing_first_name']['placeholder'] = 'First';
    $fields['billing']['billing_first_name']['label'] = 'NAME';
    $fields['billing']['billing_first_name']['class'] = array('form-row-first');
    
    $fields['billing']['billing_last_name']['placeholder'] = 'Last';
    $fields['billing']['billing_last_name']['label'] = ''; // Hide label to align with First Name
    $fields['billing']['billing_last_name']['class'] = array('form-row-last');
    
    $fields['billing']['billing_address_1']['placeholder'] = 'Street address, P.O. box, company name';
    $fields['billing']['billing_address_1']['label'] = 'ADDRESS';
    $fields['billing']['billing_address_1']['class'] = array('form-row-wide');
    
    // Remove Address 2 and Company
    unset($fields['billing']['billing_address_2']);
    unset($fields['billing']['billing_company']);
    
    $fields['billing']['billing_city']['placeholder'] = 'City';
    $fields['billing']['billing_city']['label'] = 'LOCATION';
    $fields['billing']['billing_city']['class'] = array('form-row-first');
    
    $fields['billing']['billing_state']['placeholder'] = 'State';
    $fields['billing']['billing_state']['label'] = ''; // Hide label
    $fields['billing']['billing_state']['class'] = array('form-row-last');
    
    $fields['billing']['billing_country']['placeholder'] = 'Country';
    $fields['billing']['billing_country']['label'] = ''; // Hide label
    $fields['billing']['billing_country']['class'] = array('form-row-first');
    
    $fields['billing']['billing_postcode']['placeholder'] = 'Pincode';
    $fields['billing']['billing_postcode']['label'] = ''; // Hide label
    $fields['billing']['billing_postcode']['class'] = array('form-row-last');
    
    $fields['billing']['billing_email']['placeholder'] = 'alexander@botanical.com';
    $fields['billing']['billing_email']['label'] = 'EMAIL ADDRESS';
    $fields['billing']['billing_email']['class'] = array('form-row-wide');
    
    $fields['billing']['billing_phone']['placeholder'] = '00000-00000';
    $fields['billing']['billing_phone']['label'] = 'PHONE NUMBER';
    $fields['billing']['billing_phone']['class'] = array('form-row-wide');

    return $fields;
}

// Override locale JS rules so WooCommerce address-i18n script doesn't overwrite our labels or layout
add_filter('woocommerce_get_country_locale', 'cmr_override_country_locale');
function cmr_override_country_locale($locales) {
    foreach ($locales as $country => $fields) {
        if (isset($locales[$country]['address_1'])) {
            $locales[$country]['address_1']['label'] = 'ADDRESS';
            $locales[$country]['address_1']['priority'] = 30;
        }
        if (isset($locales[$country]['city'])) {
            $locales[$country]['city']['label'] = 'LOCATION';
            $locales[$country]['city']['priority'] = 40;
        }
        if (isset($locales[$country]['state'])) {
            $locales[$country]['state']['label'] = '';
            $locales[$country]['state']['priority'] = 50;
        }
        if (isset($locales[$country]['postcode'])) {
            $locales[$country]['postcode']['label'] = '';
            $locales[$country]['postcode']['priority'] = 70;
        }
    }
    return $locales;
}

// Change "Place Order" button text and HTML
add_filter('woocommerce_order_button_text', 'cmr_custom_order_button_text');
function cmr_custom_order_button_text() {
    return 'Proceed to Checkout'; 
}

add_filter('woocommerce_order_button_html', 'cmr_custom_order_button_html');
function cmr_custom_order_button_html($button) {
    return '<button type="submit" class="button alt cmr-place-order-btn" name="woocommerce_checkout_place_order" id="place_order" value="Proceed to Checkout" data-value="Proceed to Checkout"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px; vertical-align:middle;"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg> Proceed to Checkout</button>';
}

// Force shipping to billing address only (removes shipping fields)
add_filter('wc_ship_to_billing_address_only', '__return_true');

// Redirect to cart immediately after adding to cart
add_filter('woocommerce_add_to_cart_redirect', 'cmr_redirect_to_cart_after_add');
function cmr_redirect_to_cart_after_add() {
    return wc_get_cart_url();
}

// Enqueue custom checkout CSS with cache busting
add_action('wp_enqueue_scripts', 'cmr_enqueue_checkout_css', 99);
function cmr_enqueue_checkout_css() {
    if (is_checkout() || is_cart()) {
        wp_enqueue_style('cmr-custom-checkout', get_template_directory_uri() . '/assets/css/custom-checkout.css', array(), time());
    }
}


// Redirect shop page to research reports page
add_action( 'template_redirect', 'quanto_redirect_shop_to_research_reports' );
function quanto_redirect_shop_to_research_reports() {
    if ( function_exists('is_shop') && is_shop() && ! is_search() ) {
        wp_safe_redirect( home_url( '/research-reports/' ) );
        exit;
    }
}

// Force Elementor to regenerate the CSS files for our global shortcode tabs 
// whenever Elementor clears its cache, so they are never missing on the frontend.
// CRITICAL: Also delete the footer HTML transient so the footer shortcode
// re-fetches from ?quanto_footer=main on the next page load, which triggers
// Elementor to regenerate the CSS files as a side effect.
add_action( 'elementor/core/files/clear_cache', 'cmr_regenerate_tab_css_on_cache_clear' );
function cmr_regenerate_tab_css_on_cache_clear() {
    // Delete all component HTML caches so they get re-fetched with fresh CSS
    delete_transient( 'cmr_footer_html_cache' );
    delete_transient( 'cmr_connect_cache' );
    delete_transient( 'cmr_gtm_cache' );
    delete_transient( 'cmr_global_cta_banner_cache' );
    
    $tab_slugs = array(
        'your-challenge-our-research-your-advantage',
        'fotter-card',
        'testimonials',
        'we-worked-with-largest-global-brands',
        'your-next-big-decision-deserves-better-intelligence',
        'cmr-connect',
        'cmr-gtm',
        'global-cta-banner',
    );
    foreach ( $tab_slugs as $slug ) {
        $tab_posts = get_posts(array(
            'name'           => $slug,
            'post_type'      => 'quanto_tab_build',
            'posts_per_page' => 1,
            'post_status'    => 'publish',
        ));
        if ( $tab_posts && !empty($tab_posts[0]) ) {
            $post_id = $tab_posts[0]->ID;
            if ( class_exists( '\\Elementor\\Core\\Files\\CSS\\Post' ) && class_exists( '\\Elementor\\Plugin' ) ) {
                $document = \Elementor\Plugin::$instance->documents->get( $post_id );
                if ( $document ) {
                    $css_file = new \Elementor\Core\Files\CSS\Post( $post_id );
                    $css_file->update();
                }
            }
        }
    }
}

// Delete all component transients whenever post cache or object cache is flushed
add_action( 'wp_cache_flush', function() {
    delete_transient( 'cmr_footer_html_cache' );
    delete_transient( 'cmr_connect_cache' );
    delete_transient( 'cmr_gtm_cache' );
    delete_transient( 'cmr_global_cta_banner_cache' );
});

// Delete component transients when any quanto_footer post is saved
add_action( 'save_post_quanto_footer', function() {
    delete_transient( 'cmr_footer_html_cache' );
});

// Delete component transients when any quanto_tab_build post is saved
add_action( 'save_post_quanto_tab_build', function() {
    delete_transient( 'cmr_footer_html_cache' );
    delete_transient( 'cmr_connect_cache' );
    delete_transient( 'cmr_gtm_cache' );
    delete_transient( 'cmr_global_cta_banner_cache' );
});

/**
 * Pre-warm Elementor CSS & HTML caches by generating disk CSS files, backing them up in DB,
 * and hitting template URLs so that CSS never breaks at night or in the morning.
 */
function cmr_prewarm_elementor_caches() {
    $target_slugs = array(
        'cmr-gtm',
        'cmr-connect',
        'global-cta-banner',
        'similar-reports-by-industry',
        'your-next-big-decision-deserves-better-intelligence',
        'we-worked-with-largest-global-brands',
        'testimonials',
        'fotter-card',
        'your-challenge-our-research-your-advantage',
        'who-we-serve',
    );

    $upload_dir = wp_upload_dir();
    $elementor_css_dir = ! empty( $upload_dir['basedir'] ) ? trailingslashit( $upload_dir['basedir'] ) . 'elementor/css/' : '';

    // 1. Process Footer Template (post 13499)
    $footer_id = function_exists( 'quanto_get_resolved_footer_id' ) ? quanto_get_resolved_footer_id() : 13499;
    if ( $footer_id && class_exists( '\\Elementor\\Plugin' ) && isset( \Elementor\Plugin::$instance->frontend ) ) {
        try {
            \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $footer_id, true );
            if ( class_exists( '\\Elementor\\Core\\Files\\CSS\\Post' ) ) {
                $css_file = \Elementor\Core\Files\CSS\Post::create( $footer_id );
                $css_file->update();
            }
            if ( ! empty( $elementor_css_dir ) ) {
                $f_post_css = $elementor_css_dir . 'post-' . $footer_id . '.css';
                if ( file_exists( $f_post_css ) ) {
                    update_option( 'cmr_css_backup_' . $footer_id . '_post', @file_get_contents( $f_post_css ), false );
                }
                foreach ( array( 'desktop', 'mobile', 'tablet', 'laptop' ) as $dev ) {
                    $f_local = $elementor_css_dir . 'local-' . $footer_id . '-frontend-' . $dev . '.css';
                    if ( file_exists( $f_local ) ) {
                        update_option( 'cmr_css_backup_' . $footer_id . '_' . $dev, @file_get_contents( $f_local ), false );
                    }
                }
            }
        } catch ( Exception $e ) {}
    }

    // 2. Process All 10 Tab Builder Templates in-process
    foreach ( $target_slugs as $slug ) {
        $tab_posts = get_posts( array(
            'name'           => $slug,
            'post_type'      => 'quanto_tab_build',
            'posts_per_page' => 1,
            'post_status'    => 'publish',
        ) );

        if ( ! empty( $tab_posts[0] ) ) {
            $pid = $tab_posts[0]->ID;
            try {
                if ( class_exists( '\\Elementor\\Plugin' ) && isset( \Elementor\Plugin::$instance->frontend ) ) {
                    \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $pid, true );
                    if ( class_exists( '\\Elementor\\Core\\Files\\CSS\\Post' ) ) {
                        $css_file = \Elementor\Core\Files\CSS\Post::create( $pid );
                        $css_file->update();
                    }
                }
                if ( ! empty( $elementor_css_dir ) ) {
                    $post_css_file = $elementor_css_dir . 'post-' . $pid . '.css';
                    if ( file_exists( $post_css_file ) ) {
                        update_option( 'cmr_css_backup_' . $pid . '_post', @file_get_contents( $post_css_file ), false );
                    }
                    foreach ( array( 'desktop', 'mobile', 'tablet', 'laptop' ) as $dev ) {
                        $loc_file = $elementor_css_dir . 'local-' . $pid . '-frontend-' . $dev . '.css';
                        if ( file_exists( $loc_file ) ) {
                            update_option( 'cmr_css_backup_' . $pid . '_' . $dev, @file_get_contents( $loc_file ), false );
                        }
                    }
                }
            } catch ( Exception $e ) {}
        }
    }

    // 3. Clear component HTML caches so they immediately regenerate with fresh CSS
    delete_transient( 'cmr_footer_html_cache' );
    delete_transient( 'cmr_connect_cache' );
    delete_transient( 'cmr_gtm_cache' );
    delete_transient( 'cmr_global_cta_banner_cache' );

    // 4. Background HTTP requests to warm edge caches
    $urls = array(
        home_url( '/' ),
        home_url( '/?quanto_footer=main' ),
        home_url( '/?quanto_tab_build=cmr-gtm' ),
        home_url( '/?quanto_tab_build=cmr-connect' ),
        home_url( '/?quanto_tab_build=global-cta-banner' ),
        home_url( '/?quanto_tab_build=similar-reports-by-industry' ),
        home_url( '/?quanto_tab_build=your-next-big-decision-deserves-better-intelligence' ),
        home_url( '/?quanto_tab_build=we-worked-with-largest-global-brands' ),
        home_url( '/?quanto_tab_build=testimonials' ),
        home_url( '/?quanto_tab_build=fotter-card' ),
        home_url( '/?quanto_tab_build=your-challenge-our-research-your-advantage' ),
        home_url( '/?quanto_tab_build=who-we-serve' ),
    );

    foreach ( array_unique( $urls ) as $url ) {
        wp_remote_get( $url, array(
            'timeout'   => 5,
            'blocking'  => false,
            'sslverify' => false,
            'headers'   => array( 'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36' ),
        ) );
    }
}

// Trigger pre-warming when Elementor cache is cleared
add_action( 'elementor/core/files/clear_cache', 'cmr_prewarm_elementor_caches' );

// Trigger pre-warming when a new user registers (WordPress & WooCommerce)
add_action( 'user_register', 'cmr_prewarm_elementor_caches' );
add_action( 'woocommerce_created_customer', 'cmr_prewarm_elementor_caches' );

// Trigger pre-warming when any user logs in (or re-logs in)
add_action( 'wp_login', 'cmr_prewarm_elementor_caches' );

// Trigger pre-warming when any builder template or page is saved/published
$all_builder_types = array( 'quanto_tab_build', 'quanto_footer', 'quanto_header', 'quanto_off_canvas', 'quanto_offcanvas', 'quanto_archive', 'quanto_builder', 'quanto_single', 'page' );
foreach ( $all_builder_types as $btype ) {
    add_action( 'save_post_' . $btype, 'cmr_prewarm_elementor_caches' );
    add_action( 'publish_' . $btype, 'cmr_prewarm_elementor_caches' );
}

// Trigger pre-warming when any WordPress cache is flushed
add_action( 'wp_cache_flush', 'cmr_prewarm_elementor_caches' );

// Custom 3-Hour cron interval schedule
add_filter( 'cron_schedules', function( $schedules ) {
    if ( ! isset( $schedules['every_three_hours'] ) ) {
        $schedules['every_three_hours'] = array(
            'interval' => 3 * HOUR_IN_SECONDS,
            'display'  => 'Every 3 Hours',
        );
    }
    return $schedules;
} );

// Scheduled 3-Hour Cache Pre-warming (Internal server cron)
if ( ! wp_next_scheduled( 'cmr_scheduled_cache_prewarm_3h' ) ) {
    wp_schedule_event( time(), 'every_three_hours', 'cmr_scheduled_cache_prewarm_3h' );
}
add_action( 'cmr_scheduled_cache_prewarm_3h', 'cmr_prewarm_elementor_caches' );

// Also keep hourly fallback check
if ( ! wp_next_scheduled( 'cmr_scheduled_cache_prewarm' ) ) {
    wp_schedule_event( time(), 'hourly', 'cmr_scheduled_cache_prewarm' );
}
add_action( 'cmr_scheduled_cache_prewarm', 'cmr_prewarm_elementor_caches' );

// =========================================================================
// 100% Automatic Server Pre-warm (Runs every 3 hours automatically on server)
// Requires ZERO manual cPanel setup and ZERO external cron configuration!
// =========================================================================
add_action( 'init', function() {
    // Avoid running inside AJAX or REST requests
    if ( ( defined( 'DOING_AJAX' ) && DOING_AJAX ) || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
        return;
    }

    // Check if 3 hours have elapsed since last automatic prewarm
    if ( false === get_transient( 'cmr_auto_prewarm_3h_timer' ) ) {
        // Set transient lock for 3 hours
        set_transient( 'cmr_auto_prewarm_3h_timer', time(), 3 * HOUR_IN_SECONDS );

        // Run non-blocking on shutdown so visitor page speed is NEVER slowed down
        add_action( 'shutdown', function() {
            if ( function_exists( 'fastcgi_finish_request' ) ) {
                @fastcgi_finish_request(); // Flushes all HTML to visitor immediately
            }
            if ( function_exists( 'cmr_prewarm_elementor_caches' ) ) {
                cmr_prewarm_elementor_caches();
            }
            $log_file = get_template_directory() . '/prewarm-cron.log';
            $log_entry = sprintf( "[%s] Auto 3-Hour Server Prewarm Executed (Zero-config)\n", date( 'Y-m-d H:i:s' ) );
            @file_put_contents( $log_file, $log_entry, FILE_APPEND | LOCK_EX );
        } );
    }
} );

// Direct webhook / URL trigger to pre-warm on demand: https://cmrindia.com/?cmr_prewarm_now=1&key=cmr_secure_2026
add_action( 'init', function() {
    if ( isset( $_GET['cmr_prewarm_now'] ) ) {
        $key = isset( $_GET['key'] ) ? sanitize_text_field( $_GET['key'] ) : '';
        if ( current_user_can( 'manage_options' ) || $key === 'cmr_secure_2026' ) {
            cmr_prewarm_elementor_caches();
            wp_send_json_success( array(
                'message'   => 'CMR Elementor Caches Successfully Prewarmed & Backed Up!',
                'timestamp' => current_time( 'mysql' ),
                'slugs'     => 11,
            ) );
        }
    }
} );

// Detect Crawlers & Bots at night/day: ensures CSS is pre-warmed before rendering
add_action( 'init', function() {
    if ( is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) || ( defined( 'DOING_CRON' ) && DOING_CRON ) ) {
        return;
    }

    $ua = isset( $_SERVER['HTTP_USER_AGENT'] ) ? strtolower( $_SERVER['HTTP_USER_AGENT'] ) : '';
    if ( empty( $ua ) ) {
        return;
    }

    $crawler_patterns = array(
        'bot', 'crawl', 'spider', 'slurp', 'google', 'bing', 'yandex',
        'duckduck', 'baidu', 'facebookexternalhit', 'twitterbot',
        'semrush', 'ahrefs', 'petalbot', 'rogerbot', 'dotbot'
    );

    $is_crawler = false;
    foreach ( $crawler_patterns as $pattern ) {
        if ( strpos( $ua, $pattern ) !== false ) {
            $is_crawler = true;
            break;
        }
    }

    if ( $is_crawler ) {
        // Throttled: Run at most once every 30 minutes to avoid overhead under heavy spidering
        if ( false === get_transient( 'cmr_crawler_prewarm_throttle' ) ) {
            set_transient( 'cmr_crawler_prewarm_throttle', 1, 30 * MINUTE_IN_SECONDS );
            cmr_prewarm_elementor_caches();
        }
    }
} );

/**
 * Remove <br> tags from WooCommerce My Account navigation link labels via filter.
 */
add_filter( 'woocommerce_account_menu_items', function( $items ) {
    foreach ( $items as $endpoint => $label ) {
        // Strip out any <br>, <br/>, or <br /> tags from the label text
        $items[$endpoint] = str_ireplace( array( '<br>', '<br/>', '<br />', "\n", "\r", "\t" ), '', $label );
        // Also trim extra whitespace that might have been left
        $items[$endpoint] = trim( $items[$endpoint] );
    }
    return $items;
}, 999 );

/**
 * If the <br> tag or bullet points are injected by Elementor/theme CSS into WooCommerce My Account navigation,
 * this CSS will force them to be hidden in frontend and Elementor preview.
 */
add_action( 'wp_head', 'cmr_woocommerce_myaccount_css_fix', 999 );
add_action( 'admin_head', 'cmr_woocommerce_myaccount_css_fix', 999 );
add_action( 'elementor/editor/after_enqueue_styles', 'cmr_woocommerce_myaccount_css_fix', 999 );
function cmr_woocommerce_myaccount_css_fix() {
    ?>
    <style id="cmr-myaccount-bullet-fix">
        .woocommerce-MyAccount-navigation ul,
        .woocommerce-MyAccount-navigation ul:not(.custom-ul),
        .woocommerce-account .woocommerce-MyAccount-navigation ul,
        .woocommerce-account .woocommerce-MyAccount-navigation ul:not(.custom-ul),
        .elementor-widget-text-editor .woocommerce-MyAccount-navigation ul,
        .elementor-widget-text-editor .woocommerce-MyAccount-navigation ul:not(.custom-ul),
        .elementor-widget-text-editor .woocommerce-account .woocommerce-MyAccount-navigation ul,
        .page--content .woocommerce-MyAccount-navigation ul,
        .entry-content .woocommerce-MyAccount-navigation ul {
            list-style: none !important;
            list-style-type: none !important;
            padding-left: 0 !important;
            margin-left: 0 !important;
            margin-top: 0 !important;
            margin-bottom: 0 !important;
        }

        .woocommerce-MyAccount-navigation ul li,
        .woocommerce-MyAccount-navigation ul:not(.custom-ul) li,
        .woocommerce-account .woocommerce-MyAccount-navigation ul li,
        .woocommerce-account .woocommerce-MyAccount-navigation ul:not(.custom-ul) li,
        .elementor-widget-text-editor .woocommerce-MyAccount-navigation ul li,
        .elementor-widget-text-editor .woocommerce-MyAccount-navigation ul:not(.custom-ul) li,
        .elementor-widget-text-editor .woocommerce-account .woocommerce-MyAccount-navigation ul li,
        .page--content .woocommerce-MyAccount-navigation ul li,
        .entry-content .woocommerce-MyAccount-navigation ul li,
        li.woocommerce-MyAccount-navigation-link {
            list-style: none !important;
            list-style-type: none !important;
            display: block !important;
            padding-left: 0 !important;
            margin-left: 0 !important;
            margin-top: 0 !important;
            margin-bottom: 12px !important;
        }

        .woocommerce-MyAccount-navigation ul li::before,
        .woocommerce-MyAccount-navigation ul li::after,
        .woocommerce-MyAccount-navigation ul:not(.custom-ul) li::before,
        .woocommerce-MyAccount-navigation ul:not(.custom-ul) li::after,
        .woocommerce-account .woocommerce-MyAccount-navigation ul li::before,
        .woocommerce-account .woocommerce-MyAccount-navigation ul li::after,
        .elementor-widget-text-editor .woocommerce-MyAccount-navigation ul li::before,
        .elementor-widget-text-editor .woocommerce-MyAccount-navigation ul li::after,
        .elementor-widget-text-editor .woocommerce-account .woocommerce-MyAccount-navigation ul li::before,
        .elementor-widget-text-editor .woocommerce-account .woocommerce-MyAccount-navigation ul li::after,
        .page--content .woocommerce-MyAccount-navigation ul > li::before,
        .page--content .woocommerce-MyAccount-navigation ul > li::after,
        .entry-content .woocommerce-MyAccount-navigation ul > li::before,
        .entry-content .woocommerce-MyAccount-navigation ul > li::after,
        li.woocommerce-MyAccount-navigation-link::before,
        li.woocommerce-MyAccount-navigation-link::after,
        li.woocommerce-MyAccount-navigation-link a::before,
        li.woocommerce-MyAccount-navigation-link a::after {
            display: none !important;
            content: none !important;
        }

        .woocommerce-MyAccount-navigation-link a br {
            display: none !important;
        }

        .woocommerce-MyAccount-navigation ul li a,
        li.woocommerce-MyAccount-navigation-link a {
            display: inline-block !important;
            text-decoration: none !important;
            font-size: 16px !important;
            line-height: 1.6 !important;
            color: #0F0F0F !important;
            font-weight: 500 !important;
            transition: color 0.2s ease !important;
        }

        .woocommerce-MyAccount-navigation ul li a:hover,
        li.woocommerce-MyAccount-navigation-link a:hover,
        li.woocommerce-MyAccount-navigation-link.is-active a {
            color: #4820B0 !important;
            font-weight: 600 !important;
        }
    </style>
    <?php
}

/**
/**
 * Redirect to requested page (e.g. gated table page) after login if redirect_to is set,
 * or fallback to cart page if the user has items in their cart.
 */
function cmr_redirect_after_login( $redirect, $user = null ) {
    if ( ! empty( $_REQUEST['redirect_to'] ) ) {
        return wp_validate_redirect( $_REQUEST['redirect_to'], $redirect );
    }
    if ( ! empty( $_POST['redirect'] ) ) {
        return wp_validate_redirect( $_POST['redirect'], $redirect );
    }
    if ( ! empty( $_GET['redirect_to'] ) ) {
        return wp_validate_redirect( $_GET['redirect_to'], $redirect );
    }

    if ( class_exists( 'WooCommerce' ) && WC()->cart && ! WC()->cart->is_empty() ) {
        return wc_get_cart_url();
    }
    return $redirect;
}
add_filter( 'woocommerce_registration_redirect', 'cmr_redirect_after_login', 99, 2 );
add_filter( 'woocommerce_login_redirect', 'cmr_redirect_after_login', 99, 2 );
add_filter( 'login_redirect', 'cmr_redirect_after_login', 99, 3 );

// Pass redirect_to into hidden input fields in WooCommerce login form
add_action( 'woocommerce_login_form', 'cmr_inject_redirect_to_hidden_fields' );
function cmr_inject_redirect_to_hidden_fields() {
    if ( ! empty( $_GET['redirect_to'] ) ) {
        $redirect_to = esc_url( $_GET['redirect_to'] );
        echo '<input type="hidden" name="redirect" value="' . $redirect_to . '" />';
        echo '<input type="hidden" name="redirect_to" value="' . $redirect_to . '" />';
    }
}

// Hide WooCommerce registration/signup column when coming from a gated page with redirect_to
add_action( 'wp_head', function() {
    if ( is_account_page() && ( ! empty( $_GET['redirect_to'] ) || isset( $_GET['hide_register'] ) ) ) {
        ?>
        <style id="cmr-hide-gated-registration">
            /* Hide Register/Signup column for gated table visitors */
            #customer_login .u-column2,
            .woocommerce-form-register,
            .woocommerce-FormRow--register,
            .col-2.u-column2 {
                display: none !important;
            }
            #customer_login .u-column1,
            .col-1.u-column1 {
                width: 100% !important;
                max-width: 480px !important;
                margin: 0 auto !important;
                float: none !important;
            }
            #customer_login {
                display: block !important;
            }
        </style>
        <?php
    }
} );

/**
 * Fallback: If a user logs in and lands on My Account with redirect_to set,
 * redirect them to their destination page.
 */
add_action( 'template_redirect', 'cmr_force_cart_redirect_after_registration' );
function cmr_force_cart_redirect_after_registration() {
    if ( is_user_logged_in() && is_account_page() && ! empty( $_GET['redirect_to'] ) ) {
        $target = wp_validate_redirect( $_GET['redirect_to'], '' );
        if ( $target ) {
            wp_safe_redirect( $target );
            exit;
        }
    }

    if ( is_account_page() && isset( $_GET['register'] ) && $_GET['register'] === 'success' ) {
        if ( class_exists( 'WooCommerce' ) && WC()->cart && ! WC()->cart->is_empty() ) {
            wp_safe_redirect( wc_get_cart_url() );
            exit;
        }
    }
}

// Include Nav Search Popup
require_once QUANTO_DIR_PATH_INC . 'cmr-nav-search-popup.php';

// Include Nav Cart Icon
require_once QUANTO_DIR_PATH_INC . 'cmr-nav-cart-icon.php';



// Modify Search Query to load 30 items at once for the "Load More" functionality
function cmr_modify_search_query($query) {
    if ( ! is_admin() && $query->is_main_query() && $query->is_search() ) {
        $query->set( 'posts_per_page', 30 );
    }
}
add_action( 'pre_get_posts', 'cmr_modify_search_query' );

// Set 9 posts per page & handle year filter on Category Archives
function cmr_category_posts_per_page($query) {
    if ( ! is_admin() && $query->is_main_query() && ( $query->is_category() || $query->is_archive() ) ) {
        if ( $query->is_category() ) {
            $query->set( 'post_type', 'post' );
        }
        $query->set( 'posts_per_page', 9 );

        $selected_year = 0;
        if ( ! empty( $_GET['y'] ) ) {
            $selected_year = intval( $_GET['y'] );
        } elseif ( ! empty( $_GET['year'] ) ) {
            $selected_year = intval( $_GET['year'] );
        }

        if ( $selected_year > 0 ) {
            $query->set( 'year', $selected_year );
            $query->set( 'date_query', array(
                array(
                    'year' => $selected_year,
                ),
            ) );
        }
    }
}
add_action( 'pre_get_posts', 'cmr_category_posts_per_page' );

// Include cmr_news in Tag archive queries only (not category archives to avoid duplicate post/clone entries)
function cmr_include_news_in_taxonomies( $query ) {
    if ( ! is_admin() && $query->is_main_query() && $query->is_tag() ) {
        $post_types = $query->get( 'post_type' );
        if ( empty( $post_types ) || $post_types === 'post' ) {
            $query->set( 'post_type', array( 'post', 'cmr_news' ) );
        } elseif ( is_array( $post_types ) && ! in_array( 'cmr_news', $post_types ) ) {
            $post_types[] = 'cmr_news';
            $query->set( 'post_type', $post_types );
        }
    }
}
add_action( 'pre_get_posts', 'cmr_include_news_in_taxonomies' );

// Shortcode for Account Icon
function cmr_account_icon_shortcode() {
    static $css_rendered = false;
    $url = home_url('/my-account/');
    $icon_html = '';
    
    if ( is_user_logged_in() ) {
        $current_user = wp_get_current_user();
        $name = $current_user->display_name;
        if ( empty( trim( $name ) ) ) {
            $name = $current_user->user_login;
        }
        $initial = mb_substr( trim( $name ), 0, 1 );
        
        $icon_html = '<div class="cmr-account-initial" style="width:30px; height:30px; min-width:30px; min-height:30px; border-radius:50%; background:#00bfbc; color:#fff; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:14px; line-height:1; margin:0 auto;">' . esc_html( strtoupper( $initial ) ) . '</div>';
    } else {
        $icon_html = '<i class="ri-user-line" style="font-size:22px;"></i>';
    }
    
    ob_start();
    if ( ! $css_rendered ) {
        $css_rendered = true;
        ?>
        <style>
        .cmr-account-link {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            text-decoration: none;
            color: #333; /* Default black for inner/blog headers */
            transition: color 0.3s ease;
            width: 40px !important;
            height: 40px !important;
            min-width: 40px !important;
            min-height: 40px !important;
            padding: 0 !important;
            margin: 0 !important;
            vertical-align: middle !important;
            box-sizing: border-box !important;
            line-height: 1 !important;
        }

        /* Make login face white ONLY on main header / home page */
        body.home .cmr-account-link,
        body.home-page .cmr-account-link,
        #quanto-header-desktop .cmr-account-link,
        .main-header-wrapper .cmr-account-link {
            color: #fff;
        }

        /* Keep black on mobile / blog header */
        #quanto-header-mobile .cmr-account-link,
        .blog-header-wrapper .cmr-account-link {
            color: #333;
        }

        .cmr-account-link:hover {
            color: #4820B0 !important;
        }

        /* Force black when header is sticky */
        .elementor-sticky--effects .cmr-account-link,
        .is-sticky .cmr-account-link,
        header.sticky .cmr-account-link,
        .intel-nav-fixed-js .cmr-account-link {
            color: #333 !important;
        }
        </style>
        <?php
    }
    echo '<a href="' . esc_url( $url ) . '" class="cmr-account-link cmr-has-tooltip" aria-label="Profile">' . $icon_html . '<span class="cmr-header-tooltip">Profile</span></a>';
    return ob_get_clean();
}
add_shortcode( 'cmr_account_icon', 'cmr_account_icon_shortcode' );

// Auto-inject header tooltips (Search, Cart, Profile) and align items perfectly
function cmr_header_action_tooltips_script() {
    ?>
    <script>
    (function() {
        function setupCmrHeaderTooltips() {
            var items = [
                { selector: '.cmr-nav-search-container, .cmr-nav-search-trigger', text: 'Search' },
                { selector: '.cmr-nav-cart-container, .cmr-nav-cart-container-black', text: 'Cart' },
                { selector: '.cmr-account-link', text: 'Profile' }
            ];
            items.forEach(function(item) {
                var els = document.querySelectorAll(item.selector);
                els.forEach(function(el) {
                    var container = el.classList.contains('cmr-nav-search-trigger') ? (el.closest('.cmr-nav-search-container') || el) : el;
                    container.classList.add('cmr-has-tooltip');
                    if (!container.querySelector('.cmr-header-tooltip')) {
                        var span = document.createElement('span');
                        span.className = 'cmr-header-tooltip';
                        span.textContent = item.text;
                        container.appendChild(span);
                    }
                });
            });

            // Ensure parent Elementor widgets and containers are vertically centered
            var actionSelectors = ['.cmr-nav-search-container', '.cmr-nav-cart-container', '.cmr-nav-cart-container-black', '.cmr-account-link'];
            actionSelectors.forEach(function(sel) {
                document.querySelectorAll(sel).forEach(function(node) {
                    var widget = node.closest('.elementor-widget');
                    if (widget) {
                        widget.style.setProperty('display', 'inline-flex', 'important');
                        widget.style.setProperty('align-items', 'center', 'important');
                        widget.style.setProperty('justify-content', 'center', 'important');
                        widget.style.setProperty('vertical-align', 'middle', 'important');
                        widget.style.setProperty('margin-bottom', '0px', 'important');
                    }
                    var container = node.closest('.elementor-widget-container');
                    if (container) {
                        container.style.setProperty('display', 'inline-flex', 'important');
                        container.style.setProperty('align-items', 'center', 'important');
                        container.style.setProperty('justify-content', 'center', 'important');
                        container.style.setProperty('vertical-align', 'middle', 'important');
                    }
                });
            });
        }
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', setupCmrHeaderTooltips);
        } else {
            setupCmrHeaderTooltips();
        }
        setTimeout(setupCmrHeaderTooltips, 500);
        setTimeout(setupCmrHeaderTooltips, 1500);
    })();
    </script>
    <?php
}
add_action( 'wp_footer', 'cmr_header_action_tooltips_script', 999 );

require_once get_template_directory() . '/inc/cmr-product-card.php';


// Ensure Elementor CSS is loaded on single product pages since we use Elementor templates there
add_action('wp_enqueue_scripts', function() {
    if ( is_product() && class_exists( '\Elementor\Plugin' ) ) {
        \Elementor\Plugin::instance()->frontend->enqueue_styles();
        // Also enqueue the global kit CSS if possible
        $kit_id = get_option( 'elementor_active_kit' );
        if ( $kit_id ) {
            wp_enqueue_style( 'elementor-post-' . $kit_id, wp_upload_dir()['baseurl'] . '/elementor/css/post-' . $kit_id . '.css' );
        }
        
        // Enqueue Similar Reports Post CSS
        $posts = get_posts(array(
            'name' => 'similar-reports-by-industry',
            'post_type' => 'quanto_tab_build',
            'posts_per_page' => 1,
            'post_status' => 'publish'
        ));
        if ( $posts && !empty($posts[0]) ) {
            $post_id = $posts[0]->ID;
            if ( class_exists( '\Elementor\Core\Files\CSS\Post' ) ) {
                $css_file = new \Elementor\Core\Files\CSS\Post($post_id);
                $css_file->enqueue();
            }
        }
    }
});

add_action('init', function() {
    if ( isset($_GET['clear_elementor_cache']) && class_exists( '\Elementor\Plugin' ) ) {
        \Elementor\Plugin::instance()->files_manager->clear_cache();
        echo 'Elementor CSS Cache Cleared!';
        exit;
    }
});


// Include Review Modal
require_once get_template_directory() . '/inc/cmr-review-modal.php';

// Include Table Scraper Tool
require_once get_template_directory() . '/inc/table-scraper.php';

add_action( 'rest_api_init', function () {
    register_rest_route( 'quanto/v1', '/import_page', array(
        'methods' => 'POST',
        'callback' => function( $request ) {
            $params = $request->get_json_params();
            if ( empty($params['title']) || empty($params['content']) ) {
                return new WP_Error( 'missing_data', 'Missing title or content', array('status' => 400) );
            }
            
            $post_data = array(
                'post_title'    => wp_strip_all_tags( $params['title'] ),
                'post_content'  => wp_kses_post( $params['content'] ),
                'post_status'   => 'publish',
                'post_type'     => !empty($params['post_type']) ? sanitize_text_field($params['post_type']) : 'page',
                'meta_input'    => array(
                    '_scraped_source_url' => esc_url_raw( $params['url'] ?? '' ),
                    '_scraped_from_table' => '1',
                    '_cmr_is_gated'        => '1',
                )
            );

            $post_id = wp_insert_post( $post_data );
            if ( is_wp_error( $post_id ) ) {
                return $post_id;
            }
            return rest_ensure_response( array('success' => true, 'post_id' => $post_id) );
        },
        'permission_callback' => '__return_true',
    ) );
    register_rest_route( 'quanto/v1', '/tablepress/(?P<id>\d+)', array(
        'methods' => 'GET',
        'callback' => function( $request ) {
            if ( ! class_exists( 'TablePress' ) ) return new WP_Error( 'no_tp', 'TablePress not active' );
            $id = $request['id'];
            $table = TablePress::$model_table->load( $id );
            return rest_ensure_response( $table );
        },
        'permission_callback' => '__return_true',
    ) );
    register_rest_route( 'quanto/v1', '/tablepress/all', array(
        'methods' => 'GET',
        'callback' => function() {
            if ( ! class_exists( 'TablePress' ) ) return new WP_Error( 'no_tp', 'TablePress not active' );
            return rest_ensure_response( TablePress::$model_table->load_all() );
        },
        'permission_callback' => '__return_true',
    ) );
    
    register_rest_route( 'quanto/v1', '/fetch_page', array(
        'methods' => 'GET',
        'callback' => function( $request ) {
            $url = $request->get_param( 'url' );
            if ( empty( $url ) || ! filter_var( $url, FILTER_VALIDATE_URL ) ) {
                return new WP_Error( 'invalid_url', 'Missing or invalid URL', array( 'status' => 400 ) );
            }
            
            $response = wp_remote_get( $url, array( 'timeout' => 30 ) );
            if ( is_wp_error( $response ) ) {
                return new WP_Error( 'fetch_failed', 'Failed to fetch the URL: ' . $response->get_error_message(), array( 'status' => 500 ) );
            }
            
            $html = wp_remote_retrieve_body( $response );
            if ( empty( $html ) ) {
                return new WP_Error( 'empty_content', 'The URL returned an empty response', array( 'status' => 500 ) );
            }
            
            // Basic parsing to try to extract just the body or main content
            $content = $html;
            $title = '';
            
            if ( class_exists('DOMDocument') ) {
                $dom = new DOMDocument();
                @$dom->loadHTML( mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8') );
                
                $title_nodes = $dom->getElementsByTagName('title');
                if ( $title_nodes->length > 0 ) {
                    $title = $title_nodes->item(0)->nodeValue;
                }
                
                // For a dynamic fetch, just returning the body is usually safest if no selector is given
                $body_nodes = $dom->getElementsByTagName('body');
                if ( $body_nodes->length > 0 ) {
                    $content = $dom->saveHTML( $body_nodes->item(0) );
                }
            }
            
            return rest_ensure_response( array(
                'success' => true,
                'url' => $url,
                'title' => $title,
                'content' => $content
            ) );
        },
        'permission_callback' => '__return_true',
    ) );

    register_rest_route( 'quanto/v1', '/update_table_slugs', array(
        'methods'  => 'GET',
        'callback' => function() {
            delete_option( 'cmr_table_pages_renamed_v2' );
            cmr_update_table_page_slugs_and_titles();
            return rest_ensure_response( array(
                'success' => true,
                'message' => 'Table page titles and slugs updated successfully',
                'pages'   => array(
                    'Gated Section'      => home_url( '/records/' ),
                    'Speakers'           => home_url( '/speakers-info/' ),
                    'Audience Profiling' => home_url( '/audience-profiling/' ),
                    'CMR LINKS'          => home_url( '/imsg-links/' ),
                ),
            ) );
        },
        'permission_callback' => '__return_true',
    ) );
} );

// Update Table Scraped Page Titles & Slugs
add_action( 'init', 'cmr_update_table_page_slugs_and_titles' );
function cmr_update_table_page_slugs_and_titles() {
    $updated = get_option( 'cmr_table_pages_renamed_v2' );
    if ( $updated ) {
        return;
    }

    $mappings = array(
        '1-gated-section-2026-07-30-csv' => array(
            'title' => 'Gated Section',
            'slug'  => 'records',
        ),
        '4-speakers-2026-07-30-csv' => array(
            'title' => 'Speakers',
            'slug'  => 'speakers-info',
        ),
        '9-audience-profiling-2026-07-30-csv' => array(
            'title' => 'Audience Profiling',
            'slug'  => 'audience-profiling',
        ),
        '12-cmr-links-2026-07-30-csv' => array(
            'title' => 'CMR LINKS',
            'slug'  => 'imsg-links',
        ),
    );

    foreach ( $mappings as $old_slug => $data ) {
        $page = get_page_by_path( $old_slug );
        if ( ! $page ) {
            $query = new WP_Query( array(
                'post_type'      => 'page',
                'name'           => $old_slug,
                'posts_per_page' => 1,
                'post_status'    => 'any',
            ) );
            if ( $query->have_posts() ) {
                $page = $query->posts[0];
            }
        }

        if ( $page ) {
            wp_update_post( array(
                'ID'         => $page->ID,
                'post_title' => $data['title'],
                'post_name'  => $data['slug'],
            ) );
        }
    }

    flush_rewrite_rules();
    update_option( 'cmr_table_pages_renamed_v2', '1' );
}

/**
 * Hide Elementor popups (like the consultation form) on the login page.
 */
function quanto_hide_elementor_popup_on_login() {
    echo '<style>.elementor-location-popup { display: none !important; }</style>';
}
add_action( 'login_head', 'quanto_hide_elementor_popup_on_login' );

/**
 * CMR Live Section Header & View All Button Styling Override
 */
add_action( 'wp_head', function() {
    ?>
    <style id="cmr-live-header-override">
        /* 1. Header Container Row - Space Between & No Wrap */
        .elementor-element.elementor-element-2c76331,
        div[data-id="2c76331"],
        .elementor-element-2c76331.e-con-full,
        .elementor-element-2c76331.e-con {
            display: flex !important;
            flex-direction: row !important;
            justify-content: space-between !important;
            align-items: flex-end !important;
            width: 100% !important;
            gap: 25px !important;
            flex-wrap: nowrap !important;
            --display: flex !important;
            --flex-direction: row !important;
            --justify-content: space-between !important;
            --align-items: flex-end !important;
            --flex-wrap: nowrap !important;
        }

        /* Heading takes all available space so it only wraps into 2 clean lines */
        .elementor-element-2c76331 > h1,
        .elementor-element-2c76331 .e-heading-base,
        .elementor-element-2c76331 [data-id="983db4c"],
        div[data-id="2c76331"] > h1 {
            flex: 1 1 auto !important;
            width: auto !important;
            max-width: none !important;
            margin: 0 !important;
            font-family: 'Instrument Sans', sans-serif !important;
            font-size: 42px !important;
            font-weight: 600 !important;
            line-height: 1.22 !important;
            color: #000000 !important;
            letter-spacing: -0.5px !important;
            --container-widget-width: initial !important;
            --width: auto !important;
            flex-basis: auto !important;
            flex-grow: 1 !important;
            flex-shrink: 1 !important;
        }

        /* 2. Div holding View all button - Takes ONLY content width, NO 50% column width */
        .elementor-element-2c76331 .elementor-element-c8d290c,
        .elementor-element.elementor-element-c8d290c,
        div[data-id="c8d290c"] {
            flex: 0 0 auto !important;
            width: auto !important;
            max-width: max-content !important;
            min-width: 0 !important;
            margin: 0 0 8px 0 !important;
            padding: 0 !important;
            display: inline-flex !important;
            align-items: flex-end !important;
            justify-content: flex-end !important;
            align-self: flex-end !important;
            --container-widget-width: auto !important;
            --width: auto !important;
            flex-basis: auto !important;
            flex-grow: 0 !important;
            flex-shrink: 0 !important;
        }

        .elementor-element-c8d290c .elementor-widget-quanto_button,
        .elementor-element-c8d290c .elementor-widget-container {
            width: auto !important;
            margin: 0 !important;
            padding: 0 !important;
            display: inline-flex !important;
        }

        /* 3. View all Button Redesign */
        .elementor-element-c8d290c a.quanto-link-btn,
        div[data-id="c8d290c"] a.quanto-link-btn,
        div[data-id="b4d41ec"] a.quanto-link-btn {
            display: inline-flex !important;
            align-items: center !important;
            gap: 5px !important;
            font-family: 'Instrument Sans', sans-serif !important;
            font-size: 15px !important;
            font-weight: 600 !important;
            color: #111111 !important;
            text-decoration: none !important;
            border-bottom: 1.5px solid #111111 !important;
            padding: 0 0 2px 0 !important;
            border-radius: 0 !important;
            background: transparent !important;
            box-shadow: none !important;
            letter-spacing: -0.1px !important;
            transition: all 0.2s ease !important;
            height: auto !important;
            line-height: 1.2 !important;
            white-space: nowrap !important;
            width: auto !important;
        }

        .elementor-element-c8d290c a.quanto-link-btn:hover,
        div[data-id="c8d290c"] a.quanto-link-btn:hover,
        div[data-id="b4d41ec"] a.quanto-link-btn:hover {
            color: #000000 !important;
            border-bottom-color: #000000 !important;
        }

        .elementor-element-c8d290c a.quanto-link-btn span,
        div[data-id="c8d290c"] a.quanto-link-btn span,
        div[data-id="b4d41ec"] a.quanto-link-btn span {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            position: static !important;
            width: auto !important;
            height: auto !important;
            background: transparent !important;
            overflow: visible !important;
            margin-left: 2px !important;
        }

        .elementor-element-c8d290c a.quanto-link-btn span .arry1,
        div[data-id="c8d290c"] a.quanto-link-btn span .arry1,
        div[data-id="b4d41ec"] a.quanto-link-btn span .arry1 {
            display: inline-block !important;
            position: static !important;
            transform: rotate(-45deg) !important;
            font-size: 13px !important;
            color: #111111 !important;
            line-height: 1 !important;
            transition: transform 0.25s ease !important;
        }

        .elementor-element-c8d290c a.quanto-link-btn span .arry2,
        div[data-id="c8d290c"] a.quanto-link-btn span .arry2,
        div[data-id="b4d41ec"] a.quanto-link-btn span .arry2 {
            display: none !important;
        }

        .elementor-element-c8d290c a.quanto-link-btn:hover span .arry1,
        div[data-id="c8d290c"] a.quanto-link-btn:hover span .arry1,
        div[data-id="b4d41ec"] a.quanto-link-btn:hover span .arry1 {
            transform: rotate(-45deg) translate(2px, -2px) !important;
        }

        /* CMR LIVE Pill Badge — custom class + Elementor selectors (both work) */
        .cmr-live-badge,
        .e-d155fe5-f948194,
        [data-id="d155fe5"] {
            display: inline-flex !important;
            flex-direction: row !important;
            flex-wrap: nowrap !important;
            align-items: center !important;
            justify-content: flex-start !important;
            background: #ffffff !important;
            border: 1.5px solid #d1d5db !important;
            border-radius: 9999px !important;
            padding: 6px 18px 6px 8px !important;
            gap: 10px !important;
            width: auto !important;
            max-width: fit-content !important;
            min-width: 0 !important;
            height: auto !important;
            min-height: 36px !important;
            box-sizing: border-box !important;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04) !important;
            margin: 0 0 24px 0 !important;
            align-self: flex-start !important;
            /* Kill Elementor e-con variables */
            --min-height: 0px !important;
            --height: auto !important;
            --gap: 10px !important;
            --flex-direction: row !important;
            --flex-wrap: nowrap !important;
            --align-items: center !important;
            --justify-content: flex-start !important;
            --padding-top: 0px !important;
            --padding-bottom: 0px !important;
            --padding-left: 0px !important;
            --padding-right: 0px !important;
        }

        .cmr-live-badge *,
        [data-id="d155fe5"] * {
            box-sizing: border-box !important;
        }

        /* Red dot circle wrapper */
        .cmr-live-badge [data-id="533a48d"],
        .cmr-live-badge .elementor-widget-image,
        [data-id="d155fe5"] [data-id="533a48d"],
        [data-id="d155fe5"] .elementor-widget-image {
            margin: 0 !important;
            padding: 0 !important;
            width: 22px !important;
            height: 22px !important;
            min-width: 22px !important;
            min-height: 22px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            flex-shrink: 0 !important;
        }

        .cmr-live-badge [data-id="533a48d"] .elementor-widget-container,
        [data-id="d155fe5"] [data-id="533a48d"] .elementor-widget-container {
            margin: 0 !important;
            padding: 0 !important;
            width: 22px !important;
            height: 22px !important;
            min-width: 22px !important;
            min-height: 22px !important;
            border-radius: 50% !important;
            background: rgba(220, 38, 38, 0.12) !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            flex-shrink: 0 !important;
            position: relative !important;
        }

        /* Hide original image, show red dot via ::after */
        .cmr-live-badge img,
        [data-id="d155fe5"] img {
            display: none !important;
        }

        .cmr-live-badge [data-id="533a48d"] .elementor-widget-container::after,
        [data-id="d155fe5"] [data-id="533a48d"] .elementor-widget-container::after {
            content: '' !important;
            display: block !important;
            width: 9px !important;
            height: 9px !important;
            border-radius: 50% !important;
            background: #dc2626 !important;
        }

        /* CMR LIVE text */
        .cmr-live-badge h6,
        .cmr-live-badge .e-heading-base,
        .cmr-live-badge [data-id="e62c637"],
        [data-id="d155fe5"] h6,
        [data-id="d155fe5"] .e-heading-base,
        [data-id="d155fe5"] [data-id="e62c637"] {
            font-family: 'Instrument Sans', sans-serif !important;
            font-size: 14px !important;
            font-weight: 700 !important;
            color: #000000 !important;
            letter-spacing: 0.5px !important;
            margin: 0 !important;
            padding: 0 !important;
            line-height: 1 !important;
            height: auto !important;
            text-transform: uppercase !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            vertical-align: middle !important;
            white-space: nowrap !important;
        }

        /* CMR Live / Video Insights Category Badges - Purple #4820B0 */
        .elementor-element.elementor-element-bf85eb0 h6:first-child,
        .elementor-element.elementor-element-1de6221 h6:first-child,
        .elementor-element.elementor-element-275e662 .elementor-element-2c76331 h6:first-child,
        .elementor-element.elementor-element-275e662 .e-div-block-base:not(.elementor-element-d155fe5):not([data-id="d155fe5"]) h6:first-child,
        .elementor-element.elementor-element-c2c8f73 .e-div-block-base:not(.elementor-element-d155fe5):not([data-id="d155fe5"]) h6:first-child,
        [data-id="bf85eb0"] h6:first-child,
        [data-id="1de6221"] h6:first-child,
        [data-id="bf85eb0"] .e-heading-base:first-child,
        [data-id="1de6221"] .e-heading-base:first-child,
        [data-id="c2c8f73"] .e-div-block-base:not(.elementor-element-d155fe5):not([data-id="d155fe5"]) h6:first-child,
        [data-id="c2c8f73"] .e-div-block-base:not(.elementor-element-d155fe5):not([data-id="d155fe5"]) .e-heading-base:first-child,
        [data-id="275e662"] .e-div-block-base:not(.elementor-element-d155fe5):not([data-id="d155fe5"]) h6:first-child,
        [data-id="85160bc"] h6:first-child,
        [data-id="e5a291e"] h6:first-child,
        [data-id="ec16651"] h6:first-child,
        [data-id="ed5537e"] h6:first-child,
        .elementor-element-85160bc h6:first-child,
        .elementor-element-e5a291e h6:first-child,
        .elementor-element-ec16651 h6:first-child,
        .elementor-element-ed5537e h6:first-child,
        [data-id="1fd1573"],
        [data-id="1fd1573"] .elementor-heading-title,
        [data-id="1fd1573"] h6,
        [data-id="ff3f001"],
        [data-id="ff3f001"] .elementor-heading-title,
        [data-id="ff3f001"] h6,
        .e-1fd1573-0ea6203,
        .e-ff3f001-b9ccb27 {
            color: #4820B0 !important;
        }

        /* CMR Live / Video Insights Card Titles - font-weight: 600 */
        .elementor-element.elementor-element-275e662 h3,
        .elementor-element.elementor-element-c2c8f73 h3,
        .elementor-element.elementor-element-b82bad4 h3,
        .elementor-element.elementor-element-ed5537e h3,
        .elementor-element.elementor-element-85160bc h3,
        .elementor-element.elementor-element-e5a291e h3,
        .elementor-element.elementor-element-ec16651 h3,
        [data-id="c2c8f73"] h3,
        [data-id="c2c8f73"] h3.e-heading-base,
        [data-id="c2c8f73"] .elementor-heading-title,
        [data-id="275e662"] h3,
        [data-id="b82bad4"] h3,
        [data-id="ed5537e"] h3,
        [data-id="85160bc"] h3,
        [data-id="e5a291e"] h3,
        [data-id="ec16651"] h3,
        [data-id="255acd5"],
        [data-id="255acd5"] .elementor-heading-title,
        [data-id="255acd5"] h3,
        [data-id="bb5a035"],
        [data-id="bb5a035"] .elementor-heading-title,
        [data-id="bb5a035"] h3,
        .e-255acd5-0533542,
        .e-bb5a035-8148c2a {
            font-weight: 600 !important;
        }

        /* Universal Card Title Hover - Purple #4820B0 */
        .cmr-intel-trends-card:hover .cmr-intel-trends-title,
        .cmr-intel-trends-card:hover .cmr-intel-trends-title a,
        .cmr-intel-trends-card:hover h3,
        .cmr-intel-trends-card:hover h3 a,
        .cmr-intel-trends-title:hover,
        .cmr-intel-trends-title:hover a,
        .cmr-intel-trends-title a:hover,
        .stay-updated-card:hover .stay-updated-card-title,
        .stay-updated-card:hover .stay-updated-card-title a,
        .stay-updated-card:hover h3,
        .stay-updated-card:hover h3 a,
        .stay-updated-card-title:hover,
        .stay-updated-card-title:hover a,
        .stay-updated-card-title a:hover,
        .cmr-wwt-card:hover .cmr-wwt-card-title,
        .cmr-wwt-card:hover .cmr-wwt-card-title a,
        .cmr-wwt-card-title:hover,
        .cmr-wwt-card-title a:hover,
        .intel-card:hover .intel-title,
        .intel-card:hover .intel-title a,
        .intel-title:hover,
        .intel-title a:hover,
        .cmr-intel-list-item:hover .cmr-intel-list-title,
        .cmr-intel-list-item:hover .cmr-intel-list-title a,
        .cmr-intel-list-title:hover,
        .cmr-intel-list-title a:hover,
        .cmr-mrg-card:hover .cmr-mrg-card-title,
        .cmr-mrg-card:hover .cmr-mrg-card-title a,
        .cmr-mrg-card-title:hover,
        .cmr-mrg-card-title a:hover,
        .cmr-enterprisecgd-card:hover .cmr-enterprisecgd-card-title,
        .cmr-enterprisecgd-card:hover .cmr-enterprisecgd-card-title a,
        .cmr-enterprisecgd-card-img-wrap:hover ~ .cmr-enterprisecgd-card-title,
        .cmr-enterprisecgd-card-img-wrap:hover ~ .cmr-enterprisecgd-card-title a,
        .cmr-enterprisecgd-card-title:hover,
        .cmr-enterprisecgd-card-title a:hover,
        .cmr-channelcgd-card:hover .cmr-channelcgd-card-title,
        .cmr-channelcgd-card:hover .cmr-channelcgd-card-title a,
        .cmr-channelcgd-card-img-wrap:hover ~ .cmr-channelcgd-card-title,
        .cmr-channelcgd-card-img-wrap:hover ~ .cmr-channelcgd-card-title a,
        .cmr-channelcgd-card-title:hover,
        .cmr-channelcgd-card-title a:hover,
        .cmr-smbcgd-card:hover .cmr-smbcgd-card-title,
        .cmr-smbcgd-card:hover .cmr-smbcgd-card-title a,
        .cmr-smbcgd-card-img-wrap:hover ~ .cmr-smbcgd-card-title,
        .cmr-smbcgd-card-img-wrap:hover ~ .cmr-smbcgd-card-title a,
        .cmr-smbcgd-card-title:hover,
        .cmr-smbcgd-card-title a:hover,
        .cmr-card:hover .cmr-card-title,
        .cmr-mc-card:hover .cmr-mc-title,
        .cmr-mc-card:hover .cmr-mc-title a,
        .cmr-mc-title:hover,
        .cmr-mc-title a:hover,
        .cmr-team-card:hover .cmr-team-card-name,
        .cmr-team-member:hover .cmr-team-name,
        .elementor-element-275e662 .e-con:hover h3,
        .elementor-element-c2c8f73 .e-con:hover h3,
        .elementor-element-275e662 .elementor-element:hover h3,
        .elementor-element-c2c8f73 .elementor-element:hover h3,
        [data-id="275e662"] .e-con:hover h3,
        [data-id="c2c8f73"] .e-con:hover h3,
        [data-id="b82bad4"]:hover h3,
        [data-id="ec16651"]:hover h3,
        [data-id="ed5537e"]:hover h3,
        [data-id="85160bc"]:hover h3,
        [data-id="e5a291e"]:hover h3,
        [data-id="255acd5"]:hover h3,
        [data-id="bb5a035"]:hover h3,
        .cmr-trending-card:hover .cmr-trending-card-title,
        .cmr-ls-card:hover .cmr-ls-title,
        .cmr-podcast-card:hover .cmr-podcast-title,
        .elementor-widget-posts .elementor-post:hover .elementor-post__title,
        .elementor-widget-posts .elementor-post:hover .elementor-post__title a,
        .elementor-post__title a:hover {
            color: #4820B0 !important;
        }

        /* CMR Dark / Featured Slide Title Hover - Cyan #00D2B9 */
        .cmr-fi-slide:hover .cmr-fi-slide-title,
        .cmr-fi-slide-title:hover,
        .cmr-card-featured:hover .cmr-card-title,
        .cmr-card-featured .cmr-card-title:hover,
        .cmr-insights-featured:hover .insights-title,
        .cmr-insights-featured .insights-title:hover,
        .insights-featured-card:hover .insights-title,
        .insights-featured-card:hover .insights-title a,
        .insights-featured-content:hover .insights-title,
        .insights-featured-content .insights-title:hover,
        .insights-featured-content .insights-title a:hover {
            color: #00D2B9 !important;
        }

        .cmr-card-featured .cmr-read-coverage,
        .cmr-card-featured:hover .cmr-read-coverage,
        .cmr-card-featured .cmr-read-coverage:hover,
        .cmr-card-featured .cmr-card-link-wrapper:hover .cmr-read-coverage {
            color: #ffffff !important;
            border-bottom: 1px solid #ffffff !important;
        }

        .cmr-intel-trends-title,
        .cmr-intel-trends-title a,
        .stay-updated-card-title,
        .stay-updated-card-title a,
        .cmr-wwt-card-title,
        .cmr-wwt-card-title a,
        .intel-title,
        .intel-title a,
        .cmr-intel-list-title,
        .cmr-intel-list-title a,
        .cmr-mrg-card-title,
        .cmr-mrg-card-title a,
        .cmr-card-title,
        .cmr-mc-title,
        .cmr-mc-title a,
        .cmr-team-card-name,
        .cmr-team-name,
        .cmr-trending-card-title,
        .cmr-ls-title,
        .cmr-podcast-title,
        .cmr-fi-slide-title {
            transition: color 0.3s ease !important;
        }


        /* Purple / Dark Banner CTA Button - Transparent background with White Text & Border (NO Black) */
        .e-con[style*="4820B0"] .elementor-button,
        .e-con[style*="4820b0"] .elementor-button,
        .e-con[style*="bid-decision"] .elementor-button,
        .e-con[style*="background-color: rgb(72, 32, 176)"] .elementor-button,
        .elementor-element[style*="4820B0"] .elementor-button,
        .elementor-element[style*="4820b0"] .elementor-button,
        .elementor-element[style*="bid-decision"] .elementor-button,
        .elementor-element[style*="background-color: rgb(72, 32, 176)"] .elementor-button,
        .elementor-element-88332ab .elementor-button,
        [data-id="88332ab"] .elementor-button,
        .elementor-element-e50df8e .elementor-button,
        [data-id="e50df8e"] .elementor-button,
        .elementor-element-e50df8e,
        [data-id="e50df8e"],
        .btn-outline,
        a.btn-outline,
        .elementor-widget-button[style*="border:solid"] .elementor-button,
        .elementor-widget-button[style*="border-color:#FFFFFF"] .elementor-button,
        .elementor-widget-button[style*="border-color: #FFFFFF"] .elementor-button,
        .elementor-widget-button[style*="border-color:#fff"] .elementor-button,
        .elementor-widget-button[style*="border-color: #fff"] .elementor-button,
        .elementor-element-e50df8e a.elementor-button,
        .elementor-element-e50df8e button.elementor-button {
            background-color: transparent !important;
            background: transparent !important;
            box-shadow: none !important;
            color: #FFFFFF !important;
            border-color: #FFFFFF !important;
        }

        .e-con[style*="4820B0"] .elementor-button *,
        .e-con[style*="4820b0"] .elementor-button *,
        .e-con[style*="bid-decision"] .elementor-button *,
        .e-con[style*="background-color: rgb(72, 32, 176)"] .elementor-button *,
        .elementor-element[style*="4820B0"] .elementor-button *,
        .elementor-element[style*="4820b0"] .elementor-button *,
        .elementor-element[style*="bid-decision"] .elementor-button *,
        .elementor-element-88332ab .elementor-button *,
        [data-id="88332ab"] .elementor-button *,
        .elementor-element-e50df8e .elementor-button *,
        [data-id="e50df8e"] .elementor-button *,
        .elementor-element-e50df8e *,
        [data-id="e50df8e"] *,
        .btn-outline *,
        a.btn-outline * {
            color: #FFFFFF !important;
            fill: #FFFFFF !important;
        }

        /* CMR Video / Live Cards Image & Play Icon Hover Scale */
        .elementor-widget-video .elementor-custom-embed-image-overlay,
        .elementor-element-9b2ddd6 .elementor-custom-embed-image-overlay,
        .elementor-element-d713748 .elementor-custom-embed-image-overlay,
        [data-id="9b2ddd6"] .elementor-custom-embed-image-overlay,
        [data-id="d713748"] .elementor-custom-embed-image-overlay,
        .cmr-trending-img-wrap img,
        .cmr-ls-img-wrap img,
        .cmr-podcast-img-wrap img,
        .cmr-topview-feat-img img {
            transform: scale(1) !important;
            transition: transform 0.4s cubic-bezier(0.25, 1, 0.5, 1) !important;
        }

        .elementor-widget-video:hover .elementor-custom-embed-image-overlay,
        .elementor-element-ec16651:hover .elementor-custom-embed-image-overlay,
        .elementor-element-ed5537e:hover .elementor-custom-embed-image-overlay,
        [data-id="ec16651"]:hover .elementor-custom-embed-image-overlay,
        [data-id="ed5537e"]:hover .elementor-custom-embed-image-overlay,
        [data-id="c2c8f73"] .elementor-element:hover .elementor-custom-embed-image-overlay,
        .cmr-trending-card:hover .cmr-trending-img-wrap img,
        .cmr-ls-card:hover .cmr-ls-img-wrap img,
        .cmr-podcast-card:hover .cmr-podcast-img-wrap img {
            transform: scale(1.06) !important;
        }

        .elementor-custom-embed-play,
        .cmr-trending-play-btn,
        .cmr-ls-play-btn,
        .cmr-podcast-play-btn {
            transition: transform 0.3s ease, background 0.3s ease !important;
        }

        .elementor-widget-video:hover .elementor-custom-embed-play,
        .elementor-custom-embed-play:hover,
        .cmr-trending-card:hover .cmr-trending-play-btn,
        .cmr-ls-card:hover .cmr-ls-play-btn,
        .cmr-podcast-card:hover .cmr-podcast-play-btn {
            transform: translate(-50%, -50%) scale(1.1) !important;
        }

        /* CTA Buttons & Action Elements Hover Scale */
        .elementor-button,
        .elementor-widget-button .elementor-button,
        .cmr-crc-btn,
        .cmr-trending-btn,
        .cmr-stay-updated-btn,
        .cmr-spotlight-btn,
        .cmr-mc-load-more,
        .cmr-mc-pill,
        .cmr-mc-filter-btn {
            transition: transform 0.25s ease, background-color 0.25s ease, color 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease !important;
        }

        .elementor-button:hover,
        .elementor-widget-button .elementor-button:hover,
        .cmr-crc-btn:hover,
        .cmr-trending-btn:hover,
        .cmr-stay-updated-btn:hover,
        .cmr-spotlight-btn:hover,
        .cmr-mc-load-more:hover {
            transform: scale(1.05) !important;
        }

        /* Link CTA Arrow Hover Translate / Scale */
        .quanto-link-btn,
        .cmr-ls-explore,
        .cmr-mc-read-coverage {
            transition: all 0.25s ease !important;
        }

        .quanto-link-btn:hover,
        .cmr-ls-explore:hover,
        .cmr-mc-read-coverage:hover {
            transform: scale(1.03) !important;
        }

        /* 0169cee text widget styling (desktop + mobile) */
        [data-id="0169cee"],
        [data-id="0169cee"] *,
        .elementor-element-0169cee,
        .elementor-element-0169cee * {
            font-weight: 600 !important;
        }

        /* 403530a text widget styling - clear Times New Roman / Word styles */
        [data-id="403530a"],
        [data-id="403530a"] *,
        .elementor-element-403530a,
        .elementor-element-403530a *,
        .MsoNormal,
        .MsoNormal * {
            font-family: "Instrument Sans", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
            line-height: 1.6 !important;
        }

        [data-id="403530a"] p,
        .elementor-element-403530a p {
            margin-top: 0 !important;
            margin-bottom: 20px !important;
        }

        /* 1e0db62 overview gap */
        [data-id="1e0db62"],
        .elementor-element-1e0db62 {
            margin-bottom: 20px !important;
            --margin-bottom: 20px !important;
        }

        /* Mobile: center the badge */
        @media (max-width: 768px) {
            .cmr-live-badge,
            [data-id="d155fe5"] {
                justify-content: center !important;
                margin: 0 auto 20px auto !important;
                align-self: center !important;
                --justify-content: center !important;
            }

            [data-id="dbed1d7"],
            .e-dbed1d7-febf73c {
                font-size: 20px !important;
                line-height: 1.3 !important;
            }

            [data-id="efd0eb4"],
            .e-efd0eb4-113e810 {
                font-size: 36px !important;
                font-weight: 600 !important;
                line-height: 1.2 !important;
            }

            [data-id="0169cee"],
            .elementor-element-0169cee,
            [data-id="0169cee"] p,
            [data-id="0169cee"] span,
            [data-id="0169cee"] b,
            [data-id="0169cee"] strong,
            .elementor-element-0169cee p,
            .elementor-element-0169cee span,
            .elementor-element-0169cee b,
            .elementor-element-0169cee strong {
                font-size: 28px !important;
                font-weight: 600 !important;
                line-height: 1.3 !important;
            }

            .elementor-element-1e0db62,
            .elementor-element-1e0db62 .elementor-heading-title,
            [data-id="1e0db62"],
            [data-id="1e0db62"] .elementor-heading-title,
            [data-id="1e0db62"] h1,
            [data-id="1e0db62"] h2,
            [data-id="1e0db62"] h3,
            [data-id="1e0db62"] h4,
            [data-id="1e0db62"] h5,
            [data-id="1e0db62"] h6,
            .elementor-22037 .elementor-element.elementor-element-1e0db62 .elementor-heading-title {
                font-size: 12px !important;
                font-weight: 600 !important;
                text-align: center !important;
                justify-content: center !important;
                margin-left: auto !important;
                margin-right: auto !important;
                margin-bottom: 20px !important;
                --margin-bottom: 20px !important;
                width: 100% !important;
            }
        }

        @media (max-width: 768px) {

            .elementor-element-b06ac09,
            div[data-id="b06ac09"] {
                display: flex !important;
                flex-direction: column !important;
                justify-content: center !important;
                align-items: center !important;
                text-align: center !important;
                width: 100% !important;
            }

            .elementor-element-2c76331,
            div[data-id="2c76331"],
            .elementor-element-b82bad4,
            div[data-id="b82bad4"] {
                display: flex !important;
                flex-direction: column !important;
                align-items: center !important;
                text-align: center !important;
                width: 100% !important;
            }

            .elementor-element-b82bad4 h1,
            .elementor-element-2c76331 h1,
            .elementor-element-2c76331 > h1,
            .elementor-element-2c76331 .e-heading-base,
            .elementor-element-2c76331 [data-id="983db4c"],
            div[data-id="2c76331"] > h1,
            div[data-id="983db4c"],
            .e-983db4c-d987a57,
            .e-983db4c-e1cafae {
                font-family: 'Instrument Sans', sans-serif !important;
                font-size: 26px !important;
                font-weight: 700 !important;
                line-height: 1.25 !important;
                color: #000000 !important;
                letter-spacing: -0.5px !important;
                text-align: center !important;
                margin: 0 auto 20px auto !important;
                max-width: 100% !important;
                width: 100% !important;
            }

            .elementor-element.elementor-element-c8d290c,
            div[data-id="c8d290c"],
            .elementor-element.elementor-element-c49530d,
            div[data-id="c49530d"],
            .elementor-element.elementor-element-b4d41ec,
            div[data-id="b4d41ec"] {
                display: none !important;
            }

            [data-id="f93180d"],
            [data-id="f93180d"] h2,
            h2[data-id="f93180d"],
            .e-f93180d-76d932c {
                font-size: 32px !important;
                line-height: 1.25 !important;
                letter-spacing: -0.5px !important;
            }

            section.hero,
            .hero {
                position: relative !important;
                min-height: 65vh !important;
                overflow: hidden !important;
            }

            /* Foundation Section (8c80839) Mobile Centering */
            .cmr-foundation-wrap,
            [data-id="8c80839"] .cmr-foundation-wrap {
                height: auto !important;
                width: 100% !important;
            }
            .cmr-foundation-panel,
            [data-id="8c80839"] .cmr-foundation-panel {
                position: relative !important;
                height: auto !important;
                padding: 40px 15px !important;
                text-align: center !important;
                justify-content: center !important;
                align-items: center !important;
            }
            .cmr-foundation-inner,
            [data-id="8c80839"] .cmr-foundation-inner {
                text-align: center !important;
                align-items: center !important;
                justify-content: center !important;
                margin: 0 auto !important;
                width: 100% !important;
            }
            .cmr-foundation-title,
            [data-id="8c80839"] .cmr-foundation-title {
                font-size: 28px !important;
                margin: 0 auto 30px auto !important;
                text-align: center !important;
                width: 100% !important;
                max-width: 100% !important;
            }
            .cmr-foundation-content,
            [data-id="8c80839"] .cmr-foundation-content {
                flex-direction: column !important;
                gap: 24px !important;
                align-items: center !important;
                text-align: center !important;
                width: 100% !important;
            }
            .cmr-foundation-left,
            [data-id="8c80839"] .cmr-foundation-left {
                flex: none !important;
                width: 100% !important;
                display: flex !important;
                justify-content: center !important;
                align-items: center !important;
                gap: 24px !important;
                overflow-x: auto !important;
                padding-right: 0 !important;
                padding-bottom: 12px !important;
                border-bottom: 1px solid #eaeaea !important;
                border-right: none !important;
                margin: 0 auto 20px auto !important;
            }
            .cmr-foundation-nav-item,
            [data-id="8c80839"] .cmr-foundation-nav-item {
                font-size: 20px !important;
                margin-bottom: 0 !important;
                white-space: nowrap !important;
                text-align: center !important;
            }
            .cmr-foundation-right,
            [data-id="8c80839"] .cmr-foundation-right {
                flex: none !important;
                width: 100% !important;
                text-align: center !important;
                display: flex !important;
                flex-direction: column !important;
                align-items: center !important;
                justify-content: center !important;
            }
            .cmr-foundation-block,
            [data-id="8c80839"] .cmr-foundation-block {
                position: relative !important;
                opacity: 1 !important;
                pointer-events: auto !important;
                transform: none !important;
                display: none;
                padding-left: 0 !important;
                text-align: center !important;
                width: 100% !important;
                margin: 0 auto !important;
            }
            .cmr-foundation-block.show,
            [data-id="8c80839"] .cmr-foundation-block.show {
                display: block !important;
            }
            .cmr-foundation-block-title,
            [data-id="8c80839"] .cmr-foundation-block-title {
                font-size: 26px !important;
                text-align: center !important;
                margin: 0 auto 15px auto !important;
            }
            .cmr-foundation-block-desc,
            [data-id="8c80839"] .cmr-foundation-block-desc {
                font-size: 16px !important;
                color: #444 !important;
                line-height: 1.6 !important;
                text-align: center !important;
                margin: 0 auto !important;
                max-width: 100% !important;
            }
        }

        /* ===================================================
           Team Section (e9e2ef0) - Desktop & Responsive Layout
           =================================================== */
        .elementor-element-e9e2ef0,
        [data-id="e9e2ef0"] {
            width: 100% !important;
            max-width: 1280px !important;
            margin: 0 auto !important;
            box-sizing: border-box !important;
        }

        @media (min-width: 1025px) {
            .elementor-element-e9e2ef0,
            [data-id="e9e2ef0"] {
                display: flex !important;
                flex-direction: row !important;
                justify-content: space-between !important;
                align-items: flex-start !important;
                gap: 40px !important;
                padding: 60px 24px 40px 24px !important;
            }

            .elementor-element-b5534eb,
            [data-id="b5534eb"] {
                flex: 0 0 280px !important;
                width: 280px !important;
                display: flex !important;
                flex-direction: column !important;
                align-items: flex-start !important;
                text-align: left !important;
            }

            [data-id="01a9d2a"],
            .e-01a9d2a-6018b41 {
                font-family: 'Instrument Sans', sans-serif !important;
                font-size: 42px !important;
                font-weight: 600 !important;
                color: #0F0F0F !important;
                line-height: 1.15 !important;
                letter-spacing: -1.2px !important;
                margin: 0 0 35px 0 !important;
                text-align: left !important;
            }

            .elementor-element-b95ab32,
            [data-id="b95ab32"] {
                display: block !important;
                text-align: left !important;
                margin: 0 !important;
            }

            .elementor-element-c946a02,
            [data-id="c946a02"] {
                display: flex !important;
                flex-direction: row !important;
                gap: 24px !important;
                flex: 1 1 auto !important;
                width: calc(100% - 320px) !important;
                max-width: calc(100% - 320px) !important;
                justify-content: flex-start !important;
                align-items: stretch !important;
            }

            .elementor-element-f64cd23,
            [data-id="f64cd23"],
            .elementor-element-a6469e6,
            [data-id="a6469e6"],
            .elementor-element-5600e8d,
            [data-id="5600e8d"] {
                flex: 1 1 0% !important;
                min-width: 0 !important;
                width: calc(33.333% - 16px) !important;
                max-width: calc(33.333% - 16px) !important;
                display: flex !important;
                flex-direction: column !important;
                align-items: flex-start !important;
                text-align: left !important;
            }
        }

        /* Team Card Shared Styles */
        .elementor-element-f64cd23,
        [data-id="f64cd23"],
        .elementor-element-a6469e6,
        [data-id="a6469e6"],
        .elementor-element-5600e8d,
        [data-id="5600e8d"] {
            cursor: pointer !important;
        }

        .elementor-element-f64cd23 .e-image-link-base,
        [data-id="f64cd23"] .e-image-link-base,
        .elementor-element-a6469e6 .e-image-link-base,
        [data-id="a6469e6"] .e-image-link-base,
        .elementor-element-5600e8d .e-image-link-base,
        [data-id="5600e8d"] .e-image-link-base,
        [data-id="c946a02"] .e-image-link-base {
            display: block !important;
            overflow: hidden !important;
            position: relative !important;
            width: 100% !important;
            margin-bottom: 16px !important;
        }

        .elementor-element-f64cd23 img,
        [data-id="f64cd23"] img,
        .elementor-element-a6469e6 img,
        [data-id="a6469e6"] img,
        .elementor-element-5600e8d img,
        [data-id="5600e8d"] img,
        [data-id="c946a02"] img {
            width: 100% !important;
            height: auto !important;
            aspect-ratio: 3/3.8 !important;
            object-fit: cover !important;
            display: block !important;
            background: #f5f5f5 !important;
            border-radius: 0 !important;
            margin-bottom: 0 !important;
            transform: scale(1) !important;
            transition: transform 0.45s cubic-bezier(0.25, 1, 0.5, 1) !important;
            will-change: transform !important;
        }

        .elementor-element-f64cd23:hover img,
        [data-id="f64cd23"]:hover img,
        .elementor-element-a6469e6:hover img,
        [data-id="a6469e6"]:hover img,
        .elementor-element-5600e8d:hover img,
        [data-id="5600e8d"]:hover img,
        [data-id="c946a02"] > div:hover img,
        [data-id="c946a02"] > .e-con:hover img,
        [data-id="c946a02"] .e-image-link-base:hover img {
            transform: scale(1.06) !important;
        }

        [data-id="dd9791d"],
        [data-id="fc8c017"],
        [data-id="7333940"],
        .e-dd9791d-13c3858,
        .e-fc8c017-24113ae,
        .e-7333940-b3d4700 {
            font-family: 'Instrument Sans', sans-serif !important;
            font-size: 18px !important;
            font-weight: 600 !important;
            color: #0F0F0F !important;
            margin: 0 0 6px 0 !important;
            letter-spacing: -0.3px !important;
            line-height: 1.25 !important;
            text-align: left !important;
            transition: color 0.3s ease !important;
        }

        [data-id="dd9791d"] a,
        [data-id="fc8c017"] a,
        [data-id="7333940"] a,
        [data-id="c946a02"] h3 a,
        [data-id="c946a02"] .e-heading-link-base {
            color: #0F0F0F !important;
            text-decoration: none !important;
            transition: color 0.3s ease !important;
        }

        .elementor-element-f64cd23:hover [data-id="dd9791d"],
        .elementor-element-f64cd23:hover [data-id="dd9791d"] a,
        [data-id="f64cd23"]:hover [data-id="dd9791d"],
        [data-id="f64cd23"]:hover [data-id="dd9791d"] a,
        .elementor-element-a6469e6:hover [data-id="fc8c017"],
        .elementor-element-a6469e6:hover [data-id="fc8c017"] a,
        [data-id="a6469e6"]:hover [data-id="fc8c017"],
        [data-id="a6469e6"]:hover [data-id="fc8c017"] a,
        .elementor-element-5600e8d:hover [data-id="7333940"],
        .elementor-element-5600e8d:hover [data-id="7333940"] a,
        [data-id="5600e8d"]:hover [data-id="7333940"],
        [data-id="5600e8d"]:hover [data-id="7333940"] a,
        [data-id="c946a02"] > div:hover h3,
        [data-id="c946a02"] > div:hover h3 a,
        [data-id="c946a02"] > .e-con:hover h3,
        [data-id="c946a02"] > .e-con:hover h3 a,
        [data-id="c946a02"] > div:hover .e-heading-link-base,
        [data-id="dd9791d"]:hover,
        [data-id="dd9791d"] a:hover,
        [data-id="fc8c017"]:hover,
        [data-id="fc8c017"] a:hover,
        [data-id="7333940"]:hover,
        [data-id="7333940"] a:hover {
            color: #6A42E5 !important;
        }

        [data-id="5caff27"],
        [data-id="711c626"],
        [data-id="e1f1f3e"],
        .e-5caff27-a02fe46,
        .e-711c626-3281288,
        .e-e1f1f3e-023d3ec {
            font-family: 'Instrument Sans', sans-serif !important;
            font-size: 13px !important;
            color: #475569 !important;
            margin: 0 0 16px 0 !important;
            line-height: 1.35 !important;
            text-align: left !important;
        }

        .elementor-element-8294ddf,
        [data-id="8294ddf"],
        .elementor-element-d1e2194,
        [data-id="d1e2194"],
        .elementor-element-0c9cb54,
        [data-id="0c9cb54"] {
            display: flex !important;
            flex-direction: row !important;
            gap: 8px !important;
            align-items: center !important;
            margin-top: auto !important;
        }

        [data-id="8294ddf"] .e-svg-base,
        [data-id="d1e2194"] .e-svg-base,
        [data-id="0c9cb54"] .e-svg-base {
            width: 28px !important;
            height: 28px !important;
            min-width: 28px !important;
            min-height: 28px !important;
            max-width: 28px !important;
            max-height: 28px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            background-color: #0F0F0F !important;
            border-radius: 4px !important;
            padding: 6px !important;
            box-sizing: border-box !important;
            cursor: pointer !important;
            transition: background-color 0.2s ease !important;
        }

        [data-id="8294ddf"] .e-svg-base:hover,
        [data-id="d1e2194"] .e-svg-base:hover,
        [data-id="0c9cb54"] .e-svg-base:hover {
            background-color: #333 !important;
        }

        [data-id="8294ddf"] svg,
        [data-id="d1e2194"] svg,
        [data-id="0c9cb54"] svg {
            width: 14px !important;
            height: 14px !important;
            fill: #ffffff !important;
            color: #ffffff !important;
        }

        [data-id="8294ddf"] svg path,
        [data-id="d1e2194"] svg path,
        [data-id="0c9cb54"] svg path {
            fill: #ffffff !important;
        }

        /* Pure CSS fix for dark LinkedIn icon where SVG path was inverted black */
        [data-id="8294ddf"] .e-svg-base:first-child svg,
        [data-id="d1e2194"] .e-svg-base:first-child svg,
        [data-id="0c9cb54"] .e-svg-base:first-child svg,
        .e-b19586d-e15efd3 svg,
        .e-4b4209f-09e851b svg,
        .e-14c9d22-c974106 svg {
            display: none !important;
        }

        [data-id="8294ddf"] .e-svg-base:first-child::after,
        [data-id="d1e2194"] .e-svg-base:first-child::after,
        [data-id="0c9cb54"] .e-svg-base:first-child::after,
        .e-b19586d-e15efd3::after,
        .e-4b4209f-09e851b::after,
        .e-14c9d22-c974106::after {
            content: '' !important;
            display: block !important;
            width: 14px !important;
            height: 14px !important;
            background-color: #ffffff !important;
            -webkit-mask: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath d='M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452z'/%3E%3C/svg%3E") no-repeat center / contain !important;
            mask: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath d='M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452z'/%3E%3C/svg%3E") no-repeat center / contain !important;
        }

        /* Pure CSS fix for Twitter X icon to be clean and bright white */
        [data-id="8294ddf"] .e-svg-base:last-child svg,
        [data-id="d1e2194"] .e-svg-base:last-child svg,
        [data-id="0c9cb54"] .e-svg-base:last-child svg,
        .e-86f8429-26c3862 svg,
        .e-0642c71-09df4d7 svg,
        .e-64d1e53-61af5be svg {
            display: none !important;
        }

        [data-id="8294ddf"] .e-svg-base:last-child::after,
        [data-id="d1e2194"] .e-svg-base:last-child::after,
        [data-id="0c9cb54"] .e-svg-base:last-child::after,
        .e-86f8429-26c3862::after,
        .e-0642c71-09df4d7::after,
        .e-64d1e53-61af5be::after {
            content: '' !important;
            display: block !important;
            width: 13px !important;
            height: 13px !important;
            background-color: #ffffff !important;
            -webkit-mask: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath d='M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z'/%3E%3C/svg%3E") no-repeat center / contain !important;
            mask: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath d='M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z'/%3E%3C/svg%3E") no-repeat center / contain !important;
        }

        /* Mobile / Tablet Responsive for Team Section */
        @media (max-width: 1024px) {
            .elementor-element-e9e2ef0,
            [data-id="e9e2ef0"] {
                display: flex !important;
                flex-direction: column !important;
                align-items: center !important;
                text-align: center !important;
                padding: 40px 20px 20px 20px !important;
                gap: 20px !important;
            }

            .elementor-element-b5534eb,
            [data-id="b5534eb"] {
                width: 100% !important;
                flex: 0 0 100% !important;
                display: flex !important;
                flex-direction: column !important;
                align-items: center !important;
                text-align: center !important;
            }

            [data-id="01a9d2a"],
            .e-01a9d2a-6018b41 {
                font-family: 'Instrument Sans', sans-serif !important;
                font-size: 26px !important;
                font-weight: 600 !important;
                color: #0F0F0F !important;
                line-height: 1.2 !important;
                letter-spacing: -0.8px !important;
                margin: 0 auto 20px auto !important;
                text-align: center !important;
            }

            [data-id="01a9d2a"] br,
            .e-01a9d2a-6018b41 br {
                display: none !important;
            }

            .elementor-element-b95ab32,
            [data-id="b95ab32"] {
                display: block !important;
                text-align: center !important;
                margin: 0 auto 20px auto !important;
            }

            .elementor-element-c946a02,
            [data-id="c946a02"] {
                display: flex !important;
                flex-direction: row !important;
                flex-wrap: nowrap !important;
                overflow-x: auto !important;
                overflow-y: hidden !important;
                width: 100% !important;
                max-width: 100% !important;
                gap: 16px !important;
                padding: 10px 10px 20px 10px !important;
                -webkit-overflow-scrolling: touch !important;
                scroll-snap-type: x mandatory !important;
                scrollbar-width: none !important;
                justify-content: flex-start !important;
                box-sizing: border-box !important;
            }

            .elementor-element-c946a02::-webkit-scrollbar,
            [data-id="c946a02"]::-webkit-scrollbar {
                display: none !important;
            }

            .elementor-element-f64cd23,
            [data-id="f64cd23"],
            .elementor-element-a6469e6,
            [data-id="a6469e6"],
            .elementor-element-5600e8d,
            [data-id="5600e8d"] {
                flex: 0 0 78% !important;
                min-width: 250px !important;
                max-width: 280px !important;
                scroll-snap-align: start !important;
                display: flex !important;
                flex-direction: column !important;
                align-items: flex-start !important;
                text-align: left !important;
            }
        }

        /* Disable Sticky Product Image on Mobile & Tablet */
        @media (max-width: 991px) {
            .custom-product-image-col {
                position: static !important;
                top: auto !important;
            }
        }

        /* Numbered Feature List Alignment Fix (f2865ab) */
        [data-id="f2865ab"] {
            align-items: center !important;
        }

        [data-id="f2865ab"] > .e-con,
        [data-id="f2865ab"] .elementor-element.e-con,
        [data-id="62255ad"],
        [data-id="35bf586"],
        [data-id="2b6a9d0"],
        [data-id="1f92c9c"] {
            display: flex !important;
            flex-direction: row !important;
            align-items: center !important;
            justify-content: flex-start !important;
            gap: 12px !important;
        }

        [data-id="f2865ab"] .elementor-widget-icon,
        [data-id="69c1bd9"],
        [data-id="51b5d64"],
        [data-id="84efbab"],
        [data-id="c1c0524"] {
            margin: 0 !important;
            padding: 0 !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            flex-shrink: 0 !important;
            align-self: center !important;
            width: auto !important;
            line-height: 1 !important;
        }

        [data-id="f2865ab"] .elementor-icon-wrapper {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            line-height: 1 !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        [data-id="f2865ab"] .elementor-icon {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            text-align: center !important;
            line-height: 1 !important;
            margin: 0 !important;
            width: 32px !important;
            height: 32px !important;
        }

        [data-id="f2865ab"] .elementor-icon i,
        [data-id="f2865ab"] .elementor-icon svg {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            line-height: 1 !important;
            vertical-align: middle !important;
            margin: 0 !important;
        }

        [data-id="f2865ab"] p,
        [data-id="f2865ab"] .e-paragraph-base,
        [data-id="45dca67"],
        [data-id="03e24db"],
        [data-id="77aff1c"],
        [data-id="e8e7777"] {
            margin: 0 !important;
            padding: 0 !important;
            line-height: 1.35 !important;
            display: flex !important;
            align-items: center !important;
            align-self: center !important;
        }
    </style>
    <?php
}, 999 );

add_action( 'wp_footer', function() {
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var el = document.querySelector('[data-id="0169cee"]');
        if (el) {
            var bTags = el.querySelectorAll('b, strong');
            bTags.forEach(function(tag) {
                var parent = tag.parentNode;
                while (tag.firstChild) {
                    parent.insertBefore(tag.firstChild, tag);
                }
                parent.removeChild(tag);
            });
        }

        // Clean MS Word / Times New Roman inline styles from 403530a
        var msoElements = document.querySelectorAll('[data-id="403530a"], .elementor-element-403530a, .MsoNormal, [style*="Times New Roman"]');
        msoElements.forEach(function(item) {
            item.removeAttribute('style');
            item.classList.remove('MsoNormal');
            var styledNodes = item.querySelectorAll('*[style]');
            styledNodes.forEach(function(node) {
                node.removeAttribute('style');
                node.classList.remove('MsoNormal');
            });
        });

        // Fix any invalid team links if present
        var badLinks = document.querySelectorAll('a[href*="http://teams/"]');
        badLinks.forEach(function(link) {
            link.href = link.href.replace('http://teams/', '/teams/');
        });

        // Ensure About Us team social icons are pure white
        function fixAboutTeamSocialIcons() {
            var containers = document.querySelectorAll('[data-id="8294ddf"], [data-id="d1e2194"], [data-id="0c9cb54"], .elementor-element-8294ddf, .elementor-element-d1e2194, .elementor-element-0c9cb54');
            containers.forEach(function(wrap) {
                var svgBoxes = wrap.querySelectorAll('.e-svg-base');
                if (svgBoxes.length >= 1) {
                    var linkedinBox = svgBoxes[0];
                    if (!linkedinBox.getAttribute('aria-label')) {
                        linkedinBox.setAttribute('aria-label', 'LinkedIn');
                    }
                }
                if (svgBoxes.length >= 2) {
                    var twitterBox = svgBoxes[1];
                    if (!twitterBox.getAttribute('aria-label')) {
                        twitterBox.setAttribute('aria-label', 'X (Twitter)');
                    }
                }
            });
        }
        fixAboutTeamSocialIcons();

        // Mobile Parallax Scroll Effect for Careers Gallery
        function initMobileGalleryParallax() {
            if (window.innerWidth > 768) return;
            if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') return;

            var galleryWidgets = document.querySelectorAll(
                '[data-id="377f540"] .elementor-widget-image, .elementor-element-377f540 .elementor-widget-image'
            );
            if (!galleryWidgets.length) return;

            galleryWidgets.forEach(function(widget) {
                var img = widget.querySelector('img');
                if (!img) return;

                widget.style.overflow = 'hidden';

                gsap.fromTo(img, 
                    { y: -30, scale: 1.1 },
                    {
                        y: 30,
                        scale: 1.1,
                        ease: 'none',
                        scrollTrigger: {
                            trigger: widget,
                            start: 'top bottom',
                            end: 'bottom top',
                            scrub: 0.8
                        }
                    }
                );
            });
        }
        initMobileGalleryParallax();
        window.addEventListener('resize', initMobileGalleryParallax);

        // Single Team Member Sticky Image & Social Controller (Pins entire left column on desktop)
        function initSingleTeamSticky() {
            var isTeamPage = document.body.classList.contains('single-teams') || 
                             document.body.classList.contains('single-team') || 
                             document.body.classList.contains('single-quanto_team') || 
                             window.location.pathname.indexOf('/teams/') !== -1 || 
                             window.location.pathname.indexOf('/team/') !== -1;

            if (!isTeamPage) return;

            var memberImgs = document.querySelectorAll('.elementor-widget-image img, .elementor-widget-theme-post-featured-image img, .elementor-image img');
            var targetCol = null;

            memberImgs.forEach(function(img) {
                if (img.closest('header, #quanto-header-desktop, footer, .header, nav, .elementor-location-header')) return;
                if (!targetCol) {
                    var col = img.closest('.elementor-column') || img.closest('.e-con');
                    if (col) {
                        targetCol = col;
                    }
                }
            });

            if (targetCol) {
                targetCol.classList.add('cmr-team-sticky-col');

                // Disable GSAP parallax on member image so it never translates over social icons
                var parallaxImgs = targetCol.querySelectorAll('#mas_smooth_scroller, [data-speed], .elementor-widget-quanto-feature-image');
                parallaxImgs.forEach(function(pEl) {
                    pEl.removeAttribute('data-speed');
                    if (pEl.id === 'mas_smooth_scroller') pEl.removeAttribute('id');
                    pEl.style.setProperty('transform', 'none', 'important');
                });

                if (window.innerWidth < 769) {
                    targetCol.style.removeProperty('position');
                    targetCol.style.removeProperty('top');
                    targetCol.style.removeProperty('align-self');
                    targetCol.style.removeProperty('z-index');
                }
            }

            if (window.ScrollTrigger) {
                setTimeout(function() {
                    ScrollTrigger.refresh();
                }, 100);
                setTimeout(function() {
                    ScrollTrigger.refresh();
                }, 500);
                setTimeout(function() {
                    ScrollTrigger.refresh();
                }, 1500);
            }
        }
        initSingleTeamSticky();
        window.addEventListener('resize', initSingleTeamSticky);
        window.addEventListener('load', initSingleTeamSticky);
    });
    </script>
    <?php
}, 999 );

/**
 * Auto-sync enhanced Quanto_Process widget to quanto-core plugin
 */
add_action( 'init', 'quanto_sync_process_widget_to_plugin', 1 );
function quanto_sync_process_widget_to_plugin() {
    $theme_widget = get_template_directory() . '/inc/widgets/process.php';
    $plugin_widget = WP_PLUGIN_DIR . '/quanto-core/addons/widgets/process.php';

    if ( file_exists( $theme_widget ) && file_exists( $plugin_widget ) ) {
        $theme_content = file_get_contents( $theme_widget );
        $plugin_content = file_get_contents( $plugin_widget );

        if ( strpos( $plugin_content, 'card_bg_color' ) === false || md5( $theme_content ) !== md5( $plugin_content ) ) {
            if ( is_writable( $plugin_widget ) ) {
                @file_put_contents( $plugin_widget, $theme_content );
            }
        }
    }
}

/**
 * Redirect /quarterly-results/ to external financial results page
 */
add_action( 'template_redirect', 'cmr_redirect_quarterly_results_to_cmrsl' );
function cmr_redirect_quarterly_results_to_cmrsl() {
    if ( is_admin() ) {
        return;
    }
    if ( function_exists( 'quanto_is_elementor_editor' ) && quanto_is_elementor_editor() ) {
        return;
    }
    if ( is_page( 'quarterly-results' ) || ( isset( $_SERVER['REQUEST_URI'] ) && preg_match( '#^/quarterly-results/?(\?.*)?$#i', $_SERVER['REQUEST_URI'] ) ) ) {
        wp_redirect( 'https://www.cmrsl.net/financial-results/', 301 );
        exit;
    }
}

/**
 * Modal Headings and Text Updates
 * 1. Subscriber form heading -> "CMR Connect Subscribe"
 * 2. CMR GTM form heading -> "CMR GTM partner with us"
 * 3. Talk to Analyst modal heading -> "Request a Consultation"
 */
add_filter( 'elementor/frontend/the_content', function( $content ) {
    if ( is_admin() && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
        return $content;
    }
    // Subscriber form checkboxes
    if ( strpos( $content, 'custom-subscribe-form' ) !== false || strpos( $content, 'Stay Ahead with CMR' ) !== false || strpos( $content, '7832' ) !== false || strpos( $content, '7849' ) !== false ) {
        $content = str_replace( 'CMR Pulse', 'CMR GTM', $content );
    }
    return $content;
}, 999 );

add_filter( 'wpcf7_form_elements', function( $content ) {
    if ( strpos( $content, 'CMR Pulse' ) !== false ) {
        $content = str_replace( 'CMR Pulse', 'CMR GTM', $content );
    }
    return $content;
}, 999 );

add_filter( 'wpcf7_posted_data', function( $posted_data ) {
    if ( isset( $posted_data['subscriptions'] ) && is_array( $posted_data['subscriptions'] ) ) {
        foreach ( $posted_data['subscriptions'] as $k => $val ) {
            if ( $val === 'CMR Pulse' ) {
                $posted_data['subscriptions'][$k] = 'CMR GTM';
            }
        }
    }
    return $posted_data;
}, 999 );

/**
 * Enable <br> tags in Quanto Team member designations
 */
add_filter( 'elementor/widget/render_content', function( $widget_content, $widget ) {
    if ( $widget->get_name() === 'quanto_team' || strpos( $widget_content, 'team-member-position' ) !== false ) {
        // Decode escaped <br> tags in team-member-position
        $widget_content = preg_replace_callback( '/class="([^"]*team-member-position[^"]*)"([^>]*)>(.*?)<\/(span|p|div)>/is', function( $matches ) {
            $decoded = str_ireplace( array( '&lt;br&gt;', '&lt;br/&gt;', '&lt;br /&gt;' ), '<br>', $matches[3] );
            return 'class="' . $matches[1] . '"' . $matches[2] . '>' . $decoded . '</' . $matches[4] . '>';
        }, $widget_content );
    }
    return $widget_content;
}, 99, 2 );

// General the_content filter fallback
add_filter( 'the_content', function( $content ) {
    if ( strpos( $content, 'team-member-position' ) !== false ) {
        $content = preg_replace_callback( '/class="([^"]*team-member-position[^"]*)"([^>]*)>(.*?)<\/(span|p|div)>/is', function( $matches ) {
            $decoded = str_ireplace( array( '&lt;br&gt;', '&lt;br/&gt;', '&lt;br /&gt;' ), '<br>', $matches[3] );
            return 'class="' . $matches[1] . '"' . $matches[2] . '>' . $decoded . '</' . $matches[4] . '>';
        }, $content );
    }
    return $content;
}, 999 );

// Client-side execution in footer and Elementor frontend hooks to support both live site and Elementor editor iframe
add_action( 'wp_footer', 'cmr_enable_team_br_tags_js', 99999 );
function cmr_enable_team_br_tags_js() {
    ?>
    <script id="cmr-team-br-fix">
    (function() {
        function decodeTeamBrTags() {
            var positions = document.querySelectorAll('.team-member-position, .quanto-team-box .team-member-position, .cmr-team-card-role');
            positions.forEach(function(el) {
                if (el && el.innerHTML && (el.innerHTML.indexOf('&lt;br&gt;') !== -1 || el.innerHTML.indexOf('&lt;br/&gt;') !== -1 || el.innerHTML.indexOf('&lt;br /&gt;') !== -1 || el.textContent.indexOf('<br>') !== -1 || el.textContent.indexOf('<br/>') !== -1 || el.textContent.indexOf('<br />') !== -1)) {
                    el.innerHTML = el.innerHTML
                        .replace(/&lt;br\s*\/?&gt;/gi, '<br>')
                        .replace(/&amp;lt;br\s*\/?&amp;gt;/gi, '<br>');
                }
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', decodeTeamBrTags);
        } else {
            decodeTeamBrTags();
        }
        window.addEventListener('load', decodeTeamBrTags);
        setTimeout(decodeTeamBrTags, 100);
        setTimeout(decodeTeamBrTags, 500);
        setTimeout(decodeTeamBrTags, 1500);

        if (window.jQuery) {
            jQuery(window).on('elementor/frontend/init', function() {
                if (window.elementorFrontend && elementorFrontend.hooks) {
                    elementorFrontend.hooks.addAction('frontend/element_ready/quanto_team.default', function() {
                        setTimeout(decodeTeamBrTags, 50);
                        setTimeout(decodeTeamBrTags, 300);
                    });
                }
            });
        }
    })();
    </script>
    <?php
}


