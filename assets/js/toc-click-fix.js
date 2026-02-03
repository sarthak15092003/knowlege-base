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

            // Add active to the target
            var $targetLink = $('#docy-toc a[href="#' + targetId + '"], .doc-nav a[href="#' + targetId + '"], .nav-sidebar a[href="#' + targetId + '"]');
            $targetLink.addClass('active');
            $targetLink.parent('li').addClass('active');
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
                var targetPosition = target.offset().top - scrollOffset;

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

            var scrollPos = $(window).scrollTop() + 150; // Offset for detection
            var currentSection = null;

            // Find all headings with IDs
            $('.blog_single_item h1[id], .blog_single_item h2[id], .blog_single_item h3[id], .blog_single_item h4[id], .blog_single_item h5[id]').each(function () {
                var $heading = $(this);
                var headingTop = $heading.offset().top;

                if (scrollPos >= headingTop) {
                    currentSection = $heading.attr('id');
                }
            });

            if (currentSection) {
                setActiveTocItem(currentSection);
            }
        }

        // Throttled scroll handler
        $(window).on('scroll', function () {
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

            // Add to clicked
            $clickedLink.addClass('active');
            $clickedLink.parent('li').addClass('active');

            var target = $(href);

            if (target.length) {
                var adminBarHeight = $('#wpadminbar').length > 0 ? $('#wpadminbar').height() : 0;
                var scrollOffset = ($(window).width() <= 1024 ? 70 : 120) + adminBarHeight;
                var targetPosition = target.offset().top - scrollOffset;

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
