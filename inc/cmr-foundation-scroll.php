<?php
/*
 * Foundation Scroll Component Shortcode
 * Shortcode: [cmr_foundation_scroll]
 */

add_shortcode('cmr_foundation_scroll', 'cmr_foundation_scroll_shortcode');

function cmr_foundation_scroll_shortcode($atts) {
    ob_start();
    ?>
    <style>
        .cmr-foundation-wrap, .cmr-foundation-wrap * { box-sizing: border-box; }

        .cmr-foundation-wrap {
            position: relative;
            background: #ffffff;
            color: #1a1a2e;
            overflow: visible;
            margin-top: 0 !important;
            margin-bottom: 0 !important;
            padding-top: 0 !important;
            padding-bottom: 0 !important;
        }

        /* Reset parent Elementor container and shortcode widget padding */
        [data-id="0e46dc4"],
        [data-id="0e46dc4"] > .e-con-inner,
        [data-id="0e46dc4"] .elementor-widget-container,
        [data-id="8c80839"],
        [data-id="8c80839"] > .e-con-inner,
        [data-id="8c80839"] .elementor-widget-container {
            padding-top: 0 !important;
            padding-bottom: 0 !important;
            margin-top: 0 !important;
            margin-bottom: 0 !important;
        }

        /* Connect next section (Meet the people / Team: 0888bae) with clean, equal spacing */
        [data-id="0888bae"],
        [data-id="0888bae"] > .e-con-inner,
        [data-id="0888bae"] [data-id="e9e2ef0"] {
            margin-top: 0 !important;
            padding-top: 0 !important;
        }

        .cmr-foundation-panel {
            width: 100%;
            height: auto !important;
            min-height: auto !important;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            z-index: 2;
            padding-top: 140px !important;
            padding-bottom: 140px !important;
        }

        .cmr-foundation-inner {
            display: flex;
            flex-direction: column;
            width: 100%;
            max-width: 1280px;
            padding: 0 20px;
            margin: 0 auto;
        }

        .cmr-foundation-title {
            font-size: 42px;
            font-weight: 600;
            line-height: 1.2;
            letter-spacing: -1px;
            max-width: 600px;
            margin-bottom: 35px;
            font-family: 'Instrument Sans', sans-serif;
            color: #111;
        }

        .cmr-foundation-content {
            display: flex;
            align-items: flex-start;
        }

        .cmr-foundation-left {
            flex: 0 0 33.3333%; /* 4 columns out of 12 */
            padding-right: 60px;
            border-right: 1px solid #eaeaea;
        }

        .cmr-foundation-nav-item {
            display: block;
            background: transparent;
            border: none;
            padding: 0;
            text-align: left;
            width: 100%;
            font-size: 32px;
            font-weight: 600;
            color: #6e6e73; /* Accessible 5.4:1 contrast ratio against #ffffff */
            margin-bottom: 30px;
            cursor: pointer;
            transition: color 0.4s ease;
            font-family: 'Instrument Sans', sans-serif;
            letter-spacing: -1px;
            outline: none;
        }

        .cmr-foundation-nav-item:focus-visible {
            outline: 2px solid #4e2ecf;
            outline-offset: 4px;
            border-radius: 4px;
        }

        .cmr-foundation-nav-item:hover,
        .cmr-foundation-nav-item.active {
            color: #4e2ecf; /* Purple color matching the image (7.8:1 contrast) */
        }

        .cmr-foundation-right {
            flex: 0 0 66.6666%; /* 8 columns out of 12 */
            position: relative;
        }

        .cmr-foundation-block {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            opacity: 0;
            visibility: hidden; /* Accessible hidden state prevents contrast checks on inactive slides */
            transform: translateY(30px);
            transition: opacity 0.5s ease, transform 0.5s ease, visibility 0.5s ease;
            pointer-events: none;
            padding-left: 100px;
        }

        .cmr-foundation-block:first-child {
            position: relative;
        }

        .cmr-foundation-block.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
            pointer-events: auto;
        }

        .cmr-foundation-block-title {
            font-size: 36px;
            font-weight: 600;
            line-height: 1.2;
            letter-spacing: -1px;
            margin: 0 0 20px 0;
            font-family: 'Instrument Sans', sans-serif;
            color: #111;
        }

        .cmr-foundation-block-desc {
            font-size: 16px;
            color: #444;
            line-height: 1.6;
            font-family: 'Instrument Sans', sans-serif;
            margin: 0;
        }

        @media (max-width: 768px) {
            .cmr-foundation-wrap { height: auto !important; }
            .cmr-foundation-panel { position: relative !important; height: auto !important; padding: 40px 15px !important; text-align: center !important; justify-content: center !important; align-items: center !important; }
            .cmr-foundation-inner { text-align: center !important; align-items: center !important; justify-content: center !important; margin: 0 auto !important; width: 100% !important; }
            .cmr-foundation-title { font-size: 28px !important; margin: 0 auto 30px auto !important; text-align: center !important; width: 100% !important; }
            .cmr-foundation-content { flex-direction: column !important; gap: 24px !important; align-items: center !important; text-align: center !important; width: 100% !important; }
            .cmr-foundation-left { flex: none !important; width: 100% !important; display: flex !important; justify-content: center !important; align-items: center !important; gap: 24px !important; overflow-x: auto !important; padding-right: 0 !important; padding-bottom: 12px !important; border-bottom: 1px solid #eaeaea !important; border-right: none !important; margin: 0 auto 20px auto !important; }
            .cmr-foundation-nav-item { font-size: 20px !important; margin-bottom: 0 !important; white-space: nowrap !important; text-align: center !important; width: auto !important; }
            .cmr-foundation-right { flex: none !important; width: 100% !important; text-align: center !important; display: flex !important; flex-direction: column !important; align-items: center !important; justify-content: center !important; }
            .cmr-foundation-block { position: relative !important; opacity: 1 !important; visibility: visible !important; pointer-events: auto !important; transform: none !important; display: none; padding-left: 0 !important; text-align: center !important; width: 100% !important; margin: 0 auto !important; }
            .cmr-foundation-block.show { display: block !important; }
            .cmr-foundation-block-title { font-size: 26px !important; text-align: center !important; margin: 0 auto 15px auto !important; }
            .cmr-foundation-block-desc { font-size: 16px !important; color: #444 !important; line-height: 1.6 !important; text-align: center !important; margin: 0 auto !important; max-width: 100% !important; }
        }
    </style>

    <div class="cmr-foundation-wrap">
        <div class="cmr-foundation-panel">
            <div class="cmr-foundation-inner">
                <h2 class="cmr-foundation-title">The Foundation Behind Every Insight We Deliver</h2>
                
                <div class="cmr-foundation-content">
                    <div class="cmr-foundation-left" role="tablist" aria-label="Foundation Pillars">
                        <button type="button" class="cmr-foundation-nav-item active" data-index="0" role="tab" aria-selected="true" aria-controls="foundation-block-0" id="foundation-tab-0">Purpose</button>
                        <button type="button" class="cmr-foundation-nav-item" data-index="1" role="tab" aria-selected="false" aria-controls="foundation-block-1" id="foundation-tab-1">Vision</button>
                        <button type="button" class="cmr-foundation-nav-item" data-index="2" role="tab" aria-selected="false" aria-controls="foundation-block-2" id="foundation-tab-2">Mission</button>
                    </div>
                    
                    <div class="cmr-foundation-right">
                        <div class="cmr-foundation-block show" data-index="0" role="tabpanel" id="foundation-block-0" aria-labelledby="foundation-tab-0">
                            <h3 class="cmr-foundation-block-title">Empowering Better Decisions</h3>
                            <p class="cmr-foundation-block-desc">We help organizations navigate complexity, make informed decisions, and unlock opportunities for sustained growth.</p>
                        </div>
                        
                        <div class="cmr-foundation-block" data-index="1" role="tabpanel" id="foundation-block-1" aria-labelledby="foundation-tab-1">
                            <h3 class="cmr-foundation-block-title">Empowering What’s Next</h3>
                            <p class="cmr-foundation-block-desc">We envision a world where every organization anticipates change with confidence and transforms market insight into competitive advantage.</p>
                        </div>
                        
                        <div class="cmr-foundation-block" data-index="2" role="tabpanel" id="foundation-block-2" aria-labelledby="foundation-tab-2">
                            <h3 class="cmr-foundation-block-title">Excellence in Action</h3>
                            <p class="cmr-foundation-block-desc">We combine independent research, market intelligence, and strategic consulting with advisory and go-to-market expertise to help organizations shape markets, accelerate growth, and create lasting ecosystem value.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Failsafe wait for GSAP
            function initFoundationScroll() {
                if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') {
                    setTimeout(initFoundationScroll, 50);
                    return;
                }
                
                // If on mobile, ignore GSAP pinning entirely for better UX
                if (window.innerWidth <= 768) {
                    initMobileFoundation();
                    return;
                }
                
                gsap.registerPlugin(ScrollTrigger);
                
                const wraps = document.querySelectorAll('.cmr-foundation-wrap');
                
                wraps.forEach(wrap => {
                    const panel = wrap.querySelector('.cmr-foundation-panel');
                    const blocks = wrap.querySelectorAll('.cmr-foundation-block');
                    const navItems = wrap.querySelectorAll('.cmr-foundation-nav-item');
                    const totalBlocks = blocks.length;
                    
                    if (totalBlocks <= 1) return;
                    
                    // Set scroll duration (amount of pinning) - relaxed so user can comfortably read each slide
                    const scrollDuration = totalBlocks * 350;
                    
                    ScrollTrigger.create({
                        trigger: wrap,
                        start: "top top+=80", // Account for sticky headers if any
                        end: "+=" + scrollDuration,
                        pin: panel,
                        scrub: 0.5, // Smooth easing for pleasant scrolling
                        onUpdate: self => {
                            // Calculate current active slide index based on progress
                            let rawIndex = self.progress * totalBlocks;
                            let idx = Math.min(totalBlocks - 1, Math.floor(rawIndex));
                            
                            // Prevent precision issues at the very end
                            if (self.progress > 0.99) idx = totalBlocks - 1;
                            
                            // Update active classes and aria-selected
                            navItems.forEach((m, i) => {
                                const isActive = i === idx;
                                m.classList.toggle("active", isActive);
                                m.setAttribute("aria-selected", isActive ? "true" : "false");
                            });
                            blocks.forEach((s, i) => s.classList.toggle("show", i === idx));
                        }
                    });
                    
                    // Click handlers for menu items to switch active slide and scroll smoothly
                    navItems.forEach((item, i) => {
                        item.addEventListener('click', function() {
                            navItems.forEach(m => {
                                m.classList.remove('active');
                                m.setAttribute('aria-selected', 'false');
                            });
                            blocks.forEach(s => s.classList.remove('show'));
                            item.classList.add('active');
                            item.setAttribute('aria-selected', 'true');
                            if (blocks[i]) blocks[i].classList.add('show');

                            const st = ScrollTrigger.getAll().find(t => t.trigger === wrap);
                            if (st) {
                                const targetY = st.start + (i / (totalBlocks - 1 || 1)) * (st.end - st.start);
                                window.scrollTo({ top: targetY, behavior: 'smooth' });
                            }
                        });
                    });
                });
            }
            
            function initMobileFoundation() {
                const wraps = document.querySelectorAll('.cmr-foundation-wrap');
                wraps.forEach(wrap => {
                    const blocks = wrap.querySelectorAll('.cmr-foundation-block');
                    const navItems = wrap.querySelectorAll('.cmr-foundation-nav-item');
                    
                    navItems.forEach((item, i) => {
                        item.addEventListener('click', function() {
                            navItems.forEach(m => {
                                m.classList.remove('active');
                                m.setAttribute('aria-selected', 'false');
                            });
                            blocks.forEach(s => s.classList.remove('show'));
                            
                            this.classList.add('active');
                            this.setAttribute('aria-selected', 'true');
                            blocks[i].classList.add('show');
                        });
                    });
                });
            }
            
            initFoundationScroll();
        });
    </script>
    <?php
    return ob_get_clean();
}
