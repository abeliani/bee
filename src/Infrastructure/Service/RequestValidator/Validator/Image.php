<?php

namespace Abeliani\Blog\Infrastructure\Service\RequestValidator\Validator;

use Symfony\Component\Validator\Constraints\Image as SymfonyImage;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
final class Image extends SymfonyImage
{

}