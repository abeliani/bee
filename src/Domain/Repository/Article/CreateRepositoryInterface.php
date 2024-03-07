<?php

namespace Abeliani\Blog\Domain\Repository\Article;

use Abeliani\Blog\Domain\Model\Article;

interface CreateRepositoryInterface
{
    public function create(Article $a): void;
}
