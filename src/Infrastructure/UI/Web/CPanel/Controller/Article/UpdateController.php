<?php

declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\UI\Web\CPanel\Controller\Article;

use Abeliani\Blog\Application\Enum\AuthRequestAttrs;
use Abeliani\Blog\Application\Middleware\JwtAuthenticationMiddleware;
use Abeliani\Blog\Domain\Enum;
use Abeliani\Blog\Domain\Exception\ArticleException;
use Abeliani\Blog\Domain\Exception\InvalidEntityException;
use Abeliani\Blog\Domain\Exception\NotFoundException;
use Abeliani\Blog\Domain\Model\User;
use Abeliani\Blog\Domain\Repository\Article\ReadRepositoryInterface;
use Abeliani\Blog\Infrastructure\Middleware\WithMiddleware;
use Abeliani\Blog\Infrastructure\Persistence\Mapper\ArticleMapper;
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
final readonly class UpdateController implements RequestHandlerInterface
{
    public function __construct(
        private Environment             $view,
        private FormService             $formService,
        private ResponseInterface       $response,
        private ArticleService          $article,
        private ReadRepositoryInterface $repository,
        private ArticleMapper           $mapper,
    ) {
    }

    /**
     * @throws ArticleException
     * @throws RuntimeError
     * @throws LoaderError
     * @throws \JsonException
     * @throws \ImagickException
     * @throws SyntaxError
     * @throws NotFoundException
     * @throws \ReflectionException
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $formInspector = $this->formService->buildInspector($request, ArticleForm::class);
        /** @var ArticleForm $form */
        $form = $formInspector->getForm();
        /** @var User $actor */
        $actor = $request->getAttribute(AuthRequestAttrs::CurrentUser->value);

        if (!$formInspector->validate('id')
            || !($article = $this->repository->find($form->getId(), $actor->getId()))) {
            throw new NotFoundException('Article not found');
        }

        if (!$formInspector->isEmptyForm() && $formInspector->validate()) {
            $this->article->update($actor, $article, $form);

            return $this->response
                ->withStatus(302)
                ->withHeader('Location', '/cpanel/article');
        }

        if ($formInspector->isEmptyForm()) {
            $formData = $this->mapper->mapToFormData($article);

            $render = $this->view->render('cpanel/article_create.twig', array_merge($formData, [
                'section' => 'Update article',
                'action_url' => '/cpanel/article/update/' . $form->getId(),
                'languages' => Enum\Utils::toArray(Enum\Language::class),
                'statuses' => Enum\Utils::toArray(Enum\ArticleStatus::class),
                'errors' => $formInspector->getValidateErrors(),
            ]));

            $this->response->getBody()->write($render);
            return $this->response;
        }

        throw new InvalidEntityException('Wrong article update request');
    }
}
