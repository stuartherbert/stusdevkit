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

namespace StusDevKit\MissingBitsKit\Tests\Unit\Reflection;

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionType;
use StusDevKit\ExceptionsKit\Exceptions\Rfc9457ProblemDetailsException;
use StusDevKit\MissingBitsKit\Reflection\UnsupportedReflectionTypeException;

/**
 * Contract + behaviour tests for UnsupportedReflectionTypeException.
 *
 * This subclass of Rfc9457ProblemDetailsException pre-fills `type`,
 * `status`, and `title`, and carries the offending ReflectionType
 * subclass name in the `extra` slot under the `class_name` key. The
 * exception signals a library-maintenance gap: PHP added a new
 * ReflectionType subclass and the flattener does not yet know how to
 * handle it, hence the 500-class status (this is a bug in the
 * library, not a caller contract violation). These tests lock down
 * the subclass shape and the literal constant values the constructor
 * bakes in.
 *
 * A ReflectionType subclass instance is obtained by reflecting on a
 * fixture property - the runtime will not let userland construct
 * ReflectionNamedType directly.
 */
#[TestDox(UnsupportedReflectionTypeException::class)]
class UnsupportedReflectionTypeExceptionTest extends TestCase
{
    // ================================================================
    //
    // Class identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\MissingBitsKit\\Reflection namespace')]
    public function test_lives_in_the_expected_namespace(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the published namespace is part of the contract - callers
        // import the exception by FQN, so moving it is a breaking
        // change that must go through a major version bump.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'StusDevKit\\MissingBitsKit\\Reflection';

        // ----------------------------------------------------------------
        // perform the change

        $actual = (new ReflectionClass(UnsupportedReflectionTypeException::class))
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

        // this is a concrete throwable class - not a trait, not an
        // interface, not an enum. Pinning this prevents a silent
        // reshape (e.g. promoting to an interface) from slipping past
        // review.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(
            UnsupportedReflectionTypeException::class,
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
        // this exception.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(
            UnsupportedReflectionTypeException::class,
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

    #[TestDox('declares no additional public methods beyond its parent')]
    public function test_declares_no_additional_public_methods(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this subclass exists to pre-fill the parent's `type`,
        // `status`, and `title`, and to stash the offending
        // ReflectionType subclass name in `extra` - nothing more.
        // Any new public method declared on the subclass is a
        // surface-area expansion the parent would not pick up, so
        // the declared-here method set is pinned by enumeration.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['__construct'];
        $reflection = new ReflectionClass(
            UnsupportedReflectionTypeException::class,
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = array_values(array_map(
            static fn ($method) => $method->getName(),
            array_filter(
                $reflection->getMethods(ReflectionMethod::IS_PUBLIC),
                static fn ($method) => $method->getDeclaringClass()
                    ->getName() === UnsupportedReflectionTypeException::class,
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
        // parameter set down to just `refType`. Losing the override
        // would mean every caller suddenly has to supply type / status
        // / title / extra again, which would be a silent breaking
        // change.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(
            UnsupportedReflectionTypeException::class,
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
        // UnsupportedReflectionTypeException(...)`. A protected or
        // private constructor would make the class unthrowable from
        // user code.

        // ----------------------------------------------------------------
        // setup your test

        $method = (new ReflectionClass(
            UnsupportedReflectionTypeException::class,
        ))->getMethod('__construct');

        // ----------------------------------------------------------------
        // perform the change

        $actual = $method->isPublic();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('::__construct() declares $refType as its only parameter')]
    public function test_construct_declares_refType_as_its_only_parameter(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the published parameter set is pinned by enumeration, in
        // order. Adding, removing, renaming, or reordering parameters
        // is a breaking change for every throw-site.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['refType'];
        $method = (new ReflectionClass(
            UnsupportedReflectionTypeException::class,
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

    #[TestDox('::__construct() declares $refType as ReflectionType')]
    public function test_construct_declares_refType_as_ReflectionType(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the parameter is typed to the abstract ReflectionType parent
        // - not a concrete subclass - because this exception is
        // raised precisely when the flattener does not recognise the
        // concrete subclass it has been handed. Narrowing this to a
        // specific subclass would make it impossible to throw for the
        // new subclass that motivated the exception in the first
        // place.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ReflectionType::class;
        $param = (new ReflectionClass(
            UnsupportedReflectionTypeException::class,
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

    // ================================================================
    //
    // ::__construct() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::__construct() accepts a ReflectionType')]
    public function test_construct_accepts_a_ReflectionType(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the constructor's published contract is "given a
        // ReflectionType, produce an instance". Pinning instantiation
        // as its own test means a silent regression in the
        // parent-constructor chain (e.g. a required parameter added
        // upstream) surfaces here rather than only in downstream
        // tests.

        // ----------------------------------------------------------------
        // setup your test

        $refType = $this->obtainNamedType();

        // ----------------------------------------------------------------
        // perform the change

        $unit = new UnsupportedReflectionTypeException(
            refType: $refType,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(
            UnsupportedReflectionTypeException::class,
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
        // source file.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new UnsupportedReflectionTypeException(
            refType: $this->obtainNamedType(),
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->getTypeAsString();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            'http://github.com/stuartherbert/stusdevkit/',
            $actual,
        );
    }

    #[TestDox('->getStatus() returns 500')]
    public function test_getStatus_returns_500(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // 500 is the RFC-correct status for this exception because it
        // signals a library-maintenance gap, not a caller contract
        // violation. The caller fed in a perfectly valid
        // ReflectionType subclass; this library just does not know
        // how to handle it yet. That is a server-side problem, hence
        // the 500-class status.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new UnsupportedReflectionTypeException(
            refType: $this->obtainNamedType(),
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
        // RFC 9457 body. It is a fixed string chosen to explain the
        // gap - pinning the literal guards against accidental edits
        // that would leave responses inconsistent with the exception
        // class.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new UnsupportedReflectionTypeException(
            refType: $this->obtainNamedType(),
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->getTitle();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            'Unsupported PHP ReflectionType child class',
            $actual,
        );
    }

    #[TestDox('->hasExtra() returns true because the class name is stored in extra')]
    public function test_hasExtra_returns_true(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this exception always carries the offending ReflectionType
        // subclass name in the extra slot, so hasExtra() must report
        // true. Downstream response builders rely on this to decide
        // whether to emit the `extra` member in the serialised body.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new UnsupportedReflectionTypeException(
            refType: $this->obtainNamedType(),
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->hasExtra();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('->getExtra() carries the ReflectionType subclass name under the "class_name" key')]
    public function test_getExtra_carries_the_ReflectionType_class_name(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the constructor stores `$refType::class` - i.e. the concrete
        // subclass name, NOT the string form of the type itself. This
        // is a footgun: callers who expect `(string)$refType` (e.g.
        // `int`, `?string`) will instead see `ReflectionNamedType`
        // here. Pinning the literal key and value here makes that
        // contract impossible to miss.

        // ----------------------------------------------------------------
        // setup your test

        $refType = $this->obtainNamedType();
        $unit = new UnsupportedReflectionTypeException(
            refType: $refType,
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->getExtra();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            ['class_name' => ReflectionNamedType::class],
            $actual,
        );
    }

    #[TestDox('->maybeGetDetail() returns null because no detail is set')]
    public function test_maybeGetDetail_returns_null(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this exception does not populate the RFC 9457 `detail`
        // slot - the class name is carried in `extra` instead. That
        // means maybeGetDetail() must return null, and getMessage()
        // falls back to the fixed title.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new UnsupportedReflectionTypeException(
            refType: $this->obtainNamedType(),
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
        // human-readable string even though the class-name payload
        // lives in `extra`.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new UnsupportedReflectionTypeException(
            refType: $this->obtainNamedType(),
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->getMessage();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            'Unsupported PHP ReflectionType child class',
            $actual,
        );
    }

    #[TestDox('->hasInstance() returns false because no instance URI is set')]
    public function test_hasInstance_returns_false(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this exception does not populate the instance URI slot - the
        // exception is about a library-maintenance gap, not a specific
        // resource. hasInstance() must therefore report false.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new UnsupportedReflectionTypeException(
            refType: $this->obtainNamedType(),
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->hasInstance();

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($actual);
    }

    // ================================================================
    //
    // Helpers
    //
    // ----------------------------------------------------------------

    /**
     * Produce a real ReflectionNamedType for the behaviour tests.
     *
     * PHP refuses to let userland construct ReflectionNamedType (or
     * any ReflectionType subclass) directly. We therefore declare an
     * anonymous class with a typed property and reflect on it. The
     * concrete subclass that comes back is ReflectionNamedType -
     * that's the `class_name` value the behavioural tests below pin.
     */
    private function obtainNamedType(): ReflectionType
    {
        // correctness! the fixture class must carry a named (scalar)
        // type on a property we can reflect on; anything else would
        // give us a different ReflectionType subclass.
        $fixture = new class {
            public int $value = 0;
        };

        // shorthand
        $prop = (new ReflectionClass($fixture))->getProperty('value');
        $type = $prop->getType();

        // correctness! PHP must give us back a ReflectionType here;
        // if the property has no type, we want a loud failure rather
        // than a confusing null-chain downstream.
        $this->assertInstanceOf(ReflectionType::class, $type);

        // all done
        return $type;
    }
}
