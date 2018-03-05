<?php

namespace Demv\JSend;

use function Dgame\Ensurance\ensure;

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
     */
    public function respond(int $code = null): void
    {
        $code = $code ?? JSend::getDefaultHttpStatusCode($this);
        ensure($code)->isInt()->isBetween(100, 511);

        header('Content-Type: application/json; charset="UTF-8"', true, $code);
        print json_encode($this);
        exit;
    }
}
