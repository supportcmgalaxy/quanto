<?php
/**
 * CMR Universal Video Popup (Lightbox Modal)
 * Ensures YouTube videos in Elementor video widgets (and action buttons)
 * open in a fast, lightweight modal popup on click instead of downloading 3.5MB scripts upfront.
 * Works seamlessly in both live frontend and Elementor Editor preview.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_filter( 'elementor/widget/render_content', 'cmr_replace_elementor_yt_iframe_with_facade', 20, 2 );
function cmr_replace_elementor_yt_iframe_with_facade( $content, $widget ) {
    if ( is_admin() || ( function_exists( 'quanto_is_elementor_edit' ) && quanto_is_elementor_edit() ) ) {
        return $content;
    }
    if ( ! is_object( $widget ) || ! method_exists( $widget, 'get_name' ) || $widget->get_name() !== 'video' ) {
        return $content;
    }
    // Homepage widgets already use Elementor's image overlay
    if ( strpos( $content, 'elementor-custom-embed-image-overlay' ) !== false ) {
        return $content;
    }
    if ( stripos( $content, 'youtube' ) === false || stripos( $content, '<iframe' ) === false ) {
        return $content;
    }

    return preg_replace_callback(
        '/<iframe\b[^>]*\bsrc=["\'][^"\']*youtube(?:-nocookie)?\.com\/embed\/([a-zA-Z0-9_-]{11})[^"\']*["\'][^>]*>\s*<\/iframe>/is',
        function( $m ) {
            $vid = $m[1];
            $thumb = 'https://i.ytimg.com/vi/' . $vid . '/hqdefault.jpg';
            return '<div class="elementor-custom-embed-image-overlay cmr-yt-facade" data-cmr-yt-id="' . esc_attr( $vid ) . '" role="button" tabindex="0" aria-label="Play Video">'
                . '<img src="' . esc_url( $thumb ) . '" alt="Video preview" loading="lazy" decoding="async" width="480" height="360">'
                . '<div class="elementor-custom-embed-play cmr-yt-play-btn" aria-hidden="true">'
                . '<svg viewBox="0 0 68 48" width="68" height="48"><path d="M66.52 7.74c-.78-2.93-2.49-5.41-5.42-6.19C55.79.13 34 0 34 0S12.21.13 6.9 1.55c-2.93.78-4.63 3.26-5.42 6.19C.06 13.05 0 24 0 24s.06 10.95 1.48 16.26c.78 2.93 2.49 5.41 5.42 6.19C12.21 47.87 34 48 34 48s21.79-.13 27.1-1.55c2.93-.78 4.64-3.26 5.42-6.19C67.94 34.95 68 24 68 24s-.06-10.95-1.48-16.26z" fill="#f00"/><path d="M45 24L27 14v20" fill="#fff"/></svg>'
                . '</div>'
                . '</div>';
        },
        $content
    );
}

add_action( 'wp_footer', 'cmr_render_video_modal_html', 99 );
add_action( 'elementor/editor/footer', 'cmr_render_video_modal_html', 99 );

function cmr_render_video_modal_html() {
    static $rendered = false;
    if ( $rendered ) {
        return;
    }
    $rendered = true;
    ?>
    <style id="cmr-video-modal-css">
    /* Prevent any inline iframe from ever rendering/playing inside the card frame */
    .elementor-element-9b2ddd6 .elementor-video iframe,
    .elementor-element-d713748 .elementor-video iframe,
    .elementor-widget-video .elementor-video iframe {
        display: none !important;
        pointer-events: none !important;
    }

    .cmr-yt-facade {
        position: relative;
        width: 100%;
        aspect-ratio: 16 / 9;
        background: #000;
        cursor: pointer;
        overflow: hidden;
    }
    .cmr-yt-facade img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    .cmr-yt-facade .cmr-yt-play-btn {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        pointer-events: none;
        transition: transform 0.2s ease;
    }
    .cmr-yt-facade:hover .cmr-yt-play-btn {
        transform: translate(-50%, -50%) scale(1.08);
    }

    .cmr-vmodal-overlay {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        width: 100vw !important;
        height: 100vh !important;
        background: rgba(0, 0, 0, 0.88) !important;
        backdrop-filter: blur(8px) !important;
        -webkit-backdrop-filter: blur(8px) !important;
        z-index: 99999999 !important;
        display: none;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
        padding: 20px;
        box-sizing: border-box;
    }
    .cmr-vmodal-overlay.active {
        display: flex !important;
        opacity: 1 !important;
    }
    .cmr-vmodal-wrap {
        position: relative;
        width: 100%;
        max-width: 960px;
        background: #000;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7);
        transform: scale(0.95);
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .cmr-vmodal-overlay.active .cmr-vmodal-wrap {
        transform: scale(1);
    }
    .cmr-vmodal-aspect {
        position: relative;
        width: 100%;
        padding-top: 56.25%; /* 16:9 Aspect Ratio */
    }
    .cmr-vmodal-aspect iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: 0;
    }
    .cmr-vmodal-close {
        position: absolute;
        top: -45px;
        right: 0;
        width: 36px;
        height: 36px;
        background: rgba(255, 255, 255, 0.2);
        color: #fff;
        border: none;
        border-radius: 50%;
        font-size: 24px;
        line-height: 1;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.2s ease, transform 0.2s ease;
        padding: 0;
    }
    .cmr-vmodal-close:hover {
        background: rgba(255, 255, 255, 0.4);
        transform: scale(1.1);
    }
    @media (max-width: 767px) {
        .cmr-vmodal-overlay {
            padding: 10px;
        }
        .cmr-vmodal-close {
            top: -40px;
            right: 0;
        }
    }
    </style>

    <div class="cmr-vmodal-overlay" id="cmrVideoModalOverlay" role="dialog" aria-modal="true" aria-label="Video Player">
        <div class="cmr-vmodal-wrap">
            <button class="cmr-vmodal-close" id="cmrVideoModalClose" aria-label="Close Video">&times;</button>
            <div class="cmr-vmodal-aspect" id="cmrVideoModalFrameWrap"></div>
        </div>
    </div>

    <script id="cmr-video-modal-js">
    (function() {
        var overlay = document.getElementById('cmrVideoModalOverlay');
        var frameWrap = document.getElementById('cmrVideoModalFrameWrap');
        var closeBtn = document.getElementById('cmrVideoModalClose');

        if (!overlay || !frameWrap) return;

        function extractYouTubeId(url) {
            if (!url) return '';
            var regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
            var match = url.match(regExp);
            return (match && match[2].length === 11) ? match[2] : '';
        }

        function openVideoModal(videoId) {
            if (!videoId) return;
            frameWrap.innerHTML = '<iframe src="https://www.youtube.com/embed/' + encodeURIComponent(videoId) + '?autoplay=1&rel=0&modestbranding=1" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>';
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeVideoModal() {
            overlay.classList.remove('active');
            frameWrap.innerHTML = '';
            document.body.style.overflow = '';
        }

        if (closeBtn) {
            closeBtn.addEventListener('click', closeVideoModal);
        }

        overlay.addEventListener('click', function(e) {
            if (e.target === overlay) {
                closeVideoModal();
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && overlay.classList.contains('active')) {
                closeVideoModal();
            }
        });

        /**
         * Global Capturing Event Listener
         * Intercepts clicks at document root during capturing phase (useCapture = true).
         * This runs BEFORE Elementor's native jQuery handlers, preventing inline iframe creation
         * both on the live site and inside the Elementor Editor preview.
         */
        document.addEventListener('click', function(e) {
            // Never intercept clicks on/inside the modal overlay or its close button
            if (e.target.closest('#cmrVideoModalOverlay')) {
                return;
            }

            var widget = e.target.closest('[data-widget_type="video.default"], .elementor-widget-video, .elementor-element-9b2ddd6, .elementor-element-d713748');
            var playTrigger = e.target.closest('.elementor-custom-embed-play, .elementor-custom-embed-image-overlay, .elementor-open-inline, .elementor-video, .elementor-video-play-icon');
            var watchBtn = e.target.closest('a[href*="youtu.be"], a[href*="youtube.com/watch"], [data-id="93c603b"] a, [data-id="e8b38a1"] a, .cmr-video-btn');

            if (!widget && !watchBtn) {
                return;
            }

            // If it's a video widget, only intercept if clicking on the playTrigger / media area
            if (widget && !watchBtn && !playTrigger) {
                return;
            }

            var ytUrl = '';

            // 0. Facade thumbnail data attribute
            var facadeEl = e.target.closest('[data-cmr-yt-id]');
            if (facadeEl && facadeEl.dataset && facadeEl.dataset.cmrYtId) {
                ytUrl = 'https://youtu.be/' + facadeEl.dataset.cmrYtId;
            }

            // 1. Direct button link
            if (!ytUrl && watchBtn) {
                ytUrl = watchBtn.getAttribute('href') || '';
            }

            // 2. Video widget settings & fallbacks
            if (!ytUrl && widget) {
                var settingsStr = widget.getAttribute('data-settings');
                if (settingsStr) {
                    try {
                        var settings = JSON.parse(settingsStr);
                        ytUrl = settings.youtube_url || '';
                    } catch (err) {}
                }

                // Fallback: child anchor
                if (!ytUrl) {
                    var link = widget.querySelector('a[href*="youtu"]');
                    if (link && link.href) ytUrl = link.href;
                }

                // Fallback: companion CTA button in same card/column
                if (!ytUrl) {
                    var card = widget.closest('.elementor-top-column, .e-con, .e-con-inner, .elementor-column');
                    if (card) {
                        var cardLink = card.querySelector('a[href*="youtu"]');
                        if (cardLink && cardLink.href) ytUrl = cardLink.href;
                    }
                }

                // Fallback: known widget IDs
                if (!ytUrl) {
                    if (widget.classList.contains('elementor-element-9b2ddd6') || widget.dataset.id === '9b2ddd6') {
                        ytUrl = 'https://youtu.be/AOH5MO9Oim4';
                    } else if (widget.classList.contains('elementor-element-d713748') || widget.dataset.id === 'd713748') {
                        ytUrl = 'https://youtu.be/Nj79yA4cx9o';
                    }
                }
            }

            var vid = extractYouTubeId(ytUrl);
            if (vid) {
                // Intercept completely so Elementor's inline embed never executes
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();

                // Clean up any inline iframe that Elementor may have injected
                if (widget) {
                    var purgeIframes = function() {
                        var ifrs = widget.querySelectorAll('.elementor-video iframe, iframe');
                        ifrs.forEach(function(ifr) {
                            ifr.src = '';
                            ifr.remove();
                        });
                    };
                    purgeIframes();
                    setTimeout(purgeIframes, 30);
                    setTimeout(purgeIframes, 150);
                }

                openVideoModal(vid);
            }
        }, true); // useCapture = true is crucial!

        /**
         * Elementor Editor integration:
         * When Elementor initializes or re-renders video widgets in the preview,
         * unbind native Elementor inline click listeners on the image overlay.
         */
        function disableElementorNativeInlineVideo() {
            if (window.jQuery) {
                window.jQuery('.elementor-widget-video .elementor-custom-embed-image-overlay, .elementor-widget-video .elementor-custom-embed-play').off('click');
            }
        }

        if (window.elementorFrontend && window.elementorFrontend.hooks) {
            window.elementorFrontend.hooks.addAction('frontend/element_ready/video.default', function($scope) {
                if (window.jQuery && $scope) {
                    $scope.find('.elementor-custom-embed-image-overlay, .elementor-custom-embed-play').off('click');
                }
            });
        }

        window.addEventListener('load', disableElementorNativeInlineVideo);
        document.addEventListener('DOMContentLoaded', disableElementorNativeInlineVideo);
    })();
    </script>
    <?php
}


