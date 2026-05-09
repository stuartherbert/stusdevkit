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

namespace StusDevKit\CollectionsKit\Tests\Unit\Exceptions;

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionUnionType;
use StusDevKit\CollectionsKit\Exceptions\NoValueForKeyInCollectionException;
use StusDevKit\ExceptionsKit\Exceptions\Rfc9457ProblemDetailsException;

/**
 * Contract + behaviour tests for NoValueForKeyInCollectionException.
 *
 * NoValueForKeyInCollectionException is a fixed-shape specialisation
 * of Rfc9457ProblemDetailsException: `type` and `status` are
 * hard-coded, and the `title` is composed from the caller-supplied
 * collection type name plus the missing key. These tests lock down
 * both the subclass contract (parent class, constructor shape) and
 * the constant values the constructor pins, so any unintentional
 * change to the wire contract fails with a named diagnostic.
 */
#[TestDox(NoValueForKeyInCollectionException::class)]
class NoValueForKeyInCollectionExceptionTest extends TestCase
{
    // ================================================================
    //
    // Class identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\CollectionsKit\\Exceptions namespace')]
    public function test_lives_in_the_expected_namespace(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the published namespace is part of the contract — callers
        // import this exception by FQN, so moving it is a breaking
        // change that must go through a major version bump.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'StusDevKit\\CollectionsKit\\Exceptions';

        // ----------------------------------------------------------------
        // perform the change

        $actual = (new ReflectionClass(NoValueForKeyInCollectionException::class))
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

        // this is a concrete throwable class — not a trait, not an
        // interface, not an enum. Pinning this prevents a silent
        // reshape from slipping past review.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(NoValueForKeyInCollectionException::class);

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

        $reflection = new ReflectionClass(NoValueForKeyInCollectionException::class);

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
        // parameter set down to (collectionType, key). Losing the
        // override would mean every caller suddenly has to supply
        // type / status / title again, which would be a silent
        // breaking change.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(NoValueForKeyInCollectionException::class);

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
        // NoValueForKeyInCollectionException(...)`. A protected or
        // private constructor would make the class unthrowable from
        // user code.

        // ----------------------------------------------------------------
        // setup your test

        $method = (new ReflectionClass(NoValueForKeyInCollectionException::class))
            ->getMethod('__construct');

        // ----------------------------------------------------------------
        // perform the change

        $actual = $method->isPublic();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('::__construct() declares $collectionType and $key as its only parameters')]
    public function test_construct_declares_expected_parameter_set(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the published parameter set is pinned by enumeration, in
        // order. Adding, removing, renaming, or reordering parameters
        // is a breaking change for every throw-site.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['collectionType', 'key'];
        $method = (new ReflectionClass(NoValueForKeyInCollectionException::class))
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

    #[TestDox('::__construct() declares $collectionType as string')]
    public function test_construct_declares_collectionType_as_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // `collectionType` must be a string — it is the human-readable
        // name of the collection class (e.g. "CollectionAsDict") that
        // gets interpolated into the title.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'string';
        $param = (new ReflectionClass(NoValueForKeyInCollectionException::class))
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

    #[TestDox('::__construct() declares $key as int|string')]
    public function test_construct_declares_key_as_int_or_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // `$key` accepts the `array-key` shape (int|string), which is
        // what dictionaries and indexes use as their TKey template
        // bound. Narrowing this to either type alone would reject
        // valid keys at half the call sites.

        // ----------------------------------------------------------------
        // setup your test

        $param = (new ReflectionClass(NoValueForKeyInCollectionException::class))
            ->getMethod('__construct')->getParameters()[1];
        $paramType = $param->getType();
        $this->assertInstanceOf(ReflectionUnionType::class, $paramType);

        // ----------------------------------------------------------------
        // perform the change

        $names = [];
        foreach ($paramType->getTypes() as $t) {
            $this->assertInstanceOf(ReflectionNamedType::class, $t);
            $names[] = $t->getName();
        }
        sort($names);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(['int', 'string'], $names);
    }

