<?php
/**
 * Elementor Widget: CMR Featured Video Insight
 * Allows setting Video URL, Poster, Title, Date, Duration, and Watch Link
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Quanto_Featured_Video_Insight_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'cmr_featured_video_insight';
    }

    public function get_title() {
        return esc_html__( 'CMR Featured Video Insight', 'quanto' );
    }

    public function get_icon() {
        return 'eicon-video-camera';
    }

    public function get_categories() {
        return [ 'quanto-addons', 'general' ];
    }

    public function get_keywords() {
        return [ 'video', 'featured', 'insight', 'card', 'cmr', 'watch', 'duration' ];
    }

    protected function register_controls() {

        // ==========================================
        // CONTENT SECTION: Video Settings
        // ==========================================
        $this->start_controls_section(
            'section_video',
            [
                'label' => esc_html__( 'Video & Media Settings', 'quanto' ),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'video_url',
            [
                'label'       => esc_html__( 'Video URL', 'quanto' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'https://www.youtube.com/watch?v=... or MP4 URL',
                'default'     => 'https://www.youtube.com/watch?v=ScMzIvxBSi4',
                'description' => esc_html__( 'Supports YouTube, Vimeo, direct MP4 file URLs or iframe embed codes.', 'quanto' ),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'video_poster',
            [
                'label'       => esc_html__( 'Video Poster / Cover Image', 'quanto' ),
                'type'        => \Elementor\Controls_Manager::MEDIA,
                'description' => esc_html__( 'Optional cover image if using self-hosted video or placeholder.', 'quanto' ),
            ]
        );

        $this->add_control(
            'aspect_ratio',
            [
                'label'   => esc_html__( 'Video Aspect Ratio', 'quanto' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => '56.25%',
                'options' => [
                    '56.25%' => esc_html__( '16:9 (Standard)', 'quanto' ),
                    '75%'    => esc_html__( '4:3', 'quanto' ),
                    '100%'   => esc_html__( '1:1 (Square)', 'quanto' ),
                    '66.66%' => esc_html__( '3:2', 'quanto' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .cmr-fvi-video-wrap' => 'padding-top: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();


        // ==========================================
        // CONTENT SECTION: Card Details
        // ==========================================
        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__( 'Card Details', 'quanto' ),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'date_text',
            [
                'label'       => esc_html__( 'Date', 'quanto' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'default'     => '09 April 2026',
                'placeholder' => '09 April 2026',
            ]
        );

        $this->add_control(
            'duration_text',
            [
                'label'       => esc_html__( 'Time Duration', 'quanto' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'default'     => '1 min read',
                'placeholder' => '1 min read',
            ]
        );

        $this->add_control(
            'title_text',
            [
                'label'       => esc_html__( 'Title', 'quanto' ),
                'type'        => \Elementor\Controls_Manager::TEXTAREA,
                'default'     => 'From ideas to innovation – Exclusive conversations with the trailblazers of India’s EV journey',
                'placeholder' => esc_html__( 'Enter insight title...', 'quanto' ),
                'rows'        => 3,
            ]
        );

        $this->add_control(
            'btn_text',
            [
                'label'       => esc_html__( 'Button Text', 'quanto' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'default'     => 'Read Insight',
                'placeholder' => 'Read Insight',
            ]
        );

        $this->add_control(
            'target_link',
            [
                'label'         => esc_html__( 'Watch Page Link (URL)', 'quanto' ),
                'type'          => \Elementor\Controls_Manager::URL,
                'placeholder'   => 'https://your-site.com/video-page',
                'show_external' => true,
                'default'       => [
                    'url'         => '#',
                    'is_external' => false,
                    'nofollow'    => false,
                ],
            ]
        );

        $this->add_responsive_control(
            'card_max_width',
            [
                'label'      => esc_html__( 'Card Max Width (px)', 'quanto' ),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range'      => [
                    'px' => [
                        'min' => 280,
                        'max' => 1200,
                        'step' => 10,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 600,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .cmr-fvi-card-wrapper' => 'max-width: {{SIZE}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->end_controls_section();


        // ==========================================
        // STYLE SECTION: Card Container
        // ==========================================
        $this->start_controls_section(
            'section_style_card',
            [
                'label' => esc_html__( 'Card Container', 'quanto' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'card_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'quanto' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .cmr-fvi-card' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .cmr-fvi-body' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'card_border_color',
            [
                'label'     => esc_html__( 'Border Color', 'quanto' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#E5E7EB',
                'selectors' => [
                    '{{WRAPPER}} .cmr-fvi-card' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'card_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'quanto' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .cmr-fvi-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'card_body_padding',
            [
                'label'      => esc_html__( 'Body Padding', 'quanto' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .cmr-fvi-body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();


        // ==========================================
        // STYLE SECTION: Typography & Colors
        // ==========================================
        $this->start_controls_section(
            'section_style_typography',
            [
                'label' => esc_html__( 'Typography & Colors', 'quanto' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        // Date & Duration Style
        $this->add_control(
            'heading_meta_style',
            [
                'label'     => esc_html__( 'Date & Duration', 'quanto' ),
                'type'      => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'meta_color',
            [
                'label'     => esc_html__( 'Meta Text Color', 'quanto' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#475569',
                'selectors' => [
                    '{{WRAPPER}} .cmr-fvi-meta' => 'color: {{VALUE}} !important;',
                    '{{WRAPPER}} .cmr-fvi-date' => 'color: {{VALUE}} !important;',
                    '{{WRAPPER}} .cmr-fvi-duration' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'dash_color',
            [
                'label'     => esc_html__( 'Divider Dash Color', 'quanto' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#64748B',
                'selectors' => [
                    '{{WRAPPER}} .cmr-fvi-dash' => 'background-color: {{VALUE}} !important;',
                ],
            ]
        );

        // Title Style
        $this->add_control(
            'heading_title_style',
            [
                'label'     => esc_html__( 'Title Style', 'quanto' ),
                'type'      => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => esc_html__( 'Title Color', 'quanto' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#0F172A',
                'selectors' => [
                    '{{WRAPPER}} .cmr-fvi-title' => 'color: {{VALUE}} !important;',
                    '{{WRAPPER}} .cmr-fvi-title a' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'title_hover_color',
            [
                'label'     => esc_html__( 'Title Hover Color', 'quanto' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#4F46E5',
                'selectors' => [
                    '{{WRAPPER}} .cmr-fvi-title a:hover' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .cmr-fvi-title, {{WRAPPER}} .cmr-fvi-title a',
            ]
        );

        // Watch Button Style
        $this->add_control(
            'heading_button_style',
            [
                'label'     => esc_html__( 'Watch Link Button', 'quanto' ),
                'type'      => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'btn_color',
            [
                'label'     => esc_html__( 'Button Color', 'quanto' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#0F172A',
                'selectors' => [
                    '{{WRAPPER}} .cmr-fvi-btn' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'btn_hover_color',
            [
                'label'     => esc_html__( 'Button Hover Color', 'quanto' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#4F46E5',
                'selectors' => [
                    '{{WRAPPER}} .cmr-fvi-btn:hover' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $video_url = isset( $settings['video_url'] ) ? $settings['video_url'] : '';
        $poster    = '';
        $poster_img_html = '';
        if ( ! empty( $settings['video_poster']['id'] ) ) {
            $poster = wp_get_attachment_image_url( $settings['video_poster']['id'], 'large' ) ?: wp_get_attachment_image_url( $settings['video_poster']['id'], 'full' );
            $poster_img_html = wp_get_attachment_image( $settings['video_poster']['id'], 'large', false, array(
                'class'         => 'cmr-fvi-poster-img',
                'fetchpriority' => 'high',
                'decoding'      => 'async',
                'sizes'         => '(max-width: 767px) 100vw, 680px',
            ) );
        } elseif ( ! empty( $settings['video_poster']['url'] ) ) {
            $poster = $settings['video_poster']['url'];
        }

        $date      = isset( $settings['date_text'] ) ? $settings['date_text'] : '';
        $duration  = ! empty( $settings['duration_text'] ) && $settings['duration_text'] !== '22:44 min' && $settings['duration_text'] !== '1 min' ? $settings['duration_text'] : '1 min read';
        $title     = isset( $settings['title_text'] ) ? $settings['title_text'] : '';
        $btn_text  = ! empty( $settings['btn_text'] ) && $settings['btn_text'] !== 'Watch' && $settings['btn_text'] !== 'Insight' ? $settings['btn_text'] : 'Read Insight';
        
        $link_url  = '#';
        $target    = '_self';
        $nofollow  = '';

        if ( ! empty( $settings['target_link']['url'] ) ) {
            $link_url = $settings['target_link']['url'];
            if ( ! empty( $settings['target_link']['is_external'] ) ) {
                $target = '_blank';
            }
            if ( ! empty( $settings['target_link']['nofollow'] ) ) {
                $nofollow = 'rel="nofollow"';
            }
        }

        $video_embed = ! empty( $video_url ) && function_exists( 'cmr_get_video_embed_html' )
            ? cmr_get_video_embed_html( $video_url, $poster )
            : '';

        ?>
        <div class="cmr-fvi-card-wrapper">
            <div class="cmr-fvi-card">
                
                <!-- Video / Media Box -->
                <div class="cmr-fvi-video-wrap">
                    <?php if ( ! empty( $video_embed ) ) : ?>
                        <?php echo $video_embed; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    <?php elseif ( ! empty( $poster_img_html ) ) : ?>
                        <a href="<?php echo esc_url( $link_url ); ?>" class="cmr-fvi-poster-link" target="<?php echo esc_attr( $target ); ?>" <?php echo esc_attr( $nofollow ); ?>>
                            <?php echo $poster_img_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        </a>
                    <?php elseif ( ! empty( $poster ) ) : ?>
                        <a href="<?php echo esc_url( $link_url ); ?>" class="cmr-fvi-poster-link" target="<?php echo esc_attr( $target ); ?>" <?php echo esc_attr( $nofollow ); ?>>
                            <img src="<?php echo esc_url( $poster ); ?>" alt="<?php echo esc_attr( $title ); ?>" class="cmr-fvi-poster-img" fetchpriority="high" decoding="async">
                        </a>
                    <?php else : ?>
                        <iframe src="https://www.youtube.com/embed/ScMzIvxBSi4?rel=0&modestbranding=1" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen loading="lazy"></iframe>
                    <?php endif; ?>
                </div>

                <!-- Body Content -->
                <div class="cmr-fvi-body">
                    <!-- Meta info: Date & Duration -->
                    <div class="cmr-fvi-meta">
                        <div class="cmr-fvi-date">
                            <span class="cmr-fvi-dash"></span>
                            <span class="cmr-fvi-date-txt"><?php echo esc_html( $date ); ?></span>
                        </div>
                        <?php if ( ! empty( $duration ) ) : ?>
                            <div class="cmr-fvi-duration"><?php echo esc_html( $duration ); ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Main Title -->
                    <?php if ( ! empty( $title ) ) : ?>
                        <h3 class="cmr-fvi-title">
                            <a href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $target ); ?>" <?php echo esc_attr( $nofollow ); ?>>
                                <?php echo esc_html( $title ); ?>
                            </a>
                        </h3>
                    <?php endif; ?>

                    <!-- Read Insight Button -->
                    <div class="cmr-fvi-action">
                        <a href="<?php echo esc_url( $link_url ); ?>" class="cmr-fvi-btn" target="<?php echo esc_attr( $target ); ?>" <?php echo esc_attr( $nofollow ); ?>>
                            <span class="cmr-fvi-btn-text"><?php echo esc_html( $btn_text ); ?></span>
                            <svg class="cmr-fvi-btn-arrow" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="7" y1="17" x2="17" y2="7"></line>
                                <polyline points="7 7 17 7 17 17"></polyline>
                            </svg>
                        </a>
                    </div>
                </div>

            </div>
        </div>
        <?php
    }
}
