<?php

namespace Demv\JSend;

/**
 * Interface StatusInterface
 * @package Demv\JSend
 */
interface StatusInterface
{
    public const KEY            = 'status';
    public const STATUS_SUCCESS = 'success';
    public const STATUS_FAIL    = 'fail';
    public const STATUS_ERROR   = 'error';

    public function isFail(): bool;

    public function isSuccess(): bool;

    public function isError(): bool;

    public function getStatus(): string;
}
