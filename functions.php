<?php declare ( strict_types = 1 );

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

define( 'DDMS_THEME_DIR', dirname( __FILE__ ) );

date_default_timezone_set( 'EST' );
// if ( $_SERVER['REQUEST_URI'] === '/' ) {
//     header( $_SERVER["SERVER_PROTOCOL"] . " 404 Not Found", true, 404 );
//     die();
// }

// require_once 'vendor/autoload.php';
include_once DDMS_THEME_DIR . "/includes/Entities/BaseEntity.class.php"; //loading BaseEntity first

$include_dirs = [
    "/includes/*.php",
    "/includes/api/*.php",
    "/includes/Entities/*.php",
    "/includes/fotki-php/ACF.php",
    "/includes/fotki-php/Fields.php",
    "/includes/fotki-php/Response.php",
];

foreach ( $include_dirs as $include_dir ) {
    foreach ( glob( dirname( __FILE__ ) . $include_dir ) as $filename ) {
        include_once $filename;
    }
}

// $just_test = \DDMS\Entities\Area::getById(60); //skrasii. remove
// $just_test = \DDMS\Entities\Area::getAllByDockId(15); //skrasii. remove
// $just_test = \DDMS\Entities\Job::getById(128); //skrasii. remove
// $just_test = \DDMS\Entities\Job::getAllActiveByDockId(15); //skrasii. remove
// $just_test = \DDMS\Entities\Job::getAllActiveByDockSlug('dock-3b9a'); //skrasii. remove
// $just_test = \DDMS\Entities\Dock::getById(15); //skrasii. remove
// $job_data_test = [
//     'job_status'    => true,  // bool
//     // 'dock'          => '15',  // dock id: 15 || 16 || 17 
//     'dock'          => 'dock-3b9a',  // dock slug: 3b9a (aka 15, aka dock 34) 
//     'dock_area'     => '68',  // dock_area: "56" related dock_area 
//     'title'         => 'aaa',  // title: "TSY Job title 1" (post_title)
//     'description'   => 'aaa-descZZZ',
//     'company'       => 'Tallinn Shipyardzzzz',  // existing company: "Tallinn Shipyard"
//     'person'        => 'AAAPerson',  // person: "John Doe"
//     'pin'           => '4444',  // pin: "2285" job pin. used to close active work
// ];
// $just_test = \DDMS\Entities\Job::addNew($job_data_test); //skrasii. remove
// $just_test = \DDMS\Entities\Company::getAll(); //skrasii. remove

add_action('init', function () {

    register_post_type('dock',
        [
            'labels'      => [
                'name'          => __( 'Docks', 'ddms' ),
                'singular_name' => __( 'Dock', 'ddms' ),
                'add_new_item'  => __( 'Add New Dock', 'ddms' ),
            ],
            'public'      => true,
            'has_archive' => true,
            'rewrite'     => [ 'slug' => 'docks' ],
            'supports'    => [ 'title' ],
            // 'supports' => [ 'title', 'editor', 'author' ],
            'menu_icon'   => 'dashicons-align-none'
        ]
    );

    register_post_type('area',
        [
            'labels'      => [
                'name'          => __( 'Areas', 'ddms' ),
                'singular_name' => __( 'Area', 'ddms' ),
                'add_new'       => __( 'Add Area', 'ddms' ),
                'add_new_item'  => __( 'Add New Area', 'ddms' ),
            ],
            'public'      => true,
            'has_archive' => true,
            'rewrite'     => [ 'slug' => 'area' ],
            'supports'    => [ 'title' ],
            'menu_icon'   => 'dashicons-table-col-after'
        ]
    );

    register_post_type('job',
        [
            'labels'      => [
                'name'          => __( 'Jobs', 'ddms' ),
                'singular_name' => __( 'Job', 'ddms' ),
                'add_new'       => __( 'Add New Job', 'ddms' ),
                'add_new_item'  => __( 'Add New Job', 'ddms' ),
            ],
            'public'      => true,
            'has_archive' => true,
            'rewrite'     => [ 'slug' => 'job' ],
            'supports'    => [ 'title' ],
            'menu_icon'   => 'dashicons-clipboard'
        ]
    );

    register_post_type('company',
        [
            'labels'      => [
                'name'          => __( 'Companies', 'ddms' ),
                'singular_name' => __( 'Company', 'ddms' ),
                'add_new_item'  => __( 'Add New Company', 'ddms' ),
            ],
            'public'      => true,
            'has_archive' => true,
            'rewrite'     => [ 'slug' => 'company' ],
            'supports'    => [ 'title' ],
            'menu_icon'   => 'dashicons-businessperson'
        ]
    );

});


//
// WP Login page customization

