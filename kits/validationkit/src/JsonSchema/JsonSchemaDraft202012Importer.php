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

namespace StusDevKit\ValidationKit\JsonSchema;

use stdClass;
use StusDevKit\ValidationKit\Contracts\ValidationSchema;
use StusDevKit\ValidationKit\Exceptions\InvalidJsonSchemaException;
use StusDevKit\ValidationKit\Schemas\Builtins\ArraySchema;
use StusDevKit\ValidationKit\Schemas\Builtins\IntSchema;
use StusDevKit\ValidationKit\Schemas\Builtins\NumberSchema;
use StusDevKit\ValidationKit\Schemas\Builtins\ObjectSchema;
use StusDevKit\ValidationKit\Schemas\Builtins\StringSchema;
use StusDevKit\ValidationKit\Schemas\LazySchema;
use StusDevKit\ValidationKit\Validate;

/**
 * JsonSchemaDraft202012Importer converts a JSON Schema
 * Draft 2020-12 document into a ValidationKit schema.
 *
 * The importer reads JSON Schema keywords and maps them
 * to the corresponding ValidationKit schemas, constraints,
 * and metadata.
 *
 * Usage:
 *
 *     use StusDevKit\ValidationKit\JsonSchema\JsonSchema;
 *     use StusDevKit\ValidationKit\JsonSchema\JsonSchemaDraft202012Importer;
 *
 *     $json = '{"type":"string","minLength":1}';
 *     $jsonSchema = new JsonSchema(
 *         json_decode($json),
 *     );
 *
 *     $importer = new JsonSchemaDraft202012Importer();
 *     $schema = $importer->import($jsonSchema);
 *
 *     $schema->parse('hello'); // 'hello'
 *     $schema->parse('');      // throws
 *
 * Limitations:
 *
 * - Only `#/$defs/<name>` format `$ref` values are
 *   supported. External references and other JSON
 *   Pointer forms are not.
 * - `format: date-time` maps to the `dateTime()` builder
 *   method on StringSchema.
 * - `$id`, `$anchor`, `$vocabulary`, `$dynamicRef`,
 *   `$dynamicAnchor` are not supported.
 * - `unevaluatedProperties`, `unevaluatedItems`,
 *   `contentEncoding`, `contentMediaType` are stored
 *   as metadata.
 */
class JsonSchemaDraft202012Importer
{
    /**
     * string format values that map to builder methods
     *
     * @var array<string, string>
     */
    private const FORMAT_METHODS = [
        'email'                 => 'email',
        'uri'                   => 'url',
        'uuid'                  => 'uuid',
        'ipv4'                  => 'ipv4',
        'ipv6'                  => 'ipv6',
        'date'                  => 'date',
        'date-time'             => 'dateTime',
        'time'                  => 'time',
        'duration'              => 'duration',
        'hostname'              => 'hostname',
        'uri-reference'         => 'uriReference',
        'idn-email'             => 'idnEmail',
        'idn-hostname'          => 'idnHostname',
        'iri'                   => 'iri',
        'iri-reference'         => 'iriReference',
        'uri-template'          => 'uriTemplate',
        'json-pointer'          => 'jsonPointer',
        'relative-json-pointer' => 'relativeJsonPointer',
        'regex'                 => 'isRegex',
    ];

    // ================================================================
    //
    // Public API
    //
    // ----------------------------------------------------------------

    /**
     * import a JSON Schema Draft 2020-12 document as a
     * ValidationKit schema
     *
     * The `$schema` keyword is validated if present. The
     * `$defs` section is registered in a schema registry
     * to support `$ref` resolution.
     *
     * @return ValidationSchema<mixed>
     */
    public function import(JsonSchema $jsonSchema): ValidationSchema
    {
        $root = $jsonSchema->toObject();
        $registry = new JsonSchemaRegistry();

        // register $defs for $ref resolution
        if (
            isset($root->{'$defs'})
            && $root->{'$defs'} instanceof stdClass
        ) {
            $this->registerDefs(
                defs: $root->{'$defs'},
                registry: $registry,
            );
        }

        return $this->importSchema(
            schema: $root,
            registry: $registry,
        );
    }

    // ================================================================
    //
    // $defs Registration
    //
    // ----------------------------------------------------------------

