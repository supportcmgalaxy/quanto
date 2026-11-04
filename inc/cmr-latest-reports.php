<?php
/**
 * CMR Latest Reports Section Shortcode (with AJAX filtering)
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! function_exists( 'cmr_latest_reports_shortcode' ) ) {
    function cmr_latest_reports_shortcode( $atts ) {
        $atts = shortcode_atts( array(
            'title' => 'Browse latest reports',
            'posts_per_page' => 8,
        ), $atts );

        // Get product categories for the filter
        $categories = get_terms( array(
            'taxonomy'   => 'product_cat',
            'hide_empty' => true,
        ) );

        ob_start();
        ?>
        <style>
            .cmr-latest-section {
                max-width: 1280px;
                margin: 60px auto;
                padding: 0 20px;
                font-family: 'Instrument Sans', sans-serif !important;
            }

            .cmr-latest-header {
                margin-bottom: 30px;
            }

            .cmr-latest-title {
                font-size: 32px;
                font-weight: 600;
                color: #000000;
                margin-bottom: 25px;
                letter-spacing: -0.5px;
            }

            .cmr-filter-bar {
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 20px;
                flex-wrap: wrap;
            }

            .cmr-filter-pills {
                display: flex;
                gap: 12px;
                align-items: center;
                flex-wrap: wrap;
            }

            .cmr-filter-pill {
                background: #ffffff;
                border: 1px solid #e5e7eb;
                color: #374151;
                padding: 0 24px;
                min-width: 72px;
                height: 40px;
                border-radius: 40px;
                font-size: 14px;
                font-weight: 500;
                cursor: pointer;
                transition: all 0.2s ease;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                text-align: center;
                line-height: 1;
                box-sizing: border-box;
                font-family: inherit;
            }

            .cmr-filter-pill:hover {
                border-color: #6b46c1;
                color: #6b46c1;
                background: #fdfcff;
            }

            .cmr-filter-pill.active {
                background: #6b46c1;
                border-color: #6b46c1;
                color: #ffffff;
                box-shadow: 0 2px 6px rgba(107, 70, 193, 0.25);
            }

            .cmr-search-wrapper {
                position: relative;
                display: flex;
                align-items: center;
                background: #ffffff;
                border: 1px solid #e5e7eb;
                border-radius: 40px;
                padding: 3px 4px 3px 18px;
                width: 290px;
                height: 40px;
                box-sizing: border-box;
                transition: border-color 0.2s ease, box-shadow 0.2s ease;
            }

            .cmr-search-wrapper:focus-within {
                border-color: #6b46c1;
                box-shadow: 0 0 0 3px rgba(107, 70, 193, 0.12);
            }

            .cmr-search-wrapper input {
                border: none !important;
                outline: none !important;
                background: transparent !important;
                font-size: 14px !important;
                width: 100% !important;
                color: #111827 !important;
                box-shadow: none !important;
                padding: 0 10px 0 0 !important;
                margin: 0 !important;
                font-family: inherit !important;
                height: 100% !important;
                line-height: normal !important;
            }

            .cmr-search-wrapper input::placeholder {
                color: #9ca3af !important;
                font-size: 14px !important;
            }

            .cmr-search-wrapper button {
                width: 32px !important;
                height: 32px !important;
                border-radius: 50% !important;
                background: #6b46c1 !important;
                color: #ffffff !important;
                border: none !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                cursor: pointer !important;
                flex-shrink: 0 !important;
                padding: 0 !important;
                transition: background 0.2s ease, transform 0.15s ease;
            }

            .cmr-search-wrapper button:hover {
                background: #5b32b0 !important;
                transform: scale(1.05);
            }

            .cmr-search-wrapper button i {
                font-size: 13px !important;
                line-height: 1 !important;
            }

            .cmr-latest-grid {
                display: grid;
                grid-template-columns: repeat(4, 1fr);
                gap: 20px;
                margin-top: 40px;
            }

            .cmr-lr-card {
                background: #ffffff;
                border: 1px solid #e5e7eb;
                border-radius: 0;
                overflow: hidden;
                display: flex;
                flex-direction: column;
                transition: transform 0.3s ease;
            }

            .cmr-lr-card:hover {
                transform: translateY(-5px);
            }

            .cmr-lr-image-wrap {
                width: 100%;
                aspect-ratio: auto;
                height: auto;
                position: relative;
                overflow: hidden;
                background: #f8f9fa;
            }

            .cmr-lr-image-wrap img {
                width: 100%;
                height: auto;
                object-fit: contain;
                object-position: center;
                display: block;
            }

            .cmr-lr-badge {
                position: absolute;
                top: 15px;
                left: 15px;
                background: #ffffff;
                color: #ea580c; /* Orange for NEW */
                font-size: 11px;
                font-weight: 600;
                letter-spacing: 0.8px;
                text-transform: uppercase;
                height: 30px;
                padding: 0 12px;
                box-sizing: border-box;
                border-radius: 0;
                z-index: 10;
                display: inline-flex;
                align-items: center;
                gap: 6px;
                line-height: 1;
                box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            }

            .cmr-lr-badge i,
            .cmr-lr-badge svg {
                font-size: 11px;
                line-height: 1;
            }

            .cmr-lr-content {
                padding: 20px;
                display: flex;
                flex-direction: column;
                flex: 1;
            }

            .cmr-lr-category {
                font-size: 12px;
                color: #9ca3af;
                margin-bottom: 8px;
                font-weight: 500;
            }

            .cmr-lr-title {
                font-size: 16px;
                font-weight: 600;
                color: #111827;
                margin-bottom: 15px;
                line-height: 1.4;
                text-decoration: none;
            }

            .cmr-lr-title:hover {
                color: #6b46c1;
            }

            .cmr-lr-stars {
                color: #f59e0b;
                font-size: 13px;
                margin-bottom: 20px;
            }
            .cmr-lr-stars span {
                color: #6b7280;
                font-size: 12px;
                margin-left: 5px;
            }

            .cmr-lr-price {
                font-size: 20px;
                font-weight: 600;
                color: #111827;
                margin-bottom: 20px;
                margin-top: auto;
            }

            .cmr-lr-btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                width: 100%;
                padding: 10px;
                background: #ffffff;
                border: 1px solid #d1d5db;
                border-radius: 50px;
                color: #374151;
                font-size: 14px;
                font-weight: 600;
                text-decoration: none;
                transition: all 0.2s ease;
            }

            .cmr-lr-btn:hover {
                background: #f3f4f6;
                color: #111827;
            }

            .cmr-load-more-wrap {
                text-align: center;
                margin-top: 40px;
            }

            .cmr-load-more-btn {
                background: #ffffff;
                border: 1px solid #d1d5db;
                color: #374151;
                padding: 12px 40px;
                border-radius: 50px;
                font-size: 14px;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.2s ease;
            }

            .cmr-load-more-btn:hover {
                background: #f3f4f6;
                color: #111827;
            }

            .cmr-pagination-wrap {
                display: none;
                justify-content: center;
                align-items: center;
                margin-top: 40px;
                gap: 5px;
            }
            
            .cmr-pagination-wrap .page-numbers {
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
                transition: all 0.2s ease;
            }
            
            .cmr-pagination-wrap .page-numbers:hover {
                opacity: 0.7;
            }
            
            .cmr-pagination-wrap .page-numbers.current {
                background: #6b46c1;
                color: #fff;
            }
            
            .cmr-pagination-wrap .page-numbers.prev, 
            .cmr-pagination-wrap .page-numbers.next {
                color: #6b46c1;
            }
            
            .cmr-pagination-wrap .page-numbers.dots {
                width: auto;
            }

            .cmr-loading-spinner {
                display: none;
                text-align: center;
                margin-top: 20px;
            }

            @media (max-width: 1024px) {
                .cmr-latest-grid {
                    grid-template-columns: repeat(3, 1fr);
                }
            }

            @media (max-width: 768px) {
                .cmr-latest-grid {
                    grid-template-columns: repeat(2, 1fr);
                }
                .cmr-filter-bar {
                    flex-direction: column;
                    align-items: stretch;
                    gap: 16px;
                }
                .cmr-filter-pills {
                    display: flex;
                    flex-wrap: nowrap;
                    overflow-x: auto;
                    -webkit-overflow-scrolling: touch;
                    scrollbar-width: none;
                    gap: 8px;
                    width: 100%;
                    padding-bottom: 4px;
                }
                .cmr-filter-pills::-webkit-scrollbar {
                    display: none;
                }
                .cmr-filter-pill {
                    flex-shrink: 0;
                    white-space: nowrap;
                    padding: 0 18px;
                    font-size: 13px;
                    height: 36px;
                    min-width: 60px;
                }
                .cmr-search-wrapper {
                    width: 100%;
                    height: 40px;
                }
            }

            @media (max-width: 480px) {
                .cmr-latest-grid {
                    grid-template-columns: 1fr;
                }
            }

            .cmr-typing-indicator {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                padding: 20px;
            }
            .cmr-typing-indicator span {
                width: 12px;
                height: 12px;
                background-color: #6b46c1;
                border-radius: 50%;
                animation: cmr-typing 1.4s infinite ease-in-out both;
            }
            .cmr-typing-indicator span:nth-child(1) {
                animation-delay: -0.32s;
            }
            .cmr-typing-indicator span:nth-child(2) {
                animation-delay: -0.16s;
            }
            @keyframes cmr-typing {
                0%, 80%, 100% { transform: scale(0); }
                40% { transform: scale(1); }
            }
        </style>
        <?php $uid = uniqid(); ?>

        <div id="cmr-latest-reports" style="position: relative; top: -100px;"></div>
        <section class="cmr-latest-section" id="reports-<?php echo $uid; ?>">
            <div class="cmr-latest-header">
                <h2 class="cmr-latest-title"><?php echo esc_html( $atts['title'] ); ?></h2>
                <div class="cmr-filter-bar">
                    <div class="cmr-filter-pills">
                        <button class="cmr-filter-pill active" data-cat="">All</button>
                        <?php foreach ( $categories as $category ) : 
                            $slug_check = strtolower( $category->slug );
                            if ( in_array( $slug_check, array( 'digital', 'uncategorized', 'uncategorised' ) ) ) {
                                continue;
                            }
                        ?>
                            <button class="cmr-filter-pill" data-cat="<?php echo esc_attr( $category->slug ); ?>"><?php echo esc_html( $category->name ); ?></button>
                        <?php endforeach; ?>
                    </div>
                    <div class="cmr-search-wrapper">
                        <input type="text" id="cmr-lr-search-<?php echo $uid; ?>" placeholder="Search by name">
                        <button id="cmr-lr-search-btn-<?php echo $uid; ?>" aria-label="<?php esc_attr_e( 'Search Reports', 'quanto' ); ?>"><i class="fa-solid fa-magnifying-glass"></i></button>
                    </div>
                </div>
            </div>
            
            <div class="cmr-latest-grid" id="cmr-latest-grid-<?php echo $uid; ?>">
                <!-- Products will be loaded here via AJAX -->
            </div>

            <div class="cmr-loading-spinner" id="cmr-loading-spinner-<?php echo $uid; ?>">
                <div class="cmr-typing-indicator">
                    <span></span><span></span><span></span>
                </div>
            </div>

            <div class="cmr-load-more-wrap">
                <button class="cmr-load-more-btn" id="cmr-load-more-btn-<?php echo $uid; ?>" style="display: none;">Load More</button>
            </div>
            
            <div class="cmr-pagination-wrap" id="cmr-pagination-wrap-<?php echo $uid; ?>"></div>
        </section>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let currentPage = 1;
                let currentCategory = '';
                let currentSearch = '';
                let loadMoreCount = 0;
                const grid = document.getElementById('cmr-latest-grid-<?php echo $uid; ?>');
                const loadMoreBtn = document.getElementById('cmr-load-more-btn-<?php echo $uid; ?>');
                const paginationWrap = document.getElementById('cmr-pagination-wrap-<?php echo $uid; ?>');
                const spinner = document.getElementById('cmr-loading-spinner-<?php echo $uid; ?>');
                const searchInput = document.getElementById('cmr-lr-search-<?php echo $uid; ?>');
                const searchBtn = document.getElementById('cmr-lr-search-btn-<?php echo $uid; ?>');
                const filterPills = document.getElementById('reports-<?php echo $uid; ?>').querySelectorAll('.cmr-filter-pill');

                function loadProducts( reset = false, pageNum = null ) {
                    if ( pageNum ) {
                        currentPage = pageNum;
                    }

                    if ( reset ) {
                        if (!pageNum) {
                            currentPage = 1;
                            loadMoreCount = 0;
                        }
                        grid.style.opacity = '0.5';
                    } else {
                        grid.style.opacity = '1';
                    }

                    spinner.style.display = 'block';
                    loadMoreBtn.style.display = 'none';
                    paginationWrap.style.display = 'none';

                    const formData = new FormData();
                    formData.append('action', 'cmr_load_reports');
                    formData.append('paged', currentPage);
                    formData.append('category', currentCategory);
                    formData.append('search', currentSearch);
                    formData.append('posts_per_page', <?php echo intval( $atts['posts_per_page'] ); ?>);

                    fetch('<?php echo admin_url( 'admin-ajax.php' ); ?>', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        spinner.style.display = 'none';
                        grid.style.opacity = '1';
                        if ( data.success ) {
                            if ( reset ) {
                                grid.innerHTML = data.data.html;
                            } else {
                                grid.insertAdjacentHTML('beforeend', data.data.html);
                            }
                            
                            if ( data.data.has_more ) {
                                if ( loadMoreCount < 2 ) {
                                    loadMoreBtn.style.display = 'inline-block';
                                } else {
                                    paginationWrap.innerHTML = data.data.pagination;
                                    paginationWrap.style.display = 'flex';
                                    bindPaginationEvents();
                                }
                            } else if ( data.data.pagination && loadMoreCount >= 2 ) {
                                paginationWrap.innerHTML = data.data.pagination;
                                paginationWrap.style.display = 'flex';
                                bindPaginationEvents();
                            }
                        } else {
                            if ( reset ) {
                                grid.innerHTML = '<div style="grid-column: 1 / -1; text-align: center; padding: 50px 20px; color: #6b7280; font-size: 15px;"><i class="fa-solid fa-magnifying-glass" style="font-size: 24px; color: #9ca3af; margin-bottom: 12px; display: block;"></i>No reports found matching your criteria.</div>';
                            }
                        }
                    })
                    .catch(err => {
                        spinner.style.display = 'none';
                        console.error('Error fetching reports:', err);
                    });
                }

                function bindPaginationEvents() {
                    const pageLinks = paginationWrap.querySelectorAll('a.page-numbers');
                    pageLinks.forEach(link => {
                        link.addEventListener('click', function(e) {
                            e.preventDefault();
                            const href = this.getAttribute('href');
                            
                            let page = 1;
                            const pagedMatch = href.match(/paged=(\d+)/);
                            const pageMatch = href.match(/\/page\/(\d+)/);
                            
                            if ( pagedMatch ) {
                                page = pagedMatch[1];
                            } else if ( pageMatch ) {
                                page = pageMatch[1];
                            } else {
                                const textVal = parseInt(this.innerText);
                                if ( !isNaN(textVal) ) {
                                    page = textVal;
                                }
                            }
                            
                            loadProducts(false, parseInt(page));
                        });
                    });
                }

                // Initial Load
                loadProducts(true);

                // Load More Click
                loadMoreBtn.addEventListener('click', function() {
                    currentPage++;
                    loadMoreCount++;
                    loadProducts();
                });

                // Filter Click
                filterPills.forEach(pill => {
                    pill.addEventListener('click', function() {
                        filterPills.forEach(p => p.classList.remove('active'));
                        this.classList.add('active');
                        currentCategory = this.getAttribute('data-cat');
                        loadProducts(true);
                    });
                });

                // Search with debounce & live clearing
                let searchTimer = null;

                function triggerSearch() {
                    const val = searchInput.value.trim();
                    if ( val !== currentSearch ) {
                        currentSearch = val;
                        loadProducts(true);
                    }
                }

                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimer);
                    searchTimer = setTimeout(triggerSearch, 350);
                });

                searchBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    clearTimeout(searchTimer);
                    triggerSearch();
                });

                searchInput.addEventListener('keydown', function(e) {
                    if ( e.key === 'Enter' ) {
                        e.preventDefault();
                        clearTimeout(searchTimer);
                        triggerSearch();
                    }
                });
            });
        </script>
        <?php
        return ob_get_clean();
    }
}
add_shortcode( 'cmr_latest_reports', 'cmr_latest_reports_shortcode' );

