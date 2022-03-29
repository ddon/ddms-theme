<?php declare ( strict_types = 1 );

namespace DDMS\Entities;

use Exception;

class Dock extends BaseEntity
{

    public static $dock_post_type = 'dock';
    
    /**
     *
     * @param int $dock_id
     * @return object
     * @throws Exception if dock_id is empty
     */
    public static function getById( int $dock_id ) {

        if ( empty( $dock_id ) ) {
            throw new \Exception( "dock_id is required" );
        }

        global $wpdb;

        $dock_db = $wpdb->get_row(
            $wpdb->prepare(
                "
                    SELECT post_title, post_name
                    FROM {$wpdb->prefix}posts
                    WHERE post_type = '%s'
                    AND ID = '%d'
                    AND post_status = 'publish'
                ",
                self::$dock_post_type, $dock_id
            )
        );

        if ( empty( $dock_db ) ) {
            throw new \Exception( "No dock found with ID: $dock_id" );
        }
        
        // error_log ( var_export ( $dock_db, true ) );

        $dock_meta = \Fotki\ACF::getPostMeta($dock_id);

        $dock_data = (object) [
            "id" => $dock_id,
            'name' => $dock_db->post_title,
            'slug' => $dock_db->post_name,
            'dock_plan' => $dock_meta->dock_plan,
            'svg_imagemap' => $dock_meta->svg_imagemap,
            'dock_pin' => $dock_meta->dock_pin,
            'connection' => $dock_meta->connection,
            'last_update' => $dock_meta->last_update,
        ];

        // error_log ( var_export ( $dock_data, true ) );
        // var_dump ( $dock_data );

        return $dock_data;
    }

    /**
     * Returns all available docks
     * @return array
     */
    public static function getAll() {

        global $wpdb;

        $dock_ids = $wpdb->get_col(
            $wpdb->prepare(
                "
                    SELECT ID
                    FROM {$wpdb->prefix}posts
                    WHERE post_type = '%s'
                        AND post_status = 'publish'
                ",
                self::$dock_post_type
            )
        );


        $all_docks = [];

        foreach ( $dock_ids as $d_id ) {
            $all_docks[] = self::getById( (int) $d_id );
        }

        // error_log ( var_export ( $all_docks, true ) );
        // var_dump ( $all_docks );

        return $all_docks;
    }

    /**
     * Returns dock data available docks
     * 
     * @param string $slug  wp post_name
     * @return object
     */
    public static function getBySlug( $slug ) {

        if ( empty( $slug ) ) {
            throw new \Exception( "Dock slug is required" );
        }

        $slug = htmlspecialchars( $slug );

        global $wpdb;

        $dock_id = $wpdb->get_var(
            $wpdb->prepare(
                "
                    SELECT ID
                    FROM {$wpdb->prefix}posts
                    WHERE post_type = '%s'
                        AND post_name = '%s'
                        AND post_status = 'publish'
                ",
                self::$dock_post_type, $slug
            )
        );

        if ( empty( $dock_id ) ) {
            throw new \Exception( "Dock not found" );
        }

        $dock_data = self::getById( (int) $dock_id );;

        return $dock_data;
    }

}
