<?php

declare(strict_types=1);

namespace Abeliani\Blog\Application\Service\Image\Processor\Filter\Imagick;

use Abeliani\Blog\Application\Service\Image\Builder\BuilderActionInterface;
use Abeliani\Blog\Application\Service\Image\Builder\Filter\Negate;
use Abeliani\Blog\Application\Service\Image\Processor\ProcessorInterface;

#[\Attribute(\Attribute::TARGET_CLASS)]
final readonly class NegateImagick implements ProcessorInterface
{
    /**
     * @inheritDoc
     * @param \Imagick $image
     * @param Negate $action
     * @throws \ImagickException
     */
    public function __invoke(mixed $image, BuilderActionInterface $action): \Imagick
    {
        if (!$image->negateImage(false)) {
            throw new \RuntimeException('Failed to apply Imagick negate filter');
        }

        return $image;
    }
}