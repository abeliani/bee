<?php
declare(strict_types=1);

namespace Abeliani\Blog\Application\Service\Image\Processor\Manipulate\GD;

use Abeliani\Blog\Application\Service\Image\Builder\Manipulate\Save;
use Abeliani\Blog\Application\Service\Image\Processor\ProcessorInterface;

#[\Attribute(\Attribute::TARGET_CLASS)]
class SaveGdImage implements ProcessorInterface
{
    /**
     * @inheritDoc
     * @param \GdImage $image
     * @param Save $action
     */
    public function __invoke(mixed $image, mixed $action): mixed
    {
        if (!call_user_func(sprintf('image%s', $action->getFormat()), $image, $action->getFilePath())) {
            throw new \RuntimeException('Failed to save image');
        }

        return $image;
    }
}