    // ================================================================
    //
    // ::__construct() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('::__construct() accepts a collection type and a string key')]
    public function test_construct_accepts_a_collection_type_and_string_key(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the constructor's published contract is "given a collection
        // type and a key, produce an instance". Pinning instantiation
        // as its own test means a silent regression in the
        // parent-constructor chain surfaces here rather than only in
        // downstream tests.

        // ----------------------------------------------------------------
        // setup your test

        // nothing to do

        // ----------------------------------------------------------------
        // perform the change

        $unit = new NoValueForKeyInCollectionException(
            collectionType: 'CollectionAsDict',
            key: 'missing',
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(NoValueForKeyInCollectionException::class, $unit);
    }

    #[TestDox('::__construct() accepts an integer key')]
    public function test_construct_accepts_an_integer_key(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // dictionaries and indexes can be integer-keyed — the
        // constructor must accept an int as well as a string.

        // ----------------------------------------------------------------
        // setup your test

        // nothing to do

        // ----------------------------------------------------------------
        // perform the change

        $unit = new NoValueForKeyInCollectionException(
            collectionType: 'CollectionAsDict',
            key: 42,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(NoValueForKeyInCollectionException::class, $unit);
    }

    #[TestDox('->getTypeAsString() returns the fixed type URI')]
    public function test_getTypeAsString_returns_the_fixed_type_uri(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the type URI is a fixed documentation link baked into the
        // constructor — it must not vary per throw-site. Pinning the
        // literal value here guards against accidental edits in the
        // source file.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new NoValueForKeyInCollectionException(
            collectionType: 'CollectionAsDict',
            key: 'missing',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->getTypeAsString();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            'https://stusdevkit.dev/errors/collections/no-value-for-key',
            $actual,
        );
    }

    #[TestDox('->getStatus() returns 500')]
    public function test_getStatus_returns_500(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // 500 Internal Server Error is the status chosen for this
        // exception because a missing-key access represents a
        // programming-contract violation that reached runtime — the
        // caller failed to guard get() with has() or maybeGet().
        // Pinning the literal here prevents accidental
        // reclassification.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new NoValueForKeyInCollectionException(
            collectionType: 'CollectionAsDict',
            key: 'missing',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->getStatus();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(500, $actual);
    }

    #[TestDox('->getTitle() composes "{type} does not contain {key}" for a string key')]
    public function test_getTitle_composes_title_for_string_key(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the title is composed by concatenating the collection type
        // and the missing key with " does not contain " in the
        // middle. Pinning the literal composition guards against
        // accidental edits to the format string that would leave
        // responses inconsistent with what consumers expect.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new NoValueForKeyInCollectionException(
            collectionType: 'CollectionAsDict',
            key: 'missing',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->getTitle();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('CollectionAsDict does not contain missing', $actual);
    }

    #[TestDox('->getTitle() composes "{type} does not contain {key}" for an integer key')]
    public function test_getTitle_composes_title_for_integer_key(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // PHP's string concatenation coerces the int to its decimal
        // representation; pinning this guards the concrete output
        // shape callers will see in problem-details responses.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new NoValueForKeyInCollectionException(
            collectionType: 'CollectionAsDict',
            key: 42,
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->getTitle();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('CollectionAsDict does not contain 42', $actual);
    }

    #[TestDox('->getMessage() falls back to the title when no detail is set')]
    public function test_getMessage_falls_back_to_title(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // because no `detail` is supplied, the parent constructor
        // populates the built-in Exception message slot from the
        // composed title. Callers who log `$e->getMessage()` get a
        // useful human-readable string identifying the collection
        // and the missing key.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new NoValueForKeyInCollectionException(
            collectionType: 'CollectionAsDict',
            key: 'missing',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->getMessage();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('CollectionAsDict does not contain missing', $actual);
    }
}
