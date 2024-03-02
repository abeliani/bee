<?php
declare(strict_types=1);

namespace Abeliani\Blog\Application\Service\Image\Builder\Filter;

use Abeliani\Blog\Application\Service\Image\FiltersEnum;

class Negate extends Filter
{
    public function __construct(private readonly bool $value = true)
    {
    }

    public static function getName(): string
    {
        return FiltersEnum::negate->name;
    }

    public function getValue(): bool
    {
        return $this->value;
    }
}
