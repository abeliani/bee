<?php

namespace Abeliani\Blog\Application\Service\Image\Builder;

use Abeliani\Blog\Domain\Interface\ToArrayInterface;

interface BuilderActionInterface extends ToArrayInterface
{
    public static function type(): string;
    public function getType(): string;
    public function getLibrary(): string;
    public function toArray(): array;
}
