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

namespace StusDevKit\ValidationKit\Tests\Unit\JsonSchema;

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use stdClass;
use StusDevKit\ValidationKit\Exceptions\InvalidJsonSchemaException;
use StusDevKit\ValidationKit\JsonSchema\JsonSchema;
use StusDevKit\ValidationKit\JsonSchema\JsonSchemaDraft202012Importer;
use StusDevKit\ValidationKit\JsonSchema\JsonSchemaRegistry;
use StusDevKit\ValidationKit\Schemas\Builtins\ArraySchema;
use StusDevKit\ValidationKit\Schemas\Builtins\BooleanSchema;
use StusDevKit\ValidationKit\Schemas\Builtins\IntSchema;
use StusDevKit\ValidationKit\Schemas\Builtins\LiteralSchema;
use StusDevKit\ValidationKit\Schemas\Builtins\MixedSchema;
use StusDevKit\ValidationKit\Schemas\Builtins\NullableSchema;
use StusDevKit\ValidationKit\Schemas\Builtins\NullSchema;
use StusDevKit\ValidationKit\Schemas\Builtins\NumberSchema;
use StusDevKit\ValidationKit\Schemas\Builtins\ObjectSchema;
use StusDevKit\ValidationKit\Schemas\Builtins\StringSchema;
use StusDevKit\ValidationKit\Schemas\Logic\AllOfSchema;
use StusDevKit\ValidationKit\Schemas\Logic\AnyOfSchema;
use StusDevKit\ValidationKit\Schemas\Logic\EnumSchema;
use StusDevKit\ValidationKit\Schemas\Logic\OneOfSchema;

#[TestDox(JsonSchemaDraft202012Importer::class)]
class JsonSchemaDraft202012ImporterTest extends TestCase
{
    // ================================================================
    //
    // Class identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\ValidationKit\\JsonSchema namespace')]
    public function test_lives_in_the_expected_namespace(): void
    {
        // the published namespace is part of the contract - callers
        // type-hint against the FQN, so moving it is a breaking
        // change that must go through a major version bump.

        $expected = 'StusDevKit\\ValidationKit\\JsonSchema';

        $actual = (new ReflectionClass(
            JsonSchemaDraft202012Importer::class,
        ))->getNamespaceName();

        $this->assertSame($expected, $actual);
    }

    #[TestDox('is a concrete class')]
    public function test_is_a_concrete_class(): void
    {
        // callers `new JsonSchemaDraft202012Importer()` to convert
        // a JSON Schema document. Making the class abstract, an
        // interface, or a trait would break every call site.

        $reflection = new ReflectionClass(
            JsonSchemaDraft202012Importer::class,
        );

        $this->assertFalse($reflection->isAbstract());
        $this->assertFalse($reflection->isInterface());
        $this->assertFalse($reflection->isTrait());
    }

    // ================================================================
    //
    // Published method set
    //
    // ----------------------------------------------------------------

