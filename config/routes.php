<?php

declare(strict_types=1);

use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;
use Abeliani\Blog\Infrastructure\Delivery\API\ItWorksController;
use Abeliani\Blog\Infrastructure\Delivery\Backend\ItIsBackendController;
use Abeliani\Blog\Infrastructure\Delivery\Backend\PostEditController;

return simpleDispatcher(function (RouteCollector $r) {
    $r->get('/', ItWorksController::class);
    $r->get('/back/post/create', PostEditController::class);
    $r->get('/back', ItIsBackendController::class);
});