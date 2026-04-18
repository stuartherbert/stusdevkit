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

namespace StusDevKit\MissingBitsKit\Tests\Unit\Enums;

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionNamedType;
use StusDevKit\MissingBitsKit\Enums\EnumToArray;
use StusDevKit\MissingBitsKit\Tests\Fixtures\Enums\IntBackedSampleEnum;
use StusDevKit\MissingBitsKit\Tests\Fixtures\Enums\SingleCaseBackedEnum;
use StusDevKit\MissingBitsKit\Tests\Fixtures\Enums\StringBackedSampleEnum;

/**
 * Contract + behaviour tests for the EnumToArray trait.
 *
 * These tests act as a lockdown on the trait's published shape and
 * observed runtime behaviour: reshaping the method, renaming it, or
 * changing the returned map structure must be an intentional act
 * that updates these tests at the same time.
 */
#[TestDox(EnumToArray::class)]
class EnumToArrayTest extends TestCase
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

        // EnumToArray must be a trait (not a class or interface).
        // Using enums rely on this so they can declare `use
        // EnumToArray;` in their body.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(EnumToArray::class);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $reflection->isTrait();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('lives in the StusDevKit\\MissingBitsKit\\Enums namespace')]
    public function test_lives_in_the_expected_namespace(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the published namespace is part of the contract - using
        // enums import the trait by FQN, so moving it is a breaking
        // change that must go through a major version bump.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'StusDevKit\\MissingBitsKit\\Enums';

        // ----------------------------------------------------------------
        // perform the change

        $actual = (new ReflectionClass(EnumToArray::class))
            ->getNamespaceName();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('exposes only a toArray() method')]
    public function test_exposes_only_a_toArray_method(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the trait exists to supply a single method, toArray().
        // Adding a second method is a surface-area expansion that
        // every using enum inherits, so the method set is pinned by
        // enumeration - any addition fails with a diff that names
        // the new method, rather than a cryptic count mismatch.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['toArray'];
        $reflection = new ReflectionClass(EnumToArray::class);

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
    // toArray() shape
    //
    // ----------------------------------------------------------------

    #[TestDox('declares a toArray() method')]
    public function test_declares_a_toArray_method(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the single supplied method is `toArray()`. Renaming it is
        // a breaking change for every using enum's callers.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(EnumToArray::class);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $reflection->hasMethod('toArray');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('toArray() is public')]
    public function test_toArray_is_public(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the method must be public so using enums can satisfy the
        // StaticallyArrayable interface contract, which requires a
        // public method.

        // ----------------------------------------------------------------
        // setup your test

        $method = (new ReflectionClass(EnumToArray::class))
            ->getMethod('toArray');

        // ----------------------------------------------------------------
        // perform the change

        $actual = $method->isPublic();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('toArray() is static')]
    public function test_toArray_is_static(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the trait implements StaticallyArrayable::toArray(), which
        // is static. The set of enum cases is a property of the
        // type, not of any individual case, so silently downgrading
        // to an instance method would defeat the design.

        // ----------------------------------------------------------------
        // setup your test

        $method = (new ReflectionClass(EnumToArray::class))
            ->getMethod('toArray');

        // ----------------------------------------------------------------
        // perform the change

        $actual = $method->isStatic();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('toArray() takes no parameters')]
    public function test_toArray_takes_no_parameters(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the method promises a parameter-less call: the type's
        // full array representation, no options, no filters. Adding
        // a required parameter would break every call site.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 0;
        $method = (new ReflectionClass(EnumToArray::class))
            ->getMethod('toArray');

        // ----------------------------------------------------------------
        // perform the change

        $actual = $method->getNumberOfParameters();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('toArray() declares an `array` return type')]
    public function test_toArray_declares_an_array_return_type(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the runtime return type is `array`. The richer
        // `array<string, TValue>` shape lives in the docblock for
        // PHPStan; the native return type pins the runtime shape.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'array';
        $method = (new ReflectionClass(EnumToArray::class))
            ->getMethod('toArray');
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
    // toArray() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('toArray() returns a name-to-value map for a string-backed enum')]
    public function test_toArray_returns_name_to_value_map_for_string_backed_enum(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the contract: keys are case names (uppercase), values are
        // the backing strings. Every case in the fixture must
        // appear exactly once.

        // ----------------------------------------------------------------
        // setup your test

        $expected = [
            'ZEBRA' => 'zebra-value',
            'APPLE' => 'apple-value',
            'MANGO' => 'mango-value',
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actual = StringBackedSampleEnum::toArray();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('toArray() returns a name-to-value map for an int-backed enum')]
    public function test_toArray_returns_name_to_value_map_for_int_backed_enum(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the trait is generic over the backing type. For an
        // int-backed enum, the values in the map must be ints, not
        // their string forms.

        // ----------------------------------------------------------------
        // setup your test

        $expected = [
            'TEN'       => 10,
            'TWENTY'    => 20,
            'THIRTY'    => 30,
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actual = IntBackedSampleEnum::toArray();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('toArray() returns a one-entry map for a single-case enum')]
    public function test_toArray_returns_one_entry_map_for_single_case_enum(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the minimum-size input - a single-case enum - must still
        // produce a valid map. Pins the behaviour at the boundary
        // where the loop body runs exactly once.

        // ----------------------------------------------------------------
        // setup your test

        $expected = [
            'ONLY' => 'only-value',
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actual = SingleCaseBackedEnum::toArray();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('toArray() preserves case declaration order')]
    public function test_toArray_preserves_case_declaration_order(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the returned map's keys must match the case declaration
        // order, not the alphabetic order of case names. The
        // StringBackedSampleEnum fixture declares its cases in
        // non-alphabetical order (ZEBRA, APPLE, MANGO) so a hidden
        // sort would show up here as an ordering mismatch.

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['ZEBRA', 'APPLE', 'MANGO'];

        // ----------------------------------------------------------------
        // perform the change

        $actual = array_keys(StringBackedSampleEnum::toArray());

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }
}
