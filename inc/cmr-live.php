<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! function_exists( 'cmr_live_shortcode' ) ) {
    function cmr_live_shortcode( $atts ) {
        $atts = shortcode_atts( array(
            'posts_per_page' => 10,
        ), $atts, 'cmr_live_page' );
        
        // Call the new shortcode function to return identical HTML
        return cmr_live_podcast_carousel_shortcode($atts);
    }
}
add_shortcode( 'cmr_live_page', 'cmr_live_shortcode' );

if ( ! function_exists( 'cmr_live_podcast_carousel_shortcode' ) ) {
    function cmr_live_podcast_carousel_shortcode( $atts ) {
        $atts = shortcode_atts( array(
            'posts_per_page' => 10,
        ), $atts, 'cmr_live_podcast_carousel' );

        $query_args = array(
            'post_type'      => 'cmr_media',
            'posts_per_page' => intval( $atts['posts_per_page'] ),
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC',
            'meta_query'     => array(
                'relation' => 'OR',
                array(
                    'key'     => '_cmr_show_in_podcast',
                    'value'   => '1',
                    'compare' => '=',
                ),
                array(
                    'relation' => 'AND',
                    array(
                        'key'     => '_cmr_media_type',
                        'value'   => 'PODCAST',
                        'compare' => 'LIKE',
                    ),
                    array(
                        'key'     => '_cmr_show_in_podcast',
                        'compare' => 'NOT EXISTS',
                    ),
                ),
            ),
        );

        $live_query = new WP_Query( $query_args );
        $posts = $live_query->posts;

        // Fallback to all media if no posts found with specific meta
        if ( empty( $posts ) ) {
            $fallback_args = array(
                'post_type'      => 'cmr_media',
                'posts_per_page' => intval( $atts['posts_per_page'] ),
                'post_status'    => 'publish',
                'orderby'        => 'date',
                'order'          => 'DESC',
            );
            $live_query = new WP_Query( $fallback_args );
            $posts = $live_query->posts;
        }

        ob_start();
        ?>
        <style>
            .cmr-podcast-carousel-section {
                background-color: #111;
                padding: 60px 0;
                color: #fff;
                font-family: inherit;
            }
            .cmr-podcast-carousel-inner {
                max-width: 1280px;
                margin: 0 auto;
                padding: 0 20px;
            }
            .cmr-podcast-breadcrumb {
                font-size: 12px;
                color: #888;
                margin-bottom: 20px;
            }
            .cmr-podcast-breadcrumb span {
                color: #fff;
            }
            .cmr-podcast-header {
                display: flex;
                justify-content: space-between;
                align-items: flex-end;
                margin-bottom: 40px;
            }
            .cmr-podcast-title-area h2 {
                font-size: 42px;
                font-weight: 600;
                color: #fff;
                margin: 0 0 10px 0;
                letter-spacing: 0 !important;
            }
            .cmr-podcast-title-area p {
                font-size: 16px;
                color: #aaa;
                margin: 0;
            }
            .cmr-podcast-swiper {
                overflow: visible !important;
                clip-path: inset(-100px -100vw -100px 0);
            }
            .cmr-podcast-pagination {
                display: flex;
                gap: 8px;
                justify-content: flex-end;
                position: relative;
                width: auto;
            }
            .cmr-podcast-pagination .swiper-pagination-bullet {
                position: relative;
                width: 30px;
                height: 3px;
                background: #555;
                border-radius: 0;
                opacity: 1;
                margin: 0;
                overflow: hidden;
            }
            .cmr-podcast-pagination .swiper-pagination-bullet::after {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                height: 100%;
                width: 0;
                background: #fff;
            }
            .cmr-podcast-pagination .swiper-pagination-bullet-active {
                background: #555;
            }
            .cmr-podcast-pagination .swiper-pagination-bullet-active::after {
                animation: bulletProgress 4s linear forwards;
            }
            @keyframes bulletProgress {
                0% { width: 0; }
                100% { width: 100%; }
            }
            .cmr-podcast-card {
                display: flex;
                flex-direction: column;
                background: transparent;
                color: #fff;
                text-decoration: none;
            }
            .cmr-podcast-swiper .swiper-slide {
                width: 910px;
                max-width: 90vw;
            }
            .cmr-podcast-img-wrap {
                position: relative;
                width: 100%;
                aspect-ratio: 910 / 479;
                border-radius: 8px;
                overflow: hidden;
                margin-bottom: 20px;
                background: #222;
            }
            .cmr-podcast-img-wrap img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                display: block;
            }
            .cmr-podcast-badge {
                position: absolute;
                top: 15px;
                left: 15px;
                background: #fff;
                color: #111;
                font-size: 11px;
                font-weight: 600;
                padding: 4px 8px;
                border-radius: 4px;
                z-index: 2;
            }
            .cmr-podcast-play-btn {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                width: 50px;
                height: 50px;
                background: rgba(255, 255, 255, 0.3);
                backdrop-filter: blur(4px);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 2;
            }
            .cmr-podcast-play-btn svg {
                width: 20px;
                height: 20px;
                fill: #fff;
                margin-left: 3px;
            }
            .cmr-podcast-meta {
                font-size: 11px;
                font-weight: 600;
                color: #888;
                margin-bottom: 12px;
                letter-spacing: 0.5px;
                text-transform: uppercase;
            }
            .cmr-podcast-meta .type-podcast {
                color: #4820B0;
            }
            .cmr-podcast-meta .type-topview {
                color: #4820B0;
            }
            .cmr-podcast-title {
                font-size: 20px;
                font-weight: 600; /* Bolder as requested */
                color: #fff;
                margin: 0;
                line-height: 1.4;
                letter-spacing: 0 !important; /* Fix squishing */
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }
            .cmr-podcast-player {
                display: none !important;
            }
            .cmr-podcast-player .play-icon {
                width: 30px;
                height: 30px;
                border: 2px solid #fff;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
            }
            .cmr-podcast-player .play-icon svg {
                width: 12px;
                height: 12px;
                fill: #fff;
                margin-left: 2px;
            }
            .cmr-podcast-player .waveform {
                flex: 1;
                height: 20px;
                background: repeating-linear-gradient(90deg, #555, #555 2px, transparent 2px, transparent 4px);
                opacity: 0.5;
            }
            .cmr-podcast-player .progress-bar-wrap {
                flex: 1;
                height: 4px;
                background: #555;
                border-radius: 2px;
                position: relative;
            }
            .cmr-podcast-player .progress-bar-inner {
                width: 30%;
                height: 100%;
                background: #fff;
                border-radius: 2px;
            }
            .cmr-podcast-player .time {
                font-size: 11px;
                color: #aaa;
                font-weight: 600;
            }
            .cmr-podcast-player .controls {
                display: flex;
                align-items: center;
                gap: 10px;
                color: #fff;
            }
            .cmr-podcast-player .controls svg {
                width: 16px;
                height: 16px;
                fill: none;
                stroke: currentColor;
                stroke-width: 2;
            }
            .cmr-podcast-player .volume {
                display: flex;
                align-items: center;
                gap: 5px;
            }
            .cmr-podcast-player .volume-bar {
                width: 40px;
                height: 4px;
                background: #fff;
                border-radius: 2px;
            }
            @media (max-width: 768px) {
                .cmr-podcast-header {
                    flex-direction: column;
                    align-items: flex-start;
                    gap: 20px;
                }
                .cmr-podcast-player .waveform, .cmr-podcast-player .progress-bar-wrap {
                    display: none;
                }
            }
            /* Add overflow hidden to body/section to prevent horizontal scrollbar on whole page */
            .cmr-podcast-carousel-section {
                overflow: hidden;
            }
        </style>

        <div class="cmr-podcast-carousel-section">
            <div class="cmr-podcast-carousel-inner">
                <div class="cmr-podcast-breadcrumb">
                    Home > <span>CMR Live</span>
                </div>
                
                <div class="cmr-podcast-header">
                    <div class="cmr-podcast-title-area">
                        <h2>CMR Live - Podcast </h2>
                        <p>Browse premium research reports across industries, designed to deliver actionable insights and strategic clarity.</p>
                    </div>
                    <div class="cmr-podcast-nav-area" style="display: flex; align-items: center; gap: 15px;">
                        <div class="cmr-podcast-pagination swiper-pagination-custom"></div>
                    </div>
                </div>

                <div class="swiper cmr-podcast-swiper">
                    <div class="swiper-wrapper">
                        <?php if ( ! empty($posts) ) : ?>
                            <?php 
                            foreach ( $posts as $post_obj ) : 
                                // Get thumbnail using our new smart fallback function
                                $thumbnail_url = function_exists('cmr_get_thumbnail_with_fallback') ? cmr_get_thumbnail_with_fallback($post_obj->ID, 'large') : get_the_post_thumbnail_url( $post_obj->ID, 'large' );
                                
                                $category_name = 'AUTOMOTIVE';
                                $terms = get_the_terms( $post_obj->ID, 'category' );
                                if ( $terms && ! is_wp_error( $terms ) ) {
                                    $category_name = $terms[0]->name;
                                }
                                
                                $post_date = get_the_date('d M Y', $post_obj);
                                $media_type = get_post_meta( $post_obj->ID, '_cmr_media_type', true );
                                $media_duration = get_post_meta( $post_obj->ID, '_cmr_media_duration', true );
                                $media_url = get_post_meta( $post_obj->ID, '_cmr_media_url', true );
                                
                                $type = $media_type ? $media_type : 'PODCAST';
                                $is_podcast = (strtoupper($type) === 'PODCAST');
                                $type_class = $is_podcast ? 'type-podcast' : 'type-topview';
                                
                                $duration = $media_duration ? $media_duration : '05:00 MINS';
                                $link = esc_url(get_permalink($post_obj->ID));
                            ?>
                            <div class="swiper-slide">
                                <a href="<?php echo $link; ?>" class="cmr-podcast-card" target="_blank" rel="noopener noreferrer">
                                    <div class="cmr-podcast-img-wrap">
                                        <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo esc_attr(get_the_title($post_obj)); ?>">
                                        <div class="cmr-podcast-badge"><?php echo esc_html($duration); ?></div>
                                        <?php if ( ! $is_podcast ) : ?>
                                        <div class="cmr-podcast-play-btn">
                                            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="cmr-podcast-meta">
                                        <span class="<?php echo esc_attr($type_class); ?>"><?php echo esc_html($type); ?></span> &bull; 
                                        <?php echo esc_html(strtoupper($category_name)); ?> &bull; 
                                        <?php echo esc_html(strtoupper($post_date)); ?> &bull; 100 VIEW
                                    </div>
                                    
                                    <h3 class="cmr-podcast-title"><?php echo esc_html(get_the_title($post_obj)); ?></h3>
                                </a>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof Swiper !== 'undefined') {
                    new Swiper('.cmr-podcast-swiper', {
                        slidesPerView: 'auto',
                        spaceBetween: 30,
                        autoplay: {
                            delay: 4000,
                            disableOnInteraction: false,
                        },
                        pagination: {
                            el: '.cmr-podcast-pagination',
                            clickable: true,
                        },
                        breakpoints: {
                            768: {
                                slidesPerView: 'auto',
                            },
                            1024: {
                                slidesPerView: 'auto',
                            }
                        }
                    });
                }
            });
        </script>
        <?php
        wp_reset_postdata();
        return ob_get_clean();
    }
}
add_shortcode( 'cmr_live_podcast_carousel', 'cmr_live_podcast_carousel_shortcode' );

