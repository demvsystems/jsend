<?php

namespace Demv\JSend;

use JsonSerializable;
use Psr\Http\Message\ResponseInterface;

/**
 * Interface JSendResponseInterface
 * @package Demv\JSend
 */
interface JSendResponseInterface extends JsonSerializable
{
    /**
     * @return array
     */
    public function getData(): array;

    /**
     * @return StatusInterface
     */
    public function getStatus(): StatusInterface;

    /**
     * @return JSendErrorResponseInterface
     */
    public function getError(): JSendErrorResponseInterface;

    /**
     * @param int|null $code
     */
    public function respond(int $code = null): void;

    /**
     * @param int|null $code
     * @param array    $headers
     *
     * @return ResponseInterface
     */
    public function asResponse(int $code = null, array $headers = []): ResponseInterface;
}
