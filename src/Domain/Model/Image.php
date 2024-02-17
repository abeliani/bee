<?php

declare(strict_types=1);

namespace Abeliani\Blog\Domain\Model;

class Image extends Jsonable
{
    public function __construct(
        private readonly string $type,
        private readonly string $url,
        private readonly string $alt,
    ) {
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getAlt(): string
    {
        return $this->alt;
    }

    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'url' => $this->url,
            'alt' => $this->alt,
        ];
    }
}
