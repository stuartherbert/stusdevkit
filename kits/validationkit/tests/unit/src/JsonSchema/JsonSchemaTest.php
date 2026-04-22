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

use JsonSerializable;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use stdClass;
use StusDevKit\ValidationKit\JsonSchema\JsonSchema;

use const JSON_PRETTY_PRINT;

#[TestDox(JsonSchema::class)]
class JsonSchemaTest extends TestCase
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

        $actual = (new ReflectionClass(JsonSchema::class))
            ->getNamespaceName();

        $this->assertSame($expected, $actual);
    }

    #[TestDox('is a final class')]
    public function test_is_a_final_class(): void
    {
        // JsonSchema is a value object - the importer and exporter
        // both depend on its construction semantics being stable.
        // Pin `final` so a well-meaning subclass cannot override
        // jsonSerialize() and silently reshape the wire format.

        $reflection = new ReflectionClass(JsonSchema::class);

        $this->assertTrue($reflection->isFinal());
    }

    #[TestDox('implements JsonSerializable')]
    public function test_implements_json_serializable(): void
    {
        // the class earns its keep by plugging into json_encode()
        // via JsonSerializable. Losing that interface would quietly
        // turn the wire format back into a generic stdClass dump.

        $reflection = new ReflectionClass(JsonSchema::class);

        $this->assertTrue(
            $reflection->implementsInterface(JsonSerializable::class),
        );
    }

    // ================================================================
    //
    // Published method set
    //
    // ----------------------------------------------------------------

    #[TestDox('exposes only the expected public methods')]
    public function test_exposes_only_the_expected_public_methods(): void
    {
        // the class exists to wrap a stdClass document. Pin the
        // method set by enumeration - any addition fails with a
        // diff that names the new method.

        $expected = [
            '__construct',
            'jsonSerialize',
            'toArray',
            'toObject',
        ];
        $reflection = new ReflectionClass(JsonSchema::class);

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

    #[TestDox('::__construct() declares $schema as its sole parameter')]
    public function test_constructor_declares_expected_parameters(): void
    {
        // pin the name AND position of the constructor parameter so
        // that named-argument callers (and round-trippers that pass
        // a decoded stdClass in) keep working.

        $expected = ['schema'];
        $method = (new ReflectionClass(JsonSchema::class))
            ->getMethod('__construct');

        $actual = array_map(
            static fn ($parameter) => $parameter->getName(),
            $method->getParameters(),
        );

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::__construct() allows the $schema parameter to be omitted')]
    public function test_constructor_parameter_is_optional(): void
    {
        // callers use `new JsonSchema()` for the empty-schema case
        // (emits `{}`). If $schema ever becomes required, that
        // pattern breaks - pin the default so the regression fails
        // here rather than in a downstream test.

        $method = (new ReflectionClass(JsonSchema::class))
            ->getMethod('__construct');
        $parameter = $method->getParameters()[0];

        $this->assertTrue($parameter->isOptional());
    }

    #[TestDox('->jsonSerialize() declares no parameters')]
    public function test_jsonSerialize_declares_no_parameters(): void
    {
        $method = (new ReflectionClass(JsonSchema::class))
            ->getMethod('jsonSerialize');

        $actual = array_map(
            static fn ($parameter) => $parameter->getName(),
            $method->getParameters(),
        );

        $this->assertSame([], $actual);
    }

    #[TestDox('->toObject() declares no parameters')]
    public function test_toObject_declares_no_parameters(): void
    {
        $method = (new ReflectionClass(JsonSchema::class))
            ->getMethod('toObject');

        $actual = array_map(
            static fn ($parameter) => $parameter->getName(),
            $method->getParameters(),
        );

        $this->assertSame([], $actual);
    }

    #[TestDox('->toArray() declares no parameters')]
    public function test_toArray_declares_no_parameters(): void
    {
        $method = (new ReflectionClass(JsonSchema::class))
            ->getMethod('toArray');

        $actual = array_map(
            static fn ($parameter) => $parameter->getName(),
            $method->getParameters(),
        );

        $this->assertSame([], $actual);
    }

    // ================================================================
    //
    // Construction
    //
    // ----------------------------------------------------------------

    /**
     * the default constructor must produce an empty schema that
     * serialises to a JSON object, not an array - that's the whole
     * reason stdClass is the internal representation.
     */
    #[TestDox('::__construct() with no arguments produces an empty schema')]
    public function test_default_constructor_produces_empty_schema(): void
    {
        $unit = new JsonSchema();

        $this->assertSame('{}', json_encode($unit));
    }

    /**
     * passing a stdClass in must round-trip through jsonSerialize()
     * and json_encode() without reshaping - the importer relies on
     * this to carry decoded schema documents through unchanged.
     */
    #[TestDox('::__construct() wraps a supplied stdClass document')]
    public function test_constructor_wraps_a_supplied_stdclass(): void
    {
        $document = new stdClass();
        $document->type = 'string';
        $document->minLength = 1;

        $unit = new JsonSchema($document);

        $this->assertSame(
            '{"type":"string","minLength":1}',
            json_encode($unit),
        );
    }

    /**
     * passing null explicitly must behave the same as the zero-arg
     * default - both are documented code paths in the class
     * docblock.
     */
    #[TestDox('::__construct() treats an explicit null the same as no argument')]
    public function test_constructor_accepts_explicit_null(): void
    {
        $unit = new JsonSchema(null);

        $this->assertSame('{}', json_encode($unit));
    }

    // ================================================================
    //
    // jsonSerialize()
    //
    // ----------------------------------------------------------------

    /**
     * jsonSerialize() must return the underlying stdClass by
     * reference-equivalence so that json_encode() sees a PHP object
     * and emits a JSON object (`{}`) rather than a JSON array
     * (`[]`). This is the bug the class exists to prevent.
     */
    #[TestDox('->jsonSerialize() returns a stdClass for an empty schema')]
    public function test_jsonSerialize_returns_stdclass_for_empty_schema(): void
    {
        $unit = new JsonSchema();

        $actual = $unit->jsonSerialize();

        $this->assertInstanceOf(stdClass::class, $actual);
    }

    /**
     * empty schema must encode as the JSON object `{}`, not `[]`.
     * This is the high-stakes behaviour pin for the class.
     */
    #[TestDox('->jsonSerialize() encodes empty schemas as {} via json_encode()')]
    public function test_empty_schema_encodes_as_object(): void
    {
        $unit = new JsonSchema();

        $this->assertSame('{}', json_encode($unit));
    }

    /**
     * json_encode() with JSON_PRETTY_PRINT must still produce a
     * valid JSON object for a non-empty schema. Pin the behaviour
     * because callers write schemas to disk for human review.
     */
    #[TestDox('->jsonSerialize() cooperates with JSON_PRETTY_PRINT')]
    public function test_jsonSerialize_with_pretty_print(): void
    {
        $document = new stdClass();
        $document->type = 'string';

        $unit = new JsonSchema($document);

        $expected = "{\n    \"type\": \"string\"\n}";

        $this->assertSame(
            $expected,
            json_encode($unit, JSON_PRETTY_PRINT),
        );
    }

    // ================================================================
    //
    // toObject()
    //
    // ----------------------------------------------------------------

    /**
     * toObject() must return a stdClass so callers can walk the
     * schema with the same key access the importer uses
     * (`$obj->type`, `$obj->{'$ref'}` etc).
     */
    #[TestDox('->toObject() returns a stdClass')]
    public function test_toObject_returns_stdclass(): void
    {
        $document = new stdClass();
        $document->type = 'string';

        $unit = new JsonSchema($document);

        $this->assertInstanceOf(stdClass::class, $unit->toObject());
    }

    /**
     * toObject() must return a deep clone so a caller cannot reach
     * back into the JsonSchema and mutate its internal state. The
     * class is a value object; leaking a shared reference would
     * break that contract.
     */
    #[TestDox('->toObject() returns a deep clone that does not alias the internal state')]
    public function test_toObject_returns_a_deep_clone(): void
    {
        $document = new stdClass();
        $document->type = 'string';

        $unit = new JsonSchema($document);

        $copy = $unit->toObject();
        $copy->type = 'mutated';

        // the original schema must remain untouched
        $this->assertSame(
            '{"type":"string"}',
            json_encode($unit),
        );
    }

    /**
     * toObject() on the empty schema must still return a stdClass,
     * not null or an empty array - callers expect a uniform type.
     */
    #[TestDox('->toObject() returns a stdClass for an empty schema')]
    public function test_toObject_empty_schema(): void
    {
        $unit = new JsonSchema();

        $actual = $unit->toObject();

        $this->assertInstanceOf(stdClass::class, $actual);
        $this->assertSame('{}', json_encode($actual));
    }

    // ================================================================
    //
    // toArray()
    //
    // ----------------------------------------------------------------

    /**
     * toArray() must return an associative array for a non-empty
     * schema so callers can use `array_key_exists()` and
     * array-style dispatch without converting.
     */
    #[TestDox('->toArray() returns an associative array for a non-empty schema')]
    public function test_toArray_returns_associative_array(): void
    {
        $document = new stdClass();
        $document->type = 'string';
        $document->minLength = 1;

        $unit = new JsonSchema($document);

        $expected = [
            'type'      => 'string',
            'minLength' => 1,
        ];

        $this->assertSame($expected, $unit->toArray());
    }

    /**
     * toArray() on the empty schema must return an empty array,
     * not a representation that re-encodes as `[]` versus `{}` in
     * downstream JSON. Callers use array_key_exists() to test for
     * keyword presence, so the shape matters.
     */
    #[TestDox('->toArray() returns an empty array for an empty schema')]
    public function test_toArray_empty_schema(): void
    {
        $unit = new JsonSchema();

        $this->assertSame([], $unit->toArray());
    }

    /**
     * nested objects must be converted to nested associative
     * arrays, not left as stdClass - toArray() is the full-depth
     * conversion.
     */
    #[TestDox('->toArray() converts nested stdClass values to nested arrays')]
    public function test_toArray_converts_nested_objects(): void
    {
        $inner = new stdClass();
        $inner->type = 'string';

        $document = new stdClass();
        $document->properties = new stdClass();
        $document->properties->name = $inner;

        $unit = new JsonSchema($document);

        $expected = [
            'properties' => [
                'name' => [
                    'type' => 'string',
                ],
            ],
        ];

        $this->assertSame($expected, $unit->toArray());
    }
}
