<?php
/**
 * Sticky Navigation Script for intel-nav-bar
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'wp_enqueue_scripts', function() {
    if ( is_front_page() || is_home() || ( function_exists( 'get_queried_object_id' ) && (int) get_queried_object_id() === 14 ) || ( function_exists( 'is_cart' ) && ( is_cart() || is_checkout() || is_account_page() ) ) ) {
        return;
    }
    wp_enqueue_style( 'cmr-sticky-nav-css', get_template_directory_uri() . '/assets/css/cmr-sticky-nav.css', array(), '1.0.0' );
}, 20 );

add_action('wp_footer', function() {
    ?>
    <script>
    if (!window.cmrStickyNavInitialized) {
        window.cmrStickyNavInitialized = true;
        
        function initStickyNav() {
            // Global click to close dropdown when clicking outside
            if (!window.cmrStickyNavClickOutsideAttached) {
                window.cmrStickyNavClickOutsideAttached = true;
                document.addEventListener('click', function(e) {
                    document.querySelectorAll('.intel-nav-bar.is-dropdown-open').forEach(function(bar) {
                        if (!bar.contains(e.target)) {
                            bar.classList.remove('is-dropdown-open');
                            var btn = bar.querySelector('.intel-nav-dropdown-btn');
                            if (btn) btn.setAttribute('aria-expanded', 'false');
                        }
                    });
                });
            }

            // Zero out parent elementor container padding on mobile for 16px screen edge alignment
            function fixMobileEdgeSpacing() {
                if (window.innerWidth <= 768) {
                    document.querySelectorAll('.cmr-latest-insights-section, [class*="cmr-latest-insights"], .cmr-stay-updated-section, [class*="cmr-stay-updated"], .cmr-explore-sectors-section, [class*="cmr-explore-sectors"], .cmr-mc-wrapper').forEach(function(sec) {
                        var p = sec.parentElement;
                        while (p && p !== document.body) {
                            if (p.classList && (
                                p.classList.contains('elementor-element') ||
                                p.classList.contains('e-con') ||
                                p.classList.contains('e-con-inner') ||
                                p.classList.contains('elementor-widget') ||
                                p.classList.contains('elementor-widget-container') ||
                                p.classList.contains('elementor-column') ||
                                p.classList.contains('elementor-section') ||
                                p.classList.contains('elementor-container')
                            )) {
                                p.style.setProperty('padding-left', '0px', 'important');
                                p.style.setProperty('padding-right', '0px', 'important');
                                p.style.setProperty('margin-left', '0px', 'important');
                                p.style.setProperty('margin-right', '0px', 'important');
                                p.style.setProperty('--padding-left', '0px', 'important');
                                p.style.setProperty('--padding-right', '0px', 'important');
                                var inners = p.querySelectorAll('.e-con-inner');
                                inners.forEach(function(inner) {
                                    inner.style.setProperty('padding-left', '0px', 'important');
                                    inner.style.setProperty('padding-right', '0px', 'important');
                                    inner.style.setProperty('margin-left', '0px', 'important');
                                    inner.style.setProperty('margin-right', '0px', 'important');
                                    inner.style.setProperty('--padding-left', '0px', 'important');
                                    inner.style.setProperty('--padding-right', '0px', 'important');
                                });
                            }
                            p = p.parentElement;
                        }
                    });
                }
            }
            fixMobileEdgeSpacing();
            window.addEventListener('resize', fixMobileEdgeSpacing);
            setTimeout(fixMobileEdgeSpacing, 200);
            setTimeout(fixMobileEdgeSpacing, 800);

            // Find all intel nav bars across sections
            const navBars = document.querySelectorAll('.cmr-industry-nav-bar, .cmr-latest-nav-bar, .intel-nav-bar');
            navBars.forEach(navBar => {
                if (navBar.dataset.stickyInitialized) return;
                navBar.dataset.stickyInitialized = 'true';

                const section = navBar.closest('.cmr-industry-intelligence, .cmr-latest-insights-section, .cmr-industry-intel-section, .cmr-marketing-services-section, .cmr-consulting-advisory-section, .cmr-enterprisecgd-wrapper, .cmr-channelcgd-wrapper, .cmr-smbcgd-wrapper, .cmr-mrg-wrapper, .cmr-mc-wrapper, .elementor-section, .e-con, section') || navBar.parentElement;

                // Setup Mobile Dropdown Toggle Button
                let toggleWrap = navBar.querySelector('.intel-nav-mobile-wrap');
                if (!toggleWrap) {
                    toggleWrap = document.createElement('div');
                    toggleWrap.className = 'intel-nav-mobile-wrap';
                    
                    const navLinks = navBar.querySelectorAll('.intel-nav-links a:not(.cmr-nav-btn-subscribe)');
                    const defaultText = navLinks.length > 0 ? navLinks[0].textContent.trim() : 'Overview';
                    
                    toggleWrap.innerHTML = `
                        <button type="button" class="intel-nav-dropdown-btn" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="intel-nav-current-label">${defaultText}</span>
                            <svg class="intel-nav-chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </button>
                    `;
                    navBar.appendChild(toggleWrap);
                    
                    const dropdownBtn = toggleWrap.querySelector('.intel-nav-dropdown-btn');
                    dropdownBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        const isOpen = navBar.classList.toggle('is-dropdown-open');
                        dropdownBtn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                    });
                }
                
                const placeholder = document.createElement('div');
                placeholder.className = 'cmr-nav-placeholder';
                placeholder.style.height = '0px';
                placeholder.style.marginBottom = '0px';
                navBar.parentNode.insertBefore(placeholder, navBar);
                
                function getStickyHeaderOffset() {
                    let offset = 0;
                    const wpAdminBar = document.getElementById('wpadminbar');
                    if (wpAdminBar && window.getComputedStyle(wpAdminBar).position === 'fixed') {
                        offset = wpAdminBar.offsetHeight;
                    }
                    
                    const headerSelectors = [
                        'header',
                        '.header',
                        '[data-elementor-type="header"]',
                        '.elementor-location-header',
                        '.elementor-sticky',
                        '.elementor-sticky--active',
                        '.elementor-sticky--effects',
                        'header .elementor-element',
                        '.header .elementor-element',
                        '.main-header-wrapper',
                        '.blog-header-wrapper',
                        '.mas-sticky-header',
                        '.mas-sticky-yes'
                    ];
                    
                    const candidates = document.querySelectorAll(headerSelectors.join(', '));
                    candidates.forEach(h => {
                        if (!h || h === navBar || h.contains(navBar) || navBar.contains(h)) return;
                        const hStyle = window.getComputedStyle(h);
                        const isSticky = hStyle.position === 'fixed' || 
                                         hStyle.position === 'sticky' || 
                                         h.classList.contains('elementor-sticky--active') || 
                                         h.classList.contains('elementor-sticky--effects') ||
                                         h.classList.contains('mas-sticky-header');
                        if (isSticky) {
                            const hRect = h.getBoundingClientRect();
                            if (hRect.top <= offset + 25 && hRect.bottom > offset && hRect.bottom < (window.innerHeight * 0.45)) {
                                if (hRect.bottom > offset) {
                                    offset = hRect.bottom;
                                }
                            }
                        }
                    });

                    // Fallback for mobile fixed header
                    if (offset === 0) {
                        const topHeader = document.querySelector('header, .header, .elementor-location-header');
                        if (topHeader) {
                            const rect = topHeader.getBoundingClientRect();
                            const hStyle = window.getComputedStyle(topHeader);
                            if ((hStyle.position === 'fixed' || hStyle.position === 'sticky') && rect.bottom > 0 && rect.bottom < 140) {
                                offset = rect.bottom;
                            }
                        }
                    }
                    
                    return offset > 0 ? (Math.floor(offset) - 1) : 0;
                }

                function updateSticky() {
                    const sectionRect = section.getBoundingClientRect();
                    const stickyOffset = getStickyHeaderOffset();

                    let boundaryBottom = sectionRect.bottom;
                    
                    const isStandaloneWrapper = navBar.closest('.cmr-mrg-wrapper, .cmr-enterprisecgd-wrapper, .cmr-channelcgd-wrapper, .cmr-smbcgd-wrapper, .cmr-mc-wrapper');
                    if (!isStandaloneWrapper) {
                        // First see if there is a market updates section to stop at
                        let stopSection = document.querySelector('.cmr-market-updates-section, .cmr-mui-section, #cmr-market-updates');
                        
                        if (!stopSection) {
                            stopSection = document.getElementById('cmr-testimonials-section') || 
                                          document.getElementById('testimonials') || 
                                          document.querySelector('.elementor-element-82ef444') ||
                                          document.querySelector('.elementor-element-d9c32ac') ||
                                          document.querySelector('.elementor-widget-testimonial-carousel') ||
                                          document.querySelector('.elementor-widget-testimonial');
                                          
                            if (!stopSection) {
                                const headings = Array.from(document.querySelectorAll('h1, h2, h3, h4, h5, h6')).filter(h => h.textContent.toLowerCase().includes('testimonial'));
                                if (headings.length > 0) {
                                    stopSection = headings[0].closest('.elementor-section') || headings[0].closest('section') || headings[0].parentElement;
                                }
                            }
                        }

                        if (stopSection) {
                            // If we have a specific stop section (like market updates), we want the nav to stick 
                            // UNTIL that section, even if it means sticking past the navBar's immediate parent section.
                            // We use the page-level parent to ensure we don't unstick too early.
                            const pageWrapper = navBar.closest('.elementor-location-single, .elementor-location-archive, body');
                            const maxBottom = pageWrapper ? pageWrapper.getBoundingClientRect().bottom : sectionRect.bottom;
                            boundaryBottom = Math.min(maxBottom, stopSection.getBoundingClientRect().top);
                        } else {
                            const footer = document.querySelector('footer, .elementor-location-footer');
                            if (footer) {
                                const pageWrapper = navBar.closest('.elementor-location-single, .elementor-location-archive, body');
                                const maxBottom = pageWrapper ? pageWrapper.getBoundingClientRect().bottom : sectionRect.bottom;
                                boundaryBottom = Math.min(maxBottom, footer.getBoundingClientRect().top);
                            }
                        }
                    }

                    const triggerTop = placeholder.getBoundingClientRect().top;

                    if (triggerTop <= stickyOffset && boundaryBottom > (navBar.offsetHeight + stickyOffset)) {
                        if (!navBar.classList.contains('intel-nav-fixed-js')) {
                            placeholder.style.height = navBar.offsetHeight + 'px';
                            placeholder.style.marginBottom = window.innerWidth <= 768 ? '25px' : '30px';
                            navBar.classList.add('intel-nav-fixed-js');
                            document.body.appendChild(navBar); 
                            const subscribeBtn = navBar.querySelector('.cmr-nav-btn-subscribe');
                            if (subscribeBtn && window.innerWidth > 768) {
                                subscribeBtn.style.setProperty('display', 'inline-flex', 'important');
                            }
                        }
                        
                        if (boundaryBottom <= (navBar.offsetHeight + stickyOffset)) {
                            navBar.style.top = (boundaryBottom - navBar.offsetHeight) + 'px';
                        } else {
                            navBar.style.top = stickyOffset + 'px';
                        }
                    } else {
                        if (navBar.classList.contains('intel-nav-fixed-js')) {
                            navBar.classList.remove('intel-nav-fixed-js');
                            navBar.style.top = '';
                            placeholder.parentNode.insertBefore(navBar, placeholder.nextSibling);
                            placeholder.style.height = '0px';
                            placeholder.style.marginBottom = '0px';
                            const subscribeBtn = navBar.querySelector('.cmr-nav-btn-subscribe');
                            if (subscribeBtn && window.innerWidth > 768) {
                                subscribeBtn.style.setProperty('display', 'none', 'important');
                            }
                        }
                    }
                }
                
                window.addEventListener('scroll', updateSticky, { passive: true });
                window.addEventListener('resize', updateSticky, { passive: true });
                setTimeout(updateSticky, 100);
                setTimeout(updateSticky, 1000); // Failsafe for late render
                
                // Active section spy for mobile dropdown label
                function updateActiveSpy() {
                    if (window.innerWidth > 768) return;
                    const navLinks = navBar.querySelectorAll('.intel-nav-links a:not(.cmr-nav-btn-subscribe)');
                    if (!navLinks.length) return;
                    
                    const currentTop = navBar.classList.contains('intel-nav-fixed-js') ? (parseFloat(navBar.style.top) || 0) + (navBar.offsetHeight || 50) : 0;
                    const scrollPos = window.scrollY + currentTop + 70;
                    let activeLink = null;
                    
                    navLinks.forEach(link => {
                        const href = link.getAttribute('href');
                        if (!href || href === '#top') return;
                        const hashIdx = href.indexOf('#');
                        if (hashIdx === -1) return;
                        const targetId = href.substring(hashIdx + 1);
                        if (!targetId) return;
                        const target = document.getElementById(targetId);
                        if (target) {
                            const top = target.getBoundingClientRect().top + window.scrollY;
                            if (top <= scrollPos) {
                                activeLink = link;
                            }
                        }
                    });
                    
                    const labelSpan = navBar.querySelector('.intel-nav-current-label');
                    if (activeLink && labelSpan) {
                        if (labelSpan.textContent !== activeLink.textContent.trim()) {
                            labelSpan.textContent = activeLink.textContent.trim();
                        }
                        navLinks.forEach(l => l.classList.remove('active'));
                        activeLink.classList.add('active');
                    } else if (window.scrollY < 400 && labelSpan && navLinks.length > 0) {
                        labelSpan.textContent = navLinks[0].textContent.trim();
                        navLinks.forEach(l => l.classList.remove('active'));
                        navLinks[0].classList.add('active');
                    }
                }
                window.addEventListener('scroll', updateActiveSpy, { passive: true });

                // Add smooth scrolling for anchor links inside this navBar
                const links = navBar.querySelectorAll('.intel-nav-links a');
                links.forEach(link => {
                    link.addEventListener('click', function(e) {
                        // Close dropdown on mobile and update label
                        navBar.classList.remove('is-dropdown-open');
                        const dropdownBtn = navBar.querySelector('.intel-nav-dropdown-btn');
                        if (dropdownBtn) dropdownBtn.setAttribute('aria-expanded', 'false');
                        const labelSpan = navBar.querySelector('.intel-nav-current-label');
                        if (labelSpan && !this.classList.contains('cmr-nav-btn-subscribe')) {
                            labelSpan.textContent = this.textContent.trim();
                        }
                        const allLinks = navBar.querySelectorAll('.intel-nav-links a');
                        allLinks.forEach(l => l.classList.remove('active'));
                        if (!this.classList.contains('cmr-nav-btn-subscribe')) {
                            this.classList.add('active');
                        }

                        const href = this.getAttribute('href');
                        const linkText = this.innerText.toLowerCase().trim();
                        
                        // Special handling for Overview to scroll to top
                        if (linkText === 'overview' || href === '#top') {
                            e.preventDefault();
                            e.stopPropagation(); // Prevent Elementor from hijacking
                            window.scrollTo({
                                top: 0,
                                behavior: 'smooth'
                            });
                            return;
                        }
                        
                        // For other links, check if they have a hash and point to the current page
                        if (!href) return;
                        
                        const hashIndex = href.indexOf('#');
                        if (hashIndex === -1) return;
                        
                        // If it's a full URL, check if the path matches the current page
                        if (hashIndex > 0) {
                            const linkUrl = new URL(href, window.location.href);
                            if (linkUrl.pathname !== window.location.pathname) {
                                return; // Different page, let the browser handle it
                            }
                        }
                        
                        const targetId = href.substring(hashIndex + 1);
                        if (!targetId) return;
                        
                        let targetElement = document.getElementById(targetId);
                        if (targetId === 'overview' || targetId === 'insights') {
                            const headingWrap = section.querySelector('.cmr-intel-header-wrapper, .cmr-intel-header');
                            if (headingWrap) {
                                targetElement = headingWrap;
                            }
                        }
                        
                        // Fallback 1: Try known shortcode wrapper selectors directly
                        if (!targetElement) {
                            const selectorMap = {
                                'reports': '.cmr-latest-section, .cmr-featured-reports-section, [id^="reports-"]',
                                'cmr-in-news': '.cmr-media-coverage-wrapper, .cmr-mc-wrapper, [class*="cmr-dmc-"]',
                                'cmr-market-updates': '.cmr-mui-section, .cmr-market-updates-section',
                                'featured': '.cmr-cancg-section, .cmr-enterprisecg-section, .cmr-smbcg-section, .cmr-featured-insight-section',
                                'latest': '.cmr-channelcgd-wrapper, .cmr-enterprisecgd-wrapper, .cmr-smbcgd-wrapper'
                            };

                            // Try exact match first, then partial match
                            if (selectorMap[targetId]) {
                                targetElement = document.querySelector(selectorMap[targetId]);
                            }
                            if (!targetElement) {
                                for (const [key, selector] of Object.entries(selectorMap)) {
                                    if (targetId.includes(key) || key.includes(targetId)) {
                                        targetElement = document.querySelector(selector);
                                        if (targetElement) break;
                                    }
                                }
                            }
                        }

                        // Fallback 2: Search by heading text for Elementor sections missing IDs
                        if (!targetElement) {
                            const headings = Array.from(document.querySelectorAll('h1, h2, h3, h4, h5, h6, .elementor-heading-title'));
                            let matchingHeading = null;
                            
                            if (targetId.includes('report')) {
                                matchingHeading = headings.find(h => {
                                    const txt = h.textContent.toLowerCase().trim();
                                    return (txt.includes('reports') || txt.includes('featured reports') || txt.includes('latest reports'))
                                        && !h.closest('.intel-nav-bar') && !h.closest('[class*="-nav"]');
                                });
                            } else if (targetId.includes('insight')) {
                                matchingHeading = headings.find(h => h.textContent.toLowerCase().includes('insight'));
                            } else if (targetId.includes('market-update') || targetId.includes('market')) {
                                matchingHeading = headings.find(h => h.textContent.toLowerCase().includes('market updates') || h.textContent.toLowerCase().includes('updates'));
                            } else if (targetId.includes('featured')) {
                                matchingHeading = headings.find(h => h.textContent.toLowerCase().includes('featured'));
                            } else if (targetId.includes('latest')) {
                                matchingHeading = headings.find(h => h.textContent.toLowerCase().includes('latest'));
                            } else if (targetId.includes('media-resource')) {
                                matchingHeading = headings.find(h => h.textContent.toLowerCase().includes('media resource'));
                            } else if (targetId.includes('media-contact')) {
                                matchingHeading = headings.find(h => h.textContent.toLowerCase().includes('media contact') || h.textContent.toLowerCase().includes('contact us'));
                            } else if (targetId.includes('newsroom') || targetId.includes('news')) {
                                matchingHeading = headings.find(h => {
                                    const txt = h.textContent.toLowerCase().trim();
                                    return (txt.includes('media coverage') || txt.includes('cmr in news') || txt.includes('cmr media coverage') || txt.includes('newsroom') || txt.includes('cmr live'))
                                        && !h.closest('.intel-nav-bar') && !h.closest('[class*="-nav"]');
                                });
                            } else if (targetId.includes('explore')) {
                                matchingHeading = headings.find(h => h.textContent.toLowerCase().includes('explore industry intelligence') || h.textContent.toLowerCase().includes('intelligence'));
                            } else if (targetId === 'cmr-footer-card-section' || targetId.includes('subscribe')) {
                                // Search for headings or widgets containing "CMR Connect" or "Subscribe Now"
                                const possibleCards = Array.from(document.querySelectorAll('h1, h2, h3, h4, h5, h6, .elementor-heading-title, .elementor-button-text'));
                                matchingHeading = possibleCards.find(el => {
                                    const txt = el.textContent.toLowerCase();
                                    return txt.includes('cmr connect') || txt.includes('monthly digest') || (txt.includes('subscribe now') && !el.closest('.intel-nav-bar'));
                                });
                            }
                            
                            if (matchingHeading) {
                                targetElement = matchingHeading.closest('.elementor-section') || matchingHeading.closest('.e-con-parent') || matchingHeading.closest('.e-con-full') || matchingHeading.closest('.cmr-latest-section') || matchingHeading.closest('.cmr-media-coverage-wrapper') || matchingHeading.closest('.cmr-mc-wrapper') || matchingHeading.closest('[class*="cmr-"]') || matchingHeading.parentElement;
                            }
                        }

                        if (targetElement) {
                            e.preventDefault();
                            e.stopPropagation(); // Prevent Elementor from hijacking
                            
                            const stickyOffset = getStickyHeaderOffset();
                            const navHeight = navBar.offsetHeight || 52;
                            const finalOffset = stickyOffset + navHeight + 25; // 25px breathing room
                            
                            const targetPosition = targetElement.getBoundingClientRect().top + window.scrollY;
                            
                            window.scrollTo({
                                top: targetPosition - finalOffset,
                                behavior: 'smooth'
                            });
                        }
                    }, true); // Use capture phase to beat Elementor's native scroll
                });
            });
        }

        // Dynamic Anchor Script: assign IDs to Elementor sections based on headings
        // so sticky navbar anchor links work regardless of page builder setup
        function assignDynamicAnchors() {
            var allHeadings = document.querySelectorAll('h1, h2, h3, h4, h5, h6, .elementor-heading-title');
            
            allHeadings.forEach(function(h) {
                // Skip headings inside nav bars
                if (h.closest('.intel-nav-bar') || h.closest('[class*="-nav-bar"]')) return;
                
                var text = h.innerText.toLowerCase().trim();
                var section = h.closest('.elementor-top-section') || h.closest('section') || h.closest('.elementor-section') || h.closest('.e-con') || h.closest('.e-con-parent') || h.closest('.e-con-full');
                
                if (!section) return;
                // Don't overwrite existing IDs
                if (section.id) return;
                
                // "CMR in news" section
                if (text.includes("recognition of cmr in news") || text.includes("featured media coverage") || text === "cmr in news" || text.includes("cmr media coverage") || text === "media coverage") {
                    section.id = 'cmr-in-news';
                }
                
                // "Reports" section
                if (text.includes("similar reports") || text.includes("browse latest reports") || text.includes("featured reports") || text === "reports" || text.includes("latest reports")) {
                    if (!section.id) section.id = 'reports';
                }
                
                // "Market Updates" section
                if (text.includes("market intelligence &") || text.includes("market updates")) {
                    if (!section.id) section.id = 'cmr-market-updates';
                }
                
                // "Explore Industry Intelligence" section
                if (text.includes("explore industry intelligence") || text.includes("explore our industry intelligence")) {
                    if (!section.id) section.id = 'explore-industry-intelligence';
                }
                
                // "Featured Intelligence" / "Newsroom" section
                if (text.includes("featured intelligence") || (text.includes("media releases") && !text.includes("featured media")) || text === "newsroom") {
                    if (!section.id || (section.id !== 'cmr-in-news')) section.id = 'newsroom';
                }
                
                // "Insights" / "Latest Insights" section
                if (text.includes("latest insights") && !section.id) {
                    section.id = 'overview';
                }
                
                // "Trends" section
                if (text.includes("industry intelligence trends") && !section.id) {
                    section.id = 'trends';
                }
            });
        }

        // Global capture-phase handler for "Explore Marketing Services" / #our-offerings button
        function handleGlobalOfferingsAnchor(e) {
            var link = e.target.closest('a[href*="our-offering"], a[href*="explore-marketing"], a.download-btn a');
            if (!link) {
                var btn = e.target.closest('.elementor-button');
                if (btn && btn.textContent.toLowerCase().includes('explore marketing')) {
                    link = btn;
                }
            }
            if (!link) return;

            var target = document.querySelector('.explore-sectors-title') || 
                         document.querySelector('.cmr-explore-sectors-section') || 
                         document.querySelector('.our-offerings') || 
                         document.getElementById('our-offerings');

            if (target) {
                e.preventDefault();
                e.stopPropagation();
                if (e.stopImmediatePropagation) e.stopImmediatePropagation();

                var rect = target.getBoundingClientRect();
                var scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                var finalY = rect.top + scrollTop - 90;

                window.scrollTo({
                    top: Math.max(0, finalY),
                    behavior: 'smooth'
                });

                if (window.jQuery) {
                    window.jQuery('html, body').stop().animate({ scrollTop: Math.max(0, finalY) }, 600);
                }

                if (window.history && window.history.pushState) {
                    window.history.pushState(null, null, '#our-offerings');
                }
                return false;
            }
        }
        document.addEventListener('click', handleGlobalOfferingsAnchor, true);
        window.addEventListener('click', handleGlobalOfferingsAnchor, true);

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                assignDynamicAnchors();
                initStickyNav();
            });
        } else {
            assignDynamicAnchors();
            initStickyNav();
        }
    }
    </script>
    <?php
});
