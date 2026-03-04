jQuery(document).ready(function ($) {
    if (typeof DocyInfinite === 'undefined') {
        console.error('DocyInfinite is not defined!');
        return;
    }

    var isLoading = false;
    var catsLoaded = [];
    var sequence = DocyInfinite.sequence || [];

    // Safety: Recover sequence from debug div if needed
    if (sequence.length === 0) {
        var debugSeq = $('#cat-sequence-debug').data('sequence');
        if (debugSeq) sequence = debugSeq;
    }

    console.log('--- Infinite Scroll V2 ---');
    console.log('Sequence:', JSON.stringify(sequence));

    // Identify current category
    var $container = $('#category-posts-container');
    var initialCat = $container.attr('data-cat-slug') || $container.data('cat-slug');

    if (initialCat) {
        catsLoaded.push(initialCat);
        console.log('Initial Category:', initialCat);
    } else {
        console.warn('No initial cat slug found on #category-posts-container');
    }

    function loadNext() {
        if (isLoading || sequence.length < 2) return;
        var last = catsLoaded[catsLoaded.length - 1];
        var idx = sequence.indexOf(last);

        console.log('loadNext check. Last loaded:', last, 'Index:', idx);

        if (idx === -1 || idx >= sequence.length - 1) {
            console.log('Reached end of sequence or cat not found.');
            return;
        }

        var next = sequence[idx + 1];
        if (catsLoaded.indexOf(next) !== -1) {
            console.log('Next category already loaded:', next);
            return;
        }

        console.log('>> FETCHING NEXT:', next);
        fetchCat(next, 'append');
    }

    function loadPrev() {
        if (isLoading || sequence.length < 2) return;
        var first = catsLoaded[0];
        var idx = sequence.indexOf(first);

        if (idx <= 0) return;

        var prev = sequence[idx - 1];
        if (catsLoaded.indexOf(prev) !== -1) return;

        console.log('>> FETCHING PREV:', prev);
        fetchCat(prev, 'prepend');
    }

    function fetchCat(slug, mode) {
        if (isLoading) return;
        isLoading = true;

        var loader = (mode === 'append') ? '#infinite-scroll-loader' : '#infinite-scroll-loader-up';
        $(loader).fadeIn(150).find('p').text('Loading ' + slug.replace(/-/g, ' ') + '...');

        console.log('AJAX call for slug:', slug);

        $.ajax({
            url: DocyInfinite.ajax_url,
            type: 'POST',
            data: {
                action: 'docy_get_category_posts_ajax',
                cat_slug: slug,
                security: DocyInfinite.nonce
            },
            success: function (res) {
                console.log('AJAX Response for', slug, ':', res.success);
                if (res.success && res.data.html) {
                    var $newContent = $(res.data.html);

                    if (mode === 'append') {
                        $('#infinite-scroll-loader').before($newContent);
                        catsLoaded.push(slug);
                    } else {
                        var hOld = $(document).height();
                        var sOld = $(window).scrollTop();
                        $('#infinite-scroll-loader-up').after($newContent);
                        catsLoaded.unshift(slug);
                        var hNew = $(document).height();
                        $(window).scrollTop(sOld + (hNew - hOld));
                    }
                    console.log('Successfully Loaded & Appended:', slug);
                    updateSidebar(slug);

                    // Immediately check if we need to load MORE (e.g. screen still not full)
                    setTimeout(checkHeight, 300);
                } else {
                    console.error('AJAX Success but failed to provide HTML for:', slug);
                }
            },
            error: function (xhr, status, error) {
                console.error('AJAX Error for:', slug, error);
            },
            complete: function () {
                isLoading = false;
                $('#infinite-scroll-loader, #infinite-scroll-loader-up').hide();
            }
        });
    }

    function checkHeight() {
        if (isLoading) return;
        var s = $(window).scrollTop();
        var wh = $(window).height();
        var dh = $(document).height();

        // Downward trigger: Near bottom (within 1200px)
        if (s + wh > dh - 1200) {
            loadNext();
        }

        // Upward trigger: Near top (within 800px)
        if (s < 800 && s > 10) {
            loadPrev();
        }
    }

    $(window).on('scroll resize', function () {
        checkHeight();

        // Sidebar Highlighting
        var s = $(window).scrollTop();
        var wh = $(window).height();
        var mid = s + (wh / 3);
        var cur = '';

        $('.category-posts-row').each(function () {
            var $r = $(this);
            var $h = $r.prev('.category-header-card');
            var t = $h.length ? $h.offset().top : $r.offset().top;
            var b = $r.offset().top + $r.outerHeight();
            if (mid >= t && mid <= b) {
                cur = $r.data('cat-slug');
                return false;
            }
        });

        if (cur && cur !== lastSlug) {
            lastSlug = cur;
            updateSidebar(cur);
        }
    });

    var lastSlug = initialCat;

    function updateSidebar(slug) {
        var $btn = $('.section-header[data-target="' + slug + '"]');
        if ($btn.length && !$btn.hasClass('active')) {
            $('.section-header.active').removeClass('active');
            $('.section-content.expanded').removeClass('expanded');
            $('.expand-icon.expanded').removeClass('expanded').css('transform', 'rotate(180deg)');

            $btn.addClass('active');
            $('#' + slug).addClass('expanded');
            $btn.find('.expand-icon').addClass('expanded').css('transform', 'rotate(270deg)');

            var $sb = $('.modern-sidebar');
            if ($sb.length) {
                var p = $btn.position().top;
                $sb.stop().animate({ scrollTop: $sb.scrollTop() + p - 60 }, 300);
            }
        }
    }

    // Aggressive initial checks to fill screen
    var initChecks = 0;
    var initInterval = setInterval(function () {
        checkHeight();
        initChecks++;
        if (initChecks > 10) clearInterval(initInterval);
    }, 1000);

    // Immediate check
    checkHeight();
});