    /**
     * register all $defs entries in the registry
     *
     * Uses a two-pass approach to support recursive
     * definitions:
     *
     * 1. Pre-register every name as a LazySchema that
     *    captures the resolved schema by reference.
     * 2. Import each definition body. Any `$ref` inside
     *    a definition resolves to the LazySchema from
     *    pass 1, which defers resolution until
     *    validation time.
     */
    private function registerDefs(
        stdClass $defs,
        JsonSchemaRegistry $registry,
    ): void {
        /** @var array<string, ValidationSchema<mixed>> $resolved */
        $resolved = [];

        // pass 1: register lazy placeholders
        foreach (get_object_vars($defs) as $name => $defBody) {
            /** @var non-empty-string $defName */
            $defName = (string) $name;
            $registry->register(
                name: $defName,
                schema: new LazySchema(
                    function () use (&$resolved, $defName) {
                        /** @var ValidationSchema<mixed> $schema */
                        $schema = $resolved[$defName];
                        return $schema;
                    },
                ),
            );
        }

        // pass 2: import each definition body
        foreach (get_object_vars($defs) as $name => $defBody) {
            if (! $defBody instanceof stdClass) {
                throw InvalidJsonSchemaException::malformed(
                    reason: '$defs/' . $name
                        . ' must be a JSON object',
                );
            }

            $resolved[$name] = $this->importSchema(
                schema: $defBody,
                registry: $registry,
            );
        }
    }

    // ================================================================
    //
    // Schema Dispatcher
    //
    // ----------------------------------------------------------------

    /**
     * import a single JSON Schema object as a
     * ValidationKit schema
     *
     * @return ValidationSchema<mixed>
     */
    private function importSchema(
        stdClass $schema,
        JsonSchemaRegistry $registry,
    ): ValidationSchema {
        // $ref takes precedence over all other keywords
        if (
            isset($schema->{'$ref'})
            && is_string($schema->{'$ref'})
            && $schema->{'$ref'} !== ''
        ) {
            return $registry->resolveRef(
                ref: $schema->{'$ref'},
            );
        }

        // const (before enum — const is more specific)
        if (property_exists($schema, 'const')) {
            return $this->applyMetadata(
                schema: Validate::literal(
                    value: $schema->{'const'},
                ),
                jsonSchema: $schema,
            );
        }

        // enum
        if (isset($schema->enum) && is_array($schema->enum)) {
            /** @var list<string|int> $enumValues */
            $enumValues = $schema->enum;
            return $this->applyMetadata(
                schema: Validate::enum(
                    valuesOrEnumClass: $enumValues,
                ),
                jsonSchema: $schema,
            );
        }

        // composition keywords
        if (isset($schema->anyOf) && is_array($schema->anyOf)) {
            return $this->importAnyOfSchema(
                schema: $schema,
                registry: $registry,
            );
        }

        if (isset($schema->allOf) && is_array($schema->allOf)) {
            return $this->importAllOfSchema(
                schema: $schema,
                registry: $registry,
            );
        }

        if (isset($schema->oneOf) && is_array($schema->oneOf)) {
            return $this->importOneOfSchema(
                schema: $schema,
                registry: $registry,
            );
        }

        if (isset($schema->not) && $schema->not instanceof stdClass) {
            return $this->applyMetadata(
                schema: Validate::not(
                    schema: $this->importSchema(
                        schema: $schema->not,
                        registry: $registry,
                    ),
                ),
                jsonSchema: $schema,
            );
        }

        if (
            isset($schema->{'if'})
            && $schema->{'if'} instanceof stdClass
        ) {
            return $this->importConditionalSchema(
                schema: $schema,
                registry: $registry,
            );
        }

        // type-based dispatch
        if (isset($schema->type) && is_string($schema->type)) {
            return $this->importTypedSchema(
                schema: $schema,
                registry: $registry,
            );
        }

        // type as array (e.g. ["string", "null"] for
        // nullable in OAS 3.1)
        if (isset($schema->type) && is_array($schema->type)) {
            return $this->importTypeArraySchema(
                schema: $schema,
                registry: $registry,
            );
        }

        // no type, no composition — true schema
        return $this->applyMetadata(
            schema: Validate::mixed(),
            jsonSchema: $schema,
        );
    }

