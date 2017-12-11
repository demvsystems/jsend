<?php

namespace Demv\JSend;

use Seld\JsonLint\JsonParser;
use function Dgame\Ensurance\ensure;
use Seld\JsonLint\ParsingException;

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

        $status = Status::instance($response['status']);
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
     * @throws ParsingException
     */
    public static function decode(string $json): JSendResponseInterface
    {
        $parser = new JsonParser();
        $result = $parser->parse($json, JsonParser::PARSE_TO_ASSOC | JsonParser::DETECT_KEY_CONFLICTS);

        return self::interpret($result);
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
        $status = Status::instance($response['status']);
        if ($status->isError()) {
            ensure($response)->isArray()->hasKey('message')->orThrow('Need a descriptive error-message');
        }

        return json_encode($response, $options);
    }

    /**
     * @param JSendResponseInterface $response
     *
     * @return int
     */
    public static function getDefaultHttpStatusCode(JSendResponseInterface $response): int
    {
        if ($response->getStatus()->isError()) {
            return $response->getError()->getCode() ?? 500;
        }

        return 200;
    }

    /**
     * @param JSendResponseInterface $response
     * @param int|null               $code
     */
    public static function render(JSendResponseInterface $response, int $code = null): void
    {
        $code = $code ?? self::getDefaultHttpStatusCode($response);
        ensure($code)->isInt()->isBetween(100, 511);

        header('Content-Type: application/json; charset="UTF-8"', true, $code);
        print json_encode($response);
        exit;
    }
}
