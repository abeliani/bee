<?php

declare(strict_types=1);

use Abeliani\Blog\Infrastructure\UI\Web\CPanel;
use Abeliani\Blog\Infrastructure\UI\Web\Frontend;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

return simpleDispatcher(function (RouteCollector $r) {
    $r->addGroup('/', function (RouteCollector $r) {
        $r->get('', Frontend\Controller\Article\IndexController::class);
    });

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

        $r->get('/category/update/{id:\d+}', CPanel\Controller\Category\UpdateController::class);
        $r->post('/category/update/{id:\d+}', CPanel\Controller\Category\UpdateController::class);

        $r->get('/category', CPanel\Controller\CategoryIndexController::class);
    });
});
