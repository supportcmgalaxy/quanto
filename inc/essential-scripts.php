<?php
/**
 * @Packge     : Quanto
 * @Version    : 1.0
 * @Author     : Mirrortheme
 * @Author URI : https://mirrortheme.com/
 *
 */

// Block direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Enqueue scripts and styles.
 */
function quanto_essential_scripts() {

	$quanto_theme_ver     = wp_get_theme()->get( 'Version' ) ?: '1.0.8';
	$quanto_style_path    = get_stylesheet_directory() . '/style.css';
	$quanto_style_version = file_exists( $quanto_style_path ) ? filemtime( $quanto_style_path ) : $quanto_theme_ver;
	wp_enqueue_style( 'quanto-style', get_stylesheet_uri(), array(), $quanto_style_version );
    wp_enqueue_style( 'cmr-mobile-menu-css', get_theme_file_uri( '/assets/css/cmr-mobile-menu.css' ), array(), $quanto_theme_ver );

    // google font
    wp_enqueue_style( 'quanto-fonts', quanto_google_fonts() ,array(), wp_get_theme()->get( 'Version' ) );

    // Bootstrap Style
    wp_enqueue_style( 'bootstrap-style', get_theme_file_uri( '/assets/css/bootstrap.min.css' ), array(), '5.3.3' );

    // Fontawesome Style
    wp_enqueue_style( 'fontawesome-style', get_theme_file_uri( '/assets/css/all.css' ), array(), '6.7.2' );

    // remixicon Style
    wp_enqueue_style( 'remixicon-style', get_theme_file_uri( '/assets/css/remixicon.css' ), array(), '2.0' );

    // Bootstrap Icons Style - Removed (0 icons used in the project, saves ~300KB font files)
    // wp_enqueue_style( 'bootstrap-icons-style', get_theme_file_uri( '/assets/css/bootstrap-icons.min.css' ), array(), '1.11.3' );

    // magnific popup Style
    wp_enqueue_style( 'magnific-popup-style', get_theme_file_uri( '/assets/css/magnific-popup.css' ), array(), $quanto_theme_ver );

    // meanmenu min Style
    wp_enqueue_style( 'meanmenu-min-style', get_theme_file_uri( '/assets/css/meanmenu.min.css' ), array(), '2.0.7' );

    // odometer Style
    wp_enqueue_style( 'odometer-style', get_theme_file_uri( '/assets/css/odometer.css' ), array(), $quanto_theme_ver );

    // swiper bundle min Style
    wp_enqueue_style( 'swiper-bundle-min-style', get_theme_file_uri( '/assets/css/swiper-bundle.min.css' ), array(), '7.0.8' );

    // Core Style
    wp_enqueue_style( 'quanto-core-style', get_theme_file_uri( '/assets/css/core.css' ), array(), '1.0' );

    // quanto app style
    wp_enqueue_style( 'quanto-main-style', get_theme_file_uri('/assets/css/style.css') ,array(), $quanto_theme_ver );
    wp_enqueue_style( 'quanto-blog-style', get_theme_file_uri('/assets/css/blog-default.css') ,array(), $quanto_theme_ver );
    
    // CMR News Style
    wp_enqueue_style( 'cmr-news-style', get_theme_file_uri('/assets/css/cmr-news.css') ,array(), $quanto_theme_ver );
    
    // CMR Common Modal Styles
    wp_enqueue_style( 'cmr-modal-style', get_theme_file_uri('/assets/css/cmr-modal.css'), array(), $quanto_theme_ver );
    if ( function_exists( 'is_cart' ) && ( is_cart() || is_checkout() || is_account_page() ) ) {
        wp_enqueue_style( 'cmr-checkout-block', get_theme_file_uri('/assets/css/cmr-checkout-block.css') ,array(), $quanto_theme_ver );
    }
    wp_register_style( 'cmr-latest-insights', get_theme_file_uri('/assets/css/cmr-latest-insights.css'), array(), $quanto_theme_ver );
    wp_enqueue_style( 'cmr-industry-intelligence', get_theme_file_uri('/assets/css/cmr-industry-intelligence.css'), array(), $quanto_theme_ver );
    wp_register_style( 'cmr-explore-sectors', get_theme_file_uri('/assets/css/cmr-explore-sectors.css'), array(), $quanto_theme_ver );
    wp_register_style( 'cmr-stay-updated', get_theme_file_uri('/assets/css/cmr-stay-updated.css'), array(), $quanto_theme_ver );

    // Enqueue homepage Elementor CSS anywhere we inject homepage Elementor sections,
    // including WooCommerce product pages rendered via custom template helpers.
    if ( ( is_home() || is_archive() || is_page() || is_singular( array( 'post', 'cmr_news', 'cmr_media_release', 'cmr_quarterly', 'product' ) ) ) && class_exists( '\\Elementor\\Core\\Files\\CSS\\Post' ) ) {
        $homepage_id = get_option( 'page_on_front' );
        if ( ! $homepage_id ) {
            $homepage_id = 14; // Fallback
        }

        if ( function_exists( 'quanto_enqueue_elementor_post_assets' ) ) {
            quanto_enqueue_elementor_post_assets( $homepage_id );
            
            // Only enqueue Similar Reports page assets on single report/product pages
            if ( is_singular( array( 'product', 'cmr_media_release', 'cmr_quarterly' ) ) ) {
                $target_page = get_page_by_path( 'similar-reports-by-industry' );
                if ( ! $target_page ) {
                    $target_page = get_page_by_path( 'test' );
                }
                if ( $target_page ) {
                    quanto_enqueue_elementor_post_assets( $target_page->ID );
                }
            }
        } else {
            $upload_dir = wp_upload_dir();
            if ( ! empty( $upload_dir['basedir'] ) ) {
                $css_path = trailingslashit( $upload_dir['basedir'] ) . 'elementor/css/';
                $css_url  = trailingslashit( $upload_dir['baseurl'] ) . 'elementor/css/';

                // 1. Enqueue active kit CSS
                $active_kit_id = get_option( 'elementor_active_kit' );
                if ( $active_kit_id ) {
                    $kit_file = 'post-' . $active_kit_id . '.css';
                    if ( file_exists( $css_path . $kit_file ) ) {
                        wp_enqueue_style( 'elementor-post-' . $active_kit_id, $css_url . $kit_file, array(), null );
                    }
                }

                // 2. Enqueue homepage post CSS
                $css_file = new \Elementor\Core\Files\CSS\Post( $homepage_id );
                $css_file->enqueue();

                // 3. Enqueue responsive/optimized styles if present
                $devices = array( 'desktop', 'laptop', 'tablet', 'mobile' );
                
                // base-*.css (responsive layout defaults)
                foreach ( $devices as $device ) {
                    $base_file = 'base-' . $device . '.css';
                    if ( file_exists( $css_path . $base_file ) ) {
                        wp_enqueue_style( 'base-' . $device, $css_url . $base_file, array(), null );
                    }
                }

                // local-[homepage_id]-frontend-*.css (homepage responsive overrides)
                foreach ( $devices as $device ) {
                    $local_file = 'local-' . $homepage_id . '-frontend-' . $device . '.css';
                    if ( file_exists( $css_path . $local_file ) ) {
                        wp_enqueue_style( 'local-' . $homepage_id . '-frontend-' . $device, $css_url . $local_file, array(), null );
                    }
                }
            }
        }
        
        // Aggressively force Elementor to load all core layout styles so the footer doesn't break
        if ( class_exists( '\Elementor\Plugin' ) ) {
            wp_enqueue_style( 'elementor-frontend' );
            wp_enqueue_style( 'elementor-icons' );
            wp_enqueue_style( 'e-flexbox' );
            wp_enqueue_style( 'e-container' );
            wp_enqueue_style( 'elementor-widget-heading' );
            wp_enqueue_style( 'elementor-widget-text-editor' );
            wp_enqueue_style( 'elementor-widget-icon-list' );
            wp_enqueue_style( 'elementor-widget-image' );
            wp_enqueue_style( 'elementor-widget-button' );
            wp_enqueue_style( 'elementor-widget-divider' );
            wp_enqueue_style( 'elementor-widget-spacer' );
        }
    }



    // Load Js
    
    // Bootstrap
    wp_enqueue_script( 'bootstrap-bundle', get_theme_file_uri( '/assets/js/bootstrap.bundle.min.js' ), array( 'jquery' ), '5.3.3', true );

    // jquery mixitup (avoid double load if already enqueued)
    if ( ! wp_script_is( 'mixitup', 'enqueued' ) ) {
        wp_enqueue_script( 'jquery-mixitup', get_theme_file_uri( '/assets/js/jquery.mixitup.min.js' ), array('jquery'), '2.1.11', true );
    }

    // swiper bundle
    wp_enqueue_script( 'swiper-bundle', get_theme_file_uri( '/assets/js/swiper-bundle.min.js' ), array('jquery'), '7.0.8', true );

    // magnific popup (avoid double load if already enqueued)
    if ( ! wp_script_is( 'magnific-popup', 'enqueued' ) ) {
        wp_enqueue_script( 'magnific-popup', get_theme_file_uri( '/assets/js/jquery.magnific-popup.min.js' ), array('jquery'), '1.1.0', true );
    }

    // Odometer JS
    wp_enqueue_script( 'odometer-min-script', get_theme_file_uri( '/assets/js/odometer.min.js' ), array( 'jquery' ), '0.4.8', true );
    wp_enqueue_script( 'viewport-jquery-script', get_theme_file_uri( '/assets/js/viewport.jquery.js' ), array('jquery'), $quanto_theme_ver, true );

    // Meanmenu JS
    wp_enqueue_script( 'jquery-meanmenu', get_theme_file_uri( '/assets/js/jquery.meanmenu.min.js' ), array('jquery'), $quanto_theme_ver, true );

    // gsap JS: only enqueue theme copy if mas-animation plugin has not loaded it
    if ( ! wp_script_is( 'gsap', 'enqueued' ) ) {
        wp_enqueue_script( 'gsap-script', get_theme_file_uri( '/assets/js/gsap.js' ), array( 'jquery' ), '3.11.4', true );
        wp_enqueue_script( 'gsap-scroll-smoother-script', get_theme_file_uri( '/assets/js/gsap-scroll-smoother.js' ), array( 'jquery' ), '3.11.4', true );
        wp_enqueue_script( 'gsap-scroll-to-plugin-script', get_theme_file_uri( '/assets/js/gsap-scroll-to-plugin.js' ), array( 'jquery' ), '3.11.4', true );
        wp_enqueue_script( 'gsap-scroll-trigger-script', get_theme_file_uri( '/assets/js/gsap-scroll-trigger.js' ), array( 'jquery' ), '3.11.4', true );
        wp_enqueue_script( 'gsap-split-text-script', get_theme_file_uri( '/assets/js/gsap-split-text.js' ), array( 'jquery' ), '3.11.2', true );
    }

    // main script
    wp_enqueue_script( 'quanto-main-script', get_theme_file_uri( '/assets/js/main.js' ), array('jquery'), $quanto_theme_ver, true );
    
    // comment reply
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'quanto_essential_scripts', 99 );

