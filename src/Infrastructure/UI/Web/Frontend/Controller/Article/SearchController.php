<?php

declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\UI\Web\Frontend\Controller\Article;

use Abeliani\Blog\Domain\Repository\Article;
use Abeliani\Blog\Domain\Repository\Category;
use Abeliani\Blog\Domain\Repository\Tag;
use Psr\Http\Message;
use Psr\Http\Server\RequestHandlerInterface;
use Twig\Environment;
use Twig\Error;

readonly class SearchController implements RequestHandlerInterface
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
        if ($term = $request->getQueryParams()['term'] ?? null) {
            $articles = $this->repository->search($term, 10);
        }

        $this->response->getBody()->write($this->view->render('front/index.twig', [
            'meta_lang' => getenv('APP_LANG'),
            'canonical' => sprintf('%s/article/search?term=%s', getenv('APP_HOST'), $term),
            'meta_desc' => getenv('SITE_NAME'),
            'meta_title' => sprintf('%s | статьи по фразе', $metaName = ucfirst(strtolower($term))),
            'articles' => $articles ?? [],
            'last_articles' => $this->repository->findLast(),
            'categories' => $this->categoryRepository->findAll(),
            'tags' => $this->tagRepository->findAll(),
        ]));
        return $this->response;
    }
}
