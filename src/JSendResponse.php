<?php

namespace Demv\JSend;

use BadMethodCallException;

final class JSendResponse extends AbstractJSendResponse
{
    public function getError(): JSendErrorResponseInterface
    {
        throw new BadMethodCallException('This is not a JSend-Error');
    }
}
