<?php

declare(strict_types=1);

use Abeliani\Blog\Infrastructure\UI\Web\CPanel\Controller;
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
        $r->get('/category/create', Controller\CategoryCreateController::class);
        $r->post('/category/create', Controller\CategoryCreateController::class);
        $r->get('/category', Controller\CategoryIndexController::class);
        $r->get('/category/update/{id:\d+}', Controller\CategoryUpdateController::class);
        $r->post('/category/update/{id:\d+}', Controller\CategoryUpdateController::class);
        $r->get('', ItIsBackendController::class);
    });
});
