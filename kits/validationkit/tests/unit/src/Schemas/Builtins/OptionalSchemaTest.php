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
use StusDevKit\ValidationKit\Schemas\Builtins\OptionalSchema;
use StusDevKit\ValidationKit\Validate;

#[TestDox('OptionalSchema')]
class OptionalSchemaTest extends TestCase
{
    // ================================================================
    //
    // Identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\ValidationKit\\Schemas\\Builtins namespace')]
    public function test_lives_in_expected_namespace(): void
    {
        // Validate::optional() and ObjectSchema::partial()
        // both construct this class; the namespace must
        // stay put
        $reflection = new \ReflectionClass(OptionalSchema::class);
        $this->assertSame(
            'StusDevKit\\ValidationKit\\Schemas\\Builtins',
            $reflection->getNamespaceName(),
        );
    }

    #[TestDox('extends BaseSchema')]
    public function test_extends_BaseSchema(): void
    {
        // the wrapper uses the standard parse pipeline via
        // BaseSchema
        $reflection = new \ReflectionClass(OptionalSchema::class);
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
        // ObjectSchema::partial() constructs OptionalSchema
        // with a named argument, so the parameter name is
        // load-bearing — lock it down
        $method = new \ReflectionMethod(OptionalSchema::class, '__construct');
        $paramNames = array_map(
            fn(\ReflectionParameter $p) => $p->getName(),
            $method->getParameters(),
        );
        $this->assertSame(['innerSchema'], $paramNames);
    }

    #[TestDox('->unwrap() returns the wrapped inner schema')]
    public function test_unwrap_returns_inner_schema(): void
    {
        // ObjectSchema::required() depends on unwrap() to
        // peel the optional wrapper back off — it must
        // return exactly the schema that went in
        $inner = Validate::string();
        $unit = new OptionalSchema(innerSchema: $inner);

        $actualResult = $unit->unwrap();

        $this->assertSame($inner, $actualResult);
    }

    #[TestDox('->unwrap() returns a ValidationSchema')]
    public function test_unwrap_return_type(): void
    {
        // the published return type is ValidationSchema so
        // the generic contract survives reflection
        $method = new \ReflectionMethod(OptionalSchema::class, 'unwrap');
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

    #[TestDox('->parse() returns null when given null (null short-circuits the inner schema)')]
    public function test_parse_accepts_null(): void
    {
        // null is the standalone signal for "absent" — the
        // inner schema must never see it
        $unit = new OptionalSchema(innerSchema: Validate::string());

        $actualResult = $unit->parse(null);

        $this->assertNull($actualResult);
    }

    #[TestDox('->parse() returns null when the ObjectSchema field is missing (missing key short-circuits)')]
    public function test_parse_accepts_missing_object_field(): void
    {
        // the real reason OptionalSchema exists: fields
        // marked optional on an ObjectSchema may be absent
        // from the input object entirely. ObjectSchema
        // normalises that absence to null before calling
        // parseWithContext(), and OptionalSchema accepts it
        $schema = Validate::object(shape: [
            'nickname' => Validate::optional(Validate::string()),
        ]);

        $input = new \stdClass();
        // deliberately no 'nickname' property — the
        // optional field is entirely missing

        $actualResult = $schema->parse($input);

        $this->assertInstanceOf(\stdClass::class, $actualResult);
        $this->assertObjectHasProperty('nickname', $actualResult);
        $this->assertNull($actualResult->nickname);
    }

    #[TestDox('->parse() delegates to the inner schema when the value is present')]
    public function test_parse_delegates_to_inner_schema(): void
    {
        // when a value is supplied, the inner schema still
        // gets the final say on whether it is acceptable
        $unit = new OptionalSchema(innerSchema: Validate::string());

        $actualResult = $unit->parse('hello');

        $this->assertSame('hello', $actualResult);
    }

    #[TestDox('->parse() throws when the inner schema rejects a present value')]
    public function test_parse_rejects_when_inner_fails(): void
    {
        // "optional" waives absence, not type correctness;
        // a wrong-type value must still fail the inner
        // schema's type check
        $unit = new OptionalSchema(innerSchema: Validate::string());

        $this->expectException(ValidationException::class);

        $unit->parse(42);
    }
}
