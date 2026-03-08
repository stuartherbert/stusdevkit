<?php

//
// Stu's Dev Kit
//
// Building blocks for assembling the things you need to build, in a way
// that will last.
//
// Copyright (c) 2026-present Stuart Herbert
// All rights reserved.
//
// Redistribution and use in source and binary forms, with or without
// modification, are permitted provided that the following conditions
// are met:
//
//   * Re-distributions of source code must retain the above copyright
//     notice, this list of conditions and the following disclaimer.
//
//   * Redistributions in binary form must reproduce the above copyright
//     notice, this list of conditions and the following disclaimer in
//     the documentation and/or other materials provided with the
//     distribution.
//
//   * Neither the names of the copyright holders nor the names of his
//     contributors may be used to endorse or promote products derived
//     from this software without specific prior written permission.
//
// THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
// "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
// LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
// FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
// COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
// INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
// BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
// LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
// CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
// LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
// ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
// POSSIBILITY OF SUCH DAMAGE.
//

declare(strict_types=1);

namespace StusDevKit\ExceptionsKit\Tests\Unit\Exceptions;

use Exception;
use JsonSerializable;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use StusDevKit\ExceptionsKit\Exceptions\Rfc9457ProblemDetailsException;
use Throwable;

#[TestDox('Rfc9457ProblemDetailsException')]
class Rfc9457ProblemDetailsExceptionTest extends TestCase
{
    // ================================================================
    //
    // Construction
    //
    // ----------------------------------------------------------------