// AJAX Handler for Latest Reports
if ( ! function_exists( 'cmr_load_reports_ajax' ) ) {
    function cmr_load_reports_ajax() {
        $paged = isset( $_POST['paged'] ) ? intval( $_POST['paged'] ) : 1;
        $category = isset( $_POST['category'] ) ? sanitize_text_field( $_POST['category'] ) : '';
        $search = isset( $_POST['search'] ) ? trim( sanitize_text_field( $_POST['search'] ) ) : '';
        $posts_per_page = isset( $_POST['posts_per_page'] ) ? intval( $_POST['posts_per_page'] ) : 8;
        
        $query_args = array(
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => $posts_per_page,
            'paged' => $paged,
            'orderby' => 'date',
            'order' => 'DESC',
        );

        if ( ! empty( $category ) ) {
            $query_args['tax_query'] = array(
                array(
                    'taxonomy' => 'product_cat',
                    'field'    => 'slug',
                    'terms'    => $category,
                ),
            );
        }

        if ( ! empty( $search ) ) {
            $query_args['s'] = $search;
        }

        $query = new WP_Query( $query_args );

        if ( ! $query->have_posts() ) {
            wp_send_json_error( 'No products found' );
        }

        ob_start();
        while ( $query->have_posts() ) {
            $query->the_post();
            global $product;
            
            $image_url = wp_get_attachment_image_src( $product->get_image_id(), 'medium_large' );
            if ( ! $image_url ) {
                $image_url = wp_get_attachment_image_src( $product->get_image_id(), 'large' );
            }
            if ( ! $image_url ) {
                $image_url = wp_get_attachment_image_src( $product->get_image_id(), 'medium' );
            }
            $image_url = $image_url ? $image_url[0] : 'https://via.placeholder.com/600x600';
            
            $cats = $product->get_category_ids();
            $cat_name = 'Report';
            if ( ! empty($cats) ) {
                $term = get_term_by( 'id', $cats[0], 'product_cat' );
                if ( $term ) {
                    $cat_name = $term->name;
                }
            }
            
            ?>
            <div class="cmr-lr-card">
                <div class="cmr-lr-image-wrap">
                    <?php if ( function_exists( 'cmr_render_product_badge' ) ) { cmr_render_product_badge( $product, 'cmr-lr-badge' ); } ?>
                    <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $product->get_name() ); ?>">
                </div>
                <div class="cmr-lr-content">
                    <div class="cmr-lr-category">&mdash; <?php echo esc_html( $cat_name ); ?></div>
                    <a href="<?php echo esc_url( $product->get_permalink() ); ?>" class="cmr-lr-title"><?php echo esc_html( $product->get_name() ); ?></a>
                    
                    <div class="cmr-lr-stars">
                        <?php 
                        $rating = floatval( $product->get_average_rating() );
                        $count = intval( $product->get_review_count() );
                        for ( $s = 1; $s <= 5; $s++ ) {
                            if ( $s <= $rating ) echo '<i class="fa-solid fa-star"></i>';
                            elseif ( $s - 0.5 <= $rating ) echo '<i class="fa-solid fa-star-half-stroke"></i>';
                            else echo '<i class="fa-regular fa-star"></i>';
                        }
                        ?>
                        <span>(<?php echo $count; ?>)</span>
                    </div>
                    
                    <div class="cmr-lr-price">
                        <?php echo $product->get_price_html(); ?>
                    </div>
                    
                    <a href="<?php echo esc_url( $product->get_permalink() ); ?>" class="cmr-lr-btn">
                        Download Report <i class="fa-solid fa-arrow-down"></i>
                    </a>
                </div>
            </div>
            <?php
        }
        $html = ob_get_clean();

        $has_more = ( $query->max_num_pages > $paged );

        $pagination_html = '';
        if ( $query->max_num_pages > 1 ) {
            // Include custom SVG arrows for Prev/Next like the articles
            $prev_icon = '<svg width="10" height="16" viewBox="0 0 10 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8.5 15L1.5 8L8.5 1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
            $next_icon = '<svg width="10" height="16" viewBox="0 0 10 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.5 15L8.5 8L1.5 1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
            
            $pagination_html = paginate_links( array(
                'base' => '%_%',
                'format' => '?paged=%#%',
                'current' => $paged,
                'total' => $query->max_num_pages,
                'prev_text' => $prev_icon,
                'next_text' => $next_icon,
                'type' => 'plain'
            ) );
        }

        wp_send_json_success( array(
            'html' => $html,
            'has_more' => $has_more,
            'pagination' => $pagination_html
        ) );
    }
}
add_action( 'wp_ajax_cmr_load_reports', 'cmr_load_reports_ajax' );
add_action( 'wp_ajax_nopriv_cmr_load_reports', 'cmr_load_reports_ajax' );
