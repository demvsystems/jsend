<?php

namespace Demv\JSend;

use JsonSerializable;

/**
 * Interface JSendJsonInterface
 * @package Demv\JSend
 */
interface JSendJsonInterface extends JsonSerializable
{
    /**
     * @return array
     */
    public function asArray(): array;

    /**
     * @param int $options
     *
     * @return string
     */
    public function encode(int $options = 0): string;

    /**
     * @param string $json
     *
     * @return mixed
     */
    public static function decode(string $json);

    /**
     * @param array $decoded
     *
     * @return mixed
     */
    public static function from(array $decoded);
}
