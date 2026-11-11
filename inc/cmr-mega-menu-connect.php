<?php
/* Mega Menu Shortcode: [cmr_mega_menu_connect] */

add_shortcode('cmr_mega_menu_connect', 'cmr_mega_menu_connect_shortcode');

function cmr_get_mmc_posts($slug, $fallback_offset) {
    $args = array(
        'post_type'      => array( 'post', 'cmr_news', 'cmr_media' ),
        'posts_per_page' => 20, // Fetch more to allow filtering
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC',
        'category_name'  => $slug
    );
    $raw_posts = get_posts($args);
    if (count($raw_posts) < 3) {
        $args['category_name'] = '';
        $args['offset'] = $fallback_offset;
        $raw_posts = get_posts($args);
    }
    
    $unique_posts = array();
    
    global $cmr_mmc_seen_titles;
    if (!isset($cmr_mmc_seen_titles)) {
        $cmr_mmc_seen_titles = array();
    }
    
    foreach ($raw_posts as $p) {
        $title = trim($p->post_title);
        if (!isset($cmr_mmc_seen_titles[$title])) {
            $cmr_mmc_seen_titles[$title] = true;
            $unique_posts[] = $p;
            if (count($unique_posts) == 3) {
                break;
            }
        }
    }
    
    return $unique_posts;
}

