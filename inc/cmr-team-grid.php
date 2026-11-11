<?php
/**
 * CMR Team Grid Shortcode
 * Displays team members from a custom post type.
 * Shortcode: [cmr_team_grid]
 */

add_shortcode('cmr_team_grid', 'cmr_team_grid_shortcode');

function cmr_team_grid_shortcode($atts) {
    // Attributes
    $atts = shortcode_atts(array(
        'posts_per_page' => -1,
        'post_type'      => 'quanto_team', 
    ), $atts);

    // Setup the query
    $args = array(
        'post_type'      => $atts['post_type'],
        'posts_per_page' => $atts['posts_per_page'],
        'post_status'    => 'publish',
        'orderby'        => 'menu_order date',
        'order'          => 'ASC'
    );

    $team_query = new WP_Query($args);

    if (empty($team_query->posts)) {
        return '<p>No team members found.</p>';
    }

    ob_start();
    ?>
    <style>
        .cmr-team-grid-container {
            margin: 0 auto;
        }
        .cmr-team-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 40px;
        }
        @media (max-width: 768px) {
            .cmr-team-grid {
                grid-template-columns: 1fr;
            }
        }
        .cmr-team-member {
            display: flex;
            flex-direction: column;
        }
        .cmr-team-image-wrap {
            position: relative;
            overflow: hidden;
            margin-bottom: 20px;
            background-color: #f0f0f0;
            aspect-ratio: 3 / 4; /* Typical portrait aspect ratio */
        }
        .cmr-team-image-wrap img {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
            display: block;
            transition: transform 0.4s ease;
        }
        .cmr-team-overlay {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0, 0, 0, 0.2);
            opacity: 0;
            transition: opacity 0.4s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        .cmr-team-image-wrap:hover .cmr-team-overlay {
            opacity: 1;
        }
        .cmr-team-image-wrap:hover img,
        .cmr-team-member:hover .cmr-team-image-wrap img {
            transform: scale(1.06);
        }
        .cmr-team-overlay svg {
            width: 48px;
            height: 48px;
            color: white;
        }
        .cmr-team-name {
            font-size: 24px;
            font-weight: 600;
            margin: 0 0 5px 0;
            color: #111;
            letter-spacing: -0.5px;
            transition: color 0.3s ease;
        }
        .cmr-team-member:hover .cmr-team-name,
        .cmr-team-name:hover {
            color: #4820B0 !important;
        }
        .cmr-team-role {
            font-size: 14px;
            color: #666;
            margin: 0 0 15px 0;
            font-weight: 500;
        }
        .cmr-team-social {
            display: flex;
            gap: 10px;
        }
        .cmr-social-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            background-color: #111;
            color: #fff;
            border-radius: 4px;
            text-decoration: none;
            transition: background-color 0.2s ease;
        }
        .cmr-social-icon:hover {
            background-color: #444;
        }
        .cmr-social-icon svg {
            width: 14px;
            height: 14px;
            fill: #ffffff !important;
        }
        .cmr-social-icon svg path {
            fill: #ffffff !important;
        }
    </style>

    <div class="cmr-team-grid-container">
        <div class="cmr-team-grid">
            <?php foreach ($team_query->posts as $team_post) : 
                $post_id = $team_post->ID;
                
                $all_meta = get_post_meta($post_id);
                $role = '';
                $linkedin = '';
                $twitter = '';

                foreach ($all_meta as $key => $values) {
                    // Ignore internal WordPress meta keys starting with '_' except specific ones
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

                // Fallback to excerpt for role if still empty
                if (empty($role)) {
                    $role = get_the_excerpt($post_id);
                }

                $image_url = get_the_post_thumbnail_url($post_id, 'large');
                if (!$image_url) {
                    $image_url = quanto_get_placeholder_image_uri(); // Fallback
                }
            ?>
                <div class="cmr-team-member">
                    <a href="<?php echo get_permalink($post_id); ?>" class="cmr-team-image-wrap" style="display:block;">
                        <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr(get_the_title($post_id)); ?>">
                        <div class="cmr-team-overlay">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="7" y1="17" x2="17" y2="7"></line>
                                <polyline points="7 7 17 7 17 17"></polyline>
                            </svg>
                        </div>
                    </a>
                    <div class="cmr-team-info">
                        <h3 class="cmr-team-name"><?php echo get_the_title($post_id); ?></h3>
                        <p class="cmr-team-role"><?php echo esc_html($role); ?></p>
                        <div class="cmr-team-social">
                            <?php if ($linkedin) : ?>
                                <a href="<?php echo esc_url($linkedin); ?>" target="_blank" class="cmr-social-icon cmr-linkedin" aria-label="LinkedIn">
                                    <svg viewBox="0 0 24 24">
                                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452z"/>
                                    </svg>
                                </a>
                            <?php endif; ?>
                            <?php if ($twitter) : ?>
                                <a href="<?php echo esc_url($twitter); ?>" target="_blank" class="cmr-social-icon cmr-twitter" aria-label="X (Twitter)">
                                    <svg viewBox="0 0 24 24">
                                        <path d="M18.901 1.153h3.68l-8.04 9.19L24 22.846h-7.406l-5.8-7.584-6.638 7.584H.474l8.6-9.83L0 1.154h7.594l5.243 6.932ZM17.61 20.644h2.039L6.486 3.24H4.298Z"/>
                                    </svg>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <?php
    return ob_get_clean();
}
