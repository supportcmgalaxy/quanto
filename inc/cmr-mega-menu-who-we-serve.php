<?php
/* Mega Menu Shortcode: [cmr_mega_menu_who_we_serve] */

add_shortcode('cmr_mega_menu_who_we_serve', 'cmr_mega_menu_who_we_serve_shortcode');

function cmr_mega_menu_who_we_serve_shortcode($atts) {
    ob_start();
    ?>
    <style>
        .cmr-mms-wrapper {
            position: relative;
            font-family: 'Instrument Sans', sans-serif;
            width: 900px !important;
            max-width: none !important;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
            background: #fff;
            overflow: visible;
        }
        
        /* The top triangle arrow */
        .cmr-mms-wrapper::before {
            content: '';
            position: absolute;
            top: -8px;
            left: 50%;
            transform: translateX(-50%) rotate(45deg);
            width: 16px;
            height: 16px;
            background: #fff;
            box-shadow: -3px -3px 5px rgba(0,0,0,0.03);
            border-radius: 2px;
            z-index: 0;
        }

        .cmr-mms-top {
            padding: 0px 30px 30px;
            position: relative;
            z-index: 1;
            background: #fff;
            border-radius: 12px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
        }

        .cmr-mms-col {
            display: flex;
            flex-direction: column;
            gap: 28px;
        }

        .cmr-mms-label {
            font-size: 13px;
            font-weight: 600;
            color: #9ba4b5;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 0px;
        }

        .cmr-mms-item {
            text-decoration: none;
            display: block;
        }
        
        .cmr-mms-item:hover h4 {
            color: #6A35FF;
        }

        .cmr-mms-item-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 6px;
        }

        .cmr-mms-item h4 {
            font-size: 18px;
            font-weight: 600;
            letter-spacing: 0px;
            color: #111;
            margin: 0;
            transition: color 0.2s ease;
        }

        .cmr-mms-badge {
            font-size: 12px;
            font-weight: 600;
            color: #0b8a4f;
            background: #e6f7ec;
            padding: 2px 8px;
            border-radius: 12px;
        }

        .cmr-mms-item p {
            font-size: 15px;
            color: #666;
            margin: 0;
            line-height: 1.4;
        }

        @media (max-width: 768px) {
            .cmr-mms-top {
                grid-template-columns: 1fr;
            }
        }
        @media (max-width: 1024px) {
            .cmr-has-mega-menu-serve .cmr-mms-wrapper {
                position: static !important;
                transform: none !important;
                width: 100% !important;
                box-shadow: none !important;
                display: none;
                opacity: 1;
                visibility: visible;
                padding-top: 0;
                margin-top: 0;
            }
            .cmr-has-mega-menu-serve.cmr-mobile-open > .cmr-mms-wrapper {
                display: block !important;
            }
            .cmr-has-mega-menu-serve .cmr-mms-wrapper::before {
                display: none !important;
            }
            .cmr-mm-grid {
                grid-template-columns: 1fr !important;
                gap: 15px !important;
            }
            .cmr-mm-bottom-content {
                flex-direction: column !important;
                align-items: flex-start !important;
            }
        }
    </style>

    <div class="cmr-mms-wrapper">
        <div class="cmr-mms-top">
            <div class="cmr-mms-col">
                <div class="cmr-mms-label">WHO WE SERVE</div>
                
                <a href="<?php echo esc_url( home_url( '/ai/' ) ); ?>" class="cmr-mms-item">
                    <div class="cmr-mms-item-header">
                        <h4>AI</h4>
                        <span class="cmr-mms-badge">New</span>
                    </div>
                    <p>Adoption trends and enterprise returns</p>
                </a>

                <a href="<?php echo esc_url( home_url( '/automotive/' ) ); ?>" class="cmr-mms-item">
                    <div class="cmr-mms-item-header">
                        <h4>Automotive</h4>
                        <span class="cmr-mms-badge">New</span>
                    </div>
                    <p>Software-defined vehicles and mobility shifts</p>
                </a>
                
                <a href="<?php echo esc_url( home_url( '/consumer-tech/' ) ); ?>" class="cmr-mms-item">
                    <div class="cmr-mms-item-header">
                        <h4>Consumer Tech</h4>
                    </div>
                    <p>Device demand and buyer behaviour</p>
                </a>
                
                <a href="<?php echo esc_url( home_url( '/digital-supply-chain/' ) ); ?>" class="cmr-mms-item">
                    <div class="cmr-mms-item-header">
                        <h4>Digital Supply Chain</h4>
                    </div>
                    <p>Automation, visibility and resilience</p>
                </a>
            </div>
            
            <div class="cmr-mms-col">
                <div class="cmr-mms-label" style="visibility: hidden;">&nbsp;</div>

                <a href="<?php echo esc_url( home_url( '/enterprise-tech/' ) ); ?>" class="cmr-mms-item">
                    <div class="cmr-mms-item-header">
                        <h4>Enterprise Tech</h4>
                        <span class="cmr-mms-badge">New</span>
                    </div>
                    <p>Cloud, data centre and IT budget trends</p>
                </a>

                <a href="<?php echo esc_url( home_url( '/it-telecom/' ) ); ?>" class="cmr-mms-item">
                    <div class="cmr-mms-item-header">
                        <h4>IT & Telecom</h4>
                    </div>
                    <p>From devices to networks to cloud</p>
                </a>

                <a href="<?php echo esc_url( home_url( '/msme/' ) ); ?>" class="cmr-mms-item">
                    <div class="cmr-mms-item-header">
                        <h4>MSME</h4>
                        <span class="cmr-mms-badge">New</span>
                    </div>
                    <p>Digital adoption and tech spend in small business</p>
                </a>
                
                <a href="<?php echo esc_url( home_url( '/semiconductors/' ) ); ?>" class="cmr-mms-item">
                    <div class="cmr-mms-item-header">
                        <h4>Semiconductors</h4>
                        <span class="cmr-mms-badge">New</span>
                    </div>
                    <p>Foundry, memory and AI silicon trends</p>
                </a>
            </div>
        </div>
    <?php
    return ob_get_clean();
}

