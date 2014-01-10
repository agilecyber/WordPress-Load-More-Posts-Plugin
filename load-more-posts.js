jQuery(function($) {
    $(document).on('click', '#load-more-posts a', function(event) {
        event.preventDefault();

        var button = $(this),
            data = {
            'action': 'load_more_posts',
            'query': load_more_posts_ajax.query,
            'page': load_more_posts_ajax.page
        };

        $.ajax({
            url: load_more_posts_ajax.ajax_url,
            data: data,
            type: 'POST',
            beforeSend: function(xhr) {
                button.text('Loading...');
            },
            success: function(data) {
                if( data ) {
                    button.text('Load more posts');
                    $('#content').append(data);
                    load_more_posts_ajax.page++;
                    if( load_more_posts_ajax.page == load_more_posts_ajax.max_page )
                        button.remove();
                } else {
                    button.remove();
                }
            }
        });
    });
});
