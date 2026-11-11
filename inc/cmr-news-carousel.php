<?php
/**
 * CMR News Carousel Shortcode
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'cmr_news_carousel', 'cmr_render_news_carousel_shortcode' );

function cmr_render_news_carousel_shortcode( $atts ) {
    if ( ! wp_style_is( 'swiper-bundle-min-style', 'enqueued' ) ) {
        wp_enqueue_style( 'swiper-bundle-min-style', get_template_directory_uri() . '/assets/css/swiper-bundle.min.css', array(), '7.0.8' );
    }
    if ( ! wp_script_is( 'swiper-bundle', 'enqueued' ) ) {
        wp_enqueue_script( 'swiper-bundle', get_template_directory_uri() . '/assets/js/swiper-bundle.min.js', array('jquery'), '7.0.8', true );
    }
    $mc_css_path = get_template_directory() . '/assets/css/cmr-media-coverage.css';
    $mc_css_ver  = file_exists( $mc_css_path ) ? filemtime( $mc_css_path ) : '1.0.0';
    wp_enqueue_style( 'cmr-media-coverage-style', get_template_directory_uri() . '/assets/css/cmr-media-coverage.css', array(), $mc_css_ver );

    $args = array(
        'post_type'      => 'cmr_news',
        'posts_per_page' => 5,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'post_status'    => 'publish',
        'tax_query'      => array(
            array(
                'taxonomy' => 'cmr_news_category',
                'field'    => 'slug',
                'terms'    => 'cmr-in-news',
            ),
        ),
    );
    $query = new WP_Query( $args );

    ob_start();
    ?>
    <style>
    .cmr-nc-wrapper {
        width: 100%;
        max-width: 100%;
        margin: 0 auto;
        padding: 0;
        font-family: 'Instrument Sans', sans-serif;
        box-sizing: border-box;
    }

    .cmr-nc-header {
        text-align: center;
        margin-bottom: 35px;
        padding: 0 16px;
    }

    .cmr-nc-title {
        font-size: 42px;
        font-weight: 700;
        letter-spacing: -1px;
        margin: 0 0 12px 0;
        color: #111111;
        line-height: 1.2;
    }

    .cmr-nc-subtitle {
        font-size: 16px;
        color: #555555;
        max-width: 620px;
        margin: 0 auto;
        line-height: 1.5;
    }

    .cmr-nc-carousel {
        width: 100% !important;
        overflow: hidden !important;
        padding: 10px 0 20px 0 !important;
        position: relative !important;
    }

    .cmr-nc-carousel .swiper-wrapper {
        align-items: stretch;
    }

    /* Prevent CLS before Swiper JS initializes */
    .cmr-nc-carousel:not(.swiper-initialized) .swiper-wrapper {
        display: flex;
        justify-content: center;
    }

    .cmr-nc-carousel:not(.swiper-initialized) .swiper-slide {
        flex: 0 0 84.7%;
        width: 84.7%;
        max-width: 84.7%;
        opacity: 1;
        transform: scale(1);
    }

    .cmr-nc-carousel:not(.swiper-initialized) .swiper-slide:not(:first-child) {
        display: none;
    }

    .cmr-nc-carousel .swiper-slide {
        transition: opacity 0.4s ease, transform 0.4s ease;
        opacity: 0.55;
        transform: scale(0.96);
        border-radius: 0 !important;
        box-sizing: border-box;
        height: auto;
    }

    .cmr-nc-carousel .swiper-slide-active {
        opacity: 1;
        transform: scale(1);
    }

    .cmr-nc-card {
        position: relative;
        height: 740px !important;
        border-radius: 0 !important;
        overflow: hidden;
        background-color: #0b1020;
        background-size: cover;
        background-position: center;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        box-sizing: border-box;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    }

    .cmr-nc-card .cmr-nc-bg-img {
        position: absolute;
        inset: 0;
        width: 100% !important;
        height: 100% !important;
        object-fit: cover;
        object-position: center;
        z-index: 0;
        pointer-events: none;
        border-radius: 0 !important;
    }

    .cmr-nc-overlay {
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: linear-gradient(180deg, rgba(0, 0, 0, 0.15) 0%, rgba(0, 0, 0, 0.25) 30%, rgba(10, 15, 30, 0.72) 62%, rgba(10, 15, 30, 0.95) 100%);
        z-index: 1;
        pointer-events: none;
        border-radius: 0 !important;
    }

    /* Top Bar: Featured Badge on top-left */
    .cmr-nc-top-bar {
        position: relative;
        z-index: 3;
        display: flex;
        justify-content: flex-start;
        align-items: center;
        padding: 40px 60px 0 60px;
        width: 100%;
        box-sizing: border-box;
    }

    /* Featured Badge */
    .cmr-nc-badge {
        background: #ffffff;
        color: #5842c3;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        height: 30px !important;
        padding: 0 12px !important;
        box-sizing: border-box !important;
        border-radius: 0 !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 5px;
        line-height: 1;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .cmr-nc-badge svg {
        color: #5842c3;
        fill: #5842c3;
        display: inline-block;
        vertical-align: -1px;
        margin-right: 0;
        width: 10px;
        height: 10px;
    }

    /* Bottom Card Content */
    .cmr-nc-card-content {
        position: relative;
        z-index: 3;
        padding: 30px 60px 60px 60px !important;
        color: #ffffff;
        display: flex;
        flex-direction: column;
        box-sizing: border-box;
    }

    /* Logo inside bottom content */
    .cmr-nc-logo-wrap {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
        max-width: 100%;
    }

    .cmr-nc-logo {
        background: #ffffff !important;
        padding: 4px !important;
        box-sizing: content-box !important;
        height: 32px !important;
        max-height: 32px !important;
        width: auto !important;
        max-width: none !important;
        object-fit: contain !important;
        object-position: center !important;
        border-radius: 5px !important;
        display: inline-block !important;
    }

    .cmr-nc-logo-bbc {
        background: #bb1919;
        color: #ffffff;
        font-weight: 800;
        font-size: 13px;
        padding: 5px 8px;
        border-radius: 4px;
        display: inline-flex;
        flex-direction: column;
        line-height: 1.1;
        letter-spacing: 0.5px;
        text-align: center;
        box-shadow: 0 2px 6px rgba(0,0,0,0.3);
    }

    .cmr-nc-logo-fallback {
        background: rgba(0, 0, 0, 0.55);
        color: #ffffff;
        font-size: 14px;
        font-weight: 700;
        padding: 6px 12px;
        border-radius: 4px;
        backdrop-filter: blur(4px);
    }

    .cmr-nc-meta-info {
        display: flex;
        align-items: center;
        font-size: 14px;
        color: #e2e8f0;
        margin-bottom: 12px;
        line-height: 1.4;
    }

    .cmr-nc-meta-left {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        font-weight: 500;
    }

    .cmr-nc-meta-sep {
        opacity: 0.6;
        margin: 0 2px;
    }

    .cmr-nc-publisher {
        font-weight: 600;
        color: #ffffff;
    }

    .cmr-nc-date {
        color: #cbd5e1;
    }

    .cmr-nc-time {
        font-size: 14px;
        color: #cbd5e1;
        font-weight: 500;
        white-space: nowrap;
    }

    .cmr-nc-post-title {
        font-family: 'Instrument Sans', sans-serif;
        font-size: 28px;
        font-weight: 700;
        line-height: 1.3;
        color: #ffffff;
        margin: 0 0 12px 0;
        letter-spacing: -0.4px;
        max-width: 950px;
        transition: color 0.3s ease !important;
    }

    .cmr-nc-post-title a {
        color: #ffffff !important;
        text-decoration: none !important;
        transition: color 0.3s ease !important;
    }

    .cmr-nc-card:hover .cmr-nc-post-title,
    .cmr-nc-card:hover .cmr-nc-post-title a,
    .cmr-nc-post-title:hover,
    .cmr-nc-post-title a:hover,
    .cmr-nc-post-title:hover a {
        color: #00D2B9 !important;
    }

    .cmr-nc-excerpt {
        font-family: 'Instrument Sans', sans-serif;
        font-size: 15px;
        line-height: 1.55;
        color: rgba(255, 255, 255, 0.9);
        margin: 0 0 22px 0;
        max-width: 850px;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .cmr-nc-read-btn {
        display: inline-flex !important;
        align-items: center !important;
        gap: 8px !important;
        width: fit-content !important;
        max-width: max-content !important;
        align-self: flex-start !important;
        color: #ffffff !important;
        font-size: 16px !important;
        font-weight: 700 !important;
        text-decoration: none !important;
        border-bottom: 2px solid #ffffff !important;
        padding-bottom: 3px !important;
        transition: opacity 0.2s ease !important;
        line-height: 1.2 !important;
    }

    .cmr-nc-read-btn:hover {
        opacity: 0.85 !important;
    }

    /* Pagination: Horizontal Rounded Dashes with Fill Loader (Matching Hero Banner) */
    .cmr-nc-carousel .swiper-pagination {
        position: static !important;
        margin-top: 24px !important;
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;
        gap: 12px !important;
        width: 100% !important;
    }

    .cmr-nc-carousel .swiper-pagination-bullet,
    .cmr-nc-carousel .swiper-pagination-bullet.swiper-pagination-bullet-active,
    .cmr-nc-carousel .swiper-pagination-bullet-active {
        width: 55px !important;
        height: 6px !important;
        background: #ded8fa !important;
        border-radius: 10px !important;
        position: relative !important;
        overflow: hidden !important;
        cursor: pointer !important;
        display: inline-block !important;
        box-shadow: none !important;
        border: none !important;
        padding: 0 !important;
        margin: 0 !important;
        outline: none !important;
        opacity: 1 !important;
        vertical-align: middle !important;
    }

    .cmr-nc-carousel .swiper-pagination-bullet .dot-fill {
        position: absolute !important;
        top: 0 !important;
        left: 0 !important;
        height: 100% !important;
        width: 0%;
        background: #5842c3 !important;
        border-radius: 10px !important;
        display: block !important;
        pointer-events: none !important;
        z-index: 2 !important;
    }

    @media (max-width: 1024px) {
        .cmr-nc-card {
            height: 580px !important;
        }
        .cmr-nc-top-bar {
            padding: 30px 40px 0 40px !important;
        }
        .cmr-nc-card-content {
            padding: 24px 40px 40px 40px !important;
        }
        .cmr-nc-post-title {
            font-size: 24px !important;
        }
    }

    /* Mobile Edge-to-Edge and Card Peeking */
    @media (max-width: 768px) {
        .cmr-nc-wrapper {
            width: 100% !important;
            max-width: 100% !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
            margin: 0 !important;
            overflow: hidden !important;
        }

        .elementor-element:has(.cmr-nc-wrapper),
        .e-con:has(.cmr-nc-wrapper),
        .e-con-inner:has(.cmr-nc-wrapper),
        .elementor-widget:has(.cmr-nc-wrapper),
        .elementor-widget-container:has(.cmr-nc-wrapper) {
            padding-left: 0 !important;
            padding-right: 0 !important;
            margin-left: 0 !important;
            margin-right: 0 !important;
            --padding-left: 0px !important;
            --padding-right: 0px !important;
        }

        .cmr-nc-header {
            padding: 0 16px !important;
            margin-bottom: 22px !important;
        }

        .cmr-nc-title {
            font-size: 26px !important;
            line-height: 1.25 !important;
            letter-spacing: -0.5px !important;
            margin-bottom: 8px !important;
        }

        .cmr-nc-subtitle {
            font-size: 14px !important;
            line-height: 1.45 !important;
        }

        .cmr-nc-carousel {
            padding-bottom: 15px !important;
        }

        .cmr-nc-card {
            height: 480px !important;
            border-radius: 0 !important;
        }

        .cmr-nc-top-bar {
            padding: 18px 20px 0 20px !important;
        }

        .cmr-nc-card-content {
            padding: 20px 20px 24px 20px !important;
        }

        .cmr-nc-logo-wrap {
            margin-bottom: 12px !important;
        }

        .cmr-nc-post-title {
            font-size: 18px !important;
            line-height: 1.35 !important;
            margin-bottom: 8px !important;
        }

        .cmr-nc-excerpt {
            font-size: 13px !important;
            line-height: 1.45 !important;
            margin-bottom: 14px !important;
        }

        .cmr-nc-read-btn {
            font-size: 15px !important;
        }

        .cmr-nc-carousel .swiper-pagination {
            gap: 8px !important;
        }

        .cmr-nc-carousel .swiper-pagination-bullet,
        .cmr-nc-carousel .swiper-pagination-bullet.swiper-pagination-bullet-active {
            width: 44px !important;
            height: 5px !important;
        }
    }
    </style>

    <div class="cmr-nc-wrapper">
        <div class="cmr-nc-header">
            <h2 class="cmr-nc-title">CMR in the News</h2>
            <p class="cmr-nc-subtitle">Stay updated with our latest press releases, media coverage, and strategic market announcements.</p>
        </div>

        <?php if ( $query->have_posts() ) : ?>
        <div class="swiper cmr-nc-carousel">
            <div class="swiper-wrapper">
                <?php
                $slide_index = 0;
                while ( $query->have_posts() ) : $query->the_post(); 
                    $post_id = get_the_ID();
                    $thumb_id = get_post_thumbnail_id( $post_id );
                    $is_first_slide = ( $slide_index === 0 );
                    $bg_img_html = '';
                    if ( $thumb_id ) {
                        $img_attrs = array(
                            'class'    => 'cmr-nc-bg-img',
                            'sizes'    => '(max-width: 768px) 88vw, 85vw',
                            'alt'      => esc_attr( get_the_title() ),
                            'decoding' => $is_first_slide ? 'sync' : 'async',
                            'loading'  => $is_first_slide ? 'eager' : 'lazy',
                        );
                        if ( $is_first_slide ) {
                            $img_attrs['fetchpriority'] = 'high';
                        }
                        $bg_img_html = wp_get_attachment_image( $thumb_id, 'large', false, $img_attrs );
                    }
                    if ( empty( $bg_img_html ) ) {
                        $bg_image = get_the_post_thumbnail_url( $post_id, 'large' ) ?: quanto_get_placeholder_image_uri();
                        $bg_img_html = '<img src="' . esc_url( $bg_image ) . '" class="cmr-nc-bg-img" alt="' . esc_attr( get_the_title() ) . '" loading="' . ( $is_first_slide ? 'eager' : 'lazy' ) . '" decoding="' . ( $is_first_slide ? 'sync' : 'async' ) . '"' . ( $is_first_slide ? ' fetchpriority="high"' : '' ) . '>';
                    }
                    $slide_index++;
                    $publisher_name = get_post_meta( $post_id, '_cmr_news_publisher_name', true );
                    if ( ! $publisher_name ) {
                        $publisher_name = 'BBC News';
                    }
                    $logo_id = get_post_meta( $post_id, '_cmr_news_source_logo_id', true );
                    $logo_url = '';
                    if ( $logo_id ) {
                        if ( is_numeric( $logo_id ) ) {
                            $logo_url = wp_get_attachment_url( $logo_id );
                            if ( ! $logo_url ) {
                                $logo_url = wp_get_attachment_image_url( $logo_id, 'full' );
                            }
                        } else {
                            $logo_url = $logo_id;
                        }
                    }
                    
                    // Word count or custom reading time
                    $reading_time = get_post_meta( $post_id, '_cmr_news_reading_time', true );
                    if ( ! $reading_time ) {
                        $word_count = str_word_count( strip_tags( get_post_field( 'post_content', $post_id ) ) );
                        $reading_time = max( 1, ceil( $word_count / 200 ) );
                    }
                    
                    $document_id = get_post_meta( $post_id, '_cmr_news_document_id', true );
                    $ext_url = get_post_meta( $post_id, '_cmr_news_external_link', true );
                    if ( $document_id ) {
                        $link = wp_get_attachment_url( $document_id );
                        $target = '_blank';
                    } elseif ( $ext_url ) {
                        $link = $ext_url;
                        $target = '_blank';
                    } else {
                        $link = get_permalink( $post_id );
                        $target = '_self';
                    }

                    $is_featured = get_post_meta( $post_id, '_cmr_news_is_featured', true );
                    $show_featured = ( $query->current_post === 0 || $is_featured == '1' );
                ?>
                <div class="swiper-slide">
                    <div class="cmr-nc-card">
                        <?php echo $bg_img_html; ?>
                        <div class="cmr-nc-overlay"></div>
                        
                        <!-- Top Bar: Featured Badge on top-left -->
                        <div class="cmr-nc-top-bar">
                            <?php if ( $show_featured ) : ?>
                                <span class="cmr-nc-badge">
                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/>
                                    </svg>
                                    FEATURED
                                </span>
                            <?php endif; ?>
                        </div>

                        <!-- Bottom Card Content -->
                        <div class="cmr-nc-card-content">
                            <!-- Publisher Logo placed above metadata -->
                            <div class="cmr-nc-logo-wrap">
                                <?php if ( $logo_url ) : ?>
                                    <img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php echo esc_attr( $publisher_name ); ?>" class="cmr-nc-logo">
                                <?php elseif ( stripos( $publisher_name, 'bbc' ) !== false ) : ?>
                                    <div class="cmr-nc-logo-bbc">
                                        <span>BBC</span><span>NEWS</span>
                                    </div>
                                <?php elseif ( $publisher_name ) : ?>
                                    <span class="cmr-nc-logo-fallback"><?php echo esc_html( $publisher_name ); ?></span>
                                <?php endif; ?>
                            </div>

                            <div class="cmr-nc-meta-info">
                                <div class="cmr-nc-meta-left">
                                    <span class="cmr-nc-publisher"><?php echo esc_html( $publisher_name ); ?></span>
                                    <span class="cmr-nc-meta-sep">|</span>
                                    <span class="cmr-nc-date">Published <?php echo get_the_date('M d, Y'); ?></span>
                                    <?php if ( $reading_time ) : ?>
                                        <span class="cmr-nc-meta-sep">|</span>
                                        <span class="cmr-nc-time"><?php echo esc_html( $reading_time ); ?><?php echo is_numeric($reading_time) ? ' mins' : ''; ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <h3 class="cmr-nc-post-title">
                                <a href="<?php echo esc_url( $link ); ?>" target="<?php echo esc_attr( $target ); ?>">
                                    <?php the_title(); ?>
                                </a>
                            </h3>
                            <div class="cmr-nc-excerpt"><?php echo wp_trim_words( get_the_excerpt(), 20, '...' ); ?></div>
                            
                            <a href="<?php echo esc_url( $link ); ?>" target="<?php echo esc_attr( $target ); ?>" class="cmr-nc-read-btn">
                                <span>Read Coverage</span> 
                                <svg width="14" height="14" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4 12L12 4M12 4H6M12 4V10" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
            <div class="swiper-pagination"></div>
        </div>
        
        <script>
        (function() {
            function initCmrNewsCarousel() {
                if (typeof Swiper === 'undefined') {
                    setTimeout(initCmrNewsCarousel, 120);
                    return;
                }
                document.querySelectorAll('.cmr-nc-carousel:not(.swiper-initialized)').forEach(function(carouselEl) {
                    var slideCount = carouselEl.querySelectorAll('.swiper-slide').length;
                    var swiperInstance = new Swiper(carouselEl, {
                        slidesPerView: 1.18,
                        spaceBetween: 24,
                        centeredSlides: true,
                        loop: slideCount > 1,
                        speed: 400,
                        autoplay: slideCount > 1 ? {
                            delay: 6000,
                            disableOnInteraction: false,
                            pauseOnMouseEnter: true,
                        } : false,
                        mousewheel: {
                            enabled: true,
                            forceToAxis: true,
                            releaseOnEdges: true,
                            sensitivity: 1,
                            thresholdDelta: 10,
                            thresholdTime: 350,
                        },
                        pagination: {
                            el: carouselEl.querySelector('.swiper-pagination') || '.swiper-pagination',
                            clickable: true,
                            renderBullet: function (index, className) {
                                return '<span class="' + className + '"><span class="dot-fill"></span></span>';
                            }
                        },
                        breakpoints: {
                            640: {
                                slidesPerView: 1.25,
                                spaceBetween: 20,
                                centeredSlides: true,
                            },
                            768: {
                                slidesPerView: 1.35,
                                spaceBetween: 24,
                                centeredSlides: true,
                            },
                            1024: {
                                slidesPerView: 1.2,
                                spaceBetween: 30,
                                centeredSlides: true,
                            },
                            1400: {
                                slidesPerView: 1.15,
                                spaceBetween: 36,
                                centeredSlides: true,
                            }
                        }
                    });

                    function updateBulletProgress() {
                        var bullets = carouselEl.querySelectorAll('.swiper-pagination-bullet');
                        var activeIndex = typeof swiperInstance.realIndex !== 'undefined' ? swiperInstance.realIndex : 0;

                        bullets.forEach(function(dot) {
                            var fill = dot.querySelector('.dot-fill');
                            if (!fill) {
                                fill = document.createElement('span');
                                fill.className = 'dot-fill';
                                dot.appendChild(fill);
                            }
                            fill.style.transition = 'none';
                            fill.style.webkitTransition = 'none';
                            fill.style.width = '0%';
                        });

                        if (bullets[activeIndex]) {
                            var activeDot = bullets[activeIndex];
                            var fill = activeDot.querySelector('.dot-fill');
                            if (!fill) {
                                fill = document.createElement('span');
                                fill.className = 'dot-fill';
                                activeDot.appendChild(fill);
                            }
                            fill.style.transition = 'none';
                            fill.style.webkitTransition = 'none';
                            fill.style.width = '0%';

                            requestAnimationFrame(function() {
                                requestAnimationFrame(function() {
                                    fill.style.transition = 'width 6000ms linear';
                                    fill.style.webkitTransition = 'width 6000ms linear';
                                    fill.style.width = '100%';
                                });
                            });
                        }
                    }

                    swiperInstance.on('slideChangeTransitionStart', updateBulletProgress);

                    // Initialize first slide progress fill
                    setTimeout(updateBulletProgress, 80);

                    // 2-finger touch gesture support on touchscreens
                    (function(swiper, el) {
                        var isTwoFinger = false;
                        var startX = 0;
                        var startY = 0;
                        var triggered = false;

                        el.addEventListener('touchstart', function(e) {
                            if (e.touches && e.touches.length === 2) {
                                isTwoFinger = true;
                                startX = (e.touches[0].clientX + e.touches[1].clientX) / 2;
                                startY = (e.touches[0].clientY + e.touches[1].clientY) / 2;
                                triggered = false;
                            } else {
                                isTwoFinger = false;
                            }
                        }, { passive: true });

                        el.addEventListener('touchmove', function(e) {
                            if (!isTwoFinger || !e.touches || e.touches.length !== 2 || triggered) return;

                            var currentX = (e.touches[0].clientX + e.touches[1].clientX) / 2;
                            var currentY = (e.touches[0].clientY + e.touches[1].clientY) / 2;
                            var diffX = currentX - startX;
                            var diffY = currentY - startY;

                            // Horizontal 2-finger swipe detection
                            if (Math.abs(diffX) > 35 && Math.abs(diffX) > Math.abs(diffY)) {
                                triggered = true;
                                if (diffX < 0) {
                                    swiper.slideNext();
                                } else {
                                    swiper.slidePrev();
                                }
                            }
                        }, { passive: true });

                        el.addEventListener('touchend', function(e) {
                            if (!e.touches || e.touches.length < 2) {
                                isTwoFinger = false;
                                triggered = false;
                            }
                        }, { passive: true });
                    })(swiperInstance, carouselEl);
                });
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initCmrNewsCarousel);
            } else {
                initCmrNewsCarousel();
            }
            window.addEventListener('load', initCmrNewsCarousel);

            if (window.jQuery) {
                jQuery(window).on('elementor/frontend/init', function() {
                    initCmrNewsCarousel();
                });
            }
        })();
        </script>
        <?php else : ?>
            <p>No news items found.</p>
        <?php endif; ?>
        <?php wp_reset_postdata(); ?>
    </div>
    <?php
    return ob_get_clean();
}