// Automatically inject the mega menu into the nav bar
add_action('wp_footer', 'cmr_inject_who_we_serve_mega_menu', 100);
function cmr_inject_who_we_serve_mega_menu() {
    // Generate the mega menu HTML
    $mega_menu_html = do_shortcode('[cmr_mega_menu_who_we_serve]');
    ?>
    <div id="cmr-hidden-mega-menu-serve" style="display: none;">
        <?php echo $mega_menu_html; ?>
    </div>

    <style>
        /* CSS to make the nav item act as a dropdown wrapper */
        .cmr-has-mega-menu-serve {
            position: relative !important;
        }
        
        .cmr-mms-wrapper {
            position: absolute !important;
            top: 80px;
            left: 50%;
            transform: translateX(-50%);
            width: max-content !important;
            max-width: none !important;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease, transform 0.3s ease;
            padding-top: 15px;
            z-index: 9999;
        }

        /* Show on hover */
        .elementor-nav-menu--main .elementor-item:hover + .cmr-mms-wrapper-outer,
        .cmr-has-mega-menu-serve:hover .cmr-mms-wrapper-outer,
        .elementor-nav-menu--main .elementor-item:hover + .cmr-mms-wrapper,
        .cmr-has-mega-menu-serve:hover .cmr-mms-wrapper {
            opacity: 1;
            visibility: visible;
            transform: translateX(-50%) translateY(0);
            top: 80px !important;
        }

        /* Hide the default submenu arrow if there is one */
        .cmr-has-mega-menu-serve > a .sub-arrow {
            display: none !important;
        }
            
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var megaMenuTemplate = document.getElementById('cmr-hidden-mega-menu-serve');
            if (!megaMenuTemplate) return;

            function injectMegaMenu() {
                var found = false;
                var navLinks = document.querySelectorAll('.menu-item > a, .menu-item > button, .elementor-item');
                navLinks.forEach(function(link) {
                    var text = link.innerText.trim().toLowerCase();
                    if (text === 'who we serve') {
                        var parentLi = link.closest('li, .menu-item');
                        if (parentLi) {
                            if (!parentLi.classList.contains('cmr-has-mega-menu-serve')) {
                                parentLi.classList.add('cmr-has-mega-menu-serve');
                                Array.from(megaMenuTemplate.childNodes).forEach(function(node) { parentLi.appendChild(node.cloneNode(true)); });
                            }
                            found = true;
                        }
                    }
                });
                return found;
            }

            if (!injectMegaMenu()) {
                var mmAttempts = 0;
                var mmTimer = setInterval(function() {
                    mmAttempts++;
                    if (injectMegaMenu() || mmAttempts >= 8) {
                        clearInterval(mmTimer);
                    }
                }, 500);
            }

            document.addEventListener('click', function(e) {
                if (window.innerWidth <= 1024) {
                    var link = e.target.closest('a, button');
                    if (link) {
                        var text = link.innerText.trim().toLowerCase();
                        if (text === 'who we serve') {
                            var parentLi = link.closest('.cmr-has-mega-menu-serve');
                            if (parentLi) {
                                e.preventDefault();
                                e.stopPropagation();
                                parentLi.classList.toggle('cmr-mobile-open');
                            }
                        }
                    }
                }
            }, true);
        });
    </script>
    <?php
}



