<?php

namespace Demv\JSend;

/**
 * Interface JSendStatusInterface
 * @package Demv\JSend
 */
interface JSendStatusInterface
{
    /**
     * @return Status
     */
    public function getStatus(): Status;

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

    /**
     * @param callable $closure
     *
     * @return bool
     */
    public function onSuccess(callable $closure): bool;

    /**
     * @param callable $closure
     *
     * @return bool
     */
    public function onFail(callable $closure): bool;

    /**
     * @param callable $closure
     *
     * @return bool
     */
    public function onError(callable $closure): bool;
}
