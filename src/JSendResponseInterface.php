<?php

namespace Demv\JSend;

use JsonSerializable;

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
}