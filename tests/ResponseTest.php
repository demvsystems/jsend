<?php

namespace Demv\JSend\Test;

use Demv\JSend\JSend;
use Demv\JSend\JSendResponse;
use Demv\JSend\ResponseFactory;
use Demv\JSend\Status;
use Demv\JSend\StatusInterface;
use PHPUnit\Framework\TestCase;

final class ResponseTest extends TestCase
{
    public function testDefaultHttpStatusCode(): void
    {
        $this->assertEquals(200, JSend::getDefaultHttpStatusCode(ResponseFactory::instance()->success()));
        $this->assertEquals(200, JSend::getDefaultHttpStatusCode(ResponseFactory::instance()->fail()));
        $this->assertEquals(500, JSend::getDefaultHttpStatusCode(ResponseFactory::instance()->error(['message' => 'wtf'])));
        $this->assertEquals(400, JSend::getDefaultHttpStatusCode(ResponseFactory::instance()->error(['message' => 'wtf', 'code' => 400])));
    }

    public function testSuccessFactory(): void
    {
        $response = JSendResponse::success(['Erfolgreich!']);

        $this->assertTrue($response->getStatus()->isSuccess());
        $this->assertEquals(['Erfolgreich!'], $response->getData());
    }

    public function testFailFactory(): void
    {
        $response = JSendResponse::fail(['Irgendwas lief schief']);

        $this->assertTrue($response->getStatus()->isFail());
        $this->assertEquals(['Irgendwas lief schief'], $response->getData());
    }

    public function testErrorFactory(): void
    {
        $response = JSendResponse::error('Es ist ein Fehler aufgetreten');

        $this->assertTrue($response->getStatus()->isError());
        $this->assertEmpty($response->getData());
        $this->assertEquals('Es ist ein Fehler aufgetreten', $response->getError()->getMessage());
    }

    public function testSuccessConversion(): void
    {
        $json = '{"status": "success", "data": ["Holy", "Moly"]}';

        $success = new DummyResponse();
        $success->withBody(new DummyStream($json));
        $success->withStatus(214);

        $response = ResponseFactory::instance()->convert($success);
        $this->assertTrue($response->getStatus()->isSuccess());
        $this->assertEquals(['Holy', 'Moly'], $response->getData());
        $this->assertJsonStringEqualsJsonString($json, json_encode($response));
    }

    public function testFailConversion(): void
    {
        $fail = new DummyResponse();
        $fail->withBody(new DummyStream('{"status": "fail", "data": null}'));

        $response = ResponseFactory::instance()->convert($fail);
        $this->assertTrue($response->getStatus()->isFail());
        $this->assertEmpty($response->getData());
        $this->assertJsonStringEqualsJsonString('{"status": "fail", "data": null}', json_encode($response));
    }

    public function testErrorConversion(): void
    {
        $json = '{"status": "error", "data": ["Invalid"], "message": "Something is not right..."}';

        $error = new DummyResponse();
        $error->withBody(new DummyStream($json));
        $error->withStatus(501);

        $result         = json_decode($json, true);
        $result['code'] = $error->getStatusCode();

        $response = ResponseFactory::instance()->convert($error);
        $this->assertTrue($response->getStatus()->isError());
        $this->assertEquals(['Invalid'], $response->getData());
        $this->assertJsonStringEqualsJsonString(json_encode($result), json_encode($response));
        $this->assertEquals('Something is not right...', $response->getError()->getMessage());
        $this->assertEquals($error->getStatusCode(), $response->getError()->getCode());
    }

    public function testMapping(): void
    {
        $response = new JSendResponse(Status::translate(1), null);
        $this->assertTrue($response->getStatus()->isSuccess());

        $response = new JSendResponse(Status::translate(0), null);
        $this->assertTrue($response->getStatus()->isFail());

        $response = new JSendResponse(Status::translate(-1), null);
        $this->assertTrue($response->getStatus()->isError());

        $response = new JSendResponse(Status::translate(true), null);
        $this->assertTrue($response->getStatus()->isSuccess());

        $response = new JSendResponse(Status::translate(false), null);
        $this->assertTrue($response->getStatus()->isFail());

        $response = new JSendResponse(Status::translate(false, [false => StatusInterface::STATUS_ERROR]), null);
        $this->assertTrue($response->getStatus()->isError());
    }

    public function testPsr7Response(): void
    {
        $response = JSendResponse::success(['Erfolgreich!'])->asResponse(200);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('{"status":"success","data":["Erfolgreich!"]}', $response->getBody()->getContents());

        $response = JSendResponse::fail(['Irgendwas lief schief'])->asResponse(400);
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('{"status":"fail","data":["Irgendwas lief schief"]}', $response->getBody()->getContents());

        $response = JSendResponse::error('Es ist ein Fehler aufgetreten', 404)->asResponse(500);
        $this->assertEquals(500, $response->getStatusCode());
        $this->assertEquals('{"status":"error","message":"Es ist ein Fehler aufgetreten","code":404}', $response->getBody()->getContents());

        $response = JSendResponse::success(['Stimmt der Header?'])->asResponse();
        $this->assertEquals(['application/json'], $response->getHeader('content-type'));

        $response = JSendResponse::success(['Eigene Header werden übernommen'])->asResponse(null, ['foo' => 'bar']);
        $this->assertEquals(['bar'], $response->getHeader('foo'));
    }

    public function testPsr7ResponseOptionalCode(): void
    {
        $response = JSendResponse::success(['Erfolgreich!'])->asResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('{"status":"success","data":["Erfolgreich!"]}', $response->getBody()->getContents());

        $response = JSendResponse::fail(['Irgendwas lief schief'])->asResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('{"status":"fail","data":["Irgendwas lief schief"]}', $response->getBody()->getContents());

        $response = JSendResponse::error('Es ist ein Fehler aufgetreten', 404)->asResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('{"status":"error","message":"Es ist ein Fehler aufgetreten","code":404}', $response->getBody()->getContents());

        $response = JSendResponse::error('Es ist ein Fehler aufgetreten')->asResponse();
        $this->assertEquals(500, $response->getStatusCode());
        $this->assertEquals('{"status":"error","message":"Es ist ein Fehler aufgetreten"}', $response->getBody()->getContents());
    }
}
