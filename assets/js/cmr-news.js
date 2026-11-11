jQuery(document).ready(function($) {
    $('.cmr-news-tab-btn').on('click', function() {
        // Remove active class from all buttons
        $(this).siblings().removeClass('active');
        // Add active class to clicked button
        $(this).addClass('active');

        // Hide all tab panes
        var container = $(this).closest('.cmr-news-container');
        container.find('.cmr-news-tab-pane').removeClass('active');

        // Show target tab pane
        var targetId = $(this).data('target');
        container.find('#' + targetId).addClass('active');
        
        // Update "Explore All" text
        var arrowHtml = '<img src="/wp-content/uploads/2026/04/Symbol.svg" class="cmr-arrow-icon" alt="Arrow">';
        container.find('.cmr-explore-all').html('Explore All ' + arrowHtml);
    });
    
    // Initialize the first tab's button text
    if ($('.cmr-explore-all').length) {
        var arrowHtml = '<img src="/wp-content/uploads/2026/04/Symbol.svg" class="cmr-arrow-icon" alt="Arrow">';
        $('.cmr-explore-all').html('Explore All ' + arrowHtml);
    }
});

jQuery(document).ready(function($) {
    if ( $('.cmr-media-coverage-wrapper').length === 0 ) return;

    var currentPage = 1;
    var currentPublisher = 'all';
    var currentSearch = '';
    var isLoading = false;
    
    function loadCoverage( reset ) {
        if ( isLoading ) return;
        isLoading = true;
        
        if ( reset ) {
            currentPage = 1;
            $('#cmr-mc-results').css('opacity', '0.5');
        } else {
            currentPage++;
            $('#cmr-mc-load-more').text('Loading...');
        }
        
        $.ajax({
            url: cmr_news_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'cmr_load_media_coverage',
                nonce: cmr_news_ajax.nonce,
                page: currentPage,
                publisher: currentPublisher,
                search: currentSearch
            },
            success: function( response ) {
                if ( response.success ) {
                    if ( reset ) {
                        $('#cmr-mc-results').html( response.data.html ).css('opacity', '1');
                    } else {
                        // Append to existing grid if it exists, otherwise just append
                        var newHtml = $(response.data.html);
                        var existingGridInner = $('#cmr-mc-results').find('.cmr-mc-grid-inner');
                        
                        if ( existingGridInner.length ) {
                            // Only append the inner cards to the existing grid
                            var innerItems = newHtml.find('.cmr-mc-card');
                            if(innerItems.length === 0 && newHtml.hasClass('cmr-mc-card')) {
                                innerItems = newHtml;
                            } else if (innerItems.length === 0) {
                                // fallback if wrapper is different
                                innerItems = newHtml.html();
                            }
                            existingGridInner.append( innerItems );
                        } else {
                            $('#cmr-mc-results').append( response.data.html );
                        }
                    }
                    
                    if ( response.data.has_more ) {
                        $('#cmr-mc-load-more-wrap').show();
                        $('#cmr-mc-load-more').text('Load More');
                    } else {
                        $('#cmr-mc-load-more-wrap').hide();
                    }
                }
                isLoading = false;
            },
            error: function() {
                isLoading = false;
                $('#cmr-mc-results').css('opacity', '1');
                $('#cmr-mc-load-more').text('Load More');
            }
        });
    }

    // Filter by publisher
    $('.cmr-mc-filter-btn').on('click', function() {
        $('.cmr-mc-filter-btn').removeClass('active');
        $(this).addClass('active');
        
        // if this was inside dropdown, highlight the dropdown toggle
        if ( $(this).closest('.cmr-mc-dropdown-menu').length ) {
            $(this).closest('.cmr-mc-filter-dropdown').find('.cmr-mc-dropdown-toggle').addClass('active-filter');
        } else {
            $('.cmr-mc-dropdown-toggle').removeClass('active-filter');
        }
        
        currentPublisher = $(this).data('publisher');
        loadCoverage( true );
    });

    // Search
    $('#cmr-mc-search-btn').on('click', function() {
        currentSearch = $('#cmr-mc-search-input').val();
        loadCoverage( true );
    });
    
    $('#cmr-mc-search-input').on('keypress', function(e) {
        if (e.which == 13) {
            currentSearch = $(this).val();
            loadCoverage( true );
        }
    });

    // Load more
    $('#cmr-mc-load-more').on('click', function() {
        loadCoverage( false );
    });
    
    // Dropdown toggle
    $('.cmr-mc-dropdown-toggle').on('click', function() {
        $(this).siblings('.cmr-mc-dropdown-menu').toggle();
    });
    
    $(document).on('click', function(e) {
        if ( !$(e.target).closest('.cmr-mc-filter-dropdown').length ) {
            $('.cmr-mc-dropdown-menu').hide();
        }
    });
});
