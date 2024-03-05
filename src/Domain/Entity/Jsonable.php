<?php

declare(strict_types=1);

namespace Abeliani\Blog\Domain\Entity;

abstract readonly class Jsonable implements \JsonSerializable, \Stringable
{
    abstract public function toArray(): array;

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function __toString(): string
    {
        return json_encode($this->jsonSerialize(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}