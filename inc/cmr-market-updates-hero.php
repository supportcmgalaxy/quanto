<?php
/**
 * Shortcode for Market Updates Hero Section
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! function_exists( 'cmr_market_updates_hero_shortcode' ) ) {
    function cmr_market_updates_hero_shortcode( $atts ) {
        $atts = shortcode_atts( array(
            'post_type'      => 'post',
            'category'       => 'market-updates',
            'posts_per_page' => 5,
        ), $atts );

        $query_args = array(
            'post_type'      => !empty($atts['post_type']) ? $atts['post_type'] : 'post',
            'posts_per_page' => 15, // Query more to account for duplicates
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC',
        );

        if ( ! empty( $atts['category'] ) ) {
            $query_args['category_name'] = $atts['category'];
        }

        $hero_posts = get_posts( $query_args );

        $posts_data = array();
        $seen_titles = array();
        if ( !empty($hero_posts) ) {
            foreach ( $hero_posts as $post_obj ) {
                $title = trim(get_the_title($post_obj));
                if ( isset( $seen_titles[$title] ) ) {
                    continue;
                }
                $seen_titles[$title] = true;
                
                $thumbnail_url = get_the_post_thumbnail_url( $post_obj->ID, 'full' );
                if ( ! $thumbnail_url ) {
                    $thumbnail_url = quanto_get_placeholder_image_uri();
                }
                
                $category_name = 'Market Updates';
                $terms = get_the_terms( $post_obj->ID, 'category' );
                if ( $terms && ! is_wp_error( $terms ) ) {
                    $specific_term = null;
                    foreach ( $terms as $term ) {
                        if ( ! in_array( strtolower( $term->slug ), array( 'market-updates', 'market-update', 'uncategorized' ) ) && ! in_array( strtolower( $term->name ), array( 'market updates', 'market update', 'uncategorized' ) ) ) {
                            $specific_term = $term->name;
                            break;
                        }
                    }
                    if ( $specific_term ) {
                        $category_name = $specific_term;
                    } else {
                        $category_name = $terms[0]->name;
                    }
                }
                
                $post_date = get_the_date('M d, Y', $post_obj);
                
                $excerpt = get_the_excerpt($post_obj);
                if ( empty( $excerpt ) ) {
                    $content = $post_obj->post_content;
                    $excerpt = wp_trim_words( $content, 20 );
                } else {
                    $excerpt = wp_trim_words( $excerpt, 20 );
                }

                $posts_data[] = array(
                    'title'    => $title,
                    'link'     => get_permalink($post_obj->ID),
                    'image'    => $thumbnail_url,
                    'category' => $category_name,
                    'date'     => $post_date,
                    'excerpt'  => wp_kses_post( $excerpt ),
                );
                
                if ( count($posts_data) >= $atts['posts_per_page'] ) {
                    break;
                }
            }
        }
                $slider_id = 'cmr-mu-hero-' . wp_rand(1000, 9999);

        ob_start();
        ?>
        <style>
            .cmr-mu-hero-wrap {
                font-family: 'Instrument Sans', sans-serif !important;
                max-width: 1280px;
                margin: 60px auto;
                padding: 0 20px;
                text-align: center;
            }
            .cmr-mu-hero-breadcrumbs {
                font-size: 13px;
                color: #555;
                margin-bottom: 25px;
            }
            .cmr-mu-hero-breadcrumbs a {
                color: #555;
                text-decoration: none;
            }
            .cmr-mu-hero-breadcrumbs span {
                margin: 0 8px;
            }
            
            .cmr-mu-hero-title {
                font-size: 60px;
                font-weight: 600;
                color: #111;
                margin: 0 0 15px 0;
                line-height: 1.1;
                letter-spacing: -1.5px;
            }
            .cmr-mu-hero-subtitle {
                font-size: 18px;
                color: #555;
                margin: 0 0 40px 0;
            }

            /* Search Bar */
            .cmr-mu-hero-search {
                position: relative;
                max-width: 800px;
                margin: 0 auto 30px auto;
            }
            .cmr-mu-hero-search input {
                width: 100%;
                height: 60px;
                padding: 0 70px;
                border: 1px solid #6B3FA0;
                border-radius: 40px;
                font-size: 16px;
                color: #333;
                background: #fff;
                box-sizing: border-box;
                outline: none;
            }
            .cmr-mu-hero-search input::placeholder {
                color: #aaa;
            }
            .cmr-mu-hero-search-icon-left {
                position: absolute;
                left: 25px;
                top: 50%;
                transform: translateY(-50%);
                color: #6B3FA0;
                display: flex;
            }
            .cmr-mu-hero-search-btn {
                position: absolute;
                right: 8px;
                top: 8px;
                width: 44px;
                height: 44px;
                border-radius: 50%;
                background: #6B3FA0;
                border: none;
                color: #fff;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                transition: background 0.3s ease;
            }
            .cmr-mu-hero-search-btn:hover {
                background: #502e7a;
            }

            /* Categories */
            .cmr-mu-hero-cats {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                gap: 12px;
                margin-bottom: 60px;
            }
            .cmr-mu-hero-cat-pill {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 10px 20px;
                background: #fff;
                border: 1px solid #eaeaea;
                border-radius: 30px;
                font-size: 13px;
                font-weight: 600;
                color: #111;
                text-decoration: none;
                transition: all 0.3s ease;
            }
            .cmr-mu-hero-cat-pill:hover {
                border-color: #6B3FA0;
                color: #6B3FA0;
            }
            .cmr-mu-hero-cat-pill svg {
                width: 14px;
                height: 14px;
                color: #666;
            }

            /* Slider */
            .cmr-mu-slider-wrapper {
                position: relative;
                width: 100%;
                overflow: hidden;
            }
            .cmr-mu-slider-track {
                display: flex;
                transition: transform 0.6s cubic-bezier(0.25, 1, 0.5, 1);
            }
            .cmr-mu-slide {
                flex: 0 0 90%;
                margin-right: 30px;
                display: flex;
                background: #F8F9FB;
                border: 1px solid #eaeaea;
                min-height: 400px;
                text-align: left;
            }
            
            .cmr-mu-slide-img {
                flex: 0 0 55%;
                position: relative;
                overflow: hidden;
            }
            .cmr-mu-slide-img img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                display: block;
            }
            .cmr-mu-slide-badge {
                position: absolute;
                top: 20px;
                left: 20px;
                background: rgba(255,255,255,0.95);
                color: #6B3FA0;
                height: 30px !important;
                padding: 0 12px !important;
                box-sizing: border-box !important;
                border-radius: 0 !important;
                font-size: 11px;
                font-weight: 600;
                letter-spacing: 1px;
                display: inline-flex;
                align-items: center;
                gap: 6px;
                line-height: 1;
            }
            
            .cmr-mu-slide-content {
                flex: 1;
                padding: 40px;
                display: flex;
                flex-direction: column;
                justify-content: center;
            }
            .cmr-mu-slide-meta {
                font-size: 13px;
                font-weight: 500;
                color: #666;
                margin-bottom: 20px;
                display: flex;
                align-items: center;
                gap: 10px;
            }
            .cmr-mu-slide-meta::before {
                content: "";
                display: block;
                width: 24px;
                height: 1px;
                background: #ccc;
            }
            .cmr-mu-slide-title {
                font-size: 32px;
                font-weight: 600;
                color: #111;
                margin: 0 0 20px 0;
                line-height: 1.2;
                letter-spacing: -1px;
                transition: color 0.3s ease !important;
            }
            .cmr-mu-slide-title a {
                color: inherit;
                text-decoration: none;
                transition: color 0.3s ease !important;
            }
            .cmr-mu-slide:hover .cmr-mu-slide-title,
            .cmr-mu-slide:hover .cmr-mu-slide-title a,
            .cmr-mu-slide:hover h3,
            .cmr-mu-slide-title:hover,
            .cmr-mu-slide-title:hover a,
            .cmr-mu-slide-title a:hover {
                color: #6A42E5 !important;
            }
            .cmr-mu-slide-desc {
                font-size: 16px;
                color: #444;
                line-height: 1.6;
                margin-bottom: 30px;
            }
            .cmr-mu-slide-link {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                font-size: 14px;
                font-weight: 600;
                color: #111 !important;
                text-decoration: none;
                border-bottom: 1px solid #111 !important;
                padding-bottom: 4px;
                align-self: flex-start;
                transition: color 0.3s ease, border-color 0.3s ease;
            }
            .cmr-mu-slide-link svg {
                width: 14px;
                height: 14px;
                stroke: #111 !important;
                transition: transform 0.3s ease, stroke 0.3s ease;
            }
            .cmr-mu-slide:hover .cmr-mu-slide-link {
                color: #111 !important;
                border-bottom-color: #111 !important;
            }
            .cmr-mu-slide:hover .cmr-mu-slide-link svg {
                color: #111 !important;
                stroke: #111 !important;
                transform: none !important;
            }
            .cmr-mu-slide-link:hover {
                color: #6A42E5 !important;
                border-color: #6A42E5 !important;
            }
            .cmr-mu-slide-link:hover svg {
                stroke: #6A42E5 !important;
                transform: translate(2px, -2px) !important;
            }

            .cmr-mu-slide-img img {
                transition: transform 0.5s cubic-bezier(0.25, 1, 0.5, 1) !important;
            }
            .cmr-mu-slide:hover .cmr-mu-slide-img img {
                transform: scale(1.04);
            }


            /* Pagination */
            .cmr-mu-pagination {
                display: flex;
                justify-content: center;
                gap: 8px;
                margin-top: 30px;
            }
            .cmr-mu-dot {
                width: 20px;
                height: 4px;
                background: #e0e0e0;
                border-radius: 4px;
                cursor: pointer;
                transition: width 0.3s ease, background-color 0.3s ease;
                position: relative;
                overflow: hidden;
            }
            .cmr-mu-dot.active {
                width: 40px;
                background: #e0e0e0;
            }
            .cmr-mu-dot.active::after {
                content: "";
                position: absolute;
                left: 0;
                top: 0;
                height: 100%;
                width: 0%;
                background: #6B3FA0;
                animation: loaderDot 5s linear forwards;
            }
            .cmr-mu-hero-wrap:hover .cmr-mu-dot.active::after {
                animation-play-state: paused;
            }
            @keyframes loaderDot {
                from { width: 0%; }
                to { width: 100%; }
            }

            @media (max-width: 992px) {
                .cmr-mu-slide {
                    flex-direction: column;
                    flex: 0 0 100%;
                    margin-right: 0;
                }
                .cmr-mu-slide-img {
                    height: 250px;
                }
                .cmr-mu-slide-content {
                    padding: 30px 20px;
                }
                .cmr-mu-hero-title {
                    font-size: 38px;
                }
            }
        </style>

        <div class="cmr-mu-hero-wrap" id="cmr-market-updates">
            <div class="cmr-mu-hero-breadcrumbs">
                <a href="<?php echo esc_url(home_url('/')); ?>">Home</a>
                <span>&gt;</span>
                <span style="color: #111; font-weight: 500;">Market Updates</span>
            </div>

            <h1 class="cmr-mu-hero-title">Market Intelligence &<br>Real-Time Updates</h1>
            <p class="cmr-mu-hero-subtitle">Actionable insights. Real-time signals. Smarter decisions. Stay ahead of what moves markets.</p>

            <form id="cmr-mu-hero-search-form" class="cmr-mu-hero-search" action="<?php echo esc_url(home_url('/')); ?>" method="get">
                <div class="cmr-mu-hero-search-icon-left">
                    <img src="<?php echo esc_url( home_url( '/wp-content/uploads/2026/06/cmrlogo-with-oly-c.svg' ) ); ?>" alt="CMR Logo" style="width: 24px; height: auto;">
                </div>
                <input type="text" name="s" placeholder="Search..." required>
                <button type="submit" class="cmr-mu-hero-search-btn" aria-label="<?php esc_attr_e( 'Search Market Updates', 'quanto' ); ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                </button>
            </form>




            <?php if ( ! empty($posts_data) ) : ?>
            <div class="cmr-mu-slider-wrapper" id="<?php echo esc_attr($slider_id); ?>">
                <div class="cmr-mu-slider-track">
                    <?php foreach ( $posts_data as $index => $post ) : ?>
                        <div class="cmr-mu-slide">
                            <div class="cmr-mu-slide-img">
                                <img src="<?php echo esc_url($post['image']); ?>" alt="<?php echo esc_attr($post['title']); ?>">
                                <div class="cmr-mu-slide-badge">
                                    <svg width="10" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path></svg> FEATURED
                                </div>
                            </div>
                            <div class="cmr-mu-slide-content">
                                <div class="cmr-mu-slide-meta"><?php echo esc_html($post['category']); ?> | <?php echo esc_html($post['date']); ?></div>
                                <h3 class="cmr-mu-slide-title"><?php echo esc_html($post['title']); ?></h3>
                                <div class="cmr-mu-slide-desc"><?php echo wp_kses_post($post['excerpt']); ?></div>
                                <a href="<?php echo esc_url($post['link']); ?>" class="cmr-mu-slide-link">
                                    Read More 
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="cmr-mu-pagination" id="<?php echo esc_attr($slider_id); ?>-dots">
                <?php foreach ( $posts_data as $index => $post ) : ?>
                    <div class="cmr-mu-dot <?php echo $index === 0 ? 'active' : ''; ?>" data-slide="<?php echo esc_attr($index); ?>"></div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const slider = document.getElementById('<?php echo esc_js($slider_id); ?>');
                if (!slider) return;

                const track = slider.querySelector('.cmr-mu-slider-track');
                const slides = slider.querySelectorAll('.cmr-mu-slide');
                const dotsContainer = document.getElementById('<?php echo esc_js($slider_id); ?>-dots');
                const dots = dotsContainer ? dotsContainer.querySelectorAll('.cmr-mu-dot') : [];
                
                if (!track || slides.length === 0) return;

                let currentIndex = 0;

                function updateSlider(index) {
                    currentIndex = index;
                    const slideWidth = slides[0].getBoundingClientRect().width;
                    
                    // Gap is 30px, plus we use 90% flex basis if there's more than 1 slide, 
                    // but we can just measure exactly:
                    const slideStyle = window.getComputedStyle(slides[0]);
                    const marginRight = parseFloat(slideStyle.marginRight) || 0;
                    const moveAmount = (slideWidth + marginRight) * index;
                    
                    track.style.transform = 'translateX(-' + moveAmount + 'px)';

                    dots.forEach(function(dot, i) {
                        dot.classList.remove('active');
                        // Force reflow to restart CSS animation
                        void dot.offsetWidth;
                        
                        if (i === index) {
                            dot.classList.add('active');
                        }
                    });
                }

                dots.forEach(function(dot) {
                    dot.addEventListener('click', function() {
                        const index = parseInt(this.getAttribute('data-slide'));
                        updateSlider(index);
                    });
                    
                    // Sync the slide change perfectly with the end of the CSS animation
                    dot.addEventListener('animationend', function(e) {
                        if (e.animationName === 'loaderDot' && dot.classList.contains('active')) {
                            let nextIndex = (currentIndex + 1) % slides.length;
                            updateSlider(nextIndex);
                        }
                    });
                });
                
                window.addEventListener('resize', function() {
                    updateSlider(currentIndex);
                });

                // Initialize first dot to trigger animation correctly
                if (slides.length > 1) {
                    updateSlider(0);
                }
            });
        </script>
        
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            var muSearchForm = document.getElementById('cmr-mu-hero-search-form');
            if (muSearchForm) {
                muSearchForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    var searchTerm = this.querySelector('input[name="s"]').value;
                    if (!searchTerm) return;
                    
                    var grid = document.querySelector('.cmr-mui-grid');
                    if (grid) {
                        grid.scrollIntoView({behavior: 'smooth', block: 'start'});
                        grid.innerHTML = '<p style="grid-column:1/-1; text-align:center; padding:40px; font-size:18px;">Searching...</p>';
                        
                        var loadMoreBtn = document.getElementById('cmr-mui-load-more');
                        if (loadMoreBtn) loadMoreBtn.style.display = 'none';
                        var paginationWrap = document.getElementById('cmr-mui-pagination-wrap');
                        if (paginationWrap) paginationWrap.style.display = 'none';

                        var formData = new FormData();
                        formData.append('action', 'cmr_insights_ajax_search');
                        formData.append('search_term', searchTerm);
                        formData.append('prefix', 'cmr-mui-');
                        formData.append('category', 'market-updates');
                        
                        fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                            method: 'POST',
                            body: formData
                        })
                        .then(function(res) { return res.text(); })
                        .then(function(html) {
                            grid.innerHTML = html;
                        });
                    }
                });
            }
        });
        </script>
        <?php
        return ob_get_clean();
    }
}
add_shortcode( 'cmr_market_updates_hero', 'cmr_market_updates_hero_shortcode' );

