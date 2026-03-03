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
    console.log('Order:', JSON.stringify(sequence));

    // Identify current category
    var $container = $('#category-posts-container');
    var initialCat = $container.attr('data-cat-slug') || $container.data('cat-slug');

    if (initialCat) {
        catsLoaded.push(initialCat);
        console.log('Started at:', initialCat);
    }

    function loadNext() {
        if (isLoading || sequence.length < 2) return;
        var last = catsLoaded[catsLoaded.length - 1];
        var idx = sequence.indexOf(last);
        if (idx === -1 || idx >= sequence.length - 1) return;

        var next = sequence[idx + 1];
        if (catsLoaded.indexOf(next) !== -1) return;

        console.log('>> Trigger Next:', next);
        fetchCat(next, 'append');
    }

    function loadPrev() {
        if (isLoading || sequence.length < 2) return;
        var first = catsLoaded[0];
        var idx = sequence.indexOf(first);
        if (idx <= 0) return;

        var prev = sequence[idx - 1];
        if (catsLoaded.indexOf(prev) !== -1) return;

        console.log('>> Trigger Prev:', prev);
        fetchCat(prev, 'prepend');
    }

    function fetchCat(slug, mode) {
        if (isLoading) return;
        isLoading = true;

        var loader = (mode === 'append') ? '#infinite-scroll-loader' : '#infinite-scroll-loader-up';
        $(loader).fadeIn(100).find('p').text('Loading ' + slug.replace(/-/g, ' ') + '...');

        $.ajax({
            url: DocyInfinite.ajax_url,
            type: 'POST',
            data: {
                action: 'docy_get_category_posts_ajax',
                cat_slug: slug,
                security: DocyInfinite.nonce
            },
            success: function (res) {
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
                    console.log('DONE:', slug);
                    updateSidebar(slug);
                    setTimeout(checkHeight, 500);
                } else {
                    console.error('AJAX OK but no HTML', slug);
                }
            },
            error: function (e) { console.error('AJAX Error', e); },
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

        if (s + wh > dh - 1200) loadNext();
        if (s < 600 && s > 20) loadPrev();
    }

    $(window).on('scroll', function () {
        checkHeight();

        // Sidebar Sync
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

    // Initial check
    setTimeout(checkHeight, 1000);
    setTimeout(checkHeight, 2500);
});
