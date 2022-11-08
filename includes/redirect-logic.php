<?php 
/**
 * 
 * Redirect logic
 * 
 */

add_action('wp_footer', 'insert_my_footer');
function insert_my_footer() {
    $page_id = get_queried_object_id();
    $url_redirect = get_field( 'redirect_to', $page_id );

    if ( get_field( 'if_redirect', $page_id ) == true )
        echo do_shortcode( '[redirect-page url="'. $url_redirect .'"]' );
}

