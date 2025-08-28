<?php

namespace Demv\JSend;

use function Dgame\Ensurance\ensure;

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

    private string $status;

    private function __construct(string $status)
    {
        $this->status = $status;
    }

    public static function instance(string $status): StatusInterface
    {
        ensure($status)->isIn([self::STATUS_SUCCESS, self::STATUS_FAIL, self::STATUS_ERROR])
            ->orThrow('Expected valid status');
        if (!array_key_exists($status, self::$instances)) {
            self::$instances[$status] = new self($status);
        }

        return self::$instances[$status];
    }

    public static function success(): StatusInterface
    {
        return self::instance(self::STATUS_SUCCESS);
    }

    public static function fail(): StatusInterface
    {
        return self::instance(self::STATUS_FAIL);
    }

    public static function error(): StatusInterface
    {
        return self::instance(self::STATUS_ERROR);
    }

    /**
     * @param mixed   $value
     * @param array<int, string> $mapping
     *
     */
    public static function translate(mixed $value, array $mapping = self::DEFAULT_MAPPING): StatusInterface
    {
        ensure($value)->isKeyOf($mapping)->orThrow('Cannot map %d, there is not mapping available', $value);
        $status = $mapping[$value];
        ensure($status)->isNotEmpty()->isString()->orThrow('Status must be string');

        return self::instance($status);
    }

    public function isFail(): bool
    {
        return $this->status === self::STATUS_FAIL;
    }

    public function isSuccess(): bool
    {
        return $this->status === self::STATUS_SUCCESS;
    }

    public function isError(): bool
    {
        return $this->status === self::STATUS_ERROR;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function __toString(): string
    {
        return $this->status;
    }
}