    /**
     * dispatch to the correct type-specific importer
     *
     * @return ValidationSchema<mixed>
     */
    private function importTypedSchema(
        stdClass $schema,
        JsonSchemaRegistry $registry,
    ): ValidationSchema {
        assert(
            isset($schema->type) && is_string($schema->type),
        );

        return match ($schema->type) {
            'string'  => $this->importStringSchema($schema),
            'integer' => $this->importIntSchema($schema),
            'number'  => $this->importNumberSchema($schema),
            'boolean' => $this->applyMetadata(
                schema: Validate::boolean(),
                jsonSchema: $schema,
            ),
            'null' => $this->applyMetadata(
                schema: Validate::null(),
                jsonSchema: $schema,
            ),
            'array' => $this->importArraySchema(
                schema: $schema,
                registry: $registry,
            ),
            'object' => $this->importObjectSchema(
                schema: $schema,
                registry: $registry,
            ),
            default => throw InvalidJsonSchemaException::unknownType(
                type: $schema->type ?: 'empty',
            ),
        };
    }

    /**
     * import a schema whose `type` is an array of types
     *
     * JSON Schema Draft 2020-12 and OpenAPI 3.1 allow
     * `type` to be an array, e.g. `["string", "null"]`
     * for nullable values.
     *
     * - Single-element array: treated as that type.
     * - Two-element array containing "null": imported as
     *   nullable wrapper around the non-null type.
     * - Other multi-type arrays: imported as anyOf with
     *   one branch per type.
     *
     * All non-type keywords (constraints, metadata) from
     * the original schema are preserved by cloning the
     * schema object and setting its `type` to the resolved
     * single-type string before delegating to
     * importTypedSchema().
     *
     * @return ValidationSchema<mixed>
     */
    private function importTypeArraySchema(
        stdClass $schema,
        JsonSchemaRegistry $registry,
    ): ValidationSchema {
        assert(
            isset($schema->type) && is_array($schema->type),
        );

        /** @var list<string> $types */
        $types = array_values($schema->type);

        // single-element array — treat as scalar type
        if (count($types) === 1) {
            $clone = clone $schema;
            $clone->type = $types[0];

            return $this->importTypedSchema(
                schema: $clone,
                registry: $registry,
            );
        }

        // two-element array with "null" — nullable pattern
        $nullIndex = array_search('null', $types, true);
        if (count($types) === 2 && $nullIndex !== false) {
            $innerType = $types[$nullIndex === 0 ? 1 : 0];

            // clone the schema with the inner type so that
            // constraints (minLength, pattern, etc.) are
            // applied to the inner schema, not lost
            $clone = clone $schema;
            $clone->type = $innerType;

            $innerSchema = $this->importTypedSchema(
                schema: $clone,
                registry: $registry,
            );

            return $this->applyMetadata(
                schema: Validate::nullable(
                    schema: $innerSchema,
                ),
                jsonSchema: $schema,
            );
        }

        // multi-type array — build an anyOf with one
        // branch per type
        $hasNull = false;
        $members = [];
        foreach ($types as $type) {
            if ($type === 'null') {
                $hasNull = true;
                continue;
            }

            $clone = clone $schema;
            $clone->type = $type;

            $members[] = $this->importTypedSchema(
                schema: $clone,
                registry: $registry,
            );
        }

        $innerSchema = count($members) === 1
            ? $members[0]
            : Validate::anyOf(schemas: $members);

        if ($hasNull) {
            $innerSchema = Validate::nullable(
                schema: $innerSchema,
            );
        }

        return $this->applyMetadata(
            schema: $innerSchema,
            jsonSchema: $schema,
        );
    }

    // ================================================================
    //
    // Primitive Type Importers
    //
    // ----------------------------------------------------------------

    /**
     * import a string schema with constraints and format
     */
    private function importStringSchema(
        stdClass $schema,
    ): StringSchema {
        $result = Validate::string();

        // length constraints
        if (
            isset($schema->minLength)
            && is_int($schema->minLength)
        ) {
            $result = $result->min(
                length: $schema->minLength,
            );
        }

        if (
            isset($schema->maxLength)
            && is_int($schema->maxLength)
        ) {
            $result = $result->max(
                length: $schema->maxLength,
            );
        }

        // pattern — add PCRE delimiters
        if (
            isset($schema->pattern)
            && is_string($schema->pattern)
        ) {
            $result = $result->regex(
                pattern: '/' . $schema->pattern . '/',
            );
        }

        // format
        if (
            isset($schema->format)
            && is_string($schema->format)
        ) {
            $result = $this->applyStringFormat(
                schema: $result,
                format: $schema->format,
            );
        }

        return $this->applyMetadata(
            schema: $result,
            jsonSchema: $schema,
        );
    }

