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

namespace StusDevKit\ValidationKit\Tests\Unit\Coercions;

use DateTimeImmutable;
use DateTimeInterface;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
use StusDevKit\ValidationKit\Coercions\CoerceToDateTime;
use StusDevKit\ValidationKit\Contracts\ValueCoercion;

#[TestDox('CoerceToDateTime')]
class CoerceToDateTimeTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\ValidationKit\\Coercions namespace')]
    public function test_lives_in_expected_namespace(): void
    {
        $reflection = new ReflectionClass(CoerceToDateTime::class);

        $this->assertSame(
            'StusDevKit\\ValidationKit\\Coercions',
            $reflection->getNamespaceName(),
        );
    }

    #[TestDox('is a class')]
    public function test_is_a_class(): void
    {
        $reflection = new ReflectionClass(CoerceToDateTime::class);

        $this->assertFalse($reflection->isInterface());
        $this->assertFalse($reflection->isTrait());
        $this->assertFalse($reflection->isEnum());
    }

    #[TestDox('implements ValueCoercion')]
    public function test_implements_value_coercion(): void
    {
        $reflection = new ReflectionClass(CoerceToDateTime::class);

        $this->assertTrue($reflection->implementsInterface(ValueCoercion::class));
    }

    #[TestDox('publishes exactly the coerce() method')]
    public function test_publishes_expected_public_methods(): void
    {
        $reflection = new ReflectionClass(CoerceToDateTime::class);

        $ownMethods = array_values(array_filter(
            $reflection->getMethods(ReflectionMethod::IS_PUBLIC),
            static fn (ReflectionMethod $m): bool
                => $m->getDeclaringClass()->getName() === CoerceToDateTime::class,
        ));
        $methodNames = array_map(
            static fn (ReflectionMethod $m): string => $m->getName(),
            $ownMethods,
        );
        sort($methodNames);

        $this->assertSame(['coerce'], $methodNames);
    }

    // ================================================================
    //
    // Shape
    //
    // ----------------------------------------------------------------

    #[TestDox('->coerce() is an instance method')]
    public function test_coerce_is_instance_method(): void
    {
        $method = new ReflectionMethod(CoerceToDateTime::class, 'coerce');

        $this->assertFalse($method->isStatic());
        $this->assertTrue($method->isPublic());
    }

    #[TestDox('->coerce() accepts a single mixed $data parameter')]
    public function test_coerce_accepts_single_mixed_parameter(): void
    {
        $method = new ReflectionMethod(CoerceToDateTime::class, 'coerce');

        $params = $method->getParameters();

        $this->assertCount(1, $params);
        $this->assertSame('data', $params[0]->getName());

        $type = $params[0]->getType();
        $this->assertInstanceOf(ReflectionNamedType::class, $type);
        $this->assertSame('mixed', $type->getName());
    }

    #[TestDox('->coerce() returns mixed')]
    public function test_coerce_returns_mixed(): void
    {
        $method = new ReflectionMethod(CoerceToDateTime::class, 'coerce');

        $type = $method->getReturnType();
        $this->assertInstanceOf(ReflectionNamedType::class, $type);
        $this->assertSame('mixed', $type->getName());
    }

    // ================================================================
    //
    // ISO 8601 String Coercion
    //
    // ----------------------------------------------------------------

    #[TestDox('->coerce() coerces ISO 8601 (ATOM) string to DateTimeImmutable')]
    public function test_coerces_atom_string(): void
    {
        /** converts an ISO 8601 ATOM-format string to a DateTimeImmutable */
        $unit = new CoerceToDateTime();
        $input = '2026-01-15T10:30:00+00:00';

        $actualResult = $unit->coerce($input);

        $this->assertInstanceOf(
            DateTimeImmutable::class,
            $actualResult,
        );
        $this->assertSame(
            '2026-01-15T10:30:00+00:00',
            $actualResult->format(DateTimeInterface::ATOM),
        );
    }

    // ================================================================
    //
    // Lenient String Coercion
    //
    // ----------------------------------------------------------------

    #[TestDox('->coerce() coerces lenient date string to DateTimeImmutable')]
    public function test_coerces_lenient_date_string(): void
    {
        /** falls back to lenient parsing for non-ATOM date strings */
        $unit = new CoerceToDateTime();
        $input = '2026-01-15';

        $actualResult = $unit->coerce($input);

        $this->assertInstanceOf(
            DateTimeImmutable::class,
            $actualResult,
        );
        $this->assertSame(
            '2026-01-15',
            $actualResult->format('Y-m-d'),
        );
    }

    // ================================================================
    //
    // Timestamp Coercion
    //
    // ----------------------------------------------------------------

    #[TestDox('->coerce() coerces integer timestamp to DateTimeImmutable')]
    public function test_coerces_integer_timestamp(): void
    {
        /** converts an integer Unix timestamp to a DateTimeImmutable */
        $unit = new CoerceToDateTime();
        $timestamp = 1700000000;

        $actualResult = $unit->coerce($timestamp);

        $this->assertInstanceOf(
            DateTimeImmutable::class,
            $actualResult,
        );
        $this->assertSame(
            $timestamp,
            $actualResult->getTimestamp(),
        );
    }

    // ================================================================
    //
    // Non-Coercible Values
    //
    // ----------------------------------------------------------------

    #[TestDox('->coerce() returns unparseable string unchanged')]
    public function test_returns_unparseable_string_unchanged(): void
    {
        /** a string that cannot be parsed as a date is returned unchanged */
        $unit = new CoerceToDateTime();
        $input = 'not-a-date';

        $actualResult = $unit->coerce($input);

        $this->assertSame('not-a-date', $actualResult);
    }

    #[TestDox('->coerce() returns null unchanged')]
    public function test_returns_null_unchanged(): void
    {
        /** null is returned unchanged */
        $unit = new CoerceToDateTime();

        $actualResult = $unit->coerce(null);

        $this->assertNull($actualResult);
    }

    #[TestDox('->coerce() returns float unchanged')]
    public function test_returns_float_unchanged(): void
    {
        /** a float is returned unchanged — only int timestamps are coerced */
        $unit = new CoerceToDateTime();

        $actualResult = $unit->coerce(3.14);

        $this->assertSame(3.14, $actualResult);
    }

    #[TestDox('->coerce() returns array unchanged')]
    public function test_returns_array_unchanged(): void
    {
        /** an array is returned unchanged */
        $unit = new CoerceToDateTime();
        $input = ['2026-01-15'];

        $actualResult = $unit->coerce($input);

        $this->assertSame($input, $actualResult);
    }
}
