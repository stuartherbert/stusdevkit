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
use Stringable;
use StusDevKit\MissingBitsKit\Reflection\FlattenReflectionType;
use StusDevKit\MissingBitsKit\Reflection\IntersectionTypesNotSupportedException;
use StusDevKit\MissingBitsKit\Reflection\UnsupportedReflectionTypeException;
use Traversable;

#[TestDox(FlattenReflectionType::class)]
class FlattenReflectionTypeTest extends TestCase
{
    // ================================================================
    //
    // Class identity
    //
    // ----------------------------------------------------------------

    #[TestDox('is declared as a class')]
    public function test_is_declared_as_a_class(): void
    {
        $reflection = new ReflectionClass(FlattenReflectionType::class);

        $this->assertFalse($reflection->isInterface());
        $this->assertFalse($reflection->isTrait());
        $this->assertFalse($reflection->isEnum());
    }

    #[TestDox('lives in the StusDevKit\\MissingBitsKit\\Reflection namespace')]
    public function test_lives_in_the_expected_namespace(): void
    {
        $expected = 'StusDevKit\\MissingBitsKit\\Reflection';

        $actual = (new ReflectionClass(FlattenReflectionType::class))
            ->getNamespaceName();

        $this->assertSame($expected, $actual);
    }

    #[TestDox('publishes exactly the expected set of public methods')]
    public function test_publishes_expected_public_methods(): void
    {
        $expected = ['from'];

        $reflection = new ReflectionClass(FlattenReflectionType::class);
        $methodNames = [];
        foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
            // skip inherited methods - we only pin the class's own API
            if ($method->getDeclaringClass()->getName() !== FlattenReflectionType::class) {
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
        $method = (new ReflectionClass(FlattenReflectionType::class))
            ->getMethod('from');

        $actual = $method->isPublic();

        $this->assertTrue($actual);
    }

    #[TestDox('::from() is static')]
    public function test_from_is_static(): void
    {
        $method = (new ReflectionClass(FlattenReflectionType::class))
            ->getMethod('from');

        $actual = $method->isStatic();

        $this->assertTrue($actual);
    }

    #[TestDox('::from() takes exactly one parameter')]
    public function test_from_takes_exactly_one_parameter(): void
    {
        $expected = 1;
        $method = (new ReflectionClass(FlattenReflectionType::class))
            ->getMethod('from');

        $actual = $method->getNumberOfParameters();

        $this->assertSame($expected, $actual);
    }

    #[TestDox("::from()'s parameter has a ReflectionType type")]
    public function test_from_parameter_has_a_ReflectionType_type(): void
    {
        $expected = ReflectionType::class;
        $method = (new ReflectionClass(FlattenReflectionType::class))
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
        $method = (new ReflectionClass(FlattenReflectionType::class))
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
     * a ReflectionNamedType is a leaf node. from() should return
     * a one-element array containing its string form.
     */
    #[TestDox('::from() returns the type name for a ReflectionNamedType')]
    public function test_from_returns_name_for_named_type(): void
    {
        $fn = static fn (int $x): int => $x;
        $refType = (new ReflectionFunction($fn))->getReturnType();
        $this->assertInstanceOf(ReflectionNamedType::class, $refType);
        $actual = FlattenReflectionType::from($refType);
        $this->assertSame(['int'], $actual);
    }

    /**
     * a nullable named type (?int) describes two distinct leaf
     * types: the base type and `null`. from() must surface both
     * so that callers iterating the result see `null` as a
     * first-class leaf, just as they would with an explicit
     * `int|null` union.
     */
    #[TestDox('::from() splits a nullable ReflectionNamedType into the base type and null')]
    public function test_from_splits_nullable_into_base_type_and_null(): void
    {
        $fn = static fn (?int $x): ?int => $x;
        $refType = (new ReflectionFunction($fn))->getReturnType();
        $this->assertInstanceOf(ReflectionNamedType::class, $refType);
        $actual = FlattenReflectionType::from($refType);
        // order is not guaranteed, so assert by set
        sort($actual);
        $this->assertSame(['int', 'null'], $actual);
    }

    /**
     * `mixed` reports allowsNull() = true but its string form is
     * `mixed`, not `?mixed`. from() must leave it as a single
     * leaf - it must not synthesise an extra `null` entry.
     */
    #[TestDox('::from() does not append null for a mixed ReflectionNamedType')]
    public function test_from_does_not_append_null_for_mixed(): void
    {
        $fn = static fn (mixed $x): mixed => $x;
        $refType = (new ReflectionFunction($fn))->getReturnType();
        $this->assertInstanceOf(ReflectionNamedType::class, $refType);
        $actual = FlattenReflectionType::from($refType);
        $this->assertSame(['mixed'], $actual);
    }

    /**
     * an explicit `null` return type is its own ReflectionNamedType
     * whose string form is `null`. from() must not treat it as
     * nullable and produce `['null', 'null']`.
     */
    #[TestDox('::from() does not duplicate null for an explicit null ReflectionNamedType')]
    public function test_from_does_not_duplicate_null_for_explicit_null(): void
    {
        $fn = static fn (): null => null;
        $refType = (new ReflectionFunction($fn))->getReturnType();
        $this->assertInstanceOf(ReflectionNamedType::class, $refType);
        $actual = FlattenReflectionType::from($refType);
        $this->assertSame(['null'], $actual);
    }

    // ================================================================
    //
    // ReflectionUnionType
    //
    // ----------------------------------------------------------------

    /**
     * a scalar-only union has PHP's canonical ordering baked in:
     * `int|string` and `string|int` both stringify to
     * "string|int" - PHP normalises at parse time. Our
     * implementation takes its ordering directly from the text
     * representation, so the observable output must match PHP's
     * canonical form. The test asserts the literal order
     * (without sort) to pin that contract - if a future PHP
     * release changes the canonical order, we want the failure
     * to land here rather than surprise a downstream caller.
     */
    #[TestDox('::from() returns all member names for a ReflectionUnionType, in PHP\'s canonical order for scalar-only unions')]
    public function test_from_returns_scalar_union_members_in_canonical_order(): void
    {
        $fn = static fn (int|string $x): int|string => $x;
        $refType = (new ReflectionFunction($fn))->getReturnType();
        $this->assertInstanceOf(ReflectionUnionType::class, $refType);
        $actual = FlattenReflectionType::from($refType);
        $this->assertSame(['string', 'int'], $actual);
    }

    /**
     * class-only unions are the case where declaration order is
     * meaningful to the caller - for a DI resolver, it tells
     * the resolver "try A first, then B". PHP's text
     * representation of a class-only union preserves the order
     * the developer wrote (unlike scalar unions, which PHP
     * canonicalises). Our implementation takes its ordering
     * from the text, so we must honour that for class-only
     * unions. This test pins the guarantee.
     */
    #[TestDox('::from() preserves declaration order for class-only ReflectionUnionType')]
    public function test_from_preserves_declaration_order_for_class_only_union(): void
    {
        $fn = static fn (Countable|Traversable $x): Countable|Traversable => $x;
        $refType = (new ReflectionFunction($fn))->getReturnType();
        $this->assertInstanceOf(ReflectionUnionType::class, $refType);
        $actual = FlattenReflectionType::from($refType);
        $this->assertSame(['Countable', 'Traversable'], $actual);
    }

    // ================================================================
    //
    // ReflectionIntersectionType (refused)
    //
    // A flat list of names cannot faithfully represent an intersection.
    // `A&B` (a value satisfying BOTH) would collapse to `['A', 'B']`,
    // indistinguishable from the list produced for `A|B` (a value
    // satisfying EITHER) - so downstream callers reasoning from the
    // flat list would draw wrong conclusions. from() refuses
    // intersections rather than silently produce misleading output.
    //
    // ----------------------------------------------------------------

    /**
     * a bare intersection `A&B` cannot be flattened without
     * losing its "and" semantics (see section comment above), so
     * from() must refuse it explicitly via
     * IntersectionTypesNotSupportedException.
     */
    #[TestDox('::from() throws IntersectionTypesNotSupportedException for a ReflectionIntersectionType')]
    public function test_from_throws_for_intersection(): void
    {
        $fn = static fn (Countable&Traversable $x): Countable&Traversable => $x;
        $refType = (new ReflectionFunction($fn))->getReturnType();
        $this->assertInstanceOf(ReflectionIntersectionType::class, $refType);
        $this->expectException(IntersectionTypesNotSupportedException::class);
        FlattenReflectionType::from($refType);
    }

    /**
     * a DNF type such as `(A&B)|C` mixes a union and an
     * intersection. Even though the non-intersection branch
     * could in principle be flattened, from() refuses the whole
     * input: returning a flat list that silently dropped the
     * intersection branch would be as misleading as flattening
     * the intersection itself. Callers get a uniform "cannot
     * flatten intersections" response regardless of where the
     * intersection appears in the tree.
     */
    #[TestDox('::from() throws IntersectionTypesNotSupportedException for a DNF type with any intersection branch')]
    public function test_from_throws_for_dnf_with_intersection_branch(): void
    {
        $fn = static fn ((Countable&Traversable)|int $x): (Countable&Traversable)|int => $x;
        $refType = (new ReflectionFunction($fn))->getReturnType();
        $this->assertInstanceOf(ReflectionUnionType::class, $refType);
        $this->expectException(IntersectionTypesNotSupportedException::class);
        FlattenReflectionType::from($refType);
    }

    // ================================================================
    //
    // Unsupported ReflectionType
    //
    // ----------------------------------------------------------------

    /**
     * from() delegates to GetReflectionTypes for the top-level
     * unwrap. An unknown ReflectionType subclass must therefore
     * surface the same UnsupportedReflectionTypeException.
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
        FlattenReflectionType::from($refType);
    }
}
