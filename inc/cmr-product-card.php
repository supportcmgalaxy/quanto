<?php
function cmr_render_custom_product_card($product = null) {
    if (!$product) {
        global $product;
    }
    if (!$product) return;
    
    $image_url = wp_get_attachment_image_src( $product->get_image_id(), 'medium' );
    $image_url = $image_url ? $image_url[0] : quanto_get_placeholder_image_uri();
    
    $cats = $product->get_category_ids();
    $cat_name = 'Report';
    if ( ! empty($cats) ) {
        $term = get_term_by( 'id', $cats[0], 'product_cat' );
        if ( $term ) {
            $cat_name = $term->name;
        }
    }
    
    ?>
    <div class="cmr-lr-card">
        <div class="cmr-lr-image-wrap">
            <?php if ( function_exists( 'cmr_render_product_badge' ) ) { cmr_render_product_badge( $product, 'cmr-lr-badge' ); } ?>
            <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $product->get_name() ); ?>">
        </div>
        <div class="cmr-lr-content">
            <div class="cmr-lr-category">&mdash; <?php echo esc_html( $cat_name ); ?></div>
            <a href="<?php echo esc_url( $product->get_permalink() ); ?>" class="cmr-lr-title"><?php echo esc_html( $product->get_name() ); ?></a>
            
            <div class="cmr-lr-stars">
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
            
            <div class="cmr-lr-price">
                <?php echo $product->get_price_html(); ?>
            </div>
            
            <a href="<?php echo esc_url( $product->get_permalink() ); ?>" class="cmr-lr-btn">
                Download Report <i class="fa-solid fa-arrow-down"></i>
            </a>
        </div>
    </div>
<?php
}
