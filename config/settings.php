<?php

// as chaves iguais serão sobrescritas pelo array em settings.local.php

return [
    'displayErrorDetails' => getenv('DEV_MODE'), // slim
    'addContentLengthHeader' => false, // slim
    'logger' => [ // slim log
        'name' => 'AppLog',
        'path' => __DIR__ . '/../logs/app.log',
        'level' => \Monolog\Logger::DEBUG,
    ],
    'db' => [
        'host' => getenv('DB_HOST'),
        'db_name' => getenv('DB_NAME'),
        'db_user' => getenv('DB_USER'),
        'db_pass' => getenv('DB_PASS'),
        'db_port' => getenv('DB_PORT'),
        'charset' => 'utf8mb4'
    ],
    'jwt' => [
        'app_secret' => getenv('APP_SECRET'),
        'token_expires' => 18000 // 5h
    ],
    'pheanstalk' => [
        'host' => getenv('BK_HOST'),
        'port' => getenv('BK_PORT')
    ],
    'mailgun' => [
        'api_key' => 'b69e2de4814e04738ff2562594b1f39e-c1fe131e-b606b9b4',
        'domain'  => 'sandboxb2908068fd6645cebb8adcebdd107296.mailgun.org',
        'tube_name' => 'phpstart_email',
        'message' => [
            'subject_prefix' => 'Phpstart ',
            'default_from' => 'suporte@incluirtecnologia.com.br',
            'default_from_name' => 'Phpstart',
            'default_bcc' => false,
        ]
    ]
];
