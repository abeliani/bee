<?php

declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\Service\RequestValidator\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\EmailValidator;

class EmailMxValidator extends EmailValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if ($value === null || $value === '') {
            return;
        }

        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->setCode(Email::INVALID_FORMAT_ERROR)
                ->addViolation();
        }

        $domain = substr(strrchr($value, "@"), 1);
        if (!checkdnsrr($domain, 'MX')) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
