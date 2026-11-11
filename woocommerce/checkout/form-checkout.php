<?php
defined( 'ABSPATH' ) || exit;

// Remove the default coupon form at the top
remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );

if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
    echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
    return;
}
?>

<style>
/* Critical Inline Checkout Grid Rules (Bypasses all CDN/Browser CSS Caches) */
.cmr-checkout-wrap {
    display: grid !important;
    grid-template-columns: 1fr 420px !important;
    gap: 40px !important;
    max-width: 1280px !important;
    margin: 40px auto !important;
    padding: 0 24px !important;
    align-items: start !important;
}
@media (max-width: 900px) {
    .cmr-checkout-wrap {
        grid-template-columns: 1fr !important;
    }
}
/* Custom Grouped Billing Form Styles */
.cmr-custom-billing-wrapper {
    width: 100% !important;
}
.cmr-field-group {
    margin-bottom: 24px !important;
}
.cmr-group-label {
    display: block !important;
    font-size: 13px !important;
    font-weight: 700 !important;
    letter-spacing: 0.04em !important;
    color: #374151 !important;
    margin-bottom: 8px !important;
    text-transform: uppercase !important;
}
.cmr-grid-2 {
    display: grid !important;
    grid-template-columns: 1fr 1fr !important;
    gap: 16px !important;
}
@media (max-width: 600px) {
    .cmr-grid-2 {
        grid-template-columns: 1fr !important;
    }
}
.cmr-grid-2 .form-row,
.cmr-field-group .form-row {
    margin: 0 !important;
    padding: 0 !important;
    width: 100% !important;
}
.cmr-custom-billing-wrapper input.input-text,
.cmr-custom-billing-wrapper select,
.cmr-custom-billing-wrapper textarea {
    width: 100% !important;
    height: 48px !important;
    border: 1px solid #e5e7eb !important;
    border-radius: 6px !important;
    padding: 0 16px !important;
    font-size: 15px !important;
    color: #111827 !important;
    background: #fff !important;
    box-sizing: border-box !important;
    transition: border-color 0.2s, box-shadow 0.2s !important;
}
.cmr-custom-billing-wrapper input.input-text:focus,
.cmr-custom-billing-wrapper select:focus {
    border-color: #4820B0 !important;
    outline: none !important;
    box-shadow: 0 0 0 3px rgba(72, 32, 176, 0.1) !important;
}
.cmr-custom-billing-wrapper .form-row label {
    display: none !important;
}
.cmr-phone-row {
    display: flex !important;
    gap: 12px !important;
    align-items: center !important;
}
.cmr-phone-prefix {
    width: 76px !important;
    height: 48px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    border: 1px solid #e5e7eb !important;
    border-radius: 6px !important;
    background: #f9fafb !important;
    font-size: 15px !important;
    font-weight: 600 !important;
    color: #111827 !important;
}
.cmr-phone-input-wrap {
    flex: 1 !important;
}
.woocommerce-shipping-fields {
    display: none !important;
}
/* Make sure Order Summary right column stays visible and styled */
.cmr-checkout-right {
    width: 100% !important;
}
.cmr-promo-box {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 24px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.02);
}
.cmr-promo-header {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 15px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 14px;
}
.cmr-applied-coupons-wrap {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 12px;
}
.cmr-applied-pill {
    flex: 1;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 10px 14px;
    font-size: 14px;
    font-weight: 600;
    color: #111827;
    background: #f9fafb;
}
.cmr-status-badge {
    color: #10b981;
    font-size: 13px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 4px;
}
.cmr-remove-pill-btn {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 10px 14px;
    font-size: 14px;
    font-weight: 600;
    color: #374151;
    background: #fff;
    text-decoration: none;
}
.cmr-remove-pill-btn:hover {
    background: #f3f4f6;
    color: #111827;
}
.cmr-coupon-form {
    display: flex;
    gap: 8px;
    margin-bottom: 10px;
}
.cmr-coupon-input {
    flex: 1;
    border: 1px solid #d1d5db !important;
    border-radius: 8px !important;
    padding: 0 14px !important;
    font-size: 14px !important;
    height: 44px !important;
    box-sizing: border-box !important;
}
.cmr-coupon-apply,
button.cmr-coupon-apply,
#apply_custom_coupon {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    text-align: center !important;
    background: #111827 !important;
    color: #fff !important;
    border: none !important;
    border-radius: 8px !important;
    padding: 0 20px !important;
    height: 44px !important;
    line-height: 1 !important;
    font-size: 14px !important;
    font-weight: 600 !important;
    cursor: pointer !important;
    box-sizing: border-box !important;
}
.cmr-coupon-hints {
    font-size: 12px;
    color: #6b7280;
}
.cmr-order-box {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 24px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.02);
}
/* Product Card */
.cmr-checkout-product-card {
    display: flex !important;
    gap: 16px !important;
    align-items: flex-start !important;
    padding-bottom: 20px !important;
    border-bottom: 1px solid #f3f4f6 !important;
    margin-bottom: 20px !important;
}
.cmr-product-thumb {
    flex: 0 0 75px !important;
    width: 75px !important;
    height: 104.1px !important;
}
.cmr-product-thumb img {
    width: 75px !important;
    height: 104.1px !important;
    object-fit: cover !important;
    border-radius: 6px !important;
    border: 1px solid #e5e7eb !important;
    box-shadow: 0 2px 6px rgba(0,0,0,0.08) !important;
}
.cmr-product-name {
    font-size: 15px !important;
    font-weight: 700 !important;
    color: #111827 !important;
    margin: 0 0 6px 0 !important;
    line-height: 1.4 !important;
}
.cmr-product-brand {
    font-size: 13px !important;
    color: #6b7280 !important;
    margin: 0 !important;
}
.cmr-summary-heading {
    font-size: 16px !important;
    font-weight: 700 !important;
    color: #111827 !important;
    margin: 0 0 16px 0 !important;
}
.cmr-summary-list .cmr-summary-row {
    display: flex !important;
    justify-content: space-between !important;
    align-items: center !important;
    margin-bottom: 14px !important;
    font-size: 14px !important;
    color: #4b5563 !important;
}
.cmr-summary-list .nowrap,
.cmr-summary-list .nowrap .amount,
.cmr-summary-list .nowrap .woocommerce-Price-amount {
    white-space: nowrap !important;
    text-align: right !important;
    font-weight: 600 !important;
    color: #111827 !important;
}
.cmr-summary-list .coupon-discount .cmr-row-value,
.cmr-summary-list .coupon-discount .nowrap,
.cmr-summary-list .coupon-discount .amount {
    color: #10b981 !important;
    font-weight: 600 !important;
}
.cmr-summary-list .coupon-discount .woocommerce-remove-coupon {
    display: none !important; /* Hide duplicate remove link inside order totals table */
}
.cmr-summary-list .total-row {
    margin-top: 16px !important;
    padding-top: 16px !important;
    border-top: 1px solid #e5e7eb !important;
    font-size: 18px !important;
    font-weight: 800 !important;
    color: #111827 !important;
}
.cmr-summary-list .total-row .nowrap {
    font-size: 20px !important;
    font-weight: 800 !important;
}
/* Proceed Button */
#payment, .woocommerce-checkout-payment {
    background: transparent !important;
    border: none !important;
    padding: 0 !important;
    margin: 0 !important;
}
#payment ul.wc_payment_methods,
#payment ul.payment_methods,
.woocommerce-checkout-payment ul.wc_payment_methods,
.woocommerce-checkout-payment ul.payment_methods,
.woocommerce-terms-and-conditions-wrapper,
.woocommerce-privacy-policy-text {
    display: none !important;
}
#place_order, .button.alt.cmr-place-order-btn {
    width: 100% !important;
    background: #4820B0 !important;
    color: #fff !important;
    font-size: 16px !important;
    font-weight: 600 !important;
    padding: 15px 24px !important;
    border-radius: 9999px !important;
    border: none !important;
    margin-top: 24px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    cursor: pointer !important;
    box-shadow: 0 4px 14px rgba(72, 32, 176, 0.3) !important;
    transition: background 0.2s !important;
}
#place_order:hover, .button.alt.cmr-place-order-btn:hover {
    background: #3e1b99 !important;
}
/* Trust Badges */
.cmr-trust-badges {
    text-align: left !important;
    margin-top: 24px !important;
    padding-top: 20px !important;
    border-top: 1px solid #e5e7eb !important;
}
.cmr-trust-label {
    font-size: 13px !important;
    font-weight: 600 !important;
    color: #111827 !important;
    margin-bottom: 12px !important;
    text-align: left !important;
}
.cmr-trust-icons {
    display: flex !important;
    justify-content: flex-start !important;
    flex-wrap: wrap !important;
    gap: 8px !important;
}
.cmr-icon-pill {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 6px;
    padding: 4px 8px;
    display: flex;
    align-items: center;
}
.cmr-icon-pill img {
    height: 18px;
    width: auto;
}
/* Back to Report Button */
.cmr-back-link-wrap {
    max-width: 1280px !important;
    margin: 0 auto 16px auto !important;
    padding: 0 !important;
    text-align: left !important;
}
.cmr-back-to-report-btn {
    display: inline-flex !important;
    align-items: center !important;
    gap: 8px !important;
    font-size: 14px !important;
    font-weight: 600 !important;
    color: #4820B0 !important;
    text-decoration: none !important;
    transition: all 0.2s !important;
}
.cmr-back-to-report-btn:hover {
    color: #3e1b99 !important;
    transform: translateX(-3px) !important;
}
</style>