function cmr_mega_menu_connect_shortcode($atts) {
    ob_start();

    // Fetch 3 distinct sets of posts
    $posts_enterprise = cmr_get_mmc_posts('enterprise-connect', 0);
    $posts_smb = cmr_get_mmc_posts('smb-connect,smb-connect-industry-connect,smb', 3);
    $posts_channel = cmr_get_mmc_posts('channel-connect', 6);

    ?>
    <style>
        .cmr-mmc-wrapper {
            position: relative;
            font-family: 'Instrument Sans', sans-serif;
            width: 900px !important;
            max-width: none !important;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
            background: #fff;
            background: #fff;
            display: flex;
        }
        
        .cmr-mmc-wrapper::before {
            content: '';
            position: absolute;
            top: -8px;
            left: 50%;
            transform: translateX(-50%) rotate(45deg);
            width: 16px;
            height: 16px;
            background: #f3f5ff; 
            box-shadow: -3px -3px 5px rgba(0,0,0,0.03);
            border-radius: 2px;
            z-index: 0;
        }

        .cmr-mmc-left {
            width: 35%;
            padding: 30px;
            background: #fff;
            position: relative;
            z-index: 2;
            border-top-left-radius: 12px;
            border-bottom-left-radius: 12px;
        }

        .cmr-mmc-right {
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

        .cmr-mmc-label {
            font-size: 13px;
            font-weight: 600;
            color: #9ba4b5;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 14px;
        }

        .cmr-mmc-menu-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .cmr-mmc-item {
            text-decoration: none;
            display: block;
            position: relative;
            padding: 0 !important;
            margin: 0 !important;
        }
        
        .cmr-mmc-item:hover h4 {
            color: #6A35FF;
        }

        .cmr-mmc-item-header {
            display: flex;
            align-items: center;
            margin-bottom: 6px;
        }

        .cmr-mmc-item h4 {
            font-size: 18px;
            font-weight: 600;
            letter-spacing: 0px;
            color: #111;
            margin: 0;
            transition: color 0.2s ease;
            display: inline-flex;
            align-items: center;
        }

        .cmr-mmc-item p {
            font-size: 14px;
            color: #666;
            margin: 0;
            line-height: 1.4;
        }

        .cmr-mmc-item.active h4 {
            color: #6A35FF !important;
        }

        .cmr-mmc-item.active::after {
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

        .cmr-mmc-text-inner {
            border-bottom: 2px solid transparent;
            padding-bottom: 2px;
            display: inline-flex;
            align-items: center;
            transition: border-color 0.2s;
        }

        .cmr-mmc-item.active .cmr-mmc-text-inner {
            border-bottom: 2px solid #6A35FF;
        }

        .cmr-mmc-arrow {
            display: none;
            margin-left: 6px;
        }

        .cmr-mmc-item.active .cmr-mmc-arrow {
            display: inline-block;
        }

        /* POSTS LIST */
        .cmr-mmc-posts-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
            flex: 1;
        }

        .cmr-mmc-post-card {
            display: flex !important;
            flex-direction: row !important;
            align-items: stretch;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            text-decoration: none;
            padding: 0 !important;
            margin: 0 !important;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .cmr-mmc-post-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        .cmr-mmc-post-img {
            width: 140px;
            flex-shrink: 0;
            background: #eaeaea;
            position: relative;
            min-height: 110px;
        }

        .cmr-mmc-post-img img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .cmr-mmc-post-content {
            padding: 15px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            flex: 1;
        }

        .cmr-mmc-post-date {
            font-size: 12px;
            color: #666;
            margin-bottom: 5px;
        }

        .cmr-mmc-post-title {
            font-size: 15px;
            font-weight: 600;
            color: #111;
            line-height: 1.3;
            margin: 0 0 10px 0;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .cmr-mmc-post-readmore {
            font-size: 13px;
            font-weight: 600;
            color: #111;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            border-bottom: 1px solid #111;
            padding-bottom: 1px;
            width: max-content;
        }

        .cmr-mmc-explore-btn {
            text-align: center;
            margin-top: 20px;
        }

        .cmr-mmc-explore-btn a {
            font-size: 16px;
            font-weight: 600;
            color: #000 !important;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 0 !important;
            transition: color 0.2s ease;
        }

        .cmr-mmc-explore-btn a:hover {
            color: #6A35FF;
        }

        @media (max-width: 768px) {
            .cmr-mmc-wrapper {
                flex-direction: column;
            }
            .cmr-mmc-left, .cmr-mmc-right {
                width: 100%;
            }
        }
                        @media (max-width: 1024px) {
            .cmr-mmc-right { display: none !important; }
            .cmr-has-mega-menu-connect .cmr-mmc-wrapper-outer {
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
            .cmr-has-mega-menu-connect.cmr-mobile-open > .cmr-mmc-wrapper-outer {
                display: block !important;
            }
            .cmr-has-mega-menu-connect .cmr-mmc-wrapper-outer::before {
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

    <div class="cmr-mmc-wrapper" id="cmr-mmc-wrapper-inner">
        <div class="cmr-mmc-left">
            <div class="cmr-mmc-label">CONNECT</div>
            <div class="cmr-mmc-menu-list">
                <a href="<?php echo esc_url( home_url( '/enterprise-connect/' ) ); ?>" class="cmr-mmc-item cmr-mmc-item-hover-trigger active" data-target="cmr-mmc-list-enterprise">
                    <div class="cmr-mmc-item-header">
                        <h4>
                            <span class="cmr-mmc-text-inner">Enterprise Connect
                                <svg class="cmr-mmc-arrow" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg>
                            </span>
                        </h4>
                    </div>
                    <p>Insights for enterprise leaders</p>
                </a>
                
                <a href="<?php echo esc_url( home_url( '/smb-connect/' ) ); ?>" class="cmr-mmc-item cmr-mmc-item-hover-trigger" data-target="cmr-mmc-list-smb">
                    <div class="cmr-mmc-item-header">
                        <h4>
                            <span class="cmr-mmc-text-inner">SMB Connect 
                                <svg class="cmr-mmc-arrow" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg>
                            </span>
                        </h4>
                    </div>
                    <p>Growth strategies for SMBs</p>
                </a>
                
                <a href="<?php echo esc_url( home_url( '/channel-connect/' ) ); ?>" class="cmr-mmc-item cmr-mmc-item-hover-trigger" data-target="cmr-mmc-list-channel">
                    <div class="cmr-mmc-item-header">
                        <h4>
                            <span class="cmr-mmc-text-inner">Channel Connect
                                <svg class="cmr-mmc-arrow" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg>
                            </span>
                        </h4>
                    </div>
                    <p>Opportunities for channel partners</p>
                </a>
            </div>
        </div>

        <div class="cmr-mmc-right">
            <div class="cmr-mmc-label">LATEST POSTS</div>
            
            <?php
            $tabs = array(
                'cmr-mmc-list-enterprise' => $posts_enterprise,
                'cmr-mmc-list-smb' => $posts_smb,
                'cmr-mmc-list-channel' => $posts_channel
            );
            $first_tab = true;
            foreach ($tabs as $tab_id => $tab_posts) :
            ?>
            <div class="cmr-mmc-posts-list" id="<?php echo esc_attr($tab_id); ?>" style="<?php echo $first_tab ? 'display: flex;' : 'display: none;'; ?>">
                <?php if ( ! empty( $tab_posts ) ) : ?>
                    <?php foreach ( $tab_posts as $post ) : 
                        $thumbnail_url = get_the_post_thumbnail_url( $post->ID, 'medium' );
                        if ( ! $thumbnail_url ) {
                            $thumbnail_url = 'https://cmrindia.com/wp-content/uploads/2026/06/Why-Chipsets-are-the-New-Frontier-in-Smartphones1.jpg';
                        }
                    ?>
                        <a href="<?php echo esc_url( get_permalink( $post->ID ) ); ?>" class="cmr-mmc-post-card">
                            <div class="cmr-mmc-post-img">
                                <img src="<?php echo esc_url( $thumbnail_url ); ?>" alt="<?php echo esc_attr( get_the_title( $post->ID ) ); ?>" loading="lazy" decoding="async">
                            </div>
                            <div class="cmr-mmc-post-content">
                                <div class="cmr-mmc-post-date"><?php echo get_the_date( 'M j, Y', $post->ID ); ?></div>
                                <div class="cmr-mmc-post-title"><?php echo esc_html( get_the_title( $post->ID ) ); ?></div>
                                <div class="cmr-mmc-post-readmore">
                                    Read More 
                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No recent posts found.</p>
                <?php endif; ?>
            </div>
            <?php 
            $first_tab = false;
            endforeach; 
            ?>
            
            <div class="cmr-mmc-explore-btn">
                <a href="<?php echo esc_url( home_url( '/insights/' ) ); ?>">
                    Explore More industry insights 
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg>
                </a>
            </div>
        </div>
    </div>
    
    
    <?php
    return ob_get_clean();
}

// Automatically inject the mega menu into the nav bar
add_action('wp_footer', 'cmr_inject_connect_mega_menu', 100);
function cmr_inject_connect_mega_menu() {
    $mega_menu_html = do_shortcode('[cmr_mega_menu_connect]');
    ?>
    <div id="cmr-hidden-mega-menu-connect" style="display: none;">
        <?php echo $mega_menu_html; ?>
    </div>

    <style>
        .cmr-has-mega-menu-connect {
            position: relative !important;
        }
        
        .cmr-has-mega-menu-connect > ul.sub-menu,
        .cmr-has-mega-menu-connect > .elementor-nav-menu--dropdown {
            display: none !important;
        }
        
        .cmr-mmc-wrapper-outer {
            position: absolute !important;
            top: 60px;
            left: 50% !important;
            transform: translateX(-50%) !important;
            width: max-content !important;
            max-width: none !important;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
            padding-top: 15px;
            z-index: 9999;
        }

        .elementor-nav-menu--main .elementor-item:hover + .cmr-mmc-wrapper-outer,
        .cmr-has-mega-menu-connect:hover .cmr-mmc-wrapper-outer {
            opacity: 1;
            visibility: visible;
            top: 60px !important;
        }

        .cmr-has-mega-menu-connect > a .sub-arrow {
            display: none !important;
        }
            
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var megaMenuTemplate = document.getElementById('cmr-mmc-wrapper-inner');
            if (!megaMenuTemplate) return;

            function injectMegaMenu() {
                var found = false;
                var navLinks = document.querySelectorAll('.menu-item > a, .menu-item > button, .elementor-item');
                navLinks.forEach(function(link) {
                    var text = link.innerText.trim().toLowerCase();
                    if (text === 'connect' || text === 'cmr connect') {
                        var parentLi = link.closest('li, .menu-item');
                        if (parentLi) {
                            if (!parentLi.classList.contains('cmr-has-mega-menu-connect')) {
                                parentLi.classList.add('cmr-has-mega-menu-connect');
                                var wrapperOuter = document.createElement('div');
                                wrapperOuter.className = 'cmr-mmc-wrapper-outer';
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
                        if (text === 'connect' || text === 'cmr connect') {
                            var parentLi = link.closest('.cmr-has-mega-menu-connect');
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




