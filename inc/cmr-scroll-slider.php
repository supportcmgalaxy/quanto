<?php
/**
 * CMR Common Horizontal Scroll-on-Scroll Slider
 *
 * Reusable, zero-dependency (no GSAP) sticky horizontal scroll animation.
 * Pins the parent section (keeping headings + cards together) and scrolls
 * the card track edge-to-edge across the full viewport without container clipping.
 *
 * Usage:
 * - Automatically enhances `.process-box-wrapper.horizontal-scroll` (pins its parent section).
 * - Reusable anywhere: add class `cmr-scroll-slider` to any Elementor section/container
 *   or slider wrapper (optionally add `cmr-scroll-track` on the moving row).
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
        top: 64px;
        width: 100%;
        overflow-x: clip;
        overflow-y: visible;
    }
    .cmr-hscroll-track-ready {
        display: flex !important;
        flex-wrap: nowrap !important;
        will-change: transform;
    }
    .cmr-hscroll-unclip {
        overflow: visible !important;
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
        var STICKY_TOP = 64;
        var BREAKPOINT = 992;
        var instances = [];
        var ticking = false;

        function ensureStickyAncestors(el) {
            var p = el.parentElement;
            while (p && p !== document.body && p !== document.documentElement) {
                var cs = window.getComputedStyle(p);
                if (cs.overflow === 'hidden' || cs.overflowY === 'hidden' || cs.overflowX === 'hidden') {
                    p.style.overflow = 'clip';
                }
                p = p.parentElement;
            }
        }

        function unclipBetween(track, sticky) {
            var p = track.parentElement;
            while (p && p !== sticky) {
                p.classList.add('cmr-hscroll-unclip');
                p = p.parentElement;
            }
        }

        function resolvePinTarget(target) {
            // If target is an Elementor parent section/container, pin it directly
            if (target.classList.contains('cmr-scroll-slider')) {
                return target;
            }
            // For `.process-box-wrapper.horizontal-scroll`, pin its outer Elementor parent section
            // so the section heading ("WHO WE SERVE") stays pinned with the cards
            var parentSection = target.closest('.e-con.e-parent, .elementor-section');
            return parentSection || target;
        }

        function resolveTrack(pinTarget, originalTarget) {
            if (originalTarget && originalTarget !== pinTarget && originalTarget.matches('.cmr-scroll-track, .process-box-wrapper, .swiper-wrapper')) {
                return originalTarget;
            }
            return pinTarget.querySelector('.cmr-scroll-track, .process-box-wrapper, .swiper-wrapper, :scope > .e-con-inner') || pinTarget;
        }

        function setupSlider(originalTarget) {
            var pinTarget = resolvePinTarget(originalTarget);
            if (pinTarget.dataset.cmrHscrollInit === '1') return;
            if (pinTarget.closest('.cmr-hscroll-spacer')) return;
            pinTarget.dataset.cmrHscrollInit = '1';

            var track = resolveTrack(pinTarget, originalTarget);

            var spacer = document.createElement('div');
            spacer.className = 'cmr-hscroll-spacer';
            var sticky = document.createElement('div');
            sticky.className = 'cmr-hscroll-sticky';

            pinTarget.parentNode.insertBefore(spacer, pinTarget);
            sticky.appendChild(pinTarget);
            spacer.appendChild(sticky);

            track.classList.add('cmr-hscroll-track-ready');
            unclipBetween(track, sticky);
            ensureStickyAncestors(spacer);

            instances.push({
                spacer: spacer,
                sticky: sticky,
                track: track,
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
                var trackRect = inst.track.getBoundingClientRect();
                var startLeft = Math.max(0, trackRect.left);
                var viewportW = document.documentElement.clientWidth || window.innerWidth;
                var maxScroll = Math.max(0, inst.track.scrollWidth - (viewportW - 2 * startLeft));
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
