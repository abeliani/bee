<?php

declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\Service;

use Abeliani\Blog\Domain\Service\Mailer\MailerInterface;

final class Mailer implements MailerInterface
{
    private string $address;
    private mixed $socket = null;

    public function __construct(
        private readonly string $host,
        int $port,
        private readonly string $username,
        private readonly string $password,
        private readonly int $timeout = 30,
    ) {
        $this->address = sprintf('tls://%s:%d', $this->host, $port);
    }

    private function connect(): void
    {
        $this->socket = stream_socket_client(
            $this->address, $errCode, $errMessage, $this->timeout, STREAM_CLIENT_CONNECT, stream_context_create()
        );

        if (!$this->socket) {
            throw new \RuntimeException(sprintf('Connection failed: %s (%s)', $errMessage, $errCode));
        }

        $this->execute(sprintf('EHLO %s', $this->host));
        $this->execute('STARTTLS');
    }

    private function execute(string $command): string
    {
        // todo check false
        fputs($this->socket,  "{$command}\r\n");
        $response = '';
        while ($str = fgets($this->socket, 512)) {
            $response .= $str;
            if (substr($str, 3, 1) == ' ') {
                break;
            }
        }
        // todo check response
        return $response;
    }

    private function authenticate(): void
    {
        $this->execute('AUTH LOGIN');
        $this->execute(base64_encode($this->username));
        $this->execute(base64_encode($this->password));
    }

    public function send(string $to, string $subject, string $body): bool
    {
        $this->connect();
        $this->authenticate();

        if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
            throw new \RuntimeException('Invalid email address');
        }

        $to = trim(preg_replace('/[\x00-\x1F\x7F]/', '', $to));
        $subject = trim(preg_replace('/[\r\n]/', '', $subject));
        $body = trim(str_replace("\n", "\r\n", $body));
        $body = preg_replace('/^\./m', '..', $body);

        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $headers .= "From: master@treecode.ru\r\n"; // fixme dynamic
        $headers .= "To: $to\r\n";
        $headers .= "Subject: $subject\r\n";
        $message = "{$headers}\r\n{$body}";

        $this->execute(sprintf('MAIL FROM: <%s>', $this->username));
        $this->execute(sprintf('RCPT TO: <%s>', $to));
        $this->execute('DATA');
        $this->execute("{$message}\r\n.");
        $this->execute('QUIT');

        return fclose($this->socket);
    }

    public function __destruct()
    {
        // if send() wasn't called or fail
        is_resource($this->socket) and fclose($this->socket);
    }
}
