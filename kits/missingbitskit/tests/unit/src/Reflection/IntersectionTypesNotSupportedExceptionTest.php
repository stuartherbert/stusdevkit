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
use ReflectionIntersectionType;
use ReflectionMethod;
use ReflectionNamedType;
use StusDevKit\ExceptionsKit\Exceptions\Rfc9457ProblemDetailsException;
use StusDevKit\MissingBitsKit\Reflection\IntersectionTypesNotSupportedException;

/**
 * Contract + behaviour tests for IntersectionTypesNotSupportedException.
 *
 * This subclass of Rfc9457ProblemDetailsException pre-fills `type`,
 * `status`, and `title`, and carries the offending intersection type's
 * string form in the `extra` slot under the `type` key. Those values
 * are part of the wire contract - any drift would change how the
 * exception serialises into an RFC 9457 response body, silently
 * breaking downstream consumers that key off the title or the extra
 * members. These tests pin the subclass shape and the literal
 * constant values the constructor bakes in.
 *
 * A real ReflectionIntersectionType cannot be constructed directly in
 * PHP - the runtime only hands them out via reflection on actual
 * typed properties / parameters. The behavioural tests therefore
 * obtain one by reflecting on a fixture class carrying an intersection
 * type, which also doubles as documentation of how callers will
 * actually hit this exception in the wild.
 */
