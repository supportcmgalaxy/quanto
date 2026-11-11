<?php
/**
 * CMR Media Contacts Shortcode
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'cmr_media_contacts', 'cmr_render_media_contacts_shortcode' );

function cmr_render_media_contacts_shortcode( $atts ) {
    // Enqueue styles
    wp_enqueue_style( 'cmr-media-contacts-style', get_template_directory_uri() . '/assets/css/cmr-media-contacts.css', array(), time() );

    // Optional attributes
    $atts = shortcode_atts( array(
        // We can add attributes here if needed in the future
    ), $atts, 'cmr_media_contacts' );

    ob_start();
    ?>
    <div class="cmr-media-contacts-wrapper">
        <div class="cmr-media-contacts-header">
            <h2>Global Media Contacts</h2>
            <p>Connect with our global communications team for press inquiries, expert commentary, and media resources.</p>
        </div>

        <div class="cmr-media-contacts-grid">
            <div class="cmr-media-contact-card">
                <div class="cmr-media-contact-img" style="background-image: url('<?php echo esc_url( home_url( '/wp-content/uploads/2026/05/Avatar-Image.svg' ) ); ?>');"></div>
                <div class="cmr-media-contact-info">
                    <h4>Rose Hines</h4>
                    <p>CMR Services</p>
                    <a href="mailto:press@cmr.com" class="cmr-media-contact-email">
                        <svg width="18" height="14" viewBox="0 0 18 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M1 2.5C1 1.67157 1.67157 1 2.5 1H15.5C16.3284 1 17 1.67157 17 2.5V11.5C17 12.3284 16.3284 13 15.5 13H2.5C1.67157 13 1 12.3284 1 11.5V2.5Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M1 3L9 8L17 3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                </div>
            </div>

            <div class="cmr-media-contact-card">
                <div class="cmr-media-contact-img" style="background-image: url('<?php echo esc_url( home_url( '/wp-content/uploads/2026/05/Avatar-Image-1.svg' ) ); ?>');"></div>
                <div class="cmr-media-contact-info">
                    <h4>Eric Yates</h4>
                    <p>CMR Services</p>
                    <a href="mailto:press@cmr.com" class="cmr-media-contact-email">
                        <svg width="18" height="14" viewBox="0 0 18 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M1 2.5C1 1.67157 1.67157 1 2.5 1H15.5C16.3284 1 17 1.67157 17 2.5V11.5C17 12.3284 16.3284 13 15.5 13H2.5C1.67157 13 1 12.3284 1 11.5V2.5Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M1 3L9 8L17 3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <div class="cmr-media-testimonial-card">
            <blockquote>
                “ In an era defined by rapid transitions, Cyber Media Research (CMR) provided us with the definitive roadmap we needed. Their deep-dive insights into the evolving EV ecosystem, coupled with high-fidelity market penetration data, transformed how we evaluated regional demand.
            </blockquote>
            <div class="cmr-media-testimonial-divider"></div>
            <div class="cmr-media-testimonial-author">
                <div class="cmr-media-author-logo">
                    <!-- Fake TOI logo using text/color for placeholder -->
                    <div style="background: #E31837; color: white; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; font-weight: bold; border-radius: 8px; font-size: 14px;">TOI</div>
                </div>
                <div class="cmr-media-author-info">
                    <h4>Richard Joseph</h4>
                    <p>Marketing Coordinator</p>
                </div>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
