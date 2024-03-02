<?php
declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\Service\RequestValidator\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class EnumVValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint)
    {
        if (!$value instanceof \UnitEnum) {
            return;
        }

        /** @var \UnitEnum $enum */
        $enum = $constraint->enumClass;
        if (!is_a($enum, \UnitEnum::class, true)) {
            throw new \InvalidArgumentException("The class {$constraint->enumClass} is not an enum.");
        }

        $possibleValues = array_map(fn($case) => $case->value, $enum::cases());
        if (!in_array($value->value ?? null, $possibleValues, true)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value->value)
                ->setParameter('{{ enumClass }}', $constraint->enumClass)
                ->addViolation();
        }
    }
}
