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



    exit;



}







 // theme option callback



function quanto_opt( $id = null, $url = null ){



    global $quanto_opt;







    if( $id && $url ){







        if( isset( $quanto_opt[$id][$url] ) && $quanto_opt[$id][$url] ){



            return $quanto_opt[$id][$url];



        }



    }else{



        if( isset( $quanto_opt[$id] )  && $quanto_opt[$id] ){



            return $quanto_opt[$id];



        }



    }



}











// theme logo



function quanto_theme_logo() {



    // escaping allow html



    $allowhtml = array(



        'a'    => array(



            'href' => array()



        ),



        'span' => array(),



        'i'    => array(



            'class' => array()



        )



    );



    $siteUrl = home_url('/');



    if( has_custom_logo() ) {



        $custom_logo_id = get_theme_mod( 'custom_logo' );



        $siteLogo = '';



        $siteLogo .= '<a class="logo" href="'.esc_url( $siteUrl ).'">';



        $siteLogo .= quanto_img_tag( array(



            "class" => "img-fluid logo-img",



            "url"   => esc_url( wp_get_attachment_image_url( $custom_logo_id, 'full') )



        ) );



        $siteLogo .= '</a>';







        return $siteLogo;



    } elseif( !quanto_opt('quanto_text_title') && quanto_opt('dark_logo', 'url' )  ){



        $siteLogo = '<img class="" src="'.esc_url( quanto_opt('dark_logo', 'url' ) ).'" alt="'.esc_attr__( 'logo', 'quanto' ).'" />';



        return '<a class="" href="'.esc_url( $siteUrl ).'">'.$siteLogo.'</a>';



    }elseif( quanto_opt('quanto_text_title') ){



        return '<h5><a class="logo" href="'.esc_url( $siteUrl ).'">'.wp_kses( quanto_opt('quanto_text_title'), $allowhtml ).'</a></h5>';



    }else{



        return '<h5><a class="logo" href="'.esc_url( $siteUrl ).'">'.esc_html( get_bloginfo('name') ).'</a></h5>';



    }



}







// Quanto Coming Soon Logo



function quanto_coming_soon_logo() {



    // escaping allow html



    $allowhtml = array(



        'a'    => array(



            'href' => array()



        ),



        'span' => array(),



        'i'    => array(



            'class' => array()



        )



    );



    $siteUrl = home_url('/');



    // site logo



    if( quanto_opt( 'quanto_coming_logo', 'url' )  ){







        $siteLogo = '<img src="'.esc_url( quanto_opt('quanto_coming_logo', 'url' ) ).'" alt="'.esc_attr__( 'logo', 'quanto' ).'" />';







        return '<a class="logo" href="'.esc_url( $siteUrl ).'">'.$siteLogo.'</a>';







    }elseif( quanto_opt('quanto_coming_site_title') ){



        return '<h2 class="mb-0"><a class="text-logo" href="'.esc_url( $siteUrl ).'">'.wp_kses( quanto_opt('quanto_coming_site_title'), $allowhtml ).'</a></h2>';



    }else{



        return '<h2 class="mb-0"><a class="text-logo" href="'.esc_url( $siteUrl ).'">'.esc_html( get_bloginfo('name') ).'</a></h2>';



    }



}







// custom meta id callback



function quanto_meta( $id = '' ){



    $value = get_post_meta( get_the_ID(), '_quanto_'.$id, true );



    return $value;



}











// Robust Elementor Template Shortcode with forced CSS

function quanto_elementor_template_shortcode( $atts ) {

    $id = isset( $atts['id'] ) ? intval( $atts['id'] ) : 0;

    if ( ! $id || ! class_exists( '\\Elementor\\Plugin' ) ) {

        return '';

    }

    

    // The second parameter 'true' forces Elementor to output inline CSS for the template.

    // This is much safer than echoing a raw <link> tag which can break layout.

    return \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $id, true );

}

add_shortcode( 'quanto-template', 'quanto_elementor_template_shortcode' );





// Blog Date Permalink



