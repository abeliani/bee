<?php
declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\Service\RequestValidator;

final class FromInspector
{
    private array $errors = [];

    public function __construct(
        private readonly object $form,
        private readonly bool $isEmptyForm,
        private readonly \Closure $validaor,
        private readonly \Closure $converter,
    ) {
    }

    public function getForm(): object
    {
        return $this->form;
    }

    public function formToArray(): array
    {
        return $this->converter->__invoke($this->form);
    }

    public function validate(): bool
    {
        $this->errors = $this->validaor->__invoke($this->form);
        return $this->hasError();
    }

    public function getValidateErrors(): array
    {
        return $this->errors;
    }

    public function hasError(): bool
    {
        return !empty($this->errors);
    }

    public function isEmptyForm(): bool
    {
        return $this->isEmptyForm;
    }
}
