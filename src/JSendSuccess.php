<?php

namespace Demv\JSend;

use function Dgame\Ensurance\enforce;
use function Dgame\Extraction\export;

/**
 * Class JSendSuccess
 * @package Demv\JSend
 */
final class JSendSuccess implements JSendInterface
{
    use JSendStatusTrait;
    use JSendDataTrait;
    use JSendResponseTrait;
    use JSendJsonTrait;
    use ResponseDataTrait;

    /**
     * JSendSuccess constructor.
     *
     * @param array|null $data
     */
    public function __construct(array $data = null)
    {
        $this->status = Status::success();
        $this->data   = $data;

        $this->initResponseData();
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
     * @param string $json
     *
     * @return JSendSuccess
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
     * @return JSendSuccess
     */
    public static function from(array $decoded): self
    {
        ['status' => $status, 'data' => $data] = export('status', 'data')->requireAll()->from($decoded);
        enforce(Status::from($status)->isSuccess())->orThrow('Decode non-success in JSendSuccess');

        return new self($data);
    }
}
