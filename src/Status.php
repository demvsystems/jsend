<?php

namespace Demv\JSend;

/**
 * Class Status
 * @package Demv\JSend
 */

use function Dgame\Ensurance\ensure;

/**
 * Class Status
 * @package Demv\JSend
 */
final class Status implements StatusInterface
{
    /**
     * @var string
     */
    private $status;

    /**
     * Status constructor.
     *
     * @param string $status
     */
    public function __construct(string $status)
    {
        ensure($status)->isIn([self::STATUS_SUCCESS, self::STATUS_FAIL, self::STATUS_ERROR])->orThrow('Expected valid status');

        $this->status = $status;
    }

    /**
     * @return StatusInterface
     */
    public static function success(): StatusInterface
    {
        return new self(self::STATUS_SUCCESS);
    }

    /**
     * @return StatusInterface
     */
    public static function fail(): StatusInterface
    {
        return new self(self::STATUS_FAIL);
    }

    /**
     * @return StatusInterface
     */
    public static function error(): StatusInterface
    {
        return new self(self::STATUS_ERROR);
    }

    /**
     * @return bool
     */
    public function isFail(): bool
    {
        return $this->status === self::STATUS_FAIL;
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->status === self::STATUS_SUCCESS;
    }

    /**
     * @return bool
     */
    public function isError(): bool
    {
        return $this->status === self::STATUS_ERROR;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->status;
    }
}
