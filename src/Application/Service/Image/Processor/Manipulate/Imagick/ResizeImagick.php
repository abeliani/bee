<?php
declare(strict_types=1);

namespace Abeliani\Blog\Application\Service\Image\Processor\Manipulate\Imagick;

use Abeliani\Blog\Application\Service\Image\Builder\BuilderActionInterface;
use Abeliani\Blog\Application\Service\Image\Builder\Manipulate\Resize;
use Abeliani\Blog\Application\Service\Image\Processor\ProcessorInterface;

#[\Attribute(\Attribute::TARGET_CLASS)]
class ResizeImagick implements ProcessorInterface
{
    /**
     * @inheritDoc
     * @param \Imagick $image
     * @param Resize $action
     * @throws \ImagickException
     */
    public function __invoke(mixed $image, BuilderActionInterface $action): \Imagick
    {
        $result = $image->resizeImage(
            (int)$action->getSize()->getWidth(),
            (int)$action->getSize()->getHeight(),
            \Imagick::FILTER_LANCZOS,
            1
        );

        if (!$result) {
            throw new \RuntimeException('Failed to resize image');
        }

        return $image;
    }
}
