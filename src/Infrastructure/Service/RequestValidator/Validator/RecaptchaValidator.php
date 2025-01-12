<?php

declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\Service\RequestValidator\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class RecaptchaValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if ($value === null || $value === '') {
            return;
        }

        $url = sprintf(
            'https://www.google.com/recaptcha/api/siteverify?secret=%s&response=%s',
            '6LfQG7QqAAAAAL-woiB0TcYb8WSvCdo2xiHGNMf4', // pass as param
            $value,
        );
        // fixme the request must be separated from client validate
        $response = file_get_contents($url);
        $responseKeys = json_decode($response, true);

        if (empty($responseKeys['success']) || $responseKeys['score'] < 0.6) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
