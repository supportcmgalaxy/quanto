<?php
/* Hero Banner Shortcode: [cmr_hero_banner] */

add_shortcode('cmr_hero_banner', 'cmr_hero_banner_shortcode');

function cmr_hero_banner_shortcode($atts) {
    $upload_dir   = wp_upload_dir();
    $base_upload  = ! empty( $upload_dir['baseurl'] ) ? $upload_dir['baseurl'] : 'https://qai8358l95-staging.onrocket.site/wp-content/uploads';
    $default_s1   = $base_upload . '/2026/10/CMR-Lead-Slider-1.jpg-2.jpeg';
    $default_s2   = $base_upload . '/2026/10/CMR-Lead-Slider-2.jpg-1.jpeg';
    $default_s1_m = $base_upload . '/2026/10/CMR-Lead-Slider-1.jpg-2-768x432.jpeg';
    $default_s2_m = $base_upload . '/2026/10/CMR-Lead-Slider-2.jpg-1-768x432.jpeg';

    $atts = shortcode_atts( array(
        'slider_1' => $default_s1,
        'slider_2' => $default_s2,
    ), $atts );
    ob_start();
    ?>
    <style>
    /* =========================
       HERO BASE
    ========================= */
    .hero {
      position: relative;
      min-height: 100vh;
      overflow: hidden;
      display: flex;
      flex-direction: column;
      justify-content: center;
      box-sizing: border-box;
      margin-top: 0 !important;
    }

    /* =========================
       BACKGROUND
    ========================= */
    .hero-video-wrapper {
      position: absolute;
      inset: 0;
      z-index: 1;
      overflow: hidden;
      background-color: #0b132b;
    }

    .hero-video-wrapper::after {
      content: '';
      position: absolute;
      inset: 0;
      background: linear-gradient(180deg, rgba(0, 0, 0, 0.15) 0%, rgba(0, 0, 0, 0.25) 100%);
      z-index: 3;
      pointer-events: none;
    }

    .hero-bg-slide {
      position: absolute;
      inset: 0;
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      opacity: 0;
      transition: opacity 1s cubic-bezier(0.4, 0, 0.2, 1);
      z-index: 1;
    }

    .hero-bg-slide.active {
      opacity: 1;
      z-index: 2;
    }

    /* =========================
       CONTENT (Dynamic Real-Time Alignment with Header)
    ========================= */
    .hero-content {
      position: relative;
      z-index: 3;
      color: white;
      width: 100%;
      box-sizing: border-box !important;
      padding-top: 0 !important;
      padding-bottom: 0 !important;
      padding-left: clamp(20px, 10vw, 220px);
      padding-right: 20px;
      margin: 0 !important;
      max-width: 100% !important;
    }

    /* =========================
       TEXT
    ========================= */
    .hero-title {
      font-size: 72px;
      line-height: 70px;
      letter-spacing: -2px;
      color:white;
      font-weight: 500;
      transition: opacity 0.5s ease, transform 0.5s ease;
    }

    .hero-title span {
      color: #00EDE9;
    }

    .hero-title.fade {
      opacity: 0;
      transform: translateY(20px);
    }

    .hero p,
    .hero-subtitle {
      margin-top: 20px !important;
      margin-bottom: 30px;
      font-size: 20px;
      line-height: 30px;
      max-width: 720px;
      transition: opacity 0.5s ease, transform 0.5s ease;
    }

    .hero-subtitle br.desktop-br {
      display: inline;
    }

    @media (max-width: 768px) {
      .hero-subtitle br.desktop-br {
        display: none;
      }
    }

    .hero p.fade,
    .hero-subtitle.fade {
      opacity: 0;
      transform: translateY(15px);
    }

    /* =========================
       BUTTONS
    ========================= */
    .hero .buttons,
    .buttons {
      display: flex;
      gap: 16px;
      align-items: center;
    }

    .hero .btn-primary,
    .btn-primary {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 8px !important;
        background: #FFFFFF !important;
        color: #0F0F0F !important;
        border: 1px solid #FFFFFF !important;
        border-radius: 50px !important;
        font-family: "Instrument Sans", sans-serif !important;
        font-size: 16px !important;
        font-weight: 500 !important;
        line-height: 1 !important;
        letter-spacing: -0.18px !important;
        text-decoration: none !important;
        padding: 16px 28px !important;
        box-sizing: border-box !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease !important;
        cursor: pointer !important;
        width: auto !important;
        min-width: unset !important;
        height: auto !important;
    }

    .hero .btn-primary:hover,
    .btn-primary:hover {
        background: #FFFFFF !important;
        color: #0F0F0F !important;
        transform: translateY(-2px) !important;
    }

    /* ===== BUTTON ICON ===== */
    .hero .hero-arrow-button-white,
    .hero-arrow-button-white {
        width: 14px !important;
        height: 14px !important;
        object-fit: contain !important;
        flex-shrink: 0 !important;
    }

    /* ===== TALK TO ANALYST BUTTON ===== */
    .hero .btn-outline,
    .btn-outline {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 8px !important;
        background: transparent !important;
        color: #FFFFFF !important;
        border: 1px solid rgba(255,255,255,0.75) !important;
        border-radius: 50px !important;
        font-family: "Instrument Sans", sans-serif !important;
        font-size: 16px !important;
        font-weight: 500 !important;
        line-height: 1 !important;
        letter-spacing: -0.14px !important;
        text-decoration: none !important;
        padding: 16px 28px !important;
        box-sizing: border-box !important;
        transition: transform 0.2s ease, background 0.2s ease, border-color 0.2s ease !important;
        cursor: pointer !important;
        width: auto !important;
        min-width: unset !important;
        height: auto !important;
    }

    /* ===== HOVER CHANGE ===== */
    .hero .btn-outline:hover,
    .btn-outline:hover {
        background: rgba(255,255,255,0.15) !important;
        color: #FFFFFF !important;
        border-color: #FFFFFF !important;
        transform: translateY(-2px) !important;
    }

    /* ===== ICON ===== */
    .hero .hero-arrow-button,
    .hero-arrow-button {
        width: 14px !important;
        height: 14px !important;
        object-fit: contain !important;
        flex-shrink: 0 !important;
    }

    /* =========================
       ICONS
    ========================= */
    .inline-wrap {
      display: inline-block;
      white-space: nowrap;
    }

    .hero-arrow-inline {
      display: none !important;
      width: 0 !important;
      height: 0 !important;
      visibility: hidden !important;
    }

    /* =========================
       INDICATORS WITH PROGRESS FILL
    ========================= */
    @-webkit-keyframes dotProgressFill {
      0% {
        width: 0%;
      }
      100% {
        width: 100%;
      }
    }
    @keyframes dotProgressFill {
      0% {
        width: 0%;
      }
      100% {
        width: 100%;
      }
    }

    .hero .hero-indicators,
    .hero-indicators {
      position: absolute !important;
      bottom: 40px !important;
      right: 80px !important;
      z-index: 10 !important;
      display: flex !important;
      gap: 12px !important;
      align-items: center !important;
    }

    .hero .hero-indicators .dot,
    .hero-indicators .dot,
    .hero .dot {
      width: 55px !important;
      height: 6px !important;
      background: rgba(255, 255, 255, 0.35) !important;
      border-radius: 10px !important;
      position: relative !important;
      overflow: hidden !important;
      cursor: pointer !important;
      display: block !important;
      box-shadow: none !important;
      border: none !important;
      padding: 0 !important;
      margin: 0 !important;
      outline: none !important;
    }

    /* Keep container background semi-transparent when active so the white fill inside is visible */
    .hero .hero-indicators .dot.active,
    .hero-indicators .dot.active,
    .hero .dot.active {
      background: rgba(255, 255, 255, 0.35) !important;
      width: 55px !important;
    }

    .hero .hero-indicators .dot .dot-fill,
    .hero-indicators .dot .dot-fill,
    .hero .dot .dot-fill {
      position: absolute !important;
      top: 0 !important;
      left: 0 !important;
      height: 100% !important;
      width: 0%;
      background: #ffffff !important;
      border-radius: 10px !important;
      display: block !important;
      pointer-events: none !important;
      z-index: 2 !important;
    }

    /* =========================
       MOBILE HERO FIX
    ========================= */
    @media (max-width: 768px){

      section.hero,
      .hero {
        position: relative !important;
        min-height: 100vh !important;
        overflow: hidden !important;
        display: flex !important;
        flex-direction: column !important;
        justify-content: center !important;
      }

      .hero .hero-content,
      .hero-content {
        padding: 100px 20px 85px !important;
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        text-align: center !important;
        box-sizing: border-box !important;
        width: 100% !important;
        max-width: 100% !important;
      }

      .hero-title{
        font-size: 36px;
        line-height: 46px;
        letter-spacing: -1px;
        font-weight: 600;
        max-width: 360px;
        margin: 0 auto 18px;
        text-align: center;
      }

      .hero-title br{
        display: none;
      }

      .hero-title .inline-wrap{
        white-space: nowrap;
      }

      .hero p{
        font-size: 15px;
        line-height: 24px;
        color: #ffffff;
        max-width: 360px;
        margin: 0 auto 26px;
        text-align: center;
      }

      .hero .buttons,
      .buttons {
        display: flex !important;
        flex-direction: column !important;
        align-items: stretch !important;
        gap: 14px !important;
        margin-top: 5px !important;
        margin-bottom: 25px !important;
        width: 100% !important;
        max-width: 340px !important;
        box-sizing: border-box !important;
      }

      .hero .btn-primary,
      .hero .btn-outline,
      .btn-primary,
      .btn-outline {
        width: 100% !important;
        max-width: 100% !important;
        min-width: 100% !important;
        border-radius: 50px !important;
        font-size: 16px !important;
        font-weight: 500 !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        padding: 16px 28px !important;
        box-sizing: border-box !important;
        text-align: center !important;
      }

      .hero .btn-primary,
      .btn-primary {
        background: #ffffff !important;
        color: #0f0f0f !important;
        border: 1px solid #ffffff !important;
      }

      .hero .btn-outline,
      .btn-outline {
        background: transparent !important;
        border: 1px solid rgba(255,255,255,0.85) !important;
        color: #ffffff !important;
      }
      
      /* Indicators on Mobile: Centered at bottom with clean spacing */
      .hero .hero-indicators,
      .hero-indicators {
        position: absolute !important;
        bottom: 25px !important;
        left: 50% !important;
        right: auto !important;
        transform: translateX(-50%) !important;
        z-index: 10 !important;
        display: flex !important;
        gap: 10px !important;
        align-items: center !important;
        justify-content: center !important;
        margin: 0 auto !important;
        width: auto !important;
      }

      .hero .hero-indicators .dot,
      .hero-indicators .dot,
      .hero .dot {
        width: 48px !important;
        height: 5px !important;
      }

      .hero .hero-indicators .dot.active,
      .hero-indicators .dot.active,
      .hero .dot.active {
        width: 48px !important;
      }
      
      .btn-primary img,
      .btn-outline img {
        width: 14px !important;
        height: 14px !important;
        margin-left: 6px !important;
      }

      .hero-bg-slide:nth-child(1) {
        background-image: url('<?php echo esc_url( $default_s1_m ); ?>') !important;
      }
      .hero-bg-slide:nth-child(2) {
        background-image: url('<?php echo esc_url( $default_s2_m ); ?>') !important;
      }
    }
    </style>

    <section class="hero">

      <!-- BACKGROUND -->
      <div class="hero-video-wrapper">
        <div class="hero-bg-slide active" style="background-image: url('<?php echo esc_url( $atts['slider_1'] ); ?>');"></div>
        <div class="hero-bg-slide" style="background-image: url('<?php echo esc_url( $atts['slider_2'] ); ?>');"></div>
      </div>

      <!-- INDICATORS -->
      <div class="hero-indicators">
        <span class="dot active"><span class="dot-fill"></span></span>
        <span class="dot"><span class="dot-fill"></span></span>
      </div>

      <!-- CONTENT -->
      <div class="hero-content">

      <h1 class="hero-title">
        Foresight that clears <br>
        complexity into <span>clarity</span>
      </h1>

      <p class="hero-subtitle">
        Industry intelligence, custom research and bespoke consulting<br class="desktop-br"> that help enterprises remove ambiguity from their biggest strategic bets.
      </p>

      <div class="buttons">
        <a href="<?php echo esc_url( home_url( '/research-reports/' ) ); ?>" class="btn-primary">
          Get Report
          <img class="hero-arrow-button-white"
               src="https://cmrindia.com/wp-content/uploads/2026/04/Symbol-1.svg"
               alt="Arrow"
               aria-hidden="true">
        </a>

        <a href="#elementor-action%3Aaction%3Dpopup%3Aopen%26settings%3DeyJpZCI6Ijc2MzciLCJ0b2dnbGUiOmZhbHNlfQ%3D%3D" class="btn-outline open-popup" data-popup-id="7637">
          Connect with us
          <img class="hero-arrow-button"
               src="https://cmrindia.com/wp-content/uploads/2026/04/Symbol.svg"
               alt="Arrow"
               aria-hidden="true">
        </a>
      </div>

    </div>

  </section>

  <script>
  (function() {
    function initHeroBannerSlider() {
      const hero = document.querySelector('.hero');
      if (!hero) return;
      if (hero.dataset.cmrHeroInit === 'true') return;
      hero.dataset.cmrHeroInit = 'true';

      // 1. Replace title & indicators with fresh nodes to detach any legacy external scripts holding references
      const rawTitle = hero.querySelector('.hero-title');
      const rawIndicators = hero.querySelector('.hero-indicators');
      if (!rawTitle || !rawIndicators) return;

      const title = rawTitle.cloneNode(true);
      rawTitle.parentNode.replaceChild(title, rawTitle);

      const subtitle = hero.querySelector('.hero-subtitle') || hero.querySelector('p');

      const indicators = rawIndicators.cloneNode(true);
      rawIndicators.parentNode.replaceChild(indicators, rawIndicators);

      const dots = indicators.querySelectorAll('.dot');
      if (!dots.length) return;

      const bgSlides = hero.querySelectorAll('.hero-bg-slide');

      const texts = [
        `Foresight that clears <br>
        complexity into <span>clarity</span>`,

        `Influence that moves <br>
        markets and <span>minds</span>`
      ];

      const subtitles = [
        `Industry intelligence, custom research and bespoke consulting<br class="desktop-br"> that help enterprises remove ambiguity from their biggest strategic bets.`,
        `Go-to-market strategies and analyst credibility that connect<br class="desktop-br"> enterprises with the ecosystem leaders and buyers who matter most.`
      ];

      const DURATION = 6000; // 6 seconds per slide
      let activeIdx = 0;
      let slideTimer = null;

      function setSlide(index, isFirstRun) {
        if (slideTimer) {
          clearTimeout(slideTimer);
          slideTimer = null;
        }

        activeIdx = index;

        // Background transition
        bgSlides.forEach(function(slide, i) {
          if (i === activeIdx) {
            slide.classList.add('active');
          } else {
            slide.classList.remove('active');
          }
        });

        // 1. Reset all indicators
        dots.forEach(function(dot) {
          dot.classList.remove('active');
          let fill = dot.querySelector('.dot-fill');
          if (!fill) {
            fill = document.createElement('span');
            fill.className = 'dot-fill';
            dot.appendChild(fill);
          }
          fill.style.transition = 'none';
          fill.style.webkitTransition = 'none';
          fill.style.width = '0%';
        });

        // 2. Activate target indicator & animate progress fill from 0 to 100%
        if (dots[activeIdx]) {
          const activeDot = dots[activeIdx];
          activeDot.classList.add('active');
          let fill = activeDot.querySelector('.dot-fill');
          if (!fill) {
            fill = document.createElement('span');
            fill.className = 'dot-fill';
            activeDot.appendChild(fill);
          }
          fill.style.transition = 'none';
          fill.style.webkitTransition = 'none';
          fill.style.width = '0%';
          void fill.offsetWidth; // Force layout reflow

          requestAnimationFrame(function() {
            fill.style.transition = 'width ' + DURATION + 'ms linear';
            fill.style.webkitTransition = 'width ' + DURATION + 'ms linear';
            fill.style.width = '100%';
          });
        }

        // 3. Smooth Text Transition
        if (!isFirstRun) {
          title.classList.add('fade');
          if (subtitle) subtitle.classList.add('fade');
          setTimeout(function() {
            title.innerHTML = texts[activeIdx];
            if (subtitle) subtitle.innerHTML = subtitles[activeIdx];
            title.classList.remove('fade');
            if (subtitle) subtitle.classList.remove('fade');
          }, 250);
        } else {
          title.innerHTML = texts[activeIdx];
          if (subtitle) subtitle.innerHTML = subtitles[activeIdx];
        }

        // 4. Automatically switch to next slide the instant the fill completes
        slideTimer = setTimeout(function() {
          const nextIdx = (activeIdx + 1) % texts.length;
          setSlide(nextIdx, false);
        }, DURATION);
      }

      // Click handlers on indicators
      dots.forEach(function(dot, i) {
        dot.addEventListener('click', function(e) {
          e.preventDefault();
          if (activeIdx === i) return;
          setSlide(i, false);
        });
      });

        // Initialize first slide
        setSlide(0, true);

        // NOTE: Popup triggers (.open-report-popup, .open-popup) are handled
        // by the unified body click listener in essential-scripts.php.
        // No separate click handlers needed here.
      }

      function alignHeroWithHeader() {
        var hero = document.querySelector('.hero');
        var heroContent = hero ? hero.querySelector('.hero-content') : null;
        var heroIndicators = hero ? hero.querySelector('.hero-indicators') : null;
        if (!heroContent) return;

        if (window.innerWidth <= 768) {
          heroContent.style.paddingLeft = '';
          heroContent.style.paddingRight = '';
          if (heroIndicators) {
            heroIndicators.style.right = '';
            heroIndicators.style.left = '';
            heroIndicators.style.transform = '';
          }
          return;
        }

        if (heroIndicators) {
          heroIndicators.style.left = '';
          heroIndicators.style.transform = '';
        }

        var headerLogo = document.querySelector('#quanto-header-desktop .header-logo, .header .header-logo, .header-logo img, .header-logo, .elementor-element-7ed4f5b');
        if (headerLogo) {
          var logoRect = headerLogo.getBoundingClientRect();
          if (logoRect.left > 0) {
            var newPad = Math.round(logoRect.left) + 'px';
            if (heroContent.style.paddingLeft !== newPad) {
              heroContent.style.paddingLeft = newPad;
            }
          }
        }

        if (heroIndicators) {
          var headerBtn = document.querySelector('#quanto-header-desktop .download-btn, #quanto-header-desktop .elementor-button, .header .download-btn, .header .elementor-button, #quanto-header-desktop .elementor-element-200fa94');
          if (headerBtn) {
            var btnRect = headerBtn.getBoundingClientRect();
            var rightOffset = window.innerWidth - btnRect.right;
            if (rightOffset > 0) {
              var newRight = Math.round(rightOffset) + 'px';
              if (heroIndicators.style.right !== newRight) {
                heroIndicators.style.right = newRight;
              }
            }
          }
        }
      }

      var alignRaf = null;
      function requestAlign() {
        if (alignRaf) return;
        alignRaf = requestAnimationFrame(function() {
          alignRaf = null;
          alignHeroWithHeader();
        });
      }

      if (document.readyState === 'interactive' || document.readyState === 'complete') {
        initHeroBannerSlider();
        requestAlign();
      } else {
        document.addEventListener('DOMContentLoaded', function() {
          initHeroBannerSlider();
          requestAlign();
        });
      }
      window.addEventListener('load', function() {
        initHeroBannerSlider();
        requestAlign();
      });
      window.addEventListener('resize', requestAlign);
    })();
    </script>
    <?php
    return ob_get_clean();
}
