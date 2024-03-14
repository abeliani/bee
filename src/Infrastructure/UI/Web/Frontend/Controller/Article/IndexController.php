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
        $limit = 10;
        $cursor = $request->getQueryParams();
        $forwardLimitId = $backwardLimitId = 0;

        if (isset($cursor['id']) && isset($cursor['sign'])) {
            $direction = $cursor['sign'] === '+' ? 1 : 0;
            $articles = $this->repository->findByCursor(
                (int) $cursor['id'], $direction, $limit, ArticleStatus::Published
            );

            if (!$direction) {
                $backwardLimitId = $this->repository->findLastId();
            }
        } else {
            $articles = $this->repository->findAll($limit, ArticleStatus::Published);
        }

        if (!empty($direction) || empty($cursor)) {
            $forwardLimitId = $this->repository->findFirstId();
        }

        $pager = new \SplFixedArray(2);
        foreach ($articles as $article) {
            if (!empty($cursor) && $pager[0] === null) {
                $pager[0] = $backwardLimitId === $article->getId() ? 0 : $article->getId();
                continue;
            }
            if ($articles->count() === $articles->key() + 1) {
                $pager[1] = $forwardLimitId === $article->getId() ? 0 : $article->getId();
            }
        }

        $this->response->getBody()->write($this->view->render('front/index.twig', [
            'meta_lang' => 'ru',
            'canonical' => 'https://localhost',
            'meta_desc' => 'meta description',
            'meta_title' => 'Hello, world!',
            'meta_author' => 'Me',
            'articles' => $articles,
            'last_articles' => $this->repository->findLast(),
            'categories' => $this->categoryRepository->findAll(),
            'tags' => $this->tagRepository->findAll(),
            'pager' => $pager,
            'uploadsDir' => 'uploads',
        ]));
        return $this->response;
    }
}
