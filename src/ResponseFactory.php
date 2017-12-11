<?php

namespace Demv\JSend;

use Psr\Http\Message\ResponseInterface;
use Seld\JsonLint\JsonParser;

/**
 * Class ResponseFactory
 * @package Demv\JSend
 */
final class ResponseFactory
{
    /**
     * @var ResponseFactory
     */
    private static $instance;

    /**
     * ResponseFactory constructor.
     */
    private function __construct()
    {
    }

    /**
     * @return ResponseFactory
     */
    public static function instance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @param ResponseInterface $response
     *
     * @return JSendResponseInterface
     * @throws \Seld\JsonLint\ParsingException
     */
    public function convert(ResponseInterface $response): JSendResponseInterface
    {
        $code    = $response->getStatusCode();
        $content = $response->getBody()->getContents();

        $parser = new JsonParser();
        $result = $parser->parse($content, JsonParser::PARSE_TO_ASSOC | JsonParser::DETECT_KEY_CONFLICTS);

        if ($code >= 200 && $code < 300) {
            return $this->success($result['data'] ?? null);
        }

        if ($code >= 400) {
            return $this->error($result, $code);
        }

        return $this->fail($result['data'] ?? null);
    }

    /**
     * @param array|null $data
     *
     * @return JSendResponseInterface
     */
    public function success(array $data = null): JSendResponseInterface
    {
        return new JSendResponse(Status::success(), $data);
    }

    /**
     * @param array|null $data
     *
     * @return JSendResponseInterface
     */
    public function fail(array $data = null): JSendResponseInterface
    {
        return new JSendResponse(Status::fail(), $data);
    }

    /**
     * @param array    $response
     * @param int|null $code
     *
     * @return JSendResponseInterface
     */
    public function error(array $response, int $code = null): JSendResponseInterface
    {
        if ($code !== null || !array_key_exists('code', $response)) {
            $response['code'] = $code;
        }

        return new JSendErrorResponse(Status::error(), $response);
    }
}