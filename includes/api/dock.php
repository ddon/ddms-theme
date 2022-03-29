<?php declare ( strict_types = 1 );

namespace DDMS\API;

class Dock
{
 
    public static function getBySlug() {
        $slug = (string) \Fotki\Fields::get_text_by_name('slug');

        if ( empty( $slug ) ) {
            \Fotki\Response::json( [
                'ok' => false,
                'error' => "slug is required",
            ] );
        }

        try {
            $dock_data = \DDMS\Entities\Dock::getBySlug( $slug );
        } catch ( \Exception $e ) {
            \Fotki\Response::json( [
                'ok' => false,
                'error' => $e->getMessage()
            ] );
        }

        \Fotki\Response::json( [
            'ok' => true,
            'data' => $dock_data
        ] );
    }

}