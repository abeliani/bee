<?php
declare(strict_types=1);

namespace Abeliani\Blog\Application\Service\Image\Args;

final readonly class Coords implements ImageBuilderArgInterface
{
    public function __construct(private float $x, private float $y)
    {
    }

    public function getX(): float
    {
        return $this->x;
    }

    public function getY(): float
    {
        return $this->y;
    }

    public function toArray(): array
    {
        return [
            'x' => $this->x,
            'y' => $this->y,
        ];
    }
}