/**
 * High-Priority Asset Optimization & Deduplication Hook
 * Removes duplicate GSAP/Animation scripts and unneeded WooCommerce assets on the Homepage
 */
add_action( 'wp_enqueue_scripts', 'quanto_optimize_homepage_performance', 999 );
function quanto_optimize_homepage_performance() {
    // 1. Deduplicate GSAP scripts if mas-animation plugin already loaded GSAP
    if ( wp_script_is( 'gsap', 'enqueued' ) ) {
        wp_dequeue_script( 'gsap-script' );
        wp_dequeue_script( 'gsap-scroll-smoother-script' );
        wp_dequeue_script( 'gsap-scroll-to-plugin-script' );
        wp_dequeue_script( 'gsap-scroll-trigger-script' );
        wp_dequeue_script( 'gsap-split-text-script' );
    }
    if ( wp_script_is( 'mixitup', 'enqueued' ) ) {
        wp_dequeue_script( 'jquery-mixitup' );
    }

    // 2. Conditional WooCommerce asset loading: On Homepage, dequeue unused cart/product scripts
    if ( ( is_front_page() || is_home() ) && class_exists( 'WooCommerce' ) ) {
        wp_dequeue_style( 'woocommerce-layout' );
        wp_dequeue_style( 'woocommerce-smallscreen' );
        wp_dequeue_style( 'woocommerce_frontend_styles' );
        wp_dequeue_script( 'wc-add-to-cart' );
        wp_dequeue_script( 'zoom' );
        wp_dequeue_script( 'flexslider' );
        wp_dequeue_script( 'photoswipe' );
        wp_dequeue_script( 'photoswipe-ui-default' );
    }
}

