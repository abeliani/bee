<?php

namespace Abeliani\Blog\Domain\Repository\Category;

use Abeliani\Blog\Domain\Model\Category;

interface CreateCategoryRepositoryInterface
{
    public function create(Category $c): void;
}
