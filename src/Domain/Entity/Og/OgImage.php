<?php

declare(strict_types=1);

namespace Abeliani\Blog\Domain\Entity\Og;

use Abeliani\Blog\Domain\Entity\Jsonable;

final readonly class OgImage extends Jsonable
{
    public function __construct(
        private string $image,
        private ?string $type,
        private ?string $with,
        private ?string $height,
        private ?string $alt,
    ) {
    }

    public function toArray(): array
    {
        return [
            'image' => $this->image,
            'type' => $this->type,
            'with' => $this->with,
            'height' => $this->height,
            'alt' => $this->alt,
        ];
    }
}
