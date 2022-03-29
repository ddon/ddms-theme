<?php declare ( strict_types = 1 );

namespace DDMS\Entities;

use Exception;

class Job extends BaseEntity
{

    public static $job_post_type = 'job';

   
    /**
     *
     * @param int $job_id
     * @return object
     * @throws Exception if job_id is empty
     */
    public static function getById ( int $job_id ) {

        if ( empty( $job_id ) ) {
            throw new \Exception( "job_id is required" );
        }

        global $wpdb;

        $job_db = $wpdb->get_row(
            $wpdb->prepare(
                "
                    SELECT post_title, post_date, post_type
                    FROM {$wpdb->prefix}posts
                    WHERE post_type = '%s'
                    AND ID = %d
                    
                    
                ",
                self::$job_post_type, $job_id
            )
        );
        // error_log ( var_export ( $job, true ) );

        if ( empty ($job_db) ) {
            throw new \Exception( "Couldn't find the job." );
        }

        $job_meta = \Fotki\ACF::getPostMeta($job_id);

        try {
            $job_company_name = \DDMS\Entities\Company::getById( (int) $job_meta->company )->name;
        } catch (\Throwable $th) {
            $job_company_name = $job_meta->new_company;
        }
        

        try {
            $dock_area_data = \DDMS\Entities\Area::getById( (int) $job_meta->dock_area );
        } catch (\Throwable $th) {
            $dock_area_data = null;
        }
        



        $job_data = [
            "id"            => $job_id,
            'status'        => ($job_meta->job_status) ? "Active" : "Closed",
            'title'         => $job_db->post_title,
            'description'   => $job_meta->description,
            // 'pin'           => $job_meta->job_pin,
            'person'        => $job_meta->person,
            'company'       => $job_company_name,
            'dock'          => $job_meta->dock,
            'dock_area'     => $job_meta->dock_area,
            'area_data'     => $dock_area_data,
            "date_created"  => $job_db->post_date,
        ];

        // error_log ( var_export ( $job_data, true ) );

        return (object) $job_data;
    }
   
    /**
     * Gets all active jobs by dock_id
     * @param int $dock_id
     * @return object
     * @throws Exception if dock_id is empty
     */
    public static function getAllActiveByDockId ( int $dock_id ) {

        if ( empty( $dock_id ) ) {
            throw new \Exception( "dock_id is required" );
        }

        global $wpdb;

        $active_jobs_db = $wpdb->get_col(
            $wpdb->prepare(
                "
                SELECT pm1.post_id

                FROM {$wpdb->prefix}postmeta as pm1,
                {$wpdb->prefix}postmeta as pm2
                
                WHERE 
                    pm1.post_id = pm2.post_id 

                    AND pm1.meta_key = 'job_status'
                    AND pm1.meta_value = 1

                    AND pm2.meta_key = 'dock'
                    AND pm2.meta_value = %d
                ", $dock_id
            )
        );

        $active_jobs = [];

        foreach ( $active_jobs_db as $j_id ) {
            $active_jobs[] = self::getById( (int) $j_id );
        }

        // error_log ( var_export ( $active_jobs, true ) );

        return $active_jobs;
    }
   
   
    /**
     * Gets all active jobs by dock_slug
     * @param string $dock_slug
     * @return object
     * @throws Exception if dock_slug is empty
     */
    public static function getAllActiveByDockSlug ( string $dock_slug ) {

        $dock_slug = htmlspecialchars( $dock_slug );

        if ( empty( $dock_slug ) ) {
            throw new \Exception( "dock_slug is required" );
        }
        
        try {
            $dock_id = \DDMS\Entities\Dock::getBySlug( $dock_slug )->id;
        } catch (\Throwable $th) {
            $dock_id = '';
            throw $th;
        }
        
        global $wpdb;

        $active_jobs_db = $wpdb->get_col(
            $wpdb->prepare(
                "
                SELECT pm1.post_id

                FROM {$wpdb->postmeta} as pm1,
                {$wpdb->postmeta} as pm2
                
                WHERE 
                    pm1.post_id = pm2.post_id 

                    AND pm1.meta_key = 'job_status'
                    AND pm1.meta_value = 1

                    AND pm2.meta_key = 'dock'
                    AND pm2.meta_value = %d
                ", $dock_id
            )
        );

        $active_jobs = [];

        foreach ( $active_jobs_db as $j_id ) {
            $active_jobs[] = self::getById( (int) $j_id );
        }

        // error_log ( var_export ( $active_jobs, true ) );

        return $active_jobs;
    }
   

    /**
     * Gets all jobs (active and closed) by dock_id
     * @param int $dock_id
     * @return object
     * @throws Exception if dock_id is empty
     */
    public static function getAllByDockId ( int $dock_id ) {

        if ( empty( $dock_id ) ) {
            throw new \Exception( "dock_id is required" );
        }

        global $wpdb;

        $jobs_db = $wpdb->get_col(
            $wpdb->prepare(
                "
                SELECT post_id
                FROM {$wpdb->prefix}postmeta
                WHERE meta_key = 'dock' AND meta_value = %d
                ", $dock_id
            )
        );

        $jobs = [];

        foreach ( $jobs_db as $j_id ) {
            $jobs[] = self::getById( (int) $j_id );
        }

        // error_log ( var_export ( $jobs, true ) );

        return $jobs;
    }

