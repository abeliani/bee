<?php

declare(strict_types=1);

use Abeliani\Blog\Domain\Repository\Article;
use Abeliani\Blog\Domain\Repository\Category;
use Abeliani\Blog\Domain\Repository\Tag;
use Abeliani\Blog\Domain\Repository\User\CreateUserRepositoryInterface;
use Abeliani\Blog\Domain\Repository\User\ReadUserRepositoryInterface;
use Abeliani\Blog\Infrastructure\Persistence\Mapper;
use Abeliani\Blog\Infrastructure\Repository;
use Abeliani\Blog\Infrastructure\Repository\CreateUserRepository;
use Abeliani\Blog\Infrastructure\Repository\ReadUserRepository;
use DI\Container;

return [
    Mapper\UserMapper::class => fn(): Mapper\UserMapper => new Mapper\UserMapper(),
    Mapper\CategoryMapper::class => fn (): Mapper\CategoryMapper => new Mapper\CategoryMapper(),
    Mapper\ArticleMapper::class => fn (): Mapper\ArticleMapper => new Mapper\ArticleMapper(),

    CreateUserRepositoryInterface::class => function(Container $c): CreateUserRepositoryInterface {
        return new CreateUserRepository($c->get(PDO::class));
    },
    ReadUserRepositoryInterface::class => function(Container $c): ReadUserRepositoryInterface {
        return new ReadUserRepository($c->get(PDO::class), $c->get(Mapper\UserMapper::class));
    },
    Category\CreateCategoryRepositoryInterface::class => function(Container $c): Category\CreateCategoryRepositoryInterface {
        return new Repository\CreateCategoryRepository($c->get(PDO::class));
    },
    Category\ReadRepositoryInterface::class => function(Container $c): Category\ReadRepositoryInterface {
        return new Repository\Category\ReadRepository($c->get(PDO::class), $c->get(Mapper\CategoryMapper::class));
    },
    Category\UpdateCategoryRepositoryInterface::class => function(Container $c): Category\UpdateCategoryRepositoryInterface {
        return new Repository\UpdateCategoryRepository($c->get(PDO::class));
    },
    Article\CreateRepositoryInterface::class => function(Container $c): Article\CreateRepositoryInterface {
        return new Repository\Article\CreateRepository($c->get(PDO::class));
    },
    Article\UpdateRepositoryInterface::class => function(Container $c): Article\UpdateRepositoryInterface {
        return new Repository\Article\UpdateRepository($c->get(PDO::class));
    },
    Article\ReadRepositoryInterface::class => function(Container $c): Article\ReadRepositoryInterface {
        return new Repository\Article\ReadRepository($c->get(PDO::class), $c->get(Mapper\ArticleMapper::class));
    },
    Tag\ReadRepositoryInterface::class => function(Container $c): Tag\ReadRepositoryInterface {
        return new Repository\Tag\ReadRepository($c->get(PDO::class));
    },
];
