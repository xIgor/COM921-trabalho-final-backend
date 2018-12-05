<?php

require __DIR__ . '/../vendor/autoload.php';

use Slim\App;
use Phinx\Wrapper\TextWrapper;
use Phinx\Console\PhinxApplication;
use IntecPhp\Service\DbHandler;

$app = new App([
    'settings' => require __DIR__ . '/../config/settings.php'
]);

require __DIR__ . '/../config/dependencies.php';
require __DIR__ . '/../config/routes.php';

$c = $app->getContainer();

$dbname = $c->get('settings')['db']['db_name'];
$db = $c->get(DbHandler::class);
$db->query("drop database $dbname; create database $dbname; use $dbname");
$phinx = new TextWrapper(new PhinxApplication(), [
    'configuration' => __DIR__ . '/../phinx.php',
    'parser' => 'php'
]);
$output = $phinx->getMigrate();
