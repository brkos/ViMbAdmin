<?php

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// composer autoloader
require_once( APPLICATION_PATH . '/../vendor/autoload.php' );

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);

// this solves the issue "Fatal error: spl_autoload() ... Class Doctrine_Event could not be loaded in Doctrine_Record ..."
register_shutdown_function( array( 'Zend_Session', 'writeClose' ), true );

$application
    ->bootstrap()
    ->run();
