<?php
/**
 * CMR Media Releases General Shortcode
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'cmr_media_releases_general', 'cmr_media_releases_general_shortcode' );

function cmr_media_releases_general_shortcode( $atts ) {
    ob_start();

    $atts = shortcode_atts( array(
        'count'          => 3,
        'posts_per_page' => 3,
    ), $atts, 'cmr_media_releases_general' );
    $limit = !empty($atts['count']) ? intval($atts['count']) : 3;

    $terms = array('media-release', 'media-releases', 'press-release', 'press-releases', 'pressreleases', 'press-release-2', 'press-releases-2');

    // Query latest cmr_news and standard posts (press releases)
    $args = array(
        'post_type'      => array('cmr_news', 'post'),
        'posts_per_page' => 12,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'post_status'    => 'publish',
        'tax_query'      => array(
            'relation' => 'OR',
            array(
                'taxonomy' => 'cmr_news_category',
                'field'    => 'slug',
                'terms'    => $terms,
            ),
            array(
                'taxonomy' => 'category',
                'field'    => 'slug',
                'terms'    => $terms,
            ),
        ),
    );
    $query = new WP_Query( $args );

    $posts_data = [];
    $seen_titles = [];
    if ( $query->have_posts() ) {
        while ( $query->have_posts() && count($posts_data) < $limit ) {
            $query->the_post();
            
            $post_id = get_the_ID();
            $title = get_the_title();
            $link = get_permalink();
            $title_key = trim(mb_strtolower($title));
            if ( in_array($title_key, $seen_titles) ) {
                continue;
            }
            $seen_titles[] = $title_key;

            $excerpt = function_exists( 'quanto_get_clean_post_excerpt' ) ? quanto_get_clean_post_excerpt( $post_id, 20 ) : wp_trim_words( get_the_excerpt( $post_id ), 20, '...' );
            $bg_image = get_the_post_thumbnail_url( $post_id, 'full' );

            // Estimate read time (rough calculation based on word count)
            $content = get_post_field( 'post_content', $post_id );
            $word_count = str_word_count( strip_tags( $content ) );
            $read_time = ceil( $word_count / 200 ); // Average 200 words per minute
            if ($read_time < 1) $read_time = 1;
            
            $document_id = get_post_meta( $post_id, '_cmr_news_document_id', true );
            $pdf_link = '';
            if ( $document_id ) {
                $pdf_link = wp_get_attachment_url( $document_id );
            }

            $posts_data[] = [
                'title'     => $title,
                'link'      => $link,
                'excerpt'   => $excerpt,
                'image'     => $bg_image,
                'read_time' => $read_time . ' min read',
                'pdf_link'  => $pdf_link
            ];
        }
    }
    wp_reset_postdata();

    // Fallback data if no posts found
    if ( empty( $posts_data ) ) {
        $posts_data = [
            [
                'title' => 'CMR India expands AI-driven intelligence capabilities',
                'excerpt' => 'Strengthening enterprise research solutions across key sectors to provide real-time strategic guidance for global stakeholders.',
                'image' => quanto_get_placeholder_image_uri(),
                'read_time' => '4 min read',
                'link' => '#',
                'pdf_link' => '#'
            ],
            [
                'title' => 'India\'s EV Market Accelerates 57% in Q1 2026...',
                'excerpt' => 'Electric vehicles continue to dominate the new car sales market...',
                'image' => quanto_get_placeholder_image_uri(),
                'read_time' => '3 min read',
                'link' => '#',
                'pdf_link' => ''
            ],
            [
                'title' => 'ADAS Market Grows 49%; CMR Survey 2026...',
                'excerpt' => 'Advanced Driver Assistance Systems are becoming standard...',
                'image' => quanto_get_placeholder_image_uri(),
                'read_time' => '5 min read',
                'link' => '#',
                'pdf_link' => '#'
            ],
            [
                'title' => 'Tech Infrastructure Spending Surges Post-Pandemic',
                'excerpt' => 'A closer look at how enterprises are scaling their tech stacks.',
                'image' => quanto_get_placeholder_image_uri(),
                'read_time' => '2 min read',
                'link' => '#',
                'pdf_link' => ''
            ]
        ];
    }

    $top_post = !empty($posts_data) ? $posts_data[0] : null;
    $bottom_posts = array_slice( $posts_data, 0, $limit );
    ?>
    <style>
        .cmr-mrg-section {
            font-family: 'Instrument Sans', sans-serif;
            max-width: 1280px;
            margin: 0 auto;
            padding: 60px 20px;
            color: #111;
        }

        /* Top Featured Post */
        .cmr-mrg-featured {
            display: flex;
            background: #fff;
            border: 1px solid #eaeaea;
            margin-bottom: 24px;
            min-height: 400px;
            transition: all 0.4s ease;
        }
        
        .cmr-mrg-featured-img-wrap {
            flex: 0 0 50%;
            overflow: hidden;
            position: relative;
        }

        .cmr-mrg-featured-img {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
            object-position: center !important;
            background-color: #f4f4f4;
            position: absolute;
            top: 0;
            left: 0;
            transition: opacity 0.4s ease;
        }

        .cmr-mrg-placeholder-svg {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #ccc;
        }

        .cmr-mrg-featured-content {
            flex: 0 0 50%;
            padding: 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .cmr-mrg-meta-top {
            display: flex;
            align-items: center;
            gap: 20px;
            font-size: 14px;
            color: #666;
            margin-bottom: 20px;
        }

        .cmr-mrg-label {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .cmr-mrg-label::before {
            content: "";
            width: 24px;
            height: 1px;
            background: #666;
            display: inline-block;
        }

        .cmr-mrg-title {
            font-size: 32px;
            font-weight: 600;
            line-height: 1.1;
            margin: 0 0 20px 0;
            letter-spacing: 1px;
            color: #111;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .cmr-mrg-excerpt {
            font-size: 15px;
            color: #444;
            line-height: 1.6;
            margin: 0 0 40px 0;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .cmr-mrg-actions {
            display: flex;
            gap: 16px;
            align-items: center;
        }

        .cmr-mrg-btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background-color: #6241CA !important;
            border: 1px solid #6241CA !important;
            color: #ffffff !important;
            text-decoration: none;
            padding: 8px 24px !important;
            border-radius: 50px !important;
            font-size: 15px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .cmr-mrg-btn-primary svg path,
        .cmr-mrg-btn-primary svg polyline,
        .cmr-mrg-btn-primary svg line {
            stroke: #ffffff !important;
        }
        
        .cmr-mrg-btn-primary:hover {
            background-color: #6241ca !important;
            border-color: #6241ca !important;
        }

        .cmr-mrg-btn-outline {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background-color: white !important;
            border: 1px solid #000000 !important;
            color: #000000 !important;
            text-decoration: none;
            padding: 8px 24px !important;
            border-radius: 50px !important;
            font-size: 15px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .cmr-mrg-btn-outline svg path,
        .cmr-mrg-btn-outline svg polyline,
        .cmr-mrg-btn-outline svg line {
            stroke: #000000 !important;
        }

        .cmr-mrg-btn-outline:hover {
            background-color: #f5f5f5 !important;
            border-color: #f5f5f5 !important;
        }

        /* Bottom Row Items */
        .cmr-mrg-nav {
            display: flex;
            gap: 24px;
        }

        .cmr-mrg-nav-item {
            flex: 1;
            display: flex;
            background: #f9f9fb;
            cursor: pointer;
            border-top: 3px solid transparent; position: relative;
            transition: all 0.3s ease;
            text-decoration: none;
            color: inherit;
        }
        
        .cmr-mrg-nav-item:hover {
            background: #f0f0f5;
        }

        .cmr-mrg-nav-item::before {
            content: '';
            position: absolute;
            top: -3px;
            left: 0;
            height: 3px;
            background-color: #00E5FF;
            width: 0;
            z-index: 10;
        }

        .cmr-mrg-nav-item.active {
            background: #fff;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        .cmr-mrg-nav-item.active::before {
            animation: cmrLoaderAnim 5s linear forwards;
        }

        @keyframes cmrLoaderAnim {
            from { width: 0; }
            to { width: 100%; }
        }

        .cmr-mrg-nav-img-wrap {
            width: 120px;
            flex-shrink: 0;
            position: relative;
            background-color: #fff;
            display: flex;
            align-items: stretch;
            justify-content: center;
            overflow: hidden;
        }

        .cmr-mrg-nav-img {
            width: 100% !important;
            height: 100% !important;
            min-height: 100% !important;
            max-height: none !important;
            object-fit: cover !important;
            object-position: center !important;
            background-color: #f4f4f4;
            display: block;
            position: absolute;
            top: 0;
            left: 0;
            z-index: 1;
        }

        .cmr-mrg-nav-content {
            padding: 20px;
            flex: 1;
        }

        .cmr-mrg-nav-label {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 12px;
            color: #666;
            margin-bottom: 8px;
        }

        .cmr-mrg-nav-label::before {
            content: "";
            width: 16px;
            height: 1px;
            background: #666;
            display: inline-block;
        }

        .cmr-mrg-nav-title {
            font-size: 15px;
            font-weight: 600;
            line-height: 1.4;
            margin: 0;
            letter-spacing: 1px;
            color: #111;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        @media (max-width: 992px) {
            .cmr-mrg-section {
                padding: 40px 16px !important;
            }
            .cmr-mrg-featured {
                flex-direction: column;
            }
            .cmr-mrg-featured-img-wrap {
                height: 300px;
            }
            .cmr-mrg-featured-content {
                padding: 30px 20px;
            }
            .cmr-mrg-nav {
                flex-direction: column;
            }
        }
        
        @media (max-width: 768px) {
            .cmr-mrg-section {
                padding: 20px 16px 40px 16px !important;
                box-sizing: border-box !important;
                max-width: 100% !important;
            }
            .cmr-mrg-featured {
                margin-left: 0 !important;
                margin-right: 0 !important;
                width: 100% !important;
                box-sizing: border-box !important;
            }
            .cmr-mrg-featured-content {
                padding: 24px 16px !important;
                box-sizing: border-box !important;
            }
            .cmr-mrg-title {
                font-size: 24px !important;
                line-height: 1.25 !important;
                margin-bottom: 12px !important;
            }
            .cmr-mrg-excerpt {
                font-size: 14px !important;
                line-height: 1.5 !important;
                margin-bottom: 24px !important;
            }
            .cmr-mrg-actions {
                width: 100% !important;
                flex-direction: column !important;
                align-items: stretch !important;
                gap: 12px !important;
            }
            .cmr-mrg-btn-primary,
            .cmr-mrg-btn-outline {
                width: 100% !important;
                display: flex !important;
                justify-content: center !important;
                align-items: center !important;
                box-sizing: border-box !important;
                text-align: center !important;
                padding: 12px 20px !important;
                font-size: 15px !important;
            }
            .cmr-mrg-nav-item {
                flex-direction: column;
            }
            .cmr-mrg-nav-img-wrap {
                width: 100%;
                height: 180px;
            }
            .cmr-mrg-nav-content {
                padding: 16px !important;
            }
        }
        
        @media (max-width: 576px) {
            .cmr-mrg-title {
                font-size: 22px !important;
            }
        }
    </style>

    <div class="cmr-mrg-section" id="cmr-in-news">
        <!-- Top Featured Area -->
        <div class="cmr-mrg-featured">
            <div class="cmr-mrg-featured-img-wrap" style="background-color:#fff;">
                <svg class="cmr-mrg-placeholder-svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="display: <?php echo $top_post['image'] ? 'none' : 'block'; ?>;"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                <img src="<?php echo esc_url($top_post['image']); ?>" alt="Featured Image" class="cmr-mrg-featured-img" id="cmr-mrg-main-img" style="display: <?php echo $top_post['image'] ? 'block' : 'none'; ?>;">
            </div>
            <div class="cmr-mrg-featured-content">
                <div class="cmr-mrg-meta-top">
                    <span class="cmr-mrg-label">Press Release</span>
                    <span id="cmr-mrg-main-time"><?php echo esc_html($top_post['read_time']); ?></span>
                </div>
                <h2 class="cmr-mrg-title" id="cmr-mrg-main-title"><?php echo wp_kses_post($top_post['title']); ?></h2>
                <p class="cmr-mrg-excerpt" id="cmr-mrg-main-excerpt"><?php echo wp_kses_post($top_post['excerpt']); ?></p>
                
                <div class="cmr-mrg-actions">
                    <a href="<?php echo esc_url($top_post['link']); ?>" class="cmr-mrg-btn-primary" id="cmr-mrg-main-link">
                        Read full Release
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Bottom Navigation Row -->
        <div class="cmr-mrg-nav">
            <?php foreach ( $bottom_posts as $index => $post ) : ?>
            <div class="cmr-mrg-nav-item <?php echo $index === 0 ? 'active' : ''; ?>" data-index="<?php echo $index; ?>">
                <div class="cmr-mrg-nav-img-wrap">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="color:#ccc; position:absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                    <?php if ($post['image']) : ?>
                        <img src="<?php echo esc_url($post['image']); ?>" alt="Thumbnail" class="cmr-mrg-nav-img">
                    <?php endif; ?>
                </div>
                <div class="cmr-mrg-nav-content">
                    <div class="cmr-mrg-nav-label">Media Releases</div>
                    <h4 class="cmr-mrg-nav-title"><?php echo wp_kses_post($post['title']); ?></h4>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const postsData = <?php echo json_encode($bottom_posts); ?>;
        const navItems = document.querySelectorAll('.cmr-mrg-nav-item');
        
        const mainImg = document.getElementById('cmr-mrg-main-img');
        const mainTime = document.getElementById('cmr-mrg-main-time');
        const mainTitle = document.getElementById('cmr-mrg-main-title');
        const mainExcerpt = document.getElementById('cmr-mrg-main-excerpt');
        const mainLink = document.getElementById('cmr-mrg-main-link');

        let currentIndex = 0;
        let rotationInterval;

        function switchTab(idx) {
            navItems.forEach(nav => nav.classList.remove('active'));
            void document.body.offsetWidth; // Force reflow to restart CSS animation
            
            const activeItem = document.querySelector(`.cmr-mrg-nav-item[data-index="${idx}"]`);
            if (activeItem) {
                activeItem.classList.add('active');
            }
            
            const data = postsData[idx];
            if (data) {
                mainImg.style.opacity = 0;
                setTimeout(() => {
                    if (data.image) {
                        mainImg.src = data.image;
                        mainImg.style.display = 'block';
                        const svg = document.querySelector('.cmr-mrg-placeholder-svg');
                        if(svg) svg.style.display = 'none';
                    } else {
                        mainImg.style.display = 'none';
                        const svg = document.querySelector('.cmr-mrg-placeholder-svg');
                        if(svg) svg.style.display = 'block';
                    }
                    mainTime.textContent = data.read_time;
                    mainTitle.innerHTML = data.title;
                    mainExcerpt.innerHTML = data.excerpt;
                    if (mainLink && data.link) {
                        mainLink.href = data.link;
                    }
                    
                    mainImg.style.opacity = 1;
                }, 200);
            }
        }

        function startRotation() {
            clearInterval(rotationInterval);
            rotationInterval = setInterval(() => {
                currentIndex = (currentIndex + 1) % postsData.length;
                switchTab(currentIndex);
            }, 5000);
        }

        navItems.forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const idx = parseInt(this.getAttribute('data-index'), 10);
                currentIndex = idx;
                switchTab(currentIndex);
                startRotation(); // Restart interval on manual click
            });
        });

        // Start initial rotation
        startRotation();
    });
    </script>
    <?php
    return ob_get_clean();
}
