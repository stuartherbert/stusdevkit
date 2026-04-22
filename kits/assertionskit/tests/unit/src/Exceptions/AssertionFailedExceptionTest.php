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

namespace StusDevKit\AssertionsKit\Tests\Unit\Exceptions;

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
use StusDevKit\AssertionsKit\Exceptions\AssertionFailedException;
use StusDevKit\ExceptionsKit\Exceptions\Rfc9457ProblemDetailsException;

/**
 * Contract + behaviour tests for AssertionFailedException.
 *
 * AssertionFailedException is a fixed-shape specialisation of
 * Rfc9457ProblemDetailsException: `type` and `status` are hard-coded,
 * while `title`, `extra`, and `detail` are accepted from the caller.
 * The convention is that `extra` carries `expected` and `actual`
 * fields describing what the assertion saw.
 *
 * These tests lock down both the subclass contract (parent class,
 * constructor shape) and the constant values the constructor pins so
 * any unintentional change to the wire contract fails with a named
 * diagnostic.
 */
#[TestDox(AssertionFailedException::class)]
class AssertionFailedExceptionTest extends TestCase
{
    // ================================================================
    //
    // Class identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\AssertionsKit\\Exceptions namespace')]
    public function test_lives_in_the_expected_namespace(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the published namespace is part of the contract - callers
        // import AssertionFailedException by FQN, so moving it is a
        // breaking change that must go through a major version bump.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'StusDevKit\\AssertionsKit\\Exceptions';

        // ----------------------------------------------------------------
        // perform the change

        $actual = (new ReflectionClass(AssertionFailedException::class))
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

        // AssertionFailedException is a concrete throwable class -
        // not a trait, not an interface, not an enum. Pinning this
        // prevents a silent reshape (for example, promoting it to an
        // interface) from slipping past review.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(AssertionFailedException::class);

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

        // the parent class is the RFC 9457 problem-details base,
        // which gives this exception its JsonSerializable wire
        // format, getExtra() accessor, and status/title/type
        // getters. Swapping the parent for a different Exception
        // subclass would silently drop every one of those features
        // from anything that catches AssertionFailedException.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(AssertionFailedException::class);

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

        // this subclass exists to pre-fill the parent's `type` and
        // `status`, nothing more. Any new public method declared on
        // the subclass is a surface-area expansion the parent would
        // not pick up, so the declared-here method set is pinned by
        // enumeration.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['__construct'];
        $reflection = new ReflectionClass(AssertionFailedException::class);

        // ----------------------------------------------------------------
        // perform the change

        // only consider methods actually declared *on* this subclass,
        // not every method inherited from the parent class chain.
        $actual = array_values(array_map(
            static fn ($method) => $method->getName(),
            array_filter(
                $reflection->getMethods(ReflectionMethod::IS_PUBLIC),
                static fn ($method) => $method->getDeclaringClass()
                    ->getName() === AssertionFailedException::class,
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
        // the parameter set down to `title`, `extra`, and `detail` -
        // the caller doesn't need to supply `type` or `status` since
        // those are fixed for this exception. Losing the override
        // would mean every throw-site suddenly has to supply type
        // and status again, which would be a silent breaking change.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(AssertionFailedException::class);

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
        // AssertionFailedException(...)`. A protected or private
        // constructor would make the class unthrowable from user
        // code.

        // ----------------------------------------------------------------
        // setup your test

        $method = (new ReflectionClass(AssertionFailedException::class))
            ->getMethod('__construct');

        // ----------------------------------------------------------------
        // perform the change

        $actual = $method->isPublic();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('::__construct() declares $title, $extra, $detail in that order')]
    public function test_construct_declares_the_expected_parameter_list(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the published parameter set is pinned by enumeration, in
        // order. Adding, removing, renaming, or reordering
        // parameters is a breaking change for every throw-site.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['title', 'extra', 'detail'];
        $method = (new ReflectionClass(AssertionFailedException::class))
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

    #[TestDox('::__construct() declares $title as string')]
    public function test_construct_declares_title_as_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // `title` is the short human-readable summary of the
        // assertion failure. It must be a string - widening to
        // `mixed` would allow nulls and arrays to sneak into the
        // RFC 9457 `title` slot.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'string';
        $param = (new ReflectionClass(AssertionFailedException::class))
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

    #[TestDox('::__construct() requires $title (no default)')]
    public function test_construct_requires_title(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // every AssertionFailedException must carry a title - the
        // title is what the caller sees in `getMessage()` when no
        // detail is supplied. Defaulting it to an empty string would
        // produce useless blank exception messages at throw-sites
        // that forget to pass one.

        // ----------------------------------------------------------------
        // setup your test

        $param = (new ReflectionClass(AssertionFailedException::class))
            ->getMethod('__construct')->getParameters()[0];

        // ----------------------------------------------------------------
        // perform the change

        $actual = $param->isDefaultValueAvailable();

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($actual);
    }

    #[TestDox('::__construct() declares $extra as array')]
    public function test_construct_declares_extra_as_array(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // `extra` carries the assertion's `expected` / `actual`
        // payload and must be an array. Widening to `iterable` or
        // `mixed` would allow objects to reach the parent
        // constructor, which would then fail to serialise them as
        // part of the RFC 9457 `extra` member.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'array';
        $param = (new ReflectionClass(AssertionFailedException::class))
            ->getMethod('__construct')->getParameters()[1];
        $paramType = $param->getType();
        $this->assertInstanceOf(ReflectionNamedType::class, $paramType);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $paramType->getName();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::__construct() defaults $extra to the empty array')]
    public function test_construct_defaults_extra_to_empty_array(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the empty array default means callers who only care about
        // the title can `throw new AssertionFailedException($title)`
        // without having to pass `extra: []` themselves. Any other
        // default would silently inject payload into the parent's
        // `extra` slot.

        // ----------------------------------------------------------------
        // setup your test

        $param = (new ReflectionClass(AssertionFailedException::class))
            ->getMethod('__construct')->getParameters()[1];
        $this->assertTrue($param->isDefaultValueAvailable());

        // ----------------------------------------------------------------
        // perform the change

        $actual = $param->getDefaultValue();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame([], $actual);
    }

    #[TestDox('::__construct() declares $detail as string')]
    public function test_construct_declares_detail_as_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // `detail` is the user-provided per-occurrence message. The
        // constructor accepts the empty string as "no detail" rather
        // than nullable string, so the caller doesn't have to think
        // about the difference between `detail: null` and `detail:
        // ''`.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'string';
        $param = (new ReflectionClass(AssertionFailedException::class))
            ->getMethod('__construct')->getParameters()[2];
        $paramType = $param->getType();
        $this->assertInstanceOf(ReflectionNamedType::class, $paramType);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $paramType->getName();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::__construct() defaults $detail to the empty string')]
    public function test_construct_defaults_detail_to_empty_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the empty-string default is the marker the constructor
        // uses to mean "no detail was supplied" - when $detail is
        // empty the title is used as the exception message instead.
        // Any other default would silently reach the parent's detail
        // slot.

        // ----------------------------------------------------------------
        // setup your test

        $param = (new ReflectionClass(AssertionFailedException::class))
            ->getMethod('__construct')->getParameters()[2];
        $this->assertTrue($param->isDefaultValueAvailable());

        // ----------------------------------------------------------------
        // perform the change

        $actual = $param->getDefaultValue();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('', $actual);
    }

    // ================================================================
    //
    // ::__construct() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::__construct() accepts a title only')]
    public function test_construct_accepts_a_title_only(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the constructor's minimal form is a bare title. Pinning
        // this case as its own test means a silent regression in the
        // parent-constructor chain (for example, a required
        // parameter added upstream) surfaces here rather than only
        // in downstream tests.

        // ----------------------------------------------------------------
        // setup your test

        // nothing to do

        // ----------------------------------------------------------------
        // perform the change

        $unit = new AssertionFailedException(
            title: 'Failed asserting that a condition is true',
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(AssertionFailedException::class, $unit);
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

        $unit = new AssertionFailedException(
            title: 'Failed asserting that a condition is true',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->getTypeAsString();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            'https://github.com/stuartherbert/stusdevkit/errors/assertion-failed',
            $actual,
        );
    }

    #[TestDox('->getStatus() returns 422')]
    public function test_getStatus_returns_422(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // 422 Unprocessable Content is the RFC-correct status for a
        // request whose syntax was fine but whose semantics (the
        // asserted condition failed) could not be processed. Pinning
        // the literal here prevents accidental reclassification to a
        // generic 400 or 500.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new AssertionFailedException(
            title: 'Failed asserting that a condition is true',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->getStatus();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(422, $actual);
    }

    #[TestDox('->getTitle() returns the caller-supplied title')]
    public function test_getTitle_returns_the_caller_supplied_title(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // unlike type and status, the title varies per call site -
        // each assertion picks its own short summary. Pinning that
        // the getter returns the caller's value verbatim (not, say,
        // trimmed or lowercased) documents that the caller's string
        // is the single source of truth.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new AssertionFailedException(
            title: 'Failed asserting that a condition is true',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->getTitle();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            'Failed asserting that a condition is true',
            $actual,
        );
    }

    #[TestDox('->hasExtra() returns false when no extra is supplied')]
    public function test_hasExtra_returns_false_when_no_extra_is_supplied(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the default empty array means `extra` is effectively
        // absent from the RFC 9457 payload. Downstream response
        // builders lean on hasExtra() to decide whether to emit the
        // `extra` member in the serialised body, so the default case
        // must report false.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new AssertionFailedException(
            title: 'Failed asserting that a condition is true',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->hasExtra();

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($actual);
    }

    #[TestDox('->getExtra() returns the empty array when no extra is supplied')]
    public function test_getExtra_returns_the_empty_array_when_none_supplied(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the return type is always an array, even when the caller
        // didn't provide anything. That means consumers can iterate
        // the result without first checking hasExtra() - it will
        // simply iterate nothing.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new AssertionFailedException(
            title: 'Failed asserting that a condition is true',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->getExtra();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame([], $actual);
    }

    #[TestDox('->hasExtra() returns true when extra is supplied')]
    public function test_hasExtra_returns_true_when_extra_is_supplied(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the convention for assertion failures is to pass
        // `expected` and `actual` strings in `extra`. When those are
        // present, hasExtra() must report true so downstream response
        // builders know to include the `extra` member.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new AssertionFailedException(
            title: 'Failed asserting that a condition is true',
            extra: [
                'expected' => 'true',
                'actual'   => 'false',
            ],
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->hasExtra();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('->getExtra() returns the caller-supplied extra verbatim')]
    public function test_getExtra_returns_the_caller_supplied_extra(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the getter is a pass-through: no filtering, no
        // canonicalisation, no key renaming. Pinning this preserves
        // the contract that whatever the caller passed is what a
        // consumer reads out - important because the `expected` /
        // `actual` keys are part of the wire format for assertion
        // failure payloads.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new AssertionFailedException(
            title: 'Failed asserting that a condition is true',
            extra: [
                'expected' => 'true',
                'actual'   => 'false',
            ],
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->getExtra();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            [
                'expected' => 'true',
                'actual'   => 'false',
            ],
            $actual,
        );
    }

    #[TestDox('->maybeGetDetail() returns null when no detail is supplied')]
    public function test_maybeGetDetail_returns_null_when_none_supplied(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the constructor converts the empty-string default into
        // `null` on the parent constructor - that's the marker used
        // to distinguish "no detail" from a legitimately empty
        // detail. Callers of maybeGetDetail() rely on the null to
        // know when to fall back to the title.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new AssertionFailedException(
            title: 'Failed asserting that a condition is true',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->maybeGetDetail();

        // ----------------------------------------------------------------
        // test the results

        $this->assertNull($actual);
    }

    #[TestDox('->getMessage() falls back to the title when no detail is supplied')]
    public function test_getMessage_falls_back_to_title_when_no_detail(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // because no detail is supplied, the parent constructor
        // populates the built-in Exception message slot from the
        // title. Callers who log `$e->getMessage()` get a useful
        // human-readable string even though no per-occurrence
        // detail was provided.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new AssertionFailedException(
            title: 'Failed asserting that a condition is true',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->getMessage();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            'Failed asserting that a condition is true',
            $actual,
        );
    }

    #[TestDox('->maybeGetDetail() returns the supplied detail verbatim')]
    public function test_maybeGetDetail_returns_the_supplied_detail(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // when the caller supplies a non-empty detail, it reaches
        // the parent's detail slot unchanged. Pinning this preserves
        // the contract that the caller's string is the single source
        // of truth for the per-occurrence message.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new AssertionFailedException(
            title: 'Failed asserting that a condition is true',
            extra: [
                'expected' => 'true',
                'actual'   => 'false',
            ],
            detail: 'user activation flag should be true',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->maybeGetDetail();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            'user activation flag should be true',
            $actual,
        );
    }

    #[TestDox('->getMessage() uses the detail when one is supplied')]
    public function test_getMessage_uses_the_detail_when_supplied(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // when detail is non-empty the parent constructor routes it
        // into the built-in Exception message slot instead of the
        // title. This is the Exception slot stack-trace dumps and
        // default logging read, so it's load-bearing for operator
        // diagnostics.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new AssertionFailedException(
            title: 'Failed asserting that a condition is true',
            extra: [
                'expected' => 'true',
                'actual'   => 'false',
            ],
            detail: 'user activation flag should be true',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->getMessage();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            'user activation flag should be true',
            $actual,
        );
    }

    #[TestDox('->hasInstance() returns false because no instance URI is set')]
    public function test_hasInstance_returns_false(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // AssertionFailedException does not populate the instance
        // URI slot - the exception is about a programming-contract
        // violation, not a specific resource. hasInstance() must
        // therefore report false.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new AssertionFailedException(
            title: 'Failed asserting that a condition is true',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->hasInstance();

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($actual);
    }
}
