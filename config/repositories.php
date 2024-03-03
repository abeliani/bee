<?php
declare(strict_types=1);

use Abeliani\Blog\Domain\Repository\Category\CreateCategoryRepositoryInterface;
use Abeliani\Blog\Domain\Repository\Category\ReadCategoryRepositoryInterface;
use Abeliani\Blog\Domain\Repository\User\CreateUserRepositoryInterface;
use Abeliani\Blog\Domain\Repository\User\ReadUserRepositoryInterface;
use Abeliani\Blog\Infrastructure\Persistence\Mapper\CategoryMapper;
use Abeliani\Blog\Infrastructure\Persistence\Mapper\UserMapper;
use Abeliani\Blog\Infrastructure\Repository\CreateCategoryRepository;
use Abeliani\Blog\Infrastructure\Repository\CreateUserRepository;
use Abeliani\Blog\Infrastructure\Repository\ReadCategoryRepository;
use Abeliani\Blog\Infrastructure\Repository\ReadUserRepository;
use DI\Container;

return [
    UserMapper::class => fn(): UserMapper => new UserMapper(),
    CategoryMapper::class => fn (): CategoryMapper => new CategoryMapper(),

    CreateUserRepositoryInterface::class => function(Container $c): CreateUserRepositoryInterface {
        return new CreateUserRepository($c->get(PDO::class));
    },
    ReadUserRepositoryInterface::class => function(Container $c): ReadUserRepositoryInterface {
        return new ReadUserRepository($c->get(PDO::class), $c->get(UserMapper::class));
    },
    CreateCategoryRepositoryInterface::class => function(Container $c): CreateCategoryRepositoryInterface {
        return new CreateCategoryRepository($c->get(PDO::class));
    },
    ReadCategoryRepositoryInterface::class => function(Container $c): ReadCategoryRepository {
        return new ReadCategoryRepository($c->get(PDO::class), $c->get(CategoryMapper::class));
    },
];
