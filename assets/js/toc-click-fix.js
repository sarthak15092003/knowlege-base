/**
 * TOC Click Fix - Custom implementation for accurate TOC highlighting
 */
(function ($) {
    'use strict';

    $(document).ready(function () {
        var isManualScroll = false;
        var scrollTimeout;

        // Function to update active TOC item
        function setActiveTocItem(targetId) {
            // Remove all active classes
            $('#docy-toc a, .doc-nav a, .nav-sidebar a').removeClass('active');
            $('#docy-toc li, .doc-nav li, .nav-sidebar li').removeClass('active');

            // Add active to the target and all its parent list items
            var $targetLink = $('#docy-toc a[href="#' + targetId + '"], .doc-nav a[href="#' + targetId + '"], .nav-sidebar a[href="#' + targetId + '"]');
            $targetLink.addClass('active');
            $targetLink.parents('li').addClass('active'); // Bubbles active class up to first-level steps
        }

        // Handle TOC link clicks
        $(document).on('click', '#docy-toc a, .doc-nav a, .nav-sidebar a', function (e) {
            var $clickedLink = $(this);
            var href = $clickedLink.attr('href');

            // Only handle anchor links
            if (!href || !href.startsWith('#')) {
                return;
            }

            e.preventDefault();
            e.stopPropagation();

            // Set flag to prevent scrollspy interference
            isManualScroll = true;

            // Get target ID
            var targetId = href.substring(1);

            // Immediately set active state
            setActiveTocItem(targetId);

            // Get the target element
            var target = $(href);

            if (target.length) {
                // Scroll to target with offset for sticky header
                var adminBarHeight = $('#wpadminbar').length > 0 ? $('#wpadminbar').height() : 0;
                var scrollOffset = ($(window).width() <= 1024 ? 70 : 120) + adminBarHeight;

                // Fix: Account for body scrolling by adding current scrollTop
                var currentScroll = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop;
                var targetPosition = target[0].getBoundingClientRect().top + currentScroll - scrollOffset;

                $('html, body').stop().animate({
                    scrollTop: targetPosition
                }, 600, function () {
                    // After scroll completes, keep the active state and re-enable scrollspy
                    setTimeout(function () {
                        setActiveTocItem(targetId);
                        isManualScroll = false;
                    }, 300);
                });
            }
        });

        // Custom scroll detection for automatic TOC highlighting
        function updateTocOnScroll() {
            if (isManualScroll) return;

            var currentScroll = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop;
            var scrollPos = currentScroll + 150; // Offset for detection
            var currentSection = null;

            // Find all headings with IDs
            $('.blog_single_item h1[id], .blog_single_item h2[id], .blog_single_item h3[id], .blog_single_item h4[id], .blog_single_item h5[id]').each(function () {
                var $heading = $(this);
                var currentScroll = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop;
                var headingTop = $heading[0].getBoundingClientRect().top + currentScroll;

                if (scrollPos >= headingTop) {
                    currentSection = $heading.attr('id');
                }
            });

            if (currentSection) {
                setActiveTocItem(currentSection);
            }
        }

        // Throttled scroll handler
        $(window).add(document.body).on('scroll', function () {
            clearTimeout(scrollTimeout);
            scrollTimeout = setTimeout(updateTocOnScroll, 100);
        });

        // Initial check
        updateTocOnScroll();

        // Also handle clicks on the mobile TOC
        $(document).on('click', '#docy-tocs a, .bottom_table_content a', function (e) {
            var $clickedLink = $(this);
            var href = $clickedLink.attr('href');

            if (!href || !href.startsWith('#')) {
                return;
            }

            e.preventDefault();
            e.stopPropagation();

            isManualScroll = true;

            var targetId = href.substring(1);

            // Remove active from all
            $('#docy-tocs a, .bottom_table_content a').removeClass('active');
            $('#docy-tocs li, .bottom_table_content li').removeClass('active');

            // Add to clicked and all its parent list items
            $clickedLink.addClass('active');
            $clickedLink.parents('li').addClass('active');

            var target = $(href);

            if (target.length) {
                var adminBarHeight = $('#wpadminbar').length > 0 ? $('#wpadminbar').height() : 0;
                var scrollOffset = ($(window).width() <= 1024 ? 70 : 120) + adminBarHeight;

                // Fix: Account for body scrolling by adding current scrollTop
                var currentScroll = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop;
                var targetPosition = target[0].getBoundingClientRect().top + currentScroll - scrollOffset;

                $('html, body').stop().animate({
                    scrollTop: targetPosition
                }, 600, function () {
                    setTimeout(function () {
                        isManualScroll = false;
                    }, 300);
                });

                // Close mobile TOC if open
                $('.bottom_table_content').slideUp(300);
                $('#toc-overlay').fadeOut(300);
            }
        });
    });

})(jQuery);
