<?php
/**
 * CMR SMB Connect General Shortcode
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'cmr_smb_connect_general', 'cmr_smb_connect_general_shortcode' );

function cmr_smb_connect_general_shortcode( $atts ) {
    ob_start();

    $unique_ids = cmr_get_unique_smb_post_ids();
    $sliced_ids = array_slice( $unique_ids, 0, 4 );

    $query = new WP_Query(); // Empty default
    if ( ! empty( $sliced_ids ) ) {
        $args = array(
            'post_type'      => array( 'post', 'cmr_news', 'cmr_media' ),
            'post__in'       => $sliced_ids,
            'orderby'        => 'post__in', // Maintain the correct date order from SQL
            'posts_per_page' => 4,
        );
        $query = new WP_Query( $args );
    }

    $posts_data = [];
    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            
            $post_id = get_the_ID();
            $title = get_the_title();
            $link = get_permalink();
            $excerpt = wp_trim_words( get_the_excerpt(), 20 );
            $bg_image = get_the_post_thumbnail_url( $post_id, 'large' );

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

    $top_post = $posts_data[0];
    $bottom_posts = array_slice($posts_data, 1, 3); // Take next 3 for the bottom tabs
    ?>
    <style>
        .cmr-smbcg-section {
            font-family: 'Instrument Sans', sans-serif;
            max-width: 1280px;
            margin: 0 auto;
            padding: 60px 20px;
            color: #111;
        }

        /* Top Featured Post */
        .cmr-smbcg-featured {
            display: flex;
            background: #fff;
            border: 1px solid #eaeaea;
            margin-bottom: 24px;
            min-height: 400px;
            transition: all 0.4s ease;
        }
        
        .cmr-smbcg-featured-img-wrap {
            flex: 0 0 50%;
            overflow: hidden;
            position: relative;
            min-height: 380px;
            align-self: stretch;
        }

        .cmr-smbcg-featured-img {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            display: block !important;
            margin: 0 !important;
            padding: 0 !important;
            transition: opacity 0.4s ease;
        }

        .cmr-smbcg-placeholder-svg {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #ccc;
        }

        .cmr-smbcg-featured-content {
            flex: 0 0 50%;
            padding: 100px 60px 100px 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .cmr-smbcg-meta-top {
            display: flex;
            align-items: center;
            gap: 20px;
            font-size: 14px;
            color: #666;
            margin-bottom: 20px;
        }

        .cmr-smbcg-label {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .cmr-smbcg-label::before {
            content: "";
            width: 24px;
            height: 1px;
            background: #666;
            display: inline-block;
        }

        .cmr-smbcg-title {
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

        .cmr-smbcg-excerpt {
            font-size: 15px;
            color: #444;
            line-height: 1.6;
            margin: 0 0 40px 0;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .cmr-smbcg-actions {
            display: flex;
            gap: 16px;
            align-items: center;
        }

        .cmr-smbcg-btn-primary {
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
        
        .cmr-smbcg-btn-primary svg path,
        .cmr-smbcg-btn-primary svg polyline,
        .cmr-smbcg-btn-primary svg line {
            stroke: #ffffff !important;
        }
        
        .cmr-smbcg-btn-primary:hover {
            background-color: #6241ca !important;
            border-color: #6241ca !important;
        }

        .cmr-smbcg-btn-outline {
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

        .cmr-smbcg-btn-outline svg path,
        .cmr-smbcg-btn-outline svg polyline,
        .cmr-smbcg-btn-outline svg line {
            stroke: #000000 !important;
        }

        .cmr-smbcg-btn-outline:hover {
            background-color: #f5f5f5 !important;
            border-color: #f5f5f5 !important;
        }

        /* Bottom Row Items */
        .cmr-smbcg-nav {
            display: flex;
            gap: 24px;
        }

        .cmr-smbcg-nav-item {
            flex: 1;
            display: flex;
            background: #f9f9fb;
            cursor: pointer;
            border-top: 3px solid transparent; position: relative;
            transition: all 0.3s ease;
            text-decoration: none;
            color: inherit;
        }
        
        .cmr-smbcg-nav-item:hover {
            background: #f0f0f5;
        }

        .cmr-smbcg-nav-item::before {
            content: '';
            position: absolute;
            top: -3px;
            left: 0;
            height: 3px;
            background-color: #00E5FF;
            width: 0;
            z-index: 10;
        }

        .cmr-smbcg-nav-item.active::before {
            animation: cmrLoaderAnim 5s linear forwards;
        }

        @keyframes cmrLoaderAnim {
            from { width: 0; }
            to { width: 100%; }
        }

        .cmr-smbcg-nav-item.active {
            background: #fff;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        .cmr-smbcg-nav-img-wrap {
            width: 120px;
            flex-shrink: 0;
            position: relative;
            background-color: #f4f4f4;
            display: flex;
            align-items: stretch;
            justify-content: center;
            overflow: hidden;
        }

        .cmr-smbcg-nav-img {
            width: 100% !important;
            height: 100% !important;
            min-height: 100% !important;
            max-height: none !important;
            object-fit: cover !important;
            display: block;
            position: absolute;
            top: 0;
            left: 0;
            z-index: 1;
        }

        .cmr-smbcg-nav-content {
            padding: 20px;
            flex: 1;
        }

        .cmr-smbcg-nav-label {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 12px;
            color: #666;
            margin-bottom: 8px;
        }

        .cmr-smbcg-nav-label::before {
            content: "";
            width: 16px;
            height: 1px;
            background: #666;
            display: inline-block;
        }

        .cmr-smbcg-nav-title {
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
            .cmr-smbcg-section {
                padding: 40px 16px !important;
                box-sizing: border-box !important;
                width: 100% !important;
                max-width: 100% !important;
            }
            .cmr-smbcg-featured {
                flex-direction: column;
                margin-left: 0 !important;
                margin-right: 0 !important;
                width: 100% !important;
                box-sizing: border-box !important;
            }
            .cmr-smbcg-featured-img-wrap {
                height: 280px;
                width: 100% !important;
            }
            .cmr-smbcg-featured-content {
                padding: 30px 20px !important;
                box-sizing: border-box !important;
                width: 100% !important;
            }
            .cmr-smbcg-nav {
                flex-direction: column;
                gap: 16px;
            }
        }
        
        @media (max-width: 768px) {
            .cmr-smbcg-section {
                padding: 20px 16px 40px 16px !important;
                box-sizing: border-box !important;
                width: 100% !important;
                max-width: 100% !important;
            }
            .cmr-smbcg-featured {
                margin-left: 0 !important;
                margin-right: 0 !important;
                width: 100% !important;
                box-sizing: border-box !important;
            }
            .cmr-smbcg-featured-img-wrap {
                height: 240px;
                min-height: 240px;
                width: 100% !important;
            }
            .cmr-smbcg-featured-content {
                padding: 24px 16px !important;
                box-sizing: border-box !important;
                width: 100% !important;
            }
            .cmr-smbcg-meta-top {
                margin-bottom: 12px !important;
            }
            .cmr-smbcg-title {
                font-size: 24px !important;
                line-height: 1.25 !important;
                margin-bottom: 12px !important;
            }
            .cmr-smbcg-excerpt {
                font-size: 14px !important;
                line-height: 1.5 !important;
                margin-bottom: 24px !important;
            }
            .cmr-smbcg-actions {
                flex-direction: row;
                align-items: center;
            }
            .cmr-smbcg-nav-item {
                flex-direction: column;
                width: 100% !important;
                box-sizing: border-box !important;
            }
            .cmr-smbcg-nav-img-wrap {
                width: 100% !important;
                height: 180px !important;
            }
            .cmr-smbcg-nav-content {
                padding: 16px !important;
                box-sizing: border-box !important;
            }
        }
    </style>

    <div class="cmr-smbcg-section" id="cmr-smbcg-app">
        <!-- Top Featured Area -->
        <div class="cmr-smbcg-featured">
            <div class="cmr-smbcg-featured-img-wrap" style="background-color:#f4f4f4;">
                <svg class="cmr-smbcg-placeholder-svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                <img src="<?php echo esc_url($top_post['image']); ?>" alt="Featured Image" class="cmr-smbcg-featured-img" id="cmr-smbcg-main-img" style="display: <?php echo $top_post['image'] ? 'block' : 'none'; ?>;">
            </div>
            <div class="cmr-smbcg-featured-content">
                <div class="cmr-smbcg-meta-top">
                    <span class="cmr-smbcg-label">SMB Connect</span>
                    <span id="cmr-smbcg-main-time"><?php echo esc_html($top_post['read_time']); ?></span>
                </div>
                <h2 class="cmr-smbcg-title" id="cmr-smbcg-main-title"><?php echo esc_html($top_post['title']); ?></h2>
                <p class="cmr-smbcg-excerpt" id="cmr-smbcg-main-excerpt"><?php echo esc_html($top_post['excerpt']); ?></p>
                
                <div class="cmr-smbcg-actions">
                    <a href="<?php echo esc_url($top_post['link']); ?>" class="cmr-smbcg-btn-primary" id="cmr-smbcg-main-link">
                        Read More
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Bottom Navigation Row -->
        <div class="cmr-smbcg-nav">
            <?php foreach ( $bottom_posts as $index => $post ) : ?>
            <div class="cmr-smbcg-nav-item <?php echo $index === 0 ? 'active' : ''; ?>" data-index="<?php echo $index; ?>">
                <div class="cmr-smbcg-nav-img-wrap">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="color:#ccc; position:absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                    <?php if ($post['image']) : ?>
                        <img src="<?php echo esc_url($post['image']); ?>" alt="Thumbnail" class="cmr-smbcg-nav-img">
                    <?php endif; ?>
                </div>
                <div class="cmr-smbcg-nav-content">
                    <div class="cmr-smbcg-nav-label">SMB Connect</div>
                    <h4 class="cmr-smbcg-nav-title"><?php echo esc_html($post['title']); ?></h4>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const postsData = <?php echo json_encode($bottom_posts); ?>;
        const navItems = document.querySelectorAll('.cmr-smbcg-nav-item');
        
        const mainImg = document.getElementById('cmr-smbcg-main-img');
        const mainTime = document.getElementById('cmr-smbcg-main-time');
        const mainTitle = document.getElementById('cmr-smbcg-main-title');
        const mainExcerpt = document.getElementById('cmr-smbcg-main-excerpt');
        const mainLink = document.getElementById('cmr-smbcg-main-link');

        let currentIndex = 0;
        let rotationInterval;

        function switchTab(idx) {
            navItems.forEach(nav => nav.classList.remove('active'));
            void document.body.offsetWidth; // Force reflow to restart CSS animation
            const activeItem = document.querySelector(`.cmr-smbcg-nav-item[data-index="${idx}"]`);
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
                    } else {
                        mainImg.style.display = 'none';
                    }
                    mainTime.textContent = data.read_time;
                    mainTitle.textContent = data.title;
                    mainExcerpt.textContent = data.excerpt;
                    mainLink.href = data.link;
                    
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

        navItems.forEach((item) => {
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