    /**
     * apply a format keyword to a string schema
     *
     * Known formats are mapped to builder methods.
     * Unknown formats are stored as metadata.
     */
    private function applyStringFormat(
        StringSchema $schema,
        string $format,
    ): StringSchema {
        $method = self::FORMAT_METHODS[$format] ?? null;

        if ($method !== null) {
            return $schema->{$method}();
        }

        // unknown format — store as metadata so it
        // survives a round-trip
        return $schema->withMetadata(
            data: ['format' => $format],
        );
    }

    /**
     * import an integer schema with constraints
     */
    private function importIntSchema(
        stdClass $schema,
    ): IntSchema {
        $result = Validate::int();
        $result = $this->applyIntConstraints(
            schema: $result,
            jsonSchema: $schema,
        );

        return $this->applyMetadata(
            schema: $result,
            jsonSchema: $schema,
        );
    }

    /**
     * import a number schema with constraints
     *
     * JSON Schema `number` accepts both integers and
     * floats, so this maps to NumberSchema rather than
     * FloatSchema.
     */
    private function importNumberSchema(
        stdClass $schema,
    ): NumberSchema {
        $result = Validate::number();
        $result = $this->applyNumberConstraints(
            schema: $result,
            jsonSchema: $schema,
        );

        return $this->applyMetadata(
            schema: $result,
            jsonSchema: $schema,
        );
    }

    /**
     * apply numeric constraints to an integer schema
     */
    private function applyIntConstraints(
        IntSchema $schema,
        stdClass $jsonSchema,
    ): IntSchema {
        if (
            isset($jsonSchema->minimum)
            && is_int($jsonSchema->minimum)
        ) {
            $schema = $schema->gte(
                value: $jsonSchema->minimum,
            );
        }

        if (
            isset($jsonSchema->exclusiveMinimum)
            && is_int($jsonSchema->exclusiveMinimum)
        ) {
            $schema = $schema->gt(
                value: $jsonSchema->exclusiveMinimum,
            );
        }

        if (
            isset($jsonSchema->maximum)
            && is_int($jsonSchema->maximum)
        ) {
            $schema = $schema->lte(
                value: $jsonSchema->maximum,
            );
        }

        if (
            isset($jsonSchema->exclusiveMaximum)
            && is_int($jsonSchema->exclusiveMaximum)
        ) {
            $schema = $schema->lt(
                value: $jsonSchema->exclusiveMaximum,
            );
        }

        if (
            isset($jsonSchema->multipleOf)
            && is_int($jsonSchema->multipleOf)
        ) {
            $schema = $schema->multipleOf(
                value: $jsonSchema->multipleOf,
            );
        }

        return $schema;
    }

    /**
     * apply numeric constraints to a number schema
     */
    private function applyNumberConstraints(
        NumberSchema $schema,
        stdClass $jsonSchema,
    ): NumberSchema {
        if (
            isset($jsonSchema->minimum)
            && (is_int($jsonSchema->minimum)
                || is_float($jsonSchema->minimum))
        ) {
            $schema = $schema->gte(
                value: $jsonSchema->minimum,
            );
        }

        if (
            isset($jsonSchema->exclusiveMinimum)
            && (is_int($jsonSchema->exclusiveMinimum)
                || is_float($jsonSchema->exclusiveMinimum))
        ) {
            $schema = $schema->gt(
                value: $jsonSchema->exclusiveMinimum,
            );
        }

        if (
            isset($jsonSchema->maximum)
            && (is_int($jsonSchema->maximum)
                || is_float($jsonSchema->maximum))
        ) {
            $schema = $schema->lte(
                value: $jsonSchema->maximum,
            );
        }

        if (
            isset($jsonSchema->exclusiveMaximum)
            && (is_int($jsonSchema->exclusiveMaximum)
                || is_float($jsonSchema->exclusiveMaximum))
        ) {
            $schema = $schema->lt(
                value: $jsonSchema->exclusiveMaximum,
            );
        }

        if (
            isset($jsonSchema->multipleOf)
            && (is_int($jsonSchema->multipleOf)
                || is_float($jsonSchema->multipleOf))
        ) {
            $schema = $schema->multipleOf(
                value: $jsonSchema->multipleOf,
            );
        }

        return $schema;
    }

