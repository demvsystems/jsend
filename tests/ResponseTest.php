<?php

namespace Demv\JSend\Test;

use Demv\JSend\JSend;
use Demv\JSend\ResponseFactory;
use Demv\JSend\Status;
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

    public function testSuccessBuild(): void
    {
        $json = '{"status": "success", "data": null}';

        $response = JSend::build()->success()->createResponse();
        $this->assertTrue($response->getStatus()->isSuccess());
        $this->assertEmpty($response->getData());
        $this->assertJsonStringEqualsJsonString($json, json_encode($response));

        $json = '{"status": "success", "data": ["Holy", "Moly"]}';

        $response = JSend::build()->success()->setData(['Holy', 'Moly'])->createResponse();
        $this->assertTrue($response->getStatus()->isSuccess());
        $this->assertEquals(['Holy', 'Moly'], $response->getData());
        $this->assertJsonStringEqualsJsonString($json, json_encode($response));
    }

    public function testStatusObjectToStringBuild(): void
    {
        $json = '{"status": "success", "data": ["Holy", "Moly"]}';

        $response = JSend::build()->setStatus(Status::success())->setData(['Holy', 'Moly'])->createResponse();
        $this->assertTrue($response->getStatus()->isSuccess());
        $this->assertEquals(['Holy', 'Moly'], $response->getData());
        $this->assertJsonStringEqualsJsonString($json, json_encode($response));
    }

    public function testFailBuild(): void
    {
        $json = '{"status": "fail", "data": []}';

        $response = JSend::build()->fail()->setData([])->createResponse();
        $this->assertTrue($response->getStatus()->isFail());
        $this->assertEmpty($response->getData());
        $this->assertJsonStringEqualsJsonString($json, json_encode($response));
    }

    public function testErrorBuild(): void
    {
        $json = '{"status": "error", "data": ["Invalid"], "message": "Something is not right...", "code": 42}';

        $response = JSend::build()->error()->setData(['Invalid'])->setMessage('Something is not right...')->setCode(42)->createResponse();
        $this->assertTrue($response->getStatus()->isError());
        $this->assertEquals(['Invalid'], $response->getData());
        $this->assertJsonStringEqualsJsonString($json, json_encode($response));
        $this->assertEquals('Something is not right...', $response->getError()->getMessage());
        $this->assertEquals(42, $response->getError()->getCode());
    }
}