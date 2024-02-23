<?php

namespace Abeliani\Blog\Infrastructure\Service\Form;

use Abeliani\Blog\Infrastructure\Service\Hydrator;
use Abeliani\Blog\Infrastructure\Service\RequestValidator\FromInspector;
use Abeliani\Blog\Infrastructure\Service\RequestValidator\RequestValidatorService;
use Psr\Http\Message\ServerRequestInterface;

final class FormService
{
    public function __construct(
        private readonly Hydrator $hydrator,
        private readonly RequestValidatorService $validator,
    )
    {
    }

    public function buildInspector(ServerRequestInterface|array $data, string $formClass): FromInspector
    {
        if (!is_array($data)) {
            $data = array_merge_recursive($data->getParsedBody(), $data->getUploadedFiles());
        }

        return new FromInspector(
            $this->hydrate($data, $formClass),
            empty($data),
            fn ($form) => $this->validator->validate($form),
            fn ($form) => $this->hydrator->convert($form)
        );
    }

    private function hydrate(array $data, string $formClass): object
    {
        $form = new $formClass;

        empty($data) ?
            $this->hydrator->hydrateEmpty($form) :
            $this->hydrator->hydrate($data, $form);

        return $form;
    }
}
