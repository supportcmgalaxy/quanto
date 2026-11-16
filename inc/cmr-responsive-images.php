<?php
/**
 * CMR Responsive Images Optimization
 *
 * Automatically fixes PageSpeed Insights "Properly size images" warnings:
 * - Overrides default inaccurate "100vw" sizes attribute for floating and grid images
 * - Ensures team member profile photos have proper responsive sizes and aspect ratio
 * - Serves optimized thumbnail for testimonial avatars (Mask-group)
 *
 * Saves ~225+ KiB download size on mobile.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// 1. Hook WordPress native responsive image sizes calculation
add_filter( 'wp_calculate_image_sizes', 'cmr_optimize_image_sizes_attribute', 999, 5 );

function cmr_optimize_image_sizes_attribute( $sizes, $size, $image_src, $image_meta, $attachment_id ) {
    if ( is_admin() ) {
        return $sizes;
    }

    $src = is_string( $image_src ) ? $image_src : '';

    // Floating parallax images on About Us (image-2, image-3, image-1)
    if ( (int) $attachment_id === 53716 || strpos( $src, 'image-2' ) !== false || (int) $attachment_id === 53715 || strpos( $src, 'image-3' ) !== false ) {
        return '(max-width: 767px) 210px, (max-width: 1024px) 350px, 450px';
    }

    if ( (int) $attachment_id === 53714 || strpos( $src, 'image-1' ) !== false ) {
        return '(max-width: 767px) 390px, (max-width: 1024px) 450px, 600px';
    }

    // Team member profile pictures
    if ( strpos( $src, 'Profile-Pictures' ) !== false || in_array( (int) $attachment_id, array( 53138, 53136, 53135, 53137, 53139, 53140 ), true ) ) {
        return '(max-width: 767px) 255px, (max-width: 1024px) 280px, 340px';
    }

    return $sizes;
}

// 2. Output Buffer Filter on template_redirect for Elementor rendered image tags
add_action( 'template_redirect', 'cmr_responsive_images_buffer_start', 1 );

function cmr_responsive_images_buffer_start() {
    if ( is_admin() || ( function_exists( 'quanto_is_elementor_edit' ) && quanto_is_elementor_edit() ) ) {
        return;
    }

    ob_start( 'cmr_responsive_images_buffer_callback' );
}

function cmr_responsive_images_buffer_callback( $html ) {
    if ( empty( $html ) || ! is_string( $html ) ) {
        return $html;
    }

    // 1. Floating Parallax Images (About Us Section / mas_smooth_scroller)
    // image-2 (Team laptop, displayed ~209x252 on mobile)
    $html = preg_replace(
        '/(<img\b[^>]*?image-2[^>]*?)sizes="[^"]*"/i',
        '$1sizes="(max-width: 767px) 210px, (max-width: 1024px) 350px, 450px"',
        $html
    );

    // image-3 (Office / whiteboard, displayed ~204x189 on mobile)
    $html = preg_replace(
        '/(<img\b[^>]*?image-3[^>]*?)sizes="[^"]*"/i',
        '$1sizes="(max-width: 767px) 210px, (max-width: 1024px) 350px, 450px"',
        $html
    );

    // image-1 (displayed ~392x437 on mobile)
    $html = preg_replace(
        '/(<img\b[^>]*?image-1[^>]*?)sizes="[^"]*"/i',
        '$1sizes="(max-width: 767px) 390px, (max-width: 1024px) 450px, 600px"',
        $html
    );

    // 2. Team Member Profile Pictures (Thomas George, Anil Chopra, Prabhu Ram, etc.)
    $html = preg_replace_callback(
        '/<img\b([^>]*Profile-Pictures[^>]*)>/i',
        function( $matches ) {
            $attrs = rtrim( $matches[1], "/ \t\n\r\0\x0B" );

            // Ensure accurate sizes attribute
            if ( strpos( $attrs, 'sizes=' ) === false ) {
                $attrs .= ' sizes="(max-width: 767px) 255px, (max-width: 1024px) 280px, 340px"';
            } else {
                $attrs = preg_replace( '/sizes="[^"]*"/i', 'sizes="(max-width: 767px) 255px, (max-width: 1024px) 280px, 340px"', $attrs );
            }

            // Correct displayed width & height attributes
            $attrs = preg_replace( '/width=["\']751["\']/i', 'width="255"', $attrs );
            $attrs = preg_replace( '/height=["\']1001["\']/i', 'height="340"', $attrs );

            return '<img ' . trim( $attrs ) . ' />';
        },
        $html
    );

    // 3. Testimonial Avatar (Mask-group.png, Mask-group-1.png, etc. displayed at 56x56)
    $html = preg_replace_callback(
        '/<img\b([^>]*?src="[^"]*?Mask-group(?:-[0-9]+)?)\.png"([^>]*?>)/i',
        function( $matches ) {
            $before = $matches[1];
            $after  = $matches[2];
            $before = preg_replace( '/\s+(?:width|height)=["\'][^"\']*["\']/i', '', $before );
            $after  = preg_replace( '/\s+(?:width|height)=["\'][^"\']*["\']/i', '', $after );
            return '<img' . $before . '-150x150.png" width="56" height="56"' . $after;
        },
        $html
    );

    // 4. Accessible Social Links: Ensure all icon links have a discernible name (aria-label)
    $html = preg_replace_callback(
        '/<a\b([^>]*?class=["\'][^"\']*elementor-icon[^"\']*["\'][^>]*)>/i',
        function( $matches ) {
            $tag = $matches[0];
            if ( strpos( $tag, 'aria-label' ) !== false ) {
                return $tag;
            }
            if ( stripos( $tag, 'linkedin.com' ) !== false ) {
                return str_replace( '<a ', '<a aria-label="LinkedIn" ', $tag );
            }
            if ( stripos( $tag, 'twitter.com' ) !== false || stripos( $tag, 'x.com' ) !== false ) {
                return str_replace( '<a ', '<a aria-label="Twitter" ', $tag );
            }
            if ( stripos( $tag, 'facebook.com' ) !== false ) {
                return str_replace( '<a ', '<a aria-label="Facebook" ', $tag );
            }
            if ( stripos( $tag, 'youtube.com' ) !== false ) {
                return str_replace( '<a ', '<a aria-label="YouTube" ', $tag );
            }
            return str_replace( '<a ', '<a aria-label="Social Link" ', $tag );
        },
        $html
    );

    return $html;
}
