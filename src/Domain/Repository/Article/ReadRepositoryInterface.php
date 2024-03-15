<?php

namespace Abeliani\Blog\Domain\Repository\Article;

use Abeliani\Blog\Domain\Enum\ArticleStatus;
use Abeliani\Blog\Domain\Model\Article;
use Abeliani\Blog\Domain\Collection\CollectionInterface;

interface ReadRepositoryInterface
{
    public function find(int $id): ?Article;
    public function findFirstId(): int;
    public function findLastId(): int;
    public function findByAuthor(int $id, int $creatorId): ?Article;
    public function findAll(int $limit, ?ArticleStatus $status = null): CollectionInterface;
    public function finaByCategory(int $creatorId, int $limit, ?ArticleStatus $status = null): CollectionInterface;
    public function findByCursor(int $cursor, int $direction, int $limit, ?ArticleStatus $status = null): CollectionInterface;
    public function findLast(int $count = 5): CollectionInterface;
    public function search(string $therm, int $limit, ArticleStatus $status = ArticleStatus::Published): CollectionInterface;
}
