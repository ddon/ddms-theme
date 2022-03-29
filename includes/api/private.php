<?php

namespace DDMS\API;

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

// Dock
add_action( 'wp_ajax_get_dock_by_slug', ['DDMS\API\Dock', 'getBySlug'] );

// Job
add_action( 'wp_ajax_get_active_jobs_by_dock_slug', ['DDMS\API\Job', 'getAllActiveByDockSlug'] );
add_action( 'wp_ajax_add_new_job', ['DDMS\API\Job', 'addNew'] );
add_action( 'wp_ajax_close_job_by_pin', ['DDMS\API\Job', 'closeByPin'] );

// Area
add_action( 'wp_ajax_get_area_by_id', ['DDMS\API\Area', 'getById'] );

// Company
add_action( 'wp_ajax_get_all_companies', ['DDMS\API\Company', 'getAll'] );