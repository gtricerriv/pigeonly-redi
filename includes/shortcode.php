<?php 
/**
 * 
 * Shortcode
 * 
 */


function redirect_page($atts = '') {
    ob_start();
    $atributos = shortcode_atts( 
        [ 
            'url' => PIGEONLY_DOMAIN,
        ],
        $atts 
    );
    
    wp_redirect( $atributos['url'] );
    return ob_get_clean();
}
add_shortcode( 'redirect-page', 'redirect_page' );