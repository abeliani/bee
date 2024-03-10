<?php
declare(strict_types=1);

use Abeliani\Blog\Domain\Repository\Article;
use Abeliani\Blog\Domain\Repository\Category;
use Abeliani\Blog\Infrastructure\Persistence\Mapper\ArticleMapper;
use Abeliani\Blog\Infrastructure\Persistence\Mapper\CategoryMapper;
use Abeliani\Blog\Infrastructure\Service;
use Abeliani\Blog\Infrastructure\UI\Web\Frontend\Controller\Article as FrontArticle;
use Abeliani\Blog\Infrastructure\UI\Web\CPanel\Controller\Article as CpanelArticle;
use Abeliani\Blog\Infrastructure\Service\Form\FormService;
use Abeliani\Blog\Infrastructure\UI\Web\CPanel\Controller\Category as CpanelCategory;
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
    FrontArticle\IndexController::class => function(Container $c): FrontArticle\IndexController {
        return new FrontArticle\IndexController(
            $c->get(Environment::class),
            new Response(),
            $c->get(Article\ReadRepositoryInterface::class),
        );
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
    CpanelArticle\CreateController::class => function(Container $c): CpanelArticle\CreateController {
        return new CpanelArticle\CreateController(
            $c->get(Environment::class),
            $c->get(FormService::class),
            new Response(),
            $c->get(Service\ArticleService::class),
            $c->get(Category\ReadRepositoryInterface::class),

        );
    },
    CpanelArticle\UpdateController::class => function(Container $c): CpanelArticle\UpdateController {
        return new CpanelArticle\UpdateController(
            $c->get(Environment::class),
            $c->get(FormService::class),
            new Response(),
            $c->get(Service\ArticleService::class),
            $c->get(Article\ReadRepositoryInterface::class),
            $c->get(Category\ReadRepositoryInterface::class),
            $c->get(ArticleMapper::class),
        );
    },
    CpanelArticle\IndexController::class => function(Container $c): CpanelArticle\IndexController {
        return new CpanelArticle\IndexController(
            $c->get(Environment::class),
            new Response(),
            $c->get(Article\ReadRepositoryInterface::class),
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
            $c->get(Category\ReadRepositoryInterface::class),
        );
    },
    CpanelCategory\UpdateController::class => function(Container $c): CpanelCategory\UpdateController {
        return new CpanelCategory\UpdateController(
            $c->get(Environment::class),
            $c->get(FormService::class),
            new Response(),
            $c->get(Service\CategoryService::class),
            $c->get(Category\ReadRepositoryInterface::class),
            $c->get(CategoryMapper::class),
        );
    },
];