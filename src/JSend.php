<?php

namespace Demv\JSend;

use InvalidArgumentException;
use function Dgame\Ensurance\ensure;

/**
 * Class JSend
 * @package Demv\JSend
 */
final class JSend
{
    /**
     * @param array $response
     *
     * @return JSendResponseInterface
     */
    private static function interpret(array $response): JSendResponseInterface
    {
        ensure($response)->isArray()->hasKey('status')->orThrow('Key "status" is required');

        $status = new Status($response['status']);
        if ($status->isSuccess() || $status->isFail()) {
            ensure($response)->isArray()->hasKey('data')->orThrow('Key "data" is required');

            return new JSendResponse($status, $response['data']);
        }

        return new JSendErrorResponse($status, $response);
    }

    /**
     * @param string $json
     *
     * @return JSendResponseInterface
     */
    public static function decode(string $json): JSendResponseInterface
    {
        $result = json_decode($json, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return self::interpret($result);
        }

        throw new InvalidArgumentException('Malformed Json: ' . $json . ' :: ' . json_last_error_msg());
    }

    /**
     * @param array $response
     * @param int   $options
     *
     * @return string
     */
    public static function encode(array $response, int $options = 0): string
    {
        ensure($response)->isNotEmpty()->orThrow('Empty response cannot be converted to valid JSend-JSON');
        ensure($response)->isArray()->hasKey('status')->orThrow('Key "status" is required');
        $status    = new Status($response['status']);
        if ($status->isError()) {
            ensure($response)->isArray()->hasKey('message')->orThrow('Need a descriptive error-message');
        }

        return json_encode($response, $options);
    }
}
