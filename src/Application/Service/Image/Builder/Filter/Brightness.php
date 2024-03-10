<?php

declare(strict_types=1);

namespace Abeliani\Blog\Application\Service\Image\Builder\Filter;

use Abeliani\Blog\Application\Service\Image\FiltersEnum;
use Abeliani\Blog\Application\Service\Image\Processor\Filter\GD\BrightnessGdImage;

#[BrightnessGdImage]
final readonly class Brightness extends Filter
{
    public function __construct(private float $value)
    {
    }

    public static function type(): string
    {
        return FiltersEnum::brightness->name;
    }

    public function getValue(): float
    {
        return $this->value;
    }
}
