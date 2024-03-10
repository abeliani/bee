<?php

declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\UI\Web\CPanel\Controller\Article;

use Abeliani\Blog\Application\Enum\AuthRequestAttrs;
use Abeliani\Blog\Application\Middleware\JwtAuthenticationMiddleware;
use Abeliani\Blog\Domain\Enum;
use Abeliani\Blog\Domain\Model\User;
use Abeliani\Blog\Domain\Repository\Category;
use Abeliani\Blog\Infrastructure\Middleware\WithMiddleware;
use Abeliani\Blog\Infrastructure\Service\ArticleService;
use Abeliani\Blog\Infrastructure\Service\Form\FormService;
use Abeliani\Blog\Infrastructure\UI\Form\ArticleForm;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

#[WithMiddleware(JwtAuthenticationMiddleware::class)]
final readonly class CreateController implements RequestHandlerInterface
{
    public function __construct(
        private Environment $view,
        private FormService $formService,
        private ResponseInterface $response,
        private ArticleService $article,
        private Category\ReadRepositoryInterface $categoryRepository,
    ) {
    }

    /**
     * @throws \ImagickException
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws \JsonException
     * @throws \ReflectionException
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $formInspector = $this->formService->buildInspector($request, ArticleForm::class);

        if (!$formInspector->isEmptyForm() && $formInspector->validate()) {
            /** @var ArticleForm $form */
            $form = $formInspector->getForm();
            /** @var User $actor */
            $actor = $request->getAttribute(AuthRequestAttrs::CurrentUser->value);
            $this->article->create($actor, $form);

            return $this->response
                ->withStatus(302)
                ->withHeader('Location', '/cpanel/article');
        }

        foreach ($this->categoryRepository->findAll() as $category) {
            $categories[sprintf('%s (%s)', $category->getTitle(), $category->getStatus()->name)] = $category->getId();
        }

        $render = $this->view->render('cpanel/article_create.twig', array_merge($formInspector->formToArray(), [
            'section' => 'Crete article',
            'action_url' => '/cpanel/article/create',
            'languages' => Enum\Utils::toArray(Enum\Language::class),
            'statuses' => Enum\Utils::toArray(Enum\ArticleStatus::class),
            'categories' => $categories ?? [],
            'errors' => $formInspector->getValidateErrors(),
        ]));

        $this->response->getBody()->write($render);
        return $this->response;
    }
}
