<?php

namespace Demv\JSend;

/**
 * Interface StatusInterface
 * @package Demv\JSend
 */
interface StatusInterface
{
    const KEY            = 'status';
    const STATUS_SUCCESS = 'success';
    const STATUS_FAIL    = 'fail';
    const STATUS_ERROR   = 'error';

    /**
     * @return bool
     */
    public function isFail(): bool;

    /**
     * @return bool
     */
    public function isSuccess(): bool;

    /**
     * @return bool
     */
    public function isError(): bool;
}
