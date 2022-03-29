<?php declare ( strict_types = 1 );

namespace DDMS\API;

class Area
{
 
    public static function getById() {
        $area_id = (string) \Fotki\Fields::get_text_by_name('areaId');

        if ( empty( $area_id ) ) {
            \Fotki\Response::json( [
                'ok' => false,
                'error' => "areaId is required",
            ] );
        }

        try {
            $area_data = \DDMS\Entities\Area::getById( $area_id );
        } catch ( \Exception $e ) {
            \Fotki\Response::json( [
                'ok' => false,
                'error' => $e->getMessage()
            ] );
        }

        \Fotki\Response::json( [
            'ok' => true,
            'data' => $area_data
        ] );
    }

}