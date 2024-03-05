<?php declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\UI\Web\CPanel\Controller;

use Abeliani\Blog\Application\Enum\AuthRequestAttrs;
use Abeliani\Blog\Application\Middleware\JwtAuthenticationMiddleware;
use Abeliani\Blog\Domain\Enum\CategoryStatus;
use Abeliani\Blog\Domain\Enum\Language;
use Abeliani\Blog\Domain\Enum\Utils;
use Abeliani\Blog\Domain\Exception\CategoryException;
use Abeliani\Blog\Domain\Exception\InvalidEntityException;
use Abeliani\Blog\Domain\Exception\NotFoundException;
use Abeliani\Blog\Domain\Model\User;
use Abeliani\Blog\Domain\Repository\Category\ReadCategoryRepositoryInterface;
use Abeliani\Blog\Infrastructure\Middleware\WithMiddleware;
use Abeliani\Blog\Infrastructure\Persistence\Mapper\CategoryMapper;
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
final readonly class CategoryUpdateController implements RequestHandlerInterface
{
    public function __construct(
        private Environment                     $view,
        private FormService                     $formService,
        private ResponseInterface               $response,
        private CategoryService                 $category,
        private ReadCategoryRepositoryInterface $repository,
        private CategoryMapper                  $mapper,
    )
    {
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws \JsonException
     * @throws \ReflectionException
     * @throws NotFoundException|CategoryException
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $formInspector = $this->formService->buildInspector($request, CategoryForm::class);
        /** @var CategoryForm $form */
        $form = $formInspector->getForm();
        /** @var User $actor */
        $actor = $request->getAttribute(AuthRequestAttrs::CurrentUser->value);

        if (!$formInspector->validate('id')
            || !($category = $this->repository->find($form->getId(), $actor->getId()))) {
            throw new NotFoundException('Category not found');
        }

        if (!$formInspector->isEmptyForm() && $formInspector->validate()) {
            $this->category->update($actor, $category, $form);

            return $this->response
                ->withStatus(302)
                ->withHeader('Location', '/cpanel/category');
        }

        if ($formInspector->isEmptyForm()) {
            $formData = $this->mapper->mapToFormData($category);

            $render = $this->view->render('cpanel/category_create.twig', array_merge($formData, [
                'section' => 'Update category',
                'action_url' => '/cpanel/category/update/' . $form->getId(),
                'languages' => Utils::toArray(Language::class),
                'statuses' => Utils::toArray(CategoryStatus::class),
                'errors' => $formInspector->getValidateErrors(),
            ]));

            $this->response->getBody()->write($render);
            return $this->response;
        }

        throw new InvalidEntityException('Wrong category update request');
    }
}