    // ================================================================
    //
    // Collection Type Importers
    //
    // ----------------------------------------------------------------

    /**
     * import an array schema, detecting tuples via
     * prefixItems
     *
     * @return ValidationSchema<mixed>
     */
    private function importArraySchema(
        stdClass $schema,
        JsonSchemaRegistry $registry,
    ): ValidationSchema {
        // tuple detection: prefixItems present
        if (
            isset($schema->prefixItems)
            && is_array($schema->prefixItems)
        ) {
            return $this->importTupleSchema(
                schema: $schema,
                registry: $registry,
            );
        }

        // regular array
        $elementSchema = Validate::mixed();
        if (
            isset($schema->items)
            && $schema->items instanceof stdClass
        ) {
            $elementSchema = $this->importSchema(
                schema: $schema->items,
                registry: $registry,
            );
        }

        $result = Validate::array(element: $elementSchema);
        $result = $this->applyArrayConstraints(
            schema: $result,
            jsonSchema: $schema,
            registry: $registry,
        );

        return $this->applyMetadata(
            schema: $result,
            jsonSchema: $schema,
        );
    }

    /**
     * import a tuple schema from prefixItems
     *
     * @return ValidationSchema<mixed>
     */
    private function importTupleSchema(
        stdClass $schema,
        JsonSchemaRegistry $registry,
    ): ValidationSchema {
        assert(
            isset($schema->prefixItems)
            && is_array($schema->prefixItems),
        );

        $schemas = [];
        foreach ($schema->prefixItems as $itemSchema) {
            if (! $itemSchema instanceof stdClass) {
                throw InvalidJsonSchemaException::malformed(
                    reason: 'prefixItems entries must be'
                        . ' JSON objects',
                );
            }

            $schemas[] = $this->importSchema(
                schema: $itemSchema,
                registry: $registry,
            );
        }

        return $this->applyMetadata(
            schema: Validate::tuple(schemas: $schemas),
            jsonSchema: $schema,
        );
    }

    /**
     * apply array-specific constraints
     *
     * @param ArraySchema<mixed> $schema
     * @return ArraySchema<mixed>
     */
    private function applyArrayConstraints(
        ArraySchema $schema,
        stdClass $jsonSchema,
        JsonSchemaRegistry $registry,
    ): ArraySchema {
        if (
            isset($jsonSchema->minItems)
            && is_int($jsonSchema->minItems)
        ) {
            $schema = $schema->min(
                length: $jsonSchema->minItems,
            );
        }

        if (
            isset($jsonSchema->maxItems)
            && is_int($jsonSchema->maxItems)
        ) {
            $schema = $schema->max(
                length: $jsonSchema->maxItems,
            );
        }

        if (
            isset($jsonSchema->uniqueItems)
            && $jsonSchema->uniqueItems === true
        ) {
            $schema = $schema->uniqueItems();
        }

        if (
            isset($jsonSchema->contains)
            && $jsonSchema->contains instanceof stdClass
        ) {
            $containsSchema = $this->importSchema(
                schema: $jsonSchema->contains,
                registry: $registry,
            );

            $minContains = isset($jsonSchema->minContains)
                && is_int($jsonSchema->minContains)
                ? $jsonSchema->minContains
                : null;

            $maxContains = isset($jsonSchema->maxContains)
                && is_int($jsonSchema->maxContains)
                ? $jsonSchema->maxContains
                : null;

            $schema = $schema->contains(
                schema: $containsSchema,
                minContains: $minContains,
                maxContains: $maxContains,
            );
        }

        return $schema;
    }

