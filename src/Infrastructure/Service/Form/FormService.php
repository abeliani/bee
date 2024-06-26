<?php

declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\Service\Form;

use Abeliani\Blog\Infrastructure\Service\Hydrator;
use Abeliani\Blog\Infrastructure\Service\RequestValidator\FromInspector;
use Abeliani\Blog\Infrastructure\Service\RequestValidator\RequestValidatorService;
use Psr\Http\Message\ServerRequestInterface;

final readonly class FormService
{
    public function __construct(
        private Hydrator $hydrator,
        private RequestValidatorService $validator,
    ) {
    }

    /**
     * @throws \JsonException
     * @throws \ReflectionException
     */
    public function buildInspector(ServerRequestInterface|array $data, string $formClass): FromInspector
    {
        if (!is_array($data)) {
            $query = $data->getQueryParams();
            $data = array_merge_recursive($data->getParsedBody(), $data->getUploadedFiles());
        }

        return new FromInspector(
            $this->hydrate(array_merge($data, $query ?? []), $formClass),
            empty($data), // fixme. empty form is empty if empty form object but not empty request data
            fn (object $form, ?string $field) => $this->validator->validate($form, $field),
            fn (object $form) => $this->hydrator->convert($form)
        );
    }

    /**
     * @throws \JsonException
     * @throws \ReflectionException
     */
    private function hydrate(array $data, string $formClass): object
    {
        $form = new $formClass;

        empty($data) ?
            $this->hydrator->hydrateEmpty($form) :
            $this->hydrator->hydrate($data, $form);

        return $form;
    }
}
