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

namespace StusDevKit\MissingBitsKit\Tests\Unit\Json;

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use StusDevKit\MissingBitsKit\Json\JsonValidationError;

/**
 * Contract + behaviour tests for the JsonValidationError value
 * object.
 *
 * The combination reads as "here's what it IS, here's what it
 * DOES": identity and shape pin the published surface, and the
 * behaviour section exercises the constructor + getters.
 */
#[TestDox(JsonValidationError::class)]
class JsonValidationErrorTest extends TestCase
{
    // ================================================================
    //
    // Class identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\MissingBitsKit\\Json namespace')]
    public function test_lives_in_the_expected_namespace(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the published namespace is part of the contract - callers
        // type-hint against the FQN, so moving it is a breaking
        // change that must go through a major version bump.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'StusDevKit\\MissingBitsKit\\Json';

        // ----------------------------------------------------------------
        // perform the change

        $actual = (new ReflectionClass(JsonValidationError::class))
            ->getNamespaceName();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // Published method set
    //
    // ----------------------------------------------------------------

    #[TestDox('exposes only __construct(), getCode() and getMessage() as public methods')]
    public function test_exposes_only_the_expected_public_methods(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the stored fields are private - callers reach them only
        // through the getters, so the published surface is the
        // constructor + getters. Pin the set by enumeration so any
        // addition, rename, or new getter is caught with a diff
        // that names the change.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['__construct', 'getCode', 'getMessage'];
        $reflection = new ReflectionClass(JsonValidationError::class);

        // ----------------------------------------------------------------
        // perform the change

        $actual = array_map(
            static fn ($method) => $method->getName(),
            $reflection->getMethods(ReflectionMethod::IS_PUBLIC),
        );
        sort($actual);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // __construct() signature
    //
    // ----------------------------------------------------------------

    #[TestDox('::__construct() declares $code and $message as parameters in that order')]
    public function test_construct_declares_expected_parameters(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the parameter names are part of the published surface -
        // callers use named arguments for any call with more than
        // one parameter, so renaming a parameter is a breaking
        // change. Pin the names AND their order (by enumeration)
        // so either kind of change is caught with a diff that
        // names the offender.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['code', 'message'];
        $method = (new ReflectionClass(JsonValidationError::class))
            ->getMethod('__construct');

        // ----------------------------------------------------------------
        // perform the change

        $actual = array_map(
            static fn ($parameter) => $parameter->getName(),
            $method->getParameters(),
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // getCode() shape
    //
    // ----------------------------------------------------------------

    #[TestDox('->getCode() declares an int return type')]
    public function test_getCode_declares_an_int_return_type(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the return type is part of the published contract -
        // widening (e.g. to `int|string`) or narrowing (e.g. to a
        // specific union of JSON_ERROR_* constants) is a breaking
        // change for callers that type-hint around it.

        // ----------------------------------------------------------------
        // setup your test

        $method = (new ReflectionClass(JsonValidationError::class))
            ->getMethod('getCode');

        // ----------------------------------------------------------------
        // perform the change

        $returnType = $method->getReturnType();

        // ----------------------------------------------------------------
        // test the results

        $this->assertNotNull($returnType);
        $this->assertSame('int', (string) $returnType);
    }

    // ================================================================
    //
    // getMessage() shape
    //
    // ----------------------------------------------------------------

    #[TestDox('->getMessage() declares a string return type')]
    public function test_getMessage_declares_a_string_return_type(): void
    {
        // ----------------------------------------------------------------
        // setup your test

        $method = (new ReflectionClass(JsonValidationError::class))
            ->getMethod('getMessage');

        // ----------------------------------------------------------------
        // perform the change

        $returnType = $method->getReturnType();

        // ----------------------------------------------------------------
        // test the results

        $this->assertNotNull($returnType);
        $this->assertSame('string', (string) $returnType);
    }

    // ================================================================
    //
    // Constructor + getter behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('->getCode() returns the $code passed to the constructor')]
    public function test_getCode_returns_the_code_passed_to_the_constructor(): void
    {
        // ----------------------------------------------------------------
        // setup your test

        $error = new JsonValidationError(
            code: 4,
            message: 'Syntax error',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $error->getCode();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(4, $actual);
    }

    #[TestDox('->getMessage() returns the $message passed to the constructor')]
    public function test_getMessage_returns_the_message_passed_to_the_constructor(): void
    {
        // ----------------------------------------------------------------
        // setup your test

        $error = new JsonValidationError(
            code: 4,
            message: 'Syntax error',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $error->getMessage();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('Syntax error', $actual);
    }
}