    /**
     * import an object schema with properties, required,
     * and additionalProperties
     *
     * When properties are defined, imports as an object
     * schema with a shape. When only
     * additionalProperties is defined (with
     * propertyNames), imports as a record schema.
     *
     * @return ValidationSchema<mixed>
     */
    private function importObjectSchema(
        stdClass $schema,
        JsonSchemaRegistry $registry,
    ): ValidationSchema {
        // record detection: no properties, but
        // additionalProperties with propertyNames
        if (
            ! isset($schema->properties)
            && isset($schema->propertyNames)
            && isset($schema->additionalProperties)
            && $schema->additionalProperties instanceof stdClass
        ) {
            return $this->importRecordSchema(
                schema: $schema,
                registry: $registry,
            );
        }

        // build shape from properties
        $shape = [];
        $required = [];

        if (
            isset($schema->required)
            && is_array($schema->required)
        ) {
            /** @var list<string> $required */
            $required = $schema->required;
        }

        if (
            isset($schema->properties)
            && $schema->properties instanceof stdClass
        ) {
            foreach (get_object_vars($schema->properties) as $key => $fieldBody) {
                if (! $fieldBody instanceof stdClass) {
                    throw InvalidJsonSchemaException::malformed(
                        reason: 'properties/' . $key
                            . ' must be a JSON object',
                    );
                }

                $fieldSchema = $this->importSchema(
                    schema: $fieldBody,
                    registry: $registry,
                );

                // fields not in required become optional
                if (! in_array($key, $required, strict: true)) {
                    $fieldSchema = Validate::optional(
                        schema: $fieldSchema,
                    );
                }

                $shape[$key] = $fieldSchema;
            }
        }

        /** @var array<string, ValidationSchema<mixed>> $typedShape */
        $typedShape = $shape;
        $result = Validate::object(shape: $typedShape);

        // additionalProperties policy
        if (isset($schema->additionalProperties)) {
            if ($schema->additionalProperties === false) {
                $result = $result->strict();
            } elseif ($schema->additionalProperties === true) {
                $result = $result->passthrough();
            } elseif ($schema->additionalProperties instanceof stdClass) {
                $result = $result->catchall(
                    schema: $this->importSchema(
                        schema: $schema->additionalProperties,
                        registry: $registry,
                    ),
                );
            }
        }

        $result = $this->applyObjectConstraints(
            schema: $result,
            jsonSchema: $schema,
            registry: $registry,
        );

        return $this->applyMetadata(
            schema: $result,
            jsonSchema: $schema,
        );
    }

    /**
     * import a record schema from propertyNames and
     * additionalProperties
     *
     * @return ValidationSchema<mixed>
     */
    private function importRecordSchema(
        stdClass $schema,
        JsonSchemaRegistry $registry,
    ): ValidationSchema {
        assert(
            isset($schema->propertyNames)
            && $schema->propertyNames instanceof stdClass
            && isset($schema->additionalProperties)
            && $schema->additionalProperties instanceof stdClass,
        );

        /** @var ValidationSchema<string> $keySchema */
        $keySchema = $this->importSchema(
            schema: $schema->propertyNames,
            registry: $registry,
        );

        $valueSchema = $this->importSchema(
            schema: $schema->additionalProperties,
            registry: $registry,
        );

        return $this->applyMetadata(
            schema: Validate::record(
                key: $keySchema,
                value: $valueSchema,
            ),
            jsonSchema: $schema,
        );
    }

    /**
     * apply object-specific constraints
     */
    private function applyObjectConstraints(
        ObjectSchema $schema,
        stdClass $jsonSchema,
        JsonSchemaRegistry $registry,
    ): ObjectSchema {
        if (
            isset($jsonSchema->minProperties)
            && is_int($jsonSchema->minProperties)
        ) {
            $schema = $schema->minProperties(
                count: $jsonSchema->minProperties,
            );
        }

        if (
            isset($jsonSchema->maxProperties)
            && is_int($jsonSchema->maxProperties)
        ) {
            $schema = $schema->maxProperties(
                count: $jsonSchema->maxProperties,
            );
        }

        if (
            isset($jsonSchema->propertyNames)
            && $jsonSchema->propertyNames instanceof stdClass
        ) {
            $schema = $schema->propertyNames(
                schema: $this->importSchema(
                    schema: $jsonSchema->propertyNames,
                    registry: $registry,
                ),
            );
        }

        if (
            isset($jsonSchema->patternProperties)
            && $jsonSchema->patternProperties instanceof stdClass
        ) {
            /** @var array<string, ValidationSchema<mixed>> $patterns */
            $patterns = [];
            foreach (get_object_vars($jsonSchema->patternProperties) as $pattern => $patternBody) {
                if (! $patternBody instanceof stdClass) {
                    throw InvalidJsonSchemaException::malformed(
                        reason: 'patternProperties/'
                            . $pattern
                            . ' must be a JSON object',
                    );
                }

                $patterns[(string) $pattern] = $this->importSchema(
                    schema: $patternBody,
                    registry: $registry,
                );
            }

            $schema = $schema->patternProperties(
                patterns: $patterns,
            );
        }

        if (
            isset($jsonSchema->dependentRequired)
            && $jsonSchema->dependentRequired instanceof stdClass
        ) {
            /** @var array<string, list<string>> $deps */
            $deps = [];
            foreach (get_object_vars($jsonSchema->dependentRequired) as $key => $required) {
                if (! is_array($required)) {
                    throw InvalidJsonSchemaException::malformed(
                        reason: 'dependentRequired/'
                            . $key
                            . ' must be an array',
                    );
                }
                /** @var list<string> $typedRequired */
                $typedRequired = $required;
                $deps[(string) $key] = $typedRequired;
            }

            $schema = $schema->dependentRequired(
                dependencies: $deps,
            );
        }

        if (
            isset($jsonSchema->dependentSchemas)
            && $jsonSchema->dependentSchemas instanceof stdClass
        ) {
            /** @var array<string, ValidationSchema<mixed>> $depSchemas */
            $depSchemas = [];
            foreach (get_object_vars($jsonSchema->dependentSchemas) as $key => $depBody) {
                if (! $depBody instanceof stdClass) {
                    throw InvalidJsonSchemaException::malformed(
                        reason: 'dependentSchemas/'
                            . $key
                            . ' must be a JSON object',
                    );
                }

                $depSchemas[(string) $key] = $this->importSchema(
                    schema: $depBody,
                    registry: $registry,
                );
            }

            $schema = $schema->dependentSchemas(
                dependencies: $depSchemas,
            );
        }

        return $schema;
    }

