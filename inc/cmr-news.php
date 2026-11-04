<?php
/**
 * CMR News & Media Releases Feature
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// 1. Register Custom Post Type and Taxonomy
add_action( 'init', 'cmr_news_register_post_type' );
function cmr_news_register_post_type() {
    $labels = array(
        'name'                  => 'CMR News',
        'singular_name'         => 'CMR News Item',
        'menu_name'             => 'CMR News',
        'add_new'               => 'Add New',
        'add_new_item'          => 'Add New News Item',
        'edit_item'             => 'Edit News Item',
        'new_item'              => 'New News Item',
        'view_item'             => 'View News Item',
        'all_items'             => 'All News Items',
        'search_items'          => 'Search News',
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'cmr-news-item' ),
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-media-document',
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments', 'author', 'revisions', 'trackbacks', 'custom-fields', 'post-formats' ),
        'taxonomies'         => array( 'category', 'post_tag' ),
        'show_in_rest'       => true,
    );

    register_post_type( 'cmr_news', $args );
    
    // TEMPORARY: Flush rewrite rules to fix the /cmr-news/ page collision automatically
    if ( get_option('cmr_news_rewrite_flushed_v2') !== 'yes' ) {
        flush_rewrite_rules();
        update_option('cmr_news_rewrite_flushed_v2', 'yes');
    }

    $tax_labels = array(
        'name'              => 'News Categories',
        'singular_name'     => 'News Category',
        'search_items'      => 'Search Categories',
        'all_items'         => 'All Categories',
        'parent_item'       => 'Parent Category',
        'parent_item_colon' => 'Parent Category:',
        'edit_item'         => 'Edit Category',
        'update_item'       => 'Update Category',
        'add_new_item'      => 'Add New Category',
        'new_item_name'     => 'New Category Name',
        'menu_name'         => 'News Categories',
    );

    $tax_args = array(
        'hierarchical'      => true,
        'labels'            => $tax_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'cmr-news-category' ),
        'show_in_rest'      => true,
    );

    register_taxonomy( 'cmr_news_category', array( 'cmr_news' ), $tax_args );
}

// Enable extra blog-like post capabilities and Elementor support for cmr_news
add_action( 'init', 'cmr_news_enable_extra_features', 20 );
function cmr_news_enable_extra_features() {
    add_post_type_support( 'cmr_news', array( 'author', 'revisions', 'trackbacks', 'custom-fields', 'post-formats', 'elementor' ) );
    register_taxonomy_for_object_type( 'post_format', 'cmr_news' );
}

// Enable Elementor editor for cmr_news
add_filter( 'option_elementor_cpt_support', 'cmr_news_elementor_support_filter' );
add_filter( 'default_option_elementor_cpt_support', 'cmr_news_elementor_support_filter' );
function cmr_news_elementor_support_filter( $value ) {
    if ( ! is_array( $value ) ) {
        $value = array( 'post', 'page' );
    }
    if ( ! in_array( 'cmr_news', $value ) ) {
        $value[] = 'cmr_news';
    }
    return $value;
}

// Add dropdown filter for News Categories on admin edit.php
add_action( 'restrict_manage_posts', 'cmr_news_filter_admin_by_category' );
function cmr_news_filter_admin_by_category( $post_type ) {
    if ( $post_type === 'cmr_news' ) {
        $taxonomy = 'cmr_news_category';
        $selected = isset( $_GET[$taxonomy] ) ? $_GET[$taxonomy] : '';
        $info_taxonomy = get_taxonomy( $taxonomy );
        if ( $info_taxonomy ) {
            wp_dropdown_categories( array(
                'show_option_all' => sprintf( __( 'All %s', 'quanto' ), $info_taxonomy->label ),
                'taxonomy'        => $taxonomy,
                'name'            => $taxonomy,
                'orderby'         => 'name',
                'selected'        => $selected,
                'show_count'      => true,
                'hide_empty'      => false,
                'value_field'     => 'slug',
            ) );
        }
    }
}

// Ensure the Categories column in the admin list falls back to News Categories if standard categories are not yet set
add_action( 'manage_cmr_news_posts_custom_column', 'cmr_news_custom_column_fallback_content', 5, 2 );
function cmr_news_custom_column_fallback_content( $column_name, $post_id ) {
    if ( $column_name === 'taxonomy-category' ) {
        $cats = get_the_terms( $post_id, 'category' );
        if ( empty( $cats ) || is_wp_error( $cats ) ) {
            $news_cats = get_the_terms( $post_id, 'cmr_news_category' );
            if ( ! empty( $news_cats ) && ! is_wp_error( $news_cats ) ) {
                $out = array();
                foreach ( $news_cats as $nc ) {
                    $out[] = sprintf( '<a href="%s">%s</a>', esc_url( admin_url( 'edit.php?post_type=cmr_news&cmr_news_category=' . $nc->slug ) ), esc_html( $nc->name ) );
                }
                echo implode( ', ', $out );
            }
        }
    }
}

// Keep cmr_news_category and core category in sync on save (bidirectional)
add_action( 'save_post_cmr_news', 'cmr_news_sync_categories_on_save', 25, 2 );
function cmr_news_sync_categories_on_save( $post_id, $post ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( wp_is_post_revision( $post_id ) ) return;
    
    // Direction 1: cmr_news_category → standard category
    $news_terms = wp_get_object_terms( $post_id, 'cmr_news_category' );
    if ( ! empty( $news_terms ) && ! is_wp_error( $news_terms ) ) {
        $cat_ids = array();
        foreach ( $news_terms as $nt ) {
            $cat = get_term_by( 'slug', $nt->slug, 'category' );
            if ( ! $cat ) {
                $cat = get_term_by( 'name', $nt->name, 'category' );
            }
            if ( ! $cat ) {
                $inserted = wp_insert_term( $nt->name, 'category', array( 'slug' => $nt->slug ) );
                if ( ! is_wp_error( $inserted ) && isset( $inserted['term_id'] ) ) {
                    $cat_ids[] = (int) $inserted['term_id'];
                }
            } else {
                $cat_ids[] = (int) $cat->term_id;
            }
        }
        if ( ! empty( $cat_ids ) ) {
            wp_set_object_terms( $post_id, $cat_ids, 'category', true );
        }
    }

    // Direction 2: standard category → cmr_news_category
    // If a post has a standard category assigned but no matching cmr_news_category, auto-assign it.
    $cat_terms = wp_get_object_terms( $post_id, 'category' );
    if ( ! empty( $cat_terms ) && ! is_wp_error( $cat_terms ) ) {
        $existing_news_slugs = ! empty( $news_terms ) && ! is_wp_error( $news_terms )
            ? wp_list_pluck( $news_terms, 'slug' )
            : array();

        $news_term_ids_to_add = array();
        foreach ( $cat_terms as $ct ) {
            // Only match categories that correspond to known cmr_news_category terms
            $news_term = get_term_by( 'name', $ct->name, 'cmr_news_category' );
            if ( ! $news_term ) {
                $news_term = get_term_by( 'slug', $ct->slug, 'cmr_news_category' );
            }
            if ( $news_term && ! in_array( $news_term->slug, $existing_news_slugs ) ) {
                $news_term_ids_to_add[] = (int) $news_term->term_id;
            }
        }
        if ( ! empty( $news_term_ids_to_add ) ) {
            wp_set_object_terms( $post_id, $news_term_ids_to_add, 'cmr_news_category', true );
        }
    }
}

// One-time backfill of core category terms from cmr_news_category for existing news posts (bidirectional)
add_action( 'admin_init', 'cmr_news_backfill_categories_once' );
function cmr_news_backfill_categories_once() {
    if ( get_option( 'cmr_news_cat_backfilled_v2' ) === 'yes' ) {
        return;
    }
    
    $news_posts = get_posts( array(
        'post_type'      => 'cmr_news',
        'posts_per_page' => -1,
        'post_status'    => 'any',
        'fields'         => 'ids',
    ) );
    
    if ( ! empty( $news_posts ) ) {
        foreach ( $news_posts as $p_id ) {
            $news_terms = wp_get_object_terms( $p_id, 'cmr_news_category' );
            
            // Direction 1: cmr_news_category → standard category
            if ( ! empty( $news_terms ) && ! is_wp_error( $news_terms ) ) {
                $cat_ids = array();
                foreach ( $news_terms as $nt ) {
                    $cat = get_term_by( 'slug', $nt->slug, 'category' );
                    if ( ! $cat ) {
                        $cat = get_term_by( 'name', $nt->name, 'category' );
                    }
                    if ( ! $cat ) {
                        $inserted = wp_insert_term( $nt->name, 'category', array( 'slug' => $nt->slug ) );
                        if ( ! is_wp_error( $inserted ) && isset( $inserted['term_id'] ) ) {
                            $cat_ids[] = (int) $inserted['term_id'];
                        }
                    } else {
                        $cat_ids[] = (int) $cat->term_id;
                    }
                }
                if ( ! empty( $cat_ids ) ) {
                    wp_set_object_terms( $p_id, $cat_ids, 'category', true );
                }
            }

            // Direction 2: standard category → cmr_news_category
            $cat_terms = wp_get_object_terms( $p_id, 'category' );
            if ( ! empty( $cat_terms ) && ! is_wp_error( $cat_terms ) ) {
                $existing_news_slugs = ! empty( $news_terms ) && ! is_wp_error( $news_terms )
                    ? wp_list_pluck( $news_terms, 'slug' )
                    : array();

                $news_term_ids_to_add = array();
                foreach ( $cat_terms as $ct ) {
                    $news_term = get_term_by( 'name', $ct->name, 'cmr_news_category' );
                    if ( ! $news_term ) {
                        $news_term = get_term_by( 'slug', $ct->slug, 'cmr_news_category' );
                    }
                    if ( $news_term && ! in_array( $news_term->slug, $existing_news_slugs ) ) {
                        $news_term_ids_to_add[] = (int) $news_term->term_id;
                        $existing_news_slugs[]  = $news_term->slug;
                    }
                }
                if ( ! empty( $news_term_ids_to_add ) ) {
                    wp_set_object_terms( $p_id, $news_term_ids_to_add, 'cmr_news_category', true );
                }
            }
        }
    }
    
    update_option( 'cmr_news_cat_backfilled_v2', 'yes' );
}

add_action( 'init', 'cmr_news_insert_default_terms' );
function cmr_news_insert_default_terms() {
    $terms = array( 'CMR In News', 'Media Releases', 'Quarterly Results' );
    foreach ( $terms as $term ) {
        if ( ! term_exists( $term, 'cmr_news_category' ) ) {
            wp_insert_term( $term, 'cmr_news_category' );
        }
    }
}

// 2. Add Custom Meta Boxes
add_action( 'add_meta_boxes', 'cmr_news_add_meta_boxes' );
function cmr_news_add_meta_boxes() {
    add_meta_box(
        'cmr_news_meta_box',
        'News Details',
        'cmr_news_render_meta_box',
        'cmr_news',
        'normal',
        'high'
    );
}

function cmr_news_render_meta_box( $post ) {
    wp_nonce_field( 'cmr_news_save_meta_box_data', 'cmr_news_meta_box_nonce' );

    $external_link = get_post_meta( $post->ID, '_cmr_news_external_link', true );
    $source_logo_id = get_post_meta( $post->ID, '_cmr_news_source_logo_id', true );
    $source_logo_url = '';
    if ( $source_logo_id ) {
        $source_logo_url = wp_get_attachment_url( $source_logo_id );
    }
    
    $reading_time = get_post_meta( $post->ID, '_cmr_news_reading_time', true );
    $publisher_name = get_post_meta( $post->ID, '_cmr_news_publisher_name', true );
    $is_featured = get_post_meta( $post->ID, '_cmr_news_is_featured', true );
    $document_id = get_post_meta( $post->ID, '_cmr_news_document_id', true );
    $document_url = $document_id ? wp_get_attachment_url( $document_id ) : '';
    
    ?>
    <style>
        .cmr-meta-row { margin-bottom: 15px; }
        .cmr-meta-row label { display: block; font-weight: bold; margin-bottom: 5px; }
        .cmr-meta-row input[type="text"] { width: 100%; max-width: 600px; }
        .cmr-logo-preview { max-width: 150px; margin-top: 10px; display: block; }
    </style>
    
    <div class="cmr-meta-row">
        <label for="cmr_news_is_featured" style="font-weight: normal; cursor: pointer;">
            <input type="checkbox" id="cmr_news_is_featured" name="cmr_news_is_featured" value="1" <?php checked( $is_featured, '1' ); ?> />
            <strong>Pin this to the top</strong> (Show this item first in its tab, regardless of date)
        </label>
    </div>

    <div class="cmr-meta-row">
        <label for="cmr_news_publisher_name">Publisher Name (e.g., CNN, Times of India)</label>
        <input type="text" id="cmr_news_publisher_name" name="cmr_news_publisher_name" value="<?php echo esc_attr( $publisher_name ); ?>" />
    </div>

    <div class="cmr-meta-row">
        <label for="cmr_news_external_link">External Link URL</label>
        <input type="text" id="cmr_news_external_link" name="cmr_news_external_link" value="<?php echo esc_attr( $external_link ); ?>" placeholder="https://" />
        <p class="description">Provide a link to the external article OR upload a document below.</p>
    </div>

    <div class="cmr-meta-row">
        <label for="cmr_news_document">Or Upload a Document (PDF, etc.)</label>
        <input type="hidden" id="cmr_news_document_id" name="cmr_news_document_id" value="<?php echo esc_attr( $document_id ); ?>" />
        <button type="button" class="button cmr_upload_doc_btn">Upload/Select Document</button>
        <button type="button" class="button cmr_remove_doc_btn" <?php echo ! $document_id ? 'style="display:none;"' : ''; ?>>Remove Document</button>
        <div class="cmr-doc-preview" style="margin-top: 10px; <?php echo ! $document_id ? 'display:none;' : ''; ?>">
            <a href="<?php echo esc_url( $document_url ); ?>" target="_blank">View Current Document</a>
        </div>
    </div>
    
    <div class="cmr-meta-row">
        <label for="cmr_news_reading_time">Reading Time (e.g., 8 mins)</label>
        <input type="text" id="cmr_news_reading_time" name="cmr_news_reading_time" value="<?php echo esc_attr( $reading_time ); ?>" placeholder="8 mins" />
    </div>

    <div class="cmr-meta-row">
        <label for="cmr_news_source_logo">Source Logo Image (Link Image)</label>
        <input type="hidden" id="cmr_news_source_logo_id" name="cmr_news_source_logo_id" value="<?php echo esc_attr( $source_logo_id ); ?>" />
        <button type="button" class="button cmr_upload_logo_btn">Select/Upload Logo</button>
        <button type="button" class="button cmr_remove_logo_btn" <?php echo ! $source_logo_id ? 'style="display:none;"' : ''; ?>>Remove Logo</button>
        <img src="<?php echo esc_url( $source_logo_url ); ?>" class="cmr-logo-preview" <?php echo ! $source_logo_id ? 'style="display:none;"' : ''; ?> />
    </div>
    
    <script>
    jQuery(document).ready(function($){
        var mediaUploader;
        $('.cmr_upload_logo_btn').click(function(e) {
            e.preventDefault();
            if (mediaUploader) { mediaUploader.open(); return; }
            mediaUploader = wp.media.frames.file_frame = wp.media({
                title: 'Choose Source Logo',
                button: { text: 'Choose Logo' }, multiple: false
            });
            mediaUploader.on('select', function() {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                $('#cmr_news_source_logo_id').val(attachment.id);
                $('.cmr-logo-preview').attr('src', attachment.url).show();
                $('.cmr_remove_logo_btn').show();
            });
            mediaUploader.open();
        });
        $('.cmr_remove_logo_btn').click(function(e){
            e.preventDefault();
            $('#cmr_news_source_logo_id').val('');
            $('.cmr-logo-preview').attr('src', '').hide();
            $(this).hide();
        });

        // Document Uploader
        var docUploader;
        $('.cmr_upload_doc_btn').click(function(e) {
            e.preventDefault();
            if (docUploader) { docUploader.open(); return; }
            docUploader = wp.media.frames.file_frame = wp.media({
                title: 'Choose Document',
                button: { text: 'Choose Document' }, multiple: false
            });
            docUploader.on('select', function() {
                var attachment = docUploader.state().get('selection').first().toJSON();
                $('#cmr_news_document_id').val(attachment.id);
                $('.cmr-doc-preview a').attr('href', attachment.url);
                $('.cmr-doc-preview').show();
                $('.cmr_remove_doc_btn').show();
            });
            docUploader.open();
        });
        $('.cmr_remove_doc_btn').click(function(e){
            e.preventDefault();
            $('#cmr_news_document_id').val('');
            $('.cmr-doc-preview a').attr('href', '#');
            $('.cmr-doc-preview').hide();
            $(this).hide();
        });
    });
    </script>
    <?php
}

add_action( 'save_post', 'cmr_news_save_meta_box_data' );
function cmr_news_save_meta_box_data( $post_id ) {
    if ( ! isset( $_POST['cmr_news_meta_box_nonce'] ) ) { return; }
    if ( ! wp_verify_nonce( $_POST['cmr_news_meta_box_nonce'], 'cmr_news_save_meta_box_data' ) ) { return; }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return; }
    if ( ! current_user_can( 'edit_post', $post_id ) ) { return; }

    if ( isset( $_POST['cmr_news_external_link'] ) ) {
        update_post_meta( $post_id, '_cmr_news_external_link', sanitize_text_field( $_POST['cmr_news_external_link'] ) );
    }
    if ( isset( $_POST['cmr_news_source_logo_id'] ) ) {
        update_post_meta( $post_id, '_cmr_news_source_logo_id', sanitize_text_field( $_POST['cmr_news_source_logo_id'] ) );
    }
    if ( isset( $_POST['cmr_news_reading_time'] ) ) {
        update_post_meta( $post_id, '_cmr_news_reading_time', sanitize_text_field( $_POST['cmr_news_reading_time'] ) );
    }
    if ( isset( $_POST['cmr_news_publisher_name'] ) ) {
        update_post_meta( $post_id, '_cmr_news_publisher_name', sanitize_text_field( $_POST['cmr_news_publisher_name'] ) );
    }
    if ( isset( $_POST['cmr_news_document_id'] ) ) {
        update_post_meta( $post_id, '_cmr_news_document_id', sanitize_text_field( $_POST['cmr_news_document_id'] ) );
    }
    
    update_post_meta( $post_id, '_cmr_news_is_featured', isset( $_POST['cmr_news_is_featured'] ) ? '1' : '0' );
}

// 3. Enqueue Admin Scripts for Media Uploader
add_action( 'admin_enqueue_scripts', 'cmr_news_admin_scripts' );
function cmr_news_admin_scripts() {
    global $typenow;
    if ( $typenow == 'cmr_news' ) {
        wp_enqueue_media();
    }
}

// 4. Register Shortcode
add_shortcode( 'cmr_news_tabs', 'cmr_news_tabs_shortcode' );
function cmr_news_tabs_shortcode( $atts ) {
    $atts = shortcode_atts( array(
        'category' => '', // Comma separated slugs to include (e.g. 'cmr-in-news')
        'exclude'  => '', // Don't exclude anything by default
    ), $atts, 'cmr_news_tabs' );

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
    $is_who_we_serve = is_page('who-we-serve') || is_page('press-releases');
    $bg_class = $is_who_we_serve ? ' cmr-news-black-bg' : '';
    ?>
    <style id="cmr-news-mobile-firstcard-gap-css">
    @media (max-width: 768px) {
        .cmr-news-grid, .cmr-media-grid, .cmr-insights-grid {
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
        .cmr-news-tabs {
            display: flex !important;
            overflow-x: auto !important;
            white-space: nowrap !important;
            gap: 15px !important;
            padding-left: 20px !important;
            padding-right: 20px !important;
            padding-bottom: 10px !important;
            scroll-padding-left: 20px !important;
            -webkit-overflow-scrolling: touch !important;
            scrollbar-width: none !important;
            box-sizing: border-box !important;
        }
        .cmr-card, .cmr-card-featured, .cmr-card-standard, .cmr-media-left, .cmr-media-horizontal-card {
            flex: 0 0 85% !important;
            max-width: 85% !important;
            width: 85% !important;
            height: 400px !important;
            scroll-snap-align: start !important;
            scroll-margin-left: 20px !important;
        }
        .cmr-card-standard .cmr-card-image-wrap .cmr-card-logo,
        .cmr-card-image-wrap .cmr-card-logo,
        .cmr-card-logo-desktop {
            display: none !important;
        }
        .cmr-card-logo-mobile,
        .cmr-card-content .cmr-card-logo,
        .cmr-card-featured .cmr-card-content .cmr-card-logo,
        .cmr-card-featured .cmr-card-logo {
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
    .cmr-card-logo-mobile {
        display: none !important;
    }
    .cmr-card-featured .cmr-card-content .cmr-card-logo,
    .cmr-card-featured .cmr-card-logo {
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
        margin-bottom: 16px !important;
    }
    </style>
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
                    $display_name = $term->name;
                    
                    if ( $term->slug === 'cmr-in-news' || $term->name == 'CMR In News' ) {
                        $icon_url = get_theme_file_uri( '/assets/images/icons/airdrop.svg' );
                    } elseif ( $term->slug === 'media-releases' || $term->slug === 'press-releases' || $term->name == 'Media Releases' || $term->name == 'Press Releases' ) {
                        $icon_url = get_theme_file_uri( '/assets/images/icons/Frame.svg' );
                        $display_name = 'Media Releases';
                    } elseif ( $term->slug === 'quarterly-results' || $term->name == 'Quarterly Results' ) {
                        $icon_url = get_theme_file_uri( '/assets/images/icons/quterly.svg' );
                    }
                    
                    if ( $icon_url ) {
                        echo '<span class="cmr-tab-icon" style="-webkit-mask-image: url(' . esc_url($icon_url) . '); mask-image: url(' . esc_url($icon_url) . ');"></span> ';
                    }
                    echo '<span>' . esc_html( $display_name ) . '</span>'; 
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
                    $is_media_releases = ( $term->slug === 'media-releases' || $term->slug === 'media-release' || $term->slug === 'press-releases' );
                    $grid_class = $is_media_releases ? 'cmr-insights-grid' : 'cmr-news-grid';
                    
                    if ( $is_media_releases ) {
                        wp_enqueue_style( 'cmr-latest-insights' ); // Ensure grid CSS is loaded
                    }
                    ?>
                    <div class="<?php echo esc_attr( $grid_class ); ?>">
                        <?php
                        $target_count = 4;
                        
                        $pinned_query = new WP_Query( array(
                            'post_type' => 'cmr_news',
                            'tax_query' => array(
                                'relation' => 'OR',
                                array(
                                    'taxonomy' => 'cmr_news_category',
                                    'field'    => 'term_id',
                                    'terms'    => $term->term_id,
                                ),
                                array(
                                    'taxonomy' => 'category',
                                    'field'    => 'name',
                                    'terms'    => $term->name,
                                ),
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
                                    'relation' => 'OR',
                                    array(
                                        'taxonomy' => 'cmr_news_category',
                                        'field'    => 'term_id',
                                        'terms'    => $term->term_id,
                                    ),
                                    array(
                                        'taxonomy' => 'category',
                                        'field'    => 'name',
                                        'terms'    => $term->name,
                                    ),
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
                                    $placeholder = 'https://via.placeholder.com/800x600?text=No+Image';
                                    $img_src = $bg_image ? $bg_image : $placeholder;
                                    
                                    if ( $count === 0 ) {
                                        ?>
                                        <div class="cmr-insights-featured">
                                            <a href="<?php echo esc_url( $link ); ?>" target="<?php echo esc_attr( $target ); ?>" class="insights-featured-card" style="background-image: url('<?php echo esc_url( $img_src ); ?>');">
                                                <div class="insights-featured-overlay"></div>
                                                <div class="insights-featured-content">
                                                    <span class="insights-tag">&mdash; Media Releases</span>
                                                    <h3 class="insights-title"><?php the_title(); ?></h3>
                                                    <span class="insights-more-link">More Details <i class="fa-solid fa-arrow-right" style="transform: rotate(-45deg);"></i></span>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="cmr-insights-stack">
                                        <?php
                                    } else {
                                        ?>
                                            <a href="<?php echo esc_url( $link ); ?>" target="<?php echo esc_attr( $target ); ?>" class="insights-stacked-card">
                                                <div class="insights-stacked-image">
                                                    <img src="<?php echo esc_url( $img_src ); ?>" alt="<?php the_title_attribute(); ?>" />
                                                </div>
                                                <div class="insights-stacked-content">
                                                    <span class="insights-tag">&mdash; Media Releases</span>
                                                    <h4 class="insights-title"><?php the_title(); ?></h4>
                                                    <span class="insights-more-link">More Details <i class="fa-solid fa-arrow-right" style="transform: rotate(-45deg);"></i></span>
                                                </div>
                                            </a>
                                        <?php
                                    }
                                } else {
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
                                                <?php
                                                $arrow_url = $is_who_we_serve ? 'https://cmrindia.com/wp-content/uploads/2026/04/Symbol.svg' : 'https://cmrindia.com/wp-content/uploads/2026/04/Symbol-1.svg';
                                                $btn_text = 'Read Coverage';
                                                ?>
                                                <span class="cmr-read-coverage"><?php echo esc_html($btn_text); ?> <img src="<?php echo esc_url($arrow_url); ?>" class="cmr-arrow-icon" alt="Arrow"></span>
                                            </div>
                                        </a>
                                    </div>
                                    <?php
                                }
                                $count++;
                            }
                            if ( $is_media_releases && $count > 1 ) {
                                echo '</div>'; // close cmr-insights-stack
                            }
                            wp_reset_postdata();
                        }
                        ?>
                    </div>
                    
                    <div class="cmr-news-footer">
                        <a href="<?php echo esc_url( home_url( '/cmr-news/' ) ); ?>" class="cmr-explore-all">Explore All <img src="https://cmrindia.com/wp-content/uploads/2026/04/Symbol.svg" class="cmr-arrow-icon" alt="Arrow"></a>
                    </div>
                </div>
            <?php 
                $first = false;
            endforeach; 
            ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

// Force comments open for cmr_news
add_filter( 'comments_open', 'cmr_news_force_comments_open', 10, 2 );
function cmr_news_force_comments_open( $open, $post_id ) {
    if ( get_post_type( $post_id ) === 'cmr_news' ) {
        return true;
    }
    return $open;
}

require_once get_template_directory() . '/inc/cmr-news-coverage.php';
