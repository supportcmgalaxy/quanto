<?php
/**
 * CMR Research Reports Hero Section Shortcode
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! function_exists( 'cmr_research_reports_hero_shortcode' ) ) {
    function cmr_research_reports_hero_shortcode( $atts ) {
        // Shortcode attributes
        $atts = shortcode_atts( array(
            'title'       => 'Research Reports',
            'description' => 'Browse premium research reports across industries, designed to deliver actionable insights and strategic clarity.',
            'bg_image'    => 'https://cmrindia.com/wp-content/uploads/2026/06/Gradients_2-1-scaled.png',
        ), $atts );

        ob_start();
        ?>
        <style>
            .cmr-research-hero {
                background-color: #0a0a0a;
                background-image: url('<?php echo esc_url($atts['bg_image']); ?>');
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
                padding: 100px 20px 80px;
                color: #ffffff;
                text-align: center;
                font-family: 'Instrument Sans', sans-serif !important;
                position: relative;
                overflow: hidden;
            }

            /* Add a dark overlay just in case the image is too bright */
            .cmr-research-hero::before {
                content: '';
                position: absolute;
                top: 0; left: 0; right: 0; bottom: 0;
                background: linear-gradient(135deg, rgba(10,10,10,0.9) 0%, rgba(20,20,20,0.7) 100%);
                z-index: 1;
            }

            .cmr-research-hero-content {
                position: relative;
                z-index: 2;
                max-width: 800px;
                margin: 0 auto;
            }

            .cmr-research-breadcrumbs {
                font-size: 13px;
                color: #a0a0a0;
                margin-bottom: 20px;
                display: flex;
                justify-content: center;
                align-items: center;
                gap: 8px;
            }

            .cmr-research-breadcrumbs a {
                color: #a0a0a0;
                text-decoration: none;
                transition: color 0.3s ease;
            }

            .cmr-research-breadcrumbs a:hover {
                color: #ffffff;
            }
            
            .cmr-research-breadcrumbs span.separator {
                font-size: 10px;
            }

            .cmr-research-hero h1 {
                font-size: 56px;
                font-weight: 600;
                color: #ffffff;
                margin: 0 0 20px 0;
                letter-spacing: -1.5px;
            }

            .cmr-research-hero p.desc {
                font-size: 18px;
                color: #ffffff;
                line-height: 1.6;
                margin: 0 auto 40px auto;
                max-width: 650px;
            }

            /* Search Bar */
            .cmr-research-search-wrapper {
                position: relative;
                max-width: 638px;
                margin: 0 auto 30px auto;
            }

            .cmr-vp-hero-search {
                display: flex;
                align-items: center;
                background: #ffffff;
                border-radius: 50px;
                height: 54px;
                padding: 0 6px 0 20px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.15);
                border: 2px solid transparent;
                transition: all 0.3s ease;
                box-sizing: border-box;
                width: 100%;
            }
            
            .cmr-vp-hero-search:focus-within {
                border-color: #7b4cf6;
                box-shadow: 0 10px 30px rgba(123, 76, 246, 0.25);
            }

            .cmr-vp-hero-search-icon-left {
                margin-right: 14px;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
            }

            .cmr-vp-hero-search-icon-left img {
                width: 24px;
                height: 24px;
                object-fit: contain;
                display: block;
            }

            .cmr-vp-hero-search input[type="text"] {
                flex: 1;
                min-width: 0;
                border: none !important;
                background: transparent !important;
                box-shadow: none !important;
                padding: 0 !important;
                margin: 0 !important;
                height: 100%;
                font-size: 16px;
                font-family: inherit;
                color: #111827;
                outline: none !important;
            }

            .cmr-vp-hero-search input[type="text"]::placeholder {
                color: #9ca3af;
                font-size: 16px;
            }

            .cmr-vp-hero-search-btn {
                background: #6b46c1;
                color: #ffffff;
                border: none;
                width: 42px;
                height: 42px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                transition: background 0.3s ease, transform 0.2s ease;
                padding: 0;
                flex-shrink: 0;
                margin-left: 8px;
            }

            .cmr-vp-hero-search-btn svg {
                width: 17px;
                height: 17px;
                display: block;
            }

            .cmr-vp-hero-search-btn:hover {
                background: #55359e;
                transform: scale(1.05);
            }

            /* Categories / Filters */
            .cmr-research-categories {
                display: flex;
                justify-content: center;
                flex-wrap: wrap;
                gap: 30px;
                margin-top: 30px;
            }

            .cmr-research-categories a {
                color: #ffffff;
                text-decoration: none;
                font-size: 15px;
                font-weight: 500;
                opacity: 0.8;
                transition: opacity 0.3s ease, color 0.3s ease;
            }

            .cmr-research-categories a:hover {
                opacity: 1;
                color: #a78bfa;
            }

            @media (max-width: 768px) {
                .cmr-research-hero h1 {
                    font-size: 40px;
                }
                .cmr-research-hero p.desc {
                    font-size: 16px;
                }
                .cmr-research-categories {
                    gap: 15px;
                }
                .cmr-research-search-wrapper {
                    padding: 0 10px;
                }
                .cmr-vp-hero-search {
                    height: 48px;
                    padding: 0 5px 0 16px;
                }
                .cmr-vp-hero-search-icon-left {
                    margin-right: 10px;
                }
                .cmr-vp-hero-search-icon-left img {
                    width: 20px;
                    height: 20px;
                }
                .cmr-vp-hero-search input[type="text"] {
                    font-size: 14px;
                }
                .cmr-vp-hero-search input[type="text"]::placeholder {
                    font-size: 14px;
                }
                .cmr-vp-hero-search-btn {
                    width: 38px;
                    height: 38px;
                }
                .cmr-vp-hero-search-btn svg {
                    width: 15px;
                    height: 15px;
                }
            }
        </style>

        <section class="cmr-research-hero">
            <div class="cmr-research-hero-content">
                
                <div class="cmr-research-breadcrumbs">
                    <a href="<?php echo esc_url( home_url('/') ); ?>">Home</a>
                    <span class="separator"><i class="fa-solid fa-chevron-right"></i></span>
                    <span>Research</span>
                </div>

                <h1><?php echo esc_html( $atts['title'] ); ?></h1>
                
                <p class="desc"><?php echo esc_html( $atts['description'] ); ?></p>

                <div class="cmr-research-search-wrapper">
                    <form id="cmr-vp-hero-search-form" class="cmr-vp-hero-search" action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get">
                        <div class="cmr-vp-hero-search-icon-left">
                            <img decoding="async" src="<?php echo esc_url( home_url( '/' ) ); ?>wp-content/uploads/2026/06/cmrlogo-with-oly-c.svg" alt="CMR Logo" style="width: 24px; height: auto;">
                        </div>
                        <input type="text" name="s" placeholder="Search..." value="<?php echo get_search_query(); ?>">
                        <input type="hidden" name="post_type" value="product" />
                        <button type="submit" class="cmr-vp-hero-search-btn" aria-label="<?php esc_attr_e( 'Search Reports', 'quanto' ); ?>">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        </button>
                    </form>
                </div>
            </div>
        </section>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const heroForm = document.getElementById('cmr-vp-hero-search-form');
                if (heroForm) {
                    const heroInput = heroForm.querySelector('input[name="s"]');
                    const latestSearchInput = document.querySelector('input[id^="cmr-lr-search-"]');
                    const latestSearchBtn = document.querySelector('button[id^="cmr-lr-search-btn-"]');
                    const latestSection = document.querySelector('.cmr-latest-section');

                    if (heroInput && latestSearchInput) {
                        heroInput.addEventListener('input', function() {
                            latestSearchInput.value = this.value;
                            latestSearchInput.dispatchEvent(new Event('input', { bubbles: true }));
                        });
                    }

                    heroForm.addEventListener('submit', function(e) {
                        if (latestSearchInput && latestSection) {
                            e.preventDefault();
                            latestSearchInput.value = heroInput ? heroInput.value.trim() : '';
                            latestSearchInput.dispatchEvent(new Event('input', { bubbles: true }));
                            if (latestSearchBtn) {
                                latestSearchBtn.click();
                            }
                            
                            // Scroll down so they can see the results
                            const yOffset = -80; // offset for sticky header
                            const y = latestSection.getBoundingClientRect().top + window.pageYOffset + yOffset;
                            window.scrollTo({top: y, behavior: 'smooth'});
                        }
                    });
                }
            });
        </script>
        <?php
        return ob_get_clean();
    }
}
add_shortcode( 'cmr_research_hero', 'cmr_research_reports_hero_shortcode' );
