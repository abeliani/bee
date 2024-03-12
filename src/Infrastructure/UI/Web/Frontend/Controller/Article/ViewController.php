<?php

declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\UI\Web\Frontend\Controller\Article;

use Abeliani\Blog\Domain\Exception\NotFoundException;
use Abeliani\Blog\Domain\Repository\Article;
use Abeliani\Blog\Domain\Repository\Category;
use Abeliani\Blog\Domain\Repository\Tag;
use Psr\Http\Message;
use Psr\Http\Server\RequestHandlerInterface;
use Twig\Environment;
use Twig\Error;

readonly class ViewController implements RequestHandlerInterface
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
        if (!($id = $request->getQueryParams()['id'] ?? null) || !$article = $this->repository->find((int) $id)) {
            throw new NotFoundException();
        }

        $this->response->getBody()->write($this->view->render('front/article.twig', [
            'meta_lang' => 'en',
            'canonical' => 'https://localhost',
            'meta_desc' => 'meta description',
            'meta_title' => $article->getTitle(),
            'meta_author' => 'Me',
            'article' => $article,
            'time_to_read' => round(str_word_count($article->getContent()) / 200),
            'last_articles' => $this->repository->findLast(),
            'categories' => $this->categoryRepository->findAll(),
            'tags' => $this->tagRepository->findByArticle($article->getTranslateId()),
            'uploadsDir' => 'uploads',
        ]));
        return $this->response;
    }
}
