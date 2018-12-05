<?php

//Everything is relative to the application root now.
chdir(dirname(__DIR__));

require './vendor/autoload.php';
$settings = require 'config/settings.php';

if(file_exists('config/settings.local.php')) {
    $settings = array_replace_recursive($settings, require './config/settings.local.php');
}

$app = new \Slim\App([
    'settings' => $settings
]);

require './config/dependencies.php';
require './config/routes.php';

$app->run();
