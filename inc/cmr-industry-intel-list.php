<?php
/**
 * Shortcode for Industry Intelligence List with Banner
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! function_exists( 'cmr_industry_intel_list_shortcode' ) ) {
    function cmr_industry_intel_list_shortcode( $atts ) {
        $atts = shortcode_atts( array(
            'posts_per_page' => 3,
            'category'       => '',
            'offset'         => 4,
            'nav_title'      => '',
        ), $atts );

        $offset = isset( $atts['offset'] ) ? max( 0, intval( $atts['offset'] ) ) : 4;
        $max_posts = intval( $atts['posts_per_page'] );
        $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : ( isset($_GET['paged']) ? intval($_GET['paged']) : 1 );

        $query_args = array(
            'post_type'      => array( 'post', 'cmr_news' ),
            'posts_per_page' => max( 40, ( $max_posts + $offset ) * 4 ),
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC',
            'paged'          => $paged,
        );

        if ( ! empty( $atts['category'] ) ) {
            $query_args['tax_query'] = array(
                'relation' => 'OR',
                array(
                    'taxonomy' => 'category',
                    'field'    => 'slug',
                    'terms'    => sanitize_text_field( $atts['category'] ),
                ),
                array(
                    'taxonomy' => 'cmr_news_category',
                    'field'    => 'slug',
                    'terms'    => sanitize_text_field( $atts['category'] ),
                )
            );
        }

        $insights_query = new WP_Query( $query_args );

        $unique_posts = array();
        $seen_ids = array();
        $seen_titles = array();
        $skipped = 0;

        if ( $insights_query->have_posts() ) {
            foreach ( $insights_query->posts as $p ) {
                $norm_title = sanitize_title( $p->post_title );
                if ( in_array( $p->ID, $seen_ids, true ) || ( ! empty( $norm_title ) && in_array( $norm_title, $seen_titles, true ) ) ) {
                    continue;
                }
                $seen_ids[] = $p->ID;
                $seen_titles[] = $norm_title;

                if ( $skipped < $offset ) {
                    $skipped++;
                    continue;
                }

                $unique_posts[] = $p;
                if ( count( $unique_posts ) >= $max_posts ) {
                    break;
                }
            }
        }

        ob_start();
        ?>
        <style>
            .cmr-intel-list-wrapper {
                font-family: 'Instrument Sans', sans-serif !important;
                max-width: 1280px;
                margin: 0 auto;
                display: flex;
                flex-direction: column;
                gap: 40px;
            }
            .cmr-intel-list-item {
                display: flex;
                gap: 30px;
                align-items: center;
            }
            .cmr-intel-list-img {
                flex: 0 0 45%;
                aspect-ratio: 16 / 9;
                overflow: hidden;
                border-radius: 0 !important;
            }
            .cmr-intel-list-img a {
                display: block;
                width: 100%;
                height: 100%;
                border-radius: 0 !important;
            }
            .cmr-intel-list-img img {
                width: 100%;
                height: 100%;
                aspect-ratio: 16 / 9;
                object-fit: cover;
                object-position: center left;
                display: block;
                border-radius: 0 !important;
                transform: scale(1);
                transition: transform 0.4s cubic-bezier(0.25, 1, 0.5, 1) !important;
            }
            .cmr-intel-list-item:hover .cmr-intel-list-img img,
            .cmr-intel-list-img:hover img {
                transform: scale(1.06) !important;
            }
            .cmr-intel-list-content {
                flex: 1;
                display: flex;
                flex-direction: column;
            }
            .cmr-intel-list-meta {
                display: flex;
                align-items: center;
                gap: 15px;
                font-size: 12px;
                color: #888;
                margin-bottom: 12px;
            }
            .cmr-intel-list-cat {
                display: flex;
                align-items: center;
                gap: 10px;
            }
            .cmr-intel-list-cat::before {
                content: '';
                display: block;
                width: 20px;
                height: 1px;
                background: #ccc;
            }
            .cmr-intel-list-title {
                font-size: 20px;
                font-weight: 600;
                color: #111;
                margin: 0 0 12px 0;
                line-height: 1.4;
                letter-spacing: -0.3px;
            }
            .cmr-intel-list-title a {
                color: #111;
                text-decoration: none;
                transition: color 0.3s ease !important;
            }
            .cmr-intel-list-item:hover .cmr-intel-list-title,
            .cmr-intel-list-item:hover .cmr-intel-list-title a,
            .cmr-intel-list-title:hover,
            .cmr-intel-list-title:hover a,
            .cmr-intel-list-title a:hover {
                color: #4820B0 !important;
            }
            .cmr-intel-list-excerpt {
                font-size: 15px;
                color: #555;
                line-height: 1.6;
                margin-bottom: 20px;
            }
            .cmr-intel-list-more {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                font-size: 13px;
                font-weight: 600;
                color: #111;
                text-decoration: none;
                border-bottom: 1.5px solid #111;
                padding-bottom: 2px;
                align-self: flex-start;
                transition: all 0.3s ease;
            }
            .cmr-intel-list-more:hover {
                color: #555;
                border-color: #555;
            }
            .cmr-intel-list-more svg {
                width: 12px;
                height: 12px;
                transition: transform 0.3s ease;
            }
            .cmr-intel-list-more:hover svg {
                transform: translate(2px, -2px);
            }
            /* Banner */
            .cmr-intel-list-banner {
                background: #4B24B3;
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 35px 40px;
                color: #fff;
                margin: 10px 0;
            }
            .cmr-intel-list-banner-left h3 {
                font-size: 45px;
                font-weight: 600;
                margin: 0;
                color: #fff;
                letter-spacing: -0.5px;
            }
            .cmr-intel-list-banner-mid {
                flex: 1;
                padding: 0 30px;
            }
            .cmr-intel-list-banner-mid p {
                font-size: 15px;
                line-height: 1.5;
                margin: 0;
                color: rgba(255, 255, 255, 0.9);
                max-width: 320px;
            }
            .cmr-intel-list-banner-right .cmr-cta-btn,
            .cmr-intel-list-banner-right .download-btn {
                background: #ffffff !important;
                color: #111111 !important;
                text-decoration: none;
                padding: 8px 24px;
                border-radius: 40px;
                font-weight: 600;
                font-size: 14px;
                display: inline-flex;
                align-items: center;
                gap: 8px;
                transition: all 0.3s ease;
                cursor: pointer;
            }
            .cmr-intel-list-banner-right .cmr-cta-btn:hover,
            .cmr-intel-list-banner-right .download-btn:hover {
                background: #f0f0f0;
                transform: translateY(-2px);
            }
            .cmr-intel-list-banner-right .cmr-cta-btn svg,
            .cmr-intel-list-banner-right .download-btn svg {
                width: 14px;
                height: 14px;
            }
            .cmr-intel-list-load-more {
                text-align: center;
                margin-top: 20px;
                width: 100%;
            }
            .cmr-intel-list-load-more a,
            .cmr-intel-list-load-more button {
                background: transparent;
                border: 1px solid #ccc;
                color: #111;
                font-size: 14px;
                font-weight: 600;
                padding: 0;
                border-radius: 40px;
                cursor: pointer;
                transition: transform 0.3s cubic-bezier(0.25, 1, 0.5, 1), border-color 0.3s ease, color 0.3s ease, background 0.3s ease !important;
                font-family: inherit;
                width: 260px;
                height: 44px;
                display: inline-flex;
                justify-content: center;
                align-items: center;
                text-decoration: none;
                box-sizing: border-box;
                transform: scale(1);
            }
            .cmr-intel-list-load-more a:hover,
            .cmr-intel-list-load-more button:hover {
                background: #fafafa;
                border-color: #6B3FA0 !important;
                color: #6B3FA0 !important;
                transform: scale(1.05) !important;
            }

            .cmr-pagination-style {
                display: flex;
                justify-content: center;
                align-items: center;
                gap: 15px;
                margin-top: 40px;
                margin-bottom: 20px;
                font-family: 'Instrument Sans', sans-serif;
            }
            .cmr-pagination-style .page-numbers {
                display: flex;
                justify-content: center;
                align-items: center;
                width: 50px;
                height: 50px;
                border-radius: 50%;
                text-decoration: none;
                color: #333;
                font-size: 18px;
                font-weight: 500;
                transition: all 0.3s ease;
                background: transparent;
            }
            .cmr-pagination-style .page-numbers.current {
                background: #6A35FF;
                color: #fff;
                box-shadow: 0 4px 15px rgba(106, 53, 255, 0.3);
            }
            .cmr-pagination-style a.page-numbers:hover {
                background: #f5f5f5;
            }
            .cmr-pagination-style .page-numbers.dots {
                width: auto;
                background: transparent;
                pointer-events: none;
                letter-spacing: 2px;
            }
            .cmr-pagination-style .prev.page-numbers,
            .cmr-pagination-style .next.page-numbers {
                width: auto;
                box-shadow: none;
                padding: 0 15px;
            }
            .cmr-pagination-style .prev.page-numbers:hover,
            .cmr-pagination-style .next.page-numbers:hover {
                background: transparent;
                opacity: 0.7;
            }
            .cmr-pagination-style .prev.page-numbers svg,
            .cmr-pagination-style .next.page-numbers svg {
                display: block;
            }
            @media (max-width: 768px) {
                .cmr-intel-list-wrapper {
                    width: 100% !important;
                    max-width: 100% !important;
                    box-sizing: border-box !important;
                    gap: 30px !important;
                }
                .cmr-intel-list-items {
                    width: 100% !important;
                    max-width: 100% !important;
                    box-sizing: border-box !important;
                    gap: 30px !important;
                }
                .cmr-intel-list-item {
                    display: flex !important;
                    flex-direction: column !important;
                    align-items: flex-start !important;
                    text-align: left !important;
                    gap: 16px !important;
                    width: 100% !important;
                    max-width: 100% !important;
                    box-sizing: border-box !important;
                }
                .cmr-intel-list-img {
                    width: 100% !important;
                    max-width: 100% !important;
                    flex: none !important;
                    aspect-ratio: 16 / 9 !important;
                    border-radius: 0 !important;
                    overflow: hidden !important;
                    margin: 0 0 14px 0 !important;
                    margin-left: 0 !important;
                    margin-right: auto !important;
                    text-align: left !important;
                    display: block !important;
                    box-sizing: border-box !important;
                }
                .cmr-intel-list-img a {
                    display: block !important;
                    width: 100% !important;
                    height: 100% !important;
                    border-radius: 0 !important;
                    text-align: left !important;
                    margin: 0 !important;
                    padding: 0 !important;
                }
                .cmr-intel-list-img img {
                    width: 100% !important;
                    max-width: 100% !important;
                    height: 100% !important;
                    aspect-ratio: 16 / 9 !important;
                    object-fit: cover !important;
                    object-position: center left !important;
                    border-radius: 0 !important;
                    display: block !important;
                    margin: 0 !important;
                    margin-left: 0 !important;
                    margin-right: auto !important;
                }
                .cmr-intel-list-content {
                    width: 100% !important;
                    max-width: 100% !important;
                    box-sizing: border-box !important;
                    display: flex !important;
                    flex-direction: column !important;
                    align-items: flex-start !important;
                    text-align: left !important;
                }
                .cmr-intel-list-title,
                .cmr-intel-list-meta,
                .cmr-intel-list-excerpt,
                .cmr-intel-list-more {
                    text-align: left !important;
                    align-self: flex-start !important;
                }
                .cmr-intel-list-banner {
                    flex-direction: column !important;
                    text-align: center !important;
                    gap: 20px !important;
                    padding: 30px 20px !important;
                }
                .cmr-intel-list-banner-left h3 {
                    font-size: 35px !important;
                }
                .cmr-intel-list-banner-mid {
                    padding: 0 !important;
                }
            }
        </style>

        <div class="cmr-intel-list-wrapper">
            <?php if ( ! empty( $unique_posts ) ) : ?>
                <div class="cmr-intel-list-items" style="display:flex; flex-direction:column; gap:40px; width: 100%;">
                <?php
                $count = 0;
                foreach ( $unique_posts as $insight_post ) :
                    $count++;
                    $post_title = get_the_title( $insight_post );
                    $post_link = get_permalink( $insight_post );
                    $thumb_id  = get_post_thumbnail_id( $insight_post->ID );
                    $img_html  = '';
                    if ( $thumb_id ) {
                        $img_html = wp_get_attachment_image( $thumb_id, 'medium_large', false, array(
                            'loading'  => ( $count <= 1 ) ? 'eager' : 'lazy',
                            'decoding' => 'async',
                            'sizes'    => '(max-width: 768px) 100vw, 650px',
                            'alt'      => esc_attr( $post_title ),
                        ) );
                    }
                    if ( empty( $img_html ) ) {
                        $thumbnail_url = get_the_post_thumbnail_url( $insight_post->ID, 'medium_large' );
                        if ( ! $thumbnail_url ) {
                            $thumbnail_url = quanto_get_placeholder_image_uri();
                        }
                        $img_html = '<img src="' . esc_url( $thumbnail_url ) . '" alt="' . esc_attr( $post_title ) . '" loading="' . ( $count <= 1 ? 'eager' : 'lazy' ) . '" decoding="async" />';
                    }
                    
                    $default_cat_name = ! empty( $atts['nav_title'] ) ? $atts['nav_title'] : 'Industry Intelligence';
                    if ( ! empty( $atts['category'] ) ) {
                        $term_obj = get_term_by( 'slug', $atts['category'], 'category' );
                        if ( ! $term_obj || is_wp_error( $term_obj ) ) {
                            $term_obj = get_term_by( 'slug', $atts['category'], 'cmr_news_category' );
                        }
                        if ( $term_obj && ! is_wp_error( $term_obj ) ) {
                            $default_cat_name = $term_obj->name;
                        }
                    }

                    $category_name = $default_cat_name;

                    $content = get_post_field( 'post_content', $insight_post->ID );
                    $word_count = str_word_count( strip_tags( $content ) );
                    $read_time = ceil( $word_count / 200 );
                    if ($read_time < 1) $read_time = 1;
                    ?>
                    
                    <div class="cmr-intel-list-item">
                        <div class="cmr-intel-list-img">
                            <a href="<?php echo esc_url( $post_link ); ?>">
                                <?php echo $img_html; ?>
                            </a>
                        </div>
                        <div class="cmr-intel-list-content">
                            <div class="cmr-intel-list-meta">
                                <span class="cmr-intel-list-cat"><?php echo esc_html( $category_name ); ?></span>
                                <span><?php echo esc_html( $read_time ); ?> min read</span>
                            </div>
                            <h3 class="cmr-intel-list-title">
                                <a href="<?php echo esc_url( $post_link ); ?>"><?php echo esc_html( $post_title ); ?></a>
                            </h3>
                            <div class="cmr-intel-list-excerpt">
                                <?php 
                                $p_id = $insight_post->ID;
                                $excerpt = function_exists( 'quanto_get_clean_post_excerpt' ) ? quanto_get_clean_post_excerpt( $p_id, 18 ) : wp_trim_words( get_the_excerpt( $p_id ), 18, '...' );
                                echo wp_kses_post( $excerpt ); 
                                ?>
                            </div>
                            <a href="<?php echo esc_url( $post_link ); ?>" class="cmr-intel-list-more">
                                More Details 
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="7" y1="17" x2="17" y2="7"></line>
                                    <polyline points="7 7 17 7 17 17"></polyline>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <?php if ( $count === 2 ) : ?>
                        <div class="cmr-intel-list-banner">
                            <div class="cmr-intel-list-banner-left">
                                <h3>Need deeper insights?</h3>
                            </div>
                            <div class="cmr-intel-list-banner-mid">
                                <p>Talk to our analysts for tailored recommendations across your sector.</p>
                            </div>
                            <div class="cmr-intel-list-banner-right">
                                <a href="javascript:void(0)" class="cmr-cta-btn open-popup" data-popup-id="7637" onclick="if(window.cmrOpenPopup){window.cmrOpenPopup(7637);event.preventDefault();event.stopPropagation();}">
                                    Connect to Analyst 
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="7" y1="17" x2="17" y2="7"></line>
                                        <polyline points="7 7 17 7 17 17"></polyline>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>

                <?php endforeach; ?>
                </div> <!-- /.cmr-intel-list-items -->
                
                <?php
                // Generate Pagination
                $pagination_html = '';
                if ( $insights_query->max_num_pages > 1 ) {
                    $big = 999999999; // need an unlikely integer
                    $pagination_html = paginate_links( array(
                        'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                        'format'    => '?paged=%#%',
                        'total'     => $insights_query->max_num_pages,
                        'current'   => $paged,
                        'prev_text' => '<svg width="12" height="18" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7 1L1 7L7 13" stroke="#4B24B3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
                        'next_text' => '<svg width="12" height="18" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 13L7 7L1 1" stroke="#4B24B3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
                    ) );
                }
                ?>

                <?php
                $intel_cat_slug = !empty($atts['category']) ? $atts['category'] : 'industry-connect';
                $intel_cat_obj  = get_category_by_slug( $intel_cat_slug );
                $intel_cat_url  = $intel_cat_obj ? get_category_link( $intel_cat_obj->term_id ) : home_url( '/category/' . $intel_cat_slug . '/' );
                ?>
                <div class="cmr-intel-list-load-more">
                    <a href="<?php echo esc_url( $intel_cat_url ); ?>" id="cmr-intel-load-more-btn" style="display: inline-flex; align-items: center; justify-content: center; text-decoration: none;">View All</a>
                </div>
            <?php else : ?>
                <p>No insights found.</p>
            <?php endif; wp_reset_postdata(); ?>
        </div>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            var loadMoreBtn = document.getElementById('cmr-intel-load-more-btn');
            var container = document.querySelector('.cmr-intel-list-items');
            var pag = document.querySelector('.cmr-pagination-wrapper');
            var cat = '<?php echo esc_js($atts['category']); ?>';
            var offset = <?php echo intval($offset); ?>;
            var maxPage = <?php echo intval($insights_query->max_num_pages); ?>;
            
            function loadPage(page, append) {
                var data = new FormData();
                data.append('action', 'cmr_industry_intel_list_load_more');
                data.append('page', page);
                data.append('offset', offset);
                data.append('base_url', window.location.pathname);
                if (cat) {
                    data.append('category', cat);
                }
                
                fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                    method: 'POST',
                    body: data
                })
                .then(response => response.text())
                .then(html => {
                    if (html.trim() !== '') {
                        var htmlContent = html;
                        var dynamicPagMatch = html.match(/<div class="cmr-dynamic-pagination" style="display:none;">(.*?)<\/div>/s);
                        var dynamicPagHtml = '';
                        if (dynamicPagMatch) {
                            dynamicPagHtml = dynamicPagMatch[1];
                            htmlContent = htmlContent.replace(dynamicPagMatch[0], '');
                        }
                        
                        container.insertAdjacentHTML('beforeend', htmlContent);
                        
                        // Refresh ScrollTrigger to recalculate horizontal scroll layouts after DOM change
                        if (typeof ScrollTrigger !== 'undefined') {
                            ScrollTrigger.refresh();
                        }
                        
                        if (loadMoreBtn && append) {
                            loadMoreBtn.setAttribute('data-page', page);
                            loadMoreBtn.textContent = 'Load More';
                            loadMoreBtn.disabled = false;
                        }
                        
                        if (page >= 3 || page >= maxPage || !append) {
                            if (loadMoreBtn) loadMoreBtn.parentElement.style.display = 'none';
                            if (pag) {
                                if (dynamicPagHtml) {
                                    pag.innerHTML = dynamicPagHtml;
                                }
                                pag.style.display = 'flex';
                            }
                        }
                    } else {
                        if (loadMoreBtn) loadMoreBtn.parentElement.style.display = 'none';
                    }
                })
                .catch(err => {
                    console.error('Error loading insights:', err);
                    if (loadMoreBtn) {
                        loadMoreBtn.textContent = 'Load More';
                        loadMoreBtn.disabled = false;
                    }
                });
            }
            
            if (pag) {
                pag.addEventListener('click', function(e) {
                    var link = e.target.closest('a.page-numbers');
                    if (link) {
                        e.preventDefault();
                        var href = link.getAttribute('href');
                        var urlParams = new URLSearchParams(href.split('?')[1]);
                        var page = urlParams.get('paged');
                        
                        if (!page) {
                            var match = href.match(/\/page\/(\d+)/);
                            if (match) {
                                page = match[1];
                            } else {
                                page = 1;
                            }
                        }
                        
                        if (page) {
                            loadPage(parseInt(page), true);
                        }
                    }
                });
            }
        });
        </script>
        <?php
        return ob_get_clean();
    }
}
add_shortcode( 'cmr_industry_intel_list', 'cmr_industry_intel_list_shortcode' );

