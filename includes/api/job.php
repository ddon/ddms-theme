<?php declare ( strict_types = 1 );

namespace DDMS\API;

class Job
{
 
    public static function getAllActiveByDockSlug() {
        $dock_slug = (string) \Fotki\Fields::get_text_by_name('slug');

        if ( empty( $dock_slug ) ) {
            \Fotki\Response::json( [
                'ok' => false,
                'error' => "dock_slug is required",
            ] );
        }

        try {
            $active_jobs = \DDMS\Entities\Job::getAllActiveByDockSlug( $dock_slug );
        } catch ( \Exception $e ) {
            \Fotki\Response::json( [
                'ok' => false,
                'error' => $e->getMessage()
            ] );
        }

        \Fotki\Response::json( [
            'ok' => true,
            'data' => $active_jobs
        ] );
    }
 
    public static function closeByPin() {
        $job_pin = (int) \Fotki\Fields::post_id_by_name('job_pin');
        $job_id = (int) \Fotki\Fields::post_id_by_name('job_id');

        if ( empty( $job_id ) ) {
            \Fotki\Response::json( [
                'ok' => false,
                'error' => "job_id is required",
            ] );
        }

        if ( empty( $job_pin ) ) {
            \Fotki\Response::json( [
                'ok' => false,
                'error' => "job_pin is required",
            ] );
        }

        try {
            $job_closed = \DDMS\Entities\Job::closeByPin( $job_id, $job_pin );
        } catch ( \Exception $e ) {
            \Fotki\Response::json( [
                'ok' => false,
                'error' => $e->getMessage()
            ] );
        }

        \Fotki\Response::json( [
            'ok' => true,
            'data' => $job_closed
        ] );
    }
 
    public static function addNew() {

        // // Takes raw data from the request
        // $json = file_get_contents('php://input');

        // // Converts it into a PHP object
        // $job_data = json_decode($json);
        // error_log( var_export( $job_data, true ));


        // POST
        $job_data = [
            'job_status'    => true,  // bool
            'dock'          => \Fotki\Fields::post_text_by_name('dock'),  // dock slug 
            'dock_area'     => \Fotki\Fields::post_id_by_name('dock_area'),  // dock_area: "56" related dock_area 
            'title'         => \Fotki\Fields::post_text_by_name('title'),  // title: "TSY Job title 1" (post_title)
            'description'   => \Fotki\Fields::post_text_by_name('description'),
            'company'       => \Fotki\Fields::post_text_by_name('company'),  // existing company: "Tallinn Shipyard"
            'person'        => \Fotki\Fields::post_text_by_name('person'),  // person: "John Doe"
            'pin'           => \Fotki\Fields::post_text_by_name('pin'),  // pin: "2285" job pin. used to close active work
        ];
        
        //GET
        // $job_data = [
        //     'job_status'    => true,  // bool
        //     'dock'          => \Fotki\Fields::get_text_by_name('dock'),  // dock slug 
        //     'dock_area'     => \Fotki\Fields::get_id_by_name('dock_area'),  // dock_area: "56" related dock_area 
        //     'title'         => \Fotki\Fields::get_text_by_name('title'),  // title: "TSY Job title 1" (get_title)
        //     'description'   => \Fotki\Fields::get_text_by_name('description'), //not mandatory
        //     'company'       => \Fotki\Fields::get_text_by_name('company'),  // ID existing company: "Tallinn Shipyard"
        //     'person'        => \Fotki\Fields::get_text_by_name('person'),  // person: "John Doe"
        //     'pin'           => \Fotki\Fields::get_text_by_name('pin'),  // pin: "2285" job pin. used to close active work
        // ];

        // https://ddms.greenoak.ee/wp-admin/admin-ajax.php?action=add_new_job&dock=dock-3b9a&dock_area=56&title=Api Test&description=api-test-desc&company=Tallin Shipyard&person=Someone&pin=4321
        
        // validation. all fields required except description
        foreach ($job_data as $k => $v) {
            if ( empty( $v ) && $k !== 'description') {
                \Fotki\Response::json( [
                    'ok' => false,
                    'field' => $k,
                    'error' => "$k is required",
                ] );
            }
        }

        try {
            $new_job = \DDMS\Entities\Job::addNew( $job_data );
        } catch ( \Exception $e ) {
            \Fotki\Response::json( [
                'ok' => false,
                'error' => $e->getMessage()
            ] );
        }

        \Fotki\Response::json( [
            'ok' => true,
            'data' => $new_job
        ] );
    }

}