if ( ! function_exists( 'cmr_trending_podcast_shortcode' ) ) {
    function cmr_trending_podcast_shortcode( $atts ) {
        $atts = shortcode_atts( array(
            'posts_per_page' => 3,
        ), $atts, 'cmr_trending_podcast' );

        $query_args = array(
            'post_type'      => 'cmr_media',
            'posts_per_page' => intval( $atts['posts_per_page'] ),
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC',
            'meta_query'     => array(
                'relation' => 'OR',
                array(
                    'key'     => '_cmr_show_in_podcast',
                    'value'   => '1',
                    'compare' => '=',
                ),
                array(
                    'relation' => 'AND',
                    array(
                        'key'     => '_cmr_media_type',
                        'value'   => 'PODCAST',
                        'compare' => 'LIKE',
                    ),
                    array(
                        'key'     => '_cmr_show_in_podcast',
                        'compare' => 'NOT EXISTS',
                    ),
                ),
            ),
        );

        $trending_query = new WP_Query( $query_args );
        $posts = $trending_query->posts;

        if ( empty( $posts ) ) {
            $fallback_args = array(
                'post_type'      => 'cmr_media',
                'posts_per_page' => intval( $atts['posts_per_page'] ),
                'post_status'    => 'publish',
                'orderby'        => 'date',
                'order'          => 'DESC',
            );
            $trending_query = new WP_Query( $fallback_args );
            $posts = $trending_query->posts;
        }

        ob_start();
        ?>
        <style>
            html {
                scroll-behavior: smooth;
            }
            .cmr-trending-section {
                padding: 60px 20px;
                background-color: #fff;
                font-family: inherit;
                max-width: 1280px;
                margin: 0 auto;
                scroll-margin-top: 110px;
            }
            .cmr-trending-title {
                font-size: 42px;
                font-weight: 600;
                color: #000;
                margin: 0 0 40px 0;
                letter-spacing: 0 !important;
            }
            .cmr-trending-grid {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 30px;
            }
            .cmr-trending-card {
                display: flex;
                flex-direction: column;
                background: #fff;
                text-decoration: none;
                color: #000;
            }
            .cmr-trending-img-wrap {
                position: relative;
                width: 100%;
                aspect-ratio: 16/10;
                overflow: hidden;
                margin-bottom: 20px;
                background: #f5f5f5;
            }
            .cmr-trending-img-wrap img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                display: block;
            }
            .cmr-trending-badge {
                position: absolute;
                top: 15px;
                left: 15px;
                background: #fff;
                color: #111;
                font-size: 11px;
                font-weight: 600;
                padding: 4px 8px;
                border-radius: 4px;
                z-index: 2;
            }
            .cmr-trending-play-btn {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                width: 50px;
                height: 50px;
                background: transparent;
                border: 2px solid #fff;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 2;
            }
            .cmr-trending-play-btn svg {
                width: 20px;
                height: 20px;
                fill: #fff;
                margin-left: 3px;
            }
            .cmr-trending-meta {
                font-size: 11px;
                font-weight: 600;
                color: #888;
                margin-bottom: 12px;
                letter-spacing: 0.5px;
                text-transform: uppercase;
            }
            .cmr-trending-meta .type-podcast {
                color: #4820B0;
            }
            .cmr-trending-meta .type-topview {
                color: #4820B0;
            }
            .cmr-trending-card-title {
                font-size: 22px;
                font-weight: 600;
                color: #000;
                margin: 0 0 20px 0;
                line-height: 1.3;
                letter-spacing: 0 !important;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }
            .cmr-trending-btn-wrap {
                margin-top: auto;
            }
            .cmr-trending-btn {
                display: inline-flex;
                align-items: center;
                gap: 10px;
                padding: 10px 20px;
                border: 1px solid #ccc;
                border-radius: 30px;
                background: #fff;
                color: #000;
                font-size: 14px;
                font-weight: 600;
                text-decoration: none;
                transition: all 0.2s ease;
            }
            .cmr-trending-btn:hover {
                border-color: #000;
                background: #fafafa;
            }
            .cmr-trending-btn svg {
                width: 16px;
                height: 16px;
                fill: currentColor;
            }
            @media (max-width: 1024px) {
                .cmr-trending-grid {
                    grid-template-columns: repeat(2, 1fr);
                }
            }
            @media (max-width: 768px) {
                .cmr-trending-grid {
                    grid-template-columns: 1fr;
                }
                .cmr-trending-title {
                    font-size: 32px;
                }
            }
        </style>

        <div class="cmr-trending-section" id="podcasts">
            <h2 class="cmr-trending-title">Trending Podcast</h2>
            
            <div class="cmr-trending-grid">
                <?php if ( ! empty($posts) ) : ?>
                    <?php 
                    foreach ( $posts as $post_obj ) : 
                        $thumbnail_url = '';
                        if ( function_exists('cmr_get_thumbnail_with_fallback') ) {
                            $thumbnail_url = cmr_get_thumbnail_with_fallback($post_obj->ID, 'large');
                        } else {
                            $thumbnail_url = get_the_post_thumbnail_url( $post_obj->ID, 'large' );
                            if ( ! $thumbnail_url ) {
                                $thumbnail_url = 'https://cmrindia.com/wp-content/uploads/2026/06/Why-Chipsets-are-the-New-Frontier-in-Smartphones1.jpg';
                            }
                        }
                        
                        $category_name = 'AUTOMOTIVE';
                        $terms = get_the_terms( $post_obj->ID, 'category' );
                        if ( $terms && ! is_wp_error( $terms ) ) {
                            $category_name = $terms[0]->name;
                        }
                        
                        $post_date = get_the_date('d M', $post_obj); // e.g. "10 MAY"
                        $media_type = get_post_meta( $post_obj->ID, '_cmr_media_type', true );
                        $media_duration = get_post_meta( $post_obj->ID, '_cmr_media_duration', true );
                        $media_url = get_post_meta( $post_obj->ID, '_cmr_media_url', true );
                        
                        $type = $media_type ? $media_type : 'PODCAST';
                        $is_podcast = (strtoupper($type) === 'PODCAST');
                        $type_class = $is_podcast ? 'type-podcast' : 'type-topview';
                        
                        $duration = $media_duration ? $media_duration : '05:00 MINS';
                        $link = esc_url(get_permalink($post_obj->ID));
                    ?>
                    <a href="<?php echo $link; ?>" class="cmr-trending-card" target="_blank" rel="noopener noreferrer">
                        <div class="cmr-trending-img-wrap">
                            <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo esc_attr(get_the_title($post_obj)); ?>">
                            <div class="cmr-trending-badge"><?php echo esc_html($duration); ?></div>
                            <div class="cmr-trending-play-btn">
                                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                            </div>
                        </div>
                        
                        <div class="cmr-trending-meta">
                            <span class="<?php echo esc_attr($type_class); ?>"><?php echo esc_html($type); ?></span> &bull; 
                            <?php echo esc_html(strtoupper($category_name)); ?> &bull; 
                            <?php echo esc_html(strtoupper($post_date)); ?>
                        </div>
                        
                        <h3 class="cmr-trending-card-title"><?php echo esc_html(get_the_title($post_obj)); ?></h3>
                        
                        <div class="cmr-trending-btn-wrap">
                            <div class="cmr-trending-btn">
                                <span class="elementor-button-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M19.5743 17.7C20.0851 17.8692 20.637 17.5953 20.8074 17.0879C21.3167 15.5698 21.75 13.4642 21.75 11.9376C21.75 6.58728 17.3848 2.25 12 2.25C6.61522 2.25 2.25 6.58728 2.25 11.9376C2.25 13.2733 2.58197 15.052 3.00601 16.4928L3.19263 17.0879C3.36301 17.5953 3.9149 17.8692 4.42566 17.7C4.90447 17.5413 5.17745 17.0495 5.06931 16.5704C4.60361 15.1822 4.2 13.1949 4.2 11.9376C4.2 7.65734 7.69218 4.18752 12 4.18752C16.3078 4.18752 19.8 7.65734 19.8 11.9376C19.8 13.1949 19.4231 15.0867 18.9573 16.4748C18.7871 16.9824 19.0636 17.5307 19.5743 17.7Z" fill="currentColor"></path><path d="M7.86225 14.1777C7.48381 13.461 6.63943 13.1705 5.94623 13.2744L5.73725 13.3203C3.83231 13.9556 2.80552 16.0192 3.43549 17.9267L3.93647 19.4296C4.6321 21.2254 6.61779 22.1809 8.46479 21.5654L8.66206 21.4765C8.98391 21.3064 9.26209 21.0422 9.45405 20.7431C9.67098 20.4051 9.83662 19.9254 9.70209 19.415L7.86225 14.1777Z" fill="currentColor"></path><path d="M18.0534 13.2744C17.3602 13.1705 16.5158 13.461 16.1374 14.1777L14.2976 19.415C14.163 19.9254 14.3287 20.4051 14.5456 20.7431C14.765 21.0849 15.0971 21.3816 15.4782 21.5439L15.7136 21.6201C17.5683 22.1353 19.5158 21.1024 20.1267 19.2529L20.6189 17.747C21.1129 15.951 20.172 14.0615 18.4382 13.3837L18.0534 13.2744Z" fill="currentColor"></path></svg>
                                </span>
                                Play Episode
                            </div>
                        </div>
                    </a>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p>No podcasts found.</p>
                <?php endif; ?>
            </div>
        </div>
        <?php
        wp_reset_postdata();
        return ob_get_clean();
    }
}
add_shortcode( 'cmr_trending_podcast', 'cmr_trending_podcast_shortcode' );

