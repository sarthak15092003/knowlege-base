jQuery(document).ready(function ($) {
    var isLoading = false;
    var catsLoaded = [];
    var sequence = DocyInfinite.sequence;
    var lastScrollPos = $(window).scrollTop();

    // Initialize with current category slug
    var initialCat = $('#category-posts-container').data('cat-slug');
    if (initialCat) {
        catsLoaded.push(initialCat);
    }

    function loadNextCategory() {
        if (isLoading) return;

        var lastCat = catsLoaded[catsLoaded.length - 1];
        var currentIndex = sequence.indexOf(lastCat);

        if (currentIndex === -1 || currentIndex >= sequence.length - 1) {
            // Already at the end or not in sequence
            $('#infinite-scroll-loader').hide();
            return;
        }

        var nextCat = sequence[currentIndex + 1];
        if (catsLoaded.indexOf(nextCat) !== -1) return;

        fetchCategory(nextCat, 'append');
    }

    function loadPrevCategory() {
        if (isLoading) return;

        var firstCat = catsLoaded[0];
        var currentIndex = sequence.indexOf(firstCat);

        if (currentIndex <= 0) return; // Already at the first category

        var prevCat = sequence[currentIndex - 1];
        if (catsLoaded.indexOf(prevCat) !== -1) return;

        fetchCategory(prevCat, 'prepend');
    }

    function fetchCategory(slug, mode) {
        isLoading = true;
        if (mode === 'append') {
            $('#infinite-scroll-loader').show();
        } else {
            $('#infinite-scroll-loader-top').show();
        }

        $.ajax({
            url: DocyInfinite.ajax_url,
            type: 'POST',
            data: {
                action: 'docy_get_category_posts_ajax',
                cat_slug: slug,
                security: DocyInfinite.nonce
            },
            success: function (response) {
                if (response.success && response.data && response.data.html) {
                    if (mode === 'append') {
                        $('#infinite-scroll-loader').before(response.data.html);
                        catsLoaded.push(slug);
                    } else {
                        // ROBUST PREPEND LOGIC
                        var $html = $('html');
                        $html.addClass('no-smooth-scroll');

                        var oldHeight = document.documentElement.scrollHeight;
                        var oldScroll = $(window).scrollTop();

                        // Prepend content but keep it hidden briefly to avoid visual jump
                        var $newContent = $(response.data.html).addClass('infinite-item-hidden');
                        var $loader = $('#infinite-scroll-loader-top');

                        // Swap loader for content
                        $loader.hide();
                        $loader.after($newContent);

                        // Calculate total height change (including loader removal)
                        var newHeight = document.documentElement.scrollHeight;
                        var diff = newHeight - oldHeight;

                        // Instant scroll adjustment
                        window.scrollTo(0, oldScroll + diff);

                        // Reveal content and cleanup
                        $newContent.removeClass('infinite-item-hidden');
                        setTimeout(function () {
                            $html.removeClass('no-smooth-scroll');
                        }, 50);

                        catsLoaded.unshift(slug);
                    }
                    console.log('Successfully loaded category: ' + slug);
                    updateSidebarHighlight(slug);
                } else {
                    console.error('Failed to load category: ' + slug, response);
                    $('#infinite-scroll-loader').hide();
                    $('#infinite-scroll-loader-top').hide();
                }
                isLoading = false;
            },
            error: function (xhr, status, error) {
                console.error('AJAX Error loading category: ' + slug, error);
                isLoading = false;
                $('#infinite-scroll-loader').hide();
                $('#infinite-scroll-loader-top').hide();
            }
        });
    }

    /**
     * Sidebar Syncing & Scroll Handling
     */
    var lastActiveSlug = initialCat || '';

    $(window).on('scroll', function () {
        var scrollPos = $(window).scrollTop();
        var windowHeight = $(window).height();
        var docHeight = $(document).height();

        // 1. Infinite Scroll Loading Triggers
        if (scrollPos + windowHeight > docHeight - 300) {
            loadNextCategory();
        }

        // Only load previous category if we are scrolling UP and near the top
        if (scrollPos < 100 && scrollPos < lastScrollPos) {
            loadPrevCategory();
        }

        lastScrollPos = scrollPos;

        // 2. Sidebar Highlighting Logic
        var threshold = scrollPos + 250;
        var currentSlug = '';

        if (scrollPos < 100 && catsLoaded.indexOf(sequence[0]) !== -1) {
            currentSlug = sequence[0];
        } else {
            $('.category-posts-row').each(function () {
                var $header = $(this).prev('.category-header-card');
                var sectionTop = $header.length ? $header.offset().top : $(this).offset().top;
                var sectionBottom = $(this).offset().top + $(this).outerHeight();

                if (threshold >= sectionTop && threshold <= sectionBottom + 50) {
                    currentSlug = $(this).data('cat-slug');
                    return false;
                }
            });
        }

        if (currentSlug && currentSlug !== lastActiveSlug) {
            lastActiveSlug = currentSlug;
            updateSidebarHighlight(currentSlug);
        }
    });

    // 3. Detect INTENT to scroll up at the very top (where scroll event doesn't fire)
    $(window).on('wheel', function (e) {
        if ($(window).scrollTop() <= 5 && e.originalEvent.deltaY < 0) {
            loadPrevCategory();
        }
    });

    var touchStartY = 0;
    $(window).on('touchstart', function (e) {
        touchStartY = e.originalEvent.touches[0].pageY;
    });
    $(window).on('touchmove', function (e) {
        var touchY = e.originalEvent.touches[0].pageY;
        if ($(window).scrollTop() <= 5 && touchY > touchStartY + 20) {
            loadPrevCategory();
        }
    });

    /**
     * Sidebar Syncing & Highlight Updating
     */
    function updateSidebarHighlight(slug) {
        var targetId = slug;
        var targetHeader = $('.section-header[data-target="' + targetId + '"]');

        if (targetHeader.length) {
            if (targetHeader.hasClass('active')) return;

            // Accordion behavior
            $('.section-header.active').removeClass('active');
            $('.section-content.expanded').removeClass('expanded');
            $('.expand-icon.expanded').removeClass('expanded').css('transform', 'rotate(180deg)');

            targetHeader.addClass('active');
            var $content = $('#' + targetId);
            $content.addClass('expanded');

            var $icon = targetHeader.find('.expand-icon');
            $icon.addClass('expanded').css('transform', 'rotate(270deg)');

            var sidebar = $('.modern-sidebar');
            if (sidebar.length) {
                var headerTop = targetHeader.position().top;
                sidebar.stop().animate({
                    scrollTop: sidebar.scrollTop() + headerTop - 20
                }, 300);
            }
        }
    }

    // Set initial state
    $(window).trigger('scroll');
});
