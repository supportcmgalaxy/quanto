<?php
/**
 * Shortcode for Featured Insight Carousel
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! function_exists( 'cmr_featured_intelligence_carousel_shortcode' ) ) {
    function cmr_featured_intelligence_carousel_shortcode( $atts ) {
        $atts = shortcode_atts( array(
            'post_type'      => '',
            'category'       => '',
            'posts_per_page' => 5,
        ), $atts );

        $page_slug = '';
        $queried_obj = get_queried_object();
        if ( $queried_obj instanceof WP_Post ) {
            $page_slug = $queried_obj->post_name;
        } elseif ( $queried_obj instanceof WP_Term ) {
            $page_slug = $queried_obj->slug;
        }
        if ( empty( $page_slug ) && ! empty( $_SERVER['REQUEST_URI'] ) ) {
            $req_path = trim( wp_parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ), '/' );
            if ( ! empty( $req_path ) ) {
                $segments = explode( '/', $req_path );
                $page_slug = sanitize_title( end( $segments ) );
            }
        }
        if ( empty( $page_slug ) ) {
            global $post;
            if ( ! empty( $post ) && isset( $post->post_name ) ) {
                $page_slug = $post->post_name;
            }
        }

        // Determine category slugs based on attribute or current page
        $cat_slugs = array();
        if ( ! empty( $atts['category'] ) ) {
            $cat_slugs = array_map( 'trim', explode( ',', $atts['category'] ) );
        } elseif ( ! empty( $page_slug ) ) {
            if ( $page_slug === 'industry-intelligence' ) {
                $cat_slugs = array( 'industry-intelligence', 'industry-insights' );
            } elseif ( $page_slug === 'industry-connect' ) {
                $cat_slugs = array( 'industry-connect' );
            } elseif ( in_array( $page_slug, array( 'viewpoints', 'viewpoint' ), true ) ) {
                $cat_slugs = array( 'viewpoints', 'viewpoint' );
            } elseif ( in_array( $page_slug, array( 'market-updates', 'market-update' ), true ) ) {
                $cat_slugs = array( 'market-updates', 'market-update' );
            } elseif ( $page_slug === 'consulting-advisory' ) {
                $cat_slugs = array( 'consulting-advisory', 'consulting' );
            } elseif ( $page_slug === 'marketing-services' ) {
                $cat_slugs = array( 'marketing-services', 'marketing' );
            } elseif ( $page_slug === 'enterprise-connect' ) {
                $cat_slugs = array( 'enterprise-connect' );
            } elseif ( $page_slug === 'channel-connect' ) {
                $cat_slugs = array( 'channel-connect' );
            } elseif ( $page_slug === 'smb-connect' ) {
                $cat_slugs = array( 'smb-connect' );
            } else {
                $cat_slugs = array( $page_slug );
            }
        } else {
            $cat_slugs = array( 'industry-intelligence' );
        }

        $post_type = ! empty( $atts['post_type'] ) ? $atts['post_type'] : array( 'post', 'cmr_news' );

        $query_args = array(
            'post_type'      => $post_type,
            'posts_per_page' => intval( $atts['posts_per_page'] ),
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC',
        );

        if ( ! empty( $cat_slugs ) ) {
            $query_args['tax_query'] = array(
                'relation' => 'OR',
                array(
                    'taxonomy' => 'category',
                    'field'    => 'slug',
                    'terms'    => $cat_slugs,
                ),
                array(
                    'taxonomy' => 'cmr_news_category',
                    'field'    => 'slug',
                    'terms'    => $cat_slugs,
                ),
            );
        }

        $featured_query = new WP_Query( $query_args );

        // Fallback to general posts if no posts found with specific tax query
        if ( ! $featured_query->have_posts() && ! empty( $query_args['tax_query'] ) ) {
            unset( $query_args['tax_query'] );
            $featured_query = new WP_Query( $query_args );
        }

        if ( ! $featured_query->have_posts() ) {
            return '<p>No featured insights found.</p>';
        }

        $posts_data = array();
        while ( $featured_query->have_posts() ) {
            $featured_query->the_post();
            
            $thumbnail_url = get_the_post_thumbnail_url( get_the_ID(), 'full' );
            if ( ! $thumbnail_url ) {
                $thumbnail_url = quanto_get_placeholder_image_uri();
            }
            
            // Categories & Badges
            $category_name = 'Industry Intelligence';
            $badge_name = 'Industry Intelligence';
            $terms = get_the_terms( get_the_ID(), 'category' );
            if ( ! $terms || is_wp_error( $terms ) ) {
                $terms = get_the_terms( get_the_ID(), 'cmr_news_category' );
            }
            if ( $terms && ! is_wp_error( $terms ) ) {
                $found_term = null;
                foreach ( $terms as $t ) {
                    $t_slug = strtolower( $t->slug );
                    $t_name = strtolower( $t->name );
                    if ( $t_slug !== 'uncategorized' && $t_name !== 'cmr in news' && $t_slug !== 'cmr-in-news' && $t_name !== 'news' && $t_slug !== 'news' ) {
                        $found_term = $t;
                        break;
                    }
                }
                if ( ! $found_term ) {
                    $found_term = $terms[0];
                }
                $category_name = $found_term->name;
                $badge_name = $found_term->name;
            }

            if ( in_array( strtolower( $badge_name ), array( 'cmr in news', 'news', 'uncategorized' ), true ) ) {
                $badge_name = 'Industry Intelligence';
                $category_name = 'Industry Intelligence';
            }

            // Read time calculation
            $content = get_post_field( 'post_content', get_the_ID() );
            $word_count = str_word_count( strip_tags( $content ) );
            $read_time = ceil( $word_count / 200 );
            if ($read_time < 1) $read_time = 1;

            // Excerpt
            $excerpt = get_the_excerpt();
            if ( empty( $excerpt ) ) {
                $excerpt = wp_trim_words( $content, 20 );
            } else {
                $excerpt = wp_trim_words( $excerpt, 20 );
            }

            $posts_data[] = array(
                'title'       => get_the_title(),
                'link'        => get_permalink(),
                'image'       => $thumbnail_url,
                'category'    => $category_name,
                'badge'       => $badge_name,
                'read_time'   => $read_time,
                'excerpt'     => wp_kses_post( $excerpt ),
            );
        }
        wp_reset_postdata();

        $slider_id = 'cmr-fi-' . wp_rand(1000, 9999);

        ob_start();
        ?>
        <style>
            .cmr-fi-carousel-wrapper {
                font-family: 'Instrument Sans', sans-serif !important;
                max-width: 1280px;
                margin: 50px auto;
                padding: 0 20px;
                /* overflow: visible to allow thumbs to overlap */
            }
            .cmr-fi-header {
                margin-bottom: 30px;
            }
            .cmr-fi-header h2 {
                font-size: 36px;
                font-weight: 600;
                color: #111;
                margin: 0 0 10px 0;
                letter-spacing: -1px;
            }
            .cmr-fi-header p {
                font-size: 16px;
                color: #555;
                margin: 0;
            }
            
            .cmr-fi-slider-container {
                position: relative;
                width: 100%;
                height: 550px;
                overflow: hidden;
            }
            
            .cmr-fi-track {
                display: flex;
                height: 100%;
                transition: transform 0.5s cubic-bezier(0.25, 1, 0.5, 1);
                gap: 10px;
            }
            
            .cmr-fi-slide {
                flex: 0 0 92%; /* Shows less of the next slide */
                height: 100%;
                position: relative;
                border-radius: 4px;
                overflow: hidden;
                background-size: cover;
                background-position: center;
                transition: opacity 0.5s ease;
                display: block;
                text-decoration: none;
                cursor: pointer;
            }
            .cmr-fi-slide:last-child {
                flex: 0 0 100%;
            }
            
            @media (max-width: 992px) {
                .cmr-fi-slide {
                    flex: 0 0 100%;
                }
            }

            .cmr-fi-slide::before {
                content: '';
                position: absolute;
                top: 0; left: 0; right: 0; bottom: 0;
                background: linear-gradient(to top, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.4) 40%, rgba(0,0,0,0.1) 100%);
            }
            
            .cmr-fi-top-bar {
                position: absolute;
                top: 0;
                left: 0;
                width: 0;
                height: 6px;
                background: #00D2B9; /* Cyan color */
                z-index: 2;
            }

            @keyframes cmrFiProgress {
                0% { width: 0; }
                100% { width: 100%; }
            }

            .cmr-fi-slide.active .cmr-fi-top-bar {
                animation: cmrFiProgress 5s linear forwards;
            }

            .cmr-fi-badge-pill {
                position: absolute;
                top: 30px;
                left: 40px;
                background: #fff;
                color: #111;
                padding: 6px 20px;
                border-radius: 30px;
                font-size: 13px;
                font-weight: 600;
                z-index: 2;
            }

            .cmr-fi-content-box {
                position: absolute;
                bottom: 40px;
                left: 40px;
                right: 40px;
                z-index: 2;
                max-width: 700px;
            }
            
            @media (max-width: 768px) {
                .cmr-fi-carousel-wrapper {
                    padding-left: 16px !important;
                    padding-right: 16px !important;
                    margin-top: 25px !important;
                    margin-bottom: 35px !important;
                    margin-left: 0 !important;
                    margin-right: 0 !important;
                    width: 100% !important;
                    max-width: 100% !important;
                    box-sizing: border-box !important;
                }
                .cmr-fi-header {
                    margin-bottom: 20px !important;
                    width: 100% !important;
                }
                .cmr-fi-header h2 {
                    font-size: 26px !important;
                    line-height: 1.25 !important;
                    letter-spacing: -0.5px !important;
                    margin: 0 0 8px 0 !important;
                }
                .cmr-fi-header p {
                    font-size: 14px !important;
                    line-height: 1.5 !important;
                    color: #475569 !important;
                    margin: 0 !important;
                }
                .cmr-fi-badge-pill { top: 16px; left: 16px; padding: 5px 14px; font-size: 12px; }
                .cmr-fi-content-box { bottom: 16px; left: 16px; right: 16px; }
                .cmr-fi-slider-container { height: 460px; border-radius: 8px; }
                .cmr-fi-slide { border-radius: 8px; flex: 0 0 100% !important; }
                .cmr-fi-slide-title { font-size: 20px !important; line-height: 1.3 !important; margin-bottom: 10px !important; }
                .cmr-fi-slide-excerpt { font-size: 13px !important; line-height: 1.5 !important; margin-bottom: 15px !important; }
            }

            .cmr-fi-meta-row {
                display: flex;
                align-items: center;
                gap: 15px;
                color: #eee;
                font-size: 14px;
                margin-bottom: 15px;
            }
            .cmr-fi-meta-row::before {
                content: '';
                display: block;
                width: 30px;
                height: 1px;
                background: #ccc;
            }

            .cmr-fi-slide-title {
                font-size: 28px;
                font-weight: 600;
                color: #fff;
                margin: 0 0 15px 0;
                line-height: 1.3;
                letter-spacing: -0.5px;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
                transition: color 0.3s ease !important;
            }

            .cmr-fi-slide:hover .cmr-fi-slide-title,
            .cmr-fi-slide-title:hover {
                color: #00D2B9 !important;
            }

            .cmr-fi-slide-excerpt {
                font-size: 15px;
                color: #ddd;
                line-height: 1.6;
                margin-bottom: 25px;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }

            .cmr-fi-more-link {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                font-size: 15px;
                font-weight: 600;
                color: #fff !important;
                text-decoration: none;
                transition: color 0.3s ease;
            }
            .cmr-fi-more-link svg {
                width: 14px;
                height: 14px;
                stroke: #fff !important;
                transition: transform 0.3s ease, stroke 0.3s ease;
            }
            .cmr-fi-slide:hover .cmr-fi-more-link {
                color: #fff !important;
            }
            .cmr-fi-slide:hover .cmr-fi-more-link svg {
                color: #fff !important;
                stroke: #fff !important;
                transform: none !important;
            }
            .cmr-fi-more-link:hover {
                color: #00D2B9 !important;
            }
            .cmr-fi-more-link:hover svg {
                stroke: #00D2B9 !important;
                transform: translate(3px, -3px) !important;
            }


            /* Thumbnails */
            .cmr-fi-thumbs {
                position: relative;
                margin-top: -60px;
                margin-bottom: -25px;
                display: flex;
                justify-content: flex-end;
                padding-right: calc(8% + 30px);
                gap: 10px;
                z-index: 10;
            }
            @media (max-width: 992px) {
                .cmr-fi-thumbs {
                    display: none; /* Hide thumbs on small screens */
                }
            }
            
            .cmr-fi-thumb {
                width: 50px;
                height: 50px;
                border-radius: 6px;
                overflow: hidden;
                cursor: pointer;
                opacity: 0.6;
                transition: all 0.3s ease;
                border: 2px solid transparent;
            }
            .cmr-fi-thumb img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                display: block;
            }
            .cmr-fi-thumb.active {
                opacity: 1;
                border-color: #00D2B9;
                transform: scale(1.1);
            }
            .cmr-fi-thumb:hover {
                opacity: 1;
            }
        </style>

        <div class="cmr-fi-carousel-wrapper" id="<?php echo esc_attr( $slider_id ); ?>">
            <div class="cmr-fi-header">
                <h2>Featured Intelligence</h2>
                <p>Curated insights and high-impact analysis shaping key industry conversations.</p>
            </div>
            
            <div class="cmr-fi-slider-container">
                <div class="cmr-fi-track">
                    <?php foreach ( $posts_data as $index => $post ) : ?>
                        <a href="<?php echo esc_url( $post['link'] ); ?>" class="cmr-fi-slide" style="background-image: url('<?php echo esc_url( $post['image'] ); ?>'); display: block; text-decoration: none; cursor: pointer;">
                            <div class="cmr-fi-top-bar"></div>
                            <div class="cmr-fi-badge-pill"><?php echo esc_html( $post['badge'] ); ?></div>
                            
                            <div class="cmr-fi-content-box">
                                <div class="cmr-fi-meta-row">
                                    <span><?php echo esc_html( $post['category'] ); ?></span>
                                    <span><?php echo esc_html( $post['read_time'] ); ?> min read</span>
                                </div>
                                <h3 class="cmr-fi-slide-title"><?php echo esc_html( $post['title'] ); ?></h3>
                                <div class="cmr-fi-slide-excerpt"><?php echo wp_kses_post( $post['excerpt'] ); ?></div>
                                <span class="cmr-fi-more-link">
                                    More Details 
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="7" y1="17" x2="17" y2="7"></line>
                                        <polyline points="7 7 17 7 17 17"></polyline>
                                    </svg>
                                </span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="cmr-fi-thumbs">
                <?php foreach ( $posts_data as $index => $post ) : ?>
                    <div class="cmr-fi-thumb <?php echo $index === 0 ? 'active' : ''; ?>" data-slide="<?php echo esc_attr($index); ?>">
                        <img src="<?php echo esc_url( $post['image'] ); ?>" alt="Thumb">
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const slider = document.getElementById('<?php echo esc_js( $slider_id ); ?>');
                if (!slider) return;

                const track = slider.querySelector('.cmr-fi-track');
                const thumbs = slider.querySelectorAll('.cmr-fi-thumb');
                const slides = slider.querySelectorAll('.cmr-fi-slide');
                
                if (!track || slides.length === 0) return;

                let currentIndex = 0;
                let intervalId;

                function startInterval() {
                    clearInterval(intervalId);
                    intervalId = setInterval(function() {
                        let nextIndex = (currentIndex + 1) % slides.length;
                        updateSlider(nextIndex);
                    }, 5000);
                }

                function updateSlider(index) {
                    currentIndex = index;
                    
                    const slideWidth = slides[0].getBoundingClientRect().width;
                    const gap = 10; 
                    const moveAmount = (slideWidth + gap) * index;
                    
                    track.style.transform = 'translateX(-' + moveAmount + 'px)';

                    // Update slides active class to restart animation
                    slides.forEach(function(slide, i) {
                        slide.classList.remove('active');
                        if (i === index) {
                            // Trigger reflow to restart CSS animation
                            void slide.offsetWidth;
                            slide.classList.add('active');
                        }
                    });

                    // Update thumbs
                    thumbs.forEach(function(thumb, i) {
                        if (i === index) {
                            thumb.classList.add('active');
                        } else {
                            thumb.classList.remove('active');
                        }
                    });

                    startInterval();
                }

                thumbs.forEach(function(thumb) {
                    thumb.addEventListener('click', function() {
                        const index = parseInt(this.getAttribute('data-slide'));
                        updateSlider(index);
                    });
                });
                
                // Recalculate on resize
                window.addEventListener('resize', function() {
                    updateSlider(currentIndex);
                });

                // Initialize the slider
                updateSlider(0);

            });
        </script>
        <?php
        return ob_get_clean();
    }
}
// Remove old shortcode if previously added
remove_shortcode( 'cmr_featured_intelligence_carousel' );
add_shortcode( 'cmr_featured_intelligence_carousel', 'cmr_featured_intelligence_carousel_shortcode' );
