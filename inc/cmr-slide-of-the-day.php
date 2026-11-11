<?php
/**
 * CMR Slide of the Day Component
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

function cmr_slide_of_the_day_shortcode( $atts ) {
    ob_start();
    ?>
    <style>
        :root {
            --bg-dark: #070707;
            --teal-accent: #00BFBC;
            --text-gray: #9ca3af;
        }

        * {
            box-sizing: border-box;
        }

        /* Top Spacer for Desktop View */
        .top-spacer {
            background-color: #ffffff;
            height: 60px;
            width: 100%;
        }

        /* Hero Dark Section */
        .hero-section {
            position: relative;
            padding-top: 50px;
            padding-bottom: 60px;
            overflow: visible;
            z-index: 10;
        }

        /* Container wrapper for image */
        .image-container-wrapper {
            position: relative;
            max-width: 440px;
            margin: 0 auto;
        }

        /* Desktop: Teal block protruding ABOVE the dark section and to the RIGHT */
        .teal-backdrop {
            position: absolute;
            top: -90px;
            left: 0;
            width: calc(100% + 55px);
            height: 480px;
            background-color: var(--teal-accent);
            z-index: 1;
        }

        /* Main Image Card */
        .hero-image-card {
            position: relative;
            z-index: 2;
            background-color: #000000;
            width: 100%;
            height: 520px;
            margin-bottom: -110px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);
            overflow: hidden;
        }

        .hero-image-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center bottom;
            display: block;
        }

        /* Stat text overlay in the bottom right of the image */
        .stat-overlay {
            position: absolute;
            bottom: 24px;
            right: 24px;
            z-index: 3;
            text-align: right;
            color: #ffffff;
            pointer-events: none;
        }

        .stat-overlay .stat-number {
            font-size: 2.85rem;
            font-weight: 800;
            line-height: 1;
            letter-spacing: -0.03em;
            margin-bottom: 4px;
            text-shadow: 0 2px 10px rgba(0,0,0,0.8);
        }

        .stat-overlay .stat-label {
            font-size: 0.75rem;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.9);
            letter-spacing: 0.02em;
            margin: 0;
            text-shadow: 0 1px 4px rgba(0,0,0,0.8);
        }

        /* Right Content Column */
        .content-col {
            padding-left: 3.5rem;
            color: #ffffff;
        }

        /* Slide of the Day Badge */
        .badge-tag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #ffffff;
            margin-bottom: 1.5rem;
        }

        .badge-tag i {
            font-size: 0.95rem;
        }

        /* Title */
        .hero-title {
            font-size: clamp(2.4rem, 4vw, 3.25rem);
            font-weight: 600;
            line-height: 1.15;
            letter-spacing: -0.03em;
            color: #ffffff;
            margin-bottom: 1.75rem;
        }

        /* Description Paragraph */
        .hero-desc {
            font-size: 0.95rem;
            line-height: 1.65;
            color: var(--text-gray);
            max-width: 440px;
            margin-bottom: 2.25rem;
            font-weight: 400;
        }

        /* Download Button */
        .btn-download {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background-color: #ffffff;
            color: #000000;
            font-weight: 700;
            font-size: 0.95rem;
            padding: 0.85rem 1.8rem;
            border-radius: 9999px;
            border: none;
            text-decoration: none;
            transition: all 0.25s ease;
        }

        .btn-download:hover {
            background-color: #f3f4f6;
            color: #000000;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(255, 255, 255, 0.25);
        }

        .slide-day-icon {
            width: 16px;
            height: 16px;
            object-fit: contain;
            display: inline-block;
            vertical-align: middle;
        }

        .button-icon {
            width: 13px;
            height: 13px;
            object-fit: contain;
            display: inline-block;
            vertical-align: middle;
            transition: transform 0.25s ease;
        }

        .btn-download:hover .button-icon {
            transform: translate(2px, -2px);
        }

        /* ==========================================================
           MOBILE RESPONSIVE STYLES (EXACT MATCH FOR FIGMA SCREENSHOT)
           ========================================================== */
        @media (max-width: 991.98px) {
            .top-spacer {
                display: none;
            }

            .hero-section {
                padding-top: 35px;
                padding-bottom: 0px !important;
                background-color: var(--bg-dark);
            }

            .hero-section .container {
                padding-bottom: 0px !important;
            }

            .hero-section .row {
                margin-bottom: 0px !important;
            }

            /* Content container */
            .content-col {
                padding: 0 16px;
                text-align: center;
                display: flex;
                flex-direction: column;
                align-items: center;
                margin-bottom: 35px;
            }

            .badge-tag {
                font-size: 0.75rem;
                font-weight: 600;
                letter-spacing: 0.08em;
                margin-bottom: 1.25rem;
                justify-content: center;
            }

            .hero-title {
                font-size: 1.85rem;
                line-height: 1.25;
                font-weight: 600;
                margin-bottom: 1.25rem;
                text-align: center;
                max-width: 100%;
            }

            .hero-desc {
                font-size: 0.88rem;
                line-height: 1.55;
                color: rgba(255, 255, 255, 0.85);
                margin-bottom: 1.75rem;
                text-align: center;
                max-width: 380px;
            }

            .btn-download {
                width: 100%;
                max-width: 330px;
                height: 48px;
                padding: 0 20px;
                font-size: 0.92rem;
                font-weight: 600;
            }

            /* Mobile Image Wrapper & Proper Left-Right Gaps */
            .image-col {
                padding-left: 18px !important;
                padding-right: 18px !important;
                padding-bottom: 0px !important;
                margin-bottom: 0px !important;
                display: flex;
                justify-content: center;
                width: 100%;
            }

            .image-container-wrapper {
                width: 100%;
                max-width: 440px;
                margin: 0 auto;
                margin-bottom: 0px !important;
                position: relative;
            }

            /* Teal Backdrop Frame with clean side spacing */
            .teal-backdrop {
                top: 20%;
                bottom: 0px;
                left: -12px;
                width: calc(100% + 24px);
                height: auto;
                background-color: var(--teal-accent);
                z-index: 1;
            }

            .hero-image-card {
                width: 100%;
                height: 440px;
                margin-bottom: 14px;
                box-shadow: none;
                background-color: #000000;
            }

            .hero-image-card img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                object-position: center bottom;
            }

            .stat-overlay {
                bottom: 18px;
                right: 18px;
            }

            .stat-overlay .stat-number {
                font-size: 2.4rem;
                line-height: 1;
                margin-bottom: 2px;
            }

            .stat-overlay .stat-label {
                font-size: 0.7rem;
            }
        }
    </style>

    <!-- Hero Dark Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center flex-column-reverse flex-lg-row">
                
                <!-- Image Column (Desktop: Left, Mobile: Bottom) -->
                <div class="col-lg-6 col-md-12 image-col">
                    <div class="image-container-wrapper">
                        <!-- Teal Backdrop -->
                        <div class="teal-backdrop"></div>
                        
                        <!-- Main Image Card -->
                        <div class="hero-image-card">
                            <img src="<?php echo esc_url( home_url( '/wp-content/uploads/2026/04/Rectangle-25.png' ) ); ?>" alt="India AI Market">
                            
                            <!-- Stat Overlay -->
                            <div class="stat-overlay">
                                <div class="stat-number">$14.2B</div>
                                <div class="stat-label">Projected Market Value (2028)</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Text / Content Column (Desktop: Right, Mobile: Top) -->
                <div class="col-lg-6 col-md-12">
                    <div class="content-col">
                        <!-- Slide Tag -->
                        <div class="badge-tag">
                            <img src="<?php echo esc_url( home_url( '/wp-content/uploads/2026/05/slide-of-day.svg' ) ); ?>"
                            class="slide-day-icon" alt="Slide of the Day">
                            <span>SLIDE OF THE DAY</span>
                        </div>

                        <!-- Title -->
                        <h1 class="hero-title">
                            India AI market growing<br>at 18% YoY
                        </h1>

                        <!-- Paragraph -->
                        <p class="hero-desc">
                            Our latest study indicates that generative AI adoption among Indian SMEs surpass large enterprises by 2026, driven by localised language models.
                        </p>

                        <!-- CTA Button -->
                        <div class="w-100 d-flex justify-content-center justify-content-lg-start">
                            <a href="#download" class="btn-download">
                                Download free report <img src="<?php echo esc_url( home_url( '/wp-content/uploads/2026/04/Symbol-1.svg' ) ); ?>"
                            class="button-icon" alt="arrow">
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const reportBtn = document.querySelector('.btn-download');
            if (reportBtn) {
                reportBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (typeof elementorProFrontend !== 'undefined' && elementorProFrontend.modules && elementorProFrontend.modules.popup) {
                        elementorProFrontend.modules.popup.showPopup({ id: 7758 });
                    } else {
                        console.log('Elementor Popup JS not loaded');
                    }
                });
            }
        });
    </script>
    <?php
    return ob_get_clean();
}
add_shortcode( 'cmr_slide_of_the_day', 'cmr_slide_of_the_day_shortcode' );
