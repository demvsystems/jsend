<?php

namespace Demv\JSend;

use function Dgame\Ensurance\ensure;

final class JSendErrorResponse extends AbstractJSendResponse implements JSendErrorResponseInterface
{
    private string $message;

    private ?int $code;

    /**
     * @param StatusInterface $status
     * @param array<string, mixed> $response
     */
    public function __construct(StatusInterface $status, array $response)
    {
        parent::__construct($status, $response['data'] ?? null);
        ensure($response)->isArray()->hasKey('message')->orThrow('Key "message" is required');
        ensure(trim($response['message']))->isString()->isLongerThan(0)->orThrow(
            'Key "message" should be a descriptive error-message'
        );

        $this->message = $response['message'];
        $this->code    = $response['code'] ?? null;
    }

    public function getCode(): ?int
    {
        return $this->code;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return array<string, mixed>
     */
    public function asArray(): array
    {
        return array_merge(
            array_filter(parent::asArray()),
            array_filter(
                [
                    'message' => $this->message,
                    'code'    => $this->code
                ]
            )
        );
    }

    public function getError(): JSendErrorResponseInterface
    {
        return $this;
    }
}