function quanto_blog_date_permalink() {



    $year  = get_the_time('Y');



    $month_link = get_the_time('m');



    $day   = get_the_time('d');



    $link = get_day_link( $year, $month_link, $day);



    return $link;



}







//audio format iframe match



function quanto_iframe_match() {



    $audio_content = quanto_embedded_media( array('audio', 'iframe') );



    $iframe_match = preg_match("/\iframe\b/i",$audio_content, $match);



    return $iframe_match;



}











//Post embedded media



function quanto_embedded_media( $type = array() ){



    $content = do_shortcode( apply_filters( 'the_content', get_the_content() ) );



    $embed   = get_media_embedded_in_content( $content, $type );











    if( in_array( 'audio' , $type) ){



        if( count( $embed ) > 0 ){



            $output = str_replace( '?visual=true', '?visual=false', $embed[0] );



        }else{



           $output = '';



        }







    }else{



        if( count( $embed ) > 0 ){



            $output = $embed[0];



        }else{



           $output = '';



        }



    }



    return $output;



}











// WP post link pages



function quanto_link_pages(){



    wp_link_pages( array(



        'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'quanto' ) . '</span>',



        'after'       => '</div>',



        'link_before' => '<span>',



        'link_after'  => '</span>',



        'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'quanto' ) . ' </span>%',



        'separator'   => '<span class="screen-reader-text">, </span>',



    ) );



}











// Data Background image attr



function quanto_data_bg_attr( $imgUrl = '' ){



    return 'data-bg-img="'.esc_url( $imgUrl ).'"';



}







// image alt tag



function quanto_image_alt( $url = '' ){



    if( $url != '' ){



        // attachment id by url



        $attachmentid = attachment_url_to_postid( esc_url( $url ) );



       // attachment alt tag



        $image_alt = get_post_meta( esc_html( $attachmentid ) , '_wp_attachment_image_alt', true );



        if( $image_alt ){



            return $image_alt ;



        }else{



            $filename = pathinfo( esc_url( $url ) );



            $alt = str_replace( '-', ' ', $filename['filename'] );



            return $alt;



        }



    }else{



       return;



    }



}











// Flat Content wysiwyg output with meta key and post id







function quanto_get_textareahtml_output( $content ) {



    global $wp_embed;







    $content = $wp_embed->autoembed( $content );



    $content = $wp_embed->run_shortcode( $content );



    $content = wpautop( $content );



    $content = do_shortcode( $content );







    return $content;



}







/**



 * Add a pingback url auto-discovery header for single posts, pages, or attachments.



 */







function quanto_pingback_header() {



    if ( is_singular() && pings_open() ) {



        echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';



    }



}



add_action( 'wp_head', 'quanto_pingback_header' );























// Excerpt More



function quanto_excerpt_more( $more ) {



    return '...';



}







add_filter( 'excerpt_more', 'quanto_excerpt_more' );











// quanto comment template callback



