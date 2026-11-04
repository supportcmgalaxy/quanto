<?php
/**
 * CMR Featured Reports Section Shortcode
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! function_exists( 'cmr_featured_reports_shortcode' ) ) {
    function cmr_featured_reports_shortcode( $atts ) {
        $atts = shortcode_atts( array(
            'title' => 'Featured reports',
        ), $atts );

        // Try to get 3 products (can be marked as featured, or just recent)
        $args = array(
            'limit'   => 3,
            'status'  => 'publish',
            'orderby' => 'date',
            'order'   => 'DESC',
        );
        $products = wc_get_products( $args );

        ob_start();
        ?>
        <style>
            .cmr-featured-reports-section {
                max-width: 1280px;
                margin: 50px auto 20px;
                padding: 0 20px;
                font-family: 'Instrument Sans', sans-serif !important;
                box-sizing: border-box;
            }

            .cmr-featured-reports-title {
                font-size: 32px;
                font-weight: 600;
                color: #000000;
                margin-bottom: 30px;
                letter-spacing: -0.5px;
            }

            .cmr-featured-grid {
                display: grid;
                grid-template-columns: 44% 1fr;
                gap: 20px;
            }

            .cmr-fr-card {
                background: #ffffff;
                border-radius: 0;
                overflow: hidden;
                border: 1px solid #e5e7eb;
                position: relative;
                transition: transform 0.3s ease, box-shadow 0.3s ease;
                display: flex;
                cursor: pointer;
            }

            .cmr-fr-card:hover {
                transform: translateY(-5px);
            }

            .cmr-fr-card:hover .cmr-fr-small-title {
                color: #6b46c1;
            }

            .cmr-fr-card .cmr-fr-image-wrap {
                overflow: hidden;
            }

            .cmr-fr-card .cmr-fr-image-wrap img {
                transform: scale(1);
                transition: transform 0.5s cubic-bezier(0.25, 1, 0.5, 1) !important;
            }

            .cmr-fr-card:hover .cmr-fr-image-wrap img {
                transform: scale(1.06) !important;
            }

            .cmr-fr-badge {
                position: absolute;
                top: 15px;
                left: 15px;
                background: #ffffff;
                color: #6b46c1;
                font-size: 11px;
                font-weight: 600;
                letter-spacing: 0.8px;
                text-transform: uppercase;
                height: 30px;
                padding: 0 12px;
                box-sizing: border-box;
                border-radius: 0;
                z-index: 10;
                display: inline-flex;
                align-items: center;
                gap: 6px;
                line-height: 1;
                box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            }

            .cmr-fr-badge i,
            .cmr-fr-badge svg {
                font-size: 11px;
                line-height: 1;
            }

            /* Big Card (Left) */
            .cmr-fr-large {
                flex-direction: column;
                height: 740px;
                position: relative;
                background: #0a0e1a;
            }

            .cmr-fr-large .cmr-fr-image-wrap {
                width: 100%;
                height: 100%;
                position: absolute;
                top: 0;
                left: 0;
                background: #0a0e1a;
                overflow: hidden;
            }

            .cmr-fr-large .cmr-fr-image-wrap img {
                width: 100%;
                height: 100%;
                object-fit: contain;
                object-position: center top;
            }

            .cmr-fr-large .cmr-fr-image-wrap::after {
                content: '';
                position: absolute;
                top: 0; left: 0; right: 0; bottom: 0;
                background: linear-gradient(to top, rgba(10,14,26,0.95) 0%, rgba(10,14,26,0.65) 25%, rgba(10,14,26,0) 55%);
                pointer-events: none;
            }

            .cmr-fr-large-content {
                position: relative;
                z-index: 2;
                padding: 35px 30px;
                display: flex;
                flex-direction: column;
                justify-content: flex-end;
                height: 100%;
                color: #ffffff;
                box-sizing: border-box;
            }

            .cmr-fr-large-bottom-wrap {
                margin-top: auto;
                width: 100%;
                display: flex;
                flex-direction: column;
            }

            .cmr-fr-large-title {
                font-size: 28px;
                font-weight: 600;
                color: #ffffff;
                margin-bottom: 0;
                line-height: 1.25;
                text-decoration: none;
            }

            .cmr-fr-stars {
                color: #f59e0b;
                font-size: 14px;
                margin: 6px 0;
            }
            .cmr-fr-stars span {
                color: #d1d5db;
                font-size: 12px;
                margin-left: 5px;
            }

            .cmr-fr-brand {
                font-size: 13px;
                color: #d1d5db;
                margin-bottom: 0;
            }

            .cmr-fr-large-footer {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 15px;
                flex-wrap: wrap;
                margin-top: 14px;
            }

            .cmr-fr-large-price {
                font-size: 24px;
                font-weight: 600;
                color: #ffffff;
            }

            .cmr-fr-large-price del {
                font-size: 16px;
                color: #9ca3af;
                margin-right: 6px;
            }

            .cmr-fr-large-footer a.button,
            .cmr-fr-large-footer .button {
                margin: 0 !important;
                width: auto !important;
                min-height: 44px !important;
                padding: 10px 22px !important;
            }



            /* Small Cards (Right) */
            .cmr-fr-small-col {
                display: flex;
                flex-direction: column;
                gap: 20px;
            }

            .cmr-fr-small {
                flex-direction: row;
                height: 360px;
                display: flex;
            }

            .cmr-fr-small .cmr-fr-image-wrap {
                height: 100%;
                flex: 0 0 360px;
                width: 360px;
                aspect-ratio: 1 / 1;
                position: relative;
                overflow: hidden;
                background: #f8f9fa;
            }

            .cmr-fr-small .cmr-fr-image-wrap img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                object-position: center;
                display: block;
            }

            .cmr-fr-small-content {
                flex: 1;
                min-width: 0;
                padding: 24px 22px;
                display: flex;
                flex-direction: column;
                box-sizing: border-box;
            }

            .cmr-fr-category {
                font-size: 12px;
                color: #9ca3af;
                margin-bottom: 8px;
                text-transform: uppercase;
                font-weight: 600;
            }

            .cmr-fr-small-title {
                font-size: 26px;
                font-weight: 600;
                color: #111827;
                margin-bottom: 12px;
                line-height: 1.25;
                text-decoration: none;
            }

            .cmr-fr-small-title:hover {
                color: #6b46c1;
            }

            .cmr-fr-small .cmr-fr-brand {
                color: #6b7280;
                font-size: 13px;
                margin-bottom: 12px;
            }

            .cmr-fr-price {
                margin-top: auto;
                font-size: 20px;
                font-weight: 600;
                color: #111827;
                padding-top: 4px;
            }

            .cmr-fr-price del {
                color: #9ca3af;
                font-size: 14px;
                font-weight: 400;
                margin-right: 5px;
            }

            @media (max-width: 992px) {
                .cmr-featured-grid {
                    grid-template-columns: 1fr;
                }
                .cmr-fr-large {
                    height: auto !important;
                    min-height: auto !important;
                    flex-direction: column !important;
                    background: #0a0e1a !important;
                    border-radius: 12px !important;
                    overflow: hidden !important;
                }
                .cmr-fr-large .cmr-fr-image-wrap {
                    position: relative !important;
                    top: auto !important;
                    left: auto !important;
                    width: 100% !important;
                    height: auto !important;
                    aspect-ratio: 1 / 1 !important;
                    background: #0a0e1a !important;
                    display: flex !important;
                    align-items: center !important;
                    justify-content: center !important;
                    overflow: hidden !important;
                }
                .cmr-fr-large .cmr-fr-image-wrap img {
                    width: 100% !important;
                    height: 100% !important;
                    object-fit: contain !important;
                    object-position: center !important;
                }
                .cmr-fr-large .cmr-fr-image-wrap::after {
                    display: none !important;
                }
                .cmr-fr-large-content {
                    position: relative !important;
                    width: 100% !important;
                    height: auto !important;
                    padding: 24px 20px !important;
                    background: #0a0e1a !important;
                    display: flex !important;
                    flex-direction: column !important;
                    justify-content: flex-start !important;
                }
                .cmr-fr-large-bottom-wrap {
                    margin-top: 0 !important;
                }
                .cmr-fr-large-title {
                    font-size: 22px !important;
                    line-height: 1.3 !important;
                    margin-bottom: 4px !important;
                }
                .cmr-fr-stars {
                    margin: 2px 0 6px !important;
                }
                .cmr-fr-brand {
                    margin-bottom: 12px !important;
                }
                .cmr-fr-large-footer {
                    display: flex !important;
                    align-items: center !important;
                    justify-content: space-between !important;
                    flex-wrap: wrap !important;
                    gap: 12px !important;
                    margin-top: 8px !important;
                }
                .cmr-fr-small {
                    height: 240px;
                }
                .cmr-fr-small .cmr-fr-image-wrap {
                    flex: 0 0 240px;
                    width: 240px;
                }
                .cmr-fr-small-title {
                    font-size: 22px;
                }
            }

            @media (max-width: 576px) {
                .cmr-fr-large-title {
                    font-size: 20px !important;
                }
                .cmr-fr-large-content {
                    padding: 20px 16px !important;
                }
                .cmr-fr-small {
                    flex-direction: column;
                    height: auto;
                }
                .cmr-fr-small .cmr-fr-image-wrap {
                    width: 100%;
                    height: auto;
                    aspect-ratio: 1 / 1;
                    flex: none;
                }
                .cmr-fr-small-content {
                    width: 100%;
                    padding: 20px;
                }
                .cmr-fr-small-title {
                    font-size: 18px;
                }
            }
        </style>

        <section class="cmr-featured-reports-section" id="reports">
            <h2 class="cmr-featured-reports-title"><?php echo esc_html( $atts['title'] ); ?></h2>
            
            <div class="cmr-featured-grid">
                
                <?php if ( ! empty( $products ) && isset( $products[0] ) ) : 
                    $product = $products[0];
                    $image_url = wp_get_attachment_image_src( $product->get_image_id(), 'large' );
                    $image_url = $image_url ? $image_url[0] : 'https://via.placeholder.com/600x800';
                ?>
                <!-- Large Card -->
                <div class="cmr-fr-card cmr-fr-large" onclick="if(!event.target.closest('a.button, .add_to_cart_button, button, input')) { window.location.href='<?php echo esc_url( $product->get_permalink() ); ?>'; }">
                    <div class="cmr-fr-image-wrap">
                        <?php if ( function_exists( 'cmr_render_product_badge' ) ) { cmr_render_product_badge( $product, 'cmr-fr-badge' ); } ?>
                        <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $product->get_name() ); ?>" fetchpriority="high" loading="eager" decoding="async">
                    </div>
                    <div class="cmr-fr-large-content">
                        <div class="cmr-fr-large-bottom-wrap">
                            <a href="<?php echo esc_url( $product->get_permalink() ); ?>" class="cmr-fr-large-title"><?php echo esc_html( $product->get_name() ); ?></a>
                            <div class="cmr-fr-stars">
                                <?php 
                                $rating = floatval( $product->get_average_rating() );
                                $count = intval( $product->get_review_count() );
                                for ( $s = 1; $s <= 5; $s++ ) {
                                    if ( $s <= $rating ) echo '<i class="fa-solid fa-star"></i>';
                                    elseif ( $s - 0.5 <= $rating ) echo '<i class="fa-solid fa-star-half-stroke"></i>';
                                    else echo '<i class="fa-regular fa-star"></i>';
                                }
                                ?>
                                <span>(<?php echo $count; ?>)</span>
                            </div>
                            <div class="cmr-fr-brand">Cyber Media Research (CMR)</div>
                            <div class="cmr-fr-large-footer">
                                <?php if ( $product->get_price_html() ) : ?>
                                    <div class="cmr-fr-large-price"><?php echo $product->get_price_html(); ?></div>
                                <?php endif; ?>
                                <a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>" 
                                   data-quantity="1" 
                                   class="button product_type_simple add_to_cart_button ajax_add_to_cart" 
                                   data-product_id="<?php echo esc_attr( $product->get_id() ); ?>" 
                                   data-product_sku="<?php echo esc_attr( $product->get_sku() ); ?>" 
                                   aria-label="<?php echo esc_attr( $product->add_to_cart_description() ); ?>" 
                                   onclick="event.stopPropagation();"
                                   rel="nofollow">
                                    Download Report
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <div class="cmr-fr-small-col">
                    <?php 
                    // Small Cards
                    for ( $i = 1; $i <= 2; $i++ ) {
                        if ( isset( $products[$i] ) ) {
                            $product = $products[$i];
                            $image_url = wp_get_attachment_image_src( $product->get_image_id(), 'medium' );
                            $image_url = $image_url ? $image_url[0] : 'https://via.placeholder.com/400x400';
                            
                            $cats = $product->get_category_ids();
                            $cat_name = 'Report';
                            if ( ! empty($cats) ) {
                                $term = get_term_by( 'id', $cats[0], 'product_cat' );
                                if ( $term ) {
                                    $cat_name = $term->name;
                                }
                            }
                            ?>
                            <div class="cmr-fr-card cmr-fr-small" onclick="if(!event.target.closest('a.button, .add_to_cart_button, button, input')) { window.location.href='<?php echo esc_url( $product->get_permalink() ); ?>'; }">
                                <div class="cmr-fr-image-wrap">
                                    <?php if ( function_exists( 'cmr_render_product_badge' ) ) { cmr_render_product_badge( $product, 'cmr-fr-badge' ); } ?>
                                    <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $product->get_name() ); ?>" loading="lazy" decoding="async">
                                </div>
                                <div class="cmr-fr-small-content">
                                    <div class="cmr-fr-category">&mdash; <?php echo esc_html( $cat_name ); ?></div>
                                    <a href="<?php echo esc_url( $product->get_permalink() ); ?>" class="cmr-fr-small-title"><?php echo esc_html( $product->get_name() ); ?></a>
                                    <div class="cmr-fr-stars">
                                        <?php 
                                        $rating = floatval( $product->get_average_rating() );
                                        $count = intval( $product->get_review_count() );
                                        for ( $s = 1; $s <= 5; $s++ ) {
                                            if ( $s <= $rating ) echo '<i class="fa-solid fa-star"></i>';
                                            elseif ( $s - 0.5 <= $rating ) echo '<i class="fa-solid fa-star-half-stroke"></i>';
                                            else echo '<i class="fa-regular fa-star"></i>';
                                        }
                                        ?>
                                        <span>(<?php echo $count; ?>)</span>
                                    </div>
                                    <div class="cmr-fr-brand">Cyber Media Research (CMR)</div>
                                    <div class="cmr-fr-price">
                                        <?php echo $product->get_price_html(); ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>

            </div>
        </section>
        <?php
        return ob_get_clean();
    }
}
add_shortcode( 'cmr_featured_reports', 'cmr_featured_reports_shortcode' );
