<?php
declare(strict_types=1);

namespace Abeliani\Blog\Application\Service\Image\Processor\Manipulate\GD;

use Abeliani\Blog\Application\Service\Image\Builder\BuilderActionInterface;
use Abeliani\Blog\Application\Service\Image\Builder\Manipulate\Resize;
use Abeliani\Blog\Application\Service\Image\Processor\ProcessorInterface;

#[\Attribute(\Attribute::TARGET_CLASS)]
class ResizeGdImage implements ProcessorInterface
{
    /**
     * @inheritDoc
     * @param \GdImage $image
     * @param Resize $action
     */
    public function __invoke(mixed $image, BuilderActionInterface $action): \GdImage
    {
        $resized = imagescale(
            $image,
            (int)$action->getSize()->getWidth(),
            (int)$action->getSize()->getHeight()
        );

        imagedestroy($image);

        if (!$resized) {
            throw new \RuntimeException('Failed to resize image');
        }

        return $resized;
    }
}
