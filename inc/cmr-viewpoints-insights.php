<?php
/**
 * Shortcode for Viewpoints & Strategic Insights
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! function_exists( 'cmr_viewpoints_insights_shortcode' ) ) {
    function cmr_viewpoints_insights_shortcode( $atts ) {
        $atts = shortcode_atts( array(
            'posts_per_page' => 9,
            'category'       => '', // e.g. 'viewpoints'
        ), $atts, 'cmr_viewpoints_insights' );

        $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
        
        $query_args = array(
            'post_type'      => 'post',
            'posts_per_page' => 9,
            'paged'          => $paged,
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC',
        );

        if ( ! empty( $atts['category'] ) ) {
            $query_args['category_name'] = $atts['category'];
        } else {
            // Default to viewpoints if none specified
            $query_args['category_name'] = 'viewpoint,viewpoints';
        }

        $insights_posts = get_posts( $query_args );
        $all_query = new WP_Query($query_args);
        $posts = $all_query->posts;
        $max_pages = $all_query->max_num_pages;

        ob_start();
        ?>
        <style>
            .cmr-vpi-section {
                font-family: 'Instrument Sans', sans-serif !important;
                width: 100%;
                max-width: 1280px;
                margin: 0 auto;
                padding: 40px 20px;
                box-sizing: border-box;
            }
            .cmr-vpi-header {
                margin-bottom: 30px;
            }
            .cmr-vpi-title {
                font-size: 42px;
                font-weight: 600;
                color: #111;
                margin: 0 0 15px 0;
                line-height: 1.1;
                letter-spacing: -1px;
            }
            .cmr-vpi-subtitle {
                font-size: 18px;
                color: #444;
                margin: 0;
                font-weight: 400;
            }
            .cmr-vpi-nav-bar {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 20px 0;
                margin-bottom: 30px;
                background: #fff;
                border-bottom: 1px solid #eaeaea;
                position: static;
            }
            .cmr-vpi-filters {
                display: flex;
                gap: 10px;
                flex-wrap: wrap;
            }
            .cmr-vpi-filter-btn {
                padding: 8px 20px;
                border: 1px solid #ddd;
                border-radius: 30px;
                background: #fff;
                color: #333;
                font-size: 14px;
                font-weight: 500;
                cursor: pointer;
                transition: all 0.2s ease;
                font-family: inherit;
            }
            .cmr-vpi-filter-btn:hover {
                border-color: #6d4bdf;
                color: #6d4bdf;
            }
            .cmr-vpi-filter-btn.active {
                border-color: #6d4bdf;
                color: #6d4bdf;
                box-shadow: 0 0 0 1px #6d4bdf;
            }
            .cmr-vpi-search-wrap {
                position: relative;
                width: 250px;
            }
            .cmr-vpi-search-wrap input {
                width: 100%;
                padding: 8px 40px 8px 20px;
                border: 1px solid #ddd;
                border-radius: 30px;
                font-size: 14px;
                font-family: inherit;
                box-sizing: border-box;
                outline: none;
            }
            .cmr-vpi-search-wrap input:focus {
                border-color: #6d4bdf;
            }
            .cmr-vpi-search-icon {
                position: absolute;
                right: 5px;
                top: 8px;
                width: 32px;
                height: 32px;
                background: #6d4bdf;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #fff;
                cursor: pointer;
            }
            .cmr-vpi-grid {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 30px;
            }
            .cmr-vpi-card {
                display: flex;
                flex-direction: column;
                background: #fff;
                height: 100%;
                text-decoration: none;
                color: inherit;
                cursor: pointer;
            }
            .cmr-vpi-card-img {
                width: 100%;
                aspect-ratio: 4 / 3;
                object-fit: cover;
                display: block;
                border-radius: 4px;
                margin-bottom: 20px;
                transition: transform 0.4s cubic-bezier(0.25, 1, 0.5, 1) !important;
            }
            .cmr-vpi-card:hover .cmr-vpi-card-img,
            .cmr-vpi-card-img:hover {
                transform: scale(1.05) !important;
            }
            .cmr-vpi-card-meta {
                display: flex;
                align-items: center;
                justify-content: space-between;
                font-size: 13px;
                color: #888;
                margin-bottom: 12px;
            }
            .cmr-vpi-card-cat-date {
                display: flex;
                align-items: center;
                gap: 10px;
            }
            .cmr-vpi-card-cat-date::before {
                content: '';
                display: inline-block;
                width: 20px;
                height: 1px;
                background: #ccc;
            }
            .cmr-vpi-card-title {
                font-size: 20px;
                font-weight: 600;
                color: #111;
                margin: 0 0 15px 0;
                line-height: 1.3;
                letter-spacing: -0.5px;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
                transition: color 0.3s ease !important;
            }
            .cmr-vpi-card:hover .cmr-vpi-card-title,
            .cmr-vpi-card:hover .cmr-vpi-card-title a,
            .cmr-vpi-card:hover h3,
            .cmr-vpi-card:hover h3 a,
            .cmr-vpi-card-title:hover,
            .cmr-vpi-card-title:hover a,
            .cmr-vpi-card-title a:hover {
                color: #6A42E5 !important;
            }
            .cmr-vpi-card-excerpt {
                font-size: 15px;
                color: #555;
                line-height: 1.5;
                margin: 0 0 20px 0;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }
            .cmr-vpi-read-more {
                display: inline-flex;
                align-items: center;
                font-size: 15px;
                font-weight: 600;
                color: #111 !important;
                text-decoration: none;
                border-bottom: 1px solid #111 !important;
                padding-bottom: 2px;
                align-self: flex-start;
                margin-top: auto;
                transition: color 0.3s ease, border-color 0.3s ease;
            }
            .cmr-vpi-read-more svg {
                width: 16px;
                height: 16px;
                margin-left: 5px;
                stroke: #111 !important;
                transition: color 0.3s ease, stroke 0.3s ease, transform 0.3s ease;
            }
            .cmr-vpi-card:hover .cmr-vpi-read-more {
                color: #111 !important;
                border-bottom-color: #111 !important;
            }
            .cmr-vpi-card:hover .cmr-vpi-read-more svg {
                color: #111 !important;
                stroke: #111 !important;
                transform: none !important;
            }
            .cmr-vpi-read-more:hover {
                color: #6A42E5 !important;
                border-bottom-color: #6A42E5 !important;
            }
            .cmr-vpi-read-more:hover svg {
                color: #6A42E5 !important;
                stroke: #6A42E5 !important;
                transform: translate(2px, -2px) !important;
            }
            .cmr-vpi-card-hidden {
                display: none !important;
            }


            .cmr-vpi-btn {
                background: #fff;
                color: #111;
                border: 1px solid #ccc;
                padding: 10px 40px;
                border-radius: 50px;
                font-size: 16px;
                font-weight: 500;
                cursor: pointer;
                transition: transform 0.3s cubic-bezier(0.25, 1, 0.5, 1), border-color 0.3s ease, color 0.3s ease, background 0.3s ease !important;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                text-decoration: none;
                transform: scale(1);
            }
            .cmr-vpi-btn:hover {
                background: #f8f8f8;
                border-color: #6A42E5 !important;
                color: #6A42E5 !important;
                transform: scale(1.05) !important;
            }

            .cmr-vpi-pagination {
                width: 100%;
                text-align: center;
                display: flex;
                justify-content: center;
            }
            .cmr-vpi-pagination .nav-links {
                display: flex;
                justify-content: center;
                gap: 15px;
                align-items: center;
            }
            .cmr-vpi-pagination .page-numbers {
                padding: 0;
                width: 40px;
                height: 40px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border: none;
                border-radius: 50%;
                text-decoration: none;
                color: #333;
                font-size: 16px;
                font-weight: 500;
                background: transparent;
            }
            .cmr-vpi-pagination .page-numbers.current {
                background: #6244d4;
                color: #fff;
            }
            .cmr-vpi-pagination .page-numbers.prev, 
            .cmr-vpi-pagination .page-numbers.next {
                color: #6244d4;
            }
            .cmr-vpi-pagination .page-numbers.dots {
                width: auto;
            }

            @media (max-width: 992px) {
                .cmr-vpi-section {
                    padding: 24px 16px !important;
                }
                .cmr-vpi-grid {
                    grid-template-columns: repeat(2, 1fr);
                }
                .cmr-vpi-nav-bar {
                    flex-direction: column;
                    align-items: stretch;
                    gap: 12px;
                    position: static !important;
                }
                .cmr-vpi-filters {
                    width: 100%;
                    display: flex;
                    flex-wrap: wrap;
                    gap: 6px 8px;
                }
                .cmr-vpi-filter-btn {
                    padding: 6px 14px;
                    font-size: 13px;
                    border-radius: 20px;
                    line-height: 1.2;
                }
                .cmr-vpi-search-wrap {
                    width: 100% !important;
                }
                .cmr-vpi-search-wrap input {
                    width: 100% !important;
                    height: 44px;
                    font-size: 14px;
                    padding: 8px 44px 8px 18px;
                    border-radius: 30px;
                }
                .cmr-vpi-search-icon {
                    width: 32px;
                    height: 32px;
                    top: 6px;
                    right: 6px;
                }
                .cmr-vpi-search-icon svg {
                    width: 14px;
                    height: 14px;
                }
                .cmr-vpi-nav-links {
                    flex-wrap: wrap;
                }
            }
            @media (max-width: 768px) {
                .cmr-vpi-section {
                    padding: 20px 16px !important;
                }
                .cmr-vpi-header {
                    margin-top: 20px !important;
                    margin-bottom: 20px !important;
                }
                .cmr-vpi-title {
                    font-size: 32px !important;
                    line-height: 1.15 !important;
                    letter-spacing: -0.5px !important;
                    margin: 0 0 10px 0 !important;
                }
                .cmr-vpi-subtitle {
                    font-size: 15px !important;
                    line-height: 1.4 !important;
                }
                .cmr-vpi-grid {
                    grid-template-columns: 1fr;
                }
                .cmr-vpi-nav-bar {
                    position: static !important;
                    padding: 12px 0;
                    gap: 14px;
                }
                .cmr-vpi-filters {
                    width: 100%;
                    gap: 6px 8px;
                }
                .cmr-vpi-filter-btn {
                    padding: 6px 14px;
                    font-size: 12.5px;
                    border-radius: 20px;
                }
                .cmr-vpi-search-wrap {
                    width: 100% !important;
                }
                .cmr-vpi-search-wrap input {
                    width: 100% !important;
                    height: 44px;
                    font-size: 14px;
                    padding: 8px 44px 8px 18px;
                    border-radius: 30px;
                }
                .cmr-vpi-search-icon {
                    width: 32px;
                    height: 32px;
                    top: 6px;
                    right: 6px;
                }
                .cmr-vpi-search-icon svg {
                    width: 14px;
                    height: 14px;
                }
            }
        </style>

        <div class="cmr-vpi-section">
            <div class="intel-nav-bar" style="margin-bottom: 30px;">
                <div class="intel-nav-title">
                    Viewpoints
                </div>
                <div class="intel-nav-links" style="display: flex; align-items: center;">
                    <a href="#featured">Featured</a>
                    <a href="#latest-updates">Latest Updates</a>
                    <a href="#cmr-live">CMR live</a>
                    <a href="#reports">Reports</a>
                    <a href="#expert-insights" class="expert-btn" style="display: inline-flex; align-items: center;">
                        Get expert insights 
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="width: 14px; height: 14px; margin-left: 4px;">
                            <line x1="7" y1="17" x2="17" y2="7"></line>
                            <polyline points="7 7 17 7 17 17"></polyline>
                        </svg>
                    </a>
                </div>
            </div>

            <div class="cmr-vpi-header" style="margin-top: 40px;">
                <h2 class="cmr-vpi-title">Expert Viewpoints & Strategic Perspectives</h2>
                <p class="cmr-vpi-subtitle">Insights, opinions, and analysis shaping enterprise decisions across industries.</p>
            </div>

            <div class="cmr-vpi-nav-bar">
                <div class="cmr-vpi-filters">
                    <button class="cmr-vpi-filter-btn active" data-filter="all">All</button>
                    <button class="cmr-vpi-filter-btn" data-filter="automotive">Automotive</button>
                    <button class="cmr-vpi-filter-btn" data-filter="consumer-tech">Consumer Tech</button>
                    <button class="cmr-vpi-filter-btn" data-filter="digital-supply-chain">Digital Supply Chain</button>
                </div>
                <div class="cmr-vpi-search-wrap">
                    <input type="text" placeholder="Search by name">
                    <div class="cmr-vpi-search-icon">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="cmr-vpi-grid">
                <?php if ( ! empty($posts) ) : 
                    $post_count = 0;
                    foreach ( $posts as $post_obj ) : 
                        $post_count++;
                        $hidden_class = ( $post_count > 9 ) ? ' cmr-vpi-card-hidden' : '';
                        
                        $thumbnail_url = get_the_post_thumbnail_url( $post_obj->ID, 'large' );
                        if ( ! $thumbnail_url ) {
                            $thumbnail_url = 'https://via.placeholder.com/600x400?text=Insight+Image';
                        }
                        
                        $category_name = 'Uncategorized';
                        $all_term_names = array();
                        $terms = get_the_terms( $post_obj->ID, 'category' );
                        if ( $terms && ! is_wp_error( $terms ) ) {
                            $category_name = $terms[0]->name;
                            foreach ($terms as $t) {
                                $all_term_names[] = $t->name;
                                $all_term_names[] = $t->slug;
                            }
                        }
                        $cat_data_attr = esc_attr(strtolower(implode(' ', array_unique($all_term_names))));
                        
                        $post_date = get_the_date('d M Y', $post_obj);
                        
                        // Approximate read time
                        $content = $post_obj->post_content;
                        $word_count = str_word_count( strip_tags( $content ) );
                        $read_time = ceil( $word_count / 200 );
                        if ($read_time < 1) $read_time = 1;

                        $excerpt = get_the_excerpt($post_obj);
                        if ( empty( $excerpt ) ) {
                            $excerpt = wp_trim_words( $content, 20 );
                        }
                    ?>
                    <a href="<?php echo esc_url(get_permalink($post_obj->ID)); ?>" class="cmr-vpi-card<?php echo $hidden_class; ?>" data-category="<?php echo $cat_data_attr; ?>">
                        <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo esc_attr(get_the_title($post_obj)); ?>" class="cmr-vpi-card-img">
                        <div class="cmr-vpi-card-meta">
                            <div class="cmr-vpi-card-cat-date">
                                <span><?php echo esc_html($category_name); ?></span> | 
                                <span><?php echo esc_html($post_date); ?></span>
                            </div>
                            <div class="cmr-vpi-card-read"><?php echo esc_html($read_time); ?> min read</div>
                        </div>
                        <h3 class="cmr-vpi-card-title"><?php echo esc_html(get_the_title($post_obj)); ?></h3>
                        <p class="cmr-vpi-card-excerpt"><?php echo esc_html(wp_strip_all_tags($excerpt)); ?></p>
                        <span class="cmr-vpi-read-more">
                            Read More 
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="7" y1="17" x2="17" y2="7"></line>
                                <polyline points="7 7 17 7 17 17"></polyline>
                            </svg>
                        </span>
                    </a>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p>No insights found.</p>
                <?php endif; ?>
            </div>

            <?php
            $vpi_cat_slug = !empty($atts['category']) ? $atts['category'] : 'viewpoints';
            $vpi_cat_obj  = get_category_by_slug( $vpi_cat_slug );
            if ( ! $vpi_cat_obj ) $vpi_cat_obj = get_category_by_slug( 'viewpoint' );
            $vpi_cat_url  = $vpi_cat_obj ? get_category_link( $vpi_cat_obj->term_id ) : home_url( '/category/viewpoints/' );
            ?>
            <div class="cmr-vpi-actions" style="text-align: center; margin-top: 40px;">
                <a href="<?php echo esc_url( $vpi_cat_url ); ?>" class="cmr-vpi-btn" style="background: transparent; border: 1px solid #ccc; color: #111; font-size: 14px; font-weight: 600; border-radius: 40px; text-decoration: none; transition: all 0.3s ease; width: 260px; height: 44px; display: inline-flex; justify-content: center; align-items: center; box-sizing: border-box;">View All</a>
            </div>
        </div>


        <script>
            // Filter functionality (Pills and AJAX Search)
            document.addEventListener('DOMContentLoaded', function() {
                var searchInput = document.querySelector('.cmr-vpi-search-wrap input');
                var filterBtns = document.querySelectorAll('.cmr-vpi-filter-btn');
                var grid = document.querySelector('.cmr-vpi-grid');
                var viewAllWrap = document.querySelector('.cmr-vpi-actions');
                var viewAllBtn = viewAllWrap ? viewAllWrap.querySelector('.cmr-vpi-btn') : null;
                var paginationWrap = document.getElementById('cmr-vpi-pagination-wrap');
                
                var initialGridHtml = grid ? grid.innerHTML : '';
                var initialViewAllUrl = viewAllBtn ? viewAllBtn.getAttribute('href') : '<?php echo esc_url( home_url( '/category/viewpoints/' ) ); ?>';
                var currentFilter = 'all';
                var searchTimer;

                // Category URL mapping for View All button
                var categoryUrls = {
                    'all': initialViewAllUrl,
                    'automotive': '<?php echo esc_url( home_url( '/category/automotive/' ) ); ?>',
                    'consumer-tech': '<?php echo esc_url( home_url( '/category/consumer-tech/' ) ); ?>',
                    'digital-supply-chain': '<?php echo esc_url( home_url( '/category/digital-supply-chain/' ) ); ?>'
                };

                // Function to filter rendered cards or restore initial state
                function applyFilter(filterVal) {
                    currentFilter = (filterVal || 'all').toLowerCase().trim();

                    if (searchInput) searchInput.value = '';

                    // If returning to "All", restore exact initial state
                    if (currentFilter === 'all') {
                        if (grid) grid.innerHTML = initialGridHtml;
                        if (viewAllBtn) viewAllBtn.setAttribute('href', initialViewAllUrl);
                        if (viewAllWrap) viewAllWrap.style.display = 'block';
                        if (paginationWrap) paginationWrap.style.display = 'none';
                        return;
                    }

                    // Update View All button link to current category
                    var targetCatUrl = categoryUrls[currentFilter] || initialViewAllUrl;
                    if (viewAllBtn) viewAllBtn.setAttribute('href', targetCatUrl);
                    if (viewAllWrap) viewAllWrap.style.display = 'block';

                    // Fetch 9 posts of this category from server
                    fetchServerResults('', currentFilter);
                }

                function fetchServerResults(searchTerm, catFilter) {
                    if (!grid) return;
                    grid.innerHTML = '<p style="grid-column:1/-1; text-align:center; padding:40px; font-size:18px;">Loading insights...</p>';
                    if (paginationWrap) paginationWrap.style.display = 'none';

                    var formData = new FormData();
                    formData.append('action', 'cmr_insights_ajax_search');
                    formData.append('search_term', searchTerm || '');
                    formData.append('cat_filter', catFilter || '');
                    formData.append('prefix', 'cmr-vpi-');
                    formData.append('category', 'viewpoint,viewpoints');

                    fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                        method: 'POST',
                        body: formData
                    })
                    .then(function(res) { return res.text(); })
                    .then(function(html) {
                        grid.innerHTML = html;
                        if (viewAllWrap) viewAllWrap.style.display = 'block';
                    });
                }

                // Attach pill click events
                filterBtns.forEach(function(btn) {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        filterBtns.forEach(function(b) { b.classList.remove('active'); });
                        this.classList.add('active');
                        var filterVal = this.getAttribute('data-filter') || this.textContent.trim().toLowerCase().replace(/\s+/g, '-');
                        applyFilter(filterVal);
                    });
                });

                // Search input event
                if (searchInput) {
                    searchInput.addEventListener('keyup', function(e) {
                        clearTimeout(searchTimer);
                        var val = e.target.value.trim();
                        
                        searchTimer = setTimeout(function() {
                            if (!val && currentFilter === 'all') {
                                if (grid) grid.innerHTML = initialGridHtml;
                                if (viewAllWrap) viewAllWrap.style.display = 'block';
                                return;
                            }
                            fetchServerResults(val, currentFilter);
                        }, 500); // 500ms debounce
                    });
                }
                
                // Load More Functionality
                var loadMoreBtn = document.getElementById('cmr-vpi-load-more');
                var paginationWrap = document.getElementById('cmr-vpi-pagination-wrap');
                if (loadMoreBtn) {
                    loadMoreBtn.addEventListener('click', function() {
                        var hiddenCards = document.querySelectorAll('.cmr-vpi-card.cmr-vpi-card-hidden');
                        var cardsToShow = 9;
                        for (var i = 0; i < hiddenCards.length; i++) {
                            if (i < cardsToShow) {
                                hiddenCards[i].classList.remove('cmr-vpi-card-hidden');
                            }
                        }
                        
                        var remainingHidden = document.querySelectorAll('.cmr-vpi-card.cmr-vpi-card-hidden');
                        if (remainingHidden.length === 0) {
                            loadMoreBtn.style.display = 'none';
                            if (paginationWrap) {
                                paginationWrap.style.display = 'block';
                            }
                        }
                    });
                }
                
                // Sticky Nav Functionality
                const sections = document.querySelectorAll('.cmr-vpi-section');
                sections.forEach(section => {
                    const navBar = section.querySelector('.intel-nav-bar');
                    if (!navBar) return;
                    
                    // Create a placeholder to prevent grid jumping when bar becomes fixed
                    const placeholder = document.createElement('div');
                    placeholder.className = 'cmr-vpi-nav-placeholder';
                    placeholder.style.height = '0px';
                    placeholder.style.marginBottom = '0px';
                    navBar.parentNode.insertBefore(placeholder, navBar);
                    
                    function updateSticky() {
                        const sectionRect = section.getBoundingClientRect();
                        
                        let stickyOffset = 0;
                        const wpAdminBar = document.getElementById('wpadminbar');
                        if (wpAdminBar && window.getComputedStyle(wpAdminBar).position === 'fixed') {
                            stickyOffset = wpAdminBar.offsetHeight;
                        }
                        const headers = document.querySelectorAll('header, [data-elementor-type="header"], .elementor-location-header, .elementor-sticky--active');
                        headers.forEach(h => {
                            if (h === navBar || h.contains(navBar)) return;
                            const hStyle = window.getComputedStyle(h);
                            if (hStyle.position === 'fixed' || hStyle.position === 'sticky' || h.classList.contains('elementor-sticky--active')) {
                                const hRect = h.getBoundingClientRect();
                                if (hRect.top <= stickyOffset + 10 && hRect.bottom > stickyOffset && hRect.bottom < (window.innerHeight / 2)) {
                                    stickyOffset = hRect.bottom;
                                }
                            }
                        });

                        let boundaryBottom = sectionRect.bottom;
                        
                        let testimonialsSection = document.getElementById('cmr-testimonials-section') || 
                                                  document.getElementById('testimonials') || 
                                                  document.querySelector('.elementor-widget-testimonial-carousel') ||
                                                  document.querySelector('.elementor-widget-testimonial');
                                                  
                        if (!testimonialsSection) {
                            const headings = Array.from(document.querySelectorAll('h1, h2, h3, h4, h5, h6')).filter(h => h.textContent.toLowerCase().includes('testimonial'));
                            if (headings.length > 0) {
                                testimonialsSection = headings[0].closest('.elementor-section') || headings[0].closest('section') || headings[0].parentElement;
                            }
                        }

                        if (testimonialsSection) {
                            boundaryBottom = testimonialsSection.getBoundingClientRect().top;
                        } else {
                            const footer = document.querySelector('footer, .elementor-location-footer');
                            if (footer) {
                                boundaryBottom = footer.getBoundingClientRect().top;
                            }
                        }

                        // Trigger sticky as soon as the section touches the sticky offset
                        if (sectionRect.top <= stickyOffset && boundaryBottom > (navBar.offsetHeight + stickyOffset)) {
                            if (!navBar.classList.contains('intel-nav-fixed-js')) {
                                // Save original height
                                placeholder.style.height = navBar.offsetHeight + 'px';
                                const style = window.getComputedStyle(navBar);
                                placeholder.style.marginBottom = style.marginBottom;
                                
                                navBar.classList.add('intel-nav-fixed-js');
                                document.body.appendChild(navBar); // Escaping elementor transform context
                            }
                            
                            if (boundaryBottom <= (navBar.offsetHeight + stickyOffset)) {
                                navBar.style.top = (boundaryBottom - navBar.offsetHeight) + 'px';
                            } else {
                                navBar.style.top = (stickyOffset > 0 ? (Math.floor(stickyOffset) - 1) : 0) + 'px';
                            }
                        } else {
                            if (navBar.classList.contains('intel-nav-fixed-js')) {
                                navBar.classList.remove('intel-nav-fixed-js');
                                navBar.style.top = '';
                                placeholder.parentNode.insertBefore(navBar, placeholder.nextSibling);
                                placeholder.style.height = '0px';
                                placeholder.style.marginBottom = '0px';
                            }
                        }
                    }
                    
                    window.addEventListener('scroll', updateSticky, { passive: true });
                    window.addEventListener('resize', updateSticky, { passive: true });
                    setTimeout(updateSticky, 100);
                    
                    // Helper: find an Elementor section by heading text and inject an ID
                    function cmrInjectId(id, matchFn) {
                        if (document.getElementById(id)) return;
                        const allHeadings = Array.from(document.querySelectorAll('h1, h2, h3, h4, h5, h6, .elementor-heading-title, .elementor-widget-heading .elementor-heading-title'));
                        const heading = allHeadings.find(h => !h.closest('.intel-nav-bar') && matchFn(h.textContent.toLowerCase().trim()));
                        if (!heading) return;
                        // Walk up to find the nearest Elementor section/container (max 6 levels)
                        let target = heading;
                        for (let i = 0; i < 6; i++) {
                            const p = target.parentElement;
                            if (!p || p === document.body) break;
                            const et = p.getAttribute('data-element_type');
                            if (et === 'section' || et === 'container') { target = p; break; }
                            target = p;
                        }
                        target.id = id;
                    }

                    // AUTO-INJECT IDs on page load so nav links always work
                    function cmrAutoInjectIds() {
                        // id="featured" — Featured posts section (try to match heading only, no fallback needed — handled in click)
                        cmrInjectId('featured', t => t.includes('featured') || t.includes('top stories') || t.includes('highlighted'));

                        // id="latest-updates" — Points to Expert Viewpoints & Strategic Perspectives (the shortcode grid)
                        cmrInjectId('latest-updates', t => 
                            t.includes('expert viewpoints') || t.includes('strategic perspectives') ||
                            t.includes('latest updates') || t.includes('latest insights') || t.includes('recent updates')
                        );
                        // Fallback: inject on the .cmr-vpi-nav-bar (the filter/search bar row) so it scrolls to the articles
                        if (!document.getElementById('latest-updates')) {
                            const vpiNav = document.querySelector('.cmr-vpi-nav-bar');
                            if (vpiNav) vpiNav.id = 'latest-updates';
                        }

                        // id="cmr-live" — CMR Live section
                        cmrInjectId('cmr-live', t => t.includes('cmr live') || t.includes('cmr tv') || t.includes('live session') || t.includes('live video'));

                        // id="reports" — Reports by Industry section
                        cmrInjectId('reports', t => t.includes('reports by industry') || t.includes('similar reports'));

                        // id="expert-insights" — Footer card (tight target, max 3 levels)
                        if (!document.getElementById('expert-insights')) {
                            const allEls = Array.from(document.querySelectorAll('h1, h2, h3, h4, h5, h6, .elementor-heading-title'));
                            const footerCardHeading = allEls.find(h => {
                                const t = h.textContent.toLowerCase();
                                return !h.closest('.intel-nav-bar') && (
                                    (t.includes('let') && t.includes('connect')) ||
                                    t.includes('speak directly') || t.includes('market expert') ||
                                    t.includes('your challenge') || t.includes('your ambition')
                                );
                            });
                            if (footerCardHeading) {
                                let target = footerCardHeading;
                                for (let i = 0; i < 3; i++) {
                                    const p = target.parentElement;
                                    if (!p) break;
                                    target = p;
                                    const et = p.getAttribute('data-element_type');
                                    if (et === 'container' || et === 'section' || et === 'column') break;
                                }
                                target.id = 'expert-insights';
                            }
                        }
                    }
                    // Run immediately and after 1s for lazy-loaded Elementor widgets
                    cmrAutoInjectIds();
                    setTimeout(cmrAutoInjectIds, 1000);

                    // Smooth scroll on nav link click
                    const navLinks = navBar.querySelectorAll('.intel-nav-links a');
                    navLinks.forEach(link => {
                        link.addEventListener('click', function(e) {
                            const href = this.getAttribute('href');
                            if (!href || href.indexOf('#') === -1) return;

                            const targetId = href.substring(href.indexOf('#') + 1);
                            if (!targetId) return;

                            // "Featured" always scrolls to page top
                            if (targetId === 'featured') {
                                e.preventDefault();
                                window.scrollTo({ top: 0, behavior: 'smooth' });
                                return;
                            }

                            // Re-run auto-inject in case page changed
                            cmrAutoInjectIds();

                            const targetElement = document.getElementById(targetId);
                            if (targetElement) {
                                e.preventDefault();

                                let stickyOffset = 0;
                                const wpAdminBar = document.getElementById('wpadminbar');
                                if (wpAdminBar && window.getComputedStyle(wpAdminBar).position === 'fixed') {
                                    stickyOffset = wpAdminBar.offsetHeight;
                                }

                                const targetPosition = targetElement.getBoundingClientRect().top + window.scrollY - stickyOffset - 80;
                                window.scrollTo({ top: targetPosition, behavior: 'smooth' });
                            }
                        });
                    });
                });
            });
        </script>
        <?php
        return ob_get_clean();
    }
}
add_shortcode( 'cmr_viewpoints_insights', 'cmr_viewpoints_insights_shortcode' );
