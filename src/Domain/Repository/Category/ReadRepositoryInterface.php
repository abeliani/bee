<?php

namespace Abeliani\Blog\Domain\Repository\Category;

use Abeliani\Blog\Domain\Collection\CollectionInterface;
use Abeliani\Blog\Domain\Model\Category;

interface ReadRepositoryInterface
{
    public function find(int $id, int $creatorId): ?Category;
    public function findAll(): CollectionInterface;
}
