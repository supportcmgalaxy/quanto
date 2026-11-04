<?php
// Shortcode for Nav Cart Icon
add_shortcode('cmr_nav_cart', 'cmr_nav_cart_shortcode');
function cmr_nav_cart_shortcode() {
    $cart_count = ( function_exists('WC') && WC()->cart ) ? count(WC()->cart->get_cart()) : 0;
    
    ob_start();
    ?>
    <style>
    .cmr-nav-cart-container {
        position: relative;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        vertical-align: middle !important;
        text-decoration: none;
        width: 40px !important;
        height: 40px !important;
        min-width: 40px !important;
        min-height: 40px !important;
        padding: 0 !important;
        margin: 0 !important;
        box-sizing: border-box !important;
        line-height: 1 !important;
        color: #fff; /* White by default matching search icon */
        transition: color 0.3s ease;
    }
    
    .cmr-nav-cart-container:hover {
        color: #d1d1d1 !important; /* Matches search icon hover */
    }
    
    /* Change to black when header is sticky */
    .elementor-sticky--effects .cmr-nav-cart-container,
    .is-sticky .cmr-nav-cart-container,
    header.sticky .cmr-nav-cart-container,
    .intel-nav-fixed-js .cmr-nav-cart-container {
        color: #333 !important;
    }

    .elementor-sticky--effects .cmr-nav-cart-container:hover,
    .is-sticky .cmr-nav-cart-container:hover,
    header.sticky .cmr-nav-cart-container:hover,
    .intel-nav-fixed-js .cmr-nav-cart-container:hover {
        color: #4820B0 !important;
    }

    .cmr-nav-cart-container svg {
        width: 22px !important;
        height: 22px !important;
        display: block !important;
        margin: 0 auto !important;
    }

    .cmr-nav-cart-badge {
        position: absolute !important;
        bottom: 2px !important;
        right: 2px !important;
        background: #00BFBC; /* Cyan/teal */
        color: #fff; /* white */
        font-size: 10px !important;
        font-family: Arial, sans-serif;
        font-weight: 800 !important;
        width: 16px !important;
        height: 16px !important;
        border-radius: 50% !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        border: 2px solid transparent;
        line-height: 1 !important;
    }
    </style>
    
    <a href="<?php echo function_exists('wc_get_cart_url') ? esc_url(wc_get_cart_url()) : '#'; ?>" class="cmr-nav-cart-container cmr-has-tooltip" aria-label="Cart">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M16 8V6a4 4 0 0 0-8 0v2M4 9a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v11a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V9z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <div class="cmr-nav-cart-badge">
            <span class="cmr-nav-cart-badge-count"><?php echo esc_html($cart_count); ?></span>
        </div>
        <span class="cmr-header-tooltip">Cart</span>
    </a>
    <?php
    return ob_get_clean();
}

// Add fragment for AJAX update so the cart count updates when items are added
add_filter('woocommerce_add_to_cart_fragments', 'cmr_nav_cart_fragment_badge');
function cmr_nav_cart_fragment_badge($fragments) {
    $cart_count = ( function_exists('WC') && WC()->cart ) ? count(WC()->cart->get_cart()) : 0;
    $fragments['div.cmr-nav-cart-badge'] = '<div class="cmr-nav-cart-badge"><span class="cmr-nav-cart-badge-count">' . esc_html($cart_count) . '</span></div>';
    return $fragments;
}

// Shortcode for Nav Cart Icon (Always Black)
add_shortcode('cmr_nav_cart_black', 'cmr_nav_cart_black_shortcode');
function cmr_nav_cart_black_shortcode() {
    $cart_count = ( function_exists('WC') && WC()->cart ) ? count(WC()->cart->get_cart()) : 0;
    
    ob_start();
    ?>
    <style>
    .cmr-nav-cart-container-black {
        position: relative;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        vertical-align: middle !important;
        text-decoration: none;
        width: 40px !important;
        height: 40px !important;
        min-width: 40px !important;
        min-height: 40px !important;
        padding: 0 !important;
        margin: 0 !important;
        box-sizing: border-box !important;
        line-height: 1 !important;
        color: #333 !important;
        transition: color 0.3s ease;
    }
    .cmr-nav-cart-container-black:hover {
        color: #4820B0 !important;
    }
    .cmr-nav-cart-container-black svg {
        width: 22px !important;
        height: 22px !important;
        display: block !important;
        margin: 0 auto !important;
    }
    .cmr-nav-cart-badge {
        position: absolute !important;
        bottom: 2px !important;
        right: 2px !important;
        background: #00BFBC; /* Cyan/teal */
        color: #fff; /* white */
        font-size: 10px !important;
        font-family: Arial, sans-serif;
        font-weight: 800 !important;
        width: 16px !important;
        height: 16px !important;
        border-radius: 50% !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        border: 2px solid transparent;
        line-height: 1 !important;
    }
    </style>
    
    <a href="<?php echo function_exists('wc_get_cart_url') ? esc_url(wc_get_cart_url()) : '#'; ?>" class="cmr-nav-cart-container-black cmr-has-tooltip" aria-label="Cart">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M16 8V6a4 4 0 0 0-8 0v2M4 9a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v11a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V9z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <div class="cmr-nav-cart-badge">
            <span class="cmr-nav-cart-badge-count"><?php echo esc_html($cart_count); ?></span>
        </div>
        <span class="cmr-header-tooltip">Cart</span>
    </a>
    <?php
    return ob_get_clean();
}

// ============================================================
// IMPORTANT: JavaScript is loaded via wp_footer, NOT inside
// the shortcodes, because Elementor caches shortcode output
// and would prevent updated JS from ever reaching the browser.
// ============================================================
// Enqueue external JS for badge sync
add_action('wp_enqueue_scripts', 'cmr_nav_cart_badge_js', 99);
function cmr_nav_cart_badge_js() {
    if (!class_exists('WooCommerce')) return;
    
    // Add cache busting version
    $version = filemtime( get_stylesheet_directory() . '/assets/js/cmr-cart-badge-sync.js' );
    wp_enqueue_script( 'cmr-cart-badge-sync', get_stylesheet_directory_uri() . '/assets/js/cmr-cart-badge-sync.js', array(), $version, true );
}

add_action('wp_footer', 'cmr_nav_cart_initial_count', 99);
function cmr_nav_cart_initial_count() {
    if (!class_exists('WooCommerce')) return;
    
    $live_count = ( function_exists('WC') && WC()->cart ) ? count(WC()->cart->get_cart()) : 0;
    ?>
    <script>
    (function() {
        // Fix cached badge count on every page load
        var liveCount = <?php echo intval($live_count); ?>;
        var badges = document.querySelectorAll('.cmr-nav-cart-badge-count');
        badges.forEach(function(b) { b.textContent = liveCount; });

        // Scroll color change for home page cart icon
        window.addEventListener('scroll', function() {
            var carts = document.querySelectorAll('.cmr-nav-cart-container');
            carts.forEach(function(cart) {
                if (window.scrollY > 50) {
                    cart.style.setProperty('color', '#333', 'important');
                } else {
                    cart.style.setProperty('color', '#fff', 'important');
                }
            });
        });
    })();
    </script>
    <?php
}
