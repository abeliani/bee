<?php

declare(strict_types=1);

use Abeliani\Blog\Application\Service\UserRegistrationService;
use Abeliani\Blog\Console\Command\RegisterUserCommand;
use Abeliani\Blog\Domain\Factory\UserFactory;
use Abeliani\Blog\Domain\Repository\User\CreateUserRepositoryInterface;
use Abeliani\Blog\Domain\Service\PasswordHasher\PasswordHasherInterface;
use Abeliani\Blog\Infrastructure\Repository\CreateUserRepository;
use Abeliani\Blog\Infrastructure\Service\PasswordHasher;
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
        $pdo = new PDO('mysql:host=db;dbname=bee', 'root', 'root');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    },
    CreateUserRepositoryInterface::class => function($container): CreateUserRepository {
        return new CreateUserRepository($container->get(PDO::class));
    },
    PasswordHasherInterface::class => function(): PasswordHasherInterface {
        return new PasswordHasher();
    },
    UserFactory::class => function($container): UserFactory {
        return new UserFactory($container->get(PasswordHasherInterface::class));
    },
    UserRegistrationService::class => function($container): UserRegistrationService {
        return new UserRegistrationService(
            $container->get(CreateUserRepositoryInterface::class),
            $container->get(UserFactory::class),
        );
    },

    RegisterUserCommand::class => function($container): RegisterUserCommand {
        return new RegisterUserCommand($container->get(UserRegistrationService::class));
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