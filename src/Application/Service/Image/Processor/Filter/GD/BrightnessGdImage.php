<?php

declare(strict_types=1);

namespace Abeliani\Blog\Application\Service\Image\Processor\Filter\GD;

use Abeliani\Blog\Application\Service\Image\Builder\BuilderActionInterface;
use Abeliani\Blog\Application\Service\Image\Builder\Filter\Brightness;
use Abeliani\Blog\Application\Service\Image\Processor\ProcessorInterface;

#[\Attribute(\Attribute::TARGET_CLASS)]
final readonly class BrightnessGdImage implements ProcessorInterface
{
    /**
     * @inheritDoc
     * @param \GdImage $image
     * @param Brightness $action
     */
    public function __invoke(mixed $image, BuilderActionInterface $action): \GdImage
    {
        if (!imagefilter($image, IMG_FILTER_BRIGHTNESS, (int) $action->getValue())) {
            throw new \RuntimeException('Failed to apply GdImage negate filter');
        }

        return $image;
    }
}