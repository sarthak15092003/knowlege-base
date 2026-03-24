/**
 * CMGALAXY Header JavaScript - V2.3
 * Fixed flickering and cross-talk between top and banner search bars.
 */
(function ($) {
    'use strict';

    $(document).ready(function () {
        console.log('CMGALAXY Header V2.3 Initialized');

        // --- GLOBAL STATE ---
        var searchInstances = [];
        var activeSection = null;

        // --- BACKDROP MANAGEMENT ---
        function updateBackdrop() {
            var $backdrop = $('#cmgalaxy-search-backdrop');
            if (!$backdrop.length) return;

            // Backdrop is only for the top header search, never for banner search.
            var anyActive = $('.cmgalaxy-search-section.popup-active').length > 0;
            
            if (anyActive) {
                if (!$backdrop.hasClass('active')) {
                    $backdrop.css('display', 'block');
                    $backdrop[0].offsetHeight; // Force reflow
                    $backdrop.addClass('active');
                }
            } else {
                $backdrop.removeClass('active');
                setTimeout(function() {
                    if (!$backdrop.hasClass('active')) $backdrop.css('display', 'none');
                }, 300);
            }
        }

        // --- CLOSE UTILITY ---
        function closeAllSearches(exceptElement) {
            $('.cmgalaxy-search-section, .header_search_form').each(function() {
                var $sec = $(this);
                if (exceptElement && $sec.is(exceptElement)) return;
                
                $sec.removeClass('popup-active');
                $sec.removeClass('is-loading');
                $sec.find('.cmgalaxy-search-suggestions').hide();
                $sec.find('.cmgalaxy-search-loader').removeClass('active');
                $sec.find('.cmgalaxy-search-input, .use-cmgalaxy-live-search-input').removeClass('is-loading');
            });
            updateBackdrop();
        }

        function openLexDrawer(query) {
            var $drawer = $('#lex-drawer');
            if (!$drawer.length) return;

            $drawer.removeClass('closing').addClass('open');
            $('body').addClass('lex-drawer-open');
            localStorage.setItem('lex_drawer_state', 'open');

            if (query) {
                var iframe = document.getElementById('lex-assistant-frame');
                if (iframe && iframe.contentWindow) {
                    setTimeout(function () {
                        iframe.contentWindow.postMessage({ type: 'lex_start_search', query: query }, '*');
                    }, 500);
                }
            }
        }

        function syncLexExpandedState() {
            var iframe = document.getElementById('lex-assistant-frame');
            var $panel = $('.lex-drawer-panel');
            if (!iframe || !iframe.contentWindow || !$panel.length) return;
            iframe.contentWindow.postMessage({
                type: 'lex_expanded_state',
                expanded: $panel.hasClass('expanded')
            }, '*');
        }

        // --- SUGGESTION RENDERING ---
        function renderSuggestions($suggestions, query, results, inputSelector) {
            if ($suggestions.data('last-query') === query && results.length === 0 && $suggestions.find('.ask-ai-item').length > 0) return;
            $suggestions.empty().data('last-query', query);

            if (results.length > 0) {
                results.forEach(function (res) {
                    var escapedQuery = query.replace(/[-[\]{}()*+?.,\\^$|#\s]/g, '\\$&');
                    var regex = new RegExp('(' + escapedQuery + ')', 'gi');
                    var highlightedTitle = res.title.replace(regex, '<mark class="search-highlight">$1</mark>');

                    var $item = $('<a href="' + res.url + '" class="suggestion-item">' +
                        '<div class="suggestion-content"><div class="suggestion-title">' + highlightedTitle + '</div>' +
                        '<div class="suggestion-meta">Article in ' + res.type + '</div></div></a>');
                    $suggestions.append($item);
                });
            }

            var $askLex = $('<div class="ask-ai-suggestion"><div class="ask-ai-item ask-lex-suggestion" data-input-selector="' + inputSelector + '">' +
                '<div class="ask-ai-icon"><img src="' + lexLogoUrl + '" style="width:20px;"></div>' +
                '<div class="suggestion-content"><div class="ask-ai-text">Ask Lex: <span class="ask-ai-query">"' + query + '"</span></div>' +
                '<div class="suggestion-meta">Get an AI-powered answer instantly</div></div></div></div>');
            $suggestions.append($askLex);
            $suggestions.show();
        }

        // --- CORE LIVE SEARCH ---
        function initSearch(inputClass, sectionClass) {
            var searchTimer;

            // Use direct input listener to avoid delegated lag
            $(document).on('input', inputClass, function () {
                var $input = $(this);
                var $section = $input.closest(sectionClass);
                var $suggestions = $section.find('.cmgalaxy-search-suggestions');
                var $loader = $section.find('.cmgalaxy-search-loader');
                var query = $input.val().trim();
                
                clearTimeout(searchTimer);
                if (query.length < 2) {
                    $suggestions.hide().empty().data('last-query', '');
                    $section.removeClass('is-loading');
                    $loader.removeClass('active');
                    $input.removeClass('is-loading');
                    return;
                }

                searchTimer = setTimeout(function () {
                    $section.addClass('is-loading');
                    $loader.addClass('active');
                    $input.addClass('is-loading');
                    $.ajax({
                        url: (typeof cmgalaxy_ajax_url !== 'undefined' ? cmgalaxy_ajax_url : (typeof ajaxurl !== 'undefined' ? ajaxurl : '/wp-admin/admin-ajax.php')),
                        type: 'POST',
                        data: { action: 'lex_live_search', query: query },
                        success: function (res) {
                            if (res.success) {
                                renderSuggestions($suggestions, query, res.data.results, inputClass);
                                if ($input.is(':focus')) $suggestions.show();
                            }
                        },
                        complete: function () {
                            $section.removeClass('is-loading');
                            $loader.removeClass('active');
                            $input.removeClass('is-loading');
                        }
                    });
                }, 150);
            });

            // Focus management with strict exclusivity
            $(document).on('focusin', inputClass, function (e) {
                var $input = $(this);
                var $section = $input.closest(sectionClass);
                
                // Nuclear kill of other states before this one expands
                if (!$section.hasClass('popup-active')) {
                    closeAllSearches($section);
                    $section.addClass('popup-active');
                    updateBackdrop();
                    
                    if ($('#lex-drawer').hasClass('open')) {
                        $(document).trigger('lex:close');
                    }
                }

                var query = $input.val().trim();
                if (query.length >= 2) {
                    var $suggestions = $section.find('.cmgalaxy-search-suggestions');
                    if ($suggestions.children().length > 0) $suggestions.show();
                }
            });

            $(document).on('keydown', inputClass, function (e) {
                if (e.key === 'Enter') e.preventDefault();
            });
        }

        // --- INITIALIZE INSTANCES ---
        initSearch('.cmgalaxy-search-input', '.cmgalaxy-search-section'); // Top Bar
        initSearch('.use-cmgalaxy-live-search-input', '.header_search_form'); // Banner

        // --- PERSISTENCE ---
        function restoreLexDrawerState() {
            var state = localStorage.getItem('lex_drawer_state');
            var expanded = localStorage.getItem('lex_drawer_expanded') === 'true';
            var $drawer = $('#lex-drawer');
            var $panel = $('.lex-drawer-panel');

            if (state === 'open' && $drawer.length) {
                // Add no-animation class to prevent slide-in on page load
                $drawer.addClass('lex-no-animation');
                $drawer.addClass('open');
                $('body').addClass('lex-drawer-open');
                
                if (expanded && $panel.length) {
                    $panel.addClass('expanded');
                }

                // Sync expanded state with iframe
                setTimeout(function() {
                    syncLexExpandedState();
                }, 100);

                // Remove the no-animation class after a short delay so future interactions are animated
                setTimeout(function() {
                    $drawer.removeClass('lex-no-animation');
                }, 500);
            }
        }

        // Restore state on load
        restoreLexDrawerState();

        // --- GLOBAL TRIGGERS ---
        $(document).on('mousedown', function (e) {
            if (!$(e.target).closest('.cmgalaxy-search-section, .header_search_form').length) {
                closeAllSearches();
            }
        });

        $(document).on('mousedown', '#cmgalaxy-search-backdrop', function () {
            closeAllSearches();
        });

        $(document).on('keydown', function (e) {
            if (e.key === '/' && !$('input, textarea').is(':focus')) {
                e.preventDefault();
                var $target = $('.cmgalaxy-search-input:visible, .use-cmgalaxy-live-search-input:visible').first();
                if ($target.length) $target.focus();
            }
            if (e.key === 'Escape') closeAllSearches();
        });

        // --- LEX DRAWER LOGIC ---
        $(document).on('click', '.ask-lex-suggestion', function () {
            var selector = $(this).attr('data-input-selector') || '.cmgalaxy-search-input';
            var query = $(selector).first().val().trim();
            closeAllSearches();
            openLexDrawer(query);
        });

        $(document).on('click', '.cmgalaxy-ask-lex-btn', function (e) {
            e.preventDefault();
            closeAllSearches();
            openLexDrawer();
        });

        $(document).on('click', '#lex-side-trigger', function (e) {
            e.preventDefault();
            closeAllSearches();
            openLexDrawer();
        });

        $(document).on('lex:close', function () {
            var $drawer = $('#lex-drawer');
            if ($drawer.length) {
                $drawer.addClass('closing');
                localStorage.setItem('lex_drawer_state', 'closed');
                setTimeout(function () {
                    $drawer.removeClass('open closing');
                    $('.lex-drawer-panel').removeClass('expanded');
                    localStorage.setItem('lex_drawer_expanded', 'false');
                    $('body').removeClass('lex-drawer-open');
                    syncLexExpandedState();
                }, 300);
            }
        });

        // Helper for Lex iframe communication
        window.addEventListener('message', function (event) {
            if (event.data === 'close-lex') $(document).trigger('lex:close');
            if (event.data === 'expand-lex') {
                var $panel = $('.lex-drawer-panel');
                if ($panel.length) {
                    $panel.toggleClass('expanded');
                    localStorage.setItem('lex_drawer_expanded', $panel.hasClass('expanded') ? 'true' : 'false');
                    syncLexExpandedState();
                }
            }
        });

        $('#lex-assistant-frame').on('load', function () {
            syncLexExpandedState();
        });

        // Scroll states
        $(window).scroll(function () {
            if ($(this).scrollTop() > 100) $('.header').addClass('scrolled');
            else $('.header').removeClass('scrolled');
        });
    });

})(jQuery);

