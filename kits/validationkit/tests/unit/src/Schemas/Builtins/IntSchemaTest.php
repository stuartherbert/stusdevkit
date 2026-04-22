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
use StusDevKit\ValidationKit\Schemas\Builtins\IntSchema;

#[TestDox(IntSchema::class)]
class IntSchemaTest extends TestCase
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
        $actual = (new ReflectionClass(IntSchema::class))
            ->getNamespaceName();
        $this->assertSame($expected, $actual);
    }

    #[TestDox('is declared as a non-abstract class')]
    public function test_is_a_class(): void
    {
        $reflection = new ReflectionClass(IntSchema::class);
        $this->assertFalse($reflection->isInterface());
        $this->assertFalse($reflection->isTrait());
        $this->assertFalse($reflection->isAbstract());
    }

    #[TestDox('extends BaseSchema')]
    public function test_extends_BaseSchema(): void
    {
        $reflection = new ReflectionClass(IntSchema::class);
        $parent = $reflection->getParentClass();
        $this->assertNotFalse($parent);
        $this->assertSame(BaseSchema::class, $parent->getName());
    }

    #[TestDox('implements ValidationSchema via BaseSchema')]
    public function test_implements_ValidationSchema(): void
    {
        $reflection = new ReflectionClass(IntSchema::class);
        $this->assertContains(
            ValidationSchema::class,
            $reflection->getInterfaceNames(),
        );
    }

    // ================================================================
    //
    // Shape — own public methods (beyond the BaseSchema contract)
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
            'int32',
            'int64',
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
        $reflection = new ReflectionClass(IntSchema::class);
        $this->assertTrue($reflection->hasMethod($method));
        $this->assertTrue(
            $reflection->getMethod($method)->isPublic(),
        );
    }

    // ================================================================
    //
    // Behaviour — type check (strict)
    //
    // ----------------------------------------------------------------

    #[TestDox('->parse() accepts an int input')]
    public function test_parse_accepts_int(): void
    {
        $schema = new IntSchema();
        $this->assertSame(42, $schema->parse(42));
    }

    #[TestDox('->parse() rejects a numeric string without ->coerce()')]
    public function test_parse_rejects_numeric_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // strict mode refuses "42" — coerce() is the opt-in for
        // accepting payloads from HTTP/JSON where ints may be
        // quoted.

        // ----------------------------------------------------------------
        // setup your test

        $schema = new IntSchema();

        // ----------------------------------------------------------------
        // test the results

        $this->expectException(ValidationException::class);

        // ----------------------------------------------------------------
        // perform the change

        $schema->parse('42');
    }

    #[TestDox('->parse() rejects a float input')]
    public function test_parse_rejects_float(): void
    {
        $schema = new IntSchema();
        $this->expectException(ValidationException::class);
        $schema->parse(3.14);
    }

    // ================================================================
    //
    // Behaviour — coercion
    //
    // ----------------------------------------------------------------

    #[TestDox('->parse() on a coercing schema accepts a numeric string')]
    public function test_parse_coerces_numeric_string(): void
    {
        $schema = (new IntSchema())->coerce();
        $this->assertSame(42, $schema->parse('42'));
    }

    // ================================================================
    //
    // Behaviour — numeric constraints
    //
    // ----------------------------------------------------------------

    #[TestDox('->gte() accepts a value equal to the lower bound')]
    public function test_gte_accepts_equal(): void
    {
        $schema = (new IntSchema())->gte(value: 0);
        $this->assertSame(0, $schema->parse(0));
    }

    #[TestDox('->gte() rejects a value below the lower bound')]
    public function test_gte_rejects_below(): void
    {
        $schema = (new IntSchema())->gte(value: 0);
        $this->expectException(ValidationException::class);
        $schema->parse(-1);
    }

    #[TestDox('->lte() accepts a value equal to the upper bound')]
    public function test_lte_accepts_equal(): void
    {
        $schema = (new IntSchema())->lte(value: 100);
        $this->assertSame(100, $schema->parse(100));
    }

    #[TestDox('->lte() rejects a value above the upper bound')]
    public function test_lte_rejects_above(): void
    {
        $schema = (new IntSchema())->lte(value: 100);
        $this->expectException(ValidationException::class);
        $schema->parse(101);
    }

    #[TestDox('->positive() accepts a value greater than zero')]
    public function test_positive_accepts_above_zero(): void
    {
        $schema = (new IntSchema())->positive();
        $this->assertSame(1, $schema->parse(1));
    }

    #[TestDox('->positive() rejects zero')]
    public function test_positive_rejects_zero(): void
    {
        $schema = (new IntSchema())->positive();
        $this->expectException(ValidationException::class);
        $schema->parse(0);
    }

    #[TestDox('->multipleOf() accepts a multiple of the given value')]
    public function test_multipleOf_accepts_multiple(): void
    {
        $schema = (new IntSchema())->multipleOf(value: 5);
        $this->assertSame(25, $schema->parse(25));
    }

    #[TestDox('->multipleOf() rejects a non-multiple')]
    public function test_multipleOf_rejects_non_multiple(): void
    {
        $schema = (new IntSchema())->multipleOf(value: 5);
        $this->expectException(ValidationException::class);
        $schema->parse(26);
    }
}
