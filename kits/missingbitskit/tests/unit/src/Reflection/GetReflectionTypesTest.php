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
use ReflectionClass;
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
    // Class identity
    //
    // ----------------------------------------------------------------

    #[TestDox('is declared as a class')]
    public function test_is_declared_as_a_class(): void
    {
        $reflection = new ReflectionClass(GetReflectionTypes::class);

        $this->assertFalse($reflection->isInterface());
        $this->assertFalse($reflection->isTrait());
        $this->assertFalse($reflection->isEnum());
    }

    #[TestDox('lives in the StusDevKit\\MissingBitsKit\\Reflection namespace')]
    public function test_lives_in_the_expected_namespace(): void
    {
        $expected = 'StusDevKit\\MissingBitsKit\\Reflection';

        $actual = (new ReflectionClass(GetReflectionTypes::class))
            ->getNamespaceName();

        $this->assertSame($expected, $actual);
    }

    #[TestDox('publishes exactly the expected set of public methods')]
    public function test_publishes_expected_public_methods(): void
    {
        $expected = ['from'];

        $reflection = new ReflectionClass(GetReflectionTypes::class);
        $methodNames = [];
        foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
            // skip inherited methods - we only pin the class's own API
            if ($method->getDeclaringClass()->getName() !== GetReflectionTypes::class) {
                continue;
            }
            $methodNames[] = $method->getName();
        }
        sort($methodNames);

        $this->assertSame($expected, $methodNames);
    }

    // ================================================================
    //
    // ::from() method shape
    //
    // ----------------------------------------------------------------

    #[TestDox('::from() is public')]
    public function test_from_is_public(): void
    {
        $method = (new ReflectionClass(GetReflectionTypes::class))
            ->getMethod('from');

        $actual = $method->isPublic();

        $this->assertTrue($actual);
    }

    #[TestDox('::from() is static')]
    public function test_from_is_static(): void
    {
        $method = (new ReflectionClass(GetReflectionTypes::class))
            ->getMethod('from');

        $actual = $method->isStatic();

        $this->assertTrue($actual);
    }

    #[TestDox('::from() takes exactly one parameter')]
    public function test_from_takes_exactly_one_parameter(): void
    {
        $expected = 1;
        $method = (new ReflectionClass(GetReflectionTypes::class))
            ->getMethod('from');

        $actual = $method->getNumberOfParameters();

        $this->assertSame($expected, $actual);
    }

    #[TestDox("::from()'s parameter has a ReflectionType type")]
    public function test_from_parameter_has_a_ReflectionType_type(): void
    {
        $expected = ReflectionType::class;
        $method = (new ReflectionClass(GetReflectionTypes::class))
            ->getMethod('from');
        $parameters = $method->getParameters();
        $this->assertCount(1, $parameters);
        $paramType = $parameters[0]->getType();
        $this->assertInstanceOf(ReflectionNamedType::class, $paramType);

        $actual = $paramType->getName();

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::from() declares an array return type')]
    public function test_from_declares_an_array_return_type(): void
    {
        $expected = 'array';
        $method = (new ReflectionClass(GetReflectionTypes::class))
            ->getMethod('from');
        $returnType = $method->getReturnType();
        $this->assertInstanceOf(ReflectionNamedType::class, $returnType);

        $actual = $returnType->getName();

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // ReflectionNamedType
    //
    // ----------------------------------------------------------------

    /**
     * a ReflectionNamedType is a leaf node - it has no sub-types.
     * from() should return a one-element array containing the
     * input unchanged.
     */
    #[TestDox('::from() wraps a ReflectionNamedType in a single-item array')]
    public function test_from_wraps_named_type_in_array(): void
    {
        $fn = static fn (int $x): int => $x;
        $refType = (new ReflectionFunction($fn))->getReturnType();
        $this->assertInstanceOf(ReflectionNamedType::class, $refType);
        $actual = GetReflectionTypes::from($refType);
        $this->assertCount(1, $actual);
        $this->assertSame($refType, $actual[0]);
    }

    /**
     * a nullable named type (?int) is still a single
     * ReflectionNamedType that reports allowsNull() = true.
     * from() must not expand it into [int, null].
     */
    #[TestDox('::from() wraps a nullable ReflectionNamedType in a single-item array')]
    public function test_from_wraps_nullable_named_type_in_array(): void
    {
        $fn = static fn (?int $x): ?int => $x;
        $refType = (new ReflectionFunction($fn))->getReturnType();
        $this->assertInstanceOf(ReflectionNamedType::class, $refType);
        $actual = GetReflectionTypes::from($refType);
        $this->assertCount(1, $actual);
        $this->assertSame($refType, $actual[0]);
        $this->assertTrue($actual[0]->allowsNull());
    }

    // ================================================================
    //
    // ReflectionUnionType
    //
    // ----------------------------------------------------------------

    /**
     * a union type has two or more member types. from() must
     * return them in the order PHP reports them, without
     * modification.
     */
    #[TestDox('::from() returns the member types of a ReflectionUnionType')]
    public function test_from_returns_member_types_of_union(): void
    {
        $fn = static fn (int|string $x): int|string => $x;
        $refType = (new ReflectionFunction($fn))->getReturnType();
        $this->assertInstanceOf(ReflectionUnionType::class, $refType);
        $actual = GetReflectionTypes::from($refType);
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

    /**
     * PHP 8.2+ supports DNF types such as (Countable&Traversable)|int.
     * The intersection member must remain a ReflectionIntersectionType
     * in the returned list - from() is a one-level unwrap, not a
     * full flatten. FlattenReflectionType is the helper for callers
     * that need leaf-only results.
     */
    #[TestDox('::from() does not recurse into compound members of a union (DNF)')]
    public function test_from_does_not_recurse_into_dnf_union_members(): void
    {
        $fn = static fn ((Countable&Traversable)|int $x): (Countable&Traversable)|int => $x;
        $refType = (new ReflectionFunction($fn))->getReturnType();
        $this->assertInstanceOf(ReflectionUnionType::class, $refType);
        $actual = GetReflectionTypes::from($refType);
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

    /**
     * an intersection type has two or more class/interface
     * member types. from() must return them in the order PHP
     * reports them, without modification.
     */
    #[TestDox('::from() returns the member types of a ReflectionIntersectionType')]
    public function test_from_returns_member_types_of_intersection(): void
    {
        $fn = static fn (Countable&Traversable $x): Countable&Traversable => $x;
        $refType = (new ReflectionFunction($fn))->getReturnType();
        $this->assertInstanceOf(ReflectionIntersectionType::class, $refType);
        $actual = GetReflectionTypes::from($refType);
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

    /**
     * if PHP ever adds a new ReflectionType child class, from()
     * must fail loudly rather than silently accept it. This test
     * stands in for that future by supplying a user-defined
     * subclass that from() has no handler for.
     */
    #[TestDox('::from() throws UnsupportedReflectionTypeException for an unknown ReflectionType subclass')]
    public function test_from_throws_for_unknown_reflection_type_subclass(): void
    {
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
        $this->expectException(UnsupportedReflectionTypeException::class);
        GetReflectionTypes::from($refType);
    }
}