function quanto_comment_callback( $comment, $args, $depth ) {



        $add_below = 'comment';



    ?>



    <li <?php comment_class( array('comment-item') ); ?> style="margin-bottom: 30px; border-bottom: 1px solid #f3f4f6; padding-bottom: 30px; list-style: none;">

        <div id="comment-<?php comment_ID() ?>" class="d-flex align-items-start">

            <div class="comment-avatar me-3" style="width: 50px; height: 50px; flex-shrink: 0;">

                <?php echo get_avatar( $comment, 50, '', '', array('class' => 'rounded-circle w-100 h-100 object-fit-cover') ); ?>

            </div>

            <div class="comment-content flex-grow-1">

                <div class="d-flex align-items-center mb-1">

                    <h6 class="mb-0 me-2" style="font-family: 'Instrument Sans', sans-serif; font-weight: 600; font-size: 16px; color: #111;"><?php echo esc_html( ucwords( get_comment_author() ) ); ?></h6>

                    <?php

                    if ( get_post_type() === 'cmr_news' ) {

                        $r = get_comment_meta( get_comment_ID(), 'rating', true );

                        if ( $r && $r >= 1 && $r <= 5 ) {

                            echo '<span class="comment-rating-stars" style="color: #fbbf24; font-size: 12px; margin-top: 1px;">';

                            echo str_repeat('<i class="fa-solid fa-star"></i>', $r);

                            echo str_repeat('<i class="fa-regular fa-star" style="color: #e5e7eb;"></i>', 5 - $r);

                            echo '</span>';

                        }

                    }

                    ?>

                </div>

                

                <div class="comment-date mb-3" style="font-size: 12px; color: #6b7280; font-family: 'Instrument Sans', sans-serif;">

                    <?php printf( esc_html__('%1$s | %2$s', 'quanto'), get_comment_date(), get_comment_time() ); ?>

                </div>



                <div class="comment-text" style="font-size: 14px; color: #374151; line-height: 1.6; font-family: 'Instrument Sans', sans-serif;">

                    <?php comment_text(); ?>

                </div>

                

                <?php if ( $comment->comment_approved == '0' ) : ?>

                <p class="comment-awaiting-moderation text-danger mt-2" style="font-size: 12px;"><?php esc_html_e( 'Your comment is awaiting moderation.', 'quanto' ); ?></p>

                <?php endif; ?>

            </div>

        </div>

<?php



}







//body class



add_filter( 'body_class', 'quanto_body_class' );



function quanto_body_class( $classes ) {



    if( class_exists('ReduxFramework') ) {



        $quanto_blog_single_sidebar = quanto_opt('quanto_blog_single_sidebar');



        if( ($quanto_blog_single_sidebar != '2' && $quanto_blog_single_sidebar != '3' ) || ! is_active_sidebar('quanto-blog-sidebar') ) {



            $classes[] = 'no-sidebar';



        }



    } else {



        if( !is_active_sidebar('quanto-blog-sidebar') ) {



            $classes[] = 'no-sidebar';



        }



    }



    return $classes;



}











function quanto_footer_global_option(){

    return;





    // Quanto Footer Bottom Enable Disable



    if( class_exists( 'ReduxFramework' ) ){



        $quanto_footer_bottom_active = quanto_opt( 'quanto_disable_footer_bottom' );



    }else{



        $quanto_footer_bottom_active = '1';



    }







    $allowhtml = array(



        'p'         => array(



            'class'     => array()



        ),



        'span'      => array(



            'class'     => array(),



        ),



        'a'         => array(



            'href'      => array(),



            'title'     => array()



        ),



        'br'        => array(),



        'em'        => array(),



        'strong'    => array(),



        'b'         => array(),



    );







    if( $quanto_footer_bottom_active == '1' ){



        echo '<!-- Footer -->';



        echo '<footer class="footer-wrapper footer-layout1">';







            if( $quanto_footer_bottom_active == '1' ){



                $allowhtml = array(



                    'p'         => array(



                        'class'     => array()



                    ),



                    'span'      => array(),



                    'a'         => array(



                        'href'      => array(),



                        'title'     => array(),



                        'class'     => array(),



                    ),



                    'br'        => array(),



                    'em'        => array(),



                    'strong'    => array(),



                    'b'         => array(),



                );



                echo '<div class="copyright-wrap">';



                    echo '<div class="container">';



                        echo '<div class="row align-items-center">';



                            if( ! empty( quanto_opt( 'quanto_copyright_text' ) ) ){



                                $copyright_text = quanto_opt( 'quanto_copyright_text' );

                                if ( strpos( $copyright_text, 'Mirrortheme' ) !== false || strpos( $copyright_text, 'Quanto' ) !== false ) {

                                    $copyright_text = 'Copyright &copy; {year} <a href="{site_url}">{site_name}</a>. All Rights Reserved.';

                                }

                                $copyright_text = str_replace(

                                    array( '{year}', '{site_url}', '{site_name}' ),

                                    array( date('Y'), esc_url( home_url( '/' ) ), esc_html( get_bloginfo( 'name' ) ) ),

                                    $copyright_text

                                );

                                echo '<p class="copyright-text">'.wp_kses( $copyright_text, $allowhtml ).'</p>';



                            }



                        echo '</div>';



                    echo '</div>';



                echo '</div>';



            }



        echo '</footer>';



        echo '<!-- End Footer -->';



    }



}







