<?php
/**
 * Shortcode for Performance Results Accordion Component
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! function_exists( 'cmr_performance_results_shortcode' ) ) {
    function cmr_performance_results_shortcode( $atts ) {
        ob_start();
        ?>
        <style>
            .cmr-pr-section {
                font-family: 'Instrument Sans', sans-serif !important;
                background: #f8f9fb;
                padding: 80px 20px;
                width: 100%;
                box-sizing: border-box;
            }

            .cmr-pr-container {
                max-width: 1200px;
                margin: 0 auto;
            }

            /* Header */
            .cmr-pr-header {
                margin-bottom: 60px;
            }

            .cmr-pr-title {
                font-size: 42px;
                font-weight: 600;
                color: #111;
                margin: 0 0 15px 0;
                letter-spacing: -1px;
            }

            .cmr-pr-subtitle {
                font-size: 15px;
                color: #555;
                max-width: 800px;
                line-height: 1.6;
                margin: 0;
            }

            /* Accordion */
            .cmr-pr-accordion-item {
                border-bottom: 1px solid #ddd;
            }
            .cmr-pr-accordion-item:first-child {
                border-top: 1px solid #ddd;
            }

            .cmr-pr-accordion-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 30px 0;
                cursor: pointer;
                background: transparent;
                border: none;
                width: 100%;
                text-align: left;
                font-family: inherit;
            }

            .cmr-pr-accordion-title {
                font-size: 24px;
                font-weight: 600;
                color: #111;
            }

            .cmr-pr-accordion-icon {
                width: 24px;
                height: 24px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #111;
                transition: transform 0.3s ease;
            }
            .cmr-pr-accordion-icon svg {
                width: 20px;
                height: 20px;
            }

            /* The content wrapper for smooth expanding */
            .cmr-pr-accordion-content-wrap {
                display: grid;
                grid-template-rows: 0fr;
                transition: grid-template-rows 0.3s ease;
                overflow: hidden;
            }

            .cmr-pr-accordion-inner {
                min-height: 0;
                padding-bottom: 0;
                transition: padding 0.3s ease;
            }

            /* Active State */
            .cmr-pr-accordion-item.active .cmr-pr-accordion-content-wrap {
                grid-template-rows: 1fr;
            }
            .cmr-pr-accordion-item.active .cmr-pr-accordion-inner {
                padding-bottom: 40px;
            }
            .cmr-pr-accordion-item.active .cmr-pr-accordion-icon {
                transform: rotate(180deg); /* If arrow is down, rotate up. Or if arrow is right, rotate down. The image shows down for expanded, right for collapsed. Let's start with right, rotate to down. */
            }
            
            /* Actually, the image shows DOWN when expanded, RIGHT when collapsed. */
            .cmr-pr-accordion-item:not(.active) .cmr-pr-accordion-icon {
                transform: rotate(-90deg); /* Points right */
            }

            /* Grid Layout (FY26) */
            .cmr-pr-grid {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 20px;
            }

            .cmr-pr-card {
                background: #fff;
                display: flex;
                flex-direction: column;
            }

            .cmr-pr-card-img {
                height: 220px;
                background: linear-gradient(135deg, #e3cbf9 0%, #a0e8f0 50%, #87f2e1 100%);
                position: relative;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            /* The logo SVG in the image */
            .cmr-pr-card-logo {
                width: 100px;
                height: 100px;
            }

            .cmr-pr-card-badge {
                position: absolute;
                top: 20px;
                left: 20px;
                background: #fff;
                color: #111;
                font-size: 11px;
                font-weight: 600;
                padding: 4px 10px;
                border-radius: 20px;
                z-index: 2;
            }

            .cmr-pr-card-content {
                padding: 30px;
                flex: 1;
                display: flex;
                flex-direction: column;
            }

            .cmr-pr-card-meta {
                display: flex;
                justify-content: space-between;
                align-items: center;
                font-size: 11px;
                color: #777;
                margin-bottom: 20px;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }
            
            .cmr-pr-card-meta-left {
                display: flex;
                align-items: center;
                gap: 10px;
            }
            
            .cmr-pr-card-meta-left::before {
                content: '';
                display: block;
                width: 20px;
                height: 1px;
                background: #ccc;
            }

            .cmr-pr-card-meta-right {
                font-weight: 600;
                color: #111;
            }

            .cmr-pr-card-title {
                font-size: 20px;
                font-weight: 600;
                color: #111;
                line-height: 1.3;
                margin-bottom: 15px;
            }

            .cmr-pr-card-desc {
                font-size: 14px;
                color: #555;
                line-height: 1.5;
                margin-bottom: 30px;
            }

            .cmr-pr-card-link {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                font-size: 14px;
                font-weight: 600;
                color: #111;
                text-decoration: none;
                border-bottom: 1px solid #111;
                padding-bottom: 2px;
                width: max-content;
                margin-top: auto;
                transition: color 0.2s ease, border-color 0.2s ease;
            }

            .cmr-pr-card-link:hover {
                color: #6A35FF;
                border-bottom-color: #6A35FF;
            }
            .cmr-pr-card-link svg {
                width: 14px;
                height: 14px;
            }

            /* List Layout (FY25) */
            .cmr-pr-list {
                display: flex;
                flex-direction: column;
            }

            .cmr-pr-list-item {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 25px 0;
                border-bottom: 1px solid #eaeaea;
                text-decoration: none;
                position: relative;
            }
            .cmr-pr-list-item:first-child {
                border-top: 1px solid #eaeaea;
            }

            /* Vertical colored accent line */
            .cmr-pr-list-item::before {
                content: '';
                position: absolute;
                left: 0;
                top: 50%;
                transform: translateY(-50%);
                width: 3px;
                height: 24px;
                background: linear-gradient(180deg, #37c4e5, #8a4bdf); /* cyan to purple */
            }

            .cmr-pr-list-title {
                padding-left: 20px;
                font-size: 15px;
                font-weight: 500;
                color: #111;
            }

            .cmr-pr-list-action {
                font-size: 13px;
                font-weight: 600;
                color: #111;
                display: flex;
                align-items: center;
                gap: 6px;
            }
            
            .cmr-pr-list-item:hover .cmr-pr-list-title,
            .cmr-pr-list-item:hover .cmr-pr-list-action {
                color: #6A35FF;
            }

            @media (max-width: 992px) {
                .cmr-pr-grid {
                    grid-template-columns: 1fr;
                }
            }
        </style>

        <div class="cmr-pr-section">
            <div class="cmr-pr-container">
                
                <div class="cmr-pr-header">
                    <h2 class="cmr-pr-title">Performance Results</h2>
                    <p class="cmr-pr-subtitle">At CMR, we strive to deliver only the best to our clients and achieve better year-on-year growth. This shows in our results.</p>
                </div>

                <div class="cmr-pr-accordion">

                    <!-- Results FY26 (Expanded by default) -->
                    <div class="cmr-pr-accordion-item active">
                        <button class="cmr-pr-accordion-header">
                            <span class="cmr-pr-accordion-title">Results FY26</span>
                            <span class="cmr-pr-accordion-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                            </span>
                        </button>
                        <div class="cmr-pr-accordion-content-wrap">
                            <div class="cmr-pr-accordion-inner">
                                
                                <div class="cmr-pr-grid">
                                    <!-- Q3 -->
                                    <div class="cmr-pr-card">
                                        <div class="cmr-pr-card-img">
                                            <div class="cmr-pr-card-badge">Q3 Result</div>
                                            <!-- SVG Logo placeholder based on image -->
                                            <img class="cmr-pr-card-logo" src="<?php echo esc_url( home_url( '/wp-content/uploads/2026/06/cmrlogo-with-oly-c.svg' ) ); ?>" alt="CMR Logo">
                                        </div>
                                        <div class="cmr-pr-card-content">
                                            <div class="cmr-pr-card-meta">
                                                <div class="cmr-pr-card-meta-left">Quarterly Results</div>
                                                <div class="cmr-pr-card-meta-right">10 JAN</div>
                                            </div>
                                            <div class="cmr-pr-card-title">CMR Group announces FY26- Q3 Preliminary Results</div>
                                            <div class="cmr-pr-card-desc">CMR has today announced preliminary results for the financial year ended 31 March 2026.</div>
                                            <a href="#" class="cmr-pr-card-link">View Report <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg></a>
                                        </div>
                                    </div>

                                    <!-- Q2 -->
                                    <div class="cmr-pr-card">
                                        <div class="cmr-pr-card-img">
                                            <div class="cmr-pr-card-badge">Q2 Result</div>
                                            <img class="cmr-pr-card-logo" src="<?php echo esc_url( home_url( '/wp-content/uploads/2026/06/cmrlogo-with-oly-c.svg' ) ); ?>" alt="CMR Logo">
                                        </div>
                                        <div class="cmr-pr-card-content">
                                            <div class="cmr-pr-card-meta">
                                                <div class="cmr-pr-card-meta-left">Quarterly Results</div>
                                                <div class="cmr-pr-card-meta-right">10 OCT</div>
                                            </div>
                                            <div class="cmr-pr-card-title">CMR Group announces FY26- Q2 Preliminary Results</div>
                                            <div class="cmr-pr-card-desc">The acquisition marks a major strategic move aimed at strengthening market presence...</div>
                                            <a href="#" class="cmr-pr-card-link">View Report <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg></a>
                                        </div>
                                    </div>

                                    <!-- Q1 -->
                                    <div class="cmr-pr-card">
                                        <div class="cmr-pr-card-img">
                                            <div class="cmr-pr-card-badge">Q1 Result</div>
                                            <img class="cmr-pr-card-logo" src="<?php echo esc_url( home_url( '/wp-content/uploads/2026/06/cmrlogo-with-oly-c.svg' ) ); ?>" alt="CMR Logo">
                                        </div>
                                        <div class="cmr-pr-card-content">
                                            <div class="cmr-pr-card-meta">
                                                <div class="cmr-pr-card-meta-left">Quarterly Results</div>
                                                <div class="cmr-pr-card-meta-right">10 JUN</div>
                                            </div>
                                            <div class="cmr-pr-card-title">CMR Group announces FY26- Q1 Preliminary Results</div>
                                            <div class="cmr-pr-card-desc">CMR has announced its trading update for the second quarter of the 2026 financial year.</div>
                                            <a href="#" class="cmr-pr-card-link">View Report <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg></a>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Results FY25 (Expanded by default) -->
                    <div class="cmr-pr-accordion-item active">
                        <button class="cmr-pr-accordion-header">
                            <span class="cmr-pr-accordion-title">Results FY25</span>
                            <span class="cmr-pr-accordion-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                            </span>
                        </button>
                        <div class="cmr-pr-accordion-content-wrap">
                            <div class="cmr-pr-accordion-inner">
                                
                                <div class="cmr-pr-list">
                                    <a href="#" class="cmr-pr-list-item">
                                        <div class="cmr-pr-list-title">Annual Report</div>
                                        <div class="cmr-pr-list-action">Download <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="width:12px;height:12px;"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg></div>
                                    </a>
                                    <a href="#" class="cmr-pr-list-item">
                                        <div class="cmr-pr-list-title">Press Release</div>
                                        <div class="cmr-pr-list-action">Download <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="width:12px;height:12px;"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg></div>
                                    </a>
                                    <a href="#" class="cmr-pr-list-item">
                                        <div class="cmr-pr-list-title">FY25-Q4</div>
                                        <div class="cmr-pr-list-action">Download <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="width:12px;height:12px;"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg></div>
                                    </a>
                                    <a href="#" class="cmr-pr-list-item">
                                        <div class="cmr-pr-list-title">FY25-Q3</div>
                                        <div class="cmr-pr-list-action">Download <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="width:12px;height:12px;"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg></div>
                                    </a>
                                    <a href="#" class="cmr-pr-list-item">
                                        <div class="cmr-pr-list-title">FY25-Q2</div>
                                        <div class="cmr-pr-list-action">Download <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="width:12px;height:12px;"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg></div>
                                    </a>
                                    <a href="#" class="cmr-pr-list-item">
                                        <div class="cmr-pr-list-title">FY25-Q1</div>
                                        <div class="cmr-pr-list-action">Download <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="width:12px;height:12px;"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg></div>
                                    </a>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Results FY24 (Collapsed by default) -->
                    <div class="cmr-pr-accordion-item">
                        <button class="cmr-pr-accordion-header">
                            <span class="cmr-pr-accordion-title">Results FY24</span>
                            <span class="cmr-pr-accordion-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                            </span>
                        </button>
                        <div class="cmr-pr-accordion-content-wrap">
                            <div class="cmr-pr-accordion-inner">
                                <div class="cmr-pr-list">
                                    <a href="#" class="cmr-pr-list-item"><div class="cmr-pr-list-title">Annual Report</div><div class="cmr-pr-list-action">Download <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="width:12px;height:12px;"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg></div></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Results FY23 (Collapsed by default) -->
                    <div class="cmr-pr-accordion-item">
                        <button class="cmr-pr-accordion-header">
                            <span class="cmr-pr-accordion-title">Results FY23</span>
                            <span class="cmr-pr-accordion-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                            </span>
                        </button>
                        <div class="cmr-pr-accordion-content-wrap">
                            <div class="cmr-pr-accordion-inner">
                                <div class="cmr-pr-list">
                                    <a href="#" class="cmr-pr-list-item"><div class="cmr-pr-list-title">Annual Report</div><div class="cmr-pr-list-action">Download <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="width:12px;height:12px;"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg></div></a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const headers = document.querySelectorAll('.cmr-pr-accordion-header');
                headers.forEach(header => {
                    header.addEventListener('click', function() {
                        const item = this.closest('.cmr-pr-accordion-item');
                        item.classList.toggle('active');
                    });
                });
            });
        </script>
        <?php
        return ob_get_clean();
    }
}

add_shortcode( 'cmr_performance_results', 'cmr_performance_results_shortcode' );
