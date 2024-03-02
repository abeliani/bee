<?php

namespace Abeliani\Blog\Application\Service\Image\Processor\Handler;

interface ImageHandlerInterface
{
    public function getStream(): mixed;
}