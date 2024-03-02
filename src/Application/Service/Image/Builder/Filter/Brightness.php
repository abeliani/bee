<?php
declare(strict_types=1);

namespace Abeliani\Blog\Application\Service\Image\Builder\Filter;

use Abeliani\Blog\Application\Service\Image\FiltersEnum;

class Brightness extends Filter
{
    public function __construct(private readonly float $value)
    {
    }

    public static function getName(): string
    {
        return FiltersEnum::brightness->name;
    }

    public function getValue(): float
    {
        return $this->value;
    }
}
