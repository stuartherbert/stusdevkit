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
use StusDevKit\DependencyKit\Exceptions\UnresolvedParameterException;
use StusDevKit\ExceptionsKit\Exceptions\Rfc9457ProblemDetailsException;

/**
 * Contract + behaviour tests for UnresolvedParameterException.
 *
 * UnresolvedParameterException is the reflection-driven resolver's
 * equivalent of PSR-11's NotFoundExceptionInterface failure: it is
 * thrown by ResolveParameter::for() when a declared parameter's type
 * cannot be satisfied from the DI container and the parameter has no
 * fallback (no default value, not nullable). It is a fixed-shape
 * specialisation of Rfc9457ProblemDetailsException: `type`, `status`,
 * and `title` are hard-coded, the caller-supplied parameter name and
 * rendered type are carried in the `extra` slot under the `paramName`
 * / `paramType` keys, and the class implements PSR-11's
 * NotFoundExceptionInterface so framework code catching the PSR
 * contract will recognise it.
 *
 * These tests pin the subclass contract (parent class, implemented
 * interface, constructor shape) and the constant values the
 * constructor bakes in, so any unintentional drift away from the wire
 * contract fails with a named diagnostic.
 */
#[TestDox(UnresolvedParameterException::class)]
class UnresolvedParameterExceptionTest extends TestCase
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
        // import UnresolvedParameterException by FQN, so moving it is
        // a breaking change that must go through a major version bump.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'StusDevKit\\DependencyKit\\Exceptions';

        // ----------------------------------------------------------------
        // perform the change

        $actual = (new ReflectionClass(UnresolvedParameterException::class))
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

        // UnresolvedParameterException is a concrete throwable class -
        // not a trait, not an interface, not an enum. Pinning this
        // prevents a silent reshape (e.g. promoting to an interface)
        // from slipping past review.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(
            UnresolvedParameterException::class,
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
        // UnresolvedParameterException.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(
            UnresolvedParameterException::class,
        );

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
        // framework code relies on to detect a missing-dependency
        // failure. The resolver sits one layer above PSR-11's
        // ContainerInterface::get(), but from a caller's perspective
        // the failure shape is the same - "the container cannot
        // provide what was asked for" - so this class opts into the
        // PSR marker interface to let standard catch-sites pick it up.

        // ----------------------------------------------------------------
        // setup your test

        $interfaces = (new ReflectionClass(
            UnresolvedParameterException::class,
        ))->getInterfaceNames();

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
        // `status`, and `title`, and to stash the parameter name and
        // type in `extra` - nothing more. Any new public method
        // declared on the subclass is a surface-area expansion the
        // parent would not pick up, so the declared-here method set is
        // pinned by enumeration.
        //
        // notably there are no `getParamName()` / `getParamType()`
        // helpers - callers fetch those from the parent's getExtra().
        // Adding helpers later would be a deliberate API addition that
        // must update this list.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['__construct'];
        $reflection = new ReflectionClass(
            UnresolvedParameterException::class,
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = array_values(array_map(
            static fn ($method) => $method->getName(),
            array_filter(
                $reflection->getMethods(ReflectionMethod::IS_PUBLIC),
                static fn ($method) => $method->getDeclaringClass()
                    ->getName() === UnresolvedParameterException::class,
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
        // parameter set down to just `paramName` and `paramType`.
        // Losing the override would mean every caller suddenly has to
        // supply type / status / title / extra again, a silent
        // breaking change.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(
            UnresolvedParameterException::class,
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

        // the constructor must be public so the resolver (and any
        // caller writing a custom resolver) can `throw new
        // UnresolvedParameterException(...)`. A protected or private
        // constructor would make the class unthrowable from user code.

        // ----------------------------------------------------------------
        // setup your test

        $method = (new ReflectionClass(UnresolvedParameterException::class))
            ->getMethod('__construct');

        // ----------------------------------------------------------------
        // perform the change

        $actual = $method->isPublic();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('::__construct() declares $paramName and $paramType as its parameters, in that order')]
    public function test_construct_declares_paramName_and_paramType(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the published parameter set is pinned by enumeration, in
        // order. Adding, removing, renaming, or reordering parameters
        // is a breaking change for every throw-site. The PHP argument
        // names (`paramName`, `paramType`) are used verbatim as the
        // `extra` keys on the wire, so the two must stay in sync.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['paramName', 'paramType'];
        $method = (new ReflectionClass(UnresolvedParameterException::class))
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

    #[TestDox('::__construct() declares $paramName as string')]
    public function test_construct_declares_paramName_as_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // `paramName` must be a string - it carries the declared name
        // of the unresolved parameter, as reported by
        // `ReflectionParameter::getName()`, which is always a string.
        // Widening this to `mixed` or narrowing to a specialised type
        // would each change what a throw-site is allowed to pass.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'string';
        $param = (new ReflectionClass(UnresolvedParameterException::class))
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

    #[TestDox('::__construct() declares $paramType as string')]
    public function test_construct_declares_paramType_as_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // `paramType` must be a string - it carries the *rendered*
        // representation of the parameter's declared type (union,
        // intersection, nullable, class, builtin) flattened into a
        // single human-readable string. Keeping it `string` lets the
        // caller format however suits the declaration site rather
        // than forcing a specific reflection shape on everyone.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'string';
        $param = (new ReflectionClass(UnresolvedParameterException::class))
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

    // ================================================================
    //
    // ::__construct() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::__construct() accepts a parameter name and a parameter type string')]
    public function test_construct_accepts_a_parameter_name_and_type(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the constructor's published contract is "given a parameter
        // name and a rendered type string, produce an instance".
        // Pinning instantiation as its own test means a silent
        // regression in the parent-constructor chain (e.g. a required
        // parameter added upstream) surfaces here rather than only in
        // downstream tests.

        // ----------------------------------------------------------------
        // setup your test

        // nothing to do

        // ----------------------------------------------------------------
        // perform the change

        $unit = new UnresolvedParameterException(
            paramName: 'logger',
            paramType: 'Psr\\Log\\LoggerInterface',
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(UnresolvedParameterException::class, $unit);
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

        $unit = new UnresolvedParameterException(
            paramName: 'logger',
            paramType: 'Psr\\Log\\LoggerInterface',
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

    #[TestDox('->getStatus() returns 500')]
    public function test_getStatus_returns_500(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // 500 Internal Server Error is the status pinned by the
        // constructor - an unresolvable parameter is a wiring failure
        // on the server side (the declared type, the container
        // bindings, and the parameter defaults do not line up), not a
        // request problem. Pinning the literal here prevents
        // accidental reclassification.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new UnresolvedParameterException(
            paramName: 'logger',
            paramType: 'Psr\\Log\\LoggerInterface',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->getStatus();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(500, $actual);
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

        $unit = new UnresolvedParameterException(
            paramName: 'logger',
            paramType: 'Psr\\Log\\LoggerInterface',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->getTitle();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            'Cannot resolve parameter from DI container',
            $actual,
        );
    }

    #[TestDox('->hasExtra() returns true because the parameter name and type are stored in extra')]
    public function test_hasExtra_returns_true(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this exception always carries the parameter name and type in
        // the extra slot, so hasExtra() must report true. Downstream
        // response builders rely on this to decide whether to emit
        // the `extra` member in the serialised body.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new UnresolvedParameterException(
            paramName: 'logger',
            paramType: 'Psr\\Log\\LoggerInterface',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->hasExtra();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('->getExtra() carries the parameter name and type under the "paramName" and "paramType" keys')]
    public function test_getExtra_carries_paramName_and_paramType(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the wire-format keys are `paramName` and `paramType`
        // (camelCase), matching the PHP parameter names verbatim. The
        // resolver reads back these exact keys when formatting
        // diagnostics, so pinning the literals here makes any
        // accidental rename impossible to miss.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new UnresolvedParameterException(
            paramName: 'logger',
            paramType: 'Psr\\Log\\LoggerInterface',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->getExtra();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            [
                'paramName' => 'logger',
                'paramType' => 'Psr\\Log\\LoggerInterface',
            ],
            $actual,
        );
    }

    #[TestDox('->maybeGetDetail() returns null because no detail is set')]
    public function test_maybeGetDetail_returns_null(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // UnresolvedParameterException does not populate the RFC 9457
        // `detail` slot - the parameter name and type are carried in
        // `extra` instead. That means maybeGetDetail() must return
        // null, and getMessage() falls back to the fixed title.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new UnresolvedParameterException(
            paramName: 'logger',
            paramType: 'Psr\\Log\\LoggerInterface',
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
        // human-readable string even though the paramName / paramType
        // payload lives in `extra`.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new UnresolvedParameterException(
            paramName: 'logger',
            paramType: 'Psr\\Log\\LoggerInterface',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->getMessage();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            'Cannot resolve parameter from DI container',
            $actual,
        );
    }

    #[TestDox('->hasInstance() returns false because no instance URI is set')]
    public function test_hasInstance_returns_false(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // UnresolvedParameterException does not populate the instance
        // URI slot - the exception is about a wiring failure, not a
        // specific resource. hasInstance() must therefore report
        // false.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new UnresolvedParameterException(
            paramName: 'logger',
            paramType: 'Psr\\Log\\LoggerInterface',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->hasInstance();

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($actual);
    }
}
