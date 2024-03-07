<?php

declare(strict_types=1);

use Abeliani\Blog\Infrastructure\Delivery\Web\Controller\ItWorksController;
use Abeliani\Blog\Infrastructure\UI\Web\CPanel;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

return simpleDispatcher(function (RouteCollector $r) {
    $r->get('/', ItWorksController::class);

    $r->addGroup('/cpanel', function (RouteCollector $r) {
        $r->post('/login', CPanel\LoginBackendController::class);
        $r->get('/login', CPanel\LoginBackendController::class);
        $r->get('/logout', CPanel\LogoutBackendController::class);

        $r->get('', CPanel\ItIsBackendController::class);

        $r->get('/article/create', CPanel\Controller\Article\CreateController::class);
        $r->post('/article/create', CPanel\Controller\Article\CreateController::class);

        $r->get('/article/update/{id:\d+}', CPanel\Controller\Article\UpdateController::class);
        $r->post('/article/update/{id:\d+}', CPanel\Controller\Article\UpdateController::class);

        $r->get('/article', CPanel\Controller\Article\IndexController::class);

        $r->get('/category/create', CPanel\Controller\CategoryCreateController::class);
        $r->post('/category/create', CPanel\Controller\CategoryCreateController::class);

        $r->get('/category/update/{id:\d+}', CPanel\Controller\CategoryUpdateController::class);
        $r->post('/category/update/{id:\d+}', CPanel\Controller\CategoryUpdateController::class);

        $r->get('/category', CPanel\Controller\CategoryIndexController::class);
    });
});
