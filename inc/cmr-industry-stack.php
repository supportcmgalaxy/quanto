<?php
/**
 * Shortcode for Industry Intelligence Stack Component
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! function_exists( 'cmr_industry_stack_shortcode' ) ) {
    function cmr_industry_stack_shortcode( $atts ) {
        $atts = shortcode_atts( array(
            'icon_1' => home_url( '/wp-content/uploads/2026/06/service-icon1.svg' ),
            'icon_2' => home_url( '/wp-content/uploads/2026/06/Group-266.svg' ),
            'icon_3' => home_url( '/wp-content/uploads/2026/06/Vector2.svg.svg' ),
        ), $atts );

        ob_start();
        ?>
        <style>
            .cmr-stack-wrapper {
                font-family: 'Instrument Sans', sans-serif;
                max-width: 1280px;
                margin: 0 auto;
                padding: 20px 0px;
                background-color: #f9fafb;
            }
            .cmr-stack-header {
                text-align: center;
                margin-bottom: 50px;
            }
            .cmr-stack-header h2 {
                font-size: 42px;
                font-weight: 600;
                margin: 0 0 15px 0;
                letter-spacing: -1px;
                color: #111;
            }
            .cmr-stack-header p {
                font-size: 16px;
                color: #555;
                margin: 0;
            }
            .cmr-stack-grid {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 30px;
            }
            .cmr-stack-card {
                background: #fff;
                padding: 20px;
                border-radius: 4px;
                display: flex;
                flex-direction: column;
                height: auto;
                min-height: 402px;
                box-shadow: 0 4px 20px rgba(0,0,0,0.03);
                position: relative;
                box-sizing: border-box;
            }
            .cmr-stack-card-top {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                margin-bottom: 30px;
            }
            .cmr-stack-icon {
                width: 48px;
                height: 48px;
                display: flex;
                align-items: center;
                justify-content: flex-start;
            }
            .cmr-stack-number {
                font-size: 16px;
                font-weight: 600;
                color: #111;
            }
            .cmr-stack-title {
                font-size: 24px;
                font-weight: 600;
                color: #111;
                margin: 0 0 15px 0;
                line-height: 1.3;
                letter-spacing: -1px;
            }
            .cmr-stack-desc {
                font-size: 15px;
                color: #555;
                line-height: 1.6;
                margin: 0 0 25px 0;
            }
            .cmr-stack-list {
                list-style: none;
                padding: 0;
                margin: 0;
            }
            .cmr-stack-list li {
                position: relative;
                padding-left: 15px;
                font-size: 14px;
                color: #444;
                margin-bottom: 12px;
                line-height: 1.4;
            }
            .cmr-stack-list li:last-child {
                margin-bottom: 0;
            }
            .cmr-stack-list li::before {
                content: '';
                position: absolute;
                left: 0;
                top: 7px;
                width: 4px;
                height: 4px;
                background-color: #111;
                border-radius: 50%;
            }
            @media (max-width: 992px) {
                .cmr-stack-grid {
                    grid-template-columns: repeat(2, 1fr);
                }
            }
            @media (max-width: 768px) {
                .cmr-stack-wrapper {
                    padding: 20px 0px !important;
                    width: 100% !important;
                    box-sizing: border-box !important;
                }
                .cmr-stack-grid {
                    grid-template-columns: 1fr;
                    padding: 0 !important;
                    margin: 0 !important;
                    width: 100% !important;
                    box-sizing: border-box !important;
                }
                .cmr-stack-card {
                    height: auto;
                    min-height: 400px;
                    width: 100% !important;
                    padding: 20px !important;
                    box-sizing: border-box !important;
                }
            }
        </style>

        <div class="cmr-stack-wrapper">
            <div class="cmr-stack-grid">
                <!-- Card 1 -->
                <div class="cmr-stack-card">
                    <div class="cmr-stack-card-top">
                        <div class="cmr-stack-icon">
                            <img src="<?php echo esc_url( $atts['icon_1'] ); ?>" alt="Industry Intelligence Icon" style="width: 34px; height: 34px; object-fit: contain;">
                        </div>
                        <div class="cmr-stack-number">01</div>
                    </div>
                    <h3 class="cmr-stack-title">Industry<br>Intelligence</h3>
                    <p class="cmr-stack-desc">Actionable intelligence on markets, technologies, competitors, and emerging trends to help organizations anticipate change, identify opportunities, and make confident strategic decisions.</p>
                    <ul class="cmr-stack-list">
                        <li>Performance Intelligence - Market, product, and brand performance tracking </li>
                        <li>Competitive Intelligence - Market landscape, benchmarking, and competitive positioning </li>
                        <li>Strategic Intelligence - Forward-looking insights to guide business and investment decisions</li>
                    </ul>
                </div>

                <!-- Card 2 -->
                <div class="cmr-stack-card">
                    <div class="cmr-stack-card-top">
                        <div class="cmr-stack-icon">
                            <img src="<?php echo esc_url( $atts['icon_2'] ); ?>" alt="Consulting & Advisory Icon" style="width: 34px; height: 34px; object-fit: contain;">
                        </div>
                        <div class="cmr-stack-number">02</div>
                    </div>
                    <h3 class="cmr-stack-title">Consulting &<br>Advisory</h3>
                    <p class="cmr-stack-desc">Evidence-based consulting that transforms consumer and enterprise research into actionable intelligence, enabling organizations to innovate, differentiate, and grow with confidence.</p>
                    <ul class="cmr-stack-list">
                        <li>Consumer Intelligence - Market, customer, and brand insights </li>
                        <li>Enterprise Intelligence - B2B decision-maker, partner, and ecosystem research </li>
                        <li>Strategic Advisory - Research-backed recommendations and growth strategies</li>
                    </ul>
                </div>

                <!-- Card 3 -->
                <div class="cmr-stack-card">
                    <div class="cmr-stack-card-top">
                        <div class="cmr-stack-icon">
                            <img src="<?php echo esc_url( $atts['icon_3'] ); ?>" alt="Marketing Services Icon" style="width: 34px; height: 34px; object-fit: contain;">
                        </div>
                        <div class="cmr-stack-number">03</div>
                    </div>
                    <h3 class="cmr-stack-title">Marketing<br>Services</h3>
                    <p class="cmr-stack-desc">Intelligence-powered marketing that helps organizations build credibility, engage decision-makers, and accelerate growth through analyst-led programs and strategic market engagement.</p>
                    <ul class="cmr-stack-list">
                        <li>Engagement-Led - Executive forums, roundtables, communities, and ecosystem programs Advisory support</li>
                        <li>Campaign-Led - Integrated campaigns, demand generation, and content-led marketing Market entry & growth strategy</li>
                        <li>Visibility-Led - Thought leadership, webinars, digital outreach, media engagement, and industry recognition</li>
                    </ul>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}
add_shortcode( 'cmr_industry_stack', 'cmr_industry_stack_shortcode' );
