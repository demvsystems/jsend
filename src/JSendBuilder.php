<?php

namespace Demv\JSend;

/**
 * Class JSendBuilder
 * @package Demv\JSend
 */
final class JSendBuilder
{
    /**
     * @var string
     */
    private $status;
    /**
     * @var array|null
     */
    private $data;
    /**
     * @var string
     */
    private $message;
    /**
     * @var int
     */
    private $code;

    /**
     * @param string $status
     *
     * @return JSendBuilder
     */
    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return JSendBuilder
     */
    public function success(): self
    {
        $this->setStatus(StatusInterface::STATUS_SUCCESS);

        return $this;
    }

    /**
     * @return JSendBuilder
     */
    public function fail(): self
    {
        $this->setStatus(StatusInterface::STATUS_FAIL);

        return $this;
    }

    /**
     * @return JSendBuilder
     */
    public function error(): self
    {
        $this->setStatus(StatusInterface::STATUS_ERROR);

        return $this;
    }

    /**
     * @param array|null $data
     *
     * @return JSendBuilder
     */
    public function setData(?array $data): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getData(): ?array
    {
        return $this->data;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message ?? '';
    }

    /**
     * @param string $message
     *
     * @return JSendBuilder
     */
    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code ?? 0;
    }

    /**
     * @param int $code
     *
     * @return JSendBuilder
     */
    public function setCode(int $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return array
     */
    public function asArray(): array
    {
        return [
            'status'  => $this->status,
            'data'    => $this->data,
            'message' => $this->message,
            'code'    => $this->code
        ];
    }

    /**
     * @return JSendResponseInterface
     */
    public function createResponse(): JSendResponseInterface
    {
        $status = Status::instance($this->status);
        if ($status->isError()) {
            return new JSendErrorResponse($status, $this->asArray());
        }

        return new JSendResponse($status, $this->data);
    }
}