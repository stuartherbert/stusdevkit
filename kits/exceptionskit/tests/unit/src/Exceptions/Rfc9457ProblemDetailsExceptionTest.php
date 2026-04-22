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

namespace StusDevKit\ExceptionsKit\Tests\Unit\Exceptions;

use Exception;
use JsonSerializable;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
use RuntimeException;
use StusDevKit\ExceptionsKit\Exceptions\Rfc9457ProblemDetailsException;
use Throwable;

/**
 * Contract + behaviour tests for Rfc9457ProblemDetailsException.
 *
 * These tests lock down two things at once: the published API
 * surface of the class (constructor parameters, getter method set,
 * return types) and the runtime behaviour of every accessor. Any
 * change to either - a new getter, a renamed parameter, a silent
 * shift in jsonSerialize() key shape - must fail here with a
 * named diagnostic.
 */
#[TestDox(Rfc9457ProblemDetailsException::class)]
class Rfc9457ProblemDetailsExceptionTest extends TestCase
{
    // ================================================================
    //
    // Class identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\ExceptionsKit\\Exceptions namespace')]
    public function test_lives_in_the_expected_namespace(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the published namespace is part of the contract - callers
        // import the class by FQN, so moving it is a breaking change
        // that must go through a major version bump.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'StusDevKit\\ExceptionsKit\\Exceptions';

        // ----------------------------------------------------------------
        // perform the change

        $actual = (new ReflectionClass(
            Rfc9457ProblemDetailsException::class,
        ))->getNamespaceName();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('is declared as a class')]
    public function test_is_declared_as_a_class(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // Rfc9457ProblemDetailsException is a concrete throwable class
        // - not a trait, not an interface, not an enum. Pinning this
        // prevents a silent reshape from slipping past review.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(
            Rfc9457ProblemDetailsException::class,
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = (! $reflection->isInterface())
            && (! $reflection->isTrait())
            && (! $reflection->isEnum());

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('extends Exception')]
    public function test_extends_exception(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // extending PHP's built-in Exception is what makes instances
        // throwable through the normal `throw` / `catch` machinery and
        // gives them getMessage() / getPrevious() / getFile() /
        // getLine() / getTrace() for free. Swapping the parent for
        // something else would break every call site that catches
        // `\Exception` expecting this class.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(
            Rfc9457ProblemDetailsException::class,
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $reflection->getParentClass();

        // ----------------------------------------------------------------
        // test the results

        $this->assertNotFalse($actual);
        $this->assertSame(Exception::class, $actual->getName());
    }

