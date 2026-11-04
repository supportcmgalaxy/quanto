<?php
/**
 * @Packge     : Quanto
 * @Version    : 1.0
 * @Author     : Mirrortheme
 * @Author URI : https://mirrortheme.com/
 *
 */

// Block direct access
if ( ! defined('ABSPATH') ) {
    exit;
}

get_header();
$query = get_search_query();
?>

<style>
.cmr-search-page {
    text-align: center;
    padding: 80px 20px;
    margin-top: 120px;
    background: #fff;
    min-height: 70vh;
}
.cmr-search-title {
    font-size: 60px;
    font-weight: 600;
    color: #111;
    margin-bottom: 20px;
    line-height: 1.2;
    letter-spacing: 2px;
    font-family: inherit;
}
.cmr-search-form-wrapper {
    max-width: 700px;
    margin: 0 auto 60px auto;
    position: relative;
}
.cmr-search-form-wrapper form {
    display: flex;
    align-items: center;
    border: 1px solid #6241ca; /* Purple border */
    border-radius: 50px;
    padding: 6px;
    background: #fff;
    box-shadow: 0 4px 20px rgba(124, 58, 237, 0.05);
}
.cmr-search-form-wrapper .cat-icon {
    padding-left: 20px;
    color: #475569;
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 500;
    font-size: 15px;
    white-space: nowrap;
}
.cmr-search-form-wrapper .cat-icon i {
    color: #6241ca;
    font-size: 18px;
}
.cmr-search-form-wrapper input {
    border: none;
    outline: none;
    flex-grow: 1;
    padding: 15px 20px;
    font-size: 16px;
    background: transparent;
    box-shadow: none;
    color: #333;
}
.cmr-search-form-wrapper input:focus {
    box-shadow: none;
    outline: none;
}
.cmr-search-form-wrapper button {
    background: #6241ca;
    color: #fff;
    border: none;
    border-radius: 50%;
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 20px;
    transition: all 0.3s ease;
}
.cmr-search-form-wrapper button:hover {
    background: #6241ca;
    transform: scale(1.05);
}
.cmr-search-empty-state {
    max-width: 600px;
    margin: 0 auto;
}
.cmr-search-illustration {
    margin-bottom: 30px;
    opacity: 0.8;
}
.cmr-search-empty-title {
    font-size: 32px;
    font-weight: 700;
    margin-bottom: 15px;
    color: #0f172a;
}
.cmr-search-empty-text {
    font-size: 16px;
    color: #64748b;
    margin-bottom: 50px;
    line-height: 1.6;
}
.cmr-search-empty-text strong {
    color: #1e293b;
}
.cmr-popular-topics-title {
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: #94a3b8;
    margin-bottom: 25px;
    font-weight: 600;
}
.cmr-popular-topics {
    display: flex;
    gap: 15px;
    justify-content: center;
    flex-wrap: wrap;
}
.cmr-topic-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    border: 1px solid #e2e8f0;
    border-radius: 30px;
    color: #475569;
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.3s ease;
    background: #fff;
}
.cmr-topic-btn i {
    font-size: 18px;
    color: #94a3b8;
    transition: all 0.3s ease;
}
.cmr-topic-btn:hover {
    border-color: #6241ca;
    color: #6241ca;
    box-shadow: 0 4px 12px rgba(124, 58, 237, 0.1);
}
.cmr-topic-btn:hover i {
    color: #6241ca;
}

