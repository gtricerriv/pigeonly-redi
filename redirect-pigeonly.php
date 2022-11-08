<?php
/*
Plugin Name: Redirect Pigeonly
Plugin URI: Deuslink
Description: Plugin ...
Version: 0.1
Author: Deuslink
License: GPLv2 or later
*/

if ( is_readable( __DIR__ . '/vendor/autoload.php' ) ) {
    require __DIR__ . '/vendor/autoload.php';
}

/**
 * Define Constants
 */
define( 'PLUGIN_VERSION', '1.0.8' );
define( 'PLUGIN_PATH', plugins_url('/', __FILE__) );
define( 'PIGEONLY_DOMAIN', 'https://pigeonly.com/' );

function get_file( $path ){
    $file = dirname( __FILE__ ) . $path;
    return require_once $file;
}

/**
 * Get files
 */
get_file( '/includes/admin/dashboard.php' );
get_file( '/includes/shortcode.php' );

get_file( '/includes/redirect-logic.php' );
get_file( '/includes/create-posts.php' );