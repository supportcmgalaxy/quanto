<?php
/**
 * Shortcode for Industry Intelligence Trends Section
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! function_exists( 'cmr_industry_intelligence_trends_shortcode' ) ) {
    function cmr_industry_intelligence_trends_shortcode( $atts ) {
        $atts = shortcode_atts( array(
            'posts_per_page' => 6,
            'category'       => '',
            'title'          => '',
            'subtitle'       => '',
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

        // Determine specific category slugs based on attribute or current page
        $cat_slugs = array();
        $section_default_title = 'Industry Intelligence Trends';
        $section_default_label = 'Industry Intelligence';

        if ( ! empty( $atts['category'] ) ) {
            $cat_slugs = array_map( 'trim', explode( ',', $atts['category'] ) );
        } elseif ( ! empty( $page_slug ) ) {
            if ( $page_slug === 'industry-intelligence' ) {
                $cat_slugs = array( 'industry-intelligence' );
                $section_default_title = 'Industry Intelligence Trends';
                $section_default_label = 'Industry Intelligence';
            } elseif ( $page_slug === 'industry-connect' ) {
                $cat_slugs = array( 'industry-connect' );
                $section_default_title = 'Industry Connect Trends';
                $section_default_label = 'Industry Connect';
            } elseif ( in_array( $page_slug, array( 'viewpoints', 'viewpoint' ), true ) ) {
                $cat_slugs = array( 'viewpoints', 'viewpoint' );
                $section_default_title = 'Viewpoints Trends';
                $section_default_label = 'Viewpoints';
            } elseif ( in_array( $page_slug, array( 'market-updates', 'market-update' ), true ) ) {
                $cat_slugs = array( 'market-updates', 'market-update' );
                $section_default_title = 'Market Updates Trends';
                $section_default_label = 'Market Updates';
            } elseif ( $page_slug === 'consulting-advisory' ) {
                $cat_slugs = array( 'consulting-advisory', 'consulting' );
                $section_default_title = 'Consulting & Advisory Trends';
                $section_default_label = 'Consulting & Advisory';
            } elseif ( $page_slug === 'marketing-services' ) {
                $cat_slugs = array( 'marketing-services', 'marketing' );
                $section_default_title = 'Marketing Services Trends';
                $section_default_label = 'Marketing Services';
            } elseif ( $page_slug === 'enterprise-connect' ) {
                $cat_slugs = array( 'enterprise-connect' );
                $section_default_title = 'Enterprise Connect Trends';
                $section_default_label = 'Enterprise Connect';
            } elseif ( $page_slug === 'channel-connect' ) {
                $cat_slugs = array( 'channel-connect' );
                $section_default_title = 'Channel Connect Trends';
                $section_default_label = 'Channel Connect';
            } elseif ( $page_slug === 'smb-connect' ) {
                $cat_slugs = array( 'smb-connect' );
                $section_default_title = 'SMB Connect Trends';
                $section_default_label = 'SMB Connect';
            } else {
                $cat_slugs = array( $page_slug );
                $section_default_title = ucwords( str_replace( '-', ' ', $page_slug ) ) . ' Trends';
                $section_default_label = ucwords( str_replace( '-', ' ', $page_slug ) );
            }
        } else {
            $cat_slugs = array( 'industry-intelligence' );
        }

        $query_args = array(
            'post_type'      => array( 'post', 'cmr_news' ),
            'posts_per_page' => intval( $atts['posts_per_page'] ),
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC',
            'tax_query'      => array(
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
            ),
        );

        $trends_query = new WP_Query( $query_args );


        ob_start();
        ?>
        <style>
            .cmr-intel-trends-wrapper {
                font-family: 'Instrument Sans', sans-serif !important;
                max-width: 1280px;
                margin: 50px auto;
                padding: 0 20px;
                overflow: hidden; /* Hide outer overflow */
            }
            .cmr-intel-trends-header {
                margin-bottom: 40px;
            }
            .cmr-intel-trends-header h2 {
                font-size: 36px;
                font-weight: 600;
                color: #111;
                margin: 0 0 10px 0;
                letter-spacing: -1px;
            }
            .cmr-intel-trends-header p {
                font-size: 16px;
                color: #555;
                margin: 0;
            }
            
            .cmr-intel-trends-track-container {
                overflow: hidden; /* Important for GSAP */
                width: 100%;
            }

            .cmr-intel-trends-track {
                display: flex;
                gap: 20px;
                width: max-content; /* Let it be as wide as needed for GSAP */
                padding-bottom: 20px;
            }

            .cmr-intel-trends-card {
                /* We set fixed width for cards so they overflow the screen */
                width: calc((1280px - 80px) / 3); /* approx 1/3 of max container width */
                max-width: 400px; 
                flex-shrink: 0;
                display: flex;
                flex-direction: column;
            }
            
            @media (max-width: 992px) {
                .cmr-intel-trends-card {
                    width: calc(50vw - 30px);
                }
            }
            @media (max-width: 768px) {
                .cmr-intel-trends-wrapper {
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
                .cmr-intel-trends-header {
                    margin-bottom: 20px !important;
                    width: 100% !important;
                }
                .cmr-intel-trends-header h2 {
                    font-size: 26px !important;
                    line-height: 1.25 !important;
                    letter-spacing: -0.5px !important;
                    margin: 0 0 8px 0 !important;
                }
                .cmr-intel-trends-header p {
                    font-size: 14px !important;
                    line-height: 1.5 !important;
                    color: #475569 !important;
                    margin: 0 !important;
                }
                .cmr-intel-trends-card {
                    width: calc(85vw - 20px);
                }
            }

            .cmr-intel-trends-img {
                width: 100%;
                aspect-ratio: 16 / 9;
                overflow: hidden;
                margin-bottom: 20px;
            }

            .cmr-intel-trends-img img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                display: block;
                transform: scale(1);
                transition: transform 0.4s cubic-bezier(0.25, 1, 0.5, 1) !important;
            }

            .cmr-intel-trends-card:hover .cmr-intel-trends-img img,
            .cmr-intel-trends-img:hover img {
                transform: scale(1.06) !important;
            }

            .cmr-intel-trends-meta {
                display: flex;
                justify-content: space-between;
                align-items: center;
                font-size: 13px;
                color: #888;
                margin-bottom: 15px;
            }

            .cmr-intel-trends-cat {
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .cmr-intel-trends-cat::before {
                content: '';
                display: block;
                width: 20px;
                height: 1px;
                background: #ccc;
            }

            .cmr-intel-trends-title {
                font-size: 18px;
                font-weight: 600;
                line-height: 1.4;
                margin: 0 0 25px 0;
                letter-spacing: -0.3px;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }

            .cmr-intel-trends-title a {
                color: #111;
                text-decoration: none;
                transition: color 0.3s ease !important;
            }

            .cmr-intel-trends-card:hover .cmr-intel-trends-title,
            .cmr-intel-trends-card:hover .cmr-intel-trends-title a,
            .cmr-intel-trends-title:hover,
            .cmr-intel-trends-title:hover a,
            .cmr-intel-trends-title a:hover {
                color: #4820B0 !important;
            }

            .cmr-intel-trends-more {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                font-size: 14px;
                font-weight: 600;
                color: #111 !important;
                text-decoration: none;
                border-bottom: 1.5px solid #111 !important;
                padding-bottom: 2px;
                align-self: flex-start;
                margin-top: auto;
                transition: color 0.3s ease, border-color 0.3s ease;
            }

            .cmr-intel-trends-more svg {
                width: 12px;
                height: 12px;
                stroke: #111 !important;
                transition: transform 0.3s ease, stroke 0.3s ease;
            }

            .cmr-intel-trends-card:hover .cmr-intel-trends-more {
                color: #111 !important;
                border-bottom-color: #111 !important;
            }

            .cmr-intel-trends-card:hover .cmr-intel-trends-more svg {
                color: #111 !important;
                stroke: #111 !important;
                transform: none !important;
            }

            .cmr-intel-trends-more:hover {
                color: #6A42E5 !important;
                border-color: #6A42E5 !important;
            }

            .cmr-intel-trends-more:hover svg {
                stroke: #6A42E5 !important;
                transform: translate(2px, -2px) !important;
            }

        </style>

        <div class="cmr-intel-trends-wrapper" id="cmr-intel-trends-section">
            <div class="cmr-intel-trends-header">
                <h2><?php echo esc_html( ! empty( $atts['title'] ) ? $atts['title'] : $section_default_title ); ?></h2>
                <p><?php echo esc_html( ! empty( $atts['subtitle'] ) ? $atts['subtitle'] : 'Track emerging shifts, growth signals, and market movements in real time.' ); ?></p>
            </div>
            
            <?php if ( $trends_query->have_posts() ) : ?>
                <div class="cmr-intel-trends-track-container">
                    <div class="cmr-intel-trends-track" id="cmr-intel-trends-track">
                        <?php
                        while ( $trends_query->have_posts() ) : $trends_query->the_post();
                            $post_title = get_the_title();
                            $post_link = get_permalink();
                            $thumbnail_url = get_the_post_thumbnail_url( get_the_ID(), 'full' );
                            if ( ! $thumbnail_url ) {
                                $thumbnail_url = quanto_get_placeholder_image_uri();
                            }
                            
                            // Category
                            $category_name = ! empty( $section_default_label ) ? $section_default_label : 'Industry Intelligence';
                            $terms = get_the_terms( get_the_ID(), 'category' );
                            if ( ! $terms || is_wp_error( $terms ) ) {
                                $terms = get_the_terms( get_the_ID(), 'cmr_news_category' );
                            }
                            if ( $terms && ! is_wp_error( $terms ) ) {
                                foreach ( $terms as $t ) {
                                    if ( in_array( $t->slug, $cat_slugs, true ) ) {
                                        $category_name = $t->name;
                                        break;
                                    }
                                }
                                if ( empty( $category_name ) ) {
                                    $valid_terms = array_filter( $terms, function( $t ) {
                                        return $t->slug !== 'uncategorized';
                                    });
                                    if ( ! empty( $valid_terms ) ) {
                                        $first_term = reset( $valid_terms );
                                        $category_name = $first_term->name;
                                    }
                                }
                            }


                            // Reading time
                            $content = get_post_field( 'post_content', get_the_ID() );
                            $word_count = str_word_count( strip_tags( $content ) );
                            $read_time = ceil( $word_count / 200 );
                            if ($read_time < 1) $read_time = 1;
                            ?>
                            
                            <div class="cmr-intel-trends-card">
                                <div class="cmr-intel-trends-img">
                                    <a href="<?php echo esc_url( $post_link ); ?>">
                                        <img src="<?php echo esc_url( $thumbnail_url ); ?>" alt="<?php echo esc_attr( $post_title ); ?>" loading="lazy" />
                                    </a>
                                </div>
                                <div class="cmr-intel-trends-meta">
                                    <span class="cmr-intel-trends-cat"><?php echo esc_html( $category_name ); ?></span>
                                    <span><?php echo esc_html( $read_time ); ?> min read</span>
                                </div>
                                <h3 class="cmr-intel-trends-title">
                                    <a href="<?php echo esc_url( $post_link ); ?>"><?php echo esc_html( $post_title ); ?></a>
                                </h3>
                                <a href="<?php echo esc_url( $post_link ); ?>" class="cmr-intel-trends-more">
                                    More Details 
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="7" y1="17" x2="17" y2="7"></line>
                                        <polyline points="7 7 17 7 17 17"></polyline>
                                    </svg>
                                </a>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            <?php else : ?>
                <p>No trends found.</p>
            <?php endif; wp_reset_postdata(); ?>
        </div>

        <script>
        document.addEventListener("DOMContentLoaded", function() {
            if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
                gsap.registerPlugin(ScrollTrigger);
                
                let track = document.getElementById("cmr-intel-trends-track");
                let section = document.getElementById("cmr-intel-trends-section");
                
                if (track && section) {
                    function getScrollAmount() {
                        let trackWidth = track.scrollWidth;
                        let containerWidth = track.parentElement.offsetWidth;
                        // Move left by the difference between full track width and visible container width
                        return -(trackWidth - containerWidth); 
                    }
                    
                    const tween = gsap.to(track, {
                        x: getScrollAmount,
                        ease: "none"
                    });
    
                    ScrollTrigger.create({
                        trigger: section,
                        start: "center center", // Pin when section reaches center
                        end: () => `+=${getScrollAmount() * -1}`, // Scroll length based on track width
                        pin: true,
                        animation: tween,
                        scrub: 1,
                        invalidateOnRefresh: true
                    });
                }
            }
        });
        </script>
        <?php
        return ob_get_clean();
    }
}
add_shortcode( 'cmr_industry_intelligence_trends', 'cmr_industry_intelligence_trends_shortcode' );
