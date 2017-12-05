<?php

namespace Demv\JSend;

/**
 * Interface JSendErrorResponseInterface
 * @package Demv\JSend
 */
interface JSendErrorResponseInterface extends JSendResponseInterface
{
    /**
     * @return string
     */
    public function getMessage(): string;

    /**
     * @return int|null
     */
    public function getCode(): ?int;
}