<?php
$back_url = 'javascript:history.back()';
if ( WC()->cart && ! WC()->cart->is_empty() ) {
    foreach ( WC()->cart->get_cart() as $cart_item ) {
        if ( ! empty( $cart_item['product_id'] ) ) {
            $back_url = get_permalink( $cart_item['product_id'] );
            break;
        }
    }
}
?>
<div class="cmr-back-link-wrap">
    <a href="<?php echo esc_url( $back_url ); ?>" class="cmr-back-to-report-btn">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
        Back to Report
    </a>
</div>

<div class="cmr-checkout-notices-container" style="max-width: 1280px; margin: 0 auto; padding: 0 24px; box-sizing: border-box;">
    <?php do_action( 'woocommerce_before_checkout_form', $checkout ); ?>
</div>

<form name="checkout" id="cmr-checkout-main-form" method="post" class="cmr-checkout-wrap woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

    <div class="cmr-checkout-left">
        <div class="cmr-checkout-card" id="guest-shipping-address">
            <h2 class="cmr-section-title">Shipping address</h2>
            
            <?php if ( $checkout->get_checkout_fields() ) : ?>
                <div class="cmr-billing-fields">
                    <?php do_action( 'woocommerce_checkout_billing' ); ?>
                </div>
            <?php endif; ?>

            <p class="cmr-terms-notice">
                By proceeding with your purchase you agree to our
                <a href="#">Terms and Conditions</a> and <a href="#">Privacy Policy</a>
            </p>
        </div>
    </div>

    <div class="cmr-checkout-right">
        <!-- Promo Code -->
        <?php if ( wc_coupons_enabled() ) : ?>
        <div class="cmr-promo-box">
            <div class="cmr-promo-header">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg>
                Promo Code
            </div>
            <?php
            $applied_coupons = WC()->cart->get_coupons();
            if ( ! empty( $applied_coupons ) ) {
                foreach ( $applied_coupons as $code => $coupon ) {
                    ?>
                    <div class="cmr-applied-coupons-wrap">
                        <div class="cmr-applied-pill">
                            <span><?php echo esc_html( strtoupper( $code ) ); ?></span>
                            <span class="cmr-status-badge">
                                Applied
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="#10b981"><circle cx="12" cy="12" r="10"/><path fill="#fff" d="M10 14.17l-2.59-2.58L6 13l4 4 8-8-1.41-1.42z"/></svg>
                            </span>
                        </div>
                        <a href="<?php echo esc_url( add_query_arg( 'remove_coupon', rawurlencode( $code ), wc_get_checkout_url() ) ); ?>" class="cmr-remove-pill-btn">
                            Remove &times;
                        </a>
                    </div>
                    <div class="cmr-coupon-hints">Try: CMR10, CMRINDIA15, or FIRST20</div>
                    <?php
                }
            } else {
                ?>
                <div class="cmr-coupon-form">
                    <input type="text" name="coupon_code" class="cmr-coupon-input" placeholder="Enter coupon code" id="coupon_code">
                    <button type="button" class="cmr-coupon-apply" id="apply_custom_coupon">Apply</button>
                </div>
                <div class="cmr-coupon-hints">Try: CMR10, CMRINDIA15, or FIRST20</div>
                <?php
            }
            ?>
        </div>
        <?php endif; ?>

        <!-- Product Summary -->
        <div class="cmr-order-box">
            <div id="order_review" class="woocommerce-checkout-review-order">
                <?php do_action( 'woocommerce_checkout_order_review' ); ?>
            </div>

            <!-- Trust Badges moved inside cmr-order-box -->
            <div class="cmr-trust-badges">
                <p class="cmr-trust-label">Guaranteed safe checkout</p>
                <div class="cmr-trust-icons">
                    <?php
                    foreach ( array( 'ApplePay' => 'Apple Pay', 'GooglePay' => 'Google Pay', 'Mastercard' => 'Mastercard', 'Amex' => 'Amex', 'PayPal' => 'PayPal', 'Visa' => 'Visa' ) as $icon_slug => $icon_label ) : ?>
                        <div class="cmr-icon-pill">
                            <img decoding="async" src="<?php echo esc_url( home_url( '/wp-content/uploads/2026/05/' . $icon_slug . '.svg' ) ); ?>" alt="<?php echo esc_attr( $icon_label ); ?>">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>

