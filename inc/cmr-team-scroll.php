<?php
/**
 * CMR Team Scroll Shortcode
 * Displays specific team members in a 2-column layout on Desktop and horizontal scroll on Mobile.
 * Shortcode: [cmr_team_scroll]
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

add_shortcode('cmr_team_scroll', 'cmr_team_scroll_shortcode');

function cmr_team_scroll_shortcode($atts) {
    $args = array(
        'post_type'      => 'quanto_team',
        'posts_per_page' => -1,
        'post_status'    => 'publish'
    );

    $team_query = new WP_Query($args);

    if (empty($team_query->posts)) {
        return '';
    }

    $allowed_names = array('Thomas George', 'Anil Chopra', 'Prabhu Ram');
    $team_members = array();
    
    foreach($team_query->posts as $p) {
        if (in_array($p->post_title, $allowed_names)) {
            $team_members[] = $p;
        }
    }

    // Sort to match the requested order
    usort($team_members, function($a, $b) use ($allowed_names) {
        return array_search($a->post_title, $allowed_names) - array_search($b->post_title, $allowed_names);
    });

    if (empty($team_members)) {
        return '';
    }

    ob_start();
    ?>
    <style>
        .cmr-team-scroll-section {
            width: 100%;
            max-width: 1280px;
            margin: 0 auto;
            padding: 40px 20px 20px 20px;
            font-family: "Instrument Sans", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: #fff;
            box-sizing: border-box;
        }

        /* Desktop 2-Column Layout (Matching Image 1) */
        @media (min-width: 1025px) {
            .cmr-team-scroll-section {
                display: flex !important;
                flex-direction: row !important;
                justify-content: space-between !important;
                align-items: flex-start !important;
                gap: 50px !important;
                padding: 60px 24px 30px 24px !important;
            }
            .cmr-team-left-col {
                flex: 0 0 280px !important;
                width: 280px !important;
                display: flex !important;
                flex-direction: column !important;
                align-items: flex-start !important;
                text-align: left !important;
            }
            .cmr-team-scroll-title {
                font-family: "Instrument Sans", sans-serif !important;
                font-size: 42px !important;
                font-weight: 600 !important;
                color: #0F0F0F !important;
                line-height: 1.15 !important;
                letter-spacing: -1.2px !important;
                margin: 0 0 35px 0 !important;
                text-align: left !important;
            }
            .cmr-team-desktop-btn {
                display: block !important;
                text-align: left !important;
                margin: 0 !important;
            }
            .cmr-team-mobile-btn {
                display: none !important;
            }
            .cmr-team-right-col {
                flex: 1 1 auto !important;
                width: calc(100% - 330px) !important;
                max-width: calc(100% - 330px) !important;
            }
            .cmr-team-scroll-wrapper {
                display: flex !important;
                flex-direction: row !important;
                gap: 24px !important;
                width: 100% !important;
                justify-content: flex-start !important;
                overflow: visible !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .cmr-team-card {
                flex: 1 1 0% !important;
                min-width: 0 !important;
                width: calc(33.333% - 16px) !important;
                max-width: calc(33.333% - 16px) !important;
                display: flex !important;
                flex-direction: column !important;
                align-items: flex-start !important;
                text-align: left !important;
                cursor: pointer !important;
                height: 100% !important;
            }
            .cmr-team-card-image {
                width: 100% !important;
                aspect-ratio: 3/3.8 !important;
                background: #f5f5f5 !important;
                margin-bottom: 16px !important;
                overflow: hidden !important;
                position: relative !important;
            }
            .cmr-team-card-image img {
                width: 100% !important;
                height: 100% !important;
                object-fit: cover !important;
                display: block !important;
                transform: scale(1) !important;
                transition: transform 0.4s cubic-bezier(0.25, 1, 0.5, 1) !important;
                will-change: transform !important;
            }
            .cmr-team-card:hover .cmr-team-card-image img,
            .cmr-team-card-image:hover img {
                transform: scale(1.08) !important;
            }
            .cmr-team-card-name {
                font-family: "Instrument Sans", sans-serif !important;
                font-size: 18px !important;
                font-weight: 600 !important;
                color: #0F0F0F !important;
                margin: 0 0 6px 0 !important;
                letter-spacing: -0.3px !important;
                line-height: 1.25 !important;
                text-align: left !important;
                transition: color 0.3s ease !important;
            }
            .cmr-team-card:hover .cmr-team-card-name,
            .cmr-team-card-name:hover,
            .cmr-team-card:hover h3,
            .cmr-team-card h3:hover {
                color: #6A42E5 !important;
            }
            .cmr-team-card-role {
                font-family: "Instrument Sans", sans-serif !important;
                font-size: 13px !important;
                color: #475569 !important;
                margin: 0 0 16px 0 !important;
                line-height: 1.35 !important;
                text-align: left !important;
            }
            .cmr-team-card-social {
                display: flex !important;
                gap: 8px !important;
                margin-top: auto !important;
            }
            .cmr-social-icon-btn {
                display: inline-flex !important;
                align-items: center !important;
                justify-content: center !important;
                width: 28px !important;
                height: 28px !important;
                background-color: #0F0F0F !important;
                color: #fff !important;
                border-radius: 4px !important;
                text-decoration: none !important;
                transition: background-color 0.2s ease !important;
            }
            .cmr-social-icon-btn:hover {
                background-color: #333 !important;
            }
            .cmr-social-icon-btn svg {
                width: 14px !important;
                height: 14px !important;
                fill: #ffffff !important;
            }
            .cmr-social-icon-btn svg path {
                fill: #ffffff !important;
            }
        }

        /* View Team Link button styling */
        .cmr-team-view-all a {
            display: inline-flex !important;
            align-items: center !important;
            gap: 8px !important;
            font-family: "Instrument Sans", sans-serif !important;
            font-size: 15px !important;
            font-weight: 600 !important;
            color: #0F0F0F !important;
            text-decoration: none !important;
            border-bottom: 1.5px solid #0F0F0F !important;
            padding-bottom: 4px !important;
            transition: opacity 0.2s ease !important;
        }
        .cmr-team-view-all a:hover {
            opacity: 0.7 !important;
        }

        /* Mobile & Tablet Centered View */
        @media (max-width: 1024px) {
            .cmr-team-scroll-section {
                display: flex !important;
                flex-direction: column !important;
                align-items: center !important;
                text-align: center !important;
                padding: 40px 20px 0 20px !important;
            }
            .cmr-team-left-col {
                width: 100% !important;
                text-align: center !important;
                display: flex !important;
                flex-direction: column !important;
                align-items: center !important;
            }
            .cmr-team-scroll-title {
                font-family: "Instrument Sans", sans-serif !important;
                font-size: 26px !important;
                font-weight: 600 !important;
                color: #0F0F0F !important;
                margin-bottom: 30px !important;
                letter-spacing: -0.8px !important;
                line-height: 1.2 !important;
                text-align: center !important;
            }
            .cmr-team-desktop-btn {
                display: none !important;
            }
            .cmr-team-right-col {
                width: 100% !important;
                max-width: 100% !important;
            }
            .cmr-team-scroll-wrapper {
                display: flex !important;
                gap: 16px !important;
                overflow-x: auto !important;
                scroll-snap-type: x mandatory !important;
                scrollbar-width: none !important;
                -webkit-overflow-scrolling: touch !important;
                padding-bottom: 20px !important;
                width: 100% !important;
            }
            .cmr-team-scroll-wrapper::-webkit-scrollbar {
                display: none !important;
            }
            .cmr-team-card {
                flex: 0 0 80% !important;
                min-width: 250px !important;
                max-width: 280px !important;
                scroll-snap-align: start !important;
                display: flex !important;
                flex-direction: column !important;
                align-items: flex-start !important;
                text-align: left !important;
                cursor: pointer !important;
                height: auto !important;
            }
            .cmr-team-card-image {
                width: 100% !important;
                aspect-ratio: 3/4 !important;
                background: #f5f5f5 !important;
                margin-bottom: 18px !important;
                overflow: hidden !important;
                position: relative !important;
            }
            .cmr-team-card-image img {
                width: 100% !important;
                height: 100% !important;
                object-fit: cover !important;
                display: block !important;
                transform: scale(1) !important;
                transition: transform 0.4s cubic-bezier(0.25, 1, 0.5, 1) !important;
                will-change: transform !important;
            }
            .cmr-team-card:hover .cmr-team-card-image img,
            .cmr-team-card-image:hover img {
                transform: scale(1.08) !important;
            }
            .cmr-team-card-name {
                font-size: 20px !important;
                font-weight: 600 !important;
                color: #111 !important;
                margin: 0 0 6px 0 !important;
                letter-spacing: -0.4px !important;
                transition: color 0.3s ease !important;
            }
            .cmr-team-card:hover .cmr-team-card-name,
            .cmr-team-card-name:hover,
            .cmr-team-card:hover h3,
            .cmr-team-card h3:hover {
                color: #6A42E5 !important;
            }
            .cmr-team-card-role {
                font-size: 14px !important;
                color: #555 !important;
                margin: 0 0 16px 0 !important;
                line-height: 1.4 !important;
            }
            .cmr-team-card-social {
                display: flex !important;
                gap: 10px !important;
                margin-top: auto !important;
            }
            .cmr-social-icon-btn {
                display: inline-flex !important;
                align-items: center !important;
                justify-content: center !important;
                width: 32px !important;
                height: 32px !important;
                background-color: #000 !important;
                color: #fff !important;
                border-radius: 6px !important;
                text-decoration: none !important;
            }
            .cmr-social-icon-btn svg {
                width: 16px !important;
                height: 16px !important;
                fill: #ffffff !important;
            }
            .cmr-social-icon-btn svg path {
                fill: #ffffff !important;
            }
            .cmr-team-mobile-btn {
                display: block !important;
                text-align: center !important;
                margin-top: 20px !important;
            }
        }
    </style>

    <div class="cmr-team-scroll-section">
        <div class="cmr-team-left-col">
            <h2 class="cmr-team-scroll-title">Meet the people behind our success</h2>
            <div class="cmr-team-view-all cmr-team-desktop-btn">
                <a href="<?php echo esc_url( home_url( '/leadership/' ) ); ?>">View Team 
                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1 11L11 1M11 1H3M11 1V9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
            </div>
        </div>
        
        <div class="cmr-team-right-col">
            <div class="cmr-team-scroll-wrapper">
                <?php foreach ($team_members as $team_post) : 
                    $post_id = $team_post->ID;
                    
                    $all_meta = get_post_meta($post_id);
                    $role = '';
                    $linkedin = '';
                    $twitter = '';

                    foreach ($all_meta as $key => $values) {
                        if (strpos($key, '_') === 0 && !preg_match('/team_role|designation|linkedin|twitter/i', $key)) {
                            continue;
                        }
                        
                        $val = $values[0];
                        if (empty($val)) continue;

                        if (empty($role) && preg_match('/designation|role|job_title|position/i', $key)) {
                            $role = $val;
                        }
                        if (empty($linkedin) && preg_match('/linkedin/i', $key)) {
                            $linkedin = $val;
                        }
                        if (empty($twitter) && preg_match('/twitter|x_url/i', $key)) {
                            $twitter = $val;
                        }
                    }

                    if (empty($role)) {
                        $role = get_the_excerpt($post_id);
                    }

                    $image_url = get_the_post_thumbnail_url($post_id, 'large');
                    if (!$image_url) {
                        $image_url = quanto_get_placeholder_image_uri(); 
                    }
                ?>
                    <div class="cmr-team-card">
                        <div class="cmr-team-card-image">
                            <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($team_post->post_title); ?>">
                        </div>
                        <h3 class="cmr-team-card-name"><?php echo esc_html($team_post->post_title); ?></h3>
                        <p class="cmr-team-card-role"><?php echo esc_html($role); ?></p>
                        
                        <div class="cmr-team-card-social">
                            <?php if ($linkedin) : ?>
                                <a href="<?php echo esc_url($linkedin); ?>" target="_blank" class="cmr-social-icon-btn" aria-label="LinkedIn">
                                    <svg viewBox="0 0 24 24">
                                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452z"/>
                                    </svg>
                                </a>
                            <?php endif; ?>
                            <?php if ($twitter) : ?>
                                <a href="<?php echo esc_url($twitter); ?>" target="_blank" class="cmr-social-icon-btn" aria-label="X (Twitter)">
                                    <svg viewBox="0 0 24 24">
                                        <path d="M18.901 1.153h3.68l-8.04 9.19L24 22.846h-7.406l-5.8-7.584-6.638 7.584H.474l8.6-9.83L0 1.154h7.594l5.243 6.932ZM17.61 20.644h2.039L6.486 3.24H4.298Z"/>
                                    </svg>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="cmr-team-view-all cmr-team-mobile-btn">
                <a href="<?php echo esc_url( home_url( '/leadership/' ) ); ?>">View Team 
                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1 11L11 1M11 1H3M11 1V9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
