<?php

namespace Abeliani\Blog\Domain\Repository;

use Abeliani\Blog\Domain\Model\Category;

interface UpdateCategoryRepositoryInterface
{
    public function update(Category $category): void;
}