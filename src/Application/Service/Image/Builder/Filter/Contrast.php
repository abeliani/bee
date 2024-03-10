<?php

declare(strict_types=1);

namespace Abeliani\Blog\Application\Service\Image\Builder\Filter;

use Abeliani\Blog\Application\Service\Image\FiltersEnum;
use Abeliani\Blog\Application\Service\Image\Processor\Filter\GD\ContrastGdImage;

#[ContrastGdImage]
final readonly class Contrast extends Filter
{
    public function __construct(private float $value)
    {
    }

    public static function type(): string
    {
        return FiltersEnum::contrast->name;
    }

    public function getValue(): float
    {
        return $this->value;
    }
}
