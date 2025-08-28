<?php

namespace Demv\JSend\Test;

use Psr\Http\Message\StreamInterface;

/**
 * Class DummyStream
 * @package Demv\JSend\Test
 */
final class DummyStream implements StreamInterface
{
    private string $content;

    public function __construct(string $content)
    {
        $this->content = $content;
    }

    public function __toString(): string
    {
        return $this->getContents();
    }

    public function close(): void
    {
        // TODO: Implement close() method.
    }

    public function detach()
    {
        // TODO: Implement detach() method.
        return null;
    }

    public function getSize(): ?int
    {
        // TODO: Implement getSize() method.
        return null;
    }

    public function tell(): int
    {
        // TODO: Implement tell() method.
        return 0;
    }

    public function eof(): bool
    {
        // TODO: Implement eof() method.
        return false;
    }

    public function isSeekable(): bool
    {
        // TODO: Implement isSeekable() method.
        return false;
    }

    public function seek(int $offset, int $whence = SEEK_SET): void
    {
        // TODO: Implement seek() method.
    }

    public function rewind(): void
    {
        // TODO: Implement rewind() method.
    }

    public function isWritable(): bool
    {
        // TODO: Implement isWritable() method.
        return false;
    }

    public function write(string $string): int
    {
        // TODO: Implement write() method.
        return 0;
    }

    public function isReadable(): bool
    {
        // TODO: Implement isReadable() method.
        return true;
    }

    public function read(int $length): string
    {
        // TODO: Implement read() method.
        return '';
    }

    public function getContents(): string
    {
        return $this->content;
    }

    public function getMetadata($key = null)
    {
        // TODO: Implement getMetadata() method.
        return null;
    }
}
