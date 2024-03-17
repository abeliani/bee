<?php

namespace Abeliani\Blog\Domain\Repository\Tag;

use Abeliani\Blog\Domain\Collection\CollectionInterface;
use Abeliani\Blog\Domain\Model\Tag;

interface ReadRepositoryInterface
{
    public function find(int $id): ?Tag;
    public function findAll(): CollectionInterface;
    public function findByArticle(int $articleId): CollectionInterface;
}