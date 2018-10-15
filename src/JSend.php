<?php

namespace Demv\JSend;

use Dgame\Ensurance\Exception\EnsuranceException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use function Dgame\Extraction\export;

/**
 * Class JSend
 * @package Demv\JSend
 */
final class JSend implements JSendInterface
{
    use JSendStatusTrait;
    use JSendJsonTrait;

    /**
     * @var array
     */
    private $decoded = [];
    /**
     * @var JSendInterface
     */
    private $jsend;

    /**
     * JSend constructor.
     *
     * @param Status $status
     * @param array  $decoded
     */
    public function __construct(Status $status, array $decoded)
    {
        if (!array_key_exists('status', $decoded)) {
            $decoded['status'] = (string) $status;
        }

        $this->status  = $status;
        $this->decoded = $decoded;
    }

    /**
     * @param string $json
     *
     * @return array
     * @throws InvalidJsonException
     */
    public static function safeDecode(string $json): array
    {
        $decoded = json_decode($json, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidJsonException(json_last_error_msg());
        }

        return $decoded;
    }

    /**
     * @param string $json
     *
     * @return JSend
     * @throws InvalidJsonException
     */
    public static function decode(string $json): self
    {
        $decoded = self::safeDecode($json);

        return self::from($decoded);
    }

    /**
     * @param array $decoded
     *
     * @return JSend
     */
    public static function from(array $decoded): self
    {
        ['status' => $status] = export('status')->requireAll()->from($decoded);

        return new self(Status::from($status), $decoded);
    }

    /**
     * @param ResponseInterface $response
     *
     * @return JSendInterface
     * @throws EnsuranceException
     * @throws InvalidJsonException
     */
    public static function translate(ResponseInterface $response): JSendInterface
    {
        $code    = $response->getStatusCode();
        $body    = $response->getBody();
        $decoded = self::safeDecode($body);
        if (!array_key_exists('status', $decoded)) {
            $decoded['status'] = Status::fromStatusCode($code);
        }

        return self::decode($body)->withStatus($code);
    }

    /**
     * @param array|null $data
     *
     * @return JSend
     */
    public static function success(array $data = null): self
    {
        return new self(Status::success(), ['data' => $data]);
    }

    /**
     * @param array|null $data
     *
     * @return JSend
     */
    public static function fail(array $data = null): self
    {
        return new self(Status::fail(), ['data' => $data]);
    }

    /**
     * @param string   $message
     * @param int|null $code
     *
     * @return JSend
     */
    public static function error(string $message, int $code = null): self
    {
        return new self(Status::error(), ['message' => $message, 'code' => $code]);
    }

    /**
     * @return array
     */
    public function asArray(): array
    {
        return $this->decoded;
    }

    /**
     * @param callable $closure
     *
     * @return bool
     */
    public function onSuccess(callable $closure): bool
    {
        if ($this->isSuccess()) {
            $closure($this->intoSuccess());

            return true;
        }

        return false;
    }

    /**
     * @param callable $closure
     *
     * @return bool
     */
    public function onFail(callable $closure): bool
    {
        if ($this->isFail()) {
            $closure($this->intoFail());

            return true;
        }

        return false;
    }

    /**
     * @param callable $closure
     *
     * @return bool
     */
    public function onError(callable $closure): bool
    {
        if ($this->isError()) {
            $closure($this->intoError());

            return true;
        }

        return false;
    }

    /**
     * @return JSendInterface
     * @throws EnsuranceException
     */
    public function into(): JSendInterface
    {
        if ($this->isSuccess()) {
            return $this->intoSuccess();
        }

        if ($this->isFail()) {
            return $this->intoFail();
        }

        if ($this->isError()) {
            return $this->intoError();
        }

        throw new EnsuranceException('Neither success, fail or error');
    }

    /**
     * @return JSendSuccess
     */
    public function intoSuccess(): JSendSuccess
    {
        if ($this->jsend === null || !$this->jsend->isSuccess()) {
            $this->jsend = JSendSuccess::from($this->decoded);
        }

        return $this->jsend;
    }

