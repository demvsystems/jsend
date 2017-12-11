<?php

namespace Demv\JSend\Test;

use Psr\Http\Message\StreamInterface;

/**
 * Class DummyStream
 * @package Demv\JSend\Test
 */
final class DummyStream implements StreamInterface
{
    private $content;

    public function __construct(string $content)
    {
        $this->content = $content;
    }

    public function __toString()
    {
        return $this->getContents();
    }

    public function close(): void
    {
        // TODO: Implement close() method.
    }

    public function detach(): void
    {
        // TODO: Implement detach() method.
    }

    public function getSize(): void
    {
        // TODO: Implement getSize() method.
    }

    public function tell(): void
    {
        // TODO: Implement tell() method.
    }

    public function eof(): void
    {
        // TODO: Implement eof() method.
    }

    public function isSeekable(): void
    {
        // TODO: Implement isSeekable() method.
    }

    public function seek($offset, $whence = SEEK_SET): void
    {
        // TODO: Implement seek() method.
    }

    public function rewind(): void
    {
        // TODO: Implement rewind() method.
    }

    public function isWritable(): void
    {
        // TODO: Implement isWritable() method.
    }

    public function write($string): void
    {
        // TODO: Implement write() method.
    }

    public function isReadable(): void
    {
        // TODO: Implement isReadable() method.
    }

    public function read($length): void
    {
        // TODO: Implement read() method.
    }

    public function getContents()
    {
        return $this->content;
    }

    public function getMetadata($key = null): void
    {
        // TODO: Implement getMetadata() method.
    }
}