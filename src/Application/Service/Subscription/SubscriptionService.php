<?php

declare(strict_types=1);

namespace Abeliani\Blog\Application\Service\Subscription;

use Abeliani\Blog\Domain\Enum\SubscriptionStatus;
use Abeliani\Blog\Domain\Model\Subscription;
use Abeliani\Blog\Domain\Repository\Subscription\CreateRepositoryInterface;
use Abeliani\Blog\Domain\Repository\Subscription\ReadRepositoryInterface;
use Abeliani\Blog\Domain\Service\Mailer\MailerInterface;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Validation\Constraint\PermittedFor;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Twig\Environment;

final readonly class SubscriptionService
{
    private Configuration $jwtConfig;

    public function __construct(
        string $secret,
        private string $domain,
        private string $appId,
        private string $expiring,
        private Environment $view,
        private MailerInterface $mailer,
        private CreateRepositoryInterface $create,
        private ReadRepositoryInterface $read,
    ) {

        $this->jwtConfig = Configuration::forSymmetricSigner(new Sha256(), InMemory::base64Encoded($secret));
    }

    public function subscribe(string $email): Subscription
    {
        // bad deal to manage already active subscriber
        if ($s = $this->read->findByEmail($email)) {
            // if found but inactive think about sending a new token
            return $s;
        }

        $now = new \DateTimeImmutable;
        $s = new Subscription($email, SubscriptionStatus::Pending, $now);

        $token = $this->jwtConfig->builder()
            ->issuedBy($this->domain)
            ->permittedFor($this->appId)
            ->issuedAt($now)
            ->expiresAt($now->modify($this->expiring))
            ->withClaim('email', $s->getEmail())
            ->getToken($this->jwtConfig->signer(), $this->jwtConfig->signingKey());

        $message = $this->view->render('email/confirm_email_subscription.twig', [
            // fixme dynamic host
            'confirm_url' => sprintf('http://localhost/subscribe/confirm/%s', base64_encode($token->toString())),
        ]);

        $this->mailer->send($s->getEmail(), 'Подтверждение подписки treecode', $message);
        $this->create->save($s);

        return $s;
    }

    public function confirm(string $jwt): bool
    {
        $token = $this->jwtConfig->parser()->parse($jwt);

        if (!($email = $token->claims()->get('email')) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        if (!$s = $this->read->findByEmail($email)) {
            return false;
        }

        $constraints = new \SplFixedArray(2);
        $constraints[0] = new PermittedFor($this->appId);
        $constraints[1] = new SignedWith($this->jwtConfig->signer(), $this->jwtConfig->signingKey());

        if ($this->jwtConfig->validator()->validate($token, ...$constraints)) {
            return $this->create->activate($s) !== null;
        }

        throw new \RuntimeException('Failed to activate subscription');
    }
}
