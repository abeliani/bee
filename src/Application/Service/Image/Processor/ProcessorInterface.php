<?php

namespace Abeliani\Blog\Application\Service\Image\Processor;

use Abeliani\Blog\Application\Service\Image\Builder\BuilderActionInterface;

interface ProcessorInterface
{
    /**
     * @param mixed $image
     * @param BuilderActionInterface $action
     * @return mixed
     */
    public function __invoke(mixed $image, BuilderActionInterface $action): mixed;
}