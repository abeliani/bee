<?php

namespace Abeliani\Blog\Domain\Repository\Category;

use Abeliani\Blog\Domain\Model\Category;

interface ReadCategoryRepositoryInterface
{
    public function find(int $id): ?Category;
    public function findAll(): CategoryCollection;
    public function findPublished(int $actorId): CategoryCollection;
}
