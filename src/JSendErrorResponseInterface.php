<?php

namespace Demv\JSend;

interface JSendErrorResponseInterface extends JSendResponseInterface
{
    public function getMessage(): string;

    public function getCode(): ?int;
}
