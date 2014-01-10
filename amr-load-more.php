<?php
/*
Plugin Name: Load More Posts
Plugin URI: https://www.github.com/amrvignesh
Description: Load more posts on button click.
Version: 1.0
Author: Vignesh A M R
*/

function load_more_posts_scripts() {
    wp_enqueue_script( 'load-more-posts', plugin_dir_url( __FILE__ ) . 'load-more-posts.js', array('jquery'), '1.0', true );
    wp_localize_script( 'load-more-posts', 'load_more_posts_ajax', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
}
add_action( 'wp_enqueue_scripts', 'load_more_posts_scripts' );

function load_more_posts_callback() {
    $args = json_decode( stripslashes( $_POST['query'] ), true );
    $args['paged'] = $_POST['page'] + 1; 
    $args['post_status'] = 'publish'; 

    $query = new WP_Query( $args );

    if( $query->have_posts() ) :
        while( $query->have_posts() ): $query->the_post();
            get_template_part( 'template-parts/content', 'excerpt' );
        endwhile;
    endif;

    wp_die();
}
add_action('wp_ajax_load_more_posts', 'load_more_posts_callback');
add_action('wp_ajax_nopriv_load_more_posts', 'load_more_posts_callback');

function load_more_posts_button() {
    global $wp_query;
    $max_pages = $wp_query->max_num_pages;

    $args = array(
        'total' => $max_pages,
        'current' => $current_page,
        'prev_next' => true,
        'prev_text' => __('« Previous'),
        'next_text' => __('Next »'),
        'type' => 'plain',
        'add_args' => false,
        'add_fragment' => ''
    );

    if( $max_pages > 1 ) :
?>
    <div id="load-more-posts" class="load-more-posts">
        <?php echo paginate_links( $args ); ?>
    </div>
<?php
    endif;
}
add_action( 'wp_ajax_nopriv_load_more_posts', 'load_more_posts_button' );
add_action( 'wp_ajax_load_more_posts', 'load_more_posts_button' );
