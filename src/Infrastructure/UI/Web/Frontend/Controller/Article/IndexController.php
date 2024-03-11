<?php

declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\UI\Web\Frontend\Controller\Article;

use Abeliani\Blog\Domain\Enum\ArticleStatus;
use Abeliani\Blog\Domain\Repository\Article;
use Abeliani\Blog\Domain\Repository\Category;
use Abeliani\Blog\Domain\Repository\Tag;
use Psr\Http\Message;
use Psr\Http\Server\RequestHandlerInterface;
use Twig\Environment;
use Twig\Error;

readonly class IndexController implements RequestHandlerInterface
{
    public function __construct(
        private Environment $view,
        private Message\ResponseInterface $response,
        private Article\ReadRepositoryInterface $repository,
        private Category\ReadRepositoryInterface $categoryRepository,
        private Tag\ReadRepositoryInterface $tagRepository,
    ) {
    }

    /**
     * @throws Error\SyntaxError
     * @throws Error\RuntimeError
     * @throws Error\LoaderError
     */
    public function handle(Message\ServerRequestInterface $request): Message\ResponseInterface
    {
        $this->response->getBody()->write($this->view->render('front/index.twig', [
            'meta_lang' => 'en',
            'canonical' => 'https://localhost',
            'meta_desc' => 'meta description',
            'meta_title' => 'Hello, world!',
            'meta_author' => 'Me',
            'articles' => $this->repository->findAll(ArticleStatus::Published),
            'last_articles' => $this->repository->findLast(),
            'categories' => $this->categoryRepository->findAll(),
            'tags' => $this->tagRepository->findAll(),
            'uploadsDir' => 'uploads',
        ]));
        return $this->response;
    }
}
