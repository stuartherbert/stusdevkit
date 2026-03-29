<?php

//
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
//

declare(strict_types=1);
namespace StusDevKit\ValidationKit\Tests\Acceptance;

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use stdClass;
use StusDevKit\ValidationKit\Exporters\JsonSchema;
use StusDevKit\ValidationKit\Exporters\JsonSchemaDraft202012Exporter;
use StusDevKit\ValidationKit\Validate;

#[TestDox('JsonSchemaDraft202012Exporter')]
class JsonSchemaDraft202012ExporterTest extends TestCase
{
    private const string SCHEMA_URI
        = 'https://json-schema.org/draft/2020-12/schema';

    // ================================================================
    //
    // JsonSchema value object
    //
    // ----------------------------------------------------------------

    #[TestDox('empty JsonSchema serializes as {}')]
    public function test_empty_json_schema_serializes_as_object(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that an empty JsonSchema serializes
        // as a JSON object {} rather than a JSON array []

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchema();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = json_encode($unit);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('{}', $actualResult);
    }

    #[TestDox('nested empty schemas serialize as {}')]
    public function test_nested_empty_schemas_serialize(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that nested empty arrays within
        // a JsonSchema are converted to {} during JSON
        // serialization

        // ----------------------------------------------------------------
        // setup your test

        $properties = new \stdClass();
        $properties->data = new \stdClass();

        $schema = new \stdClass();
        $schema->type = 'object';
        $schema->properties = $properties;

        $unit = new JsonSchema($schema);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = json_encode($unit);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            '{"type":"object","properties":{"data":{}}}',
            $actualResult,
        );
    }

    // ================================================================
    //
    // Primitive types
    //
    // ----------------------------------------------------------------

    #[TestDox('exports string type')]
    public function test_exports_string_type(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that Validate::string() exports as
        // a JSON Schema with type "string"

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Exporter();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->export(
            Validate::string(),
        )->toObject();

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals(
            (object) [
                '$schema' => self::SCHEMA_URI,
                'type' => 'string',
            ],
            $actualResult,
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('exports integer type')]
    public function test_exports_integer_type(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that Validate::int() exports as
        // a JSON Schema with type "integer"

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Exporter();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->export(
            Validate::int(),
        )->toObject();

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals(
            (object) [
                '$schema' => self::SCHEMA_URI,
                'type' => 'integer',
            ],
            $actualResult,
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('exports float as number type')]
    public function test_exports_float_as_number(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that Validate::float() exports as
        // type "number" (JSON Schema has no float type)

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Exporter();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->export(
            Validate::float(),
        )->toObject();

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals(
            (object) [
                '$schema' => self::SCHEMA_URI,
                'type' => 'number',
            ],
            $actualResult,
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('exports boolean type')]
    public function test_exports_boolean_type(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that Validate::boolean() exports as
        // type "boolean"

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Exporter();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->export(
            Validate::boolean(),
        )->toObject();

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals(
            (object) [
                '$schema' => self::SCHEMA_URI,
                'type' => 'boolean',
            ],
            $actualResult,
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('exports null type')]
    public function test_exports_null_type(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that Validate::null() exports as
        // type "null"

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Exporter();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->export(
            Validate::null(),
        )->toObject();

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals(
            (object) [
                '$schema' => self::SCHEMA_URI,
                'type' => 'null',
            ],
            $actualResult,
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('exports mixed as empty schema')]
    public function test_exports_mixed_as_empty_schema(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that Validate::mixed() exports as
        // an empty schema (accepts anything)

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Exporter();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->export(
            Validate::mixed(),
        )->toObject();

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals(
            (object) ['$schema' => self::SCHEMA_URI],
            $actualResult,
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // Literal and Enum
    //
    // ----------------------------------------------------------------

    #[TestDox('exports literal as const')]
    public function test_exports_literal_as_const(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that Validate::literal() exports
        // with the "const" keyword

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Exporter();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->export(
            Validate::literal('active'),
        )->toObject();

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals(
            (object) [
                '$schema' => self::SCHEMA_URI,
                'const' => 'active',
            ],
            $actualResult,
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('exports enum values')]
    public function test_exports_enum_values(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that Validate::enum() exports
        // with the "enum" keyword

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Exporter();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->export(
            Validate::enum(['a', 'b', 'c']),
        )->toObject();

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals(
            (object) [
                '$schema' => self::SCHEMA_URI,
                'enum' => ['a', 'b', 'c'],
            ],
            $actualResult,
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // String constraints
    //
    // ----------------------------------------------------------------

    #[TestDox('exports string min and max length')]
    public function test_exports_string_min_max_length(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that string min() and max()
        // export as minLength and maxLength

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Exporter();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->export(
            Validate::string()->min(length: 1)->max(length: 100),
        )->toObject();

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals(
            (object) [
                '$schema' => self::SCHEMA_URI,
                'type' => 'string',
                'minLength' => 1,
                'maxLength' => 100,
            ],
            $actualResult,
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('exports string pattern with delimiters stripped')]
    public function test_exports_string_pattern(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that string regex() exports as
        // pattern with PCRE delimiters and flags stripped

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Exporter();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->export(
            Validate::string()->regex(pattern: '/^[a-z]+$/i'),
        )->toObject();

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals(
            (object) [
                '$schema' => self::SCHEMA_URI,
                'type' => 'string',
                'pattern' => '^[a-z]+$',
            ],
            $actualResult,
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('exports string email format')]
    public function test_exports_string_email_format(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that string email() exports as
        // format "email"

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Exporter();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->export(
            Validate::string()->email(),
        )->toObject();

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals(
            (object) [
                '$schema' => self::SCHEMA_URI,
                'type' => 'string',
                'format' => 'email',
            ],
            $actualResult,
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // Numeric constraints
    //
    // ----------------------------------------------------------------

    #[TestDox('exports int minimum and maximum')]
    public function test_exports_int_minimum_maximum(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that int gte() and lte() export
        // as minimum and maximum

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Exporter();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->export(
            Validate::int()->gte(value: 0)->lte(value: 100),
        )->toObject();

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals(
            (object) [
                '$schema' => self::SCHEMA_URI,
                'type' => 'integer',
                'minimum' => 0,
                'maximum' => 100,
            ],
            $actualResult,
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('exports exclusive bounds')]
    public function test_exports_exclusive_bounds(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that int gt() and lt() export as
        // exclusiveMinimum and exclusiveMaximum

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Exporter();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->export(
            Validate::int()->gt(value: 0)->lt(value: 100),
        )->toObject();

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals(
            (object) [
                '$schema' => self::SCHEMA_URI,
                'type' => 'integer',
                'exclusiveMinimum' => 0,
                'exclusiveMaximum' => 100,
            ],
            $actualResult,
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('exports multipleOf')]
    public function test_exports_multiple_of(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that int multipleOf() exports as
        // the multipleOf keyword

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Exporter();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->export(
            Validate::int()->multipleOf(value: 5),
        )->toObject();

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals(
            (object) [
                '$schema' => self::SCHEMA_URI,
                'type' => 'integer',
                'multipleOf' => 5,
            ],
            $actualResult,
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // Array schema
    //
    // ----------------------------------------------------------------

    #[TestDox('exports array with items')]
    public function test_exports_array_with_items(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that Validate::array() exports with
        // type "array" and items containing the element schema

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Exporter();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->export(
            Validate::array(Validate::string()),
        )->toObject();

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals(
            (object) [
                '$schema' => self::SCHEMA_URI,
                'type' => 'array',
                'items' => (object) ['type' => 'string'],
            ],
            $actualResult,
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('exports array with constraints')]
    public function test_exports_array_constraints(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that array min(), max(), and
        // uniqueItems() export as minItems, maxItems, and
        // uniqueItems

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Exporter();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->export(
            Validate::array(Validate::string())
                ->min(length: 1)
                ->max(length: 10)
                ->uniqueItems(),
        )->toObject();

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals(
            (object) [
                '$schema' => self::SCHEMA_URI,
                'type' => 'array',
                'items' => (object) ['type' => 'string'],
                'minItems' => 1,
                'maxItems' => 10,
                'uniqueItems' => true,
            ],
            $actualResult,
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('exports array contains with minContains')]
    public function test_exports_array_contains(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that array contains() exports with
        // the contains and minContains keywords

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Exporter();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->export(
            Validate::array(Validate::mixed())->contains(
                schema: Validate::string(),
                minContains: 2,
            ),
        )->toObject();

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals(
            (object) [
                '$schema' => self::SCHEMA_URI,
                'type' => 'array',
                'items' => new stdClass(),
                'contains' => (object) ['type' => 'string'],
                'minContains' => 2,
            ],
            $actualResult,
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // Tuple and Record
    //
    // ----------------------------------------------------------------

    #[TestDox('exports tuple as prefixItems')]
    public function test_exports_tuple_as_prefix_items(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that Validate::tuple() exports with
        // prefixItems and items: false

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Exporter();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->export(
            Validate::tuple([
                Validate::string(),
                Validate::int(),
            ]),
        )->toObject();

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals(
            (object) [
                '$schema' => self::SCHEMA_URI,
                'type' => 'array',
                'prefixItems' => [
                    (object) ['type' => 'string'],
                    (object) ['type' => 'integer'],
                ],
                'items' => false,
            ],
            $actualResult,
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('exports record')]
    public function test_exports_record(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that Validate::record() exports
        // with propertyNames and additionalProperties

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Exporter();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->export(
            Validate::record(
                Validate::string(),
                Validate::int(),
            ),
        )->toObject();

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals(
            (object) [
                '$schema' => self::SCHEMA_URI,
                'type' => 'object',
                'propertyNames' => (object) [
                    'type' => 'string',
                ],
                'additionalProperties' => (object) [
                    'type' => 'integer',
                ],
            ],
            $actualResult,
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // Object schema
    //
    // ----------------------------------------------------------------

    #[TestDox('exports object with properties and required')]
    public function test_exports_object_with_properties_and_required(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that Validate::object() exports
        // with properties, required (excluding optional
        // fields), and additionalProperties

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Exporter();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->export(
            Validate::object([
                'name' => Validate::string(),
                'age' => Validate::int(),
                'nickname' => Validate::optional(
                    Validate::string(),
                ),
            ]),
        )->toObject();

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals(
            (object) [
                '$schema' => self::SCHEMA_URI,
                'type' => 'object',
                'properties' => (object) [
                    'name' => (object) ['type' => 'string'],
                    'age' => (object) ['type' => 'integer'],
                    'nickname' => (object) [
                        'type' => 'string',
                    ],
                ],
                'required' => ['name', 'age'],
                'additionalProperties' => false,
            ],
            $actualResult,
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('exports object with passthrough policy')]
    public function test_exports_object_passthrough_policy(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that passthrough() exports
        // additionalProperties as true

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Exporter();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->export(
            Validate::object([
                'name' => Validate::string(),
            ])->passthrough(),
        )->toObject();

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals(
            (object) [
                '$schema' => self::SCHEMA_URI,
                'type' => 'object',
                'properties' => (object) [
                    'name' => (object) ['type' => 'string'],
                ],
                'required' => ['name'],
                'additionalProperties' => true,
            ],
            $actualResult,
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // Nullable
    //
    // ----------------------------------------------------------------

    #[TestDox('exports nullable as anyOf with null')]
    public function test_exports_nullable_as_any_of_with_null(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that Validate::nullable() exports
        // as anyOf containing the inner schema and null

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Exporter();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->export(
            Validate::nullable(Validate::string()),
        )->toObject();

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals(
            (object) [
                '$schema' => self::SCHEMA_URI,
                'anyOf' => [
                    (object) ['type' => 'string'],
                    (object) ['type' => 'null'],
                ],
            ],
            $actualResult,
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // Logic schemas
    //
    // ----------------------------------------------------------------

    #[TestDox('exports anyOf')]
    public function test_exports_any_of(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that Validate::anyOf() exports
        // with the anyOf keyword

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Exporter();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->export(
            Validate::anyOf([
                Validate::string(),
                Validate::int(),
            ]),
        )->toObject();

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals(
            (object) [
                '$schema' => self::SCHEMA_URI,
                'anyOf' => [
                    (object) ['type' => 'string'],
                    (object) ['type' => 'integer'],
                ],
            ],
            $actualResult,
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('exports not')]
    public function test_exports_not(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that Validate::not() exports with
        // the not keyword

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Exporter();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->export(
            Validate::not(Validate::string()),
        )->toObject();

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals(
            (object) [
                '$schema' => self::SCHEMA_URI,
                'not' => (object) ['type' => 'string'],
            ],
            $actualResult,
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('exports conditional as if/then/else')]
    public function test_exports_conditional(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that Validate::conditional() exports
        // with if, then, and else keywords

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Exporter();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->export(
            Validate::conditional(
                if: Validate::string(),
                then: Validate::string()->min(length: 1),
                else: Validate::int(),
            ),
        )->toObject();

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals(
            (object) [
                '$schema' => self::SCHEMA_URI,
                'if' => (object) ['type' => 'string'],
                'then' => (object) [
                    'type' => 'string',
                    'minLength' => 1,
                ],
                'else' => (object) ['type' => 'integer'],
            ],
            $actualResult,
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // Metadata
    //
    // ----------------------------------------------------------------

    #[TestDox('exports description')]
    public function test_exports_description(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that withDescription() exports as
        // the description keyword

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Exporter();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->export(
            Validate::string()->withDescription('a name'),
        )->toObject();

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals(
            (object) [
                '$schema' => self::SCHEMA_URI,
                'type' => 'string',
                'description' => 'a name',
            ],
            $actualResult,
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('exports default value')]
    public function test_exports_default_value(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that withDefault() exports as the
        // default keyword

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Exporter();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->export(
            Validate::string()->withDefault('unknown'),
        )->toObject();

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals(
            (object) [
                '$schema' => self::SCHEMA_URI,
                'type' => 'string',
                'default' => 'unknown',
            ],
            $actualResult,
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // Specialized types
    //
    // ----------------------------------------------------------------

    #[TestDox('exports uuid as string with uuid format')]
    public function test_exports_uuid_as_string_format(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that Validate::uuid() exports as
        // type "string" with format "uuid"

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Exporter();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->export(
            Validate::uuid(),
        )->toObject();

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals(
            (object) [
                '$schema' => self::SCHEMA_URI,
                'type' => 'string',
                'format' => 'uuid',
            ],
            $actualResult,
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('exports dateTime as string with date-time format')]
    public function test_exports_datetime_as_string_format(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that Validate::dateTime() exports
        // as type "string" with format "date-time"

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Exporter();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->export(
            Validate::dateTime(),
        )->toObject();

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals(
            (object) [
                '$schema' => self::SCHEMA_URI,
                'type' => 'string',
                'format' => 'date-time',
            ],
            $actualResult,
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // Nested complex example
    //
    // ----------------------------------------------------------------

    #[TestDox('exports nested object with full structure')]
    public function test_exports_nested_object(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a realistic nested schema
        // with objects, arrays, optional fields, and
        // constraints exports as the complete expected
        // JSON Schema structure

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Exporter();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->export(
            Validate::object([
                'name' => Validate::string()
                    ->min(length: 1)
                    ->max(length: 255),
                'email' => Validate::string()->email(),
                'age' => Validate::optional(
                    Validate::int()
                        ->gte(value: 0)
                        ->lte(value: 150),
                ),
                'tags' => Validate::array(Validate::string())
                    ->max(length: 20)
                    ->uniqueItems(),
                'address' => Validate::object([
                    'street' => Validate::string(),
                    'city' => Validate::string(),
                    'zip' => Validate::string()
                        ->regex(pattern: '/^\d{5}$/'),
                ]),
            ]),
        )->toObject();

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals(
            (object) [
                '$schema' => self::SCHEMA_URI,
                'type' => 'object',
                'properties' => (object) [
                    'name' => (object) [
                        'type' => 'string',
                        'minLength' => 1,
                        'maxLength' => 255,
                    ],
                    'email' => (object) [
                        'type' => 'string',
                        'format' => 'email',
                    ],
                    'age' => (object) [
                        'type' => 'integer',
                        'minimum' => 0,
                        'maximum' => 150,
                    ],
                    'tags' => (object) [
                        'type' => 'array',
                        'items' => (object) [
                            'type' => 'string',
                        ],
                        'maxItems' => 20,
                        'uniqueItems' => true,
                    ],
                    'address' => (object) [
                        'type' => 'object',
                        'properties' => (object) [
                            'street' => (object) [
                                'type' => 'string',
                            ],
                            'city' => (object) [
                                'type' => 'string',
                            ],
                            'zip' => (object) [
                                'type' => 'string',
                                'pattern' => '^\d{5}$',
                            ],
                        ],
                        'required' => [
                            'street',
                            'city',
                            'zip',
                        ],
                        'additionalProperties' => false,
                    ],
                ],
                'required' => [
                    'name',
                    'email',
                    'tags',
                    'address',
                ],
                'additionalProperties' => false,
            ],
            $actualResult,
        );

        // ----------------------------------------------------------------
        // clean up the database

    }
}
