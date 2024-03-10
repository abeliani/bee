<?php

declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\UI\Web\CPanel\Controller\Article;

use Abeliani\Blog\Application\Enum\AuthRequestAttrs;
use Abeliani\Blog\Application\Middleware\JwtAuthenticationMiddleware;
use Abeliani\Blog\Domain\Enum;
use Abeliani\Blog\Domain\Exception;
use Abeliani\Blog\Domain\Model\User;
use Abeliani\Blog\Domain\Repository\Article;
use Abeliani\Blog\Domain\Repository\Category;
use Abeliani\Blog\Infrastructure\Middleware\WithMiddleware;
use Abeliani\Blog\Infrastructure\Persistence\Mapper\ArticleMapper;
use Abeliani\Blog\Infrastructure\Service\ArticleService;
use Abeliani\Blog\Infrastructure\Service\Form\FormService;
use Abeliani\Blog\Infrastructure\UI\Form\ArticleForm;
use Psr\Http\Message;
use Psr\Http\Server\RequestHandlerInterface;
use Twig\Environment;
use Twig\Error;

#[WithMiddleware(JwtAuthenticationMiddleware::class)]
final readonly class UpdateController implements RequestHandlerInterface
{
    public function __construct(
        private Environment               $view,
        private FormService               $formService,
        private Message\ResponseInterface $response,
        private ArticleService            $article,
        private Article\ReadRepositoryInterface $repository,
        private Category\ReadRepositoryInterface $categoryRepository,
        private ArticleMapper             $mapper,
    ) {
    }

    /**
     * @throws Exception\ArticleException
     * @throws Error\RuntimeError
     * @throws Error\LoaderError
     * @throws \JsonException
     * @throws \ImagickException
     * @throws Error\SyntaxError
     * @throws Exception\NotFoundException
     * @throws \ReflectionException
     */
    public function handle(Message\ServerRequestInterface $request): Message\ResponseInterface
    {
        $formInspector = $this->formService->buildInspector($request, ArticleForm::class);
        /** @var ArticleForm $form */
        $form = $formInspector->getForm();
        /** @var User $actor */
        $actor = $request->getAttribute(AuthRequestAttrs::CurrentUser->value);

        if (!$formInspector->validate('id')
            || !($article = $this->repository->findByAuthor($form->getId(), $actor->getId()))) {
            throw new Exception\NotFoundException('Article not found');
        }

        if (!$formInspector->isEmptyForm() && $formInspector->validate()) {
            $this->article->update($actor, $article, $form);

            return $this->response
                ->withStatus(302)
                ->withHeader('Location', '/cpanel/article');
        }

        if ($formInspector->isEmptyForm()) {
            $form = $this->mapper->mapToFormData($article);
        } else {
            $form = $form->toArray();
        }

        foreach ($this->categoryRepository->findAll() as $category) {
            $categories[sprintf('%s (%s)', $category->getTitle(), $category->getStatus()->name)] = $category->getId();
        }

        $render = $this->view->render('cpanel/article_create.twig', array_merge($form, [
            'section' => 'Update article',
            'action_url' => '/cpanel/article/update/' . $form['id'],
            'upload_dir' => '/uploads',
            'categories' => $categories ?? [],
            'languages' => Enum\Utils::toArray(Enum\Language::class),
            'statuses' => Enum\Utils::toArray(Enum\ArticleStatus::class),
            'errors' => $formInspector->getValidateErrors(),
        ]));

        $this->response->getBody()->write($render);
        return $this->response;
    }
}
