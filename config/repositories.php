<?php
declare(strict_types=1);

use Abeliani\Blog\Domain\Repository\Category;
use Abeliani\Blog\Domain\Repository\User\CreateUserRepositoryInterface;
use Abeliani\Blog\Domain\Repository\User\ReadUserRepositoryInterface;
use Abeliani\Blog\Infrastructure\Persistence\Mapper\CategoryMapper;
use Abeliani\Blog\Infrastructure\Persistence\Mapper\UserMapper;
use Abeliani\Blog\Infrastructure\Repository;
use Abeliani\Blog\Infrastructure\Repository\CreateUserRepository;
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
    Category\CreateCategoryRepositoryInterface::class => function(Container $c): Category\CreateCategoryRepositoryInterface {
        return new Repository\CreateCategoryRepository($c->get(PDO::class));
    },
    Category\ReadCategoryRepositoryInterface::class => function(Container $c): Category\ReadCategoryRepositoryInterface {
        return new Repository\ReadCategoryRepository($c->get(PDO::class), $c->get(CategoryMapper::class));
    },
    Category\UpdateCategoryRepositoryInterface::class => function(Container $c): Category\UpdateCategoryRepositoryInterface {
        return new Repository\UpdateCategoryRepository($c->get(PDO::class));
    },
];
