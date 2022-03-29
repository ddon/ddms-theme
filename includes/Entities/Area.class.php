<?php declare ( strict_types = 1 );

namespace DDMS\Entities;

use Exception;

class Area extends BaseEntity
{

    public static $area_post_type = 'area';

   
    /**
     *
     * @param int $area_id
     * @return object
     * @throws Exception if area_id is empty
     */
    public static function getById ( $area_id ) {
        $area_id = (int) $area_id;

        if ( empty( $area_id ) ) {
            throw new \Exception( "area_id is required" );
        }

        global $wpdb;

        $name_en = $wpdb->get_var(
            $wpdb->prepare(
                "
                    SELECT post_title
                    FROM {$wpdb->prefix}posts
                    WHERE post_type = '%s'
                    AND ID = '%d'
                    AND post_status = 'publish'
                ",
                self::$area_post_type, $area_id
            )
        );

        $area_meta = \Fotki\ACF::getPostMeta($area_id);

        $area = [
            "id" => $area_id,
            'title' => $name_en,
            'name_en' => $name_en,
            'name_ru' => $area_meta->title_ru,
            'dock' => unserialize($area_meta->related_dock)[0]
        ];

        // error_log ( var_export ( $area, true ) );

        return (object) $area;
    }

    
    /**
     * Gets list of all dock areas by dock_id
     * 
     * @param int $dock_id
     * @return object
     * @throws Exception if dock_id is empty
     */
    public static function getAllByDockId (int $dock_id) {

        if ( empty( $dock_id ) ) {
            throw new \Exception( "dock_id is required" );
        }

        global $wpdb;

        $serialized_dock_id = serialize(["$dock_id"]);

        $areas_db = $wpdb->get_results(
            $wpdb->prepare(
                "
                SELECT post_id
                FROM {$wpdb->prefix}postmeta
                WHERE meta_key = 'related_dock'
                AND meta_value = '%s'

                ", $serialized_dock_id
            )
        );

        $areas = [];

        foreach ( $areas_db as $a ) {
            $areas[] = self::getById( (int) $a->post_id );
        }
        // error_log ( var_export ( $areas, true ) );
        
        return $areas;

    }

}