if ( ! function_exists( 'cmr_trending_topview_shortcode' ) ) {
    function cmr_trending_topview_shortcode( $atts ) {
        $atts = shortcode_atts( array(
            'posts_per_page' => 5,
        ), $atts, 'cmr_trending_topview' );

        $query_args = array(
            'post_type'      => 'cmr_media',
            'posts_per_page' => intval( $atts['posts_per_page'] ),
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC',
            'meta_query'     => array(
                'relation' => 'OR',
                array(
                    'key'     => '_cmr_show_in_topview',
                    'value'   => '1',
                    'compare' => '=',
                ),
                array(
                    'relation' => 'AND',
                    array(
                        'key'     => '_cmr_media_type',
                        'value'   => 'TOP VIEW',
                        'compare' => 'LIKE',
                    ),
                    array(
                        'key'     => '_cmr_show_in_topview',
                        'compare' => 'NOT EXISTS',
                    ),
                ),
            ),
        );

        $topview_query = new WP_Query( $query_args );
        $posts = $topview_query->posts;

        if ( empty( $posts ) ) {
            $fallback_args = array(
                'post_type'      => 'cmr_media',
                'posts_per_page' => intval( $atts['posts_per_page'] ),
                'post_status'    => 'publish',
                'orderby'        => 'date',
                'order'          => 'DESC',
            );
            $topview_query = new WP_Query( $fallback_args );
            $posts = $topview_query->posts;
        }

        ob_start();
        ?>
        <style>
            .cmr-topview-section {
                padding: 60px 20px 120px 20px;
                font-family: inherit;
                scroll-margin-top: 110px;
            }
            .cmr-topview-container {
                max-width: 1280px;
                margin: 0 auto;
            }
            .cmr-topview-title {
                font-size: 42px;
                font-weight: 600;
                color: #000;
                margin: 0 0 30px 0;
                letter-spacing: 0 !important;
            }
            .cmr-topview-layout {
                display: grid;
                grid-template-columns: 2fr 1fr;
                gap: 30px;
            }
            
            /* Left Column: Featured Video */
            .cmr-topview-featured {
                display: flex;
                flex-direction: column;
                text-decoration: none;
                color: #000;
            }
            .cmr-topview-feat-img {
                position: relative;
                width: 100%;
                aspect-ratio: 16/9;
                background: #ccc;
                margin-bottom: 20px;
                overflow: hidden;
            }
            .cmr-topview-feat-img img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                display: block;
            }
            .cmr-topview-feat-badge {
                position: absolute;
                top: 15px;
                left: 15px;
                background: #fff;
                color: #111;
                font-size: 12px;
                font-weight: 600;
                padding: 5px 10px;
                border-radius: 4px;
                z-index: 2;
            }
            .cmr-topview-feat-play {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                width: 70px;
                height: 70px;
                background: rgba(255, 255, 255, 0.25);
                backdrop-filter: blur(5px);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 2;
                transition: transform 0.2s;
            }
            .cmr-topview-featured:hover .cmr-topview-feat-play {
                transform: translate(-50%, -50%) scale(1.05);
            }
            .cmr-topview-feat-play svg {
                width: 24px;
                height: 24px;
                fill: #fff;
                margin-left: 4px;
            }
            .cmr-topview-feat-meta {
                font-size: 12px;
                font-weight: 600;
                color: #888;
                margin-bottom: 15px;
                letter-spacing: 0.5px;
                text-transform: uppercase;
            }
            .cmr-topview-feat-meta .type-topview {
                color: #4820B0;
            }
            .cmr-topview-feat-title {
                font-size: 26px;
                font-weight: 600;
                color: #000;
                margin: 0;
                line-height: 1.3;
                letter-spacing: 0 !important;
                max-width: 600px;
            }

            /* Right Column: Playlist */
            .cmr-topview-playlist {
                display: flex;
                flex-direction: column;
                gap: 15px;
                max-height: 550px;
                overflow-y: auto;
                padding-right: 15px;
            }
            /* Custom Scrollbar */
            .cmr-topview-playlist::-webkit-scrollbar {
                width: 6px;
            }
            .cmr-topview-playlist::-webkit-scrollbar-track {
                background: #e0e0e0;
                border-radius: 3px;
            }
            .cmr-topview-playlist::-webkit-scrollbar-thumb {
                background: #6f42c1;
                border-radius: 3px;
            }
            
            .cmr-topview-list-item {
                display: flex;
                gap: 15px;
                text-decoration: none;
                color: #000;
                padding: 10px;
                transition: background 0.2s;
            }
            .cmr-topview-list-item:hover {
                background: #f0f0f0;
            }
            .cmr-topview-list-item.active {
                border: 1px solid #6f42c1;
                background: #fff;
            }
            .cmr-topview-list-img {
                position: relative;
                width: 140px;
                flex-shrink: 0;
                aspect-ratio: 16/9;
                background: #ccc;
                overflow: hidden;
            }
            .cmr-topview-list-img img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                display: block;
            }
            .cmr-topview-list-play {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                width: 30px;
                height: 30px;
                border: 1.5px solid #fff;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 2;
                background: rgba(0,0,0,0.3);
            }
            .cmr-topview-list-play svg {
                width: 12px;
                height: 12px;
                fill: #fff;
                margin-left: 2px;
            }
            .cmr-topview-list-content {
                flex: 1;
                display: flex;
                flex-direction: column;
                justify-content: center;
            }
            .cmr-topview-list-date {
                font-size: 10px;
                color: #666;
                margin-bottom: 5px;
                font-weight: 600;
            }
            .cmr-topview-list-title {
                font-size: 15px;
                font-weight: 600;
                color: #111;
                margin: 0;
                line-height: 1.3;
                letter-spacing: 0 !important;
                display: -webkit-box;
                -webkit-line-clamp: 3;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }

            @media (max-width: 991px) {
                .cmr-topview-layout {
                    grid-template-columns: 1fr;
                }
                .cmr-topview-playlist {
                    max-height: 400px;
                }
            }
            @media (max-width: 768px) {
                .cmr-topview-title {
                    font-size: 32px;
                }
                .cmr-topview-list-img {
                    width: 120px;
                }
                .cmr-topview-feat-title {
                    font-size: 20px;
                }
            }
        </style>

        <div class="cmr-topview-section" id="top-view">
            <div class="cmr-topview-container">
                <h2 class="cmr-topview-title">Trending Top View</h2>
                
                <div class="cmr-topview-layout">
                    <?php 
                    if ( ! empty($posts) ) : 
                        // Featured Post (first item)
                        $feat_post = $posts[0];
                        $feat_thumb = get_the_post_thumbnail_url( $feat_post->ID, 'large' );
                        if ( ! $feat_thumb ) $feat_thumb = 'https://cmrindia.com/wp-content/uploads/2026/06/Why-Chipsets-are-the-New-Frontier-in-Smartphones1.jpg';
                        
                        $feat_cat = 'AUTOMOTIVE';
                        $terms = get_the_terms( $feat_post->ID, 'category' );
                        if ( $terms && ! is_wp_error( $terms ) ) {
                            $feat_cat = $terms[0]->name;
                        }
                        
                        $feat_date = get_the_date('d M Y', $feat_post);
                        $feat_type = get_post_meta( $feat_post->ID, '_cmr_media_type', true ) ?: 'TOP VIEW';
                        $feat_dur = get_post_meta( $feat_post->ID, '_cmr_media_duration', true ) ?: '08:20 MINS';
                        $feat_link = esc_url(get_permalink($feat_post->ID));
                    ?>
                    
                    <!-- Left: Featured -->
                    <a href="<?php echo esc_url($feat_link); ?>" class="cmr-topview-featured js-feat-link" target="_blank" rel="noopener noreferrer">
                        <div class="cmr-topview-feat-img">
                            <img class="js-feat-img" src="<?php echo esc_url($feat_thumb); ?>" alt="<?php echo esc_attr(get_the_title($feat_post)); ?>">
                            <div class="cmr-topview-feat-badge js-feat-dur"><?php echo esc_html($feat_dur); ?></div>
                            <div class="cmr-topview-feat-play">
                                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                            </div>
                        </div>
                        <div class="cmr-topview-feat-meta">
                            <span class="type-topview js-feat-type"><?php echo esc_html($feat_type); ?></span> &bull; 
                            <span class="js-feat-cat"><?php echo esc_html(strtoupper($feat_cat)); ?></span> &bull; 
                            <span class="js-feat-date"><?php echo esc_html(strtoupper($feat_date)); ?></span> &bull; 100 VIEW
                        </div>
                        <h3 class="cmr-topview-feat-title js-feat-title"><?php echo esc_html(get_the_title($feat_post)); ?></h3>
                    </a>

                    <!-- Right: Playlist -->
                    <div class="cmr-topview-playlist">
                        <?php 
                        foreach ( $posts as $index => $post_obj ) : 
                            $thumb = get_the_post_thumbnail_url( $post_obj->ID, 'medium' );
                            if ( ! $thumb ) $thumb = 'https://cmrindia.com/wp-content/uploads/2026/06/Why-Chipsets-are-the-New-Frontier-in-Smartphones1.jpg';
                            
                            $date = get_the_date('d M Y', $post_obj);
                            $link = esc_url(get_permalink($post_obj->ID));
                            
                            // Get metadata for JS
                            $full_thumb = get_the_post_thumbnail_url( $post_obj->ID, 'large' );
                            if ( ! $full_thumb ) $full_thumb = 'https://cmrindia.com/wp-content/uploads/2026/06/Why-Chipsets-are-the-New-Frontier-in-Smartphones1.jpg';
                            $dur = get_post_meta( $post_obj->ID, '_cmr_media_duration', true ) ?: '08:20 MINS';
                            $type = get_post_meta( $post_obj->ID, '_cmr_media_type', true ) ?: 'TOP VIEW';
                            $cat = 'AUTOMOTIVE';
                            $terms = get_the_terms( $post_obj->ID, 'category' );
                            if ( $terms && ! is_wp_error( $terms ) ) {
                                $cat = $terms[0]->name;
                            }
                            $cat = strtoupper($cat);
                            $date_upper = strtoupper($date);
                            $title = esc_attr(get_the_title($post_obj));
                            
                            $active_class = ($index === 0) ? 'active' : '';
                        ?>
                        <a href="<?php echo esc_url($link); ?>" class="cmr-topview-list-item <?php echo $active_class; ?>" data-thumb="<?php echo esc_url($full_thumb); ?>" data-dur="<?php echo esc_attr($dur); ?>" data-type="<?php echo esc_attr($type); ?>" data-cat="<?php echo esc_attr($cat); ?>" data-date="<?php echo esc_attr($date_upper); ?>" data-title="<?php echo $title; ?>" data-link="<?php echo esc_url($link); ?>">
                            <div class="cmr-topview-list-img">
                                <img src="<?php echo esc_url($thumb); ?>" alt="<?php echo esc_attr(get_the_title($post_obj)); ?>">
                                <div class="cmr-topview-list-play">
                                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                                </div>
                            </div>
                            <div class="cmr-topview-list-content">
                                <div class="cmr-topview-list-date">&bull; <?php echo esc_html(strtoupper($date)); ?></div>
                                <h4 class="cmr-topview-list-title"><?php echo esc_html(get_the_title($post_obj)); ?></h4>
                            </div>
                        </a>
                        <?php endforeach; ?>
                    </div>
                    <?php else : ?>
                        <p>No posts found.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var topviewContainers = document.querySelectorAll('.cmr-topview-layout');
                topviewContainers.forEach(function(container) {
                    var listItems = container.querySelectorAll('.cmr-topview-list-item');
                    var featLink = container.querySelector('.js-feat-link');
                    var featImg = container.querySelector('.js-feat-img');
                    var featDur = container.querySelector('.js-feat-dur');
                    var featType = container.querySelector('.js-feat-type');
                    var featCat = container.querySelector('.js-feat-cat');
                    var featDate = container.querySelector('.js-feat-date');
                    var featTitle = container.querySelector('.js-feat-title');
                    
                    if(!featLink) return;
                    
                    listItems.forEach(function(item) {
                        item.addEventListener('click', function(e) {
                            e.preventDefault();
                            
                            // Update active state
                            listItems.forEach(function(el) { el.classList.remove('active'); });
                            this.classList.add('active');
                            
                            // Update featured area
                            featLink.href = this.getAttribute('data-link');
                            featImg.src = this.getAttribute('data-thumb');
                            featDur.textContent = this.getAttribute('data-dur');
                            featType.textContent = this.getAttribute('data-type');
                            featCat.textContent = this.getAttribute('data-cat');
                            featDate.textContent = this.getAttribute('data-date');
                            featTitle.textContent = this.getAttribute('data-title');
                        });
                    });
                });
            });
        </script>
        <?php
        wp_reset_postdata();
        return ob_get_clean();
    }
}
add_shortcode( 'cmr_trending_topview', 'cmr_trending_topview_shortcode' );

