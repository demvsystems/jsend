<?php

namespace Demv\JSend;

/**
 * Interface JSendDataInterface
 * @package Demv\JSend
 */
interface JSendDataInterface
{
    /**
     * @param string|null $key
     *
     * @return bool
     */
    public function hasData(string $key = null): bool;

    /**
     * @param string|null $key
     * @param mixed|null  $default
     *
     * @return mixed
     */
    public function getData(string $key = null, $default = null);
}
