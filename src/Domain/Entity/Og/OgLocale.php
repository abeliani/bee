<?php

declare(strict_types=1);

namespace Abeliani\Blog\Domain\Entity\Og;

use Abeliani\Blog\Domain\Entity\Jsonable;
use Abeliani\Blog\Domain\Enum\Locale;

final readonly class OgLocale extends Jsonable
{
    public function __construct(private readonly Locale $alternative)
    {
    }

    public function toArray(): array
    {
        return [
            'og:locale:alternative' => $this->alternative->value
        ];
    }
}
