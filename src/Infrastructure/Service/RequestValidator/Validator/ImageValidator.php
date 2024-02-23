<?php

namespace Abeliani\Blog\Infrastructure\Service\RequestValidator\Validator;

use GuzzleHttp\Psr7\UploadedFile;
use Symfony\Component\HttpFoundation\File\UploadedFile as SymfonyUploadedFile;
use \Symfony\Component\Validator\Constraints\ImageValidator as SymfonyImageValidator;

final class ImageValidator extends SymfonyImageValidator
{
    public function validate($value, $constraint): void
    {
        if ($value instanceof UploadedFile) {
            $value = new SymfonyUploadedFile(
                $value->getStream()->getMetadata('uri'),
                $value->getClientFilename(),
                $value->getClientMediaType(),
                $value->getError(),
            );
        }

        parent::validate($value, $constraint);
    }
}