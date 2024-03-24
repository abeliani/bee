<?php

declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\UI\Web\Frontend\Controller\Redirector;

use Abeliani\Blog\Domain\Exception\NotFoundException;
use Abeliani\Blog\Infrastructure\Service\Redirector;
use GuzzleHttp\Psr7\Utils;
use Psr\Http\Message;
use Psr\Http\Server\RequestHandlerInterface;
use Twig\Environment;
use Twig\Error;

readonly class ViewController implements RequestHandlerInterface
{
    public function __construct(
        private Environment $view,
        private Redirector $redirector,
        private Message\ResponseInterface $response,
        private int $redirectDelay,
    ) {
    }

    /**
     * @throws Error\SyntaxError|Error\RuntimeError|Error\LoaderError
     * @throws NotFoundException
     */
    public function handle(Message\ServerRequestInterface $request): Message\ResponseInterface
    {
        if (!$redirect = $this->redirector->get($request->getQueryParams()['hash'])) {
            throw new NotFoundException('Redirect url not found');
        }

        $url = sprintf('%s://%s', $redirect->getProtocol()->name, $redirect->getPath());

        if ($redirect->isFast()) {
            return $this->response
                ->withStatus(302)
                ->withHeader('Location', $url);
        }

        $render = $this->view->render('front/redirector.twig', [
            'url' => $url,
            'delay' => $this->redirectDelay,
        ]);

        return $this->response
            ->withStatus(200)
            ->withBody(Utils::streamFor($render));
    }
}