    /**
     * @return JSendFail
     */
    public function intoFail(): JSendFail
    {
        if ($this->jsend === null || !$this->jsend->isFail()) {
            $this->jsend = JSendFail::from($this->decoded);
        }

        return $this->jsend;
    }

    /**
     * @return JSendError
     */
    public function intoError(): JSendError
    {
        if ($this->jsend === null || !$this->jsend->isError()) {
            $this->jsend = JSendError::from($this->decoded);
        }

        return $this->jsend;
    }

    /**
     * @return int
     * @throws EnsuranceException
     */
    public function getDefaultHttpStatusCode(): int
    {
        return $this->into()->getDefaultHttpStatusCode();
    }

    /**
     * @param string|null $key
     *
     * @return bool
     * @throws EnsuranceException
     */
    public function hasData(string $key = null): bool
    {
        return $this->into()->hasData($key);
    }

    /**
     * @param string|null $key
     * @param null        $default
     *
     * @return mixed
     * @throws EnsuranceException
     */
    public function getData(string $key = null, $default = null)
    {
        return $this->into()->getData($key, $default);
    }

    /**
     * @param int|null $code
     *
     * @throws EnsuranceException
     */
    public function respond(int $code = null): void
    {
        $this->into()->respond($code);
    }

    /**
     * @return int
     * @throws EnsuranceException
     */
    public function getStatusCode(): int
    {
        return $this->into()->getStatusCode();
    }

    /**
     * @return string
     * @throws EnsuranceException
     */
    public function getProtocolVersion()
    {
        return $this->into()->getProtocolVersion();
    }

    /**
     * @param string $version
     *
     * @return JSendInterface
     * @throws EnsuranceException
     */
    public function withProtocolVersion($version)
    {
        return $this->into()->withProtocolVersion($version);
    }

    /**
     * @return \string[][]
     * @throws EnsuranceException
     */
    public function getHeaders()
    {
        return $this->into()->getHeaders();
    }

    /**
     * @param string $name
     *
     * @return bool
     * @throws EnsuranceException
     */
    public function hasHeader($name)
    {
        return $this->into()->hasHeader($name);
    }

    /**
     * @param string $name
     *
     * @return string[]
     * @throws EnsuranceException
     */
    public function getHeader($name)
    {
        return $this->into()->getHeader($name);
    }

    /**
     * @param string $name
     *
     * @return string
     * @throws EnsuranceException
     */
    public function getHeaderLine($name)
    {
        return $this->into()->getHeaderLine($name);
    }

    /**
     * @param string          $name
     * @param string|string[] $value
     *
     * @return JSendInterface
     * @throws EnsuranceException
     */
    public function withHeader($name, $value)
    {
        return $this->into()->withHeader($name, $value);
    }

    /**
     * @param string          $name
     * @param string|string[] $value
     *
     * @return JSendInterface
     * @throws EnsuranceException
     */
    public function withAddedHeader($name, $value)
    {
        return $this->into()->withAddedHeader($name, $value);
    }

    /**
     * @param string $name
     *
     * @return JSendInterface
     * @throws EnsuranceException
     */
    public function withoutHeader($name)
    {
        return $this->into()->withoutHeader($name);
    }

    /**
     * @return StreamInterface
     * @throws EnsuranceException
     */
    public function getBody()
    {
        return $this->into()->getBody();
    }

    /**
     * @param StreamInterface $body
     *
     * @return JSendInterface
     * @throws EnsuranceException
     */
    public function withBody(StreamInterface $body)
    {
        return $this->into()->withBody($body);
    }

    /**
     * @param int    $code
     * @param string $reasonPhrase
     *
     * @return JSendInterface
     * @throws EnsuranceException
     */
    public function withStatus($code, $reasonPhrase = '')
    {
        return $this->into()->withStatus($code, $reasonPhrase);
    }

    /**
     * @return string
     * @throws EnsuranceException
     */
    public function getReasonPhrase()
    {
        return $this->into()->getReasonPhrase();
    }
}
