<?php

declare(strict_types=1);

use Abeliani\Blog\Infrastructure\UI\Web\CPanel\Controller\CategoryCreateController;
use Abeliani\Blog\Infrastructure\UI\Web\CPanel\Controller\CategoryIndexController;
use Abeliani\Blog\Infrastructure\UI\Web\CPanel\ItIsBackendController;
use Abeliani\Blog\Infrastructure\UI\Web\CPanel\LoginBackendController;
use Abeliani\Blog\Infrastructure\UI\Web\CPanel\LogoutBackendController;
use Abeliani\Blog\Infrastructure\UI\Web\CPanel\PostEditController;
use Abeliani\Blog\Infrastructure\Delivery\Web\Controller\ItWorksController;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

return simpleDispatcher(function (RouteCollector $r) {
    $r->get('/', ItWorksController::class);

    $r->addGroup('/cpanel', function (RouteCollector $r) {
        $r->post('/login', LoginBackendController::class);
        $r->get('/login', LoginBackendController::class);
        $r->get('/logout', LogoutBackendController::class);

        $r->get('/post/create', PostEditController::class);
        $r->get('/category/create', CategoryCreateController::class);
        $r->post('/category/create', CategoryCreateController::class);
        $r->get('/category', CategoryIndexController::class);
        $r->get('', ItIsBackendController::class);
    });
});
