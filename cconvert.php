<?php
/**
 * @package Crypto Currency Converter
 * @version 1.0.0
 */
/*
Plugin Name: Crypto Currency Converter
Plugin URI: https://github.com/Vlasrv/cmcwp.git
Description: This is an example plugin to convert from one cryptocurrency to another.
Author: Roman Vlasenko
Version: 1.0.0
*/ 

include_once __DIR__.'/plugin_installer.php';
include_once __DIR__.'/exceptions.php';
include_once __DIR__.'/controllers/controller.php';

register_activation_hook(__FILE__, 'cmc_convert_activation');
register_deactivation_hook (__FILE__, 'cmc_convert_deactivate');

try {
    new CmcController(is_admin());
} catch (CmcException404 $e) {
    status_header(404);
    include(get_404_template());
    exit;
} catch (CmcException $e) {
    throw new Exception($e->getMessage());
}
