<?php
/* Mega Menu Shortcode: [cmr_mega_menu_newsroom] */

add_shortcode('cmr_mega_menu_newsroom', 'cmr_mega_menu_newsroom_shortcode');

function cmr_mega_menu_newsroom_shortcode($atts) {
    ob_start();

    // Fetch 1 latest news post
    $args = array(
        'post_type'      => 'post', 
        'posts_per_page' => 1,
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC'
    );
    $posts = get_posts($args);
    $latest_news = !empty($posts) ? $posts[0] : null;

    ?>
    <style>
        .cmr-mmn-wrapper {
            position: relative;
            font-family: 'Instrument Sans', sans-serif;
            width: 800px !important;
            max-width: none !important;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
            background: #fff;
            display: flex;
            border-radius: 12px;
            white-space: normal !important;
        }

        .cmr-mmn-wrapper * {
            box-sizing: border-box;
            white-space: normal;
        }

        .cmr-mmn-wrapper::before {
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

        .cmr-mmn-left {
            width: 45%;
            padding: 40px;
            background: #fff;
            position: relative;
            z-index: 2;
            border-top-left-radius: 12px;
            border-bottom-left-radius: 12px;
            display: flex;
            flex-direction: column;
            gap: 20px;
            white-space: normal !important;
        }

        .cmr-mmn-right {
            width: 55%;
            padding: 40px;
            background: linear-gradient(135deg, #f3f5ff 0%, #edf9fb 50%, #e6faf7 100%);
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            border-top-right-radius: 12px;
            border-bottom-right-radius: 12px;
            white-space: normal !important;
        }

        .cmr-mmn-label {
            font-size: 13px;
            font-weight: 600;
            color: #9ba4b5;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .cmr-mmn-item {
            text-decoration: none;
            display: block;
            white-space: normal !important;
        }
        
        .cmr-mmn-item:hover h4 {
            color: #6A35FF;
        }

        .cmr-mmn-item h4 {
            font-size: 18px;
            font-weight: 600;
            letter-spacing: 0px;
            color: #111;
            margin: 0 0 6px 0;
            transition: color 0.2s ease;
            white-space: normal !important;
        }

        .cmr-mmn-item p {
            font-size: 14px;
            color: #666;
            margin: 0;
            line-height: 1.4;
            white-space: normal !important;
        }

        /* Latest Announcement */
        .cmr-mmn-announcement {
            display: flex;
            flex-direction: column;
            text-decoration: none;
            white-space: normal !important;
            width: 100% !important;
        }

        .cmr-mmn-announcement-img {
            width: 100%;
            height: 180px;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 20px;
            background: #e2e8f0;
        }

        .cmr-mmn-announcement-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.3s ease;
        }
        
        .cmr-mmn-announcement:hover .cmr-mmn-announcement-img img {
            transform: scale(1.05);
        }

        .cmr-mmn-announcement-title {
            font-size: 18px !important;
            font-weight: 600 !important;
            color: #111 !important;
            line-height: 1.35 !important;
            margin-bottom: 10px !important;
            white-space: normal !important;
            display: -webkit-box !important;
            -webkit-line-clamp: 2 !important;
            -webkit-box-orient: vertical !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
            word-break: break-word !important;
        }

        .cmr-mmn-announcement-desc {
            font-size: 14px !important;
            color: #666 !important;
            line-height: 1.5 !important;
            margin-bottom: 18px !important;
            white-space: normal !important;
            display: -webkit-box !important;
            -webkit-line-clamp: 2 !important;
            -webkit-box-orient: vertical !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
            word-break: break-word !important;
        }

        .cmr-mmn-read-more {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 15px;
            font-weight: 600;
            color: #111;
            padding-bottom: 4px;
            border-bottom: 1px solid #111;
            width: max-content;
            text-decoration: none;
            transition: color 0.2s ease, border-color 0.2s ease;
        }
        
        .cmr-mmn-announcement:hover .cmr-mmn-read-more {
            color: #6A35FF;
            border-bottom-color: #6A35FF;
        }

        @media (max-width: 768px) {
            .cmr-mmn-wrapper {
                flex-direction: column;
                width: 100% !important;
            }
            .cmr-mmn-left, .cmr-mmn-right {
                width: 100%;
            }
        }
                        @media (max-width: 1024px) {
            .cmr-mmn-right { display: none !important; }
            .cmr-has-mega-menu-newsroom .cmr-mmn-wrapper-outer {
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
            .cmr-has-mega-menu-newsroom.cmr-mobile-open > .cmr-mmn-wrapper-outer {
                display: block !important;
            }
            .cmr-has-mega-menu-newsroom .cmr-mmn-wrapper-outer::before {
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

    <div class="cmr-mmn-wrapper">
        <div class="cmr-mmn-left">
            <div class="cmr-mmn-label">NEWSROOM</div>
            <a href="<?php echo esc_url( home_url( '/press-releases/' ) ); ?>" class="cmr-mmn-item">
                <h4>Media Releases</h4>
                <p>Official company announcements and updates</p>
            </a>
            <a href="https://www.cmrsl.net/financial-results/" target="_blank" rel="noopener noreferrer" class="cmr-mmn-item">
                <h4>Quarterly Results</h4>
                <p>Financial performance and investor updates</p>
            </a>
            <a href="<?php echo esc_url( home_url( '/cmr-news/' ) ); ?>" class="cmr-mmn-item">
                <h4>CMR in News</h4>
                <p>Featured coverage across leading media</p>
            </a>
        </div>

        <div class="cmr-mmn-right">
            <div class="cmr-mmn-label" style="margin-bottom: 15px;">LATEST ANNOUNCEMENT</div>
            
            <?php if ($latest_news) : 
                $thumbnail_url = get_the_post_thumbnail_url( $latest_news->ID, 'medium_large' );
                if ( ! $thumbnail_url ) {
                    $thumbnail_url = quanto_get_placeholder_image_uri();
                }
            ?>
            <a href="<?php echo esc_url(get_permalink($latest_news->ID)); ?>" class="cmr-mmn-announcement">
                <div class="cmr-mmn-announcement-img">
                    <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo esc_attr(get_the_title($latest_news->ID)); ?>">
                </div>
                <div class="cmr-mmn-announcement-title">
                    <?php echo esc_html(get_the_title($latest_news->ID)); ?>
                </div>
                <div class="cmr-mmn-announcement-desc">
                    <?php echo wp_trim_words(get_the_excerpt($latest_news->ID), 15); ?>
                </div>
                <div class="cmr-mmn-read-more">
                    Read full Release <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg>
                </div>
            </a>
            <?php else : ?>
            <a href="#" class="cmr-mmn-announcement">
                <div class="cmr-mmn-announcement-img">
                    <img src="<?php echo esc_url( quanto_get_placeholder_image_uri() ); ?>" alt="Latest Announcement">
                </div>
                <div class="cmr-mmn-announcement-title">
                    Explore our newest corporate updates.
                </div>
                <div class="cmr-mmn-announcement-desc">
                    Check out the all new dashboard view. Pages now load faster.
                </div>
                <div class="cmr-mmn-read-more">
                    Read full Release <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg>
                </div>
            </a>
            <?php endif; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

add_action('wp_footer', 'cmr_inject_newsroom_mega_menu', 100);
function cmr_inject_newsroom_mega_menu() {
    $mega_menu_html = do_shortcode('[cmr_mega_menu_newsroom]');
    ?>
    <div id="cmr-hidden-mega-menu-newsroom" style="display: none;">
        <?php echo $mega_menu_html; ?>
    </div>

    <style>
        .cmr-has-mega-menu-newsroom {
            position: relative !important;
        }
        
        .cmr-mmn-wrapper-outer {
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

        .elementor-nav-menu--main .elementor-item:hover + .cmr-mmn-wrapper-outer,
        .cmr-has-mega-menu-newsroom:hover .cmr-mmn-wrapper-outer {
            opacity: 1;
            visibility: visible;
            transform: translateX(-50%) translateY(0);
            top: 60px !important;
        }

        .cmr-has-mega-menu-newsroom > a .sub-arrow {
            display: none !important;
        }
            
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var megaMenuTemplate = document.getElementById('cmr-hidden-mega-menu-newsroom');
            if (!megaMenuTemplate) return;

            function injectMegaMenu() {
                var found = false;
                var navLinks = document.querySelectorAll('.menu-item > a, .menu-item > button, .elementor-item');
                navLinks.forEach(function(link) {
                    var text = link.innerText.trim().toLowerCase();
                    if (text === 'newsroom' || text === 'news room') {
                        var parentLi = link.closest('li, .menu-item');
                        if (parentLi) {
                            if (!parentLi.classList.contains('cmr-has-mega-menu-newsroom')) {
                                parentLi.classList.add('cmr-has-mega-menu-newsroom');
                                var wrapperOuter = document.createElement('div');
                                wrapperOuter.className = 'cmr-mmn-wrapper-outer';
                                Array.from(megaMenuTemplate.childNodes).forEach(function(node) { wrapperOuter.appendChild(node.cloneNode(true)); });
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
                        if (text === 'newsroom' || text === 'news room') {
                            var parentLi = link.closest('.cmr-has-mega-menu-newsroom');
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



