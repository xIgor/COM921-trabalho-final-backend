<?php

// Base
use Pheanstalk\Pheanstalk;
use Mailgun\Mailgun;

// Entity
use IntecPhp\Entity\ConsumerEntity;

// Model
use IntecPhp\Model\Consumer;

// Middleware
use IntecPhp\Middleware\Auth;

// Action
use IntecPhp\Action\ListComplaintRate;

// Service
use IntecPhp\Service\DbHandler;
use IntecPhp\Service\JwtWrapper;
use IntecPhp\Service\Auth as AuthService;

$c = $app->getContainer();

// ----------------------------------------- Base

$c[PDO::class] = function ($c) {
    $db = $c['settings']['db'];
    return new PDO(
        'mysql:host='.$db['host'].';dbname='.$db['db_name'].';charset=' . $db['charset'],
        $db['db_user'],
        $db['db_pass'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_PERSISTENT => false,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
};

$c[Pheanstalk::class] = function ($c) {
    $settings = $c['settings']['pheanstalk'];
    return new Pheanstalk($settings['host'], $settings['port']);
};

$c['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

$c[Mailgun::class] = function ($c) {
    $apiKey = $c['settings']['mailgun']['api_key'];
    return new Mailgun($apiKey);
};

// ----------------------------------------- Entity

$c[ConsumerEntity::class] = function ($c) {
    $db = $c[DbHandler::class];
    return new ConsumerEntity($db);
};

// ----------------------------------------- Model

$c[Consumer::class] = function ($c) {
    $conEnt = $c[ConsumerEntity::class];
    return new Consumer($conEnt);
};

// ----------------------------------------- Service

$c[DbHandler::class] = function ($c) {
    $pdo = $c[PDO::class];
    $logger = $c->get('logger');
    return new DbHandler($pdo, $logger);
};

$c[JwtWrapper::class] = function ($c) {
    $jwtSettings = $c['settings']['jwt'];
    return new JwtWrapper($jwtSettings['app_secret'], $jwtSettings['token_expires']);
};

$c[AuthService::class] = function ($c) {
    $user = $c[User::class];
    return new AuthService($user);
};

// ----------------------------------------- Worker

// ----------------------------------------- Action

$c[ListComplaintRate::class] = function ($c) {
    $consumer = $c[Consumer::class];
    return new ListComplaintRate($consumer);
};

// ----------------------------------------- Middleware

$c[Auth::class] = function ($c) {
    $jwt = $c[JwtWrapper::class];
    return new Auth($jwt);
};

// ----------------------------------------- Handlers
$c['notFoundHandler'] = function ($c) {
    return new \IntecPhp\Handler\NotFound($c['logger']);
};

$c['errorHandler'] = $c['phpErrorHandler'] = function ($c) {
    $errorDetails = $c->get('settings')['displayErrorDetails'];
    $logger = $c->get('logger');
    return new \IntecPhp\Handler\PhpError($errorDetails, $logger);
};
