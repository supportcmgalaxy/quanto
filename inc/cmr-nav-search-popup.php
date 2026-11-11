<?php
// Shortcodes for Nav Search Popup
add_shortcode('cmr_nav_search', 'cmr_nav_search_shortcode');
add_shortcode('cmr_nav_search_black', 'cmr_nav_search_black_shortcode');

add_action('wp_ajax_cmr_popup_search_ajax', 'cmr_popup_search_ajax_handler');
add_action('wp_ajax_nopriv_cmr_popup_search_ajax', 'cmr_popup_search_ajax_handler');
function cmr_popup_search_ajax_handler() {
    $search = isset($_POST['s']) ? sanitize_text_field($_POST['s']) : '';
    if (empty($search)) wp_die();
    $args = array(
        's' => $search,
        'post_type' => array('post', 'cmr_news'),
        'posts_per_page' => 5,
        'post_status' => 'publish'
    );
    $q = new WP_Query($args);
    $results = array();
    $total = $q->found_posts;
    if ($q->have_posts()) {
        while($q->have_posts()){
            $q->the_post();
            
            $type = 'Post';
            if (get_post_type() === 'cmr_news') {
                $type = 'Press Release';
            } else {
                $cats = get_the_category();
                if (!empty($cats)) {
                    $type = $cats[0]->name;
                }
            }
            
            $thumb = get_the_post_thumbnail_url(get_the_ID(), 'thumbnail');
            if (!$thumb) {
                // Return a data URI SVG placeholder
                $thumb = 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="60" viewBox="0 0 100 60"><rect width="100" height="60" fill="%23f1f5f9"/><path d="M35 40l8-10 12 15H35zm18-6l5-6 8 12H53z" fill="%23cbd5e1"/><circle cx="42" cy="25" r="5" fill="%23cbd5e1"/></svg>';
            }
            
            $results[] = array(
                'title' => get_the_title(),
                'url' => get_permalink(),
                'thumbnail' => $thumb,
                'type' => $type
            );
        }
    }
    wp_send_json(array('results' => $results, 'total' => $total));
}
add_shortcode('cmr_nav_search_black', 'cmr_nav_search_black_shortcode');

function cmr_nav_search_black_shortcode() {
    return cmr_nav_search_shortcode(array('color' => 'black'));
}

