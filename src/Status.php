<?php

namespace Demv\JSend;

use function Dgame\Ensurance\ensure;

/**
 * Class Status
 * @package Demv\JSend
 */
final class Status implements StatusInterface
{
    public const DEFAULT_MAPPING = [
        -1 => self::STATUS_ERROR,
        0  => self::STATUS_FAIL,
        1  => self::STATUS_SUCCESS
    ];

    /**
     * @var StatusInterface[]
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
     * @return StatusInterface
     */
    public static function instance(string $status): StatusInterface
    {
        ensure($status)->isIn([self::STATUS_SUCCESS, self::STATUS_FAIL, self::STATUS_ERROR])->orThrow('Expected valid status');
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
     * @param int   $value
     * @param array $mapping
     *
     * @return StatusInterface
     */
    public static function translate(int $value, array $mapping = self::DEFAULT_MAPPING): StatusInterface
    {
        ensure($value)->isKeyOf($mapping)->orThrow('Cannot map %d, there is not mapping available', $value);
        $status = $mapping[$value];
        ensure($status)->isNotEmpty()->isString()->orThrow('Status must be string');

        return self::instance($status);
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
