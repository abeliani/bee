<?php
declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\Service;

use Abeliani\Blog\Domain\Model\User;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\UnencryptedToken;
use Lcobucci\JWT\Validation\Constraint\PermittedFor;
use Lcobucci\JWT\Validation\Constraint\SignedWith;

class JWTAuthentication
{
    private Configuration $configuration;
    private \SplFixedArray $constraints;
    private ?UnencryptedToken $token = null;

    public function __construct(
        private readonly string $secret,
        private readonly string $domain,
        private readonly string $appId,
        private readonly string $expiring,
        private readonly string $tokenName,
    ) {
        $this->configuration = Configuration::forSymmetricSigner(new Sha256(), InMemory::base64Encoded($this->secret));
        $this->constraints = new \SplFixedArray(2);
        $this->constraints[0] = new PermittedFor($this->appId);
        $this->constraints[1] = new SignedWith($this->configuration->signer(), $this->configuration->verificationKey());
    }

    public function generateToken(User $user): self
    {
        $this->token = $this->createToken($user->getId(),$this->expiring);
        return $this;
    }

    public function generateTokenWIthExpiring(User $user, string $expiring): self
    {
        $this->token = $this->createToken($user->getId(), $expiring);
        return $this;
    }

    public function getFromBearer(?string $header): ?UnencryptedToken
    {
        return $this->parse(str_replace('Bearer ', '', $header));
    }

    public function getFromCookie(): ?UnencryptedToken
    {
        return $this->parse($_COOKIE[$this->tokenName] ?? '');
    }

    public function deleteToken(): bool
    {
        return setcookie($this->tokenName, '', time(), '/');
    }

    public function setToCookie(): bool
    {
        return setcookie($this->tokenName, $this->token->toString(), [
            'path' => '/',
            'domain' => $this->token->claims()->get('iss'),
            'expires' => $this->token->claims()->get('exp')->getTimestamp(),
            #'secure' => true,
            'httponly' => true,
            'samesite' => 'Lax'
        ]);
    }

    private function createToken(int $uid, string $expiring): UnencryptedToken {
        $now = new \DateTimeImmutable();
        $token = $this->configuration->builder()
            ->issuedAt($now)
            ->issuedBy($this->domain)
            ->permittedFor($this->appId)
            ->expiresAt($now->modify($expiring))
            ->withClaim('uid', $uid);

        return $token->getToken(
            $this->configuration->signer(),
            $this->configuration->signingKey()
        );
    }

    private function parse(string $token): ?UnencryptedToken
    {
        if ($token) {
            $parsed = $this->configuration->parser()->parse($token);
            if ($this->configuration->validator()->validate($parsed, ...$this->constraints)) {
                return $parsed;
            }
        }

        return null;
    }
}
