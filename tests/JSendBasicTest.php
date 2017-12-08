<?php

namespace Demv\JSend;

use BadMethodCallException;
use Dgame\Ensurance\Exception\EnsuranceException;
use PHPUnit\Framework\TestCase;

final class JSendBasicTest extends TestCase
{
    public function testEncodeSuccess(): void
    {
        $json = '{
            "status" : "success",
            "data" : {
                "post" : {
                    "id" : 1,
                    "title" : "A blog post",
                    "body" : "Some useful content"
                }
            }
        }';

        $result = JSend::encode(
            [
                'status' => 'success',
                'data'   => [
                    'post' => [
                        'id'    => 1,
                        'title' => 'A blog post',
                        'body'  => 'Some useful content'
                    ]
                ]
            ]
        );

        $this->assertJsonStringEqualsJsonString($json, $result);
    }

    public function testEncodeError(): void
    {
        $json = '{
            "status" : "error",
            "message" : "Unable to communicate with database"
        }';

        $result = JSend::encode(['status' => 'error', 'message' => 'Unable to communicate with database']);
        $this->assertJsonStringEqualsJsonString($json, $result);
    }

    public function testEncodeErrorWithoutMessage(): void
    {
        $this->expectException(EnsuranceException::class);
        $this->expectExceptionMessage('Need a descriptive error-message');

        JSend::encode(['status' => 'error']);
    }

    public function testEncodeEmpty(): void
    {
        $this->expectException(EnsuranceException::class);
        $this->expectExceptionMessage('Empty response cannot be converted to valid JSend-JSON');

        JSend::encode([]);
    }

    public function testEncodeWithoutStatus(): void
    {
        $this->expectException(EnsuranceException::class);
        $this->expectExceptionMessage('Key "status" is required');

        JSend::encode(['msg' => 'Foo']);
    }

    public function testSuccessResponse(): void
    {
        $json = '{
            "status" : "success",
            "data" : {
                "post" : {
                    "id" : 2,
                    "title" : "Another blog post",
                    "body" : "More content"
                }
            }
        }';

        $response = JSend::decode($json);
        $this->assertTrue($response->getStatus()->isSuccess());
        $this->assertFalse($response->getStatus()->isFail());
        $this->assertFalse($response->getStatus()->isError());
        $this->assertNotEmpty($response->getData());
        $this->assertEquals(
            [
                'post' => [
                    'id'    => 2,
                    'title' => 'Another blog post',
                    'body'  => 'More content'
                ]
            ],
            $response->getData()
        );
        $this->assertJsonStringEqualsJsonString($json, json_encode($response));
    }

    public function testSuccessResponseWithoutData(): void
    {
        $json = '{ "status": "success" }';
        $this->expectException(EnsuranceException::class);
        $this->expectExceptionMessage('Key "data" is required');
        JSend::decode($json);
    }

    public function testSuccessResponseWithNullData(): void
    {
        $json     = '{ "status": "success", "data": null }';
        $response = JSend::decode($json);
        $this->assertTrue($response->getStatus()->isSuccess());
        $this->assertEmpty($response->getData());
        $this->assertJsonStringEqualsJsonString($json, json_encode($response));
    }

    public function testSuccessResponseWithEmptyData(): void
    {
        $json     = '{ "status": "success", "data": [] }';
        $response = JSend::decode($json);
        $this->assertTrue($response->getStatus()->isSuccess());
        $this->assertEmpty($response->getData());
        $this->assertJsonStringEqualsJsonString($json, json_encode($response));
    }

    public function testFailResponse(): void
    {
        $json = '{
            "status" : "fail",
            "data" : { "title" : "A title is required" }
        }';

        $response = JSend::decode($json);
        $this->assertTrue($response->getStatus()->isFail());
        $this->assertEquals(['title' => 'A title is required'], $response->getData());
        $this->assertJsonStringEqualsJsonString($json, json_encode($response));
    }

    public function testGetErrorOnNoneError(): void
    {
        $response = Jsend::decode('{"status": "success", "data": null}');
        $this->expectException(BadMethodCallException::class);
        $this->expectExceptionMessage('This is not a JSend-Error');
        $response->getError();
    }

    public function testErrorResponse(): void
    {
        $json = '{
            "status" : "error",
            "message" : "Unable to communicate with database"
        }';

        $response = JSend::decode($json);
        $this->assertTrue($response->getStatus()->isError());
        $this->assertEquals('Unable to communicate with database', $response->getError()->getMessage());
        $this->assertJsonStringEqualsJsonString($json, json_encode($response));
    }
}