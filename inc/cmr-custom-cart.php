<?php
/**
 * CMR Custom Cart Shortcode
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! function_exists( 'cmr_custom_cart_shortcode' ) ) {
    function cmr_custom_cart_shortcode() {
        // Enqueue necessary scripts/styles
        ob_start();
        ?>
        <style>
            .cmr-cart-wrapper {
                max-width: 1280px;
                margin: 40px auto;
                padding: 0 20px;
                font-family: 'Instrument Sans', sans-serif !important;
                color: #111827;
            }

            .cmr-back-link {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                color: #374151;
                font-weight: 600;
                font-size: 15px;
                text-decoration: none;
                margin-bottom: 30px;
            }

            .cmr-back-link:hover {
                color: #000;
            }

            .cmr-cart-grid {
                display: grid;
                grid-template-columns: 2fr 1fr;
                gap: 40px;
            }

            /* Box styles */
            .cmr-cart-box {
                background: #ffffff;
                border: 1px solid #e5e7eb;
                border-radius: 4px;
                margin-bottom: 30px;
                position: relative;
            }

            .cmr-cart-box-header {
                padding: 20px 30px;
                border-bottom: 1px solid #e5e7eb;
            }

            .cmr-cart-box-title {
                font-size: 18px;
                font-weight: 600;
                margin: 0;
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .cmr-cart-box-title i {
                color: #6b7280;
                font-size: 16px;
            }

            /* Items List */
            .cmr-cart-items {
                padding: 0 30px;
            }

            .cmr-cart-item {
                display: flex;
                gap: 25px;
                padding: 30px 0;
                border-bottom: 1px solid #e5e7eb;
                position: relative;
            }

            .cmr-cart-item:last-child {
                border-bottom: none;
            }

            .cmr-ci-image {
                width: 120px;
                flex-shrink: 0;
            }

            .cmr-ci-image img {
                width: 100%;
                height: auto;
                border-radius: 4px;
                border: 1px solid #f3f4f6;
            }

            .cmr-ci-details {
                flex: 1;
                display: flex;
                flex-direction: column;
            }

            .cmr-ci-title {
                font-size: 16px;
                font-weight: 600;
                color: #111827;
                margin-bottom: 8px;
                line-height: 1.4;
                padding-right: 100px; /* Space for price */
            }

            .cmr-ci-brand {
                font-size: 14px;
                color: #6b7280;
                margin-bottom: 15px;
            }

            .cmr-ci-sku {
                font-size: 14px;
                font-weight: 600;
                color: #374151;
            }
            .cmr-ci-sku span {
                color: #6b7280;
                font-weight: 400;
            }

            .cmr-ci-price {
                position: absolute;
                top: 30px;
                right: 0;
                font-size: 20px;
                font-weight: 600;
                color: #111827;
            }

            .cmr-ci-remove {
                position: absolute;
                bottom: 30px;
                right: 0;
                color: #ef4444; /* Red */
                font-size: 14px;
                font-weight: 500;
                cursor: pointer;
                display: flex;
                align-items: center;
                gap: 5px;
                background: none;
                border: none;
                padding: 0;
            }

            .cmr-ci-remove:hover {
                text-decoration: underline;
            }

            /* Promo Box */
            .cmr-promo-box {
                padding: 20px 30px;
            }
            .cmr-promo-input-wrap {
                display: flex;
                gap: 10px;
                margin-top: 15px;
                margin-bottom: 10px;
            }
            .cmr-promo-input-wrap input {
                flex: 1;
                padding: 12px 15px;
                border: 1px solid #d1d5db;
                border-radius: 4px;
                font-size: 15px;
                outline: none;
            }
            .cmr-promo-input-wrap input:focus {
                border-color: #6b46c1;
            }
            .cmr-promo-btn {
                background: #ffffff;
                border: 1px solid #d1d5db;
                border-radius: 4px;
                padding: 0 20px;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.2s ease;
            }
            .cmr-promo-btn:hover {
                background: #f9fafb;
            }
            .cmr-promo-hint {
                font-size: 12px;
                color: #6b7280;
            }

            /* Applied Coupon State */
            .cmr-applied-coupon {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 12px 15px;
                border: 1px solid #d1d5db;
                border-radius: 4px;
                margin-top: 15px;
                background: #f9fafb;
            }
            .cmr-ac-code {
                font-weight: 600;
                font-size: 15px;
                display: flex;
                align-items: center;
                gap: 10px;
            }
            .cmr-ac-status {
                color: #10b981;
                font-size: 13px;
            }
            .cmr-ac-remove {
                background: none;
                border: none;
                font-size: 14px;
                font-weight: 500;
                cursor: pointer;
                color: #374151;
            }
            .cmr-ac-remove:hover {
                text-decoration: underline;
            }

            /* Order Summary Box */
            .cmr-summary-box {
                padding: 30px;
            }
            .cmr-summary-row {
                display: flex;
                justify-content: space-between;
                margin-bottom: 15px;
                font-size: 15px;
                color: #4b5563;
            }
            .cmr-summary-row.discount-row {
                color: #10b981;
            }
            .cmr-summary-total {
                display: flex;
                justify-content: space-between;
                margin-top: 30px;
                margin-bottom: 25px;
                font-size: 20px;
                font-weight: 600;
                color: #111827;
            }
            .cmr-checkout-btn {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
                background: #4b23a0;
                color: #ffffff;
                width: 100%;
                padding: 16px;
                border-radius: 50px;
                font-size: 16px;
                font-weight: 600;
                text-decoration: none;
                transition: background 0.2s;
                border: none;
                cursor: pointer;
            }
            .cmr-checkout-btn:hover {
                background: #391880;
                color: #ffffff;
            }

            /* Safe Checkout */
            .cmr-safe-checkout {
                margin-top: 25px;
            }
            .cmr-safe-checkout p {
                font-size: 14px;
                font-weight: 600;
                color: #111827;
                margin-bottom: 15px;
            }
            .cmr-payment-icons {
                display: flex;
                gap: 10px;
                flex-wrap: wrap;
            }
            .cmr-payment-icons img {
                height: 30px;
                border: 1px solid #e5e7eb;
                border-radius: 4px;
                padding: 2px 6px;
                background: #fff;
            }

            /* Loading Overlay */
            .cmr-cart-loading {
                position: absolute;
                top: 0; left: 0; right: 0; bottom: 0;
                background: rgba(255,255,255,0.7);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 100;
                opacity: 0;
                pointer-events: none;
                transition: opacity 0.2s;
            }
            .cmr-cart-loading.active {
                opacity: 1;
                pointer-events: all;
            }

            @media (max-width: 992px) {
                .cmr-cart-grid {
                    grid-template-columns: 1fr;
                    gap: 30px;
                }
            }

            @media (max-width: 768px) {
                .cmr-cart-wrapper {
                    margin: 20px auto;
                    padding: 0 15px;
                }
                .cmr-back-link {
                    margin-bottom: 20px;
                    font-size: 14px;
                }
                .cmr-cart-box {
                    margin-bottom: 20px;
                }
                .cmr-cart-box-header {
                    padding: 16px 18px;
                }
                .cmr-cart-box-title {
                    font-size: 16px;
                }
                .cmr-cart-items {
                    padding: 0 18px;
                }
                .cmr-cart-item {
                    display: flex !important;
                    flex-direction: row !important;
                    gap: 14px !important;
                    padding: 18px 0 !important;
                    align-items: flex-start !important;
                    position: relative !important;
                }
                .cmr-ci-image {
                    width: 85px !important;
                    max-width: 85px !important;
                    flex: 0 0 85px !important;
                    border-radius: 4px;
                    overflow: hidden;
                }
                .cmr-ci-image img {
                    width: 100% !important;
                    height: auto !important;
                    aspect-ratio: 1 / 1 !important;
                    object-fit: cover !important;
                    display: block !important;
                    border-radius: 4px;
                }
                .cmr-ci-details {
                    flex: 1 !important;
                    min-width: 0 !important;
                    padding-right: 0 !important;
                    display: flex !important;
                    flex-direction: column !important;
                }
                .cmr-ci-title {
                    font-size: 14px !important;
                    font-weight: 600 !important;
                    line-height: 1.35 !important;
                    margin-bottom: 4px !important;
                    padding-right: 0 !important;
                    word-break: break-word !important;
                }
                .cmr-ci-brand {
                    font-size: 12px !important;
                    margin-bottom: 4px !important;
                    color: #6b7280 !important;
                }
                .cmr-ci-sku {
                    font-size: 12px !important;
                    margin-bottom: 6px !important;
                    color: #374151 !important;
                }
                .cmr-ci-price {
                    position: static !important;
                    top: auto !important;
                    right: auto !important;
                    font-size: 16px !important;
                    font-weight: 700 !important;
                    color: #111827 !important;
                    margin-top: 4px !important;
                    margin-bottom: 8px !important;
                }
                .cmr-ci-remove {
                    position: static !important;
                    bottom: auto !important;
                    right: auto !important;
                    top: auto !important;
                    font-size: 13px !important;
                    font-weight: 500 !important;
                    color: #ef4444 !important;
                    align-self: flex-start !important;
                    display: inline-flex !important;
                    align-items: center !important;
                    gap: 5px !important;
                    background: none !important;
                    border: none !important;
                    padding: 0 !important;
                    cursor: pointer !important;
                }
                .cart-login-prompt {
                    padding: 20px 18px !important;
                    margin-bottom: 20px !important;
                }
                .cmr-promo-box {
                    padding: 18px !important;
                }
                .cmr-summary-box {
                    padding: 20px 18px !important;
                }
                .cmr-summary-total {
                    font-size: 18px !important;
                    margin-top: 20px !important;
                    margin-bottom: 20px !important;
                }
                .cmr-checkout-btn {
                    padding: 14px 20px !important;
                    font-size: 15px !important;
                }
                .woocommerce-message,
                .woocommerce-info,
                .woocommerce-error {
                    padding: 12px 16px !important;
                    font-size: 13px !important;
                    display: flex !important;
                    flex-direction: column !important;
                    align-items: flex-start !important;
                    gap: 10px !important;
                    border-radius: 6px !important;
                }
                .woocommerce-message .button,
                .woocommerce-info .button {
                    float: none !important;
                    margin: 0 !important;
                    align-self: flex-start !important;
                }
            }
        </style>

        <div class="cmr-cart-wrapper" id="cmr-custom-cart-app">
            <?php 
            if ( function_exists( 'wc_print_notices' ) ) {
                wc_print_notices(); 
            }
            echo cmr_get_cart_html(); 
            ?>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const cartApp = document.getElementById('cmr-custom-cart-app');

                function bindCartEvents() {
                    // Remove Item
                    const removeBtns = cartApp.querySelectorAll('.cmr-ci-remove');
                    removeBtns.forEach(btn => {
                        btn.addEventListener('click', function(e) {
                            e.preventDefault();
                            const cartKey = this.getAttribute('data-cart-key');
                            updateCart('remove_item', { cart_key: cartKey });
                        });
                    });

                    // Apply Coupon
                    const applyBtn = cartApp.querySelector('.cmr-promo-btn');
                    if ( applyBtn ) {
                        applyBtn.addEventListener('click', function(e) {
                            e.preventDefault();
                            const code = cartApp.querySelector('#cmr_coupon_code').value;
                            if ( code ) {
                                updateCart('apply_coupon', { coupon_code: code });
                            }
                        });
                    }

                    // Remove Coupon
                    const removeCouponBtns = cartApp.querySelectorAll('.cmr-ac-remove');
                    removeCouponBtns.forEach(btn => {
                        btn.addEventListener('click', function(e) {
                            e.preventDefault();
                            const code = this.getAttribute('data-coupon');
                            updateCart('remove_coupon', { coupon_code: code });
                        });
                    });
                }

                function updateCart( actionName, dataPayload ) {
                    const loading = cartApp.querySelector('.cmr-cart-loading');
                    if (loading) loading.classList.add('active');

                    const formData = new FormData();
                    formData.append('action', 'cmr_cart_action');
                    formData.append('cart_action', actionName);
                    
                    for ( const key in dataPayload ) {
                        formData.append(key, dataPayload[key]);
                    }

                    fetch('<?php echo admin_url( 'admin-ajax.php' ); ?>', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if ( data.success ) {
                            cartApp.innerHTML = data.data.html;
                            bindCartEvents();
                            // Update nav badge count from AJAX response
                            if (typeof data.data.cart_count !== 'undefined') {
                                document.querySelectorAll('.cmr-nav-cart-badge-count').forEach(function(b) { b.textContent = data.data.cart_count; });
                            }
                            // Dispatch standard woo event so fragments (like header cart count) update
                            jQuery(document.body).trigger('wc_fragment_refresh');
                        } else {
                            if (loading) loading.classList.remove('active');
                            alert(data.data.message || 'An error occurred.');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        if (loading) loading.classList.remove('active');
                    });
                }

                // Initial Bind
                bindCartEvents();
            });
        </script>
        <?php
        return ob_get_clean();
    }
}
add_shortcode( 'cmr_custom_cart', 'cmr_custom_cart_shortcode' );


