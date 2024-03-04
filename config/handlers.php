<?php
declare(strict_types=1);

use Abeliani\Blog\Domain\Repository\Category\ReadCategoryRepositoryInterface;
use Abeliani\Blog\Infrastructure\Delivery\Web\Controller\ItWorksController;
use Abeliani\Blog\Infrastructure\Persistence\Mapper\CategoryMapper;
use Abeliani\Blog\Infrastructure\Service\CategoryService;
use Abeliani\Blog\Infrastructure\Service\Form\FormService;
use Abeliani\Blog\Infrastructure\Service\Login;
use Abeliani\Blog\Infrastructure\UI\Web\CPanel\Controller\CategoryCreateController;
use Abeliani\Blog\Infrastructure\UI\Web\CPanel\Controller\CategoryIndexController;
use Abeliani\Blog\Infrastructure\UI\Web\CPanel\Controller\CategoryUpdateController;
use Abeliani\Blog\Infrastructure\UI\Web\CPanel\ItIsBackendController;
use Abeliani\Blog\Infrastructure\UI\Web\CPanel\LoginBackendController;
use Abeliani\Blog\Infrastructure\UI\Web\CPanel\LogoutBackendController;
use Abeliani\Blog\Infrastructure\UI\Web\CPanel\PostEditController;
use DI\Container;
use GuzzleHttp\Psr7\Response;
use Psr\EventDispatcher\EventDispatcherInterface;
use Twig\Environment;

return [
    ItWorksController::class => function(Container $c): ItWorksController {
        return new ItWorksController($c->get(Environment::class));
    },
    ItIsBackendController::class => function(Container $c): ItIsBackendController {
        return new ItIsBackendController($c->get(Environment::class));
    },
    CategoryCreateController::class => function(Container $c): CategoryCreateController {
        return new CategoryCreateController(
            $c->get(Environment::class),
            $c->get(FormService::class),
            new Response(),
            $c->get(CategoryService::class)
        );
    },
    PostEditController::class => function(Container $c): PostEditController {
        return new PostEditController($c->get(Environment::class));
    },
    LoginBackendController::class => function(Container $c): LoginBackendController {
        return new LoginBackendController(
            $c->get(Environment::class),
            $c->get(Login::class),
            $c->get(EventDispatcherInterface::class),
        );
    },
    LogoutBackendController::class => function(Container $c): LogoutBackendController {
        return new LogoutBackendController($c->get(Environment::class), $c->get(Login::class));
    },
    CategoryIndexController::class => function(Container $c): CategoryIndexController {
        return new CategoryIndexController(
            $c->get(Environment::class),
            new Response(),
            $c->get(ReadCategoryRepositoryInterface::class),
        );
    },
    CategoryUpdateController::class => function(Container $c): CategoryUpdateController {
        return new CategoryUpdateController(
            $c->get(Environment::class),
            $c->get(FormService::class),
            new Response(),
            $c->get(CategoryService::class),
            $c->get(ReadCategoryRepositoryInterface::class),
            $c->get(CategoryMapper::class),
        );
    },
];