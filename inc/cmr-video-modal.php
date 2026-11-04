<?php
/**
 * CMR Universal Video Popup (Lightbox Modal)
 * Ensures YouTube videos in Elementor video widgets (and action buttons)
 * open in a fast, lightweight modal popup on click instead of downloading 3.5MB scripts upfront.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'wp_footer', 'cmr_render_video_modal_html', 99 );
function cmr_render_video_modal_html() {
    ?>
    <style id="cmr-video-modal-css">
    .cmr-vmodal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(0, 0, 0, 0.88);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        z-index: 9999999;
        display: none;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
        padding: 20px;
        box-sizing: border-box;
    }
    .cmr-vmodal-overlay.active {
        display: flex;
        opacity: 1;
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

        // Intercept video clicks on Elementor Video widgets and card CTA buttons
        function bindVideoClicks() {
            // 1. Elementor Video widget overlay & play button
            var videoWidgets = document.querySelectorAll('[data-widget_type="video.default"], .elementor-widget-video');
            videoWidgets.forEach(function(widget) {
                if (widget.dataset.cmrModalBound) return;
                widget.dataset.cmrModalBound = 'true';

                widget.addEventListener('click', function(e) {
                    var settingsStr = widget.getAttribute('data-settings');
                    var ytUrl = '';
                    if (settingsStr) {
                        try {
                            var settings = JSON.parse(settingsStr);
                            ytUrl = settings.youtube_url || '';
                        } catch (err) {}
                    }
                    if (!ytUrl) {
                        var link = widget.querySelector('a');
                        if (link && link.href) ytUrl = link.href;
                    }

                    var vid = extractYouTubeId(ytUrl);
                    if (vid) {
                        e.preventDefault();
                        e.stopPropagation();
                        openVideoModal(vid);
                    }
                }, true); // Use capture phase so inline Elementor video embed is intercepted
            });

            // 2. Watch Insights / Play Episode buttons inside live video cards
            var videoButtons = document.querySelectorAll('[data-id="ec16651"] a, [data-id="ed5537e"] a, [data-id="93c603b"] a, [data-id="e8b38a1"] a, a[href*="youtu.be"], a[href*="youtube.com/watch"]');
            videoButtons.forEach(function(btn) {
                if (btn.dataset.cmrModalBound) return;
                var href = btn.getAttribute('href') || '';
                var vid = extractYouTubeId(href);
                if (vid) {
                    btn.dataset.cmrModalBound = 'true';
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        openVideoModal(vid);
                    });
                }
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', bindVideoClicks);
        } else {
            bindVideoClicks();
        }
        window.addEventListener('load', bindVideoClicks);
        document.addEventListener('elementor/frontend/init', bindVideoClicks);
    })();
    </script>
    <?php
}

