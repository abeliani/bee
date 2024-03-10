<?php
declare(strict_types=1);

namespace Abeliani\Blog\Application\Service\Image\Builder\Filter;

use Abeliani\Blog\Application\Service\Image\FiltersEnum;
use Abeliani\Blog\Application\Service\Image\Processor\Filter\GD\GrayscaleGdImage;

#[GrayscaleGdImage]
final readonly class Grayscale extends Filter
{
    public function __construct(private bool $value = true)
    {
    }

    public static function type(): string
    {
        return FiltersEnum::grayscale->name;
    }

    public function getValue(): bool
    {
        return $this->value;
    }
}