add_action( 'login_enqueue_scripts', function () { ?>
    <style type="text/css">
      #login h1 a, .login h1 a {
         background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 123 123' version='1.1' xmlns='http://www.w3.org/2000/svg'%3E%3Cg id='%23d31146ff'%3E%3Cpath fill='%23d31146' opacity='1.00' d=' M 31.46 0.00 L 31.74 0.00 C 31.83 2.23 32.07 4.45 32.53 6.63 C 35.91 29.73 29.17 52.90 19.87 73.88 C 14.57 85.92 8.98 97.87 4.39 110.20 C 3.13 112.72 5.45 115.40 7.92 115.78 C 14.20 117.04 20.66 116.94 27.03 117.00 C 59.06 116.47 91.44 111.27 120.88 98.24 C 121.28 102.55 121.67 106.87 122.15 111.17 C 104.46 117.46 85.76 120.54 67.08 121.87 C 47.71 123.06 28.11 122.52 9.00 118.89 C 5.09 118.23 -0.11 115.82 0.59 111.04 C 1.67 106.39 3.80 102.08 5.34 97.58 C 11.96 79.11 20.34 60.84 21.95 41.05 C 22.60 32.08 22.16 22.27 16.78 14.67 C 21.04 9.18 26.51 4.81 31.46 0.00 Z'/%3E%3C/g%3E%3Cg id='%23ced1d4ff'%3E%3Cpath fill='%23ced1d4' opacity='1.00' d=' M 84.63 17.86 C 88.13 21.83 91.09 26.27 94.00 30.68 C 77.31 60.06 52.56 84.60 23.46 101.68 C 19.89 103.80 16.28 105.89 12.37 107.32 C 13.37 107.46 14.38 107.62 15.38 107.74 L 15.07 106.69 C 29.29 100.98 42.49 92.93 54.72 83.73 C 71.59 70.87 86.60 55.45 98.61 37.94 C 101.43 41.85 103.72 46.14 105.82 50.47 C 84.13 75.09 56.44 94.31 25.80 106.03 C 21.60 107.75 17.19 108.91 13.03 110.72 C 49.29 102.55 82.63 83.33 109.25 57.57 C 111.28 61.61 112.73 65.89 114.31 70.12 C 89.44 90.15 59.74 104.10 28.51 110.70 C 23.37 111.87 18.11 112.42 12.96 113.55 C 50.08 111.90 85.81 98.11 116.57 77.72 C 117.65 82.03 118.69 86.35 119.39 90.74 C 87.35 108.25 50.52 116.63 14.08 115.51 C 11.11 115.25 7.46 115.52 5.37 112.96 C 4.68 109.44 8.69 107.73 10.98 105.95 C 43.39 84.61 70.19 54.08 84.63 17.86 Z'/%3E%3C/g%3E%3C/svg%3E");
         height:80px;
         width:320px;
         background-size: 80px 80px;
         background-repeat: no-repeat;
         padding-bottom: 30px;
      }

      .login #backtoblog, .login #nav {
         text-align: center;
      }

      .wp-core-ui .button-primary {
         background: #0B2265 !important;
         border-color: #0B2265 !important;
      }

    </style>
<?php });

add_filter( 'login_headerurl', function() {
    return home_url();
});

add_filter( 'login_headertext', function() {
    return 'BLRT Tallinn Shipyard';
});


// if( is_admin() ) {
//     $screen = get_current_screen();
//     if( $screen->base == 'dashboard' ) {
//         // wp_redirect( admin_url( 'index.php?page=custom-dashboard' ) );
//     }
// }

//
// Removing all useless widgets
function remove_dashboard_widgets(){
    global $wp_meta_boxes;
     
    foreach( $wp_meta_boxes["dashboard"] as $position => $core ){
        foreach( $core["core"] as $widget_id => $widget_info ){
             
            remove_meta_box( $widget_id, 'dashboard', $position );
        }
    } 
}
add_action( 'wp_dashboard_setup', 'remove_dashboard_widgets' );

/**
 * WordPress function for redirecting users on login based on user role
 */
function aquaris_login_redirect( $url, $request, $user ) {
    if ( $user && is_object( $user ) && is_a( $user, 'WP_User' ) ) {
        if ( $user->has_cap( 'manage_options' ) ) {
            // $url = admin_url();
            $url = home_url( '/wp-admin/admin.php?page=ddms-admin' );
        }
    }
    return $url;
}
 
add_filter( 'login_redirect', 'aquaris_login_redirect', 10, 3 );


//
// Adding DDMS widget
function add_ddms_dashboard_widgets() {
    global $wp_meta_boxes;
    
    wp_add_dashboard_widget('custom_ddms_widget', 'DDMS Overview', function(){
        echo '<p>DDMS Dashboard Widget</p>';
    }, null, null, 'normal', 'high' );
}
add_action('wp_dashboard_setup', 'add_ddms_dashboard_widgets');

