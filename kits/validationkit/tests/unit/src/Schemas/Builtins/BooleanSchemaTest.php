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
use StusDevKit\ValidationKit\Schemas\Builtins\BooleanSchema;

#[TestDox(BooleanSchema::class)]
class BooleanSchemaTest extends TestCase
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
        $actual = (new ReflectionClass(BooleanSchema::class))
            ->getNamespaceName();
        $this->assertSame($expected, $actual);
    }

    #[TestDox('is declared as a non-abstract class')]
    public function test_is_a_class(): void
    {
        $reflection = new ReflectionClass(BooleanSchema::class);
        $this->assertFalse($reflection->isInterface());
        $this->assertFalse($reflection->isTrait());
        $this->assertFalse($reflection->isAbstract());
    }

    #[TestDox('extends BaseSchema')]
    public function test_extends_BaseSchema(): void
    {
        $reflection = new ReflectionClass(BooleanSchema::class);
        $parent = $reflection->getParentClass();
        $this->assertNotFalse($parent);
        $this->assertSame(BaseSchema::class, $parent->getName());
    }

    #[TestDox('implements ValidationSchema via BaseSchema')]
    public function test_implements_ValidationSchema(): void
    {
        $reflection = new ReflectionClass(BooleanSchema::class);
        $this->assertContains(
            ValidationSchema::class,
            $reflection->getInterfaceNames(),
        );
    }

    // ================================================================
    //
    // Shape — own public method
    //
    // ----------------------------------------------------------------

    #[TestDox('exposes coerce() as a public method')]
    public function test_coerce_is_public(): void
    {
        $reflection = new ReflectionClass(BooleanSchema::class);
        $this->assertTrue($reflection->hasMethod('coerce'));
        $this->assertTrue(
            $reflection->getMethod('coerce')->isPublic(),
        );
    }

    // ================================================================
    //
    // Behaviour — type check (strict)
    //
    // ----------------------------------------------------------------

    #[TestDox('->parse() accepts the boolean value true')]
    public function test_parse_accepts_true(): void
    {
        $schema = new BooleanSchema();
        $actual = $schema->parse(true);
        $this->assertTrue($actual);
    }

    #[TestDox('->parse() accepts the boolean value false')]
    public function test_parse_accepts_false(): void
    {
        $schema = new BooleanSchema();
        $actual = $schema->parse(false);
        $this->assertFalse($actual);
    }

    /**
     * @return array<string, array{0: mixed}>
     */
    public static function nonBooleanProvider(): array
    {
        return [
            'int 1' => [1],
            'int 0' => [0],
            'string "true"' => ['true'],
            'string "yes"' => ['yes'],
            'empty string' => [''],
            'float 1.0' => [1.0],
            'array' => [[]],
        ];
    }

    #[TestDox('->parse() rejects a non-boolean value $_dataName')]
    #[DataProvider('nonBooleanProvider')]
    public function test_parse_rejects_non_boolean(mixed $input): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // without coerce() BooleanSchema demands an actual PHP
        // bool — we don't want truthy/falsy coercion to sneak
        // in silently via string "yes" or int 1.

        // ----------------------------------------------------------------
        // setup your test

        $schema = new BooleanSchema();

        // ----------------------------------------------------------------
        // test the results

        $this->expectException(ValidationException::class);

        // ----------------------------------------------------------------
        // perform the change

        $schema->parse($input);
    }

    // ================================================================
    //
    // Behaviour — safeParse
    //
    // ----------------------------------------------------------------

    #[TestDox('->safeParse() returns a successful ParseResult for a boolean input')]
    public function test_safeParse_ok_on_boolean(): void
    {
        $schema = new BooleanSchema();
        $result = $schema->safeParse(true);
        $this->assertTrue($result->succeeded());
        $this->assertTrue($result->data());
    }

    #[TestDox('->safeParse() returns a failed ParseResult for a non-boolean input')]
    public function test_safeParse_fail_on_non_boolean(): void
    {
        $schema = new BooleanSchema();
        $result = $schema->safeParse('yes');
        $this->assertTrue($result->failed());
    }

    // ================================================================
    //
    // Behaviour — coercion
    //
    // ----------------------------------------------------------------

    #[TestDox('->coerce() returns a new instance (immutable)')]
    public function test_coerce_returns_new_instance(): void
    {
        $schema = new BooleanSchema();
        $clone = $schema->coerce();
        $this->assertNotSame($schema, $clone);
    }

    #[TestDox('->parse() on a coercing schema accepts the string "true"')]
    public function test_parse_coerces_string_true(): void
    {
        $schema = (new BooleanSchema())->coerce();
        $this->assertTrue($schema->parse('true'));
    }

    #[TestDox('->parse() on a coercing schema accepts the string "false"')]
    public function test_parse_coerces_string_false(): void
    {
        $schema = (new BooleanSchema())->coerce();
        $this->assertFalse($schema->parse('false'));
    }
}