    #[TestDox('exposes only import() as a public method')]
    public function test_exposes_only_import_as_public_method(): void
    {
        // the class has a single job - parse a JSON Schema document
        // into a ValidationKit schema. Pin the public surface by
        // enumeration so a silently added helper shows up here
        // rather than creeping into the API.

        $expected = ['import'];
        $reflection = new ReflectionClass(
            JsonSchemaDraft202012Importer::class,
        );

        $actual = array_map(
            static fn ($method) => $method->getName(),
            $reflection->getMethods(ReflectionMethod::IS_PUBLIC),
        );
        sort($actual);

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // Method shape
    //
    // ----------------------------------------------------------------

    #[TestDox('->import() declares $jsonSchema, $registry and $loader as parameters in that order')]
    public function test_import_declares_expected_parameters(): void
    {
        // callers use named arguments for multi-parameter calls,
        // so pin names and order. Registry and loader are optional -
        // reordering or renaming them silently breaks every call
        // site that uses named args.

        $expected = ['jsonSchema', 'registry', 'loader'];
        $method = (new ReflectionClass(
            JsonSchemaDraft202012Importer::class,
        ))->getMethod('import');

        $actual = array_map(
            static fn ($parameter) => $parameter->getName(),
            $method->getParameters(),
        );

        $this->assertSame($expected, $actual);
    }

    #[TestDox('->import() marks $registry as optional')]
    public function test_import_registry_is_optional(): void
    {
        // most callers do not carry a registry of their own - the
        // importer creates one internally when none is supplied.
        // If $registry becomes required, every simple call site
        // breaks - catch that here.

        $method = (new ReflectionClass(
            JsonSchemaDraft202012Importer::class,
        ))->getMethod('import');
        $parameters = $method->getParameters();

        $this->assertTrue($parameters[1]->isOptional());
    }

    #[TestDox('->import() marks $loader as optional')]
    public function test_import_loader_is_optional(): void
    {
        // external $ref resolution is opt-in - callers that do not
        // use it must not have to supply a loader. Pin the optional
        // status so the friendly default does not regress.

        $method = (new ReflectionClass(
            JsonSchemaDraft202012Importer::class,
        ))->getMethod('import');
        $parameters = $method->getParameters();

        $this->assertTrue($parameters[2]->isOptional());
    }

    // ================================================================
    //
    // Primitive types
    //
    // ----------------------------------------------------------------

    /**
     * the type: string keyword is the minimal happy path for the
     * dispatcher; it must land on StringSchema without needing any
     * other keyword.
     */
    #[TestDox('->import() maps type: string to a StringSchema')]
    public function test_imports_string_type(): void
    {
        $unit = new JsonSchemaDraft202012Importer();

        $result = $unit->import(
            jsonSchema: $this->jsonToSchema('{"type":"string"}'),
        );

        $this->assertInstanceOf(StringSchema::class, $result);
    }

    #[TestDox('->import() maps type: integer to an IntSchema')]
    public function test_imports_integer_type(): void
    {
        $unit = new JsonSchemaDraft202012Importer();

        $result = $unit->import(
            jsonSchema: $this->jsonToSchema('{"type":"integer"}'),
        );

        $this->assertInstanceOf(IntSchema::class, $result);
    }

    #[TestDox('->import() maps type: number to a NumberSchema')]
    public function test_imports_number_type(): void
    {
        $unit = new JsonSchemaDraft202012Importer();

        $result = $unit->import(
            jsonSchema: $this->jsonToSchema('{"type":"number"}'),
        );

        $this->assertInstanceOf(NumberSchema::class, $result);
    }

    #[TestDox('->import() maps type: boolean to a BooleanSchema')]
    public function test_imports_boolean_type(): void
    {
        $unit = new JsonSchemaDraft202012Importer();

        $result = $unit->import(
            jsonSchema: $this->jsonToSchema('{"type":"boolean"}'),
        );

        $this->assertInstanceOf(BooleanSchema::class, $result);
    }

    #[TestDox('->import() maps type: null to a NullSchema')]
    public function test_imports_null_type(): void
    {
        $unit = new JsonSchemaDraft202012Importer();

        $result = $unit->import(
            jsonSchema: $this->jsonToSchema('{"type":"null"}'),
        );

        $this->assertInstanceOf(NullSchema::class, $result);
    }

    /**
     * an empty schema is JSON Schema's "true schema" - accepts
     * anything. It must land on MixedSchema, which is the
     * ValidationKit equivalent of "permit everything".
     */
    #[TestDox('->import() maps an empty schema body to a MixedSchema')]
    public function test_imports_empty_schema_as_mixed(): void
    {
        $unit = new JsonSchemaDraft202012Importer();

        $result = $unit->import(
            jsonSchema: $this->jsonToSchema('{}'),
        );

        $this->assertInstanceOf(MixedSchema::class, $result);
    }

    // ================================================================
    //
    // String constraints
    //
    // ----------------------------------------------------------------

    /**
     * minLength / maxLength are the standard JSON Schema names and
     * must round-trip to the StringSchema min/max(length:) builder
     * methods. Pin the acceptance behaviour end-to-end so a
     * regression in either the reader or the builder surfaces here.
     */
    #[TestDox('->import() applies minLength and maxLength as string length constraints')]
    public function test_imports_string_length_constraints(): void
    {
        $unit = new JsonSchemaDraft202012Importer();

        $result = $unit->import(
            jsonSchema: $this->jsonToSchema(
                '{"type":"string","minLength":2,"maxLength":4}',
            ),
        );

        $this->assertSame('abc', $result->parse('abc'));
        $this->expectException(\Throwable::class);
        $result->parse('a');
    }

    /**
     * JSON Schema patterns are ECMA 262 regex without delimiters.
     * ValidationKit's StringSchema::regex() expects PCRE with
     * delimiters, so the importer must add them. Drive this through
     * parse() to avoid peeking at private state.
     */
    #[TestDox('->import() applies pattern keyword as a regex constraint')]
    public function test_imports_string_pattern(): void
    {
        $unit = new JsonSchemaDraft202012Importer();

        $result = $unit->import(
            jsonSchema: $this->jsonToSchema(
                '{"type":"string","pattern":"^[a-z]+$"}',
            ),
        );

        $this->assertSame('abc', $result->parse('abc'));
        $this->expectException(\Throwable::class);
        $result->parse('ABC');
    }

    /**
     * format: email is one of the most common format hints - pin
     * that the format dispatch table still wires it to the email()
     * builder method so the parse path rejects non-emails.
     */
    #[TestDox('->import() applies format: email as an email constraint')]
    public function test_imports_string_email_format(): void
    {
        $unit = new JsonSchemaDraft202012Importer();

        $result = $unit->import(
            jsonSchema: $this->jsonToSchema(
                '{"type":"string","format":"email"}',
            ),
        );

        $this->assertSame(
            'user@example.com',
            $result->parse('user@example.com'),
        );
        $this->expectException(\Throwable::class);
        $result->parse('not-an-email');
    }

    // ================================================================
    //
    // Numeric constraints
    //
    // ----------------------------------------------------------------

    /**
     * minimum / maximum on an integer schema must translate to
     * inclusive range checks at parse time. Pin the boundary
     * behaviour so an off-by-one between inclusive and exclusive
     * is caught.
     */
    #[TestDox('->import() applies minimum and maximum as inclusive integer bounds')]
    public function test_imports_integer_min_max(): void
    {
        $unit = new JsonSchemaDraft202012Importer();

        $result = $unit->import(
            jsonSchema: $this->jsonToSchema(
                '{"type":"integer","minimum":0,"maximum":10}',
            ),
        );

        $this->assertSame(0, $result->parse(0));
        $this->assertSame(10, $result->parse(10));
        $this->expectException(\Throwable::class);
        $result->parse(11);
    }

    /**
     * exclusiveMinimum must reject the boundary value itself.
     * Pin the distinction from inclusive minimum.
     */
    #[TestDox('->import() applies exclusiveMinimum as a strict lower bound')]
    public function test_imports_integer_exclusive_minimum(): void
    {
        $unit = new JsonSchemaDraft202012Importer();

        $result = $unit->import(
            jsonSchema: $this->jsonToSchema(
                '{"type":"integer","exclusiveMinimum":0}',
            ),
        );

        $this->assertSame(1, $result->parse(1));
        $this->expectException(\Throwable::class);
        $result->parse(0);
    }

    // ================================================================
    //
    // Array schemas
    //
    // ----------------------------------------------------------------

    /**
     * type: array with items lands on ArraySchema and applies the
     * element schema to every member. Pin both the class and the
     * element-level validation so a future refactor that forgets
     * either is caught.
     */
    #[TestDox('->import() maps type: array with items to an ArraySchema')]
    public function test_imports_array_schema(): void
    {
        $unit = new JsonSchemaDraft202012Importer();

        $result = $unit->import(
            jsonSchema: $this->jsonToSchema(
                '{"type":"array","items":{"type":"string"}}',
            ),
        );

        $this->assertInstanceOf(ArraySchema::class, $result);
        $this->assertSame(['a', 'b'], $result->parse(['a', 'b']));
    }

    // ================================================================
    //
    // Object schemas
    //
    // ----------------------------------------------------------------

    /**
     * type: object with properties lands on ObjectSchema. Pin the
     * class and the round-trip on a valid payload so the three
     * moving parts (type dispatch, property import, required list)
     * are all exercised.
     */
    #[TestDox('->import() maps type: object with properties to an ObjectSchema')]
    public function test_imports_object_schema(): void
    {
        $unit = new JsonSchemaDraft202012Importer();
        $json = <<<'JSON'
            {
                "type": "object",
                "properties": {
                    "name": {"type": "string"}
                },
                "required": ["name"]
            }
            JSON;

        $result = $unit->import(
            jsonSchema: $this->jsonToSchema($json),
        );

        $this->assertInstanceOf(ObjectSchema::class, $result);
        $input = new \stdClass();
        $input->name = 'Alice';
        $parsed = $result->parse($input);
        $this->assertSame('Alice', $parsed->name);
    }

    // ================================================================
    //
    // Composition keywords
    //
    // ----------------------------------------------------------------

    /**
     * anyOf lands on AnyOfSchema and accepts any branch. Pin the
     * class so a future refactor that routes through allOf or a
     * flattened type array is caught by name.
     */
    #[TestDox('->import() maps anyOf to an AnyOfSchema')]
    public function test_imports_any_of(): void
    {
        $unit = new JsonSchemaDraft202012Importer();
        $json = <<<'JSON'
            {
                "anyOf": [
                    {"type": "string"},
                    {"type": "integer"}
                ]
            }
            JSON;

        $result = $unit->import(
            jsonSchema: $this->jsonToSchema($json),
        );

        $this->assertInstanceOf(AnyOfSchema::class, $result);
    }

    #[TestDox('->import() maps allOf to an AllOfSchema')]
    public function test_imports_all_of(): void
    {
        $unit = new JsonSchemaDraft202012Importer();
        $json = <<<'JSON'
            {
                "allOf": [
                    {"type": "string"}
                ]
            }
            JSON;

        $result = $unit->import(
            jsonSchema: $this->jsonToSchema($json),
        );

        $this->assertInstanceOf(AllOfSchema::class, $result);
    }

    #[TestDox('->import() maps oneOf to a OneOfSchema')]
    public function test_imports_one_of(): void
    {
        $unit = new JsonSchemaDraft202012Importer();
        $json = <<<'JSON'
            {
                "oneOf": [
                    {"type": "string"},
                    {"type": "integer"}
                ]
            }
            JSON;

        $result = $unit->import(
            jsonSchema: $this->jsonToSchema($json),
        );

        $this->assertInstanceOf(OneOfSchema::class, $result);
    }

    // ================================================================
    //
    // Enum and const
    //
    // ----------------------------------------------------------------

    /**
     * enum is a closed set of allowed values and must land on
     * EnumSchema - not on a chain of OR'd literals, which would
     * work at runtime but miss the intent.
     */
    #[TestDox('->import() maps enum to an EnumSchema')]
    public function test_imports_enum(): void
    {
        $unit = new JsonSchemaDraft202012Importer();

        $result = $unit->import(
            jsonSchema: $this->jsonToSchema(
                '{"enum":["red","green","blue"]}',
            ),
        );

        $this->assertInstanceOf(EnumSchema::class, $result);
    }

    /**
     * const is a single allowed value and must land on
     * LiteralSchema - the more specific representation of "exactly
     * this one value".
     */
    #[TestDox('->import() maps const to a LiteralSchema')]
    public function test_imports_const(): void
    {
        $unit = new JsonSchemaDraft202012Importer();

        $result = $unit->import(
            jsonSchema: $this->jsonToSchema('{"const":"fixed"}'),
        );

        $this->assertInstanceOf(LiteralSchema::class, $result);
    }

    // ================================================================
    //
    // Nullable (type array)
    //
    // ----------------------------------------------------------------

    /**
     * ["X", "null"] is the OAS 3.1 / Draft 2020-12 idiom for a
     * nullable value. The importer must detect the two-element
     * "null" pattern and wrap the non-null type in NullableSchema,
     * not produce an anyOf or a plain NullSchema.
     */
    #[TestDox('->import() maps type: [X, null] to a NullableSchema wrapping X')]
    public function test_imports_nullable_via_type_array(): void
    {
        $unit = new JsonSchemaDraft202012Importer();

        $result = $unit->import(
            jsonSchema: $this->jsonToSchema(
                '{"type":["string","null"]}',
            ),
        );

        $this->assertInstanceOf(NullableSchema::class, $result);
        $this->assertNull($result->parse(null));
        $this->assertSame('hello', $result->parse('hello'));
    }

    // ================================================================
    //
    // Registry support ($defs / $ref)
    //
    // ----------------------------------------------------------------

    /**
     * when a registry is supplied, every $defs entry must land in
     * it under its bare name. This is the other half of the
     * round-trip with the exporter - import, export, re-import, and
     * see the same names on both sides.
     */
    #[TestDox('->import() registers $defs entries into the supplied registry')]
    public function test_imports_defs_into_registry(): void
    {
        $unit = new JsonSchemaDraft202012Importer();
        $registry = new JsonSchemaRegistry();
        $json = <<<'JSON'
            {
                "$defs": {
                    "Name": {"type": "string"}
                },
                "$ref": "#/$defs/Name"
            }
            JSON;

        $unit->import(
            jsonSchema: $this->jsonToSchema($json),
            registry: $registry,
        );

        $this->assertTrue($registry->has(name: 'Name'));
    }

    /**
     * a top-level $ref pointing into $defs must resolve to the
     * referenced schema, and the resolved schema must validate the
     * same values as the definition body.
     */
    #[TestDox('->import() resolves a top-level $ref into its $defs target')]
    public function test_imports_ref_resolves_to_defs(): void
    {
        $unit = new JsonSchemaDraft202012Importer();
        $json = <<<'JSON'
            {
                "$defs": {
                    "Name": {"type": "string"}
                },
                "$ref": "#/$defs/Name"
            }
            JSON;

        $result = $unit->import(
            jsonSchema: $this->jsonToSchema($json),
        );

        $this->assertSame('hello', $result->parse('hello'));
    }

    /**
     * when no registry is supplied the importer must still handle
     * $ref / $defs internally - callers that do not care about the
     * registry must not be forced to construct one.
     */
    #[TestDox('->import() handles $ref / $defs without a caller-supplied registry')]
    public function test_imports_ref_without_caller_registry(): void
    {
        $unit = new JsonSchemaDraft202012Importer();
        $json = <<<'JSON'
            {
                "$defs": {
                    "Id": {"type": "integer"}
                },
                "$ref": "#/$defs/Id"
            }
            JSON;

        $result = $unit->import(
            jsonSchema: $this->jsonToSchema($json),
        );

        $this->assertSame(42, $result->parse(42));
    }

    // ================================================================
    //
    // Error paths
    //
    // ----------------------------------------------------------------

    /**
     * an unknown `type` value is a caller mistake - the importer
     * must fail fast with a typed exception rather than silently
     * producing a MixedSchema or a null.
     */
    #[TestDox('->import() throws InvalidJsonSchemaException for an unknown type')]
    public function test_import_throws_on_unknown_type(): void
    {
        $unit = new JsonSchemaDraft202012Importer();

        $this->expectException(InvalidJsonSchemaException::class);

        $unit->import(
            jsonSchema: $this->jsonToSchema('{"type":"banana"}'),
        );
    }

    /**
     * a $defs entry that is not a JSON object is malformed - the
     * importer must reject it rather than importing garbage as a
     * MixedSchema.
     */
    #[TestDox('->import() throws InvalidJsonSchemaException when a $defs entry is not an object')]
    public function test_import_throws_on_non_object_defs_entry(): void
    {
        $unit = new JsonSchemaDraft202012Importer();
        $json = <<<'JSON'
            {
                "$defs": {
                    "Broken": "not-an-object"
                },
                "type": "string"
            }
            JSON;

        $this->expectException(InvalidJsonSchemaException::class);

        $unit->import(
            jsonSchema: $this->jsonToSchema($json),
        );
    }

    // ================================================================
    //
    // Helpers
    //
    // ----------------------------------------------------------------

    /**
     * build a JsonSchema from a JSON string so that each test case
     * can stay focussed on the schema text under test rather than
     * json_decode plumbing.
     */
    private function jsonToSchema(string $json): JsonSchema
    {
        $decoded = json_decode($json);
        assert($decoded instanceof stdClass);

        return new JsonSchema($decoded);
    }
}