function quanto_social_icon(){



    $quanto_social_icon = quanto_opt( 'quanto_social_links' );



    if( ! empty( $quanto_social_icon ) && isset( $quanto_social_icon ) ){



        echo '<div class="author-links">';



        foreach( $quanto_social_icon as $social_icon ){



            if( ! empty( $social_icon['title'] ) ){



                echo '<a href="'.esc_url( $social_icon['url'] ).'"><i class="'.esc_attr( $social_icon['title'] ).'"></i>'.esc_html( $social_icon['description'] ).'</a>';



            }



        }



        echo '</div>';



    }



}











// global header



function quanto_global_header_option() {



    quanto_global_header();



    echo '<header class="quanto-header main-header bg-color-white prebuilt-header" id="sticky-menu">';



        echo '<div class="sticky-wrap">';



            echo '<div class="sticky-active">';



                echo '<div class="container custom-container">';



                    echo '<div class="row gx-3 align-items-center justify-content-between">';



                        echo '<div class="col-auto align-self-center">';



                            echo '<div class="header-logo">';



                                echo quanto_theme_logo();



                            echo '</div>';



                        echo '</div>';



                        echo '<div class="col text-end">';



                            if( has_nav_menu( 'primary-menu' ) ){



                                echo '<nav class="main-menu menu-style1 d-none d-lg-block">';



                                    wp_nav_menu( array(



                                        "theme_location"    => 'primary-menu',



                                        "container"         => '',



                                        "menu_class"        => ''



                                    ) );



                                echo '</nav>';



                            }



                            



                        echo '</div>';



                        



                        echo '<div class="col-auto d-inline-block d-lg-none">';



                            echo '<!-- Mobile Menu Toggler -->';



                            echo '<button class="menuBar-toggle quanto-menu-toggle d-inline-block d-lg-none" aria-label="' . esc_attr__( 'Toggle Mobile Menu', 'quanto' ) . '">';



                                echo '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">

                                        <line x1="3" y1="6" x2="21" y2="6"></line>

                                        <line x1="3" y1="12" x2="21" y2="12"></line>

                                        <line x1="3" y1="18" x2="21" y2="18"></line>

                                    </svg>';



                            echo '</button>';



                        echo '</div>';







                    echo '</div>';



                echo '</div>';



            echo '</div>';



        echo '</div>';



    echo '</header>';



}







// Quanto Default Header



if( ! function_exists( 'quanto_global_header' ) ){



    function quanto_global_header(){



        // quanto-body-visible



        // Mobile Menu



        echo '<div class="quanto-menu-wrapper">';



            echo '<div class="quanto-menu-area text-center">';



                echo '<div class="quanto-menu-mobile-top">';



                    echo '<div class="mobile-logo">';



                        echo quanto_theme_logo();



                    echo '</div>';



                    echo '<button class="quanto-menu-toggle mobile" aria-label="' . esc_attr__( 'Close Mobile Menu', 'quanto' ) . '"><i class="ri-close-line"></i></button>';



                echo '</div>';



                $menu_location = has_nav_menu( 'mobile-menu' ) ? 'mobile-menu' : ( has_nav_menu( 'primary-menu' ) ? 'primary-menu' : '' );



                if( $menu_location ){



                    echo '<div class="quanto-mobile-menu">';



                        wp_nav_menu( array(



                            "theme_location"    => $menu_location,



                            "container"         => '',



                            "menu_class"        => ''



                        ) );



                    echo '</div>';



                }



            echo '</div>';



        echo '</div>';



    }



}











