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

        // --- LEX DRAWER PERSISTENCE ---
        var lexState = localStorage.getItem('lex_drawer_state');
        var lexExpanded = localStorage.getItem('lex_drawer_expanded');
        var $drawer = $('#lex-drawer');
        var $panel = $('.lex-drawer-panel');

        if (lexState === 'open' && $drawer.length) {
            // Apply immediately but suppress initial animation to prevent flicker
            $drawer.addClass('open lex-no-animation');
            if (lexExpanded === 'true' && $panel.length) {
                $panel.addClass('expanded');
            }

            // Remove the suppression class after a frame to allow future animations
            setTimeout(function () {
                $drawer.removeClass('lex-no-animation');

                // Also update iframe body if expanded
                if (lexExpanded === 'true') {
                    var $iframe = $('#lex-assistant-frame');
                    $iframe.on('load', function () {
                        if (this.contentDocument) {
                            $(this.contentDocument.body).addClass('is-expanded');
                        }
                    });
                    // If already loaded
                    if ($iframe[0] && $iframe[0].contentDocument) {
                        $($iframe[0].contentDocument.body).addClass('is-expanded');
                    }
                }
            }, 100);
        }
        // --- END PERSISTENCE ---

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

            if (sideMenu.hasClass('menu-opened')) {
                // Close menu
                console.log('Action: CLOSING menu');
                sideMenu.removeClass('menu-opened');
                body.removeClass('menu-is-opened').addClass('menu-is-closed');
                clickCapture.css({ 'opacity': '0', 'visibility': 'hidden' });
            } else {
                // Open menu
                console.log('Action: OPENING menu');
                sideMenu.addClass('menu-opened');
                body.removeClass('menu-is-closed').addClass('menu-is-opened');
                clickCapture.css({ 'opacity': '1', 'visibility': 'visible' });
            }
        });

        // Close button handler
        $(document).on('click', '.close_nav', function (e) {
            e.preventDefault();
            console.log('===== CLOSE BUTTON CLICKED =====');
            $('.side_menu').removeClass('menu-opened');
            $('body').removeClass('menu-is-opened').addClass('menu-is-closed');
            $('.click_capture').css({ 'opacity': '0', 'visibility': 'hidden' });
        });

        // Click capture (overlay) - close menu when clicking outside
        $(document).on('click', '.click_capture', function () {
            console.log('===== OVERLAY CLICKED =====');
            $('.side_menu').removeClass('menu-opened');
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

        // Search suggestions functionality
        var searchTimer;
        var $searchInput = $('.cmgalaxy-search-input');
        var $suggestions = $('#search-suggestions');

        $searchInput.on('input', function () {
            var query = $(this).val().trim();
            clearTimeout(searchTimer);

            if (query.length < 2) {
                $suggestions.hide().empty();
                if (query.length === 0) {
                    $('#cmgalaxy-search-backdrop').removeClass('active');
                    $('.cmgalaxy-search-section').removeClass('popup-active');
                }
                return;
            }

            searchTimer = setTimeout(function () {
                $.ajax({
                    url: (typeof ajaxurl !== 'undefined') ? ajaxurl : cmgalaxy_ajax_url,
                    type: 'POST',
                    data: {
                        action: 'lex_live_search',
                        query: query
                    },
                    success: function (response) {
                        if (response.success) {
                            renderSuggestions(query, response.data.results);
                            $('#cmgalaxy-search-backdrop').addClass('active');
                            $('.cmgalaxy-search-section').addClass('popup-active');
                        }
                    }
                });
            }, 300);
        });

        $searchInput.on('focus click', function () {
            // Ensure Lex Drawer closes when search is activated
            if ($('#lex-drawer').hasClass('open')) {
                $(document).trigger('lex:close');
            }

            var query = $(this).val().trim();
            $('#cmgalaxy-search-backdrop').addClass('active');
            $('.cmgalaxy-search-section').addClass('popup-active');

            if (query.length >= 2 && $suggestions.children().length > 0) {
                $suggestions.show();
            } else {
                $suggestions.hide();
            }
        });

        function renderSuggestions(query, results) {
            $suggestions.empty();

            if (results.length > 0) {
                results.forEach(function (res) {
                    var $item = $('<a href="' + res.url + '" class="suggestion-item">' +
                        '<div class="suggestion-content">' +
                        '<div class="suggestion-title">' + res.title + '</div>' +
                        '<div class="suggestion-meta">Article in ' + res.type + '</div>' +
                        '</div>' +
                        '</a>');
                    $suggestions.append($item);
                });
            }

            // Always add "Ask Lex" suggestion
            var $askLex = $('<div class="ask-ai-suggestion">' +
                '<div class="ask-ai-item" id="ask-lex-suggestion">' +
                '<div class="ask-ai-icon">' +
                '<img src="' + lexLogoUrl + '" style="width:20px;height:auto;">' +
                '</div>' +
                '<div class="ask-ai-text">Ask Lex: <span class="ask-ai-query">"' + query + '"</span></div>' +
                '</div>' +
                '</div>');

            $suggestions.append($askLex);
            $suggestions.show();
        }

        // Close suggestions when clicking outside or on backdrop
        $(document).on('click', function (e) {
            if (!$(e.target).closest('.cmgalaxy-search-section').length) {
                $suggestions.hide();
                $('#cmgalaxy-search-backdrop').removeClass('active');
                $('.cmgalaxy-search-section').removeClass('popup-active');
            }
        });

        $(document).on('click', '#cmgalaxy-search-backdrop', function () {
            $suggestions.hide();
            $(this).removeClass('active');
            $('.cmgalaxy-search-section').removeClass('popup-active');
        });

        // Handle Ask Lex suggestion click
        $(document).on('click', '#ask-lex-suggestion', function () {
            var query = $searchInput.val().trim();
            $suggestions.hide();
            $('#cmgalaxy-search-backdrop').removeClass('active');
            $('.cmgalaxy-search-section').removeClass('popup-active');

            // Open Lex Drawer
            var $drawer = $('#lex-drawer');
            if ($drawer.length) {
                $drawer.removeClass('closing').addClass('open');
                localStorage.setItem('lex_drawer_state', 'open');

                // Send message to iframe to start search
                var iframe = document.getElementById('lex-assistant-frame');
                if (iframe && iframe.contentWindow) {
                    // Give a small delay to ensure iframe is ready/listening
                    setTimeout(function () {
                        iframe.contentWindow.postMessage({
                            type: 'lex_start_search',
                            query: query
                        }, '*');
                    }, 500);
                }
            }
        });

        // Ask Lex button click handler — opens the right-side drawer
        $(document).on('click', '.cmgalaxy-ask-lex-btn', function (e) {
            e.preventDefault();

            // Close search popup if active before opening Lex
            $suggestions.hide();
            $('#cmgalaxy-search-backdrop').removeClass('active');
            $('.cmgalaxy-search-section').removeClass('popup-active');

            // Only handle click if drawer isn't already open
            var $drawer = $('#lex-drawer');
            if ($drawer.length && !$drawer.hasClass('open')) {
                console.log('Action: OPENING Lex Drawer via Ask Lex Button');
                $drawer.removeClass('closing').addClass('open');
                localStorage.setItem('lex_drawer_state', 'open');
            }
        });

        // Lex Side Trigger Click
        $(document).on('click', '#lex-side-trigger', function (e) {
            e.preventDefault();
            $('.cmgalaxy-ask-lex-btn').first().click();
        });

        // Listen for messages from Lex Assistant iframe (e.g., closing from inside)
        window.addEventListener('message', function (event) {
            console.log('Message received from iframe:', event.data);
            if (event.data === 'close-lex') {
                $(document).trigger('lex:close');
            }
            if (event.data === 'expand-lex') {
                $('#lex-drawer-expand').click(); // Try to trigger the hidden button if it exists
                // Or directly toggle expansion
                var $panel = $('.lex-drawer-panel');
                if ($panel.length) {
                    $panel.toggleClass('expanded');
                    var isExpanded = $panel.hasClass('expanded');
                    localStorage.setItem('lex_drawer_expanded', isExpanded ? 'true' : 'false');

                    // Toggle class on iframe body to change icon
                    var $iframe = $('#lex-assistant-frame');
                    if ($iframe.length && $iframe[0].contentDocument) {
                        $($iframe[0].contentDocument.body).toggleClass('is-expanded', isExpanded);
                    }
                }
            }
        });

        // Unified Lex Closing Logic
        $(document).on('lex:close', function () {
            console.log('Action: CLOSING Lex Drawer');
            var $drawer = $('#lex-drawer');
            if ($drawer.length) {
                $drawer.addClass('closing');
                localStorage.setItem('lex_drawer_state', 'closed');
                setTimeout(function () {
                    $drawer.removeClass('open closing');
                    $drawer.find('.lex-drawer-panel').removeClass('expanded'); // Reset expansion state
                    localStorage.setItem('lex_drawer_expanded', 'false');
                    $('body').removeClass('lex-drawer-open');
                }, 300); // Matches the CSS transition time
            }
        });

        // Close Lex Drawer button (if still present or for developer use)
        $(document).on('click', '#lex-drawer-close', function (e) {
            e.preventDefault();
            $(document).trigger('lex:close');
        });

        // Toggle Expand Lex Drawer
        $(document).on('click', '#lex-drawer-expand', function (e) {
            e.preventDefault();
            var $panel = $('.lex-drawer-panel');
            if ($panel.length) {
                console.log('Action: TOGGLING Lex Expansion');
                $panel.toggleClass('expanded');
                var isExpanded = $panel.hasClass('expanded');
                localStorage.setItem('lex_drawer_expanded', isExpanded ? 'true' : 'false');
            }
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
