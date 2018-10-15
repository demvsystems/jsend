<?php

namespace Demv\JSend\Test;

use Demv\JSend\JSend;
use Demv\JSend\Status;
use Dgame\Ensurance\Exception\EnsuranceException;
use Exception;
use PHPUnit\Framework\TestCase;

final class JSendBasicTest extends TestCase
{
    /**
     *
     */
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

        $result = JSend::from(
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

        $this->assertJsonStringEqualsJsonString($json, json_encode($result));
    }

    /**
     *
     */
    public function testEncodeError(): void
    {
        $json = '{
            "status" : "error",
            "message" : "Unable to communicate with database"
        }';

        $result = JSend::from(['status' => 'error', 'message' => 'Unable to communicate with database']);
        $this->assertJsonStringEqualsJsonString($json, $result->encode());
    }

    /**
     * @throws Exception
     */
    public function testEncodeErrorWithoutMessage(): void
    {
        $this->expectException(EnsuranceException::class);
        $this->expectExceptionMessage('Field "message" is required');

        JSend::from(['status' => 'error'])->into();
    }

    /**
     * @throws Exception
     */
    public function testEncodeEmpty(): void
    {
        $this->expectException(EnsuranceException::class);
        $this->expectExceptionMessage('Field "status" is required');

        JSend::from([])->into();
    }

    /**
     *
     */
    public function testEncodeWithoutStatus(): void
    {
        $this->expectException(EnsuranceException::class);
        $this->expectExceptionMessage('Field "status" is required');

        JSend::from(['msg' => 'Foo']);
    }

    /**
     * @throws \Demv\JSend\InvalidJsonException
     * @throws EnsuranceException
     */
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
        $this->assertTrue($response->isSuccess());
        $this->assertFalse($response->isFail());
        $this->assertFalse($response->isError());
        $this->assertNotEmpty($response->into()->getData());
        $this->assertEquals(
            [
                'post' => [
                    'id'    => 2,
                    'title' => 'Another blog post',
                    'body'  => 'More content'
                ]
            ],
            $response->into()->getData()
        );
        $this->assertJsonStringEqualsJsonString($json, json_encode($response));
    }

    /**
     * @throws EnsuranceException
     * @throws \Demv\JSend\InvalidJsonException
     */
    public function testSuccessResponseWithoutData(): void
    {
        $json = '{ "status": "success" }';
        $this->expectException(EnsuranceException::class);
        $this->expectExceptionMessage('Field "data" is required');
        JSend::decode($json)->into();
    }

    /**
     * @throws \Demv\JSend\InvalidJsonException
     */
    public function testSuccessResponseWithNullData(): void
    {
        $json     = '{ "status": "success", "data": null }';
        $response = JSend::decode($json);
        $this->assertTrue($response->isSuccess());
        $this->assertEmpty($response->intoSuccess()->getData());
        $this->assertJsonStringEqualsJsonString($json, $response->encode());
    }

    /**
     * @throws \Demv\JSend\InvalidJsonException
     */
    public function testSuccessResponseWithEmptyData(): void
    {
        $json     = '{ "status": "success", "data": [] }';
        $response = JSend::decode($json);
        $this->assertTrue($response->isSuccess());
        $this->assertEmpty($response->intoSuccess()->getData());
        $this->assertJsonStringEqualsJsonString($json, json_encode($response));
    }

    /**
     * @throws \Demv\JSend\InvalidJsonException
     */
    public function testFailResponse(): void
    {
        $json = '{
            "status" : "fail",
            "data" : { "title" : "A title is required" }
        }';

        $response = JSend::decode($json);
        $this->assertTrue($response->isFail());
        $this->assertEquals(['title' => 'A title is required'], $response->intoFail()->getData());
        $this->assertJsonStringEqualsJsonString($json, json_encode($response));
    }

    /**
     * @throws \Demv\JSend\InvalidJsonException
     */
    public function testGetErrorOnNoneError(): void
    {
        $response = Jsend::decode('{"status": "success", "data": null}');
        $this->expectException(EnsuranceException::class);
        $this->expectExceptionMessage('Field "message" is required');
        $response->intoError();
    }

    /**
     * @throws \Demv\JSend\InvalidJsonException
     */
    public function testGetErrorOnNoneErrorWithMessageField(): void
    {
        $response = Jsend::decode('{"status": "success", "message": ""}');
        $this->expectException(EnsuranceException::class);
        $this->expectExceptionMessage('Decode non-error in JSendError');
        $response->intoError();
    }

    /**
     * @throws \Demv\JSend\InvalidJsonException
     */
    public function testErrorResponse(): void
    {
        $json = '{
            "status" : "error",
            "message" : "Unable to communicate with database"
        }';

        $response = JSend::decode($json);
        $this->assertTrue($response->isError());
        $this->assertEquals('Unable to communicate with database', $response->intoError()->getMessage());
        $this->assertJsonStringEqualsJsonString($json, json_encode($response));
    }

    /**
     *
     */
    public function testSuccess(): void
    {
        $this->assertTrue(Status::success()->isSuccess());
        $json = '{"status": "success", "data": null}';
        $this->assertJsonStringEqualsJsonString($json, JSend::success()->encode());
    }

    /**
     *
     */
    public function testFail(): void
    {
        $this->assertTrue(Status::fail()->isFail());
        $json = '{"status": "fail", "data": null}';
        $this->assertJsonStringEqualsJsonString($json, JSend::fail()->encode());
    }

    /**
     *
     */
    public function testError(): void
    {
        $this->assertTrue(Status::error()->isError());
        $json = '{"message": "", "code": null, "status": "error"}';
        $this->assertJsonStringEqualsJsonString($json, JSend::error('')->encode());
    }
}
