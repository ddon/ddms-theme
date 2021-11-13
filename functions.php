<?php declare ( strict_types = 1 );

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

// if ( $_SERVER['REQUEST_URI'] === '/' ) {
//     header( $_SERVER["SERVER_PROTOCOL"] . " 404 Not Found", true, 404 );
//     die();
// }

date_default_timezone_set( 'EST' );

// require_once 'vendor/autoload.php';

// $include_dirs = [
//     "/includes/*.php",
//     "/includes/api/*.php",
//     "/includes/fotki-php/*.php",
//     "/includes/Entities/*.php",
// ];

// foreach ( $include_dirs as $include_dir ) {
//     foreach ( glob( dirname( __FILE__ ) . $include_dir ) as $filename ) {
//         include_once $filename;
//     }
// }