/* Results List Styles */
.cmr-search-results-list {
    text-align: left;
    margin-top: 40px;
    max-width: 1280px;
    margin-left: auto;
    margin-right: auto;
}
.cmr-search-results-list .quanto-blog-box {
    margin-bottom: 40px;
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    transition: all 0.3s ease;
    border: 1px solid transparent;
}
.cmr-search-results-list .quanto-blog-box:hover {
    border-color: rgba(98, 65, 202, 0.3);
    background: rgba(98, 65, 202, 0.03);
    box-shadow: 0 4px 25px rgba(98, 65, 202, 0.06);
}
.cmr-search-results-list .quanto-blog-box:hover .quanto-blog-content h5 a,
.cmr-search-results-list .quanto-blog-box:hover .quanto-blog-content p,
.cmr-search-results-list .quanto-blog-box:hover .quanto-blog-content .blog-text,
.cmr-search-results-list .quanto-blog-box:hover .quanto-blog-content .read-more-btn,
.cmr-search-results-list .quanto-blog-box:hover .post-meta,
.cmr-search-results-list .quanto-blog-box:hover .post-meta a,
.cmr-search-results-list .quanto-blog-box:hover .post-meta span {
    color: #6241ca !important;
}
.cmr-search-results-list .quanto-blog-box > div {
    display: flex;
    flex-direction: row;
    gap: 40px;
    align-items: center;
}
.cmr-search-results-list .quanto-blog-thumb,
.cmr-search-results-list .post-thumb {
    flex: 0 0 350px;
    margin-bottom: 0 !important;
}
.cmr-search-results-list .quanto-blog-thumb img,
.cmr-search-results-list .post-thumb img {
    border-radius: 8px;
    width: 100%;
    height: auto;
    object-fit: cover;
}
.cmr-search-results-list .quanto-blog-content {
    flex: 1;
    padding: 0 !important;
}
.cmr-search-results-list .quanto-blog-content h5 {
    font-size: 18px !important;
    line-height: 1.4;
    margin-bottom: 12px;
}
.cmr-search-results-list .quanto-blog-content p,
.cmr-search-results-list .quanto-blog-content .blog-text {
    font-size: 14px !important;
    line-height: 1.6;
    margin-bottom: 15px;
}
.cmr-search-results-list .blog-text {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

@media (max-width: 768px) {
    .cmr-search-results-list .quanto-blog-box > div {
        flex-direction: column;
        gap: 20px;
    }
    .cmr-search-results-list .quanto-blog-thumb,
    .cmr-search-results-list .post-thumb {
        flex: 0 0 auto;
        width: 100%;
    }
}

/* Pagination Styles to Match Design */
.cmr-pagination {
    margin-top: 60px;
}
.cmr-pagination .nav-links {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 8px;
    width: 100%;
}
.cmr-pagination .page-numbers {
    display: inline-flex;
    justify-content: center;
    align-items: center;
    width: 44px;
    height: 44px;
    border-radius: 50%;
    text-decoration: none;
    color: #333;
    font-size: 16px;
    font-weight: 500;
    transition: all 0.3s ease;
}
.cmr-pagination .page-numbers.current {
    background: #4820B0; /* Purple background for active */
    color: #fff;
    box-shadow: 0 4px 15px rgba(72, 32, 176, 0.2);
}
.cmr-pagination .page-numbers:not(.current):hover {
    color: #4820B0;
    background: #f8fafc;
}
.cmr-pagination .page-numbers.prev,
.cmr-pagination .page-numbers.next {
    color: #6241ca; /* Purple arrows */
    font-size: 24px;
    background: transparent;
}
.cmr-pagination .page-numbers.prev:hover,
.cmr-pagination .page-numbers.next:hover {
    background: transparent;
    transform: scale(1.1);
}

/* Load More Button for Search */
.cmr-search-load-more-btn {
    background: transparent;
    border: 1px solid #ccc;
    color: #111;
    font-size: 14px;
    font-weight: 600;
    border-radius: 40px;
    cursor: pointer;
    transition: all 0.3s ease;
    width: 288px;
    height: 54px;
    display: inline-flex;
    justify-content: center;
    align-items: center;
}
.cmr-search-load-more-btn:hover {
    border-color: #6241ca;
    color: #6241ca;
}
.cmr-search-item-hidden {
    display: none !important;
}
</style>

<div class="cmr-search-page">
    <div class="container">
        
        <?php if ($query) : ?>
            <h1 class="cmr-search-title">&ldquo;<?php echo esc_html($query); ?>&rdquo;</h1>
        <?php endif; ?>
        
        <div class="cmr-search-form-wrapper">
            <form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                <div class="cat-icon">
                    <img src="https://cmrindia.com/wp-content/uploads/2026/06/cmrlogo-with-oly-c.svg" alt="CMR" style="height: 24px; width: auto;">
                </div>
                <input type="search" name="s" value="<?php echo esc_attr($query); ?>" placeholder="Search...">
                <button type="submit" aria-label="<?php esc_attr_e( 'Submit Search', 'quanto' ); ?>"><i class="ri-search-line"></i></button>
            </form>
        </div>

        <?php if ( have_posts() ) : ?>
            <div class="cmr-search-results-list">
                <div class="row">
                    <?php 
                    global $wp_query;
                    $total_search_posts = $wp_query->post_count;
                    $search_i = 0;
                    while ( have_posts() ) : the_post(); 
                        $search_i++;
                        $hidden_class = ($search_i > 10) ? ' cmr-search-item-hidden' : '';
                    ?>
                        <div class="col-12 cmr-search-item<?php echo $hidden_class; ?>">
                            <div class="quanto-blog-box fade-anim" data-delay="0.30" data-direction="right">
                                <?php get_template_part('templates/content', get_post_format()); ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>

                <?php if ($total_search_posts > 10): ?>
                    <div class="text-center mt-5 mb-5" id="cmr-search-load-more-wrap" style="width: 100%; display: none;">
                        <button id="cmr-search-load-more" class="cmr-search-load-more-btn">Load More</button>
                    </div>
                <?php endif; ?>

                <div class="cmr-pagination mt-4" id="cmr-search-pagination-wrap" style="display: flex; justify-content: center; width: 100%;">
                    <?php 
                    the_posts_pagination( array(
                        'prev_text' => '<i class="ri-arrow-left-s-line"></i>',
                        'next_text' => '<i class="ri-arrow-right-s-line"></i>',
                    ) ); 
                    ?>
                </div>

            </div>
        <?php else : ?>
            <div class="cmr-search-empty-state">
                <div class="cmr-search-illustration">
                    <!-- Clean minimal magnifying glass illustration -->
                    <svg width="220" height="160" viewBox="0 0 220 160" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <!-- Background abstract shapes (mountains/clouds) -->
                        <path d="M20 130 Q 50 80 80 120 T 150 100 T 200 130" fill="#f1f5f9" opacity="0.6"/>
                        <path d="M40 140 Q 90 90 120 130 T 210 120" fill="#e2e8f0" opacity="0.4"/>
                        <!-- Magnifying Glass -->
                        <circle cx="110" cy="65" r="35" stroke="#cbd5e1" stroke-width="12" fill="#f8fafc"/>
                        <circle cx="102" cy="57" r="15" fill="#e2e8f0" opacity="0.5"/>
                        <path d="M135 90 L165 120" stroke="#cbd5e1" stroke-width="14" stroke-linecap="round"/>
                        <!-- Small decorative elements -->
                        <rect x="50" y="110" width="8" height="12" rx="2" fill="#cbd5e1"/>
                        <rect x="150" y="140" width="16" height="20" rx="2" fill="#e2e8f0"/>
                        <ellipse cx="110" cy="145" rx="15" ry="4" fill="#cbd5e1" opacity="0.3"/>
                    </svg>
                </div>
                <h2 class="cmr-search-empty-title">No results found</h2>
                <p class="cmr-search-empty-text">
                    We couldn't find anything matching <strong>&ldquo;<?php echo esc_html($query); ?>&rdquo;</strong>.<br>
                    It might be a very specialized niche, or there's a typo in the query.
                </p>
                
                <div class="cmr-popular-topics-title">EXPLORE POPULAR TOPICS</div>
                <div class="cmr-popular-topics">
                    <a href="<?php echo home_url('/automotive'); ?>" class="cmr-topic-btn"><i class="ri-car-line"></i> Automotive</a>
                    <a href="<?php echo home_url('/consumer-tech'); ?>" class="cmr-topic-btn"><i class="ri-smartphone-line"></i> Consumer Tech</a>
                    <a href="<?php echo home_url('/it-telecom'); ?>" class="cmr-topic-btn"><i class="ri-base-station-line"></i> IT & Telecom</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php 
// Output requested Elementor templates at the bottom of the search page
$search_bottom_templates = array(
    'testimonials',
    'we-worked-with-largest-global-brands',
    'your-challenge-our-research-your-advantage',
    'fotter-card',
);

foreach ($search_bottom_templates as $slug) {
    $template_post = get_page_by_path($slug, OBJECT, 'quanto_tab_build');
    if ($template_post) {
        if (function_exists('cmr_print_elementor_css')) {
            cmr_print_elementor_css($template_post->ID);
        }
        if (class_exists('\Elementor\Plugin')) {
            echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display($template_post->ID);
        } else {
            echo apply_filters('the_content', $template_post->post_content);
        }
    }
}
?>

<?php get_footer(); ?>
