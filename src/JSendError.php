<?php

namespace Demv\JSend;

use function Dgame\Ensurance\enforce;
use function Dgame\Extraction\export;

/**
 * Class JSendError
 * @package Demv\JSend
 */
final class JSendError implements JSendInterface
{
    use JSendStatusTrait;
    use JSendResponseTrait;
    use JSendJsonTrait;
    use ResponseDataTrait;

    /**
     * JSendError constructor.
     *
     * @param string   $message
     * @param int|null $code
     */
    public function __construct(string $message, int $code = null)
    {
        $this->status  = Status::error();
        $this->message = $message;
        $this->code    = $code;

        $this->initResponseData();
    }

    /**
     * @return int|null
     */
    public function getCode(): ?int
    {
        return $this->code;
    }

    /**
     * @return null|string
     */
    public function getMessage(): ?string
    {
        return $this->getReasonPhrase();
    }

    /**
     * @return array
     */
    public function asArray(): array
    {
        return array_filter(
            [
                'status'  => (string) $this->status,
                'message' => $this->message,
                'code'    => $this->code
            ]
        );
    }

    /**
     * @param string $json
     *
     * @return JSendError
     * @throws InvalidJsonException
     */
    public static function decode(string $json): self
    {
        $decoded = JSend::safeDecode($json);

        return self::from($decoded);
    }

    /**
     * @param array $decoded
     *
     * @return JSendError
     */
    public static function from(array $decoded): self
    {
        ['status' => $status, 'message' => $message, 'code' => $code] = export('status', 'message', 'code')->require('status', 'message')->from($decoded);
        enforce(Status::from($status)->isError())->orThrow('Decode non-error in JSendError');

        return new self($message, $code);
    }

    /**
     * @param string|null $key
     *
     * @return bool
     */
    public function hasData(string $key = null): bool
    {
        return false;
    }

    /**
     * @param string|null $key
     * @param mixed|null  $default
     *
     * @return array|mixed|null
     */
    public function getData(string $key = null, $default = null)
    {
        return $key === null ? [] : $default;
    }
}
