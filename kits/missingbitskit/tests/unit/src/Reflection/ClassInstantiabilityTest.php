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

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionEnum;
use ReflectionNamedType;
use StusDevKit\MissingBitsKit\Arrays\StaticallyArrayable;
use StusDevKit\MissingBitsKit\Reflection\ClassInstantiability;

/**
 * Contract + behaviour tests for the ClassInstantiability enum.
 *
 * These tests act as a lockdown on the enum's published shape and
 * observed runtime behaviour: adding a case, renaming a case,
 * changing a backing value, or altering what `isInstantiable()` /
 * `toArray()` return must be an intentional act that updates these
 * tests at the same time.
 *
 * Each case's backing value is user-facing — it appears in error
 * messages via `$result->value` — so the values are pinned
 * individually.
 */
#[TestDox(ClassInstantiability::class)]
class ClassInstantiabilityTest extends TestCase
{
    // ================================================================
    //
    // Enum identity
    //
    // ----------------------------------------------------------------

    /**
     * ClassInstantiability must be an enum (not a class or
     * interface). Callers rely on this so they can pattern-match
     * on cases.
     */
    #[TestDox('is declared as an enum')]
    public function test_is_declared_as_an_enum(): void
    {
        $reflection = new ReflectionClass(ClassInstantiability::class);
        $actual = $reflection->isEnum();
        $this->assertTrue($actual);
    }

    /**
     * the published namespace is part of the contract - callers
     * import by FQN, so moving the enum is a breaking change
     * that must go through a major version bump.
     */
    #[TestDox('lives in the StusDevKit\\MissingBitsKit\\Reflection namespace')]
    public function test_lives_in_the_expected_namespace(): void
    {
        $expected = 'StusDevKit\\MissingBitsKit\\Reflection';
        $actual = (new ReflectionClass(ClassInstantiability::class))
            ->getNamespaceName();
        $this->assertSame($expected, $actual);
    }

    /**
     * the enum is declared `enum ClassInstantiability: string`.
     * Switching to a pure enum or an int-backed enum would
     * silently break every caller that reads `$result->value`
     * for error messages. Pin the backing type.
     */
    #[TestDox('is a string-backed enum')]
    public function test_is_a_string_backed_enum(): void
    {
        $expected = 'string';
        $reflection = new ReflectionEnum(ClassInstantiability::class);
        $backingType = $reflection->getBackingType();
        $this->assertInstanceOf(ReflectionNamedType::class, $backingType);
        $actual = $backingType->getName();
        $this->assertSame($expected, $actual);
    }

    /**
     * the enum is required by the project's engineering
     * standards to implement StaticallyArrayable, so that its
     * case set is exposed as an array for data-provider tests,
     * config rendering, and similar consumers.
     */
    #[TestDox('implements StaticallyArrayable')]
    public function test_implements_StaticallyArrayable(): void
    {
        $reflection = new ReflectionClass(ClassInstantiability::class);
        $actual = $reflection->implementsInterface(StaticallyArrayable::class);
        $this->assertTrue($actual);
    }

    // ================================================================
    //
    // Cases
    //
    // ----------------------------------------------------------------

    /**
     * @return array<string, array{0: string}>
     */
    public static function caseNameProvider(): array
    {
        return [
            'INSTANTIABLE'              => ['INSTANTIABLE'],
            'CLASS_DOES_NOT_EXIST'      => ['CLASS_DOES_NOT_EXIST'],
            'IS_INTERFACE'              => ['IS_INTERFACE'],
            'IS_TRAIT'                  => ['IS_TRAIT'],
            'IS_ENUM'                   => ['IS_ENUM'],
            'IS_ABSTRACT'               => ['IS_ABSTRACT'],
            'CONSTRUCTOR_NOT_PUBLIC'    => ['CONSTRUCTOR_NOT_PUBLIC'],
        ];
    }

    /**
     * each case is pinned by name so that a reader of the
     * TestDox output sees the full case set as a sequence of
     * `has case X` lines. Removing or renaming a case fails
     * the corresponding line with a clear message, naming
     * exactly which case has drifted.
     */
    #[TestDox('has case $caseName')]
    #[DataProvider('caseNameProvider')]
    public function test_has_case(string $caseName): void
    {
        $reflection = new ReflectionEnum(ClassInstantiability::class);
        $actual = $reflection->hasCase($caseName);
        $this->assertTrue($actual);
    }

    // ================================================================
    //
    // Case backing values
    //
    // ----------------------------------------------------------------

