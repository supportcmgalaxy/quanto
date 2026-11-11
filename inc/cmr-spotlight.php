<?php
/**
 * CMR Spotlight Shortcode
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'cmr_spotlight', 'cmr_render_spotlight_shortcode' );

function cmr_render_spotlight_shortcode( $atts ) {
    // Enqueue styles
    wp_enqueue_style( 'cmr-spotlight-style', get_template_directory_uri() . '/assets/css/cmr-spotlight.css', array(), time() );

    ob_start();
    ?>
    <div class="cmr-spotlight-wrapper">
        <div class="cmr-spotlight-grid">
            
            <!-- Title -->
            <div class="cmr-spotlight-title-cell">
                <h2>CMR in the Spotlight</h2>
            </div>

            <!-- Row 1 Logos -->
            <div class="cmr-spotlight-cell r1-c4">
                <span class="cmr-spotlight-brand">Times of India</span>
            </div>
            <div class="cmr-spotlight-cell r1-c5">
                <span class="cmr-spotlight-brand">CNBC</span>
            </div>

            <!-- Row 2 Logos -->
            <div class="cmr-spotlight-cell r2-c1">
                <span class="cmr-spotlight-brand">NDTV</span>
            </div>
            <div class="cmr-spotlight-cell r2-c2">
                <span class="cmr-spotlight-brand">ABP News</span>
            </div>
            <div class="cmr-spotlight-cell r2-c3">
                <span class="cmr-spotlight-brand">BBC News</span>
            </div>
            <div class="cmr-spotlight-cell r2-c4">
                <span class="cmr-spotlight-brand">Aaj Tak</span>
            </div>
            <div class="cmr-spotlight-cell r2-c5">
                <span class="cmr-spotlight-brand">CNN</span>
            </div>
            <div class="cmr-spotlight-cell r2-c6">
                <span class="cmr-spotlight-brand">India Today</span>
            </div>

            <!-- Row 3 Logos -->
            <div class="cmr-spotlight-cell r3-c2">
                <span class="cmr-spotlight-brand">Zee News</span>
            </div>
            <div class="cmr-spotlight-cell r3-c3">
                <span class="cmr-spotlight-brand">Republic Bharat</span>
            </div>

        </div>
    </div>
    <?php
    return ob_get_clean();
}
