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
     * @var Status[]
     */
    private static $instances = [];
    /**
     * @var string
     */
    private $status;

    /**
     * Status constructor.
     *
     * @param string $status
     */
    private function __construct(string $status)
    {
        $this->status = $status;
    }

    /**
     * @param string $status
     *
     * @return Status
     */
    public static function instance(string $status): self
    {
        ensure($status)->isIn([self::STATUS_SUCCESS, self::STATUS_FAIL, self::STATUS_ERROR])
                       ->orThrow('Expected valid status');
        if (!array_key_exists($status, self::$instances)) {
            self::$instances[$status] = new self($status);
        }

        return self::$instances[$status];
    }

    /**
     * @return StatusInterface
     */
    public static function success(): StatusInterface
    {
        return self::instance(self::STATUS_SUCCESS);
    }

    /**
     * @return StatusInterface
     */
    public static function fail(): StatusInterface
    {
        return self::instance(self::STATUS_FAIL);
    }

    /**
     * @return StatusInterface
     */
    public static function error(): StatusInterface
    {
        return self::instance(self::STATUS_ERROR);
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