/**
 * Helper to generate cart HTML
 */
function cmr_get_cart_html() {
    if ( WC()->cart->is_empty() ) {
        ob_start();
        ?>
        <div class="cmr-cart-empty" style="text-align: center; padding: 100px 20px;">
            <i class="fa-solid fa-cart-shopping" style="font-size: 48px; color: #d1d5db; margin-bottom: 20px;"></i>
            <h2 style="font-size: 24px; font-weight: 600; margin-bottom: 15px;">Your cart is currently empty!</h2>
            <p style="color: #6b7280; margin-bottom: 30px;">Browse our latest reports and add them to your cart.</p>
            <a href="https://cmrindia.com/research-reports/" style="display: inline-block; background: #4b23a0; color: #fff; padding: 12px 30px; border-radius: 50px; text-decoration: none; font-weight: 600;">Back to reports</a>
        </div>
        <?php
        return ob_get_clean();
    }

    ob_start();
    $cart_count = count(WC()->cart->get_cart());
    ?>
    <a href="/research-reports/" class="cmr-back-link">&larr; Back to Reports</a>
    
    <div class="cmr-cart-grid">
        <div class="cmr-cart-loading"><i class="fa-solid fa-circle-notch fa-spin fa-2x" style="color: #6b46c1;"></i></div>
        
        <!-- Left Column -->
        <div class="cmr-cart-items-wrap">
            <div class="cmr-cart-box">
                <div class="cmr-cart-box-header">
                    <h3 class="cmr-cart-box-title"><i class="fa-solid fa-bag-shopping"></i> Shopping Cart (<?php echo esc_html( $cart_count ); ?> items)</h3>
                </div>
                
                <div class="cmr-cart-items">
                    <?php 
                    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                        $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                        $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

                        if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
                            $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
                            $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image('thumbnail'), $cart_item, $cart_item_key );
                            $product_name = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
                            $price = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
                            $sku = $_product->get_sku();
                            
                            ?>
                            <div class="cmr-cart-item">
                                <div class="cmr-ci-image">
                                    <?php echo $thumbnail; ?>
                                </div>
                                <div class="cmr-ci-details">
                                    <div class="cmr-ci-title"><?php echo wp_kses_post( $product_name ); ?></div>
                                    <div class="cmr-ci-brand">Cyber Media Research (CMR)</div>
                                    <?php if ( $sku ) : ?>
                                        <div class="cmr-ci-sku"><span>SKU:</span> <?php echo esc_html( $sku ); ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="cmr-ci-price">
                                    <?php echo $price; ?>
                                </div>
                                <button class="cmr-ci-remove" data-cart-key="<?php echo esc_attr( $cart_item_key ); ?>">
                                    <i class="fa-regular fa-trash-can"></i> Remove
                                </button>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>
            
            <!-- Pre-Checkout Login Form -->
            <div class="cart-login-prompt" id="cart-login-prompt" style="background: #ffffff; border: 1px solid #e5e7eb; border-radius: 4px; padding: 30px; margin-bottom: 30px; <?php if ( is_user_logged_in() ) echo 'display: none;'; ?>">
                <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 8px;">Login to Continue Checkout</h3>
                <p style="color: #6b7280; font-size: 14px; margin-bottom: 20px;">Please sign in to save your cart and proceed to secure payment.</p>
                
                <form class="woocommerce-form woocommerce-form-login login" method="post" action="<?php echo esc_url( wc_get_cart_url() ); ?>" style="border: none !important; padding: 0 !important; margin: 0 !important;">
                    <?php do_action( 'woocommerce_login_form_start' ); ?>

                    <label for="username"><?php esc_html_e( 'EMAIL ADDRESS', 'woocommerce' ); ?></label>
                    <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="username" autocomplete="username" required style="width: 100%; padding: 12px 15px; border: 1px solid #d1d5db; border-radius: 4px; margin-bottom: 15px;" />

                    <label for="password"><?php esc_html_e( 'PASSWORD', 'woocommerce' ); ?></label>
                    <input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password" autocomplete="current-password" required style="width: 100%; padding: 12px 15px; border: 1px solid #d1d5db; border-radius: 4px; margin-bottom: 15px;" />

                    <?php do_action( 'woocommerce_login_form' ); ?>

                    <input type="hidden" name="redirect" value="<?php echo esc_url( wc_get_checkout_url() ); ?>" />
                    <?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
                    <button type="submit" class="woocommerce-button button woocommerce-form-login__submit" name="login" value="<?php esc_attr_e( 'Log in', 'woocommerce' ); ?>" style="background: #4b23a0; color: #fff; border: none; padding: 14px 25px; border-radius: 50px; font-weight: 600; width: 100%; cursor: pointer; display: flex; align-items: center; justify-content: center;"><?php esc_html_e( 'Sign In', 'woocommerce' ); ?> <svg style="margin-left: 6px;" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 11L11 1M11 1H1M11 1V11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></button>

                    <?php do_action( 'woocommerce_login_form_end' ); ?>
                </form>
                <div class="cart-login-links" style="margin-top: 15px; text-align: center;">
                    <a href="<?php echo esc_url( wp_lostpassword_url() ); ?>" class="forgot-password-link" style="color: #6b46c1; text-decoration: none; font-size: 14px;">Forgot Password?</a>
                </div>
                
                <div class="login-separator" style="display: flex; align-items: center; text-align: center; margin: 25px 0; color: #9ca3af;"><span style="flex: 1; border-bottom: 1px solid #e5e7eb;"></span><span style="padding: 0 10px; font-size: 13px; font-weight: 600;">OR</span><span style="flex: 1; border-bottom: 1px solid #e5e7eb;"></span></div>
                
                <p class="create-account-text" style="color: #6b7280; font-size: 14px; margin-bottom: 15px; text-align: center;">Create an account to gain instant access to your premium market reports and insights.</p>
                
                <a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="create-account-btn" style="display: flex; align-items: center; justify-content: center; background: #ffffff; border: 2px solid #4b23a0; color: #4b23a0; padding: 12px 25px; border-radius: 50px; font-weight: 600; text-decoration: none;">Create Account <svg style="margin-left: 6px;" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 11L11 1M11 1H1M11 1V11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
                
                <div style="text-align: center; margin-top: 24px; padding-top: 20px; border-top: 1px dashed #e5e7eb;">
                    <a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" onclick="var p=document.querySelectorAll('.wc-proceed-to-checkout a.checkout-button, .cart-collaterals .checkout-button, .cmr-checkout-btn'); p.forEach(function(b){ b.removeAttribute('disabled'); b.style.setProperty('opacity', '1', 'important'); b.style.setProperty('pointer-events', 'auto', 'important'); b.style.setProperty('cursor', 'pointer', 'important'); b.style.setProperty('filter', 'none', 'important'); }); document.getElementById('cart-login-prompt').style.setProperty('display', 'none', 'important'); window.location.href='<?php echo esc_url( wc_get_checkout_url() ); ?>'; return false;" style="font-size: 14px; font-weight: 600; color: #4820B0; text-decoration: underline;">Or continue as guest without creating an account ↓</a>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="cmr-cart-sidebar">
            
            <!-- Promo Box -->
            <div class="cmr-cart-box">
                <div class="cmr-promo-box">
                    <h3 class="cmr-cart-box-title" style="font-size: 16px;"><i class="fa-solid fa-tag"></i> Promo Code</h3>
                    
                    <?php 
                    $applied_coupons = WC()->cart->get_applied_coupons();
                    if ( empty( $applied_coupons ) ) : 
                    ?>
                        <div class="cmr-promo-input-wrap">
                            <input type="text" id="cmr_coupon_code" placeholder="CMR10">
                            <button class="cmr-promo-btn">Apply</button>
                        </div>
                        <div class="cmr-promo-hint">Try: CMR10, CMRINDIA15, or FIRST20</div>
                    <?php else : ?>
                        <?php foreach ( $applied_coupons as $coupon_code ) : ?>
                            <div class="cmr-applied-coupon">
                                <div class="cmr-ac-code">
                                    <?php echo esc_html( strtoupper( $coupon_code ) ); ?>
                                    <span class="cmr-ac-status">Applied <i class="fa-solid fa-circle-check"></i></span>
                                </div>
                                <button class="cmr-ac-remove" data-coupon="<?php echo esc_attr( $coupon_code ); ?>">Remove &times;</button>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Summary Box -->
            <div class="cmr-cart-box">
                <div class="cmr-summary-box">
                    <h3 class="cmr-cart-box-title" style="margin-bottom: 20px;">Order Summary</h3>
                    
                    <div class="cmr-summary-row">
                        <span>Subtotal (<?php echo esc_html( $cart_count ); ?> items)</span>
                        <span><?php echo wc_price( WC()->cart->get_subtotal() ); ?></span>
                    </div>
                    
                    <?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
                        <?php 
                        $taxes = WC()->cart->get_taxes();
                        $tax_total = array_sum( $taxes );
                        if ( $tax_total > 0 ) :
                        ?>
                        <div class="cmr-summary-row">
                            <span>Tax</span>
                            <span><?php echo wc_price( $tax_total ); ?></span>
                        </div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
                        <div class="cmr-summary-row discount-row">
                            <span>Discount (<?php echo esc_html( $code ); ?>)</span>
                            <span>-<?php echo wc_price( WC()->cart->get_coupon_discount_amount( $code ) ); ?></span>
                        </div>
                    <?php endforeach; ?>

                    <div class="cmr-summary-total">
                        <span>Total</span>
                        <span><?php echo wc_price( WC()->cart->get_total('edit') ); ?></span>
                    </div>

                    <a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="cmr-checkout-btn">
                        <i class="fa-regular fa-credit-card"></i> Proceed to Checkout
                    </a>
                </div>
            </div>

            <div class="cmr-safe-checkout">
                <p>Guaranteed safe checkout</p>
                <div class="cmr-payment-icons">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/b/b0/Apple_Pay_logo.svg" alt="Apple Pay" loading="lazy" decoding="async" width="40" height="24" style="height:24px;">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/f/f2/Google_Pay_Logo.svg" alt="Google Pay" loading="lazy" decoding="async" width="40" height="24" style="height:24px;">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" alt="Mastercard" loading="lazy" decoding="async" width="40" height="24" style="height:24px;">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/f/fa/American_Express_logo_%282018%29.svg" alt="Amex" loading="lazy" decoding="async" width="40" height="24" style="height:24px;">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg" alt="PayPal" loading="lazy" decoding="async" width="40" height="24" style="height:24px;">
                    <img src="<?php echo esc_url( home_url( '/wp-content/uploads/2026/05/Visa.svg' ) ); ?>" alt="Visa" loading="lazy" decoding="async" width="40" height="24" style="height:24px;">
                </div>
            </div>

        </div>
    </div>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var isLoggedOut = !document.body.classList.contains('logged-in') || <?php echo ! is_user_logged_in() ? 'true' : 'false'; ?>;
        var promptBox = document.getElementById('cart-login-prompt');
        var proceedBtns = document.querySelectorAll('.wc-proceed-to-checkout a.checkout-button, .cart-collaterals .checkout-button, .cmr-checkout-btn');
        if (isLoggedOut && promptBox) {
            promptBox.style.setProperty('display', 'block', 'important');
            proceedBtns.forEach(function(btn) {
                btn.setAttribute('disabled', 'disabled');
                btn.style.setProperty('opacity', '0.5', 'important');
                btn.style.setProperty('pointer-events', 'none', 'important');
                btn.style.setProperty('cursor', 'not-allowed', 'important');
                btn.style.setProperty('filter', 'grayscale(1)', 'important');
            });
        } else if (promptBox) {
            promptBox.style.setProperty('display', 'none', 'important');
        }
    });
    </script>
    <?php
    return ob_get_clean();
}


