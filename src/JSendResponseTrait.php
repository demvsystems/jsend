<?php

namespace Demv\JSend;

use Dgame\Ensurance\Exception\EnsuranceException;
use GuzzleHttp\Psr7\MessageTrait;
use function Dgame\Ensurance\ensure;

/**
 * Trait JSendResponseTrait
 * @package Demv\JSend
 */
trait JSendResponseTrait
{
    use MessageTrait;

    /**
     * @var string
     */
    private $message;
    /**
     * @var int|null
     */
    private $code;

    /**
     * @param int|null $code
     */
    final public function respond(int $code = null): void
    {
        $code = $code ?? $this->getStatusCode();
        ensure($code)->isInt()->isBetween(100, 511);
        header('Content-Type: application/json; charset="UTF-8"', true, $code);
        print json_encode($this);
        exit;
    }

    /**
     * @return int
     */
    final public function getStatusCode()
    {
        return $this->code ?? $this->getDefaultHttpStatusCode();
    }

    /**
     * @param        $code
     * @param string $reasonPhrase
     *
     * @return JSend|JSendInterface
     * @throws EnsuranceException
     */
    public function withStatus($code, $reasonPhrase = '')
    {
        $decoded           = $this->asArray();
        $decoded['status'] = (string) Status::fromStatusCode($code ?? $this->getDefaultHttpStatusCode());

        $jsend       = JSend::from($decoded)->into();
        $jsend->code = $code;
        if (!empty($reasonPhrase)) {
            $jsend->message = $reasonPhrase;
        }

        return $jsend;
    }

    /**
     * @return string
     */
    public function getReasonPhrase()
    {
        return $this->message;
    }
}
