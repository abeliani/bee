<?php

declare(strict_types=1);

use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;
use Abeliani\Blog\Infrastructure\Delivery\API\ItWorksController;

return simpleDispatcher(function (RouteCollector $r) {
    $r->get('/', ItWorksController::class);
});