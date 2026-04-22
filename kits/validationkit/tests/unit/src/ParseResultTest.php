<?php

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

declare(strict_types=1);

namespace StusDevKit\ValidationKit\Tests\Unit;

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use StusDevKit\ValidationKit\Exceptions\ValidationException;
use StusDevKit\ValidationKit\ParseResult;
use StusDevKit\ValidationKit\ValidationIssue;
use StusDevKit\ValidationKit\ValidationIssuesList;

/**
 * Contract + behaviour tests for ParseResult.
 *
 * ParseResult is the two-state discriminated result that a
 * schema's safeParse() returns: either ok($data) or
 * fail($exception). The constructor is private, so the
 * named constructors are the only way to produce instances;
 * succeeded()/failed() report which state applies. The
 * accessor pair (data()/maybeData() for the success slot,
 * error()/maybeError() for the failure slot) follows the
 * project's maybe/definite pattern: the throwing accessor
 * throws on the wrong state, the maybe accessor returns
 * null. These tests nail down both named constructors and
 * every accessor against both states.
 */
#[TestDox(ParseResult::class)]
class ParseResultTest extends TestCase
{
    // ================================================================
    //
    // Class identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\ValidationKit namespace')]
    public function test_lives_in_the_expected_namespace(): void
    {
        $expected = 'StusDevKit\\ValidationKit';

        $actual = (new ReflectionClass(ParseResult::class))
            ->getNamespaceName();

        $this->assertSame($expected, $actual);
    }

    #[TestDox('is a final class')]
    public function test_is_final(): void
    {
        // ParseResult is a closed two-state sum type; allowing
        // subclasses would let callers introduce new states
        // that the rest of the library has no handling for.
        $reflection = new ReflectionClass(ParseResult::class);

        $this->assertTrue($reflection->isFinal());
    }

    #[TestDox('has a private constructor so ::ok() and ::fail() are the only builders')]
    public function test_constructor_is_private(): void
    {
        // forcing callers through the named constructors keeps
        // the success/failure invariants honest: ok() always
        // has data, fail() always has an exception.
        $reflection = new ReflectionClass(ParseResult::class);
        $ctor = $reflection->getConstructor();

        $this->assertNotNull($ctor);
        $this->assertTrue($ctor->isPrivate());
    }

    // ================================================================
    //
    // Named constructors
    //
    // ----------------------------------------------------------------

    #[TestDox('::ok() produces a ParseResult in the success state')]
    public function test_ok_produces_success_state(): void
    {
        // ::ok() is the success constructor; the returned
        // object's state is observed through succeeded().
        $result = ParseResult::ok('hello');

        $this->assertInstanceOf(ParseResult::class, $result);
        $this->assertTrue($result->succeeded());
        $this->assertFalse($result->failed());
    }

    #[TestDox('::fail() produces a ParseResult in the failure state')]
    public function test_fail_produces_failure_state(): void
    {
        // ::fail() needs a ValidationException to carry; we
        // build the minimum valid one (empty issues list) so
        // the test focuses on state-flag wiring.
        $exception = new ValidationException(new ValidationIssuesList());

        $result = ParseResult::fail($exception);

        $this->assertInstanceOf(ParseResult::class, $result);
        $this->assertTrue($result->failed());
        $this->assertFalse($result->succeeded());
    }

    // ================================================================
    //
    // Data accessors on success
    //
    // ----------------------------------------------------------------

    #[TestDox('->data() returns the stored value when the parse succeeded')]
    public function test_data_returns_value_on_success(): void
    {
        // data() is the throwing accessor for the success
        // slot; on a successful result it hands back exactly
        // what was passed to ::ok().
        $result = ParseResult::ok('hello');

        $this->assertSame('hello', $result->data());
    }

    #[TestDox('->maybeData() returns the stored value when the parse succeeded')]
    public function test_maybeData_returns_value_on_success(): void
    {
        // maybeData() shares the success-path behaviour with
        // data(); the two only diverge on failure.
        $result = ParseResult::ok(42);

        $this->assertSame(42, $result->maybeData());
    }

    #[TestDox('->maybeError() returns null when the parse succeeded')]
    public function test_maybeError_returns_null_on_success(): void
    {
        // a successful result has no error, so the
        // non-throwing error accessor must return null.
        $result = ParseResult::ok('hello');

        $this->assertNull($result->maybeError());
    }

    // ================================================================
    //
    // Data accessors on failure
    //
    // ----------------------------------------------------------------

    #[TestDox('->data() throws the stored ValidationException when the parse failed')]
    public function test_data_throws_on_failure(): void
    {
        // robustness!
        // on a failed result, data() has no value to return;
        // contract is to re-throw the stored exception so
        // callers that forgot to check succeeded() still get a
        // loud failure.
        $exception = new ValidationException(new ValidationIssuesList());
        $result = ParseResult::fail($exception);

        $this->expectException(ValidationException::class);

        $result->data();
    }

    #[TestDox('->maybeData() returns null when the parse failed')]
    public function test_maybeData_returns_null_on_failure(): void
    {
        // maybeData() is the non-throwing partner; on failure
        // it returns null instead of throwing.
        $exception = new ValidationException(new ValidationIssuesList());
        $result = ParseResult::fail($exception);

        $this->assertNull($result->maybeData());
    }

    // ================================================================
    //
    // Error accessors on failure
    //
    // ----------------------------------------------------------------

    #[TestDox('->error() returns the stored ValidationException when the parse failed')]
    public function test_error_returns_exception_on_failure(): void
    {
        // error() is the throwing accessor for the failure
        // slot; on a failed result it returns exactly the
        // exception passed to ::fail().
        $exception = new ValidationException(new ValidationIssuesList());
        $result = ParseResult::fail($exception);

        $this->assertSame($exception, $result->error());
    }

    #[TestDox('->maybeError() returns the stored ValidationException when the parse failed')]
    public function test_maybeError_returns_exception_on_failure(): void
    {
        // maybeError() shares the failure-path behaviour with
        // error(); only the success-path branch differs.
        $exception = new ValidationException(new ValidationIssuesList());
        $result = ParseResult::fail($exception);

        $this->assertSame($exception, $result->maybeError());
    }

    // ================================================================
    //
    // Error accessors on success
    //
    // ----------------------------------------------------------------

    #[TestDox('->error() throws a ValidationException when the parse succeeded')]
    public function test_error_throws_on_success(): void
    {
        // robustness!
        // there is no error to return on a successful result;
        // throwing makes the caller's bug (asking for an error
        // they do not have) visible.
        $result = ParseResult::ok('hello');

        $this->expectException(ValidationException::class);

        $result->error();
    }

    // ================================================================
    //
    // Round-trip payloads
    //
    // ----------------------------------------------------------------

    #[TestDox('preserves the exact ValidationIssue list carried by the exception on failure')]
    public function test_failure_preserves_issue_payload(): void
    {
        // real failures carry issue data; the result must
        // hand the same ValidationException back untouched so
        // formatters see every issue.
        $issues = new ValidationIssuesList();
        $issues->add(new ValidationIssue(
            type: 'tag:stusdevkit,2026:test:problem',
            input: 'bad',
            path: ['field'],
            message: 'Expected int, received string',
        ));
        $exception = new ValidationException($issues);

        $result = ParseResult::fail($exception);

        $this->assertSame($issues, $result->error()->issues());
    }
}
