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

namespace StusDevKit\ValidationKit\Tests\Unit\Schemas\Builtins;

use BackedEnum;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionEnum;
use StusDevKit\ValidationKit\Schemas\Builtins\UnknownKeyPolicy;
use UnitEnum;

#[TestDox('UnknownKeyPolicy')]
class UnknownKeyPolicyTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\ValidationKit\\Schemas\\Builtins namespace')]
    public function test_lives_in_expected_namespace(): void
    {
        // the enum lives with its consumers (ObjectSchema,
        // AssocArraySchema) so a refactor that moves those
        // classes must move the enum too.
        $reflection = new ReflectionEnum(UnknownKeyPolicy::class);
        $this->assertSame(
            'StusDevKit\\ValidationKit\\Schemas\\Builtins',
            $reflection->getNamespaceName(),
        );
    }

    #[TestDox('is a backed enum')]
    public function test_is_a_backed_enum(): void
    {
        // backed enums can round-trip through a string wire
        // format (JSON Schema import/export); a plain enum
        // would silently break that path.
        $reflection = new ReflectionEnum(UnknownKeyPolicy::class);
        $this->assertTrue($reflection->isBacked());
    }

    #[TestDox('is backed by strings')]
    public function test_is_backed_by_strings(): void
    {
        // JSON Schema's `additionalProperties` keyword is a
        // string-valued concept; the enum's backing type
        // must match.
        $reflection = new ReflectionEnum(UnknownKeyPolicy::class);
        $backingType = $reflection->getBackingType();
        $this->assertNotNull($backingType);
        /** @var \ReflectionNamedType $backingType */
        $this->assertSame('string', $backingType->getName());
    }

    #[TestDox('implements UnitEnum and BackedEnum')]
    public function test_implements_enum_interfaces(): void
    {
        // pinning the PHP enum interfaces keeps the type
        // contract stable for callers that constrain by
        // interface (e.g. a hypothetical enum-aware
        // serialiser).
        $unit = UnknownKeyPolicy::Strip;

        $this->assertInstanceOf(UnitEnum::class, $unit);
        $this->assertInstanceOf(BackedEnum::class, $unit);
    }

    // ================================================================
    //
    // Shape
    //
    // ----------------------------------------------------------------

    #[TestDox('has exactly the cases Strip, Strict and Passthrough')]
    public function test_declares_expected_case_set(): void
    {
        // set-based assertion: if a future contributor adds
        // or removes a case, this test names the offender
        // rather than reporting an arbitrary count mismatch.
        $caseNames = array_map(
            static fn(UnknownKeyPolicy $c): string => $c->name,
            UnknownKeyPolicy::cases(),
        );

        $this->assertSame(
            ['Strip', 'Strict', 'Passthrough'],
            $caseNames,
        );
    }

    /**
     * @return array<string, array{0: string, 1: string}>
     */
    public static function provideCaseBackingValues(): array
    {
        return [
            'Strip' => ['Strip', 'strip'],
            'Strict' => ['Strict', 'strict'],
            'Passthrough' => ['Passthrough', 'passthrough'],
        ];
    }

    #[DataProvider('provideCaseBackingValues')]
    #[TestDox('case $caseName is backed by the string "$expectedValue"')]
    public function test_case_has_expected_backing_value(
        string $caseName,
        string $expectedValue,
    ): void {
        // data provider uses scalar inputs (per the project
        // TestDox convention) and resolves to the enum case
        // inside the test body.
        /** @var UnknownKeyPolicy $case */
        $case = constant(
            UnknownKeyPolicy::class . '::' . $caseName,
        );

        $this->assertSame($expectedValue, $case->value);
    }

    // ================================================================
    //
    // Behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('from() resolves a known backing value to its enum case')]
    public function test_from_resolves_known_value(): void
    {
        $this->assertSame(
            UnknownKeyPolicy::Strip,
            UnknownKeyPolicy::from('strip'),
        );
        $this->assertSame(
            UnknownKeyPolicy::Strict,
            UnknownKeyPolicy::from('strict'),
        );
        $this->assertSame(
            UnknownKeyPolicy::Passthrough,
            UnknownKeyPolicy::from('passthrough'),
        );
    }

    #[TestDox('tryFrom() returns null for an unknown backing value')]
    public function test_tryFrom_returns_null_for_unknown_value(): void
    {
        // tryFrom is the softer of the two factories; it's
        // the one an input-handling boundary should prefer.
        $this->assertNull(UnknownKeyPolicy::tryFrom('unknown'));
    }
}
