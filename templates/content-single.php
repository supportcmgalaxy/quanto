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



    quanto_setPostViews( get_the_ID() );



    ?>



    <?php echo '<div class="blog-item-wrapper">'; ?>



        <?php echo '<div class="blog-item blog-item-details">'; ?>



            <div <?php post_class(); ?> >



                <?php



                if( class_exists('ReduxFramework') ) {



                    $quanto_post_details_title_position = quanto_opt('quanto_post_details_title_position');



                } else {



                    $quanto_post_details_title_position = 'header';



                }



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



                echo '<div class="row justify-content-center row-padding-bottom">';
                    if ( class_exists('ReduxFramework') ) {
                        $column_class = quanto_opt('quanto_blog_details_title_column');
                        if ( empty($column_class) ) {
                            $column_class = 'col-xl-9 col-xxl-9';
                        }
                    } else {
                        if ( is_active_sidebar('quanto-blog-sidebar') ) {
                            $column_class = 'col-xl-12 col-xxl-12';
                        } else {
                            $column_class = 'col-xl-9 col-xxl-9';
                        }
                    }
                    echo '<div class="' . esc_attr($column_class) . '">';



                        // Inject breadcrumbs above the title



                        if ( function_exists('quanto_breadcrumbs') ) {



                            echo '<div class="breadcumb-menu-wrap move-anim" data-delay="0.45" style="margin-bottom: 15px;">';



                            quanto_breadcrumbs( array( 'breadcrumbs_classes' => 'nav' ) );



                            echo '</div>';



                        }



                        if( $quanto_post_details_title_position != 'header' ) {



                            echo '<div class="title-box blog-title move-anim" data-delay="0.45">';



                                //title



                                echo '<h2>'.wp_kses( get_the_title(), $allowhtml ).'</h2>';



                            echo '</div>';



                        }



                        if ( ( function_exists( 'quanto_is_press_release' ) && quanto_is_press_release() ) || get_post_type() === 'cmr_news' ) {

                            $active_post_id = get_the_ID();
                            $active_excerpt = function_exists( 'quanto_get_clean_post_excerpt' ) ? quanto_get_clean_post_excerpt( $active_post_id, 35 ) : '';

                            if ( ! empty( $active_excerpt ) ) {

                                echo '<div class="cmr-single-lead-excerpt" style="font-size: 16px; line-height: 1.65; color: #2D3748; margin-top: 18px; margin-bottom: 24px; font-family: \'Instrument Sans\', sans-serif;">';

                                echo '<p>' . esc_html( $active_excerpt ) . '</p>';

                                echo '</div>';

                            }

                        }



                        // Blog Post Meta



                        do_action( 'quanto_blog_details_post_meta' );



                    echo '</div>';



                echo '</div>';



                // Blog Post Thumbnail



                do_action( 'quanto_blog_post_thumb' );



                



                echo '<div class="content-box">';



                    // Share Links



                    if( class_exists('ReduxFramework') ) {



                        $quanto_post_details_share_options = quanto_opt('quanto_post_details_share_options');



                        if ( ! isset($quanto_post_details_share_options) || $quanto_post_details_share_options === '' ) {



                            $quanto_post_details_share_options = true;



                        }



                    } else {



                        $quanto_post_details_share_options = true;



                    }



                    if( $quanto_post_details_share_options ){



                        echo '<div class="social-links">';



                            /**



                            *



                            * Hook for Blog Details Share Options



                            *



                            * Hook quanto_blog_details_share_options



                            *



                            * @Hooked quanto_blog_details_share_options_cb 10



                            *



                            */



                            do_action( 'quanto_blog_details_share_options' );



                        echo '</div>';



                    }



                    echo '<div class="row justify-content-center social-links-scroll position-relative">';
                        if( $quanto_post_details_share_options ) {
                            echo '<div class="col-xl-9 col-xxl-8">';
                        } else {
                            echo '<div class="col-xl-12 col-xxl-12">';
                        }



                            echo '<div class="blog-body">';



                                // Blog Content



                                the_content();



                            echo '</div>';



                            // Tags



                            $quanto_post_tag = get_the_tags();



                            if( is_array( $quanto_post_tag ) && ! empty( $quanto_post_tag ) ){



                                echo '<div class="blog-tags">';



                                    echo '<ul class="custom-ul">';



                                        foreach( $quanto_post_tag as $tags ){



                                            echo '<li><a href="'.esc_url( get_tag_link( $tags->term_id ) ).'">'.esc_html( $tags->name ).'</a></li>';



                                        }



                                    echo '</ul>';



                                echo '</div>';



                            }



                            ?>



                            <?php



                            // Custom Author Box for Media Releases



                            if ( get_post_type() === 'cmr_news' ) {



                                $custom_author_id = get_post_meta( get_the_ID(), '_cmr_news_custom_author', true );



                                if ( ! $custom_author_id ) {



                                    $custom_author_id = get_the_author_meta( 'ID' );



                                }



                                if ( $custom_author_id ) {



                                    $author_data = get_userdata( $custom_author_id );



                                    if ( $author_data ) {



                                        $designation = get_user_meta( $custom_author_id, 'designation', true );



                                        $linkedin = get_user_meta( $custom_author_id, 'linkedin_url', true );



                                        $x_url = get_user_meta( $custom_author_id, 'x_url', true );



                                        $bio = get_user_meta( $custom_author_id, 'description', true );



                                        ?>



                                        <div class="cmr-custom-author-box mt-5 mb-5 p-4 border rounded bg-white">



                                            <div class="d-flex align-items-center mb-3">



                                                <div class="cmr-custom-author-avatar me-4">



                                                    <?php echo get_avatar( $custom_author_id, 80, '', '', array( 'class' => 'rounded-circle' ) ); ?>



                                                </div>



                                                <div class="cmr-custom-author-info">



                                                    <h4 class="mb-1" style="font-weight: 600; font-size: 20px; font-family: 'Instrument Sans', sans-serif; letter-spacing: 0.5px;"><?php echo esc_html( $author_data->display_name ); ?></h4>



                                                    <?php if ( $designation ) : ?>



                                                        <p class="mb-2 text-muted" style="font-size: 14px; font-family: 'Instrument Sans', sans-serif;"><?php echo esc_html( $designation ); ?></p>



                                                    <?php endif; ?>



                                                    <div class="cmr-custom-author-social d-flex gap-2">



                                                        <?php if ( $linkedin ) : ?>



                                                            <a href="<?php echo esc_url( $linkedin ); ?>" target="_blank" rel="noopener noreferrer" style="color: #000; font-size: 18px;"><i class="fa-brands fa-linkedin"></i></a>



                                                        <?php endif; ?>



                                                        <?php if ( $x_url ) : ?>



                                                            <a href="<?php echo esc_url( $x_url ); ?>" target="_blank" rel="noopener noreferrer" style="color: #000; font-size: 18px;"><i class="fa-brands fa-square-x-twitter"></i></a>



                                                        <?php endif; ?>



                                                    </div>



                                                </div>



                                            </div>



                                            <?php if ( $bio ) : ?>



                                                <div class="cmr-custom-author-bio mt-3">



                                                    <p style="font-size: 14px; line-height: 1.6; color: #555;"><?php echo wp_kses_post( $bio ); ?></p>



                                                </div>



                                            <?php endif; ?>



                                        </div>



                                        <?php



                                    }



                                }



                            }



                            ?>



                            <?php



                            /**



                            *



                            * Hook for Blog Details Comments



                            *



                            * Hook quanto_blog_details_comments



                            *



                            * @Hooked quanto_blog_details_comments_cb 10



                            *



                            */



                            do_action( 'quanto_blog_details_comments' );



                        echo '</div>';



                    echo '</div>';



                echo '</div>';



            echo '</div>';



        echo '</div>';



    echo '</div>';



    if ( ( function_exists( 'quanto_is_press_release' ) && quanto_is_press_release() ) || get_post_type() === 'cmr_news' ) {



        ?>



        <div class="insights-cta-banner" style="margin-bottom: -50px; max-width: 1200px; width: 100%; margin-left: auto; margin-right: auto;">



            <div class="insights-cta-content">



                <h3 class="insights-cta-title">Want deeper insights?</h3>



                <p class="insights-cta-text">Connect with our analysts to understand how these<br>trends impact your specific vertical.</p>



            </div>



            <div class="insights-cta-actions">



                <div class="elementor-element elementor-element-b3cba9e download-btn animejs-disable elementor-widget elementor-widget-button" data-id="b3cba9e" data-element_type="widget" data-e-type="widget" data-settings="{&quot;mas-animation&quot;:&quot;none&quot;}" data-widget_type="button.default" style="align-self: flex-end; width: 235px;">



                    <a class="elementor-button elementor-button-link elementor-size-sm insights-cta-button primary" href="#" style="justify-content: center; color: #111 !important; background-color: #ffffff !important; width: 100%; display: flex; align-items: center;">



                        <span class="elementor-button-content-wrapper" style="width: 100%; display: flex; align-items: center; justify-content: center;">



                            <span class="elementor-button-text" style="margin-right: 6px; color: #111 !important; font-weight: 500 !important; line-height: 1; white-space: nowrap;">Talk to Analyst</span>



                            <span class="elementor-button-icon" style="display: flex; align-items: center;">



                                <img src="<?php echo esc_url( home_url( '/wp-content/uploads/2026/04/Symbol-1.svg' ) ); ?>" alt="Icon" width="16" height="14" style="object-fit: contain;">



                            </span>



                        </span>



                    </a>



                </div>







            </div>



        </div>



        <?php



    }



   