    #[TestDox('implements JsonSerializable')]
    public function test_implements_json_serializable(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the class promises a JSON wire format for problem-detail
        // responses by implementing JsonSerializable, which is how
        // json_encode() learns to call jsonSerialize() instead of
        // dumping the raw Exception properties. Dropping the
        // interface would silently change the serialised payload.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(
            Rfc9457ProblemDetailsException::class,
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $reflection->implementsInterface(
            JsonSerializable::class,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('exposes only the expected public methods declared on the class')]
    public function test_exposes_only_the_expected_public_methods(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the declared-here public method set is pinned by enumeration.
        // Any new public method on the class (or a renamed one) shows
        // up as a diff that names the specific member, rather than as
        // a cryptic count mismatch. Inherited methods from Exception
        // are excluded - those belong to the parent's contract.

        // ----------------------------------------------------------------
        // setup your test

        $expected = [
            '__construct',
            'hasDetail',
            'maybeGetDetail',
            'hasExtra',
            'getExtra',
            'hasInstance',
            'maybeGetInstanceAsString',
            'getStatus',
            'getTitle',
            'getTypeAsString',
            'jsonSerialize',
        ];
        $reflection = new ReflectionClass(
            Rfc9457ProblemDetailsException::class,
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = array_values(array_map(
            static fn ($method) => $method->getName(),
            array_filter(
                $reflection->getMethods(ReflectionMethod::IS_PUBLIC),
                static fn ($method) => $method->getDeclaringClass()
                    ->getName() === Rfc9457ProblemDetailsException::class,
            ),
        ));

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // ::__construct() shape
    //
    // ----------------------------------------------------------------

    #[TestDox('::__construct() is declared')]
    public function test_construct_is_declared(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the constructor is the single entry point for populating an
        // instance. Losing the declaration would fall through to
        // Exception's default constructor, silently dropping every
        // problem-details slot.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(
            Rfc9457ProblemDetailsException::class,
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $reflection->hasMethod('__construct');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('::__construct() is public')]
    public function test_construct_is_public(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the constructor must be public so callers can `throw new
        // Rfc9457ProblemDetailsException(...)`. A protected or private
        // constructor would make the class unthrowable from user code.

        // ----------------------------------------------------------------
        // setup your test

        $method = (new ReflectionClass(
            Rfc9457ProblemDetailsException::class,
        ))->getMethod('__construct');

        // ----------------------------------------------------------------
        // perform the change

        $actual = $method->isPublic();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('::__construct() declares its parameters in the expected order')]
    public function test_construct_declares_its_parameters_in_the_expected_order(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the published parameter set is pinned by enumeration, in
        // order. Callers use named arguments (`type:`, `status:`,
        // `title:`, ...), so renaming is a breaking change; positional
        // callers also exist, so reordering is too. A diff naming the
        // specific drift is more useful than a count mismatch.

        // ----------------------------------------------------------------
        // setup your test

        $expected = [
            'type',
            'status',
            'title',
            'extra',
            'detail',
            'instance',
            'previous',
        ];
        $method = (new ReflectionClass(
            Rfc9457ProblemDetailsException::class,
        ))->getMethod('__construct');

        // ----------------------------------------------------------------
        // perform the change

        $actual = array_map(
            static fn ($param) => $param->getName(),
            $method->getParameters(),
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    /**
     * parameter-name / expected-native-type pairs for the
     * constructor's scalar and array parameters
     *
     * @return array<string, array{string, string}>
     */
    public static function provideScalarConstructorParams(): array
    {
        return [
            'type'     => ['type', 'string'],
            'status'   => ['status', 'int'],
            'title'    => ['title', 'string'],
            'extra'    => ['extra', 'array'],
            'detail'   => ['detail', 'string'],
            'instance' => ['instance', 'string'],
        ];
    }

    #[TestDox('::__construct() declares $$paramName as $expectedType')]
    #[DataProvider('provideScalarConstructorParams')]
    public function test_construct_declares_scalar_parameter_types(
        string $paramName,
        string $expectedType,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // each constructor parameter's native type is part of the
        // published contract. Widening (`string` -> `mixed`) or
        // narrowing (`int` -> a specific enum) changes what call sites
        // are allowed to pass and must be an intentional change.
        //
        // nullable parameters (`detail`, `instance`) still report their
        // inner type as `string` via ReflectionNamedType::getName() -
        // nullability is a separate flag tested further down.

        // ----------------------------------------------------------------
        // setup your test

        $method = (new ReflectionClass(
            Rfc9457ProblemDetailsException::class,
        ))->getMethod('__construct');
        $params = $method->getParameters();
        $paramsByName = [];
        foreach ($params as $param) {
            $paramsByName[$param->getName()] = $param;
        }
        $this->assertArrayHasKey($paramName, $paramsByName);
        $paramType = $paramsByName[$paramName]->getType();
        $this->assertInstanceOf(ReflectionNamedType::class, $paramType);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $paramType->getName();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expectedType, $actual);
    }

    #[TestDox('::__construct() declares $previous as ?Throwable')]
    public function test_construct_declares_previous_as_throwable(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // `previous` accepts any Throwable (including the SPL hierarchy
        // and userland exceptions) and is nullable so callers can omit
        // it. Binding it to a concrete exception class here would break
        // exception-chaining for anything that is not that class.

        // ----------------------------------------------------------------
        // setup your test

        $params = (new ReflectionClass(
            Rfc9457ProblemDetailsException::class,
        ))->getMethod('__construct')->getParameters();
        $previousParam = null;
        foreach ($params as $param) {
            if ($param->getName() === 'previous') {
                $previousParam = $param;
                break;
            }
        }
        $this->assertNotNull($previousParam);
        $paramType = $previousParam->getType();
        $this->assertInstanceOf(ReflectionNamedType::class, $paramType);

        // ----------------------------------------------------------------
        // perform the change

        $actualName = $paramType->getName();
        $actualNullable = $paramType->allowsNull();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(Throwable::class, $actualName);
        $this->assertTrue($actualNullable);
    }

    /**
     * constructor parameters that carry defaults, and the default
     * values the signature declares for each
     *
     * @return array<string, array{string, mixed}>
     */
    public static function provideOptionalConstructorParams(): array
    {
        return [
            'extra'    => ['extra', []],
            'detail'   => ['detail', null],
            'instance' => ['instance', null],
            'previous' => ['previous', null],
        ];
    }

    #[TestDox('::__construct() declares a default value for $$paramName so it is optional')]
    #[DataProvider('provideOptionalConstructorParams')]
    public function test_construct_declares_default_for_optional_parameter(
        string $paramName,
        mixed $expectedDefault,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // `type`, `status`, and `title` are mandatory per RFC 9457,
        // but `extra`, `detail`, `instance`, and `previous` are all
        // optional. Their defaults are part of the published contract
        // - callers rely on being able to omit them, so removing a
        // default would be a breaking change. Pinning the default
        // value (not just the fact of having one) guards against
        // silent changes like flipping `extra = []` to `extra = null`.

        // ----------------------------------------------------------------
        // setup your test

        $params = (new ReflectionClass(
            Rfc9457ProblemDetailsException::class,
        ))->getMethod('__construct')->getParameters();
        $target = null;
        foreach ($params as $param) {
            if ($param->getName() === $paramName) {
                $target = $param;
                break;
            }
        }
        $this->assertNotNull($target);
        $this->assertTrue($target->isDefaultValueAvailable());

        // ----------------------------------------------------------------
        // perform the change

        $actual = $target->getDefaultValue();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expectedDefault, $actual);
    }

    // ================================================================
    //
    // ::__construct() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::__construct() accepts the required parameters only')]
    public function test_construct_accepts_required_parameters_only(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // type, status, and title are the mandatory RFC 9457 members.
        // Supplying just these three must succeed - the optional
        // parameters (extra, detail, instance, previous) all carry
        // defaults on the constructor signature.

        // ----------------------------------------------------------------
        // setup your test

        // nothing to do

        // ----------------------------------------------------------------
        // perform the change

        $unit = new Rfc9457ProblemDetailsException(
            type: 'https://example.com/problems/not-found',
            status: 404,
            title: 'Resource not found',
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(
            Rfc9457ProblemDetailsException::class,
            $unit,
        );
    }

    #[TestDox('::__construct() accepts all parameters')]
    public function test_construct_accepts_all_parameters(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // every parameter the signature declares must accept a value
        // without complaint. Pinning the full-parameter path
        // separately catches a regression where a later parameter is
        // silently ignored by a parent-class constructor change.

        // ----------------------------------------------------------------
        // setup your test

        $previous = new RuntimeException('underlying cause');

        // ----------------------------------------------------------------
        // perform the change

        $unit = new Rfc9457ProblemDetailsException(
            type: 'https://example.com/problems/insufficient-funds',
            status: 422,
            title: 'Insufficient funds',
            extra: ['account_id' => 'abc-123', 'balance' => 30],
            detail: 'Your account does not have enough funds',
            instance: 'https://example.com/accounts/abc-123',
            previous: $previous,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(
            Rfc9457ProblemDetailsException::class,
            $unit,
        );
    }

    #[TestDox('::__construct() produces a Throwable')]
    public function test_construct_produces_a_throwable(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // instances are throwable through the normal PHP machinery.
        // Pinning Throwable directly (alongside Exception) guards
        // against a future refactor that changes the parent class
        // to one that breaks either catch path.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new Rfc9457ProblemDetailsException(
            type: 'https://example.com/problems/server-error',
            status: 500,
            title: 'Internal server error',
        );

        // ----------------------------------------------------------------
        // perform the change

        // nothing to do - instance-of is the assertion

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(Exception::class, $unit);
        $this->assertInstanceOf(Throwable::class, $unit);
    }

    // ================================================================
    //
    // ->getMessage() behaviour
    //
    // The built-in Exception::getMessage() is populated by this
    // class from either `detail` (when present) or `title` (as a
    // fallback). That fallback exists so RFC 9457 responses always
    // have a human-readable string available, even when no
    // per-occurrence detail was supplied.
    //
    // ----------------------------------------------------------------

    #[TestDox('->getMessage() returns the title when no detail was provided')]
    public function test_getMessage_falls_back_to_title_when_no_detail(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // with no `detail`, the parent Exception's message slot is
        // populated from `title`. This guarantees that catchers who
        // log `$e->getMessage()` still get a useful string even for
        // minimally-populated instances.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new Rfc9457ProblemDetailsException(
            type: 'https://example.com/problems/not-found',
            status: 404,
            title: 'Resource not found',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->getMessage();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('Resource not found', $actual);
    }

    #[TestDox('->getMessage() returns the detail string when one was provided')]
    public function test_getMessage_returns_detail_when_provided(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // when a `detail` is supplied, it is the more specific
        // description of this occurrence and must override the
        // generic title in the Exception message slot.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new Rfc9457ProblemDetailsException(
            type: 'https://example.com/problems/not-found',
            status: 404,
            title: 'Resource not found',
            detail: 'The user with ID 42 was not found',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->getMessage();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('The user with ID 42 was not found', $actual);
    }

    // ================================================================
    //
    // ->getPrevious() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('->getPrevious() returns null when no previous exception was provided')]
    public function test_getPrevious_returns_null_by_default(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the `previous` parameter defaults to null. In that case,
        // the built-in Exception chain must report no predecessor -
        // callers walking the chain rely on getPrevious() returning
        // null as the terminator.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new Rfc9457ProblemDetailsException(
            type: 'https://example.com/problems/not-found',
            status: 404,
            title: 'Resource not found',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->getPrevious();

        // ----------------------------------------------------------------
        // test the results

        $this->assertNull($actual);
    }

    #[TestDox('->getPrevious() returns the previous exception when one was provided')]
    public function test_getPrevious_returns_provided_previous(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the `previous` parameter must round-trip unchanged through
        // the parent Exception constructor so `getPrevious()` returns
        // the exact instance the caller passed in. Identity (not just
        // equality) matters because downstream code often uses the
        // chain to find a specific cause.

        // ----------------------------------------------------------------
        // setup your test

        $previous = new RuntimeException('database connection failed');
        $unit = new Rfc9457ProblemDetailsException(
            type: 'https://example.com/problems/server-error',
            status: 500,
            title: 'Internal server error',
            previous: $previous,
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->getPrevious();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($previous, $actual);
    }

    // ================================================================
    //
    // ->getTypeAsString() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('->getTypeAsString() returns the type URI passed into the constructor')]
    public function test_getTypeAsString_returns_the_type_uri(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // `type` is the RFC 9457 field that points at documentation
        // for this class of problem. It must round-trip unchanged -
        // callers building response bodies rely on the exact URI the
        // thrower supplied.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new Rfc9457ProblemDetailsException(
            type: 'https://example.com/problems/validation-error',
            status: 422,
            title: 'Validation error',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->getTypeAsString();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            'https://example.com/problems/validation-error',
            $actual,
        );
    }

    // ================================================================
    //
    // ->getStatus() behaviour
    //
    // ----------------------------------------------------------------

    /**
     * representative HTTP status codes a 4xx/5xx problem-details
     * response might carry
     *
     * @return array<string, array{int}>
     */
    public static function provideStatusCodes(): array
    {
        return [
            '400 Bad Request'           => [400],
            '401 Unauthorized'          => [401],
            '403 Forbidden'             => [403],
            '404 Not Found'             => [404],
            '409 Conflict'              => [409],
            '422 Unprocessable Entity'  => [422],
            '429 Too Many Requests'     => [429],
            '500 Internal Server Error' => [500],
            '502 Bad Gateway'           => [502],
            '503 Service Unavailable'   => [503],
        ];
    }

    #[TestDox('->getStatus() returns $statusCode when $statusCode was passed into the constructor')]
    #[DataProvider('provideStatusCodes')]
    public function test_getStatus_returns_the_http_status_code(
        int $statusCode,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // the HTTP status must round-trip unchanged for every
        // supported code. Sweeping a representative set (4xx + 5xx)
        // catches a regression where the class silently clamps,
        // coerces, or overrides the caller's value.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new Rfc9457ProblemDetailsException(
            type: 'https://example.com/problems/test',
            status: $statusCode,
            title: 'Test problem',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->getStatus();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($statusCode, $actual);
    }

    // ================================================================
    //
    // ->getTitle() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('->getTitle() returns the title passed into the constructor')]
    public function test_getTitle_returns_the_title(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the RFC 9457 `title` field is a human-readable summary of
        // the problem type. It must round-trip unchanged so response
        // bodies carry exactly the string the thrower chose.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new Rfc9457ProblemDetailsException(
            type: 'https://example.com/problems/insufficient-funds',
            status: 422,
            title: 'Insufficient funds',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->getTitle();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('Insufficient funds', $actual);
    }

    // ================================================================
    //
    // ->hasDetail() / ->maybeGetDetail() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('->hasDetail() returns false when no detail was provided')]
    public function test_hasDetail_returns_false_when_no_detail(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // hasDetail() is the safety check paired with maybeGetDetail().
        // It must report false for the optional-omitted case so
        // callers can branch on it without first calling
        // maybeGetDetail() and comparing against null themselves.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new Rfc9457ProblemDetailsException(
            type: 'https://example.com/problems/not-found',
            status: 404,
            title: 'Resource not found',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->hasDetail();

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($actual);
    }

    #[TestDox('->hasDetail() returns true when a detail was provided')]
    public function test_hasDetail_returns_true_when_detail_provided(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // when the caller supplies a detail string, hasDetail() must
        // report true. Paired with the @phpstan-assert-if-true on the
        // source docblock, this narrows maybeGetDetail()'s return
        // type to non-empty-string after a hasDetail() check.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new Rfc9457ProblemDetailsException(
            type: 'https://example.com/problems/not-found',
            status: 404,
            title: 'Resource not found',
            detail: 'User 42 was not found in the database',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->hasDetail();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('->maybeGetDetail() returns null when no detail was provided')]
    public function test_maybeGetDetail_returns_null_when_no_detail(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the `maybe*` accessor returns null rather than throwing
        // when the optional slot is empty - that is the point of
        // the `maybe` prefix in this codebase's naming convention.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new Rfc9457ProblemDetailsException(
            type: 'https://example.com/problems/not-found',
            status: 404,
            title: 'Resource not found',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->maybeGetDetail();

        // ----------------------------------------------------------------
        // test the results

        $this->assertNull($actual);
    }

    #[TestDox('->maybeGetDetail() returns the detail string when one was provided')]
    public function test_maybeGetDetail_returns_provided_detail(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // when a detail was supplied, it must round-trip unchanged
        // so the RFC 9457 response body and any downstream logging
        // see exactly what the thrower wrote.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new Rfc9457ProblemDetailsException(
            type: 'https://example.com/problems/not-found',
            status: 404,
            title: 'Resource not found',
            detail: 'User 42 was not found in the database',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->maybeGetDetail();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            'User 42 was not found in the database',
            $actual,
        );
    }

    // ================================================================
    //
    // ->hasInstance() / ->maybeGetInstanceAsString() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('->hasInstance() returns false when no instance URI was provided')]
    public function test_hasInstance_returns_false_when_no_instance(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // hasInstance() is the safety check paired with
        // maybeGetInstanceAsString(). It must report false for the
        // optional-omitted case so callers can branch on it directly.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new Rfc9457ProblemDetailsException(
            type: 'https://example.com/problems/not-found',
            status: 404,
            title: 'Resource not found',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->hasInstance();

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($actual);
    }

    #[TestDox('->hasInstance() returns true when an instance URI was provided')]
    public function test_hasInstance_returns_true_when_instance_provided(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // when the caller supplies an instance URI, hasInstance() must
        // report true. Paired with the @phpstan-assert-if-true on the
        // source docblock, this narrows maybeGetInstanceAsString()'s
        // return type to non-empty-string after a hasInstance() check.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new Rfc9457ProblemDetailsException(
            type: 'https://example.com/problems/not-found',
            status: 404,
            title: 'Resource not found',
            instance: 'https://example.com/users/42',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->hasInstance();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('->maybeGetInstanceAsString() returns null when no instance URI was provided')]
    public function test_maybeGetInstanceAsString_returns_null_when_no_instance(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the `maybe*` accessor returns null rather than throwing
        // when the optional instance slot is empty, matching the
        // same convention as maybeGetDetail().

        // ----------------------------------------------------------------
        // setup your test

        $unit = new Rfc9457ProblemDetailsException(
            type: 'https://example.com/problems/not-found',
            status: 404,
            title: 'Resource not found',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->maybeGetInstanceAsString();

        // ----------------------------------------------------------------
        // test the results

        $this->assertNull($actual);
    }

    #[TestDox('->maybeGetInstanceAsString() returns the instance URI when one was provided')]
    public function test_maybeGetInstanceAsString_returns_provided_instance(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // when an instance URI was supplied, it must round-trip
        // unchanged - the accessor's suffix `AsString` reflects a
        // forward-compatibility note in the source: a future release
        // will add a second accessor returning a PHP 8.5 Uri object.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new Rfc9457ProblemDetailsException(
            type: 'https://example.com/problems/not-found',
            status: 404,
            title: 'Resource not found',
            instance: 'https://example.com/users/42',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->maybeGetInstanceAsString();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('https://example.com/users/42', $actual);
    }

    // ================================================================
    //
    // ->hasExtra() / ->getExtra() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('->hasExtra() returns false when no extra data was provided')]
    public function test_hasExtra_returns_false_when_no_extra(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // hasExtra() must report false for the default-empty case so
        // callers can skip the extra-data branch in response
        // builders. The parameter defaults to `[]`, which counts as
        // "no extra" for this accessor.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new Rfc9457ProblemDetailsException(
            type: 'https://example.com/problems/not-found',
            status: 404,
            title: 'Resource not found',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->hasExtra();

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($actual);
    }

    #[TestDox('->hasExtra() returns true when extra data was provided')]
    public function test_hasExtra_returns_true_when_extra_provided(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // when the caller supplies any non-empty extra-data array,
        // hasExtra() must report true. Downstream response builders
        // use this to decide whether to emit an `extra` member in
        // the serialised body.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new Rfc9457ProblemDetailsException(
            type: 'https://example.com/problems/insufficient-funds',
            status: 422,
            title: 'Insufficient funds',
            extra: ['account_id' => 'abc-123', 'balance' => 30],
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->hasExtra();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('->getExtra() returns an empty array by default')]
    public function test_getExtra_returns_empty_array_by_default(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // with no extra-data argument, the accessor must return an
        // empty array - not null. That lets callers iterate the
        // result unconditionally (`foreach ($extra as ...)`) without
        // a null guard, which is simpler and less error-prone.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new Rfc9457ProblemDetailsException(
            type: 'https://example.com/problems/not-found',
            status: 404,
            title: 'Resource not found',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->getExtra();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame([], $actual);
    }

    #[TestDox('->getExtra() returns the extra data passed into the constructor')]
    public function test_getExtra_returns_provided_extra(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the extra-data array must round-trip unchanged - shape,
        // order, and values. Downstream log aggregators and response
        // builders rely on exact equality with what the thrower
        // supplied.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new Rfc9457ProblemDetailsException(
            type: 'https://example.com/problems/insufficient-funds',
            status: 422,
            title: 'Insufficient funds',
            extra: [
                'account_id' => 'abc-123',
                'balance'    => 30,
                'currency'   => 'GBP',
            ],
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->getExtra();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            [
                'account_id' => 'abc-123',
                'balance'    => 30,
                'currency'   => 'GBP',
            ],
            $actual,
        );
    }

    #[TestDox('->getExtra() preserves nested extra data structure')]
    public function test_getExtra_preserves_nested_structure(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the @phpstan-type definitions on the source file allow for
        // one level of nesting inside extra-data values. This test
        // pins that a nested structure survives the round-trip
        // intact - no flattening, no key-order reshuffling.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new Rfc9457ProblemDetailsException(
            type: 'https://example.com/problems/limit-exceeded',
            status: 422,
            title: 'Limit exceeded',
            extra: [
                'account_id' => 'abc-123',
                'limits'     => [
                    'daily'   => 1000,
                    'monthly' => 5000,
                ],
            ],
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->getExtra();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            [
                'account_id' => 'abc-123',
                'limits'     => [
                    'daily'   => 1000,
                    'monthly' => 5000,
                ],
            ],
            $actual,
        );
    }

    // ================================================================
    //
    // ->jsonSerialize() behaviour
    //
    // jsonSerialize() is the RFC 9457 wire format. The key set is
    // fixed at six members (type, title, status, instance, detail,
    // extra) regardless of which optional parameters the caller
    // supplied - response consumers learn to expect all six.
    //
    // ----------------------------------------------------------------

    #[TestDox('->jsonSerialize() returns the six fixed keys in the documented order')]
    public function test_jsonSerialize_returns_the_six_fixed_keys(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the wire contract is an array with exactly these six keys,
        // in this order. Pinning the enumerated key set (not just the
        // cardinality) means any rename or addition fails with a diff
        // that names the offender.

        // ----------------------------------------------------------------
        // setup your test

        $expected = [
            'type',
            'title',
            'status',
            'instance',
            'detail',
            'extra',
        ];
        $unit = new Rfc9457ProblemDetailsException(
            type: 'https://example.com/problems/test',
            status: 500,
            title: 'Test problem',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = array_keys($unit->jsonSerialize());

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('->jsonSerialize() nulls the optional slots when they were not provided')]
    public function test_jsonSerialize_nulls_unprovided_optional_slots(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // with only the required parameters supplied, the serialised
        // payload must carry:
        //   - type / title / status populated from the caller's values
        //   - instance / detail as explicit nulls (not missing keys)
        //   - extra as an empty array (not null, not missing)
        //
        // explicit nulls matter: RFC 9457 consumers can tell a "this
        // field was intentionally omitted" from a "this field was
        // accidentally dropped" by the literal null. An empty array
        // for `extra` follows the same convention as getExtra().

        // ----------------------------------------------------------------
        // setup your test

        $unit = new Rfc9457ProblemDetailsException(
            type: 'https://example.com/problems/not-found',
            status: 404,
            title: 'Resource not found',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->jsonSerialize();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            [
                'type'     => 'https://example.com/problems/not-found',
                'title'    => 'Resource not found',
                'status'   => 404,
                'instance' => null,
                'detail'   => null,
                'extra'    => [],
            ],
            $actual,
        );
    }

    #[TestDox('->jsonSerialize() populates every key when every parameter was provided')]
    public function test_jsonSerialize_populates_every_key_when_all_provided(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // with every optional parameter supplied, the serialised
        // payload must round-trip each value into the matching key.
        // This pins the one-to-one mapping between constructor
        // parameter and serialised member so a parameter rename that
        // forgets to update the serialiser shows up here.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new Rfc9457ProblemDetailsException(
            type: 'https://example.com/problems/insufficient-funds',
            status: 422,
            title: 'Insufficient funds',
            extra: [
                'account_id' => 'abc-123',
                'balance'    => 30,
            ],
            detail: 'Your account balance is too low',
            instance: 'https://example.com/accounts/abc-123',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->jsonSerialize();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            [
                'type'     => 'https://example.com/problems/insufficient-funds',
                'title'    => 'Insufficient funds',
                'status'   => 422,
                'instance' => 'https://example.com/accounts/abc-123',
                'detail'   => 'Your account balance is too low',
                'extra'    => [
                    'account_id' => 'abc-123',
                    'balance'    => 30,
                ],
            ],
            $actual,
        );
    }

    #[TestDox('->jsonSerialize() produces valid JSON when consumed by json_encode()')]
    public function test_jsonSerialize_is_consumable_by_json_encode(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // implementing JsonSerializable is pointless unless the
        // resulting array actually encodes cleanly. This test is the
        // end-to-end round-trip: instantiate, json_encode, json_decode,
        // assert equality. A regression where jsonSerialize() returned
        // something non-encodable (e.g. a resource, a closure) would
        // surface here as a false return from json_encode().

        // ----------------------------------------------------------------
        // setup your test

        $unit = new Rfc9457ProblemDetailsException(
            type: 'https://example.com/problems/insufficient-funds',
            status: 422,
            title: 'Insufficient funds',
            extra: ['account_id' => 'abc-123', 'balance' => 30],
            detail: 'Your account balance is too low',
            instance: 'https://example.com/accounts/abc-123',
        );

        // ----------------------------------------------------------------
        // perform the change

        $json = json_encode($unit);

        // ----------------------------------------------------------------
        // test the results

        $this->assertIsString($json);
        $decoded = json_decode($json, associative: true);
        $this->assertSame(
            [
                'type'     => 'https://example.com/problems/insufficient-funds',
                'title'    => 'Insufficient funds',
                'status'   => 422,
                'instance' => 'https://example.com/accounts/abc-123',
                'detail'   => 'Your account balance is too low',
                'extra'    => [
                    'account_id' => 'abc-123',
                    'balance'    => 30,
                ],
            ],
            $decoded,
        );
    }

    // ================================================================
    //
    // Throw / catch round-trip
    //
    // ----------------------------------------------------------------

    #[TestDox('survives throw and catch with every accessor returning the constructor values')]
    public function test_survives_throw_and_catch_with_all_properties_intact(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the final cross-check: instantiate with every slot
        // populated, throw, catch as the published type, and verify
        // every accessor still returns what the constructor received.
        // This covers the "what really happens in production" path
        // in a single test - if any part of the accessor chain broke
        // between construction and catch, this fails.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new Rfc9457ProblemDetailsException(
            type: 'https://example.com/problems/forbidden',
            status: 403,
            title: 'Forbidden',
            extra: ['required_role' => 'admin'],
            detail: 'You do not have permission to access this resource',
            instance: 'https://example.com/resources/secret',
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

        $this->assertNotNull($caught);
        $this->assertSame(
            'https://example.com/problems/forbidden',
            $caught->getTypeAsString(),
        );
        $this->assertSame(403, $caught->getStatus());
        $this->assertSame('Forbidden', $caught->getTitle());
        $this->assertSame(
            'You do not have permission to access this resource',
            $caught->maybeGetDetail(),
        );
        $this->assertSame(
            'https://example.com/resources/secret',
            $caught->maybeGetInstanceAsString(),
        );
        $this->assertSame(
            ['required_role' => 'admin'],
            $caught->getExtra(),
        );
    }
}
