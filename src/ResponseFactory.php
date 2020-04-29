<?php

namespace Demv\JSend;

use Psr\Http\Message\ResponseInterface;

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
     */
    public function convert(ResponseInterface $response): JSendResponseInterface
    {
        $result         = Json::decode($response->getBody()->getContents());
        $result['code'] = $response->getStatusCode();

        return JSend::interpret($result);
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
