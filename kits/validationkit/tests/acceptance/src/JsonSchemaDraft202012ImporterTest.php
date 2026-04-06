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
use StusDevKit\ValidationKit\Exceptions\InvalidJsonSchemaException;
use StusDevKit\ValidationKit\JsonSchema\JsonSchema;
use StusDevKit\ValidationKit\JsonSchema\JsonSchemaDraft202012Exporter;
use StusDevKit\ValidationKit\JsonSchema\JsonSchemaDraft202012Importer;
use StusDevKit\ValidationKit\JsonSchema\JsonSchemaRegistry;
use StusDevKit\ValidationKit\Validate;

#[TestDox('JsonSchemaDraft202012Importer')]
class JsonSchemaDraft202012ImporterTest extends TestCase
{
    // ================================================================
    //
    // Helpers
    //
    // ----------------------------------------------------------------

    /**
     * build a JsonSchema from a JSON string
     *
     * This is a convenience helper that mirrors how
     * real-world code would typically receive JSON
     * Schema documents — as a JSON string decoded into
     * an object.
     */
    private function jsonToSchema(string $json): JsonSchema
    {
        $decoded = json_decode($json);
        assert($decoded instanceof stdClass);

        return new JsonSchema($decoded);
    }

    // ================================================================
    //
    // Primitive Types — acceptance
    //
    // ----------------------------------------------------------------

