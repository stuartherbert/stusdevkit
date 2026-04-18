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

use Countable;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionFunction;
use ReflectionIntersectionType;
use ReflectionNamedType;
use ReflectionType;
use ReflectionUnionType;
use StusDevKit\MissingBitsKit\Reflection\GetReflectionTypes;
use StusDevKit\MissingBitsKit\Reflection\UnsupportedReflectionTypeException;
use Traversable;

#[TestDox(GetReflectionTypes::class)]
class GetReflectionTypesTest extends TestCase
{
    // ================================================================
    //
    // ReflectionNamedType
    //
    // ----------------------------------------------------------------

    #[TestDox('::from() wraps a ReflectionNamedType in a single-item array')]
    public function test_from_wraps_named_type_in_array(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // a ReflectionNamedType is a leaf node - it has no sub-types.
        // from() should return a one-element array containing the
        // input unchanged.

        // ----------------------------------------------------------------
        // setup your test

        $fn = static fn (int $x): int => $x;
        $refType = (new ReflectionFunction($fn))->getReturnType();
        $this->assertInstanceOf(ReflectionNamedType::class, $refType);

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetReflectionTypes::from($refType);

        // ----------------------------------------------------------------
        // test the results

        $this->assertCount(1, $actual);
        $this->assertSame($refType, $actual[0]);
    }

    #[TestDox('::from() wraps a nullable ReflectionNamedType in a single-item array')]
    public function test_from_wraps_nullable_named_type_in_array(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // a nullable named type (?int) is still a single
        // ReflectionNamedType that reports allowsNull() = true.
        // from() must not expand it into [int, null].

        // ----------------------------------------------------------------
        // setup your test

        $fn = static fn (?int $x): ?int => $x;
        $refType = (new ReflectionFunction($fn))->getReturnType();
        $this->assertInstanceOf(ReflectionNamedType::class, $refType);

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetReflectionTypes::from($refType);

        // ----------------------------------------------------------------
        // test the results

        $this->assertCount(1, $actual);
        $this->assertSame($refType, $actual[0]);
        $this->assertTrue($actual[0]->allowsNull());
    }

    // ================================================================
    //
    // ReflectionUnionType
    //
    // ----------------------------------------------------------------

    #[TestDox('::from() returns the member types of a ReflectionUnionType')]
    public function test_from_returns_member_types_of_union(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // a union type has two or more member types. from() must
        // return them in the order PHP reports them, without
        // modification.

        // ----------------------------------------------------------------
        // setup your test

        $fn = static fn (int|string $x): int|string => $x;
        $refType = (new ReflectionFunction($fn))->getReturnType();
        $this->assertInstanceOf(ReflectionUnionType::class, $refType);

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetReflectionTypes::from($refType);

        // ----------------------------------------------------------------
        // test the results

        $this->assertCount(2, $actual);
        // PHP does not guarantee the ordering of union members, so we
        // assert by set rather than by index
        $names = [];
        foreach ($actual as $type) {
            if (! $type instanceof ReflectionNamedType) {
                $this->fail(
                    'expected ReflectionNamedType, got ' . $type::class,
                );
            }
            $names[] = $type->getName();
        }
        sort($names);
        $this->assertSame(['int', 'string'], $names);
    }

    #[TestDox('::from() does not recurse into compound members of a union (DNF)')]
    public function test_from_does_not_recurse_into_dnf_union_members(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // PHP 8.2+ supports DNF types such as (Countable&Traversable)|int.
        // The intersection member must remain a ReflectionIntersectionType
        // in the returned list - from() is a one-level unwrap, not a
        // full flatten. FlattenReflectionType is the helper for callers
        // that need leaf-only results.

        // ----------------------------------------------------------------
        // setup your test

        $fn = static fn ((Countable&Traversable)|int $x): (Countable&Traversable)|int => $x;
        $refType = (new ReflectionFunction($fn))->getReturnType();
        $this->assertInstanceOf(ReflectionUnionType::class, $refType);

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetReflectionTypes::from($refType);

        // ----------------------------------------------------------------
        // test the results

        // PHP does not guarantee the ordering of DNF members, so we
        // assert by category rather than by index
        $this->assertCount(2, $actual);
        $intersections = array_filter(
            $actual,
            static fn (ReflectionType $t): bool
                => $t instanceof ReflectionIntersectionType,
        );
        $nameds = array_filter(
            $actual,
            static fn (ReflectionType $t): bool
                => $t instanceof ReflectionNamedType,
        );
        $this->assertCount(1, $intersections);
        $this->assertCount(1, $nameds);
    }

    // ================================================================
    //
    // ReflectionIntersectionType
    //
    // ----------------------------------------------------------------

    #[TestDox('::from() returns the member types of a ReflectionIntersectionType')]
    public function test_from_returns_member_types_of_intersection(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // an intersection type has two or more class/interface
        // member types. from() must return them in the order PHP
        // reports them, without modification.

        // ----------------------------------------------------------------
        // setup your test

        $fn = static fn (Countable&Traversable $x): Countable&Traversable => $x;
        $refType = (new ReflectionFunction($fn))->getReturnType();
        $this->assertInstanceOf(ReflectionIntersectionType::class, $refType);

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetReflectionTypes::from($refType);

        // ----------------------------------------------------------------
        // test the results

        $this->assertCount(2, $actual);
        // assert by set to avoid depending on PHP's member ordering
        $names = [];
        foreach ($actual as $type) {
            if (! $type instanceof ReflectionNamedType) {
                $this->fail(
                    'expected ReflectionNamedType, got ' . $type::class,
                );
            }
            $names[] = $type->getName();
        }
        sort($names);
        $this->assertSame(['Countable', 'Traversable'], $names);
    }

    // ================================================================
    //
    // Unsupported ReflectionType
    //
    // ----------------------------------------------------------------

    #[TestDox('::from() throws UnsupportedReflectionTypeException for an unknown ReflectionType subclass')]
    public function test_from_throws_for_unknown_reflection_type_subclass(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // if PHP ever adds a new ReflectionType child class, from()
        // must fail loudly rather than silently accept it. This test
        // stands in for that future by supplying a user-defined
        // subclass that from() has no handler for.

        // ----------------------------------------------------------------
        // setup your test

        $refType = new class () extends ReflectionType {
            public function __toString(): string
            {
                return 'custom';
            }

            public function allowsNull(): bool
            {
                return false;
            }
        };

        // ----------------------------------------------------------------
        // test the results

        $this->expectException(UnsupportedReflectionTypeException::class);

        // ----------------------------------------------------------------
        // perform the change

        GetReflectionTypes::from($refType);
    }
}
