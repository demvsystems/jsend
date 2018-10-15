<?php

namespace Demv\JSend;

use function Dgame\Ensurance\enforce;
use function Dgame\Extraction\export;

/**
 * Class JSendFail
 * @package Demv\JSend
 */
final class JSendFail implements JSendInterface
{
    use JSendStatusTrait;
    use JSendDataTrait;
    use JSendResponseTrait;
    use JSendJsonTrait;
    use ResponseDataTrait;

    /**
     * JSendFail constructor.
     *
     * @param array|null $data
     */
    public function __construct(array $data = null)
    {
        $this->status = Status::fail();
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
     * @return JSendFail
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
     * @return JSendFail
     */
    public static function from(array $decoded): self
    {
        ['status' => $status, 'data' => $data] = export('status', 'data')->requireAll()->from($decoded);
        enforce(Status::from($status)->isFail())->orThrow('Decode non-fail in JSendFail');

        return new self($data);
    }
}