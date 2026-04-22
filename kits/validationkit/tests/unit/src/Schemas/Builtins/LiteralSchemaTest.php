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

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use StusDevKit\ValidationKit\Exceptions\ValidationException;
use StusDevKit\ValidationKit\Schemas\BaseSchema;
use StusDevKit\ValidationKit\Schemas\Builtins\LiteralSchema;

#[TestDox('LiteralSchema')]
class LiteralSchemaTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\ValidationKit\\Schemas\\Builtins namespace')]
    public function test_lives_in_expected_namespace(): void
    {
        // Validate::literal() resolves to this namespace;
        // this test pins the class down
        $reflection = new \ReflectionClass(LiteralSchema::class);
        $this->assertSame(
            'StusDevKit\\ValidationKit\\Schemas\\Builtins',
            $reflection->getNamespaceName(),
        );
    }

    #[TestDox('extends BaseSchema')]
    public function test_extends_BaseSchema(): void
    {
        // literal schemas participate in the full pipeline
        // (withCustomConstraint, withDefault, etc.) via
        // BaseSchema
        $reflection = new \ReflectionClass(LiteralSchema::class);
        $parent = $reflection->getParentClass();
        $this->assertNotFalse($parent);
        $this->assertSame(
            BaseSchema::class,
            $parent->getName(),
        );
    }

    // ================================================================
    //
    // Shape
    //
    // ----------------------------------------------------------------

    #[TestDox('::__construct() takes expectedValue and typeCheckError in that order')]
    public function test_construct_parameter_names(): void
    {
        // the expected value is positional-first; the error
        // factory is an optional second parameter. Named
        // arguments are the intended call style
        $method = new \ReflectionMethod(LiteralSchema::class, '__construct');
        $paramNames = array_map(
            fn(\ReflectionParameter $p) => $p->getName(),
            $method->getParameters(),
        );
        $this->assertSame(
            ['expectedValue', 'typeCheckError'],
            $paramNames,
        );
    }

    #[TestDox('->expectedValue() returns the literal value the schema was built with')]
    public function test_expectedValue_returns_stored_value(): void
    {
        // expectedValue() is the published introspection
        // hook — tools need it to export literals to JSON
        // Schema or to build union discriminators
        $unit = new LiteralSchema(expectedValue: 'admin');

        $actualResult = $unit->expectedValue();

        $this->assertSame('admin', $actualResult);
    }

    // ================================================================
    //
    // Behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('->parse() returns the string literal when the input matches exactly')]
    public function test_parse_accepts_matching_string(): void
    {
        // exact-match on a string literal is the canonical
        // use case (role fields, tagged unions, etc.)
        $unit = new LiteralSchema(expectedValue: 'admin');

        $actualResult = $unit->parse('admin');

        $this->assertSame('admin', $actualResult);
    }

    #[TestDox('->parse() returns the int literal when the input matches exactly')]
    public function test_parse_accepts_matching_int(): void
    {
        // literals are not restricted to strings — integers
        // must round-trip verbatim too
        $unit = new LiteralSchema(expectedValue: 42);

        $actualResult = $unit->parse(42);

        $this->assertSame(42, $actualResult);
    }

    #[TestDox('->parse() throws when the input is a different string')]
    public function test_parse_rejects_different_string(): void
    {
        // "close but wrong" must be a hard failure — the
        // whole point of a literal is that only one value
        // passes
        $unit = new LiteralSchema(expectedValue: 'admin');

        $this->expectException(ValidationException::class);

        $unit->parse('user');
    }

    #[TestDox('->parse() throws when given the string form of a numeric literal (strict comparison)')]
    public function test_parse_rejects_type_coerced_match(): void
    {
        // footgun alert: PHP's loose comparison would say
        // 42 == '42', but LiteralSchema is strict (===). A
        // string input against an int literal must fail —
        // this test locks that behaviour down
        $unit = new LiteralSchema(expectedValue: 42);

        $this->expectException(ValidationException::class);

        $unit->parse('42');
    }

    #[TestDox('->parse() throws when given a value of a different type')]
    public function test_parse_rejects_different_type(): void
    {
        // a boolean against a string literal is flatly
        // wrong; the schema must say so
        $unit = new LiteralSchema(expectedValue: 'admin');

        $this->expectException(ValidationException::class);

        $unit->parse(true);
    }
}
