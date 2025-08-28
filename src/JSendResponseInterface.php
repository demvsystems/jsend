<?php

namespace Demv\JSend;

use JsonSerializable;
use Psr\Http\Message\ResponseInterface;

interface JSendResponseInterface extends JsonSerializable
{
    /**
     * @return array<string, mixed>
     */
    public function getData(): array;

    public function getStatus(): StatusInterface;

    public function getError(): JSendErrorResponseInterface;

    /**
     * @param int|null $code
     *
     * @return never This method calls exit() after sending its response
     */
    public function respond(?int $code = null): void;

    /**
     * @param int|null $code
     * @param array<string, string|string[]> $headers
     */
    public function asResponse(?int $code = null, array $headers = []): ResponseInterface;
}
