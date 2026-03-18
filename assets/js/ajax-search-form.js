; (function ($) {
    "use strict";

    /**
     * Registration Form
     */
    if (jQuery(".registerform").length) {
        jQuery(".registerform").on("submit", function (e) {
            e.preventDefault();
            let ajax_url = docy_local_object.ajaxurl;
            jQuery.post(
                ajax_url,
                {
                    data: jQuery(this).serialize(),
                    action: "dt_custom_registration_form",
                },
                function (res) {
                    jQuery("#reg-form-validation-messages").html(res.data.message);
                }
            );
            return false;
        });
    }

    // Search widget
    const postTypes = docy_local_object.post_types;
    let activePostType = 'all';
    let searchTimeout;
    let currentSearchTerm = ''; // Store the current search term

    function docySearchTabs() {
        let tabsHtml = `<div data-type="all" class="tab-item active">All</div>`;
        if (postTypes && Array.isArray(postTypes)) {
            postTypes.forEach(type => {
                const cleanedType = type.replace(/[-_]+/g, ' ').replace(/[^a-zA-Z0-9\s]/g, '').trim();
                const postTypeName = cleanedType.charAt(0).toUpperCase() + cleanedType.slice(1);
                tabsHtml += `<div data-type="${type}" class="tab-item">${postTypeName}</div>`;
            });
        }
        $('#search-tabs').html(tabsHtml);
    }

    function displayResults(postType, searchTerm) {
        $('#search-preloader').show();
        $.ajax({
            url: docy_local_object.ajaxurl,
            method: 'POST',
            data: {
                action: 'ajax_search',
                post_type: postType !== 'all' ? postType : postTypes,
                keyword: searchTerm,
                security: docy_local_object.ajax_nonce
            },
            beforeSend: function () {
                $(".spinner").css("display", "block");
                $('#search-results').append('<div id="search-preloader"></div>')
            },
            success: function (response) {
                $('#docy-search-result').addClass('ajax-search');
                $('#search-results').html(response ? response : `<h5 class="error">${$('#docy-search-result').data('noresult')}</h5>`);
                $(".spinner").hide();
            }
        });
    }

    // Throttled search on keyup for improved performance
    function handleSearch(searchTerm) {
        clearTimeout(searchTimeout);
        currentSearchTerm = searchTerm;

        if (currentSearchTerm) {
            searchTimeout = setTimeout(() => {
                displayResults(activePostType, currentSearchTerm);
            }, 100);
        } else {
            $("#docy-search-result").removeClass("ajax-search").html("");
            $(".spinner").hide();
        }
    }

    // Event listener for keyup on search input
    $('#searchInput').on('keyup', function () {
        if ($(this).hasClass('use-cmgalaxy-live-search-input')) {
            return;
        }
        if ($(this).val()) {
            handleSearch($(this).val());
        }
    });

    // Event listener for search keyword click
    $(".header_search_keyword ul li a").on("click", function (e) {
        e.preventDefault();
        const searchTerm = $(this).text();
        $("#searchInput").val(searchTerm).focus();
        handleSearch(searchTerm);
    });

    // Tab switching
    $('#search-tabs').on('click', '.tab-item', function (e) {
        e.preventDefault();
        activePostType = $(this).data('type');
        $('.tab-item').removeClass('active');
        $(this).addClass('active');

        $('#search-results').children().not('#search-preloader').hide();
        $('#search-preloader').show();

        if (currentSearchTerm) {
            displayResults(activePostType, currentSearchTerm);
        }
    });

    docySearchTabs();

})(jQuery);
