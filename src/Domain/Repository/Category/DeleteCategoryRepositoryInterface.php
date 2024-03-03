<?php

namespace Abeliani\Blog\Domain\Repository\Category;

use Abeliani\Blog\Domain\Model\Category;

interface DeleteCategoryRepositoryInterface
{
    public function delete(Category $category): void;
}
