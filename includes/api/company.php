<?php declare ( strict_types = 1 );

namespace DDMS\API;

class Company
{
 
    public static function getAll() {

        try {
            $companies = \DDMS\Entities\Company::getAll();
        } catch ( \Exception $e ) {
            \Fotki\Response::json( [
                'ok' => false,
                'error' => $e->getMessage()
            ] );
        }

        \Fotki\Response::json( [
            'ok' => true,
            'data' => $companies
        ] );
    }
 
}