<?php

namespace Demv\JSend;

/**
 * Trait JSendJsonTrait
 * @package Demv\JSend
 */
trait JSendJsonTrait
{
    /**
     * @param int $options
     *
     * @return string
     */
    final public function encode(int $options = 0): string
    {
        return json_encode($this, $options);
    }

    /**
     * @return array
     */
    final public function jsonSerialize(): array
    {
        return $this->asArray();
    }
}
