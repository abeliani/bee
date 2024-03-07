<?php

namespace Abeliani\Blog\Domain\Repository\Article;

use Abeliani\Blog\Domain\Model\Article;
use Abeliani\Blog\Domain\Collection\CollectionInterface;

interface ReadRepositoryInterface
{
    public function find(int $id, int $creatorId): ?Article;
    public function findAll(): CollectionInterface;
}