if ( ! function_exists( 'cmr_cta_banner_shortcode' ) ) {
    function cmr_cta_banner_shortcode( $atts ) {
        $atts = shortcode_atts( array(
            'title'       => 'Need deeper insights?',
            'text'        => 'Talk to our analysts for tailored recommendations across your sector.',
            'button_text' => 'Connect to Analyst',
            'button_link' => '#elementor-action%3Aaction%3Dpopup%3Aopen%26settings%3DeyJpZCI6Ijc2MzciLCJ0b2dnbGUiOmZhbHNlfQ%3D%3D',
            'popup_id'    => '7637',
        ), $atts, 'cmr_cta_banner' );

        ob_start();
        ?>
        <style>
            .cmr-cta-banner-wrapper {
                padding: 40px 20px;
                font-family: inherit;
            }
            .cmr-cta-banner {
                max-width: 1280px;
                margin: 0 auto;
                background-color: #4a25aa; /* Deep purple */
                border-radius: 4px;
                padding: 40px 50px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 40px;
                color: #fff;
            }
            .cmr-cta-title {
                font-size: 42px;
                font-weight: 600;
                margin: 0;
                line-height: 1.2;
                letter-spacing: -1px;
                white-space: nowrap;
                color: #fff !important;
            }
            .cmr-cta-text {
                font-size: 16px;
                line-height: 1.5;
                margin: 0;
                max-width: 350px;
                color: rgba(255, 255, 255, 0.9);
            }
            .cmr-cta-btn {
                display: inline-flex;
                align-items: center;
                gap: 10px;
                background-color: #fff;
                color: #4a25aa;
                font-size: 15px;
                font-weight: 600;
                text-decoration: none;
                padding: 15px 30px;
                border-radius: 50px;
                transition: all 0.3s ease;
                white-space: nowrap;
                flex-shrink: 0;
                cursor: pointer;
            }
            .cmr-cta-btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            }
            .cmr-cta-btn svg {
                width: 14px;
                height: 14px;
                stroke: currentColor;
                stroke-width: 2;
                fill: none;
            }
            
            @media (max-width: 1024px) {
                .cmr-cta-banner {
                    flex-direction: column;
                    text-align: center;
                    gap: 30px;
                    padding: 40px 30px;
                }
                .cmr-cta-text {
                    max-width: 100%;
                }
                .cmr-cta-title {
                    white-space: normal;
                    font-size: 36px;
                }
            }
        </style>

        <div class="cmr-cta-banner-wrapper">
            <div class="cmr-cta-banner">
                <h3 class="cmr-cta-title"><?php echo esc_html( $atts['title'] ); ?></h3>
                
                <p class="cmr-cta-text"><?php echo esc_html( $atts['text'] ); ?></p>
                
                <a href="javascript:void(0)" class="cmr-cta-btn open-popup" data-popup-id="<?php echo esc_attr( $atts['popup_id'] ); ?>" onclick="if(window.cmrOpenPopup){window.cmrOpenPopup('<?php echo esc_js( $atts['popup_id'] ); ?>');event.preventDefault();event.stopPropagation();}">
                    <?php echo esc_html( $atts['button_text'] ); ?>
                    <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="7" y1="17" x2="17" y2="7"></line>
                        <polyline points="7 7 17 7 17 17"></polyline>
                    </svg>
                </a>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}
