<?php

/**
 *  Config File For Handel Route, Database And Request
 *
 *  Author: Ashot Shushanyan
 *  Email: ashotshushanyan14@gmail.com
 *
 */

// Define Path app
define('SCRIPT', str_replace('\\', '/', rtrim(__DIR__, '/')) . '/');
define('SYSTEM', SCRIPT . 'System/');
define('VIEW_ROOT', SCRIPT. 'app/views');
define('CONTROLLERS', SCRIPT . 'app/controllers/');
define('MODELS', SCRIPT . 'app/models/');
define('TEST_HOST_NAME', 'TestUrl');

// Config Database
switch (gethostname())
{
    case TEST_HOST_NAME:
        $host = 'localhost';
        $name = '';
        $user = '';
        $pass = '';
        $port = '';
        $protocol = '';
        break;

    default:
        $host = 'localhost';
        $name = 'mentorcliqTask';
        $user = 'root';
        $pass = '';
        $port = '3308';
        $protocol = 'http://';
        break;
}


define('DATABASE', [
    'Port'   => $port,
    'Host'   => $host,
    'Driver' => 'PDO',
    'Name'   => $name,
    'User'   => $user,
    'Pass'   => $pass,
    'Prefix' => ''
]);
