<?php

namespace Demv\JSend\Test;

use Demv\JSend\JSend;
use Demv\JSend\JSendError;
use PHPUnit\Framework\TestCase;

final class ResponseTest extends TestCase
{
    /**
     * @throws \Dgame\Ensurance\Exception\EnsuranceException
     */
    public function testSuccessFactory(): void
    {
        $jsend = JSend::success(['Erfolgreich!']);

        $this->assertTrue($jsend->isSuccess());
        $this->assertEquals(['Erfolgreich!'], $jsend->getData());
    }

    /**
     * @throws \Dgame\Ensurance\Exception\EnsuranceException
     */
    public function testFailFactory(): void
    {
        $jsend = JSend::fail(['Irgendwas lief schief']);

        $this->assertTrue($jsend->isFail());
        $this->assertEquals(['Irgendwas lief schief'], $jsend->getData());
    }

    public function testErrorFactory(): void
    {
        $jsend = JSend::error('Es ist ein Fehler aufgetreten');

        $this->assertTrue($jsend->isError());
        $this->assertEmpty($jsend->intoError()->getData());
        $this->assertEquals(
            'Es ist ein Fehler aufgetreten',
            $jsend->intoError()->getMessage()
        );
    }

    /**
     * @throws \Demv\JSend\InvalidJsonException
     * @throws \Dgame\Ensurance\Exception\EnsuranceException
     */
    public function testSuccessConversion(): void
    {
        $json = '{"status": "success", "data": ["Holy", "Moly"]}';

        $success = new DummyResponse();
        $success->withBody(new DummyStream($json));
        $success->withStatus(214);

        $jsend = JSend::translate($success);
        $this->assertTrue($jsend->getStatus()->isSuccess());
        $this->assertEquals(['Holy', 'Moly'], $jsend->getData());
        $this->assertJsonStringEqualsJsonString($json, json_encode($jsend));
    }

    /**
     * @throws \Demv\JSend\InvalidJsonException
     * @throws \Dgame\Ensurance\Exception\EnsuranceException
     */
    public function testFailConversion(): void
    {
        $fail = new DummyResponse();
        $fail->withBody(new DummyStream('{"status": "fail", "data": null}'));

        $jsend = JSend::translate($fail);
        $this->assertTrue($jsend->getStatus()->isFail());
        $this->assertEmpty($jsend->getData());
        $this->assertJsonStringEqualsJsonString('{"status": "fail", "data": null}', json_encode($jsend));
    }

    /**
     * @throws \Demv\JSend\InvalidJsonException
     * @throws \Dgame\Ensurance\Exception\EnsuranceException
     */
    public function testErrorConversion(): void
    {
        $json = '{"status": "error", "message": "Something is not right..."}';

        $error = new DummyResponse();
        $error->withBody(new DummyStream($json));
        $error->withStatus(501);

        $result         = json_decode($json, true);
        $result['code'] = $error->getStatusCode();

        /** @var JSendError $jsend */
        $jsend = JSend::translate($error);
        $this->assertTrue($jsend->isError());
        $this->assertEmpty($jsend->getData());
        $this->assertJsonStringEqualsJsonString(json_encode($result), $jsend->encode());
        $this->assertEquals('Something is not right...', $jsend->getMessage());
        $this->assertEquals($error->getStatusCode(), $jsend->getCode());
    }

    /**
     * @throws \Dgame\Ensurance\Exception\EnsuranceException
     */
    public function testPsr7Response(): void
    {
        $jsend = JSend::success(['Erfolgreich!'])->withStatus(200);
        $this->assertEquals(200, $jsend->getStatusCode());
        $this->assertEquals('{"status":"success","data":["Erfolgreich!"]}', $jsend->getBody()->getContents());

        $jsend = JSend::fail(['Irgendwas lief schief'])->withStatus(400);
        $this->assertEquals(400, $jsend->getStatusCode());
        $this->assertEquals('{"status":"fail","data":["Irgendwas lief schief"]}', $jsend->getBody()->getContents());

        $jsend = JSend::error('Es ist ein Fehler aufgetreten', 404)->withStatus(500);
        $this->assertEquals(500, $jsend->getStatusCode());
        $this->assertEquals(
            '{"status":"error","message":"Es ist ein Fehler aufgetreten","code":404}',
            $jsend->getBody()->getContents()
        );

        $jsend = JSend::success(['Stimmt der Header?']);
        $this->assertEquals(['application/json'], $jsend->getHeader('content-type'));

        $jsend = JSend::success(['Eigene Header werden übernommen'])->withHeader('foo', 'bar');
        $this->assertEquals(['bar'], $jsend->getHeader('foo'));
    }

    /**
     * @throws \Dgame\Ensurance\Exception\EnsuranceException
     */
    public function testPsr7ResponseOptionalCode(): void
    {
        $jsend = JSend::success(['Erfolgreich!']);
        $this->assertEquals(200, $jsend->getStatusCode());
        $this->assertEquals('{"status":"success","data":["Erfolgreich!"]}', $jsend->getBody()->getContents());

        $jsend = JSend::fail(['Irgendwas lief schief']);
        $this->assertEquals(400, $jsend->getStatusCode());
        $this->assertEquals('{"status":"fail","data":["Irgendwas lief schief"]}', $jsend->getBody()->getContents());

        $jsend = JSend::error('Es ist ein Fehler aufgetreten', 404);
        $this->assertEquals(404, $jsend->getStatusCode());
        $this->assertEquals(
            '{"status":"error","message":"Es ist ein Fehler aufgetreten","code":404}',
            $jsend->getBody()->getContents()
        );

        $jsend = JSend::error('Es ist ein Fehler aufgetreten');
        $this->assertEquals(500, $jsend->getStatusCode());
        $this->assertEquals(
            '{"status":"error","message":"Es ist ein Fehler aufgetreten"}',
            $jsend->getBody()->getContents()
        );
    }
}
