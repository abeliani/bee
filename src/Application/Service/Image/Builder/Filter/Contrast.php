<?php
declare(strict_types=1);

namespace Abeliani\Blog\Application\Service\Image\Builder\Filter;

use Abeliani\Blog\Application\Service\Image\FiltersEnum;

class Contrast extends Filter
{
    public function __construct(private readonly float $value)
    {
    }

    public static function getName(): string
    {
        return FiltersEnum::contrast->name;
    }

    public function getValue(): float
    {
        return $this->value;
    }
}
