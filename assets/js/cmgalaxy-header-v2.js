/**
 * CMGALAXY Header JavaScript
 */
(function ($) {
    'use strict';

    console.log('===== CMGALAXY Header JS Loaded =====');

    $(document).ready(function () {
        console.log('===== Document Ready =====');
        console.log('Mobile menu button found:', $('.mobile_menu_btn').length);
        console.log('Side menu found:', $('.side_menu').length);

        // Search shortcut functionality - Press '/' to focus search
        $(document).on('keydown', function (e) {
            // Check if '/' key is pressed and no input is focused
            if (e.key === '/' && !$('input, textarea').is(':focus')) {
                e.preventDefault();
                $('.cmgalaxy-search-input').focus();
            }

            // Escape key to blur search
            if (e.key === 'Escape') {
                $('.cmgalaxy-search-input').blur();
            }
        });

        // Search input enhancements
        $('.cmgalaxy-search-input').on('focus', function () {
            $(this).parent().addClass('search-focused');
        }).on('blur', function () {
            $(this).parent().removeClass('search-focused');
        });

        // Mobile menu toggle - using event delegation
        $(document).on('click', '.mobile_menu_btn', function (e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('===== HAMBURGER BUTTON CLICKED =====');

            var body = $('body');
            var sideMenu = $('.side_menu');
            var clickCapture = $('.click_capture');

            // Check if menu is currently open (checking body class is more reliable)
            var isOpen = body.hasClass('menu-is-opened') || sideMenu.hasClass('menu-opened');
            console.log('Is menu open?', isOpen);

            if (isOpen) {
                // Close menu
                console.log('Action: CLOSING menu');
                sideMenu.removeClass('menu-opened');
                body.removeClass('menu-is-opened').addClass('menu-is-closed');
                clickCapture.css({ 'opacity': '0', 'visibility': 'hidden' });
                sideMenu.attr('style', 'transform: translate3d(-360px, 0, 0) !important');
            } else {
                // Open menu
                console.log('Action: OPENING menu');
                sideMenu.addClass('menu-opened');
                body.removeClass('menu-is-closed').addClass('menu-is-opened');
                clickCapture.css({ 'opacity': '1', 'visibility': 'visible' });
                sideMenu.attr('style', 'transform: translate3d(0, 0, 0) !important');
            }

            console.log('Final Body Classes:', body.attr('class'));
        });

        // Close button handler
        $(document).on('click', '.close_nav', function (e) {
            e.preventDefault();
            console.log('===== CLOSE BUTTON CLICKED =====');
            $('.side_menu').removeClass('menu-opened').attr('style', 'transform: translate3d(-360px, 0, 0) !important');
            $('body').removeClass('menu-is-opened').addClass('menu-is-closed');
            $('.click_capture').css({ 'opacity': '0', 'visibility': 'hidden' });
        });

        // Click capture (overlay) - close menu when clicking outside
        $(document).on('click', '.click_capture', function () {
            console.log('===== OVERLAY CLICKED =====');
            $('.side_menu').removeClass('menu-opened').attr('style', 'transform: translate3d(-360px, 0, 0) !important');
            $('body').removeClass('menu-is-opened').addClass('menu-is-closed');
            $(this).css({ 'opacity': '0', 'visibility': 'hidden' });
        });

        // Smooth scroll for anchor links (excluding TOC links which are handled separately)
        $('a[href^="#"]').not('#docy-toc a, .doc-nav a, .nav-sidebar a, #docy-tocs a, .bottom_table_content a').on('click', function (e) {
            var target = $(this.getAttribute('href'));
            if (target.length) {
                e.preventDefault();
                $('html, body').stop().animate({
                    scrollTop: target.offset().top - 120
                }, 600);
            }
        });

        // Header scroll effect
        var header = $('.header');
        var lastScrollTop = 0;

        $(window).scroll(function () {
            var scrollTop = $(this).scrollTop();

            if (scrollTop > 100) {
                header.addClass('scrolled');
            } else {
                header.removeClass('scrolled');
            }

            // DISABLED - This was hiding the navbar on scroll
            // Keep navbar always visible
            /*
            if (scrollTop > lastScrollTop && scrollTop > 200) {
                header.addClass('header-hidden');
            } else {
                header.removeClass('header-hidden');
            }
            */

            lastScrollTop = scrollTop;
        });

        // Search suggestions (placeholder for future enhancement)
        $('.cmgalaxy-search-input').on('input', function () {
            var query = $(this).val();
            if (query.length > 2) {
                // Future: Add search suggestions functionality
                console.log('Search query:', query);
            }
        });

        // Ask Lex button click handler
        $('.cmgalaxy-ask-lex-btn').on('click', function (e) {
            e.preventDefault();
            // Future: Add Ask Lex functionality
            alert('Ask Lex feature coming soon!');
        });

        // Community and Sign In link handlers
        $('.cmgalaxy-nav-link').on('click', function (e) {
            var text = $(this).text().trim();
            if (text === 'Community' || text === 'Sign In') {
                e.preventDefault();
                alert(text + ' feature coming soon!');
            }
        });

    });

})(jQuery);
