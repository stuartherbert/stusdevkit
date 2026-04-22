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
use StusDevKit\ValidationKit\Schemas\Builtins\StringSchema;

#[TestDox(StringSchema::class)]
class StringSchemaTest extends TestCase
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
        $actual = (new ReflectionClass(StringSchema::class))
            ->getNamespaceName();
        $this->assertSame($expected, $actual);
    }

    #[TestDox('is declared as a non-abstract class')]
    public function test_is_a_class(): void
    {
        $reflection = new ReflectionClass(StringSchema::class);
        $this->assertFalse($reflection->isInterface());
        $this->assertFalse($reflection->isTrait());
        $this->assertFalse($reflection->isAbstract());
    }

    #[TestDox('extends BaseSchema')]
    public function test_extends_BaseSchema(): void
    {
        $reflection = new ReflectionClass(StringSchema::class);
        $parent = $reflection->getParentClass();
        $this->assertNotFalse($parent);
        $this->assertSame(BaseSchema::class, $parent->getName());
    }

    #[TestDox('implements ValidationSchema via BaseSchema')]
    public function test_implements_ValidationSchema(): void
    {
        $reflection = new ReflectionClass(StringSchema::class);
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
            'min',
            'max',
            'length',
            'regex',
            'email',
            'url',
            'uuid',
            'ipv4',
            'ipv6',
            'includes',
            'startsWith',
            'endsWith',
            'date',
            'time',
            'duration',
            'dateTime',
            'hostname',
            'uriReference',
            'idnEmail',
            'idnHostname',
            'iri',
            'iriReference',
            'uriTemplate',
            'jsonPointer',
            'relativeJsonPointer',
            'isRegex',
            'password',
            'applyTrim',
            'applyToLowerCase',
            'applyToUpperCase',
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
        $reflection = new ReflectionClass(StringSchema::class);
        $this->assertTrue($reflection->hasMethod($method));
        $this->assertTrue(
            $reflection->getMethod($method)->isPublic(),
        );
    }

    // ================================================================
    //
    // Behaviour — type check
    //
    // ----------------------------------------------------------------

    #[TestDox('->parse() accepts a string input')]
    public function test_parse_accepts_string(): void
    {
        $schema = new StringSchema();
        $this->assertSame('hello', $schema->parse('hello'));
    }

    #[TestDox('->parse() rejects an int input without ->coerce()')]
    public function test_parse_rejects_int(): void
    {
        $schema = new StringSchema();
        $this->expectException(ValidationException::class);
        $schema->parse(42);
    }

    #[TestDox('->parse() rejects an array input')]
    public function test_parse_rejects_array(): void
    {
        $schema = new StringSchema();
        $this->expectException(ValidationException::class);
        $schema->parse([]);
    }

    // ================================================================
    //
    // Behaviour — coercion
    //
    // ----------------------------------------------------------------

    #[TestDox('->parse() on a coercing schema accepts an int input')]
    public function test_parse_coerces_int(): void
    {
        $schema = (new StringSchema())->coerce();
        $this->assertSame('42', $schema->parse(42));
    }

    // ================================================================
    //
    // Behaviour — length constraints
    //
    // ----------------------------------------------------------------

    #[TestDox('->min() accepts a string at the minimum length')]
    public function test_min_accepts_at_minimum(): void
    {
        $schema = (new StringSchema())->min(length: 3);
        $this->assertSame('abc', $schema->parse('abc'));
    }

    #[TestDox('->min() rejects a string shorter than the minimum length')]
    public function test_min_rejects_below(): void
    {
        $schema = (new StringSchema())->min(length: 3);
        $this->expectException(ValidationException::class);
        $schema->parse('ab');
    }

    #[TestDox('->max() accepts a string at the maximum length')]
    public function test_max_accepts_at_maximum(): void
    {
        $schema = (new StringSchema())->max(length: 3);
        $this->assertSame('abc', $schema->parse('abc'));
    }

    #[TestDox('->max() rejects a string longer than the maximum length')]
    public function test_max_rejects_above(): void
    {
        $schema = (new StringSchema())->max(length: 3);
        $this->expectException(ValidationException::class);
        $schema->parse('abcd');
    }

    #[TestDox('->length() accepts a string of the exact length')]
    public function test_length_accepts_exact(): void
    {
        $schema = (new StringSchema())->length(length: 3);
        $this->assertSame('abc', $schema->parse('abc'));
    }

    #[TestDox('->length() rejects a string of a different length')]
    public function test_length_rejects_different(): void
    {
        $schema = (new StringSchema())->length(length: 3);
        $this->expectException(ValidationException::class);
        $schema->parse('ab');
    }

    // ================================================================
    //
    // Behaviour — format constraints
    //
    // ----------------------------------------------------------------

    #[TestDox('->email() accepts a valid email address')]
    public function test_email_accepts_valid(): void
    {
        $schema = (new StringSchema())->email();
        $this->assertSame(
            'user@example.com',
            $schema->parse('user@example.com'),
        );
    }

    #[TestDox('->email() rejects an invalid email address')]
    public function test_email_rejects_invalid(): void
    {
        $schema = (new StringSchema())->email();
        $this->expectException(ValidationException::class);
        $schema->parse('not-an-email');
    }

    #[TestDox('->regex() accepts a string matching the pattern')]
    public function test_regex_accepts_match(): void
    {
        $schema = (new StringSchema())->regex(pattern: '/^[a-z]+$/');
        $this->assertSame('abc', $schema->parse('abc'));
    }

    #[TestDox('->regex() rejects a string not matching the pattern')]
    public function test_regex_rejects_mismatch(): void
    {
        $schema = (new StringSchema())->regex(pattern: '/^[a-z]+$/');
        $this->expectException(ValidationException::class);
        $schema->parse('ABC');
    }

    #[TestDox('->startsWith() accepts a string with the given prefix')]
    public function test_startsWith_accepts_prefix(): void
    {
        $schema = (new StringSchema())->startsWith(prefix: 'foo');
        $this->assertSame('foobar', $schema->parse('foobar'));
    }

    #[TestDox('->startsWith() rejects a string without the given prefix')]
    public function test_startsWith_rejects_missing_prefix(): void
    {
        $schema = (new StringSchema())->startsWith(prefix: 'foo');
        $this->expectException(ValidationException::class);
        $schema->parse('barbaz');
    }

    // ================================================================
    //
    // Behaviour — transforms
    //
    // ----------------------------------------------------------------

    #[TestDox('->applyTrim() strips surrounding whitespace before validation')]
    public function test_applyTrim_strips_whitespace(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // applyTrim() is a normaliser — it runs before constraint
        // checks, so the min() length applies to the trimmed
        // string, not the raw input.

        // ----------------------------------------------------------------
        // setup your test

        $schema = (new StringSchema())
            ->applyTrim()
            ->min(length: 1);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $schema->parse('  hello  ');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('hello', $actual);
    }

    #[TestDox('->applyToLowerCase() lowercases the string before validation')]
    public function test_applyToLowerCase_lowercases(): void
    {
        $schema = (new StringSchema())->applyToLowerCase();
        $this->assertSame('hello', $schema->parse('HELLO'));
    }

    #[TestDox('->applyToUpperCase() uppercases the string before validation')]
    public function test_applyToUpperCase_uppercases(): void
    {
        $schema = (new StringSchema())->applyToUpperCase();
        $this->assertSame('HELLO', $schema->parse('hello'));
    }
}
