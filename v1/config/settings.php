<?php

// Error reporting for production
//error_reporting(0);
//ini_set('display_errors', '0');

ini_set('curl.cainfo', dirname(__DIR__).'/config/cacert.pem');

//var_dump(dirname(__DIR__).'/config/cacert.pem');

// Timezone
date_default_timezone_set('Europe/Berlin');

// Settings
$settings = [];

// Path settings
$settings['root'] = dirname(__DIR__);
$settings['temp'] = $settings['root'] . '/tmp';
$settings['public'] = $settings['root'] . '/public';

// Error Handling Middleware settings
$settings['error'] = [

    // Should be set to false in production
    'display_error_details' => true,

    // Parameter is passed to the default ErrorHandler
    // View in rendered output by enabling the "displayErrorDetails" setting.
    // For the console and unit tests we also disable it
    'log_errors' => true,

    // Display error details in error log
    'log_error_details' => true,
];

$settings['logger'] = [ 
                'name' => 'app',
                'path' => $settings['temp'].'/app.log',
                'level' => Monolog\Logger::DEBUG,
            ];

return $settings;
