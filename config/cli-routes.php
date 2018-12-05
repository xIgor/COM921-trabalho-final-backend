<?php

$app->any('/worker/{worker}', function($req, $resp, $args) {
    $worker = $this->get(sprintf('worker_%s', $args['worker']));
    $logger = $this->get('logger');
    do {
        try {
            $worker->watch();
        } catch(\Throwable $e) {
            $logger->warning($e->getMessage());
        }
    } while(true);
});