function quanto_custom_search_form( $class ) {



    echo '<!-- Search Form -->';



    echo '<form method="get" action="'.esc_url( home_url( '/' ) ).'" class="'.esc_attr( $class ).'">';



        echo '<label class="searchIcon">';



            echo '<input value="'.esc_html( get_search_query() ).'" name="s" required type="search" placeholder="'.esc_attr__('What are you looking for?', 'quanto').'">';



        echo '</label>';



    echo '</form>';



    echo '<!-- End Search Form -->';



}















//Fire the wp_body_open action.



if ( ! function_exists( 'wp_body_open' ) ) {



	function wp_body_open() {



		do_action( 'wp_body_open' );



	}



}







//Remove Tag-Clouds inline style



add_filter( 'wp_generate_tag_cloud', 'quanto_remove_tagcloud_inline_style',10,1 );



function quanto_remove_tagcloud_inline_style( $input ){



   return preg_replace('/ style=("|\')(.*?)("|\')/','',$input );



}







// password protected form



add_filter('the_password_form','quanto_password_form',10,1);



function quanto_password_form( $output ) {



    $output = '<form action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" class="post-password-form" method="post"><div class="theme-input-group">



        <input name="post_password" type="password" class="theme-input-style" placeholder="'.esc_attr__( 'Enter Password','quanto' ).'">



        <button type="submit" class="submit-btn btn-fill">'.esc_html__( 'Enter','quanto' ).'</button></div></form>';



    return $output;



}







function quanto_setPostViews( $postID ) {



    $count_key  = 'post_views_count';



    $count      = get_post_meta( $postID, $count_key, true );



    if( $count == '' ){



        $count = 0;



        delete_post_meta( $postID, $count_key );



        add_post_meta( $postID, $count_key, '0' );



    }else{



        $count++;



        update_post_meta( $postID, $count_key, $count );



    }



}







function quanto_getPostViews( $postID ){



    $count_key  = 'post_views_count';



    $count      = get_post_meta( $postID, $count_key, true );



    if( $count == '' ){



        delete_post_meta( $postID, $count_key );



        add_post_meta( $postID, $count_key, '0' );



        return __( '0', 'quanto' );



    }



    return $count;



}







/* This code filters the Categories widget to include the post count inside the link */



function quanto_cat_add_count_span($output) {



    // Modify the category list output to include count in a <span> element



    $output = preg_replace('/<\/a>\s*\(([0-9]+)\)/', '</a> <span>($1)</span>', $output);



    return $output;



}



add_filter('wp_list_categories', 'quanto_cat_add_count_span');







/* This code filters the Archive widget to include the post count inside the link */



add_filter( 'get_archives_link', 'quanto_archive_remove_count' );



function quanto_archive_remove_count( $links ) {



    $links = preg_replace('/<\/a>&nbsp;\([0-9]+\)/', '</a>', $links);



    return $links;



}







// Blog Category



if( ! function_exists( 'quanto_blog_category' ) ){



    function quanto_blog_category(){



        $quanto_post_categories = get_the_category();

        if ( empty( $quanto_post_categories ) && get_post_type() === 'cmr_news' ) {

            $quanto_post_categories = get_the_terms( get_the_ID(), 'cmr_news_category' );

        }



        if( is_array( $quanto_post_categories ) && ! empty( $quanto_post_categories ) ){

            $cat_links = array();

            foreach( $quanto_post_categories as $cat ){

                $term_link = get_term_link( $cat );

                if ( ! is_wp_error( $term_link ) ) {

                    $cat_links[] = '<a href="'.esc_url( $term_link ).'">'.esc_html( $cat->name ).'</a>';

                } else {

                    $cat_links[] = esc_html( $cat->name );

                }

            }

            if ( ! empty( $cat_links ) ) {

                echo '<li><span>' . implode( ', ', $cat_links ) . '</span></li>';

            }

        }



    }



}







// Add Extra Class On Comment Reply Button



function quanto_custom_comment_reply_link( $content ) {



    $extra_classes = 'replay-btn';



    return preg_replace( '/comment-reply-link/', 'quanto-link-btn ' . $extra_classes, $content);



}







