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
    private ?StreamInterface $body = null;
    private ?int $code             = null;

    public function getProtocolVersion(): string
    {
        return '1.1';
    }

    public function withProtocolVersion(string $version): ResponseInterface
    {
        return $this;
    }

    public function getHeaders(): array
    {
        return [];
    }

    public function hasHeader(string $name): bool
    {
        return false;
    }

    public function getHeader(string $name): array
    {
        return [];
    }

    public function getHeaderLine(string $name): string
    {
        return '';
    }

    public function withHeader(string $name, $value): ResponseInterface
    {
        return $this;
    }

    public function withAddedHeader(string $name, $value): ResponseInterface
    {
        return $this;
    }

    public function withoutHeader(string $name): ResponseInterface
    {
        return $this;
    }

    public function getBody(): StreamInterface
    {
        if ($this->body === null) {
            return new DummyStream('');
        }

        return $this->body;
    }

    public function withBody(StreamInterface $body): ResponseInterface
    {
        $this->body = $body;

        return $this;
    }

    public function getStatusCode(): int
    {
        return $this->code ?? 200;
    }

    public function withStatus(int $code, string $reasonPhrase = ''): ResponseInterface
    {
        $this->code = $code;

        return $this;
    }

    public function getReasonPhrase(): string
    {
        return '';
    }
}
