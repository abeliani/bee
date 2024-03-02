<?php
declare(strict_types=1);

namespace Abeliani\Blog\Application\Service\Image\Processor\Manipulate\Imagick;

use Abeliani\Blog\Application\Service\Image\Builder\BuilderActionInterface;
use Abeliani\Blog\Application\Service\Image\Builder\Manipulate\Save;
use Abeliani\Blog\Application\Service\Image\Processor\ProcessorInterface;

#[\Attribute(\Attribute::TARGET_CLASS)]
class SaveImagick implements ProcessorInterface
{
    /**
     * @inheritDoc
     * @param \Imagick $image
     * @param Save $action
     * @throws \ImagickException
     */
    public function __invoke(mixed $image, BuilderActionInterface $action): \Imagick
    {
        if (!$image->setImageFormat($action->getFormat()) || !$image->writeImage($action->getPath())) {
            throw new \RuntimeException('Failed to save image');
        }

        return $image;
    }
}