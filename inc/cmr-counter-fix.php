<?php
/**
 * CMR Counter Widget Fix
 * Enables HTML line breaks (<br>) inside the Counter widget text.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// 1. PHP filter on Elementor widget output
add_filter( 'elementor/widget/render_content', 'cmr_fix_counter_widget_html', 10, 2 );
function cmr_fix_counter_widget_html( $content, $widget ) {
    if ( ! empty( $content ) && ( strpos( $content, 'funfact-info' ) !== false || ( is_object( $widget ) && method_exists( $widget, 'get_name' ) && $widget->get_name() === 'quanto_counter' ) ) ) {
        // Decode escaped <br> tags so they render as actual line breaks
        $content = preg_replace( '/&lt;br\s*\/?&gt;/i', '<br>', $content );
    }
    return $content;
}

// 2. Client-side script for instant rendering and Elementor live editor
add_action( 'wp_footer', 'cmr_fix_counter_br_script', 99 );
add_action( 'elementor/frontend/after_enqueue_scripts', 'cmr_fix_counter_br_script', 99 );
function cmr_fix_counter_br_script() {
    ?>
    <script>
    (function($) {
        if (!$) return;
        function decodeCounterBr() {
            $('.funfact-info').each(function() {
                var html = $(this).html();
                if (html && html.indexOf('&lt;br') !== -1) {
                    $(this).html(html.replace(/&lt;br\s*\/?&gt;/gi, '<br>'));
                }
            });
        }

        // Real-Time Smooth Count-Up Engine (rAF + IntersectionObserver)
        function initRealtimeCounterAnimation() {
            var counterNodes = document.querySelectorAll('.counter-item, [data-odometer-final], .odometer');
            if (!counterNodes.length) return;

            var targets = [];
            counterNodes.forEach(function(node) {
                if (node.hasAttribute('data-odometer-final')) {
                    if (targets.indexOf(node) === -1) targets.push(node);
                } else {
                    var inner = node.querySelectorAll('[data-odometer-final], .odometer');
                    inner.forEach(function(sub) {
                        if (targets.indexOf(sub) === -1) targets.push(sub);
                    });
                }
            });

            // Set clean initial 0 state
            targets.forEach(function(el) {
                if (!el.getAttribute('data-cmr-counting') && !el.getAttribute('data-cmr-done')) {
                    var finalVal = el.getAttribute('data-odometer-final');
                    if (finalVal) {
                        el.innerHTML = '0';
                    }
                }
            });

            function startCounting(el) {
                if (el.getAttribute('data-cmr-counting') === 'true' || el.getAttribute('data-cmr-done') === 'true') {
                    return;
                }
                el.setAttribute('data-cmr-counting', 'true');

                var rawFinal = el.getAttribute('data-odometer-final') || el.innerText || '';
                var cleanNumberStr = rawFinal.replace(/[^\d.,]/g, '').trim();
                if (!cleanNumberStr) return;

                var hasComma = cleanNumberStr.indexOf(',') !== -1;
                var targetNumber = parseFloat(cleanNumberStr.replace(/,/g, ''));
                if (isNaN(targetNumber) || targetNumber <= 0) {
                    el.innerHTML = cleanNumberStr;
                    el.setAttribute('data-cmr-done', 'true');
                    return;
                }

                // 1.4s for smaller numbers, 1.8s for larger numbers
                var duration = targetNumber > 100 ? 1800 : 1400;
                var startTime = null;

                function tick(timestamp) {
                    if (!startTime) startTime = timestamp;
                    var elapsed = timestamp - startTime;
                    var progress = Math.min(elapsed / duration, 1);

                    // Cubic ease-out: starts fast, slows down gently into final number
                    var ease = 1 - Math.pow(1 - progress, 3);
                    var current = Math.floor(ease * targetNumber);

                    var displayStr = hasComma ? current.toLocaleString('en-US') : current.toString();
                    el.innerHTML = displayStr;

                    if (progress < 1) {
                        window.requestAnimationFrame(tick);
                    } else {
                        var finalDisplay = hasComma ? Math.floor(targetNumber).toLocaleString('en-US') : Math.floor(targetNumber).toString();
                        el.innerHTML = finalDisplay;
                        el.setAttribute('data-cmr-done', 'true');
                    }
                }

                window.requestAnimationFrame(tick);
            }

            function isElementInView(el) {
                var rect = el.getBoundingClientRect();
                return (
                    rect.top < (window.innerHeight || document.documentElement.clientHeight) + 50 &&
                    rect.bottom > 0
                );
            }

            if ('IntersectionObserver' in window) {
                var observer = new IntersectionObserver(function(entries) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            startCounting(entry.target);
                            observer.unobserve(entry.target);
                        }
                    });
                }, {
                    threshold: 0.05,
                    rootMargin: '0px 0px 50px 0px'
                });

                targets.forEach(function(el) {
                    observer.observe(el);
                });
            }

            function checkVisibleNow() {
                targets.forEach(function(el) {
                    if (isElementInView(el)) {
                        startCounting(el);
                    }
                });
            }

            checkVisibleNow();
            window.addEventListener('scroll', checkVisibleNow, { passive: true });
        }

        function runAllCounterFixes() {
            decodeCounterBr();
            initRealtimeCounterAnimation();
        }

        $(document).ready(runAllCounterFixes);
        $(window).on('load', runAllCounterFixes);
        // Elementor live editor hook
        $(window).on('elementor/frontend/init', function() {
            if (window.elementorFrontend && elementorFrontend.hooks) {
                elementorFrontend.hooks.addAction('frontend/element_ready/quanto_counter.default', function($scope) {
                    runAllCounterFixes();
                });
                elementorFrontend.hooks.addAction('frontend/element_ready/global', function() {
                    runAllCounterFixes();
                });
            }
        });
    })(window.jQuery);
    </script>
    <?php
}
