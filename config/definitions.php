<?php

declare(strict_types=1);

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Abeliani\Blog\Infrastructure\Delivery\API\ItWorksController;
use Abeliani\Blog\Infrastructure\Delivery\Backend\ItIsBackendController;
use Abeliani\Blog\Infrastructure\Delivery\Backend\PostEditController;

return [
    Environment::class => function(): Environment {
        return new Environment(new FilesystemLoader(TEMPLATES_DIR));
    },
    PDO::class => function (): PDO {
        $pdo = new PDO('mysql:host=localhost;dbname=bee', 'root', 'root');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    },

    ItWorksController::class => function($container): ItWorksController {
        return new ItWorksController($container->get(Environment::class));
    },
    ItIsBackendController::class => function($container): ItIsBackendController {
        return new ItIsBackendController($container->get(Environment::class));
    },
    PostEditController::class => function($container): PostEditController {
        return new PostEditController($container->get(Environment::class));
    },
];