<?php

namespace Demv\JSend;

/**
 * Trait JSendStatusTrait
 * @package Demv\JSend
 */
trait JSendStatusTrait
{
    /**
     * @var Status
     */
    private $status;

    /**
     * @return Status
     */
    final public function getStatus(): Status
    {
        return $this->status;
    }

    /**
     * @return bool
     */
    final public function isFail(): bool
    {
        return $this->status->isFail();
    }

    /**
     * @return bool
     */
    final public function isSuccess(): bool
    {
        return $this->status->isSuccess();
    }

    /**
     * @return bool
     */
    final public function isError(): bool
    {
        return $this->status->isError();
    }

    /**
     * @param callable $closure
     *
     * @return bool
     */
    final public function onSuccess(callable $closure): bool
    {
        if ($this->isSuccess()) {
            $closure($this);

            return true;
        }

        return false;
    }

    /**
     * @param callable $closure
     *
     * @return bool
     */
    final public function onFail(callable $closure): bool
    {
        if ($this->isFail()) {
            $closure($this);

            return true;
        }

        return false;
    }

    /**
     * @param callable $closure
     *
     * @return bool
     */
    final public function onError(callable $closure): bool
    {
        if ($this->isError()) {
            $closure($this);

            return true;
        }

        return false;
    }
}
