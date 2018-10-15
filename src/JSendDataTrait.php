<?php

namespace Demv\JSend;

/**
 * Trait JSendDataTrait
 * @package Demv\JSend
 */
trait JSendDataTrait
{
    /**
     * @var array
     */
    private $data = [];

    /**
     * @param string|null $key
     *
     * @return bool
     */
    final public function hasData(string $key = null): bool
    {
        if ($key === null) {
            return !empty($this->data);
        }

        return array_key_exists($key, $this->data ?? []);
    }

    /**
     * @param string|null $key
     * @param mixed|null  $default
     *
     * @return array|mixed|null
     */
    final public function getData(string $key = null, $default = null)
    {
        $data = $this->data ?? [];
        if ($key === null) {
            return $data;
        }

        return $data[$key] ?? $default;
    }
}
