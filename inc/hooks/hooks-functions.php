<?php

/**

 * @Packge     : Quanto

 * @Version    : 1.0

 * @Author     : Mirrortheme

 * @Author URI : https://mirrortheme.com/

 *

 */



    // Block direct access

    if( ! defined( 'ABSPATH' ) ){

        exit();

    }





    // cursor hook function

    if( ! function_exists( 'quanto_cursor_wrap_cb' ) ) {

        function quanto_cursor_wrap_cb() {

            $cursor_display =  quanto_opt('quanto_display_cursor');



            if( class_exists('ReduxFramework') ){

                if( $cursor_display ){

                    echo '<div class="cursor d-none d-lg-block">';

                    echo '</div>';

                }

            }

        }

    };



    

    // preloader hook function

    if( ! function_exists( 'quanto_preloader_wrap_cb' ) ) {

        function quanto_preloader_wrap_cb() {

            $preloader_display =  quanto_opt('quanto_display_preloader');

            $preloader_image   = quanto_opt('preloader_image');



            if( class_exists('ReduxFramework') ){

                if( $preloader_display ){

                    echo '<div class="preloader">';

                        echo '<div class="spinner-wrap">';

                            echo '<div class="preloader-logo">';

                                if ( ! empty( $preloader_image['url'] ) ) {

                                    echo '<img src="' . esc_url( $preloader_image['url'] ) . '" alt="' . esc_attr__('Preloader', 'quanto') . '">';

                                }  

                            echo '</div>';

                            echo '<div class="spinner"></div>';

                        echo '</div>';

                    echo '</div>';

                }

            }else{

                echo '<div class="preloader">';

                    echo '<div class="spinner-wrap">';

                        echo '<div class="preloader-logo">';

                        echo '</div>';

                        echo '<div class="spinner"></div>';

                    echo '</div>';

                echo '</div>';

            }

        }

    };



    // Header Hook function

    if( !function_exists('quanto_header_cb') ) {

        function quanto_header_cb( ) {

            get_template_part('templates/header');

        }

    } 



    // Breadcrumb Hook function

    if( !function_exists('quanto_breadcrumb_cb') ) {

        function quanto_breadcrumb_cb( ) {

            if ( class_exists('ReduxFramework') ) {

                $breadcrumb_switcher = quanto_opt('quanto_full_breadcrumb_switcher');

            } else {

                $breadcrumb_switcher = 1; // Default ON if Redux not present

            }



            if ( $breadcrumb_switcher == 1 ) {

                get_template_part('templates/header-menu-bottom');

            }

        }

    } 



    // Blog Start Wrapper Function

    if( !function_exists('quanto_blog_section_title_cb') ) {

        function quanto_blog_section_title_cb() {

            if( class_exists( 'ReduxFramework' ) ){

                $breadcrumb_switcher = quanto_opt('quanto_full_breadcrumb_switcher');

                $quanto_blog_section_custom_title_tag    = quanto_opt('quanto_blog_section_custom_title_tag');

            }else{

                $breadcrumb_switcher = 0; // Default Off if Redux not present

                $quanto_blog_section_custom_title_tag    = 'h1';

            }



            $quanto_blog_section_title_switcher = quanto_opt('quanto_blog_section_title_switcher');

            $quanto_blog_section_custom_title = quanto_opt('quanto_blog_section_custom_title');

            if( $quanto_blog_section_title_switcher == 1 ){

                echo '<section class="quanto-hero-blog-section overflow-hidden">';

                    echo '<div class="container custom-container">';

                        echo '<div class="row g-4">';

                            echo '<div class="col-lg-12 col-xxl-11">';

                                echo '<div class="quanto-hero-blog__content move-anim" data-delay="0.45">';

                                    echo quanto_heading_tag(

                                        array(

                                            "tag"   => esc_attr( $quanto_blog_section_custom_title_tag ),

                                            "text"  => !empty( $quanto_blog_section_custom_title ) ? esc_html( $quanto_blog_section_custom_title) : esc_html__( 'Blog', 'quanto' ),

                                            'class' => 'title'

                                        )

                                    );

                                echo '</div>';

                            echo '</div>';

                        echo '</div>';

                    echo '</div>';

                echo '</section>';

            }

        }

    }



    // Blog Start Wrapper Function

    if( !function_exists('quanto_blog_start_wrap_cb') ) {

        function quanto_blog_start_wrap_cb() {

            echo '<section class="quanto-blog-section section-padding-top section-padding-bottom overflow-hidden">';

                echo '<div class="container custom-container">';

                    if( is_active_sidebar( 'quanto-blog-sidebar' ) ){

                        $quanto_gutter_class = 'gx-30';

                    }else{

                        $quanto_gutter_class = '';

                    }

                    echo '<div class="row '.esc_attr( $quanto_gutter_class ).'">';

        }

    }



    // Blog End Wrapper Function

    if( !function_exists('quanto_blog_end_wrap_cb') ) {

        function quanto_blog_end_wrap_cb() {

                    echo '</div>';

                echo '</div>';

            echo '</section>';

        }

    }



    // Blog Column Start Wrapper Function

    if( !function_exists('quanto_blog_col_start_wrap_cb') ) {

        function quanto_blog_col_start_wrap_cb() {

            if( class_exists('ReduxFramework') ) {

                $quanto_blog_sidebar = quanto_opt('quanto_blog_sidebar');

                if( $quanto_blog_sidebar == '2' && is_active_sidebar('quanto-blog-sidebar') ) {

                    echo '<div class="col-lg-8 order-lg-last">';

                } elseif( $quanto_blog_sidebar == '3' && is_active_sidebar('quanto-blog-sidebar') ) {

                    echo '<div class="col-lg-8">';

                } else {

                    echo '<div class="col-lg-12">';

                }



            } else {

                if( is_active_sidebar('quanto-blog-sidebar') ) {

                    echo '<div class="col-lg-8">';

                } else {

                    echo '<div class="col-lg-12">';

                }

            }

        }

    }

    // Blog Column End Wrapper Function

    if( !function_exists('quanto_blog_col_end_wrap_cb') ) {

        function quanto_blog_col_end_wrap_cb() {

            echo '</div>';

        }

    }



    // Blog Sidebar

    if( !function_exists('quanto_blog_sidebar_cb') ) {

        function quanto_blog_sidebar_cb( ) {

            if( class_exists('ReduxFramework') ) {

                $quanto_blog_sidebar = quanto_opt('quanto_blog_sidebar');

            } else {

                $quanto_blog_sidebar = 2;

            }

            if( $quanto_blog_sidebar != 1 && is_active_sidebar('quanto-blog-sidebar') ) {

                // Sidebar

                get_sidebar();

            }

        }

    }





    if( !function_exists('quanto_blog_details_sidebar_cb') ) {

        function quanto_blog_details_sidebar_cb( ) {

            if( class_exists('ReduxFramework') ) {

                $quanto_blog_single_sidebar = quanto_opt('quanto_blog_single_sidebar');

            } else {

                $quanto_blog_single_sidebar = 4;

            }

            if( $quanto_blog_single_sidebar != 1 ) {

                // Sidebar

                get_sidebar();

            }



        }

    }



    // Blog Pagination Function

    if( !function_exists('quanto_blog_pagination_cb') ) {

        function quanto_blog_pagination_cb( ) {

            get_template_part('templates/pagination');

        }

    }



    // Blog Content Function

    if( !function_exists('quanto_blog_content_cb') ) {

        function quanto_blog_content_cb( ) {

            if( class_exists('ReduxFramework') ) {

                $quanto_blog_grid = quanto_opt('quanto_blog_grid');

            } else {

                $quanto_blog_grid = '1';

            }



            if( $quanto_blog_grid == '1' ) {

                $quanto_blog_grid_class = 'col-lg-12';

            } elseif( $quanto_blog_grid == '2' ) {

                $quanto_blog_grid_class = 'col-md-6';

            } else {

                $quanto_blog_grid_class = 'col-md-6 col-lg-4';

            }



            echo '<div class="row gx-4 gy-5">';

                if( have_posts() ) {

                    while( have_posts() ) {

                        the_post();

                        echo '<div class="'.esc_attr($quanto_blog_grid_class).'">';

                            if( class_exists( 'ReduxFramework' )){

                                $quanto_blog_style = quanto_opt('quanto_blog_style');



                                if('blog_style_one' == $quanto_blog_style ){

                                    echo '<div class="quanto-blog-box fade-anim" data-delay="0.30" data-direction="right">';

                                }elseif('blog_style_two' == $quanto_blog_style ){

                                    echo '<div class="quanto-blog-box style-2 fade-anim" data-delay="0.30" data-direction="right">';

                                }

                            }else{

                                echo '<div class="quanto-blog-box fade-anim" data-delay="0.30" data-direction="right">';

                            }

                                get_template_part('templates/content',get_post_format());

                            echo '</div>';

                        echo '</div>';

                    }

                    wp_reset_postdata();

                } else{

                    if( class_exists( 'ReduxFramework' )){

                        $quanto_blog_style = quanto_opt('quanto_blog_style');



                        if('blog_style_one' == $quanto_blog_style ){

                            echo '<div class="quanto-blog-box fade-anim" data-delay="0.30" data-direction="right">';

                        }elseif('blog_style_two' == $quanto_blog_style ){

                            echo '<div class="quanto-blog-box style-2 fade-anim" data-delay="0.30" data-direction="right">';

                        }

                    }else{

                        echo '<div class="quanto-blog-box fade-anim" data-delay="0.30" data-direction="right">';

                    }

                        get_template_part('templates/content','none');

                    echo '</div>';

                }

            echo '</div>';

        }

    }



    if ( ! function_exists( 'quanto_find_footer_post_by_slug' ) ) {

        function quanto_find_footer_post_by_slug() {

            foreach ( array( 'main', 'main-footer', 'main-fotter' ) as $slug ) {

                $posts = get_posts( array(

                    'name'        => $slug,

                    'post_type'   => 'quanto_footer',

                    'post_status' => 'publish',

                    'numberposts' => 1,

                ) );



                if ( ! empty( $posts ) ) {

                    return (int) $posts[0]->ID;

                }

            }



            return false;

        }

    }



    if ( ! function_exists( 'quanto_get_resolved_footer_id' ) ) {

        function quanto_get_resolved_footer_id() {

            if ( ! class_exists( '\\Elementor\\Plugin' ) ) {

                return false;

            }



            if ( ! class_exists( 'ReduxFramework' ) ) {

                $footer_id = quanto_find_footer_post_by_slug();

                if ( $footer_id ) {

                    return $footer_id;

                }

                return false;

            }



            if ( is_page() || is_page_template( 'template-builder.php' ) ) {

                $post_id               = get_queried_object_id();

                $footer_enable_disable = '';

                $footer_settings       = '';

                $footer_local          = '';



                if (

                    class_exists( '\\Elementor\\Core\\Settings\\Manager' ) &&

                    method_exists( '\\Elementor\\Core\\Settings\\Manager', 'get_settings_managers' )

                ) {

                    try {

                        $page_settings_manager = \Elementor\Core\Settings\Manager::get_settings_managers( 'page' );

                        if ( $page_settings_manager && method_exists( $page_settings_manager, 'get_model' ) ) {

                            $page_settings_model = $page_settings_manager->get_model( $post_id );

                            if ( $page_settings_model ) {

                                $footer_settings       = $page_settings_model->get_settings( 'quanto_footer_style' );

                                $footer_local          = $page_settings_model->get_settings( 'quanto_footer_builder_option' );

                                $footer_enable_disable = $page_settings_model->get_settings( 'quanto_footer_choice' );

                            }

                        }

                    } catch ( Exception $e ) {

                        return false;

                    }

                }



                if ( $footer_enable_disable === 'yes' ) {

                    if ( $footer_settings === 'footer_builder' && ! empty( $footer_local ) ) {

                        return (int) $footer_local;

                    }



                    if ( quanto_opt( 'quanto_footer_builder_trigger' ) === 'footer_builder' ) {

                        return (int) quanto_opt( 'quanto_footer_builder_select' );

                    }

                }

            }



            if ( is_archive() || is_home() || is_search() || is_singular() ) {

                $archive_id = quanto_opt( 'quanto_archive_footer_select_options' );

                if ( ! empty( $archive_id ) ) {

                    return (int) $archive_id;

                }

            }



            if ( quanto_opt( 'quanto_footer_builder_trigger' ) === 'footer_builder' ) {

                return (int) quanto_opt( 'quanto_footer_builder_select' );

            }



            $footer_id = quanto_find_footer_post_by_slug();

            if ( $footer_id ) {

                return $footer_id;

            }



            return false;

        }

    }



    if ( ! function_exists( 'quanto_enqueue_elementor_post_assets' ) ) {

        function quanto_enqueue_elementor_post_assets( $post_id ) {

            $post_id = (int) $post_id;

            if ( ! $post_id || ! class_exists( '\\Elementor\\Plugin' ) ) {

                return;

            }



            $frontend = \Elementor\Plugin::instance()->frontend;

            if ( $frontend && method_exists( $frontend, 'enqueue_styles' ) ) {

                $frontend->enqueue_styles();

            }



            $upload_dir = wp_upload_dir();

            if ( ! empty( $upload_dir['basedir'] ) && ! empty( $upload_dir['baseurl'] ) ) {

                $css_path = trailingslashit( $upload_dir['basedir'] ) . 'elementor/css/';

                $css_url  = trailingslashit( $upload_dir['baseurl'] ) . 'elementor/css/';

                $devices  = array( 'desktop', 'laptop', 'tablet', 'mobile' );



                $active_kit_id = (int) get_option( 'elementor_active_kit' );

                if ( $active_kit_id ) {

                    $kit_file = 'post-' . $active_kit_id . '.css';

                    if ( file_exists( $css_path . $kit_file ) ) {

                        $ver = filemtime( $css_path . $kit_file );

                        wp_enqueue_style( 'elementor-post-' . $active_kit_id, $css_url . $kit_file, array(), $ver );

                    }

                }



                $post_css_file = 'post-' . $post_id . '.css';

                if ( file_exists( $css_path . $post_css_file ) ) {

                    $ver = filemtime( $css_path . $post_css_file );

                    wp_enqueue_style( 'elementor-post-' . $post_id, $css_url . $post_css_file, array(), $ver );

                }



                foreach ( $devices as $device ) {

                    $base_file = 'base-' . $device . '.css';

                    if ( file_exists( $css_path . $base_file ) ) {

                        $ver = filemtime( $css_path . $base_file );

                        wp_enqueue_style( 'base-' . $device, $css_url . $base_file, array(), $ver );

                    }

                }



                foreach ( $devices as $device ) {

                    $local_file = 'local-' . $post_id . '-frontend-' . $device . '.css';

                    if ( file_exists( $css_path . $local_file ) ) {

                        $ver = filemtime( $css_path . $local_file );

                        wp_enqueue_style( 'local-' . $post_id . '-frontend-' . $device, $css_url . $local_file, array(), $ver );

                    }

                }

            }



            if ( class_exists( '\\Elementor\\Core\\Files\\CSS\\Post' ) ) {

                $css_file = new \Elementor\Core\Files\CSS\Post( $post_id );

                if ( ! file_exists( $css_file->get_path() ) ) {

                    $css_file->update();

                }

                $css_file->enqueue();

            }

        }

    }



    // Pre-generate missing Elementor CSS files BEFORE any output starts.

    // This runs on template_redirect (before wp_head) so CSS files exist

    // by the time wp_enqueue_scripts tries to enqueue them.

    // 

    // IMPORTANT: We use get_builder_content_for_display() instead of

    // $css_file->update() because update() generates EMPTY CSS for custom

    // document types like quanto_tab_build. The full rendering pipeline

    // is the only reliable way to generate CSS - this is exactly what

    // happens when you "view" the page in the browser.

    if ( ! function_exists( 'cmr_pregenerate_missing_elementor_css' ) ) {

        function cmr_pregenerate_missing_elementor_css() {

            if ( ! class_exists( '\\Elementor\\Core\\Files\\CSS\\Post' ) || ! class_exists( '\\Elementor\\Plugin' ) ) {

                return;

            }



            $post_ids_to_check = array();



            // Footer template

            if ( function_exists( 'quanto_get_resolved_footer_id' ) ) {

                $footer_id = quanto_get_resolved_footer_id();

                if ( $footer_id ) {

                    $post_ids_to_check[] = $footer_id;

                }

            }



            // All Quanto Builder templates - fetch ALL published templates dynamically

            $all_builder_posts = get_posts( array(

                'post_type'      => array( 'quanto_tab_build', 'quanto_footer', 'quanto_header', 'quanto_off_canvas', 'quanto_offcanvas', 'quanto_archive', 'quanto_builder', 'quanto_single' ),

                'posts_per_page' => -1,

                'post_status'    => 'publish',

            ) );

            if ( ! empty( $all_builder_posts ) ) {

                foreach ( $all_builder_posts as $bp ) {

                    $post_ids_to_check[] = $bp->ID;

                }

            }



            // Homepage

            $homepage_id = get_option( 'page_on_front' );

            if ( ! $homepage_id ) {

                $homepage_id = 14;

            }

            $post_ids_to_check[] = $homepage_id;



            // Similar reports page

            $target_page = get_page_by_path( 'similar-reports-by-industry' );

            if ( ! $target_page ) {

                $target_page = get_page_by_path( 'test' );

            }

            if ( $target_page ) {

                $post_ids_to_check[] = $target_page->ID;

            }



            // Check each and generate missing CSS files using FULL Elementor rendering

            global $post;

            $original_post = $post;

            

            foreach ( array_unique( $post_ids_to_check ) as $pid ) {

                $css_file = new \Elementor\Core\Files\CSS\Post( $pid );

                if ( ! file_exists( $css_file->get_path() ) ) {

                    // Use Elementor's full rendering pipeline to generate CSS.

                    // This is the SAME thing that happens when you "view" the page -

                    // it renders all widgets and generates the CSS as a side effect.

                    // We discard the HTML output; we only need the CSS file on disk.

                    \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $pid, true );

                }

            }

            

            // Restore global post state

            $post = $original_post;

            if ( $original_post ) {

                setup_postdata( $original_post );

            }

        }

    }

    add_action( 'template_redirect', 'cmr_pregenerate_missing_elementor_css', 1 );



    // Helper: read an Elementor post CSS file and return its content for inline output

    if ( ! function_exists( 'cmr_get_elementor_css_inline' ) ) {
        function cmr_get_elementor_css_inline( $post_id ) {
            $content = '';
            $upload_dir = wp_upload_dir();
            $elementor_css_dir = ! empty( $upload_dir['basedir'] ) ? trailingslashit( $upload_dir['basedir'] ) . 'elementor/css/' : '';

            // 1. Post CSS
            $post_css_path = ! empty( $elementor_css_dir ) ? $elementor_css_dir . 'post-' . intval( $post_id ) . '.css' : '';
            if ( function_exists( 'cmr_get_or_restore_css' ) ) {
                $post_content = cmr_get_or_restore_css( $post_css_path, 'cmr_css_backup_' . intval($post_id) . '_post', $post_id );
                if ( ! empty( $post_content ) ) {
                    $content .= $post_content . "\n";
                }
            } elseif ( ! empty( $post_css_path ) && file_exists( $post_css_path ) ) {
                $post_content = @file_get_contents( $post_css_path );
                if ( ! empty( $post_content ) ) {
                    $content .= $post_content . "\n";
                }
            }

            // 2. Local Responsive Device CSS
            if ( ! empty( $elementor_css_dir ) ) {
                $devices = array( 'desktop', 'mobile', 'tablet', 'laptop' );
                foreach ( $devices as $device ) {
                    $local_file = $elementor_css_dir . 'local-' . intval( $post_id ) . '-frontend-' . $device . '.css';
                    if ( function_exists( 'cmr_get_or_restore_css' ) ) {
                        $loc_content = cmr_get_or_restore_css( $local_file, 'cmr_css_backup_' . intval($post_id) . '_' . $device, $post_id, $device );
                        if ( ! empty( $loc_content ) ) {
                            $content .= $loc_content . "\n";
                        }
                    } elseif ( file_exists( $local_file ) ) {
                        $loc_content = @file_get_contents( $local_file );
                        if ( ! empty( $loc_content ) ) {
                            $content .= $loc_content . "\n";
                        }
                    }
                }
            }
            return $content;
        }
    }



    // Early footer CSS enqueue: runs during wp_enqueue_scripts so CSS lands in <head>

    if ( ! function_exists( 'quanto_enqueue_footer_css_early' ) ) {
        function quanto_enqueue_footer_css_early() {
            $footer_id = quanto_get_resolved_footer_id();
            if ( $footer_id ) {
                quanto_enqueue_elementor_post_assets( $footer_id );
            }

            // Also enqueue homepage CSS early only on front page or single posts
            // (where homepage tail sections 'who we serve' & 'testimonials' are rendered)
            if ( is_front_page() || is_home() || is_singular( 'post' ) ) {
                $homepage_id = get_option( 'page_on_front' );
                if ( ! $homepage_id ) {
                    $homepage_id = 14;
                }
                quanto_enqueue_elementor_post_assets( $homepage_id );
            }

            // Enqueue Similar Reports page assets early only on relevant single product / report pages
            if ( is_singular( array( 'product', 'cmr_media_release', 'cmr_quarterly' ) ) ) {
                $target_page = get_page_by_path( 'similar-reports-by-industry' );
                if ( ! $target_page ) {
                    $target_page = get_page_by_path( 'test' );
                }
                if ( $target_page ) {
                    quanto_enqueue_elementor_post_assets( $target_page->ID );
                }
            }
        }
    }

    // Inject inline CSS for footer directly into <head> as a bulletproof fallback.
    // This runs after wp_enqueue_scripts but before body, so even if the external CSS
    // file is 404'd by CDN/server cache, the inline styles guarantee correct rendering.
    if ( ! function_exists( 'cmr_inject_tab_css_inline_in_head' ) ) {
        function cmr_inject_tab_css_inline_in_head() {
            // Inject footer CSS inline
            if ( function_exists( 'quanto_get_resolved_footer_id' ) ) {
                $footer_id = quanto_get_resolved_footer_id();
                if ( $footer_id ) {
                    $css = cmr_get_elementor_css_inline( $footer_id );
                    if ( ! empty( $css ) ) {
                        echo '<style id="cmr-footer-' . esc_attr( $footer_id ) . '-inline-css">' . $css . '</style>' . "\n";
                    }
                }
            }
        }
    }

    add_action( 'wp_head', 'cmr_inject_tab_css_inline_in_head', 99 );



    if ( ! function_exists( 'quanto_render_elementor_footer' ) ) {

        function quanto_render_elementor_footer( $post_id, $class = 'footer' ) {

            // Enqueue elementor core assets if needed

            quanto_enqueue_elementor_post_assets( $post_id );

            

            // Generate the content FIRST so Elementor creates the CSS file on disk if missing.

            // If we try to print CSS before rendering, the CSS file won't exist yet!

            $content = '';

            if ( class_exists( '\\Elementor\\Plugin' ) ) {

                $content = \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $post_id, true );

            }

            

            echo '<footer class="' . esc_attr( $class ) . '">';

            

            // Force Elementor to print its CSS inline just in case

            if ( class_exists( '\\Elementor\\Core\\Files\\CSS\\Post' ) ) {

                $css_file = new \Elementor\Core\Files\CSS\Post( $post_id );

                

                // Read CSS from disk and output inline to prevent CDN caching issues

                $css_path = $css_file->get_path();

                if ( file_exists( $css_path ) ) {

                    $css_content = file_get_contents( $css_path );

                    if ( ! empty( $css_content ) ) {

                        echo '<style id="elementor-post-' . $post_id . '-inline-css">' . $css_content . '</style>';

                    }

                } else {

                    $css_file->enqueue();

                    $css_file->print_css();

                }

            }

            

            echo '<div data-elementor-type="wp-post" data-elementor-id="' . esc_attr( $post_id ) . '" class="elementor elementor-' . esc_attr( $post_id ) . '">';

            echo $content;

            echo '</div>';

            echo '</footer>';

        }

    }







    // footer content Function

    if( !function_exists('quanto_footer_content_cb') ) {

        function quanto_footer_content_cb( ) {

            $footer_id = quanto_get_resolved_footer_id();

            if ( $footer_id ) {

                quanto_render_elementor_footer( $footer_id );

            } else {

                quanto_footer_global_option();

            }

        }

    }



    // blog details wrapper start hook function

    if( !function_exists('quanto_blog_details_wrapper_start_cb') ) {

        function quanto_blog_details_wrapper_start_cb( ) {

            if( class_exists('ReduxFramework') ) {

                echo '<section class="blog-page-sec blog-detail-page section-padding-bottom">';

            } else {

                echo '<section class="blog-page-sec blog-detail-page local-blog-detail-page section-padding-bottom">';

            }

                echo '<div class="container custom-container">';

                    echo '<div class="row">';

        }

    }



    // blog details column wrapper start hook function

    if( !function_exists('quanto_blog_details_col_start_cb') ) {

        function quanto_blog_details_col_start_cb( ) {

            if( class_exists('ReduxFramework') ) {

                $quanto_blog_single_sidebar = quanto_opt('quanto_blog_single_sidebar');

                if( $quanto_blog_single_sidebar == '2' && is_active_sidebar('quanto-blog-sidebar') ) {

                    echo '<div class="col-lg-8 order-last">';

                } elseif( $quanto_blog_single_sidebar == '3' && is_active_sidebar('quanto-blog-sidebar') ) {

                    echo '<div class="col-lg-8">';

                } else {

                    echo '<div class="col-lg-12">';

                }



            } else {

                if( is_active_sidebar('quanto-blog-sidebar') ) {

                    echo '<div class="col-lg-8">';

                } else {

                    echo '<div class="col-lg-12">';

                }

            }

        }

    }





    // blog post meta hook function

    if( !function_exists('quanto_blog_post_meta_cb') ) {

        function quanto_blog_post_meta_cb( ) {

            if( class_exists('ReduxFramework') ) {

                $quanto_display_post_date      =  quanto_opt('quanto_display_post_date');

                $quanto_display_post_category   =  quanto_opt('quanto_display_post_category');



            } else {

                $quanto_display_post_date      = '1';

                $quanto_display_post_category   = '1';

            }



            echo '<!-- Blog Meta -->';

            echo '<div class="blog-meta">';



                if( $quanto_display_post_category ){

                    quanto_blog_category();

                }

                

                if( $quanto_display_post_date ){

                    echo '<span class="quanto-blog-date">';

                        echo esc_html( get_the_date() );

                    echo '</span>';

                }



            echo '</div>';



        }

    }



    

    // blog details post meta hook function

    if( !function_exists('quanto_blog_details_post_meta_cb') ) {

        function quanto_blog_details_post_meta_cb( ) {

            if ( function_exists( 'quanto_is_press_release' ) && quanto_is_press_release() ) {

                $post_id = get_the_ID();

                $reading_time = get_post_meta( $post_id, '_cmr_news_reading_time', true );

                if ( ! $reading_time ) {

                    $content = get_post_field( 'post_content', $post_id );

                    $word_count = str_word_count( strip_tags( $content ) );

                    $calc_time = max( 1, ceil( $word_count / 200 ) );

                    $reading_time = $calc_time . ' min read';

                }

                

                // Get taxonomy terms from category, cmr_news_category, and post tags
                $terms_news = get_the_terms( $post_id, 'cmr_news_category' );
                $terms_cat  = get_the_terms( $post_id, 'category' );
                $terms_tags = get_the_tags( $post_id );
                $all_terms  = array();

                if ( ! empty( $terms_news ) && ! is_wp_error( $terms_news ) ) {
                    foreach ( $terms_news as $t ) {
                        $all_terms[ $t->slug ] = $t;
                    }
                }
                if ( ! empty( $terms_cat ) && ! is_wp_error( $terms_cat ) ) {
                    foreach ( $terms_cat as $t ) {
                        $all_terms[ $t->slug ] = $t;
                    }
                }
                if ( ! empty( $terms_tags ) && ! is_wp_error( $terms_tags ) ) {
                    foreach ( $terms_tags as $t ) {
                        $all_terms[ $t->slug ] = $t;
                    }
                }

                $term_names = array();
                if ( ! empty( $all_terms ) ) {
                    foreach ( $all_terms as $term ) {
                        if ( count( $all_terms ) > 1 && in_array( strtolower( $term->slug ), array( 'uncategorized' ), true ) ) {
                            continue;
                        }
                        $clean_name = wp_specialchars_decode( $term->name );
                        if ( ! in_array( $clean_name, $term_names, true ) ) {
                            $term_names[] = $clean_name;
                        }
                    }
                    if ( empty( $term_names ) ) {
                        $term_names = wp_list_pluck( $all_terms, 'name' );
                    }
                }

                $tags_list = ! empty( $term_names ) ? implode( ', ', $term_names ) : esc_html__( 'General', 'quanto' );

                

                // Views count

                $views_count = function_exists( 'quanto_getPostViews' ) ? quanto_getPostViews( $post_id ) : '';

                if ( empty( $views_count ) || $views_count === '0' ) {

                    $views_count = get_post_meta( $post_id, 'quanto_post_views_count', true );

                    if ( empty( $views_count ) ) {

                        $views_count = '1024';

                    }

                }



                // Get document/external URL for download button

                $document_id = get_post_meta( $post_id, '_cmr_news_document_id', true );

                $download_url = $document_id ? wp_get_attachment_url( $document_id ) : '';

                if ( ! $download_url ) {

                    $download_url = get_post_meta( $post_id, '_cmr_news_external_link', true );

                }

                if ( ! $document_id && $download_url && function_exists('attachment_url_to_postid') ) {

                    $extracted_id = attachment_url_to_postid( $download_url );

                    if ( $extracted_id ) {

                        $document_id = $extracted_id;

                        $download_url = wp_get_attachment_url( $document_id );

                    }

                }



                $action_url = $download_url ? $download_url : '#';

                $target_attr = $download_url ? 'target="_blank" download' : 'onclick="window.print(); return false;"';



                // Render the CMR Media Meta Box

                echo '<!-- CMR News Custom Meta Box -->';

                echo '<div class="cmr-media-meta-box d-flex align-items-center justify-content-between">';

                    echo '<div class="cmr-meta-items d-flex align-items-center">';

                        

                        echo '<div class="cmr-meta-item">';

                            echo '<span class="cmr-meta-label">' . esc_html__( 'PUBLISHED', 'quanto' ) . '</span>';

                            echo '<span class="cmr-meta-value">' . esc_html( get_the_date( 'M d, Y' ) ) . '</span>';

                        echo '</div>';

                        

                        echo '<div class="cmr-meta-item">';

                            echo '<span class="cmr-meta-label">' . esc_html__( 'READING TIME', 'quanto' ) . '</span>';

                            echo '<span class="cmr-meta-value">' . esc_html( $reading_time ) . '</span>';

                        echo '</div>';

                        

                        echo '<div class="cmr-meta-item">';

                            echo '<span class="cmr-meta-label">' . esc_html__( 'INDUSTRY TAGS', 'quanto' ) . '</span>';

                            echo '<span class="cmr-meta-value">' . esc_html( $tags_list ) . '</span>';

                        echo '</div>';

                        

                        echo '<div class="cmr-meta-item">';

                            echo '<span class="cmr-meta-label">' . esc_html__( 'VIEWS', 'quanto' ) . '</span>';

                            echo '<span class="cmr-meta-value">' . esc_html( $views_count ) . '</span>';

                        echo '</div>';

                        

                    echo '</div>';

                    

                    echo '<div class="cmr-meta-actions d-flex align-items-center">';

                        // Audio listen button with Web Speech API

                        echo '<button type="button" class="cmr-btn-listen" id="cmr-listen-btn" title="' . esc_attr__( 'Listen to Press Release', 'quanto' ) . '" aria-label="' . esc_attr__( 'Listen to Press Release', 'quanto' ) . '">';

                            echo '<svg class="cmr-icon-play" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">';

                                echo '<path d="M8 5v14l11-7z" />';

                            echo '</svg>';

                            echo '<svg class="cmr-icon-pause" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" style="display: none;">';

                                echo '<path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z" />';

                            echo '</svg>';

                        echo '</button>';



                        // Download Press Release Button

                        echo '<a href="' . esc_url( $action_url ) . '" class="cmr-btn-download" ' . $target_attr . '>';

                            echo '<svg class="cmr-icon-pdf" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg" style="margin-right: 8px; vertical-align: middle;">';

                                echo '<path d="M12 16L16 12H13V4H11V12H8L12 16ZM20 18H4V20H20V18Z" />';

                            echo '</svg>';

                            echo esc_html__( 'Download Press Release', 'quanto' );

                        echo '</a>';

                        

                    echo '</div>';

                echo '</div>';

                

                // JavaScript for Listen / Audio reading

                echo '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    const listenBtn = document.getElementById("cmr-listen-btn");
                    if (!listenBtn) return;
                    
                    const playIcon = listenBtn.querySelector(".cmr-icon-play");
                    const pauseIcon = listenBtn.querySelector(".cmr-icon-pause");
                    
                    if (!("speechSynthesis" in window)) {
                        listenBtn.style.display = "none";
                        return;
                    }
                    
                    // Preload available voices
                    if (window.speechSynthesis.onvoiceschanged !== undefined) {
                        window.speechSynthesis.onvoiceschanged = function() {
                            window.speechSynthesis.getVoices();
                        };
                    }
                    window.speechSynthesis.getVoices();

                    function getFemaleVoice() {
                        const voices = window.speechSynthesis.getVoices();
                        if (!voices || !voices.length) return null;
                        
                        const femaleNames = [
                            "female", "zira", "samantha", "victoria", "karen", "jenny", 
                            "aria", "sonia", "neerja", "heera", "serena", "susan", 
                            "ava", "allison", "catherine", "hazel", "linda", "helena"
                        ];
                        
                        // 1. English voice with known female names (natural/neural preferred)
                        let matched = voices.find(function(v) {
                            const name = v.name.toLowerCase();
                            const isEng = v.lang && v.lang.toLowerCase().indexOf("en") === 0;
                            return isEng && femaleNames.some(function(fn) { return name.indexOf(fn) !== -1; });
                        });
                        
                        // 2. Any voice with "female" in name
                        if (!matched) {
                            matched = voices.find(function(v) {
                                return v.name.toLowerCase().indexOf("female") !== -1;
                            });
                        }
                        
                        // 3. Any English voice with female name
                        if (!matched) {
                            matched = voices.find(function(v) {
                                const name = v.name.toLowerCase();
                                return femaleNames.some(function(fn) { return name.indexOf(fn) !== -1; });
                            });
                        }

                        // 4. Fallback to any English voice
                        if (!matched) {
                            matched = voices.find(function(v) {
                                return v.lang && v.lang.toLowerCase().indexOf("en") === 0;
                            });
                        }
                        
                        return matched;
                    }
                    
                    let speaking = false;
                    
                    listenBtn.addEventListener("click", function(e) {
                        e.preventDefault();
                        if (window.speechSynthesis.speaking && speaking) {
                            if (window.speechSynthesis.paused) {
                                window.speechSynthesis.resume();
                                if (playIcon) playIcon.style.display = "none";
                                if (pauseIcon) pauseIcon.style.display = "inline-block";
                            } else {
                                window.speechSynthesis.pause();
                                if (playIcon) playIcon.style.display = "inline-block";
                                if (pauseIcon) pauseIcon.style.display = "none";
                            }
                        } else {
                            window.speechSynthesis.cancel();
                            const titleEl = document.querySelector(".blog-title h2");
                            const excerptEl = document.querySelector(".cmr-single-lead-excerpt");
                            const bodyEl = document.querySelector(".blog-body");
                            
                            let textToRead = "";
                            if (titleEl) textToRead += titleEl.innerText + ". ";
                            if (excerptEl) textToRead += excerptEl.innerText + ". ";
                            if (bodyEl) textToRead += bodyEl.innerText;
                            
                            if (!textToRead.trim()) return;
                            
                            const utterance = new SpeechSynthesisUtterance(textToRead);
                            const femaleVoice = getFemaleVoice();
                            if (femaleVoice) {
                                utterance.voice = femaleVoice;
                                utterance.lang = femaleVoice.lang || "en-US";
                            }
                            utterance.rate = 0.95;
                            utterance.pitch = 1.05; // Slightly elevated pitch for clear, natural female voice
                            
                            utterance.onstart = function() {
                                speaking = true;
                                if (playIcon) playIcon.style.display = "none";
                                if (pauseIcon) pauseIcon.style.display = "inline-block";
                            };
                            utterance.onend = function() {
                                speaking = false;
                                if (playIcon) playIcon.style.display = "inline-block";
                                if (pauseIcon) pauseIcon.style.display = "none";
                            };
                            utterance.onerror = function() {
                                speaking = false;
                                if (playIcon) playIcon.style.display = "inline-block";
                                if (pauseIcon) pauseIcon.style.display = "none";
                            };
                            window.speechSynthesis.speak(utterance);
                        }
                    });

                    // Stop speaking if user navigates away
                    window.addEventListener("beforeunload", function() {
                        if ("speechSynthesis" in window) {
                            window.speechSynthesis.cancel();
                        }
                    });
                });
                </script>';

                return;

            }



            if( class_exists('ReduxFramework') ) {

                $quanto_display_post_details_date      =  quanto_opt('quanto_display_post_details_date');

                $quanto_display_post_details_category   =  quanto_opt('quanto_display_post_details_category');

                $quanto_display_post_author      =  quanto_opt('quanto_display_post_author');



            } else {

                $quanto_display_post_details_date      = '1';

                $quanto_display_post_details_category   = '1';

                $quanto_display_post_author   = '1';

            }



            echo '<!-- Blog Meta -->';

            echo '<div class="meta-box">';

                echo '<ul class="custom-ul meta-info d-flex">';



                    if( $quanto_display_post_details_date ){

                        echo '<li><span><a href="'.esc_url( quanto_blog_date_permalink() ).'">';

                            echo esc_html( get_the_date( 'F d, Y' ) );

                        echo '</a></span></li>';



                    }

                    

                    if( $quanto_display_post_details_category ){

                        quanto_blog_category();

                    }



                    echo '<li><span><a href="' . esc_url( get_author_posts_url( get_the_author_meta('ID') ) ) . '">';

                        echo 'by ' . esc_html( ucwords( get_the_author() ) );

                    echo '</a></span></li>';



                echo '</ul>';

            echo '</div>';



        }

    }



    // Blog details share options helper function

    if( !function_exists('quanto_social_sharing_buttons') ) {

        function quanto_social_sharing_buttons() {

            $post_url   = rawurlencode( esc_url( get_permalink() ) );

            $post_title = rawurlencode( html_entity_decode( get_the_title(), ENT_COMPAT, 'UTF-8' ) );



            $output = '';

            // 1. LinkedIn

            $output .= '<li><a href="https://www.linkedin.com/sharing/share-offsite/?url=' . $post_url . '" target="_blank" rel="noopener noreferrer" title="Share on LinkedIn" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a></li>';

            // 2. Twitter / X

            $output .= '<li><a href="https://twitter.com/intent/tweet?url=' . $post_url . '&text=' . $post_title . '" target="_blank" rel="noopener noreferrer" title="Share on Twitter" aria-label="Twitter"><i class="fa-brands fa-x-twitter"></i></a></li>';

            // 3. Facebook

            $output .= '<li><a href="https://www.facebook.com/sharer/sharer.php?u=' . $post_url . '" target="_blank" rel="noopener noreferrer" title="Share on Facebook" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a></li>';

            // 4. WhatsApp

            $output .= '<li><a href="https://api.whatsapp.com/send?text=' . $post_title . '%20' . $post_url . '" target="_blank" rel="noopener noreferrer" title="Share on WhatsApp" aria-label="WhatsApp"><i class="fa-brands fa-whatsapp"></i></a></li>';



            return $output;

        }

    }



    // blog details share options hook function

    if( !function_exists('quanto_blog_details_share_options_cb') ) {

        function quanto_blog_details_share_options_cb( ) {

            if( class_exists('ReduxFramework') ) {

                $quanto_post_details_share_options = quanto_opt('quanto_post_details_share_options');

            } else {

                $quanto_post_details_share_options = true;

            }

            if( $quanto_post_details_share_options ) {

                $post_url   = rawurlencode( esc_url( get_permalink() ) );

                $post_title = rawurlencode( html_entity_decode( get_the_title(), ENT_COMPAT, 'UTF-8' ) );



                echo '<ul class="custom-ul">';

                    // 1. LinkedIn

                    echo '<li><a href="https://www.linkedin.com/sharing/share-offsite/?url=' . $post_url . '" target="_blank" rel="noopener noreferrer" title="Share on LinkedIn" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a></li>';

                    // 2. Twitter / X

                    echo '<li><a href="https://twitter.com/intent/tweet?url=' . $post_url . '&text=' . $post_title . '" target="_blank" rel="noopener noreferrer" title="Share on Twitter" aria-label="Twitter"><i class="fa-brands fa-x-twitter"></i></a></li>';

                    // 3. Facebook

                    echo '<li><a href="https://www.facebook.com/sharer/sharer.php?u=' . $post_url . '" target="_blank" rel="noopener noreferrer" title="Share on Facebook" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a></li>';

                    // 4. WhatsApp

                    echo '<li><a href="https://api.whatsapp.com/send?text=' . $post_title . '%20' . $post_url . '" target="_blank" rel="noopener noreferrer" title="Share on WhatsApp" aria-label="WhatsApp"><i class="fa-brands fa-whatsapp"></i></a></li>';

                echo '</ul>';

            }

        }

    }



    // Blog Details Comments hook function

    if( !function_exists('quanto_blog_details_comments_cb') ) {

        function quanto_blog_details_comments_cb( ) {

            // Disable comments and reviews on standard blog posts
            if ( get_post_type() === 'post' ) {
                return;
            }

            if ( ! comments_open() ) {

                echo '<div class="blog-comment-area">';

                    echo quanto_heading_tag( array(

                        "tag"   => "h3",

                        "text"  => esc_html__( 'Comments are closed', 'quanto' ),

                        "class" => "inner-title"

                    ) );

                echo '</div>';

            }



            // comment template.

            if ( comments_open() || get_comments_number() ) {

                comments_template();

            }

        }

    }



    // Blog Details Related Post hook function

    if( !function_exists('quanto_blog_details_related_post_cb') ) {

        function quanto_blog_details_related_post_cb( ) {

            if( class_exists('ReduxFramework') ) {

                $quanto_excerpt_length = '4';

                $quanto_post_details_related_post = quanto_opt('quanto_post_details_related_post');

            } else {

                $quanto_excerpt_length = '4';

                $quanto_post_details_related_post = false;

            }

            $post_type = get_post_type();

            $posts_per_page = '3';

            $post_not_in = array( get_the_ID() );

            

            if ( $post_type === 'cmr_news' ) {

                $terms = wp_get_post_terms( get_the_ID(), 'cmr_news_category', array( 'fields' => 'ids' ) );

                $tax_query = array();

                if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {

                    $tax_query = array(

                        array(

                            'taxonomy' => 'cmr_news_category',

                            'field'    => 'term_id',

                            'terms'    => $terms,

                        )

                    );

                }

                $args = array(

                    'post_type'      => 'cmr_news',

                    'posts_per_page' => $posts_per_page,

                    'post__not_in'   => $post_not_in,

                );

                if ( ! empty( $tax_query ) ) {

                    $args['tax_query'] = $tax_query;

                }

                $relatedpost = new WP_Query( $args );

                $section_title = esc_html__( 'Related Media Releases', 'quanto' );

                $quanto_post_details_related_post = true; // Always show for news items

            } else {
                $current_post_id  = get_the_ID();
                $all_current_cats = get_the_category( $current_post_id );
                $filtered_cat_ids = array();
                $excluded_slugs   = array( 'press-releases', 'press-release', 'media-releases', 'media-release', 'uncategorized', 'featured' );

                if ( ! empty( $all_current_cats ) && ! is_wp_error( $all_current_cats ) ) {
                    foreach ( $all_current_cats as $c ) {
                        if ( ! in_array( strtolower( $c->slug ), $excluded_slugs, true ) ) {
                            $filtered_cat_ids[] = $c->term_id;
                        }
                    }
                }

                if ( empty( $filtered_cat_ids ) ) {
                    $filtered_cat_ids = wp_get_post_categories( $current_post_id );
                }

                $relatedpost = new WP_Query( array(
                    "post_type"         => "post",
                    "posts_per_page"    => $posts_per_page,
                    "post__not_in"      => $post_not_in,
                    "tax_query"         => array(
                        array(
                            'taxonomy' => 'category',
                            'field'    => 'term_id',
                            'terms'    => $filtered_cat_ids,
                        ),
                    ),
                ) );

                $section_title = esc_html__( 'Related Articles', 'quanto' );

            }



            if( $relatedpost->have_posts() && $quanto_post_details_related_post ) {

                echo '<!-- Related Post -->';
                ?>
                <style>
                .cmr-rel-card {
                    background: #fff;
                    border: none;
                    overflow: hidden;
                    display: flex;
                    flex-direction: column;
                    height: 100%;
                    font-family: 'Instrument Sans', sans-serif;
                    border-radius: 0 !important;
                }
                .cmr-rel-card-img {
                    width: 100%;
                    aspect-ratio: 16/9;
                    overflow: hidden;
                    background: #f4f4f4;
                    margin-bottom: 18px;
                    border-radius: 0 !important;
                }
                .cmr-rel-card-img a {
                    display: block;
                    width: 100%;
                    height: 100%;
                    border-radius: 0 !important;
                }
                .cmr-rel-card-img img {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                    border-radius: 0 !important;
                    transition: transform 0.4s ease;
                    display: block;
                }
                .cmr-rel-card:hover .cmr-rel-card-img img {
                    transform: scale(1.04);
                }
                .cmr-rel-card-body {
                    display: flex;
                    flex-direction: column;
                    flex: 1;
                }
                .cmr-rel-card-meta {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    gap: 12px;
                    margin-bottom: 12px;
                    font-size: 14px;
                    color: #475569;
                    font-weight: 500;
                }
                .cmr-rel-card-meta-left {
                    display: inline-flex;
                    align-items: center;
                    gap: 6px;
                    min-width: 0;
                }
                .cmr-rel-card-meta-dash {
                    color: #475569;
                    font-weight: 600;
                    flex-shrink: 0;
                }
                .cmr-rel-card-tag {
                    font-size: 14px;
                    font-weight: 600;
                    color: #475569;
                    white-space: nowrap;
                    overflow: hidden;
                    text-overflow: ellipsis;
                }
                .cmr-rel-card-read-time {
                    font-size: 14px;
                    color: #64748b;
                    margin-left: auto;
                    white-space: nowrap;
                    flex-shrink: 0;
                }
                .cmr-rel-card-title {
                    font-size: 20px;
                    font-weight: 600;
                    line-height: 1.35;
                    letter-spacing: -0.3px;
                    display: -webkit-box;
                    -webkit-line-clamp: 2;
                    -webkit-box-orient: vertical;
                    margin: 0 0 14px;
                    overflow: hidden;
                }
                .cmr-rel-card-title a {
                    text-decoration: none;
                    color: #111 !important;
                    transition: color 0.3s ease !important;
                }
                .cmr-rel-card:hover .cmr-rel-card-title a,
                .cmr-rel-card-title a:hover {
                    color: #5A28D7 !important;
                }
                .cmr-rel-card-excerpt {
                    font-size: 14.5px;
                    line-height: 1.55;
                    color: #4B5563;
                    margin: 0 0 22px;
                    display: -webkit-box;
                    -webkit-line-clamp: 2;
                    -webkit-box-orient: vertical;
                    overflow: hidden;
                }
                .cmr-rel-read-more {
                    display: inline-flex;
                    align-items: center;
                    gap: 6px;
                    font-size: 15px;
                    font-weight: 700;
                    color: #111 !important;
                    text-decoration: none !important;
                    border-bottom: 1.5px solid #111 !important;
                    padding-bottom: 2px;
                    width: fit-content;
                    margin-top: auto;
                    transition: all 0.2s ease;
                }
                .cmr-rel-read-more svg {
                    width: 14px;
                    height: 14px;
                    transition: transform 0.2s ease;
                }
                .cmr-rel-read-more:hover {
                    color: #5A28D7 !important;
                    border-color: #5A28D7 !important;
                    gap: 9px !important;
                }
                .cmr-rel-read-more:hover svg {
                    transform: translate(2px, -2px);
                }
                </style>
                <?php

                echo '<div class="quanto-blog-section section-padding-bottom overflow-hidden">';

                    echo '<div class="container custom-container">';

                        echo '<div class="row">';

                            echo '<div class="col-12">';

                                echo '<div class="quanto__header text-center text-md-start row-padding-bottom">';

                                    echo '<h3 class="title fade-anim" data-delay="0.30" data-direction="left">' . $section_title . '</h3>';

                                echo '</div>';

                            echo '</div>';

                        echo '</div>';



                        echo '<div class="row gx-4 gy-5">';

                            while( $relatedpost->have_posts() ) {

                                $relatedpost->the_post();

                                if ( $post_type === 'cmr_news' ) {
                                    $related_id   = get_the_ID();
                                    $rel_thumb    = get_the_post_thumbnail_url( $related_id, 'large' );
                                    $rel_title    = get_the_title();
                                    $document_id  = get_post_meta( $related_id, '_cmr_news_document_id', true );
                                    $ext_url      = get_post_meta( $related_id, '_cmr_news_external_link', true );
                                    if ( $document_id ) {
                                        $rel_link   = wp_get_attachment_url( $document_id );
                                        $rel_target = '_blank';
                                    } elseif ( $ext_url ) {
                                        $rel_link   = $ext_url;
                                        $rel_target = '_blank';
                                    } else {
                                        $rel_link   = get_permalink( $related_id );
                                        $rel_target = '_self';
                                    }
                                    $news_terms = wp_get_post_terms( $related_id, 'cmr_news_category' );
                                    $single_cat_name = 'CMR In News';
                                    if ( ! empty( $news_terms ) && ! is_wp_error( $news_terms ) ) {
                                        $single_cat_name = $news_terms[0]->name;
                                    }
                                    $reading_time = get_post_meta( $related_id, '_cmr_news_reading_time', true );
                                    if ( ! empty( $reading_time ) ) {
                                        $rel_read_time = is_numeric( $reading_time ) ? $reading_time . ' min read' : $reading_time;
                                    } else {
                                        $rel_content   = get_post_field( 'post_content', $related_id );
                                        $rel_words     = str_word_count( strip_tags( $rel_content ) );
                                        $rel_read_time = max( 1, ceil( $rel_words / 200 ) ) . ' min read';
                                    }
                                    $rel_excerpt = get_the_excerpt( $related_id );
                                    if ( empty( $rel_excerpt ) ) {
                                        $rel_excerpt = get_post_field( 'post_content', $related_id );
                                    }
                                    $rel_excerpt = wp_trim_words( wp_strip_all_tags( $rel_excerpt ), 18, '...' );
                                } else {
                                    $rel_id        = get_the_ID();
                                    $rel_thumb     = get_the_post_thumbnail_url( $rel_id, 'large' );
                                    $rel_title     = get_the_title();
                                    $rel_link      = get_permalink( $rel_id );
                                    $rel_target    = '_self';
                                    $rel_content   = get_post_field( 'post_content', $rel_id );
                                    $rel_words     = str_word_count( strip_tags( $rel_content ) );
                                    $rel_read_time = max( 1, ceil( $rel_words / 200 ) ) . ' min read';

                                    // Pick single relevant industry category
                                    $rel_cats        = get_the_category( $rel_id );
                                    $single_cat_name = '';
                                    if ( ! empty( $rel_cats ) && ! is_wp_error( $rel_cats ) ) {
                                        // 1. Try to match the current post's filtered categories
                                        if ( ! empty( $filtered_cat_ids ) ) {
                                            foreach ( $rel_cats as $rc ) {
                                                if ( in_array( $rc->term_id, $filtered_cat_ids, true ) ) {
                                                    $single_cat_name = $rc->name;
                                                    break;
                                                }
                                            }
                                        }
                                        // 2. Otherwise pick the first non-generic category
                                        if ( empty( $single_cat_name ) ) {
                                            foreach ( $rel_cats as $rc ) {
                                                if ( ! in_array( strtolower( $rc->slug ), array( 'press-releases', 'press-release', 'media-releases', 'media-release', 'uncategorized', 'featured' ), true ) ) {
                                                    $single_cat_name = $rc->name;
                                                    break;
                                                }
                                            }
                                        }
                                        // 3. Fallback to first category
                                        if ( empty( $single_cat_name ) ) {
                                            $single_cat_name = $rel_cats[0]->name;
                                        }
                                    }
                                    if ( empty( $single_cat_name ) ) {
                                        $single_cat_name = 'Industry Intelligence';
                                    }

                                    $rel_excerpt = get_the_excerpt( $rel_id );
                                    if ( empty( $rel_excerpt ) ) {
                                        $rel_excerpt = $rel_content;
                                    }
                                    $rel_excerpt = wp_trim_words( wp_strip_all_tags( $rel_excerpt ), 18, '...' );
                                }
                                ?>
                                <div class="col-md-6 col-lg-4">
                                    <article class="cmr-rel-card">
                                        <div class="cmr-rel-card-img">
                                            <a href="<?php echo esc_url( $rel_link ); ?>" <?php echo ( $rel_target === '_blank' ) ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>>
                                                <img src="<?php echo esc_url( $rel_thumb ?: 'https://via.placeholder.com/800x450?text=No+Image' ); ?>" alt="<?php echo esc_attr( $rel_title ); ?>" loading="lazy" />
                                            </a>
                                        </div>
                                        <div class="cmr-rel-card-body">
                                            <div class="cmr-rel-card-meta">
                                                <div class="cmr-rel-card-meta-left">
                                                    <span class="cmr-rel-card-meta-dash">—</span>
                                                    <span class="cmr-rel-card-tag"><?php echo esc_html( $single_cat_name ); ?></span>
                                                </div>
                                                <span class="cmr-rel-card-read-time"><?php echo esc_html( $rel_read_time ); ?></span>
                                            </div>
                                            <h3 class="cmr-rel-card-title">
                                                <a href="<?php echo esc_url( $rel_link ); ?>" <?php echo ( $rel_target === '_blank' ) ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>><?php echo esc_html( $rel_title ); ?></a>
                                            </h3>
                                            <?php if ( ! empty( $rel_excerpt ) ) : ?>
                                                <p class="cmr-rel-card-excerpt"><?php echo esc_html( $rel_excerpt ); ?></p>
                                            <?php endif; ?>
                                            <a href="<?php echo esc_url( $rel_link ); ?>" <?php echo ( $rel_target === '_blank' ) ? 'target="_blank" rel="noopener noreferrer"' : ''; ?> class="cmr-rel-read-more">
                                                More Details
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                    <line x1="7" y1="17" x2="17" y2="7"></line>
                                                    <polyline points="7 7 17 7 17 17"></polyline>
                                                </svg>
                                            </a>
                                        </div>
                                    </article>
                                </div>
                                <?php
                            }

                            wp_reset_postdata();

                        echo '</div>';

                    echo '</div>';

                echo '</div>';

                echo '<!-- End Related Post -->';

            }

        }

    }



    // Blog Details Column end hook function

    if( !function_exists('quanto_blog_details_col_end_cb') ) {

        function quanto_blog_details_col_end_cb( ) {

            echo '</div>';

        }

    }



    // Blog Details Wrapper end hook function

    if( !function_exists('quanto_blog_details_wrapper_end_cb') ) {

        function quanto_blog_details_wrapper_end_cb( ) {

                    echo '</div>';

                echo '</div>';

            echo '</section>';

        }

    }



    // page start wrapper hook function

    if( !function_exists('quanto_page_start_wrap_cb') ) {

        function quanto_page_start_wrap_cb( ) {

            if( is_page( 'cart' ) ){

                $section_class = "quanto-cart-wrapper blog-details";

            }elseif( is_page( 'checkout' ) ){

                $section_class = "quanto-checkout-wrapper blog-details";

            }else{

                $section_class = "quanto-page-section";

            }

            echo '<section class="'.esc_attr( $section_class ).'">';

                echo '<div class="container">';

                    echo '<div class="row">';

        }

    }



    // page wrapper end hook function

    if( !function_exists('quanto_page_end_wrap_cb') ) {

        function quanto_page_end_wrap_cb( ) {

                    echo '</div>';

                echo '</div>';

            echo '</section>';

        }

    }



    // page column wrapper start hook function

    if( !function_exists('quanto_page_col_start_wrap_cb') ) {

        function quanto_page_col_start_wrap_cb( ) {

            if( class_exists('ReduxFramework') ) {

                $quanto_page_sidebar = quanto_opt('quanto_page_sidebar');

            }else {

                $quanto_page_sidebar = '1';

            }

            if( $quanto_page_sidebar == '2' && is_active_sidebar('quanto-page-sidebar') ) {

                echo '<div class="col-lg-8 order-last">';

            } elseif( $quanto_page_sidebar == '3' && is_active_sidebar('quanto-page-sidebar') ) {

                echo '<div class="col-lg-8">';

            } else {

                echo '<div class="col-lg-12">';

            }



        }

    }



    // page column wrapper end hook function

    if( !function_exists('quanto_page_col_end_wrap_cb') ) {

        function quanto_page_col_end_wrap_cb( ) {

            echo '</div>';

        }

    }



    // page sidebar hook function

    if( !function_exists('quanto_page_sidebar_cb') ) {

        function quanto_page_sidebar_cb( ) {

            if( class_exists('ReduxFramework') ) {

                $quanto_page_sidebar = quanto_opt('quanto_page_sidebar');

            }else {

                $quanto_page_sidebar = '1';

            }



            if( class_exists('ReduxFramework') ) {

                $quanto_page_layoutopt = quanto_opt('quanto_page_layoutopt');

            }else {

                $quanto_page_layoutopt = '3';

            }



            if( $quanto_page_layoutopt == '1' && $quanto_page_sidebar != 1 ) {

                get_sidebar('page');

            } elseif( $quanto_page_layoutopt == '2' && $quanto_page_sidebar != 1 ) {

                get_sidebar();

            }

        }

    }



    // page content hook function

    if( !function_exists('quanto_page_content_cb') ) {

        function quanto_page_content_cb( ) {

            

            echo '<div class="page--content clearfix">';

                the_content();



                // Link Pages

                quanto_link_pages();



            echo '</div>';

            // comment template.

            if ( comments_open() || get_comments_number() ) {

                comments_template();

            }



        }

    }

    if( !function_exists('quanto_blog_post_thumb_cb') ) {

        function quanto_blog_post_thumb_cb( ) {

            if( get_post_format() ) {

                $format = get_post_format();

            }else{

                $format = 'standard';

            }



            $quanto_post_slider_thumbnail = quanto_meta( 'post_format_slider' );



            if( !empty( $quanto_post_slider_thumbnail ) ){

                if ( ! is_single() ) {

                    echo '<div class="quanto-blog-thumb quanto-carousel" data-arrows="true" data-slide-show="1" data-fade="true">';

                    foreach ( $quanto_post_slider_thumbnail as $single_image ) {

                        if( class_exists( 'ReduxFramework' )){

                            $quanto_blog_style = quanto_opt('quanto_blog_style');



                            if('blog_style_one' == $quanto_blog_style ){

                                echo '<a href="' . esc_url( get_permalink() ) . '" class="post-thumbnail">';

                            }elseif('blog_style_two' == $quanto_blog_style ){

                                echo '<a href="' . esc_url( get_permalink() ) . '" class="d-inline-block overflow-hidden">';

                            }

                        }else{

                            echo '<a href="' . esc_url( get_permalink() ) . '" class="post-thumbnail">';

                        }

                            echo quanto_img_tag( array(

                                'url' => esc_url( $single_image )

                            ) );

                        echo '</a>';

                    }

                    echo '</div>';

                } else {

                    echo '<div class="img-box overflow-hidden">';

                    foreach ( $quanto_post_slider_thumbnail as $single_image ) {

                        echo quanto_img_tag( array(

                            'url' => esc_url( $single_image )

                        ) );

                    }

                    echo '</div>';

                }

            }elseif( has_post_thumbnail() && $format == 'standard' ) {

                if( ! is_single() ){

                    echo '<div class="quanto-blog-thumb">';

                        if( class_exists( 'ReduxFramework' )){

                            $quanto_blog_style = quanto_opt('quanto_blog_style');



                            if('blog_style_one' == $quanto_blog_style ){

                                echo '<a href="' . esc_url( get_permalink() ) . '" class="post-thumbnail">';

                            }elseif('blog_style_two' == $quanto_blog_style ){

                                echo '<a href="' . esc_url( get_permalink() ) . '" class="d-inline-block overflow-hidden">';

                            }

                        }else{

                            echo '<a href="' . esc_url( get_permalink() ) . '" class="post-thumbnail">';

                        }

                            the_post_thumbnail();

                        echo '</a>';

                    echo '</div>';

                } else {

                    echo '<div class="img-box overflow-hidden">';

                        the_post_thumbnail( 'full', array(

                            'class' => 'w-100 d-block',

                            'alt'   => get_the_title(),

                            'data-speed' => '0.8',

                        ) );

                    echo '</div>';

                }

            }elseif( $format == 'video' ){

                if( has_post_thumbnail() && !empty ( quanto_meta( 'post_format_video' ) ) ){

                    if( ! is_single() ){

                        echo '<div class="blog-video quanto-blog-thumb">';

                            if( class_exists( 'ReduxFramework' )){

                                $quanto_blog_style = quanto_opt('quanto_blog_style');



                                if('blog_style_one' == $quanto_blog_style ){

                                    echo '<a href="' . esc_url( get_permalink() ) . '" class="post-thumbnail">';

                                }elseif('blog_style_two' == $quanto_blog_style ){

                                    echo '<a href="' . esc_url( get_permalink() ) . '" class="d-inline-block overflow-hidden">';

                                }

                            }else{

                                echo '<a href="' . esc_url( get_permalink() ) . '" class="post-thumbnail">';

                            }

                                the_post_thumbnail();

                            echo '</a>';

                            echo '<a href="'.esc_url( quanto_meta( 'post_format_video' ) ).'" class="play-btn popup-video">';

                            echo '<i class="fas fa-play"></i>';

                            echo '</a>';

                        echo '</div>';

                    } else {

                        echo '<div class="img-box overflow-hidden">';

                            the_post_thumbnail( 'full', array(

                                'class' => 'w-100 d-block',

                                'alt'   => get_the_title(),

                                'data-speed' => '0.8',

                            ) );

                            echo '<a href="'.esc_url( quanto_meta( 'post_format_video' ) ).'" class="play-btn popup-video">';

                                echo '<i class="fas fa-play"></i>';

                            echo '</a>';

                        echo '</div>';

                    }



                }elseif( ! has_post_thumbnail() && ! is_single() ){

                    echo '<div class="blog-video">';

                        if( ! is_single() ){

                            if( class_exists( 'ReduxFramework' )){

                                $quanto_blog_style = quanto_opt('quanto_blog_style');



                                if('blog_style_one' == $quanto_blog_style ){

                                    echo '<a href="' . esc_url( get_permalink() ) . '" class="post-thumbnail">';

                                }elseif('blog_style_two' == $quanto_blog_style ){

                                    echo '<a href="' . esc_url( get_permalink() ) . '" class="d-inline-block overflow-hidden">';

                                }

                            }else{

                                echo '<a href="' . esc_url( get_permalink() ) . '" class="post-thumbnail">';

                            }

                        }

                            echo quanto_embedded_media( array( 'video', 'iframe' ) );

                        if( ! is_single() ){

                            echo '</a>';

                        }

                       

                    echo '</div>';

                }

            }elseif( $format == 'audio' ){

                $quanto_audio = quanto_meta( 'post_format_audio' );

                if( !empty( $quanto_audio ) ){

                    echo '<div class="blog-audio blog-image">';

                            echo wp_oembed_get( $quanto_audio );

                           

                    echo '</div>';

                }elseif( !is_single() ){

                    echo '<div class="blog-audio blog-image">';

                            echo quanto_embedded_media( array( 'audio', 'iframe' ) );

                           

                    echo '</div>';

                }

            } else {

                if ( ! is_single() ) {

                    echo '<div class="quanto-blog-thumb">';

                    echo '<a href="' . esc_url( get_permalink() ) . '" class="post-thumbnail" style="display: block; width: 100%; height: 100%;">';

                    echo '<div class="cmr-skeleton-image" style="width: 100%; min-height: 250px; height: 100%; border-radius: 8px; overflow: hidden; display: block;">';

                    echo '<svg viewBox="0 0 400 250" preserveAspectRatio="none" style="width: 100%; height: 100%; display: block; background: #ced6dc;">';

                    echo '<circle cx="140" cy="110" r="25" fill="#f1f4f5"/>';

                    echo '<path d="M -20 250 Q 60 120 160 250 Q 280 20 420 250 Z" fill="#f1f4f5"/>';

                    echo '</svg>';

                    echo '</div>';

                    echo '</a></div>';

                }

            }



        }

    }



    if( !function_exists( 'quanto_blog_post_content_cb' ) ) {

        function quanto_blog_post_content_cb( ) {

            $allowhtml = array(

                'p'         => array(

                    'class'     => array()

                ),

                'span'      => array(),

                'a'         => array(

                    'href'      => array(),

                    'title'     => array()

                ),

                'br'        => array(),

                'em'        => array(),

                'strong'    => array(),

                'b'         => array(),

                'sup'       => array(),

                'sub'       => array(),

            );

            echo '<!-- blog-content -->';



            echo '<div class="quanto-blog-content">';



                if( class_exists( 'ReduxFramework' )){

                    $quanto_blog_style = quanto_opt('quanto_blog_style');



                    if('blog_style_one' == $quanto_blog_style ){

                        // Blog Post Meta

                        do_action( 'quanto_blog_post_meta' );



                        if( ! is_single() ){

                            echo '<h5 class="line-clamp-2"><a href="'.esc_url( get_permalink() ).'">'.wp_kses( get_the_title(), $allowhtml ).'</a></h5>';

                        }

                    }elseif('blog_style_two' == $quanto_blog_style ){

                        // Blog Post Meta

                        do_action( 'quanto_blog_post_meta' );



                        if( ! is_single() ){

                            echo '<h5 class="line-clamp-3"><a href="'.esc_url( get_permalink() ).'">'.wp_kses( get_the_title(), $allowhtml ).'</a></h5>';

                        }

                    }

                }else{

                    // Blog Post Meta

                    do_action( 'quanto_blog_post_meta' );



                    if( ! is_single() ){

                        echo '<h5 class="line-clamp-2"><a href="'.esc_url( get_permalink() ).'">'.wp_kses( get_the_title(), $allowhtml ).'</a></h5>';

                    }

                }



                // Excerpt And Read More Button

                do_action( 'quanto_blog_postexcerpt_read_content' );



            echo '</div>';

            echo '<!-- End Post Content -->';

        }

    }



    if( ! function_exists( 'quanto_blog_postexcerpt_read_content_cb') ) {

        function quanto_blog_postexcerpt_read_content_cb( ) {

            if( class_exists( 'ReduxFramework' ) ) {

                $quanto_excerpt_length = quanto_opt('quanto_blog_postExcerpt');

            } else {

                $quanto_excerpt_length = '24';

            }

            $allowhtml = array(

                'p'         => array(

                    'class'     => array()

                ),

                'span'      => array(),

                'a'         => array(

                    'href'      => array(),

                    'title'     => array()

                ),

                'br'        => array(),

                'em'        => array(),

                'strong'    => array(),

                'b'         => array(),

            );



            if( class_exists( 'ReduxFramework' ) ) {

                $quanto_blog_admin = quanto_opt( 'quanto_blog_post_author' );

                $quanto_blog_readmore_setting_val = quanto_opt('quanto_blog_readmore_setting');

                if( $quanto_blog_readmore_setting_val == 'custom' ) {

                    $quanto_blog_readmore_setting = quanto_opt('quanto_blog_custom_readmore');

                } else {

                    $quanto_blog_readmore_setting = __( 'Read More', 'quanto' );

                }

            } else {

                $quanto_blog_readmore_setting = __( 'Read More', 'quanto' );

                $quanto_blog_admin = true;

            }



            echo '<!-- Post Summary -->';

                echo quanto_paragraph_tag( array(

                    "text"  => wp_kses( wp_trim_words( get_the_excerpt(), $quanto_excerpt_length, '' ), $allowhtml ),

                    "class" => 'blog-text',

                ) );

            echo '<!-- End Post Summary -->';

            



            if( $quanto_blog_admin || !empty( $quanto_blog_readmore_setting ) ){

                if( !empty( $quanto_blog_readmore_setting ) ){

                    echo '<!-- Button -->';

                        echo '<div class="related-article-more-details" style="margin-top:15px;">';

                        echo '<a href="'.esc_url( get_permalink() ).'" class="custom-related-details-btn">';

                        echo esc_html( $quanto_blog_readmore_setting );

                        echo ' <svg class="arrow-up-right" viewBox="0 0 15 15" width="14" height="14" fill="none" xmlns="http://www.w3.org/2000/svg" style="display: inline-block; vertical-align: middle; margin-left: 4px;"><path d="M2.04895 4.52205V3.54978C2.07104 3.12993 2.4025 2.79848 2.80025 2.79848L11.396 2.77638C11.8159 2.79848 12.1473 3.12993 12.1473 3.52768V12.1455C12.1473 12.5433 11.8159 12.8747 11.396 12.8968H10.4237C10.0039 12.8747 9.67244 12.5433 9.65035 12.1234L9.78293 6.90853L3.44106 13.2504C3.1317 13.5598 2.68976 13.5598 2.3804 13.2504L1.6733 12.5433C1.38603 12.256 1.36394 11.792 1.6733 11.4826L8.01516 5.14077L2.82234 5.29545C2.4025 5.27335 2.04895 4.96399 2.04895 4.52205Z" fill="currentColor"></path></svg>';

                        echo '</a>';

                        echo '</div>';

                    echo '<!-- End Button -->';

                }

            }









        }

    }





    add_action( 'quanto_before_content', function () {
        if ( function_exists( 'is_cart' ) && ( is_cart() || is_checkout() || is_account_page() ) ) {
            return;
        }
        echo '<div id="smooth-wrapper"><div id="smooth-content">';

    } );

    add_action( 'quanto_after_content', function () {
        if ( function_exists( 'is_cart' ) && ( is_cart() || is_checkout() || is_account_page() ) ) {
            return;
        }
        echo '</div></div>';

    } );



    if ( ! function_exists( 'quanto_elementor_tree_contains_id' ) ) {

        function quanto_elementor_tree_contains_id( $element, $target_id ) {

            if ( ! is_array( $element ) ) {

                return false;

            }



            if ( isset( $element['id'] ) && $element['id'] === $target_id ) {

                return true;

            }



            if ( empty( $element['elements'] ) || ! is_array( $element['elements'] ) ) {

                return false;

            }



            foreach ( $element['elements'] as $child_element ) {

                if ( quanto_elementor_tree_contains_id( $child_element, $target_id ) ) {

                    return true;

                }

            }



            return false;

        }

    }



    if ( ! function_exists( 'quanto_find_elementor_top_level_element' ) ) {

        function quanto_find_elementor_top_level_element( $elements, $target_id ) {

            if ( ! is_array( $elements ) ) {

                return null;

            }



            foreach ( $elements as $element ) {

                if ( quanto_elementor_tree_contains_id( $element, $target_id ) ) {

                    return $element;

                }

            }



            return null;

        }

    }



    if ( ! function_exists( 'quanto_get_homepage_elementor_data' ) ) {

        function quanto_get_homepage_elementor_data( &$homepage_id = null ) {

            $homepage_id = get_option( 'page_on_front' );

            if ( ! $homepage_id ) {

                $homepage_id = 14; // Fallback

            }



            if ( function_exists( 'quanto_enqueue_elementor_post_assets' ) ) {

                quanto_enqueue_elementor_post_assets( $homepage_id );

            } elseif ( class_exists( '\\Elementor\\Core\\Files\\CSS\\Post' ) ) {

                $css_file = new \Elementor\Core\Files\CSS\Post( $homepage_id );

                $css_file->enqueue();

            }



            $meta = get_post_meta( $homepage_id, '_elementor_data', true );

            if ( ! $meta ) {

                return null;

            }



            $data = json_decode( $meta, true );

            return is_array( $data ) ? array_values( $data ) : null;

        }

    }



    if ( ! function_exists( 'quanto_render_homepage_section_from_end' ) ) {

        function quanto_render_homepage_section_from_end( $offset_from_end ) {

            if ( ! class_exists( '\\Elementor\\Plugin' ) ) {

                return false;

            }



            $homepage_id = null;

            $data        = quanto_get_homepage_elementor_data( $homepage_id );

            if ( empty( $data ) ) {

                return false;

            }



            $index = count( $data ) - absint( $offset_from_end );

            if ( ! isset( $data[ $index ] ) ) {

                return false;

            }



            $element_instance = \Elementor\Plugin::instance()->elements_manager->create_element_instance( $data[ $index ] );

            if ( ! $element_instance ) {

                return false;

            }



            echo '<div data-elementor-type="wp-page" data-elementor-id="' . esc_attr( $homepage_id ) . '" class="elementor elementor-' . esc_attr( $homepage_id ) . '">';

            $element_instance->print_element();

            echo '</div>';



            return true;

        }

    }



    if ( ! function_exists( 'quanto_filter_elementor_section_siblings' ) ) {

        function quanto_filter_elementor_section_siblings( $elements ) {

            if ( ! is_array( $elements ) ) {

                return array();

            }



            return array_values( array_filter( $elements, function( $element ) {

                return is_array( $element )

                    && ! empty( $element['elType'] )

                    && in_array( $element['elType'], array( 'section', 'container', 'e-flexbox' ), true );

            } ) );

        }

    }



    if ( ! function_exists( 'quanto_find_elementor_section_group' ) ) {

        function quanto_find_elementor_section_group( $elements, $minimum_count = 3 ) {

            $section_siblings = quanto_filter_elementor_section_siblings( $elements );

            if ( count( $section_siblings ) >= $minimum_count ) {

                return $section_siblings;

            }



            if ( ! is_array( $elements ) ) {

                return array();

            }



            foreach ( $elements as $element ) {

                if ( empty( $element['elements'] ) || ! is_array( $element['elements'] ) ) {

                    continue;

                }



                $found = quanto_find_elementor_section_group( $element['elements'], $minimum_count );

                if ( ! empty( $found ) ) {

                    return $found;

                }

            }



            return array();

        }

    }



    if ( ! function_exists( 'quanto_print_homepage_tail_inline_styles' ) ) {

        function quanto_print_homepage_tail_inline_styles() {

            static $printed = false;



            if ( $printed ) {

                return;

            }



            echo '<style id="quanto-homepage-tail-inline-css">

                .quanto-homepage-tail-sections{width:100%;overflow:hidden}

                .quanto-homepage-tail-sections>.elementor{width:100%}

                .quanto-homepage-tail-sections .elementor-section .elementor-container{display:flex;margin-left:auto;margin-right:auto;max-width:min(100%,var(--content-width,1320px));width:100%}

                .quanto-homepage-tail-sections .elementor-column{display:flex;min-height:1px;position:relative}

                .quanto-homepage-tail-sections .elementor-column-wrap,.quanto-homepage-tail-sections .elementor-widget-wrap{align-content:flex-start;display:flex;flex-wrap:wrap;position:relative;width:100%}

                .quanto-homepage-tail-sections .elementor-widget,.quanto-homepage-tail-sections .elementor-widget-container{max-width:100%}

                .quanto-homepage-tail-sections .elementor-heading-title{margin:0}

                @media (max-width:767px){.quanto-homepage-tail-sections .e-con,.quanto-homepage-tail-sections .e-con>.e-con-inner{flex-direction:var(--mobile-flex-direction,column)}}

            </style>';

            

            // Explicitly print the Elementor CSS for the homepage, otherwise the sections will lose their styling when rendered late on other pages.

            $homepage_id = get_option( 'page_on_front' );

            if ( ! $homepage_id ) {

                $homepage_id = 14; // Fallback

            }

            if ( class_exists( '\Elementor\Core\Files\CSS\Post' ) ) {

                $css_file = new \Elementor\Core\Files\CSS\Post( $homepage_id );

                $css_file->enqueue();

                $css_file->print_css();

            }

            

            $printed = true;

        }

    }



    if ( ! function_exists( 'quanto_render_homepage_tail_sections' ) ) {

        function quanto_render_homepage_tail_sections( $count = 3 ) {

            static $rendered = false;



            if ( $rendered || ! class_exists( '\\Elementor\\Plugin' ) ) {

                return $rendered;

            }



            $homepage_id = null;

            $data        = quanto_get_homepage_elementor_data( $homepage_id );



            if ( ! $homepage_id || empty( $data ) ) {

                return false;

            }



            $section_group = quanto_find_elementor_section_group( $data, absint( $count ) );

            $elements      = array_slice( $section_group, - absint( $count ) );

            if ( empty( $elements ) ) {

                return false;

            }



            quanto_print_homepage_tail_inline_styles();



            echo '<div class="quanto-homepage-tail-sections" style="--quanto-homepage-tail-count:' . esc_attr( absint( $count ) ) . ';">';



            echo '<div data-elementor-type="wp-page" data-elementor-id="' . esc_attr( $homepage_id ) . '" class="elementor elementor-' . esc_attr( $homepage_id ) . '">';



            foreach ( $elements as $element_data ) {

                $element_instance = \Elementor\Plugin::instance()->elements_manager->create_element_instance( $element_data );

                if ( $element_instance ) {

                    $element_instance->print_element();

                }

            }



            echo '</div>';

            echo '</div>';



            $rendered = true;

            return true;

        }

    }



    if ( ! function_exists( 'quanto_render_homepage_sections_by_keyword' ) ) {

        function quanto_render_homepage_sections_by_keyword( $keywords, $skip_last = 0 ) {

            if ( ! class_exists( '\\Elementor\\Plugin' ) ) {

                return false;

            }



            $homepage_id = null;

            $data        = quanto_get_homepage_elementor_data( $homepage_id );



            if ( ! $homepage_id || empty( $data ) ) {

                return false;

            }



            $section_group = quanto_find_elementor_section_group( $data, 1 );

            if ( empty( $section_group ) ) {

                return false;

            }



            // Exclude sections from the end if requested (e.g., to avoid duplicating the footer)

            if ( $skip_last > 0 ) {

                $section_group = array_slice( $section_group, 0, - absint( $skip_last ) );

            }



            $to_render = array();

            foreach ( $section_group as $section ) {

                $json = json_encode( $section );

                foreach ( (array) $keywords as $keyword ) {

                    if ( stripos( $json, $keyword ) !== false ) {

                        $to_render[] = $section;

                        break; // Move to next section once a keyword matches

                    }

                }

            }



            if ( empty( $to_render ) ) {

                return false;

            }



            quanto_print_homepage_tail_inline_styles();



            echo '<div class="quanto-homepage-tail-sections quanto-keyword-sections">';

            echo '<div data-elementor-type="wp-page" data-elementor-id="' . esc_attr( $homepage_id ) . '" class="elementor elementor-' . esc_attr( $homepage_id ) . '">';



            foreach ( $to_render as $element_data ) {

                $element_instance = \Elementor\Plugin::instance()->elements_manager->create_element_instance( $element_data );

                if ( $element_instance ) {

                    $element_instance->print_element();

                }

            }



            echo '</div>';

            echo '</div>';



            return true;

        }

    }



    if ( ! function_exists( 'quanto_render_homepage_who_we_serve_section' ) ) {

        function quanto_render_homepage_who_we_serve_section() {

            if ( ! class_exists( '\\Elementor\\Plugin' ) ) {

                return;

            }



            $homepage_id = get_option( 'page_on_front' );

            if ( ! $homepage_id ) {

                $homepage_id = 14; // Fallback

            }



            if ( function_exists( 'quanto_enqueue_elementor_post_assets' ) ) {

                quanto_enqueue_elementor_post_assets( $homepage_id );

            } elseif ( class_exists( '\\Elementor\\Core\\Files\\CSS\\Post' ) ) {

                $css_file = new \Elementor\Core\Files\CSS\Post( $homepage_id );

                $css_file->enqueue();

            }



            $meta = get_post_meta( $homepage_id, '_elementor_data', true );

            if ( ! $meta ) {

                return;

            }



            $data = json_decode( $meta, true );

            if ( ! is_array( $data ) ) {

                return;

            }



            // Helper function to find element recursively

            $find_element = null;

            $find_element = function( $elements, $id ) use ( &$find_element ) {

                foreach ( $elements as $element ) {

                    if ( isset( $element['id'] ) && $element['id'] === $id ) {

                        return $element;

                    }

                    if ( isset( $element['elements'] ) && ! empty( $element['elements'] ) ) {

                        $found = $find_element( $element['elements'], $id );

                        if ( $found ) {

                            return $found;

                        }

                    }

                }

                return null;

            };



            $element_data = $find_element( $data, '5e656d6' );

            if ( $element_data ) {

                $element_instance = \Elementor\Plugin::instance()->elements_manager->create_element_instance( $element_data );

                if ( $element_instance ) {

                    quanto_print_homepage_tail_inline_styles();

                    echo '<div data-elementor-type="wp-page" data-elementor-id="' . esc_attr( $homepage_id ) . '" class="elementor quanto-homepage-tail-sections elementor-' . esc_attr( $homepage_id ) . '">';

                    $element_instance->print_element();

                    echo '</div>';

                }

            }

        }

    }







    if ( ! function_exists( 'quanto_render_homepage_client_testimonials_section' ) ) {

        function quanto_render_homepage_client_testimonials_section() {

            if ( ! class_exists( '\\Elementor\\Plugin' ) ) {

                return;

            }



            $homepage_id = get_option( 'page_on_front' );

            if ( ! $homepage_id ) {

                $homepage_id = 14; // Fallback

            }



            if ( function_exists( 'quanto_enqueue_elementor_post_assets' ) ) {

                quanto_enqueue_elementor_post_assets( $homepage_id );

            } elseif ( class_exists( '\\Elementor\\Core\\Files\\CSS\\Post' ) ) {

                $css_file = new \Elementor\Core\Files\CSS\Post( $homepage_id );

                $css_file->enqueue();

            }



            $meta = get_post_meta( $homepage_id, '_elementor_data', true );

            if ( ! $meta ) {

                return;

            }



            $data = json_decode( $meta, true );

            if ( ! is_array( $data ) ) {

                return;

            }



            // Helper function to find element recursively

            $find_element = null;

            $find_element = function( $elements, $id ) use ( &$find_element ) {

                foreach ( $elements as $element ) {

                    if ( isset( $element['id'] ) && $element['id'] === $id ) {

                        return $element;

                    }

                    if ( isset( $element['elements'] ) && ! empty( $element['elements'] ) ) {

                        $found = $find_element( $element['elements'], $id );

                        if ( $found ) {

                            return $found;

                        }

                    }

                }

                return null;

            };



            $element_data = $find_element( $data, '82ef444' );

            if ( $element_data ) {

                $element_instance = \Elementor\Plugin::instance()->elements_manager->create_element_instance( $element_data );

                if ( $element_instance ) {

                    quanto_print_homepage_tail_inline_styles();

                    echo '<div data-elementor-type="wp-page" data-elementor-id="' . esc_attr( $homepage_id ) . '" class="elementor quanto-homepage-tail-sections elementor-' . esc_attr( $homepage_id ) . '">';

                    $element_instance->print_element();

                    echo '</div>';

                }

            }

        }

    }





    if ( ! function_exists( 'quanto_render_homepage_global_brands_section' ) ) {

        function quanto_render_homepage_global_brands_section() {

            if ( ! class_exists( '\\Elementor\\Plugin' ) ) {

                return;

            }



            $homepage_id = get_option( 'page_on_front' );

            if ( ! $homepage_id ) {

                $homepage_id = 14; // Fallback

            }



            if ( function_exists( 'quanto_enqueue_elementor_post_assets' ) ) {

                quanto_enqueue_elementor_post_assets( $homepage_id );

            } elseif ( class_exists( '\\Elementor\\Core\\Files\\CSS\\Post' ) ) {

                $css_file = new \Elementor\Core\Files\CSS\Post( $homepage_id );

                $css_file->enqueue();

            }



            $meta = get_post_meta( $homepage_id, '_elementor_data', true );

            if ( ! $meta ) {

                return;

            }



            $data = json_decode( $meta, true );

            if ( ! is_array( $data ) ) {

                return;

            }



            // Helper function to find element recursively

            $find_element = null;

            $find_element = function( $elements, $id ) use ( &$find_element ) {

                foreach ( $elements as $element ) {

                    if ( isset( $element['id'] ) && $element['id'] === $id ) {

                        return $element;

                    }

                    if ( isset( $element['elements'] ) && ! empty( $element['elements'] ) ) {

                        $found = $find_element( $element['elements'], $id );

                        if ( $found ) {

                            return $found;

                        }

                    }

                }

                return null;

            };



            $element_data = $find_element( $data, 'c9502e0' );

            if ( $element_data ) {

                $element_instance = \Elementor\Plugin::instance()->elements_manager->create_element_instance( $element_data );

                if ( $element_instance ) {

                    quanto_print_homepage_tail_inline_styles();

                    echo '<div data-elementor-type="wp-page" data-elementor-id="' . esc_attr( $homepage_id ) . '" class="elementor quanto-homepage-tail-sections elementor-' . esc_attr( $homepage_id ) . '">';

                    $element_instance->print_element();

                    echo '</div>';

                }

            }

        }

    }





    if ( ! function_exists( 'quanto_render_homepage_challenge_research_section' ) ) {

        function quanto_render_homepage_challenge_research_section() {

            if ( ! class_exists( '\\Elementor\\Plugin' ) ) {

                return;

            }



            $homepage_id = get_option( 'page_on_front' );

            if ( ! $homepage_id ) {

                $homepage_id = 14; // Fallback

            }



            if ( function_exists( 'quanto_enqueue_elementor_post_assets' ) ) {

                quanto_enqueue_elementor_post_assets( $homepage_id );

            } elseif ( class_exists( '\\Elementor\\Core\\Files\\CSS\\Post' ) ) {

                $css_file = new \Elementor\Core\Files\CSS\Post( $homepage_id );

                $css_file->enqueue();

            }



            $meta = get_post_meta( $homepage_id, '_elementor_data', true );

            if ( ! $meta ) {

                return;

            }



            $data = json_decode( $meta, true );

            if ( ! is_array( $data ) ) {

                return;

            }



            // Helper function to find element recursively

            $find_element = null;

            $find_element = function( $elements, $id ) use ( &$find_element ) {

                foreach ( $elements as $element ) {

                    if ( isset( $element['id'] ) && $element['id'] === $id ) {

                        return $element;

                    }

                    if ( isset( $element['elements'] ) && ! empty( $element['elements'] ) ) {

                        $found = $find_element( $element['elements'], $id );

                        if ( $found ) {

                            return $found;

                        }

                    }

                }

                return null;

            };



            $element_data = $find_element( $data, 'ac412d3' );

            if ( $element_data ) {

                $element_instance = \Elementor\Plugin::instance()->elements_manager->create_element_instance( $element_data );

                if ( $element_instance ) {

                    quanto_print_homepage_tail_inline_styles();

                    echo '<div data-elementor-type="wp-page" data-elementor-id="' . esc_attr( $homepage_id ) . '" class="elementor quanto-homepage-tail-sections elementor-' . esc_attr( $homepage_id ) . '">';

                    $element_instance->print_element();

                    echo '</div>';

                }

            }

        }

    }



    if ( ! function_exists( 'quanto_render_homepage_connect_trends_section' ) ) {

        function quanto_render_homepage_connect_trends_section() {

            if ( ! class_exists( '\\Elementor\\Plugin' ) ) {

                return;

            }



            $homepage_id = get_option( 'page_on_front' );

            if ( ! $homepage_id ) {

                $homepage_id = 14; // Fallback

            }



            if ( function_exists( 'quanto_enqueue_elementor_post_assets' ) ) {

                quanto_enqueue_elementor_post_assets( $homepage_id );

            } elseif ( class_exists( '\\Elementor\\Core\\Files\\CSS\\Post' ) ) {

                $css_file = new \Elementor\Core\Files\CSS\Post( $homepage_id );

                $css_file->enqueue();

            }



            $meta = get_post_meta( $homepage_id, '_elementor_data', true );

            if ( ! $meta ) {

                return;

            }



            $data = json_decode( $meta, true );

            if ( ! is_array( $data ) ) {

                return;

            }



            // Helper function to find element recursively

            $find_element = null;

            $find_element = function( $elements, $id ) use ( &$find_element ) {

                foreach ( $elements as $element ) {

                    if ( isset( $element['id'] ) && $element['id'] === $id ) {

                        return $element;

                    }

                    if ( isset( $element['elements'] ) && ! empty( $element['elements'] ) ) {

                        $found = $find_element( $element['elements'], $id );

                        if ( $found ) {

                            return $found;

                        }

                    }

                }

                return null;

            };



            $element_data = $find_element( $data, '7c312a9' );

            if ( $element_data ) {

                $element_instance = \Elementor\Plugin::instance()->elements_manager->create_element_instance( $element_data );

                if ( $element_instance ) {

                    quanto_print_homepage_tail_inline_styles();

                    echo '<div data-elementor-type="wp-page" data-elementor-id="' . esc_attr( $homepage_id ) . '" class="elementor quanto-homepage-tail-sections elementor-' . esc_attr( $homepage_id ) . '">';

                    $element_instance->print_element();

                    echo '</div>';

                }

            }

        }

    }



    if ( ! function_exists( 'quanto_render_homepage_connect_footer_section' ) ) {

        function quanto_render_homepage_connect_footer_section() {

            if ( ! class_exists( '\\Elementor\\Plugin' ) ) {

                return;

            }



            $homepage_id = get_option( 'page_on_front' );

            if ( ! $homepage_id ) {

                $homepage_id = 14; // Fallback

            }



            if ( function_exists( 'quanto_enqueue_elementor_post_assets' ) ) {

                quanto_enqueue_elementor_post_assets( $homepage_id );

            } elseif ( class_exists( '\\Elementor\\Core\\Files\\CSS\\Post' ) ) {

                $css_file = new \Elementor\Core\Files\CSS\Post( $homepage_id );

                $css_file->enqueue();

            }



            $meta = get_post_meta( $homepage_id, '_elementor_data', true );

            if ( ! $meta ) {

                return;

            }



            $data = json_decode( $meta, true );

            if ( ! is_array( $data ) ) {

                return;

            }



            // Helper function to find element recursively

            $find_element = null;

            $find_element = function( $elements, $id ) use ( &$find_element ) {

                foreach ( $elements as $element ) {

                    if ( isset( $element['id'] ) && $element['id'] === $id ) {

                        return $element;

                    }

                    if ( isset( $element['elements'] ) && ! empty( $element['elements'] ) ) {

                        $found = $find_element( $element['elements'], $id );

                        if ( $found ) {

                            return $found;

                        }

                    }

                }

                return null;

            };



            $element_data = $find_element( $data, '2f45980' );

            if ( $element_data ) {

                $element_instance = \Elementor\Plugin::instance()->elements_manager->create_element_instance( $element_data );

                if ( $element_instance ) {

                    quanto_print_homepage_tail_inline_styles();

                    echo '<div data-elementor-type="wp-page" data-elementor-id="' . esc_attr( $homepage_id ) . '" class="elementor quanto-homepage-tail-sections elementor-' . esc_attr( $homepage_id ) . '">';

                    $element_instance->print_element();

                    echo '</div>';

                }

            }

        }

    }



    if ( ! function_exists( 'quanto_render_test_page_similar_reports_section' ) ) {

        function quanto_render_test_page_similar_reports_section() {

            if ( ! class_exists( '\\Elementor\\Plugin' ) ) {

                return;

            }



            $target_page = get_page_by_path( 'similar-reports-by-industry' );

            if ( ! $target_page ) {

                $target_page = get_page_by_path( 'test' );

            }

            if ( ! $target_page ) {

                return;

            }



            $target_page_id = $target_page->ID;



            if ( function_exists( 'quanto_enqueue_elementor_post_assets' ) ) {

                quanto_enqueue_elementor_post_assets( $target_page_id );

            } elseif ( class_exists( '\\Elementor\\Core\\Files\\CSS\\Post' ) ) {

                $css_file = new \Elementor\Core\Files\CSS\Post( $target_page_id );

                $css_file->enqueue();

            }



            $meta = get_post_meta( $target_page_id, '_elementor_data', true );

            if ( ! $meta ) {

                return;

            }



            $data = json_decode( $meta, true );

            if ( ! is_array( $data ) ) {

                return;

            }



            $target_section = null;

            foreach ( $data as $section ) {

                if ( stripos( json_encode( $section ), 'Similar Reports' ) !== false ) {

                    $target_section = $section;

                    break;

                }

            }



            if ( $target_section ) {

                $element_instance = \Elementor\Plugin::instance()->elements_manager->create_element_instance( $target_section );

                if ( $element_instance ) {

                    quanto_print_homepage_tail_inline_styles();

                    if ( class_exists( '\\Elementor\\Core\\Files\\CSS\\Post' ) ) {

                        $css_file = new \Elementor\Core\Files\CSS\Post( $target_page_id );

                        $css_content = $css_file->get_content();

                        if ( ! empty( $css_content ) ) {

                            echo '<style>' . $css_content . '</style>';

                        }

                    }

                    echo '<div data-elementor-type="wp-page" data-elementor-id="' . esc_attr( $target_page_id ) . '" class="elementor quanto-similar-reports-section elementor-' . esc_attr( $target_page_id ) . '">';

                    $element_instance->print_element();

                    echo '</div>';

                }

            }

        }

    }

