<?php

namespace Demv\JSend;

use function GuzzleHttp\Psr7\stream_for;

/**
 * Trait ResponseDataTrait
 * @package Demv\JSend
 */
trait ResponseDataTrait
{
    /**
     *
     */
    private function initResponseData(): void
    {
        $this->stream = stream_for($this->encode());

        $this->headerNames['content-type'] = 'Content-Type';
        $this->headers['Content-Type']     = ['application/json'];
    }
}
