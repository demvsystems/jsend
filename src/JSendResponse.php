<?php

namespace Demv\JSend;

use PHPUnit\Framework\MockObject\BadMethodCallException;

/**
 * Class JSendResponse
 * @package Demv\JSend
 */
final class JSendResponse extends AbstractJSendResponse
{
    /**
     * JSendResponse constructor.
     *
     * @param StatusInterface $status
     * @param array|null      $data
     */
    public function __construct(StatusInterface $status, array $data = null)
    {
        parent::__construct($status, $data);
    }

    /**
     * @return JSendErrorResponseInterface
     */
    public function getError(): JSendErrorResponseInterface
    {
        throw new BadMethodCallException('This is not a JSend-Error');
    }
}