    #[TestDox('imported string schema accepts a string')]
    public function test_string_accepts_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a JSON Schema with type
        // "string" accepts string input

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": "string"
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('hello', $schema->parse('hello'));
    }

    #[TestDox('imported string schema rejects non-string')]
    public function test_string_rejects_non_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a JSON Schema with type
        // "string" rejects non-string input

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": "string"
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);
        $result = $schema->safeParse(123);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
    }

    #[TestDox('imported integer schema accepts an integer')]
    public function test_integer_accepts_integer(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a JSON Schema with type
        // "integer" accepts integer input

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": "integer"
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(42, $schema->parse(42));
    }

    #[TestDox('imported integer schema rejects non-integer')]
    public function test_integer_rejects_non_integer(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a JSON Schema with type
        // "integer" rejects non-integer input

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": "integer"
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);
        $result = $schema->safeParse('hello');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
    }

    #[TestDox('imported number schema accepts int and float')]
    public function test_number_accepts_int_and_float(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a JSON Schema with type
        // "number" accepts both integers and floats

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": "number"
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(42, $schema->parse(42));
        $this->assertSame(3.14, $schema->parse(3.14));
    }

    #[TestDox('imported number schema rejects non-number')]
    public function test_number_rejects_non_number(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a JSON Schema with type
        // "number" rejects non-numeric input

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": "number"
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);
        $result = $schema->safeParse('hello');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
    }

    #[TestDox('imported boolean schema accepts booleans')]
    public function test_boolean_accepts_booleans(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a JSON Schema with type
        // "boolean" accepts boolean input

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": "boolean"
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($schema->parse(true));
        $this->assertFalse($schema->parse(false));
    }

    #[TestDox('imported boolean schema rejects non-boolean')]
    public function test_boolean_rejects_non_boolean(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a JSON Schema with type
        // "boolean" rejects non-boolean input

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": "boolean"
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);
        $result = $schema->safeParse('true');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
    }

    #[TestDox('imported null schema accepts null')]
    public function test_null_accepts_null(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a JSON Schema with type
        // "null" accepts null

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": "null"
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);

        // ----------------------------------------------------------------
        // test the results

        $this->assertNull($schema->parse(null));
    }

    #[TestDox('imported null schema rejects non-null')]
    public function test_null_rejects_non_null(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a JSON Schema with type
        // "null" rejects non-null input

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": "null"
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);
        $result = $schema->safeParse('hello');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
    }

    #[TestDox('empty schema accepts any value')]
    public function test_empty_schema_accepts_any_value(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that an empty JSON Schema (the
        // "true schema") is imported as Validate::mixed(),
        // accepting any value

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {}
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('hello', $schema->parse('hello'));
        $this->assertSame(42, $schema->parse(42));
        $this->assertNull($schema->parse(null));
    }

    // ================================================================
    //
    // String Constraints and Formats
    //
    // ----------------------------------------------------------------

    #[TestDox('string with minLength/maxLength accepts valid length')]
    public function test_string_length_accepts_valid(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that minLength and maxLength are
        // imported as string length constraints

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": "string",
                "minLength": 2,
                "maxLength": 5
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('abc', $schema->parse('abc'));
    }

    #[TestDox('string with minLength rejects too-short string')]
    public function test_string_min_length_rejects_short(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a string shorter than
        // minLength is rejected

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": "string",
                "minLength": 2
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);
        $result = $schema->safeParse('a');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
    }

    #[TestDox('string with maxLength rejects too-long string')]
    public function test_string_max_length_rejects_long(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a string longer than
        // maxLength is rejected

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": "string",
                "maxLength": 5
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);
        $result = $schema->safeParse('abcdef');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
    }

    #[TestDox('string with pattern accepts matching input')]
    public function test_string_pattern_accepts_match(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the pattern keyword is
        // imported as a regex constraint

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": "string",
                "pattern": "^[a-z]+$"
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('abc', $schema->parse('abc'));
    }

    #[TestDox('string with pattern rejects non-matching input')]
    public function test_string_pattern_rejects_non_match(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a string not matching the
        // pattern is rejected

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": "string",
                "pattern": "^[a-z]+$"
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);
        $result = $schema->safeParse('ABC');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
    }

    #[TestDox('string with email format accepts valid email')]
    public function test_string_email_accepts_valid(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that format "email" is imported
        // as the email() constraint

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": "string",
                "format": "email"
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            'test@example.com',
            $schema->parse('test@example.com'),
        );
    }

    #[TestDox('string with email format rejects invalid email')]
    public function test_string_email_rejects_invalid(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that format "email" rejects
        // non-email strings

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": "string",
                "format": "email"
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);
        $result = $schema->safeParse('not-an-email');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
    }

    #[TestDox('string with uri format accepts valid uri')]
    public function test_string_uri_accepts_valid(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that format "uri" is imported
        // as the url() constraint

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": "string",
                "format": "uri"
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            'https://example.com',
            $schema->parse('https://example.com'),
        );
    }

    #[TestDox('string with uri format rejects invalid uri')]
    public function test_string_uri_rejects_invalid(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that format "uri" rejects
        // non-URI strings

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": "string",
                "format": "uri"
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);
        $result = $schema->safeParse('not a url');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
    }

    #[TestDox('string with uuid format accepts valid uuid')]
    public function test_string_uuid_accepts_valid(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that format "uuid" is imported
        // as the uuid() constraint

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": "string",
                "format": "uuid"
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            '550e8400-e29b-41d4-a716-446655440000',
            $schema->parse(
                '550e8400-e29b-41d4-a716-446655440000',
            ),
        );
    }

    #[TestDox('string with uuid format rejects invalid uuid')]
    public function test_string_uuid_rejects_invalid(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that format "uuid" rejects
        // non-UUID strings

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": "string",
                "format": "uuid"
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);
        $result = $schema->safeParse('not-a-uuid');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
    }

    #[TestDox('string with unknown format stores it as metadata')]
    public function test_string_unknown_format_as_metadata(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that an unrecognised format value
        // is preserved as metadata rather than throwing

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": "string",
                "format": "date-time"
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            '2026-01-01T00:00:00Z',
            $schema->parse('2026-01-01T00:00:00Z'),
        );
    }

    // ================================================================
    //
    // Numeric Constraints
    //
    // ----------------------------------------------------------------

    #[TestDox('integer with minimum/maximum accepts in-range')]
    public function test_integer_range_accepts_valid(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that minimum and maximum are
        // imported as gte and lte constraints

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": "integer",
                "minimum": 1,
                "maximum": 10
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(5, $schema->parse(5));
        $this->assertSame(1, $schema->parse(1));
        $this->assertSame(10, $schema->parse(10));
    }

    #[TestDox('integer with minimum rejects below minimum')]
    public function test_integer_minimum_rejects_below(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a value below minimum is
        // rejected

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": "integer",
                "minimum": 1
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);
        $result = $schema->safeParse(0);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
    }

    #[TestDox('integer with maximum rejects above maximum')]
    public function test_integer_maximum_rejects_above(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a value above maximum is
        // rejected

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": "integer",
                "maximum": 10
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);
        $result = $schema->safeParse(11);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
    }

    #[TestDox('integer with exclusiveMinimum rejects boundary')]
    public function test_integer_exclusive_min_rejects_boundary(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that exclusiveMinimum is imported
        // as a gt constraint (boundary excluded)

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": "integer",
                "exclusiveMinimum": 0
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);
        $result = $schema->safeParse(0);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
    }

    #[TestDox('integer with exclusiveMaximum rejects boundary')]
    public function test_integer_exclusive_max_rejects_boundary(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that exclusiveMaximum is imported
        // as a lt constraint (boundary excluded)

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": "integer",
                "exclusiveMaximum": 10
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);
        $result = $schema->safeParse(10);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
    }

    #[TestDox('integer with multipleOf accepts a multiple')]
    public function test_integer_multiple_of_accepts(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that multipleOf is imported as
        // the multipleOf constraint

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": "integer",
                "multipleOf": 3
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(9, $schema->parse(9));
    }

    #[TestDox('integer with multipleOf rejects non-multiple')]
    public function test_integer_multiple_of_rejects(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a non-multiple value is
        // rejected by multipleOf

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": "integer",
                "multipleOf": 3
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);
        $result = $schema->safeParse(10);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
    }

    #[TestDox('number with float minimum accepts in-range')]
    public function test_number_float_range_accepts(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that number schemas correctly
        // apply float-valued range constraints

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": "number",
                "minimum": 0.5,
                "maximum": 9.5
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(3.14, $schema->parse(3.14));
    }

    #[TestDox('number with float minimum rejects below')]
    public function test_number_float_range_rejects_below(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a value below the float
        // minimum is rejected

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": "number",
                "minimum": 0.5
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);
        $result = $schema->safeParse(0.1);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
    }

    // ================================================================
    //
    // OAS Format Values
    //
    // ----------------------------------------------------------------

    #[TestDox('integer with format int32 accepts in-range value')]
    public function test_integer_int32_accepts_valid(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that format "int32" on an integer
        // schema is imported as the int32() constraint

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": "integer",
                "format": "int32"
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            42,
            $schema->parse(42),
        );
    }

    #[TestDox('integer with format int32 rejects out-of-range value')]
    public function test_integer_int32_rejects_out_of_range(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that format "int32" rejects values
        // outside the 32-bit signed integer range

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": "integer",
                "format": "int32"
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);
        $result = $schema->safeParse(3_000_000_000);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
    }

    #[TestDox('integer with format int64 accepts value')]
    public function test_integer_int64_accepts_valid(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that format "int64" on an integer
        // schema is imported as the int64() constraint

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": "integer",
                "format": "int64"
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            PHP_INT_MAX,
            $schema->parse(PHP_INT_MAX),
        );
    }

    #[TestDox('number with format float accepts in-range value')]
    public function test_number_float_accepts_valid(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that format "float" on a number
        // schema is imported as the float() constraint

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": "number",
                "format": "float"
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            3.14,
            $schema->parse(3.14),
        );
    }

    #[TestDox('number with format float rejects out-of-range value')]
    public function test_number_float_rejects_out_of_range(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that format "float" rejects values
        // outside the IEEE 754 single-precision range

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": "number",
                "format": "float"
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);
        $result = $schema->safeParse(3.5E+38);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
    }

    #[TestDox('number with format double accepts value')]
    public function test_number_double_accepts_valid(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that format "double" on a number
        // schema is imported as the double() constraint

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": "number",
                "format": "double"
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            1.7976931348623E+308,
            $schema->parse(1.7976931348623E+308),
        );
    }

    #[TestDox('string with format password accepts any string')]
    public function test_string_password_accepts_valid(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that format "password" on a string
        // schema is imported as the password() marker

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": "string",
                "format": "password"
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            's3cret!',
            $schema->parse('s3cret!'),
        );
    }

    // ================================================================
    //
    // Array and Tuple Schemas
    //
    // ----------------------------------------------------------------

    #[TestDox('array with items accepts valid elements')]
    public function test_array_items_accepts_valid(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that an array schema with items
        // is imported with an element schema

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": "array",
                "items": {"type": "integer"}
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame([1, 2, 3], $schema->parse([1, 2, 3]));
    }

    #[TestDox('array with items rejects invalid element')]
    public function test_array_items_rejects_invalid(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that an element not matching
        // the items schema is rejected

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": "array",
                "items": {"type": "integer"}
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);
        $result = $schema->safeParse([1, 'two', 3]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
    }

    #[TestDox('array with minItems rejects too few')]
    public function test_array_min_items_rejects(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that minItems is imported as an
        // array length constraint

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": "array",
                "items": {"type": "string"},
                "minItems": 1
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);
        $result = $schema->safeParse([]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
    }

    #[TestDox('array with maxItems rejects too many')]
    public function test_array_max_items_rejects(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that maxItems is imported as an
        // array length constraint

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": "array",
                "items": {"type": "string"},
                "maxItems": 3
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);
        $result = $schema->safeParse(['a', 'b', 'c', 'd']);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
    }

    #[TestDox('array with uniqueItems accepts unique values')]
    public function test_array_unique_items_accepts(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that uniqueItems is imported as
        // the uniqueItems constraint

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": "array",
                "items": {"type": "integer"},
                "uniqueItems": true
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            [1, 2, 3],
            $schema->parse([1, 2, 3]),
        );
    }

    #[TestDox('array with uniqueItems rejects duplicates')]
    public function test_array_unique_items_rejects(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that duplicate values are
        // rejected when uniqueItems is true

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": "array",
                "items": {"type": "integer"},
                "uniqueItems": true
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);
        $result = $schema->safeParse([1, 2, 2]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
    }

    #[TestDox('tuple via prefixItems accepts valid input')]
    public function test_tuple_accepts_valid(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that prefixItems is imported as
        // a tuple schema

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": "array",
                "prefixItems": [
                    {"type": "string"},
                    {"type": "integer"}
                ],
                "items": false
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            ['hello', 42],
            $schema->parse(['hello', 42]),
        );
    }

    #[TestDox('tuple via prefixItems rejects wrong types')]
    public function test_tuple_rejects_wrong_types(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a tuple with wrong element
        // types is rejected

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": "array",
                "prefixItems": [
                    {"type": "string"},
                    {"type": "integer"}
                ],
                "items": false
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);
        $result = $schema->safeParse([42, 'hello']);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
    }

    // ================================================================
    //
    // Object and Record Schemas
    //
    // ----------------------------------------------------------------

    #[TestDox('object accepts valid properties')]
    public function test_object_accepts_valid(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that an object schema with
        // properties is imported correctly

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": "object",
                "properties": {
                    "name": {"type": "string"},
                    "age": {"type": "integer"}
                },
                "required": ["name"],
                "additionalProperties": false
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);

        // ----------------------------------------------------------------
        // test the results

        $parsed = $schema->parse((object) [
            'name' => 'Alice',
            'age' => 30,
        ]);
        /** @var object{name: string, age: int} $parsed */
        $this->assertSame('Alice', $parsed->name);
        $this->assertSame(30, $parsed->age);
    }

    #[TestDox('object accepts missing optional field')]
    public function test_object_accepts_optional_missing(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that non-required fields are
        // imported as optional

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": "object",
                "properties": {
                    "name": {"type": "string"},
                    "age": {"type": "integer"}
                },
                "required": ["name"],
                "additionalProperties": false
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);

        // ----------------------------------------------------------------
        // test the results

        $parsed = $schema->parse((object) ['name' => 'Bob']);
        /** @var object{name: string} $parsed */
        $this->assertSame('Bob', $parsed->name);
    }

    #[TestDox('object rejects missing required field')]
    public function test_object_rejects_missing_required(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that missing required fields
        // cause validation failure

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": "object",
                "properties": {
                    "name": {"type": "string"}
                },
                "required": ["name"],
                "additionalProperties": false
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);
        $result = $schema->safeParse((object) []);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
    }

    #[TestDox('object with additionalProperties true keeps extras')]
    public function test_object_passthrough_keeps_extras(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that additionalProperties: true
        // is imported as passthrough mode

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": "object",
                "properties": {
                    "name": {"type": "string"}
                },
                "required": ["name"],
                "additionalProperties": true
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);

        // ----------------------------------------------------------------
        // test the results

        $parsed = $schema->parse((object) [
            'name' => 'Alice',
            'extra' => 'kept',
        ]);
        /** @var object{name: string, extra: string} $parsed */
        $this->assertSame('Alice', $parsed->name);
        $this->assertSame('kept', $parsed->extra);
    }

    #[TestDox('object with catchall schema accepts matching extras')]
    public function test_object_catchall_accepts(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that additionalProperties as a
        // schema is imported as a catchall

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": "object",
                "properties": {
                    "name": {"type": "string"}
                },
                "required": ["name"],
                "additionalProperties": {"type": "integer"}
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);

        // ----------------------------------------------------------------
        // test the results

        $parsed = $schema->parse((object) [
            'name' => 'Alice',
            'score' => 100,
        ]);
        /** @var object{name: string, score: int} $parsed */
        $this->assertSame('Alice', $parsed->name);
        $this->assertSame(100, $parsed->score);
    }

    #[TestDox('object with catchall schema rejects non-matching extras')]
    public function test_object_catchall_rejects(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that extras not matching the
        // catchall schema are rejected

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": "object",
                "properties": {
                    "name": {"type": "string"}
                },
                "required": ["name"],
                "additionalProperties": {"type": "integer"}
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);
        $result = $schema->safeParse((object) [
            'name' => 'Alice',
            'score' => 'not-int',
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
    }

    #[TestDox('record schema accepts valid key-value pairs')]
    public function test_record_accepts_valid(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that an object with propertyNames
        // and additionalProperties is imported as a record

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": "object",
                "propertyNames": {
                    "type": "string",
                    "minLength": 1
                },
                "additionalProperties": {"type": "integer"}
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            ['a' => 1, 'b' => 2],
            $schema->parse(['a' => 1, 'b' => 2]),
        );
    }

    #[TestDox('record schema rejects invalid value type')]
    public function test_record_rejects_invalid_value(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that record values not matching
        // the value schema are rejected

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": "object",
                "propertyNames": {
                    "type": "string",
                    "minLength": 1
                },
                "additionalProperties": {"type": "integer"}
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);
        $result = $schema->safeParse(['a' => 'not-int']);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
    }

    #[TestDox('object with minProperties rejects too few')]
    public function test_object_min_properties_rejects(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that minProperties is imported
        // as a constraint

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": "object",
                "additionalProperties": true,
                "minProperties": 2
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);
        $result = $schema->safeParse((object) ['a' => 1]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
    }

    // ================================================================
    //
    // Type Arrays (OAS 3.1 nullable pattern)
    //
    // ----------------------------------------------------------------

    #[TestDox('type array ["string", "null"] accepts string')]
    public function test_type_array_nullable_string_accepts_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the OAS 3.1 nullable
        // pattern (type as array with "null") imports
        // correctly and accepts the inner type

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": ["string", "null"]
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('hello', $schema->parse('hello'));
    }

    #[TestDox('type array ["string", "null"] accepts null')]
    public function test_type_array_nullable_string_accepts_null(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the OAS 3.1 nullable
        // pattern accepts null values

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": ["string", "null"]
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);

        // ----------------------------------------------------------------
        // test the results

        $this->assertNull($schema->parse(null));
    }

    #[TestDox('type array ["string", "null"] rejects non-matching type')]
    public function test_type_array_nullable_string_rejects_int(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the OAS 3.1 nullable
        // pattern rejects values that don't match the
        // inner type or null

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": ["string", "null"]
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);
        $result = $schema->safeParse(123);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
    }

    #[TestDox('type array ["string", "null"] preserves constraints')]
    public function test_type_array_nullable_preserves_constraints(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that constraints like minLength
        // are applied to the inner type when type is an
        // array with "null"

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": ["string", "null"],
                "minLength": 1
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('hello', $schema->parse('hello'));
        $this->assertNull($schema->parse(null));

        $result = $schema->safeParse('');
        $this->assertTrue($result->failed());
    }

    #[TestDox('type array ["null", "integer"] handles null-first order')]
    public function test_type_array_nullable_null_first(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the nullable pattern works
        // regardless of the order of types in the array

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": ["null", "integer"]
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(42, $schema->parse(42));
        $this->assertNull($schema->parse(null));
    }

    #[TestDox('type array with single element works as scalar type')]
    public function test_type_array_single_element(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a single-element type array
        // is treated as a plain scalar type

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": ["string"],
                "minLength": 1
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('hello', $schema->parse('hello'));

        $result = $schema->safeParse('');
        $this->assertTrue($result->failed());
    }

    #[TestDox('type array with multiple non-null types creates anyOf')]
    public function test_type_array_multi_type(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a multi-type array without
        // null creates an anyOf schema accepting any of the
        // listed types

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": ["string", "integer"]
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('hello', $schema->parse('hello'));
        $this->assertSame(42, $schema->parse(42));

        $result = $schema->safeParse(true);
        $this->assertTrue($result->failed());
    }

    #[TestDox('type array with multiple types plus null creates nullable anyOf')]
    public function test_type_array_multi_type_with_null(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a type array with multiple
        // non-null types plus null creates a nullable anyOf

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": ["string", "integer", "null"]
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('hello', $schema->parse('hello'));
        $this->assertSame(42, $schema->parse(42));
        $this->assertNull($schema->parse(null));

        $result = $schema->safeParse(true);
        $this->assertTrue($result->failed());
    }

    // ================================================================
    //
    // Composition Schemas
    //
    // ----------------------------------------------------------------

    #[TestDox('nullable accepts inner type and null')]
    public function test_nullable_accepts_inner_and_null(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the nullable pattern (anyOf
        // with a null branch) is imported as nullable()

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "anyOf": [
                    {"type": "string"},
                    {"type": "null"}
                ]
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('hello', $schema->parse('hello'));
        $this->assertNull($schema->parse(null));
    }

    #[TestDox('nullable rejects non-matching type')]
    public function test_nullable_rejects_non_matching(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a nullable schema rejects
        // values that don't match the inner type or null

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "anyOf": [
                    {"type": "string"},
                    {"type": "null"}
                ]
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);
        $result = $schema->safeParse(123);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
    }

    #[TestDox('anyOf accepts any matching branch')]
    public function test_any_of_accepts_matching(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that anyOf accepts values
        // matching any branch

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "anyOf": [
                    {"type": "string"},
                    {"type": "integer"},
                    {"type": "boolean"}
                ]
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('hello', $schema->parse('hello'));
        $this->assertSame(42, $schema->parse(42));
        $this->assertTrue($schema->parse(true));
    }

    #[TestDox('anyOf rejects non-matching value')]
    public function test_any_of_rejects_non_matching(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that anyOf rejects values that
        // don't match any branch

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "anyOf": [
                    {"type": "string"},
                    {"type": "integer"}
                ]
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);
        $result = $schema->safeParse([]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
    }

    #[TestDox('oneOf accepts matching value')]
    public function test_one_of_accepts_matching(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that oneOf is imported as
        // Validate::oneOf()

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "oneOf": [
                    {"type": "string"},
                    {"type": "integer"}
                ]
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('hello', $schema->parse('hello'));
        $this->assertSame(42, $schema->parse(42));
    }

    #[TestDox('oneOf rejects non-matching value')]
    public function test_one_of_rejects_non_matching(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that oneOf rejects values not
        // matching any branch

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "oneOf": [
                    {"type": "string"},
                    {"type": "integer"}
                ]
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);
        $result = $schema->safeParse([]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
    }

    #[TestDox('allOf accepts value matching all branches')]
    public function test_all_of_accepts_matching(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that allOf requires all branches
        // to validate

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "allOf": [
                    {"type": "integer", "minimum": 1},
                    {"type": "integer", "maximum": 10}
                ]
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(5, $schema->parse(5));
    }

    #[TestDox('allOf rejects value failing any branch')]
    public function test_all_of_rejects_failing_branch(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that failing any allOf branch
        // causes rejection

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "allOf": [
                    {"type": "integer", "minimum": 1},
                    {"type": "integer", "maximum": 10}
                ]
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);
        $belowMin = $schema->safeParse(0);
        $aboveMax = $schema->safeParse(11);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($belowMin->failed());
        $this->assertTrue($aboveMax->failed());
    }

    #[TestDox('not accepts non-matching value')]
    public function test_not_accepts_non_matching(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the not keyword is imported
        // as Validate::not()

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "not": {"type": "string"}
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(42, $schema->parse(42));
    }

    #[TestDox('not rejects matching value')]
    public function test_not_rejects_matching(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a value matching the not
        // schema is rejected

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "not": {"type": "string"}
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);
        $result = $schema->safeParse('hello');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
    }

    #[TestDox('conditional applies then branch on if-match')]
    public function test_conditional_then_branch(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that when the if-schema matches,
        // the then-branch is applied

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "if": {"type": "string"},
                "then": {"type": "string", "minLength": 1},
                "else": {"type": "integer"}
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('hello', $schema->parse('hello'));
    }

    #[TestDox('conditional rejects then-branch failure')]
    public function test_conditional_then_rejects(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that when the if-schema matches
        // but then-branch fails, the value is rejected

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "if": {"type": "string"},
                "then": {"type": "string", "minLength": 1},
                "else": {"type": "integer"}
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);
        $result = $schema->safeParse('');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
    }

    #[TestDox('conditional applies else branch on if-mismatch')]
    public function test_conditional_else_branch(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that when the if-schema does not
        // match, the else-branch is applied

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "if": {"type": "string"},
                "then": {"type": "string", "minLength": 1},
                "else": {"type": "integer"}
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(42, $schema->parse(42));
    }

    // ================================================================
    //
    // Enum and Literal
    //
    // ----------------------------------------------------------------

    #[TestDox('enum accepts listed value')]
    public function test_enum_accepts_listed(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the enum keyword is imported
        // with the listed values

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "enum": ["red", "green", "blue"]
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('red', $schema->parse('red'));
    }

    #[TestDox('enum rejects unlisted value')]
    public function test_enum_rejects_unlisted(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a value not in the enum
        // list is rejected

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "enum": ["red", "green", "blue"]
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);
        $result = $schema->safeParse('yellow');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
    }

    #[TestDox('const accepts exact value')]
    public function test_const_accepts_exact(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the const keyword is
        // imported as Validate::literal()

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "const": "fixed"
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('fixed', $schema->parse('fixed'));
    }

    #[TestDox('const rejects different value')]
    public function test_const_rejects_different(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a value different from the
        // const is rejected

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "const": "fixed"
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);
        $result = $schema->safeParse('other');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
    }

    // ================================================================
    //
    // $ref / $defs
    //
    // ----------------------------------------------------------------

    #[TestDox('$ref resolves to $defs entry')]
    public function test_ref_resolves_to_defs(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that $defs are registered and
        // $ref is resolved to the defined schema

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": "object",
                "properties": {
                    "address": {"$ref": "#/$defs/Address"}
                },
                "required": ["address"],
                "additionalProperties": false,
                "$defs": {
                    "Address": {
                        "type": "object",
                        "properties": {
                            "street": {"type": "string"}
                        },
                        "required": ["street"],
                        "additionalProperties": false
                    }
                }
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);

        // ----------------------------------------------------------------
        // test the results

        $result = $schema->parse((object) [
            'address' => (object) ['street' => '123 Main St'],
        ]);
        /** @var object{address: object{street: string}} $result */
        $this->assertSame(
            '123 Main St',
            $result->address->street,
        );
    }

    #[TestDox('$ref validates nested content')]
    public function test_ref_validates_nested(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a $ref-resolved schema
        // actually validates nested content

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": "object",
                "properties": {
                    "address": {"$ref": "#/$defs/Address"}
                },
                "required": ["address"],
                "additionalProperties": false,
                "$defs": {
                    "Address": {
                        "type": "object",
                        "properties": {
                            "street": {"type": "string"}
                        },
                        "required": ["street"],
                        "additionalProperties": false
                    }
                }
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);
        $result = $schema->safeParse((object) [
            'address' => (object) ['street' => 123],
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
    }

    #[TestDox('$ref with description sibling round-trips')]
    public function test_ref_with_description_sibling(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // Draft 2020-12 allows keywords alongside $ref.
        // This test proves that annotation siblings like
        // description are preserved alongside $ref through
        // a full import → export round-trip.

        // ----------------------------------------------------------------
        // setup your test

        $importer = new JsonSchemaDraft202012Importer();
        $exporter = new JsonSchemaDraft202012Exporter();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "$schema": "https://json-schema.org/draft/2020-12/schema",
                "type": "object",
                "properties": {
                    "name": {
                        "$ref": "#/$defs/Name",
                        "description": "The user's full name"
                    }
                },
                "additionalProperties": false,
                "$defs": {
                    "Name": {
                        "type": "string",
                        "minLength": 1
                    }
                }
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $registry = new JsonSchemaRegistry();
        $schema = $importer->import(
            jsonSchema: $jsonSchema,
            registry: $registry,
        );
        $result = $exporter->export(
            schema: $schema,
            registry: $registry,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals(
            $jsonSchema->toObject(),
            $result->toObject(),
        );

        // the ref's constraint must still validate
        $failResult = $schema->safeParse((object) [
            'name' => '',
        ]);
        $this->assertTrue($failResult->failed());
    }

    #[TestDox('$ref with validation siblings applies both')]
    public function test_ref_with_validation_siblings(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // Draft 2020-12 allows validation keywords alongside
        // $ref. This test proves that both the referenced
        // schema and the sibling validation keywords are
        // applied. The $defs Name allows minLength: 1, but
        // the sibling tightens it to minLength: 5.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": "object",
                "properties": {
                    "nickname": {
                        "$ref": "#/$defs/Name",
                        "type": "string",
                        "maxLength": 20
                    }
                },
                "$defs": {
                    "Name": {
                        "type": "string",
                        "minLength": 1
                    }
                }
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);

        // ----------------------------------------------------------------
        // test the results

        // valid: satisfies both ref (minLength: 1) and
        // sibling (maxLength: 20)
        $validResult = $schema->safeParse((object) [
            'nickname' => 'Stu',
        ]);
        $this->assertFalse($validResult->failed());

        // invalid: fails ref's minLength: 1
        $emptyResult = $schema->safeParse((object) [
            'nickname' => '',
        ]);
        $this->assertTrue($emptyResult->failed());

        // invalid: fails sibling's maxLength: 20
        $longResult = $schema->safeParse((object) [
            'nickname' => str_repeat('a', 21),
        ]);
        $this->assertTrue($longResult->failed());
    }

    // ================================================================
    //
    // Metadata
    //
    // ----------------------------------------------------------------

    #[TestDox('imports title and description')]
    public function test_imports_title_and_description(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that title and description
        // keywords are imported as metadata on the schema

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": "string",
                "title": "User Name",
                "description": "The full name"
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            'User Name',
            $schema->maybeTitle(),
        );
        $this->assertSame(
            'The full name',
            $schema->maybeDescription(),
        );
    }

    #[TestDox('imports $comment as metadata')]
    public function test_imports_comment_as_metadata(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the $comment keyword is
        // imported and stored as metadata on the schema

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": "string",
                "$comment": "internal note for devs"
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            ['$comment' => 'internal note for devs'],
            $schema->getMetadata(),
        );
    }

    #[TestDox('imports examples')]
    public function test_imports_examples(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the examples keyword is
        // imported as example values on the schema

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": "string",
                "examples": ["Alice", "Bob"]
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            ['Alice', 'Bob'],
            $schema->getExamples(),
        );
    }

    #[TestDox('imports deprecated flag')]
    public function test_imports_deprecated(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that deprecated: true is
        // imported as a deprecated flag on the schema

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": "string",
                "deprecated": true
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($schema->isDeprecated());
    }

    #[TestDox('imports readOnly flag')]
    public function test_imports_read_only(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that readOnly is imported on
        // the schema

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": "string",
                "readOnly": true
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($schema->isReadOnly());
        $this->assertFalse($schema->isWriteOnly());
    }

    #[TestDox('imports writeOnly flag')]
    public function test_imports_write_only(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that writeOnly is imported on
        // the schema

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": "string",
                "writeOnly": true
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($schema->isWriteOnly());
        $this->assertFalse($schema->isReadOnly());
    }

    #[TestDox('imports default value')]
    public function test_imports_default_value(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the default keyword is
        // imported as a default value, used when input
        // is null

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": "string",
                "default": "fallback"
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $schema = $unit->import($jsonSchema);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            'fallback',
            $schema->parse(null),
        );
    }

    // ================================================================
    //
    // Error Handling
    //
    // ----------------------------------------------------------------

    #[TestDox('throws on unknown type')]
    public function test_throws_on_unknown_type(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that an unrecognised type value
        // throws an InvalidJsonSchemaException

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "type": "foobar"
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $this->expectException(
            InvalidJsonSchemaException::class,
        );
        $unit->import($jsonSchema);
    }

    #[TestDox('throws on unresolved $ref')]
    public function test_throws_on_unresolved_ref(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a $ref pointing to a
        // non-existent $defs entry throws

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "$ref": "#/$defs/Missing"
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $this->expectException(
            InvalidJsonSchemaException::class,
        );
        $unit->import($jsonSchema);
    }

    // ================================================================
    //
    // Round-trip
    //
    // ----------------------------------------------------------------

    #[TestDox('round-trip accepts same valid data')]
    public function test_round_trip_accepts_valid(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that exporting a schema and
        // importing it back produces a schema that accepts
        // the same valid data

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();
        $exporter = new JsonSchemaDraft202012Exporter();

        $original = Validate::object([
            'name' => Validate::string()
                ->min(length: 1)
                ->max(length: 100),
            'age' => Validate::optional(
                Validate::int()->gte(value: 0),
            ),
            'tags' => Validate::array(
                element: Validate::string(),
            )->uniqueItems(),
        ])->strict();

        $validData = (object) [
            'name' => 'Alice',
            'age' => 30,
            'tags' => ['admin', 'user'],
        ];

        // ----------------------------------------------------------------
        // perform the change

        $exported = $exporter->export($original);
        $imported = $unit->import($exported);

        // ----------------------------------------------------------------
        // test the results

        $originalResult = $original->parse($validData);
        $importedResult = $imported->parse($validData);

        /** @var object{name: string, age: int} $originalResult */
        /** @var object{name: string, age: int} $importedResult */
        $this->assertSame(
            $originalResult->name,
            $importedResult->name,
        );
        $this->assertSame(
            $originalResult->age,
            $importedResult->age,
        );
    }

    #[TestDox('round-trip rejects same invalid data')]
    public function test_round_trip_rejects_invalid(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that exporting a schema and
        // importing it back produces a schema that rejects
        // the same invalid data

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Importer();
        $exporter = new JsonSchemaDraft202012Exporter();

        $original = Validate::object([
            'name' => Validate::string()
                ->min(length: 1)
                ->max(length: 100),
            'age' => Validate::optional(
                Validate::int()->gte(value: 0),
            ),
            'tags' => Validate::array(
                element: Validate::string(),
            )->uniqueItems(),
        ])->strict();

        $invalidData = (object) [
            'name' => '',
            'age' => -1,
            'tags' => ['a', 'a'],
        ];

        // ----------------------------------------------------------------
        // perform the change

        $exported = $exporter->export($original);
        $imported = $unit->import($exported);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue(
            $original->safeParse($invalidData)->failed(),
        );
        $this->assertTrue(
            $imported->safeParse($invalidData)->failed(),
        );
    }

    // ================================================================
    //
    // Registry
    //
    // ----------------------------------------------------------------

    #[TestDox('registry register and resolve')]
    public function test_registry_register_and_resolve(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that schemas registered in the
        // registry can be resolved by name and by $ref

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaRegistry();
        $schema = Validate::string();

        // ----------------------------------------------------------------
        // perform the change

        $unit->register(name: 'MyString', schema: $schema);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($unit->has('MyString'));
        $this->assertFalse($unit->has('Missing'));

        $this->assertSame(
            $schema,
            $unit->get(name: 'MyString'),
        );
        $this->assertSame(
            $schema,
            $unit->resolveRef(ref: '#/$defs/MyString'),
        );
    }

    #[TestDox('registry throws on unresolved ref')]
    public function test_registry_throws_on_missing(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that resolving a non-existent
        // ref throws

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaRegistry();

        // ----------------------------------------------------------------
        // perform the change

        $this->expectException(
            InvalidJsonSchemaException::class,
        );
        $unit->resolveRef(ref: '#/$defs/Missing');
    }

    #[TestDox('registry throws on invalid ref format')]
    public function test_registry_throws_on_invalid_ref(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a $ref that does not start
        // with #/$defs/ throws

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaRegistry();

        // ----------------------------------------------------------------
        // perform the change

        $this->expectException(
            InvalidJsonSchemaException::class,
        );
        $unit->resolveRef(ref: '#/definitions/Foo');
    }

    // ================================================================
    //
    // Exporter Registry Support
    //
    // ----------------------------------------------------------------

    #[TestDox('exporter emits $defs and $ref with registry')]
    public function test_exporter_emits_defs_and_ref(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that when a registry is provided
        // to the exporter, registered schemas appear in
        // $defs and are referenced via $ref in the output

        // ----------------------------------------------------------------
        // setup your test

        $unit = new JsonSchemaDraft202012Exporter();
        $registry = new JsonSchemaRegistry();

        $addressSchema = Validate::object([
            'street' => Validate::string(),
        ])->strict();

        $registry->register(
            name: 'Address',
            schema: $addressSchema,
        );

        $rootSchema = Validate::object([
            'home' => $addressSchema,
            'work' => $addressSchema,
        ])->strict();

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->export(
            schema: $rootSchema,
            registry: $registry,
        )->toArray();

        // ----------------------------------------------------------------
        // test the results

        /** @var array<string, mixed> $properties */
        $properties = $result['properties'];

        // properties should reference $ref
        $this->assertEquals(
            ['$ref' => '#/$defs/Address'],
            $properties['home'],
        );
        $this->assertEquals(
            ['$ref' => '#/$defs/Address'],
            $properties['work'],
        );

        // $defs should contain the full schema
        /** @var array<string, array<string, mixed>> $defs */
        $defs = $result['$defs'];
        $this->assertArrayHasKey('Address', $defs);
        $this->assertSame('object', $defs['Address']['type']);
    }

    #[TestDox('$ref round-trips through import and export')]
    public function test_ref_round_trips(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that $ref and $defs survive a
        // full import → export round-trip. The input and
        // output should be identical (the exporter adds
        // $schema and additionalProperties: false, so
        // the expected JSON includes those).

        // ----------------------------------------------------------------
        // setup your test

        $importer = new JsonSchemaDraft202012Importer();
        $exporter = new JsonSchemaDraft202012Exporter();

        $jsonSchema = $this->jsonToSchema(<<<'JSON'
            {
                "$schema": "https://json-schema.org/draft/2020-12/schema",
                "type": "object",
                "properties": {
                    "home": {"$ref": "#/$defs/Address"},
                    "work": {"$ref": "#/$defs/Address"}
                },
                "additionalProperties": false,
                "$defs": {
                    "Address": {
                        "type": "object",
                        "properties": {
                            "street": {"type": "string"}
                        },
                        "required": ["street"],
                        "additionalProperties": false
                    }
                }
            }
            JSON);

        // ----------------------------------------------------------------
        // perform the change

        $registry = new JsonSchemaRegistry();
        $schema = $importer->import(
            jsonSchema: $jsonSchema,
            registry: $registry,
        );

        $result = $exporter->export(
            schema: $schema,
            registry: $registry,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals(
            $jsonSchema->toObject(),
            $result->toObject(),
        );
    }

    // ================================================================
    //
    // unevaluatedProperties — import + validate
    //
    // ----------------------------------------------------------------

    #[TestDox('imported allOf with unevaluatedProperties: false rejects unknown properties')]
    public function test_allof_unevaluated_properties_false_rejects(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the importer correctly
        // handles the common OpenAPI pattern: allOf with
        // unevaluatedProperties: false. Properties from
        // all allOf members are accepted, but any
        // additional property is rejected.

        // ----------------------------------------------------------------
        // setup your test

        $json = <<<'JSON'
            {
                "$schema": "https://json-schema.org/draft/2020-12/schema",
                "allOf": [
                    {
                        "type": "object",
                        "properties": {
                            "name": { "type": "string" }
                        }
                    },
                    {
                        "type": "object",
                        "properties": {
                            "age": { "type": "integer" }
                        }
                    }
                ],
                "unevaluatedProperties": false
            }
            JSON;

        $importer = new JsonSchemaDraft202012Importer();
        $schema = $importer->import(
            $this->jsonToSchema($json),
        );

        // ----------------------------------------------------------------
        // perform the change

        $result = $schema->safeParse((object) [
            'name' => 'Stuart',
            'age' => 42,
            'extra' => 'should fail',
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());

    }

    #[TestDox('imported allOf with unevaluatedProperties: false accepts all member properties')]
    public function test_allof_unevaluated_properties_false_accepts(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that properties from all allOf
        // members are accepted when
        // unevaluatedProperties: false is set.

        // ----------------------------------------------------------------
        // setup your test

        $json = <<<'JSON'
            {
                "$schema": "https://json-schema.org/draft/2020-12/schema",
                "allOf": [
                    {
                        "type": "object",
                        "properties": {
                            "name": { "type": "string" }
                        }
                    },
                    {
                        "type": "object",
                        "properties": {
                            "age": { "type": "integer" }
                        }
                    }
                ],
                "unevaluatedProperties": false
            }
            JSON;

        $importer = new JsonSchemaDraft202012Importer();
        $schema = $importer->import(
            $this->jsonToSchema($json),
        );

        // ----------------------------------------------------------------
        // perform the change

        $result = $schema->safeParse((object) [
            'name' => 'Stuart',
            'age' => 42,
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($result->failed());

    }

    #[TestDox('imported object with unevaluatedProperties: false on plain object')]
    public function test_plain_object_unevaluated_properties_false(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that unevaluatedProperties on
        // a plain object (no composition) rejects
        // unevaluated properties.

        // ----------------------------------------------------------------
        // setup your test

        $json = <<<'JSON'
            {
                "$schema": "https://json-schema.org/draft/2020-12/schema",
                "type": "object",
                "properties": {
                    "name": { "type": "string" }
                },
                "unevaluatedProperties": false
            }
            JSON;

        $importer = new JsonSchemaDraft202012Importer();
        $schema = $importer->import(
            $this->jsonToSchema($json),
        );

        // ----------------------------------------------------------------
        // perform the change

        $result = $schema->safeParse((object) [
            'name' => 'Stuart',
            'extra' => 'should fail',
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());

    }

    // ================================================================
    //
    // unevaluatedProperties — import + export round-trip
    //
    // ----------------------------------------------------------------

    #[TestDox('unevaluatedProperties: false round-trips through import and export')]
    public function test_unevaluated_properties_false_round_trip(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that unevaluatedProperties:
        // false survives an import → export round-trip.

        // ----------------------------------------------------------------
        // setup your test

        $json = <<<'JSON'
            {
                "$schema": "https://json-schema.org/draft/2020-12/schema",
                "allOf": [
                    {
                        "type": "object",
                        "properties": {
                            "name": { "type": "string" }
                        },
                        "additionalProperties": false
                    }
                ],
                "unevaluatedProperties": false
            }
            JSON;

        $importer = new JsonSchemaDraft202012Importer();
        $schema = $importer->import(
            $this->jsonToSchema($json),
        );

        $exporter = new JsonSchemaDraft202012Exporter();
        $exported = $exporter->export($schema);

        // ----------------------------------------------------------------
        // perform the change

        $encoded = json_encode($exported);
        assert(is_string($encoded));

        /** @var array<string, mixed> $result */
        $result = json_decode(
            $encoded,
            associative: true,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertArrayHasKey(
            'unevaluatedProperties',
            $result,
        );
        $this->assertFalse(
            $result['unevaluatedProperties'],
        );

    }

    // ================================================================
    //
    // unevaluatedItems — import + validate
    //
    // ----------------------------------------------------------------

    #[TestDox('imported tuple with items: false rejects extra elements')]
    public function test_tuple_items_false_rejects_extra(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that items: false on a tuple
        // is correctly imported and rejects extra elements.

        // ----------------------------------------------------------------
        // setup your test

        $json = <<<'JSON'
            {
                "$schema": "https://json-schema.org/draft/2020-12/schema",
                "type": "array",
                "prefixItems": [
                    { "type": "string" }
                ],
                "items": false
            }
            JSON;

        $importer = new JsonSchemaDraft202012Importer();
        $schema = $importer->import(
            $this->jsonToSchema($json),
        );

        // ----------------------------------------------------------------
        // perform the change

        $result = $schema->safeParse(['hello', 'extra']);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());

    }

    #[TestDox('imported tuple with items schema validates extra elements')]
    public function test_tuple_items_schema_validates_extra(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that items with a schema on a
        // tuple validates extra elements against that
        // schema.

        // ----------------------------------------------------------------
        // setup your test

        $json = <<<'JSON'
            {
                "$schema": "https://json-schema.org/draft/2020-12/schema",
                "type": "array",
                "prefixItems": [
                    { "type": "string" }
                ],
                "items": { "type": "integer" }
            }
            JSON;

        $importer = new JsonSchemaDraft202012Importer();
        $schema = $importer->import(
            $this->jsonToSchema($json),
        );

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $schema->parse(['hello', 1, 2]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            ['hello', 1, 2],
            $actualResult,
        );

    }

    // ================================================================
    //
    // Combined properties + allOf + unevaluatedProperties
    //
    // ----------------------------------------------------------------

    #[TestDox('imported schema with properties + allOf + unevaluatedProperties: false')]
    public function test_properties_plus_allof_unevaluated(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the importer correctly
        // handles a schema with both top-level properties
        // and allOf combined with unevaluatedProperties:
        // false. Properties from both the top-level and
        // allOf members are accepted.

        // ----------------------------------------------------------------
        // setup your test

        $json = <<<'JSON'
            {
                "$schema": "https://json-schema.org/draft/2020-12/schema",
                "type": "object",
                "properties": {
                    "name": { "type": "string" }
                },
                "allOf": [
                    {
                        "type": "object",
                        "properties": {
                            "age": { "type": "integer" }
                        }
                    }
                ],
                "unevaluatedProperties": false
            }
            JSON;

        $importer = new JsonSchemaDraft202012Importer();
        $schema = $importer->import(
            $this->jsonToSchema($json),
        );

        // ----------------------------------------------------------------
        // perform the change — accepts both properties

        $validResult = $schema->safeParse((object) [
            'name' => 'Stuart',
            'age' => 42,
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($validResult->failed());

    }

    #[TestDox('imported schema with properties + allOf + unevaluatedProperties: false rejects unknown')]
    public function test_properties_plus_allof_unevaluated_rejects(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that properties not in the
        // top-level properties or any allOf member are
        // rejected.

        // ----------------------------------------------------------------
        // setup your test

        $json = <<<'JSON'
            {
                "$schema": "https://json-schema.org/draft/2020-12/schema",
                "type": "object",
                "properties": {
                    "name": { "type": "string" }
                },
                "allOf": [
                    {
                        "type": "object",
                        "properties": {
                            "age": { "type": "integer" }
                        }
                    }
                ],
                "unevaluatedProperties": false
            }
            JSON;

        $importer = new JsonSchemaDraft202012Importer();
        $schema = $importer->import(
            $this->jsonToSchema($json),
        );

        // ----------------------------------------------------------------
        // perform the change

        $result = $schema->safeParse((object) [
            'name' => 'Stuart',
            'age' => 42,
            'extra' => 'should fail',
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());

    }

    // ================================================================
    //
    // Content vocabulary — round-trip as metadata
    //
    // ----------------------------------------------------------------

    #[TestDox('contentEncoding round-trips through import and export')]
    public function test_content_encoding_round_trip(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that contentEncoding survives
        // an import → export round-trip as metadata.

        // ----------------------------------------------------------------
        // setup your test

        $json = <<<'JSON'
            {
                "$schema": "https://json-schema.org/draft/2020-12/schema",
                "type": "string",
                "contentEncoding": "base64"
            }
            JSON;

        $importer = new JsonSchemaDraft202012Importer();
        $schema = $importer->import(
            $this->jsonToSchema($json),
        );

        $exporter = new JsonSchemaDraft202012Exporter();
        $exported = $exporter->export($schema);

        // ----------------------------------------------------------------
        // perform the change

        $encoded = json_encode($exported);
        assert(is_string($encoded));

        /** @var array<string, mixed> $result */
        $result = json_decode(
            $encoded,
            associative: true,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertArrayHasKey(
            'contentEncoding',
            $result,
        );
        $this->assertSame(
            'base64',
            $result['contentEncoding'],
        );

    }

    #[TestDox('contentMediaType round-trips through import and export')]
    public function test_content_media_type_round_trip(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that contentMediaType survives
        // an import → export round-trip as metadata.

        // ----------------------------------------------------------------
        // setup your test

        $json = <<<'JSON'
            {
                "$schema": "https://json-schema.org/draft/2020-12/schema",
                "type": "string",
                "contentMediaType": "image/png"
            }
            JSON;

        $importer = new JsonSchemaDraft202012Importer();
        $schema = $importer->import(
            $this->jsonToSchema($json),
        );

        $exporter = new JsonSchemaDraft202012Exporter();
        $exported = $exporter->export($schema);

        // ----------------------------------------------------------------
        // perform the change

        $encoded = json_encode($exported);
        assert(is_string($encoded));

        /** @var array<string, mixed> $result */
        $result = json_decode(
            $encoded,
            associative: true,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertArrayHasKey(
            'contentMediaType',
            $result,
        );
        $this->assertSame(
            'image/png',
            $result['contentMediaType'],
        );

    }

    #[TestDox('contentSchema round-trips through import and export')]
    public function test_content_schema_round_trip(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that contentSchema survives
        // an import → export round-trip as metadata. The
        // contentSchema value is stored as a raw stdClass
        // object, not imported as a ValidationSchema.

        // ----------------------------------------------------------------
        // setup your test

        $json = <<<'JSON'
            {
                "$schema": "https://json-schema.org/draft/2020-12/schema",
                "type": "string",
                "contentMediaType": "application/json",
                "contentSchema": {
                    "type": "object",
                    "properties": {
                        "name": { "type": "string" }
                    }
                }
            }
            JSON;

        $importer = new JsonSchemaDraft202012Importer();
        $schema = $importer->import(
            $this->jsonToSchema($json),
        );

        $exporter = new JsonSchemaDraft202012Exporter();
        $exported = $exporter->export($schema);

        // ----------------------------------------------------------------
        // perform the change

        $encoded = json_encode($exported);
        assert(is_string($encoded));

        /** @var array<string, mixed> $result */
        $result = json_decode(
            $encoded,
            associative: true,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertArrayHasKey(
            'contentMediaType',
            $result,
        );
        $this->assertSame(
            'application/json',
            $result['contentMediaType'],
        );

        $this->assertArrayHasKey(
            'contentSchema',
            $result,
        );
        /** @var array<string, mixed> $contentSchema */
        $contentSchema = $result['contentSchema'];
        $this->assertSame(
            'object',
            $contentSchema['type'],
        );

    }

    #[TestDox('content vocabulary keywords coexist with $comment metadata')]
    public function test_content_keywords_coexist_with_comment(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that content vocabulary keywords
        // and $comment can coexist on the same schema
        // without overwriting each other in metadata.

        // ----------------------------------------------------------------
        // setup your test

        $json = <<<'JSON'
            {
                "$schema": "https://json-schema.org/draft/2020-12/schema",
                "type": "string",
                "$comment": "base64 encoded PNG",
                "contentEncoding": "base64",
                "contentMediaType": "image/png"
            }
            JSON;

        $importer = new JsonSchemaDraft202012Importer();
        $schema = $importer->import(
            $this->jsonToSchema($json),
        );

        $exporter = new JsonSchemaDraft202012Exporter();
        $exported = $exporter->export($schema);

        // ----------------------------------------------------------------
        // perform the change

        $encoded = json_encode($exported);
        assert(is_string($encoded));

        /** @var array<string, mixed> $result */
        $result = json_decode(
            $encoded,
            associative: true,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            'base64 encoded PNG',
            $result['$comment'],
        );
        $this->assertSame(
            'base64',
            $result['contentEncoding'],
        );
        $this->assertSame(
            'image/png',
            $result['contentMediaType'],
        );

    }

    // ================================================================
    //
    // $id — schema identification
    //
    // ----------------------------------------------------------------

    #[TestDox('$id on root schema is used as base URI for $ref resolution')]
    public function test_id_on_root_resolves_defs_ref(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that when the root schema has
        // an $id, the importer uses it as the base URI.
        // A $ref to #/$defs/Name still resolves correctly
        // because $defs registration is independent of
        // $id.

        // ----------------------------------------------------------------
        // setup your test

        $json = <<<'JSON'
            {
                "$schema": "https://json-schema.org/draft/2020-12/schema",
                "$id": "https://example.com/schemas/person",
                "type": "object",
                "properties": {
                    "name": { "$ref": "#/$defs/Name" }
                },
                "$defs": {
                    "Name": {
                        "type": "string",
                        "minLength": 1
                    }
                }
            }
            JSON;

        $importer = new JsonSchemaDraft202012Importer();
        $schema = $importer->import(
            $this->jsonToSchema($json),
        );

        // ----------------------------------------------------------------
        // perform the change

        $result = $schema->safeParse((object) [
            'name' => 'Stuart',
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($result->failed());

    }

    #[TestDox('$id on root schema rejects empty name via $defs ref')]
    public function test_id_on_root_rejects_via_defs_ref(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that validation constraints
        // from a $defs schema are applied when the root
        // schema has an $id. The Name def requires
        // minLength: 1, so an empty string should fail.

        // ----------------------------------------------------------------
        // setup your test

        $json = <<<'JSON'
            {
                "$schema": "https://json-schema.org/draft/2020-12/schema",
                "$id": "https://example.com/schemas/person",
                "type": "object",
                "properties": {
                    "name": { "$ref": "#/$defs/Name" }
                },
                "$defs": {
                    "Name": {
                        "type": "string",
                        "minLength": 1
                    }
                }
            }
            JSON;

        $importer = new JsonSchemaDraft202012Importer();
        $schema = $importer->import(
            $this->jsonToSchema($json),
        );

        // ----------------------------------------------------------------
        // perform the change

        $result = $schema->safeParse((object) [
            'name' => '',
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());

        $this->assertSame(
            [
                [
                    'type'    => 'https://stusdevkit.dev/errors/validation/too_small',
                    'path'    => ['name'],
                    'message' => 'String must be at least 1'
                        . ' characters',
                ],
            ],
            $result->error()->issues()->jsonSerialize(),
        );

    }

    // ================================================================
    //
    // $anchor — named anchors
    //
    // ----------------------------------------------------------------

    #[TestDox('$anchor can be referenced via fragment-only $ref')]
    public function test_anchor_ref_resolves(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a schema with $anchor can
        // be referenced via a plain-name fragment $ref
        // (e.g. #name-def) instead of the JSON Pointer
        // format (#/$defs/Name).

        // ----------------------------------------------------------------
        // setup your test

        $json = <<<'JSON'
            {
                "$schema": "https://json-schema.org/draft/2020-12/schema",
                "$id": "https://example.com/schemas/person",
                "type": "object",
                "properties": {
                    "name": { "$ref": "#name-def" }
                },
                "$defs": {
                    "Name": {
                        "$anchor": "name-def",
                        "type": "string",
                        "minLength": 1
                    }
                }
            }
            JSON;

        $importer = new JsonSchemaDraft202012Importer();
        $schema = $importer->import(
            $this->jsonToSchema($json),
        );

        // ----------------------------------------------------------------
        // perform the change

        $validResult = $schema->safeParse((object) [
            'name' => 'Stuart',
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($validResult->failed());

    }

    #[TestDox('$anchor ref rejects invalid data')]
    public function test_anchor_ref_rejects_invalid(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that validation constraints
        // from an anchor-referenced schema are applied.

        // ----------------------------------------------------------------
        // setup your test

        $json = <<<'JSON'
            {
                "$schema": "https://json-schema.org/draft/2020-12/schema",
                "$id": "https://example.com/schemas/person",
                "type": "object",
                "properties": {
                    "name": { "$ref": "#name-def" }
                },
                "$defs": {
                    "Name": {
                        "$anchor": "name-def",
                        "type": "string",
                        "minLength": 1
                    }
                }
            }
            JSON;

        $importer = new JsonSchemaDraft202012Importer();
        $schema = $importer->import(
            $this->jsonToSchema($json),
        );

        // ----------------------------------------------------------------
        // perform the change

        $result = $schema->safeParse((object) [
            'name' => '',
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());

        $this->assertSame(
            [
                [
                    'type'    => 'https://stusdevkit.dev/errors/validation/too_small',
                    'path'    => ['name'],
                    'message' => 'String must be at least 1'
                        . ' characters',
                ],
            ],
            $result->error()->issues()->jsonSerialize(),
        );

    }

    // ================================================================
    //
    // $id — round-trip export
    //
    // ----------------------------------------------------------------

    #[TestDox('$id on root schema round-trips through import and export')]
    public function test_id_on_root_round_trips(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the root schema's $id
        // survives an import → export round-trip

        // ----------------------------------------------------------------
        // setup your test

        $json = <<<'JSON'
            {
                "$schema": "https://json-schema.org/draft/2020-12/schema",
                "$id": "https://example.com/schemas/person",
                "type": "string"
            }
            JSON;

        $importer = new JsonSchemaDraft202012Importer();
        $schema = $importer->import(
            $this->jsonToSchema($json),
        );

        $exporter = new JsonSchemaDraft202012Exporter();
        $exported = $exporter->export($schema);

        // ----------------------------------------------------------------
        // perform the change

        $result = $exported->toObject();

        // ----------------------------------------------------------------
        // test the results

        $this->assertObjectHasProperty(
            '$id',
            $result,
        );
        $this->assertSame(
            'https://example.com/schemas/person',
            $result->{'$id'},
        );

    }

    #[TestDox('schema without $id does not emit $id on export')]
    public function test_no_id_does_not_emit_id(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that when a schema has no $id,
        // the exported JSON Schema does not include an
        // $id keyword

        // ----------------------------------------------------------------
        // setup your test

        $json = <<<'JSON'
            {
                "$schema": "https://json-schema.org/draft/2020-12/schema",
                "type": "string"
            }
            JSON;

        $importer = new JsonSchemaDraft202012Importer();
        $schema = $importer->import(
            $this->jsonToSchema($json),
        );

        $exporter = new JsonSchemaDraft202012Exporter();
        $exported = $exporter->export($schema);

        // ----------------------------------------------------------------
        // perform the change

        $result = $exported->toObject();

        // ----------------------------------------------------------------
        // test the results

        $this->assertObjectNotHasProperty(
            '$id',
            $result,
        );

    }
}
