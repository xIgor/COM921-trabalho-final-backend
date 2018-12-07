<?php

use IntecPhp\Middleware\Auth;
use IntecPhp\Middleware\AllowOrigin;

// ACTION
use IntecPhp\Action\ListComplaintRateByRegion;
use IntecPhp\Action\ListRegionStates;
use IntecPhp\Action\ListComplaintRateByState;
use IntecPhp\Action\ListCompaniesWithMoreComplaints;
use IntecPhp\Action\ListCompanySemesterEvaluation;

$app->group('/region', function () {
    $this->get('/complaint-rate', ListComplaintRateByRegion::class);
    $this->post('/list-states', ListRegionStates::class);
});

$app->group('/state', function () {
    $this->post('/complaint-rate', ListComplaintRateByState::class);
    $this->post('/complaint-company', ListCompaniesWithMoreComplaints::class);
});

$app->group('/company', function () {
    $this->post('/semester-evaluation', ListCompanySemesterEvaluation::class);
});

// enable CORS
$app->options('/{routes:.+}', function ($request, $response) {
    return $response;
});

$app->add(AllowOrigin::class);

// Catch-all route to serve a 404 Not Found page if none of the routes match
// NOTE: make sure this route is defined last
$app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function ($req, $res) {
    $handler = $this->notFoundHandler; // handle using the default Slim page not found handler
    return $handler($req, $res);
});
