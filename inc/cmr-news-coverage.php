<?php
// 5. Media Coverage Shortcode
add_shortcode( 'cmr_media_coverage', 'cmr_media_coverage_shortcode' );
add_action( 'wp_enqueue_scripts', 'cmr_media_coverage_enqueue_assets' );
function cmr_media_coverage_enqueue_assets() {
    wp_enqueue_style( 'cmr-news-style', get_template_directory_uri() . '/assets/css/cmr-news.css', array(), time() );
    wp_enqueue_script( 'cmr-news-script', get_template_directory_uri() . '/assets/js/cmr-news.js', array('jquery'), time(), true );
    wp_localize_script( 'cmr-news-script', 'cmr_news_ajax', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'nonce'    => wp_create_nonce( 'cmr_media_coverage_nonce' )
    ) );
}

function cmr_media_coverage_shortcode( $atts ) {
    // Force CSS load directly in shortcode for Elementor and other page builders
    echo '<link rel="stylesheet" href="' . esc_url( get_template_directory_uri() . '/assets/css/cmr-news.css?ver=' . time() ) . '">';

    // Get publishers for the filter
    $publishers = cmr_get_unique_publishers();
    
    ob_start();
    ?>
    <div class="cmr-media-coverage-wrapper" id="cmr-in-news">
        <div class="cmr-mc-header">
            <h2>CMR Media Coverage</h2>
            <p>Track emerging shifts, growth signals, and market movements in real time.</p>
        </div>
        
        <div class="cmr-mc-filter-bar">
            <div class="cmr-mc-filters">
                <button class="cmr-mc-filter-btn active" data-publisher="all">All</button>
                <?php 
                $count = 0;
                $dropdown_publishers = array();
                foreach ( $publishers as $pub ) {
                    if ( empty($pub) ) continue;
                    if ( $count < 4 ) {
                        echo '<button class="cmr-mc-filter-btn" data-publisher="' . esc_attr($pub) . '">' . esc_html($pub) . '</button>';
                    } else {
                        $dropdown_publishers[] = $pub;
                    }
                    $count++;
                }
                
                if ( !empty($dropdown_publishers) ) : ?>
                    <div class="cmr-mc-filter-dropdown">
                        <button class="cmr-mc-dropdown-toggle">More <svg width="10" height="6" viewBox="0 0 10 6" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></button>
                        <div class="cmr-mc-dropdown-menu">
                            <?php foreach ( $dropdown_publishers as $pub ) : ?>
                                <button class="cmr-mc-filter-btn" data-publisher="<?php echo esc_attr($pub); ?>"><?php echo esc_html($pub); ?></button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="cmr-mc-search">
                <input type="text" id="cmr-mc-search-input" placeholder="Search by name">
                <button class="cmr-mc-search-btn" id="cmr-mc-search-btn" aria-label="Search Coverage">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                </button>
            </div>
        </div>
        
        <div class="cmr-mc-results" id="cmr-mc-results">
            <?php echo cmr_get_media_coverage_html(); ?>
        </div>
        
        <div class="cmr-mc-load-more-wrap" id="cmr-mc-load-more-wrap">
            <button class="cmr-mc-load-more" id="cmr-mc-load-more" data-page="1">Load More</button>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

function cmr_get_unique_publishers() {
    global $wpdb;
    $publishers = $wpdb->get_col("
        SELECT DISTINCT meta_value FROM {$wpdb->postmeta} pm
        JOIN {$wpdb->posts} p ON p.ID = pm.post_id
        WHERE pm.meta_key = '_cmr_news_publisher_name'
        AND p.post_type = 'cmr_news'
        AND p.post_status = 'publish'
        AND meta_value != ''
    ");
    return $publishers;
}

// AJAX Handler
add_action( 'wp_ajax_cmr_load_media_coverage', 'cmr_ajax_load_media_coverage' );
add_action( 'wp_ajax_nopriv_cmr_load_media_coverage', 'cmr_ajax_load_media_coverage' );

function cmr_ajax_load_media_coverage() {
    check_ajax_referer( 'cmr_media_coverage_nonce', 'nonce' );
    
    $page = isset( $_POST['page'] ) ? intval( $_POST['page'] ) : 1;
    $publisher = isset( $_POST['publisher'] ) ? sanitize_text_field( $_POST['publisher'] ) : 'all';
    $search = isset( $_POST['search'] ) ? sanitize_text_field( $_POST['search'] ) : '';
    
    $html = cmr_get_media_coverage_html( $page, $publisher, $search );
    
    $args = cmr_get_media_coverage_query_args( $page, $publisher, $search );
    $query = new WP_Query( $args );
    $has_more = $query->max_num_pages > $page;
    
    wp_send_json_success( array(
        'html' => $html,
        'has_more' => $has_more,
        'is_first_page' => $page === 1
    ) );
}

function cmr_get_media_coverage_query_args( $page = 1, $publisher = 'all', $search = '' ) {
    $args = array(
        'post_type'      => 'cmr_news',
        'post_status'    => 'publish',
        'posts_per_page' => 7, // 1 featured + 6 grid items
        'paged'          => $page,
        'tax_query'      => array(
            'relation' => 'AND',
            array(
                'taxonomy' => 'cmr_news_category',
                'field'    => 'slug',
                'terms'    => 'cmr-in-news',
            ),
            array(
                'taxonomy' => 'cmr_news_category',
                'field'    => 'slug',
                'terms'    => array('media-releases', 'media-release', 'media_releases', 'media_release', 'press-releases', 'press-release', 'pressreleases', 'press-releases-2', 'press-release-2'),
                'operator' => 'NOT IN'
            ),
        ),
    );
    
    if ( $publisher !== 'all' && !empty($publisher) ) {
        $args['meta_query'] = array(
            array(
                'key'     => '_cmr_news_publisher_name',
                'value'   => $publisher,
                'compare' => '=',
            ),
        );
    }
    
    if ( !empty($search) ) {
        $args['s'] = $search;
    }
    
    return $args;
}

function cmr_get_media_coverage_html( $page = 1, $publisher = 'all', $search = '' ) {
    $args = cmr_get_media_coverage_query_args( $page, $publisher, $search );
    $query = new WP_Query( $args );
    
    if ( ! $query->have_posts() ) {
        if ( $page === 1 ) {
            return '<p class="cmr-mc-no-results">No media coverage found.</p>';
        }
        return '';
    }
    
    ob_start();
    
    $count = 0;
    $is_first_page = ( $page === 1 );
    
    if ( $is_first_page ) {
        echo '<div class="cmr-mc-grid">';
    }
    
    while ( $query->have_posts() ) {
        $query->the_post();
        $post_id = get_the_ID();
        
        $bg_image_id = get_post_thumbnail_id();
        $bg_image = $bg_image_id ? wp_get_attachment_url( $bg_image_id ) : '';
        
        $logo_id = get_post_meta( $post_id, '_cmr_news_source_logo_id', true );
        $logo_url = $logo_id ? wp_get_attachment_url( $logo_id ) : '';
        
        $pub_name = get_post_meta( $post_id, '_cmr_news_publisher_name', true );
        $reading_time = get_post_meta( $post_id, '_cmr_news_reading_time', true );
        $ext_link = get_post_meta( $post_id, '_cmr_news_external_link', true );
        $document_id = get_post_meta( $post_id, '_cmr_news_document_id', true );
        
        $link = '#';
        $target = '_self';
        
        if ( $document_id ) {
            $link = wp_get_attachment_url( $document_id );
            $target = '_blank';
        } elseif ( $ext_link ) {
            $link = $ext_link;
            $target = '_blank';
        } else {
            $link = get_permalink();
        }
        
        $date = get_the_date('M j, Y');
        
        // The very first item on page 1 is featured
        if ( $is_first_page && $count === 0 ) {
            ?>
            <div class="cmr-mc-featured">
                <a href="<?php echo esc_url($link); ?>" target="<?php echo esc_attr($target); ?>" class="cmr-mc-featured-inner">
                    <div class="cmr-mc-featured-image">
                        <?php if ($bg_image): ?>
                            <img src="<?php echo esc_url($bg_image); ?>" class="cmr-mc-bg" alt="<?php the_title_attribute(); ?>">
                        <?php endif; ?>
                        <span class="cmr-mc-trending-badge"><svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5 0L6.12257 3.87743L10 5L6.12257 6.12257L5 10L3.87743 6.12257L0 5L3.87743 3.87743L5 0Z" fill="currentColor"/></svg> TRENDING</span>
                        <?php if ($logo_url): ?>
                            <img src="<?php echo esc_url($logo_url); ?>" class="cmr-mc-logo" alt="Publisher Logo">
                        <?php endif; ?>
                    </div>
                    <div class="cmr-mc-featured-content">
                        <div class="cmr-mc-meta">
                            <span class="cmr-mc-publisher"><?php echo esc_html($pub_name); ?></span> <span class="cmr-mc-sep">|</span> <span class="cmr-mc-date">Published <?php echo esc_html($date); ?></span>
                        </div>
                        <h3 class="cmr-mc-title"><?php the_title(); ?></h3>
                        <div class="cmr-mc-excerpt"><?php echo wp_trim_words( get_the_excerpt(), 20, '...' ); ?></div>
                        <span class="cmr-mc-view-btn">View Coverage <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 11L11 1M11 1H3M11 1V9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
                    </div>
                </a>
            </div>
            <?php
            // Close the featured section, open grid for the rest
            echo '<div class="cmr-mc-grid-inner">';
        } else {
            // Grid items
            if ( !$is_first_page && $count === 0 ) {
                // If it's page 2+, we don't have a featured item, just start the grid
                echo '<div class="cmr-mc-grid-inner">';
            }
            ?>
            <div class="cmr-mc-card">
                <a href="<?php echo esc_url($link); ?>" target="<?php echo esc_attr($target); ?>" class="cmr-mc-card-inner">
                    <div class="cmr-mc-card-image">
                        <?php if ($bg_image): ?>
                            <img src="<?php echo esc_url($bg_image); ?>" class="cmr-mc-bg" alt="<?php the_title_attribute(); ?>">
                        <?php endif; ?>
                        <?php if ($logo_url): ?>
                            <img src="<?php echo esc_url($logo_url); ?>" class="cmr-mc-logo" alt="Publisher Logo">
                        <?php endif; ?>
                    </div>
                    <div class="cmr-mc-card-content">
                        <div class="cmr-mc-meta-row">
                            <div class="cmr-mc-meta-left">
                                <span class="cmr-mc-publisher"><?php echo esc_html($pub_name); ?></span> <span class="cmr-mc-sep">|</span> <span class="cmr-mc-date">Published <?php echo esc_html($date); ?></span>
                            </div>
                            <?php if ($reading_time): ?>
                                <div class="cmr-mc-meta-right"><?php echo esc_html($reading_time); ?> mins</div>
                            <?php endif; ?>
                        </div>
                        <h3 class="cmr-mc-title"><?php the_title(); ?></h3>
                        <span class="cmr-mc-view-btn">View Coverage <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 11L11 1M11 1H3M11 1V9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
                    </div>
                </a>
            </div>
            <?php
        }
        
        $count++;
    }
    
    // Close grid inner
    if ( $count > 0 && ( $is_first_page || !$is_first_page ) ) {
        // Wait, if it's page 1 and ONLY 1 item (featured), grid-inner was opened.
        if ( $is_first_page || $count > 0 ) {
            echo '</div>'; // close cmr-mc-grid-inner
        }
    }
    
    if ( $is_first_page ) {
        echo '</div>'; // close cmr-mc-grid
    }
    
    wp_reset_postdata();
    return ob_get_clean();
}
