<?php

namespace Demv\JSend;

use Psr\Http\Message\ResponseInterface;

final class ResponseFactory
{
    /**
     * @var ResponseFactory
     */
    private static $instance;

    private function __construct()
    {
    }

    public static function instance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function convert(ResponseInterface $response): JSendResponseInterface
    {
        $result         = Json::decode($response->getBody()->getContents());
        $result['code'] = $response->getStatusCode();

        return JSend::interpret($result);
    }

    /**
     * @param array<string, mixed>|null $data
     */
    public function success(?array $data = null): JSendResponseInterface
    {
        return new JSendResponse(Status::success(), $data);
    }

    /**
     * @param array<string, mixed>|null $data
     */
    public function fail(?array $data = null): JSendResponseInterface
    {
        return new JSendResponse(Status::fail(), $data);
    }

    /**
     * @param array<string, mixed> $response
     * @param int|null $code
     */
    public function error(array $response, ?int $code = null): JSendResponseInterface
    {
        if ($code !== null || !array_key_exists('code', $response)) {
            $response['code'] = $code;
        }

        return new JSendErrorResponse(Status::error(), $response);
    }
}
