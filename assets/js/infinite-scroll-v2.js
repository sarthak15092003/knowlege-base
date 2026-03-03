jQuery(document).ready(function ($) {
    var isLoading = false;
    var catsLoaded = [];
    var sequence = DocyInfinite.sequence;

    // Initialize with current category slug
    var initialCat = $('#category-posts-container').data('cat-slug');
    if (initialCat) {
        catsLoaded.push(initialCat);
    }

    // Loader logic for observer
    var loader = document.getElementById('infinite-scroll-loader');
    if (loader) {
        $('#infinite-scroll-loader').show();
    }

    function loadNextCategory() {
        if (isLoading) return;

        var lastCat = catsLoaded[catsLoaded.length - 1];
        var currentIndex = sequence.indexOf(lastCat);

        if (currentIndex === -1 || currentIndex >= sequence.length - 1) {
            // Already at the end or not in sequence
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
            $('#infinite-scroll-loader').find('p').text('Loading ' + slug.replace(/-/g, ' ') + '...');
            $('#infinite-scroll-loader').fadeIn();
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
                if (response.success) {
                    if (mode === 'append') {
                        $('#infinite-scroll-loader').before(response.data.html);
                        catsLoaded.push(slug);
                    } else {
                        $('#category-posts-container').prepend(response.data.html);
                        catsLoaded.unshift(slug);
                        // Prevent scroll jump when prepending
                        var addedHeight = $(response.data.html).filter('.category-header-card, .category-posts-row').map(function () {
                            return $(this).outerHeight();
                        }).get().reduce((a, b) => a + b, 0);
                        $(window).scrollTop($(window).scrollTop() + addedHeight);
                    }
                    isLoading = false;
                    console.log('Successfully loaded category: ' + slug);
                    updateSidebarHighlight(slug);
                } else {
                    isLoading = false;
                    $('#infinite-scroll-loader').hide();
                }
            },
            error: function () {
                isLoading = false;
                $('#infinite-scroll-loader').hide();
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
        if (scrollPos < 100) {
            loadPrevCategory();
        }

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
    setTimeout(function () {
        $(window).trigger('scroll');
    }, 500);
});
