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
use StusDevKit\ExceptionsKit\Exceptions\InvalidArgumentException;
use StusDevKit\ExceptionsKit\Exceptions\Rfc9457ProblemDetailsException;

/**
 * Contract + behaviour tests for InvalidArgumentException.
 *
 * InvalidArgumentException is a fixed-shape specialisation of
 * Rfc9457ProblemDetailsException: `type`, `status`, and `title` are
 * hard-coded, and only `detail` varies per throw-site. These tests
 * lock down both the subclass contract (parent class, constructor
 * shape) and the constant values the constructor pins so any
 * unintentional change to the wire contract fails with a named
 * diagnostic.
 */
#[TestDox(InvalidArgumentException::class)]
class InvalidArgumentExceptionTest extends TestCase
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
        // import InvalidArgumentException by FQN, so moving it is a
        // breaking change that must go through a major version bump.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'StusDevKit\\ExceptionsKit\\Exceptions';

        // ----------------------------------------------------------------
        // perform the change

        $actual = (new ReflectionClass(InvalidArgumentException::class))
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

        // InvalidArgumentException is a concrete throwable class -
        // not a trait, not an interface, not an enum. Pinning this
        // prevents a silent reshape (e.g. promoting to an interface)
        // from slipping past review.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(InvalidArgumentException::class);

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
        // maybeGetDetail() accessor, and status/title/type getters.
        // Swapping the parent for a different Exception subclass would
        // silently drop every one of those features from anything that
        // catches InvalidArgumentException.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(InvalidArgumentException::class);

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
        // `status`, and `title` - nothing more. Any new public method
        // declared on the subclass is a surface-area expansion the
        // parent would not pick up, so the declared-here method set
        // is pinned by enumeration.
        //
        // the constructor is the single member declared directly on
        // this class. Everything else - getTypeAsString(), getStatus(),
        // jsonSerialize(), etc. - is inherited from the parent and
        // must not be listed here.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['__construct'];
        $reflection = new ReflectionClass(InvalidArgumentException::class);

        // ----------------------------------------------------------------
        // perform the change

        $actual = array_values(array_map(
            static fn ($method) => $method->getName(),
            array_filter(
                $reflection->getMethods(ReflectionMethod::IS_PUBLIC),
                static fn ($method) => $method->getDeclaringClass()
                    ->getName() === InvalidArgumentException::class,
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

        // this subclass overrides the parent constructor to narrow the
        // parameter set down to just `detail`. Losing the override
        // would mean every caller suddenly has to supply type / status
        // / title again, which would be a silent breaking change.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(InvalidArgumentException::class);

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
        // InvalidArgumentException(...)`. A protected or private
        // constructor would make the class unthrowable from user code.

        // ----------------------------------------------------------------
        // setup your test

        $method = (new ReflectionClass(InvalidArgumentException::class))
            ->getMethod('__construct');

        // ----------------------------------------------------------------
        // perform the change

        $actual = $method->isPublic();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('::__construct() declares $detail as its only parameter')]
    public function test_construct_declares_detail_as_its_only_parameter(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the published parameter set is pinned by enumeration, in
        // order. Adding, removing, renaming, or reordering parameters
        // is a breaking change for every throw-site, so any drift
        // shows up here as a diff naming the specific parameter.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['detail'];
        $method = (new ReflectionClass(InvalidArgumentException::class))
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

    #[TestDox('::__construct() declares $detail as string')]
    public function test_construct_declares_detail_as_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // `detail` must be a string - the parent stores it as a
        // `?string` and uses it as the exception message. Widening
        // this to `mixed` or narrowing to `non-empty-string` would
        // each change what a call site is allowed to pass and must
        // be an intentional change.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'string';
        $param = (new ReflectionClass(InvalidArgumentException::class))
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

    // ================================================================
    //
    // ::__construct() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::__construct() accepts a detail string')]
    public function test_construct_accepts_a_detail_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the constructor's published contract is "given a detail
        // string, produce an instance". Pinning instantiation as its
        // own test means a silent regression in the parent-constructor
        // chain (e.g. a required parameter added upstream) surfaces
        // here rather than only in downstream tests.

        // ----------------------------------------------------------------
        // setup your test

        // nothing to do

        // ----------------------------------------------------------------
        // perform the change

        $unit = new InvalidArgumentException(
            detail: '$name must be a non-empty string',
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(InvalidArgumentException::class, $unit);
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

        $unit = new InvalidArgumentException(
            detail: '$name must be a non-empty string',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->getTypeAsString();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            'https://example.com/errors/invalid-argument',
            $actual,
        );
    }

    #[TestDox('->getStatus() returns 422')]
    public function test_getStatus_returns_422(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // 422 Unprocessable Content is the RFC-correct status for a
        // request whose syntax was fine but whose semantics (an
        // argument that fails the receiver's validation) could not
        // be processed. Pinning the literal here prevents accidental
        // reclassification to a generic 400 or 500.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new InvalidArgumentException(
            detail: '$name must be a non-empty string',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->getStatus();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(422, $actual);
    }

    #[TestDox('->getTitle() returns the fixed title "Invalid argument"')]
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

        $unit = new InvalidArgumentException(
            detail: '$name must be a non-empty string',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->getTitle();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('Invalid argument', $actual);
    }

    #[TestDox('->maybeGetDetail() returns the detail string passed into the constructor')]
    public function test_maybeGetDetail_returns_the_detail_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the `detail` parameter is the only slot a caller controls.
        // It must round-trip unchanged through the exception so the
        // receiver (log aggregator, error responder, UI) sees exactly
        // what the thrower meant.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new InvalidArgumentException(
            detail: '$age must be a positive integer',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->maybeGetDetail();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('$age must be a positive integer', $actual);
    }

    #[TestDox('->getMessage() returns the detail string passed into the constructor')]
    public function test_getMessage_returns_the_detail_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the parent's constructor forwards `detail` into the built-in
        // Exception message slot, so getMessage() and maybeGetDetail()
        // return the same string. This pin catches a regression where
        // the two slots fall out of sync (e.g. if the parent starts
        // forwarding the title instead).

        // ----------------------------------------------------------------
        // setup your test

        $unit = new InvalidArgumentException(
            detail: '$name must be a non-empty string',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->getMessage();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('$name must be a non-empty string', $actual);
    }

    #[TestDox('->hasExtra() returns false because no extra data is set')]
    public function test_hasExtra_returns_false(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // InvalidArgumentException does not populate the extra-data
        // slot - its published wire shape is "type + status + title
        // + detail" only. hasExtra() must therefore report false so
        // downstream response builders skip the extra-data branch.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new InvalidArgumentException(
            detail: '$name must be a non-empty string',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->hasExtra();

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($actual);
    }

    #[TestDox('->hasInstance() returns false because no instance URI is set')]
    public function test_hasInstance_returns_false(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // InvalidArgumentException does not populate the instance URI
        // slot - the exception is about a programming-contract
        // violation, not a specific resource. hasInstance() must
        // therefore report false.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new InvalidArgumentException(
            detail: '$name must be a non-empty string',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->hasInstance();

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($actual);
    }
}
