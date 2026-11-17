<?php
/**
 * Shortcode for Custom Explore Sectors Section (New Page)
 * Shortcode: [cmr_explore_sectors_custom]
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! function_exists( 'cmr_explore_sectors_custom_shortcode' ) ) {
    function cmr_explore_sectors_custom_shortcode( $atts ) {
        // Enqueue explore sectors CSS
        wp_enqueue_style( 'cmr-explore-sectors' );

        $atts = shortcode_atts( array(
            'id' => 'our-offerings',
        ), $atts );
        $anchor_id = ! empty( $atts['id'] ) ? sanitize_html_class( $atts['id'] ) : 'our-offerings';

        // Unique ID to avoid GSAP conflict if multiple instances exist
        $unique_id = uniqid('cmr_explore_');

        /* ==========================================================================
           👇 YAHAN AAP APNE CARDS KA CONTENT EDIT / ADD KAR SAKTE HAIN 👇
           ========================================================================== */
        $sectors = array(
            array(
                'number' => '.01',
                'title'  => 'Syndicate Events',
                'desc'   => 'Curated, high-impact gatherings that bring together key decision-makers for focused discussions, meaningful networking, and strategic collaborations',
                'link'   => '#', // Apna Link yahan dalein
            ),
            array(
                'number' => '.02',
                'title'  => 'Executive Round Tables',
                'desc'   => 'Engage with C-suite executives and industry leaders in intimate, high-value conversations to explore market trends, challenges, and growth opportunities.',
                'link'   => '#',
            ),
            array(
                'number' => '.03',
                'title'  => 'Awards & Recognition',
                'desc'   => 'Position your brand as an industry leader by recognizing and celebrating excellence, fostering brand credibility and deeper market engagement.',
                'link'   => '#',
            ),
            array(
                'number' => '.04',
                'title'  => 'Bespoke Programs',
                'desc'   => 'Customized engagement strategies designed to align with your business objectives,amplify brand influence, and drive customer engagement.',
                'link'   => '#',
            ),
            array(
                'number' => '.05',
                'title'  => 'Marketing Campaigns',
                'desc'   => 'Craft and execute high-impact campaigns that enhance brand visibility, generate quality leads, and position your solutions as industry must-haves.',
                'link'   => '#',
            ),
            array(
                'number' => '.06',
                'title'  => 'Webinars',
                'desc'   => 'Host insightful, interactive sessions featuring top industry experts, delivering thought leadership while engaging directly with your target audience.',
                'link'   => '#',
            ),
            array(
                'number' => '.07',
                'title'  => 'Offsites',
                'desc'   => 'Interact with your target customers in an uninterrupted, remote environment to establish thought leadership of your brand.',
                'link'   => '#',
            ),
            array(
                'number' => '.08',
                'title'  => 'Influencer Marketing',
                'desc'   => 'Leverage trusted industry voices and technology influencers to amplify brand awareness, build credibility, and drive high-quality engagement with your target audience.',
                'link'   => '#',
            ),
            
            // 👉 Naya card add karne ke liye upar wala block copy karke yahan paste kar sakte hain.
        );
        /* ========================================================================== */

        ob_start();
        ?>
        <div id="<?php echo esc_attr( $anchor_id ); ?>" class="cmr-explore-sectors-anchor" style="position: relative; top: -30px; scroll-margin-top: 100px;"></div>
        <?php if ( $anchor_id !== 'our-offerings' ) : ?>
            <div id="our-offerings" class="cmr-explore-sectors-anchor" style="position: relative; top: -30px; scroll-margin-top: 100px;"></div>
        <?php endif; ?>
        <div class="cmr-explore-scroll-spacer" id="<?php echo esc_attr( $unique_id ); ?>_spacer">
        <div class="cmr-explore-sectors-section" id="<?php echo esc_attr( $unique_id ); ?>_section" style="scroll-margin-top: 100px;">
            <div class="explore-sectors-container">
                <!-- Section Title: Aap title ko yahan se edit kar sakte hain -->
                <h2 class="explore-sectors-title">Our Offerings </h2>
            </div>
            

            <style>
                html {
                    scroll-behavior: smooth;
                }
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
                .elementor-element:has(#<?php echo esc_attr( $unique_id ); ?>_section),
                .e-con:has(#<?php echo esc_attr( $unique_id ); ?>_section),
                .e-con-boxed:has(#<?php echo esc_attr( $unique_id ); ?>_section),
                .e-con-inner:has(#<?php echo esc_attr( $unique_id ); ?>_section),
                .elementor-widget:has(#<?php echo esc_attr( $unique_id ); ?>_section),
                .elementor-widget-container:has(#<?php echo esc_attr( $unique_id ); ?>_section),
                .elementor-shortcode:has(#<?php echo esc_attr( $unique_id ); ?>_section) {
                    overflow: visible !important;
                }
                #<?php echo esc_attr( $unique_id ); ?>_section {
                    overflow: hidden !important;
                    position: relative !important;
                    width: 100% !important;
                    box-sizing: border-box !important;
                    background-color: #F8F9FA !important;
                    padding: 70px 0 !important;
                }
                @media (min-width: 769px) {
                    #<?php echo esc_attr( $unique_id ); ?>_section {
                        position: sticky !important;
                        z-index: 5;
                    }
                }
                #<?php echo esc_attr( $unique_id ); ?>_section .explore-sectors-container {
                    width: 100% !important;
                    max-width: 1280px !important;
                    margin: 0 auto 35px auto !important;
                    padding: 0 20px !important;
                    box-sizing: border-box !important;
                    text-align: left !important;
                }
                #<?php echo esc_attr( $unique_id ); ?>_section .explore-sectors-title {
                    text-align: left !important;
                    margin: 0 !important;
                    font-size: 42px !important;
                    font-weight: 600 !important;
                    color: #111 !important;
                    line-height: 1.2 !important;
                    letter-spacing: -1px !important;
                }
                .cmr-explore-sectors-anchor,
                #our-offerings,
                #<?php echo esc_attr( $anchor_id ); ?>,
                #<?php echo esc_attr( $unique_id ); ?>_section {
                    scroll-margin-top: 100px !important;
                }
                #<?php echo esc_attr( $unique_id ); ?>_section .explore-sectors-track-wrapper {
                    width: 100% !important;
                    overflow: hidden !important;
                    box-sizing: border-box !important;
                }
                #<?php echo esc_attr( $unique_id ); ?>_track {
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
                #<?php echo esc_attr( $unique_id ); ?>_track .explore-sector-card {
                    flex: 0 0 323px !important;
                    min-width: 323px !important;
                    max-width: 323px !important;
                    box-sizing: border-box !important;
                }
                #<?php echo esc_attr( $unique_id ); ?>_section .sector-explore-link {
                    display: none !important;
                }
                #<?php echo esc_attr( $unique_id ); ?>_section .sector-content {
                    margin-bottom: 0 !important;
                }

                @media (max-width: 768px) {
                    .cmr-explore-scroll-spacer {
                        height: auto !important;
                    }
                    .elementor-element.elementor-element-4fc6cfd,
                    .elementor-element[data-id="4fc6cfd"],
                    .elementor-element.elementor-element-4fc6cfd > .e-con-inner,
                    .elementor-element[data-id="4fc6cfd"] > .e-con-inner,
                    #our-offerings,
                    .our-offerings,
                    .elementor-element:has(#<?php echo esc_attr( $unique_id ); ?>_section),
                    .e-con:has(#<?php echo esc_attr( $unique_id ); ?>_section),
                    .e-con-boxed:has(#<?php echo esc_attr( $unique_id ); ?>_section),
                    .e-con-inner:has(#<?php echo esc_attr( $unique_id ); ?>_section),
                    .elementor-widget:has(#<?php echo esc_attr( $unique_id ); ?>_section),
                    .elementor-widget-container:has(#<?php echo esc_attr( $unique_id ); ?>_section),
                    .elementor-shortcode:has(#<?php echo esc_attr( $unique_id ); ?>_section),
                    .elementor-column:has(#<?php echo esc_attr( $unique_id ); ?>_section),
                    .elementor-column-wrap:has(#<?php echo esc_attr( $unique_id ); ?>_section),
                    .elementor-section:has(#<?php echo esc_attr( $unique_id ); ?>_section),
                    .elementor-section-boxed:has(#<?php echo esc_attr( $unique_id ); ?>_section) {
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

                    #<?php echo esc_attr( $unique_id ); ?>_section {
                        position: static !important;
                        height: auto !important;
                        min-height: auto !important;
                        max-height: none !important;
                        padding: 40px 0 !important;
                        width: 100% !important;
                        max-width: 100vw !important;
                        overflow: hidden !important;
                        box-sizing: border-box !important;
                        background-color: #F8F9FA !important;
                    }
                    #<?php echo esc_attr( $unique_id ); ?>_section .explore-sectors-container {
                        padding: 0 20px !important;
                        margin-bottom: 20px !important;
                        width: 100% !important;
                        max-width: 100% !important;
                        box-sizing: border-box !important;
                    }
                    #<?php echo esc_attr( $unique_id ); ?>_section .explore-sectors-title {
                        font-size: 26px !important;
                        line-height: 1.25 !important;
                        letter-spacing: -0.5px !important;
                        margin-bottom: 20px !important;
                    }
                    #<?php echo esc_attr( $unique_id ); ?>_section .explore-sectors-track-wrapper {
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
                    #<?php echo esc_attr( $unique_id ); ?>_section .explore-sectors-track-wrapper::-webkit-scrollbar {
                        display: none !important;
                    }
                    #<?php echo esc_attr( $unique_id ); ?>_track {
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
                    #<?php echo esc_attr( $unique_id ); ?>_track::-webkit-scrollbar {
                        display: none !important;
                    }
                    #<?php echo esc_attr( $unique_id ); ?>_track .explore-sector-card {
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
                <div class="explore-sectors-track" id="<?php echo esc_attr( $unique_id ); ?>_track">
                    <?php foreach ( $sectors as $sector ) : ?>
                        <div class="explore-sector-card">
                            <span class="sector-number"><?php echo esc_html( $sector['number'] ); ?></span>
                            <div class="sector-content">
                                <h3 class="sector-title"><?php echo esc_html( $sector['title'] ); ?></h3>
                                <p class="sector-desc"><?php echo esc_html( $sector['desc'] ); ?></p>
                            </div>
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
                    spacer  = document.getElementById("<?php echo esc_js( $unique_id ); ?>_spacer");
                    section = document.getElementById("<?php echo esc_js( $unique_id ); ?>_section");
                    track   = document.getElementById("<?php echo esc_js( $unique_id ); ?>_track");
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

                // Robust smooth scroll handling for buttons linking to #our-offerings
                function handleOfferingsScroll(e) {
                    var link = e.target.closest('a[href*="our-offering"], a[href*="explore-marketing"], a.download-btn a');
                    if (!link) {
                        var btn = e.target.closest('.elementor-button');
                        if (btn && btn.textContent.toLowerCase().includes('explore marketing')) {
                            link = btn;
                        }
                    }
                    if (!link) return;

                    var target = document.querySelector('.explore-sectors-title') || 
                                 document.querySelector('.cmr-explore-sectors-section') || 
                                 document.getElementById('our-offerings');

                    if (target) {
                        e.preventDefault();
                        e.stopPropagation();
                        if (e.stopImmediatePropagation) e.stopImmediatePropagation();

                        var rect = target.getBoundingClientRect();
                        var scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                        var finalY = rect.top + scrollTop - 90;

                        window.scrollTo({
                            top: Math.max(0, finalY),
                            behavior: 'smooth'
                        });

                        if (window.jQuery) {
                            window.jQuery('html, body').stop().animate({ scrollTop: Math.max(0, finalY) }, 600);
                        }

                        if (window.history && window.history.pushState) {
                            window.history.pushState(null, null, '#our-offerings');
                        }
                        return false;
                    }
                }

                document.addEventListener('click', handleOfferingsScroll, true);
                window.addEventListener('click', handleOfferingsScroll, true);

                if (window.location.hash && window.location.hash.includes('our-offering')) {
                    setTimeout(function() {
                        var target = document.querySelector('.explore-sectors-title') || document.querySelector('.cmr-explore-sectors-section');
                        if (target) {
                            var finalY = target.getBoundingClientRect().top + (window.pageYOffset || document.documentElement.scrollTop) - 90;
                            window.scrollTo({ top: Math.max(0, finalY), behavior: 'smooth' });
                        }
                    }, 400);
                }
            })();
            </script>
        </div>
        </div>
        <?php
        return ob_get_clean();
    }
}

// Register Shortcode
add_shortcode( 'cmr_explore_sectors_custom', 'cmr_explore_sectors_custom_shortcode' );