<script>
jQuery(document).ready(function($) {
    // Intercept Enter key inside coupon input field to prevent main order submit
    $(document).on('keydown', '#coupon_code', function(e) {
        if (e.key === 'Enter' || e.keyCode === 13) {
            e.preventDefault();
            $('#apply_custom_coupon').trigger('click');
            return false;
        }
    });

    // Make our custom coupon button work via smooth AJAX (Zero Page Reload!)
    $(document).on('click', '#apply_custom_coupon', function(e) {
        e.preventDefault();
        var code = $('#coupon_code').val().trim();
        if(!code) return;
        
        var $btn = $(this);
        $btn.text('Applying...').prop('disabled', true);
        
        var data = {
            security: wc_checkout_params.apply_coupon_nonce,
            coupon_code: code
        };
        
        $.ajax({
            type: 'POST',
            url: wc_checkout_params.wc_ajax_url.toString().replace( '%%endpoint%%', 'apply_coupon' ),
            data: data,
            success: function( response ) {
                $('.woocommerce-error, .woocommerce-message, .woocommerce-info').remove();
                if ( response ) {
                    if ($('.cmr-coupon-form').length) {
                        $('.cmr-coupon-form').before( response );
                    } else {
                        $('.cmr-promo-box').before( response );
                    }
                }
                
                // Update Order Summary subtotal & discounts instantly
                $( document.body ).trigger( 'update_checkout', { update_shipping_method: false } );
                
                // Check if coupon applied successfully without error
                if ( response && response.indexOf('woocommerce-error') === -1 && (response.indexOf('woocommerce-message') !== -1 || response.indexOf('applied successfully') !== -1) ) {
                    var upperCode = code.toUpperCase();
                    var removeUrl = wc_checkout_params.checkout_url + '?remove_coupon=' + encodeURIComponent(code);
                    var pillHtml = '<div class="cmr-applied-coupons-wrap">' +
                        '<div class="cmr-applied-pill">' +
                            '<span>' + upperCode + '</span>' +
                            '<span class="cmr-status-badge">Applied <svg width="16" height="16" viewBox="0 0 24 24" fill="#10b981"><circle cx="12" cy="12" r="10"/><path fill="#fff" d="M10 14.17l-2.59-2.58L6 13l4 4 8-8-1.41-1.42z"/></svg></span>' +
                        '</div>' +
                        '<a href="' + removeUrl + '" class="cmr-remove-pill-btn" data-code="' + upperCode + '">Remove &times;</a>' +
                    '</div>' +
                    '<div class="cmr-coupon-hints">Try: CMR10, CMRINDIA15, or FIRST20</div>';
                    
                    $('.cmr-promo-box').html(pillHtml);
                } else {
                    $btn.text('Apply').prop('disabled', false);
                }
            },
            error: function() {
                $btn.text('Apply').prop('disabled', false);
            }
        });
    });

    // Make Remove Coupon pill button work via smooth AJAX without page reload
    $(document).on('click', '.cmr-remove-pill-btn', function(e) {
        e.preventDefault();
        var $btn = $(this);
        var coupon = $btn.data('code') || ($btn.attr('href') ? $btn.attr('href').split('remove_coupon=')[1] : '');
        if (!coupon) return;
        
        $btn.text('Removing...');
        
        var data = {
            security: wc_checkout_params.remove_coupon_nonce,
            coupon: decodeURIComponent(coupon)
        };
        
        $.ajax({
            type: 'POST',
            url: wc_checkout_params.wc_ajax_url.toString().replace( '%%endpoint%%', 'remove_coupon' ),
            data: data,
            success: function( response ) {
                $('.woocommerce-error, .woocommerce-message, .woocommerce-info').remove();
                $( document.body ).trigger( 'update_checkout', { update_shipping_method: false } );
                
                var formHtml = '<div class="cmr-coupon-form">' +
                    '<input type="text" name="coupon_code" class="cmr-coupon-input" placeholder="Enter coupon code" id="coupon_code">' +
                    '<button type="button" class="cmr-coupon-apply" id="apply_custom_coupon">Apply</button>' +
                '</div>' +
                '<div class="cmr-coupon-hints">Try: CMR10, CMRINDIA15, or FIRST20</div>';
                
                $('.cmr-promo-box').html(formHtml);
                
                if ( response ) {
                    $('.cmr-coupon-form').before( response );
                }
            }
        });
    });

    // Phone country code dynamic mapper
    var phonePrefixes = {
        'IN': '+91', 'US': '+1', 'GB': '+44', 'CA': '+1', 'AU': '+61', 'DE': '+49', 'FR': '+33', 'AE': '+971', 'SG': '+65', 'MY': '+60', 'SA': '+966', 'ID': '+62', 'TH': '+66', 'PH': '+63', 'VN': '+84', 'JP': '+81', 'KR': '+82', 'CN': '+86', 'HK': '+852', 'TW': '+886', 'NL': '+31', 'IT': '+39', 'ES': '+34', 'CH': '+41', 'SE': '+46', 'NO': '+47', 'DK': '+45', 'FI': '+358', 'ZA': '+27', 'BR': '+55', 'MX': '+52', 'NZ': '+64'
    };
    $(document).on('change', '#billing_country', function() {
        var cc = $(this).val();
        if (phonePrefixes[cc]) {
            $('#cmr_phone_code').text(phonePrefixes[cc]);
        } else if (cc) {
            $('#cmr_phone_code').text('+91');
        }
    });
});
</script>
