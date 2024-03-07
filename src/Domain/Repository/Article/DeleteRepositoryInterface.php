<?php

namespace Abeliani\Blog\Domain\Repository\Article;

use Abeliani\Blog\Domain\Model\Article;

interface DeleteRepositoryInterface
{
    public function delete(Article $a): void;
}