/**
 * AJAX Handler for custom cart interactions
 */
add_action( 'wp_ajax_cmr_cart_action', 'cmr_cart_action_handler' );
add_action( 'wp_ajax_nopriv_cmr_cart_action', 'cmr_cart_action_handler' );

function cmr_cart_action_handler() {
    $action = isset( $_POST['cart_action'] ) ? sanitize_text_field( $_POST['cart_action'] ) : '';

    if ( ! WC()->cart ) {
        wp_send_json_error( array( 'message' => 'Cart not initialized.' ) );
    }

    try {
        switch ( $action ) {
            case 'remove_item':
                $cart_item_key = isset( $_POST['cart_key'] ) ? sanitize_text_field( $_POST['cart_key'] ) : '';
                if ( $cart_item_key ) {
                    WC()->cart->remove_cart_item( $cart_item_key );
                }
                break;

            case 'apply_coupon':
                $coupon_code = isset( $_POST['coupon_code'] ) ? sanitize_text_field( $_POST['coupon_code'] ) : '';
                if ( $coupon_code ) {
                    WC()->cart->add_discount( $coupon_code );
                }
                break;

            case 'remove_coupon':
                $coupon_code = isset( $_POST['coupon_code'] ) ? sanitize_text_field( $_POST['coupon_code'] ) : '';
                if ( $coupon_code ) {
                    WC()->cart->remove_coupon( $coupon_code );
                }
                break;
        }

        // Calculate totals to ensure accuracy
        WC()->cart->calculate_totals();

        wp_send_json_success( array(
            'html' => cmr_get_cart_html(),
            'cart_count' => count( WC()->cart->get_cart() )
        ) );
    } catch ( Exception $e ) {
        wp_send_json_error( array( 'message' => $e->getMessage() ) );
    }
}