add_shortcode( 'cmr_cta_banner', 'cmr_cta_banner_shortcode' );

// AJAX Handler for Browse Recently Updated
if ( ! function_exists( 'cmr_filter_recently_updated_ajax' ) ) {
    function cmr_filter_recently_updated_ajax() {
        $paged = isset( $_POST['paged'] ) ? intval( $_POST['paged'] ) : 1;
        $search = isset( $_POST['search'] ) ? sanitize_text_field( $_POST['search'] ) : '';
        $type = isset( $_POST['type'] ) ? sanitize_text_field( $_POST['type'] ) : 'all';
        $posts_per_page = 9;

        $query_args = array(
            'post_type'      => 'cmr_media',
            'posts_per_page' => $posts_per_page,
            'paged'          => $paged,
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC',
        );

        if ( ! empty( $search ) ) {
            $query_args['s'] = $search;
        }

        if ( $type !== 'all' ) {
            $is_podcast = ( strtolower($type) === 'podcast' );
            $checkbox_key = $is_podcast ? '_cmr_show_in_podcast' : '_cmr_show_in_topview';
            $format_val = $is_podcast ? 'PODCAST' : 'TOP VIEW';

            $query_args['meta_query'] = array(
                'relation' => 'OR',
                array(
                    'key'     => $checkbox_key,
                    'value'   => '1',
                    'compare' => '=',
                ),
                array(
                    'relation' => 'AND',
                    array(
                        'key'     => '_cmr_media_type',
                        'value'   => $format_val,
                        'compare' => 'LIKE',
                    ),
                    array(
                        'key'     => $checkbox_key,
                        'compare' => 'NOT EXISTS',
                    ),
                ),
            );
        }

        $query = new WP_Query( $query_args );

        ob_start();
        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();
                $post_obj = get_post();
                
                $thumbnail_url = get_the_post_thumbnail_url( $post_obj->ID, 'large' );
                if ( ! $thumbnail_url ) {
                    $thumbnail_url = 'https://cmrindia.com/wp-content/uploads/2026/06/Why-Chipsets-are-the-New-Frontier-in-Smartphones1.jpg';
                }
                
                $category_name = 'AUTOMOTIVE';
                $terms = get_the_terms( $post_obj->ID, 'category' );
                if ( $terms && ! is_wp_error( $terms ) ) {
                    $category_name = $terms[0]->name;
                }
                
                $post_date = get_the_date('d M Y', $post_obj);
                $media_type = get_post_meta( $post_obj->ID, '_cmr_media_type', true );
                $media_duration = get_post_meta( $post_obj->ID, '_cmr_media_duration', true );
                $media_url = get_post_meta( $post_obj->ID, '_cmr_media_url', true );
                
                $mtype = $media_type ? $media_type : 'PODCAST';
                $is_podcast = (strtoupper($mtype) === 'PODCAST');
                $type_class = $is_podcast ? 'type-podcast' : 'type-topview';
                
                $duration = $media_duration ? $media_duration : '05:00 MINS';
                $link = esc_url(get_permalink($post_obj->ID));
                ?>
                <a href="<?php echo $link; ?>" class="cmr-browse-card" target="_blank" rel="noopener noreferrer">
                    <div class="cmr-browse-img-wrap">
                        <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
                        <div class="cmr-browse-badge"><?php echo esc_html($duration); ?></div>
                        <div class="cmr-browse-play-btn">
                            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                        </div>
                    </div>
                    
                    <div class="cmr-browse-meta">
                        <span class="<?php echo esc_attr($type_class); ?>"><?php echo esc_html($mtype); ?></span> &bull; 
                        <?php echo esc_html(strtoupper($category_name)); ?> &bull; 
                        <?php echo esc_html(strtoupper($post_date)); ?>
                    </div>
                    
                    <h3 class="cmr-browse-card-title"><?php echo esc_html(get_the_title()); ?></h3>
                </a>
                <?php
            }
        } else {
            if ( $paged == 1 ) {
                echo '<p style="grid-column: 1/-1; text-align: center;">No media found.</p>';
            }
        }
        $html = ob_get_clean();

        wp_send_json_success( array(
            'html' => $html,
            'max_pages' => $query->max_num_pages,
            'paged' => $paged
        ) );
    }
}
add_action( 'wp_ajax_cmr_filter_recently_updated', 'cmr_filter_recently_updated_ajax' );
add_action( 'wp_ajax_nopriv_cmr_filter_recently_updated', 'cmr_filter_recently_updated_ajax' );

