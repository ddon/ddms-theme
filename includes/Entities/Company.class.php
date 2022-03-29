<?php declare ( strict_types = 1 );

namespace DDMS\Entities;

class Company extends BaseEntity
{
   public static $company_post_type = 'company';

   
    /**
     *
     * @param int $company_id
     * @return object
     * @throws Exception if company_id is empty
     */
    public static function getById ( int $company_id ) {

        if ( empty( $company_id ) ) {
            throw new \Exception( "company_id is required" );
        }

        global $wpdb;

        $company_db = $wpdb->get_row(
            $wpdb->prepare(
                "
                    SELECT post_title, post_type
                    FROM {$wpdb->prefix}posts
                    WHERE post_type = '%s'
                    AND ID = %d
                ",
                self::$company_post_type, $company_id
            )
        );
        // error_log ( var_export ( $company, true ) );

        if ( empty ($company_db) ) {
            throw new \Exception( "Couldn't find the company." );
        }

        $company_meta = \Fotki\ACF::getPostMeta($company_id);

        $company_data = [
            "id"            => $company_id,
            'name'          => $company_db->post_title,
            'description'   => $company_meta->description,
        ];

      //   error_log ( var_export ( $company_data, true ) );


        return (object) $company_data;
    }
   
    /**
     *
     * @return object list of companies
     */
    public static function getAll () {

        global $wpdb;

        $res = $wpdb->get_results(
            $wpdb->prepare(
                "
                    SELECT ID 
                    FROM {$wpdb->prefix}posts
                    WHERE post_type = '%s'
                    AND post_status = 'publish'
                ",
                self::$company_post_type, $company_id
            )
        );

        if ( empty ($res) ) {
            throw new \Exception( "Couldn't find any company." );
        }

        $companies = [];
        foreach ($res as $c) {
            // error_log ( var_export ( $c->ID, true ) );
            $companies[] = self::getById( (int) $c->ID );
        }

        return $companies;
    }
   
    /**
     *
     * @param string $company_name
     * @return object
     * @throws Exception if company_name is empty
     */
    public static function getByName ( string $company_name ) {

        if ( empty( $company_name ) ) {
            throw new \Exception( "getByName(): company_name is required" );
        }

        $post = get_page_by_title($company_name, OBJECT, self::$company_post_type);

        if ( empty ($post) ) {
            throw new \Exception( "Couldn't find the company by name." );
        }

        $company_data = self::getById($post->ID);


        return (object) $company_data;
    }
}
