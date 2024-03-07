<?php

declare(strict_types=1);

namespace Abeliani\Blog\Domain\Collection\Concrete;

use Abeliani\Blog\Domain\Model\Article;
use Abeliani\Blog\Domain\Collection\Collection;

class ArticleCollection extends Collection
{
    public function __construct()
    {
        parent::__construct(Article::class);
    }
}
