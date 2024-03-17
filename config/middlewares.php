<?php
declare(strict_types=1);

use Abeliani\Blog\Application\Middleware;
use Abeliani\Blog\Domain\Repository\User\ReadUserRepositoryInterface;
use Abeliani\Blog\Infrastructure\Service\EnvLoader;
use Abeliani\Blog\Infrastructure\Service\JWTAuthentication;
use DI\Container;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Twig\Environment;

return [
    Middleware\ErrorHandlerMiddleware::class => function(Container $c): Middleware\ErrorHandlerMiddleware {
        return new Middleware\ErrorHandlerMiddleware(
            $c->get(Environment::class),
            $c->get(LoggerInterface::class),
            EnvLoader::get('APP_ENV') !== 'prod'
        );
    },
    Middleware\RouteDispatcherMiddleware::class => function(Container $c): Middleware\RouteDispatcherMiddleware {
        return new Middleware\RouteDispatcherMiddleware($c->get(Environment::class));
    },
    Middleware\JwtAuthenticationMiddleware::class => function(Container $c): Middleware\JwtAuthenticationMiddleware {
        return new Middleware\JwtAuthenticationMiddleware(
            $c->get(JWTAuthentication::class),
            $c->get(ReadUserRepositoryInterface::class),
            $c->get(EventDispatcherInterface::class),
        );
    },
    Middleware\CsrfCheckMiddleware::class => function(Container $c): Middleware\CsrfCheckMiddleware {
        return new Middleware\CsrfCheckMiddleware();
    },
    Middleware\RateLimitMiddleware::class => function(Container $c): Middleware\RateLimitMiddleware {
        $memcached = new Memcached();
        $memcached->addServer(getenv('MEMCACHED_SERVER'), (int) getenv('MEMCACHED_PORT'));
        return new Middleware\RateLimitMiddleware($memcached, 10, 60);
    },
];
