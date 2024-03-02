<?php
declare(strict_types=1);

namespace Abeliani\Blog\Application\Service\Image\Builder\Manipulate;

use Abeliani\Blog\Application\Service\Image\ManipulateEnum;

class AdaptiveResize extends Manipulate
{
    public function __construct(private readonly float $dimension, ?string $library = null)
    {
        parent::__construct($library);
    }

    public static function getName(): string
    {
        return ManipulateEnum::adaptiveResize->name;
    }

    public function getDimension(): float
    {
        return $this->dimension;
    }

    public function getValue(): float
    {
        return $this->getDimension();
    }
}
