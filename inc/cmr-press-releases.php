<?php
/**
 * CMR Press Releases Horizontal Scroll Shortcode
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'cmr_press_releases', 'cmr_render_press_releases_shortcode' );

function cmr_render_press_releases_shortcode( $atts ) {
    // Add inline CSS for the shortcode so we don't have to enqueue a new file
    ob_start();
    ?>
    <style>
        .cmr-pr-section {
            background-color: #000 !important;
            color: #fff !important;
            padding: 60px 0 !important;
            min-height: 100vh !important;
            display: flex !important;
            flex-direction: column !important;
            justify-content: center !important;
            font-family: 'Instrument Sans', -apple-system, sans-serif;
            overflow: hidden !important;
            position: relative !important;
            width: 100% !important;
            box-sizing: border-box !important;
        }

        /* Ensure parent Elementor container does not restrict full width or add outer margin */
        .elementor-element:has(#cmr-pr-section),
        .e-con:has(#cmr-pr-section),
        .e-con-boxed:has(#cmr-pr-section),
        .e-con-inner:has(#cmr-pr-section),
        .elementor-widget:has(#cmr-pr-section),
        .elementor-widget-shortcode:has(#cmr-pr-section) {
            width: 100% !important;
            max-width: 100% !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
            box-sizing: border-box !important;
        }

        .cmr-pr-container {
            max-width: 1300px;
            width: 100%;
            margin: 0 auto;
            padding: 0 20px;
            box-sizing: border-box;
            flex-shrink: 0;
        }

        .cmr-pr-header {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            margin-bottom: 40px;
            gap: 16px;
        }

        .cmr-pr-title-area {
            text-align: center;
            max-width: 650px;
            margin: 0 auto;
            width: 100%;
        }

        .cmr-pr-title-area h2 {
            font-family: 'Instrument Sans', sans-serif;
            font-size: 28px;
            font-weight: 600;
            color: #fff;
            margin: 0 0 12px 0;
            letter-spacing: -0.5px;
            line-height: 1.25;
            text-align: center;
        }

        .cmr-pr-title-area p {
            font-family: 'Instrument Sans', sans-serif;
            font-size: 14px;
            color: #aaa;
            margin: 0 auto;
            line-height: 1.5;
            text-align: center;
            max-width: 520px;
        }

        .cmr-pr-explore-btn {
            font-family: 'Instrument Sans', sans-serif;
            color: #fff;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            border-bottom: 1px solid #fff;
            padding-bottom: 3px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: opacity 0.3s;
            margin: 0 auto;
            text-align: center;
        }

        .cmr-pr-explore-btn:hover {
            opacity: 0.8;
            color: #fff;
        }

        .cmr-pr-track-wrapper {
            width: 100% !important;
            overflow: hidden !important;
            position: relative !important;
            box-sizing: border-box !important;
            flex-shrink: 0;
        }

        .cmr-pr-cards-track {
            display: flex !important;
            flex-wrap: nowrap !important;
            gap: 40px !important;
            padding-left: max(20px, calc((100% - 1260px) / 2)) !important;
            padding-right: max(20px, calc((100% - 1260px) / 2)) !important;
            width: max-content !important;
            min-width: max-content !important;
            box-sizing: border-box !important;
            will-change: transform;
        }

        .cmr-pr-card {
            display: flex;
            background: #1a1a1a;
            border-radius: 0 !important;
            overflow: hidden;
            width: 800px; /* Fixed width for the scroll effect */
            height: 400px;
            flex-shrink: 0;
            box-sizing: border-box;
        }

        .cmr-pr-card-img {
            width: 50%;
            background-size: cover;
            background-position: center;
            border-radius: 0 !important;
        }

        .cmr-pr-card-content {
            width: 50%;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            box-sizing: border-box;
        }

        .cmr-pr-meta {
            font-size: 13px;
            color: #bbb;
            margin-bottom: 15px;
        }

        .cmr-pr-card-title {
            font-size: 18px;
            letter-spacing: 0px;
            font-weight: 600;
            color: #fff;
            margin: 0 0 15px 0;
            line-height: 1.3;
        }

        .cmr-pr-card-excerpt {
            font-size: 14px;
            color: #999;
            margin-bottom: 30px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .cmr-pr-read-btn {
            color: #fff;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            border-bottom: 1px solid #fff;
            padding-bottom: 2px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            align-self: flex-start;
            transition: opacity 0.3s;
        }

        .cmr-pr-read-btn:hover {
            opacity: 0.8;
            color: #fff;
        }

        @media (max-width: 900px) {
            .cmr-pr-card {
                width: 85vw;
                flex-direction: column;
                height: auto;
            }
            .cmr-pr-card-img {
                width: 100%;
                height: 220px;
            }
            .cmr-pr-card-content {
                width: 100%;
                padding: 24px;
            }
        }

        @media (max-width: 768px) {
            .cmr-pr-section {
                min-height: auto !important;
                padding: 50px 0 !important;
            }
            .cmr-pr-header {
                margin-bottom: 25px;
                gap: 14px;
            }
            .cmr-pr-title-area h2 {
                font-size: 28px !important;
            }
            .cmr-pr-title-area p {
                font-size: 14px !important;
                padding: 0 15px;
            }
            .cmr-pr-track-wrapper {
                overflow-x: auto !important;
                -webkit-overflow-scrolling: touch !important;
                scroll-snap-type: x mandatory !important;
                padding-bottom: 20px !important;
            }
            .cmr-pr-cards-track {
                padding-left: 20px !important;
                padding-right: 20px !important;
                gap: 20px !important;
            }
            .cmr-pr-card {
                scroll-snap-align: start !important;
            }
        }
    </style>

    <?php
    $pr_terms = array('media-release', 'media-releases', 'press-release', 'press-releases', 'pressreleases', 'press-release-2', 'press-releases-2');
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
                'terms'    => $pr_terms,
            ),
        ),
    );
    $query = new WP_Query( $args );
    ?>

    <div id="press-release"></div>
    <div class="cmr-pr-section" id="cmr-pr-section">
        <div class="cmr-pr-container">
            <div class="cmr-pr-header">
                <div class="cmr-pr-title-area">
                    <h2>Press Release</h2>
                    <p>Track emerging shifts, growth signals, and market movements in real time.</p>
                </div>
                <a href="<?php echo get_post_type_archive_link('cmr_news'); ?>" class="cmr-pr-explore-btn">
                    Explore More 
                    <svg width="12" height="12" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 12L12 4M12 4H5.5M12 4V10.5" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
            </div>
        </div>

        <div class="cmr-pr-track-wrapper">
            <div class="cmr-pr-cards-track" id="cmr-pr-track">
                <?php if ( $query->have_posts() ) : ?>
                    <?php while ( $query->have_posts() ) : $query->the_post(); 
                        $post_id = get_the_ID();
                        $bg_image = get_the_post_thumbnail_url( $post_id, 'large' );
                        if ( ! $bg_image ) {
                            $bg_image = quanto_get_placeholder_image_uri();
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
                    ?>
                    <div class="cmr-pr-card panel">
                        <div class="cmr-pr-card-img" style="background-image: url('<?php echo esc_url( $bg_image ); ?>');"></div>
                        <div class="cmr-pr-card-content">
                            <div class="cmr-pr-meta">
                                Press Release | <?php echo get_the_date('d M Y'); ?>
                            </div>
                            <h3 class="cmr-pr-card-title"><?php the_title(); ?></h3>
                            <?php
                            $pr_excerpt = get_the_excerpt( $post_id );
                            if ( empty( $pr_excerpt ) ) {
                                $pr_content = get_post_field( 'post_content', $post_id );
                                if ( ! empty( $pr_content ) ) {
                                    if ( preg_match( '/<p\b[^>]*>(.*?)<\/p>/is', $pr_content, $matches ) ) {
                                        $pr_excerpt = wp_strip_all_tags( $matches[1] );
                                    }
                                    if ( empty( $pr_excerpt ) ) {
                                        $pr_excerpt = wp_strip_all_tags( strip_shortcodes( $pr_content ) );
                                    }
                                }
                            }
                            $pr_excerpt_trimmed = wp_trim_words( $pr_excerpt, 20, '...' );
                            ?>
                            <div class="cmr-pr-card-excerpt"><?php echo esc_html( $pr_excerpt_trimmed ); ?></div>
                            <a href="<?php echo esc_url( $link ); ?>" target="<?php echo esc_attr( $target ); ?>" class="cmr-pr-read-btn">
                                Read Coverage 
                                <svg width="12" height="12" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4 12L12 4M12 4H5.5M12 4V10.5" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                    <?php endwhile; ?>
                <?php else : ?>
                    <div class="cmr-pr-card">
                        <div class="cmr-pr-card-content">
                            <p>No press releases found.</p>
                        </div>
                    </div>
                <?php endif; ?>
                <?php wp_reset_postdata(); ?>
            </div>
        </div>
    </div>

    <!-- Init GSAP ScrollTrigger for horizontal scroll -->
    <script>
    (function() {
        function initPressReleasesScroll() {
            let track   = document.getElementById("cmr-pr-track");
            let section = document.getElementById("cmr-pr-section");
            let wrapper = section ? section.querySelector('.cmr-pr-track-wrapper') : null;
            if (!track || !section || !wrapper) return;

            // Kill any pre-existing ScrollTrigger on this section to avoid duplicates
            if (typeof ScrollTrigger !== 'undefined') {
                ScrollTrigger.getAll().forEach(function(st) {
                    if (st.trigger === section) st.kill(true);
                });
            }

            // Mobile view: disable GSAP pinning to prevent layout break and let native swipe work
            if (window.innerWidth <= 768) {
                if (typeof gsap !== 'undefined') {
                    gsap.set(track, { clearProps: "all" });
                }
                track.style.transform = '';
                return;
            }

            // Desktop view: GSAP horizontal scroll with pinning
            if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') {
                setTimeout(initPressReleasesScroll, 100);
                return;
            }
            gsap.registerPlugin(ScrollTrigger);

            gsap.set(track, { clearProps: "transform,x" });

            function getScrollAmount() {
                let containerWidth = wrapper.clientWidth || window.innerWidth;
                let diff = track.scrollWidth - containerWidth;
                return diff > 0 ? -diff : 0;
            }

            const tween = gsap.to(track, {
                x: () => getScrollAmount(),
                ease: "none",
                invalidateOnRefresh: true
            });

            ScrollTrigger.create({
                trigger: section,
                start: "top top",
                end: () => "+=" + Math.abs(getScrollAmount()),
                pin: true,
                pinSpacing: true,
                animation: tween,
                scrub: 0.6,
                invalidateOnRefresh: true,
                anticipatePin: 1
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener("DOMContentLoaded", initPressReleasesScroll);
        } else {
            initPressReleasesScroll();
        }

        window.addEventListener('load', function() {
            initPressReleasesScroll();
            if (typeof ScrollTrigger !== 'undefined') ScrollTrigger.refresh();
        });

        window.addEventListener('resize', function() {
            initPressReleasesScroll();
            if (typeof ScrollTrigger !== 'undefined') ScrollTrigger.refresh();
        });

        if (window.jQuery) {
            jQuery(window).on('elementor/frontend/init', function() {
                setTimeout(function() {
                    initPressReleasesScroll();
                    if (typeof ScrollTrigger !== 'undefined') ScrollTrigger.refresh();
                }, 300);
            });
        }
    })();
    </script>
    <?php
    return ob_get_clean();
}
