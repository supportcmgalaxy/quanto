<?php
/**
 * Shortcode & Helper for Featured Video / Insight
 * Matches the Figma Card Design: Video on top, Date + Duration, Bold Title, Watch Link
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! function_exists( 'cmr_get_video_embed_html' ) ) {
    function cmr_get_video_embed_html( $video_url, $poster_url = '' ) {
        if ( empty( $video_url ) ) {
            return '';
        }

        $video_url = trim( $video_url );

        // Raw iframe / embed code
        if ( strpos( $video_url, '<iframe' ) !== false || strpos( $video_url, '<video' ) !== false ) {
            return $video_url;
        }

        // YouTube
        if ( preg_match( '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i', $video_url, $matches ) ) {
            $yt_id = $matches[1];
            return '<iframe src="https://www.youtube.com/embed/' . esc_attr( $yt_id ) . '?rel=0&modestbranding=1" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen loading="lazy"></iframe>';
        }

        // Vimeo
        if ( preg_match( '/(?:vimeo\.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|video\/|)(\d+))/i', $video_url, $matches ) ) {
            $vimeo_id = end( $matches );
            return '<iframe src="https://player.vimeo.com/video/' . esc_attr( $vimeo_id ) . '?title=0&byline=0&portrait=0" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen loading="lazy"></iframe>';
        }

        // Direct video file (mp4, webm, ogg)
        if ( preg_match( '/\.(mp4|webm|ogg)(\?.*)?$/i', $video_url ) ) {
            $poster_attr = ! empty( $poster_url ) ? ' poster="' . esc_url( $poster_url ) . '"' : '';
            return '<video controls preload="metadata"' . $poster_attr . ' playsinline><source src="' . esc_url( $video_url ) . '" type="video/mp4">Your browser does not support the video tag.</video>';
        }

        // WordPress oEmbed fallback
        $oembed = wp_oembed_get( $video_url );
        if ( $oembed ) {
            return $oembed;
        }

        return '<iframe src="' . esc_url( $video_url ) . '" frameborder="0" allowfullscreen loading="lazy"></iframe>';
    }
}

if ( ! function_exists( 'cmr_featured_insight_shortcode' ) ) {
    function cmr_featured_insight_shortcode( $atts ) {
        $atts = shortcode_atts( array(
            'category'     => '',
            'video_url'    => '',
            'poster'       => '',
            'title'        => '',
            'date'         => '',
            'duration'     => '1 min read',
            'btn_text'     => 'Read Insight',
            'link'         => '#',
            'target'       => '_self',
            'max_width'    => '600px',
            'post_type'    => 'cmr_news',
        ), $atts );

        $video_url = $atts['video_url'];
        $poster    = $atts['poster'];
        $title     = $atts['title'];
        $date      = $atts['date'];
        $duration  = ! empty( $atts['duration'] ) && $atts['duration'] !== '1 min' ? $atts['duration'] : '1 min read';
        $btn_text  = ! empty( $atts['btn_text'] ) && $atts['btn_text'] !== 'Watch' && $atts['btn_text'] !== 'Insight' ? $atts['btn_text'] : 'Read Insight';
        $link      = ! empty( $atts['link'] ) ? $atts['link'] : '#';
        $target    = $atts['target'];
        $max_width = $atts['max_width'];

        // Resolve target category:
        // 1. Explicitly passed in shortcode attribute (e.g. [cmr_featured_insight category="industry-intelligence"])
        // 2. Or automatically from the current page name / slug / archive (e.g. INDUSTRY INTELLIGENCE page)
        $target_category = ! empty( $atts['category'] ) ? trim( $atts['category'] ) : '';
        $page_slug       = '';
        $page_title      = '';

        if ( empty( $target_category ) ) {
            // Check queried object (singular page/post or category/term archive)
            $queried_obj = get_queried_object();
            if ( $queried_obj instanceof WP_Post ) {
                $page_slug  = $queried_obj->post_name;
                $page_title = $queried_obj->post_title;
            } elseif ( $queried_obj instanceof WP_Term ) {
                $page_slug  = $queried_obj->slug;
                $page_title = $queried_obj->name;
            }

            // Fallback to Elementor current document if applicable
            if ( empty( $page_slug ) && class_exists( '\Elementor\Plugin' ) && isset( \Elementor\Plugin::$instance->documents ) ) {
                $current_doc = \Elementor\Plugin::$instance->documents->get_current();
                if ( $current_doc && method_exists( $current_doc, 'get_main_id' ) ) {
                    $elem_post = get_post( $current_doc->get_main_id() );
                    if ( $elem_post ) {
                        $page_slug  = $elem_post->post_name;
                        $page_title = $elem_post->post_title;
                    }
                }
            }

            // Fallback to global $post
            if ( empty( $page_slug ) ) {
                global $post;
                if ( ! empty( $post ) && $post instanceof WP_Post ) {
                    $page_slug  = $post->post_name;
                    $page_title = $post->post_title;
                }
            }

            // Fallback to get_the_ID()
            if ( empty( $page_slug ) && function_exists( 'get_the_ID' ) && get_the_ID() ) {
                $curr_p = get_post( get_the_ID() );
                if ( $curr_p ) {
                    $page_slug  = $curr_p->post_name;
                    $page_title = $curr_p->post_title;
                }
            }

            // Fallback to URL path inspection
            if ( empty( $page_slug ) && ! empty( $_SERVER['REQUEST_URI'] ) ) {
                $req_path = trim( wp_parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ), '/' );
                if ( ! empty( $req_path ) ) {
                    $segments = explode( '/', $req_path );
                    $page_slug = sanitize_title( end( $segments ) );
                }
            }

            if ( ! empty( $page_slug ) ) {
                $target_category = $page_slug;
            } elseif ( ! empty( $page_title ) ) {
                $target_category = $page_title;
            }
        }

        // Build list of term slugs and names to match in taxonomy
        $category_terms = array();
        if ( ! empty( $target_category ) ) {
            $cat_items = array_filter( array_map( 'trim', explode( ',', $target_category ) ) );
            foreach ( $cat_items as $item ) {
                $slug_candidate = sanitize_title( $item );
                $name_candidate = $item;
                $spaced_name    = str_replace( '-', ' ', $slug_candidate );

                $category_terms[] = $slug_candidate;
                $category_terms[] = $name_candidate;
                $category_terms[] = $spaced_name;

                // Handle known Quanto theme section / sector alias mappings
                switch ( $slug_candidate ) {
                    case 'industry-intelligence':
                    case 'industry-intel':
                        $category_terms = array_merge( $category_terms, array(
                            'industry-intelligence',
                            'industry-insights',
                            'industry-insight',
                            'Industry Intelligence',
                            'Industry Insights',
                        ) );
                        break;

                    case 'industry-connect':
                    case 'industry':
                        $category_terms = array_merge( $category_terms, array(
                            'industry-connect',
                            'Industry Connect',
                        ) );
                        break;

                    case 'consulting-advisory':
                    case 'consulting':
                    case 'advisory':
                        $category_terms = array_merge( $category_terms, array(
                            'consulting-advisory',
                            'consulting',
                            'advisory',
                            'Consulting & Advisory',
                            'Consulting',
                            'Advisory',
                        ) );
                        break;

                    case 'marketing-services':
                    case 'marketing':
                        $category_terms = array_merge( $category_terms, array(
                            'marketing-services',
                            'marketing',
                            'Marketing Services',
                            'Marketing',
                        ) );
                        break;

                    case 'automotive':
                    case 'mobility':
                        $category_terms = array_merge( $category_terms, array(
                            'automotive',
                            'mobility',
                            'auto',
                            'Automotive',
                        ) );
                        break;

                    case 'consumer-tech':
                    case 'consumer':
                        $category_terms = array_merge( $category_terms, array(
                            'consumer-tech',
                            'consumer-technology',
                            'consumer',
                            'Consumer Tech',
                            'Consumer Technology',
                        ) );
                        break;

                    case 'digital-supply-chain':
                    case 'supply-chain':
                    case 'supply':
                        $category_terms = array_merge( $category_terms, array(
                            'digital-supply-chain',
                            'supply-chain',
                            'supply',
                            'Digital Supply Chain',
                            'Supply Chain',
                        ) );
                        break;

                    case 'msme':
                    case 'msme-2':
                    case 'smb':
                        $category_terms = array_merge( $category_terms, array(
                            'msme',
                            'msme-2',
                            'smb',
                            'smb-connect',
                            'MSME',
                            'SMB Connect',
                        ) );
                        break;

                    case 'it-telecom':
                    case 'telecom':
                        $category_terms = array_merge( $category_terms, array(
                            'it-telecom',
                            'it-and-telecom',
                            'telecom',
                            'IT & Telecom',
                            'IT and Telecom',
                        ) );
                        break;

                    case 'semiconductors':
                    case 'semiconductor':
                        $category_terms = array_merge( $category_terms, array(
                            'semiconductors',
                            'semiconductor',
                            'Semiconductors',
                            'Semiconductor',
                        ) );
                        break;

                    case 'ai':
                        $category_terms = array_merge( $category_terms, array(
                            'ai',
                            'artificial-intelligence',
                            'AI',
                            'Artificial Intelligence',
                        ) );
                        break;

                    case 'enterprise-tech':
                    case 'enterprise':
                        $category_terms = array_merge( $category_terms, array(
                            'enterprise-tech',
                            'enterprise-technology',
                            'enterprise',
                            'Enterprise Tech',
                            'Enterprise Technology',
                        ) );
                        break;

                    case 'market-updates':
                    case 'market-update':
                        $category_terms = array_merge( $category_terms, array(
                            'market-updates',
                            'market-update',
                            'Market Updates',
                            'Market Update',
                        ) );
                        break;

                    case 'viewpoints':
                    case 'viewpoint':
                        $category_terms = array_merge( $category_terms, array(
                            'viewpoints',
                            'viewpoint',
                            'Viewpoints',
                            'View Points',
                        ) );
                        break;

                    case 'research-reports':
                    case 'reports':
                        $category_terms = array_merge( $category_terms, array(
                            'research-reports',
                            'reports',
                            'Research Reports',
                            'Reports',
                        ) );
                        break;
                }
            }

            // Also check if page title matches "intelligence"
            if ( ! empty( $page_title ) && stripos( $page_title, 'intelligence' ) !== false ) {
                $category_terms = array_merge( $category_terms, array(
                    'industry-intelligence',
                    'industry-insights',
                    'industry-insight',
                    'Industry Intelligence',
                    'Industry Insights',
                ) );
            }

            $category_terms = array_values( array_unique( array_filter( $category_terms ) ) );
        }

        // If manual video or title not supplied, query latest post matching page category
        if ( empty( $video_url ) && empty( $title ) ) {
            $post_types = array( 'post', 'cmr_news' );
            if ( ! empty( $atts['post_type'] ) && $atts['post_type'] !== 'cmr_news' ) {
                $post_types = $atts['post_type'];
            }

            $query_args = array(
                'post_type'      => $post_types,
                'posts_per_page' => 1,
                'post_status'    => 'publish',
                'orderby'        => 'date',
                'order'          => 'DESC',
            );

            if ( ! empty( $category_terms ) ) {
                $term_slugs = array_values( array_unique( array_map( 'sanitize_title', $category_terms ) ) );
                $term_names = $category_terms;

                $query_args['tax_query'] = array(
                    'relation' => 'OR',
                    array(
                        'taxonomy' => 'category',
                        'field'    => 'slug',
                        'terms'    => $term_slugs,
                    ),
                    array(
                        'taxonomy' => 'category',
                        'field'    => 'name',
                        'terms'    => $term_names,
                    ),
                    array(
                        'taxonomy' => 'cmr_news_category',
                        'field'    => 'slug',
                        'terms'    => $term_slugs,
                    ),
                    array(
                        'taxonomy' => 'cmr_news_category',
                        'field'    => 'name',
                        'terms'    => $term_names,
                    ),
                );
            }

            $posts = get_posts( $query_args );

            // Fallback: If no post found for the specific category, retry without tax_query
            // so the section gracefully displays the latest post and never stays blank
            if ( empty( $posts ) && ! empty( $category_terms ) ) {
                unset( $query_args['tax_query'] );
                $posts = get_posts( $query_args );
            }

            if ( ! empty( $posts ) ) {
                $p = $posts[0];
                $title = get_the_title( $p );
                $date  = get_the_date( 'd F Y', $p );
                $link  = get_permalink( $p->ID );

                // Try to get video from custom field or content
                $custom_video = get_post_meta( $p->ID, 'video_url', true );
                if ( empty( $custom_video ) ) {
                    $custom_video = get_post_meta( $p->ID, '_video_url', true );
                }
                if ( empty( $custom_video ) ) {
                    $custom_video = get_post_meta( $p->ID, 'cmr_video_url', true );
                }
                if ( empty( $custom_video ) ) {
                    $custom_video = get_post_meta( $p->ID, 'youtube_url', true );
                }

                if ( ! empty( $custom_video ) ) {
                    $video_url = $custom_video;
                } else {
                    $thumb_id = get_post_thumbnail_id( $p->ID );
                    if ( $thumb_id ) {
                        $poster = wp_get_attachment_image_url( $thumb_id, 'large' ) ?: wp_get_attachment_image_url( $thumb_id, 'full' );
                        $poster_img_html = wp_get_attachment_image( $thumb_id, 'large', false, array(
                            'class'         => 'cmr-fvi-poster-img',
                            'fetchpriority' => 'high',
                            'decoding'      => 'async',
                            'sizes'         => '(max-width: 767px) 100vw, 680px',
                        ) );
                    }
                }

                // Approximate reading / video time
                if ( empty( $duration ) || $duration === '22:44 min' || $duration === '1 min' ) {
                    $duration = '1 min read';
                }
            }
        }

        if ( empty( $date ) ) {
            $date = date( 'd F Y' );
        }

        if ( empty( $title ) ) {
            $title = 'From ideas to innovation – Exclusive conversations with the trailblazers of India’s EV journey';
        }

        if ( empty( $duration ) || $duration === '22:44 min' || $duration === '1 min' ) {
            $duration = '1 min read';
        }

        $video_embed = ! empty( $video_url ) ? cmr_get_video_embed_html( $video_url, $poster ) : '';

        ob_start();
        ?>
        <div class="cmr-fvi-card-wrapper" style="width: 100%; max-width: <?php echo esc_attr( $max_width ); ?>; margin: 0 auto;">
            <div class="cmr-fvi-card">
                
                <!-- Video / Media Box -->
                <div class="cmr-fvi-video-wrap">
                    <?php if ( ! empty( $video_embed ) ) : ?>
                        <?php echo $video_embed; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    <?php elseif ( ! empty( $poster_img_html ) ) : ?>
                        <a href="<?php echo esc_url( $link ); ?>" class="cmr-fvi-poster-link" <?php echo ( $target === '_blank' ) ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>>
                            <?php echo $poster_img_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        </a>
                    <?php elseif ( ! empty( $poster ) ) : ?>
                        <a href="<?php echo esc_url( $link ); ?>" class="cmr-fvi-poster-link" <?php echo ( $target === '_blank' ) ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>>
                            <img src="<?php echo esc_url( $poster ); ?>" alt="<?php echo esc_attr( $title ); ?>" class="cmr-fvi-poster-img" fetchpriority="high" decoding="async">
                        </a>
                    <?php else : ?>
                        <!-- Default Embedded Demo Video -->
                        <iframe src="https://www.youtube.com/embed/ScMzIvxBSi4?rel=0&modestbranding=1" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen loading="lazy"></iframe>
                    <?php endif; ?>
                </div>

                <!-- Body Content -->
                <div class="cmr-fvi-body">
                    <!-- Meta info: Date & Duration -->
                    <div class="cmr-fvi-meta">
                        <div class="cmr-fvi-date">
                            <span class="cmr-fvi-dash"></span>
                            <span class="cmr-fvi-date-txt"><?php echo esc_html( $date ); ?></span>
                        </div>
                        <?php if ( ! empty( $duration ) ) : ?>
                            <div class="cmr-fvi-duration"><?php echo esc_html( $duration ); ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Main Title -->
                    <h3 class="cmr-fvi-title">
                        <a href="<?php echo esc_url( $link ); ?>" <?php echo ( $target === '_blank' ) ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>>
                            <?php echo esc_html( $title ); ?>
                        </a>
                    </h3>

                    <!-- Read Insight Button -->
                    <div class="cmr-fvi-action">
                        <a href="<?php echo esc_url( $link ); ?>" class="cmr-fvi-btn" <?php echo ( $target === '_blank' ) ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>>
                            <span class="cmr-fvi-btn-text"><?php echo esc_html( ! empty( $btn_text ) ? $btn_text : 'Read Insight' ); ?></span>
                            <svg class="cmr-fvi-btn-arrow" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="7" y1="17" x2="17" y2="7"></line>
                                <polyline points="7 7 17 7 17 17"></polyline>
                            </svg>
                        </a>
                    </div>
                </div>

            </div>
        </div>

        <style id="cmr-fvi-styles">
            .cmr-fvi-card-wrapper {
                box-sizing: border-box;
            }
            .cmr-fvi-card {
                font-family: 'Instrument Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
                width: 100%;
                background: #FFFFFF;
                border: 1px solid #E5E7EB;
                border-radius: 0px;
                overflow: hidden;
                display: flex;
                flex-direction: column;
                box-sizing: border-box;
                transition: border-color 0.25s ease, box-shadow 0.25s ease;
            }
            .cmr-fvi-card:hover {
                border-color: #CBD5E1;
                box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.03);
            }
            .cmr-fvi-video-wrap {
                position: relative;
                width: 100%;
                padding-top: 56.25%; /* 16:9 Aspect Ratio */
                background: #ffffff;
                overflow: hidden;
            }
            .cmr-fvi-video-wrap iframe,
            .cmr-fvi-video-wrap video,
            .cmr-fvi-video-wrap object,
            .cmr-fvi-video-wrap embed {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                border: none;
                object-fit: cover;
                display: block;
            }
            .cmr-fvi-poster-link {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                display: block;
            }
            .cmr-fvi-poster-img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                display: block;
            }
            .cmr-fvi-play-icon {
                display: none !important;
            }
            .cmr-fvi-body {
                padding: 28px 32px 34px 32px;
                background: #FFFFFF;
                display: flex;
                flex-direction: column;
                flex-grow: 1;
                box-sizing: border-box;
            }
            .cmr-fvi-meta {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 18px;
                font-size: 14.5px;
                color: #475569;
                font-weight: 500;
            }
            .cmr-fvi-date {
                display: inline-flex;
                align-items: center;
                gap: 12px;
            }
            .cmr-fvi-dash {
                display: inline-block;
                width: 24px;
                height: 1.5px;
                background-color: #64748B;
            }
            .cmr-fvi-duration {
                color: #475569;
                font-size: 14px;
                font-weight: 500;
            }
            .cmr-fvi-title {
                font-family: inherit !important;
                font-size: 26px !important;
                font-weight: 600 !important;
                line-height: 1.34 !important;
                color: #0F172A !important;
                letter-spacing: -0.5px !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            .cmr-fvi-title a {
                color: #0F172A !important;
                font-weight: 600 !important;
                text-decoration: none !important;
                transition: color 0.2s ease;
            }
            .cmr-fvi-title a:hover {
                color: #4F46E5 !important;
            }
            .cmr-fvi-action {
                margin-top: 24px !important;
                display: flex !important;
                align-items: center !important;
            }
            .cmr-fvi-btn {
                display: inline-flex !important;
                align-items: center !important;
                justify-content: flex-start !important;
                gap: 10px !important;
                padding: 0 !important;
                background: transparent !important;
                color: #0F172A !important;
                border: none !important;
                border-radius: 0 !important;
                font-size: 18px !important;
                font-weight: 600 !important;
                text-decoration: none !important;
                transition: color 0.25s ease, opacity 0.25s ease !important;
                cursor: pointer !important;
                line-height: 1.2 !important;
                box-shadow: none !important;
            }
            .cmr-fvi-btn .cmr-fvi-btn-text,
            .cmr-fvi-btn span {
                color: inherit !important;
                font-size: inherit !important;
                font-weight: 600 !important;
                border-bottom: 2px solid currentColor !important;
                padding-bottom: 3px !important;
                display: inline-block !important;
                text-decoration: none !important;
                transition: border-color 0.25s ease, color 0.25s ease !important;
            }
            .cmr-fvi-btn svg,
            .cmr-fvi-btn .cmr-fvi-btn-arrow {
                width: 18px !important;
                height: 18px !important;
                color: inherit !important;
                stroke: currentColor !important;
                stroke-width: 2.5 !important;
                transition: transform 0.25s ease !important;
                display: inline-block !important;
                flex-shrink: 0 !important;
            }
            .cmr-fvi-btn:hover {
                background: transparent !important;
                border: none !important;
                color: #000000 !important;
                box-shadow: none !important;
                transform: none !important;
                opacity: 0.85 !important;
            }
            .cmr-fvi-btn:hover svg,
            .cmr-fvi-btn:hover .cmr-fvi-btn-arrow {
                transform: translate(3px, -3px) !important;
            }
            @media (max-width: 767.98px) {
                .cmr-fvi-body {
                    padding: 22px 20px 24px 20px;
                }
                .cmr-fvi-title {
                    font-size: 21px !important;
                    font-weight: 600 !important;
                    line-height: 1.35 !important;
                    margin: 0 !important;
                }
                .cmr-fvi-meta {
                    font-size: 13.5px;
                    margin-bottom: 14px;
                }
                .cmr-fvi-action {
                    margin-top: 18px !important;
                }
                .cmr-fvi-btn {
                    font-size: 16px !important;
                    padding: 0 !important;
                    gap: 8px !important;
                }
                .cmr-fvi-btn svg,
                .cmr-fvi-btn .cmr-fvi-btn-arrow {
                    width: 16px !important;
                    height: 16px !important;
                }
            }
        </style>
        <?php
        return ob_get_clean();
    }
}
add_shortcode( 'cmr_featured_insight', 'cmr_featured_insight_shortcode' );
add_shortcode( 'cmr_featured_video_insight', 'cmr_featured_insight_shortcode' );
