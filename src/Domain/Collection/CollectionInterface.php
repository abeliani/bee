<?php

namespace Abeliani\Blog\Domain\Collection;

interface CollectionInterface extends \ArrayAccess, \Iterator, \Countable, \JsonSerializable, \Stringable
{
    public function addAll(mixed $items): void;

    public function add(mixed $item): void;

    public function copy(bool $deep = true): CollectionInterface;

    public function getType(): string;

    public function stream(): StreamCollectionInterface;
}
