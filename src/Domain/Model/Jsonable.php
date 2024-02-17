<?php

declare(strict_types=1);

namespace Abeliani\Blog\Domain\Model;

abstract class Jsonable implements \JsonSerializable
{
    abstract public function toArray(): array;

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }
}