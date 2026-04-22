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

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use StusDevKit\ValidationKit\Contracts\ValidationSchema;
use StusDevKit\ValidationKit\Exceptions\ValidationException;
use StusDevKit\ValidationKit\Schemas\BaseSchema;
use StusDevKit\ValidationKit\Schemas\Builtins\NumberSchema;

#[TestDox(NumberSchema::class)]
class NumberSchemaTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\ValidationKit\\Schemas\\Builtins namespace')]
    public function test_lives_in_expected_namespace(): void
    {
        $expected = 'StusDevKit\\ValidationKit\\Schemas\\Builtins';
        $actual = (new ReflectionClass(NumberSchema::class))
            ->getNamespaceName();
        $this->assertSame($expected, $actual);
    }

    #[TestDox('is declared as a non-abstract class')]
    public function test_is_a_class(): void
    {
        $reflection = new ReflectionClass(NumberSchema::class);
        $this->assertFalse($reflection->isInterface());
        $this->assertFalse($reflection->isTrait());
        $this->assertFalse($reflection->isAbstract());
    }

    #[TestDox('extends BaseSchema')]
    public function test_extends_BaseSchema(): void
    {
        $reflection = new ReflectionClass(NumberSchema::class);
        $parent = $reflection->getParentClass();
        $this->assertNotFalse($parent);
        $this->assertSame(BaseSchema::class, $parent->getName());
    }

    #[TestDox('implements ValidationSchema via BaseSchema')]
    public function test_implements_ValidationSchema(): void
    {
        $reflection = new ReflectionClass(NumberSchema::class);
        $this->assertContains(
            ValidationSchema::class,
            $reflection->getInterfaceNames(),
        );
    }

    // ================================================================
    //
    // Shape — own public methods
    //
    // ----------------------------------------------------------------

    /**
     * @return array<string, array{0: string}>
     */
    public static function ownMethodProvider(): array
    {
        $names = [
            'gt',
            'gte',
            'lt',
            'lte',
            'positive',
            'negative',
            'nonNegative',
            'nonPositive',
            'multipleOf',
            'finite',
            'float',
            'double',
            'coerce',
        ];

        $out = [];
        foreach ($names as $name) {
            $out[$name] = [$name];
        }
        return $out;
    }

    #[TestDox('declares ->$method() as a public method')]
    #[DataProvider('ownMethodProvider')]
    public function test_own_method_is_public(string $method): void
    {
        $reflection = new ReflectionClass(NumberSchema::class);
        $this->assertTrue($reflection->hasMethod($method));
        $this->assertTrue(
            $reflection->getMethod($method)->isPublic(),
        );
    }

    // ================================================================
    //
    // Behaviour — type check (accepts int AND float)
    //
    // ----------------------------------------------------------------

    #[TestDox('->parse() accepts an int input')]
    public function test_parse_accepts_int(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // NumberSchema deliberately accepts both int and float,
        // mirroring JavaScript's number type — it's the schema
        // to reach for when you don't care which PHP numeric
        // type arrives.

        // ----------------------------------------------------------------
        // setup your test

        $schema = new NumberSchema();

        // ----------------------------------------------------------------
        // perform the change

        $actual = $schema->parse(42);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(42, $actual);
    }

    #[TestDox('->parse() accepts a float input')]
    public function test_parse_accepts_float(): void
    {
        $schema = new NumberSchema();
        $this->assertSame(3.14, $schema->parse(3.14));
    }

    #[TestDox('->parse() rejects a numeric string without ->coerce()')]
    public function test_parse_rejects_numeric_string(): void
    {
        $schema = new NumberSchema();
        $this->expectException(ValidationException::class);
        $schema->parse('42');
    }

    #[TestDox('->parse() rejects a boolean input')]
    public function test_parse_rejects_boolean(): void
    {
        $schema = new NumberSchema();
        $this->expectException(ValidationException::class);
        $schema->parse(true);
    }

    // ================================================================
    //
    // Behaviour — coercion
    //
    // ----------------------------------------------------------------

    #[TestDox('->parse() on a coercing schema accepts a numeric string that represents an int')]
    public function test_parse_coerces_int_string(): void
    {
        $schema = (new NumberSchema())->coerce();
        $this->assertSame(42, $schema->parse('42'));
    }

    #[TestDox('->parse() on a coercing schema accepts a numeric string that represents a float')]
    public function test_parse_coerces_float_string(): void
    {
        $schema = (new NumberSchema())->coerce();
        $this->assertSame(3.14, $schema->parse('3.14'));
    }

    // ================================================================
    //
    // Behaviour — numeric constraints
    //
    // ----------------------------------------------------------------

    #[TestDox('->gte() accepts a value equal to the lower bound')]
    public function test_gte_accepts_equal(): void
    {
        $schema = (new NumberSchema())->gte(value: 0);
        $this->assertSame(0, $schema->parse(0));
    }

    #[TestDox('->gte() rejects a value below the lower bound')]
    public function test_gte_rejects_below(): void
    {
        $schema = (new NumberSchema())->gte(value: 0);
        $this->expectException(ValidationException::class);
        $schema->parse(-1);
    }

    #[TestDox('->multipleOf() accepts a float multiple of the given value')]
    public function test_multipleOf_accepts_float_multiple(): void
    {
        $schema = (new NumberSchema())->multipleOf(value: 0.5);
        $this->assertSame(1.5, $schema->parse(1.5));
    }
}
