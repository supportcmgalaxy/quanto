<?php
/**
 * Shortcode for Explore Sectors Section
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! function_exists( 'cmr_explore_sectors_shortcode' ) ) {
    function cmr_explore_sectors_shortcode( $atts ) {
        wp_enqueue_style( 'cmr-explore-sectors' );

        $atts = shortcode_atts( array(
            'tagline'  => 'WHO WE SERVE',
            'title'    => 'Every industry has a question. Our insights<br>deliver the answer and the impact.',
        ), $atts );

        $sectors = array(
            array(
                'number' => '.01',
                'title'  => 'AI',
                'desc'   => 'Adoption trends and enterprise returns',
                'link'   => home_url( '/ai/' ),
            ),
            array(
                'number' => '.02',
                'title'  => 'Automotive',
                'desc'   => 'Software-defined vehicles and mobility shifts',
                'link'   => home_url( '/automotive/' ),
            ),
            array(
                'number' => '.03',
                'title'  => 'Consumer Tech',
                'desc'   => 'Device demand and buyer behaviour',
                'link'   => home_url( '/consumer-tech/' ),
            ),
            array(
                'number' => '.04',
                'title'  => 'Digital Supply Chain',
                'desc'   => 'Automation, visibility and resilience',
                'link'   => home_url( '/digital-supply-chain/' ),
            ),
            array(
                'number' => '.05',
                'title'  => 'Enterprise Tech',
                'desc'   => 'Cloud, data centre and IT budget trends',
                'link'   => home_url( '/enterprise-tech/' ),
            ),
            array(
                'number' => '.06',
                'title'  => 'IT & Telecom',
                'desc'   => 'From devices to networks to cloud',
                'link'   => home_url( '/it-telecom/' ),
            ),
            array(
                'number' => '.07',
                'title'  => 'MSME',
                'desc'   => 'Digital adoption and tech spend in small business',
                'link'   => home_url( '/msme/' ),
            ),
            array(
                'number' => '.08',
                'title'  => 'Semiconductors',
                'desc'   => 'Foundry, memory and AI silicon trends',
                'link'   => home_url( '/semiconductors/' ),
            ),
        );

        ob_start();
        ?>
        <div class="cmr-explore-scroll-spacer" id="cmr-explore-spacer">
        <div class="cmr-explore-sectors-section" id="cmr-explore-section">
            <div class="explore-sectors-container">
                <?php if ( ! empty( $atts['tagline'] ) ) : ?>
                    <div class="explore-sectors-tagline"><?php echo esc_html( $atts['tagline'] ); ?></div>
                <?php endif; ?>
                <h2 class="explore-sectors-title"><?php echo wp_kses_post( $atts['title'] ); ?></h2>
            </div>
            

            <style>
                .cmr-explore-scroll-spacer {
                    position: relative;
                    width: 100%;
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                }
                .cmr-explore-ancestor-visible {
                    overflow: visible !important;
                }
                .elementor-element:has(#cmr-explore-section),
                .e-con:has(#cmr-explore-section),
                .e-con-boxed:has(#cmr-explore-section),
                .e-con-inner:has(#cmr-explore-section),
                .elementor-widget:has(#cmr-explore-section),
                .elementor-widget-container:has(#cmr-explore-section),
                .elementor-shortcode:has(#cmr-explore-section) {
                    overflow: visible !important;
                }
                #cmr-explore-section {
                    overflow: hidden !important;
                    position: relative !important;
                    width: 100% !important;
                    box-sizing: border-box !important;
                    background-color: #F8F9FA !important;
                    padding: 70px 0 !important;
                }
                @media (min-width: 769px) {
                    #cmr-explore-section {
                        position: sticky !important;
                        z-index: 5;
                    }
                }
                #cmr-explore-section .explore-sectors-container {
                    width: 100% !important;
                    max-width: 1280px !important;
                    margin: 0 auto 35px auto !important;
                    padding: 0 20px !important;
                    box-sizing: border-box !important;
                    text-align: left !important;
                }
                #cmr-explore-section .explore-sectors-tagline {
                    text-align: left !important;
                    margin: 0 0 10px 0 !important;
                }
                #cmr-explore-section .explore-sectors-title {
                    text-align: left !important;
                    margin: 0 !important;
                    font-size: 42px !important;
                    font-weight: 600 !important;
                    color: #111 !important;
                    line-height: 1.2 !important;
                    letter-spacing: -1px !important;
                }
                #cmr-explore-section .explore-sectors-track-wrapper {
                    width: 100% !important;
                    overflow: hidden !important;
                    box-sizing: border-box !important;
                }
                #cmr-explore-track {
                    display: flex !important;
                    flex-wrap: nowrap !important;
                    width: max-content !important;
                    min-width: max-content !important;
                    will-change: transform;
                    gap: 20px !important;
                    box-sizing: border-box !important;
                    padding-left: max(20px, calc((100vw - 1280px) / 2 + 20px)) !important;
                    padding-right: max(40px, calc((100vw - 1280px) / 2 + 20px)) !important;
                }
                #cmr-explore-track .explore-sector-card {
                    flex: 0 0 323px !important;
                    min-width: 323px !important;
                    max-width: 323px !important;
                    box-sizing: border-box !important;
                }

                @media (max-width: 768px) {
                    .cmr-explore-scroll-spacer {
                        height: auto !important;
                    }
                    /* Ensure outer Elementor containers don't create horizontal scroll or white/black margin */
                    .elementor-element:has(#cmr-explore-section),
                    .e-con:has(#cmr-explore-section),
                    .e-con-boxed:has(#cmr-explore-section),
                    .e-con-inner:has(#cmr-explore-section),
                    .elementor-widget:has(#cmr-explore-section),
                    .elementor-widget-container:has(#cmr-explore-section),
                    .elementor-shortcode:has(#cmr-explore-section),
                    .elementor-column:has(#cmr-explore-section),
                    .elementor-column-wrap:has(#cmr-explore-section),
                    .elementor-section:has(#cmr-explore-section),
                    .elementor-section-boxed:has(#cmr-explore-section),
                    .elementor-element:has(.cmr-explore-sectors-section),
                    .e-con:has(.cmr-explore-sectors-section),
                    .e-con-boxed:has(.cmr-explore-sectors-section),
                    .e-con-inner:has(.cmr-explore-sectors-section),
                    .elementor-widget:has(.cmr-explore-sectors-section),
                    .elementor-widget-container:has(.cmr-explore-sectors-section),
                    .elementor-shortcode:has(.cmr-explore-sectors-section),
                    .elementor-column:has(.cmr-explore-sectors-section),
                    .elementor-column-wrap:has(.cmr-explore-sectors-section),
                    .elementor-section:has(.cmr-explore-sectors-section),
                    .elementor-section-boxed:has(.cmr-explore-sectors-section) {
                        padding-left: 0 !important;
                        padding-right: 0 !important;
                        margin-left: 0 !important;
                        margin-right: 0 !important;
                        --padding-left: 0px !important;
                        --padding-right: 0px !important;
                        width: 100% !important;
                        max-width: 100% !important;
                        box-sizing: border-box !important;
                    }

                    #cmr-explore-section,
                    .cmr-explore-sectors-section {
                        padding: 40px 0 !important;
                        width: 100% !important;
                        max-width: 100vw !important;
                        overflow: hidden !important;
                        box-sizing: border-box !important;
                        background-color: #F8F9FA !important;
                    }
                    #cmr-explore-section .explore-sectors-container,
                    .cmr-explore-sectors-section .explore-sectors-container {
                        padding: 0 20px !important;
                        margin-bottom: 20px !important;
                        width: 100% !important;
                        max-width: 100% !important;
                        box-sizing: border-box !important;
                    }
                    #cmr-explore-section .explore-sectors-title,
                    .cmr-explore-sectors-section .explore-sectors-title {
                        font-size: 26px !important;
                        line-height: 1.25 !important;
                        letter-spacing: -0.5px !important;
                        margin-bottom: 20px !important;
                    }
                    #cmr-explore-section .explore-sectors-track-wrapper,
                    .cmr-explore-sectors-section .explore-sectors-track-wrapper {
                        width: 100% !important;
                        max-width: 100% !important;
                        overflow-x: auto !important;
                        overflow-y: hidden !important;
                        -webkit-overflow-scrolling: touch !important;
                        scroll-snap-type: x mandatory !important;
                        scrollbar-width: none !important;
                        -ms-overflow-style: none !important;
                        box-sizing: border-box !important;
                    }
                    #cmr-explore-section .explore-sectors-track-wrapper::-webkit-scrollbar,
                    .cmr-explore-sectors-section .explore-sectors-track-wrapper::-webkit-scrollbar {
                        display: none !important;
                    }
                    #cmr-explore-track,
                    .cmr-explore-sectors-section .explore-sectors-track {
                        display: flex !important;
                        flex-wrap: nowrap !important;
                        gap: 16px !important;
                        padding-left: 20px !important;
                        padding-right: 20px !important;
                        padding-bottom: 15px !important;
                        scroll-padding-left: 20px !important;
                        scroll-padding-inline: 20px !important;
                        width: max-content !important;
                        min-width: max-content !important;
                        box-sizing: border-box !important;
                        transform: none !important;
                    }
                    #cmr-explore-track::-webkit-scrollbar,
                    .cmr-explore-sectors-section .explore-sectors-track::-webkit-scrollbar {
                        display: none !important;
                    }
                    #cmr-explore-track .explore-sector-card,
                    .cmr-explore-sectors-section .explore-sector-card {
                        flex: 0 0 280px !important;
                        width: 280px !important;
                        max-width: 280px !important;
                        min-width: 280px !important;
                        scroll-snap-align: start !important;
                        scroll-margin-left: 20px !important;
                        flex-shrink: 0 !important;
                        box-sizing: border-box !important;
                        height: auto !important;
                        min-height: 280px !important;
                        padding: 28px 20px !important;
                    }
                }
            </style>

            <div class="explore-sectors-track-wrapper">
                <div class="explore-sectors-track" id="cmr-explore-track">
                    <?php foreach ( $sectors as $sector ) : ?>
                        <div class="explore-sector-card">
                            <span class="sector-number"><?php echo esc_html( $sector['number'] ); ?></span>
                            <div class="sector-content">
                                <h3 class="sector-title">
                                    <a href="<?php echo esc_url( ! empty( $sector['link'] ) ? $sector['link'] : '#' ); ?>" style="color: inherit; text-decoration: none;">
                                        <?php echo esc_html( $sector['title'] ); ?>
                                    </a>
                                </h3>
                                <p class="sector-desc"><?php echo esc_html( $sector['desc'] ); ?></p>
                            </div>
                            <a href="<?php echo esc_url( ! empty( $sector['link'] ) ? $sector['link'] : '#' ); ?>" class="sector-explore-link">Explore <i class="fa-solid fa-arrow-right" style="transform: rotate(-45deg);"></i></a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <script>
            (function() {
                var spacer = null;
                var section = null;
                var track = null;
                var wrapper = null;
                var centerTop = 0;
                var maxScroll = 0;
                var scrollDistance = 0;
                var ticking = false;

                function unclipAncestors(el) {
                    var p = el ? el.parentElement : null;
                    while (p && p !== document.body && p !== document.documentElement) {
                        p.classList.add('cmr-explore-ancestor-visible');
                        p = p.parentElement;
                    }
                }

                function measure() {
                    spacer  = document.getElementById("cmr-explore-spacer");
                    section = document.getElementById("cmr-explore-section");
                    track   = document.getElementById("cmr-explore-track");
                    wrapper = section ? section.querySelector('.explore-sectors-track-wrapper') : null;

                    if (!spacer || !section || !track || !wrapper) return;

                    unclipAncestors(spacer);

                    if (window.innerWidth <= 768) {
                        spacer.style.height = 'auto';
                        section.style.top = '';
                        track.style.transform = '';
                        maxScroll = 0;
                        scrollDistance = 0;
                        return;
                    }

                    // Reset transform to accurately calculate scrollWidth
                    track.style.transform = '';
                    spacer.style.height = '';

                    // Center the section vertically in the viewport while scrolling
                    var sectionH = section.offsetHeight;
                    centerTop = Math.max(10, Math.round((window.innerHeight - sectionH) / 2));
                    section.style.top = centerTop + 'px';

                    var viewportW = window.innerWidth;
                    var totalWidth = track.scrollWidth;
                    var diff = totalWidth - viewportW;

                    if (diff > 0) {
                        maxScroll = diff;
                        scrollDistance = Math.round(diff * 1.15);
                        spacer.style.height = (sectionH + scrollDistance) + 'px';
                    } else {
                        maxScroll = 0;
                        scrollDistance = 0;
                        spacer.style.height = 'auto';
                    }

                    updateScroll();
                }

                function updateScroll() {
                    ticking = false;
                    if (window.innerWidth <= 768 || scrollDistance <= 0 || !spacer || !track) return;

                    var rect = spacer.getBoundingClientRect();
                    var progress = (centerTop - rect.top) / scrollDistance;
                    progress = Math.min(1, Math.max(0, progress));

                    var x = -progress * maxScroll;
                    track.style.transform = 'translate3d(' + x.toFixed(2) + 'px, 0, 0)';
                }

                function onScroll() {
                    if (!ticking) {
                        ticking = true;
                        window.requestAnimationFrame(updateScroll);
                    }
                }

                function init() {
                    measure();
                    window.addEventListener('scroll', onScroll, { passive: true });
                    window.addEventListener('resize', measure, { passive: true });
                    window.addEventListener('load', measure);
                }

                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', init);
                } else {
                    init();
                }

                if (window.jQuery) {
                    jQuery(window).on('elementor/frontend/init', function() {
                        setTimeout(measure, 300);
                    });
                }
            })();
            </script>
        </div>
        </div>
        <?php
        return ob_get_clean();
    }
}
add_shortcode( 'cmr_explore_sectors', 'cmr_explore_sectors_shortcode' );