    /**
     * @return array<string, array{0: string, 1: string}>
     */
    public static function caseBackingValueProvider(): array
    {
        return [
            'INSTANTIABLE'              => ['INSTANTIABLE',              'instantiable'],
            'CLASS_DOES_NOT_EXIST'      => ['CLASS_DOES_NOT_EXIST',      'class does not exist'],
            'IS_INTERFACE'              => ['IS_INTERFACE',              'is an interface'],
            'IS_TRAIT'                  => ['IS_TRAIT',                  'is a trait'],
            'IS_ENUM'                   => ['IS_ENUM',                   'is an enum'],
            'IS_ABSTRACT'               => ['IS_ABSTRACT',               'is an abstract class'],
            'CONSTRUCTOR_NOT_PUBLIC'    => ['CONSTRUCTOR_NOT_PUBLIC',    'constructor is not public'],
        ];
    }

    /**
     * each case's backing value is user-facing: callers drop
     * `$result->value` into error messages ("cannot build X:
     * is an abstract class"). Every value is pinned so a silent
     * wording change is caught, not carried.
     */
    #[TestDox('$caseName has backing value "$expectedValue"')]
    #[DataProvider('caseBackingValueProvider')]
    public function test_case_has_its_expected_backing_value(
        string $caseName,
        string $expectedValue,
    ): void {
        // look up the case by name. `constant()` on a Class::NAME
        // string returns the enum case; assertInstanceOf narrows
        // the type for PHPStan and guards against the name being
        // a stale reference.
        $case = constant(ClassInstantiability::class . '::' . $caseName);
        $this->assertInstanceOf(ClassInstantiability::class, $case);
        $actual = $case->value;
        $this->assertSame($expectedValue, $actual);
    }

    // ================================================================
    //
    // toArray()
    //
    // ----------------------------------------------------------------

    /**
     * the enum uses the EnumToArray trait, which implements
     * StaticallyArrayable::toArray(). The full 7-entry map is
     * pinned here so that changes to the case set or trait
     * implementation are caught at this level as well as at
     * the trait's own test.
     */
    #[TestDox('::toArray() returns a map of every case name to its backing value')]
    public function test_toArray_returns_a_map_of_every_case_name_to_its_backing_value(): void
    {
        $expected = [
            'INSTANTIABLE'              => 'instantiable',
            'CLASS_DOES_NOT_EXIST'      => 'class does not exist',
            'IS_INTERFACE'              => 'is an interface',
            'IS_TRAIT'                  => 'is a trait',
            'IS_ENUM'                   => 'is an enum',
            'IS_ABSTRACT'               => 'is an abstract class',
            'CONSTRUCTOR_NOT_PUBLIC'    => 'constructor is not public',
        ];
        $actual = ClassInstantiability::toArray();
        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // isInstantiable()
    //
    // ----------------------------------------------------------------

    /**
     * the helper is a pass/fail predicate. It must return true
     * when - and only when - the enum value is INSTANTIABLE.
     */
    #[TestDox('->isInstantiable() returns true for INSTANTIABLE')]
    public function test_isInstantiable_returns_true_for_INSTANTIABLE(): void
    {
        $value = ClassInstantiability::INSTANTIABLE;
        $actual = $value->isInstantiable();
        $this->assertTrue($actual);
    }

    /**
     * @return array<string, array{0: string}>
     */
    public static function nonInstantiableCaseProvider(): array
    {
        return [
            'CLASS_DOES_NOT_EXIST'      => ['CLASS_DOES_NOT_EXIST'],
            'IS_INTERFACE'              => ['IS_INTERFACE'],
            'IS_TRAIT'                  => ['IS_TRAIT'],
            'IS_ENUM'                   => ['IS_ENUM'],
            'IS_ABSTRACT'               => ['IS_ABSTRACT'],
            'CONSTRUCTOR_NOT_PUBLIC'    => ['CONSTRUCTOR_NOT_PUBLIC'],
        ];
    }

    /**
     * the helper is `$this === self::INSTANTIABLE`, so every
     * other case falls through to false. Pin each non-
     * instantiable case so a future implementation change that
     * accidentally whitelists one (e.g. by switching to a
     * `match` without a default) is caught here.
     */
    #[TestDox('->isInstantiable() returns false for $caseName')]
    #[DataProvider('nonInstantiableCaseProvider')]
    public function test_isInstantiable_returns_false_for_case(string $caseName): void
    {
        // look up the case by name. `constant()` on a Class::NAME
        // string returns the enum case; assertInstanceOf narrows
        // the type for PHPStan.
        $case = constant(ClassInstantiability::class . '::' . $caseName);
        $this->assertInstanceOf(ClassInstantiability::class, $case);
        $actual = $case->isInstantiable();
        $this->assertFalse($actual);
    }
}
