<?php
/**
 * Shortcode for Quarterly Results Component
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! function_exists( 'cmr_quarterly_results_shortcode' ) ) {
    function cmr_quarterly_results_shortcode( $atts ) {
        // We can add attributes later if dynamic content is needed
        
        ob_start();
        ?>
        <style>
            .cmr-qr-wrapper {
                font-family: 'Instrument Sans', sans-serif !important;
                width: 100%;
                overflow: hidden;
            }

            .cmr-qr-container {
                max-width: 1280px;
                margin: 0 auto;
                padding: 0 20px;
                box-sizing: border-box;
            }

            /* ===== HERO SECTION ===== */
            .cmr-qr-hero {
                background: linear-gradient(135deg, #E2C7FA 0%, #BCE8ED 50%, #9CF2DE 100%);
                padding: 80px 0 100px 0;
                text-align: center;
                color: #111;
            }

            .cmr-qr-breadcrumbs {
                font-size: 13px;
                font-weight: 500;
                margin-bottom: 40px;
                color: #333;
            }
            
            .cmr-qr-breadcrumbs a {
                color: #333;
                text-decoration: none;
            }

            .cmr-qr-hero-subtitle {
                font-size: 12px;
                font-weight: 600;
                letter-spacing: 1px;
                text-transform: uppercase;
                margin-bottom: 20px;
            }

            .cmr-qr-hero-title {
                font-size: 56px;
                font-weight: 600;
                line-height: 1.1;
                margin: 0 auto 20px auto;
                max-width: 800px;
                letter-spacing: -1px;
            }

            .cmr-qr-hero-desc {
                font-size: 16px;
                font-weight: 400;
                color: #333;
                max-width: 600px;
                margin: 0 auto;
                line-height: 1.6;
            }


            /* ===== MAIN PURPLE SECTION ===== */
            .cmr-qr-main {
                background: #5B42C1;
                color: #fff;
                padding: 20px 0 180px 0; /* extra padding bottom for the overlapping card */
                position: relative;
            }

            /* CEO Quote Area */
            .cmr-qr-ceo-area {
                display: flex;
                gap: 60px;
                align-items: center;
                margin-bottom: 0px; /* Reduced gap */
            }

            .cmr-qr-quote-col {
                flex: 1;
            }

            .cmr-qr-quote-icon {
                margin-bottom: 25px;
            }

            .cmr-qr-quote-icon svg {
                width: 40px;
                height: 40px;
                fill: none;
                stroke: rgba(255,255,255,0.4);
                stroke-width: 1.5;
            }

            .cmr-qr-quote-text {
                font-size: 16px;
                line-height: 1.8;
                font-weight: 300;
                margin-bottom: 20px;
                color: rgba(255,255,255,0.9);
            }

            .cmr-qr-quote-author {
                font-size: 14px;
                font-weight: 300;
                color: rgba(255,255,255,0.7);
                margin-top: 30px;
            }

            .cmr-qr-quote-author strong {
                font-weight: 600;
                color: #fff;
            }

            .cmr-qr-ceo-img-col {
                flex: 0 0 45%; /* Increased from 40% */
                position: relative;
                display: flex;
                justify-content: center;
                margin-top: -160px;
                z-index: 5;
            }

            /* CEO Image custom shape container */
            .cmr-qr-ceo-img-wrap {
                position: relative;
                width: 100%;
                max-width: 580px; /* Increased from 380px */
                aspect-ratio: 3/4;
            }

            .cmr-qr-ceo-img-bg {
                position: absolute;
                top: 0; left: 0; width: 100%; height: 100%;
                border-radius: 20px 20px 100px 100px; /* Approximate the shield shape */
                z-index: 1;
            }

            .cmr-qr-ceo-img {
                position: absolute;
                top: 20px; left: 20px; right: 20px; bottom: 0;
                z-index: 2;
                border-radius: 20px 20px 100px 100px;
                overflow: hidden;
            }

            .cmr-qr-ceo-img img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                display: block;
            }


            /* FY26 Results Area */
            .cmr-qr-section-title {
                margin-top: -70px;
                font-size: 36px;
                font-weight: 600;
                margin-bottom: 40px;
                letter-spacing: -1px;
                color: #fff;
            }

            .cmr-qr-fy-grid {
                display: grid;
                grid-template-columns: 1.5fr 1fr;
                gap: 40px;
                margin-bottom: 80px;
            }

            /* Video Card */
            .cmr-qr-video-card {
                position: relative;
                border-radius: 12px;
                overflow: hidden;
                aspect-ratio: 16/9;
                background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
                box-shadow: 0 10px 30px rgba(0,0,0,0.2);
                cursor: pointer;
            }

            .cmr-qr-video-card img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                opacity: 0.8;
                transition: opacity 0.3s ease;
            }

            .cmr-qr-video-card:hover img {
                opacity: 0.6;
            }

            .cmr-qr-video-badge {
                position: absolute;
                top: 20px;
                left: 20px;
                background: #fff;
                color: #111;
                font-size: 11px;
                font-weight: 600;
                padding: 6px 12px;
                border-radius: 4px;
                z-index: 2;
            }

            .cmr-qr-video-play {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                width: 64px;
                height: 64px;
                border: 2px solid #fff;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 2;
                transition: transform 0.3s ease;
            }

            .cmr-qr-video-card:hover .cmr-qr-video-play {
                transform: translate(-50%, -50%) scale(1.1);
            }

            .cmr-qr-video-play svg {
                width: 24px;
                height: 24px;
                fill: #fff;
                margin-left: 4px; /* visual center for triangle */
            }

            .cmr-qr-video-title {
                position: absolute;
                bottom: 20px;
                left: 20px;
                font-size: 16px;
                font-weight: 600;
                z-index: 2;
            }

            /* Chart Card */
            .cmr-qr-chart-card {
                background: #fff;
                border-radius: 12px;
                padding: 30px;
                color: #111;
                box-shadow: 0 10px 30px rgba(0,0,0,0.1);
                display: flex;
                flex-direction: column;
            }

            .cmr-qr-chart-title {
                font-size: 15px;
                font-weight: 600;
                margin-bottom: 40px;
            }

            .cmr-qr-chart-area {
                flex: 1;
                display: flex;
                justify-content: space-around;
                align-items: flex-end;
                border-bottom: 1px solid #eaeaea;
                position: relative;
            }

            .cmr-qr-chart-bar-group {
                display: flex;
                flex-direction: column;
                align-items: center;
                height: 100%;
                justify-content: flex-end;
            }

            .cmr-qr-chart-val {
                font-size: 12px;
                font-weight: 600;
                margin-bottom: 10px;
            }

            .cmr-qr-chart-bar {
                width: 32px;
                background: #5B42C1;
                border-top-left-radius: 16px;
                border-top-right-radius: 16px;
            }

            .cmr-qr-chart-label {
                font-size: 11px;
                font-weight: 600;
                color: #666;
                margin-top: 15px;
                text-align: center;
            }


            /* Highlights Area */
            .cmr-qr-highlights-grid {
                display: grid;
                grid-template-columns: 1fr 1fr 1fr;
                gap: 40px;
            }

            .cmr-qr-highlight-item {
                position: relative;
                font-size: 16px;
                line-height: 1.5;
                font-weight: 300;
                color: rgba(255,255,255,0.9);
            }

            .cmr-qr-highlight-item:not(:last-child)::after {
                content: '';
                position: absolute;
                top: 0;
                right: -20px;
                width: 1px;
                height: 100%;
                background: rgba(255,255,255,0.2);
            }


            /* ===== BOTTOM ACTION BAR ===== */
            .cmr-qr-action-bar-wrap {
                position: absolute;
                bottom: 0;
                left: 50%;
                transform: translate(-50%, 50%);
                width: 100%;
                max-width: 1280px;
                padding: 0 20px;
                box-sizing: border-box;
                z-index: 10;
            }

            .cmr-qr-action-bar {
                background: #fff;
                box-shadow: none;
                display: flex;
                padding: 40px;
                justify-content: space-between;
                color: #111;
            }

            .cmr-qr-action-item {
                flex: 1;
                display: flex;
                flex-direction: column;
                align-items: center;
                text-align: center;
                text-decoration: none;
                transition: transform 0.2s ease;
            }

            .cmr-qr-action-item:hover {
                transform: translateY(-5px);
            }

            .cmr-qr-action-icon {
                color: #5B42C1;
                margin-bottom: 15px;
            }

            .cmr-qr-action-icon svg {
                width: 32px;
                height: 32px;
            }

            .cmr-qr-action-text {
                font-size: 14px;
                font-weight: 600;
                color: #111;
                display: flex;
                align-items: center;
                gap: 5px;
            }

            .cmr-qr-action-text svg {
                width: 12px;
                height: 12px;
            }

            /* Extra spacer to push content below the overlapping card */
            .cmr-qr-bottom-spacer {
                height: 100px;
                background: #ffffff;
            }

            @media (max-width: 992px) {
                .cmr-qr-ceo-area {
                    flex-direction: column;
                }
                .cmr-qr-ceo-img-wrap {
                    max-width: 580px; /* Increased for better visibility on mobile */
                }
                .cmr-qr-ceo-img-col {
                    margin-top: 40px;
                }
                .cmr-qr-section-title {
                    margin-top: 40px; /* Reset the negative margin from desktop so it doesn't overlap on mobile */
                }
                .cmr-qr-fy-grid {
                    grid-template-columns: 1fr;
                }
                .cmr-qr-highlights-grid {
                    grid-template-columns: 1fr;
                    gap: 20px;
                }
                .cmr-qr-highlight-item:not(:last-child)::after {
                    display: none;
                }
                .cmr-qr-action-bar {
                    flex-wrap: wrap;
                    gap: 30px;
                }
                .cmr-qr-action-item {
                    flex: 0 0 calc(50% - 15px);
                }
                .cmr-qr-hero-title {
                    font-size: 42px;
                }
            }
            
            @media (max-width: 600px) {
                .cmr-qr-action-item {
                    flex: 0 0 100%;
                }
                .cmr-qr-action-bar-wrap {
                    position: relative;
                    transform: none;
                    bottom: auto;
                    left: auto;
                    margin-top: -60px;
                }
                .cmr-qr-main {
                    padding-bottom: 100px;
                }
            }
        </style>

        <div class="cmr-qr-wrapper">
            <!-- Hero Section -->
            <div class="cmr-qr-hero">
                <div class="cmr-qr-container">
                    <div class="cmr-qr-breadcrumbs">
                        <a href="<?php echo esc_url(home_url('/')); ?>">Home</a> &gt; 
                        <a href="<?php echo esc_url(home_url('/newsroom')); ?>">Newsroom</a> &gt; 
                        <span>Quarterly Results</span>
                    </div>
                    <div class="cmr-qr-hero-subtitle">QUARTERLY RESULTS</div>
                    <h1 class="cmr-qr-hero-title">Quarterly Results & Business Performance Review</h1>
                    <p class="cmr-qr-hero-desc">A comprehensive overview of financial performance, key metrics, and strategic developments shaping CMR India's growth.</p>
                </div>
            </div>

            <!-- Main Purple Section -->
            <div class="cmr-qr-main">
                <div class="cmr-qr-container">
                    
                    <!-- CEO Quote Area -->
                    <div class="cmr-qr-ceo-area">
                        <div class="cmr-qr-quote-col">
                            <div class="cmr-qr-quote-icon">
                                <svg viewBox="0 0 24 24"><path d="M10 11h-4a3 3 0 0 1-3-3v-2a3 3 0 0 1 3-3h4v8zm10 0h-4a3 3 0 0 1-3-3v-2a3 3 0 0 1 3-3h4v8zm-14 0v4a5 5 0 0 0 5 5h3v-2h-3a3 3 0 0 1-3-3v-4h-2zm10 0v4a5 5 0 0 0 5 5h3v-2h-3a3 3 0 0 1-3-3v-4h-2z"/></svg>
                            </div>
                            <div class="cmr-qr-quote-text">
                                <p>At CMR India, we are focused on delivering intelligence that enables smarter decisions and sustainable growth.</p>
                                <p>This quarter, we continued to see strong demand across key sectors including automotive, telecom, AI, and digital ecosystems. Our strength lies in providing actionable insights that help businesses navigate complexity and identify growth opportunities.</p>
                            </div>
                            <div class="cmr-qr-quote-author">
                                <strong>Thomas George</strong>, CEO - Cyber Media Research
                            </div>
                        </div>
                        <div class="cmr-qr-ceo-img-col">
                            <div class="cmr-qr-ceo-img-wrap">
                                <div class="cmr-qr-ceo-img-bg"></div>
                                <div class="cmr-qr-ceo-img">
                                    <img src="<?php echo esc_url( home_url( '/wp-content/uploads/2026/06/Group-1000005376.png' ) ); ?>" alt="Thomas George">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- FY26 Results -->
                    <div class="cmr-qr-section-title">FY26 Result</div>
                    <div class="cmr-qr-fy-grid">
                        
                        <!-- Video Card -->
                        <div class="cmr-qr-video-card">
                            <div class="cmr-qr-video-badge">45:15 MINS</div>
                            <div class="cmr-qr-video-play">
                                <svg viewBox="0 0 24 24"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
                            </div>
                            <div class="cmr-qr-video-title">FY 26 Results</div>
                        </div>

                        <!-- Chart Card -->
                        <div class="cmr-qr-chart-card">
                            <div class="cmr-qr-chart-title">Revenue Growth by Quarter in FY26 (%)</div>
                            <div class="cmr-qr-chart-area">
                                
                                <div class="cmr-qr-chart-bar-group">
                                    <div class="cmr-qr-chart-val">5.5%</div>
                                    <div class="cmr-qr-chart-bar" style="height: 130px;"></div>
                                </div>
                                <div class="cmr-qr-chart-bar-group">
                                    <div class="cmr-qr-chart-val">5.8%</div>
                                    <div class="cmr-qr-chart-bar" style="height: 145px;"></div>
                                </div>
                                <div class="cmr-qr-chart-bar-group">
                                    <div class="cmr-qr-chart-val">5.1%</div>
                                    <div class="cmr-qr-chart-bar" style="height: 110px;"></div>
                                </div>
                                <div class="cmr-qr-chart-bar-group">
                                    <div class="cmr-qr-chart-val">5.2%</div>
                                    <div class="cmr-qr-chart-bar" style="height: 115px;"></div>
                                </div>

                            </div>
                            <div style="display: flex; justify-content: space-around; width: 100%;">
                                <div class="cmr-qr-chart-label">Q1<br>FY26</div>
                                <div class="cmr-qr-chart-label">Q2<br>FY26</div>
                                <div class="cmr-qr-chart-label">Q3<br>FY26</div>
                                <div class="cmr-qr-chart-label">Q4<br>FY26</div>
                            </div>
                        </div>

                    </div>

                    <!-- Result Highlights -->
                    <div class="cmr-qr-section-title">Result Highlights</div>
                    <div class="cmr-qr-highlights-grid">
                        <div class="cmr-qr-highlight-item">
                            5.4% increase in Group organic service revenue
                        </div>
                        <div class="cmr-qr-highlight-item">
                            4.5% increase in Group organic Adjusted EBITDAaL
                        </div>
                        <div class="cmr-qr-highlight-item">
                            €3.1 billion total shareholder returns in FY26
                        </div>
                    </div>

                </div>

                <!-- Bottom Action Bar (Absolute positioned) -->
                <div class="cmr-qr-action-bar-wrap">
                    <div class="cmr-qr-action-bar">
                        
                        <a href="#" class="cmr-qr-action-item">
                            <div class="cmr-qr-action-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="12" y2="18"></line><line x1="15" y1="15" x2="12" y2="18"></line></svg>
                            </div>
                            <div class="cmr-qr-action-text">Download Report PDF <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg></div>
                        </a>

                        <a href="#" class="cmr-qr-action-item">
                            <div class="cmr-qr-action-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line><rect x="6" y="7" width="2" height="6"></rect><rect x="11" y="5" width="2" height="8"></rect><rect x="16" y="9" width="2" height="4"></rect></svg>
                            </div>
                            <div class="cmr-qr-action-text">View Presentation <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg></div>
                        </a>

                        <a href="#" class="cmr-qr-action-item">
                            <div class="cmr-qr-action-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><rect x="8" y="13" width="8" height="4"></rect><line x1="12" y1="13" x2="12" y2="17"></line></svg>
                            </div>
                            <div class="cmr-qr-action-text">Download Spreadsheet <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg></div>
                        </a>

                        <a href="#" class="cmr-qr-action-item">
                            <div class="cmr-qr-action-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="2.18" ry="2.18"></rect><line x1="7" y1="2" x2="7" y2="22"></line><line x1="17" y1="2" x2="17" y2="22"></line><line x1="2" y1="12" x2="22" y2="12"></line><line x1="2" y1="7" x2="7" y2="7"></line><line x1="2" y1="17" x2="7" y2="17"></line><line x1="17" y1="17" x2="22" y2="17"></line><line x1="17" y1="7" x2="22" y2="7"></line></svg>
                            </div>
                            <div class="cmr-qr-action-text">CMR, a new chapter <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg></div>
                        </a>

                    </div>
                </div>

            </div>

            <!-- Spacer for overlapping card -->
            <div class="cmr-qr-bottom-spacer"></div>

        </div>
        <?php
        return ob_get_clean();
    }
}

add_shortcode( 'cmr_quarterly_results', 'cmr_quarterly_results_shortcode' );
