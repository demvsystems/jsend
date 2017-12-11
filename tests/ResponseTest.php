<?php

namespace Demv\JSend\Test;

use Demv\JSend\JSend;
use Demv\JSend\ResponseFactory;
use PHPUnit\Framework\TestCase;

final class ResponseTest extends TestCase
{
    public function testDefaultHttpStatusCode()
    {
        $this->assertEquals(200, JSend::getDefaultHttpStatusCode(ResponseFactory::instance()->success()));
        $this->assertEquals(null, JSend::getDefaultHttpStatusCode(ResponseFactory::instance()->fail()));
        $this->assertEquals(400, JSend::getDefaultHttpStatusCode(ResponseFactory::instance()->error(['message' => 'wtf'])));
        $this->assertEquals(500, JSend::getDefaultHttpStatusCode(ResponseFactory::instance()->error(['message' => 'wtf', 'code' => 500])));
    }

    public function testSuccessConversion()
    {
        $success = new DummyResponse();
        $success->withBody(new DummyStream('{"data": ["Holy", "Moly"]}'));
        $success->withStatus(214);

        $response = ResponseFactory::instance()->convert($success);
        $this->assertTrue($response->getStatus()->isSuccess());
        $this->assertEquals(['Holy', 'Moly'], $response->getData());
        $this->assertJsonStringEqualsJsonString('{"status": "success", "data": ["Holy", "Moly"]}', json_encode($response));
    }

    public function testFailConversion()
    {
        $fail = new DummyResponse();
        $fail->withBody(new DummyStream('{}'));

        $response = ResponseFactory::instance()->convert($fail);
        $this->assertTrue($response->getStatus()->isFail());
        $this->assertEmpty($response->getData());
        $this->assertJsonStringEqualsJsonString('{"status": "fail", "data": null}', json_encode($response));
    }

    public function testErrorConversion()
    {
        $json = '{"data": ["Invalid"], "message": "Something is not right..."}';

        $error = new DummyResponse();
        $error->withBody(new DummyStream($json));
        $error->withStatus(501);

        $result           = json_decode($json, true);
        $result['status'] = 'error';
        $result['code']   = $error->getStatusCode();

        $response = ResponseFactory::instance()->convert($error);
        $this->assertTrue($response->getStatus()->isError());
        $this->assertEquals(['Invalid'], $response->getData());
        $this->assertJsonStringEqualsJsonString(json_encode($result), json_encode($response));
        $this->assertEquals('Something is not right...', $response->getError()->getMessage());
        $this->assertEquals($error->getStatusCode(), $response->getError()->getCode());
    }
}