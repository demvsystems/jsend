<?php

namespace Demv\JSend\Test;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

/**
 * Class DummyResponse
 * @package Demv\JSend\Test
 */
final class DummyResponse implements ResponseInterface
{
    private $body;
    private $code;

    public function getProtocolVersion()
    {
        // TODO: Implement getProtocolVersion() method.
    }

    public function withProtocolVersion($version)
    {
        // TODO: Implement withProtocolVersion() method.
    }

    public function getHeaders()
    {
        // TODO: Implement getHeaders() method.
    }

    public function hasHeader($name)
    {
        // TODO: Implement hasHeader() method.
    }

    public function getHeader($name)
    {
        // TODO: Implement getHeader() method.
    }

    public function getHeaderLine($name)
    {
        // TODO: Implement getHeaderLine() method.
    }

    public function withHeader($name, $value)
    {
        // TODO: Implement withHeader() method.
    }

    public function withAddedHeader($name, $value)
    {
        // TODO: Implement withAddedHeader() method.
    }

    public function withoutHeader($name)
    {
        // TODO: Implement withoutHeader() method.
    }

    public function getBody()
    {
        return $this->body;
    }

    public function withBody(StreamInterface $body)
    {
        $this->body = $body;
    }

    public function getStatusCode()
    {
        return $this->code;
    }

    public function withStatus($code, $reasonPhrase = '')
    {
        $this->code = $code;
    }

    public function getReasonPhrase()
    {
    }
}