<?php
/**
 * AJAX Handlers for CMR Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'wp_ajax_cmr_load_more_intel', 'cmr_load_more_intel_ajax' );
add_action( 'wp_ajax_nopriv_cmr_load_more_intel', 'cmr_load_more_intel_ajax' );

function cmr_load_more_intel_ajax() {
    $paged = isset( $_POST['page'] ) ? intval( $_POST['page'] ) : 1;
    $base_url = isset( $_POST['base_url'] ) ? sanitize_text_field( $_POST['base_url'] ) : '/';
    $search = isset( $_POST['search'] ) ? sanitize_text_field( $_POST['search'] ) : '';
    
    $query_args = array(
        'post_type'      => array( 'post', 'cmr_news' ),
        'posts_per_page' => 6,
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC',
        'paged'          => $paged,
        'tax_query'      => array(
            'relation' => 'OR',
            array(
                'taxonomy' => 'category',
                'field'    => 'slug',
                'terms'    => array( 'industry-intelligence' ),
            ),
            array(
                'taxonomy' => 'cmr_news_category',
                'field'    => 'slug',
                'terms'    => array( 'industry-intelligence' ),
            ),
        ),

    );

    if ( !empty($search) ) {
        $query_args['s'] = $search;
    }

    $insights_query = new WP_Query( $query_args );

    ob_start();
    if ( $insights_query->have_posts() ) {
        while ( $insights_query->have_posts() ) {
            $insights_query->the_post();
            $post_title = get_the_title();
            $post_link = get_permalink();
            $thumbnail_url = get_the_post_thumbnail_url( get_the_ID(), 'full' );
            if ( ! $thumbnail_url ) {
                $thumbnail_url = quanto_get_placeholder_image_uri();
            }
            
            $content = get_post_field( 'post_content', get_the_ID() );
            $word_count = str_word_count( strip_tags( $content ) );
            $read_time = ceil( $word_count / 200 );
            if ($read_time < 1) $read_time = 1;
            
            ?>
            <div class="intel-card" style="text-align: left; align-items: flex-start;">
                <div class="intel-card-img" style="width: 100%; aspect-ratio: 16 / 9; overflow: hidden; border-radius: 8px; margin-bottom: 14px; text-align: left; margin-left: 0; margin-right: auto; display: block;">
                    <a href="<?php echo esc_url( $post_link ); ?>" style="display: block; width: 100%; height: 100%; text-align: left; margin: 0; padding: 0;">
                        <img src="<?php echo esc_url( $thumbnail_url ); ?>" alt="<?php echo esc_attr( $post_title ); ?>" style="width: 100%; height: 100%; aspect-ratio: 16 / 9; object-fit: cover; object-position: center left; display: block; border-radius: 8px; margin: 0; margin-left: 0; margin-right: auto;" />
                    </a>
                </div>
                <div class="intel-card-content">
                    <div class="intel-meta" style="display: flex !important; justify-content: space-between !important; align-items: center !important; width: 100% !important; margin-bottom: 14px !important;">
                        <span class="intel-category">Industry Intelligence</span>
                        <span class="intel-read-time" style="margin-left: auto !important;"><?php echo esc_html( $read_time ); ?> min read</span>
                    </div>
                    <h3 class="intel-title">
                        <a href="<?php echo esc_url( $post_link ); ?>"><?php echo esc_html( $post_title ); ?></a>
                    </h3>
                    <div class="intel-excerpt">
                        <?php 
                        $p_id = get_the_ID();
                        $excerpt = function_exists( 'quanto_get_clean_post_excerpt' ) ? quanto_get_clean_post_excerpt( $p_id, 15 ) : wp_trim_words( get_the_excerpt( $p_id ), 15, '...' );
                        echo wp_kses_post( $excerpt ); 
                        ?>
                    </div>
                    <a href="<?php echo esc_url( $post_link ); ?>" class="intel-read-more">
                        More Details 
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="7" y1="17" x2="17" y2="7"></line>
                            <polyline points="7 7 17 7 17 17"></polyline>
                        </svg>
                    </a>
                </div>
            </div>
            <?php
        }
    }
    $html = ob_get_clean();

    $pagination = '';
    if ( $paged >= 3 || $paged >= $insights_query->max_num_pages ) {
        // Construct standard pagination links for the current page
        $full_base = home_url( $base_url );
        $pagination = paginate_links( array(
            'base'    => trailingslashit( $full_base ) . '%_%',
            'format'  => '?paged=%#%',
            'total'   => $insights_query->max_num_pages,
            'current' => $paged,
            'prev_text' => '<svg width="12" height="18" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7 1L1 7L7 13" stroke="#6A35FF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
            'next_text' => '<svg width="12" height="18" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 1L7 7L1 13" stroke="#6A35FF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        ) );
    }

    wp_reset_postdata();

    wp_send_json_success( array(
        'html' => $html,
        'pagination' => $pagination
    ) );
}

add_action( 'wp_ajax_cmr_load_more_media_releases', 'cmr_load_more_media_releases_ajax' );
add_action( 'wp_ajax_nopriv_cmr_load_more_media_releases', 'cmr_load_more_media_releases_ajax' );

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

function cmr_load_more_media_releases_ajax() {
    $paged  = isset( $_POST['page'] ) ? max( 1, intval( $_POST['page'] ) ) : 1;
    $year   = isset( $_POST['year'] ) ? sanitize_text_field( $_POST['year'] ) : '';
    $search = isset( $_POST['search'] ) ? sanitize_text_field( $_POST['search'] ) : '';
    
    $query = cmr_get_unique_media_releases( $paged, $year, $search, 6 );

    ob_start();
    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            $post_id = get_the_ID();
            $title = get_the_title();
            $link = get_permalink();
            $excerpt = function_exists( 'quanto_get_clean_post_excerpt' ) ? quanto_get_clean_post_excerpt( $post_id, 18 ) : wp_trim_words( get_the_excerpt( $post_id ), 18, '...' );
            $bg_image = get_the_post_thumbnail_url( $post_id, 'medium_large' );
            if ( ! $bg_image ) {
                $bg_image = quanto_get_placeholder_image_uri();
            }
            
            $content = get_post_field( 'post_content', $post_id );
            $word_count = str_word_count( strip_tags( $content ) );
            $read_time = ceil( $word_count / 200 );
            if ($read_time < 1) $read_time = 1;
            $date = get_the_date('d M Y');
            ?>
            <div class="cmr-mrg-card">
                <div class="cmr-mrg-card-img-wrap">
                    <a href="<?php echo esc_url($link); ?>" style="display: block; width: 100%; height: 100%;">
                        <img src="<?php echo esc_url($bg_image); ?>" alt="<?php echo esc_attr($title); ?>" style="width: 100% !important; height: 100% !important; object-fit: cover !important; display: block !important; margin: 0 !important; padding: 0 !important;">
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
    }
    $html = ob_get_clean();

    $total_pages = $query->max_num_pages;
    $has_more = $paged < $total_pages;

    $base_url = isset( $_POST['base_url'] ) ? sanitize_text_field( $_POST['base_url'] ) : '/';
    $pagination = '';
    if ( $paged >= 3 || $paged >= $total_pages ) {
        $full_base = home_url( $base_url );
        $pagination = paginate_links( array(
            'base'    => trailingslashit( $full_base ) . '%_%',
            'format'  => '?paged=%#%',
            'total'   => $total_pages,
            'current' => $paged,
            'prev_text' => '<svg width="12" height="18" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7 1L1 7L7 13" stroke="#6A35FF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
            'next_text' => '<svg width="12" height="18" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 1L7 7L1 13" stroke="#6A35FF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        ) );
    }

    wp_reset_postdata();

    wp_send_json_success( array(
        'html' => $html,
        'has_more' => $has_more,
        'pagination' => $pagination
    ) );
}

add_action( 'wp_ajax_cmr_load_more_smb_connect', 'cmr_load_more_smb_connect_ajax' );
add_action( 'wp_ajax_nopriv_cmr_load_more_smb_connect', 'cmr_load_more_smb_connect_ajax' );

function cmr_load_more_smb_connect_ajax() {
    $paged  = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $year   = isset($_POST['year']) ? sanitize_text_field($_POST['year']) : '';
    $search = isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '';
    
    if ( empty($year) && empty($search) ) {
        $unique_ids = cmr_get_unique_smb_post_ids();
        $offset_base = 0;
        $offset = $offset_base + ( ($paged - 1) * 6 );
        
        $sliced_ids = array_slice( $unique_ids, $offset, 6 );
        
        if ( empty($sliced_ids) ) {
            wp_send_json_success(array('html' => '', 'has_more' => false));
        }
        
        $args = array(
            'post_type'      => array( 'post', 'cmr_news', 'cmr_media' ),
            'post__in'       => $sliced_ids,
            'orderby'        => 'post__in',
            'posts_per_page' => 6,
        );
        $query = new WP_Query( $args );
        
        $total_pages = ceil( max( 0, count( $unique_ids ) ) / 6 );
    } else {
        $offset_base = 0;
        $offset = $offset_base + ( ($paged - 1) * 6 );
        
        $args = array(
            'post_type'      => array( 'post', 'cmr_news', 'cmr_media' ),
            'category_name'  => 'smb-connect,smb-connect-industry-connect,smb',
            'posts_per_page' => 6,
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC',
            'offset'         => $offset
        );

        if ( !empty($year) ) {
            $args['year'] = $year;
        }
        if ( !empty($search) ) {
            $args['s'] = $search;
        }
        
        $query = new WP_Query( $args );
        $total_pages = ceil( max( 0, $query->found_posts - $offset_base ) / 6 );
    }

    ob_start();
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
            if ( ! $bg_image ) {
                $bg_image = quanto_get_placeholder_image_uri();
            }
            
            $content = get_post_field( 'post_content', $post_id );
            $word_count = str_word_count( strip_tags( $content ) );
            $read_time = ceil( $word_count / 200 );
            if ($read_time < 1) $read_time = 1;
            $date = get_the_date('d M Y');
            ?>
            <div class="cmr-smbcgd-card">
                <div class="cmr-smbcgd-card-img-wrap">
                    <a href="<?php echo esc_url($link); ?>" style="display: block; width: 100%; height: 100%;">
                        <img src="<?php echo esc_url($bg_image); ?>" alt="<?php echo esc_attr($title); ?>" style="width: 100% !important; height: 100% !important; object-fit: cover !important; display: block !important; margin: 0 !important; padding: 0 !important;">
                    </a>
                </div>
                <div class="cmr-smbcgd-card-meta">
                    <div class="cmr-smbcgd-card-label">SMB Connect <span>|</span> <?php echo esc_html($date); ?></div>
                    <div class="cmr-smbcgd-card-time"><?php echo esc_html($read_time); ?> min read</div>
                </div>
                <h3 class="cmr-smbcgd-card-title">
                    <a href="<?php echo esc_url($link); ?>"><?php echo esc_html($title); ?></a>
                </h3>
                <p class="cmr-smbcgd-card-excerpt"><?php echo esc_html($excerpt); ?></p>
                <a href="<?php echo esc_url($link); ?>" class="cmr-smbcgd-card-link">
                    Read full Release 
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg>
                </a>
            </div>
            <?php
        }
    }
    $html = ob_get_clean();

    $has_more = $paged < $total_pages;

    $base_url = isset( $_POST['base_url'] ) ? sanitize_text_field( $_POST['base_url'] ) : '/';
    $pagination = '';
    if ( $paged >= 3 || $paged >= $total_pages ) {
        $full_base = home_url( $base_url );
        $pagination = paginate_links( array(
            'base'    => trailingslashit( $full_base ) . '%_%',
            'format'  => '?paged=%#%',
            'total'   => $total_pages,
            'current' => $paged,
            'prev_text' => '<svg width="12" height="18" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7 1L1 7L7 13" stroke="#6A35FF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
            'next_text' => '<svg width="12" height="18" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 1L7 7L1 13" stroke="#6A35FF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        ) );
    }

    wp_reset_postdata();

    wp_send_json_success( array(
        'html' => $html,
        'has_more' => $has_more,
        'pagination' => $pagination
    ) );
}

add_action( 'wp_ajax_cmr_load_more_enterprise_connect', 'cmr_load_more_enterprise_connect_ajax' );
add_action( 'wp_ajax_nopriv_cmr_load_more_enterprise_connect', 'cmr_load_more_enterprise_connect_ajax' );

function cmr_load_more_enterprise_connect_ajax() {
    $paged  = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $year   = isset($_POST['year']) ? sanitize_text_field($_POST['year']) : '';
    $search = isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '';
    
    if ( empty($year) && empty($search) ) {
        $unique_ids = cmr_get_unique_enterprise_post_ids();
        $offset_base = 0;
        $offset = $offset_base + ( ($paged - 1) * 6 );
        
        $sliced_ids = array_slice( $unique_ids, $offset, 6 );
        
        if ( empty($sliced_ids) ) {
            wp_send_json_success(array('html' => '', 'has_more' => false));
        }
        
        $args = array(
            'post_type'      => array( 'post', 'cmr_news', 'cmr_media' ),
            'post__in'       => $sliced_ids,
            'orderby'        => 'post__in',
            'posts_per_page' => 6,
        );
        $query = new WP_Query( $args );
        
        $total_pages = ceil( max( 0, count( $unique_ids ) ) / 6 );
    } else {
        $offset_base = 0;
        $offset = $offset_base + ( ($paged - 1) * 6 );
        
        global $wpdb;

        $year_cond = '';
        if ( !empty($year) ) {
            $year_cond = $wpdb->prepare( " AND YEAR(p.post_date) = %d ", $year );
        }
        
        $search_cond = '';
        if ( !empty($search) ) {
            $search_like = '%' . $wpdb->esc_like( $search ) . '%';
            $search_cond = $wpdb->prepare( " AND (p.post_title LIKE %s OR p.post_content LIKE %s) ", $search_like, $search_like );
        }

        $sql = "
            SELECT p.ID, p.post_title 
            FROM {$wpdb->posts} p
            INNER JOIN {$wpdb->term_relationships} tr ON p.ID = tr.object_id
            INNER JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
            INNER JOIN {$wpdb->terms} t ON tt.term_id = t.term_id
            WHERE p.post_type IN ('post', 'cmr_news', 'cmr_media') 
              AND p.post_status = 'publish' 
              AND (t.slug IN ('enterprise-connect', 'enterprise', 'enterprise_connect') OR t.name LIKE '%Enterprise Connect%' OR t.name LIKE '%Enterprise%')
              $year_cond
              $search_cond
            ORDER BY p.post_date DESC
            LIMIT 500
        ";
        
        $results = $wpdb->get_results($sql);
        $filtered_ids = array();
        $seen_titles = array();
        if ( $results ) {
            foreach ( $results as $row ) {
                $title = trim( $row->post_title );
                if ( ! isset( $seen_titles[ $title ] ) ) {
                    $seen_titles[ $title ] = true;
                    $filtered_ids[] = $row->ID;
                }
            }
        }
        
        $sliced_ids = array_slice( $filtered_ids, $offset, 6 );
        
        if ( empty($sliced_ids) ) {
            wp_send_json_success(array('html' => '', 'has_more' => false));
        }
        
        $args = array(
            'post_type'      => array( 'post', 'cmr_news', 'cmr_media' ),
            'post__in'       => $sliced_ids,
            'orderby'        => 'post__in',
            'posts_per_page' => 6,
        );
        $query = new WP_Query( $args );
        
        $total_pages = ceil( max( 0, count( $filtered_ids ) ) / 6 );
    }

    ob_start();
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
            if ( ! $bg_image ) {
                $bg_image = quanto_get_placeholder_image_uri();
            }
            
            $content = get_post_field( 'post_content', $post_id );
            $word_count = str_word_count( strip_tags( $content ) );
            $read_time = ceil( $word_count / 200 );
            if ($read_time < 1) $read_time = 1;
            $date = get_the_date('d M Y');
            ?>
            <div class="cmr-enterprisecgd-card">
                <div class="cmr-enterprisecgd-card-img-wrap">
                    <a href="<?php echo esc_url($link); ?>" style="display: block; width: 100%; height: 100%;">
                        <img src="<?php echo esc_url($bg_image); ?>" alt="<?php echo esc_attr($title); ?>" style="width: 100% !important; height: 100% !important; object-fit: cover !important; display: block !important; margin: 0 !important; padding: 0 !important;">
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
    }
    $html = ob_get_clean();

    $has_more = $paged < $total_pages;

    $base_url = isset( $_POST['base_url'] ) ? sanitize_text_field( $_POST['base_url'] ) : '/';
    $pagination = '';
    if ( $paged >= 3 || $paged >= $total_pages ) {
        $full_base = home_url( $base_url );
        $pagination = paginate_links( array(
            'base'    => trailingslashit( $full_base ) . '%_%',
            'format'  => '?paged=%#%',
            'total'   => $total_pages,
            'current' => $paged,
            'prev_text' => '<svg width="12" height="18" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7 1L1 7L7 13" stroke="#6A35FF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
            'next_text' => '<svg width="12" height="18" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 1L7 7L1 13" stroke="#6A35FF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        ) );
    }

    wp_reset_postdata();

    wp_send_json_success( array(
        'html' => $html,
        'has_more' => $has_more,
        'pagination' => $pagination
    ) );
}

add_action( 'wp_ajax_cmr_load_more_channel_connect', 'cmr_load_more_channel_connect_ajax' );
add_action( 'wp_ajax_nopriv_cmr_load_more_channel_connect', 'cmr_load_more_channel_connect_ajax' );

function cmr_load_more_channel_connect_ajax() {
    $paged  = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $year   = isset($_POST['year']) ? sanitize_text_field($_POST['year']) : '';
    $search = isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '';
    
    if ( empty($year) && empty($search) ) {
        $unique_ids = cmr_get_unique_channel_post_ids();
        $offset_base = 0;
        $offset = $offset_base + ( ($paged - 1) * 6 );
        
        $sliced_ids = array_slice( $unique_ids, $offset, 6 );
        if ( empty( $sliced_ids ) && $paged == 1 && ! empty( $unique_ids ) ) {
            $sliced_ids = array_slice( $unique_ids, 0, 6 );
        }
        
        if ( empty($sliced_ids) ) {
            wp_send_json_success(array('html' => '', 'has_more' => false));
        }
        
        $args = array(
            'post_type'      => array( 'post', 'cmr_news', 'cmr_media' ),
            'post__in'       => $sliced_ids,
            'orderby'        => 'post__in',
            'posts_per_page' => 6,
        );
        $query = new WP_Query( $args );
        
        $total_pages = ceil( max( 0, count( $unique_ids ) ) / 6 );
    } else {
        global $wpdb;

        // Build the same SQL as cmr_get_unique_channel_post_ids() but with year/search filtering
        $where_extra = '';
        if ( !empty($year) ) {
            $where_extra .= $wpdb->prepare( " AND YEAR(p.post_date) = %d", intval($year) );
        }
        if ( !empty($search) ) {
            $where_extra .= $wpdb->prepare( " AND p.post_title LIKE %s", '%' . $wpdb->esc_like($search) . '%' );
        }

        $sql = "
            SELECT p.ID, p.post_title
            FROM {$wpdb->posts} p
            INNER JOIN {$wpdb->term_relationships} tr ON p.ID = tr.object_id
            INNER JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
            INNER JOIN {$wpdb->terms} t ON tt.term_id = t.term_id
            WHERE p.post_type IN ('post', 'cmr_news', 'cmr_media')
              AND p.post_status = 'publish'
              AND (t.slug IN ('channel-connect', 'channel', 'channel_connect') OR t.name LIKE '%Channel Connect%' OR t.name LIKE '%Channel%')
              {$where_extra}
            ORDER BY p.post_date DESC
            LIMIT 500
        ";

        $results = $wpdb->get_results( $sql );

        $unique_ids = array();
        $seen_titles = array();
        if ( $results ) {
            foreach ( $results as $row ) {
                $title = trim( $row->post_title );
                if ( ! isset( $seen_titles[$title] ) ) {
                    $seen_titles[$title] = true;
                    $unique_ids[] = intval( $row->ID );
                }
            }
        }

        $total_posts  = count( $unique_ids );
        $offset       = ( $paged - 1 ) * 6;
        $total_pages  = ceil( $total_posts / 6 );
        $sliced_ids   = array_slice( $unique_ids, $offset, 6 );

        if ( empty($sliced_ids) ) {
            wp_send_json_success( array( 'html' => '', 'has_more' => false, 'pagination' => '' ) );
        }

        $args = array(
            'post_type'      => array( 'post', 'cmr_news', 'cmr_media' ),
            'post__in'       => $sliced_ids,
            'orderby'        => 'post__in',
            'posts_per_page' => 6,
        );
        $query = new WP_Query( $args );
    }

    ob_start();
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
            if ( ! $bg_image ) {
                $bg_image = quanto_get_placeholder_image_uri();
            }
            
            $content = get_post_field( 'post_content', $post_id );
            $word_count = str_word_count( strip_tags( $content ) );
            $read_time = ceil( $word_count / 200 );
            if ($read_time < 1) $read_time = 1;
            $date = get_the_date('d M Y');
            ?>
            <div class="cmr-channelcgd-card">
                <div class="cmr-channelcgd-card-img-wrap">
                    <a href="<?php echo esc_url($link); ?>" style="display: block; width: 100%; height: 100%;">
                        <img src="<?php echo esc_url($bg_image); ?>" alt="<?php echo esc_attr($title); ?>" style="width: 100% !important; height: 100% !important; object-fit: cover !important; display: block !important; margin: 0 !important; padding: 0 !important;">
                    </a>
                </div>
                <div class="cmr-channelcgd-card-meta">
                    <div class="cmr-channelcgd-card-label">Channel Connect <span>|</span> <?php echo esc_html($date); ?></div>
                    <div class="cmr-channelcgd-card-time"><?php echo esc_html($read_time); ?> min read</div>
                </div>
                <h3 class="cmr-channelcgd-card-title">
                    <a href="<?php echo esc_url($link); ?>"><?php echo esc_html($title); ?></a>
                </h3>
                <p class="cmr-channelcgd-card-excerpt"><?php echo esc_html($excerpt); ?></p>
                <a href="<?php echo esc_url($link); ?>" class="cmr-channelcgd-card-link">
                    Read full Release 
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg>
                </a>
            </div>
            <?php
        }
    }
    $html = ob_get_clean();

    $has_more = $paged < $total_pages;

    $base_url = isset( $_POST['base_url'] ) ? sanitize_text_field( $_POST['base_url'] ) : '/';
    $pagination = '';
    if ( $paged >= 3 || $paged >= $total_pages ) {
        $full_base = home_url( $base_url );
        $pagination = paginate_links( array(
            'base'    => trailingslashit( $full_base ) . '%_%',
            'format'  => '?paged=%#%',
            'total'   => $total_pages,
            'current' => $paged,
            'prev_text' => '<svg width="12" height="18" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7 1L1 7L7 13" stroke="#6A35FF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
            'next_text' => '<svg width="12" height="18" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 1L7 7L1 13" stroke="#6A35FF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        ) );
    }

    wp_reset_postdata();

    wp_send_json_success( array(
        'html' => $html,
        'has_more' => $has_more,
        'pagination' => $pagination
    ) );
}

add_action( 'wp_ajax_cmr_load_more_consulting', 'cmr_load_more_consulting_ajax' );
add_action( 'wp_ajax_nopriv_cmr_load_more_consulting', 'cmr_load_more_consulting_ajax' );

function cmr_load_more_consulting_ajax() {
    $paged = isset( $_POST['page'] ) ? intval( $_POST['page'] ) : 1;
    $base_url = isset( $_POST['base_url'] ) ? sanitize_text_field( $_POST['base_url'] ) : '/';
    $search = isset( $_POST['search'] ) ? sanitize_text_field( $_POST['search'] ) : '';
    
    $category = isset( $_POST['category'] ) ? sanitize_text_field( $_POST['category'] ) : 'consulting-advisory';
    if ( empty( $category ) || $category === 'industry-connect' ) {
        $category = 'consulting-advisory';
    }
    
    $cat_terms = array_map( 'trim', explode( ',', $category ) );
    if ( in_array( 'consulting-advisory', $cat_terms, true ) && ! in_array( 'consulting', $cat_terms, true ) ) {
        $cat_terms[] = 'consulting';
    }
    
    $query_args = array(
        'post_type'      => array( 'post', 'cmr_news' ),
        'posts_per_page' => 6,
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC',
        'paged'          => $paged,
        'tax_query'      => array(
            'relation' => 'OR',
            array(
                'taxonomy' => 'category',
                'field'    => 'slug',
                'terms'    => $cat_terms,
            ),
            array(
                'taxonomy' => 'cmr_news_category',
                'field'    => 'slug',
                'terms'    => $cat_terms,
            ),
        ),
    );

    if ( !empty($search) ) {
        $query_args['s'] = $search;
    }

    $insights_query = new WP_Query( $query_args );

    ob_start();
    if ( $insights_query->have_posts() ) {
        while ( $insights_query->have_posts() ) {
            $insights_query->the_post();
            $post_title = get_the_title();
            $post_link = get_permalink();
            $thumbnail_url = get_the_post_thumbnail_url( get_the_ID(), 'full' );
            if ( ! $thumbnail_url ) {
                $thumbnail_url = quanto_get_placeholder_image_uri();
            }
            
            $content = get_post_field( 'post_content', get_the_ID() );
            $word_count = str_word_count( strip_tags( $content ) );
            $read_time = ceil( $word_count / 200 );
            if ($read_time < 1) $read_time = 1;
            
            ?>
            <div class="intel-card" style="text-align: left; align-items: flex-start;">
                <div class="intel-card-img" style="width: 100%; aspect-ratio: 16 / 9; overflow: hidden; border-radius: 8px; margin-bottom: 14px; text-align: left; margin-left: 0; margin-right: auto; display: block;">
                    <a href="<?php echo esc_url( $post_link ); ?>" style="display: block; width: 100%; height: 100%; text-align: left; margin: 0; padding: 0;">
                        <img src="<?php echo esc_url( $thumbnail_url ); ?>" alt="<?php echo esc_attr( $post_title ); ?>" style="width: 100%; height: 100%; aspect-ratio: 16 / 9; object-fit: cover; object-position: center left; display: block; border-radius: 8px; margin: 0; margin-left: 0; margin-right: auto;" />
                    </a>
                </div>
                <div class="intel-card-content">
                    <div class="intel-meta" style="display: flex !important; justify-content: space-between !important; align-items: center !important; width: 100% !important; margin-bottom: 14px !important;">
                        <span class="intel-category">Consulting & Advisory</span>
                        <span class="intel-read-time" style="margin-left: auto !important;"><?php echo esc_html( $read_time ); ?> min read</span>
                    </div>
                    <h3 class="intel-title">
                        <a href="<?php echo esc_url( $post_link ); ?>"><?php echo esc_html( $post_title ); ?></a>
                    </h3>
                    <p class="intel-excerpt"><?php echo wp_trim_words( get_the_excerpt(), 15, '...' ); ?></p>
                    <a href="<?php echo esc_url( $post_link ); ?>" class="intel-read-more">
                        More Details
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="7" y1="17" x2="17" y2="7"></line>
                            <polyline points="7 7 17 7 17 17"></polyline>
                        </svg>
                    </a>
                </div>
            </div>
            <?php
        }
    }
    
    $html = ob_get_clean();

    $pagination = '';
    if ( $paged >= $insights_query->max_num_pages ) {
        $pagination = '';
    } else {
        $pagination = paginate_links( array(
            'total'   => $insights_query->max_num_pages,
            'current' => $paged,
            'format'  => '?paged=%#%',
            'prev_text' => '<svg width="12" height="18" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7 1L1 7L7 13" stroke="#6A35FF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
            'next_text' => '<svg width="12" height="18" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 1L7 7L1 13" stroke="#6A35FF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        ) );
    }

    if ( $html ) {
        wp_send_json_success( array( 'html' => $html, 'pagination' => $pagination ) );
    } else {
        wp_send_json_error();
    }
}

add_action( 'wp_ajax_cmr_load_more_marketing', 'cmr_load_more_marketing_ajax' );
add_action( 'wp_ajax_nopriv_cmr_load_more_marketing', 'cmr_load_more_marketing_ajax' );

function cmr_load_more_marketing_ajax() {
    $paged = isset( $_POST['page'] ) ? intval( $_POST['page'] ) : 1;
    $base_url = isset( $_POST['base_url'] ) ? sanitize_text_field( $_POST['base_url'] ) : '/';
    $search = isset( $_POST['search'] ) ? sanitize_text_field( $_POST['search'] ) : '';
    
    $category = isset( $_POST['category'] ) ? sanitize_text_field( $_POST['category'] ) : 'industry-connect';
    
    $query_args = array(
        'post_type'      => array( 'post', 'cmr_news' ),
        'posts_per_page' => 6,
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC',
        'paged'          => $paged,
        'tax_query'      => array(
            array(
                'taxonomy' => 'category',
                'field'    => 'slug',
                'terms'    => $category,
            ),
        ),
    );

    if ( !empty($search) ) {
        $query_args['s'] = $search;
    }

    $insights_query = new WP_Query( $query_args );

    ob_start();
    if ( $insights_query->have_posts() ) {
        while ( $insights_query->have_posts() ) {
            $insights_query->the_post();
            $post_title = get_the_title();
            $post_link = get_permalink();
            $thumbnail_url = get_the_post_thumbnail_url( get_the_ID(), 'full' );
            if ( ! $thumbnail_url ) {
                $thumbnail_url = quanto_get_placeholder_image_uri();
            }
            
            $content = get_post_field( 'post_content', get_the_ID() );
            $word_count = str_word_count( strip_tags( $content ) );
            $read_time = ceil( $word_count / 200 );
            if ($read_time < 1) $read_time = 1;
            
            ?>
            <div class="intel-card" style="text-align: left; align-items: flex-start;">
                <div class="intel-card-img" style="width: 100%; aspect-ratio: 16 / 9; overflow: hidden; border-radius: 8px; margin-bottom: 14px; text-align: left; margin-left: 0; margin-right: auto; display: block;">
                    <a href="<?php echo esc_url( $post_link ); ?>" style="display: block; width: 100%; height: 100%; text-align: left; margin: 0; padding: 0;">
                        <img src="<?php echo esc_url( $thumbnail_url ); ?>" alt="<?php echo esc_attr( $post_title ); ?>" style="width: 100%; height: 100%; aspect-ratio: 16 / 9; object-fit: cover; object-position: center left; display: block; border-radius: 8px; margin: 0; margin-left: 0; margin-right: auto;" />
                    </a>
                </div>
                <div class="intel-card-content">
                    <div class="intel-meta" style="display: flex !important; justify-content: space-between !important; align-items: center !important; width: 100% !important; margin-bottom: 14px !important;">
                        <span class="intel-category">Marketing Services</span>
                        <span class="intel-read-time" style="margin-left: auto !important;"><?php echo esc_html( $read_time ); ?> min read</span>
                    </div>
                    <h3 class="intel-title">
                        <a href="<?php echo esc_url( $post_link ); ?>"><?php echo esc_html( $post_title ); ?></a>
                    </h3>
                    <p class="intel-excerpt"><?php echo wp_trim_words( get_the_excerpt(), 15, '...' ); ?></p>
                    <a href="<?php echo esc_url( $post_link ); ?>" class="intel-read-more">
                        More Details
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="7" y1="17" x2="17" y2="7"></line>
                            <polyline points="7 7 17 7 17 17"></polyline>
                        </svg>
                    </a>
                </div>
            </div>
            <?php
        }
    }
    
    $html = ob_get_clean();

    $pagination = '';
    if ( $paged >= $insights_query->max_num_pages ) {
        $pagination = '';
    } else {
        $pagination = paginate_links( array(
            'total'   => $insights_query->max_num_pages,
            'current' => $paged,
            'format'  => '?paged=%#%',
            'prev_text' => '<svg width="12" height="18" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7 1L1 7L7 13" stroke="#6A35FF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
            'next_text' => '<svg width="12" height="18" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 1L7 7L1 13" stroke="#6A35FF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        ) );
    }

    if ( $html ) {
        wp_send_json_success( array( 'html' => $html, 'pagination' => $pagination ) );
    } else {
        wp_send_json_error();
    }
}
