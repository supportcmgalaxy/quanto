<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! function_exists( 'cmr_live_section_shortcode' ) ) {
    function cmr_live_section_shortcode( $atts ) {
        $atts = shortcode_atts( array(
            'posts_per_page' => 3,
        ), $atts, 'cmr_live_section' );

        $query_args = array(
            'post_type'      => 'cmr_media',
            'posts_per_page' => intval( $atts['posts_per_page'] ),
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC',
        );

        $live_query = new WP_Query( $query_args );
        $posts = $live_query->posts;

        ob_start();
        ?>
        <style>
            .cmr-ls-section {
                padding: 60px 0;
                font-family: inherit;
                background: transparent;
            }
            .cmr-ls-container {
                max-width: 1280px;
                margin: 0 auto;
                padding: 0 20px;
            }
            .cmr-ls-header {
                display: flex;
                justify-content: space-between;
                align-items: flex-end;
                margin-bottom: 40px;
            }
            .cmr-ls-title-area h2 {
                font-size: 32px;
                font-weight: 700;
                color: #111;
                margin: 0 0 10px 0;
                letter-spacing: -0.5px;
            }
            .cmr-ls-title-area p {
                font-size: 16px;
                color: #555;
                margin: 0;
                max-width: 800px;
            }
            .cmr-ls-explore {
                font-size: 14px;
                font-weight: 600;
                color: #111;
                text-decoration: none;
                display: flex;
                align-items: center;
                gap: 5px;
                border-bottom: 1px solid #111;
                padding-bottom: 2px;
                white-space: nowrap;
            }
            .cmr-ls-explore:hover {
                color: #000;
            }
            .cmr-ls-explore svg {
                width: 14px;
                height: 14px;
            }
            .cmr-ls-grid {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 30px;
            }
            @media(max-width: 991px) {
                .cmr-ls-grid {
                    grid-template-columns: repeat(2, 1fr);
                }
            }
            @media(max-width: 767px) {
                .cmr-ls-grid {
                    grid-template-columns: 1fr;
                }
                .cmr-ls-header {
                    flex-direction: column;
                    align-items: flex-start;
                    gap: 20px;
                }
            }
            .cmr-ls-card {
                display: flex;
                flex-direction: column;
                text-decoration: none;
                color: inherit;
            }
            .cmr-ls-card:hover {
                color: inherit;
            }
            .cmr-ls-img-wrap {
                position: relative;
                width: 100%;
                aspect-ratio: 16 / 9;
                background: #eee;
                margin-bottom: 20px;
                overflow: hidden;
            }
            .cmr-ls-img-wrap img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                display: block;
                transition: transform 0.3s ease;
            }
            .cmr-ls-card:hover .cmr-ls-img-wrap img {
                transform: scale(1.05);
            }
            .cmr-ls-badge {
                position: absolute;
                top: 15px;
                left: 15px;
                background: #fff;
                color: #111;
                font-size: 11px;
                font-weight: 700;
                padding: 4px 8px;
                border-radius: 4px;
                z-index: 2;
            }
            .cmr-ls-play-btn {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                width: 44px;
                height: 44px;
                background: transparent;
                border: 2px solid #fff;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 2;
                transition: all 0.3s ease;
            }
            .cmr-ls-card:hover .cmr-ls-play-btn {
                background: rgba(255,255,255,0.2);
                transform: translate(-50%, -50%) scale(1.08);
            }
            .cmr-ls-play-btn svg {
                width: 16px;
                height: 16px;
                fill: #fff;
                margin-left: 2px;
            }
            .cmr-ls-meta {
                font-size: 11px;
                font-weight: 700;
                color: #555;
                margin-bottom: 12px;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                display: flex;
                align-items: center;
                gap: 5px;
            }
            .cmr-ls-meta .type-topview {
                color: #4820B0;
            }
            .cmr-ls-meta .type-podcast {
                color: #4820B0;
            }
            .cmr-ls-title {
                font-size: 18px;
                font-weight: 600;
                color: #111;
                margin: 0;
                line-height: 1.4;
                letter-spacing: normal !important;
                word-spacing: normal !important;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }
        </style>

        <div class="cmr-ls-section" id="cmr-live-section">
            <div class="cmr-ls-container">
                <div class="cmr-ls-header">
                    <div class="cmr-ls-title-area">
                        <h2>CMR Live</h2>
                        <p>Stream deep-dive research and real-world executive perspectives tailored for leaders navigating global market trends.</p>
                    </div>
                    <a href="<?php echo esc_url( home_url( '/cmr-live/' ) ); ?>" class="cmr-ls-explore">
                        Explore More 
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg>
                    </a>
                </div>
                
                <?php if ( $posts ) : ?>
                    <div class="cmr-ls-grid">
                        <?php foreach ( $posts as $post ) : 
                            $type_terms = wp_get_post_terms( $post->ID, 'media_type' );
                            $topic_terms = wp_get_post_terms( $post->ID, 'media_topic' );
                            
                            $type_name = ! empty( $type_terms ) && ! is_wp_error( $type_terms ) ? $type_terms[0]->name : 'Top View';
                            $type_slug = ! empty( $type_terms ) && ! is_wp_error( $type_terms ) ? $type_terms[0]->slug : 'top-view';
                            
                            $topic_name = ! empty( $topic_terms ) && ! is_wp_error( $topic_terms ) ? $topic_terms[0]->name : 'General';
                            
                            $date_str = get_the_time( 'j M', $post->ID );
                            $date_str = strtoupper($date_str); // e.g. 10 MAY
                            
                            $duration = get_post_meta( $post->ID, '_cmr_media_duration', true );
                            if ( empty( $duration ) ) {
                                $duration = get_post_meta( $post->ID, 'cmr_media_duration', true );
                            }
                            if ( empty( $duration ) ) {
                                $duration = get_post_meta( $post->ID, 'duration', true );
                            }
                            if ( empty( $duration ) ) {
                                $duration = '5:00 MINS';
                            }
                            
                            $link = get_permalink( $post->ID );
                            $img_url = get_the_post_thumbnail_url( $post->ID, 'large' );
                            if ( ! $img_url ) {
                                $img_url = quanto_get_placeholder_image_uri();
                            }
                            
                            $type_class = 'type-topview';
                            if ( stripos( $type_name, 'podcast' ) !== false ) {
                                $type_class = 'type-podcast';
                            }
                        ?>
                            <a href="<?php echo esc_url( $link ); ?>" class="cmr-ls-card">
                                <div class="cmr-ls-img-wrap">
                                    <div class="cmr-ls-badge"><?php echo esc_html( $duration ); ?></div>
                                    <img src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( $post->post_title ); ?>">
                                    <div class="cmr-ls-play-btn">
                                        <svg viewBox="0 0 24 24"><path d="M8 5v14l11-7z"></path></svg>
                                    </div>
                                </div>
                                <div class="cmr-ls-meta">
                                    <span class="<?php echo esc_attr( $type_class ); ?>"><?php echo esc_html( strtoupper($type_name) ); ?></span>
                                    <span>&middot;</span>
                                    <span><?php echo esc_html( strtoupper($topic_name) ); ?></span>
                                    <span>&middot;</span>
                                    <span><?php echo esc_html( $date_str ); ?></span>
                                </div>
                                <h3 class="cmr-ls-title"><?php echo esc_html( $post->post_title ); ?></h3>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <?php wp_reset_postdata(); ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}
add_shortcode( 'cmr_live', 'cmr_live_section_shortcode' );