    /**
     * Adds a new job
     * @param array $data
     * @return bool
     * @throws Exception if couldn't create a new job post
     */
    public static function addNew ( $data = [] ) {

        if ( empty( $data ) ) {
            throw new \Exception( "data is empty" );
        }

        //dock_id => user_id
        $dockUser = [
            '15' => 3,
            '16' => 4,
            '17' => 5
        ];

        $job_data = [
            'job_status'    => '',  // bool
            'dock'          => '',  // dock id: 15 || 16 || 17 
            'dock_area'     => '',  // dock_area id: 56  
            'title'         => '',  // title: "TSY Job title 1" (post_title)
            'description'   => '',
            'company'       => '',  // id
            'person'        => '',  // person: "John Doe"
            'pin'           => '',  // pin: "2285" job pin. used for closing active job
        ];

        //temporary check for test purposes
        $is_duplicate = get_page_by_title($data['title'], OBJECT, 'job');
        if ($is_duplicate) {
            // error_log( "\n\n" );
            // error_log( "### DUPLICATE FOUND!!! ###\n\n" );
            // error_log( "\n\n" );
            return false;
        }

        $company_name = '';
        $dock = '';

        foreach ($job_data as $k => $v) {

            if ( ! key_exists($k, $data) || empty( $data[$k] ) ) {

                if($k === 'description') {
                    continue; //skipping non mandatory field                   
                } else {
                    throw new \Exception( "field '$k' is required" );
                }

            }

            switch ($k) {
                case 'company':
                    try {
                        $company_name = \DDMS\Entities\Company::getById( (int) $data['company'] )->id;
                    } catch (\Exception $ex) {
                        error_log("Job::addNew: " . $ex->getMessage());
                    }
                    $job_data[$k] = $company_name;
                    break;

                case 'dock':
                    try {
                        $dock = \DDMS\Entities\Dock::getById( (int) $data['dock'] )->id;
                    } catch (\Throwable $th) {
                        error_log("Job::addNew: " . $th->getMessage());
                    }
                    $job_data[$k] = $dock;
                    break;

                default:
                    $job_data[$k] = $data[$k];
                    break;
            }

        }
        
        $post_id = wp_insert_post([
            'post_type'   => 'job',
            'post_status' => 'publish',
            'post_author' => $dockUser[ $job_data['dock'] ],
            'post_title'  => $job_data['title'],
            'meta_input'  => [
                'job_status'  => $job_data['job_status'],
                'dock'        => (int) $dock,
                'dock_area'   => (int) $job_data['dock_area'],
                'description' => $job_data['description'],
                'company'     => $company_name,
                'person'      => $job_data['person'],
                'job_pin'     => $job_data['pin'],
            ]
        ]);

        // error_log( "!!!!!!!!!!!!!!!!!!!\n\n" );
        // error_log( var_export( $post_id, true ) );
        // error_log( "\n\n!!!!!!!!!!!!!!!!!!!" );

        return $post_id;

    }

    /**
     * Get job's PIN number by job ID
     * @return int
     * @throws Exception if couldn't get a pin
     */
    public static function getPinById ( int $job_id ) {

         if ( empty( $job_id ) ) {
            throw new \Exception( "job_id is empty" );
        }

        try {
            $job_found = \DDMS\Entities\Job::getById( (int) $job_id );
        } catch (\Throwable $th) {
            error_log( var_export( $job_found, true ) );
            error_log( $th->getMessage() );
        }
        $job_meta = \Fotki\ACF::getPostMeta($job_id);

        $job_pin = null;
        if (! empty($job_meta) ){
            $job_pin = $job_meta->job_pin;
        }

        return $job_pin;

    }

    /**
     * Close exisitng active job
     * @param array $data
     * @return bool
     * @throws Exception if couldn't close a job
     */
    public static function closeByPin ( int $job_id, int $pin_submitted ) {

        if ( empty( $pin_submitted ) ) {
            throw new \Exception( "pin is empty" );
        }

        if ( empty( $job_id ) ) {
            throw new \Exception( "job_id is empty" );
        }

        try {
            $job_found = self::getById( (int) $job_id );
        } catch (\Throwable $th) {
            throw new \Exception( "Couldn't find job with id: $job_id" );
        }

        $job_pin = self::getPinById( (int) $job_id );

        $updated = false;

        if ( (int) $job_pin === (int) $pin_submitted) {
            $updated = update_post_meta($job_id, 'job_status', 0);
            
            if ($updated !== true ) {
                throw new \Exception( "Couldn't close the job $job_id" );
            }

        } else {
            // throw new \Exception( "Couldn't close job $job_id. Wrong pin." );
            throw new \Exception( "Couldn't close job. Wrong pin." );
        }

        return $updated;

    }

}
