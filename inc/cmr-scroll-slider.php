<?php
/**
 * CMR Common Horizontal Scroll-on-Scroll Slider
 *
 * Reusable, zero-dependency (no GSAP) horizontal scroll-on-scroll animation.
 * Replicates the exact behavior of cmrindia.com's QuantoProcess:
 * - The section heading ("Every industry is asking what's next...") scrolls UP naturally.
 * - The cards track locks right in the VERTICAL CENTER of the viewport.
 * - The cards slide edge-to-edge across the screen without being clipped by the 1280px container.
 * - When the last card arrives, the pin immediately releases with ZERO bottom white space.
 *
 * Usage:
 * - Automatically applies to `.process-box-wrapper.horizontal-scroll`.
 * - Reusable on any other slider: add class `cmr-scroll-slider` to the slider container/widget.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'wp_footer', 'cmr_render_common_scroll_slider_assets', 95 );
function cmr_render_common_scroll_slider_assets() {
    if ( is_admin() || ( function_exists( 'quanto_is_elementor_edit' ) && quanto_is_elementor_edit() ) ) {
        return;
    }
    ?>
    <style id="cmr-scroll-slider-css">
    .cmr-hscroll-spacer {
        position: relative;
        width: 100%;
        margin: 0;
        padding: 0;
    }
    .cmr-hscroll-sticky {
        position: sticky;
        width: 100vw;
        margin-left: calc(-50vw + 50%);
        overflow: clip;
        box-sizing: border-box;
        z-index: 5;
    }
    .cmr-hscroll-inner {
        width: max-content;
        box-sizing: border-box;
    }
    .cmr-hscroll-track-ready {
        display: flex !important;
        flex-wrap: nowrap !important;
        width: max-content !important;
        will-change: transform;
    }
    .cmr-hscroll-ancestor-visible {
        overflow: visible !important;
    }
    @media (max-width: 1199.98px) {
        .cmr-hscroll-spacer {
            height: auto !important;
        }
        .cmr-hscroll-sticky {
            position: static !important;
            width: 100% !important;
            margin-left: 0 !important;
            overflow: visible !important;
        }
        .cmr-hscroll-inner {
            width: 100% !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
        }
        .cmr-hscroll-track-ready {
            transform: none !important;
            width: auto !important;
        }
    }
    </style>
    <script id="cmr-scroll-slider-js">
    (function() {
        var BREAKPOINT = 1200;
        var instances = [];
        var ticking = false;

        function unclipAncestors(el) {
            var p = el.parentElement;
            while (p && p !== document.body && p !== document.documentElement) {
                p.classList.add('cmr-hscroll-ancestor-visible');
                p = p.parentElement;
            }
        }

        function resolveTrack(root) {
            if (root.matches('.process-box-wrapper, .cmr-scroll-track, .swiper-wrapper')) {
                return root;
            }
            return root.querySelector('.process-box-wrapper, .cmr-scroll-track, .swiper-wrapper, :scope > .e-con-inner') || root;
        }

        function setupSlider(targetEl) {
            var track = resolveTrack(targetEl);
            if (!track || track.dataset.cmrHscrollInit === '1') return;
            if (track.closest('.cmr-hscroll-spacer')) return;
            track.dataset.cmrHscrollInit = '1';

            var spacer = document.createElement('div');
            spacer.className = 'cmr-hscroll-spacer';

            var sticky = document.createElement('div');
            sticky.className = 'cmr-hscroll-sticky';

            var inner = document.createElement('div');
            inner.className = 'cmr-hscroll-inner';

            // Insert spacer exactly where track was, and move track into sticky inner
            track.parentNode.insertBefore(spacer, track);
            inner.appendChild(track);
            sticky.appendChild(inner);
            spacer.appendChild(sticky);

            track.classList.add('cmr-hscroll-track-ready');
            unclipAncestors(spacer);

            instances.push({
                spacer: spacer,
                sticky: sticky,
                inner: inner,
                track: track,
                centerTop: 0,
                maxScroll: 0,
                scrollDistance: 0
            });
        }

        function measureAll() {
            var isDesktop = window.innerWidth >= BREAKPOINT;

            instances.forEach(function(inst) {
                // Reset styles to measure clean native geometry
                inst.track.style.transform = '';
                inst.spacer.style.height = '';
                inst.sticky.style.top = '';
                inst.inner.style.paddingLeft = '';
                inst.inner.style.paddingRight = '';

                if (!isDesktop) {
                    inst.maxScroll = 0;
                    inst.scrollDistance = 0;
                    return;
                }

                // 1. Calculate natural start offset from container
                var containerRect = inst.spacer.parentElement.getBoundingClientRect();
                var startLeft = Math.max(30, Math.round(containerRect.left));
                inst.inner.style.paddingLeft = startLeft + 'px';
                inst.inner.style.paddingRight = startLeft + 'px';

                // 2. Vertically center the cards in the screen
                var trackH = inst.track.offsetHeight;
                var centerTop = Math.max(20, Math.round((window.innerHeight - trackH) / 2));
                inst.centerTop = centerTop;
                inst.sticky.style.top = centerTop + 'px';

                // 3. Calculate horizontal scroll distance
                var totalW = inst.track.scrollWidth;
                var viewportW = window.innerWidth;
                var maxScroll = Math.max(0, totalW - viewportW + (2 * startLeft));
                inst.maxScroll = maxScroll;

                if (maxScroll > 10) {
                    // Match cmrindia.com pacing
                    var scrollDistance = Math.round(maxScroll * 1.15);
                    inst.scrollDistance = scrollDistance;
                    inst.spacer.style.height = (trackH + scrollDistance) + 'px';
                } else {
                    inst.scrollDistance = 0;
                }
            });

            updateAll();
        }

        function updateAll() {
            ticking = false;
            if (window.innerWidth < BREAKPOINT) return;

            for (var i = 0; i < instances.length; i++) {
                var inst = instances[i];
                if (inst.scrollDistance <= 0) continue;

                var rect = inst.spacer.getBoundingClientRect();
                var progress = (inst.centerTop - rect.top) / inst.scrollDistance;
                progress = Math.min(1, Math.max(0, progress));

                inst.track.style.transform = 'translate3d(' + (-progress * inst.maxScroll).toFixed(2) + 'px, 0, 0)';
            }
        }

        function onScroll() {
            if (!ticking) {
                ticking = true;
                window.requestAnimationFrame(updateAll);
            }
        }

        function initAll() {
            var targets = document.querySelectorAll('.process-box-wrapper.horizontal-scroll, .cmr-scroll-slider');
            if (!targets.length) return;
            targets.forEach(setupSlider);
            measureAll();
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initAll);
        } else {
            initAll();
        }
        window.addEventListener('load', measureAll);
        window.addEventListener('resize', measureAll, { passive: true });
        window.addEventListener('scroll', onScroll, { passive: true });
    })();
    </script>
    <?php
}
