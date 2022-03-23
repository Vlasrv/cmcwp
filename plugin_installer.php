<?php

// added includes here just for simplify the task
require_once __DIR__.'/exceptions.php';
require_once __DIR__.'/controllers/controller.php';
require_once __DIR__.'/models/currencies.php';
require_once __DIR__.'/models/history.php';
require_once __DIR__.'/models/dataprovider/cmcapi.php';
require_once __DIR__.'/models/dataprovider/currency_model.php';
require_once __DIR__.'/models/dataprovider/history_model.php';
require_once __DIR__.'/view/simpleTemplate.php';

function cmc_convert_activation() {
    global $wpdb;
    require_once ABSPATH.'wp-admin/includes/upgrade.php';
    
    $currencies = 'CREATE TABLE `'.$wpdb->prefix.'cmc_rates` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `ticker` varchar(10) NOT NULL DEFAULT \'\',
        `name` varchar(50) NOT NULL DEFAULT \'\',
        `cmc_rank` int(11) NOT NULL DEFAULT 0,
        `price` float(16,12) NOT NULL DEFAULT 0.000000000000,
        PRIMARY KEY (`id`),
        UNIQUE KEY `ticker` (`ticker`)
        ) ENGINE=InnoDB;';
    
    $history = 'CREATE TABLE `'.$wpdb->prefix.'cmc_history` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `convert_from` varchar(10) NOT NULL DEFAULT \'\',
        `convert_to` varchar(10) NOT NULL DEFAULT \'\',
        `amount` varchar(30) NOT NULL DEFAULT 0,
        `datetime` int NOT NULL DEFAULT unix_timestamp(),
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB;';
    
    dbDelta($currencies);
    dbDelta($history);
    
    add_option('cmc_api_key', '');
}

function cmc_convert_deactivate() {
    $timestamp = wp_next_scheduled('cmc_cron_worker_start');
    wp_unschedule_event($timestamp, 'cmc_cron_worker_start');
}


// Adds a custom interval
function cmc_cron_add_schedule(){
    $schedules['cmc_cron_worker_interval'] = ['interval'=>300, 'display'=>'Every 5 minutes'];
    return $schedules;
}

// Cron handler
function cmc_cron_worker_start() {
    $currencies_obj = new CmcCurrencies();
    $currencies_obj->updateFromCmc();
}

add_filter('cron_schedules', 'cmc_cron_add_schedule');

// Schedules an event
if (!wp_next_scheduled('cmc_cron_worker_start')) {
    wp_schedule_event(time(), 'cmc_cron_worker_interval', 'cmc_cron_worker_start_hook');
}
add_action('cmc_cron_worker_start_hook', 'cmc_cron_worker_start');

// added output buffering because some WP themes 
// does not did by themselves
// this is needed for AJAX output
// I'll not like to use /wp-admin/admin-ajax.php
// because of security, I think regular customer
// must hawe restriction to access to any admin URL
add_action('init', function(){
    ob_start();
});
