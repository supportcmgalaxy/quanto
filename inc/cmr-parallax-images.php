<?php
/**
 * Lightweight, 60fps GPU-Accelerated Parallax Scroll Effect for Images
 * Supports:
 * - Home Page (2 images in Built on Research section)
 * - About Us Page (3 images in Overview section)
 *
 * Zero external libraries (no GSAP/ScrollSmoother required), 0 PageSpeed penalty.
 * Runs on IntersectionObserver + requestAnimationFrame.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'wp_footer', 'cmr_parallax_images_script', 90 );

function cmr_parallax_images_script() {
    $uri = isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : '';
    $is_home  = is_front_page() || is_home() || $uri === '/' || strpos( $uri, 'home' ) !== false;
    $is_about = is_page( 'about-us' ) || is_page( 13499 ) || strpos( $uri, 'about-us' ) !== false;

    if ( ! $is_home && ! $is_about ) {
        return;
    }
    ?>
    <style id="cmr-parallax-css">
        @media (min-width: 769px) {
            .cmr-parallax-target {
                will-change: transform;
                transition: transform 0.15s cubic-bezier(0.25, 1, 0.5, 1);
            }
        }
        /* Disable hover zoom/scale on floating parallax images */
        .cmr-parallax-target img,
        .cmr-parallax-target:hover img,
        .cmr-parallax-target .quanto-project-thumb img,
        .cmr-parallax-target .quanto-project-thumb:hover img,
        [data-id="03fdb2b"] .quanto-project-thumb img,
        [data-id="03fdb2b"] .quanto-project-thumb:hover img,
        [data-id="03fdb2b"] img:hover,
        [data-id="3dc56d0"] .quanto-project-thumb img,
        [data-id="3dc56d0"] .quanto-project-thumb:hover img,
        [data-id="3dc56d0"] img:hover {
            transform: none !important;
            transition: none !important;
        }
        /* Ensure parent columns do not clip vertical floating travel */
        [data-id="03fdb2b"] .elementor-element-6b0820f,
        [data-id="03fdb2b"] .elementor-element-a0908f6,
        [data-id="03fdb2b"] .elementor-element-8261e71,
        [data-id="03fdb2b"] .elementor-element-763b7b6,
        [data-id="3dc56d0"] .elementor-element {
            overflow: visible !important;
        }
        /* Align left and right floating images with line 3 of center text on About Us page */
        @media (min-width: 1025px) {
            [data-id="3dc56d0"] .elementor-element-18dc9bb {
                align-items: flex-start !important;
            }
            [data-id="3dc56d0"] .elementor-element-4b67977,
            [data-id="3dc56d0"] .elementor-element-7cd0be3 {
                align-self: flex-start !important;
                margin-top: 68px !important;
            }
        }
    </style>
    <script id="cmr-parallax-js" data-no-minify="1" data-cfasync="false">
    (function() {
        function initParallax() {
            if (window.innerWidth < 769) return;

            // Define targets per page with enhanced vertical travel speeds
            var config = [];

            // 1. Home Page Targets (2 images in section 03fdb2b)
            config.push({
                container: '[data-id="03fdb2b"]',
                items: [
                    { sel: '.elementor-element-b30bc68', speed: -0.65 }, // Left image (Team/laptop) floats up
                    { sel: '.elementor-element-8c4ff94', speed: 0.75 }   // Right image (Pink blazer) floats down
                ]
            });

            // 2. About Us Page Targets (3 images in section 3dc56d0)
            config.push({
                container: '[data-id="3dc56d0"]',
                items: [
                    { sel: '.elementor-element-8cd6469', speed: -0.60 }, // Left image (Laptop)
                    { sel: '.elementor-element-f7f45dd', speed: 0.70 },  // Center-bottom image (Pink blazer)
                    { sel: '.elementor-element-3a628c6', speed: -0.45 }  // Right image (Whiteboard)
                ]
            });

            var activeSections = [];

            config.forEach(function(cfg) {
                var container = document.querySelector(cfg.container);
                if (!container) return;

                var boundItems = [];
                cfg.items.forEach(function(item) {
                    var el = container.querySelector(item.sel) || document.querySelector(item.sel);
                    if (el) {
                        el.classList.add('cmr-parallax-target');
                        boundItems.push({ el: el, speed: item.speed });
                    }
                });

                if (boundItems.length) {
                    var secData = {
                        container: container,
                        items: boundItems,
                        inView: false
                    };
                    activeSections.push(secData);

                    // IntersectionObserver: Only compute transforms when section is visible
                    if ('IntersectionObserver' in window) {
                        var observer = new IntersectionObserver(function(entries) {
                            entries.forEach(function(entry) {
                                secData.inView = entry.isIntersecting;
                                if (entry.isIntersecting) {
                                    requestTick();
                                }
                            });
                        }, { rootMargin: '100px 0px 100px 0px' });
                        observer.observe(container);
                    } else {
                        secData.inView = true;
                    }
                }
            });

            if (!activeSections.length) return;

            var ticking = false;

            function requestTick() {
                if (!ticking) {
                    window.requestAnimationFrame(update);
                    ticking = true;
                }
            }

            function update() {
                ticking = false;
                if (window.innerWidth < 769) {
                    activeSections.forEach(function(sec) {
                        sec.items.forEach(function(item) {
                            item.el.style.removeProperty('transform');
                        });
                    });
                    return;
                }

                var vh = window.innerHeight || 800;

                activeSections.forEach(function(sec) {
                    if (!sec.inView) return;
                    var rect = sec.container.getBoundingClientRect();
                    var total = vh + rect.height;
                    var current = vh - rect.top;
                    var progress = current / total;
                    var delta = progress - 0.5;

                    sec.items.forEach(function(item) {
                        var y = delta * item.speed * 280;
                        item.el.style.setProperty('transform', 'translate3d(0, ' + y.toFixed(1) + 'px, 0)', 'important');
                    });
                });
            }

            window.addEventListener('scroll', requestTick, { passive: true });
            window.addEventListener('resize', requestTick, { passive: true });
            requestTick();
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initParallax);
        } else {
            initParallax();
        }
        window.addEventListener('load', initParallax);
    })();
    </script>
    <?php
}
