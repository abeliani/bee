<?php
declare(strict_types=1);

use Abeliani\Blog\Application\Service\Subscription\SubscriptionService;
use Abeliani\Blog\Domain\Repository\Tag;
use Abeliani\Blog\Domain\Repository\Article;
use Abeliani\Blog\Domain\Repository\Category;
use Abeliani\Blog\Domain\Service\TransliteratorBijective;
use Abeliani\Blog\Infrastructure\Persistence\Mapper\ArticleMapper;
use Abeliani\Blog\Infrastructure\Persistence\Mapper\CategoryMapper;
use Abeliani\Blog\Infrastructure\Service;
use Abeliani\Blog\Infrastructure\UI\Web\CPanel\Controller\Upload\ImageCreateController;
use Abeliani\Blog\Infrastructure\UI\Web\CPanel\Controller\Upload\ImageGalleryController;
use Abeliani\Blog\Infrastructure\UI\Web\CPanel\Controller\Upload\ImageIndexController;
use Abeliani\Blog\Infrastructure\UI\Web\CPanel\Controller\Upload\ImageUpdateController;
use Abeliani\Blog\Infrastructure\UI\Web\Frontend\Controller\Category as FrontCategory;
use Abeliani\Blog\Infrastructure\UI\Web\Frontend\Controller\Redirector as FrontRedirector;
use Abeliani\Blog\Infrastructure\UI\Web\Frontend\Controller\Subscription as Subscribe;
use Abeliani\Blog\Infrastructure\UI\Web\Frontend\Controller\Article as FrontArticle;
use Abeliani\Blog\Infrastructure\UI\Web\Frontend\Controller\Tag as FrontTag;
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
            $c->get(Category\ReadRepositoryInterface::class),
            $c->get(Tag\ReadRepositoryInterface::class),
        );
    },
    FrontArticle\SearchController::class => function(Container $c): FrontArticle\SearchController {
        return new FrontArticle\SearchController(
            $c->get(Environment::class),
            new Response(),
            $c->get(Article\ReadRepositoryInterface::class),
            $c->get(Category\ReadRepositoryInterface::class),
            $c->get(Tag\ReadRepositoryInterface::class),
        );
    },
    FrontCategory\ViewController::class => function(Container $c): FrontCategory\ViewController {
        return new FrontCategory\ViewController(
            $c->get(Environment::class),
            new Response(),
            $c->get(Article\ReadRepositoryInterface::class),
            $c->get(Category\ReadRepositoryInterface::class),
            $c->get(Tag\ReadRepositoryInterface::class),
        );
    },
    FrontArticle\ViewController::class => function(Container $c): FrontArticle\ViewController {
        return new FrontArticle\ViewController(
            $c->get(Environment::class),
            new Response(),
            $c->get(Article\ReadRepositoryInterface::class),
            $c->get(Category\ReadRepositoryInterface::class),
            $c->get(Tag\ReadRepositoryInterface::class),
        );
    },
    FrontTag\ViewController::class => function(Container $c): FrontTag\ViewController {
        return new FrontTag\ViewController(
            $c->get(Environment::class),
            new Response(),
            $c->get(Article\ReadRepositoryInterface::class),
            $c->get(Category\ReadRepositoryInterface::class),
            $c->get(Tag\ReadRepositoryInterface::class),
            $c->get(TransliteratorBijective::class),
        );
    },
    Subscribe\SubscribeController::class => function(Container $c): Subscribe\SubscribeController {
        return new Subscribe\SubscribeController(
            $c->get(Environment::class),
            $c->get(FormService::class),
            new Response(),
            $c->get(Article\ReadRepositoryInterface::class),
            $c->get(Category\ReadRepositoryInterface::class),
            $c->get(Tag\ReadRepositoryInterface::class),
            $c->get(SubscriptionService::class),
        );
    },
    Subscribe\SubscribeConfirmController::class => function(Container $c): Subscribe\SubscribeConfirmController {
        return new Subscribe\SubscribeConfirmController(
            $c->get(Environment::class),
            $c->get(FormService::class),
            new Response(),
            $c->get(Article\ReadRepositoryInterface::class),
            $c->get(Category\ReadRepositoryInterface::class),
            $c->get(Tag\ReadRepositoryInterface::class),
            $c->get(SubscriptionService::class),
        );
    },
    FrontRedirector\ViewController::class => function(Container $c): FrontRedirector\ViewController {
        return new FrontRedirector\ViewController(
            $c->get(Environment::class),
            $c->get(Service\Redirector::class),
            new Response(),
            5,
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
    ImageGalleryController::class => function(Container $c): ImageGalleryController {
        return new ImageGalleryController(
            new Response(),
            $c->get(\Abeliani\Blog\Domain\Repository\Upload\ImageRepositoryInterface::class),
        );
    },
    ImageIndexController::class => function(Container $c): ImageIndexController {
        return new ImageIndexController(
            $c->get(Environment::class),
            new Response(),
            $c->get(\Abeliani\Blog\Domain\Repository\Upload\ImageRepositoryInterface::class),
        );
    },
    ImageCreateController::class => function(Container $c): ImageCreateController {
        return new ImageCreateController(
            $c->get(Environment::class),
            $c->get(FormService::class),
            new Response(),
            $c->get(\Abeliani\Blog\Application\Service\Upload\UploadService::class),
        );
    },
    ImageUpdateController::class => function(Container $c): ImageUpdateController {
        return new ImageUpdateController(
            $c->get(Environment::class),
            $c->get(FormService::class),
            new Response(),
            $c->get(\Abeliani\Blog\Application\Service\Upload\UploadService::class),
            $c->get(\Abeliani\Blog\Domain\Repository\Upload\ImageRepositoryInterface::class),
        );
    },
];