if ( ! function_exists( 'cmr_browse_recently_updated_shortcode' ) ) {
    function cmr_browse_recently_updated_shortcode( $atts ) {
        ob_start();
        ?>
        <style>
            .cmr-browse-section {
                padding: 60px 20px;
                background-color: #fff;
                font-family: inherit;
                max-width: 1280px;
                margin: 0 auto;
            }
            .cmr-browse-title {
                font-size: 42px;
                font-weight: 600;
                color: #000;
                margin: 0 0 30px 0;
                letter-spacing: 0 !important;
            }
            .cmr-browse-controls {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 40px;
                flex-wrap: wrap;
                gap: 20px;
            }
            .cmr-browse-filters {
                display: flex;
                gap: 15px;
            }
            .cmr-filter-btn {
                background: #fff;
                border: 1px solid #e0e0e0;
                color: #333;
                padding: 8px 24px;
                border-radius: 30px;
                font-size: 14px;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.2s;
            }
            .cmr-filter-btn:hover {
                border-color: #999;
            }
            .cmr-filter-btn.active {
                border-color: #111;
                background: #111;
                color: #fff;
            }
            .cmr-browse-search {
                position: relative;
                width: 300px;
                max-width: 100%;
            }
            .cmr-browse-search input {
                width: 100%;
                padding: 10px 45px 10px 20px;
                border: 1px solid #e0e0e0;
                border-radius: 30px;
                font-size: 14px;
                outline: none;
                transition: border-color 0.2s;
            }
            .cmr-browse-search input:focus {
                border-color: #999;
            }
            .cmr-browse-search button {
                position: absolute;
                right: 5px;
                top: 50%;
                transform: translateY(-50%);
                width: 32px;
                height: 32px;
                background: #6f42c1;
                border: none;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
            }
            .cmr-browse-search button svg {
                width: 14px;
                height: 14px;
                stroke: #fff;
                stroke-width: 2;
                fill: none;
            }
            
            .cmr-browse-grid {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 30px;
                margin-bottom: 50px;
            }
            .cmr-browse-card {
                display: flex;
                flex-direction: column;
                text-decoration: none;
                color: #000;
            }
            .cmr-browse-img-wrap {
                position: relative;
                width: 100%;
                aspect-ratio: 16/10;
                overflow: hidden;
                margin-bottom: 20px;
                background: #f5f5f5;
            }
            .cmr-browse-img-wrap img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                display: block;
            }
            .cmr-browse-badge {
                position: absolute;
                top: 15px;
                left: 15px;
                background: #fff;
                color: #111;
                font-size: 11px;
                font-weight: 600;
                padding: 4px 8px;
                border-radius: 4px;
                z-index: 2;
            }
            .cmr-browse-play-btn {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                width: 50px;
                height: 50px;
                background: transparent;
                border: 2px solid #fff;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 2;
            }
            .cmr-browse-play-btn svg {
                width: 20px;
                height: 20px;
                fill: #fff;
                margin-left: 3px;
            }
            .cmr-browse-meta {
                font-size: 11px;
                font-weight: 600;
                color: #888;
                margin-bottom: 12px;
                letter-spacing: 0.5px;
                text-transform: uppercase;
            }
            .cmr-browse-meta .type-podcast {
                color: #4820B0;
            }
            .cmr-browse-meta .type-topview {
                color: #4820B0;
            }
            .cmr-browse-card-title {
                font-size: 22px;
                font-weight: 600;
                color: #000;
                margin: 0;
                line-height: 1.3;
                letter-spacing: 0 !important;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }
            
            .cmr-browse-loadmore-wrap {
                text-align: center;
            }
            .cmr-loadmore-btn {
                background: #fff;
                border: 1px solid #ccc;
                color: #111;
                padding: 12px 35px;
                border-radius: 30px;
                font-size: 15px;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.2s;
            }
            .cmr-loadmore-btn:hover {
                border-color: #111;
                background: #f9f9f9;
            }
            .cmr-loadmore-btn:disabled {
                opacity: 0.5;
                cursor: not-allowed;
            }
            
            .cmr-browse-loading {
                text-align: center;
                padding: 40px;
                display: none;
                grid-column: 1/-1;
            }

            @media (max-width: 1024px) {
                .cmr-browse-grid {
                    grid-template-columns: repeat(2, 1fr);
                }
            }
            @media (max-width: 768px) {
                .cmr-browse-grid {
                    grid-template-columns: 1fr;
                }
                .cmr-browse-title {
                    font-size: 32px;
                }
                .cmr-browse-controls {
                    flex-direction: column;
                    align-items: stretch;
                }
                .cmr-browse-search {
                    width: 100%;
                }
            }
        </style>

        <div class="cmr-browse-section">
            <h2 class="cmr-browse-title">Browse by Recently updated</h2>
            
            <div class="cmr-browse-controls">
                <div class="cmr-browse-filters">
                    <button class="cmr-filter-btn active" data-type="all">All</button>
                    <button class="cmr-filter-btn" data-type="podcast">Podcast</button>
                    <button class="cmr-filter-btn" data-type="top view">Top View</button>
                </div>
                <div class="cmr-browse-search">
                    <input type="text" id="cmr-browse-search-input" placeholder="Search by name">
                    <button id="cmr-browse-search-btn" aria-label="Search">
                        <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    </button>
                </div>
            </div>
            
            <div class="cmr-browse-grid" id="cmr-browse-grid">
                <!-- Content will be loaded via AJAX -->
            </div>
            
            <div class="cmr-browse-loading" id="cmr-browse-loading">Loading...</div>
            
            <div class="cmr-browse-loadmore-wrap">
                <button class="cmr-loadmore-btn" id="cmr-loadmore-btn">Load More</button>
            </div>
        </div>
        
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var grid = document.getElementById('cmr-browse-grid');
                var loadMoreBtn = document.getElementById('cmr-loadmore-btn');
                var searchInput = document.getElementById('cmr-browse-search-input');
                var searchBtn = document.getElementById('cmr-browse-search-btn');
                var filterBtns = document.querySelectorAll('.cmr-filter-btn');
                var loadingEl = document.getElementById('cmr-browse-loading');
                
                var currentPage = 1;
                var currentType = 'all';
                var currentSearch = '';
                var maxPages = 1;
                var ajaxUrl = '<?php echo admin_url('admin-ajax.php'); ?>';
                
                function fetchMedia(reset = false) {
                    if (reset) {
                        currentPage = 1;
                        grid.innerHTML = '';
                    }
                    
                    loadingEl.style.display = 'block';
                    loadMoreBtn.style.display = 'none';
                    
                    var formData = new FormData();
                    formData.append('action', 'cmr_filter_recently_updated');
                    formData.append('paged', currentPage);
                    formData.append('type', currentType);
                    formData.append('search', currentSearch);
                    
                    fetch(ajaxUrl, {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        loadingEl.style.display = 'none';
                        if (data.success) {
                            if (reset) {
                                grid.innerHTML = data.data.html;
                            } else {
                                grid.insertAdjacentHTML('beforeend', data.data.html);
                            }
                            maxPages = data.data.max_pages;
                            
                            if (currentPage < maxPages) {
                                loadMoreBtn.style.display = 'inline-block';
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        loadingEl.style.display = 'none';
                    });
                }
                
                // Initial load
                fetchMedia(true);
                
                // Load More click
                loadMoreBtn.addEventListener('click', function() {
                    currentPage++;
                    fetchMedia();
                });
                
                // Filter click
                filterBtns.forEach(function(btn) {
                    btn.addEventListener('click', function() {
                        filterBtns.forEach(b => b.classList.remove('active'));
                        this.classList.add('active');
                        currentType = this.getAttribute('data-type');
                        fetchMedia(true);
                    });
                });
                
                // Search
                var searchTimeout;
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    currentSearch = this.value;
                    searchTimeout = setTimeout(function() {
                        fetchMedia(true);
                    }, 500);
                });
                
                searchBtn.addEventListener('click', function() {
                    currentSearch = searchInput.value;
                    fetchMedia(true);
                });
            });
        </script>
        <?php
        return ob_get_clean();
    }
}
add_shortcode( 'cmr_browse_recently_updated', 'cmr_browse_recently_updated_shortcode' );

