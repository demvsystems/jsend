<?php

namespace Demv\JSend;

use function Dgame\Ensurance\ensure;

/**
 * Class Status
 * @package Demv\JSend
 */
final class Status
{
    private const SUCCESS = 'success';
    private const FAIL    = 'fail';
    private const ERROR   = 'error';

    /**
     * @var self[]
     */
    private static $froms = [];
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
     * @return self
     */
    public static function from(string $status): self
    {
        ensure($status)->isIn([self::SUCCESS, self::FAIL, self::ERROR])->orThrow('Expected valid status');
        if (!array_key_exists($status, self::$froms)) {
            self::$froms[$status] = new self($status);
        }

        return self::$froms[$status];
    }

    /**
     * @return self
     */
    public static function success(): self
    {
        return self::from(self::SUCCESS);
    }

    /**
     * @return self
     */
    public static function fail(): self
    {
        return self::from(self::FAIL);
    }

    /**
     * @return self
     */
    public static function error(): self
    {
        return self::from(self::ERROR);
    }

    /**
     * @return bool
     */
    public function isFail(): bool
    {
        return $this->status === self::FAIL;
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->status === self::SUCCESS;
    }

    /**
     * @return bool
     */
    public function isError(): bool
    {
        return $this->status === self::ERROR;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->status;
    }
}