add_filter('comment_reply_link', 'quanto_custom_comment_reply_link', 99);







// Add Extra Class On Edit Comment Link



function quanto_custom_edit_comment_link( $content ) {



    $extra_classes = 'replay-btn';



    return preg_replace( '/comment-edit-link/', 'comment-edit-link ' . $extra_classes, $content);



}







add_filter('edit_comment_link', 'quanto_custom_edit_comment_link', 99);











function quanto_post_classes( $classes, $class, $post_id ) {



    if ( get_post_type() === 'post' ) {



        if( ! is_single() ){



            if( quanto_opt( 'quanto_blog_style' ) == '3' ){



                $classes[] = "quanto-blog blog-grid grid-wide";



            }else{



                $classes[] = "quanto-blog blog-single";



            }



        }else{



            $classes[] = "quanto-blog";



        }



    }elseif( get_post_type() === 'product' ){



        // Return Class



    }elseif( get_post_type() === 'page' ){



        $classes[] = "page--item";



    }







    return $classes;



}



add_filter( 'post_class', 'quanto_post_classes', 10, 3 );

/**

 * Helper to check if the current post is specifically a Media Release or Press Release

 */

if ( ! function_exists( 'quanto_is_press_release' ) ) {

    function quanto_is_press_release( $post_id = null ) {

        if ( ! $post_id ) {

            $post_id = get_the_ID();

        }

        if ( ! $post_id ) {

            return false;

        }



        $target_slugs = array(

            'media-release',

            'media-releases',

            'press-release',

            'press-releases',

            'press-releases-news',

        );



        // 1. Check taxonomy: cmr_news_category

        $terms = get_the_terms( $post_id, 'cmr_news_category' );

        if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {

            foreach ( $terms as $term ) {

                $slug = strtolower( $term->slug );

                $name = strtolower( $term->name );

                if ( in_array( $slug, $target_slugs ) || 

                     strpos( $name, 'media release' ) !== false || 

                     strpos( $name, 'press release' ) !== false ) {

                    return true;

                }

            }

        }



        // 2. Check taxonomy: category

        $cats = get_the_terms( $post_id, 'category' );

        if ( ! empty( $cats ) && ! is_wp_error( $cats ) ) {

            foreach ( $cats as $cat ) {

                $slug = strtolower( $cat->slug );

                $name = strtolower( $cat->name );

                if ( in_array( $slug, $target_slugs ) || 

                     strpos( $name, 'media release' ) !== false || 

                     strpos( $name, 'press release' ) !== false ) {

                    return true;

                }

            }

        }



        return false;

    }

}

