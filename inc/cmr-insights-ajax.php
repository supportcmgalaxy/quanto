<?php
add_action( 'wp_ajax_cmr_insights_ajax_search', 'cmr_insights_ajax_search_callback' );
add_action( 'wp_ajax_nopriv_cmr_insights_ajax_search', 'cmr_insights_ajax_search_callback' );

function cmr_insights_ajax_search_callback() {
    $search_term = isset($_POST['search_term']) ? sanitize_text_field($_POST['search_term']) : '';
    $prefix = isset($_POST['prefix']) ? sanitize_text_field($_POST['prefix']) : 'cmr-mui-';
    $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '';
    $cat_filter = isset($_POST['cat_filter']) ? sanitize_text_field($_POST['cat_filter']) : '';

    $query_args = array(
        'post_type'      => array('post', 'cmr_news'),
        'posts_per_page' => 9,
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC',
    );
    
    if (!empty($search_term)) {
        $query_args['s'] = $search_term;
    }

    $filter_slugs = array();
    if ( ! empty( $cat_filter ) && strtolower( $cat_filter ) !== 'all' ) {
        $clean_filter = strtolower( trim( $cat_filter ) );
        if ( $clean_filter === 'automotive' ) {
            $filter_slugs = array( 'automotive', 'automobile', 'automobiles', 'auto-tech', 'auto-industry', 'auto' );
        } elseif ( $clean_filter === 'consumer-tech' ) {
            $filter_slugs = array( 'consumer-tech', 'consumer-technology', 'consumer-electronics', 'consumer' );
        } elseif ( $clean_filter === 'digital-supply-chain' ) {
            $filter_slugs = array( 'digital-supply-chain', 'supply-chain', 'supply-chains', 'logistics', 'supply' );
        } else {
            $filter_slugs = array( $clean_filter, str_replace( ' ', '-', $clean_filter ) );
        }
    }

    $tax_queries = array();
    if ( ! empty( $category ) ) {
        $tax_queries[] = array(
            'taxonomy' => 'category',
            'field'    => 'slug',
            'terms'    => array_map( 'trim', explode( ',', $category ) ),
            'operator' => 'IN',
        );
    }

    if ( ! empty( $filter_slugs ) ) {
        $tax_queries[] = array(
            'taxonomy' => 'category',
            'field'    => 'slug',
            'terms'    => $filter_slugs,
            'operator' => 'IN',
        );
    }

    if ( count( $tax_queries ) > 1 ) {
        $query_args['tax_query'] = array_merge( array( 'relation' => 'AND' ), $tax_queries );
    } elseif ( count( $tax_queries ) === 1 ) {
        $query_args['tax_query'] = $tax_queries;
    }

    $all_query = new WP_Query( $query_args );
    $posts = $all_query->posts;

    if ( empty( $posts ) ) {
        if ( ! empty( $cat_filter ) && strtolower( $cat_filter ) !== 'all' ) {
            $cat_label = ucwords( str_replace( '-', ' ', $cat_filter ) );
            echo '<p class="' . esc_attr( $prefix ) . 'no-results" style="grid-column: 1 / -1; font-size: 17px; color: #666; text-align: center; padding: 50px 20px;">No insights found under ' . esc_html( $cat_label ) . '.</p>';
        } else {
            echo '<p class="' . esc_attr( $prefix ) . 'no-results" style="grid-column: 1 / -1; font-size: 17px; color: #666; text-align: center; padding: 50px 20px;">No insights found matching "' . esc_html( $search_term ) . '".</p>';
        }
        wp_die();
    }

    foreach ( $posts as $post_obj ) : 
        $thumbnail_url = get_the_post_thumbnail_url( $post_obj->ID, 'large' );
        if ( ! $thumbnail_url ) {
            $thumbnail_url = quanto_get_placeholder_image_uri();
        }
        
        $all_term_names = array();
        $terms = get_the_terms( $post_obj->ID, 'category' );
        if ( $terms && ! is_wp_error( $terms ) ) {
            $category_name = $terms[0]->name;
            foreach ($terms as $t) {
                $all_term_names[] = $t->name;
                $all_term_names[] = $t->slug;
            }
        }
        $cat_data_attr = esc_attr(strtolower(implode(' ', array_unique($all_term_names))));
        
        $post_date = get_the_date('d M Y', $post_obj);
        
        $content = $post_obj->post_content;
        $word_count = str_word_count( strip_tags( $content ) );
        $read_time = ceil( $word_count / 200 );
        if ($read_time < 1) $read_time = 1;

        $excerpt = get_the_excerpt($post_obj);
        if ( empty( $excerpt ) ) {
            $excerpt = wp_trim_words( $content, 20 );
        }
    ?>
    <a href="<?php echo esc_url(get_permalink($post_obj->ID)); ?>" class="<?php echo esc_attr($prefix); ?>card" data-category="<?php echo $cat_data_attr; ?>">
        <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo esc_attr(get_the_title($post_obj)); ?>" class="<?php echo esc_attr($prefix); ?>card-img">
        <div class="<?php echo esc_attr($prefix); ?>card-meta">
            <div class="<?php echo esc_attr($prefix); ?>card-cat-date">
                <span><?php echo esc_html($category_name); ?></span> | 
                <span><?php echo esc_html($post_date); ?></span>
            </div>
            <div class="<?php echo esc_attr($prefix); ?>card-read"><?php echo esc_html($read_time); ?> min read</div>
        </div>
        <h3 class="<?php echo esc_attr($prefix); ?>card-title"><?php echo esc_html(get_the_title($post_obj)); ?></h3>
        <p class="<?php echo esc_attr($prefix); ?>card-excerpt"><?php echo esc_html(wp_strip_all_tags($excerpt)); ?></p>
        <span class="<?php echo esc_attr($prefix); ?>read-more">
            Read More 
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                <line x1="7" y1="17" x2="17" y2="7"></line>
                <polyline points="7 7 17 7 17 17"></polyline>
            </svg>
        </span>
    </a>
    <?php endforeach;
    wp_die();
}
