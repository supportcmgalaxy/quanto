<?php

if (!defined('ABSPATH')) {
    exit;
}

class Quanto_Logo_Carousel_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'quanto_logo_carousel';
    }

    public function get_title() {
        return esc_html__('Logo Carousel', 'quanto');
    }

    public function get_icon() {
        return 'eicon-slider-push';
    }

    public function get_categories() {
        return ['quanto-addons', 'general'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__('Logos', 'quanto'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'gallery',
            [
                'label' => esc_html__('Add Images', 'quanto'),
                'type' => \Elementor\Controls_Manager::GALLERY,
                'default' => [],
            ]
        );

        $this->add_control(
            'slides_to_show',
            [
                'label' => esc_html__('Slides to Show', 'quanto'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 5,
            ]
        );

        $this->add_control(
            'continuous',
            [
                'label' => esc_html__('Continuous Ticker?', 'quanto'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'quanto'),
                'label_off' => esc_html__('No', 'quanto'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        // Styling section
        $this->start_controls_section(
            'style_section',
            [
                'label' => esc_html__('Box Style', 'quanto'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'box_height',
            [
                'label' => esc_html__('Box Height (px)', 'quanto'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 120,
                'selectors' => [
                    '{{WRAPPER}} .quanto-logo-slide' => 'height: {{VALUE}}px;',
                ],
            ]
        );

        $this->add_control(
            'border_color',
            [
                'label' => esc_html__('Border Color', 'quanto'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#e5e7eb',
                'selectors' => [
                    '{{WRAPPER}} .quanto-logo-carousel-container' => 'border: 1px solid {{VALUE}};',
                    '{{WRAPPER}} .quanto-logo-slide' => 'border-right: 1px solid {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'grayscale',
            [
                'label' => esc_html__('Grayscale Images?', 'quanto'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'quanto'),
                'label_off' => esc_html__('No', 'quanto'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        $gallery = !empty($settings['gallery']) && is_array($settings['gallery']) ? $settings['gallery'] : [];
        if (empty($gallery)) {
            if ( class_exists('\Elementor\Plugin') && isset(\Elementor\Plugin::$instance) && isset(\Elementor\Plugin::$instance->editor) && method_exists(\Elementor\Plugin::$instance->editor, 'is_edit_mode') && \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
                echo '<div style="padding: 20px; text-align: center; background: #f9f9f9; border: 1px dashed #ccc;">' . esc_html__('Please select logos in the widget settings.', 'quanto') . '</div>';
            }
            return;
        }

        $gallery_items = [];
        foreach ($gallery as $image) {
            $img_url = '';
            if (is_array($image) && !empty($image['url'])) {
                $img_url = $image['url'];
            } elseif (is_array($image) && !empty($image['id'])) {
                $img_url = wp_get_attachment_image_url($image['id'], 'full');
            } elseif (is_string($image)) {
                $img_url = $image;
            }
            if (!empty($img_url)) {
                $gallery_items[] = $img_url;
            }
        }
        if (empty($gallery_items)) return;

        $uid = uniqid('logo-carousel-');
        $uid_safe = str_replace('-', '_', $uid);
        $is_continuous = isset($settings['continuous']) && $settings['continuous'] === 'yes';
        $is_grayscale = isset($settings['grayscale']) && $settings['grayscale'] === 'yes';
        $slides_count = !empty($settings['slides_to_show']) ? intval($settings['slides_to_show']) : 5;

        $get_logo_item_classes = function($img_url) {
            $classes = [];
            $lower = strtolower($img_url);
            if (strpos($lower, 'iqoo') !== false || strpos($lower, 'qoo') !== false) {
                $classes[] = 'logo-item-iqoo';
            }
            if (strpos($lower, 'scaler') !== false) {
                $classes[] = 'logo-item-scaler';
            }
            if (strpos($lower, 'epam') !== false) {
                $classes[] = 'logo-item-epam';
            }
            if (strpos($lower, 'vi') !== false && (strpos($lower, 'business') !== false || preg_match('/[\/\-_]vi[\/\-_.]/i', $img_url) || strpos($lower, 'vi-') !== false || strpos($lower, 'vi.') !== false || strpos($lower, 'vi_') !== false)) {
                $classes[] = 'logo-item-vi';
            }
            return !empty($classes) ? ' ' . implode(' ', $classes) : '';
        };

        // Ensure the initial items list has enough slides to fill the viewport if fewer than visible count are uploaded
        $display_items = $gallery_items;
        if ($is_continuous && count($display_items) <= $slides_count) {
            while (count($display_items) <= ($slides_count + 1)) {
                $display_items = array_merge($display_items, $gallery_items);
            }
        }
        ?>
        
        <style>
            .quanto-logo-carousel-container {
                overflow: hidden;
                width: 100%;
                border: 1px solid #e5e7eb;
                box-sizing: border-box;
                position: relative;
            }
            .quanto-logo-slide {
                display: flex;
                justify-content: center;
                align-items: center;
                padding: 8px 15px;
                border-right: 1px solid #e5e7eb;
                box-sizing: border-box;
                height: 120px;
                flex-shrink: 0;
                overflow: hidden;
            }
            .quanto-logo-slide img {
                max-width: 90%;
                max-height: 96px;
                width: auto;
                height: auto;
                object-fit: contain;
                display: block;
                transform: scale(1.35);
                transform-origin: center center;
                <?php if ($is_grayscale): ?>
                filter: grayscale(100%) opacity(0.8);
                transition: filter 0.3s ease;
                <?php endif; ?>
            }
            .quanto-logo-slide img[src*="qoo" i],
            .quanto-logo-slide img[src*="iqoo" i],
            .quanto-logo-slide.logo-item-iqoo img {
                max-width: 68% !important;
                max-height: 48px !important;
                transform: scale(0.9) !important;
            }
            .quanto-logo-slide img[src*="scaler" i],
            .quanto-logo-slide.logo-item-scaler img {
                max-width: 72% !important;
                max-height: 38px !important;
                transform: scale(0.85) !important;
            }
            .quanto-logo-slide img[src*="epam" i],
            .quanto-logo-slide.logo-item-epam img {
                max-width: 74% !important;
                max-height: 42px !important;
                transform: scale(0.88) !important;
            }
            .quanto-logo-slide img[src*="vi" i],
            .quanto-logo-slide.logo-item-vi img {
                max-width: 55% !important;
                max-height: 52px !important;
                transform: scale(0.9) !important;
            }

            <?php if ($is_grayscale): ?>
            .quanto-logo-slide img:hover {
                filter: grayscale(0%) opacity(1);
            }
            <?php endif; ?>

            @media (max-width: 768px) {
                .quanto-logo-slide {
                    height: 105px;
                    padding: 6px 12px;
                    overflow: hidden;
                }
                .quanto-logo-slide img {
                    max-height: 85px;
                    max-width: 88%;
                    transform: scale(1.3);
                    transform-origin: center center;
                }
            }

            @media (max-width: 480px) {
                .quanto-logo-slide {
                    height: 95px;
                    padding: 5px 10px;
                    overflow: hidden;
                }
                .quanto-logo-slide img {
                    max-height: 75px;
                    max-width: 88%;
                    transform: scale(1.25);
                    transform-origin: center center;
                }
            }

            <?php if ($is_continuous): ?>
            #<?php echo esc_attr($uid); ?> {
                --slides-visible: <?php echo $slides_count; ?>;
            }
            @media (max-width: 1024px) {
                #<?php echo esc_attr($uid); ?> {
                    --slides-visible: <?php echo max(3, $slides_count - 2); ?>;
                }
            }
            @media (max-width: 768px) {
                #<?php echo esc_attr($uid); ?> {
                    --slides-visible: 2;
                }
            }
            #<?php echo esc_attr($uid); ?> .quanto-logo-marquee-track {
                display: flex;
                align-items: center;
                width: max-content;
                will-change: transform;
            }
            #<?php echo esc_attr($uid); ?> .quanto-logo-slide {
                width: calc(var(--carousel-width, 100vw) / var(--slides-visible));
                flex: 0 0 calc(var(--carousel-width, 100vw) / var(--slides-visible));
            }
            <?php else: ?>
            .quanto-logo-carousel-wrapper {
                display: flex;
                align-items: center;
            }
            <?php endif; ?>
        </style>

        <?php if ($is_continuous): 
            // Duplicate array for seamless infinite marquee loop in CSS
            $marquee_items = array_merge($display_items, $display_items);
        ?>
        <style>
            @keyframes quantoMarquee_<?php echo esc_attr($uid_safe); ?> {
                0% { transform: translate3d(0, 0, 0); }
                100% { transform: translate3d(-50%, 0, 0); }
            }
            #<?php echo esc_attr($uid); ?> .quanto-logo-marquee-track {
                display: flex;
                align-items: center;
                width: max-content;
                animation: quantoMarquee_<?php echo esc_attr($uid_safe); ?> 40s linear infinite;
                will-change: transform;
            }
            #<?php echo esc_attr($uid); ?>:hover .quanto-logo-marquee-track,
            #<?php echo esc_attr($uid); ?>:active .quanto-logo-marquee-track {
                animation-play-state: paused;
            }
        </style>
        <div class="quanto-logo-carousel-container quanto-logo-marquee" id="<?php echo esc_attr($uid); ?>">
            <div class="quanto-logo-marquee-track">
                <?php foreach ($marquee_items as $img_url): ?>
                    <div class="quanto-logo-slide<?php echo $get_logo_item_classes($img_url); ?>">
                        <img src="<?php echo esc_url($img_url); ?>" alt="Logo" loading="lazy" decoding="async">
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <?php else: ?>

        <div class="quanto-logo-carousel-container swiper-container" id="<?php echo esc_attr($uid); ?>">
            <div class="quanto-logo-carousel-wrapper swiper-wrapper">
                <?php foreach ($gallery_items as $img_url): ?>
                    <div class="quanto-logo-slide swiper-slide<?php echo $get_logo_item_classes($img_url); ?>">
                        <img src="<?php echo esc_url($img_url); ?>" alt="Logo">
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <script>
        (function() {
            function initLogoSwiper_<?php echo $uid_safe; ?>() {
                var el = document.getElementById("<?php echo esc_js($uid); ?>");
                if (!el) return;
                if (el.dataset.swiperInit === 'true' && el.swiper) return;
                if (typeof Swiper !== 'undefined') {
                    try {
                        if (el.swiper) {
                            el.swiper.destroy(true, true);
                        }
                        el.dataset.swiperInit = 'true';
                        new Swiper(el, {
                            slidesPerView: <?php echo $slides_count; ?>,
                            spaceBetween: 0,
                            loop: true,
                            speed: 800,
                            autoplay: {
                                delay: 3000,
                                disableOnInteraction: false,
                                pauseOnMouseEnter: true,
                            },
                            breakpoints: {
                                320: { slidesPerView: 2 },
                                768: { slidesPerView: <?php echo max(3, $slides_count - 2); ?> },
                                1024: { slidesPerView: <?php echo $slides_count; ?> }
                            }
                        });
                    } catch(e) {}
                }
            }
            if (document.readyState === 'loading') {
                document.addEventListener("DOMContentLoaded", initLogoSwiper_<?php echo $uid_safe; ?>);
            } else {
                initLogoSwiper_<?php echo $uid_safe; ?>();
            }
            if (window.jQuery) {
                jQuery(window).on('elementor/frontend/init', function() {
                    if (window.elementorFrontend && elementorFrontend.hooks) {
                        elementorFrontend.hooks.addAction('frontend/element_ready/quanto_logo_carousel.default', function() {
                            initLogoSwiper_<?php echo $uid_safe; ?>();
                        });
                    }
                });
            }
        })();
        </script>
        <?php endif; ?>
        <?php
    }
}
