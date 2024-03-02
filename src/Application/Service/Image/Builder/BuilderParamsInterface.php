<?php

namespace Abeliani\Blog\Application\Service\Image\Builder;

interface BuilderParamsInterface
{
    public function getUploadDir(): string;
    public function getOriginalExtension(): string;
    public function getProcessedExtension(): string;
}
