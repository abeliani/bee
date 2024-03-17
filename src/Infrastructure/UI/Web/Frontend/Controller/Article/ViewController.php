<?php

declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\UI\Web\Frontend\Controller\Article;

use Abeliani\Blog\Domain\Exception\NotFoundException;
use Abeliani\Blog\Domain\Repository;
use Abeliani\Blog\Domain\Model\Category;
use Psr\Http\Message;
use Psr\Http\Server\RequestHandlerInterface;
use Twig\Environment;
use Twig\Error;

readonly class ViewController implements RequestHandlerInterface
{
    public function __construct(
        private Environment $view,
        private Message\ResponseInterface $response,
        private Repository\Article\ReadRepositoryInterface $repository,
        private Repository\Category\ReadRepositoryInterface $categoryRepository,
        private Repository\Tag\ReadRepositoryInterface $tagRepository,
    ) {
    }

    /**
     * @throws Error\SyntaxError|Error\RuntimeError|Error\LoaderError
     * @throws NotFoundException
     */
    public function handle(Message\ServerRequestInterface $request): Message\ResponseInterface
    {
        if (!($id = $request->getQueryParams()['id'] ?? null) || !$article = $this->repository->find((int) $id)) {
            throw new NotFoundException();
        }

        $categories = $this->categoryRepository->findAll();

        $this->response->getBody()->write($this->view->render('front/article.twig', [
            'meta_lang' => $article->getLanguage()->value,
            'meta_title' => $article->getSeoMeta()->getTitle(),
            'meta_desc' => $article->getSeoMeta()->getDescription(),
            'canonical' => sprintf('%s/article/%d/%s', getenv('APP_HOST'), $article->getId(), $article->getSlug()),
            'article' => $article,
            'last_articles' => $this->repository->findLast(),
            'category' => $categories->stream()
                ->filter(fn (Category $c) => $c->getId() === $article->getCategoryId())
                ->getCollection()
                ->current(),
            'categories' => $categories,
            'tags' => $this->tagRepository->findByArticle($article->getTranslateId()),
        ]));
        return $this->response;
    }
}