    #[TestDox('can instantiate with required params only')]
    public function test_can_instantiate_with_required_params(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that we can create an instance with only
        // the required constructor parameters

        // ----------------------------------------------------------------
        // setup your test



        // ----------------------------------------------------------------
        // perform the change

        $unit = new Rfc9457ProblemDetailsException(
            type: "https://example.com/problems/not-found",
            status: 404,
            title: "Resource not found",
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(Rfc9457ProblemDetailsException::class, $unit);
    }

    #[TestDox('can instantiate with all params')]
    public function test_can_instantiate_with_all_params(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that we can create an instance with all
        // constructor parameters populated

        // ----------------------------------------------------------------
        // setup your test

        $previous = new RuntimeException("underlying cause");

        // ----------------------------------------------------------------
        // perform the change

        $unit = new Rfc9457ProblemDetailsException(
            type: "https://example.com/problems/insufficient-funds",
            status: 422,
            title: "Insufficient funds",
            extra: ['account_id' => 'abc-123', 'balance' => 30],
            detail: "Your account does not have enough funds",
            instance: "https://example.com/accounts/abc-123",
            previous: $previous,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(Rfc9457ProblemDetailsException::class, $unit);
    }

    #[TestDox('is an Exception')]
    public function test_is_an_exception(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that Rfc9457ProblemDetailsException
        // extends Exception and can be thrown/caught

        // ----------------------------------------------------------------
        // setup your test

        $unit = new Rfc9457ProblemDetailsException(
            type: "https://example.com/problems/server-error",
            status: 500,
            title: "Internal server error",
        );

        // ----------------------------------------------------------------
        // perform the change



        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(Exception::class, $unit);
        $this->assertInstanceOf(Throwable::class, $unit);
    }

    #[TestDox('implements JsonSerializable')]
    public function test_implements_json_serializable(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the exception implements
        // JsonSerializable

        // ----------------------------------------------------------------
        // setup your test

        $unit = new Rfc9457ProblemDetailsException(
            type: "https://example.com/problems/server-error",
            status: 500,
            title: "Internal server error",
        );

        // ----------------------------------------------------------------
        // perform the change



        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(JsonSerializable::class, $unit);
    }

    // ================================================================
    //
    // Exception Message
    //
    // ----------------------------------------------------------------

    #[TestDox('->getMessage() returns title when detail is null')]
    public function test_uses_title_as_message_when_detail_is_null(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the exception message falls back to
        // the title when no detail is provided

        // ----------------------------------------------------------------
        // setup your test



        // ----------------------------------------------------------------
        // perform the change

        $unit = new Rfc9457ProblemDetailsException(
            type: "https://example.com/problems/not-found",
            status: 404,
            title: "Resource not found",
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame("Resource not found", $unit->getMessage());
    }

    #[TestDox('->getMessage() returns detail when detail is provided')]
    public function test_uses_detail_as_message_when_detail_is_provided(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the exception message uses the detail
        // string when one is provided

        // ----------------------------------------------------------------
        // setup your test



        // ----------------------------------------------------------------
        // perform the change

        $unit = new Rfc9457ProblemDetailsException(
            type: "https://example.com/problems/not-found",
            status: 404,
            title: "Resource not found",
            detail: "The user with ID 42 was not found",
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            "The user with ID 42 was not found",
            $unit->getMessage(),
        );
    }

    #[TestDox('->getPrevious() returns previous exception when provided')]
    public function test_stores_previous_exception(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a previous exception is correctly
        // stored and retrievable via getPrevious()

        // ----------------------------------------------------------------
        // setup your test

        $previous = new RuntimeException("database connection failed");

        // ----------------------------------------------------------------
        // perform the change

        $unit = new Rfc9457ProblemDetailsException(
            type: "https://example.com/problems/server-error",
            status: 500,
            title: "Internal server error",
            previous: $previous,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($previous, $unit->getPrevious());
    }

    #[TestDox('->getPrevious() returns null by default')]
    public function test_previous_exception_is_null_by_default(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that getPrevious() returns null when no
        // previous exception was provided

        // ----------------------------------------------------------------
        // setup your test



        // ----------------------------------------------------------------
        // perform the change

        $unit = new Rfc9457ProblemDetailsException(
            type: "https://example.com/problems/not-found",
            status: 404,
            title: "Resource not found",
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertNull($unit->getPrevious());
    }

    // ================================================================
    //
    // getTypeAsString()
    //
    // ----------------------------------------------------------------

    #[TestDox('->getTypeAsString() returns value passed into constructor')]
    public function test_get_type_as_string_returns_type_uri(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that getTypeAsString() returns the type
        // URI that was passed to the constructor

        // ----------------------------------------------------------------
        // setup your test

        $expectedType = "https://example.com/problems/validation-error";

        // ----------------------------------------------------------------
        // perform the change

        $unit = new Rfc9457ProblemDetailsException(
            type: $expectedType,
            status: 422,
            title: "Validation error",
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expectedType, $unit->getTypeAsString());
    }

    // ================================================================
    //
    // getStatus()
    //
    // ----------------------------------------------------------------

    /**
     * @return array<string, array{int}>
     */
    public static function provideStatusCodes(): array
    {
        return [
            '400 Bad Request' => [400],
            '401 Unauthorized' => [401],
            '403 Forbidden' => [403],
            '404 Not Found' => [404],
            '409 Conflict' => [409],
            '422 Unprocessable Entity' => [422],
            '429 Too Many Requests' => [429],
            '500 Internal Server Error' => [500],
            '502 Bad Gateway' => [502],
            '503 Service Unavailable' => [503],
        ];
    }

    #[DataProvider('provideStatusCodes')]
    #[TestDox('->getStatus() returns HTTP status code passed into constructor')]
    public function test_get_status_returns_http_status_code(
        int $statusCode,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that getStatus() returns the HTTP status
        // code passed to the constructor

        // ----------------------------------------------------------------
        // setup your test



        // ----------------------------------------------------------------
        // perform the change

        $unit = new Rfc9457ProblemDetailsException(
            type: "https://example.com/problems/test",
            status: $statusCode,
            title: "Test problem",
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($statusCode, $unit->getStatus());
    }

    // ================================================================
    //
    // getTitle()
    //
    // ----------------------------------------------------------------

    #[TestDox('->getTitle() returns value passed into constructor')]
    public function test_get_title_returns_title(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that getTitle() returns the title string
        // passed to the constructor

        // ----------------------------------------------------------------
        // setup your test

        $expectedTitle = "Insufficient funds";

        // ----------------------------------------------------------------
        // perform the change

        $unit = new Rfc9457ProblemDetailsException(
            type: "https://example.com/problems/insufficient-funds",
            status: 422,
            title: $expectedTitle,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expectedTitle, $unit->getTitle());
    }

    // ================================================================
    //
    // hasDetail() / maybeGetDetail()
    //
    // ----------------------------------------------------------------

    #[TestDox('->hasDetail() returns false when no detail provided')]
    public function test_has_detail_returns_false_when_no_detail(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that hasDetail() returns false when no
        // detail string was provided to the constructor

        // ----------------------------------------------------------------
        // setup your test



        // ----------------------------------------------------------------
        // perform the change

        $unit = new Rfc9457ProblemDetailsException(
            type: "https://example.com/problems/not-found",
            status: 404,
            title: "Resource not found",
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($unit->hasDetail());
    }

    #[TestDox('->hasDetail() returns true when detail provided')]
    public function test_has_detail_returns_true_when_detail_provided(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that hasDetail() returns true when a
        // detail string was provided to the constructor

        // ----------------------------------------------------------------
        // setup your test



        // ----------------------------------------------------------------
        // perform the change

        $unit = new Rfc9457ProblemDetailsException(
            type: "https://example.com/problems/not-found",
            status: 404,
            title: "Resource not found",
            detail: "User 42 was not found in the database",
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($unit->hasDetail());
    }

    #[TestDox('->maybeGetDetail() returns null when no detail provided')]
    public function test_maybe_get_detail_returns_null_when_no_detail(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeGetDetail() returns null when
        // no detail string was provided to the constructor

        // ----------------------------------------------------------------
        // setup your test



        // ----------------------------------------------------------------
        // perform the change

        $unit = new Rfc9457ProblemDetailsException(
            type: "https://example.com/problems/not-found",
            status: 404,
            title: "Resource not found",
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertNull($unit->maybeGetDetail());
    }

    #[TestDox('->maybeGetDetail() returns detail when provided')]
    public function test_maybe_get_detail_returns_detail_when_provided(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeGetDetail() returns the detail
        // string when one was provided to the constructor

        // ----------------------------------------------------------------
        // setup your test

        $expectedDetail = "User 42 was not found in the database";

        // ----------------------------------------------------------------
        // perform the change

        $unit = new Rfc9457ProblemDetailsException(
            type: "https://example.com/problems/not-found",
            status: 404,
            title: "Resource not found",
            detail: $expectedDetail,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expectedDetail, $unit->maybeGetDetail());
    }

    // ================================================================
    //
    // hasInstance() / maybeGetInstanceAsString()
    //
    // ----------------------------------------------------------------

    #[TestDox('->hasInstance() returns false when no instance provided')]
    public function test_has_instance_returns_false_when_no_instance(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that hasInstance() returns false when no
        // instance URI was provided to the constructor

        // ----------------------------------------------------------------
        // setup your test



        // ----------------------------------------------------------------
        // perform the change

        $unit = new Rfc9457ProblemDetailsException(
            type: "https://example.com/problems/not-found",
            status: 404,
            title: "Resource not found",
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($unit->hasInstance());
    }

    #[TestDox('->hasInstance() returns true when instance provided')]
    public function test_has_instance_returns_true_when_instance_provided(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that hasInstance() returns true when an
        // instance URI was provided to the constructor

        // ----------------------------------------------------------------
        // setup your test



        // ----------------------------------------------------------------
        // perform the change

        $unit = new Rfc9457ProblemDetailsException(
            type: "https://example.com/problems/not-found",
            status: 404,
            title: "Resource not found",
            instance: "https://example.com/users/42",
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($unit->hasInstance());
    }

    #[TestDox('->maybeGetInstanceAsString() returns null when no instance provided')]
    public function test_maybe_get_instance_as_string_returns_null_when_no_instance(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeGetInstanceAsString() returns
        // null when no instance URI was provided

        // ----------------------------------------------------------------
        // setup your test



        // ----------------------------------------------------------------
        // perform the change

        $unit = new Rfc9457ProblemDetailsException(
            type: "https://example.com/problems/not-found",
            status: 404,
            title: "Resource not found",
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertNull($unit->maybeGetInstanceAsString());
    }

    #[TestDox('->maybeGetInstanceAsString() returns value passed into constructor')]
    public function test_maybe_get_instance_as_string_returns_instance_when_provided(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maybeGetInstanceAsString() returns
        // the instance URI when one was provided

        // ----------------------------------------------------------------
        // setup your test

        $expectedInstance = "https://example.com/users/42";

        // ----------------------------------------------------------------
        // perform the change

        $unit = new Rfc9457ProblemDetailsException(
            type: "https://example.com/problems/not-found",
            status: 404,
            title: "Resource not found",
            instance: $expectedInstance,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            $expectedInstance,
            $unit->maybeGetInstanceAsString(),
        );
    }

    // ================================================================
    //
    // hasExtra() / getExtra()
    //
    // ----------------------------------------------------------------

    #[TestDox('->hasExtra() returns false when no extra data provided')]
    public function test_has_extra_returns_false_when_no_extra(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that hasExtra() returns false when no
        // extra data was provided to the constructor

        // ----------------------------------------------------------------
        // setup your test



        // ----------------------------------------------------------------
        // perform the change

        $unit = new Rfc9457ProblemDetailsException(
            type: "https://example.com/problems/not-found",
            status: 404,
            title: "Resource not found",
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($unit->hasExtra());
    }

    #[TestDox('->hasExtra() returns true when extra data provided')]
    public function test_has_extra_returns_true_when_extra_provided(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that hasExtra() returns true when extra
        // data was provided to the constructor

        // ----------------------------------------------------------------
        // setup your test



        // ----------------------------------------------------------------
        // perform the change

        $unit = new Rfc9457ProblemDetailsException(
            type: "https://example.com/problems/insufficient-funds",
            status: 422,
            title: "Insufficient funds",
            extra: ['account_id' => 'abc-123', 'balance' => 30],
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($unit->hasExtra());
    }

    #[TestDox('->getExtra() returns empty array by default')]
    public function test_get_extra_returns_empty_array_by_default(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that getExtra() returns an empty array
        // when no extra data was provided

        // ----------------------------------------------------------------
        // setup your test



        // ----------------------------------------------------------------
        // perform the change

        $unit = new Rfc9457ProblemDetailsException(
            type: "https://example.com/problems/not-found",
            status: 404,
            title: "Resource not found",
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame([], $unit->getExtra());
    }

    #[TestDox('->getExtra() returns extra data passed into constructor')]
    public function test_get_extra_returns_extra_data(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that getExtra() returns the extra data
        // that was provided to the constructor

        // ----------------------------------------------------------------
        // setup your test

        $expectedExtra = [
            'account_id' => 'abc-123',
            'balance' => 30,
            'currency' => 'GBP',
        ];

        // ----------------------------------------------------------------
        // perform the change

        $unit = new Rfc9457ProblemDetailsException(
            type: "https://example.com/problems/insufficient-funds",
            status: 422,
            title: "Insufficient funds",
            extra: $expectedExtra,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expectedExtra, $unit->getExtra());
    }

    #[TestDox('->getExtra() returns nested extra data')]
    public function test_get_extra_returns_nested_extra_data(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that getExtra() correctly handles nested
        // arrays in the extra data

        // ----------------------------------------------------------------
        // setup your test

        $expectedExtra = [
            'account_id' => 'abc-123',
            'limits' => [
                'daily' => 1000,
                'monthly' => 5000,
            ],
        ];

        // ----------------------------------------------------------------
        // perform the change

        $unit = new Rfc9457ProblemDetailsException(
            type: "https://example.com/problems/limit-exceeded",
            status: 422,
            title: "Limit exceeded",
            extra: $expectedExtra,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expectedExtra, $unit->getExtra());
    }

    // ================================================================
    //
    // jsonSerialize()
    //
    // ----------------------------------------------------------------

    #[TestDox('->jsonSerialize() returns correct structure with minimal params')]
    public function test_json_serialize_with_minimal_params(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that jsonSerialize() returns the correct
        // structure when only required parameters are provided

        // ----------------------------------------------------------------
        // setup your test



        // ----------------------------------------------------------------
        // perform the change

        $unit = new Rfc9457ProblemDetailsException(
            type: "https://example.com/problems/not-found",
            status: 404,
            title: "Resource not found",
        );

        $result = $unit->jsonSerialize();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame("https://example.com/problems/not-found", $result['type']);
        $this->assertSame("Resource not found", $result['title']);
        $this->assertSame(404, $result['status']);
        $this->assertNull($result['instance']);
        $this->assertNull($result['detail']);
        $this->assertSame([], $result['extra']);
    }

    #[TestDox('->jsonSerialize() returns correct structure with all params')]
    public function test_json_serialize_with_all_params(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that jsonSerialize() returns the correct
        // structure when all parameters are provided

        // ----------------------------------------------------------------
        // setup your test

        $extra = [
            'account_id' => 'abc-123',
            'balance' => 30,
        ];

        // ----------------------------------------------------------------
        // perform the change

        $unit = new Rfc9457ProblemDetailsException(
            type: "https://example.com/problems/insufficient-funds",
            status: 422,
            title: "Insufficient funds",
            extra: $extra,
            detail: "Your account balance is too low",
            instance: "https://example.com/accounts/abc-123",
        );

        $result = $unit->jsonSerialize();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame("https://example.com/problems/insufficient-funds", $result['type']);
        $this->assertSame("Insufficient funds", $result['title']);
        $this->assertSame(422, $result['status']);
        $this->assertSame("https://example.com/accounts/abc-123", $result['instance']);
        $this->assertSame("Your account balance is too low", $result['detail']);
        $this->assertSame($extra, $result['extra']);
    }

    #[TestDox('->jsonSerialize() returns all six required keys')]
    public function test_json_serialize_contains_all_required_keys(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that jsonSerialize() always returns an
        // array containing all six required keys

        // ----------------------------------------------------------------
        // setup your test



        // ----------------------------------------------------------------
        // perform the change

        $unit = new Rfc9457ProblemDetailsException(
            type: "https://example.com/problems/test",
            status: 500,
            title: "Test problem",
        );

        $result = $unit->jsonSerialize();

        // ----------------------------------------------------------------
        // test the results

        $this->assertArrayHasKey('type', $result);
        $this->assertArrayHasKey('title', $result);
        $this->assertArrayHasKey('status', $result);
        $this->assertArrayHasKey('instance', $result);
        $this->assertArrayHasKey('detail', $result);
        $this->assertArrayHasKey('extra', $result);
        $this->assertCount(6, $result);
    }

    #[TestDox('json_encode($exception) produces valid JSON output')]
    public function test_json_encode_produces_valid_json(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the exception can be passed to
        // json_encode() and produces valid JSON output

        // ----------------------------------------------------------------
        // setup your test

        $unit = new Rfc9457ProblemDetailsException(
            type: "https://example.com/problems/insufficient-funds",
            status: 422,
            title: "Insufficient funds",
            extra: ['account_id' => 'abc-123', 'balance' => 30],
            detail: "Your account balance is too low",
            instance: "https://example.com/accounts/abc-123",
        );

        // ----------------------------------------------------------------
        // perform the change

        $json = json_encode($unit);

        // ----------------------------------------------------------------
        // test the results

        $this->assertIsString($json);
        $this->assertNotFalse($json);

        $decoded = json_decode($json, associative: true);
        $this->assertIsArray($decoded);
        $this->assertSame("https://example.com/problems/insufficient-funds", $decoded['type']);
        $this->assertSame("Insufficient funds", $decoded['title']);
        $this->assertSame(422, $decoded['status']);
        $this->assertSame("https://example.com/accounts/abc-123", $decoded['instance']);
        $this->assertSame("Your account balance is too low", $decoded['detail']);
        $this->assertSame(['account_id' => 'abc-123', 'balance' => 30], $decoded['extra']);
    }

    #[TestDox('can be thrown and caught with all properties intact')]
    public function test_can_be_thrown_and_caught(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the exception can be thrown and
        // caught, with all properties intact after catching

        // ----------------------------------------------------------------
        // setup your test

        $unit = new Rfc9457ProblemDetailsException(
            type: "https://example.com/problems/forbidden",
            status: 403,
            title: "Forbidden",
            extra: ['required_role' => 'admin'],
            detail: "You do not have permission to access this resource",
            instance: "https://example.com/resources/secret",
        );

        // ----------------------------------------------------------------
        // perform the change

        $caught = null;

        try {
            throw $unit;
        } catch (Rfc9457ProblemDetailsException $e) {
            $caught = $e;
        }

        // ----------------------------------------------------------------
        // test the results

        // @phpstan-ignore-next-line
        $this->assertNotNull($caught);
        $this->assertSame("https://example.com/problems/forbidden", $caught->getTypeAsString());
        $this->assertSame(403, $caught->getStatus());
        $this->assertSame("Forbidden", $caught->getTitle());
        $this->assertSame("You do not have permission to access this resource", $caught->maybeGetDetail());
        $this->assertSame("https://example.com/resources/secret", $caught->maybeGetInstanceAsString());
        $this->assertSame(['required_role' => 'admin'], $caught->getExtra());
    }
}
