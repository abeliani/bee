<?php

declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\UI\Web\Frontend\Controller\Tag;

use Abeliani\Blog\Domain\Exception\NotFoundException;
use Abeliani\Blog\Domain\Repository\Article;
use Abeliani\Blog\Domain\Repository\Category;
use Abeliani\Blog\Domain\Repository\Tag;
use Abeliani\Blog\Domain\Service\TransliteratorBijective;
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
        private TransliteratorBijective $bijective,
    ) {
    }

    /**
     * @throws Error\SyntaxError|Error\RuntimeError|Error\LoaderError
     * @throws NotFoundException
     */
    public function handle(Message\ServerRequestInterface $request): Message\ResponseInterface
    {
        $name = $request->getQueryParams()['name'];
        $tag = $this->tagRepository->find((int) $request->getQueryParams()['id']);

        if (!$tag || ($tag->getName() !== $name) && ($tag->getName() !== $this->bijective->toRu($name))) {
            throw new NotFoundException('Tag not found');
        }

        $this->response->getBody()->write($this->view->render('front/index.twig', [
            'meta_lang' => getenv('APP_LANG'),
            'canonical' => sprintf('%s/tag/%d/%s', getenv('APP_HOST'), $tag->getId(), $name),
            'meta_title' => sprintf('%s | статьи по тегу', $metaName = ucfirst($tag->getName())),
            'meta_desc' => sprintf('Список статей по тегу %s', $metaName),
            'articles' => $this->repository->findByTagId($tag->getId()),
            'last_articles' => $this->repository->findLast(),
            'categories' => $this->categoryRepository->findAll(),
            'tags' => $this->tagRepository->findAll(),
        ]));

        return $this->response;
    }
}
