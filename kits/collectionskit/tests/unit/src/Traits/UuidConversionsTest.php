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

namespace StusDevKit\CollectionsKit\Tests\Unit\Traits;

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
use StusDevKit\CollectionsKit\Indexes\IndexOfUuids;
use StusDevKit\CollectionsKit\Traits\UuidConversions;

/**
 * Contract + behaviour tests for the UuidConversions trait.
 *
 * These tests act as a lockdown on the trait's published shape and
 * observed runtime behaviour: renaming the method, changing the
 * return shape, or dropping key preservation must be an intentional
 * act that updates these tests at the same time.
 *
 * Behaviour is exercised through IndexOfUuids, the canonical
 * using-class. IndexOfUuids `use`s the trait, so it acts as a real
 * harness that lets us reach the trait's method through a live
 * collection.
 */
#[TestDox(UuidConversions::class)]
class UuidConversionsTest extends TestCase
{
    // ================================================================
    //
    // Trait identity
    //
    // ----------------------------------------------------------------

    #[TestDox('is declared as a trait')]
    public function test_is_declared_as_a_trait(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // UuidConversions must be a trait (not a class or
        // interface). Using collections rely on this so they can
        // declare `use UuidConversions;` in their body.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(UuidConversions::class);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $reflection->isTrait();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('lives in the StusDevKit\\CollectionsKit\\Traits namespace')]
    public function test_lives_in_the_expected_namespace(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the published namespace is part of the contract - using
        // collections import the trait by FQN, so moving it is a
        // breaking change that must go through a major version bump.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'StusDevKit\\CollectionsKit\\Traits';

        // ----------------------------------------------------------------
        // perform the change

        $actual = (new ReflectionClass(UuidConversions::class))
            ->getNamespaceName();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('exposes only a toArrayOfStrings() method')]
    public function test_exposes_only_a_toArrayOfStrings_method(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the trait exists to supply a single method,
        // toArrayOfStrings(). Adding a second method is a
        // surface-area expansion that every using collection
        // inherits, so the method set is pinned by enumeration - any
        // addition fails with a diff that names the new method,
        // rather than a cryptic count mismatch.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['toArrayOfStrings'];
        $reflection = new ReflectionClass(UuidConversions::class);

        // ----------------------------------------------------------------
        // perform the change

        $actual = array_map(
            static fn ($method) => $method->getName(),
            $reflection->getMethods(),
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // toArrayOfStrings() shape
    //
    // ----------------------------------------------------------------

    #[TestDox('::toArrayOfStrings() is declared')]
    public function test_declares_a_toArrayOfStrings_method(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the single supplied method is `toArrayOfStrings()`.
        // Renaming it is a breaking change for every using
        // collection's callers.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(UuidConversions::class);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $reflection->hasMethod('toArrayOfStrings');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('::toArrayOfStrings() is public')]
    public function test_toArrayOfStrings_is_public(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the method must be public so callers can invoke it on the
        // using collection instance.

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(
            UuidConversions::class,
            'toArrayOfStrings',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $method->isPublic();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('::toArrayOfStrings() is an instance method, not static')]
    public function test_toArrayOfStrings_is_not_static(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the method reads the using collection's stored data, so
        // it must be an instance method. A silent upgrade to static
        // would defeat the design.

        // ----------------------------------------------------------------
        // setup your test

        $method = new ReflectionMethod(
            UuidConversions::class,
            'toArrayOfStrings',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $method->isStatic();

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($actual);
    }

    #[TestDox('::toArrayOfStrings() takes no parameters')]
    public function test_toArrayOfStrings_takes_no_parameters(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the method promises a parameter-less call: the collection's
        // full string-form representation, no options, no filters.
        // Adding a required parameter would break every call site.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 0;
        $method = new ReflectionMethod(
            UuidConversions::class,
            'toArrayOfStrings',
        );

        // ----------------------------------------------------------------
        // perform the change

        $actual = $method->getNumberOfParameters();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::toArrayOfStrings() declares an `array` return type')]
    public function test_toArrayOfStrings_declares_an_array_return_type(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the runtime return type is `array`. The richer
        // `array<string, string>` shape lives in the docblock for
        // PHPStan; the native return type pins the runtime shape.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'array';
        $method = new ReflectionMethod(
            UuidConversions::class,
            'toArrayOfStrings',
        );
        $returnType = $method->getReturnType();
        $this->assertInstanceOf(ReflectionNamedType::class, $returnType);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $returnType->getName();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // toArrayOfStrings() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('->toArrayOfStrings() returns an empty array for an empty collection')]
    public function test_toArrayOfStrings_returns_empty_array_for_empty_collection(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the minimum-size input - an empty collection - must still
        // produce a valid (empty) array. Pins the behaviour at the
        // boundary where there are no values to convert.

        // ----------------------------------------------------------------
        // setup your test

        $expected = [];
        $unit = new IndexOfUuids();

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->toArrayOfStrings();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('->toArrayOfStrings() converts each UuidInterface value to its string form')]
    public function test_toArrayOfStrings_converts_each_value_to_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the contract: every stored UuidInterface becomes its
        // canonical string representation (the same string that
        // IndexOfUuids uses as the key). We use hand-picked fixed
        // UUID strings so the expected array can be a literal, with
        // no runtime computation on the right-hand side.

        // ----------------------------------------------------------------
        // setup your test

        // shorthand
        $uuid1String = '018f0000-0000-7000-8000-000000000001';
        $uuid2String = '018f0000-0000-7000-8000-000000000002';
        $uuid3String = '018f0000-0000-7000-8000-000000000003';

        $expected = [
            $uuid1String => $uuid1String,
            $uuid2String => $uuid2String,
            $uuid3String => $uuid3String,
        ];

        $unit = new IndexOfUuids([
            $uuid1String => Uuid::fromString($uuid1String),
            $uuid2String => Uuid::fromString($uuid2String),
            $uuid3String => Uuid::fromString($uuid3String),
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->toArrayOfStrings();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('->toArrayOfStrings() preserves the collection\'s array keys')]
    public function test_toArrayOfStrings_preserves_keys(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // keys must survive the conversion unchanged. A silent
        // renumbering (array_values-style) would destroy the
        // UUID-keyed-by-its-own-string invariant that IndexOfUuids
        // guarantees, and would fail this test with a key mismatch.

        // ----------------------------------------------------------------
        // setup your test

        // shorthand
        $uuid1String = '018f0000-0000-7000-8000-00000000000a';
        $uuid2String = '018f0000-0000-7000-8000-00000000000b';

        $expected = [$uuid1String, $uuid2String];

        $unit = new IndexOfUuids([
            $uuid1String => Uuid::fromString($uuid1String),
            $uuid2String => Uuid::fromString($uuid2String),
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $actual = array_keys($unit->toArrayOfStrings());

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('->toArrayOfStrings() returns a one-entry map for a single-UUID collection')]
    public function test_toArrayOfStrings_returns_one_entry_map_for_single_uuid(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the minimum non-empty input - a single UUID - must still
        // produce a valid map. Pins the behaviour at the boundary
        // where the conversion runs exactly once.

        // ----------------------------------------------------------------
        // setup your test

        // shorthand
        $uuidString = '018f0000-0000-7000-8000-00000000000f';

        $expected = [$uuidString => $uuidString];

        $unit = new IndexOfUuids([
            $uuidString => Uuid::fromString($uuidString),
        ]);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit->toArrayOfStrings();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }
}
