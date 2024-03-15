<?php

namespace Abeliani\Blog\Domain\Service\Mailer;

interface MailerInterface
{
    public function send(string $to, string $subject, string $body): bool;
}