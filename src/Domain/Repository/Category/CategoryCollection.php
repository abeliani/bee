<?php

declare(strict_types=1);

namespace Abeliani\Blog\Domain\Repository\Category;

use Abeliani\Blog\Domain\Collection\Collection;
use Abeliani\Blog\Domain\Model\Category;

class CategoryCollection extends Collection
{
    public function __construct()
    {
        parent::__construct(Category::class);
    }
}
