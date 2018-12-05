<?php

use Slim\Http\Environment;
use Slim\Http\Request;
use Slim\Http\Response;

require './vendor/autoload.php';
$settings = require 'config/settings.php';

if(file_exists('config/settings.local.php')) {
    $settings = array_replace_recursive($settings, require './config/settings.local.php');
}

$app = new \Slim\App([
    'settings' => $settings
]);

require './config/dependencies.php';
require './config/cli-dependencies.php';
require './config/cli-routes.php';

$requestUri = count($argv) > 1 ? $argv['1'] : '';
$environment = Environment::mock(['REQUEST_URI' => '/' . $requestUri]);
$request = Request::createFromEnvironment($environment);

return $app->process($request, new Response());
