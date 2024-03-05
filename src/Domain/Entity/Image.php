<?php

declare(strict_types=1);

namespace Abeliani\Blog\Domain\Entity;

final readonly class Image extends Jsonable
{
    public function __construct(private string $type, private string $url)
    {
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function toArray(): array
    {
        return [
            'type' => $this->getType(),
            'url' => $this->getUrl(),
        ];
    }
}
