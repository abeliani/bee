<?php

declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\UI\Web\CPanel\Controller;

use Abeliani\Blog\Application\Enum\AuthRequestAttrs;
use Abeliani\Blog\Application\Middleware;
use Abeliani\Blog\Domain\Enum;
use Abeliani\Blog\Domain\Exception\CategoryException;
use Abeliani\Blog\Domain\Model\User;
use Abeliani\Blog\Infrastructure\Middleware\WithMiddleware;
use Abeliani\Blog\Infrastructure\Service\CategoryService;
use Abeliani\Blog\Infrastructure\Service\Form\FormService;
use Abeliani\Blog\Infrastructure\UI\Form\CategoryForm;
use Psr\Http\Message;
use Psr\Http\Server\RequestHandlerInterface;
use Twig\Environment;
use Twig\Error;

#[WithMiddleware(Middleware\CsrfCheckMiddleware::class)]
#[WithMiddleware(Middleware\JwtAuthenticationMiddleware::class)]
final readonly class CategoryCreateController implements RequestHandlerInterface
{
    public function __construct(
        private Environment $view,
        private FormService $formService,
        private Message\ResponseInterface $response,
        private CategoryService $category,
    ) {
    }

    /**
     * @throws \ImagickException
     * @throws Error\SyntaxError
     * @throws Error\RuntimeError
     * @throws Error\LoaderError
     * @throws \JsonException
     * @throws \ReflectionException
     * @throws CategoryException
     */
    public function handle(Message\ServerRequestInterface $request): Message\ResponseInterface
    {
        $formInspector = $this->formService->buildInspector($request, CategoryForm::class);

        if (!$formInspector->isEmptyForm() && $formInspector->validate()) {
            /** @var CategoryForm $form */
            $form = $formInspector->getForm();
            /** @var User $actor */
            $actor = $request->getAttribute(AuthRequestAttrs::CurrentUser->value);
            $this->category->create($actor, $form);

            return $this->response
                ->withStatus(302)
                ->withHeader('Location', '/cpanel/category');
        }

        $render = $this->view->render('cpanel/category_create.twig', array_merge($formInspector->formToArray(), [
            'section' => 'Crete category',
            'action_url' => '/cpanel/category/create',
            'languages' => Enum\Utils::toArray(Enum\Language::class),
            'statuses' => Enum\Utils::toArray(Enum\CategoryStatus::class),
            'errors' => $formInspector->getValidateErrors(),
        ]));

        $this->response->getBody()->write($render);
        return $this->response;
    }
}
