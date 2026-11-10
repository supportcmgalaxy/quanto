(function ($) {
    'use strict';

    ///////////////////////////////////////////////////////
    // Preloader
    $('.preloader').delay(800).fadeOut('slow');
    // Preloader End

    $(window).on('scroll', function () {
        if ($(window).scrollTop() > 50) {
            $('#sticky-menu').addClass('sticky-menu');
        } else {
            $('#sticky-menu').removeClass('sticky-menu');
        }
    });

    // Mobile menu js
    if (jQuery('.prebuilt-header').length > 0) {

        // header sub menu class no-boder js /////
        document.querySelectorAll(".sub-menu li.menu-item-has-children > a").forEach(function (menuItem) {
            menuItem.classList.add("no-border");
        });

        
        $.fn.vsmobilemenu = function (options) {
            var opt = $.extend({
                menuToggleBtn: ".quanto-menu-toggle",
                bodyToggleClass: "quanto-body-visible",
                subMenuClass: "quanto-submenu",
                subMenuParent: "quanto-item-has-children",
                subMenuParentToggle: "quanto-active",
                meanExpandClass: "quanto-mean-expand",
                appendElement: '<span class="quanto-mean-expand"></span>',
                subMenuToggleClass: "quanto-open",
                toggleSpeed: 400,
              },
              options
            );
        
            return this.each(function () {
                var menu = $(this); // Select menu
        
                // Menu Show & Hide
                function menuToggle() {
                    menu.toggleClass(opt.bodyToggleClass);
            
                    // collapse submenu on menu hide or show
                    var subMenu = "." + opt.subMenuClass;
                    $(subMenu).each(function () {
                    if ($(this).hasClass(opt.subMenuToggleClass)) {
                        $(this).removeClass(opt.subMenuToggleClass);
                        $(this).css("display", "none");
                        $(this).parent().removeClass(opt.subMenuParentToggle);
                    }
                    });
                }
        
                // Class Set Up for every submenu
                menu.find("li").each(function () {
                    var submenu = $(this).find("ul");
                    submenu.addClass(opt.subMenuClass);
                    submenu.css("display", "none");
                    submenu.parent().addClass(opt.subMenuParent);
                    submenu.prev("a").append(opt.appendElement);
                    submenu.next("a").append(opt.appendElement);
                });

                function toggleDropDown($element) {
                    var $submenu = $element.next("ul");

                    if ($submenu.length === 0) {
                        $submenu = $element.prev("ul"); // Fallback if .mean-expand is after the link
                    }

                    if ($submenu.length > 0) {
                        var $parent = $element.parent();
                        var $siblings = $parent.siblings();

                        // Close only sibling submenus
                        $siblings.find("ul." + opt.subMenuClass).slideUp(opt.toggleSpeed).removeClass(opt.subMenuToggleClass);
                        $siblings.removeClass(opt.subMenuParentToggle);

                        // Toggle current submenu
                        $parent.toggleClass(opt.subMenuParentToggle);
                        $submenu.slideToggle(opt.toggleSpeed).toggleClass(opt.subMenuToggleClass);
                    }
                }

        
                // Submenu toggle Button
                menu.find("." + opt.meanExpandClass).each(function () {
                    $(this).on("click", function (e) {
                        e.preventDefault();
                        toggleDropDown($(this).parent());
                    });
                });

                // Menu Show & Hide On Toggle Btn click
                $(opt.menuToggleBtn).each(function () {
                    $(this).on("click", function () {
                    menuToggle();
                    });
                });

                // Hide Menu On out side click
                menu.on("click", function (e) {
                    e.stopPropagation();
                    menuToggle();
                });
            
                // Stop Hide full menu on menu click
                menu.find("div").on("click", function (e) {
                    e.stopPropagation();
                });
            });
        };
        $(".quanto-menu-wrapper").vsmobilemenu();
    }

    /**************************************
     *****  Set Background Image *****
     **************************************/
    if ($('[data-bg-src]').length > 0) {
        $('[data-bg-src]').each(function () {
            var src = $(this).attr('data-bg-src');
            $(this).css('background-image', 'url(' + src + ')');
            $(this).removeAttr('data-bg-src').addClass('background-image');
        });
    }

    ///////////////////////////////////////////////////////
    // Sticky Menu
    $(window).on('scroll', function () {
        var scroll = $(window).scrollTop();
        if (scroll >= 20) {
            $('.quanto-menu-area').addClass('sticky');
        } else {
            $('.quanto-menu-area').removeClass('sticky');
        }
    });
    // Sticky Menu End

    // Custom Cursor
    const cursor = document.querySelector('.cursor');

    if (cursor) {
        const editCursor = (e) => {
            const { clientX: x, clientY: y } = e;
            cursor.style.left = x + 'px';
            cursor.style.top = y + 'px';
        };
        window.addEventListener('mousemove', editCursor);

        document.querySelectorAll('a, .cursor-pointer').forEach((item) => {
            item.addEventListener('mouseover', () => {
                cursor.classList.add('cursor-active');
            });

            item.addEventListener('mouseout', () => {
                cursor.classList.remove('cursor-active');
            });
        });
    }
    // Custom Cursor End


    // Check if thumbnail slider exists
    var thumbSliderElement = document.querySelector('.quanto-testimonial__thumb-slider');

    // Initialize content slider configuration
    var contentSliderConfig = {
        spaceBetween: 24,
        slidesPerView: 1,
        loop: true,
        speed: 800,
        navigation: {
            nextEl: '.quanto-testimonial__next',
            prevEl: '.quanto-testimonial__prev',
        },
    };

    // If thumbnail slider exists, initialize it and connect to content slider
    if (thumbSliderElement) {
        var thumbSlider = new Swiper('.quanto-testimonial__thumb-slider', {
            fadeEffect: { crossFade: true },
            effect: 'fade',
            loop: true,
            allowTouchMove: false,
        });

        // Add thumbs configuration only when thumbnail slider exists
        contentSliderConfig.thumbs = { swiper: thumbSlider };
        contentSliderConfig.allowTouchMove = false;
    } else {
        // For standalone slider, enable touch
        contentSliderConfig.allowTouchMove = true;
    }

    // Initialize the content slider with the appropriate configuration
    var testimonialInfo = new Swiper('.quanto-testimonial__content-slider', contentSliderConfig);


    // Register GSAP Plugins
    let device_width = window.innerWidth;
    gsap.registerPlugin(ScrollTrigger, ScrollSmoother);
    gsap.config({
        nullTargetWarn: false,
        trialWarn: false,
    });

    window.addEventListener('DOMContentLoaded', () => {
        // Set default GSAP configuration

        // Initialize defaults
        gsap.defaults({
            ease: 'power2.out',
            duration: 0.5,
        });
    });

    // ========================== Testimonial3 Slider ===========================
    // Store dependencies in variables
    const testimonial3Element = document.querySelector('.testimonial3-slider');
    const testimonial3NextButton = document.querySelector('.testimonial3-navigation > .next-btn');
    const testimonial3PrevButton = document.querySelector('.testimonial3-navigation > .prev-btn');

    // Check if all required elements are available
    if (testimonial3Element && testimonial3NextButton && testimonial3PrevButton) {
        const testimonial3Slider = new Swiper(testimonial3Element, {
            slidesPerView: 1,
            spaceBetween: 20,
            navigation: {
                nextEl: testimonial3NextButton,
                prevEl: testimonial3PrevButton,
            },
            loop: true,
            // autoplay: {
            //   delay: 5000,
            //   disableOnInteraction: false,
            // },
        });
    }


    let sp = gsap.matchMedia();
    sp.add('(min-width: 1200px)', () => {
        if ($('.quanto-service2-section').length > 0) {
            ScrollTrigger.create({
                trigger: '.quanto-service2-section',
                start: 'top -1%',
                end: 'bottom 110.5%',
                pin: '.quanto-service2-section .quanto__header',
                pinSpacing: true,
            });
        }
    });

    //  sticky of Blog Details

    ScrollTrigger.create({

        trigger: '.social-links-scroll',

        start: 'top 120px',

        end: 'bottom 470px',

        pin: '.blog-item-details .social-links',

        pinSpacing: false,

        markers: false,

    });

    // Section Jump start
    const links = document.querySelectorAll('.section-link');

    if (links && links.length > 0) {
        links.forEach((link) => {
            link.addEventListener('click', function (event) {
                event.preventDefault();

                const targetID = this.getAttribute('href');

                if (targetID === '#header') {
                    gsap.to(window, {
                        duration: 1.5,
                        scrollTo: { y: 0 },
                        ease: 'power2.inOut',
                    });
                } else {
                    const targetSection = document.querySelector(targetID);
                    if (targetSection) {
                        gsap.to(window, {
                            duration: 1,
                            scrollTo: {
                                y: targetSection,
                                offsetY: 50,
                            },
                        });
                    } else {
                        console.error(`Section with ID ${targetID} does not exist.`);
                    }
                }
            });
        });
    }
    // Section Jump End

    function initTeamAnimations() {
        if (window.innerWidth >= 992) {
            // Select all elements with the class .gsap-sticky
            const stickyElements = document.querySelectorAll(".gsap-sticky");

            // Loop through each element and apply the ScrollTrigger animation
            stickyElements.forEach((element) => {
                ScrollTrigger.create({
                trigger: element,
                start: "top 150px",
                end: "100% bottom",
                pin: element,
                pinSpacing: false,
                markers: false,
                });
            });
        }
    }
    // Initialize animations
    initTeamAnimations();


    // Style "The CMR network" button badge & center divider line across all screens & pages
    function initCmrNetworkBadge() {
        var elements = document.querySelectorAll('.elementor-widget-button .elementor-button, .cmr-network-pill-btn, .talk-btn, .download-btn');
        elements.forEach(function(el) {
            var rawTxt = (el.textContent || el.innerText || '').trim();
            if (/^(the\s+)?cmr\s+network$/i.test(rawTxt)) {
                if (el.children.length > 2) return;
                
                var btn = el.closest('.elementor-button') || el.closest('a') || el;
                if (btn.dataset.cmrDone === 'true') return;
                btn.dataset.cmrDone = 'true';
                
                btn.innerHTML = '<span>THE CMR NETWORK</span>';
                btn.classList.add('cmr-network-pill-btn');
                
                // Ensure parent widget spans 100% width
                var widget = btn.closest('.elementor-widget') || btn.closest('.elementor-widget-button') || btn.parentElement;
                if (widget) {
                    widget.classList.add('cmr-network-widget-fullwidth');
                    var container = widget.closest('.e-con') || widget.closest('.elementor-container') || widget.parentElement;
                    if (container && container.children.length === 1) {
                        container.classList.add('cmr-network-widget-fullwidth');
                    }
                }
                
                // Wrap in full-width divider bar with left & right lines
                if (!btn.parentElement.classList.contains('cmr-network-divider-bar')) {
                    var bar = document.createElement('div');
                    bar.className = 'cmr-network-divider-bar';
                    
                    var lineLeft = document.createElement('span');
                    lineLeft.className = 'cmr-network-line cmr-network-line-left';
                    
                    var lineRight = document.createElement('span');
                    lineRight.className = 'cmr-network-line cmr-network-line-right';
                    
                    btn.parentNode.insertBefore(bar, btn);
                    bar.appendChild(lineLeft);
                    bar.appendChild(btn);
                    bar.appendChild(lineRight);
                }
            }
        });
    }

    // Auto-handle counters with extra text (e.g., "20 K", "16 K", "10 K", "20K") in Number field
    function initCustomCounters() {
        document.querySelectorAll('.odometer, [data-odometer-final], .counter-item').forEach(function(el) {
            var rawFinal = el.getAttribute('data-odometer-final') || (el.classList.contains('odometer') ? el.innerText : '');
            if (!rawFinal) return;

            var trimmed = rawFinal.trim();
            var match = trimmed.match(/^([\d.,]+)\s*([a-zA-Z\s%]+)$/);
            if (match) {
                var numPart = match[1];
                var suffixPart = match[2];
                
                if (el.classList.contains('odometer') || el.hasAttribute('data-odometer-final')) {
                    el.setAttribute('data-odometer-final', numPart);
                    
                    var parent = el.closest('.counter-item') || el.parentElement;
                    if (parent) {
                        var existingExtra = parent.querySelector('.odometer-extra-suffix');
                        if (!existingExtra) {
                            var span = document.createElement('span');
                            span.className = 'odometer-extra-suffix';
                            span.textContent = ' ' + suffixPart.trim();
                            if (el.nextSibling) {
                                parent.insertBefore(span, el.nextSibling);
                            } else {
                                parent.appendChild(span);
                            }
                        } else {
                            existingExtra.textContent = ' ' + suffixPart.trim();
                        }
                    }
                }
            }
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            initCmrNetworkBadge();
            initCustomCounters();
        });
    } else {
        initCmrNetworkBadge();
        initCustomCounters();
    }
    $(document).ready(function() {
        initCmrNetworkBadge();
        initCustomCounters();
    });
    $(window).on('elementor/frontend/init', function() {
        initCmrNetworkBadge();
        initCustomCounters();
    });
    window.addEventListener('load', function() {
        initCmrNetworkBadge();
        initCustomCounters();
    });
})(jQuery);


