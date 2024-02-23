<?php

namespace Abeliani\Blog\Domain\Repository;

use Abeliani\Blog\Domain\Model\Category;

interface DeleteCategoryRepositoryInterface
{
    public function delete(Category $category): void;
}
