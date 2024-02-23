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

    public function validate(object $form): array
    {
        /** @var ConstraintViolationInterface $violation */
        foreach ($this->validator->validate($form) as $violation) {
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