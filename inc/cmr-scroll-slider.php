<?php
/**
 * CMR Common Horizontal Scroll-on-Scroll Slider
 *
 * Reusable, zero-dependency (no GSAP) sticky horizontal scroll animation.
 * Usage: Add class `cmr-scroll-slider` to any slider/container (and optionally
 * `cmr-scroll-track` on the moving inner row, or it auto-detects `.process-box-wrapper`,
 * `.swiper-wrapper`, `.e-con-inner`, or the element itself).
 * Also automatically enhances `.process-box-wrapper.horizontal-scroll`.
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
    }
    .cmr-hscroll-sticky {
        position: sticky;
        top: 96px;
        width: 100%;
        overflow: hidden;
    }
    .cmr-hscroll-track-ready {
        display: flex !important;
        flex-wrap: nowrap !important;
        will-change: transform;
    }
    @media (max-width: 991px) {
        .cmr-hscroll-spacer {
            height: auto !important;
        }
        .cmr-hscroll-sticky {
            position: static;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        .cmr-hscroll-track-ready {
            transform: none !important;
        }
    }
    </style>
    <script id="cmr-scroll-slider-js">
    (function() {
        var STICKY_TOP = 96;
        var BREAKPOINT = 992;
        var instances = [];
        var ticking = false;

        function ensureStickyAncestors(el) {
            var p = el.parentElement;
            while (p && p !== document.body && p !== document.documentElement) {
                var cs = window.getComputedStyle(p);
                if (cs.overflow === 'hidden' || cs.overflowY === 'hidden') {
                    p.style.overflow = 'clip';
                }
                p = p.parentElement;
            }
        }

        function resolveTrack(root) {
            if (root.matches('.cmr-scroll-track, .process-box-wrapper, .swiper-wrapper')) {
                return root;
            }
            return root.querySelector('.cmr-scroll-track, .process-box-wrapper, .swiper-wrapper, :scope > .e-con-inner') || root;
        }

        function setupSlider(root) {
            if (root.dataset.cmrHscrollInit === '1') return;
            if (root.closest('.cmr-hscroll-spacer')) return;
            root.dataset.cmrHscrollInit = '1';

            var track = resolveTrack(root);
            var isRootTrack = (track === root);

            var spacer = document.createElement('div');
            spacer.className = 'cmr-hscroll-spacer';
            var sticky = document.createElement('div');
            sticky.className = 'cmr-hscroll-sticky';

            root.parentNode.insertBefore(spacer, root);
            sticky.appendChild(root);
            spacer.appendChild(sticky);

            track.classList.add('cmr-hscroll-track-ready');
            ensureStickyAncestors(spacer);

            instances.push({
                spacer: spacer,
                sticky: sticky,
                track: track,
                isRootTrack: isRootTrack,
                maxScroll: 0,
                scrollable: 0
            });
        }

        function measureAll() {
            var isDesktop = window.innerWidth >= BREAKPOINT;
            instances.forEach(function(inst) {
                inst.track.style.transform = '';
                inst.spacer.style.height = '';
                if (!isDesktop) {
                    inst.maxScroll = 0;
                    inst.scrollable = 0;
                    return;
                }
                var maxScroll = Math.max(0, inst.track.scrollWidth - inst.sticky.clientWidth);
                inst.maxScroll = maxScroll;
                if (maxScroll > 10) {
                    var stickyH = inst.sticky.offsetHeight;
                    inst.spacer.style.height = (stickyH + maxScroll) + 'px';
                    inst.scrollable = maxScroll;
                } else {
                    inst.scrollable = 0;
                }
            });
            updateAll();
        }

        function updateAll() {
            ticking = false;
            if (window.innerWidth < BREAKPOINT) return;
            for (var i = 0; i < instances.length; i++) {
                var inst = instances[i];
                if (inst.scrollable <= 0) continue;
                var rect = inst.spacer.getBoundingClientRect();
                var progress = Math.min(1, Math.max(0, (STICKY_TOP - rect.top) / inst.scrollable));
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
            var targets = document.querySelectorAll('.cmr-scroll-slider, .process-box-wrapper.horizontal-scroll');
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
