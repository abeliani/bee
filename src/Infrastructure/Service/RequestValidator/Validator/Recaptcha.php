<?php

declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\Service\RequestValidator\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class Recaptcha extends Constraint
{
    public function __construct(
        public string $message = 'Please, confirm you are not a bot',
        $options = null,
        $groups = null,
        $payload = null
    ) {
        parent::__construct($options, $groups, $payload);
    }
}
