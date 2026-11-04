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

        // Bulletproof Odometer Counter Animator (IntersectionObserver)
        function initOdometerCounterAnimation() {
            var $counterItems = $('.counter-item');
            if (!$counterItems.length) return;

            function triggerSingleCounter(el) {
                var $el = $(el);
                var $odometers = $el.hasClass('odometer') ? $el : $el.find('.odometer');
                $odometers.each(function() {
                    var od = this;
                    if (od.getAttribute('data-cmr-done')) return;
                    var finalVal = od.getAttribute('data-odometer-final');
                    if (finalVal) {
                        od.setAttribute('data-cmr-done', 'true');
                        // Clean numeric value for odometer rolling
                        var cleanNum = finalVal.replace(/[^\d.,]/g, '').trim();
                        if (window.Odometer && od.odometer) {
                            od.odometer.update(cleanNum);
                        } else {
                            od.innerHTML = cleanNum;
                        }
                    }
                });
            }

            if ('IntersectionObserver' in window) {
                var observer = new IntersectionObserver(function(entries) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            triggerSingleCounter(entry.target);
                            observer.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0.1 });

                $counterItems.each(function() {
                    observer.observe(this);
                });
            } else {
                $counterItems.each(function() {
                    triggerSingleCounter(this);
                });
            }
        }

        function runAllCounterFixes() {
            decodeCounterBr();
            initOdometerCounterAnimation();
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
