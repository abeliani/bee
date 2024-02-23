<?php

namespace Abeliani\Blog\Domain\Repository;

use Abeliani\Blog\Domain\Model\Category;
use Tightenco\Collect\Support\Collection;

interface ReadCategoryRepositoryInterface
{
    public function findById($id): ?Category;
    public function findAll(): Collection;
}
