<?php

declare(strict_types=1);

namespace Demv\JSend;

use InvalidArgumentException;

class Json
{
    /**
     * Decodes the given JSON string or fails with an exception.
     *
     * @param string $json
     * @return array<string, mixed>
     * @throws InvalidArgumentException
     */
    public static function decode(string $json): array
    {
        $data = json_decode($json, true);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new InvalidArgumentException(
                'json_decode error: ' . json_last_error_msg()
            );
        }

        return $data;
    }
}
