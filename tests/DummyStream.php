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

    public function close()
    {
        // TODO: Implement close() method.
    }

    public function detach()
    {
        // TODO: Implement detach() method.
    }

    public function getSize()
    {
        // TODO: Implement getSize() method.
    }

    public function tell()
    {
        // TODO: Implement tell() method.
    }

    public function eof()
    {
        // TODO: Implement eof() method.
    }

    public function isSeekable()
    {
        // TODO: Implement isSeekable() method.
    }

    public function seek($offset, $whence = SEEK_SET)
    {
        // TODO: Implement seek() method.
    }

    public function rewind()
    {
        // TODO: Implement rewind() method.
    }

    public function isWritable()
    {
        // TODO: Implement isWritable() method.
    }

    public function write($string)
    {
        // TODO: Implement write() method.
    }

    public function isReadable()
    {
        // TODO: Implement isReadable() method.
    }

    public function read($length)
    {
        // TODO: Implement read() method.
    }

    public function getContents()
    {
        return $this->content;
    }

    public function getMetadata($key = null)
    {
        // TODO: Implement getMetadata() method.
    }
}