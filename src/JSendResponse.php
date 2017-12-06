<?php

namespace Demv\JSend;

use BadMethodCallException;

/**
 * Class JSendResponse
 * @package Demv\JSend
 */
final class JSendResponse extends AbstractJSendResponse
{
    /**
     * @return JSendErrorResponseInterface
     */
    public function getError(): JSendErrorResponseInterface
    {
        throw new BadMethodCallException('This is not a JSend-Error');
    }
}