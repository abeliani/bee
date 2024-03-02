<?php
declare(strict_types=1);

namespace Abeliani\Blog\Application\Service\Image\Args;

final readonly class Size implements ImageBuilderArgInterface
{
    public function __construct(private float $width, private float $height)
    {
    }

    public function getWidth(): float
    {
        return $this->width;
    }

    public function getHeight(): float
    {
        return $this->height;
    }

    public function toArray(): array
    {
        return [
            'width' => $this->width,
            'height' => $this->height
        ];
    }
}
