<?php

use IntecPhp\Worker\Mailgun as MailgunWorker;
use Mailgun\Mailgun;
use Pheanstalk\Pheanstalk;

$c = $app->getContainer();

// Worker
$c['worker_mailgun'] = function ($c) {
    $mconf = $c['settings']['mailgun'];
    $tube = $c[Pheanstalk::class]->watch($mconf['tube_name']);
    $apiKey = $mconf['api_key'];
    $domain = $mconf['domain'];
    $messageConfig = $mconf['message'];
    $msgClient = $c[Mailgun::class];
    return new MailgunWorker($tube, $domain, $msgClient, $messageConfig);
};