/**
 * Defer non-critical scripts for faster First Contentful Paint (FCP)
 */
add_filter( 'script_loader_tag', 'quanto_defer_non_critical_scripts', 10, 2 );
function quanto_defer_non_critical_scripts( $tag, $handle ) {
    // Do not defer in admin or Elementor editor
    if ( is_admin() || ( function_exists( '\Elementor\Plugin' ) && isset( \Elementor\Plugin::$instance ) && \Elementor\Plugin::$instance->editor && \Elementor\Plugin::$instance->editor->is_edit_mode() ) ) {
        return $tag;
    }

    $defer_handles = array(
        'odometer-min-script',
        'viewport-jquery-script',
        'cmr-cart-badge-sync',
        'swiper-bundle',
        'bootstrap-bundle',
        'quanto-main-script',
        'jquery-meanmenu',
    );

    if ( in_array( $handle, $defer_handles, true ) && false === strpos( $tag, 'defer' ) ) {
        return str_replace( ' src', ' defer="defer" src', $tag );
    }

    return $tag;
}


function quanto_block_editor_assets( ) {
    // Add custom fonts.
	wp_enqueue_style( 'quanto-editor-fonts', quanto_google_fonts(), array(), null );
}

add_action( 'enqueue_block_editor_assets', 'quanto_block_editor_assets' );
 
function quanto_google_fonts() {
    $font_families = array(
        'Instrument Sans:400,500,600,700','800','900',
    );

    $familyArgs = array(
        'family' => urlencode( implode( '|', $font_families ) ),
        'subset' => urlencode( 'latin,latin-ext' ),
    );

    $fontUrl = add_query_arg( $familyArgs, '//fonts.googleapis.com/css' );

    return esc_url_raw( $fontUrl );
}

/**
 * Lightweight Modal Trigger & Scroll Lock Controller + HTML Entity Decoder
 */
