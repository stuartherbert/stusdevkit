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
use StusDevKit\DependencyKit\Exceptions\UnsupportedParameterTypeException;
use StusDevKit\ExceptionsKit\Exceptions\Rfc9457ProblemDetailsException;

/**
 * Contract + behaviour tests for UnsupportedParameterTypeException.
 *
 * UnsupportedParameterTypeException is a fixed-shape specialisation
 * of Rfc9457ProblemDetailsException: `type`, `status`, and `title`
 * are hard-coded, and the caller-supplied parameter name and rendered
 * parameter type string are carried in the `extra` slot under the
 * `paramName` / `paramType` keys. These tests lock down the subclass
 * contract (parent class, constructor shape, the fact that it
 * deliberately does NOT implement PSR-11's
 * NotFoundExceptionInterface) and the constant values the constructor
 * pins, so any unintentional change to the wire contract fails with
 * a named diagnostic.
 */
#[TestDox(UnsupportedParameterTypeException::class)]
class UnsupportedParameterTypeExceptionTest extends TestCase
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
        // import UnsupportedParameterTypeException by FQN, so moving
        // it is a breaking change that must go through a major version
        // bump.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'StusDevKit\\DependencyKit\\Exceptions';

        // ----------------------------------------------------------------
        // perform the change

        $actual = (new ReflectionClass(UnsupportedParameterTypeException::class))
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

        // UnsupportedParameterTypeException is a concrete throwable
        // class - not a trait, not an interface, not an enum. Pinning
        // this prevents a silent reshape (e.g. promoting to an
        // interface) from slipping past review.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(
            UnsupportedParameterTypeException::class,
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
        // UnsupportedParameterTypeException.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(
            UnsupportedParameterTypeException::class,
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

    #[TestDox('does NOT implement Psr\Container\NotFoundExceptionInterface')]
    public function test_does_not_implement_not_found_exception_interface(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // PSR-11's NotFoundExceptionInterface is specifically for
        // "container has no entry for this id" failures raised by
        // ContainerInterface::get().
        // UnsupportedParameterTypeException is a structural error -
        // the parameter's declared type (a variadic or an
        // intersection) cannot be resolved safely by any PSR-11
        // container, regardless of what is registered - so it must
        // NOT advertise itself as a PSR-11 "not found" case. Callers
        // catching NotFoundExceptionInterface for missing-service
        // handling would otherwise silently swallow this design error.
        //
        // we check the implemented-interface list via reflection
        // rather than `instanceof` so that PHPStan cannot statically
        // narrow the assertion - it's the regression we want to catch
        // (someone re-adding `implements NotFoundExceptionInterface`)
        // that matters here.

        // ----------------------------------------------------------------
        // setup your test

        $interfaces = (new ReflectionClass(
            UnsupportedParameterTypeException::class,
        ))->getInterfaceNames();

        // ----------------------------------------------------------------
        // test the results

        $this->assertNotContains(
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
        // rendered type in `extra` - nothing more. Any new public
        // method declared on the subclass is a surface-area expansion
        // the parent would not pick up, so the declared-here method
        // set is pinned by enumeration.
        //
        // notably there are no `getParamName()` / `getParamType()`
        // helpers - callers fetch both from the parent's
        // getExtra()['paramName'] / getExtra()['paramType']. Adding
        // them later would be a deliberate API addition that must
        // update this list.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['__construct'];
        $reflection = new ReflectionClass(
            UnsupportedParameterTypeException::class,
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = array_values(array_map(
            static fn ($method) => $method->getName(),
            array_filter(
                $reflection->getMethods(ReflectionMethod::IS_PUBLIC),
                static fn ($method) => $method->getDeclaringClass()
                    ->getName() === UnsupportedParameterTypeException::class,
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
        // supply type / status / title / extra again, which would be a
        // silent breaking change.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(
            UnsupportedParameterTypeException::class,
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
        // UnsupportedParameterTypeException(...)`. A protected or
        // private constructor would make the class unthrowable from
        // user code.

        // ----------------------------------------------------------------
        // setup your test

        $method = (new ReflectionClass(
            UnsupportedParameterTypeException::class,
        ))->getMethod('__construct');

        // ----------------------------------------------------------------
        // perform the change

        $actual = $method->isPublic();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('::__construct() declares $paramName and $paramType as parameters in that order')]
    public function test_construct_declares_paramName_and_paramType_in_that_order(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the published parameter set is pinned by enumeration, in
        // order. Adding, removing, renaming, or reordering parameters
        // is a breaking change for every throw-site. The PHP argument
        // names are used verbatim as `extra` keys on the wire, so the
        // two must stay in sync.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['paramName', 'paramType'];
        $method = (new ReflectionClass(
            UnsupportedParameterTypeException::class,
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

    #[TestDox('::__construct() declares $paramName as string')]
    public function test_construct_declares_paramName_as_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // `paramName` must be a string - it carries the declared name
        // of the refused parameter, as reported by
        // `ReflectionParameter::getName()`, which is always a string.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'string';
        $param = (new ReflectionClass(
            UnsupportedParameterTypeException::class,
        ))->getMethod('__construct')->getParameters()[0];
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

        // `paramType` must be a string - it carries a human-readable
        // rendering of the refused type (e.g. `"stdClass ..."` for a
        // variadic, or `"Countable&Iterator"` for an intersection).
        // The resolver formats the string before throwing, so the
        // exception sees only text, not a reflection value.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'string';
        $param = (new ReflectionClass(
            UnsupportedParameterTypeException::class,
        ))->getMethod('__construct')->getParameters()[1];
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

    #[TestDox('::__construct() accepts paramName and paramType strings')]
    public function test_construct_accepts_paramName_and_paramType_strings(): void
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

        $unit = new UnsupportedParameterTypeException(
            paramName: 'services',
            paramType: 'stdClass ...',
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(
            UnsupportedParameterTypeException::class,
            $unit,
        );
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

        $unit = new UnsupportedParameterTypeException(
            paramName: 'services',
            paramType: 'stdClass ...',
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
        // constructor - an unsupported parameter type is a
        // programming-contract violation on the server side, not a
        // request problem. Pinning the literal here prevents
        // accidental reclassification.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new UnsupportedParameterTypeException(
            paramName: 'services',
            paramType: 'stdClass ...',
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

        $unit = new UnsupportedParameterTypeException(
            paramName: 'services',
            paramType: 'stdClass ...',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->getTitle();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            'Cannot resolve dependency for unsupported param type',
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
        // response builders rely on this to decide whether to emit the
        // `extra` member in the serialised body.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new UnsupportedParameterTypeException(
            paramName: 'services',
            paramType: 'stdClass ...',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->hasExtra();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('->getExtra() carries the parameter name and type under the "paramName" and "paramType" keys')]
    public function test_getExtra_carries_the_paramName_and_paramType(): void
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

        $unit = new UnsupportedParameterTypeException(
            paramName: 'services',
            paramType: 'stdClass ...',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->getExtra();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            [
                'paramName' => 'services',
                'paramType' => 'stdClass ...',
            ],
            $actual,
        );
    }

    #[TestDox('->maybeGetDetail() returns null because no detail is set')]
    public function test_maybeGetDetail_returns_null(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // UnsupportedParameterTypeException does not populate the
        // RFC 9457 `detail` slot - the parameter name and type are
        // carried in `extra` instead. That means maybeGetDetail() must
        // return null, and getMessage() falls back to the fixed title
        // (tested below).

        // ----------------------------------------------------------------
        // setup your test

        $unit = new UnsupportedParameterTypeException(
            paramName: 'services',
            paramType: 'stdClass ...',
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

        $unit = new UnsupportedParameterTypeException(
            paramName: 'services',
            paramType: 'stdClass ...',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->getMessage();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            'Cannot resolve dependency for unsupported param type',
            $actual,
        );
    }

    #[TestDox('->hasInstance() returns false because no instance URI is set')]
    public function test_hasInstance_returns_false(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // UnsupportedParameterTypeException does not populate the
        // instance URI slot - the exception is about a
        // programming-contract violation, not a specific resource.
        // hasInstance() must therefore report false.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new UnsupportedParameterTypeException(
            paramName: 'services',
            paramType: 'stdClass ...',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->hasInstance();

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($actual);
    }
}
