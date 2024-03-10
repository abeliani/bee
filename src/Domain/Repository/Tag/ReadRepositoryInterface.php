<?php

namespace Abeliani\Blog\Domain\Repository\Tag;

use Abeliani\Blog\Domain\Collection\CollectionInterface;

interface ReadRepositoryInterface
{
    public function findAll(): CollectionInterface;
}