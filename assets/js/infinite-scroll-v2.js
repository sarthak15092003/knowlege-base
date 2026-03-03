jQuery(document).ready(function ($) {
    if (typeof DocyInfinite === 'undefined') {
        console.error('DocyInfinite is not defined!');
        return;
    }

    var isLoading = false;
    var catsLoaded = [];
    var sequence = DocyInfinite.sequence;

    // Check for debug sequence if localized one is empty
    if (!sequence || sequence.length === 0) {
        var debugSeq = $('#cat-sequence-debug').data('sequence');
        if (debugSeq) sequence = debugSeq;
    }

    console.log('--- Infinite Scroll V2 Initializing ---');
    console.log('Category Sequence:', JSON.stringify(sequence));

    // Initialize with current category slug
    var $container = $('#category-posts-container');
    var initialCat = $container.attr('data-cat-slug'); // Use attr to be sure
    console.log('Starting Category:', initialCat);

    if (initialCat) {
        catsLoaded.push(initialCat);
    } else {
        console.warn('No initial category slug found!');
    }

    function loadNextCategory() {
        if (isLoading || sequence.length === 0) return;

        var lastCat = catsLoaded[catsLoaded.length - 1];
        var currentIndex = sequence.indexOf(lastCat);

        console.log('Check Load Next. Last:', lastCat, 'Index:', currentIndex);

        if (currentIndex === -1 || currentIndex >= sequence.length - 1) {
            return;
        }

        var nextCat = sequence[currentIndex + 1];
        if (catsLoaded.indexOf(nextCat) !== -1) return;

        console.log('-> Loading Next Category:', nextCat);
        fetchCategory(nextCat, 'append');
    }

    function loadPrevCategory() {
        if (isLoading || sequence.length === 0) return;

        var firstCat = catsLoaded[0];
        var currentIndex = sequence.indexOf(firstCat);

        if (currentIndex <= 0) return;

        var prevCat = sequence[currentIndex - 1];
        if (catsLoaded.indexOf(prevCat) !== -1) return;

        console.log('-> Loading Previous Category:', prevCat);
        fetchCategory(prevCat, 'prepend');
    }

    function fetchCategory(slug, mode) {
        if (isLoading) return;
        isLoading = true;

        var activeLoader = (mode === 'append') ? '#infinite-scroll-loader' : '#infinite-scroll-loader-up';
        $(activeLoader).fadeIn(200);

        $.ajax({
            url: DocyInfinite.ajax_url,
            type: 'POST',
            data: {
                action: 'docy_get_category_posts_ajax',
                cat_slug: slug,
                security: DocyInfinite.nonce
            },
            success: function (response) {
                if (response.success && response.data.html) {
                    var $html = $(response.data.html);

                    if (mode === 'append') {
                        $('#infinite-scroll-loader').before($html);
                        catsLoaded.push(slug);
                    } else {
                        var oldHeight = $(document).height();
                        var oldScroll = $(window).scrollTop();

                        $('#infinite-scroll-loader-up').after($html);
                        catsLoaded.unshift(slug);

                        var newHeight = $(document).height();
                        $(window).scrollTop(oldScroll + (newHeight - oldHeight));
                    }

                    console.log('Loaded Successfully:', slug);
                    updateSidebarHighlight(slug);
                    setTimeout(checkAndFill, 600);
                } else {
                    console.error('Empty response or error for slug:', slug);
                }
            },
            error: function (xhr, status, error) {
                console.error('AJAX Fail:', error);
            },
            complete: function () {
                isLoading = false;
                $('#infinite-scroll-loader, #infinite-scroll-loader-up').hide();
            }
        });
    }

    function checkAndFill() {
        var scrollPos = $(window).scrollTop();
        var windowHeight = $(window).height();
        var docHeight = $(document).height();

        if (scrollPos + windowHeight > docHeight - 1200) {
            loadNextCategory();
        }
    }

    $(window).on('scroll', function () {
        var scrollPos = $(window).scrollTop();
        var windowHeight = $(window).height();
        var docHeight = $(document).height();

        // Triggers
        if (scrollPos + windowHeight > docHeight - 1000) {
            loadNextCategory();
        }
        if (scrollPos < 400 && scrollPos > 20) {
            loadPrevCategory();
        }

        // Sidebar Update
        var threshold = scrollPos + (windowHeight / 2.5);
        var currentSlug = '';

        $('.category-posts-row').each(function () {
            var $row = $(this);
            var $header = $row.prev('.category-header-card');
            var top = $header.length ? $header.offset().top : $row.offset().top;
            var bottom = $row.offset().top + $row.outerHeight();

            if (threshold >= top && threshold <= bottom + 50) {
                currentSlug = $row.data('cat-slug');
                return false;
            }
        });

        if (currentSlug && currentSlug !== lastActiveSlug) {
            lastActiveSlug = currentSlug;
            updateSidebarHighlight(currentSlug);
        }
    });

    var lastActiveSlug = initialCat || '';

    function updateSidebarHighlight(slug) {
        var $header = $('.section-header[data-target="' + slug + '"]');
        if ($header.length && !$header.hasClass('active')) {
            $('.section-header.active').removeClass('active');
            $('.section-content.expanded').removeClass('expanded');
            $('.expand-icon.expanded').removeClass('expanded').css('transform', 'rotate(180deg)');

            $header.addClass('active');
            $('#' + slug).addClass('expanded');
            $header.find('.expand-icon').addClass('expanded').css('transform', 'rotate(270deg)');

            var sidebar = $('.modern-sidebar');
            if (sidebar.length) {
                var pos = $header.position().top;
                sidebar.stop().animate({ scrollTop: sidebar.scrollTop() + pos - 60 }, 400);
            }
        }
    }

    // Run on startup
    setTimeout(function () {
        checkAndFill();
    }, 1500);
});