#[TestDox(IntersectionTypesNotSupportedException::class)]
class IntersectionTypesNotSupportedExceptionTest extends TestCase
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

        $actual = (new ReflectionClass(IntersectionTypesNotSupportedException::class))
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
            IntersectionTypesNotSupportedException::class,
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
            IntersectionTypesNotSupportedException::class,
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
        // `status`, and `title`, and to stash the intersection type
        // string in `extra` - nothing more. Any new public method
        // declared on the subclass is a surface-area expansion the
        // parent would not pick up, so the declared-here method set
        // is pinned by enumeration.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['__construct'];
        $reflection = new ReflectionClass(
            IntersectionTypesNotSupportedException::class,
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = array_values(array_map(
            static fn ($method) => $method->getName(),
            array_filter(
                $reflection->getMethods(ReflectionMethod::IS_PUBLIC),
                static fn ($method) => $method->getDeclaringClass()
                    ->getName() === IntersectionTypesNotSupportedException::class,
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
            IntersectionTypesNotSupportedException::class,
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
        // IntersectionTypesNotSupportedException(...)`. A protected or
        // private constructor would make the class unthrowable from
        // user code.

        // ----------------------------------------------------------------
        // setup your test

        $method = (new ReflectionClass(
            IntersectionTypesNotSupportedException::class,
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
            IntersectionTypesNotSupportedException::class,
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

    #[TestDox('::__construct() declares $refType as ReflectionIntersectionType')]
    public function test_construct_declares_refType_as_ReflectionIntersectionType(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the parameter is narrowly typed to ReflectionIntersectionType
        // - not the ReflectionType parent - because the exception's
        // whole reason for existing is that intersection types
        // specifically cannot be flattened. Widening the type would
        // defeat the narrowing that makes the class's contract
        // self-documenting.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ReflectionIntersectionType::class;
        $param = (new ReflectionClass(
            IntersectionTypesNotSupportedException::class,
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

    #[TestDox('::__construct() accepts a ReflectionIntersectionType')]
    public function test_construct_accepts_a_ReflectionIntersectionType(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the constructor's published contract is "given an
        // intersection type, produce an instance". Pinning
        // instantiation as its own test means a silent regression in
        // the parent-constructor chain (e.g. a required parameter
        // added upstream) surfaces here rather than only in downstream
        // tests.

        // ----------------------------------------------------------------
        // setup your test

        $refType = $this->obtainIntersectionType();

        // ----------------------------------------------------------------
        // perform the change

        $unit = new IntersectionTypesNotSupportedException(
            refType: $refType,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(
            IntersectionTypesNotSupportedException::class,
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

        $unit = new IntersectionTypesNotSupportedException(
            refType: $this->obtainIntersectionType(),
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

    #[TestDox('->getStatus() returns 422')]
    public function test_getStatus_returns_422(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // 422 Unprocessable Content is the RFC-correct status for a
        // request whose syntax was fine but whose semantics (an
        // intersection type where a flattenable type was expected)
        // could not be processed. Pinning the literal here prevents
        // accidental reclassification to a generic 400 or 500.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new IntersectionTypesNotSupportedException(
            refType: $this->obtainIntersectionType(),
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
        // RFC 9457 body. It is a fixed string chosen to explain the
        // contract violation - pinning the literal guards against
        // accidental edits that would leave responses inconsistent
        // with the exception class.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new IntersectionTypesNotSupportedException(
            refType: $this->obtainIntersectionType(),
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->getTitle();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            'Intersection types cannot be flattened to a list of names',
            $actual,
        );
    }

    #[TestDox('->hasExtra() returns true because the intersection type is stored in extra')]
    public function test_hasExtra_returns_true(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this exception always carries the intersection type's string
        // form in the extra slot, so hasExtra() must report true.
        // Downstream response builders rely on this to decide whether
        // to emit the `extra` member in the serialised body.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new IntersectionTypesNotSupportedException(
            refType: $this->obtainIntersectionType(),
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->hasExtra();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('->getExtra() carries the intersection type string under the "type" key')]
    public function test_getExtra_carries_the_intersection_type_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the constructor casts the ReflectionIntersectionType to
        // string - PHP's built-in __toString() on that class renders
        // as `A&B`. The wire-format key is `type` (not
        // `intersection_type` or `refType`); pinning the literal key
        // here makes drift impossible to miss.

        // ----------------------------------------------------------------
        // setup your test

        $refType = $this->obtainIntersectionType();
        $unit = new IntersectionTypesNotSupportedException(
            refType: $refType,
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->getExtra();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            ['type' => (string) $refType],
            $actual,
        );
    }

    #[TestDox('->maybeGetDetail() returns null because no detail is set')]
    public function test_maybeGetDetail_returns_null(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this exception does not populate the RFC 9457 `detail`
        // slot - the intersection type string is carried in `extra`
        // instead. That means maybeGetDetail() must return null, and
        // getMessage() falls back to the fixed title.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new IntersectionTypesNotSupportedException(
            refType: $this->obtainIntersectionType(),
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
        // human-readable string even though the intersection type
        // payload lives in `extra`.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new IntersectionTypesNotSupportedException(
            refType: $this->obtainIntersectionType(),
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->getMessage();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            'Intersection types cannot be flattened to a list of names',
            $actual,
        );
    }

    #[TestDox('->hasInstance() returns false because no instance URI is set')]
    public function test_hasInstance_returns_false(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this exception does not populate the instance URI slot - the
        // exception is about a programming-contract violation, not a
        // specific resource. hasInstance() must therefore report
        // false.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new IntersectionTypesNotSupportedException(
            refType: $this->obtainIntersectionType(),
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
     * Produce a real ReflectionIntersectionType for the behaviour
     * tests.
     *
     * PHP refuses to let userland construct ReflectionIntersectionType
     * directly - the runtime only hands them out via reflection on an
     * actual typed property / parameter declaration. We therefore
     * declare an anonymous class carrying an intersection-typed
     * property and reflect on it. The property type is
     * `Countable&\ArrayAccess` - two built-in interfaces, no autoload
     * surprises.
     */
    private function obtainIntersectionType(): ReflectionIntersectionType
    {
        // correctness! the fixture class must carry an intersection
        // type on a property we can reflect on; anything else would
        // give us a different ReflectionType subclass.
        $fixture = new class {
            /** @var \Countable&\ArrayAccess<array-key, mixed> */
            public \Countable&\ArrayAccess $value;
        };

        // shorthand
        $prop = (new ReflectionClass($fixture))->getProperty('value');
        $type = $prop->getType();

        // correctness! PHP must give us back an intersection type
        // here; if it doesn't, the fixture above has been edited
        // incorrectly and we want a loud failure rather than a
        // confusing one downstream.
        $this->assertInstanceOf(ReflectionIntersectionType::class, $type);

        // all done
        return $type;
    }
}