// AJAX Handler for Load More
add_action( 'wp_ajax_cmr_industry_intel_list_load_more', 'cmr_industry_intel_list_load_more_ajax' );
add_action( 'wp_ajax_nopriv_cmr_industry_intel_list_load_more', 'cmr_industry_intel_list_load_more_ajax' );
function cmr_industry_intel_list_load_more_ajax() {
    $paged = isset( $_POST['page'] ) ? intval( $_POST['page'] ) : 1;
    $offset = isset( $_POST['offset'] ) ? max( 0, intval( $_POST['offset'] ) ) : 4;
    $target_skip = $offset + max( 0, ( $paged - 1 ) * 3 );

    $query_args = array(
        'post_type'      => array( 'post', 'cmr_news' ),
        'posts_per_page' => max( 50, ( $target_skip + 3 ) * 3 ),
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC',
    );
    
    if ( isset( $_POST['category'] ) && ! empty( $_POST['category'] ) ) {
        $query_args['tax_query'] = array(
            'relation' => 'OR',
            array(
                'taxonomy' => 'category',
                'field'    => 'slug',
                'terms'    => sanitize_text_field( $_POST['category'] ),
            ),
            array(
                'taxonomy' => 'cmr_news_category',
                'field'    => 'slug',
                'terms'    => sanitize_text_field( $_POST['category'] ),
            )
        );
    }
    
    $insights_query = new WP_Query( $query_args );
    $unique_posts = array();
    $seen_ids = array();
    $seen_titles = array();
    $skipped = 0;

    if ( $insights_query->have_posts() ) {
        foreach ( $insights_query->posts as $p ) {
            $norm_title = sanitize_title( $p->post_title );
            if ( in_array( $p->ID, $seen_ids, true ) || ( ! empty( $norm_title ) && in_array( $norm_title, $seen_titles, true ) ) ) {
                continue;
            }
            $seen_ids[] = $p->ID;
            $seen_titles[] = $norm_title;

            if ( $skipped < $target_skip ) {
                $skipped++;
                continue;
            }

            $unique_posts[] = $p;
            if ( count( $unique_posts ) >= 3 ) {
                break;
            }
        }
    }

    if ( ! empty( $unique_posts ) ) {
        $ajax_count = 0;
        foreach ( $unique_posts as $insight_post ) {
            $ajax_count++;
            $post_title = get_the_title( $insight_post );
            $post_link = get_permalink( $insight_post );
            $thumb_id  = get_post_thumbnail_id( $insight_post->ID );
            $img_html  = '';
            if ( $thumb_id ) {
                $img_html = wp_get_attachment_image( $thumb_id, 'medium_large', false, array(
                    'loading'  => 'lazy',
                    'decoding' => 'async',
                    'sizes'    => '(max-width: 768px) 100vw, 650px',
                    'alt'      => esc_attr( $post_title ),
                ) );
            }
            if ( empty( $img_html ) ) {
                $thumbnail_url = get_the_post_thumbnail_url( $insight_post->ID, 'medium_large' );
                if ( ! $thumbnail_url ) {
                    $thumbnail_url = quanto_get_placeholder_image_uri();
                }
                $img_html = '<img src="' . esc_url( $thumbnail_url ) . '" alt="' . esc_attr( $post_title ) . '" loading="lazy" decoding="async" />';
            }
            
            $default_cat_name = 'Industry Intelligence';
            if ( ! empty( $_POST['category'] ) ) {
                $cat_slug = sanitize_text_field( wp_unslash( $_POST['category'] ) );
                $term_obj = get_term_by( 'slug', $cat_slug, 'category' );
                if ( ! $term_obj || is_wp_error( $term_obj ) ) {
                    $term_obj = get_term_by( 'slug', $cat_slug, 'cmr_news_category' );
                }
                if ( $term_obj && ! is_wp_error( $term_obj ) ) {
                    $default_cat_name = $term_obj->name;
                }
            }

            $category_name = $default_cat_name;

            $content = get_post_field( 'post_content', $insight_post->ID );
            $word_count = str_word_count( strip_tags( $content ) );
            $read_time = ceil( $word_count / 200 );
            if ($read_time < 1) $read_time = 1;
            $p_id = $insight_post->ID;
            $excerpt = function_exists( 'quanto_get_clean_post_excerpt' ) ? quanto_get_clean_post_excerpt( $p_id, 18 ) : wp_trim_words( get_the_excerpt( $p_id ), 18, '...' );
            ?>
            <div class="cmr-intel-list-item">
                <div class="cmr-intel-list-img">
                    <a href="<?php echo esc_url( $post_link ); ?>">
                        <?php echo $img_html; ?>
                    </a>
                </div>
                <div class="cmr-intel-list-content">
                    <div class="cmr-intel-list-meta">
                        <span class="cmr-intel-list-cat"><?php echo esc_html( $category_name ); ?></span>
                        <span><?php echo esc_html( $read_time ); ?> min read</span>
                    </div>
                    <h3 class="cmr-intel-list-title">
                        <a href="<?php echo esc_url( $post_link ); ?>"><?php echo esc_html( $post_title ); ?></a>
                    </h3>
                    <div class="cmr-intel-list-excerpt">
                        <?php echo wp_kses_post( $excerpt ); ?>
                    </div>
                    <a href="<?php echo esc_url( $post_link ); ?>" class="cmr-intel-list-more">
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
    
    if ( $paged >= 3 || $paged >= $insights_query->max_num_pages ) {
        $base_url = isset( $_POST['base_url'] ) ? sanitize_text_field( wp_unslash( $_POST['base_url'] ) ) : '/';
        $full_base = home_url( $base_url );
        $pagination_html = paginate_links( array(
            'base'      => trailingslashit( $full_base ) . '%_%',
            'format'    => '?paged=%#%',
            'total'     => $insights_query->max_num_pages,
            'current'   => $paged,
            'prev_text' => '<svg width="12" height="18" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7 1L1 7L7 13" stroke="#4B24B3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
            'next_text' => '<svg width="12" height="18" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 13L7 7L1 1" stroke="#4B24B3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        ) );
        if ( $pagination_html ) {
            echo '<div class="cmr-dynamic-pagination" style="display:none;">' . $pagination_html . '</div>';
        }
    }
    
    wp_die();
}
