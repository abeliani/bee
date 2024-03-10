<?php

declare(strict_types=1);

namespace Abeliani\Blog\Application\Service\Image\Builder\Filter;

use Abeliani\Blog\Application\Service\Image\FiltersEnum;
use Abeliani\Blog\Application\Service\Image\Processor\Filter\GD\NegateGdImage;
use Abeliani\Blog\Application\Service\Image\Processor\Filter\Imagick\NegateImagick;

#[NegateGdImage]
#[NegateImagick]
final readonly class Negate extends Filter
{
    public function __construct(private bool $value = true)
    {
    }

    public static function type(): string
    {
        return FiltersEnum::negate->name;
    }

    public function getValue(): bool
    {
        return $this->value;
    }
}
