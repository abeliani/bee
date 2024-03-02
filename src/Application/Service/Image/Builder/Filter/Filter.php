<?php
declare(strict_types=1);

namespace Abeliani\Blog\Application\Service\Image\Builder\Filter;

use Abeliani\Blog\Application\Service\Image\Builder\BuilderActionInterface;
use Abeliani\Blog\Application\Service\Image\TypeEnum;

abstract class Filter implements BuilderActionInterface
{
    abstract public function getValue(): mixed;
    abstract public static function getName(): string;

    public function getType(): string
    {
        return TypeEnum::filter->name;
    }

    public function getLibrary(): string
    {
        return '';
    }

    public function toArray(): array
    {
        return [static::getName() => $this->getValue()];
    }
}
