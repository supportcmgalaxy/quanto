jQuery(document).ready(function($) {
    var currentPage = 1;
    var currentPublisher = '';
    var currentSearch = '';
    var isLoading = false;

    function loadMediaCoverage(append) {
        if (typeof append === 'undefined') append = false;
        if (isLoading) return;
        isLoading = true;

        var grid = $('#cmr-mc-grid-container');
        var loadMoreBtn = $('#cmr-mc-load-more');

        if (!append) {
            grid.html('<div class="cmr-mc-loading">Loading...</div>');
            loadMoreBtn.hide();
        } else {
            loadMoreBtn.text('Loading...').prop('disabled', true);
        }

        $.ajax({
            url: cmr_mc_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'cmr_filter_media_coverage',
                page: currentPage,
                publisher: currentPublisher,
                search: currentSearch
            },
            success: function(response) {
                if (response.success) {
                    if (!append) {
                        grid.html(response.data.html);
                    } else {
                        grid.append(response.data.html);
                    }

                    if (response.data.has_more) {
                        loadMoreBtn.show().text('Load More').prop('disabled', false);
                    } else {
                        loadMoreBtn.hide();
                    }
                }
                isLoading = false;
            },
            error: function() {
                if (!append) {
                    grid.html('<p class="cmr-mc-no-results">An error occurred while loading.</p>');
                }
                isLoading = false;
                loadMoreBtn.text('Load More').prop('disabled', false);
            }
        });
    }

    // Initial load (skip AJAX if already server-side rendered)
    if ($('#cmr-mc-grid-container .cmr-mc-loading').length > 0) {
        loadMediaCoverage();
    }

    // Dropdown toggle
    $(document).on('click', '.cmr-mc-dropdown-toggle', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).closest('.cmr-mc-filter-dropdown').toggleClass('open');
    });

    // Close dropdown on outside click
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.cmr-mc-filter-dropdown').length) {
            $('.cmr-mc-filter-dropdown').removeClass('open');
        }
    });

    // Top Level Publisher Pill Click
    $(document).on('click', '.cmr-mc-pills > .cmr-mc-pill', function(e) {
        if ($(this).hasClass('cmr-mc-dropdown-toggle')) return;

        $('.cmr-mc-pill').removeClass('active');
        $('.cmr-mc-dropdown-item').removeClass('active');
        $('.cmr-mc-dropdown-toggle span').text('More');
        $('.cmr-mc-filter-dropdown').removeClass('open');
        $(this).addClass('active');

        currentPublisher = $(this).data('publisher') || '';
        currentPage = 1;
        loadMediaCoverage(false);
    });

    // Dropdown Item Click
    $(document).on('click', '.cmr-mc-dropdown-item', function(e) {
        e.preventDefault();
        var pub = $(this).data('publisher');
        
        $('.cmr-mc-pill').removeClass('active');
        $('.cmr-mc-dropdown-item').removeClass('active');
        $(this).addClass('active');

        var dropdown = $(this).closest('.cmr-mc-filter-dropdown');
        dropdown.find('.cmr-mc-dropdown-toggle').addClass('active');
        dropdown.find('.cmr-mc-dropdown-toggle span').text(pub);
        dropdown.removeClass('open');

        currentPublisher = pub;
        currentPage = 1;
        loadMediaCoverage(false);
    });

    // Search Input with Debounce
    var searchTimeout;
    $('#cmr-mc-search-input').on('input', function() {
        clearTimeout(searchTimeout);
        var val = $(this).val();
        searchTimeout = setTimeout(function() {
            currentSearch = val;
            currentPage = 1;
            loadMediaCoverage(false);
        }, 400);
    });

    // Load More Click
    $('#cmr-mc-load-more').on('click', function() {
        currentPage++;
        loadMediaCoverage(true);
    });
});
