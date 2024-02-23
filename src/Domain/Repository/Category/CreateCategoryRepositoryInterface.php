<?php

namespace Abeliani\Blog\Domain\Repository;

use Abeliani\Blog\Domain\Model\Category;

interface CreateCategoryRepositoryInterface
{
    public function create(Category $category): void;
}
