<?php

namespace Abeliani\Blog\Domain\Collection;

interface StreamCollectionInterface
{
    public function filter(callable $condition): StreamCollectionInterface;
    public function sort(callable $comparator): StreamCollectionInterface;
    public function getCollection(): CollectionInterface;
}
