<?php

add_action( 'admin_menu', function() {
    add_menu_page(
        'DDMS Admin',                   // page_title
        'DDMS',                         // menu_title
        'manage_options',               // capability
        'ddms-admin',                   // menu_slug
        'ddms_admin_page_contents',     // function
        'dashicons-welcome-view-site',  // icon_url 
        3                               //  position 
    );
    add_submenu_page(
        'ddms-admin',                   // parent-slug
        'All Docks',                    // page_title
        'All Docks',                    // menu_title
        'manage_options',               // capability
        'ddms-admin',                   // menu_slug
        'ddms_admin_page_contents',     // function
    );
    add_submenu_page(
        'ddms-admin',                   // parent-slug
        'Dock 34',
        'Dock 34',
        'manage_options',
        'ddms-admin-dock34',             //menu_slug
        'ddms_admin_page_contents',
    );
    add_submenu_page(
        'ddms-admin',                   // parent-slug
        'Dock 2',
        'Dock 2',
        'manage_options',
        'ddms-admin-dock2',             //menu_slug
        'ddms_admin_page_contents',
    );
    add_submenu_page(
        'ddms-admin',                   // parent-slug
        'Dock 3',
        'Dock 3',
        'manage_options',
        'ddms-admin-dock3',             //menu_slug
        'ddms_admin_page_contents',
    );

});

//
// Adding styles
add_action( 'admin_enqueue_scripts', function($hook) {
    // Load only on ?page=toplevel_page_ddms-admin
    if( $hook == 'toplevel_page_ddms-admin' ) {
        wp_enqueue_style( 'ddms_admin_styles', get_template_directory_uri() . '/ddms-admin-style.css');
    }

    wp_enqueue_style( 'ddms_admin_styles', get_template_directory_uri() . '/wp-admin-custom-style.css');
    
});


function ddms_admin_page_contents() {
    global $plugin_page;

    $url = "/wp-content/themes/ddms-theme/ddms-docks-admin.php";
    $dock_pages = [
        'ddms-admin-dock34' => "?dock_id=15",
        'ddms-admin-dock2' => "?dock_id=16",
        'ddms-admin-dock3' => "?dock_id=17"
    ];

    if ( $dock_pages[$plugin_page] ) {
        $url .= $dock_pages[$plugin_page];
    }

    ?>
    <style>
        iframe {
            min-height: 95vh;
            width: 100%;
        }
    </style>

    <iframe width="100%"  src="<?= $url ?>" />

    <?php
}

//
// Adding Custom columns for Jobs (WP Admin)
add_filter('manage_job_posts_columns', function( $columns ) {
    
    return [
        'title'        => 'Title',
        'dock_name'    => 'Dock',
        'dock_area'    => 'Area',
        'job_status'   => 'Status',
        'date'         => 'Date',
    ];

});

add_filter('manage_edit-job_sortable_columns', function( $columns ) {
    
    $columns['job_status'] = 'job_status';
    return $columns;

});

add_action('manage_job_posts_custom_column', function( $column ) {
    global $post_type;
    global $post;

    $post_id = $post->ID;

    $related_dock_id = get_field('dock', $post_id);
    $dock_area_id = get_field('dock_area', $post_id);
    $job_status = get_field('job_status', $post_id);

    // var_dump($column);

    switch ($column) {
        case 'dock_name':
            
            if ( ! empty ( $related_dock_id ) ) {

                try {
                    $dock_data = \DDMS\Entities\Dock::getById((int) $related_dock_id);
                    echo($dock_data->name);

                } catch (\Throwable $th) {
                    // echo($th->getMessage());
                    echo("data error");
                }

            }

            break;
        
        case 'dock_area':
            
            if ( ! empty ( $dock_area_id ) ) {

                try {
                    $area_data = \DDMS\Entities\Area::getById((int) $dock_area_id);
                    echo($area_data->title);

                } catch (\Throwable $th) {
                    // echo($th->getMessage());
                    echo("data error");
                }

            }

            break;
        
        case 'job_status':
            
            if ($job_status) {
                echo "<span class='job_status active'>Active</span>";
            } else {
                echo "<span class='job_status inactive'>Inactive</span>";
            }

            break;
        
        default:
            # code...
            break;
    }

});


// set query to sort
add_action( 'pre_get_posts', function( $query ) {
    $orderby = $query->get( 'orderby' );

    if ( 'job_status' == $orderby ) {

        $query->set( 'meta_key', 'job_status' );
        $query->set( 'orderby', 'meta_value' );
    }
});



