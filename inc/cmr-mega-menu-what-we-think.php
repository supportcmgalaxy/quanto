<?php
/* Mega Menu Shortcode: [cmr_mega_menu_what_we_think] */

add_shortcode('cmr_mega_menu_what_we_think', 'cmr_mega_menu_what_we_think_shortcode');

function cmr_mega_menu_what_we_think_shortcode($atts) {
    ob_start();

    // Fetch 1 latest product for the report section (fallback to mock if none)
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => 1,
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC'
    );
    $products = get_posts($args);
    $product = !empty($products) ? $products[0] : null;

    ?>
    <style>
        .cmr-mmt-wrapper {
            position: relative;
            font-family: 'Instrument Sans', sans-serif;
            width: 900px !important;
            max-width: none !important;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
            background: #fff;
            display: flex;
            border-radius: 12px;
        }

        .cmr-mmt-wrapper::before {
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

        .cmr-mmt-left {
            width: 65%;
            padding: 30px;
            background: #fff;
            position: relative;
            z-index: 2;
            border-top-left-radius: 12px;
            border-bottom-left-radius: 12px;
            display: flex;
            gap: 40px;
        }

        .cmr-mmt-col {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .cmr-mmt-right {
            width: 35%;
            padding: 30px;
            background: linear-gradient(135deg, #f3f5ff 0%, #edf9fb 50%, #e6faf7 100%);
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            border-top-right-radius: 12px;
            border-bottom-right-radius: 12px;
        }

        .cmr-mmt-label {
            font-size: 13px;
            font-weight: 600;
            color: #9ba4b5;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 0px;
        }

        .cmr-mmt-item {
            text-decoration: none;
            display: block;
        }
        
        .cmr-mmt-item:hover h4 {
            color: #6A35FF;
        }

        .cmr-mmt-item h4 {
            font-size: 18px;
            font-weight: 600;
            letter-spacing: 0px;
            color: #111;
            margin: 0 0 6px 0;
            transition: color 0.2s ease;
        }

        .cmr-mmt-item p {
            font-size: 14px;
            color: #666;
            margin: 0;
            line-height: 1.4;
        }

        /* Report Card */
        .cmr-mmt-report-card {
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            margin-top: 15px;
            text-decoration: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .cmr-mmt-report-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        }

        .cmr-mmt-report-img {
            width: 100%;
            height: 140px;
            background: #000;
            overflow: hidden;
        }

        .cmr-mmt-report-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .cmr-mmt-report-content {
            padding: 15px;
            display: flex;
            flex-direction: column;
        }

        .cmr-mmt-report-cat {
            font-size: 12px;
            color: #999;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .cmr-mmt-report-cat::before {
            content: '—';
            color: #ccc;
        }

        .cmr-mmt-report-title {
            font-size: 15px;
            font-weight: 600;
            color: #111;
            line-height: 1.3;
            margin-bottom: 10px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .cmr-mmt-report-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .cmr-mmt-report-rating {
            display: flex;
            align-items: center;
            gap: 2px;
            color: #f59e0b;
            font-size: 12px;
        }

        .cmr-mmt-report-rating-count {
            color: #666;
            margin-left: 4px;
        }

        .cmr-mmt-report-price {
            font-size: 16px;
            font-weight: 600;
            color: #111;
        }

        .cmr-mmt-report-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 8px 15px;
            border: 1px solid #111;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 600;
            color: #111;
            background: transparent;
            transition: all 0.2s ease;
            width: max-content;
        }

        .cmr-mmt-report-card:hover .cmr-mmt-report-btn {
            background: #111;
            color: #fff;
        }

        @media (max-width: 768px) {
            .cmr-mmt-wrapper {
                flex-direction: column;
            }
            .cmr-mmt-left, .cmr-mmt-right {
                width: 100%;
            }
            .cmr-mmt-left {
                flex-direction: column;
            }
        }
                        @media (max-width: 1024px) {
            .cmr-mmt-right { display: none !important; }
            .cmr-has-mega-menu-think .cmr-mmt-wrapper-outer {
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
            .cmr-has-mega-menu-think.cmr-mobile-open > .cmr-mmt-wrapper-outer {
                display: block !important;
            }
            .cmr-has-mega-menu-think .cmr-mmt-wrapper-outer::before {
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

    <div class="cmr-mmt-wrapper">
        <div class="cmr-mmt-left">
            <div class="cmr-mmt-col">
                <div class="cmr-mmt-label">WHAT WE THINK</div>
                <a href="<?php echo esc_url( home_url( '/research-reports/' ) ); ?>" class="cmr-mmt-item">
                    <h4>Research Reports</h4>
                    <p>Data-driven insights and forecasts</p>
                </a>
                <a href="<?php echo esc_url( home_url( '/viewpoints/' ) ); ?>" class="cmr-mmt-item">
                    <h4>View Points</h4>
                    <p>Expert analysis on emerging trends</p>
                </a>
                <a href="<?php echo esc_url( home_url( '/market-updates/' ) ); ?>" class="cmr-mmt-item">
                    <h4>Market Updates</h4>
                    <p>Latest developments shaping industries</p>
                </a>
            </div>
            <div class="cmr-mmt-col">
                <div class="cmr-mmt-label">CMR LIVE</div>
                <a href="<?php echo esc_url( home_url( '/cmr-live/' ) ); ?>" class="cmr-mmt-item">
                    <h4>Podcasts</h4>
                    <p>Expert conversations on trends and innovation.</p>
                </a>
                <a href="<?php echo esc_url( home_url( '/cmr-live/#top-view' ) ); ?>" class="cmr-mmt-item">
                    <h4>Top View</h4>
                    <p>Watch expert perspectives and industry conversations.</p>
                </a>
            </div>
        </div>

        <div class="cmr-mmt-right">
            <div class="cmr-mmt-label">REPORT</div>
            
            <?php if ($product) : 
                $price = get_post_meta($product->ID, '_regular_price', true);
                $price_display = $price ? '$' . $price : '$450';
                $thumbnail_url = get_the_post_thumbnail_url( $product->ID, 'medium' );
                if ( ! $thumbnail_url ) {
                    $thumbnail_url = quanto_get_placeholder_image_uri();
                }
            ?>
            <a href="<?php echo esc_url(get_permalink($product->ID)); ?>" class="cmr-mmt-report-card">
                <div class="cmr-mmt-report-img">
                    <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo esc_attr(get_the_title($product->ID)); ?>">
                </div>
                <div class="cmr-mmt-report-content">
                    <div class="cmr-mmt-report-cat">IT & Telecom</div>
                    <div class="cmr-mmt-report-title"><?php echo esc_html(get_the_title($product->ID)); ?></div>
                    <div class="cmr-mmt-report-meta">
                        <div class="cmr-mmt-report-rating">
                            ★ ★ ★ ★ ★ <span class="cmr-mmt-report-rating-count">(2)</span>
                        </div>
                        <div class="cmr-mmt-report-price"><?php echo esc_html($price_display); ?></div>
                    </div>
                    <div class="cmr-mmt-report-btn">
                        Download Report ↓
                    </div>
                </div>
            </a>
            <?php else : ?>
            <a href="#" class="cmr-mmt-report-card">
                <div class="cmr-mmt-report-img">
                    <img src="<?php echo esc_url( quanto_get_placeholder_image_uri() ); ?>" alt="Report">
                </div>
                <div class="cmr-mmt-report-content">
                    <div class="cmr-mmt-report-cat">IT & Telecom</div>
                    <div class="cmr-mmt-report-title">Steering Innovation: Technology Trends and Opportunities in India's Startup</div>
                    <div class="cmr-mmt-report-meta">
                        <div class="cmr-mmt-report-rating">
                            ★ ★ ★ ★ ★ <span class="cmr-mmt-report-rating-count">(2)</span>
                        </div>
                        <div class="cmr-mmt-report-price">$450</div>
                    </div>
                    <div class="cmr-mmt-report-btn">
                        Download Report ↓
                    </div>
                </div>
            </a>
            <?php endif; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

add_action('wp_footer', 'cmr_inject_what_we_think_mega_menu', 100);
function cmr_inject_what_we_think_mega_menu() {
    $mega_menu_html = do_shortcode('[cmr_mega_menu_what_we_think]');
    ?>
    <div id="cmr-hidden-mega-menu-think" style="display: none;">
        <?php echo $mega_menu_html; ?>
    </div>

    <style>
        .cmr-has-mega-menu-think {
            position: relative !important;
        }
        
        .cmr-mmt-wrapper-outer {
            position: absolute !important;
            top: 60px;
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

        .elementor-nav-menu--main .elementor-item:hover + .cmr-mmt-wrapper-outer,
        .cmr-has-mega-menu-think:hover .cmr-mmt-wrapper-outer {
            opacity: 1;
            visibility: visible;
            transform: translateX(-50%) translateY(0);
            top: 60px !important;
        }

        .cmr-has-mega-menu-think.cmr-menu-force-closed .cmr-mmt-wrapper-outer,
        .cmr-has-mega-menu-think .cmr-mmt-wrapper-outer.cmr-closed {
            opacity: 0 !important;
            visibility: hidden !important;
            pointer-events: none !important;
        }

        .cmr-has-mega-menu-think > a .sub-arrow {
            display: none !important;
        }
            
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var megaMenuTemplate = document.getElementById('cmr-hidden-mega-menu-think');
            if (!megaMenuTemplate) return;

            function bindMegaMenuLinks(wrapperOuter, parentLi) {
                if (wrapperOuter.dataset.cmrBound) return;
                wrapperOuter.dataset.cmrBound = 'true';

                parentLi.addEventListener('mouseleave', function() {
                    parentLi.classList.remove('cmr-menu-force-closed');
                    wrapperOuter.classList.remove('cmr-closed');
                });

                wrapperOuter.querySelectorAll('a').forEach(function(link) {
                    link.addEventListener('click', function(e) {
                        var href = link.getAttribute('href') || '';
                        var hashIdx = href.indexOf('#');

                        wrapperOuter.classList.add('cmr-closed');
                        parentLi.classList.add('cmr-menu-force-closed');
                        parentLi.classList.remove('cmr-mobile-open');

                        if (hashIdx !== -1) {
                            var targetId = href.substring(hashIdx + 1);
                            var targetEl = document.getElementById(targetId);

                            var linkUrl;
                            try {
                                linkUrl = new URL(href, window.location.href);
                            } catch(err) {
                                linkUrl = null;
                            }

                            var isCurrentPage = (!linkUrl || linkUrl.pathname === window.location.pathname || href.startsWith('#'));

                            if (isCurrentPage && targetEl) {
                                e.preventDefault();
                                e.stopPropagation();

                                if (window.history && window.history.pushState) {
                                    window.history.pushState(null, null, '#' + targetId);
                                }
                                if (window.cmrUpdateActiveMegaLinks) {
                                    window.cmrUpdateActiveMegaLinks();
                                }

                                var headerOffset = 110;
                                var elPos = targetEl.getBoundingClientRect().top + window.pageYOffset;
                                window.scrollTo({
                                    top: Math.max(0, elPos - headerOffset),
                                    behavior: 'smooth'
                                });
                            }
                        } else {
                            var linkUrl;
                            try {
                                linkUrl = new URL(href, window.location.href);
                            } catch(err) {
                                linkUrl = null;
                            }
                            if (linkUrl && linkUrl.pathname === window.location.pathname) {
                                e.preventDefault();
                                if (window.history && window.history.pushState) {
                                    window.history.pushState(null, null, window.location.pathname);
                                }
                                window.scrollTo({
                                    top: 0,
                                    behavior: 'smooth'
                                });
                            }
                        }
                    });
                });
            }

            function injectMegaMenu() {
                var found = false;
                var navLinks = document.querySelectorAll('.menu-item > a, .menu-item > button, .elementor-item');
                navLinks.forEach(function(link) {
                    var text = link.innerText.trim().toLowerCase();
                    if (text === 'what we think') {
                        var parentLi = link.closest('li, .menu-item');
                        if (parentLi) {
                            if (!parentLi.classList.contains('cmr-has-mega-menu-think')) {
                                parentLi.classList.add('cmr-has-mega-menu-think');
                                var wrapperOuter = document.createElement('div');
                                wrapperOuter.className = 'cmr-mmt-wrapper-outer';
                                Array.from(megaMenuTemplate.childNodes).forEach(function(node) { wrapperOuter.appendChild(node.cloneNode(true)); });
                                parentLi.appendChild(wrapperOuter);
                                bindMegaMenuLinks(wrapperOuter, parentLi);
                            } else if (parentLi.querySelector('.cmr-mmt-wrapper-outer')) {
                                bindMegaMenuLinks(parentLi.querySelector('.cmr-mmt-wrapper-outer'), parentLi);
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
                        if (text === 'what we think') {
                            var parentLi = link.closest('.cmr-has-mega-menu-think');
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



