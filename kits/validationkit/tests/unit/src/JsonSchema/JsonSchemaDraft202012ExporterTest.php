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
use StusDevKit\ValidationKit\JsonSchema\JsonSchema;
use StusDevKit\ValidationKit\JsonSchema\JsonSchemaDraft202012Exporter;
use StusDevKit\ValidationKit\JsonSchema\JsonSchemaRegistry;
use StusDevKit\ValidationKit\Validate;

#[TestDox(JsonSchemaDraft202012Exporter::class)]
class JsonSchemaDraft202012ExporterTest extends TestCase
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
            JsonSchemaDraft202012Exporter::class,
        ))->getNamespaceName();

        $this->assertSame($expected, $actual);
    }

    #[TestDox('is a concrete class')]
    public function test_is_a_concrete_class(): void
    {
        // callers `new JsonSchemaDraft202012Exporter()` to convert
        // a schema. Making the class abstract, an interface, or a
        // trait would break every call site.

        $reflection = new ReflectionClass(
            JsonSchemaDraft202012Exporter::class,
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

    #[TestDox('exposes only export() as a public method')]
    public function test_exposes_only_export_as_public_method(): void
    {
        // the class has a single job - convert a schema. Pin the
        // public method set by enumeration so a silently added
        // helper shows up here rather than creeping into the
        // public surface.

        $expected = ['export'];
        $reflection = new ReflectionClass(
            JsonSchemaDraft202012Exporter::class,
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

    #[TestDox('->export() declares $schema and $registry as parameters in that order')]
    public function test_export_declares_expected_parameters(): void
    {
        // callers use named arguments for multi-parameter calls,
        // so pin names and order. $registry is optional - pin that
        // separately.

        $expected = ['schema', 'registry'];
        $method = (new ReflectionClass(
            JsonSchemaDraft202012Exporter::class,
        ))->getMethod('export');

        $actual = array_map(
            static fn ($parameter) => $parameter->getName(),
            $method->getParameters(),
        );

        $this->assertSame($expected, $actual);
    }

    #[TestDox('->export() marks $registry as optional')]
    public function test_export_registry_is_optional(): void
    {
        // most exporter callers do not carry a registry, so
        // $registry must stay optional. If a future refactor makes
        // it required, every simple call site breaks - catch that
        // here.

        $method = (new ReflectionClass(
            JsonSchemaDraft202012Exporter::class,
        ))->getMethod('export');
        $parameters = $method->getParameters();

        $this->assertTrue($parameters[1]->isOptional());
    }

    // ================================================================
    //
    // $schema keyword
    //
    // ----------------------------------------------------------------

    /**
     * every exported document must carry the `$schema` keyword
     * identifying Draft 2020-12 - JSON Schema validators rely on
     * this identifier to select the correct dialect.
     */
    #[TestDox('->export() emits the Draft 2020-12 $schema identifier')]
    public function test_exports_draft_2020_12_schema_identifier(): void
    {
        $unit = new JsonSchemaDraft202012Exporter();

        $result = $unit->export(schema: Validate::string());

        $this->assertInstanceOf(JsonSchema::class, $result);
        $this->assertSame(
            'https://json-schema.org/draft/2020-12/schema',
            $result->toArray()['$schema'],
        );
    }

    // ================================================================
    //
    // Primitive types
    //
    // ----------------------------------------------------------------

    /**
     * StringSchema exports to `type: string`. Pin the minimal
     * happy path so the dispatcher table stays honest.
     */
    #[TestDox('->export() converts StringSchema to type: string')]
    public function test_exports_string_schema(): void
    {
        $unit = new JsonSchemaDraft202012Exporter();

        $actual = $unit->export(schema: Validate::string())->toArray();

        $this->assertSame('string', $actual['type']);
    }

    #[TestDox('->export() converts IntSchema to type: integer')]
    public function test_exports_int_schema(): void
    {
        $unit = new JsonSchemaDraft202012Exporter();

        $actual = $unit->export(schema: Validate::int())->toArray();

        $this->assertSame('integer', $actual['type']);
    }

    #[TestDox('->export() converts FloatSchema to type: number')]
    public function test_exports_float_schema(): void
    {
        $unit = new JsonSchemaDraft202012Exporter();

        $actual = $unit->export(schema: Validate::float())->toArray();

        $this->assertSame('number', $actual['type']);
    }

    #[TestDox('->export() converts BooleanSchema to type: boolean')]
    public function test_exports_boolean_schema(): void
    {
        $unit = new JsonSchemaDraft202012Exporter();

        $actual = $unit->export(schema: Validate::boolean())->toArray();

        $this->assertSame('boolean', $actual['type']);
    }

    #[TestDox('->export() converts NullSchema to type: null')]
    public function test_exports_null_schema(): void
    {
        $unit = new JsonSchemaDraft202012Exporter();

        $actual = $unit->export(schema: Validate::null())->toArray();

        $this->assertSame('null', $actual['type']);
    }

    /**
     * MixedSchema is the "true schema" - anything goes. It exports
     * as just the `$schema` keyword with no `type`, which JSON
     * Schema validators treat as "permit everything".
     */
    #[TestDox('->export() converts MixedSchema to an empty schema body')]
    public function test_exports_mixed_schema(): void
    {
        $unit = new JsonSchemaDraft202012Exporter();

        $actual = $unit->export(schema: Validate::mixed())->toArray();

        $this->assertArrayNotHasKey('type', $actual);
    }

    // ================================================================
    //
    // String constraints
    //
    // ----------------------------------------------------------------

    /**
     * min/max length on a string schema must emit the JSON Schema
     * `minLength` / `maxLength` keywords - the standard names, not
     * the ValidationKit builder names. Pin both together so a
     * rename on either side is caught.
     */
    #[TestDox('->export() maps string min(length:) and max(length:) to minLength and maxLength')]
    public function test_exports_string_length_constraints(): void
    {
        $unit = new JsonSchemaDraft202012Exporter();
        $schema = Validate::string()->min(length: 1)->max(length: 10);

        $actual = $unit->export(schema: $schema)->toArray();

        $this->assertSame(1, $actual['minLength']);
        $this->assertSame(10, $actual['maxLength']);
    }

    #[TestDox('->export() maps string email() to format: email')]
    public function test_exports_string_email_format(): void
    {
        $unit = new JsonSchemaDraft202012Exporter();
        $schema = Validate::string()->email();

        $actual = $unit->export(schema: $schema)->toArray();

        $this->assertSame('email', $actual['format']);
    }

    #[TestDox('->export() maps string uuid() to format: uuid')]
    public function test_exports_string_uuid_format(): void
    {
        $unit = new JsonSchemaDraft202012Exporter();
        $schema = Validate::string()->uuid();

        $actual = $unit->export(schema: $schema)->toArray();

        $this->assertSame('uuid', $actual['format']);
    }

    /**
     * PCRE regex patterns carry `/.../` delimiters which are not
     * part of the ECMA 262 pattern syntax used by JSON Schema. The
     * exporter must strip them before emitting `pattern`, or the
     * output is not consumable by a compliant validator.
     */
    #[TestDox('->export() strips PCRE delimiters from string regex patterns')]
    public function test_exports_string_regex_strips_pcre_delimiters(): void
    {
        $unit = new JsonSchemaDraft202012Exporter();
        $schema = Validate::string()->regex(pattern: '/^[a-z]+$/');

        $actual = $unit->export(schema: $schema)->toArray();

        $this->assertSame('^[a-z]+$', $actual['pattern']);
    }

    // ================================================================
    //
    // Numeric constraints
    //
    // ----------------------------------------------------------------

    #[TestDox('->export() maps int gte(value:) to minimum')]
    public function test_exports_int_gte_to_minimum(): void
    {
        $unit = new JsonSchemaDraft202012Exporter();
        $schema = Validate::int()->gte(value: 0);

        $actual = $unit->export(schema: $schema)->toArray();

        $this->assertSame(0, $actual['minimum']);
    }

    #[TestDox('->export() maps int lte(value:) to maximum')]
    public function test_exports_int_lte_to_maximum(): void
    {
        $unit = new JsonSchemaDraft202012Exporter();
        $schema = Validate::int()->lte(value: 100);

        $actual = $unit->export(schema: $schema)->toArray();

        $this->assertSame(100, $actual['maximum']);
    }

    #[TestDox('->export() maps int gt(value:) to exclusiveMinimum')]
    public function test_exports_int_gt_to_exclusive_minimum(): void
    {
        $unit = new JsonSchemaDraft202012Exporter();
        $schema = Validate::int()->gt(value: 0);

        $actual = $unit->export(schema: $schema)->toArray();

        $this->assertSame(0, $actual['exclusiveMinimum']);
    }

    // ================================================================
    //
    // Array schemas
    //
    // ----------------------------------------------------------------

    /**
     * arrays export with `type: array` and an `items` subschema -
     * both are required by the JSON Schema array vocabulary.
     */
    #[TestDox('->export() emits type: array with items for ArraySchema')]
    public function test_exports_array_schema(): void
    {
        $unit = new JsonSchemaDraft202012Exporter();
        $schema = Validate::array(element: Validate::string());

        $actual = $unit->export(schema: $schema)->toArray();

        $this->assertSame('array', $actual['type']);
        $this->assertIsArray($actual['items']);
        $this->assertSame('string', $actual['items']['type']);
    }

    // ================================================================
    //
    // Object schemas
    //
    // ----------------------------------------------------------------

    /**
     * object export must emit `type: object`, the `properties` map
     * and the `required` list derived from which fields are not
     * wrapped in optional()/nullish(). Pin all three together so
     * the shape reader in the exporter stays honest.
     */
    #[TestDox('->export() emits type, properties and required for an object schema')]
    public function test_exports_object_schema(): void
    {
        $unit = new JsonSchemaDraft202012Exporter();
        $schema = Validate::object(shape: [
            'name' => Validate::string(),
            'age'  => Validate::optional(schema: Validate::int()),
        ]);

        $actual = $unit->export(schema: $schema)->toArray();

        $this->assertSame('object', $actual['type']);
        $this->assertIsArray($actual['properties']);
        $this->assertArrayHasKey('name', $actual['properties']);
        $this->assertArrayHasKey('age', $actual['properties']);
        $this->assertSame(['name'], $actual['required']);
    }

    // ================================================================
    //
    // Composition
    //
    // ----------------------------------------------------------------

    /**
     * anyOf collapses its branch list into JSON Schema's `anyOf`
     * keyword. Pin the branch count and each branch's `type` so
     * reordering or dropping a branch fails loudly.
     */
    #[TestDox('->export() emits anyOf for Validate::anyOf() with each branch preserved')]
    public function test_exports_any_of_composition(): void
    {
        $unit = new JsonSchemaDraft202012Exporter();
        $schema = Validate::anyOf(schemas: [
            Validate::string(),
            Validate::int(),
        ]);

        $actual = $unit->export(schema: $schema)->toArray();

        $this->assertIsArray($actual['anyOf']);
        $this->assertCount(2, $actual['anyOf']);
        $this->assertIsArray($actual['anyOf'][0]);
        $this->assertIsArray($actual['anyOf'][1]);
        $this->assertSame('string', $actual['anyOf'][0]['type']);
        $this->assertSame('integer', $actual['anyOf'][1]['type']);
    }

    // ================================================================
    //
    // Registry support ($defs / $ref)
    //
    // ----------------------------------------------------------------

    /**
     * when a registry carries schemas, they must appear under the
     * `$defs` keyword of the output. This is the other half of the
     * round-trip with the importer - a caller can export, load,
     * re-import, and see the same $defs entries on both sides.
     */
    #[TestDox('->export() emits $defs for registered schemas when a registry is supplied')]
    public function test_exports_defs_from_registry(): void
    {
        $unit = new JsonSchemaDraft202012Exporter();
        $nameSchema = Validate::string();

        $registry = new JsonSchemaRegistry();
        $registry->register(name: 'Name', schema: $nameSchema);

        $rootSchema = Validate::object(shape: [
            'name' => $nameSchema,
        ]);

        $actual = $unit->export(
            schema: $rootSchema,
            registry: $registry,
        )->toArray();

        $this->assertArrayHasKey('$defs', $actual);
        $this->assertIsArray($actual['$defs']);
        $this->assertArrayHasKey('Name', $actual['$defs']);
        $this->assertIsArray($actual['$defs']['Name']);
        $this->assertSame('string', $actual['$defs']['Name']['type']);
    }

    /**
     * when the same schema instance is registered AND referenced
     * inline, the inline reference must be emitted as `$ref`, not
     * a duplicated inline copy. This is the exporter's dedup
     * guarantee: object identity => `$ref` on the consumer side.
     *
     * Pairs with the importer's inverse behaviour.
     */
    #[TestDox('->export() emits $ref instead of inlining a registered schema reached by identity')]
    public function test_exports_ref_for_registered_schema_identity(): void
    {
        $unit = new JsonSchemaDraft202012Exporter();
        $nameSchema = Validate::string();

        $registry = new JsonSchemaRegistry();
        $registry->register(name: 'Name', schema: $nameSchema);

        $rootSchema = Validate::object(shape: [
            'name' => $nameSchema,
        ]);

        $actual = $unit->export(
            schema: $rootSchema,
            registry: $registry,
        )->toArray();

        $this->assertIsArray($actual['properties']);
        $this->assertIsArray($actual['properties']['name']);
        $this->assertSame(
            '#/$defs/Name',
            $actual['properties']['name']['$ref'],
        );
    }

    /**
     * an empty registry must not emit a `$defs` key. The key is
     * reserved for when schemas are actually registered - an empty
     * stanza would be noise.
     */
    #[TestDox('->export() omits $defs when the registry is empty')]
    public function test_exports_no_defs_for_empty_registry(): void
    {
        $unit = new JsonSchemaDraft202012Exporter();
        $registry = new JsonSchemaRegistry();

        $actual = $unit->export(
            schema: Validate::string(),
            registry: $registry,
        )->toArray();

        $this->assertArrayNotHasKey('$defs', $actual);
    }

    // ================================================================
    //
    // Metadata
    //
    // ----------------------------------------------------------------

    /**
     * description() on a schema becomes the JSON Schema
     * `description` keyword. Pin the direct mapping so a refactor
     * that goes through a different annotation path stays honest.
     */
    #[TestDox('->export() preserves description() as the description keyword')]
    public function test_exports_description_metadata(): void
    {
        $unit = new JsonSchemaDraft202012Exporter();
        $schema = Validate::string()->withDescription(
            text: 'a human-readable name',
        );

        $actual = $unit->export(schema: $schema)->toArray();

        $this->assertSame(
            'a human-readable name',
            $actual['description'],
        );
    }
}
