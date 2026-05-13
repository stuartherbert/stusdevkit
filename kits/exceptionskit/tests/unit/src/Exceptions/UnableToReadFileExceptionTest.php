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

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
use StusDevKit\ExceptionsKit\Exceptions\Rfc9457ProblemDetailsException;
use StusDevKit\ExceptionsKit\Exceptions\UnableToReadFileException;

/**
 * Contract + behaviour tests for UnableToReadFileException.
 *
 * UnableToReadFileException is a fixed-shape specialisation of
 * Rfc9457ProblemDetailsException: `type`, `status`, and `title` are
 * hard-coded, and the caller-supplied file path is carried in the
 * `extra` slot under the `file_path` key. The caller may also hand
 * in a PHP error record (typically the return value of
 * `error_get_last()`); when non-null it lands in `extra` under the
 * `php_error` key so the diagnostic that PHP emitted around the
 * failure travels with the exception. These tests lock down the
 * subclass contract (parent class, constructor shape) plus the
 * constant values the constructor pins.
 */
#[TestDox(UnableToReadFileException::class)]
class UnableToReadFileExceptionTest extends TestCase
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
        // import UnableToReadFileException by FQN, so moving it is a
        // breaking change that must go through a major version bump.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'StusDevKit\\ExceptionsKit\\Exceptions';

        // ----------------------------------------------------------------
        // perform the change

        $actual = (new ReflectionClass(UnableToReadFileException::class))
            ->getNamespaceName();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('is declared as a class')]
    public function test_is_declared_as_a_class(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // UnableToReadFileException is a concrete throwable class -
        // not a trait, not an interface, not an enum. Pinning this
        // prevents a silent reshape (e.g. promoting to an interface)
        // from slipping past review.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(UnableToReadFileException::class);

        // ----------------------------------------------------------------
        // perform the change

        $actual = (! $reflection->isInterface())
            && (! $reflection->isTrait())
            && (! $reflection->isEnum());

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('extends Rfc9457ProblemDetailsException')]
    public function test_extends_rfc9457_problem_details_exception(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the parent class is the RFC 9457 problem-details base, which
        // gives this exception its JsonSerializable wire format,
        // getExtra() accessor, and status/title/type getters. Swapping
        // the parent for a different Exception subclass would silently
        // drop every one of those features from anything that catches
        // UnableToReadFileException.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(UnableToReadFileException::class);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $reflection->getParentClass();

        // ----------------------------------------------------------------
        // test the results

        $this->assertNotFalse($actual);
        $this->assertSame(
            Rfc9457ProblemDetailsException::class,
            $actual->getName(),
        );
    }

    #[TestDox('declares no additional public methods beyond its parent')]
    public function test_declares_no_additional_public_methods(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this subclass exists to pre-fill the parent's `type`,
        // `status`, and `title`, and to stash the file path (plus the
        // caller-supplied PHP error record, when non-null) in `extra`
        // - nothing more. Any new public method declared on the
        // subclass is a surface-area expansion the parent would not
        // pick up, so the declared-here method set is pinned by
        // enumeration.
        //
        // notably there are no `getFilePath()` / `getPhpError()`
        // helpers - callers fetch the file path from the parent's
        // getExtra()['file_path'] and the PHP error from
        // getExtra()['php_error']. Adding helpers later would be a
        // deliberate API addition that must update this list.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['__construct'];
        $reflection = new ReflectionClass(UnableToReadFileException::class);

        // ----------------------------------------------------------------
        // perform the change

        $actual = array_values(array_map(
            static fn ($method) => $method->getName(),
            array_filter(
                $reflection->getMethods(ReflectionMethod::IS_PUBLIC),
                static fn ($method) => $method->getDeclaringClass()
                    ->getName() === UnableToReadFileException::class,
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

        // this subclass overrides the parent constructor to narrow
        // the parameter set down to `filePath` plus an optional
        // `phpError` record. Losing the override would mean every
        // caller suddenly has to supply type / status / title / extra
        // again, which would be a silent breaking change.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(UnableToReadFileException::class);

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
        // UnableToReadFileException(...)`. A protected or private
        // constructor would make the class unthrowable from user code.

        // ----------------------------------------------------------------
        // setup your test

        $method = (new ReflectionClass(UnableToReadFileException::class))
            ->getMethod('__construct');

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
        // order. Callers use named arguments (`filePath:`,
        // `phpError:`), so renaming is a breaking change; positional
        // callers also exist, so reordering is too. A diff naming the
        // specific drift is more useful than a count mismatch.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['filePath', 'phpError'];
        $method = (new ReflectionClass(UnableToReadFileException::class))
            ->getMethod('__construct');

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

    #[TestDox('::__construct() declares $filePath as string')]
    public function test_construct_declares_filePath_as_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // `filePath` must be a string - it is the path that could not
        // be read. Widening this to `mixed` or narrowing to
        // `non-empty-string` would each change what a call site is
        // allowed to pass.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'string';
        $param = (new ReflectionClass(UnableToReadFileException::class))
            ->getMethod('__construct')->getParameters()[0];
        $paramType = $param->getType();
        $this->assertInstanceOf(ReflectionNamedType::class, $paramType);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $paramType->getName();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::__construct() declares $phpError as a nullable array, defaulting to null')]
    public function test_construct_declares_phpError_as_nullable_array_default_null(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // `phpError` carries the caller's captured error_get_last()
        // result, whose native PHP return type is `?array`. Pinning
        // both the named type (`array`) AND the allowsNull flag
        // guards against a future widening to `mixed` or to a
        // non-nullable `array` - either would change what callers
        // are required to pass.
        //
        // the default value is `null` so callers who do not have a
        // PHP error to attach can omit the argument entirely
        // (`new UnableToReadFileException(filePath: $p)`). Pinning
        // the default here means a silent change to `[]` or any
        // other sentinel would fail this test.

        // ----------------------------------------------------------------
        // setup your test

        $param = (new ReflectionClass(UnableToReadFileException::class))
            ->getMethod('__construct')->getParameters()[1];
        $paramType = $param->getType();
        $this->assertInstanceOf(ReflectionNamedType::class, $paramType);

        // ----------------------------------------------------------------
        // perform the change

        $actualName = $paramType->getName();
        $actualAllowsNull = $paramType->allowsNull();
        $actualIsOptional = $param->isOptional();
        $actualDefault = $param->getDefaultValue();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('array', $actualName);
        $this->assertTrue($actualAllowsNull);
        $this->assertTrue($actualIsOptional);
        $this->assertNull($actualDefault);
    }

    // ================================================================
    //
    // ::__construct() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::__construct() accepts a file path string')]
    public function test_construct_accepts_a_file_path_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the constructor's published contract is "given a file path
        // string, produce an instance". Pinning instantiation as its
        // own test means a silent regression in the parent-constructor
        // chain (e.g. a required parameter added upstream) surfaces
        // here rather than only in downstream tests.

        // ----------------------------------------------------------------
        // setup your test

        // nothing to do

        // ----------------------------------------------------------------
        // perform the change

        $unit = new UnableToReadFileException(
            filePath: '/tmp/unreadable.txt',
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(UnableToReadFileException::class, $unit);
    }

    #[TestDox('->getTypeAsString() returns the fixed type URI')]
    public function test_getTypeAsString_returns_the_fixed_type_uri(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the type URI is a fixed documentation link baked into the
        // constructor - it must not vary per throw-site. Pinning the
        // literal value here guards against accidental edits in the
        // source file (a typo in the URL would break every consumer
        // that navigates from problem-details responses to docs).

        // ----------------------------------------------------------------
        // setup your test

        $unit = new UnableToReadFileException(
            filePath: '/tmp/unreadable.txt',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->getTypeAsString();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            'https://example.com/errors/unable-to-read-file',
            $actual,
        );
    }

    #[TestDox('->getStatus() returns 422')]
    public function test_getStatus_returns_422(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // 422 Unprocessable Content is the RFC-correct status for a
        // request whose syntax was fine but whose semantics (a file
        // the server cannot read) could not be processed. Pinning the
        // literal here prevents accidental reclassification to a
        // generic 400 or 500.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new UnableToReadFileException(
            filePath: '/tmp/unreadable.txt',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->getStatus();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(422, $actual);
    }

    #[TestDox('->getTitle() returns the fixed title')]
    public function test_getTitle_returns_the_fixed_title(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the title is the short, human-readable summary shown in the
        // RFC 9457 body. It is a fixed string chosen to match the
        // exception name - pinning the literal guards against
        // accidental edits that would leave responses inconsistent
        // with the exception class.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new UnableToReadFileException(
            filePath: '/tmp/unreadable.txt',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->getTitle();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('Unable to read file', $actual);
    }

    #[TestDox('->hasExtra() returns true because the file path is stored in extra')]
    public function test_hasExtra_returns_true(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this exception always carries the file path in the extra
        // slot, so hasExtra() must report true. Downstream response
        // builders rely on this to decide whether to emit the `extra`
        // member in the serialised body.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new UnableToReadFileException(
            filePath: '/tmp/unreadable.txt',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->hasExtra();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('->getExtra() carries only the file path when the caller omits phpError')]
    public function test_getExtra_carries_only_file_path_when_phpError_is_null(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // when the caller does not pass a PHP error record (or
        // explicitly passes null), the constructor must NOT
        // introduce a `php_error` key with a null value. The wire
        // payload stays minimal - only the `file_path` key is
        // present. Downstream consumers can rely on the key being
        // absent to mean "no PHP-level diagnostic was available at
        // the throw site".

        // ----------------------------------------------------------------
        // setup your test

        $unit = new UnableToReadFileException(
            filePath: '/tmp/unreadable.txt',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->getExtra();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            ['file_path' => '/tmp/unreadable.txt'],
            $actual,
        );
    }

    #[TestDox('->getExtra() carries the caller-supplied PHP error under the "php_error" key when phpError is non-null')]
    public function test_getExtra_carries_php_error_when_supplied(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // when the caller hands in a PHP error record (typically the
        // return value of error_get_last()), the constructor must
        // stash that record verbatim under the snake_case key
        // `php_error` so downstream log aggregators and problem-
        // details responders can surface the diagnostic that PHP
        // itself produced around the failure. The record's own keys
        // (type / message / file / line) round-trip unchanged.

        // ----------------------------------------------------------------
        // setup your test

        // a literal record matching the shape error_get_last() would
        // return - keeps the test deterministic and OS-independent.
        $phpError = [
            'type' => E_WARNING,
            'message' => 'file_get_contents(): failed to open stream',
            'file' => '/var/www/html/some/caller.php',
            'line' => 42,
        ];
        $unit = new UnableToReadFileException(
            filePath: '/tmp/unreadable.txt',
            phpError: $phpError,
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->getExtra();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            [
                'file_path' => '/tmp/unreadable.txt',
                'php_error' => $phpError,
            ],
            $actual,
        );
    }

    #[TestDox('->maybeGetDetail() returns null because no detail is set')]
    public function test_maybeGetDetail_returns_null(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // UnableToReadFileException does not populate the RFC 9457
        // `detail` slot - the file path and PHP error are carried in
        // `extra` instead. That means maybeGetDetail() must return
        // null, and getMessage() falls back to the fixed title
        // (tested below).

        // ----------------------------------------------------------------
        // setup your test

        $unit = new UnableToReadFileException(
            filePath: '/tmp/unreadable.txt',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->maybeGetDetail();

        // ----------------------------------------------------------------
        // test the results

        $this->assertNull($actual);
    }

    #[TestDox('->getMessage() falls back to the title when no detail is set')]
    public function test_getMessage_falls_back_to_title(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // because no `detail` is supplied, the parent constructor
        // populates the built-in Exception message slot from the
        // fixed title. Callers who log `$e->getMessage()` get a
        // useful human-readable string even though the file-path
        // and PHP-error payload live in `extra`.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new UnableToReadFileException(
            filePath: '/tmp/unreadable.txt',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->getMessage();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('Unable to read file', $actual);
    }

    #[TestDox('->hasInstance() returns false because no instance URI is set')]
    public function test_hasInstance_returns_false(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // UnableToReadFileException does not populate the instance URI
        // slot - the exception is about an IO failure rather than a
        // specific resource. hasInstance() must therefore report false.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new UnableToReadFileException(
            filePath: '/tmp/unreadable.txt',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->hasInstance();

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($actual);
    }
}
