<?php

namespace Demv\JSend;

use function Dgame\Ensurance\ensure;

/**
 * Class JSend
 * @package Demv\JSend
 */
final class JSend
{
    /**
     * @param array<string, mixed> $response
     */
    public static function interpret(array $response): JSendResponseInterface
    {
        ensure($response)->isArray()->hasKey(StatusInterface::KEY)->orThrow('Key "status" is required');

        $status = Status::instance($response[StatusInterface::KEY]);
        if ($status->isSuccess() || $status->isFail()) {
            ensure($response)->isArray()->hasKey('data')->orThrow('Key "data" is required');

            return new JSendResponse($status, $response['data']);
        }

        return new JSendErrorResponse($status, $response);
    }

    public static function decode(string $json): JSendResponseInterface
    {
        return self::interpret(Json::decode($json));
    }

    /**
     * @param array<string, mixed> $response
     */
    public static function encode(array $response): string
    {
        ensure($response)->isNotEmpty()->orThrow('Empty response cannot be converted to valid JSend-JSON');
        ensure($response)->isArray()->hasKey(StatusInterface::KEY)->orThrow('Key "status" is required');
        $status = Status::instance($response[StatusInterface::KEY]);
        if ($status->isError()) {
            ensure($response)->isArray()->hasKey('message')->orThrow('Need a descriptive error-message');
        }

        $encoded = json_encode($response);
        if ($encoded === false) {
            throw new \RuntimeException('Failed to encode JSON');
        }

        return $encoded;
    }

    /**
     * @param array<string, mixed> $response
     */
    public static function success(array $response): string
    {
        $response[StatusInterface::KEY] = StatusInterface::STATUS_SUCCESS;

        return self::encode($response);
    }

    /**
     * @param array<string, mixed> $response
     */
    public static function fail(array $response): string
    {
        $response[StatusInterface::KEY] = StatusInterface::STATUS_FAIL;

        return self::encode($response);
    }

    /**
     * @param array<string, mixed> $response
     */
    public static function error(array $response): string
    {
        $response[StatusInterface::KEY] = StatusInterface::STATUS_ERROR;

        return self::encode($response);
    }

    public static function getDefaultHttpStatusCode(JSendResponseInterface $response): int
    {
        if ($response->getStatus()->isError()) {
            return $response->getError()->getCode() ?? 500;
        }

        return 200;
    }
}
