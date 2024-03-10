<?php

declare(strict_types=1);

namespace Abeliani\Blog\Application\Service\Image\Builder\Filter;

use Abeliani\Blog\Application\Service\Image\FiltersEnum;

final readonly class Quality extends Filter
{
    public function __construct(private int $value)
    {
    }

    public static function type(): string
    {
        return FiltersEnum::quality->name;
    }

    public function getValue(): int
    {
        return $this->value;
    }
}