add_action( 'wp_footer', function() {
    ?>
    <script id="cmr-modal-controller-js">
    (function() {
        // Update Modal Headings & Texts
        function updateCMRModalTexts() {
            // Do not override headings while inside Elementor editor
            if (document.body && (document.body.classList.contains('elementor-editor-active') || document.body.classList.contains('elementor-editor-preview'))) {
                return;
            }

            var modalContainers = document.querySelectorAll(
                '.elementor-popup-modal, ' +
                '.elementor-7832, .elementor-7849, ' +
                '[data-elementor-id="7832"], [data-elementor-id="7849"], ' +
                '.dialog-widget-content, ' +
                '.dialog-lightbox-widget-content, ' +
                '.custom-subscribe-form, ' +
                '.dialog-widget, ' +
                '[data-elementor-type="popup"]'
            );


            // 3. Talk to Analyst modal heading -> "Request a Consultation"
            document.querySelectorAll(
                '.elementor-7637 .elementor-heading-title, ' +
                '[data-elementor-id="7637"] .elementor-heading-title, ' +
                '.elementor-element-b034026 .elementor-heading-title'
            ).forEach(function(el) {
                el.textContent = 'Request a Consultation';
            });

            modalContainers.forEach(function(modal) {
                try {
                    var walker = document.createTreeWalker(modal, NodeFilter.SHOW_TEXT, null, false);
                    var node;
                    while (node = walker.nextNode()) {
                        if (node.nodeValue && node.nodeValue.indexOf('CMR Pulse') !== -1) {
                            node.nodeValue = node.nodeValue.replace(/CMR Pulse/g, 'CMR GTM');
                        }
                    }
                } catch(e) {}

                modal.querySelectorAll('input[type="checkbox"]').forEach(function(input) {
                    if (input.value === 'CMR Pulse') {
                        input.value = 'CMR GTM';
                    }
                });

                // Restore missing icons in modal list items if empty
                modal.querySelectorAll('.elementor-icon-list-item').forEach(function(item) {
                    var iconSpan = item.querySelector('.elementor-icon-list-icon');
                    var textEl = item.querySelector('.elementor-icon-list-text');
                    if (!iconSpan || !textEl) return;
                    
                    var text = textEl.textContent.trim().toLowerCase();
                    if (!iconSpan.querySelector('svg') && !iconSpan.querySelector('i')) {
                        if (text.indexOf('key market') !== -1 || text.indexOf('trends') !== -1) {
                            iconSpan.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="12" viewBox="0 0 20 12" fill="none"><path d="M1.4 12L0 10.6L7.4 3.15L11.4 7.15L16.6 2H14V0H20V6H18V3.4L11.4 10L7.4 6L1.4 12Z" fill="white"></path></svg>';
                        } else if (text.indexOf('actionable') !== -1 || text.indexOf('insights') !== -1) {
                            iconSpan.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="20" viewBox="0 0 15 20" fill="none"><path d="M7.5 20C6.95 20 6.47917 19.8042 6.0875 19.4125C5.69583 19.0208 5.5 18.55 5.5 18H9.5C9.5 18.55 9.30417 19.0208 8.9125 19.4125C8.52083 19.8042 8.05 20 7.5 20ZM3.5 17V15H11.5V17H3.5ZM3.75 14C2.6 13.3167 1.6875 12.4 1.0125 11.25C0.3375 10.1 0 8.85 0 7.5C0 5.41667 0.729167 3.64583 2.1875 2.1875C3.64583 0.729167 5.41667 0 7.5 0C9.58333 0 11.3542 0.729167 12.8125 2.1875C14.2708 3.64583 15 5.41667 15 7.5C15 8.85 14.6625 10.1 13.9875 11.25C13.3125 12.4 12.4 13.3167 11.25 14H3.75ZM4.35 12H10.65C11.4 11.4667 11.9792 10.8083 12.3875 10.025C12.7958 9.24167 13 8.4 13 7.5C13 5.96667 12.4667 4.66667 11.4 3.6C10.3333 2.53333 9.03333 2 7.5 2C5.96667 2 4.66667 2.53333 3.6 3.6C2.53333 4.66667 2 5.96667 2 7.5C2 8.4 2.20417 9.24167 2.6125 10.025C3.02083 10.8083 3.6 11.4667 4.35 12Z" fill="white"></path></svg>';
                        } else if (text.indexOf('industry') !== -1 || text.indexOf('analysis') !== -1) {
                            iconSpan.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="18" viewBox="0 0 20 18" fill="none"><path d="M0 18V0H10V4H20V18H0ZM2 16H4V14H2V16ZM2 12H4V10H2V12ZM2 8H4V6H2V8ZM2 4H4V2H2V4ZM6 16H8V14H6V16ZM6 12H8V10H6V12ZM6 8H8V6H6V8ZM6 4H8V2H6V4ZM10 16H18V6H10V8H12V10H10V12H12V14H10V16ZM14 10V8H16V10H14ZM14 14V12H16V14H14Z" fill="white"></path></svg>';
                        }
                    }
                });
            });
        }

        var updateCMRModalDebounce = null;
        function scheduleCMRModalUpdate() {
            if (updateCMRModalDebounce) return;
            updateCMRModalDebounce = requestAnimationFrame(function() {
                updateCMRModalDebounce = null;
                updateCMRModalTexts();
            });
        }

        /**
         * reCAPTCHA Timeout Fix:
         * The plugin's recaptchaCallback renders ALL captchas on page load, including those
         * inside hidden Elementor popup containers (display:none). Google's API times out
         * trying to initialize iframes inside zero-size hidden elements.
         *
         * Fix: Override recaptchaCallback to temporarily detach hidden-popup captchas from
         * the DOM before the plugin renders, mark them 'data-cmr-deferred', then re-attach.
         * They get rendered properly only when the popup opens via cmrInitRecaptchas().
         */
        (function cmrPatchRecaptcha() {
            // This runs in wp_footer priority 999, after footer scripts (priority 20)
            // so window.recaptchaCallback is already defined by the plugin.
            var _orig = window.recaptchaCallback;
            if (typeof _orig !== 'function') return;

            window.recaptchaCallback = function() {
                // Find captchas inside hidden popup containers
                var detached = [];
                var forms = document.getElementsByTagName('form');
                for (var i = 0; i < forms.length; i++) {
                    var captchas = forms[i].getElementsByClassName('wpcf7-recaptcha');
                    // Copy to array since we'll detach elements (live HTMLCollection mutates)
                    var captchaArr = Array.prototype.slice.call(captchas);
                    for (var j = 0; j < captchaArr.length; j++) {
                        var el = captchaArr[j];
                        if (!el.getAttribute('data-sitekey')) continue;
                        var popup = el.closest
                            ? el.closest('.elementor-popup-modal, .dialog-widget, [data-elementor-type="popup"]')
                            : null;
                        if (popup) {
                            var cs = window.getComputedStyle(popup);
                            if (cs.display === 'none' || popup.classList.contains('dialog-hidden')) {
                                // Temporarily remove from DOM so the plugin skips it
                                var placeholder = document.createComment('cmr-recaptcha-deferred');
                                if (el.parentNode) {
                                    el.parentNode.insertBefore(placeholder, el);
                                    el.parentNode.removeChild(el);
                                    el.setAttribute('data-cmr-deferred', 'true');
                                    detached.push({ el: el, ph: placeholder });
                                }
                            }
                        }
                    }
                }

                // Call original: only visible (non-popup) captchas remain in DOM now
                try { _orig(); } catch(e) {}

                // Re-attach deferred captchas to their original positions
                for (var k = 0; k < detached.length; k++) {
                    var item = detached[k];
                    if (item.ph.parentNode) {
                        item.ph.parentNode.insertBefore(item.el, item.ph);
                        item.ph.parentNode.removeChild(item.ph);
                    }
                }
            };

            // Reset our deferred captchas on form submit (plugin only resets its own tracked ones)
            document.addEventListener('wpcf7submit', function(event) {
                var s = event.detail && event.detail.status;
                if (s === 'spam' || s === 'mail_sent' || s === 'mail_failed') {
                    document.querySelectorAll('.wpcf7-recaptcha[data-cmr-rendered="true"]').forEach(function(rc) {
                        var wid = rc._grecaptchaWidgetId;
                        if (typeof grecaptcha !== 'undefined' && wid !== undefined && wid !== null) {
                            try { grecaptcha.reset(wid); } catch(e) {}
                        }
                    });
                }
            }, false);
        })();

        /**
         * Render deferred reCAPTCHA widgets inside a popup container.
         * Called after popup becomes visible so Google API can size the iframe correctly.
         */
        function cmrInitRecaptchas(container) {
            if (typeof grecaptcha === 'undefined' || typeof grecaptcha.render !== 'function') return;

            var scope = container || document;
            var items = scope.querySelectorAll('.wpcf7-recaptcha');

            items.forEach(function(el) {
                // Skip if already rendered
                if (el.getAttribute('data-cmr-rendered') === 'true') return;
                if (el.querySelector('iframe')) return;

                var sitekey = el.getAttribute('data-sitekey');
                if (!sitekey) return;

                el.setAttribute('data-cmr-rendered', 'true');
                el.removeAttribute('data-cmr-deferred');

                var params = {
                    'sitekey': sitekey,
                    'type':     el.getAttribute('data-type'),
                    'size':     el.getAttribute('data-size')  || 'normal',
                    'theme':    el.getAttribute('data-theme') || 'light',
                    'align':    el.getAttribute('data-align'),
                    'badge':    el.getAttribute('data-badge'),
                    'tabindex': el.getAttribute('data-tabindex')
                };

                var cb = el.getAttribute('data-callback');
                if (cb && typeof window[cb] === 'function') params['callback'] = window[cb];

                var ecb = el.getAttribute('data-expired-callback');
                if (ecb && typeof window[ecb] === 'function') params['expired-callback'] = window[ecb];

                try {
                    var widgetId = grecaptcha.render(el, params);
                    el._grecaptchaWidgetId = widgetId;
                    el.setAttribute('data-widget-id', widgetId);
                } catch(e) {
                    // Render failed — restore deferred state for next popup open
                    el.removeAttribute('data-cmr-rendered');
                    el.setAttribute('data-cmr-deferred', 'true');
                }
            });
        }

        // Universal modal scroll lock checker (Event-driven, 0 CPU overhead)
        function checkActiveModals() {
            updateCMRModalTexts();
            var active = false;

            // Elementor Popups
            var elemModals = document.querySelectorAll('.dialog-widget.dialog-type-lightbox, .elementor-popup-modal');
            for (var i = 0; i < elemModals.length; i++) {
                var el = elemModals[i];
                if (el.style.display !== 'none' && !el.classList.contains('dialog-hidden') && window.getComputedStyle(el).display !== 'none') {
                    active = true;
                    break;
                }
            }

            // Bootstrap
            if (!active) {
                var bs = document.querySelectorAll('.modal.show');
                if (bs.length > 0) active = true;
            }

            // Magnific Popup
            if (!active) {
                var mfp = document.querySelectorAll('.mfp-wrap.mfp-ready');
                if (mfp.length > 0) active = true;
            }

            // CMR Review Modal
            if (!active) {
                var cr1 = document.getElementById('cmr-review-modal-overlay');
                if (cr1 && cr1.classList.contains('cmr-open')) active = true;
                var cr2 = document.getElementById('cmr-review-modal');
                if (cr2 && cr2.style.display === 'flex') active = true;
            }

            if (active) {
                document.documentElement.classList.add('cmr-modal-open');
                document.body.classList.add('cmr-modal-open');
            } else {
                document.documentElement.classList.remove('cmr-modal-open');
                document.body.classList.remove('cmr-modal-open');
            }
        }

        // Auto-decode any escaped HTML in process boxes (e.g. &lt;br&gt;&lt;a...&gt;)
        function fixProcessDescriptionHTML() {
            var descriptions = document.querySelectorAll('.process-description, .quanto-process-box p, .explore-sectors-track .sector-desc');
            descriptions.forEach(function(el) {
                var html = el.innerHTML;
                if (html.indexOf('&lt;') !== -1 || html.indexOf('&gt;') !== -1) {
                    var txt = document.createElement('textarea');
                    txt.innerHTML = html;
                    el.innerHTML = txt.value;
                }
            });
        }

        // Helper to extract popup ID from Elementor action URLs
        function extractPopupId(href) {
            if (!href) return null;
            try {
                var decoded = decodeURIComponent(href);
                var match = decoded.match(/settings=([A-Za-z0-9+/=]+)/);
                if (match && match[1]) {
                    var jsonStr = atob(match[1]);
                    var parsed = JSON.parse(jsonStr);
                    if (parsed && parsed.id) return parseInt(parsed.id);
                }
                var idMatch = decoded.match(/id["':=\s]+(\d+)/);
                if (idMatch && idMatch[1]) return parseInt(idMatch[1]);
            } catch(e) {}
            return null;
        }

        document.addEventListener('DOMContentLoaded', function() {
            fixProcessDescriptionHTML();

            // Function to close all modals cleanly
            function closeAllModals(e) {
                document.documentElement.classList.remove('cmr-modal-open');
                document.body.classList.remove('cmr-modal-open');
                
                if (typeof elementorProFrontend !== 'undefined' && elementorProFrontend.modules && elementorProFrontend.modules.popup) {
                    try {
                        elementorProFrontend.modules.popup.closePopup({}, e);
                    } catch(err) {}
                }

                document.querySelectorAll('.dialog-widget.dialog-type-lightbox, .elementor-popup-modal').forEach(function(el) {
                    el.style.display = 'none';
                    el.style.opacity = '0';
                });

                var crOverlay = document.getElementById('cmr-review-modal-overlay');
                if (crOverlay) crOverlay.classList.remove('cmr-open');
                var crModal = document.getElementById('cmr-review-modal');
                document.querySelectorAll('.cmr-keyboard-active').forEach(function(el) {
                    el.classList.remove('cmr-keyboard-active');
                });
                document.body.classList.remove('cmr-keyboard-active');

                setTimeout(checkActiveModals, 30);
            }

            // Function to open popup by ID cleanly and reliably
            function openPopup(id, triggerEl) {
                id = parseInt(id);
                if (!id) return;

                // Remove previous hide overrides on target popup
                var targets = document.querySelectorAll(
                    '.elementor-popup-modal[data-elementor-id="' + id + '"], ' +
                    '.dialog-widget[data-elementor-id="' + id + '"], ' +
                    '#elementor-popup-modal-' + id + ', ' +
                    '.elementor-' + id
                );
                targets.forEach(function(el) {
                    el.style.removeProperty('display');
                    el.style.removeProperty('opacity');
                    el.style.removeProperty('visibility');
                    el.scrollTop = 0;
                    el.classList.remove('cmr-keyboard-active');
                });
                document.body.classList.remove('cmr-keyboard-active');

                var opened = false;
                if (typeof elementorProFrontend !== 'undefined' && elementorProFrontend.modules) {
                    // Primary: popup.showPopup is the most reliable method and supports re-opening
                    if (elementorProFrontend.modules.popup) {
                        try {
                            elementorProFrontend.modules.popup.showPopup({ id: id, toggle: false });
                            opened = true;
                        } catch(err) {
                            console.error(err);
                        }
                    }
                    // Fallback: actionHash (can silently fail on re-open)
                    if (!opened && elementorProFrontend.modules.actionHash) {
                        try {
                            var actionUrl = 'elementor-action:action=popup:open&settings=' + btoa(JSON.stringify({ id: id.toString(), toggle: false }));
                            elementorProFrontend.modules.actionHash.runAction(actionUrl);
                            opened = true;
                        } catch(err) {}
                    }
                }

                if (window.jQuery) {
                    try {
                        window.jQuery(document).trigger('elementor/popup/show', [{ id: id }]);
                    } catch(e) {}
                }

                var modal = document.querySelector('#elementor-popup-modal-' + id) || document.querySelector('.elementor-' + id + '.elementor-location-popup');
                if (modal) {
                    var dialogWidget = modal.closest('.dialog-widget') || modal;
                    dialogWidget.style.setProperty('display', 'flex', 'important');
                    dialogWidget.style.setProperty('opacity', '1', 'important');
                    dialogWidget.style.setProperty('visibility', 'visible', 'important');
                    dialogWidget.style.setProperty('z-index', '999999', 'important');
                }

                document.documentElement.classList.add('cmr-modal-open');
                document.body.classList.add('cmr-modal-open');
                setTimeout(checkActiveModals, 50);
            }

            // Expose globally so onclick handlers can call it directly
            window.cmrOpenPopup = openPopup;
            window.cmrCloseAllModals = closeAllModals;

            // Helper: Setup Hero CTAs ("Explore Insights" scroll & "Connect with us" popup)
            function initHeroCTAButtons() {
                var talkButtons = document.querySelectorAll(
                    '.talk-btn .elementor-button-text, ' +
                    '.elementor-element-d2bb779 .elementor-button-text, ' +
                    '.elementor-element-ecd03c0 .elementor-button-text, ' +
                    '#smooth-content .talk-btn .elementor-button-text, ' +
                    '.hero .btn-outline'
                );
                
                talkButtons.forEach(function(btnText) {
                    var txt = btnText.textContent.trim().toLowerCase();
                    if (txt.includes('talk to') || txt.includes('analyst') || txt.includes('download latest report')) {
                        // Check if it's not the main header button
                        var inHeader = btnText.closest('header, [data-elementor-type="header"], #quanto-header-desktop');
                        if (!inHeader) {
                            btnText.textContent = 'Connect with us';
                        }
                    }
                });

                document.querySelectorAll('.elementor-button-text').forEach(function(btnText) {
                    var inHeader = btnText.closest('header, [data-elementor-type="header"], #quanto-header-desktop');
                    if (inHeader) return;
                    var txt = btnText.textContent.trim().toLowerCase();
                    if (txt === 'talk to our analyst' || txt === 'talk to analyst') {
                        btnText.textContent = 'Connect with us';
                    }
                });
            }

            // Helper: Replace unicode arrow on Submit buttons with the exact Talk to Analyst SVG arrow icon
            function fixSubmitButtonArrows() {
                var svgHTML = '<svg class="cmr-arrow-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 16 14" fill="none" style="display:inline-block; vertical-align:middle; margin-left:6px; width:15px; height:13px;"><path d="M2.04895 4.52205V3.54978C2.07104 3.12993 2.4025 2.79848 2.80025 2.79848L11.396 2.77638C11.8159 2.79848 12.1473 3.12993 12.1473 3.52768V12.1455C12.1473 12.5433 11.8159 12.8747 11.396 12.8968H10.4237C10.0039 12.8747 9.67244 12.5433 9.65035 12.1234L9.78293 6.90853L3.44106 13.2504C3.1317 13.5598 2.68976 13.5598 2.3804 13.2504L1.6733 12.5433C1.38603 12.256 1.36394 11.792 1.6733 11.4826L8.01516 5.14077L2.82234 5.29545C2.4025 5.27335 2.04895 4.96399 2.04895 4.52205Z" fill="currentColor"></path></svg>';

                // 1. Check all buttons with unicode arrow
                var buttons = document.querySelectorAll(
                    'button[type="submit"], ' +
                    '.elementor-field-type-submit button, ' +
                    '.elementor-button[type="submit"], ' +
                    '.wpcf7-submit, ' +
                    'button.cmr-arrow-fixed, ' +
                    '.custom-consultation-form button, ' +
                    '.elementor-button'
                );

                buttons.forEach(function(btn) {
                    if (btn.classList.contains('cmr-arrow-fixed')) return;

                    var textWrapper = btn.querySelector('.elementor-button-text') || btn.querySelector('span') || btn;
                    var fullText = (textWrapper.textContent || '').trim();

                    if (fullText.includes('↗') || fullText.includes('\u2197') || fullText.includes('&#8599;') || fullText.includes('&#x2197;')) {
                        var cleanText = fullText.replace(/[↗\u2197]/g, '').trim();

                        var existingIcon = btn.querySelector('.elementor-button-icon, i, svg:not(.cmr-arrow-icon)');
                        if (existingIcon) {
                            existingIcon.remove();
                        }

                        if (textWrapper !== btn) {
                            textWrapper.textContent = cleanText;
                        } else {
                            btn.textContent = cleanText;
                        }

                        if (!btn.querySelector('.cmr-arrow-icon')) {
                            var iconSpan = document.createElement('span');
                            iconSpan.className = 'elementor-button-icon cmr-button-icon-arrow';
                            iconSpan.style.display = 'inline-flex';
                            iconSpan.style.alignItems = 'center';
                            iconSpan.style.marginLeft = '6px';
                            iconSpan.innerHTML = svgHTML;

                            var contentWrap = btn.querySelector('.elementor-button-content-wrapper') || btn;
                            contentWrap.appendChild(iconSpan);
                        }

                        btn.classList.add('cmr-arrow-fixed');
                    }
                });

                // 2. Check input[type="submit"]
                document.querySelectorAll('input[type="submit"]').forEach(function(input) {
                    if (input.classList.contains('cmr-arrow-fixed')) return;
                    var val = input.value || '';
                    if (val.includes('↗') || val.includes('\u2197')) {
                        var btn = document.createElement('button');
                        btn.type = 'submit';
                        btn.className = input.className + ' cmr-arrow-fixed';
                        if (input.id) btn.id = input.id;
                        if (input.name) btn.name = input.name;
                        var cleanVal = val.replace(/[↗\u2197]/g, '').trim();
                        btn.innerHTML = '<span class="elementor-button-content-wrapper" style="display:inline-flex; align-items:center; justify-content:center;"><span class="elementor-button-text">' + cleanVal + '</span> <span class="cmr-button-icon-arrow" style="display:inline-flex; align-items:center; margin-left:6px;">' + svgHTML + '</span></span>';
                        input.parentNode.replaceChild(btn, input);
                    }
                });
            }

            // Helper: Ensure "More Details" links always have the diagonal arrow SVG icon
            function fixMoreDetailsArrows() {
                var arrowSvg = '<svg class="cmr-wwt-arrow" xmlns="http://www.w3.org/2000/svg" width="13" height="11" viewBox="0 0 16 14" fill="none" style="display:inline-block; vertical-align:middle; margin-left:6px;"><path d="M2.04895 4.52205V3.54978C2.07104 3.12993 2.4025 2.79848 2.80025 2.79848L11.396 2.77638C11.8159 2.79848 12.1473 3.12993 12.1473 3.52768V12.1455C12.1473 12.5433 11.8159 12.8747 11.396 12.8968H10.4237C10.0039 12.8747 9.67244 12.5433 9.65035 12.1234L9.78293 6.90853L3.44106 13.2504C3.1317 13.5598 2.68976 13.5598 2.3804 13.2504L1.6733 12.5433C1.38603 12.256 1.36394 11.792 1.6733 11.4826L8.01516 5.14077L2.82234 5.29545C2.4025 5.27335 2.04895 4.96399 2.04895 4.52205Z" fill="currentColor"></path></svg>';

                document.querySelectorAll('.cmr-wwt-card-link, .ez-card-link').forEach(function(link) {
                    if (!link.querySelector('svg')) {
                        link.insertAdjacentHTML('beforeend', ' ' + arrowSvg);
                    }
                });
            }

            initHeroCTAButtons();
            fixSubmitButtonArrows();
            fixMoreDetailsArrows();
            setTimeout(initHeroCTAButtons, 400);
            setTimeout(fixSubmitButtonArrows, 400);
            setTimeout(fixMoreDetailsArrows, 400);
            setTimeout(initHeroCTAButtons, 1200);
            setTimeout(fixSubmitButtonArrows, 1200);
            setTimeout(fixMoreDetailsArrows, 1200);
            setTimeout(fixSubmitButtonArrows, 2500);
            setTimeout(fixMoreDetailsArrows, 2500);

            // MutationObserver to catch dynamically rendered popups/forms
            if (window.MutationObserver) {
                var observer = new MutationObserver(function() {
                    fixSubmitButtonArrows();
                    fixMoreDetailsArrows();
                });
                observer.observe(document.body, { childList: true, subtree: true });
            }

            // Unified click handler for all popups & hero CTAs
            document.body.addEventListener('click', function(e) {
                // 1. Check for "Connect to Analyst" / "Connect with us" / Talk to Analyst CTA (open popup 7637)
                var connectTrigger = e.target.closest('.talk-btn, .elementor-element-d2bb779, .elementor-element-ecd03c0, .custom-talk-analyst-btn, [data-popup-id="7637"], .open-popup');
                if (!connectTrigger) {
                    var btnEl = e.target.closest('.elementor-button, button, a');
                    if (btnEl) {
                        var bTxt = (btnEl.textContent || '').trim().toLowerCase();
                        if (bTxt.includes('connect to analyst') || bTxt.includes('talk to analyst') || bTxt.includes('connect with us') || bTxt.includes('talk to our analyst')) {
                            connectTrigger = btnEl;
                        }
                    }
                }

                if (connectTrigger) {
                    var inHeader = connectTrigger.closest('header, [data-elementor-type="header"], #quanto-header-desktop');
                    if (!inHeader) {
                        e.preventDefault();
                        e.stopPropagation();
                        openPopup(7637, connectTrigger);
                        return;
                    }
                }

                // 2. Check for "Explore Insights" CTA (smooth scroll to Latest Insights)
                var exploreTrigger = e.target.closest('.elementor-element-37736ef, .elementor-element-b44d429 .download-btn, .elementor-element-758e182 .download-btn, .hero .download-btn');
                if (!exploreTrigger) {
                    var btnEl2 = e.target.closest('.elementor-button, button, a');
                    if (btnEl2 && !btnEl2.classList.contains('open-popup') && !btnEl2.hasAttribute('data-popup-id') && !btnEl2.classList.contains('cmr-cta-btn')) {
                        var bTxt2 = (btnEl2.textContent || '').trim().toLowerCase();
                        if (bTxt2.includes('explore insights') || bTxt2.includes('explore all insights')) {
                            exploreTrigger = btnEl2;
                        }
                    }
                }

                if (exploreTrigger && !exploreTrigger.classList.contains('open-popup') && !exploreTrigger.hasAttribute('data-popup-id')) {
                    var inHeader2 = exploreTrigger.closest('header, [data-elementor-type="header"], #quanto-header-desktop');
                    if (!inHeader2) {
                        e.preventDefault();
                        e.stopPropagation();

                        var targetSection = document.getElementById('overview') ||
                                            document.getElementById('insights') ||
                                            document.getElementById('latest-insights') ||
                                            document.querySelector('.cmr-latest-section') ||
                                            document.querySelector('.cmr-industry-intel-section') ||
                                            document.querySelector('.cmr-vpi-section') ||
                                            document.querySelector('.cmr-mui-section');

                        if (!targetSection) {
                            var allHeadings = Array.from(document.querySelectorAll('h1, h2, h3, h4, .elementor-heading-title'));
                            var matchHeading = allHeadings.find(function(h) {
                                var t = h.textContent.toLowerCase();
                                return t.includes('latest insights') || t.includes('expert viewpoints') || t.includes('latest industry');
                            });
                            if (matchHeading) {
                                targetSection = matchHeading.closest('.elementor-section, .elementor-element, section, .e-con') || matchHeading;
                            }
                        }

                        if (targetSection) {
                            var headerOffset = 80;
                            var wpAdminBar = document.getElementById('wpadminbar');
                            if (wpAdminBar) headerOffset += wpAdminBar.offsetHeight;
                            var elPosition = targetSection.getBoundingClientRect().top + window.pageYOffset;
                            var offsetPosition = elPosition - headerOffset;

                            window.scrollTo({
                                top: offsetPosition,
                                behavior: 'smooth'
                            });
                        }
                        return;
                    }
                }

                // 3. Check if clicked element or parent is a popup trigger
                var trigger = e.target.closest(
                    'a[href*="popup:open"], a[href*="elementor-action"], ' +
                    '.open-report-popup, .slide-cta-button, [href="#open-report-popup"], ' +
                    '.open-popup, .custom-talk-analyst-btn, [href="#open-popup"], ' +
                    '[data-elementor-open-popup], [data-popup-id]'
                );

                if (trigger) {
                    var href = trigger.getAttribute('href') || '';
                    var popupId = null;

                    if (trigger.classList.contains('open-report-popup') || trigger.classList.contains('slide-cta-button') || href === '#open-report-popup') {
                        popupId = 7758;
                    } else if (trigger.classList.contains('open-popup') || trigger.classList.contains('custom-talk-analyst-btn') || href === '#open-popup') {
                        popupId = 7637;
                    } else if (trigger.getAttribute('data-popup-id')) {
                        popupId = parseInt(trigger.getAttribute('data-popup-id'));
                    } else if (href.indexOf('7832') !== -1) {
                        popupId = 7832;
                    } else if (href.indexOf('7849') !== -1) {
                        popupId = 7849;
                    } else {
                        popupId = extractPopupId(href);
                    }

                    if (popupId) {
                        e.preventDefault();
                        e.stopPropagation();
                        openPopup(popupId, trigger);
                        return;
                    }
                }

                // 4. Handle close button click
                var closeBtn = e.target.closest('.dialog-close-button, .dialog-lightbox-close-button, .btn-close, .mfp-close, #cmr-close-review-modal, #cmr-review-modal-close');
                if (closeBtn) {
                    e.preventDefault();
                    e.stopPropagation();
                    closeAllModals(e);
                    return;
                }

                // 5. Close on clicking backdrop (outside dialog content)
                if (e.target.classList.contains('dialog-type-lightbox') || e.target.classList.contains('dialog-widget') || e.target.classList.contains('dialog-backdrop') || e.target.id === 'cmr-review-modal-overlay') {
                    e.preventDefault();
                    closeAllModals(e);
                }
            });

            // Instant ESC key close
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' || e.keyCode === 27) {
                    closeAllModals(e);
                }
            });

            // Event hooks for Elementor & Bootstrap
            if (window.jQuery) {
                jQuery(document).on('elementor/popup/show', checkActiveModals);
                jQuery(document).on('elementor/popup/hide', function() {
                    checkActiveModals();
                });
                jQuery(document).on('shown.bs.modal show.bs.modal', checkActiveModals);
                jQuery(document).on('hidden.bs.modal hide.bs.modal', function() {
                    checkActiveModals();
                });
            }

            var keyboardBlurTimeout = null;

            // Auto scroll focused input into viewport above mobile virtual keyboard
            document.addEventListener('focusin', function(e) {
                if (window.innerWidth <= 768) {
                    var target = e.target;
                    if (target && (target.tagName === 'INPUT' || target.tagName === 'TEXTAREA' || target.tagName === 'SELECT')) {
                        var modal = target.closest('.dialog-widget, .elementor-popup-modal, .modal, #cmr-review-modal, #cmr-review-modal-overlay');
                        if (modal) {
                            clearTimeout(keyboardBlurTimeout);
                            modal.classList.add('cmr-keyboard-active');
                            document.body.classList.add('cmr-keyboard-active');
                            setTimeout(function() {
                                try {
                                    target.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                } catch(err) {
                                    target.scrollIntoView(false);
                                }
                            }, 280);
                        }
                    }
                }
            }, true);

            // Restore Center Alignment when input loses focus (smooth recenter)
            document.addEventListener('focusout', function(e) {
                if (window.innerWidth <= 768) {
                    var target = e.target;
                    if (target && (target.tagName === 'INPUT' || target.tagName === 'TEXTAREA' || target.tagName === 'SELECT')) {
                        keyboardBlurTimeout = setTimeout(function() {
                            var activeEl = document.activeElement;
                            var stillInInput = activeEl && (activeEl.tagName === 'INPUT' || activeEl.tagName === 'TEXTAREA' || activeEl.tagName === 'SELECT');
                            if (!stillInInput) {
                                document.querySelectorAll('.cmr-keyboard-active').forEach(function(el) {
                                    el.classList.remove('cmr-keyboard-active');
                                    try {
                                        el.scrollTo({ top: 0, behavior: 'smooth' });
                                    } catch(err) {
                                        el.scrollTop = 0;
                                    }
                                });
                                document.body.classList.remove('cmr-keyboard-active');
                            }
                        }, 200);
                    }
                }
            }, true);

            if (window.visualViewport) {
                window.visualViewport.addEventListener('resize', function() {
                    if (window.innerWidth <= 768) {
                        var activeEl = document.activeElement;
                        if (activeEl && (activeEl.tagName === 'INPUT' || activeEl.tagName === 'TEXTAREA' || activeEl.tagName === 'SELECT')) {
                            var modal = activeEl.closest('.dialog-widget, .elementor-popup-modal, .modal, #cmr-review-modal, #cmr-review-modal-overlay');
                            if (modal) {
                                modal.classList.add('cmr-keyboard-active');
                                document.body.classList.add('cmr-keyboard-active');
                                setTimeout(function() {
                                    try {
                                        activeEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                    } catch(err) {}
                                }, 100);
                            }
                        }
                    }
                });
            }

            checkActiveModals();
            updateCMRModalTexts();

            // Hook into Elementor popup show event
            if (typeof jQuery !== 'undefined') {
                jQuery(document).on('elementor/popup/show', function(evt, popupData) {
                    updateCMRModalTexts();
                    checkActiveModals();

                    // Resolve the popup container that just became visible
                    var popupId = popupData && popupData.id ? popupData.id : null;
                    var popup = null;
                    if (popupId) {
                        popup = document.querySelector('.elementor-popup-modal[data-elementor-id="' + popupId + '"]') ||
                                document.querySelector('[data-elementor-id="' + popupId + '"]') ||
                                document.querySelector('.elementor-' + popupId);
                    }
                    if (!popup) {
                        // Fallback: first visible popup
                        var allPopups = document.querySelectorAll('.elementor-popup-modal, .dialog-widget.dialog-type-lightbox');
                        for (var pi = 0; pi < allPopups.length; pi++) {
                            var p = allPopups[pi];
                            if (p.style.display !== 'none' && window.getComputedStyle(p).display !== 'none') {
                                popup = p;
                                break;
                            }
                        }
                    }

                    // Step 1: Render any deferred (unrendered) captcha in this popup
                    if (popup) {
                        setTimeout(function() { cmrInitRecaptchas(popup); }, 150);
                    }

                    // Step 2: Reset already-rendered captchas so user gets a fresh widget
                    setTimeout(function() {
                        var scope = popup || document;
                        scope.querySelectorAll('.wpcf7-recaptcha[data-cmr-rendered="true"]').forEach(function(rc) {
                            var wid = rc._grecaptchaWidgetId;
                            if (typeof grecaptcha !== 'undefined' && typeof grecaptcha.reset === 'function') {
                                try {
                                    if (wid !== null && wid !== undefined) {
                                        grecaptcha.reset(wid);
                                    } else {
                                        grecaptcha.reset();
                                    }
                                } catch(err) {}
                            }
                        });
                    }, 300);
                });
            }

            window.addEventListener('load', function() {
                updateCMRModalTexts();
                checkActiveModals();
            });
        });
    })();
    </script>
    <?php
}, 999 );
