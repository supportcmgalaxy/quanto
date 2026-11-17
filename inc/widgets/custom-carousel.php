<?php
/**
 * CMR Custom Image Carousel Widget with Width & Height Controls
 * Allows fixing and setting exact width and height for carousel in Elementor.
 */

if (!defined('ABSPATH')) {
    exit;
}

class Quanto_Custom_Carousel_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'quanto_custom_carousel';
    }

    public function get_title() {
        return esc_html__('CMR Image Carousel (Width & Height)', 'quanto');
    }

    public function get_icon() {
        return 'eicon-slider-album';
    }

    public function get_categories() {
        return ['quanto-addons', 'general'];
    }

    public function get_keywords() {
        return ['carousel', 'slider', 'image', 'banner', 'hero', 'width', 'height', 'quanto'];
    }

    protected function register_controls() {
        // CONTENT TAB - Images
        $this->start_controls_section(
            'section_content_images',
            [
                'label' => esc_html__('Images', 'quanto'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'gallery',
            [
                'label'       => esc_html__('Add Images', 'quanto'),
                'type'        => \Elementor\Controls_Manager::GALLERY,
                'default'     => [],
                'description' => esc_html__('Select one or more images for the carousel.', 'quanto'),
            ]
        );

        $this->add_control(
            'thumbnail_size',
            [
                'label'   => esc_html__('Image Resolution', 'quanto'),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => 'full',
                'options' => [
                    'full'         => esc_html__('Full Resolution', 'quanto'),
                    'large'        => esc_html__('Large (1024x1024)', 'quanto'),
                    'medium_large' => esc_html__('Medium Large (768x768)', 'quanto'),
                    'medium'       => esc_html__('Medium (300x300)', 'quanto'),
                ],
            ]
        );

        $this->end_controls_section();

        // CONTENT TAB - Carousel Settings
        $this->start_controls_section(
            'section_content_settings',
            [
                'label' => esc_html__('Carousel Settings', 'quanto'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'effect',
            [
                'label'   => esc_html__('Effect', 'quanto'),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => 'slide',
                'options' => [
                    'slide' => esc_html__('Slide', 'quanto'),
                    'fade'  => esc_html__('Fade', 'quanto'),
                ],
            ]
        );

        $this->add_control(
            'autoplay',
            [
                'label'        => esc_html__('Autoplay', 'quanto'),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Yes', 'quanto'),
                'label_off'    => esc_html__('No', 'quanto'),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'autoplay_delay',
            [
                'label'     => esc_html__('Autoplay Speed (ms)', 'quanto'),
                'type'      => \Elementor\Controls_Manager::NUMBER,
                'default'   => 3500,
                'condition' => [
                    'autoplay' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'pause_on_hover',
            [
                'label'        => esc_html__('Pause on Hover', 'quanto'),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition'    => [
                    'autoplay' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'loop',
            [
                'label'        => esc_html__('Infinite Loop', 'quanto'),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Yes', 'quanto'),
                'label_off'    => esc_html__('No', 'quanto'),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'speed',
            [
                'label'   => esc_html__('Animation Speed (ms)', 'quanto'),
                'type'    => \Elementor\Controls_Manager::NUMBER,
                'default' => 600,
            ]
        );

        $this->add_control(
            'show_arrows',
            [
                'label'        => esc_html__('Show Navigation Arrows', 'quanto'),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Yes', 'quanto'),
                'label_off'    => esc_html__('No', 'quanto'),
                'return_value' => 'yes',
                'default'      => 'no',
            ]
        );

        $this->add_control(
            'show_dots',
            [
                'label'        => esc_html__('Show Pagination Dots', 'quanto'),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Yes', 'quanto'),
                'label_off'    => esc_html__('No', 'quanto'),
                'return_value' => 'yes',
                'default'      => 'no',
            ]
        );

        $this->end_controls_section();

        // STYLE TAB - Width, Height & Layout
        $this->start_controls_section(
            'section_style_dimensions',
            [
                'label' => esc_html__('Width & Height', 'quanto'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'carousel_width',
            [
                'label'      => esc_html__('Width', 'quanto'),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vw'],
                'range'      => [
                    'px' => [
                        'min'  => 50,
                        'max'  => 1600,
                        'step' => 10,
                    ],
                    '%' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                    'vw' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'default'    => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .quanto-custom-carousel' => 'width: {{SIZE}}{{UNIT}} !important; max-width: 100%;',
                ],
            ]
        );

        $this->add_responsive_control(
            'carousel_height',
            [
                'label'      => esc_html__('Height', 'quanto'),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh', '%'],
                'range'      => [
                    'px' => [
                        'min'  => 100,
                        'max'  => 1400,
                        'step' => 10,
                    ],
                    'vh' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                    '%' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'default'    => [
                    'unit' => '%',
                    'size' => 85,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .quanto-custom-carousel' => 'height: {{SIZE}}{{UNIT}} !important;',
                    '{{WRAPPER}} .quanto-custom-carousel .swiper' => 'height: 100% !important;',
                    '{{WRAPPER}} .quanto-custom-carousel .swiper-wrapper' => 'height: 100% !important;',
                    '{{WRAPPER}} .quanto-custom-carousel .swiper-slide' => 'height: 100% !important;',
                    '{{WRAPPER}} .quanto-custom-carousel .swiper-slide img' => 'height: {{SIZE}}{{UNIT}} !important; max-height: {{SIZE}}{{UNIT}} !important;',
                    '{{WRAPPER}} .quanto-custom-carousel img' => 'height: {{SIZE}}{{UNIT}} !important; max-height: {{SIZE}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->add_responsive_control(
            'alignment',
            [
                'label'     => esc_html__('Alignment', 'quanto'),
                'type'      => \Elementor\Controls_Manager::CHOOSE,
                'options'   => [
                    'left' => [
                        'title' => esc_html__('Left', 'quanto'),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'quanto'),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'quanto'),
                        'icon'  => 'eicon-text-align-right',
                    ],
                ],
                'default'   => 'center',
                'selectors' => [
                    '{{WRAPPER}} .quanto-custom-carousel-wrapper' => 'display: flex; justify-content: {{VALUE}};',
                ],
                'selectors_dictionary' => [
                    'left'   => 'flex-start',
                    'center' => 'center',
                    'right'  => 'flex-end',
                ],
            ]
        );

        $this->add_control(
            'object_fit',
            [
                'label'     => esc_html__('Image Fit', 'quanto'),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'default'   => 'cover',
                'options'   => [
                    'cover'      => esc_html__('Cover (Fill & Crop)', 'quanto'),
                    'contain'    => esc_html__('Contain (Fit without cropping)', 'quanto'),
                    'fill'       => esc_html__('Fill (Stretch)', 'quanto'),
                    'scale-down' => esc_html__('Scale Down', 'quanto'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .quanto-custom-carousel img' => 'object-fit: {{VALUE}} !important;',
                    '{{WRAPPER}} .quanto-custom-carousel .swiper-slide img' => 'object-fit: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'object_position',
            [
                'label'     => esc_html__('Image Position', 'quanto'),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'default'   => 'center center',
                'options'   => [
                    'center center' => esc_html__('Center Center', 'quanto'),
                    'top center'    => esc_html__('Top Center', 'quanto'),
                    'bottom center' => esc_html__('Bottom Center', 'quanto'),
                    'center left'   => esc_html__('Center Left', 'quanto'),
                    'center right'  => esc_html__('Center Right', 'quanto'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .quanto-custom-carousel img' => 'object-position: {{VALUE}} !important;',
                    '{{WRAPPER}} .quanto-custom-carousel .swiper-slide img' => 'object-position: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_responsive_control(
            'border_radius',
            [
                'label'      => esc_html__('Border Radius', 'quanto'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .quanto-custom-carousel' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .quanto-custom-carousel .swiper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .quanto-custom-carousel .swiper-slide' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .quanto-custom-carousel img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // STYLE TAB - Arrows
        $this->start_controls_section(
            'section_style_arrows',
            [
                'label'     => esc_html__('Navigation Arrows', 'quanto'),
                'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_arrows' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'arrow_color',
            [
                'label'     => esc_html__('Arrow Color', 'quanto'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .quanto-carousel-arrow' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrow_bg',
            [
                'label'     => esc_html__('Background Color', 'quanto'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => 'rgba(0,0,0,0.4)',
                'selectors' => [
                    '{{WRAPPER}} .quanto-carousel-arrow' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrow_size',
            [
                'label'     => esc_html__('Arrow Size', 'quanto'),
                'type'      => \Elementor\Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'min' => 20,
                        'max' => 80,
                    ],
                ],
                'default'   => [
                    'size' => 44,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .quanto-carousel-arrow' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; font-size: calc({{SIZE}}{{UNIT}} * 0.45);',
                ],
            ]
        );

        $this->end_controls_section();

        // STYLE TAB - Dots
        $this->start_controls_section(
            'section_style_dots',
            [
                'label'     => esc_html__('Pagination Dots', 'quanto'),
                'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_dots' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'dot_color',
            [
                'label'     => esc_html__('Dot Color', 'quanto'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => 'rgba(255,255,255,0.5)',
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullet' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'dot_active_color',
            [
                'label'     => esc_html__('Active Dot Color', 'quanto'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullet-active' => 'background: {{VALUE}} !important;',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        try {
            $settings = $this->get_settings_for_display();
            $gallery  = (!empty($settings['gallery']) && is_array($settings['gallery'])) ? $settings['gallery'] : [];

            $is_edit = false;
            if ( class_exists('\Elementor\Plugin') && isset(\Elementor\Plugin::$instance) && isset(\Elementor\Plugin::$instance->editor) && method_exists(\Elementor\Plugin::$instance->editor, 'is_edit_mode') ) {
                $is_edit = \Elementor\Plugin::$instance->editor->is_edit_mode();
            }

            if (empty($gallery)) {
                if ($is_edit) {
                    ?>
                    <div style="border: 2px dashed #9ca3af; padding: 40px; text-align: center; border-radius: 12px; background: #f9fafb; color: #4b5563;">
                        <i class="eicon-slider-album" style="font-size: 36px; color: #3b82f6; display: block; margin-bottom: 12px;"></i>
                        <strong style="font-size: 16px;"><?php esc_html_e('CMR Image Carousel', 'quanto'); ?></strong>
                        <p style="margin: 8px 0 0; font-size: 13px;"><?php esc_html_e('Click here to select images in the sidebar and configure height and width.', 'quanto'); ?></p>
                    </div>
                    <?php
                }
                return;
            }

            $uid        = uniqid('cmr-carousel-');
            $uid_safe   = str_replace('-', '_', $uid);
            $count      = count($gallery);
            $is_slider  = ($count > 1);

            $effect      = !empty($settings['effect']) ? $settings['effect'] : 'slide';
            $autoplay    = (!empty($settings['autoplay']) && $settings['autoplay'] === 'yes');
            $delay       = !empty($settings['autoplay_delay']) ? intval($settings['autoplay_delay']) : 3500;
            $hover       = (!empty($settings['pause_on_hover']) && $settings['pause_on_hover'] === 'yes');
            $loop        = (!empty($settings['loop']) && $settings['loop'] === 'yes' && $is_slider);
            $speed       = !empty($settings['speed']) ? intval($settings['speed']) : 600;
            $show_arrows = (!empty($settings['show_arrows']) && $settings['show_arrows'] === 'yes' && $is_slider);
            $show_dots   = (!empty($settings['show_dots']) && $settings['show_dots'] === 'yes' && $is_slider);
            $img_size    = !empty($settings['thumbnail_size']) ? $settings['thumbnail_size'] : 'full';
            ?>

            <style>
                #<?php echo esc_attr($uid); ?>-wrapper {
                    width: 100%;
                }
                #<?php echo esc_attr($uid); ?> {
                    position: relative;
                    overflow: hidden;
                    box-sizing: border-box;
                    height: 85% !important;
                }
                #<?php echo esc_attr($uid); ?> .swiper {
                    width: 100% !important;
                    height: 100% !important;
                    position: relative;
                    overflow: hidden;
                }
                #<?php echo esc_attr($uid); ?> .swiper-wrapper {
                    display: flex !important;
                    flex-wrap: nowrap !important;
                    width: 100% !important;
                    height: 100% !important;
                    will-change: transform;
                    transition: transform <?php echo intval($speed); ?>ms cubic-bezier(0.25, 1, 0.5, 1);
                }
                #<?php echo esc_attr($uid); ?> .swiper-slide,
                #<?php echo esc_attr($uid); ?> .quanto-single-slide {
                    width: 100% !important;
                    max-width: 100% !important;
                    height: 100% !important;
                    max-height: 100% !important;
                    flex-shrink: 0;
                    overflow: hidden;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    box-sizing: border-box !important;
                }
                #<?php echo esc_attr($uid); ?> .swiper-slide img,
                #<?php echo esc_attr($uid); ?> .quanto-single-slide img,
                #<?php echo esc_attr($uid); ?> img,
                #<?php echo esc_attr($uid); ?> .quanto-carousel-img {
                    width: 100% !important;
                    max-width: 100% !important;
                    height: 85% !important;
                    max-height: 85% !important;
                    display: block !important;
                    box-sizing: border-box !important;
                }
                @media (max-width: 768px) {
                    #<?php echo esc_attr($uid); ?>-wrapper {
                        width: 100% !important;
                        max-width: 100% !important;
                        padding: 0 !important;
                        margin: 0 !important;
                        display: flex !important;
                        justify-content: center !important;
                    }
                    #<?php echo esc_attr($uid); ?> {
                        width: 100% !important;
                        max-width: 100% !important;
                        height: auto !important;
                        min-height: auto !important;
                        border-radius: 0 !important;
                    }
                    #<?php echo esc_attr($uid); ?> .swiper,
                    #<?php echo esc_attr($uid); ?> .swiper-wrapper,
                    #<?php echo esc_attr($uid); ?> .swiper-slide,
                    #<?php echo esc_attr($uid); ?> .quanto-single-slide {
                        width: 100% !important;
                        max-width: 100% !important;
                        height: auto !important;
                    }
                    #<?php echo esc_attr($uid); ?> .swiper-slide img,
                    #<?php echo esc_attr($uid); ?> .quanto-single-slide img,
                    #<?php echo esc_attr($uid); ?> img,
                    #<?php echo esc_attr($uid); ?> .quanto-carousel-img {
                        width: 100% !important;
                        max-width: 100% !important;
                        height: auto !important;
                        max-height: none !important;
                        aspect-ratio: 1 / 1 !important;
                        object-fit: cover !important;
                        border-radius: 0 !important;
                    }
                }
                #<?php echo esc_attr($uid); ?> .quanto-carousel-arrow {
                    position: absolute;
                    top: 50%;
                    transform: translateY(-50%);
                    z-index: 10;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    cursor: pointer;
                    border-radius: 50%;
                    transition: all 0.25s ease;
                    border: none;
                    outline: none;
                    user-select: none;
                }
                #<?php echo esc_attr($uid); ?> .quanto-carousel-prev {
                    left: 16px;
                }
                #<?php echo esc_attr($uid); ?> .quanto-carousel-next {
                    right: 16px;
                }
                #<?php echo esc_attr($uid); ?> .quanto-carousel-arrow:hover {
                    transform: translateY(-50%) scale(1.08);
                }
                #<?php echo esc_attr($uid); ?> .swiper-pagination {
                    position: absolute;
                    bottom: 16px;
                    left: 0;
                    width: 100%;
                    text-align: center;
                    z-index: 10;
                }
                #<?php echo esc_attr($uid); ?> .swiper-pagination-bullet {
                    display: inline-block;
                    width: 10px;
                    height: 10px;
                    border-radius: 50%;
                    margin: 0 4px;
                    cursor: pointer;
                    background: rgba(255, 255, 255, 0.45);
                    transition: all 0.25s ease;
                }
                #<?php echo esc_attr($uid); ?> .swiper-pagination-bullet-active {
                    background: #ffffff !important;
                    transform: scale(1.25);
                }
            </style>

            <div class="quanto-custom-carousel-wrapper" id="<?php echo esc_attr($uid); ?>-wrapper">
                <div class="quanto-custom-carousel <?php echo $is_slider ? 'swiper' : ''; ?>" id="<?php echo esc_attr($uid); ?>">
                    <div class="<?php echo $is_slider ? 'swiper-wrapper' : 'quanto-single-wrapper'; ?>" style="width:100%;height:100%;">
                        <?php foreach ($gallery as $img): 
                            $image_html = '';
                            $img_id = (is_array($img) && !empty($img['id'])) ? intval($img['id']) : 0;
                            $img_url = (is_array($img) && !empty($img['url'])) ? $img['url'] : (is_string($img) ? $img : '');

                            if ($img_id > 0) {
                                $image_html = wp_get_attachment_image(
                                    $img_id,
                                    $img_size,
                                    false,
                                    [
                                        'class' => 'quanto-carousel-img',
                                        'alt'   => esc_attr__('Carousel Image', 'quanto'),
                                    ]
                                );
                            }

                            if (empty($image_html) && !empty($img_url)) {
                                $image_html = '<img src="' . esc_url($img_url) . '" alt="' . esc_attr__('Carousel Image', 'quanto') . '" class="quanto-carousel-img" />';
                            }
                        ?>
                            <div class="<?php echo $is_slider ? 'swiper-slide' : 'quanto-single-slide'; ?>" style="width:100%;height:100%;">
                                <?php echo $image_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <?php if ($show_arrows): ?>
                        <button class="quanto-carousel-arrow quanto-carousel-prev" aria-label="<?php esc_attr_e('Previous', 'quanto'); ?>">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg>
                        </button>
                        <button class="quanto-carousel-arrow quanto-carousel-next" aria-label="<?php esc_attr_e('Next', 'quanto'); ?>">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>
                        </button>
                    <?php endif; ?>

                    <?php if ($show_dots): ?>
                        <div class="swiper-pagination"></div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if ($is_slider): ?>
            <script>
            (function() {
                function initCarousel_<?php echo $uid_safe; ?>() {
                    var container = document.getElementById("<?php echo esc_js($uid); ?>");
                    if (!container || container._quantoInit) return;
                    container._quantoInit = true;

                    var wrapper = container.querySelector('.swiper-wrapper');
                    var slides  = container.querySelectorAll('.swiper-slide');
                    if (!wrapper || !slides.length) return;

                    var total = slides.length;
                    var currentIndex = 0;
                    var isLoop = <?php echo $loop ? 'true' : 'false'; ?>;
                    var autoDelay = <?php echo intval($delay); ?>;
                    var isAutoplay = <?php echo $autoplay ? 'true' : 'false'; ?>;
                    var pauseOnHover = <?php echo $hover ? 'true' : 'false'; ?>;
                    var speed = <?php echo intval($speed); ?>;
                    var autoTimer = null;
                    var isPaused = false;

                    // Setup pagination dots
                    var pagination = container.querySelector('.swiper-pagination');
                    if (pagination) {
                        pagination.innerHTML = '';
                        for (var i = 0; i < total; i++) {
                            var dot = document.createElement('span');
                            dot.className = 'swiper-pagination-bullet' + (i === 0 ? ' swiper-pagination-bullet-active' : '');
                            dot.setAttribute('data-index', i);
                            dot.setAttribute('role', 'button');
                            dot.setAttribute('aria-label', 'Go to slide ' + (i + 1));
                            (function(idx) {
                                dot.addEventListener('click', function(e) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                    goToSlide(idx);
                                    resetTimer();
                                });
                            })(i);
                            pagination.appendChild(dot);
                        }
                    }

                    function updatePagination() {
                        if (!pagination) return;
                        var dots = pagination.querySelectorAll('.swiper-pagination-bullet');
                        dots.forEach(function(d, idx) {
                            if (idx === currentIndex) {
                                d.classList.add('swiper-pagination-bullet-active');
                            } else {
                                d.classList.remove('swiper-pagination-bullet-active');
                            }
                        });
                    }

                    function goToSlide(idx) {
                        if (idx < 0) {
                            idx = isLoop ? total - 1 : 0;
                        } else if (idx >= total) {
                            idx = isLoop ? 0 : total - 1;
                        }
                        currentIndex = idx;
                        wrapper.style.transition = 'transform ' + speed + 'ms cubic-bezier(0.25, 1, 0.5, 1)';
                        wrapper.style.transform = 'translate3d(' + (-currentIndex * 100) + '%, 0, 0)';
                        updatePagination();
                    }

                    function nextSlide() {
                        goToSlide(currentIndex + 1);
                    }

                    function prevSlide() {
                        goToSlide(currentIndex - 1);
                    }

                    // Navigation buttons
                    var prevBtn = container.querySelector('.quanto-carousel-prev');
                    var nextBtn = container.querySelector('.quanto-carousel-next');

                    if (prevBtn) {
                        prevBtn.addEventListener('click', function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            prevSlide();
                            resetTimer();
                        });
                    }

                    if (nextBtn) {
                        nextBtn.addEventListener('click', function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            nextSlide();
                            resetTimer();
                        });
                    }

                    // Autoplay timer
                    function startTimer() {
                        if (!isAutoplay || total <= 1 || isPaused) return;
                        stopTimer();
                        autoTimer = setInterval(nextSlide, Math.max(1000, autoDelay));
                    }

                    function stopTimer() {
                        if (autoTimer) {
                            clearInterval(autoTimer);
                            autoTimer = null;
                        }
                    }

                    function resetTimer() {
                        stopTimer();
                        startTimer();
                    }

                    if (pauseOnHover) {
                        container.addEventListener('mouseenter', function() {
                            isPaused = true;
                            stopTimer();
                        });
                        container.addEventListener('mouseleave', function() {
                            isPaused = false;
                            startTimer();
                        });
                    }

                    document.addEventListener('visibilitychange', function() {
                        if (document.hidden) {
                            isPaused = true;
                            stopTimer();
                        } else {
                            isPaused = false;
                            startTimer();
                        }
                    });

                    // Touch swipe
                    var touchStartX = 0, touchEndX = 0, isTouching = false;
                    container.addEventListener('touchstart', function(e) {
                        if (e.touches && e.touches.length > 0) {
                            touchStartX = e.touches[0].clientX;
                            touchEndX = touchStartX;
                            isTouching = true;
                            stopTimer();
                        }
                    }, { passive: true });

                    container.addEventListener('touchmove', function(e) {
                        if (isTouching && e.touches && e.touches.length > 0) {
                            touchEndX = e.touches[0].clientX;
                        }
                    }, { passive: true });

                    container.addEventListener('touchend', function() {
                        if (!isTouching) return;
                        isTouching = false;
                        var diff = touchStartX - touchEndX;
                        if (Math.abs(diff) > 40) {
                            if (diff > 0) {
                                nextSlide();
                            } else {
                                prevSlide();
                            }
                        }
                        startTimer();
                    });

                    // Initial state
                    goToSlide(0);
                    startTimer();
                }

                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', initCarousel_<?php echo $uid_safe; ?>);
                } else {
                    initCarousel_<?php echo $uid_safe; ?>();
                }
                window.addEventListener('load', initCarousel_<?php echo $uid_safe; ?>);

                if (window.jQuery) {
                    jQuery(window).on('elementor/frontend/init', function() {
                        setTimeout(initCarousel_<?php echo $uid_safe; ?>, 200);
                        if (window.elementorFrontend && elementorFrontend.hooks) {
                            elementorFrontend.hooks.addAction('frontend/element_ready/quanto_custom_carousel.default', function() {
                                initCarousel_<?php echo $uid_safe; ?>();
                            });
                        }
                    });
                }
            })();
            </script>
            <?php endif; ?>
            <?php
        } catch (\Throwable $e) {
            if (defined('WP_DEBUG') && WP_DEBUG) {
                echo '<div style="color:red;padding:10px;">Carousel Error: ' . esc_html($e->getMessage()) . '</div>';
            }
        }
    }
}
