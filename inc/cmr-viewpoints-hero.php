<?php
/**
 * Shortcode for Viewpoints Hero Section
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! function_exists( 'cmr_viewpoints_hero_shortcode' ) ) {
    function cmr_viewpoints_hero_shortcode( $atts ) {
        $atts = shortcode_atts( array(
            'post_type'      => 'cmr_news',
            'posts_per_page' => 5,
        ), $atts );

        $query_args = array(
            'post_type'      => array('post', 'cmr_news'),
            'posts_per_page' => $atts['posts_per_page'],
            'post_status'    => 'publish',
            'category_name'  => 'viewpoint,viewpoints',
            'orderby'        => 'date',
            'order'          => 'DESC',
            'meta_query'     => array(
                array(
                    'key'     => '_thumbnail_id',
                    'compare' => 'EXISTS'
                ),
            ),
        );

        $hero_posts = get_posts( $query_args );

        $posts_data = array();
        if ( !empty($hero_posts) ) {
            foreach ( $hero_posts as $post_obj ) {
                
                $thumbnail_url = get_the_post_thumbnail_url( $post_obj->ID, 'full' );
                if ( ! $thumbnail_url ) {
                    $thumbnail_url = 'https://via.placeholder.com/1200x800?text=Featured+Image';
                }
                
                $category_name = 'Viewpoints';
                $terms = get_the_terms( $post_obj->ID, 'category' );
                if ( $terms && ! is_wp_error( $terms ) ) {
                    $has_viewpoint = false;
                    foreach ( $terms as $term ) {
                        if ( in_array( strtolower( $term->slug ), array( 'viewpoint', 'viewpoints' ) ) || in_array( strtolower( $term->name ), array( 'viewpoint', 'viewpoints' ) ) ) {
                            $category_name = $term->name;
                            $has_viewpoint = true;
                            break;
                        }
                    }
                    if ( ! $has_viewpoint ) {
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
                    'title'    => get_the_title($post_obj),
                    'link'     => get_permalink($post_obj->ID),
                    'image'    => $thumbnail_url,
                    'category' => $category_name,
                    'date'     => $post_date,
                    'excerpt'  => wp_kses_post( $excerpt ),
                );
            }
        }
                $slider_id = 'cmr-vp-hero-' . wp_rand(1000, 9999);

        ob_start();
        ?>
        <style>
            .cmr-vp-hero-wrap {
                font-family: 'Instrument Sans', sans-serif !important;
                max-width: 1280px;
                margin: 60px auto;
                padding: 0 20px;
                text-align: center;
            }
            .cmr-vp-hero-breadcrumbs {
                font-size: 13px;
                color: #555;
                margin-bottom: 25px;
            }
            .cmr-vp-hero-breadcrumbs a {
                color: #555;
                text-decoration: none;
            }
            .cmr-vp-hero-breadcrumbs span {
                margin: 0 8px;
            }
            
            .cmr-vp-hero-title {
                font-size: 60px;
                font-weight: 600;
                color: #111;
                margin: 0 0 15px 0;
                line-height: 1.1;
                letter-spacing: -1.5px;
            }
            .cmr-vp-hero-subtitle {
                font-size: 18px;
                color: #555;
                margin: 0 0 40px 0;
            }

            /* Search Bar */
            .cmr-vp-hero-search {
                position: relative;
                max-width: 800px;
                margin: 0 auto 50px auto;
            }
            .cmr-vp-hero-search input {
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
            .cmr-vp-hero-search input::placeholder {
                color: #aaa;
            }
            .cmr-vp-hero-search-icon-left {
                position: absolute;
                left: 25px;
                top: 50%;
                transform: translateY(-50%);
                color: #6B3FA0;
                display: flex;
            }
            .cmr-vp-hero-search-btn {
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
            .cmr-vp-hero-search-btn:hover {
                background: #502e7a;
            }

            /* Categories */
            .cmr-vp-hero-cats {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                gap: 12px;
                margin-bottom: 60px;
            }
            .cmr-vp-hero-cat-pill {
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
            .cmr-vp-hero-cat-pill:hover {
                border-color: #6B3FA0;
                color: #6B3FA0;
            }
            .cmr-vp-hero-cat-pill svg {
                width: 14px;
                height: 14px;
                color: #666;
            }

            /* Slider */
            .cmr-vp-slider-wrapper {
                position: relative;
                width: 100%;
                overflow: hidden;
            }
            .cmr-vp-slider-track {
                display: flex;
                transition: transform 0.6s cubic-bezier(0.25, 1, 0.5, 1);
            }
            .cmr-vp-slide {
                flex: 0 0 90%;
                margin-right: 30px;
                display: flex;
                background: #F8F9FB;
                border: 1px solid #eaeaea;
                min-height: 400px;
                text-align: left;
            }
            
            .cmr-vp-slide-img {
                flex: 0 0 55%;
                position: relative;
                overflow: hidden;
            }
            .cmr-vp-slide-img img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                display: block;
            }
            .cmr-vp-slide-badge {
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
            
            .cmr-vp-slide-content {
                flex: 1;
                padding: 40px;
                display: flex;
                flex-direction: column;
                justify-content: center;
            }
            .cmr-vp-slide-meta {
                font-size: 13px;
                font-weight: 500;
                color: #666;
                margin-bottom: 20px;
                display: flex;
                align-items: center;
                gap: 10px;
            }
            .cmr-vp-slide-meta::before {
                content: "";
                display: block;
                width: 24px;
                height: 1px;
                background: #ccc;
            }
            .cmr-vp-slide-title {
                font-size: 32px;
                font-weight: 600;
                color: #111;
                margin: 0 0 20px 0;
                line-height: 1.2;
                letter-spacing: -1px;
                transition: color 0.3s ease !important;
            }
            .cmr-vp-slide-title a {
                color: inherit;
                text-decoration: none;
                transition: color 0.3s ease !important;
            }
            .cmr-vp-slide:hover .cmr-vp-slide-title,
            .cmr-vp-slide:hover .cmr-vp-slide-title a,
            .cmr-vp-slide:hover h3,
            .cmr-vp-slide-title:hover,
            .cmr-vp-slide-title:hover a,
            .cmr-vp-slide-title a:hover {
                color: #6A42E5 !important;
            }
            .cmr-vp-slide-desc {
                font-size: 16px;
                color: #444;
                line-height: 1.6;
                margin-bottom: 30px;
            }
            .cmr-vp-slide-link {
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
            .cmr-vp-slide-link svg {
                width: 14px;
                height: 14px;
                stroke: #111 !important;
                transition: transform 0.3s ease, stroke 0.3s ease;
            }
            .cmr-vp-slide:hover .cmr-vp-slide-link {
                color: #111 !important;
                border-bottom-color: #111 !important;
            }
            .cmr-vp-slide:hover .cmr-vp-slide-link svg {
                color: #111 !important;
                stroke: #111 !important;
                transform: none !important;
            }
            .cmr-vp-slide-link:hover {
                color: #6A42E5 !important;
                border-color: #6A42E5 !important;
            }
            .cmr-vp-slide-link:hover svg {
                stroke: #6A42E5 !important;
                transform: translate(2px, -2px) !important;
            }

            .cmr-vp-slide-img img {
                transition: transform 0.5s cubic-bezier(0.25, 1, 0.5, 1) !important;
            }
            .cmr-vp-slide:hover .cmr-vp-slide-img img {
                transform: scale(1.04);
            }


            /* Pagination */
            .cmr-vp-pagination {
                display: flex;
                justify-content: center;
                gap: 8px;
                margin-top: 30px;
            }
            .cmr-vp-dot {
                position: relative;
                width: 24px;
                height: 4px;
                background: #e0e0e0;
                border-radius: 4px;
                cursor: pointer;
                overflow: hidden;
                transition: width 0.3s ease;
            }
            .cmr-vp-dot-progress {
                position: absolute;
                top: 0;
                left: 0;
                height: 100%;
                width: 0%;
                background: #6B3FA0;
                border-radius: 4px;
            }
            .cmr-vp-dot.active {
                width: 36px;
                background: #e0e0e0;
            }
            .cmr-vp-dot.active .cmr-vp-dot-progress {
                animation: cmrVpDotLoader 5s linear forwards;
            }
            .cmr-vp-slider-paused .cmr-vp-dot.active .cmr-vp-dot-progress {
                animation-play-state: paused !important;
            }
            @keyframes cmrVpDotLoader {
                0% { width: 0%; }
                100% { width: 100%; }
            }

            @media (max-width: 992px) {
                .cmr-vp-hero-wrap {
                    margin: 30px auto;
                    padding: 0 16px;
                }
                .cmr-vp-hero-title {
                    font-size: 32px;
                    letter-spacing: -1px;
                }
                .cmr-vp-hero-subtitle {
                    font-size: 15px;
                    margin-bottom: 25px;
                }
                .cmr-vp-hero-search {
                    margin: 0 auto 35px auto;
                }

                /* Mobile & Tablet Card: Full image card with dark overlay & white typography */
                .cmr-vp-slide {
                    position: relative;
                    flex: 0 0 88%;
                    margin-right: 16px;
                    height: 480px;
                    min-height: 480px;
                    border-radius: 18px;
                    overflow: hidden;
                    background: #0F172A;
                    border: none;
                    display: flex;
                    flex-direction: column;
                    justify-content: flex-end;
                    box-shadow: 0 8px 24px rgba(0,0,0,0.12);
                }
                
                .cmr-vp-slide-img {
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    z-index: 1;
                    overflow: hidden;
                }
                .cmr-vp-slide-img img {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                    display: block;
                }
                .cmr-vp-slide-img::after {
                    content: "";
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background: linear-gradient(180deg, rgba(15, 23, 42, 0.2) 0%, rgba(15, 23, 42, 0.6) 45%, rgba(15, 23, 42, 0.96) 100%);
                    z-index: 2;
                }
                
                .cmr-vp-slide-badge {
                    position: absolute;
                    top: 18px;
                    left: 18px;
                    z-index: 5;
                    background: #FFFFFF;
                    color: #0F172A;
                    border-radius: 0 !important;
                    height: 30px !important;
                    padding: 0 12px !important;
                    box-sizing: border-box !important;
                    font-size: 11px;
                    font-weight: 600;
                    letter-spacing: 0.5px;
                    text-transform: uppercase;
                    display: inline-flex;
                    align-items: center;
                    gap: 6px;
                    line-height: 1;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                }
                .cmr-vp-slide-badge svg {
                    color: #6B3FA0;
                    fill: #6B3FA0;
                }
                
                .cmr-vp-slide-content {
                    position: relative;
                    z-index: 4;
                    padding: 24px 20px;
                    display: flex;
                    flex-direction: column;
                    justify-content: flex-end;
                    text-align: left;
                    background: transparent;
                }
                .cmr-vp-slide-meta {
                    font-size: 13px;
                    font-weight: 500;
                    color: rgba(255, 255, 255, 0.9);
                    margin-bottom: 10px;
                    display: flex;
                    align-items: center;
                    gap: 8px;
                }
                .cmr-vp-slide-meta::before {
                    content: "";
                    display: inline-block;
                    width: 20px;
                    height: 2px;
                    background: rgba(255, 255, 255, 0.85);
                }
                .cmr-vp-slide-title {
                    font-family: "Instrument Sans", sans-serif;
                    font-size: 22px;
                    line-height: 1.25;
                    font-weight: 700;
                    color: #FFFFFF;
                    letter-spacing: -0.5px;
                    margin: 0 0 10px 0;
                    display: -webkit-box;
                    -webkit-line-clamp: 3;
                    -webkit-box-orient: vertical;
                    overflow: hidden;
                }
                .cmr-vp-slide-desc {
                    font-size: 13.5px;
                    color: rgba(255, 255, 255, 0.85);
                    line-height: 1.45;
                    margin-bottom: 18px;
                    display: -webkit-box;
                    -webkit-line-clamp: 3;
                    -webkit-box-orient: vertical;
                    overflow: hidden;
                }
                .cmr-vp-slide-link {
                    color: #FFFFFF;
                    font-size: 14px;
                    font-weight: 600;
                    border-bottom: none;
                    display: inline-flex;
                    align-items: center;
                    gap: 6px;
                    padding: 0;
                    text-decoration: none;
                }
                .cmr-vp-slide-link svg {
                    stroke: #FFFFFF;
                }
                .cmr-vp-slide-link:hover {
                    color: #FFFFFF;
                    opacity: 0.9;
                }
            }

            @media (max-width: 480px) {
                .cmr-vp-slide {
                    height: 440px;
                    min-height: 440px;
                    flex: 0 0 92%;
                    margin-right: 12px;
                }
                .cmr-vp-slide-content {
                    padding: 20px 16px;
                }
                .cmr-vp-slide-title {
                    font-size: 20px;
                }
                .cmr-vp-slide-desc {
                    font-size: 13px;
                    margin-bottom: 14px;
                    -webkit-line-clamp: 2;
                }
            }
        </style>

        <div class="cmr-vp-hero-wrap">
            <div class="cmr-vp-hero-breadcrumbs">
                <a href="<?php echo esc_url(home_url('/')); ?>">Home</a>
                <span>&gt;</span>
                <span style="color: #111; font-weight: 500;">Viewpoints</span>
            </div>

            <h1 class="cmr-vp-hero-title">Strategic Viewpoints on<br> Technology Shifts</h1>
            <p class="cmr-vp-hero-subtitle">Decoding complex market developments through curated editorial insights, expert columns, and analyst commentary.</p>

            <form id="cmr-vp-hero-search-form" class="cmr-vp-hero-search" action="<?php echo esc_url(home_url('/')); ?>" method="get">
                <div class="cmr-vp-hero-search-icon-left">
                    <img src="https://cmrindia.com/wp-content/uploads/2026/06/cmrlogo-with-oly-c.svg" alt="CMR Logo" style="width: 24px; height: auto;">
                </div>
                <input type="text" name="s" placeholder="Search..." required>
                <button type="submit" class="cmr-vp-hero-search-btn" aria-label="<?php esc_attr_e( 'Search Viewpoints', 'quanto' ); ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                </button>
            </form>

            <?php if ( ! empty($posts_data) ) : ?>
            <div class="cmr-vp-slider-wrapper" id="<?php echo esc_attr($slider_id); ?>">
                <div class="cmr-vp-slider-track">
                    <?php foreach ( $posts_data as $index => $post ) : ?>
                        <div class="cmr-vp-slide">
                            <div class="cmr-vp-slide-img">
                                <img src="<?php echo esc_url($post['image']); ?>" alt="<?php echo esc_attr($post['title']); ?>">
                                <div class="cmr-vp-slide-badge">
                                    <svg width="10" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path></svg> FEATURED
                                </div>
                            </div>
                            <div class="cmr-vp-slide-content">
                                <div class="cmr-vp-slide-meta"><?php echo esc_html($post['category']); ?> | <?php echo esc_html($post['date']); ?></div>
                                <h3 class="cmr-vp-slide-title"><?php echo esc_html($post['title']); ?></h3>
                                <div class="cmr-vp-slide-desc"><?php echo wp_kses_post($post['excerpt']); ?></div>
                                <a href="<?php echo esc_url($post['link']); ?>" class="cmr-vp-slide-link">
                                    Read More 
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="cmr-vp-pagination" id="<?php echo esc_attr($slider_id); ?>-dots">
                <?php foreach ( $posts_data as $index => $post ) : ?>
                    <div class="cmr-vp-dot <?php echo $index === 0 ? 'active' : ''; ?>" data-slide="<?php echo esc_attr($index); ?>">
                        <span class="cmr-vp-dot-progress"></span>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const slider = document.getElementById('<?php echo esc_js($slider_id); ?>');
                if (!slider) return;

                const track = slider.querySelector('.cmr-vp-slider-track');
                const slides = slider.querySelectorAll('.cmr-vp-slide');
                const dotsContainer = document.getElementById('<?php echo esc_js($slider_id); ?>-dots');
                const dots = dotsContainer ? dotsContainer.querySelectorAll('.cmr-vp-dot') : [];
                
                if (!track || slides.length === 0) return;

                let currentIndex = 0;

                function updateSlider(index) {
                    currentIndex = index;
                    const slideWidth = slides[0].getBoundingClientRect().width;
                    
                    const slideStyle = window.getComputedStyle(slides[0]);
                    const marginRight = parseFloat(slideStyle.marginRight) || 0;
                    const moveAmount = (slideWidth + marginRight) * index;
                    
                    track.style.transform = 'translateX(-' + moveAmount + 'px)';

                    dots.forEach(function(dot, i) {
                        const progress = dot.querySelector('.cmr-vp-dot-progress');
                        if (i === index) {
                            dot.classList.add('active');
                            if (progress) {
                                // Trigger reflow to restart CSS animation cleanly
                                progress.style.animation = 'none';
                                void progress.offsetWidth;
                                progress.style.animation = '';
                            }
                        } else {
                            dot.classList.remove('active');
                            if (progress) {
                                progress.style.animation = 'none';
                            }
                        }
                    });
                }

                let autoplayTimer = null;
                const autoplayDelay = 5000;

                function startAutoplay() {
                    stopAutoplay();
                    slider.classList.remove('cmr-vp-slider-paused');
                    if (dotsContainer) dotsContainer.classList.remove('cmr-vp-slider-paused');
                    if (slides.length > 1) {
                        autoplayTimer = setInterval(function() {
                            const nextIndex = (currentIndex + 1) % slides.length;
                            updateSlider(nextIndex);
                        }, autoplayDelay);
                    }
                }

                function stopAutoplay() {
                    slider.classList.add('cmr-vp-slider-paused');
                    if (dotsContainer) dotsContainer.classList.add('cmr-vp-slider-paused');
                    if (autoplayTimer) {
                        clearInterval(autoplayTimer);
                        autoplayTimer = null;
                    }
                }

                dots.forEach(function(dot) {
                    dot.addEventListener('click', function() {
                        const index = parseInt(this.getAttribute('data-slide'));
                        updateSlider(index);
                        startAutoplay();
                    });
                });

                // Pause on hover
                slider.addEventListener('mouseenter', stopAutoplay);
                slider.addEventListener('mouseleave', startAutoplay);
                if (dotsContainer) {
                    dotsContainer.addEventListener('mouseenter', stopAutoplay);
                    dotsContainer.addEventListener('mouseleave', startAutoplay);
                }

                // Pause autoplay when tab is not active
                document.addEventListener('visibilitychange', function() {
                    if (document.hidden) {
                        stopAutoplay();
                    } else {
                        startAutoplay();
                    }
                });
                
                // Touch Swipe Support for Mobile & Tablet
                let touchStartX = 0;
                let touchCurrentX = 0;
                let isSwiping = false;

                slider.addEventListener('touchstart', function(e) {
                    stopAutoplay();
                    touchStartX = e.touches[0].clientX;
                    isSwiping = true;
                }, { passive: true });

                slider.addEventListener('touchmove', function(e) {
                    if (!isSwiping) return;
                    touchCurrentX = e.touches[0].clientX;
                }, { passive: true });

                slider.addEventListener('touchend', function() {
                    if (!isSwiping || touchCurrentX === 0) {
                        startAutoplay();
                        return;
                    }
                    const diffX = touchStartX - touchCurrentX;
                    const threshold = 35;
                    if (diffX > threshold) {
                        const nextIndex = (currentIndex + 1) % slides.length;
                        updateSlider(nextIndex);
                    } else if (diffX < -threshold) {
                        const prevIndex = (currentIndex - 1 + slides.length) % slides.length;
                        updateSlider(prevIndex);
                    }
                    isSwiping = false;
                    touchStartX = 0;
                    touchCurrentX = 0;
                    startAutoplay();
                });

                window.addEventListener('resize', function() {
                    updateSlider(currentIndex);
                });

                // Start autoplay on initialization
                startAutoplay();
            });
        </script>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            var vpSearchForm = document.getElementById('cmr-vp-hero-search-form');
            if (vpSearchForm) {
                vpSearchForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    var searchTerm = this.querySelector('input[name="s"]').value;
                    if (!searchTerm) return;
                    
                    var grid = document.querySelector('.cmr-vpi-grid');
                    if (grid) {
                        grid.scrollIntoView({behavior: 'smooth', block: 'start'});
                        grid.innerHTML = '<p style="grid-column:1/-1; text-align:center; padding:40px; font-size:18px;">Searching...</p>';
                        
                        var loadMoreBtn = document.getElementById('cmr-vpi-load-more');
                        if (loadMoreBtn) loadMoreBtn.style.display = 'none';
                        var paginationWrap = document.getElementById('cmr-vpi-pagination-wrap');
                        if (paginationWrap) paginationWrap.style.display = 'none';

                        var formData = new FormData();
                        formData.append('action', 'cmr_insights_ajax_search');
                        formData.append('search_term', searchTerm);
                        formData.append('prefix', 'cmr-vpi-');
                        formData.append('category', 'viewpoint,viewpoints');
                        
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
add_shortcode( 'cmr_viewpoints_hero', 'cmr_viewpoints_hero_shortcode' );
