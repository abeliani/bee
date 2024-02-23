<?php
declare(strict_types=1);

use Abeliani\Blog\Application\Service\UserRegistration\UserRegistrationService;
use Abeliani\Blog\Domain\Factory\UserFactory;
use Abeliani\Blog\Domain\Repository\User\CreateUserRepositoryInterface;
use Abeliani\Blog\Domain\Repository\User\ReadUserRepositoryInterface;
use Abeliani\Blog\Domain\Service\PasswordHasher\PasswordHasherInterface;
use Abeliani\Blog\Infrastructure\Service\Form\FormService;
use Abeliani\Blog\Infrastructure\Service\Hydrator;
use Abeliani\Blog\Infrastructure\Service\JWTAuthentication;
use Abeliani\Blog\Infrastructure\Service\Login;
use Abeliani\Blog\Infrastructure\Service\PasswordHasher;
use Abeliani\Blog\Infrastructure\Service\RequestValidator\RequestValidatorService;
use DI\Container;
use Symfony\Component\Validator\Validation;

return [
    UserFactory::class => function(Container $c): UserFactory {
        return new UserFactory($c->get(PasswordHasherInterface::class));
    },
    UserRegistrationService::class => function(Container $c): UserRegistrationService {
        return new UserRegistrationService(
            $c->get(CreateUserRepositoryInterface::class),
            $c->get(UserFactory::class),
        );
    },
    JWTAuthentication::class => function(): JWTAuthentication {
        return new JWTAuthentication(
            getenv('JWT_SECRET'),
            getenv('APP_DOMAIN'),
            getenv('APP_NAME'),
            '+24 hour',
            'auth_token'
        );
    },
    PasswordHasherInterface::class => function(): PasswordHasherInterface {
        return new PasswordHasher();
    },
    Login::class => function(Container $c): Login {
        return new Login(
            $c->get(JWTAuthentication::class),
            $c->get(PasswordHasherInterface::class),
            $c->get(ReadUserRepositoryInterface::class)
        );
    },
    RequestValidatorService::class => function(): RequestValidatorService {
        return new RequestValidatorService(
            Validation::createValidatorBuilder()->enableAttributeMapping()->getValidator(),
        );
    },
    FormService::class => function(Container $c): FormService {
        return new FormService(new Hydrator(), $c->get(RequestValidatorService::class));
    },
];
