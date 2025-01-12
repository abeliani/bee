<?php

declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\UI\Web\Frontend\Form;

use Abeliani\Blog\Domain\Interface\ToArrayInterface;
use Abeliani\Blog\Infrastructure\Service\RequestValidator\Validator\EmailMx;
use Abeliani\Blog\Infrastructure\Service\RequestValidator\Validator\Recaptcha;
use Symfony\Component\Validator\Constraints as Assert;

final class SubscribeForm implements ToArrayInterface
{
    #[Assert\NotBlank]
    #[Assert\Email]
    #[EmailMx]
    private string $subscriber;

    #[Assert\NotBlank]
    #[Recaptcha]
    private string $rc;

    public function getSubscriber(): string
    {
        return $this->subscriber;
    }

    public function getRc(): string
    {
        return $this->rc;
    }

    public function toArray(): array
    {
        return [
            'rc' => $this->rc,
            'subscriber' => $this->subscriber,
        ];
    }
}
