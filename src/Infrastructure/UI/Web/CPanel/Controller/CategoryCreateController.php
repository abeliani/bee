<?php declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\UI\Web\CPanel\Controller;

use Abeliani\Blog\Application\Enum\AuthRequestAttrs;
use Abeliani\Blog\Application\Middleware\JwtAuthenticationMiddleware;
use Abeliani\Blog\Domain\Enum\CategoryStatus;
use Abeliani\Blog\Domain\Enum\EnumUtils;
use Abeliani\Blog\Domain\Enum\Languages;
use Abeliani\Blog\Domain\Exception\CategoryException;
use Abeliani\Blog\Domain\Model\User;
use Abeliani\Blog\Infrastructure\Middleware\WithMiddleware;
use Abeliani\Blog\Infrastructure\Service\CategoryService;
use Abeliani\Blog\Infrastructure\Service\Form\FormService;
use Abeliani\Blog\Infrastructure\UI\Form\CategoryForm;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

#[WithMiddleware(JwtAuthenticationMiddleware::class)]
final readonly class CategoryCreateController implements RequestHandlerInterface
{
    public function __construct(
        private Environment $view,
        private FormService $formService,
        private ResponseInterface $response,
        private CategoryService $category,
    ) {
    }

    /**
     * @throws \ImagickException
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws \JsonException
     * @throws \ReflectionException
     * @throws CategoryException
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $formInspector = $this->formService->buildInspector($request, CategoryForm::class);

        if (!$formInspector->isEmptyForm() && $formInspector->validate()) {
            /** @var CategoryForm $form */
            $form = $formInspector->getForm();
            /** @var User $actor */
            $actor = $request->getAttribute(AuthRequestAttrs::CurrentUser->value);
            $this->category->create($actor, $form);
        }

        $render = $this->view->render('cpanel/category_create.twig', array_merge($formInspector->formToArray(), [
            'section' => 'Crete category',
            'action_url' => '/cpanel/category/create',
            'languages' => EnumUtils::toArray(Languages::class),
            'statuses' => EnumUtils::toArray(CategoryStatus::class),
            'errors' => $formInspector->getValidateErrors(),
        ]));

        $this->response->getBody()->write($render);
        return $this->response;
    }
}
