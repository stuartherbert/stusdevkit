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
use StusDevKit\ValidationKit\Contracts\ValidationSchema;
use StusDevKit\ValidationKit\Exceptions\ValidationException;
use StusDevKit\ValidationKit\Schemas\BaseSchema;
use StusDevKit\ValidationKit\Schemas\Builtins\NullishSchema;
use StusDevKit\ValidationKit\Validate;

#[TestDox('NullishSchema')]
class NullishSchemaTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\ValidationKit\\Schemas\\Builtins namespace')]
    public function test_lives_in_expected_namespace(): void
    {
        // locks the namespace down — callers hit
        // NullishSchema via Validate::nullish() and expect
        // this path
        $reflection = new \ReflectionClass(NullishSchema::class);
        $this->assertSame(
            'StusDevKit\\ValidationKit\\Schemas\\Builtins',
            $reflection->getNamespaceName(),
        );
    }

    #[TestDox('extends BaseSchema')]
    public function test_extends_BaseSchema(): void
    {
        // the wrapper participates in the standard pipeline
        // via BaseSchema
        $reflection = new \ReflectionClass(NullishSchema::class);
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

    #[TestDox('::__construct() takes an innerSchema parameter')]
    public function test_construct_parameter_names(): void
    {
        // the wrapped schema must stay named innerSchema so
        // callers can use named arguments
        $method = new \ReflectionMethod(NullishSchema::class, '__construct');
        $paramNames = array_map(
            fn(\ReflectionParameter $p) => $p->getName(),
            $method->getParameters(),
        );
        $this->assertSame(['innerSchema'], $paramNames);
    }

    #[TestDox('->unwrap() returns the wrapped inner schema')]
    public function test_unwrap_returns_inner_schema(): void
    {
        // unwrap() exposes the original schema so tools
        // (JSON Schema export, ObjectSchema::required()
        // equivalents) can peel the wrapper back off
        $inner = Validate::string();
        $unit = new NullishSchema(innerSchema: $inner);

        $actualResult = $unit->unwrap();

        $this->assertSame($inner, $actualResult);
    }

    #[TestDox('->unwrap() returns a ValidationSchema')]
    public function test_unwrap_return_type(): void
    {
        // the return type is ValidationSchema so the
        // generic contract survives reflection
        $method = new \ReflectionMethod(NullishSchema::class, 'unwrap');
        $type = $method->getReturnType();
        $this->assertInstanceOf(\ReflectionNamedType::class, $type);
        $this->assertSame(
            ValidationSchema::class,
            $type->getName(),
        );
    }

    // ================================================================
    //
    // Behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('->parse() returns null when given null')]
    public function test_parse_accepts_null(): void
    {
        // null is the first half of "nullish" — accepted
        // without delegating to the inner schema
        $unit = new NullishSchema(innerSchema: Validate::string());

        $actualResult = $unit->parse(null);

        $this->assertNull($actualResult);
    }

    #[TestDox('->parse() returns null when the ObjectSchema field is missing (missing-key short-circuit)')]
    public function test_parse_accepts_missing_object_field(): void
    {
        // the second half of "nullish" — a missing field on
        // an object also resolves to null. ObjectSchema
        // turns absent keys into null before calling the
        // nullish wrapper, which then returns null without
        // tripping the inner string schema
        $schema = Validate::object(shape: [
            'nickname' => Validate::nullish(Validate::string()),
        ]);

        $input = new \stdClass();
        // note: no 'nickname' property set at all

        $actualResult = $schema->parse($input);

        $this->assertInstanceOf(\stdClass::class, $actualResult);
        $this->assertObjectHasProperty('nickname', $actualResult);
        $this->assertNull($actualResult->nickname);
    }

    #[TestDox('->parse() delegates to the inner schema when the value is a non-null string')]
    public function test_parse_delegates_to_inner_schema(): void
    {
        // non-null, non-missing values pass through the
        // inner schema verbatim — the wrapper never
        // short-circuits them
        $unit = new NullishSchema(innerSchema: Validate::string());

        $actualResult = $unit->parse('hello');

        $this->assertSame('hello', $actualResult);
    }

    #[TestDox('->parse() delegates an empty string to the inner schema (empty string is NOT a free pass)')]
    public function test_parse_delegates_empty_string_to_inner(): void
    {
        // footgun alert: although JavaScript's `nullish`
        // semantics sometimes get conflated with falsy
        // strings, ValidationKit only short-circuits on
        // null — an empty string must still travel through
        // the inner schema, which is why this string() call
        // accepts it
        $unit = new NullishSchema(innerSchema: Validate::string());

        $actualResult = $unit->parse('');

        $this->assertSame('', $actualResult);
    }

    #[TestDox('->parse() throws when the inner schema rejects a non-null value')]
    public function test_parse_rejects_when_inner_fails(): void
    {
        // the wrapper only waives null (and missing keys);
        // everything else is still the inner schema's call,
        // and its rejection must surface as a thrown
        // ValidationException
        $unit = new NullishSchema(innerSchema: Validate::string());

        $this->expectException(ValidationException::class);

        $unit->parse(42);
    }
}
