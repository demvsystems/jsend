<?php

namespace Demv\JSend;

use Psr\Http\Message\ResponseInterface;

/**
 * Interface JSendResponseInterface
 * @package Demv\JSend
 */
interface JSendResponseInterface extends ResponseInterface
{
    /**
     * @param int|null $code
     */
    public function respond(int $code = null): void;
}
