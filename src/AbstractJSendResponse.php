<?php

namespace Demv\JSend;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use function Dgame\Ensurance\ensure;

/**
 * Class AbstractJSendResponse
 * @package Demv\JSend
 */
abstract class AbstractJSendResponse implements JSendResponseInterface
{
    private StatusInterface $status;
    /**
     * @var array<string|int, mixed>|null
     */
    private ?array $data;

    /**
     * @param StatusInterface $status
     * @param array<string|int, mixed>|null $data
     */
    public function __construct(StatusInterface $status, ?array $data = null)
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
     * @return array<string|int, mixed>
     */
    final public function getData(): array
    {
        return $this->data ?? [];
    }

    /**
     * @return array<string, mixed>
     */
    public function asArray(): array
    {
        return [
            'status' => $this->status->getStatus(),
            'data'   => $this->data
        ];
    }

    /**
     * @internal
     *
     * @return array<string, mixed>
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
    public function respond(?int $code = null): void
    {
        $code = $code ?? JSend::getDefaultHttpStatusCode($this);
        ensure($code)->isInt()->isBetween(100, 511);

        header('Content-Type: application/json; charset="UTF-8"', true, $code);
        print json_encode($this);
        exit;
    }

    /**
     * @param array<string|int, mixed>|null $data
     *
     * @return AbstractJSendResponse
     */
    public static function success(?array $data = null): self
    {
        return new static(Status::success(), $data); // @phpstan-ignore-line
    }

    /**
     * @param array<string|int, mixed>|null $data
     *
     * @return AbstractJSendResponse
     */
    public static function fail(?array $data = null): self
    {
        return new static(Status::fail(), $data); // @phpstan-ignore-line
    }

    /**
     * @param string     $message
     * @param int|null   $code
     * @param array<string, mixed>|null $data
     *
     * @return AbstractJSendResponse
     */
    public static function error(string $message, ?int $code = null, ?array $data = null): self
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
     * @param array<string, string|string[]> $headers
     *
     * @return ResponseInterface
     */
    public function asResponse(?int $code = null, array $headers = []): ResponseInterface
    {
        $code = $code ?? JSend::getDefaultHttpStatusCode($this);

        $encoded = json_encode($this);
        if ($encoded === false) {
            throw new \RuntimeException('Failed to encode JSON');
        }

        return new Response($code, [
            'content-type' => 'application/json',
        ] + $headers, $encoded);
    }
}
