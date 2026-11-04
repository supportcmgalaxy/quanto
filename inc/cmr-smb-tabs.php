<?php
/**
 * CMR SMB Connect Tabs Feature
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'cmr_smb_tabs', 'cmr_smb_tabs_shortcode' );
function cmr_smb_tabs_shortcode( $atts ) {
    $atts = shortcode_atts( array(
        'category' => '', // Comma separated slugs to include (e.g. 'cmr-in-news')
        'exclude'  => '', // Don't exclude anything by default
    ), $atts, 'cmr_smb_tabs' );

    // Enqueue frontend assets using get_template_directory_uri()
    wp_enqueue_style( 'cmr-news-style', get_template_directory_uri() . '/assets/css/cmr-news.css', array(), time() );
    wp_enqueue_script( 'cmr-news-script', get_template_directory_uri() . '/assets/js/cmr-news.js', array('jquery'), time(), true );

    $all_terms = get_terms( array(
        'taxonomy'   => 'cmr_news_category',
        'hide_empty' => true,
    ) );
    
    $terms = array();
    if ( ! empty( $all_terms ) && ! is_wp_error( $all_terms ) ) {
        $include_slugs = !empty($atts['category']) ? array_map('trim', explode(',', $atts['category'])) : array();
        $exclude_slugs = !empty($atts['exclude']) ? array_map('trim', explode(',', $atts['exclude'])) : array();
        
        // If no explicit category attribute is passed, default to the 2 main news tabs
        if ( empty( $include_slugs ) ) {
            $include_slugs = array( 'cmr-in-news', 'media-releases', 'media-release', 'press-releases', 'press-release' );
        } else {
            // Force the media-releases tab to always be included if include_slugs is used
            if ( !in_array('media-releases', $include_slugs) && !in_array('media-release', $include_slugs) && !in_array('press-releases', $include_slugs) ) {
                $include_slugs[] = 'media-releases';
                $include_slugs[] = 'media-release';
            }
        }
        
        foreach ( $all_terms as $term ) {
            $term_slug = $term->slug;
            $term_name_slug = sanitize_title( $term->name );
            
            $is_included = false;
            foreach ( $include_slugs as $inc ) {
                $inc_clean = sanitize_title( $inc );
                if ( $term_slug === $inc_clean || $term_name_slug === $inc_clean || $term->name === $inc ) {
                    $is_included = true;
                    break;
                }
            }
            if ( ! $is_included ) continue;
            
            if ( ! empty( $exclude_slugs ) ) {
                $is_excluded = false;
                foreach ( $exclude_slugs as $ex ) {
                    $ex_clean = sanitize_title( $ex );
                    if ( $term_slug === $ex_clean || $term_name_slug === $ex_clean || $term->name === $ex ) {
                        $is_excluded = true;
                        break;
                    }
                }
                if ( $is_excluded ) continue;
            }
            
            $terms[] = $term;
        }

        // Ensure "CMR In News" is first and "Media Releases" is second
        usort( $terms, function( $a, $b ) {
            $is_cin_a = ( $a->slug === 'cmr-in-news' || sanitize_title($a->name) === 'cmr-in-news' );
            $is_cin_b = ( $b->slug === 'cmr-in-news' || sanitize_title($b->name) === 'cmr-in-news' );
            if ( $is_cin_a && ! $is_cin_b ) return -1;
            if ( ! $is_cin_a && $is_cin_b ) return 1;
            return 0;
        } );
    }

    if ( empty( $terms ) || is_wp_error( $terms ) ) {
        return '<p>No news content available.</p>';
    }

    ob_start();
    // Force black background for this shortcode
    $is_who_we_serve = true;
    $bg_class = ' cmr-news-black-bg cmr-smb-black-bg';
    ?>
    <!-- DEBUG ATTS: <?php print_r($atts); ?> -->
    <div class="cmr-news-container<?php echo esc_attr( $bg_class ); ?>">
        <!-- Tabs -->
        <div class="cmr-news-tabs">
            <?php 
            $first = true;
            foreach ( $terms as $term ) : 
                $active_class = $first ? 'active' : '';
            ?>
                <button class="cmr-news-tab-btn <?php echo esc_attr( $active_class ); ?>" data-target="cmr-tab-<?php echo esc_attr( $term->term_id ); ?>">
                    <?php 
                    $icon_url = '';
                    if ( $term->name == 'CMR In News' ) {
                        $icon_url = get_theme_file_uri( '/assets/images/icons/airdrop.svg' );
                    } elseif ( $term->name == 'Media Releases' ) {
                        $icon_url = get_theme_file_uri( '/assets/images/icons/Frame.svg' );
                    } elseif ( $term->name == 'Quarterly Results' ) {
                        $icon_url = get_theme_file_uri( '/assets/images/icons/quterly.svg' );
                    }
                    if ( $icon_url ) {
                        echo '<span class="cmr-tab-icon" style="-webkit-mask-image: url(' . esc_url($icon_url) . '); mask-image: url(' . esc_url($icon_url) . ');"></span> ';
                    }
                    echo '<span>' . esc_html( $term->name ) . '</span>'; 
                    ?>
                </button>
            <?php 
                $first = false;
            endforeach; 
            ?>
        </div>

        <!-- Tab Contents -->
        <div class="cmr-news-content-wrapper">
            <?php 
            $first = true;
            foreach ( $terms as $term ) : 
                $active_class = $first ? 'active' : '';
            ?>
                <div class="cmr-news-tab-pane <?php echo esc_attr( $active_class ); ?>" id="cmr-tab-<?php echo esc_attr( $term->term_id ); ?>">
                    <?php
                    $is_media_releases = ( $term->slug === 'media-releases' || $term->slug === 'media-release' );
                    $grid_class = $is_media_releases ? 'cmr-media-grid' : 'cmr-news-grid';
                    ?>
                    <div class="<?php echo esc_attr( $grid_class ); ?>">
                        <?php
                        $target_count = 4;
                        
                        $pinned_query = new WP_Query( array(
                            'post_type' => 'cmr_news',
                            'tax_query' => array(
                                array(
                                    'taxonomy' => 'cmr_news_category',
                                    'field'    => 'term_id',
                                    'terms'    => $term->term_id,
                                )
                            ),
                            'meta_query' => array(
                                array(
                                    'key'     => '_cmr_news_is_featured',
                                    'value'   => '1',
                                    'compare' => '='
                                )
                            ),
                            'orderby' => 'date',
                            'order'   => 'DESC',
                            'posts_per_page' => $target_count,
                        ) );
                        
                        $all_posts = $pinned_query->posts;
                        $remaining = $target_count - count($all_posts);
                        
                        if ( $remaining > 0 ) {
                            $pinned_ids = wp_list_pluck( $all_posts, 'ID' );
                            $normal_args = array(
                                'post_type' => 'cmr_news',
                                'tax_query' => array(
                                    array(
                                        'taxonomy' => 'cmr_news_category',
                                        'field'    => 'term_id',
                                        'terms'    => $term->term_id,
                                    )
                                ),
                                'orderby' => 'date',
                                'order'   => 'DESC',
                                'posts_per_page' => $remaining,
                            );
                            if ( ! empty( $pinned_ids ) ) {
                                $normal_args['post__not_in'] = $pinned_ids;
                            }
                            $normal_query = new WP_Query( $normal_args );
                            $all_posts = array_merge( $all_posts, $normal_query->posts );
                        }

                        if ( ! empty( $all_posts ) ) {
                            $count = 0;
                            $total_posts = count( $all_posts );
                            global $post;
                            foreach ( $all_posts as $post ) {
                                setup_postdata( $post );
                                $post_id = get_the_ID();
                                $bg_image = get_the_post_thumbnail_url( $post_id, 'large' );
                                $logo_id = get_post_meta( $post_id, '_cmr_news_source_logo_id', true );
                                $logo_url = $logo_id ? wp_get_attachment_url( $logo_id ) : '';
                                $ext_link = get_post_meta( $post_id, '_cmr_news_external_link', true );
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
                                
                                if ( $is_media_releases ) {
                                    if ( $count === 0 ) {
                                        // Left Featured
                                        ?>
                                        <div class="cmr-media-left">
                                            <a href="<?php echo esc_url( $link ); ?>" target="<?php echo esc_attr( $target ); ?>" class="cmr-card-link-wrapper">
                                                <div class="cmr-card-image-wrap">
                                                    <?php if ( $bg_image ) : ?>
                                                        <img src="<?php echo esc_url( $bg_image ); ?>" class="cmr-card-bg" alt="<?php the_title_attribute(); ?>">
                                                    <?php endif; ?>
                                                </div>
                                                <div class="cmr-card-content">
                                                    <div class="cmr-card-meta">
                                                        <span class="cmr-category-tag">&mdash; Media Releases</span>
                                                    </div>
                                                    <h3 class="cmr-card-title"><?php the_title(); ?></h3>
                                                    <?php $arrow_url = $is_who_we_serve ? 'https://cmrindia.com/wp-content/uploads/2026/04/Symbol.svg' : 'https://cmrindia.com/wp-content/uploads/2026/04/Symbol-1.svg'; ?>
                                                    <span class="cmr-read-coverage">More Details <img src="<?php echo esc_url($arrow_url); ?>" class="cmr-arrow-icon" alt="Arrow"></span>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="cmr-media-right">
                                        <?php
                                    } else {
                                        // Right List Items
                                        ?>
                                        <div class="cmr-media-horizontal-card">
                                            <a href="<?php echo esc_url( $link ); ?>" target="<?php echo esc_attr( $target ); ?>" class="cmr-card-link-wrapper">
                                                <div class="cmr-card-image-wrap">
                                                    <?php if ( $bg_image ) : ?>
                                                        <img src="<?php echo esc_url( $bg_image ); ?>" class="cmr-card-bg" alt="<?php the_title_attribute(); ?>">
                                                    <?php endif; ?>
                                                </div>
                                                <div class="cmr-card-content">
                                                    <div class="cmr-card-meta">
                                                        <span class="cmr-category-tag">&mdash; Media Releases</span>
                                                    </div>
                                                    <h3 class="cmr-card-title"><?php the_title(); ?></h3>
                                                    <?php $arrow_url = $is_who_we_serve ? 'https://cmrindia.com/wp-content/uploads/2026/04/Symbol.svg' : 'https://cmrindia.com/wp-content/uploads/2026/04/Symbol-1.svg'; ?>
                                                    <span class="cmr-read-coverage">More Details <img src="<?php echo esc_url($arrow_url); ?>" class="cmr-arrow-icon" alt="Arrow"></span>
                                                </div>
                                            </a>
                                        </div>
                                        <?php
                                    }
                                    if ( $count === $total_posts - 1 && $count > 0 ) {
                                        echo '</div>'; // Close cmr-media-right
                                    } elseif ( $count === 0 && $total_posts === 1 ) {
                                        echo '<div class="cmr-media-right"></div>'; // Empty right column if only 1 post
                                    }
                                } else {
                                    // Original CMR In News Layout
                                    $card_class = ( $count === 0 ) ? 'cmr-card cmr-card-featured' : 'cmr-card cmr-card-standard';
                                    ?>
                                    <div class="<?php echo esc_attr( $card_class ); ?>">
                                        <a href="<?php echo esc_url( $link ); ?>" target="<?php echo esc_attr( $target ); ?>" class="cmr-card-link-wrapper">
                                            <div class="cmr-card-image-wrap">
                                                <?php if ( $bg_image ) : ?>
                                                    <img src="<?php echo esc_url( $bg_image ); ?>" class="cmr-card-bg" alt="<?php the_title_attribute(); ?>">
                                                <?php endif; ?>
                                                <?php if ( $count > 0 && $logo_url ) : ?>
                                                    <img src="<?php echo esc_url( $logo_url ); ?>" class="cmr-card-logo cmr-card-logo-desktop" alt="Source Logo">
                                                <?php endif; ?>
                                            </div>
                                            <div class="cmr-card-content">
                                                <?php if ( $count === 0 && $logo_url ) : ?>
                                                    <img src="<?php echo esc_url( $logo_url ); ?>" class="cmr-card-logo" alt="Source Logo">
                                                <?php elseif ( $count > 0 && $logo_url ) : ?>
                                                    <img src="<?php echo esc_url( $logo_url ); ?>" class="cmr-card-logo cmr-card-logo-mobile" alt="Source Logo">
                                                <?php endif; ?>
                                                <div class="cmr-card-meta">
                                                    <div class="cmr-meta-left">
                                                        <?php if ( $publisher ) : ?>
                                                            <span class="cmr-publisher"><?php echo esc_html( $publisher ); ?></span> <span class="cmr-separator">|</span> 
                                                        <?php endif; ?>
                                                        <span class="cmr-date">Published <?php echo esc_html( $date ); ?></span>
                                                    </div>
                                                    <?php if ( $reading_time ) : ?>
                                                        <div class="cmr-meta-right">
                                                            <span class="cmr-read-time"><?php echo esc_html( $reading_time ); ?></span>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <h3 class="cmr-card-title"><?php the_title(); ?></h3>
                                                <?php
                                                $arrow_url = $is_who_we_serve ? 'https://cmrindia.com/wp-content/uploads/2026/04/Symbol.svg' : 'https://cmrindia.com/wp-content/uploads/2026/04/Symbol-1.svg';
                                                ?>
                                                <span class="cmr-read-coverage">Read Coverage <img src="<?php echo esc_url($arrow_url); ?>" class="cmr-arrow-icon" alt="Arrow"></span>
                                            </div>
                                        </a>
                                    </div>
                                    <?php
                                }
                                $count++;
                            }
                            wp_reset_postdata();
                        }
                        ?>
                    </div>
                </div>
            <?php 
                $first = false;
            endforeach; 
            ?>
        </div>
        
        <!-- Removed Explore All button for SMB tabs -->
    </div>
    <?php
    return ob_get_clean();
}
