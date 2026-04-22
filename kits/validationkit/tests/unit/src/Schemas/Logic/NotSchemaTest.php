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

namespace StusDevKit\ValidationKit\Tests\Unit\Schemas\Logic;

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use StusDevKit\ValidationKit\Contracts\ValidationSchema;
use StusDevKit\ValidationKit\Exceptions\ValidationException;
use StusDevKit\ValidationKit\Schemas\BaseSchema;
use StusDevKit\ValidationKit\Schemas\Logic\NotSchema;
use StusDevKit\ValidationKit\Validate;

#[TestDox('NotSchema')]
class NotSchemaTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\ValidationKit\\Schemas\\Logic namespace')]
    public function test_lives_in_expected_namespace(): void
    {
        $reflection = new \ReflectionClass(NotSchema::class);
        $this->assertSame(
            'StusDevKit\\ValidationKit\\Schemas\\Logic',
            $reflection->getNamespaceName(),
        );
    }

    #[TestDox('extends BaseSchema')]
    public function test_extends_BaseSchema(): void
    {
        $reflection = new \ReflectionClass(NotSchema::class);
        $parent = $reflection->getParentClass();
        $this->assertNotFalse($parent);
        $this->assertSame(BaseSchema::class, $parent->getName());
    }

    #[TestDox('is not final (allows extension)')]
    public function test_is_not_final(): void
    {
        $reflection = new \ReflectionClass(NotSchema::class);
        $this->assertFalse($reflection->isFinal());
    }

    // ================================================================
    //
    // Shape
    //
    // ----------------------------------------------------------------

    #[TestDox('::__construct() parameter names in order')]
    public function test_construct_parameter_names(): void
    {
        $method = new \ReflectionMethod(NotSchema::class, '__construct');
        $paramNames = array_map(
            fn(\ReflectionParameter $p) => $p->getName(),
            $method->getParameters(),
        );
        $this->assertSame(['schema', 'typeCheckError'], $paramNames);
    }

    #[TestDox('::__construct() declares $schema as a ValidationSchema')]
    public function test_construct_schema_type(): void
    {
        $method = new \ReflectionMethod(NotSchema::class, '__construct');
        $param = $method->getParameters()[0];

        $type = $param->getType();
        $this->assertInstanceOf(\ReflectionNamedType::class, $type);
        $this->assertSame(ValidationSchema::class, $type->getName());
    }

    #[TestDox('->innerSchema() returns the negated schema')]
    public function test_inner_schema_accessor(): void
    {
        $inner = Validate::string();
        $unit = new NotSchema(schema: $inner);

        $this->assertSame($inner, $unit->innerSchema());
    }

    // ================================================================
    //
    // Behaviour
    //
    // ----------------------------------------------------------------

    /**
     * JSON Schema "not" flips the inner schema's verdict:
     * when the inner schema says NO, "not" says YES and
     * passes the data through unchanged.
     */
    #[TestDox('->parse() accepts input that the inner schema rejects')]
    public function test_parse_accepts_when_inner_rejects(): void
    {
        // Validate::string() rejects an int, so NotSchema
        // must accept the int.
        $unit = new NotSchema(schema: Validate::string());

        $result = $unit->parse(42);

        $this->assertSame(42, $result);
    }

    /**
     * Symmetric check: a boolean is also not a string, so
     * "not string" must accept it.
     */
    #[TestDox('->parse() accepts non-matching input of a different type')]
    public function test_parse_accepts_boolean_against_not_string(): void
    {
        $unit = new NotSchema(schema: Validate::string());

        $result = $unit->parse(true);

        $this->assertSame(true, $result);
    }

    /**
     * JSON Schema "not" must REJECT when the inner schema
     * accepts — that is the whole point of negation.
     */
    #[TestDox('->parse() rejects input that the inner schema accepts')]
    public function test_parse_rejects_when_inner_accepts(): void
    {
        // Validate::string() accepts a string, so NotSchema
        // must reject the string.
        $unit = new NotSchema(schema: Validate::string());

        $this->expectException(ValidationException::class);
        $unit->parse('hello');
    }

    /**
     * Double-negation sanity check: "not int" accepts a
     * string because an int schema rejects a string.
     */
    #[TestDox('->parse() with not-int accepts a string')]
    public function test_parse_not_int_accepts_string(): void
    {
        $unit = new NotSchema(schema: Validate::int());

        $result = $unit->parse('hello');

        $this->assertSame('hello', $result);
    }

    /**
     * Same shape as above but the inner schema is the
     * matching one — "not int" must reject an int.
     */
    #[TestDox('->parse() with not-int rejects an int')]
    public function test_parse_not_int_rejects_int(): void
    {
        $unit = new NotSchema(schema: Validate::int());

        $this->expectException(ValidationException::class);
        $unit->parse(42);
    }
}
