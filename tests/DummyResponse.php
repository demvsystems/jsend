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

    public function getProtocolVersion(): void
    {
        // TODO: Implement getProtocolVersion() method.
    }

    public function withProtocolVersion($version): void
    {
        // TODO: Implement withProtocolVersion() method.
    }

    public function getHeaders(): void
    {
        // TODO: Implement getHeaders() method.
    }

    public function hasHeader($name): void
    {
        // TODO: Implement hasHeader() method.
    }

    public function getHeader($name): void
    {
        // TODO: Implement getHeader() method.
    }

    public function getHeaderLine($name): void
    {
        // TODO: Implement getHeaderLine() method.
    }

    public function withHeader($name, $value): void
    {
        // TODO: Implement withHeader() method.
    }

    public function withAddedHeader($name, $value): void
    {
        // TODO: Implement withAddedHeader() method.
    }

    public function withoutHeader($name): void
    {
        // TODO: Implement withoutHeader() method.
    }

    public function getBody()
    {
        return $this->body;
    }

    public function withBody(StreamInterface $body): void
    {
        $this->body = $body;
    }

    public function getStatusCode()
    {
        return $this->code;
    }

    public function withStatus($code, $reasonPhrase = ''): void
    {
        $this->code = $code;
    }

    public function getReasonPhrase(): void
    {
    }
}