<?php
declare(strict_types=1);

namespace Abeliani\Blog\Domain\Enum;

class EnumUtils
{
    public static function toArray(string $enumClass): array
    {
        if (!enum_exists($enumClass)) {
            throw new \InvalidArgumentException("Class '{$enumClass}' must be enum.");
        }

        /** @var \UnitEnum $enumClass */
        return array_column(array_map(fn($case) => [
            'name' => $case->name,
            'value' => $case->value
        ], $enumClass::cases()), 'value', 'name');
    }
}
