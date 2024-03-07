<?php
declare(strict_types=1);

use Abeliani\Blog\Domain\Repository\Article\ReadRepositoryInterface;
use Abeliani\Blog\Domain\Repository\Category\ReadCategoryRepositoryInterface;
use Abeliani\Blog\Infrastructure\Delivery\Web\Controller\ItWorksController;
use Abeliani\Blog\Infrastructure\Persistence\Mapper\ArticleMapper;
use Abeliani\Blog\Infrastructure\Persistence\Mapper\CategoryMapper;
use Abeliani\Blog\Infrastructure\Service;
use Abeliani\Blog\Infrastructure\Service\Form\FormService;
use Abeliani\Blog\Infrastructure\UI\Web\CPanel\Controller\Article;
use Abeliani\Blog\Infrastructure\UI\Web\CPanel\Controller\Category;
use Abeliani\Blog\Infrastructure\UI\Web\CPanel\Controller\CategoryCreateController;
use Abeliani\Blog\Infrastructure\UI\Web\CPanel\Controller\CategoryIndexController;
use Abeliani\Blog\Infrastructure\UI\Web\CPanel\ItIsBackendController;
use Abeliani\Blog\Infrastructure\UI\Web\CPanel\LoginBackendController;
use Abeliani\Blog\Infrastructure\UI\Web\CPanel\LogoutBackendController;
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
            $c->get(Service\CategoryService::class)
        );
    },
    Article\CreateController::class => function(Container $c): Article\CreateController {
        return new Article\CreateController(
            $c->get(Environment::class),
            $c->get(FormService::class),
            new Response(),
            $c->get(Service\ArticleService::class)
        );
    },
    Article\UpdateController::class => function(Container $c): Article\UpdateController {
        return new Article\UpdateController(
            $c->get(Environment::class),
            $c->get(FormService::class),
            new Response(),
            $c->get(Service\ArticleService::class),
            $c->get(ReadRepositoryInterface::class),
            $c->get(ArticleMapper::class),
        );
    },
    Article\IndexController::class => function(Container $c): Article\IndexController {
        return new Article\IndexController(
            $c->get(Environment::class),
            new Response(),
            $c->get(ReadRepositoryInterface::class),
        );
    },
    LoginBackendController::class => function(Container $c): LoginBackendController {
        return new LoginBackendController(
            $c->get(Environment::class),
            $c->get(Service\Login::class),
            $c->get(EventDispatcherInterface::class),
        );
    },
    LogoutBackendController::class => function(Container $c): LogoutBackendController {
        return new LogoutBackendController($c->get(Environment::class), $c->get(Service\Login::class));
    },
    CategoryIndexController::class => function(Container $c): CategoryIndexController {
        return new CategoryIndexController(
            $c->get(Environment::class),
            new Response(),
            $c->get(ReadCategoryRepositoryInterface::class),
        );
    },
    Category\UpdateController::class => function(Container $c): Category\UpdateController {
        return new Category\UpdateController(
            $c->get(Environment::class),
            $c->get(FormService::class),
            new Response(),
            $c->get(Service\CategoryService::class),
            $c->get(ReadCategoryRepositoryInterface::class),
            $c->get(CategoryMapper::class),
        );
    },
];