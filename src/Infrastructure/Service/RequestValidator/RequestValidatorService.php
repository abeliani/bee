<?php
declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\Service\RequestValidator;

use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final readonly class RequestValidatorService
{
    public function __construct(private ValidatorInterface $validator)
    {
    }

    public function validate(object $form, ?string $field): array
    {
        $violations = $field
            ? $this->validator->validateProperty($form, $field)
            : $this->validator->validate($form);

        /** @var ConstraintViolationInterface $violation */
        foreach ($violations as $violation) {
            $formPath = '';
            $path = $violation->getPropertyPath();
            // Convert ['field.nest' => 'message'] to ['field[nest]' => 'message']
            foreach (explode('.', $path) as $part) {
                if ($formPath === '') {
                    $formPath = $part;
                    continue;
                }
                $formPath .= "[$part]";
            }
            $errors[$formPath] = $violation->getMessage();
        }

        return $errors ?? [];
    }
}