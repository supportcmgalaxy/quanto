<?php
/* Custom Zendesk-style Drill-down Mobile Menu */

function cmr_inject_mobile_mega_menu() {
    ?>
    <style>
    /* Custom Zendesk-style Drill-down Mobile Menu CSS */
    .cmr-mobile-nav-overlay {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        width: 100vw !important;
        height: 100vh !important;
        background-color: #fff !important;
        z-index: 2147483647 !important;
        display: none !important;
        flex-direction: column;
        font-family: 'Instrument Sans', sans-serif;
        overflow: hidden;
    }
    .cmr-mobile-nav-overlay.cmr-nav-open {
        display: flex !important;
    }
    .cmr-mobile-nav-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px !important;
        background-color: #fff;
        color: #ff0000;
        border-bottom: 1px solid #eee;
    }
    .cmr-mobile-nav-logo { display: flex; align-items: center; }
    .cmr-mobile-nav-close {
        background: none; border: none; color: #000;
        cursor: pointer; padding: 0;
        display: flex; align-items: center; justify-content: center;
    }
    .cmr-mobile-nav-viewport { flex: 1; position: relative; overflow: hidden; background: #fff; }
    .cmr-mobile-nav-panel {
        position: absolute; top: 0; left: 0; width: 100%; height: 100%;
        overflow-y: auto; background: #fff;
        transition: transform 0.3s cubic-bezier(0.4, 0.0, 0.2, 1);
        padding: 10px 0;
    }
    .cmr-mobile-nav-panel-main { transform: translateX(0); }
    .cmr-mobile-nav-panel-main.cmr-slide-left { transform: translateX(-100%); }
    .cmr-mobile-nav-panel-sub { transform: translateX(100%); }
    .cmr-mobile-nav-panel-sub.cmr-active { transform: translateX(0); }
    .cmr-mobile-nav-item {
        display: flex; justify-content: space-between; align-items: center;
        width: 100%;
        background: none; border: none; border-bottom: 1px solid #f0f0f0;
        padding: 20px 24px; font-size: 18px; font-weight: 600; color: #111;
        text-align: left; cursor: pointer; font-family: inherit;
        text-decoration: none;
        transition: color 0.2s ease;
        -webkit-tap-highlight-color: transparent;
    }
    .cmr-mobile-nav-item svg { color: #666; transition: color 0.2s ease, stroke 0.2s ease; }

    /* Click, Active, Hover states for Main Menu Drill-down Items */
    #cmrMobileNav .cmr-mobile-nav-item:hover,
    #cmrMobileNav .cmr-mobile-nav-item:active,
    #cmrMobileNav .cmr-mobile-nav-item:focus,
    #cmrMobileNav .cmr-mobile-nav-item.active,
    #cmrMobileNav .cmr-mobile-nav-item.cmr-active-link {
        color: #6A35FF !important;
    }
    #cmrMobileNav .cmr-mobile-nav-item:hover svg,
    #cmrMobileNav .cmr-mobile-nav-item:active svg,
    #cmrMobileNav .cmr-mobile-nav-item:focus svg,
    #cmrMobileNav .cmr-mobile-nav-item.active svg,
    #cmrMobileNav .cmr-mobile-nav-item.cmr-active-link svg {
        color: #6A35FF !important;
        stroke: #6A35FF !important;
    }

    .cmr-mobile-nav-back {
        display: flex; align-items: center; gap: 12px;
        padding: 20px 24px; font-size: 18px; font-weight: 600; color: #111;
        background: none; border: none; border-bottom: 1px solid #f0f0f0;
        width: 100%; text-align: left; cursor: pointer;
        transition: color 0.2s ease;
        -webkit-tap-highlight-color: transparent;
    }
    #cmrMobileNav .cmr-mobile-nav-back:hover,
    #cmrMobileNav .cmr-mobile-nav-back:active,
    #cmrMobileNav .cmr-mobile-nav-back:focus {
        color: #6A35FF !important;
    }
    #cmrMobileNav .cmr-mobile-nav-back:hover svg,
    #cmrMobileNav .cmr-mobile-nav-back:active svg,
    #cmrMobileNav .cmr-mobile-nav-back:focus svg {
        stroke: #6A35FF !important;
    }

    .cmr-mobile-nav-content { padding: 24px; }
    .cmr-mobile-nav-label {
        font-size: 12px; font-weight: 600; color: #9ba4b5;
        letter-spacing: 1px; text-transform: uppercase; margin-bottom: 20px;
    }
    .cmr-mobile-nav-link { 
        display: block; 
        text-decoration: none; 
        margin-bottom: 24px; 
        transition: color 0.2s ease;
        -webkit-tap-highlight-color: transparent;
    }
    .cmr-mobile-nav-link:last-child { margin-bottom: 0; }
    .cmr-mobile-nav-link-title { 
        font-size: 18px; 
        font-weight: 600; 
        color: #111; 
        margin-bottom: 4px; 
        transition: color 0.2s ease; 
    }
    .cmr-mobile-nav-link-desc { font-size: 15px; color: #666; line-height: 1.4; transition: color 0.2s ease; }

    /* Click, Active, Hover states for Submenu Links */
    #cmrMobileNav .cmr-mobile-nav-link:hover .cmr-mobile-nav-link-title,
    #cmrMobileNav .cmr-mobile-nav-link:active .cmr-mobile-nav-link-title,
    #cmrMobileNav .cmr-mobile-nav-link:focus .cmr-mobile-nav-link-title,
    #cmrMobileNav .cmr-mobile-nav-link.active .cmr-mobile-nav-link-title,
    #cmrMobileNav .cmr-mobile-nav-link.cmr-active-link .cmr-mobile-nav-link-title {
        color: #6A35FF !important;
    }
    #cmrMobileNav .cmr-mobile-nav-link:hover .cmr-mobile-nav-link-desc,
    #cmrMobileNav .cmr-mobile-nav-link:active .cmr-mobile-nav-link-desc,
    #cmrMobileNav .cmr-mobile-nav-link:focus .cmr-mobile-nav-link-desc {
        color: #7c4dff !important;
    }

    @media (max-width: 1024px) {
        .quanto-menu-wrapper,
        .quanto-body-visible .quanto-menu-wrapper {
            display: none !important; visibility: hidden !important; opacity: 0 !important;
        }
    }
    
    /* Fix Mobile & Tablet Sticky Header strictly 16px Side Spacing */
    @media (max-width: 1024px) {
        header.header,
        header.header .elementor,
        header.header .elementor-element,
        header.header .e-con,
        .mas-sticky-header,
        .mas-sticky-yes.mas-sticky-header {
            padding-left: 0 !important;
            padding-right: 0 !important;
            margin-left: 0 !important;
            margin-right: 0 !important;
        }

        header.header .e-con.e-parent > .e-con-inner,
        .mas-sticky-header > .e-con-inner,
        .mas-sticky-yes.mas-sticky-header > .e-con-inner {
            padding-left: 16px !important;
            padding-right: 16px !important;
            box-sizing: border-box !important;
            width: 100% !important;
            max-width: 100% !important;
        }
    }

    /* Hide Hamburger on Desktop */
    @media (min-width: 1025px) {
        .menuBar-toggle,
        .quanto-menu-toggle:not(.mobile),
        .d-lg-none.quanto-menu-toggle {
            display: none !important;
        }
    }

    /* Standard 3-line Hamburger Menu Button (Mobile / Tablet Only) */
    @media (max-width: 1024px) {
        .menuBar-toggle,
        .quanto-menu-toggle:not(.mobile) {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            width: 44px !important;
            height: 40px !important;
            padding: 0 !important;
            background: transparent !important;
            border: 1px solid #d1d5db !important;
            border-radius: 6px !important;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04) !important;
            cursor: pointer !important;
            transition: all 0.2s ease !important;
            box-sizing: border-box !important;
            color: #111827 !important;
            position: relative !important;
        }
        .menuBar-toggle:hover,
        .quanto-menu-toggle:not(.mobile):hover {
            background: rgba(0, 0, 0, 0.04) !important;
            border-color: #9ca3af !important;
        }
        /* Hide old SVG completely so it never flashes on load */
        .menuBar-toggle svg,
        .quanto-menu-toggle:not(.mobile) svg {
            display: none !important;
        }
        /* Render 3 clean vector lines instantly via CSS */
        .menuBar-toggle::before,
        .quanto-menu-toggle:not(.mobile):before {
            content: '' !important;
            display: block !important;
            width: 20px !important;
            height: 2px !important;
            background: #111827 !important;
            border-radius: 2px !important;
            box-shadow: 0 -6px 0 0 #111827, 0 6px 0 0 #111827 !important;
        }
    }

    /* Active state for all mega menus */
    .cmr-mm-item.cmr-active-link h4,
    .cmr-mms-item.cmr-active-link h4,
    .cmr-mmt-item.cmr-active-link h4,
    .cmr-mmw-item.cmr-active-link h4,
    .cmr-mmc-item.cmr-active-link h4,
    .cmr-mmn-item.cmr-active-link h4,
    .cmr-mobile-nav-link.cmr-active-link .cmr-mobile-nav-link-title {
        color: #6A35FF !important;
    }

    /* Show arrow for all items */
    .cmr-mm-arrow, .cmr-mms-arrow, .cmr-mmt-arrow, .cmr-mmw-arrow, .cmr-mmc-arrow, .cmr-mmn-arrow {
        display: inline-block !important;
    }
    </style>
    <div class="cmr-mobile-nav-overlay" id="cmrMobileNav">
        <div class="cmr-mobile-nav-header">
            <a href="/" class="cmr-mobile-nav-logo">
                <img src="https://cmrindia.com/wp-content/uploads/2026/04/cmrheaderlogo.svg" alt="CMR" style="height: 32px;">
            </a>
            <button class="cmr-mobile-nav-close" aria-label="Close mobile menu">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>
        <div class="cmr-mobile-nav-viewport">
            
            <!-- Main Menu List -->
            <div class="cmr-mobile-nav-panel cmr-mobile-nav-panel-main cmr-active" id="cmrPanelMain">
                <button type="button" class="cmr-mobile-nav-item" data-target="panel-who-we-are">
                    Who we are
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </button>
                <button type="button" class="cmr-mobile-nav-item" data-target="panel-what-we-do">
                    What we do
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </button>
                <button type="button" class="cmr-mobile-nav-item" data-target="panel-who-we-serve">
                    Who we serve
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </button>
                <button type="button" class="cmr-mobile-nav-item" data-target="panel-what-we-think">
                    What we think
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </button>
                <button type="button" class="cmr-mobile-nav-item" data-target="panel-connect">
                    CMR Connect
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </button>
                <button type="button" class="cmr-mobile-nav-item" data-target="panel-newsroom">
                    Newsroom
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </button>
            </div>

            <!-- Sub Panel: Who We Are -->
            <div class="cmr-mobile-nav-panel cmr-mobile-nav-panel-sub" id="panel-who-we-are">
                <button class="cmr-mobile-nav-back" data-target="cmrPanelMain">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                    Who we are
                </button>
                <div class="cmr-mobile-nav-content">
                    <div class="cmr-mobile-nav-label">WHO WE ARE</div>
                    <a href="<?php echo esc_url( home_url( '/about-us/' ) ); ?>" class="cmr-mobile-nav-link">
                        <div class="cmr-mobile-nav-link-title">About Us</div>
                        <div class="cmr-mobile-nav-link-desc">Our story, expertise, and vision</div>
                    </a>
                    <a href="<?php echo esc_url( home_url( '/leadership/' ) ); ?>" class="cmr-mobile-nav-link">
                        <div class="cmr-mobile-nav-link-title">Leadership</div>
                        <div class="cmr-mobile-nav-link-desc">Meet the leaders driving innovation</div>
                    </a>
                    <a href="https://cybermedia.co.in/careers/" class="cmr-mobile-nav-link">
                        <div class="cmr-mobile-nav-link-title">Careers</div>
                        <div class="cmr-mobile-nav-link-desc">Build the future with us</div>
                    </a>
                    <a href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>" class="cmr-mobile-nav-link">
                        <div class="cmr-mobile-nav-link-title">Contact Us</div>
                        <div class="cmr-mobile-nav-link-desc">Connect with our expert team</div>
                    </a>
                </div>
            </div>

            <!-- Sub Panel: What We Do -->
            <div class="cmr-mobile-nav-panel cmr-mobile-nav-panel-sub" id="panel-what-we-do">
                <button class="cmr-mobile-nav-back" data-target="cmrPanelMain">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                    What we do
                </button>
                <div class="cmr-mobile-nav-content">
                    <div class="cmr-mobile-nav-label">WHAT WE DO</div>
                    <a href="<?php echo esc_url( home_url( '/industry-intelligence/' ) ); ?>" class="cmr-mobile-nav-link">
                        <div class="cmr-mobile-nav-link-title">Industry Intelligence</div>
                        <div class="cmr-mobile-nav-link-desc">Market research and strategic insights</div>
                    </a>
                    <a href="<?php echo esc_url( home_url( '/consulting-advisory/' ) ); ?>" class="cmr-mobile-nav-link">
                        <div class="cmr-mobile-nav-link-title">Consulting & Advisory</div>
                        <div class="cmr-mobile-nav-link-desc">Expert guidance for business growth</div>
                    </a>
                    <a href="<?php echo esc_url( home_url( '/marketing-services/' ) ); ?>" class="cmr-mobile-nav-link">
                        <div class="cmr-mobile-nav-link-title">Marketing Services</div>
                        <div class="cmr-mobile-nav-link-desc">Research-backed marketing solutions</div>
                    </a>
                </div>
            </div>

            <!-- Sub Panel: Who We Serve -->
            <div class="cmr-mobile-nav-panel cmr-mobile-nav-panel-sub" id="panel-who-we-serve">
                <button class="cmr-mobile-nav-back" data-target="cmrPanelMain">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                    Who we serve
                </button>
                <div class="cmr-mobile-nav-content">
                    <div class="cmr-mobile-nav-label">WHO WE SERVE</div>
                    <a href="<?php echo esc_url( home_url( '/ai/' ) ); ?>" class="cmr-mobile-nav-link">
                        <div class="cmr-mobile-nav-link-title">AI</div>
                        <div class="cmr-mobile-nav-link-desc">Adoption trends and enterprise returns</div>
                    </a>
                    <a href="<?php echo esc_url( home_url( '/automotive/' ) ); ?>" class="cmr-mobile-nav-link">
                        <div class="cmr-mobile-nav-link-title">Automotive</div>
                        <div class="cmr-mobile-nav-link-desc">Software-defined vehicles and mobility shifts</div>
                    </a>
                    <a href="<?php echo esc_url( home_url( '/consumer-tech/' ) ); ?>" class="cmr-mobile-nav-link">
                        <div class="cmr-mobile-nav-link-title">Consumer Tech</div>
                        <div class="cmr-mobile-nav-link-desc">Device demand and buyer behaviour</div>
                    </a>
                    <a href="<?php echo esc_url( home_url( '/digital-supply-chain/' ) ); ?>" class="cmr-mobile-nav-link">
                        <div class="cmr-mobile-nav-link-title">Digital Supply Chain</div>
                        <div class="cmr-mobile-nav-link-desc">Automation, visibility and resilience</div>
                    </a>
                    <a href="<?php echo esc_url( home_url( '/enterprise-tech/' ) ); ?>" class="cmr-mobile-nav-link">
                        <div class="cmr-mobile-nav-link-title">Enterprise Tech</div>
                        <div class="cmr-mobile-nav-link-desc">Cloud, data centre and IT budget trends</div>
                    </a>
                    <a href="<?php echo esc_url( home_url( '/it-telecom/' ) ); ?>" class="cmr-mobile-nav-link">
                        <div class="cmr-mobile-nav-link-title">IT & Telecom</div>
                        <div class="cmr-mobile-nav-link-desc">From devices to networks to cloud</div>
                    </a>
                    <a href="<?php echo esc_url( home_url( '/msme/' ) ); ?>" class="cmr-mobile-nav-link">
                        <div class="cmr-mobile-nav-link-title">MSME</div>
                        <div class="cmr-mobile-nav-link-desc">Digital adoption and tech spend in small business</div>
                    </a>
                    <a href="<?php echo esc_url( home_url( '/semiconductors/' ) ); ?>" class="cmr-mobile-nav-link">
                        <div class="cmr-mobile-nav-link-title">Semiconductors</div>
                        <div class="cmr-mobile-nav-link-desc">Foundry, memory and AI silicon trends</div>
                    </a>
                </div>
            </div>

            <!-- Sub Panel: What We Think -->
            <div class="cmr-mobile-nav-panel cmr-mobile-nav-panel-sub" id="panel-what-we-think">
                <button class="cmr-mobile-nav-back" data-target="cmrPanelMain">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                    What we think
                </button>
                <div class="cmr-mobile-nav-content">
                    <div class="cmr-mobile-nav-label">WHAT WE THINK</div>
                    <a href="<?php echo esc_url( home_url( '/research-reports/' ) ); ?>" class="cmr-mobile-nav-link">
                        <div class="cmr-mobile-nav-link-title">Research Reports</div>
                        <div class="cmr-mobile-nav-link-desc">Data-driven insights and forecasts</div>
                    </a>
                    <a href="<?php echo esc_url( home_url( '/viewpoints/' ) ); ?>" class="cmr-mobile-nav-link">
                        <div class="cmr-mobile-nav-link-title">View Points</div>
                        <div class="cmr-mobile-nav-link-desc">Expert analysis on emerging trends</div>
                    </a>
                    <a href="<?php echo esc_url( home_url( '/market-updates/' ) ); ?>" class="cmr-mobile-nav-link">
                        <div class="cmr-mobile-nav-link-title">Market Updates</div>
                        <div class="cmr-mobile-nav-link-desc">Latest developments shaping industries</div>
                    </a>

                    <div class="cmr-mobile-nav-label" style="margin-top: 24px;">CMR LIVE</div>
                    <a href="<?php echo esc_url( home_url( '/cmr-live/' ) ); ?>" class="cmr-mobile-nav-link">
                        <div class="cmr-mobile-nav-link-title">Podcasts</div>
                        <div class="cmr-mobile-nav-link-desc">Expert conversations on trends and innovation</div>
                    </a>
                    <a href="<?php echo esc_url( home_url( '/cmr-live/#top-view' ) ); ?>" class="cmr-mobile-nav-link">
                        <div class="cmr-mobile-nav-link-title">Top View</div>
                        <div class="cmr-mobile-nav-link-desc">Watch expert perspectives and industry conversations</div>
                    </a>
                </div>
            </div>

            <!-- Sub Panel: Connect -->
            <div class="cmr-mobile-nav-panel cmr-mobile-nav-panel-sub" id="panel-connect">
                <button class="cmr-mobile-nav-back" data-target="cmrPanelMain">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                    CMR Connect
                </button>
                <div class="cmr-mobile-nav-content">
                    <div class="cmr-mobile-nav-label">CMR CONNECT</div>
                    <a href="<?php echo esc_url( home_url( '/enterprise-connect/' ) ); ?>" class="cmr-mobile-nav-link">
                        <div class="cmr-mobile-nav-link-title">Enterprise Connect</div>
                        <div class="cmr-mobile-nav-link-desc">Insights for enterprise leaders</div>
                    </a>
                    <a href="<?php echo esc_url( home_url( '/smb-connect/' ) ); ?>" class="cmr-mobile-nav-link">
                        <div class="cmr-mobile-nav-link-title">SMB Connect</div>
                        <div class="cmr-mobile-nav-link-desc">Growth strategies for SMBs</div>
                    </a>
                    <a href="<?php echo esc_url( home_url( '/channel-connect/' ) ); ?>" class="cmr-mobile-nav-link">
                        <div class="cmr-mobile-nav-link-title">Channel Connect</div>
                        <div class="cmr-mobile-nav-link-desc">Opportunities for channel partners</div>
                    </a>
                </div>
            </div>

            <!-- Sub Panel: Newsroom -->
            <div class="cmr-mobile-nav-panel cmr-mobile-nav-panel-sub" id="panel-newsroom">
                <button class="cmr-mobile-nav-back" data-target="cmrPanelMain">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                    Newsroom
                </button>
                <div class="cmr-mobile-nav-content">
                    <div class="cmr-mobile-nav-label">NEWSROOM</div>
                    <a href="<?php echo esc_url( home_url( '/press-releases/' ) ); ?>" class="cmr-mobile-nav-link">
                        <div class="cmr-mobile-nav-link-title">Media Releases</div>
                        <div class="cmr-mobile-nav-link-desc">Official company announcements and updates</div>
                    </a>
                    <a href="https://www.cmrsl.net/financial-results/" target="_blank" rel="noopener noreferrer" class="cmr-mobile-nav-link">
                        <div class="cmr-mobile-nav-link-title">Quarterly Results</div>
                        <div class="cmr-mobile-nav-link-desc">Financial performance and investor updates</div>
                    </a>
                    <a href="<?php echo esc_url( home_url( '/cmr-news/' ) ); ?>" class="cmr-mobile-nav-link">
                        <div class="cmr-mobile-nav-link-title">CMR in News</div>
                        <div class="cmr-mobile-nav-link-desc">Featured coverage across leading media</div>
                    </a>
                </div>
            </div>

        </div>
    </div>
    <script>
    // STEP 1: On DOMContentLoaded, move the overlay out of its hidden parent to <body>
    // Elementor nests wp_footer output inside hidden mega-menu wrappers,
    // so the overlay inherits display:none from its parent even with !important.
    document.addEventListener('DOMContentLoaded', function() {
        // Remove duplicate overlays (Elementor clones the header multiple times)
        var allOverlays = document.querySelectorAll('.cmr-mobile-nav-overlay');
        if (allOverlays.length > 1) {
            // Keep only the last one (most complete), remove the rest
            for (var i = 0; i < allOverlays.length - 1; i++) {
                allOverlays[i].parentNode.removeChild(allOverlays[i]);
            }
        }
        // Move the remaining overlay to <body> so it's not trapped in a hidden parent
        var overlay = document.querySelector('.cmr-mobile-nav-overlay');
        if (overlay && overlay.parentElement !== document.body) {
            document.body.appendChild(overlay);
        }

        // Highlight active menu items for BOTH desktop and mobile mega menus (only 1 item active at a time)
        window.cmrUpdateActiveMegaLinks = function() {
            var currentPath = window.location.pathname.replace(/\/$/, "");
            var currentHash = window.location.hash; // e.g. '#podcasts'

            var allMegaLinks = document.querySelectorAll('.cmr-mm-item, .cmr-mms-item, .cmr-mmt-item, .cmr-mmw-item, .cmr-mmc-item, .cmr-mmn-item, .cmr-mobile-nav-link');
            
            allMegaLinks.forEach(function(l) {
                l.classList.remove('cmr-active-link');
            });

            var pathMatches = [];
            allMegaLinks.forEach(function(link) {
                if (!link.href) return;
                var linkUrl;
                try {
                    linkUrl = new URL(link.href, window.location.href);
                } catch(e) {
                    return;
                }
                var linkPath = linkUrl.pathname.replace(/\/$/, "");
                if (linkPath === currentPath) {
                    pathMatches.push({ link: link, hash: linkUrl.hash });
                }
            });

            if (pathMatches.length === 0) return;

            var activeItems = [];
            if (currentHash) {
                activeItems = pathMatches.filter(function(item) {
                    return item.hash === currentHash;
                });
            }

            if (activeItems.length === 0) {
                var noHashItems = pathMatches.filter(function(item) {
                    return !item.hash;
                });
                if (noHashItems.length > 0) {
                    activeItems = noHashItems;
                } else {
                    var firstHash = pathMatches[0].hash;
                    activeItems = pathMatches.filter(function(item) {
                        return item.hash === firstHash;
                    });
                }
            }

            activeItems.forEach(function(item) {
                item.link.classList.add('cmr-active-link');

                if (item.link.hasAttribute('data-target')) {
                    var targetId = item.link.getAttribute('data-target');
                    var wrapper = item.link.closest('.cmr-mm-wrapper, .cmr-mms-wrapper, .cmr-mmt-wrapper, .cmr-mmw-wrapper, .cmr-mmc-wrapper, .cmr-mmn-wrapper');
                    if (wrapper) {
                        wrapper.querySelectorAll('a[data-target]').forEach(function(t) { t.classList.remove('active'); });
                        wrapper.querySelectorAll('.cmr-mmw-content-panel, .cmr-mmt-content-panel, .cmr-mmc-content-panel, .cmr-mms-content-panel, .cmr-mmn-content-panel, .cmr-mmw-posts-list, .cmr-mmc-posts-list, .cmr-mmt-posts-list').forEach(function(p) { p.style.display = 'none'; });
                        item.link.classList.add('active');
                        var targetPanel = wrapper.querySelector('[id="' + targetId + '"]');
                        if (targetPanel) { targetPanel.style.display = 'flex'; }
                    }
                }
            });
        };

        window.cmrUpdateActiveMegaLinks();
        window.addEventListener('hashchange', window.cmrUpdateActiveMegaLinks);

        // Update active menu link on scroll between sections on /cmr-live/
        if (window.location.pathname.includes('cmr-live')) {
            var scrollSpyTimer;
            window.addEventListener('scroll', function() {
                clearTimeout(scrollSpyTimer);
                scrollSpyTimer = setTimeout(function() {
                    var topViewEl = document.getElementById('top-view');
                    var podcastsEl = document.getElementById('podcasts');
                    if (!topViewEl || !podcastsEl) return;

                    var scrollY = window.pageYOffset || document.documentElement.scrollTop;
                    var podcastsTop = podcastsEl.getBoundingClientRect().top + scrollY - 200;
                    var topViewTop = topViewEl.getBoundingClientRect().top + scrollY - 200;

                    var activeHash = (scrollY >= topViewTop) ? '#top-view' : '';

                    if (window.location.hash !== activeHash) {
                        if (window.history && window.history.replaceState) {
                            window.history.replaceState(null, null, activeHash || window.location.pathname);
                        }
                        window.cmrUpdateActiveMegaLinks();
                    }
                }, 100);
            }, { passive: true });
        }
    });
    
    // STEP 1.5: Hover handlers using event delegation for cloned mega menu tabs
    document.addEventListener('mouseover', function(e) {
        var trigger = e.target.closest('.cmr-mmw-item-hover-trigger, .cmr-mmt-item-hover-trigger, .cmr-mmc-item-hover-trigger, .cmr-mms-item-hover-trigger, .cmr-mmn-item-hover-trigger');
        if (trigger) {
            var targetId = trigger.getAttribute('data-target');
            if (!targetId) return;
            
            var wrapper = trigger.closest('.cmr-mm-wrapper, .cmr-mms-wrapper, .cmr-mmt-wrapper, .cmr-mmw-wrapper, .cmr-mmc-wrapper, .cmr-mmn-wrapper');
            if (wrapper) {
                // Remove active from all triggers
                wrapper.querySelectorAll('a[data-target]').forEach(function(t) { t.classList.remove('active'); });
                
                // Hide all panels
                wrapper.querySelectorAll('.cmr-mmw-content-panel, .cmr-mmt-content-panel, .cmr-mmc-content-panel, .cmr-mms-content-panel, .cmr-mmn-content-panel, .cmr-mmw-posts-list, .cmr-mmc-posts-list, .cmr-mmt-posts-list').forEach(function(p) { p.style.display = 'none'; });
                
                // Activate this trigger
                trigger.classList.add('active');
                
                // Show target panel inside this wrapper
                var targetPanel = wrapper.querySelector('[id="' + targetId + '"]');
                if (targetPanel) {
                    targetPanel.style.display = 'flex';
                }
            }
        }
    });

    // Enable active touch state on mobile devices
    document.addEventListener('touchstart', function() {}, {passive: true});

    // STEP 2: Click handlers using event delegation on capture phase
    document.addEventListener('click', function(e) {
        // 1. Toggle Open
        var toggle = e.target.closest('.menuBar-toggle, .quanto-menu-toggle');
        if (toggle) {
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();
            
            var liveOverlay = document.querySelector('.cmr-mobile-nav-overlay');
            if (liveOverlay) {
                liveOverlay.classList.add('cmr-nav-open');
                document.body.style.overflow = 'hidden';
            }
            return;
        }

        // 2. Close Button
        if (e.target.closest('.cmr-mobile-nav-close')) {
            e.preventDefault();
            var liveOverlay = document.querySelector('.cmr-mobile-nav-overlay');
            if (liveOverlay) {
                liveOverlay.classList.remove('cmr-nav-open');
                document.body.style.overflow = '';
                
                // Reset panels
                setTimeout(function() {
                    var mainPanel = document.getElementById('cmrPanelMain');
                    if (mainPanel) mainPanel.classList.remove('cmr-slide-left');
                    liveOverlay.querySelectorAll('.cmr-mobile-nav-panel-sub').forEach(function(p) {
                        p.classList.remove('cmr-active');
                    });
                }, 300);
            }
            return;
        }

        // 3. Drill down (Forward Navigation)
        var navItem = e.target.closest('.cmr-mobile-nav-item');
        if (navItem) {
            var targetId = navItem.getAttribute('data-target');
            if (targetId) {
                e.preventDefault();
                // Highlight clicked main menu item
                document.querySelectorAll('.cmr-mobile-nav-item').forEach(function(el) {
                    el.classList.remove('cmr-active-link', 'active');
                });
                navItem.classList.add('cmr-active-link', 'active');

                var targetPanel = document.getElementById(targetId);
                var mainPanel = document.getElementById('cmrPanelMain');
                if (targetPanel && mainPanel) {
                    mainPanel.classList.add('cmr-slide-left');
                    targetPanel.classList.add('cmr-active');
                }
            }
            return;
        }

        // 3.5 Submenu Link Click
        var navLink = e.target.closest('.cmr-mobile-nav-link');
        if (navLink) {
            document.querySelectorAll('.cmr-mobile-nav-link').forEach(function(el) {
                el.classList.remove('cmr-active-link', 'active');
            });
            navLink.classList.add('cmr-active-link', 'active');

            var href = navLink.getAttribute('href') || '';
            var hashIdx = href.indexOf('#');
            if (hashIdx !== -1) {
                var targetId = href.substring(hashIdx + 1);
                var targetEl = document.getElementById(targetId);

                var linkUrl;
                try {
                    linkUrl = new URL(href, window.location.href);
                } catch(err) {
                    linkUrl = null;
                }

                var isCurrentPage = (!linkUrl || linkUrl.pathname === window.location.pathname || href.startsWith('#'));

                // Close mobile drawer and release scroll lock
                var liveOverlay = document.querySelector('.cmr-mobile-nav-overlay');
                if (liveOverlay) {
                    liveOverlay.classList.remove('cmr-nav-open');
                    document.body.style.overflow = '';
                }

                if (isCurrentPage && targetEl) {
                    e.preventDefault();
                    if (window.history && window.history.pushState) {
                        window.history.pushState(null, null, '#' + targetId);
                    }
                    setTimeout(function() {
                        var headerOffset = 90;
                        var elPos = targetEl.getBoundingClientRect().top + window.pageYOffset;
                        window.scrollTo({
                            top: Math.max(0, elPos - headerOffset),
                            behavior: 'smooth'
                        });
                    }, 250);
                }
            } else {
                var linkUrl;
                try {
                    linkUrl = new URL(href, window.location.href);
                } catch(err) {
                    linkUrl = null;
                }
                if (linkUrl && linkUrl.pathname === window.location.pathname) {
                    var liveOverlay = document.querySelector('.cmr-mobile-nav-overlay');
                    if (liveOverlay) {
                        liveOverlay.classList.remove('cmr-nav-open');
                        document.body.style.overflow = '';
                    }
                    e.preventDefault();
                    if (window.history && window.history.pushState) {
                        window.history.pushState(null, null, window.location.pathname);
                    }
                    setTimeout(function() {
                        window.scrollTo({
                            top: 0,
                            behavior: 'smooth'
                        });
                    }, 250);
                }
            }
        }

        // 4. Back Button (Backward Navigation)
        var backBtn = e.target.closest('.cmr-mobile-nav-back');
        if (backBtn) {
            e.preventDefault();
            var parentPanel = backBtn.closest('.cmr-mobile-nav-panel-sub');
            var mainPanel = document.getElementById('cmrPanelMain');
            if (parentPanel && mainPanel) {
                parentPanel.classList.remove('cmr-active');
                mainPanel.classList.remove('cmr-slide-left');
            }
            return;
        }
    }, true);
    </script>
    <?php
}
add_action('wp_footer', 'cmr_inject_mobile_mega_menu', 100);