if ( ! function_exists( 'quanto_get_clean_post_excerpt' ) ) {
    /**
     * Get clean excerpt starting from real paragraph content, skipping bullet lists (ul/ol/li) and bullet glyphs.
     */
    function quanto_get_clean_post_excerpt( $post_id, $word_limit = 18 ) {
        $post = get_post( $post_id );
        if ( ! $post ) {
            return '';
        }

        $title = $post->post_title;
        $raw_content = $post->post_content;
        $raw_excerpt = $post->post_excerpt;

        // Helper to check if a text string looks like bullet points or starts with bullet glyphs
        $is_bullet_text = function( $str ) {
            $trimmed = trim( $str );
            if ( preg_match( '/^[\x{2022}\x{2023}\x{25E6}\x{2043}\x{2219}\-–—*•\d+\.]/u', $trimmed ) ) {
                return true;
            }
            if ( strpos( $trimmed, '•' ) !== false || strpos( $trimmed, '&bull;' ) !== false ) {
                return true;
            }
            return false;
        };

        $excerpt = '';

        // 1. If explicit post_excerpt exists, make sure it is not stale June 29 SSD text or bullet text
        if ( ! empty( $raw_excerpt ) ) {
            // Reject if it mentions June 29 when the post title/content does not match June 29
            $is_stale_june_ssd = ( stripos( $raw_excerpt, 'June 29' ) !== false && stripos( $title, 'June 29' ) === false );
            if ( ! $is_stale_june_ssd && ( stripos( $raw_excerpt, 'consumer SSD market' ) === false || stripos( $title, 'SSD' ) !== false ) ) {
                if ( ! $is_bullet_text( $raw_excerpt ) && stripos( $raw_excerpt, '<li' ) === false ) {
                    $excerpt = wp_trim_words( wp_strip_all_tags( $raw_excerpt ), $word_limit, '...' );
                }
            }
        }

        // 2. Extract starting from actual paragraph <p> in post_content (skipping lists and shortcodes)
        if ( empty( $excerpt ) && ! empty( $raw_content ) ) {
            // Remove <ul...</ul> and <ol...</ol> completely so bullets are never picked up
            $content_no_lists = preg_replace( '/<(ul|ol)\b[^>]*>.*?<\/\1>/is', '', $raw_content );

            // Match all <p> tags
            if ( preg_match_all( '/<p\b[^>]*>(.*?)<\/p>/is', $content_no_lists, $p_matches ) ) {
                foreach ( $p_matches[1] as $p_inner ) {
                    $clean_p = wp_strip_all_tags( $p_inner );
                    $clean_p = trim( preg_replace( '/^[\x{2022}\x{2023}\x{25E6}\x{2043}\x{2219}\-–—*•\s]+/u', '', $clean_p ) );
                    // Must have meaningful length and not be another bullet line
                    if ( mb_strlen( $clean_p ) > 20 && ! $is_bullet_text( $clean_p ) ) {
                        $excerpt = wp_trim_words( $clean_p, $word_limit, '...' );
                        break;
                    }
                }
            }

            // Fallback: strip shortcodes and lists, clean tags
            if ( empty( $excerpt ) ) {
                $clean_all = wp_strip_all_tags( strip_shortcodes( $content_no_lists ) );
                // Strip any leading bullet characters or dots
                $clean_all = trim( preg_replace( '/^[\x{2022}\x{2023}\x{25E6}\x{2043}\x{2219}\-–—*•\s]+/u', '', $clean_all ) );
                if ( ! empty( $clean_all ) ) {
                    $excerpt = wp_trim_words( $clean_all, $word_limit, '...' );
                }
            }
        }

        // 3. Fallback to native get_the_excerpt($post_id)
        if ( empty( $excerpt ) ) {
            $native = wp_strip_all_tags( get_the_excerpt( $post_id ) );
            if ( ! empty( $native ) && ( stripos( $native, 'consumer SSD market' ) === false || stripos( $title, 'SSD' ) !== false ) ) {
                $native = trim( preg_replace( '/^[\x{2022}\x{2023}\x{25E6}\x{2043}\x{2219}\-–—*•\s]+/u', '', $native ) );
                $excerpt = wp_trim_words( $native, $word_limit, '...' );
            }
        }

        return $excerpt;
    }
}

// Global Filter for Elementor Post Loop Grid, Archive Loop, and the_excerpt() calls
add_filter( 'get_the_excerpt', 'quanto_filter_clean_the_excerpt', 99, 2 );
function quanto_filter_clean_the_excerpt( $post_excerpt, $post = null ) {
    if ( is_admin() && ! wp_doing_ajax() ) {
        return $post_excerpt;
    }

    $post_id = 0;
    if ( is_object( $post ) && isset( $post->ID ) ) {
        $post_id = $post->ID;
    } elseif ( is_numeric( $post ) && $post > 0 ) {
        $post_id = intval( $post );
    } else {
        $post_id = get_the_ID();
    }

    if ( ! $post_id ) {
        return $post_excerpt;
    }

    // Only filter standard posts and cmr_news
    $post_type = get_post_type( $post_id );
    if ( ! in_array( $post_type, array( 'post', 'cmr_news' ), true ) ) {
        return $post_excerpt;
    }

    $clean = quanto_get_clean_post_excerpt( $post_id, 18 );
    if ( ! empty( $clean ) ) {
        return $clean;
    }

    return $post_excerpt;
}

