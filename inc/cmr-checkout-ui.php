<?php
/**
 * CMR Custom Checkout UI Shortcode
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

function cmr_checkout_ui_shortcode( $atts ) {
    ob_start();
    ?>
    <style>
        .cmr-checkout-container {
            max-width: 1200px;
            margin: 40px auto;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            color: #111;
        }
        .cmr-checkout-back-link {
            display: inline-flex;
            align-items: center;
            font-size: 14px;
            font-weight: 600;
            color: #111;
            text-decoration: none;
            margin-bottom: 30px;
        }
        .cmr-checkout-back-link i {
            margin-right: 8px;
        }
        .cmr-checkout-layout {
            display: flex;
            gap: 40px;
            flex-wrap: wrap;
        }
        .cmr-checkout-left {
            flex: 1;
            min-width: 320px;
            border: 1px solid #eaeaea;
            border-radius: 4px;
            padding: 40px;
        }
        .cmr-checkout-right {
            width: 380px;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        .cmr-checkout-title {
            font-size: 24px;
            font-weight: 600;
            margin-top: 0;
            margin-bottom: 30px;
            letter-spacing: -0.5px;
        }
        .cmr-form-group {
            margin-bottom: 25px;
        }
        .cmr-form-label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            color: #444;
            margin-bottom: 10px;
            letter-spacing: 0.5px;
        }
        .cmr-form-row {
            display: flex;
            gap: 15px;
        }
        .cmr-form-row > * {
            flex: 1;
        }
        .cmr-form-input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #e0e0e0;
            border-radius: 2px;
            font-size: 14px;
            color: #333;
            box-sizing: border-box;
            transition: border-color 0.2s ease;
        }
        .cmr-form-input:focus {
            outline: none;
            border-color: #4b23a0;
        }
        .cmr-form-input::placeholder {
            color: #777;
        }
        .cmr-phone-input-wrap {
            display: flex;
            gap: 10px;
        }
        .cmr-phone-code {
            width: 80px;
            text-align: center;
        }
        .cmr-phone-number {
            flex: 1;
        }
        .cmr-checkout-terms {
            font-size: 11px;
            color: #777;
            margin-top: 30px;
            line-height: 1.5;
        }
        .cmr-checkout-terms a {
            color: #777;
            text-decoration: underline;
        }

        /* Right Side Styles */
        .cmr-right-box {
            border: 1px solid #eaeaea;
            border-radius: 4px;
            padding: 25px;
        }
        .cmr-promo-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .cmr-promo-row {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 10px;
        }
        .cmr-promo-input-wrap {
            flex: 1;
            position: relative;
        }
        .cmr-promo-input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #e0e0e0;
            border-radius: 2px;
            font-size: 14px;
            box-sizing: border-box;
        }
        .cmr-promo-applied {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #10b981;
            font-size: 12px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .cmr-promo-remove {
            background: none;
            border: 1px solid #e0e0e0;
            padding: 11px 15px;
            border-radius: 2px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            color: #111;
        }
        .cmr-promo-hint {
            font-size: 11px;
            color: #777;
        }

        .cmr-order-item {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
        }
        .cmr-order-image {
            width: 70px;
            height: 90px;
            background: #f5f5f5;
            border: 1px solid #eaeaea;
            object-fit: cover;
        }
        .cmr-order-details {
            flex: 1;
        }
        .cmr-order-title {
            font-size: 14px;
            font-weight: 600;
            line-height: 1.4;
            margin-bottom: 10px;
        }
        .cmr-order-publisher {
            font-size: 12px;
            color: #777;
        }

        .cmr-order-summary-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 20px;
        }
        .cmr-summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 14px;
            color: #555;
        }
        .cmr-summary-row.discount {
            color: #10b981;
        }
        .cmr-summary-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 25px;
            margin-bottom: 25px;
            font-size: 18px;
            font-weight: 600;
            color: #111;
            padding-top: 20px;
            border-top: 1px solid #eaeaea;
        }
        
        .cmr-checkout-btn {
            width: 100%;
            background-color: #4b23a0;
            color: #fff;
            border: none;
            padding: 16px;
            border-radius: 50px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: background-color 0.2s ease;
        }
        .cmr-checkout-btn:hover {
            background-color: #381a79;
        }

        .cmr-safe-checkout-title {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 15px;
        }
        .cmr-payment-logos {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
        }
        .cmr-payment-logos img {
            height: 24px;
            border: 1px solid #eaeaea;
            border-radius: 4px;
            padding: 2px 6px;
            background: #fff;
        }

        @media (max-width: 900px) {
            .cmr-checkout-layout {
                flex-direction: column;
            }
            .cmr-checkout-right {
                width: 100%;
            }
            .cmr-form-row {
                flex-direction: column;
            }
        }
    </style>

    <div class="cmr-checkout-container">
        <a href="#" class="cmr-checkout-back-link"><i class="fa-solid fa-arrow-left"></i> Back to Reports</a>
        
        <div class="cmr-checkout-layout">
            <!-- Left Column: Shipping Address -->
            <div class="cmr-checkout-left">
                <h2 class="cmr-checkout-title">Shipping address</h2>
                
                <div class="cmr-form-group">
                    <label class="cmr-form-label">NAME</label>
                    <div class="cmr-form-row">
                        <input type="text" class="cmr-form-input" placeholder="First">
                        <input type="text" class="cmr-form-input" placeholder="Last">
                    </div>
                </div>

                <div class="cmr-form-group">
                    <label class="cmr-form-label">ADDRESS</label>
                    <input type="text" class="cmr-form-input" placeholder="Street address, P.O. box, company name">
                </div>

                <div class="cmr-form-group">
                    <label class="cmr-form-label">LOCATION</label>
                    <div class="cmr-form-row" style="margin-bottom: 15px;">
                        <input type="text" class="cmr-form-input" placeholder="City">
                        <input type="text" class="cmr-form-input" placeholder="State">
                    </div>
                    <div class="cmr-form-row">
                        <input type="text" class="cmr-form-input" placeholder="Country">
                        <input type="text" class="cmr-form-input" placeholder="Pincode">
                    </div>
                </div>

                <div class="cmr-form-group">
                    <label class="cmr-form-label">EMAIL ADDRESS</label>
                    <input type="email" class="cmr-form-input" placeholder="alexander@botanical.com">
                </div>

                <div class="cmr-form-group">
                    <label class="cmr-form-label">PHONE NUMBER</label>
                    <div class="cmr-phone-input-wrap">
                        <input type="text" class="cmr-form-input cmr-phone-code" value="+91" readonly>
                        <input type="text" class="cmr-form-input cmr-phone-number" placeholder="00000-00000">
                    </div>
                </div>

                <div class="cmr-checkout-terms">
                    By proceeding with your purchase you agree to our <a href="#">Terms and Conditions</a> and <a href="#">Privacy Policy</a>
                </div>
            </div>

            <!-- Right Column: Order Summary -->
            <div class="cmr-checkout-right">
                
                <div class="cmr-right-box">
                    <div class="cmr-promo-title"><i class="fa-solid fa-tag"></i> Promo Code</div>
                    <div class="cmr-promo-row">
                        <div class="cmr-promo-input-wrap">
                            <input type="text" class="cmr-promo-input" value="CMR10" readonly>
                            <span class="cmr-promo-applied">Applied <i class="fa-solid fa-circle-check"></i></span>
                        </div>
                        <button class="cmr-promo-remove">Remove &times;</button>
                    </div>
                    <div class="cmr-promo-hint">Try: CMR10, CMRINDIA15, or FIRST20</div>
                </div>

                <div class="cmr-right-box">
                    <div class="cmr-order-item">
                        <img src="<?php echo esc_url( quanto_get_placeholder_image_uri() ); ?>" alt="Report" class="cmr-order-image">
                        <div class="cmr-order-details">
                            <div class="cmr-order-title">Navigating Digital Supply Chain Transformation: Key Insights</div>
                            <div class="cmr-order-publisher">Cyber Media Research (CMR)</div>
                        </div>
                    </div>

                    <div class="cmr-order-summary-title">Order Summary</div>
                    
                    <div class="cmr-summary-row">
                        <span>Subtotal</span>
                        <span>$298.00</span>
                    </div>
                    <div class="cmr-summary-row">
                        <span>Tax</span>
                        <span>$2.00</span>
                    </div>
                    <div class="cmr-summary-row discount">
                        <span>Discount (10%)</span>
                        <span>-$30.00</span>
                    </div>

                    <div class="cmr-summary-total">
                        <span>Total</span>
                        <span>$270.00</span>
                    </div>

                    <button class="cmr-checkout-btn">
                        <i class="fa-solid fa-lock"></i> Proceed to Checkout
                    </button>
                </div>

                <div>
                    <div class="cmr-safe-checkout-title">Guaranteed safe checkout</div>
                    <div class="cmr-payment-logos">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/f/fa/Apple_logo_black.svg" alt="Apple Pay" style="padding: 4px; height: 18px;">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/c/c1/Google_%22G%22_logo.svg" alt="Google Pay" style="height: 18px; padding: 4px;">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" alt="Mastercard" style="padding: 4px;">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg" alt="PayPal" style="height: 16px; padding: 6px;">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" alt="Visa" style="padding: 6px; height: 14px;">
                    </div>
                </div>

            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode( 'cmr_checkout_ui', 'cmr_checkout_ui_shortcode' );
