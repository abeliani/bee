<?php

declare(strict_types=1);

chdir(ROOT_DIR);

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Abeliani\Blog\Infrastructure\Delivery\API\ItWorksController;

return [
    Environment::class => function(): Environment {
        $loader = new FilesystemLoader('templates');
        return new Environment($loader);
    },
    ItWorksController::class => function($container) {
        return new ItWorksController($container->get(Environment::class));
    },
];