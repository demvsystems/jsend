<?php

namespace Demv\JSend;

/**
 * Class JSendErrorResponse
 * @package Demv\JSend
 */

use function Dgame\Ensurance\ensure;

/**
 * Class JSendErrorResponse
 * @package Demv\JSend
 */
final class JSendErrorResponse extends AbstractJSendResponse implements JSendErrorResponseInterface
{
    /**
     * @var string
     */
    private $message;
    /**
     * @var int|null
     */
    private $code;

    /**
     * JSendErrorResponse constructor.
     *
     * @param StatusInterface $status
     * @param array           $response
     */
    public function __construct(StatusInterface $status, array $response)
    {
        parent::__construct($status, $response['data'] ?? null);
        ensure($response)->isArray()->hasKey('message')->orThrow('Key "message" is required');
        $this->message = $response['message'];
        $this->code    = $response['code'] ?? null;
    }

    /**
     * @return int|null
     */
    public function getCode(): ?int
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return array
     */
    public function asArray(): array
    {
        return array_merge(
            array_filter(parent::asArray()),
            array_filter([
                             'message' => $this->message,
                             'code'    => $this->code
                         ])
        );
    }
    
    /**
     * @return JSendErrorResponseInterface
     */
    public function getError(): JSendErrorResponseInterface
    {
        return $this;
    }
}