<?php

namespace Demv\JSend;

use function Dgame\Ensurance\ensure;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

/**
 * Class AbstractJSendResponse
 * @package Demv\JSend
 */
abstract class AbstractJSendResponse implements JSendResponseInterface
{
    /**
     * @var StatusInterface
     */
    private $status;
    /**
     * @var array|null
     */
    private $data;

    /**
     * AbstractJSendResponse constructor.
     *
     * @param StatusInterface $status
     * @param array|null      $data
     */
    public function __construct(StatusInterface $status, array $data = null)
    {
        $this->status = $status;
        $this->data   = $data;
    }

    /**
     * @return StatusInterface
     */
    final public function getStatus(): StatusInterface
    {
        return $this->status;
    }

    /**
     * @return array
     */
    final public function getData(): array
    {
        return $this->data ?? [];
    }

    /**
     * @return array
     */
    public function asArray(): array
    {
        return [
            'status' => (string) $this->status,
            'data'   => $this->data
        ];
    }

    /**
     * @internal
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->asArray();
    }

    /**
     * @param int|null $code
     *
     * @return never This method calls exit() after sending its response
     */
    public function respond(int $code = null): void
    {
        $code = $code ?? JSend::getDefaultHttpStatusCode($this);
        ensure($code)->isInt()->isBetween(100, 511);

        header('Content-Type: application/json; charset="UTF-8"', true, $code);
        print json_encode($this);
        exit;
    }

    /**
     * @param array|null $data
     *
     * @return AbstractJSendResponse
     */
    public static function success(array $data = null): self
    {
        return new static(Status::success(), $data);
    }

    /**
     * @param array|null $data
     *
     * @return AbstractJSendResponse
     */
    public static function fail(array $data = null): self
    {
        return new static(Status::fail(), $data);
    }

    /**
     * @param string     $message
     * @param int|null   $code
     * @param array|null $data
     *
     * @return AbstractJSendResponse
     */
    public static function error(string $message, int $code = null, array $data = null): self
    {
        return new JSendErrorResponse(
            Status::error(),
            [
                'message' => $message,
                'code'    => $code,
                'data'    => $data
            ]
        );
    }

    /**
     * @param int|null $code
     * @param array    $headers
     *
     * @return ResponseInterface
     */
    public function asResponse(int $code = null, array $headers = []): ResponseInterface
    {
        $code = $code ?? JSend::getDefaultHttpStatusCode($this);

        return new Response($code, [
            'content-type' => 'application/json',
        ] + $headers, json_encode($this));
    }
}