    // ================================================================
    //
    // Composition Importers
    //
    // ----------------------------------------------------------------

    /**
     * import an anyOf schema, detecting nullable patterns
     *
     * When the anyOf has exactly two members and one is
     * `{"type": "null"}`, the schema is imported as a
     * nullable wrapper around the other member.
     *
     * @return ValidationSchema<mixed>
     */
    private function importAnyOfSchema(
        stdClass $schema,
        JsonSchemaRegistry $registry,
    ): ValidationSchema {
        assert(
            isset($schema->anyOf) && is_array($schema->anyOf),
        );

        // detect nullable pattern:
        // anyOf: [{innerSchema}, {"type": "null"}]
        if (count($schema->anyOf) === 2) {
            $nullIndex = $this->findNullBranch(
                $schema->anyOf,
            );

            if ($nullIndex !== null) {
                $innerIndex = $nullIndex === 0 ? 1 : 0;

                /** @var stdClass $innerBody */
                $innerBody = $schema->anyOf[$innerIndex];

                $innerSchema = $this->importSchema(
                    schema: $innerBody,
                    registry: $registry,
                );

                return $this->applyMetadata(
                    schema: Validate::nullable(
                        schema: $innerSchema,
                    ),
                    jsonSchema: $schema,
                );
            }
        }

        // general anyOf
        $members = [];
        foreach ($schema->anyOf as $memberBody) {
            if (! $memberBody instanceof stdClass) {
                throw InvalidJsonSchemaException::malformed(
                    reason: 'anyOf entries must be'
                        . ' JSON objects',
                );
            }

            $members[] = $this->importSchema(
                schema: $memberBody,
                registry: $registry,
            );
        }

        return $this->applyMetadata(
            schema: Validate::anyOf(schemas: $members),
            jsonSchema: $schema,
        );
    }

    /**
     * import an allOf schema
     *
     * @return ValidationSchema<mixed>
     */
    private function importAllOfSchema(
        stdClass $schema,
        JsonSchemaRegistry $registry,
    ): ValidationSchema {
        assert(
            isset($schema->allOf) && is_array($schema->allOf),
        );

        $members = [];
        foreach ($schema->allOf as $memberBody) {
            if (! $memberBody instanceof stdClass) {
                throw InvalidJsonSchemaException::malformed(
                    reason: 'allOf entries must be'
                        . ' JSON objects',
                );
            }

            $members[] = $this->importSchema(
                schema: $memberBody,
                registry: $registry,
            );
        }

        return $this->applyMetadata(
            schema: Validate::allOf(schemas: $members),
            jsonSchema: $schema,
        );
    }

