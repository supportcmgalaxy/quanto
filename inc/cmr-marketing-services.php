<?php
/**
 * Shortcode for Marketing Services Section
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! function_exists( 'cmr_marketing_services_shortcode' ) ) {
    function cmr_marketing_services_shortcode( $atts ) {
        wp_enqueue_style( 'cmr-industry-intelligence' );

        $atts = shortcode_atts( array(
            'posts_per_page'   => 6,
            'nav_title'        => 'Marketing Services',
            'show_nav'         => 'true',
            'section_title'    => 'Latest Marketing Services',
            'section_subtitle' => 'Explore real-time insights and strategic analysis shaping industries and business decisions.',
            'category'         => 'industry-connect',
            'link_overview'    => '#top',
            'link_insights'    => '#overview',
            'link_trends'      => '#cmr-intel-trends-section',
            'link_reports'     => '#reports',
            'link_market'      => '#cmr-market-updates',
            'link_newsroom'    => '#newsroom',
            'link_cmr_news'    => '#cmr-in-news'
        ), $atts );

        $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

        $query_args = array(
            'post_type'      => array( 'post', 'cmr_news' ),
            'posts_per_page' => 20, // Fetch more to allow for deduplication skipping
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC',
            'paged'          => $paged,
            'tax_query'      => array(
                array(
                    'taxonomy' => 'category',
                    'field'    => 'slug',
                    'terms'    => $atts['category'],
                ),
            ),
        );

        $insights_query = new WP_Query( $query_args );
        
        $unique_posts = array();
        $seen_ids = array();
        $seen_titles = array();
        $max_posts = intval( $atts['posts_per_page'] );

        if ( $insights_query->have_posts() ) {
            foreach ( $insights_query->posts as $p ) {
                $norm_title = sanitize_title( $p->post_title );
                if ( in_array( $p->ID, $seen_ids, true ) || ( ! empty( $norm_title ) && in_array( $norm_title, $seen_titles, true ) ) ) {
                    continue;
                }
                $seen_ids[] = $p->ID;
                $seen_titles[] = $norm_title;
                $unique_posts[] = $p;
                if ( count( $unique_posts ) >= $max_posts ) {
                    break;
                }
            }
        }

        ob_start();
        ?>
        <div class="cmr-industry-intel-section" id="overview">
            
            <?php if ( $atts['show_nav'] === 'true' ) : ?>
            <div class="intel-nav-bar" style="margin-bottom: 40px;">
                <div class="intel-nav-title">
                    <?php echo esc_html( $atts['nav_title'] ); ?>
                </div>
                <div class="intel-nav-links" style="display: flex; align-items: center;">
                    <a href="<?php echo esc_attr( $atts['link_overview'] ); ?>">Overview</a>
                    <a href="<?php echo esc_attr( $atts['link_insights'] ); ?>">Insights</a>
                    <a href="<?php echo esc_attr( $atts['link_newsroom'] ); ?>">Featured</a>
                    <a href="<?php echo esc_attr( $atts['link_trends'] ); ?>">Trends</a>
                    <a href="<?php echo esc_attr( $atts['link_market'] ); ?>">Market Updates</a>
                    <a href="<?php echo esc_attr( $atts['link_reports'] ); ?>">Reports</a>
                    <a href="<?php echo esc_attr( $atts['link_cmr_news'] ); ?>">CMR in news</a>
                </div>
            </div>
            <?php endif; ?>

            <div class="cmr-intel-header-wrapper" style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 40px; flex-wrap: wrap; gap: 20px;">
                <?php if ( ! empty( $atts['section_title'] ) ) : ?>
                <div class="cmr-intel-header">
                    <h2 style="font-size: 44px; font-weight: 600; color: #111; margin: 0 0 12px 0; letter-spacing: -1px; font-family: 'Instrument Sans', sans-serif; line-height: 1.2;">
                        <?php echo esc_html( $atts['section_title'] ); ?>
                    </h2>
                    <?php if ( ! empty( $atts['section_subtitle'] ) ) : ?>
                    <p style="font-size: 16px; color: #333; margin: 0; font-family: 'Instrument Sans', sans-serif; max-width: 800px; line-height: 1.5;">
                        <?php echo esc_html( $atts['section_subtitle'] ); ?>
                    </p>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <div class="intel-controls" style="display: flex; justify-content: flex-end;">
                    <div class="intel-search">
                        <form id="intel-search-form" onsubmit="return false;" style="position: relative; display: flex; align-items: center; background: #fff; border: 1px solid #eaeaea; border-radius: 40px; padding: 4px 4px 4px 20px; width: 300px; height: 48px; box-sizing: border-box;">
                            <input type="text" id="intel-search-input" placeholder="Search by name" style="border: none; background: transparent; flex: 1; outline: none; font-size: 15px; color: #111; padding: 0;">
                            <button type="submit" class="intel-search-btn" aria-label="Search" style="background: #5c24d3; border: none; cursor: pointer; padding: 0; display: flex; align-items: center; justify-content: center; color: #fff; width: 34px; height: 34px; border-radius: 50%; margin-left: 10px; flex-shrink: 0;">
                                <i class="fa-solid fa-magnifying-glass" style="font-size: 14px;"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <?php if ( ! empty( $unique_posts ) ) : ?>
                <div class="intel-grid" id="insights">
                    <?php
                    foreach ( $unique_posts as $insight_post ) :
                        $post_title = get_the_title( $insight_post );
                        $post_link = get_permalink( $insight_post );
                        $thumbnail_url = get_the_post_thumbnail_url( $insight_post->ID, 'full' );
                        if ( ! $thumbnail_url ) {
                            $thumbnail_url = quanto_get_placeholder_image_uri();
                        }
                        
                        // Categories / Tags
                        $category_name = ! empty( $atts['nav_title'] ) ? $atts['nav_title'] : 'Marketing Services';

                        // Calculate reading time
                        $content = get_post_field( 'post_content', $insight_post->ID );
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
                                    <span class="intel-category"><?php echo esc_html( $category_name ); ?></span>
                                    <span class="intel-read-time" style="margin-left: auto !important;"><?php echo esc_html( $read_time ); ?> min read</span>
                                </div>
                                <h3 class="intel-title">
                                    <a href="<?php echo esc_url( $post_link ); ?>"><?php echo esc_html( $post_title ); ?></a>
                                </h3>
                                <div class="intel-excerpt">
                                    <?php 
                                    $p_id = $insight_post->ID;
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
                    <?php endforeach; wp_reset_postdata(); ?>
                </div>
                
                <?php
                $cat_slug_val = !empty($atts['category']) ? $atts['category'] : 'marketing-services';
                $cat_obj_val  = get_category_by_slug( $cat_slug_val );
                $cat_url_val  = $cat_obj_val ? get_category_link( $cat_obj_val->term_id ) : home_url( '/category/' . $cat_slug_val . '/' );
                ?>
                <div class="intel-pagination-wrap">
                    <div class="intel-load-more-wrap" style="text-align: center; margin-top: 30px;">
                        <a href="<?php echo esc_url($cat_url_val); ?>" class="intel-load-more-btn" style="background: transparent; border: 1px solid #ccc; color: #111; font-size: 14px; font-weight: 600; border-radius: 40px; text-decoration: none; transition: all 0.3s ease; width: 260px; height: 44px; display: inline-flex; justify-content: center; align-items: center; box-sizing: border-box;">View All</a>
                    </div>
                </div>

                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const btn = document.querySelector('.intel-load-more-btn');
                    const searchForm = document.getElementById('intel-search-form');
                    const searchInput = document.getElementById('intel-search-input');
                    let currentSearch = '';
                    let clickCount = 0;

                    function fetchMarketing(page, isSearch = false) {
                        let max = btn ? parseInt(btn.getAttribute('data-max')) : 1;
                        let ajaxurl = '<?php echo esc_url(admin_url("admin-ajax.php")); ?>';
                        
                        if (isSearch) {
                            document.querySelector('.intel-grid').innerHTML = '<p>Loading...</p>';
                            page = 1;
                        } else if (btn) {
                            btn.innerText = 'Loading...';
                            btn.disabled = true;
                        }

                        const categorySlug = '<?php echo esc_js( $atts["category"] ); ?>';
                        fetch(ajaxurl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: 'action=cmr_load_more_marketing&page=' + page + '&search=' + encodeURIComponent(currentSearch) + '&category=' + encodeURIComponent(categorySlug) + '&base_url=' + encodeURIComponent(window.location.pathname)
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                if (isSearch) {
                                    document.querySelector('.intel-grid').innerHTML = data.data.html || '<p>No results found.</p>';
                                    clickCount = 0;
                                    if (btn) btn.setAttribute('data-page', 1);
                                    
                                    const paginationWrap = document.querySelector('.intel-pagination-wrap');
                                    if (paginationWrap) {
                                        if (data.data.pagination) {
                                            paginationWrap.innerHTML = '<div class="intel-numeric-pagination" style="text-align: center; margin-top: 30px; display: flex; justify-content: center; gap: 10px;">' + data.data.pagination + '</div>';
                                        } else if (data.data.has_more && btn) {
                                            paginationWrap.innerHTML = '<div class="intel-load-more-wrap" style="text-align: center; margin-top: 30px;"><button class="intel-load-more-btn" data-page="1" data-max="'+max+'" data-ajaxurl="'+ajaxurl+'" style="background: transparent; border: 1px solid #ccc; color: #111; font-size: 14px; font-weight: 600; border-radius: 40px; cursor: pointer; transition: all 0.3s ease; width: 288px; height: 54px; display: inline-flex; justify-content: center; align-items: center;">Load More</button></div>';
                                        } else {
                                            paginationWrap.innerHTML = '';
                                        }
                                    }
                                } else {
                                    document.querySelector('.intel-grid').insertAdjacentHTML('beforeend', data.data.html);
                                    if (btn) btn.setAttribute('data-page', page);
                                    clickCount++;
                                    
                                    if (page >= max || clickCount >= 2) {
                                        document.querySelector('.intel-pagination-wrap').innerHTML = '<div class="intel-numeric-pagination" style="text-align: center; margin-top: 30px; display: flex; justify-content: center; gap: 10px;">' + data.data.pagination + '</div>';
                                    } else if (btn) {
                                        btn.innerText = 'Load More';
                                        btn.disabled = false;
                                    }
                                }
                            } else {
                                if (btn && !isSearch) btn.innerText = 'No more posts';
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            if (btn && !isSearch) {
                                btn.innerText = 'Load More';
                                btn.disabled = false;
                            }
                        });
                    }



                    document.addEventListener('click', function(e) {
                        const link = e.target.closest('.intel-pagination-wrap a.page-numbers');
                        if (link) {
                            e.preventDefault();
                            const href = link.getAttribute('href');
                            let page = 1;
                            const match = href.match(/paged=(\d+)/);
                            if (match) {
                                page = parseInt(match[1], 10);
                            } else {
                                const pathMatch = href.match(/\/page\/(\d+)/);
                                if (pathMatch) page = parseInt(pathMatch[1], 10);
                            }
                            fetchMarketing(page, false); // append like Load More
                        }
                    });

                    if (searchForm) {
                        searchForm.addEventListener('submit', function(e) {
                            e.preventDefault();
                            currentSearch = searchInput.value.trim();
                            fetchMarketing(1, true);
                        });
                    }
                });
                </script>
                
                <!-- Dynamic Anchor Script for Elementor Sections -->
                <script>
                document.addEventListener("DOMContentLoaded", function() {
                    // This script dynamically assigns IDs to sections based on their headings
                    // so that the sticky navbar anchor links work regardless of Elementor setup.
                    var allHeadings = document.querySelectorAll('h1, h2, h3, h4, h5, h6, .elementor-heading-title');
                    
                    allHeadings.forEach(function(h) {
                        var text = h.innerText.toLowerCase();
                        var section = h.closest('section') || h.closest('.elementor-section') || h.closest('.e-con');
                        
                        if (!section) return; // Skip if no valid section container found

                        // "CMR in news" section
                        if (text.includes("recognition of cmr in news") || text.includes("featured media coverage")) {
                            section.id = 'cmr-in-news';
                        }
                        
                        // "Reports" section
                        if (text.includes("similar reports") || text.includes("browse latest reports") || text.includes("featured reports")) {
                            section.id = 'reports';
                        }
                        
                        // "Market Updates" section
                        if (text.includes("market intelligence &") || text.includes("market updates")) {
                            section.id = 'cmr-market-updates';
                        }
                        
                        // "Trends" section
                        if (text.includes("Marketing Services trends")) {
                            section.id = 'trends';
                        }
                        
                        // "Newsroom" (now "Featured") section
                        if (text.includes("featured intelligence") || (text.includes("media releases") && !text.includes("featured media"))) {
                            // Only set if not already set by cmr-in-news
                            if (section.id !== 'cmr-in-news') section.id = 'newsroom';
                        }
                    });
                });
                </script>
            <?php else : ?>
                <p>No insights found.</p>
            <?php endif; ?>
        </div>
        <?php
        return ob_get_clean();
    }
}
add_shortcode( 'cmr_marketing_services', 'cmr_marketing_services_shortcode' );
