<?php

namespace Abeliani\Blog\Domain\Repository\Article;

use Abeliani\Blog\Domain\Model\Article;

interface UpdateRepositoryInterface
{
    public function update(Article $a): void;
}