<?php
/* Mega Menu Shortcode: [cmr_mega_menu_what_we_do] */

add_shortcode('cmr_mega_menu_what_we_do', 'cmr_mega_menu_what_we_do_shortcode');

function cmr_get_mmw_posts($slug, $fallback_offset) {
    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => 3,
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC',
        'category_name'  => $slug
    );
    $posts = get_posts($args);
    if (count($posts) < 3) {
        $args['category_name'] = '';
        $args['offset'] = $fallback_offset;
        $posts = get_posts($args);
    }
    return $posts;
}

function cmr_mega_menu_what_we_do_shortcode($atts) {
    ob_start();

    // Content for tabs
    $tabs_content = array(
        'cmr-mmw-list-industry' => array(
            'label' => 'INDUSTRY INTELLIGENCE',
            'heading' => 'Industry Intelligence that drives smarter business decisions',
            'desc' => 'Access real-time industry intelligence, expert analysis and strategic insights to stay ahead of market shifts and competitive dynamics.',
            'list' => array(
                'Track market trends and shifts',
                'Identify growth opportunities',
                'Assess future market outlook',
                'Make data-driven decisions'
            ),
            'link_text' => 'Explore industry intelligence',
            'link_url' => home_url('/industry-intelligence/'),
            'bottom_text' => 'Talk to our analysts for tailored recommendations across your sector.',
            'bottom_link_text' => 'Get Industry insights',
            'bottom_link_url' => home_url('/insights/')
        ),
        'cmr-mmw-list-consulting' => array(
            'label' => 'CONSULTING & ADVISORY',
            'heading' => 'Consulting & Advisory that drives business growth',
            'desc' => 'Expert guidance to help you navigate complex business challenges and accelerate your growth trajectory.',
            'list' => array(
                'Identify and size market opportunities',
                'Develop winning go-to-market strategies',
                'Navigate market and competitive risks',
                'Turn insights into actionable decisions'
            ),
            'link_text' => 'Explore consulting & advisory',
            'link_url' => home_url('/consulting-advisory/'),
            'bottom_text' => 'Talk to our advisors for customized solutions for your business.',
            'bottom_link_text' => 'Get Advisory insights',
            'bottom_link_url' => home_url('/insights/')
        ),
        'cmr-mmw-list-marketing' => array(
            'label' => 'MARKETING SERVICES',
            'heading' => 'Research-backed marketing solutions for your brand',
            'desc' => 'Leverage our deep industry knowledge to create high-impact marketing campaigns that resonate with your target audience.',
            'list' => array(
                'Build brand credibility & influence',
                'Reach the right decision-makers',
                'Convert engagement into demand',
                'Drive measurable market momentum'
            ),
            'link_text' => 'Explore marketing services',
            'link_url' => home_url('/marketing-services/'),
            'bottom_text' => 'Talk to our marketing experts to elevate your brand presence.',
            'bottom_link_text' => 'Get Marketing insights',
            'bottom_link_url' => home_url('/insights/')
        )
    );

    ?>
    <style>
        .cmr-mmw-wrapper {
            position: relative;
            font-family: 'Instrument Sans', sans-serif;
            width: 800px !important;
            max-width: 100% !important;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
            background: #fff;
            display: flex;
        }
        
        .cmr-mmw-wrapper::before {
            content: '';
            position: absolute;
            top: -8px;
            left: 30%;
            transform: translateX(-50%) rotate(45deg);
            width: 16px;
            height: 16px;
            background: #fff; 
            box-shadow: -3px -3px 5px rgba(0,0,0,0.03);
            border-radius: 2px;
            z-index: 0;
        }

        .cmr-mmw-left {
            width: 35%;
            padding: 30px;
            background: #fff;
            position: relative;
            z-index: 2;
            border-top-left-radius: 12px;
            border-bottom-left-radius: 12px;
        }

        .cmr-mmw-right {
            width: 65%;
            padding: 30px;
            background: linear-gradient(135deg, #f3f5ff 0%, #edf9fb 50%, #e6faf7 100%);
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            border-top-right-radius: 12px;
            border-bottom-right-radius: 12px;
        }

        .cmr-mmw-label {
            font-size: 13px;
            font-weight: 600;
            color: #9ba4b5;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 14px;
        }

        .cmr-mmw-menu-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .cmr-mmw-item {
            text-decoration: none;
            display: block;
            position: relative;
            padding: 0 !important;
            margin: 0 !important;
        }
        
        .cmr-mmw-item:hover h4 {
            color: #6A35FF;
        }

        .cmr-mmw-item-header {
            display: flex;
            align-items: center;
            margin-bottom: 6px;
        }

        .cmr-mmw-item h4 {
            font-size: 18px;
            font-weight: 600;
            letter-spacing: 0px;
            color: #111;
            margin: 0;
            transition: color 0.2s ease;
            display: inline-flex;
            align-items: center;
        }

        .cmr-mmw-item p {
            font-size: 14px;
            color: #666;
            margin: 0;
            line-height: 1.4;
        }

        .cmr-mmw-item.active h4 {
            color: #6A35FF !important;
        }

        .cmr-mmw-item.active::after {
            content: '';
            position: absolute;
            top: 50%;
            right: -30px;
            transform: translateY(-50%);
            width: 0;
            height: 0;
            border-top: 12px solid transparent;
            border-bottom: 12px solid transparent;
            border-right: 12px solid #f3f5ff;
            z-index: 2;
        }

        .cmr-mmw-text-inner {
            border-bottom: 2px solid transparent;
            padding-bottom: 2px;
            display: inline-flex;
            align-items: center;
            transition: border-color 0.2s;
        }

        .cmr-mmw-item.active .cmr-mmw-text-inner {
            border-bottom: 2px solid #6A35FF;
        }

        .cmr-mmw-arrow {
            display: none;
            margin-left: 6px;
        }

        .cmr-mmw-item.active .cmr-mmw-arrow {
            display: inline-block;
        }

        /* CONTENT STYLES */
        .cmr-mmw-content-panel {
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .cmr-mmw-heading {
            font-size: 28px;
            line-height: 1.15;
            font-weight: 600;
            color: #111;
            margin: 10px 0 15px 0;
            letter-spacing: -0.5px;
            max-width: 420px;
        }

        .cmr-mmw-desc {
            font-size: 15px;
            color: #444;
            line-height: 1.5;
            margin-bottom: 25px;
            max-width: 480px;
        }

        .cmr-mmw-features {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-bottom: 30px;
        }

        .cmr-mmw-feature-item {
            display: flex;
            align-items: center;
            gap: 15px;
            font-size: 15px;
            color: #333;
            font-weight: 500;
        }

        .cmr-mmw-feature-num {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #eee9fe;
            color: #4e2ecf;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 600;
            flex-shrink: 0;
        }

        .cmr-mmw-explore-link {
            font-size: 16px;
            font-weight: 600;
            color: #4e2ecf !important;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
            padding: 0 !important;
            transition: color 0.2s ease;
        }

        .cmr-mmw-explore-link:hover {
            color: #3b23a3;
        }

        .cmr-mmw-bottom-bar {
            margin-top: auto;
            padding-top: 15px;
            font-size: 14px;
            line-height: 1.4;
        }

        .cmr-mmw-bottom-bar a {
            display: inline-block;
            color: #4e2ecf !important;
            font-weight: 600;
            text-decoration: none;
        }

        .cmr-mmw-bottom-bar a:hover {
            text-decoration: underline;
        }

        }

        @media (max-width: 768px) {
            .cmr-mmw-wrapper {
                flex-direction: column;
            }
            .cmr-mmw-left, .cmr-mmw-right {
                width: 100%;
            }
        }
                        @media (max-width: 1024px) {
            .cmr-mmw-right { display: none !important; }
            .cmr-has-mega-menu-do .cmr-mmw-wrapper-outer {
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
            .cmr-has-mega-menu-do.cmr-mobile-open > .cmr-mmw-wrapper-outer {
                display: block !important;
            }
            .cmr-has-mega-menu-do .cmr-mmw-wrapper-outer::before {
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

    <div class="cmr-mmw-wrapper" id="cmr-mmw-wrapper-inner">
        <div class="cmr-mmw-left">
            <div class="cmr-mmw-label">WHAT WE DO</div>
            <div class="cmr-mmw-menu-list">
                <a href="<?php echo esc_url( home_url( '/industry-intelligence/' ) ); ?>" class="cmr-mmw-item cmr-mmw-item-hover-trigger active" data-target="cmr-mmw-list-industry">
                    <div class="cmr-mmw-item-header">
                        <h4>
                            <span class="cmr-mmw-text-inner">Industry Intelligence
                                <svg class="cmr-mmw-arrow" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg>
                            </span>
                        </h4>
                    </div>
                    <p>Market research and strategic insights</p>
                </a>
                
                <a href="<?php echo esc_url( home_url( '/consulting-advisory/' ) ); ?>" class="cmr-mmw-item cmr-mmw-item-hover-trigger" data-target="cmr-mmw-list-consulting">
                    <div class="cmr-mmw-item-header">
                        <h4>
                            <span class="cmr-mmw-text-inner">Consulting & Advisory 
                                <svg class="cmr-mmw-arrow" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg>
                            </span>
                        </h4>
                    </div>
                    <p>Expert guidance for business growth</p>
                </a>
                
                <a href="<?php echo esc_url( home_url( '/marketing-services/' ) ); ?>" class="cmr-mmw-item cmr-mmw-item-hover-trigger" data-target="cmr-mmw-list-marketing">
                    <div class="cmr-mmw-item-header">
                        <h4>
                            <span class="cmr-mmw-text-inner">Marketing Services
                                <svg class="cmr-mmw-arrow" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg>
                            </span>
                        </h4>
                    </div>
                    <p>Research-backed marketing solutions</p>
                </a>
            </div>
        </div>

        <div class="cmr-mmw-right">
            <?php
            $first_tab = true;
            foreach ($tabs_content as $tab_id => $tab_data) :
            ?>
            <div class="cmr-mmw-content-panel cmr-mmw-posts-list" id="<?php echo esc_attr($tab_id); ?>" style="<?php echo $first_tab ? 'display: flex;' : 'display: none;'; ?>">
                <div class="cmr-mmw-label"><?php echo esc_html($tab_data['label']); ?></div>
                <h2 class="cmr-mmw-heading"><?php echo esc_html($tab_data['heading']); ?></h2>
                <p class="cmr-mmw-desc"><?php echo esc_html($tab_data['desc']); ?></p>
                
                <div class="cmr-mmw-features">
                    <?php 
                    $counter = 1;
                    foreach ($tab_data['list'] as $list_item) : 
                    ?>
                        <div class="cmr-mmw-feature-item">
                            <span class="cmr-mmw-feature-num"><?php echo $counter++; ?></span>
                            <span class="cmr-mmw-feature-text"><?php echo esc_html($list_item); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div>
                    <a href="<?php echo esc_url($tab_data['link_url']); ?>" class="cmr-mmw-explore-link">
                        <?php echo esc_html($tab_data['link_text']); ?> 
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg>
                    </a>
                </div>

            </div>
            <?php 
            $first_tab = false;
            endforeach; 
            ?>
        </div>
    </div>
    
    
    <?php
    return ob_get_clean();
}

// Automatically inject the mega menu into the nav bar
add_action('wp_footer', 'cmr_inject_what_we_do_mega_menu', 100);
function cmr_inject_what_we_do_mega_menu() {
    $mega_menu_html = do_shortcode('[cmr_mega_menu_what_we_do]');
    ?>
    <div id="cmr-hidden-mega-menu-do" style="display: none;">
        <?php echo $mega_menu_html; ?>
    </div>

    <style>
        .cmr-has-mega-menu-do {
            position: relative !important;
        }
        
        .cmr-has-mega-menu-do > ul.sub-menu,
        .cmr-has-mega-menu-do > .elementor-nav-menu--dropdown {
            display: none !important;
        }
        
        .cmr-mmw-wrapper-outer {
            position: absolute !important;
            top: 60px;
            left: 50% !important;
            transform: translateX(-30%) !important;
            width: max-content !important;
            max-width: none !important;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
            padding-top: 15px;
            z-index: 9999;
        }

        .elementor-nav-menu--main .elementor-item:hover + .cmr-mmw-wrapper-outer,
        .cmr-has-mega-menu-do:hover .cmr-mmw-wrapper-outer {
            opacity: 1;
            visibility: visible;
            top: 60px !important;
        }

        .cmr-has-mega-menu-do > a .sub-arrow {
            display: none !important;
        }
            
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var megaMenuTemplate = document.getElementById('cmr-mmw-wrapper-inner');
            if (!megaMenuTemplate) return;

            function injectMegaMenu() {
                var found = false;
                var navLinks = document.querySelectorAll('.menu-item > a, .menu-item > button, .elementor-item');
                navLinks.forEach(function(link) {
                    var text = link.innerText.trim().toLowerCase();
                    if (text === 'what we do') {
                        var parentLi = link.closest('li, .menu-item');
                        if (parentLi) {
                            if (!parentLi.classList.contains('cmr-has-mega-menu-do')) {
                                parentLi.classList.add('cmr-has-mega-menu-do');
                                var wrapperOuter = document.createElement('div');
                                wrapperOuter.className = 'cmr-mmw-wrapper-outer';
                                var clonedWrapper = megaMenuTemplate.cloneNode(true);
                                clonedWrapper.removeAttribute('id');
                                wrapperOuter.appendChild(clonedWrapper);
                                parentLi.appendChild(wrapperOuter);
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
                        if (text === 'what we do') {
                            var parentLi = link.closest('.cmr-has-mega-menu-do');
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



