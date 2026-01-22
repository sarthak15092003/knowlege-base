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

        // Remove default theme toggle to avoid conflicts
        $('.mobile_menu_btn').off('click');

        function toggleCmgalaxyMenu(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            console.log('===== HAMBURGER BUTTON CLICKED =====');

            var body = $('body');
            var sideMenu = $('.side_menu');
            var collapse = $('.navbar-collapse.cmgalaxy-header');
            var button = $(e.currentTarget);
            var isExpanded = button.attr('aria-expanded') === 'true';

            console.log('Is navbar expanded?', isExpanded);

            if (isExpanded) {
                console.log('Action: CLOSING navbar');
                // Close the navbar collapse
                collapse.removeClass('show');
                button.attr('aria-expanded', 'false').addClass('collapsed');
                sideMenu.removeClass('menu-opened');
                body.removeClass('menu-is-opened').addClass('menu-is-closed');
            } else {
                console.log('Action: OPENING navbar');
                // Open the navbar collapse
                collapse.addClass('show');
                button.attr('aria-expanded', 'true').removeClass('collapsed');
                sideMenu.addClass('menu-opened');
                body.removeClass('menu-is-closed').addClass('menu-is-opened');
            }

            console.log('Final Body Classes:', body.attr('class'));
        }

        // Mobile menu toggle - using event delegation for navbar-toggler only
        $(document).on('click', '.navbar-toggler', toggleCmgalaxyMenu);

        // Keep separate handler for mobile_menu_btn if it exists elsewhere
        $(document).on('click', '.mobile_menu_btn', toggleCmgalaxyMenu);

        // Close button handler
        $(document).on('click', '.close_nav', function (e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            console.log('===== CLOSE BUTTON CLICKED =====');
            $('.side_menu').removeClass('menu-opened');
            $('.navbar-collapse.cmgalaxy-header').removeClass('show');
            $('.navbar-toggler').attr('aria-expanded', 'false').addClass('collapsed');
            $('body').removeClass('menu-is-opened').addClass('menu-is-closed');
        });

        // Click capture (overlay) - close menu when clicking outside
        $(document).on('click', '.click_capture', function (e) {
            e.stopImmediatePropagation();
            console.log('===== OVERLAY CLICKED =====');
            $('.side_menu').removeClass('menu-opened');
            $('.navbar-collapse.cmgalaxy-header').removeClass('show');
            $('.navbar-toggler').attr('aria-expanded', 'false').addClass('collapsed');
            $('body').removeClass('menu-is-opened').addClass('menu-is-closed');
        });

        // Smooth scroll for anchor links
        $('a[href^="#"]').on('click', function (e) {
            var target = $(this.getAttribute('href'));
            if (target.length) {
                e.preventDefault();
                $('html, body').stop().animate({
                    scrollTop: target.offset().top - 100
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