add_action('wp_footer', 'cmr_live_hash_scroll_handler', 99);
function cmr_live_hash_scroll_handler() {
    $uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
    if (strpos($uri, 'cmr-live') === false && !is_page('cmr-live')) {
        return;
    }
    ?>
    <script>
    (function() {
        function scrollToTargetHash() {
            if (!window.location.hash) return;
            var targetId = window.location.hash.substring(1);
            if (targetId !== 'podcasts' && targetId !== 'top-view') return;
            var targetEl = document.getElementById(targetId);
            if (targetEl) {
                var headerOffset = 110;
                var elPos = targetEl.getBoundingClientRect().top + window.pageYOffset;
                window.scrollTo({
                    top: Math.max(0, elPos - headerOffset),
                    behavior: 'smooth'
                });
            }
        }

        if (document.readyState === 'complete' || document.readyState === 'interactive') {
            setTimeout(scrollToTargetHash, 100);
        } else {
            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(scrollToTargetHash, 100);
            });
        }
        window.addEventListener('load', function() {
            setTimeout(scrollToTargetHash, 200);
            setTimeout(scrollToTargetHash, 600);
        });
        window.addEventListener('hashchange', function() {
            setTimeout(scrollToTargetHash, 50);
        });
    })();
    </script>
    <?php
}

