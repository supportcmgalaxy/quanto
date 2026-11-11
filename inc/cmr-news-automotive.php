<?php
/**
 * CMR News Automotive Shortcode
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'cmr_news_automotive', 'cmr_news_automotive_shortcode' );
function cmr_news_automotive_shortcode( $atts ) {
    $atts = shortcode_atts( array(
        'category' => 'automotive', 
        'limit'    => 4,
        'count'    => 4,
    ), $atts, 'cmr_news_automotive' );

    $posts_count = ! empty( $atts['count'] ) && $atts['count'] != 4 ? intval( $atts['count'] ) : intval( $atts['limit'] );
    if ( ! $posts_count ) {
        $posts_count = 4;
    }

    wp_enqueue_style( 'cmr-news-style', get_template_directory_uri() . '/assets/css/cmr-news.css', array(), time() );
    wp_enqueue_script( 'cmr-news-script', get_template_directory_uri() . '/assets/js/cmr-news.js', array('jquery'), time(), true );

    $cat_slug = sanitize_title( $atts['category'] );
    $cat_slugs = array( $cat_slug );
    if ( $cat_slug === 'automotive' ) {
        $cat_slugs = array( 'automotive', 'automobile', 'automobiles', 'auto-tech', 'auto-industry', 'auto', 'cmr-in-news' );
    }

    // 1. Pinned query
    $pinned_query = new WP_Query( array(
        'post_type'      => 'cmr_news',
        'post_status'    => 'publish',
        'posts_per_page' => $posts_count,
        'tax_query'      => array(
            'relation' => 'OR',
            array(
                'taxonomy' => 'cmr_news_category',
                'field'    => 'slug',
                'terms'    => $cat_slugs,
            ),
            array(
                'taxonomy' => 'category',
                'field'    => 'slug',
                'terms'    => $cat_slugs,
            ),
        ),
        'meta_query'     => array(
            array(
                'key'     => '_cmr_news_is_featured',
                'value'   => '1',
                'compare' => '='
            )
        ),
        'orderby'        => 'date',
        'order'          => 'DESC',
    ) );

    $all_posts = $pinned_query->posts;
    $remaining = $posts_count - count( $all_posts );

    if ( $remaining > 0 ) {
        $pinned_ids = wp_list_pluck( $all_posts, 'ID' );
        $normal_args = array(
            'post_type'      => 'cmr_news',
            'post_status'    => 'publish',
            'posts_per_page' => $remaining,
            'tax_query'      => array(
                'relation' => 'OR',
                array(
                    'taxonomy' => 'cmr_news_category',
                    'field'    => 'slug',
                    'terms'    => $cat_slugs,
                ),
                array(
                    'taxonomy' => 'category',
                    'field'    => 'slug',
                    'terms'    => $cat_slugs,
                ),
            ),
            'orderby'        => 'date',
            'order'          => 'DESC',
        );
        if ( ! empty( $pinned_ids ) ) {
            $normal_args['post__not_in'] = $pinned_ids;
        }
        $normal_query = new WP_Query( $normal_args );
        $all_posts = array_merge( $all_posts, $normal_query->posts );
    }

    // Fallback if no posts found with specific tax terms
    if ( empty( $all_posts ) ) {
        $fallback_query = new WP_Query( array(
            'post_type'      => 'cmr_news',
            'post_status'    => 'publish',
            'posts_per_page' => $posts_count,
            'orderby'        => 'date',
            'order'          => 'DESC',
        ) );
        $all_posts = $fallback_query->posts;
    }

    ob_start();
    ?>
    <style>
    .cmr-news-container .cmr-card-standard .cmr-card-image-wrap {
        position: relative !important;
        overflow: hidden !important;
    }
    .cmr-news-container .cmr-card-standard .cmr-card-image-wrap .cmr-card-logo,
    .cmr-news-container .cmr-card-image-wrap .cmr-card-logo {
        position: absolute !important;
        bottom: 15px !important;
        left: 15px !important;
        z-index: 3 !important;
        background: #ffffff !important;
        padding: 4px !important;
        box-sizing: content-box !important;
        height: 32px !important;
        max-height: 32px !important;
        width: auto !important;
        max-width: none !important;
        object-fit: contain !important;
        object-position: center !important;
        margin: 0 !important;
        border-radius: 0 !important;
        display: block !important;
    }
    .cmr-news-container .cmr-card-featured .cmr-card-content .cmr-card-logo,
    .cmr-news-container .cmr-card-featured .cmr-card-logo {
        position: relative !important;
        top: auto !important;
        bottom: auto !important;
        left: auto !important;
        margin-bottom: 16px !important;
        background: #ffffff !important;
        padding: 4px !important;
        box-sizing: content-box !important;
        height: 40px !important;
        max-height: 40px !important;
        width: auto !important;
        max-width: none !important;
        object-fit: contain !important;
        object-position: center !important;
        border-radius: 5px !important;
        display: inline-block !important;
    }
    @media (max-width: 768px) {
        .cmr-news-container .cmr-news-grid {
            display: flex !important;
            overflow-x: auto !important;
            gap: 15px !important;
            padding-left: 20px !important;
            padding-right: 20px !important;
            padding-bottom: 15px !important;
            scroll-padding-left: 20px !important;
            scroll-padding-inline: 20px !important;
            scroll-snap-type: x mandatory !important;
            scrollbar-width: none !important;
            -webkit-overflow-scrolling: touch !important;
            box-sizing: border-box !important;
        }
        .cmr-news-container .cmr-card, 
        .cmr-news-container .cmr-card-featured, 
        .cmr-news-container .cmr-card-standard {
            flex: 0 0 85% !important;
            max-width: 85% !important;
            width: 85% !important;
            height: 400px !important;
            scroll-snap-align: start !important;
            scroll-margin-left: 20px !important;
            border-radius: 0 !important;
        }
        .cmr-news-container .cmr-card-standard .cmr-card-image-wrap .cmr-card-logo,
        .cmr-news-container .cmr-card-image-wrap .cmr-card-logo,
        .cmr-news-container .cmr-card-logo-desktop {
            display: none !important;
        }
        .cmr-news-container .cmr-card-logo-mobile,
        .cmr-news-container .cmr-card-featured .cmr-card-content .cmr-card-logo,
        .cmr-news-container .cmr-card-featured .cmr-card-logo,
        .cmr-news-container .cmr-card-content .cmr-card-logo {
            position: relative !important;
            top: auto !important;
            bottom: auto !important;
            left: auto !important;
            right: auto !important;
            background: #ffffff !important;
            padding: 4px !important;
            box-sizing: content-box !important;
            height: 32px !important;
            max-height: 32px !important;
            width: auto !important;
            max-width: none !important;
            object-fit: contain !important;
            object-position: center !important;
            margin: 0 0 12px 0 !important;
            border-radius: 5px !important;
            display: inline-block !important;
            z-index: 2 !important;
        }
    }
    .cmr-news-container .cmr-card-logo-mobile {
        display: none !important;
    }
    </style>
    <div class="cmr-news-container cmr-news-black-bg" style="padding-bottom: 60px;">
        <div class="cmr-news-content-wrapper">
            <div class="cmr-news-tab-pane active" style="display: block;">
                <div class="cmr-news-grid">
                    <?php
                    if ( ! empty( $all_posts ) ) {
                        $count = 0;
                        global $post;
                        foreach ( $all_posts as $post ) {
                            setup_postdata( $post );
                            $post_id = get_the_ID();
                            $bg_image = get_the_post_thumbnail_url( $post_id, 'large' );
                            $logo_id = get_post_meta( $post_id, '_cmr_news_source_logo_id', true );
                            $logo_url = $logo_id ? wp_get_attachment_url( $logo_id ) : '';
                            $reading_time = get_post_meta( $post_id, '_cmr_news_reading_time', true );
                            $publisher = get_post_meta( $post_id, '_cmr_news_publisher_name', true );
                            $document_id = get_post_meta( $post_id, '_cmr_news_document_id', true );
                            $ext_url = get_post_meta( $post_id, '_cmr_news_external_link', true );
                            if ( $document_id ) {
                                $link = wp_get_attachment_url( $document_id );
                                $target = '_blank';
                            } elseif ( $ext_url ) {
                                $link = $ext_url;
                                $target = '_blank';
                            } else {
                                $link = get_permalink( $post_id );
                                $target = '_self';
                            }
                            $date = get_the_date( 'M j, Y' );
                            
                            if ( $count === 0 ) {
                                // Featured Big Card: Logo inside content above meta
                                ?>
                                <div class="cmr-card cmr-card-featured">
                                    <a href="<?php echo esc_url( $link ); ?>" target="<?php echo esc_attr( $target ); ?>" class="cmr-card-link-wrapper">
                                        <div class="cmr-card-image-wrap">
                                            <?php if ( $bg_image ) : ?>
                                                <img src="<?php echo esc_url( $bg_image ); ?>" class="cmr-card-bg" alt="<?php the_title_attribute(); ?>">
                                            <?php endif; ?>
                                        </div>
                                        <div class="cmr-card-content">
                                            <?php if ( $logo_url ) : ?>
                                                <img src="<?php echo esc_url( $logo_url ); ?>" class="cmr-card-logo" alt="Source Logo">
                                            <?php endif; ?>
                                            <div class="cmr-card-meta">
                                                <div class="cmr-meta-left">
                                                    <?php if ( $publisher ) : ?>
                                                        <span class="cmr-publisher"><?php echo esc_html( $publisher ); ?></span> <span class="cmr-separator">|</span> 
                                                    <?php endif; ?>
                                                    <span class="cmr-date">Published <?php echo esc_html( $date ); ?></span>
                                                    <?php if ( $reading_time ) : ?>
                                                        <span class="cmr-separator">|</span>
                                                        <span class="cmr-read-time"><?php echo esc_html( $reading_time ); ?><?php echo is_numeric($reading_time) ? ' mins' : ''; ?></span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <h3 class="cmr-card-title"><?php the_title(); ?></h3>
                                            <span class="cmr-read-coverage">Read Coverage <img src="<?php echo esc_url( home_url( '/wp-content/uploads/2026/04/Symbol.svg' ) ); ?>" class="cmr-arrow-icon" alt="Arrow"></span>
                                        </div>
                                    </a>
                                </div>
                                <?php
                            } else {
                                // Standard Cards: Logo overlaid on top of image
                                ?>
                                <div class="cmr-card cmr-card-standard">
                                    <a href="<?php echo esc_url( $link ); ?>" target="<?php echo esc_attr( $target ); ?>" class="cmr-card-link-wrapper">
                                        <div class="cmr-card-image-wrap">
                                            <?php if ( $bg_image ) : ?>
                                                <img src="<?php echo esc_url( $bg_image ); ?>" class="cmr-card-bg" alt="<?php the_title_attribute(); ?>">
                                            <?php endif; ?>
                                            <?php if ( $logo_url ) : ?>
                                                <img src="<?php echo esc_url( $logo_url ); ?>" class="cmr-card-logo cmr-card-logo-desktop" alt="Source Logo">
                                            <?php endif; ?>
                                        </div>
                                        <div class="cmr-card-content">
                                            <?php if ( $logo_url ) : ?>
                                                <img src="<?php echo esc_url( $logo_url ); ?>" class="cmr-card-logo cmr-card-logo-mobile" alt="Source Logo">
                                            <?php endif; ?>
                                            <div class="cmr-card-meta">
                                                <div class="cmr-meta-left">
                                                    <?php if ( $publisher ) : ?>
                                                        <span class="cmr-publisher"><?php echo esc_html( $publisher ); ?></span> <span class="cmr-separator">|</span> 
                                                    <?php endif; ?>
                                                    <span class="cmr-date">Published <?php echo esc_html( $date ); ?></span>
                                                    <?php if ( $reading_time ) : ?>
                                                        <span class="cmr-separator">|</span>
                                                        <span class="cmr-read-time"><?php echo esc_html( $reading_time ); ?><?php echo is_numeric($reading_time) ? ' mins' : ''; ?></span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <h3 class="cmr-card-title"><?php the_title(); ?></h3>
                                            <span class="cmr-read-coverage">Read Coverage <img src="<?php echo esc_url( home_url( '/wp-content/uploads/2026/04/Symbol.svg' ) ); ?>" class="cmr-arrow-icon" alt="Arrow"></span>
                                        </div>
                                    </a>
                                </div>
                                <?php
                            }
                            $count++;
                        }
                        wp_reset_postdata();
                    } else {
                        echo '<p>No news content available.</p>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
