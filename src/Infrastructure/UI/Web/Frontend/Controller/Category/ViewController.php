<?php

declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\UI\Web\Frontend\Controller\Category;

use Abeliani\Blog\Domain\Enum\ArticleStatus;
use Abeliani\Blog\Domain\Enum\Language;
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
     * @throws Error\SyntaxError|Error\RuntimeError|Error\LoaderError
     * @throws NotFoundException
     */
    public function handle(Message\ServerRequestInterface $request): Message\ResponseInterface
    {
        $id = (int) $request->getQueryParams()['id'];
        $slug = $request->getQueryParams()['slug'];
        $category = $this->categoryRepository->find($id);

        if (!$category || $category->getSlug() !== $slug) {
            throw new NotFoundException('Category not found');
        }

        $this->response->getBody()->write($this->view->render('front/index.twig', [
            'meta_lang' => $category->getLanguage()->value,
            'canonical' => sprintf('%s/category/%d/%s', getenv('APP_HOST'), $category->getId(), $category->getSlug()),
            'meta_title' => $category->getSeoMeta()->getTitle(),
            'meta_desc' => $category->getSeoMeta()->getDescription(),
            'articles' => $this->repository->finaByCategory($category->getId(), 25, ArticleStatus::Published),
            'last_articles' => $this->repository->findLast(),
            'categories' => $this->categoryRepository->findAll(),
            'tags' => $this->tagRepository->findAll(),
        ]));
        return $this->response;
    }
}
