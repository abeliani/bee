<?php
declare(strict_types=1);

namespace Abeliani\Blog\Application\Service\Image\Processor\Handler;

readonly class StreamHandler implements ImageHandlerInterface
{
    private mixed $stream;

    /**
     * @param resource $stream
     * @throws \Exception
     */
    public function __construct(mixed $stream)
    {
        if (ftell($stream) !== 0 || !rewind($stream)) {
            throw new \InvalidArgumentException('Stream cannot be empty');
        }
        if (!($this->stream = tmpfile())) {
            throw new \Exception('Failed to create a temporary stream');
        }
        if (!stream_copy_to_stream($stream, $this->stream) || !rewind($this->stream) || !rewind($stream)) {
            throw new \RuntimeException('Failed to create stream');
        }
    }

    /**
     * @throws \Exception
     */
    public function getStream(): mixed
    {
        return $this->stream;
    }
}
