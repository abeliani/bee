<?php
declare(strict_types=1);

use Abeliani\Blog\Domain\Repository\User\CreateUserRepositoryInterface;
use Abeliani\Blog\Domain\Repository\User\ReadUserRepositoryInterface;
use Abeliani\Blog\Infrastructure\Persistence\Mapper\UserMapper;
use Abeliani\Blog\Infrastructure\Repository\CreateUserRepository;
use Abeliani\Blog\Infrastructure\Repository\ReadUserRepository;
use DI\Container;

return [
    UserMapper::class => function(): UserMapper {
        return new UserMapper();
    },
    CreateUserRepositoryInterface::class => function(Container $c): CreateUserRepositoryInterface {
        return new CreateUserRepository($c->get(PDO::class));
    },
    ReadUserRepositoryInterface::class => function(Container $c): ReadUserRepositoryInterface {
        return new ReadUserRepository($c->get(PDO::class), $c->get(UserMapper::class));
    },
];
