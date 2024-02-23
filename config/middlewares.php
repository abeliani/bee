<?php
declare(strict_types=1);

use Abeliani\Blog\Application\Middleware\ErrorHandlerMiddleware;
use Abeliani\Blog\Application\Middleware\JwtAuthenticationMiddleware;
use Abeliani\Blog\Application\Middleware\RouteDispatcherMiddleware;
use Abeliani\Blog\Domain\Repository\User\ReadUserRepositoryInterface;
use Abeliani\Blog\Infrastructure\Service\JWTAuthentication;
use DI\Container;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Twig\Environment;

return [
    ErrorHandlerMiddleware::class => function(Container $c): ErrorHandlerMiddleware {
        return new ErrorHandlerMiddleware($c->get(Environment::class), $c->get(LoggerInterface::class));
    },
    RouteDispatcherMiddleware::class => function(Container $c): RouteDispatcherMiddleware {
        return new RouteDispatcherMiddleware($c->get(Environment::class));
    },
    JwtAuthenticationMiddleware::class => function(Container $c): JwtAuthenticationMiddleware {
        return new JwtAuthenticationMiddleware(
            $c->get(JWTAuthentication::class),
            $c->get(ReadUserRepositoryInterface::class),
            $c->get(EventDispatcherInterface::class),
        );
    },
];
