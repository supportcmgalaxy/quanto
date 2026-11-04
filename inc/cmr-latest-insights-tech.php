<?php
/**
 * Shortcode for Latest Insights Tech Section
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! function_exists( 'cmr_latest_insights_tech_shortcode' ) ) {
    function cmr_latest_insights_tech_shortcode( $atts ) {
        // Enqueue the specific CSS for this shortcode
        wp_enqueue_style( 'cmr-latest-insights' );

        $atts = shortcode_atts( array(
            'posts_per_page' => 4,
            'nav_title'      => 'Automotive',
            'section_title'  => 'Latest Insights',
            'section_desc'   => 'Explore expert analysis, research reports, and real-time market signals shaping industries and business strategy.',
            'category'       => 'infotech,consumer-tech',
            'link_overview'  => '#top',
            'link_insights'  => '#overview',
            'link_reports'   => '#reports',
            'link_market'    => '#cmr-market-updates',
            'link_newsroom'  => '#newsroom',
            'link_cmr_news'  => '#cmr-in-news'
        ), $atts );

        $query_args = array(
            'post_type'      => array( 'post', 'cmr_news', 'cmr_media' ),
            'posts_per_page' => max( 16, intval( $atts['posts_per_page'] ) * 4 ),
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC',
            'category_name'  => implode(',', array_map('sanitize_title', explode(',', $atts['category']))),
        );

        $insights_query = new WP_Query( $query_args );

        $unique_posts = array();
        $seen_ids = array();
        $seen_titles = array();
        $max_posts = intval( $atts['posts_per_page'] );

        if ( $insights_query->have_posts() ) {
            foreach ( $insights_query->posts as $p ) {
                $norm_title = sanitize_title( $p->post_title );
                if ( in_array( $p->ID, $seen_ids, true ) || ( ! empty( $norm_title ) && in_array( $norm_title, $seen_titles, true ) ) ) {
                    continue;
                }
                $seen_ids[] = $p->ID;
                $seen_titles[] = $norm_title;
                $unique_posts[] = $p;
                if ( count( $unique_posts ) >= $max_posts ) {
                    break;
                }
            }
        }

        ob_start();
        ?>
        <div class="cmr-latest-insights-section cmr-latest-insights-tech">
            <div class="cmr-latest-nav-bar intel-nav-bar">
                <div class="intel-nav-title">
                    <?php echo esc_html( $atts['nav_title'] ); ?>
                </div>
                <div class="intel-nav-links">
                    <a href="<?php echo esc_attr( $atts['link_overview'] ); ?>">Overview</a>
                    <a href="<?php echo esc_attr( $atts['link_insights'] ); ?>">Insights</a>
                    <a href="<?php echo esc_attr( $atts['link_reports'] ); ?>">Reports</a>
                    <a href="<?php echo esc_attr( $atts['link_market'] ); ?>">Market Updates</a>
                    <a href="<?php echo esc_attr( $atts['link_newsroom'] ); ?>">Newsroom</a>
                    <a href="<?php echo esc_attr( $atts['link_cmr_news'] ); ?>">CMR in news</a>
                </div>
            </div>

            <div id="overview" class="cmr-latest-insights-header" style="margin-bottom: 40px; margin-top: 50px;">
                <?php if ( ! empty( $atts['section_title'] ) ) : ?>
                    <h2 class="cmr-latest-insights-title" style="font-size: 42px; font-weight: 600; color: #111; margin: 0 0 15px 0; letter-spacing: -1px;">
                        <?php echo esc_html( $atts['section_title'] ); ?>
                    </h2>
                <?php endif; ?>
                
                <div class="cmr-insights-desc-search-wrap" style="display: flex; justify-content: space-between; align-items: flex-end; gap: 20px; flex-wrap: wrap;">
                    <?php if ( ! empty( $atts['section_desc'] ) ) : ?>
                        <p class="cmr-latest-insights-desc" style="font-size: 18px; color: #555; margin: 0; max-width: 750px; line-height: 1.5; flex: 1 1 500px;">
                            <?php echo esc_html( $atts['section_desc'] ); ?>
                        </p>
                    <?php endif; ?>

                    <div class="cmr-insights-search" style="margin: 0; flex: 0 0 auto;">
                        <form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                            <input type="search" class="search-field" placeholder="Search by name" value="<?php echo get_search_query(); ?>" name="s" />
                            <button type="submit" class="search-submit" aria-label="Search">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </button>
                            <input type="hidden" name="post_type" value="cmr_news" />
                        </form>
                    </div>
                </div>
            </div>

            <?php if ( ! empty( $unique_posts ) ) : ?>
                <div class="cmr-insights-grid">
                    <?php
                    $post_count = 0;
                    foreach ( $unique_posts as $insight_post ) :
                        $post_count++;
                        
                        $post_title = get_the_title( $insight_post );
                        $post_link = get_permalink( $insight_post );
                        $thumbnail_url = get_the_post_thumbnail_url( $insight_post->ID, 'full' );
                        if ( ! $thumbnail_url ) {
                            $thumbnail_url = 'https://via.placeholder.com/800x600?text=No+Image';
                        }
                        
                        // Categories / Tags
                        $category_name = ! empty( $atts['nav_title'] ) ? $atts['nav_title'] : 'Tech';
                        $terms = get_the_terms( $insight_post->ID, 'category' );
                        if ( ! $terms || is_wp_error( $terms ) ) {
                            $terms = get_the_terms( $insight_post->ID, 'cmr_news_category' );
                        }
                        if ( $terms && ! is_wp_error( $terms ) ) {
                            $found_valid = false;
                            foreach ( $terms as $t ) {
                                if ( ! in_array( strtolower( trim( $t->name ) ), array( 'cmr in news', 'media releases', 'quarterly results', 'uncategorized' ) ) ) {
                                    $category_name = $t->name;
                                    $found_valid = true;
                                    break;
                                }
                            }
                            if ( ! $found_valid && ! empty( $atts['nav_title'] ) ) {
                                $category_name = $atts['nav_title'];
                            }
                        }

                        if ( $post_count === 1 ) {
                            // Featured Left Card
                            ?>
                            <div class="cmr-insights-featured">
                                <a href="<?php echo esc_url( $post_link ); ?>" class="insights-featured-card" style="background-image: url('<?php echo esc_url( $thumbnail_url ); ?>');">
                                    <div class="insights-featured-overlay"></div>
                                    <div class="insights-featured-content">
                                        <span class="insights-tag">&mdash; <?php echo esc_html( $category_name ); ?></span>
                                        <h3 class="insights-title"><?php echo esc_html( $post_title ); ?></h3>
                                        <span class="insights-more-link">More Details <i class="fa-solid fa-arrow-right" style="transform: rotate(-45deg);"></i></span>
                                    </div>
                                </a>
                            </div>
                            <div class="cmr-insights-stack">
                            <?php
                        } else {
                            // Stacked Right Cards
                            ?>
                                <a href="<?php echo esc_url( $post_link ); ?>" class="insights-stacked-card">
                                    <div class="insights-stacked-image">
                                        <img src="<?php echo esc_url( $thumbnail_url ); ?>" alt="<?php echo esc_attr( $post_title ); ?>" />
                                    </div>
                                    <div class="insights-stacked-content">
                                        <span class="insights-tag">&mdash; <?php echo esc_html( $category_name ); ?></span>
                                        <h4 class="insights-title"><?php echo esc_html( $post_title ); ?></h4>
                                        <span class="insights-more-link">More Details <i class="fa-solid fa-arrow-right" style="transform: rotate(-45deg);"></i></span>
                                    </div>
                                </a>
                            <?php
                        }

                    endforeach;
                    
                    if ( $post_count > 1 ) {
                        echo '</div>'; // close cmr-insights-stack
                    }
                    ?>
                </div>
            <?php else : ?>
                <p>No insights found for InfoTech or Consumer Tech.</p>
            <?php endif; wp_reset_postdata(); ?>
        </div>
        
        <?php
        return ob_get_clean();
    }
}
add_shortcode( 'cmr_latest_insights_tech', 'cmr_latest_insights_tech_shortcode' );