    /**
     * import a oneOf schema
     *
     * @return ValidationSchema<mixed>
     */
    private function importOneOfSchema(
        stdClass $schema,
        JsonSchemaRegistry $registry,
    ): ValidationSchema {
        assert(
            isset($schema->oneOf) && is_array($schema->oneOf),
        );

        $members = [];
        foreach ($schema->oneOf as $memberBody) {
            if (! $memberBody instanceof stdClass) {
                throw InvalidJsonSchemaException::malformed(
                    reason: 'oneOf entries must be'
                        . ' JSON objects',
                );
            }

            $members[] = $this->importSchema(
                schema: $memberBody,
                registry: $registry,
            );
        }

        return $this->applyMetadata(
            schema: Validate::oneOf(schemas: $members),
            jsonSchema: $schema,
        );
    }

    /**
     * import a conditional schema (if/then/else)
     *
     * @return ValidationSchema<mixed>
     */
    private function importConditionalSchema(
        stdClass $schema,
        JsonSchemaRegistry $registry,
    ): ValidationSchema {
        assert(
            isset($schema->{'if'})
            && $schema->{'if'} instanceof stdClass,
        );

        $ifSchema = $this->importSchema(
            schema: $schema->{'if'},
            registry: $registry,
        );

        $thenSchema = null;
        if (
            isset($schema->then)
            && $schema->then instanceof stdClass
        ) {
            $thenSchema = $this->importSchema(
                schema: $schema->then,
                registry: $registry,
            );
        }

        $elseSchema = null;
        if (
            isset($schema->{'else'})
            && $schema->{'else'} instanceof stdClass
        ) {
            $elseSchema = $this->importSchema(
                schema: $schema->{'else'},
                registry: $registry,
            );
        }

        return $this->applyMetadata(
            schema: Validate::conditional(
                if: $ifSchema,
                then: $thenSchema,
                else: $elseSchema,
            ),
            jsonSchema: $schema,
        );
    }

    // ================================================================
    //
    // Metadata Application
    //
    // ----------------------------------------------------------------

    /**
     * apply metadata keywords from a JSON Schema object
     * to a validation schema
     *
     * Maps title, description, examples, deprecated,
     * readOnly, writeOnly, and default to the
     * corresponding builder methods. Unknown metadata
     * keywords are collected into withMetadata().
     *
     * @template TSchema of ValidationSchema<mixed>
     * @param TSchema $schema
     * @return TSchema
     */
    private function applyMetadata(
        ValidationSchema $schema,
        stdClass $jsonSchema,
    ): ValidationSchema {
        if (
            isset($jsonSchema->title)
            && is_string($jsonSchema->title)
            && $jsonSchema->title !== ''
        ) {
            $schema = $schema->withTitle($jsonSchema->title);
        }

        if (
            isset($jsonSchema->description)
            && is_string($jsonSchema->description)
            && $jsonSchema->description !== ''
        ) {
            $schema = $schema->withDescription(
                $jsonSchema->description,
            );
        }

        if (
            isset($jsonSchema->examples)
            && is_array($jsonSchema->examples)
        ) {
            /** @var list<mixed> $examples */
            $examples = array_values($jsonSchema->examples);
            $schema = $schema->withExamples($examples);
        }

        if (
            isset($jsonSchema->deprecated)
            && $jsonSchema->deprecated === true
        ) {
            $schema = $schema->withDeprecated();
        }

        if (
            isset($jsonSchema->readOnly)
            && $jsonSchema->readOnly === true
        ) {
            $schema = $schema->withReadOnly();
        }

        if (
            isset($jsonSchema->writeOnly)
            && $jsonSchema->writeOnly === true
        ) {
            $schema = $schema->withWriteOnly();
        }

        if (property_exists($jsonSchema, 'default')) {
            $schema = $schema->withDefault(
                $jsonSchema->default,
            );
        }

        if (
            isset($jsonSchema->{'$comment'})
            && is_string($jsonSchema->{'$comment'})
            && $jsonSchema->{'$comment'} !== ''
        ) {
            $schema = $schema->withMetadata(
                data: ['$comment' => $jsonSchema->{'$comment'}],
            );
        }

        return $schema;
    }

    // ================================================================
    //
    // Helpers
    //
    // ----------------------------------------------------------------

    /**
     * find the index of the {"type": "null"} branch
     * in an anyOf array, or null if none
     *
     * @param array<mixed> $branches
     */
    private function findNullBranch(array $branches): ?int
    {
        foreach ($branches as $index => $branch) {
            if (
                $branch instanceof stdClass
                && isset($branch->type)
                && $branch->type === 'null'
                && count(get_object_vars($branch)) === 1
            ) {
                return $index;
            }
        }

        return null;
    }
}