function cmr_nav_search_shortcode($atts = array()) {
    static $overlay_rendered = false;
    
    $atts = shortcode_atts(array(
        'color' => 'white',
    ), $atts);
    
    $is_black = ($atts['color'] === 'black');
    $container_class = $is_black ? 'cmr-nav-search-black' : '';
    
    ob_start();
    
    if (!$overlay_rendered) {
        $overlay_rendered = true;
        // Output CSS only once
        ?>
        <style>
        .cmr-nav-search-container {
            position: relative;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            vertical-align: middle !important;
            background: transparent;
            border: none;
            cursor: pointer;
            padding: 0 !important;
            margin: 0 !important;
            width: 40px !important;
            height: 40px !important;
            min-width: 40px !important;
            min-height: 40px !important;
            box-sizing: border-box !important;
            line-height: 1 !important;
            color: #fff; /* White on load */
        }
        
        .cmr-nav-search-container.cmr-nav-search-black,
        .cmr-nav-search-container.cmr-nav-search-black .cmr-nav-search-trigger,
        .cmr-nav-search-container.cmr-nav-search-black .cmr-nav-search-trigger svg,
        body:not(.home) .cmr-nav-search-container .cmr-nav-search-trigger,
        body:not(.home) .cmr-nav-search-container .cmr-nav-search-trigger svg {
            color: #1A1C1E !important; /* Force black on light headers */
            stroke: #1A1C1E !important;
        }
        
        .cmr-nav-search-trigger {
            background: transparent;
            border: none;
            color: inherit;
            cursor: pointer;
            padding: 0 !important;
            margin: 0 !important;
            width: 40px !important;
            height: 40px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            line-height: 1 !important;
            transition: color 0.3s ease;
        }

        .cmr-nav-search-trigger svg {
            width: 22px !important;
            height: 22px !important;
            display: block !important;
            margin: 0 auto !important;
        }
        
        .cmr-nav-search-trigger:hover {
            color: #d1d1d1;
        }
        
        .cmr-nav-search-container.cmr-nav-search-black .cmr-nav-search-trigger:hover,
        .cmr-nav-search-container.cmr-nav-search-black:hover .cmr-nav-search-trigger,
        body:not(.home) .cmr-nav-search-container .cmr-nav-search-trigger:hover,
        body:not(.home) .cmr-nav-search-container:hover .cmr-nav-search-trigger {
            color: #4820B0 !important;
            stroke: #4820B0 !important;
        }
        
        /* Change to black when header is sticky */
        .elementor-sticky--effects .cmr-nav-search-container:not(.cmr-nav-search-black),
        .is-sticky .cmr-nav-search-container:not(.cmr-nav-search-black),
        header.sticky .cmr-nav-search-container:not(.cmr-nav-search-black),
        .intel-nav-fixed-js .cmr-nav-search-container:not(.cmr-nav-search-black),
        .elementor-sticky--effects .cmr-nav-search-trigger,
        .is-sticky .cmr-nav-search-trigger,
        header.sticky .cmr-nav-search-trigger,
        .intel-nav-fixed-js .cmr-nav-search-trigger {
            color: #1A1C1E !important;
        }
        .elementor-sticky--effects .cmr-nav-search-trigger svg,
        .is-sticky .cmr-nav-search-trigger svg,
        header.sticky .cmr-nav-search-trigger svg,
        .intel-nav-fixed-js .cmr-nav-search-trigger svg {
            stroke: #1A1C1E !important;
        }

        .cmr-search-overlay-wrapper {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(4px);
            z-index: 2147483647 !important;
            display: none;
        }
        
        /* Fix for WordPress Admin Bar */
        .admin-bar .cmr-search-overlay-wrapper {
            top: 32px;
            height: calc(100vh - 32px);
        }
        @media screen and (max-width: 782px) {
            .admin-bar .cmr-search-overlay-wrapper {
                top: 46px;
                height: calc(100vh - 46px);
            }
        }

        .cmr-search-overlay-wrapper.active {
            display: block;
        }

        .cmr-search-top-bar {
            width: 100%;
            background: #ffffff;
            padding: 25px 40px;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }

        .cmr-search-overlay-content {
            width: 100%;
            max-width: 1280px;
            display: flex;
            align-items: center;
            gap: 30px;
            justify-content: space-between;
        }

        /* Form Styles matching the screenshot */
        .cmr-custom-popup-form {
            display: flex;
            align-items: center;
            flex-grow: 1;
            background: #fff;
            border-radius: 50px;
            padding: 4px;
            position: relative;
            /* Gradient Border Trick */
            background-clip: padding-box;
            border: 1px solid transparent;
        }
        
        .cmr-custom-popup-form::before {
            content: "";
            position: absolute;
            inset: 0;
            border-radius: 50px;
            padding: 1.5px; /* border thickness */
            background: linear-gradient(90deg, #6241ca, #06b6d4);
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            pointer-events: none;
        }

        .cmr-custom-popup-form .submit-btn {
            background: #6241ca; /* Purple matching design */
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 38px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 16px;
            flex-shrink: 0;
            margin-right: 7px;
            z-index: 2;
        }
        
        .cmr-custom-popup-form input {
            border: none;
            outline: none;
            flex-grow: 1;
            padding: 10px 15px;
            font-size: 16px;
            background: transparent;
            box-shadow: none;
            color: #333;
            z-index: 2;
        }
        .cmr-custom-popup-form input:focus {
            box-shadow: none;
            outline: none;
        }
        
        /* Hide native browser search cross icon */
        .cmr-custom-popup-form input[type="search"]::-webkit-search-decoration,
        .cmr-custom-popup-form input[type="search"]::-webkit-search-cancel-button,
        .cmr-custom-popup-form input[type="search"]::-webkit-search-results-button,
        .cmr-custom-popup-form input[type="search"]::-webkit-search-results-decoration {
            -webkit-appearance: none;
            display: none;
        }

        .cmr-search-overlay-close {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 50%;
            width: 46px;
            height: 46px;
            cursor: pointer;
            color: #000;
            transition: all 0.3s ease;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .cmr-search-overlay-close:hover {
            border-color: #cbd5e1;
            background: #f8fafc;
        }

        .cmr-custom-popup-form .cat-icon {
            padding-left: 20px;
            display: flex;
            align-items: center;
            z-index: 2;
        }

        .cmr-custom-popup-form .clear-btn {
            background: transparent;
            border: none;
            color: #000;
            font-size: 18px;
            cursor: pointer;
            padding: 0 10px;
            display: none;
            align-items: center;
            z-index: 2;
        }

        .cmr-custom-popup-form .results-badge {
            background: #ede9fe;
            color: #6241ca;
            font-size: 13px;
            font-weight: 600;
            padding: 6px 12px;
            border-radius: 20px;
            margin: 0 10px;
            white-space: nowrap;
            z-index: 2;
            display: none; /* hidden initially */
        }
        
        /* Dropdown Styles */
        .cmr-search-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            background: #fff;
            border-radius: 0;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-top: 15px;
            padding: 20px 0;
            display: none;
            max-height: 60vh;
            overflow-y: auto;
            overscroll-behavior: contain;
            -webkit-overflow-scrolling: touch;
            z-index: 10;
        }
        
        .cmr-search-dropdown.active {
            display: block;
        }
        
        .cmr-search-dropdown-item {
            display: flex;
            align-items: flex-start;
            padding: 15px 30px;
            text-decoration: none;
            border-bottom: 1px solid #f1f5f9;
            transition: background 0.3s;
        }
        
        .cmr-search-dropdown-item:hover {
            background: #f8fafc;
        }
        
        .cmr-search-dropdown-item:last-child {
            border-bottom: none;
        }
        
        .cmr-search-dropdown-item img {
            width: 100px;
            height: 60px;
            object-fit: cover;
            border-radius: 6px;
            margin-right: 20px;
            flex-shrink: 0;
            background: #f1f5f9;
        }
        
        .cmr-search-dropdown-content {
            display: flex;
            flex-direction: column;
        }
        
        .cmr-search-dropdown-type {
            font-size: 12px;
            color: #94a3b8;
            margin-bottom: 5px;
        }
        
        .cmr-search-dropdown-title {
            font-size: 16px;
            color: #0f172a;
            font-weight: 600;
            line-height: 1.4;
        }
        
        .cmr-search-dropdown-title span.highlight {
            color: #6241ca;
        }

        .cmr-spinner {
            animation: cmr-spin 1s linear infinite;
        }
        @keyframes cmr-spin { 100% { transform: rotate(360deg); } }
        
        /* Custom Scrollbar for dropdown */
        .cmr-search-dropdown::-webkit-scrollbar {
            width: 6px;
        }
        .cmr-search-dropdown::-webkit-scrollbar-track {
            background: #f1f5f9; 
            border-radius: 10px;
        }
        .cmr-search-dropdown::-webkit-scrollbar-thumb {
            background: #cbd5e1; 
            border-radius: 10px;
        }
        .cmr-search-dropdown::-webkit-scrollbar-thumb:hover {
            background: #94a3b8; 
        }
        </style>
        <?php
    } // End CSS
    ?>
    
    <div class="cmr-nav-search-container <?php echo esc_attr($container_class); ?> cmr-has-tooltip">
        <!-- The trigger icon -->
        <button type="button" class="cmr-nav-search-trigger" onclick="cmrOpenNavSearch()" aria-label="Search">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="2"/>
                <path d="M20 20L17 17" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
        </button>
        <span class="cmr-header-tooltip">Search</span>
    </div>
    
    <?php
    if ($overlay_rendered === true) {
        $overlay_rendered = 'completed'; // Ensure JS/HTML is only output once
        ?>
        <!-- The Overlay -->
        <div id="cmr-search-overlay" class="cmr-search-overlay-wrapper">
            <div class="cmr-search-top-bar">
                <div class="cmr-search-overlay-content">

                    <form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="cmr-custom-popup-form">
                        <div class="cat-icon">
                            <img src="<?php echo esc_url( home_url( '/wp-content/uploads/2026/06/cmrlogo-with-oly-c.svg' ) ); ?>" alt="CMR Logo" style="max-height: 24px;">
                        </div>

                        <input name="s" required value="<?php echo esc_html( get_search_query() ); ?>" type="search" placeholder="Type here..." autocomplete="off">
                        
                        <button type="button" class="clear-btn" aria-label="<?php esc_attr_e( 'Clear Search', 'quanto' ); ?>" style="<?php echo get_search_query() ? 'display:flex;' : 'display:none;'; ?>" onclick="var el=this.previousElementSibling; el.value=''; this.style.display='none'; el.focus(); el.dispatchEvent(new Event('input'));">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M18 6L6 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M6 6L18 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                        
                        <div class="results-badge" id="cmr-popup-results-badge">0 Results</div>

                        <button type="submit" class="submit-btn" id="cmr-popup-search-btn" aria-label="<?php esc_attr_e( 'Submit Search', 'quanto' ); ?>">
                            <svg class="search-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="2"/>
                                <path d="M20 20L17 17" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                            <svg class="spinner-icon cmr-spinner" style="display:none;" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" stroke-dasharray="32" stroke-linecap="round"/>
                            </svg>
                        </button>
                        
                        <div class="cmr-search-dropdown" id="cmr-popup-search-dropdown"></div>
                    </form>

                    <button type="button" class="cmr-search-overlay-close" aria-label="<?php esc_attr_e( 'Close Search', 'quanto' ); ?>" onclick="document.getElementById('cmr-search-overlay').classList.remove('active');">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M18 6L6 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M6 6L18 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>

                </div>
            </div>
        </div>

        <script>
        function cmrOpenNavSearch() {
            var overlay = document.getElementById('cmr-search-overlay');
            if (overlay.parentNode !== document.body) {
                document.body.appendChild(overlay);
            }
            overlay.classList.add('active');
            
            var input = overlay.querySelector('input[type="search"]');
            if (input) {
                setTimeout(function() { input.focus(); }, 100);
            }
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            var input = document.querySelector('.cmr-custom-popup-form input[name="s"]');
            var dropdown = document.getElementById('cmr-popup-search-dropdown');
            var badge = document.getElementById('cmr-popup-results-badge');
            var btnIcon = document.querySelector('#cmr-popup-search-btn .search-icon');
            var btnSpinner = document.querySelector('#cmr-popup-search-btn .spinner-icon');
            var clearBtn = document.querySelector('.cmr-custom-popup-form .clear-btn');
            var typingTimer;
            
            if (input) {
                input.addEventListener('input', function() {
                    clearTimeout(typingTimer);
                    
                    if (clearBtn) {
                        clearBtn.style.display = input.value.length > 0 ? 'flex' : 'none';
                    }

                    var val = input.value.trim();
                    if (val.length < 2) {
                        dropdown.classList.remove('active');
                        badge.style.display = 'none';
                        return;
                    }
                    
                    // Show spinner
                    btnIcon.style.display = 'none';
                    btnSpinner.style.display = 'block';
                    
                    typingTimer = setTimeout(function() {
                        var formData = new FormData();
                        formData.append('action', 'cmr_popup_search_ajax');
                        formData.append('s', val);
                        
                        fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                            method: 'POST',
                            body: formData
                        })
                        .then(res => res.json())
                        .then(data => {
                            // Hide spinner
                            btnIcon.style.display = 'block';
                            btnSpinner.style.display = 'none';
                            
                            if (data.results && data.results.length > 0) {
                                badge.textContent = data.total + " Results";
                                badge.style.display = 'block';
                                
                                var html = '';
                                // Escape regex
                                var regex = new RegExp('(' + val.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&') + ')', 'gi');
                                
                                data.results.forEach(function(item) {
                                    var titleHTML = item.title.replace(regex, '<span class="highlight">$1</span>');
                                    html += '<a href="' + item.url + '" class="cmr-search-dropdown-item">';
                                    html += '<img src="' + item.thumbnail + '" alt="">';
                                    html += '<div class="cmr-search-dropdown-content">';
                                    html += '<div class="cmr-search-dropdown-type">' + item.type + '</div>';
                                    html += '<div class="cmr-search-dropdown-title">' + titleHTML + '</div>';
                                    html += '</div></a>';
                                });
                                
                                if (data.total > data.results.length) {
                                    html += '<a href="javascript:void(0)" onclick="document.querySelector(\'.cmr-custom-popup-form\').submit()" class="cmr-search-dropdown-item" style="justify-content:center; padding: 20px; color: #6241ca; font-weight: 600; font-size: 18px;">View all ' + data.total + ' results</a>';
                                }
                                
                                dropdown.innerHTML = html;
                                dropdown.classList.add('active');
                            } else {
                                dropdown.innerHTML = '<div style="padding: 20px 30px; color:#666;">No results found.</div>';
                                dropdown.classList.add('active');
                                badge.style.display = 'none';
                            }
                        });
                    }, 500);
                });
            }
        });

        // Close search overlay when clicking outside
        document.addEventListener('click', function(event) {
            var overlay = document.getElementById('cmr-search-overlay');
            var topBar = overlay ? overlay.querySelector('.cmr-search-top-bar') : null;
            
            if (overlay && overlay.classList.contains('active')) {
                // Check if the click is outside the top bar AND not on the trigger button
                if (topBar && !topBar.contains(event.target) && !event.target.closest('.cmr-nav-search-trigger')) {
                    overlay.classList.remove('active');
                }
            }
        });
        
        // Update search icon color based on header mode (immediately and on scroll)
        function cmrUpdateSearchNavColor() {
            var triggers = document.querySelectorAll('.cmr-nav-search-trigger');
            triggers.forEach(function(trigger) {
                if (trigger.closest('.cmr-nav-search-black') || !document.body.classList.contains('home')) {
                    trigger.style.setProperty('color', '#1A1C1E', 'important');
                    return;
                }
                
                if (window.scrollY > 50) {
                    trigger.style.setProperty('color', '#1A1C1E', 'important');
                } else {
                    trigger.style.setProperty('color', '#fff', 'important');
                }
            });
        }
        cmrUpdateSearchNavColor();
        window.addEventListener('scroll', cmrUpdateSearchNavColor);
        </script>
        <?php
    }
    
    return ob_get_clean();
}
