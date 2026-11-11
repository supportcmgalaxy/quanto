<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked woocommerce_output_all_notices - 10
 */
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	return;
}
?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class( 'custom-product-container', $product ); ?>>

	<div class="custom-product-top-row">
		<!-- Left Column: Product Gallery / Image -->
		<div class="custom-product-image-col">
			<?php
			$image_id = $product->get_image_id();
			if ( $image_id ) {
				$image_url = wp_get_attachment_image_url( $image_id, 'full' );
				echo '<div class="custom-product-main-image"><img src="' . esc_url( $image_url ) . '" alt="' . esc_attr( $product->get_name() ) . '" /></div>';
			} else {
				echo '<div class="custom-product-main-image">' . wc_placeholder_img( 'full' ) . '</div>';
			}
			?>
		</div>

		<!-- Right Column: Product Info -->
		<div class="custom-product-info-col">
			<!-- Badge: Custom / Dynamic -->
			<?php
			if ( function_exists( 'cmr_get_product_badge' ) ) {
				$single_badge = cmr_get_product_badge( $product );
				if ( ! empty( $single_badge['show'] ) ) {
					$color = ! empty( $single_badge['color'] ) ? $single_badge['color'] : '#ea580c';
					echo '<span class="custom-new-badge" style="color:' . esc_attr( $color ) . '; border-color:' . esc_attr( $color ) . '; background: rgba(255,255,255,0.06);">';
					if ( ! empty( $single_badge['icon'] ) ) {
						echo '<i class="' . esc_attr( $single_badge['icon'] ) . '"></i> ';
					}
					echo esc_html( $single_badge['text'] );
					echo '</span>';
				}
			}
			?>

			<!-- Category Link -->
			<div class="custom-product-category">
				<?php
				$categories = wp_get_post_terms( $product->get_id(), 'product_cat' );
				if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
					$cat = $categories[0];
					echo '<a href="' . esc_url( get_term_link( $cat ) ) . '">' . esc_html( $cat->name ) . '</a>';
				}
				?>
			</div>

			<!-- Title -->
			<h1 class="custom-product-title"><?php the_title(); ?></h1>

			<!-- Publisher/Author -->
			<div class="custom-product-author">
				<?php
				$author = get_post_meta( $product->get_id(), 'product_author', true );
				if ( ! $author ) {
					$author = $product->get_attribute( 'author' );
				}
				if ( ! $author ) {
					$author = 'Cyber Media Research (CMR)';
				}
				echo esc_html( $author );
				?>
			</div>

			<!-- Rating Row -->
			<div class="custom-product-rating-row">
				<?php
				$rating_count = $product->get_rating_count();
				$review_count = $product->get_review_count();
				$average      = $product->get_average_rating();
				
				// Re-use WooCommerce star-rating markup
				echo wc_get_rating_html( $average, $rating_count );
				?>
				<a href="#reviews" class="custom-review-count">
					(<?php echo esc_html( $review_count ); ?> Reviews)
				</a>
			</div>

			<!-- SKU -->
			<?php if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) : ?>
				<div class="custom-product-sku">
					SKU: <span class="sku"><?php echo ( $sku = $product->get_sku() ) ? esc_html( $sku ) : esc_html__( 'N/A', 'woocommerce' ); ?></span>
				</div>
			<?php endif; ?>

			<!-- Price -->
			<div class="custom-product-price-display">
				<?php echo $product->get_price_html(); ?>
			</div>

			<!-- Action Buttons Row -->
			<div class="custom-product-actions-row">
				<!-- Download Button (WooCommerce Single Product Add to Cart Form) -->
				<div class="custom-download-button-wrapper">
					<?php woocommerce_template_single_add_to_cart(); ?>
				</div>

				<!-- Talk to Analyst Button (Opens Elementor Popup 7637) -->
				<a class="custom-talk-analyst-btn" href="#elementor-action%3Aaction%3Dpopup%3Aopen%26settings%3DeyJpZCI6Ijc2MzciLCJ0b2dnbGUiOmZhbHNlfQ%3D%3D">
					Talk to an Analyst <svg class="arrow-up-right" viewBox="0 0 15 15" width="14" height="14" fill="none" xmlns="http://www.w3.org/2000/svg" style="display: inline-block; vertical-align: middle; margin-left: 8px;"><path d="M2.04895 4.52205V3.54978C2.07104 3.12993 2.4025 2.79848 2.80025 2.79848L11.396 2.77638C11.8159 2.79848 12.1473 3.12993 12.1473 3.52768V12.1455C12.1473 12.5433 11.8159 12.8747 11.396 12.8968H10.4237C10.0039 12.8747 9.67244 12.5433 9.65035 12.1234L9.78293 6.90853L3.44106 13.2504C3.1317 13.5598 2.68976 13.5598 2.3804 13.2504L1.6733 12.5433C1.38603 12.256 1.36394 11.792 1.6733 11.4826L8.01516 5.14077L2.82234 5.29545C2.4025 5.27335 2.04895 4.96399 2.04895 4.52205Z" fill="currentColor"></path></svg>
				</a>
			</div>

			<!-- Guaranteed Safe Checkout -->
			<div class="custom-safe-checkout">
				<span class="safe-checkout-label">Guaranteed safe checkout</span>
				<div class="payment-icons">
					<?php
					foreach ( array( 'ApplePay' => 'Apple Pay', 'GooglePay' => 'Google Pay', 'Mastercard' => 'Mastercard', 'Amex' => 'American Express', 'PayPal' => 'PayPal', 'Visa' => 'Visa' ) as $icon_slug => $icon_label ) : ?>
						<div class="payment-icon" title="<?php echo esc_attr( $icon_label ); ?>">
							<img src="<?php echo esc_url( home_url( '/wp-content/uploads/2026/05/' . $icon_slug . '.svg' ) ); ?>" alt="<?php echo esc_attr( $icon_label ); ?>" width="38" height="24" />
						</div>
					<?php endforeach; ?>
				</div>
			</div>

			<!-- Share Row -->
			<?php
			$share_url   = urlencode( get_permalink() );
			$share_title = urlencode( get_the_title() );
			?>
			<div class="custom-share-row">
				<span class="share-label">Share:</span>
				<div class="share-icons">
					<a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo $share_url; ?>" class="share-btn" target="_blank" rel="noopener" title="Share on LinkedIn">
						<i class="fa-brands fa-linkedin-in"></i>
					</a>
					<a href="https://twitter.com/intent/tweet?url=<?php echo $share_url; ?>&text=<?php echo $share_title; ?>" class="share-btn" target="_blank" rel="noopener" title="Share on Twitter/X">
						<i class="fa-brands fa-x-twitter"></i>
					</a>
					<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $share_url; ?>" class="share-btn" target="_blank" rel="noopener" title="Share on Facebook">
						<i class="fa-brands fa-facebook-f"></i>
					</a>
					<a href="https://api.whatsapp.com/send?text=<?php echo $share_title; ?>%20<?php echo $share_url; ?>" class="share-btn" target="_blank" rel="noopener" title="Share on WhatsApp">
						<i class="fa-brands fa-whatsapp"></i>
					</a>
				</div>
			</div>
		</div>
	</div>

	<!-- Bottom Section: Custom Tabs -->

	<!-- Bottom Section: Custom Tabs -->
	<div class="custom-product-tabs-wrapper" style="margin-top: 50px;">
		<ul class="custom-tabs-nav">
			<li class="tab-nav-item active" data-tab="report-highlights">Report Highlights</li>
			<li class="tab-nav-item" data-tab="related-reports">Related Reports</li>
			<li class="tab-nav-item" data-tab="reviews">Reviews (<?php echo esc_html( $review_count ); ?>)</li>
			<li class="tab-nav-item" data-tab="industry-reports">Similar Reports by Industry</li>
		</ul>

		<div class="custom-tabs-content">
			<!-- Tab Panel: Report Highlights -->
			<div id="tab-panel-report-highlights" class="tab-content-panel active">
				<div class="report-highlights-content-wrapper cmr-product-section">
					<h2 class="cmr-section-title" style="font-size: 28px; font-weight: 700; margin-bottom: 30px;">Report Highlights</h2>
					<?php the_content(); ?>
				</div>
			</div>

			<!-- Tab Panel: Related Reports -->
			<div id="tab-panel-related-reports" class="tab-content-panel">
				<div class="cmr-product-section">
					<h2 class="cmr-section-title" style="font-size: 28px; font-weight: 700; margin-bottom: 10px;">Related Reports</h2>
					<p class="cmr-section-subtitle" style="color: #64748b; margin-bottom: 30px;">Expand your perspective with these additional resources tailored to your professional interests</p>
					
					<div class="cmr-reports-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 30px;">
						<?php
						$related_ids = wc_get_related_products( $product->get_id(), 3 );
						if ( ! empty( $related_ids ) ) {
							$related_query = new WP_Query( array(
								'post_type'      => 'product',
								'post__in'       => $related_ids,
								'posts_per_page' => 3,
							) );
							if ( $related_query->have_posts() ) {
								while ( $related_query->have_posts() ) {
									$related_query->the_post();
									if (function_exists('cmr_render_custom_product_card')) {
										cmr_render_custom_product_card();
									}
								}
							}
							wp_reset_postdata();
						} else {
							echo '<p>No related reports found.</p>';
						}
						?>
					</div>
				</div>
			</div>

			<!-- Tab Panel: Reviews -->
			<div id="tab-panel-reviews" class="tab-content-panel">
				<div class="cmr-product-section">
					<h2 class="cmr-section-title" style="font-size: 28px; font-weight: 700; margin-bottom: 40px; color: #000;">Rating</h2>
					
					<div class="cmr-rating-container" style="display: flex; gap: 60px; margin-bottom: 50px;">
						<div class="cmr-rating-summary" style="display: flex; flex-direction: column; align-items: flex-start; min-width: 140px;">
							<?php
							$rating_count = $product->get_rating_count();
							$review_count = $product->get_review_count();
							$average      = $product->get_average_rating();
							
							// Calculate distribution
							$ratings = array(5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0);
							$comments = get_comments(array('post_id' => $product->get_id(), 'status' => 'approve'));
							foreach ($comments as $comment) {
								$rate = intval(get_comment_meta($comment->comment_ID, 'rating', true));
								if ($rate >= 1 && $rate <= 5) {
									$ratings[$rate]++;
								}
							}
							?>
							<div style="font-size: 64px; font-weight: 600; line-height: 1; color: #1e293b; letter-spacing: -1px;"><?php echo esc_html( number_format($average, 1) ); ?></div>
							<div class="cmr-lr-stars" style="color: #f59e0b; margin: 15px 0 0 0; font-size: 13px; letter-spacing: 2px;">
								<?php 
								$avg_f = floatval($average);
								for ( $s = 1; $s <= 5; $s++ ) {
									if ( $s <= $avg_f ) echo '<i class="fa-solid fa-star"></i>';
									elseif ( $s - 0.5 <= $avg_f ) echo '<i class="fa-solid fa-star-half-stroke"></i>';
									else echo '<i class="fa-regular fa-star"></i>';
								}
								?>
							</div>
							<div style="color: #64748b; font-size: 15px;"><?php echo esc_html($review_count); ?> Reviews</div>
						</div>
						
						<div class="cmr-rating-bars" style="flex: 1; max-width: 320px; display: flex; flex-direction: column; gap: 12px; justify-content: flex-start; padding-top: 5px;">
							<?php 
                            $bar_colors = array(
                                5 => '#22c55e',
                                4 => '#22c55e',
                                3 => '#facc15',
                                2 => '#f59e0b',
                                1 => '#ea580c'
                            );
                            for ($i = 5; $i >= 1; $i--): 
								$percent = $review_count > 0 ? ($ratings[$i] / $review_count) * 100 : 0;
                                $color = $bar_colors[$i];
							?>
							<div style="display: flex; align-items: center; gap: 15px;">
								<span style="width: 10px; font-weight: 700; color: #0f172a; font-size: 16px;"><?php echo $i; ?></span>
								<div style="flex: 1; height: 8px; background: #f8fafc; border-radius: 4px; overflow: hidden;">
									<div style="width: <?php echo $percent; ?>%; height: 100%; background: <?php echo $color; ?>; border-radius: 4px;"></div>
								</div>
							</div>
							<?php endfor; ?>
						</div>
					</div>
					

					<div class="cmr-reviews-list" style="display: flex; flex-direction: column; gap: 50px;">
						<?php 
						if ($comments) {
							foreach ($comments as $comment) {
								$rate = intval(get_comment_meta($comment->comment_ID, 'rating', true));
						?>
						<div class="cmr-review-item" style="display: flex; gap: 24px;">
							<div class="cmr-review-avatar" style="padding-top: 5px;">
								<?php echo get_avatar($comment, 56, '', '', array('class' => 'cmr-avatar-img', 'extra_attr' => 'style="border-radius:50%;"')); ?>
							</div>
                            <div class="cmr-review-content" style="flex: 1;">
                                <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 6px;">
								    <h4 style="margin: 0; font-size: 18px; font-weight: 700; color: #000; letter-spacing: 0.2px;"><?php echo get_comment_author($comment); ?></h4>
                                    <div class="cmr-lr-stars" style="color: #f59e0b; font-size: 14px; letter-spacing: 1px; display: flex; align-items: center; margin-top: 0;">
                                        <?php 
                                        for ( $s = 1; $s <= 5; $s++ ) {
                                            if ( $s <= $rate ) echo '<i class="fa-solid fa-star"></i>';
                                            else echo '<i class="fa-regular fa-star"></i>';
                                        }
                                        ?>
                                    </div>
                                </div>
								<div style="font-size: 14px; color: #000; margin-bottom: 18px;"><?php echo get_comment_date('M d, Y', $comment); ?> | <?php echo get_comment_time('g:i a', $comment); ?></div>
								<p style="margin: 0; color: #1e293b; font-size: 16px; line-height: 1.6; max-width: 900px;"><?php echo get_comment_text($comment); ?></p>
							</div>
						</div>
						<?php 
							}
						} else {
							echo '<p>No reviews yet.</p>';
						}
						?>
					</div>

                    <div class="cmr-reviews-footer" style="display: flex; align-items: center; gap: 30px; margin-top: 60px; padding-top: 0;">
                        <div style="font-size: 15px; font-weight: 600; color: #000;">View all <?php echo esc_html($review_count); ?> reviews</div>
                        <a href="javascript:void(0);" onclick="cmrOpenReviewModal();" style="color: #6d28d9; font-weight: 500; font-size: 15px; text-decoration: none; display: flex; align-items: center; gap: 4px;">Add a Review <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2 10L10 2M10 2H4M10 2V8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
                    </div>
				</div>
			</div>

			<!-- Tab Panel: Similar Reports by Industry -->
			<div id="tab-panel-industry-reports" class="tab-content-panel">
				<div class="cmr-product-section">
					<?php echo do_shortcode('[cmr_similar_reports]'); ?>
				</div>
			</div>
		</div>
	</div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
	var tabNavItems = document.querySelectorAll('.custom-tabs-nav .tab-nav-item');
	
	// Add smooth scrolling click event
	tabNavItems.forEach(function(item) {
		item.addEventListener('click', function() {
			var targetTab = this.getAttribute('data-tab');
			var targetSection = document.getElementById('tab-panel-' + targetTab);
			
			if (targetSection) {
			    // Calculate sticky header offset if needed, roughly 100px
				var offsetTop = targetSection.getBoundingClientRect().top + window.pageYOffset - 120;
				window.scrollTo({
					top: offsetTop,
					behavior: 'smooth'
				});
			}
		});
	});

	// Intersection Observer to highlight active nav item on scroll
	var observerOptions = {
		root: null,
		rootMargin: '-130px 0px -70% 0px',
		threshold: 0
	};
	
	var observer = new IntersectionObserver(function(entries) {
		entries.forEach(function(entry) {
			if (entry.isIntersecting) {
				var id = entry.target.getAttribute('id').replace('tab-panel-', '');
				tabNavItems.forEach(function(nav) {
					nav.classList.remove('active');
					if (nav.getAttribute('data-tab') === id) {
						nav.classList.add('active');
					}
				});
			}
		});
	}, observerOptions);

	document.querySelectorAll('.tab-content-panel').forEach(function(section) {
		observer.observe(section);
	});
});
</script>

<style>
/* Hide the default WooCommerce reviews list inside the modal since we only want the form */
#cmr-review-modal #comments {
    display: none !important;
}
#cmr-review-modal .cmr-modal-content {
	/* Ensure modal is centered nicely */
	margin: 0 auto;
}
</style>

<?php do_action( 'woocommerce_after_single_product' ); ?>
