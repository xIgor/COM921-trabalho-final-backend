<?php

$settings = require './config/settings.php';
if (file_exists('config/settings.local.php')) {
    $settings = array_replace_recursive($settings, require './config/settings.local.php');
}

$db = $settings['db'];
return [
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/data/database/migrations',
        'seeds' => '%%PHINX_CONFIG_DIR%%/data/database/seeds'
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_database' => 'development',
        'development' => [
            'adapter' => 'mysql',
            'host' => $db['host'],
            'name' => $db['db_name'],
            'user' => $db['db_user'],
            'pass' => $db['db_pass'],
            'port' => $db['db_port'],
            'charset' => $db['charset'],
        ],
    ],
    'version_order' => 'creation'
];
