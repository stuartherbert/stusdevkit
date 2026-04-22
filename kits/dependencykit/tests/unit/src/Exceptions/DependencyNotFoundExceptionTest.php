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

namespace StusDevKit\DependencyKit\Tests\Unit\Exceptions;

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
use StusDevKit\DependencyKit\Exceptions\DependencyNotFoundException;
use StusDevKit\ExceptionsKit\Exceptions\Rfc9457ProblemDetailsException;

/**
 * Contract + behaviour tests for DependencyNotFoundException.
 *
 * DependencyNotFoundException is the PSR-11 "not found" exception
 * thrown by a PSR-11 container when get($id) is called for an id the
 * container cannot resolve. It is a fixed-shape specialisation of
 * Rfc9457ProblemDetailsException: `type`, `status`, and `title` are
 * hard-coded, the caller-supplied requested-name string is carried in
 * the `extra` slot under the `requested_name` key, and the class
 * implements PSR-11's NotFoundExceptionInterface so that framework
 * code catching the PSR contract will recognise it.
 *
 * These tests pin the subclass contract (parent class, implemented
 * interface, constructor shape) and the constant values the
 * constructor bakes in, so any unintentional drift away from the wire
 * contract fails with a named diagnostic.
 */
#[TestDox(DependencyNotFoundException::class)]
class DependencyNotFoundExceptionTest extends TestCase
{
    // ================================================================
    //
    // Class identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\DependencyKit\\Exceptions namespace')]
    public function test_lives_in_the_expected_namespace(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the published namespace is part of the contract - callers
        // import DependencyNotFoundException by FQN, so moving it is a
        // breaking change that must go through a major version bump.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'StusDevKit\\DependencyKit\\Exceptions';

        // ----------------------------------------------------------------
        // perform the change

        $actual = (new ReflectionClass(DependencyNotFoundException::class))
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

        // DependencyNotFoundException is a concrete throwable class -
        // not a trait, not an interface, not an enum. Pinning this
        // prevents a silent reshape (e.g. promoting to an interface)
        // from slipping past review.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(DependencyNotFoundException::class);

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
        // DependencyNotFoundException.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(DependencyNotFoundException::class);

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

    #[TestDox('implements Psr\Container\NotFoundExceptionInterface')]
    public function test_implements_not_found_exception_interface(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // PSR-11's NotFoundExceptionInterface is the catch-all that
        // framework code relies on to detect "container has no entry
        // for this id". Without it, a caller using a try / catch around
        // NotFoundExceptionInterface would miss this exception and
        // treat it as an uncaught container failure instead of a
        // missing-service condition.
        //
        // we check the implemented-interface list via reflection
        // rather than `instanceof` so that PHPStan cannot statically
        // narrow the assertion - a future accidental removal of
        // `implements NotFoundExceptionInterface` is the regression we
        // want to catch.

        // ----------------------------------------------------------------
        // setup your test

        $interfaces = (new ReflectionClass(DependencyNotFoundException::class))
            ->getInterfaceNames();

        // ----------------------------------------------------------------
        // test the results

        $this->assertContains(
            NotFoundExceptionInterface::class,
            $interfaces,
        );
    }

    #[TestDox('declares no additional public methods beyond its parent')]
    public function test_declares_no_additional_public_methods(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this subclass exists to pre-fill the parent's `type`,
        // `status`, and `title`, and to stash the requested name in
        // `extra` - nothing more. Any new public method declared on
        // the subclass is a surface-area expansion the parent would
        // not pick up, so the declared-here method set is pinned by
        // enumeration.
        //
        // notably there is no `getRequestedName()` helper - callers
        // fetch the requested name from the parent's
        // getExtra()['requested_name']. Adding one later would be a
        // deliberate API addition that must update this list.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['__construct'];
        $reflection = new ReflectionClass(DependencyNotFoundException::class);

        // ----------------------------------------------------------------
        // perform the change

        $actual = array_values(array_map(
            static fn ($method) => $method->getName(),
            array_filter(
                $reflection->getMethods(ReflectionMethod::IS_PUBLIC),
                static fn ($method) => $method->getDeclaringClass()
                    ->getName() === DependencyNotFoundException::class,
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
        // parameter set down to just `requestedName`. Losing the
        // override would mean every caller suddenly has to supply type
        // / status / title / extra again, a silent breaking change.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(DependencyNotFoundException::class);

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
        // DependencyNotFoundException(...)` when a PSR-11 get() cannot
        // locate the requested id. A protected or private constructor
        // would make the class unthrowable from user code.

        // ----------------------------------------------------------------
        // setup your test

        $method = (new ReflectionClass(DependencyNotFoundException::class))
            ->getMethod('__construct');

        // ----------------------------------------------------------------
        // perform the change

        $actual = $method->isPublic();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('::__construct() declares $requestedName as its only parameter')]
    public function test_construct_declares_requestedName_as_its_only_parameter(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the published parameter set is pinned by enumeration, in
        // order. Adding, removing, renaming, or reordering parameters
        // is a breaking change for every throw-site. The PHP argument
        // name `requestedName` maps onto the `requested_name` key in
        // `extra` (snake-case), so renaming here also changes the wire
        // key via the constructor body.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['requestedName'];
        $method = (new ReflectionClass(DependencyNotFoundException::class))
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

    #[TestDox('::__construct() declares $requestedName as string')]
    public function test_construct_declares_requestedName_as_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // `requestedName` must be a string - it carries the id passed
        // to PSR-11's ContainerInterface::get(), which is itself typed
        // as string. Widening this to `mixed` or narrowing to another
        // type would change what throw-sites are allowed to pass.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'string';
        $param = (new ReflectionClass(DependencyNotFoundException::class))
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

    #[TestDox('::__construct() accepts a requested-name string')]
    public function test_construct_accepts_a_requested_name_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the constructor's published contract is "given a requested
        // name string, produce an instance". Pinning instantiation as
        // its own test means a silent regression in the parent
        // constructor (e.g. a required parameter added upstream)
        // surfaces here rather than only in downstream tests.

        // ----------------------------------------------------------------
        // setup your test

        // nothing to do

        // ----------------------------------------------------------------
        // perform the change

        $unit = new DependencyNotFoundException(
            requestedName: 'acme.service',
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(DependencyNotFoundException::class, $unit);
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

        $unit = new DependencyNotFoundException(
            requestedName: 'acme.service',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->getTypeAsString();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            'https://github.com/stuartherbert/stusdevkit/',
            $actual,
        );
    }

    #[TestDox('->getStatus() returns 400')]
    public function test_getStatus_returns_400(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // 400 Bad Request is the status pinned by the constructor -
        // the caller asked the container for an id it does not know,
        // which is a caller-side problem. Pinning the literal here
        // prevents accidental reclassification (e.g. to 404 or 500).

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DependencyNotFoundException(
            requestedName: 'acme.service',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->getStatus();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(400, $actual);
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

        $unit = new DependencyNotFoundException(
            requestedName: 'acme.service',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->getTitle();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            'Requested item not found in DI container',
            $actual,
        );
    }

    #[TestDox('->hasExtra() returns true because the requested name is stored in extra')]
    public function test_hasExtra_returns_true(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this exception always carries the requested name in the
        // extra slot, so hasExtra() must report true. Downstream
        // response builders rely on this to decide whether to emit
        // the `extra` member in the serialised body.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DependencyNotFoundException(
            requestedName: 'acme.service',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->hasExtra();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('->getExtra() carries the requested name under the "requested_name" key')]
    public function test_getExtra_carries_the_requested_name(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the wire-format key is `requested_name` (snake_case),
        // deliberately distinct from the PHP argument name
        // `requestedName` (camelCase). Pinning the literal here makes
        // an accidental rename - in either direction - impossible to
        // miss.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DependencyNotFoundException(
            requestedName: 'acme.service',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->getExtra();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            ['requested_name' => 'acme.service'],
            $actual,
        );
    }

    #[TestDox('->maybeGetDetail() returns null because no detail is set')]
    public function test_maybeGetDetail_returns_null(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // DependencyNotFoundException does not populate the RFC 9457
        // `detail` slot - the requested-name string is carried in
        // `extra` instead. That means maybeGetDetail() must return
        // null, and getMessage() falls back to the fixed title.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DependencyNotFoundException(
            requestedName: 'acme.service',
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
        // populates the built-in Exception message slot from the fixed
        // title. Callers who log `$e->getMessage()` get a useful
        // human-readable string even though the requested-name payload
        // lives in `extra`.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DependencyNotFoundException(
            requestedName: 'acme.service',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->getMessage();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            'Requested item not found in DI container',
            $actual,
        );
    }

    #[TestDox('->hasInstance() returns false because no instance URI is set')]
    public function test_hasInstance_returns_false(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // DependencyNotFoundException does not populate the instance
        // URI slot - the exception is about an unknown id, not a
        // specific resource. hasInstance() must therefore report false.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new DependencyNotFoundException(
            requestedName: 'acme.service',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->hasInstance();

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($actual);
    }
}
