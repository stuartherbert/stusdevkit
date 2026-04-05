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

use BackedEnum;
use stdClass;
use StusDevKit\ValidationKit\Constraints\ArrayContainsConstraint;
use StusDevKit\ValidationKit\Constraints\ArrayExactLengthConstraint;
use StusDevKit\ValidationKit\Constraints\ArrayMaxLengthConstraint;
use StusDevKit\ValidationKit\Constraints\ArrayMinLengthConstraint;
use StusDevKit\ValidationKit\Constraints\ArrayUniqueItemsConstraint;
use StusDevKit\ValidationKit\Constraints\NumericGtConstraint;
use StusDevKit\ValidationKit\Constraints\NumericGteConstraint;
use StusDevKit\ValidationKit\Constraints\NumericLtConstraint;
use StusDevKit\ValidationKit\Constraints\NumericLteConstraint;
use StusDevKit\ValidationKit\Constraints\NumericMultipleOfConstraint;
use StusDevKit\ValidationKit\Constraints\ObjectDependentRequiredConstraint;
use StusDevKit\ValidationKit\Constraints\ObjectDependentSchemasConstraint;
use StusDevKit\ValidationKit\Constraints\ObjectMaxPropertiesConstraint;
use StusDevKit\ValidationKit\Constraints\ObjectMinPropertiesConstraint;
use StusDevKit\ValidationKit\Constraints\ObjectPatternPropertiesConstraint;
use StusDevKit\ValidationKit\Constraints\ObjectPropertyNamesConstraint;
use StusDevKit\ValidationKit\Constraints\StringDateConstraint;
use StusDevKit\ValidationKit\Constraints\StringDateTimeConstraint;
use StusDevKit\ValidationKit\Constraints\StringDurationConstraint;
use StusDevKit\ValidationKit\Constraints\StringEmailConstraint;
use StusDevKit\ValidationKit\Constraints\StringExactLengthConstraint;
use StusDevKit\ValidationKit\Constraints\StringHostnameConstraint;
use StusDevKit\ValidationKit\Constraints\StringIpv4Constraint;
use StusDevKit\ValidationKit\Constraints\StringIpv6Constraint;
use StusDevKit\ValidationKit\Constraints\StringMaxLengthConstraint;
use StusDevKit\ValidationKit\Constraints\StringMinLengthConstraint;
use StusDevKit\ValidationKit\Constraints\StringRegexConstraint;
use StusDevKit\ValidationKit\Constraints\StringTimeConstraint;
use StusDevKit\ValidationKit\Constraints\StringUrlConstraint;
use StusDevKit\ValidationKit\Constraints\StringUuidConstraint;
use StusDevKit\ValidationKit\Contracts\PipelineStep;
use StusDevKit\ValidationKit\Contracts\ValidationSchema;
use StusDevKit\ValidationKit\Schemas\BaseSchema;
use StusDevKit\ValidationKit\Schemas\BuiltinObjects\DateTimeInterfaceSchema;
use StusDevKit\ValidationKit\Schemas\BuiltinObjects\InstanceOfSchema;
use StusDevKit\ValidationKit\Schemas\Builtins\ArraySchema;
use StusDevKit\ValidationKit\Schemas\Builtins\BooleanSchema;
use StusDevKit\ValidationKit\Schemas\Builtins\FloatSchema;
use StusDevKit\ValidationKit\Schemas\Builtins\IntSchema;
use StusDevKit\ValidationKit\Schemas\Builtins\LiteralSchema;
use StusDevKit\ValidationKit\Schemas\Builtins\MixedSchema;
use StusDevKit\ValidationKit\Schemas\Builtins\NullableSchema;
use StusDevKit\ValidationKit\Schemas\Builtins\NullishSchema;
use StusDevKit\ValidationKit\Schemas\Builtins\NullSchema;
use StusDevKit\ValidationKit\Schemas\Builtins\NumberSchema;
use StusDevKit\ValidationKit\Schemas\Builtins\ObjectSchema;
use StusDevKit\ValidationKit\Schemas\Builtins\OptionalSchema;
use StusDevKit\ValidationKit\Schemas\Builtins\StringSchema;
use StusDevKit\ValidationKit\Schemas\Builtins\UnknownKeyPolicy;
use StusDevKit\ValidationKit\Schemas\Codec;
use StusDevKit\ValidationKit\Schemas\Collections\RecordSchema;
use StusDevKit\ValidationKit\Schemas\Collections\TupleSchema;
use StusDevKit\ValidationKit\Schemas\DevKit\WhenSchema;
use StusDevKit\ValidationKit\Schemas\LazySchema;
use StusDevKit\ValidationKit\Schemas\Logic\AllOfSchema;
use StusDevKit\ValidationKit\Schemas\Logic\AnyOfSchema;
use StusDevKit\ValidationKit\Schemas\Logic\ConditionalSchema;
use StusDevKit\ValidationKit\Schemas\Logic\DiscriminatedAnyOfSchema;
use StusDevKit\ValidationKit\Schemas\Logic\EnumSchema;
use StusDevKit\ValidationKit\Schemas\Logic\NotSchema;
use StusDevKit\ValidationKit\Schemas\Logic\OneOfSchema;
use StusDevKit\ValidationKit\Schemas\UuidSchema;

/**
 * JsonSchemaDraft202012Exporter converts a ValidationSchema
 * into a JSON Schema Draft 2020-12 object.
 *
 * The exporter reads the schema structure and constraints
 * using introspection methods, then maps them to the
 * corresponding JSON Schema keywords.
 *
 * Usage:
 *
 *     use StusDevKit\ValidationKit\JsonSchema\JsonSchemaDraft202012Exporter;
 *     use StusDevKit\ValidationKit\Validate;
 *
 *     $schema = Validate::object([
 *         'name' => Validate::string()->min(length: 1),
 *         'age' => Validate::int()->gte(value: 0),
 *     ]);
 *
 *     $exporter = new JsonSchemaDraft202012Exporter();
 *     $jsonSchema = $exporter->export($schema);
 *     // $jsonSchema is a JsonSchema value object
 *
 *     echo json_encode(
 *         $jsonSchema,
 *         JSON_PRETTY_PRINT,
 *     );
 *
 * Limitations:
 *
 * - LazySchema (recursive schemas) exports as `{}`
 *   (true schema) because JSON Schema `$ref` / `$defs`
 *   requires a schema registry which is not implemented.
 * - InstanceOfSchema has no JSON Schema equivalent and
 *   exports as a descriptive comment.
 * - PCRE regex flags are stripped when converting to
 *   JSON Schema `pattern` (ECMA 262 has no flag
 *   support).
 * - Constraints without JSON Schema equivalents
 *   (includes, startsWith, endsWith, finite) are
 *   skipped.
 */
class JsonSchemaDraft202012Exporter
{
    /**
     * maps spl_object_id to definition name for $ref
     * detection
     *
     * Built from the registry at the start of export()
     * and cleared when done.
     *
     * @var array<int, string>
     */
    private array $refMap = [];

    // ================================================================
    //
    // Public API
    //
    // ----------------------------------------------------------------

    /**
     * export a validation schema as a JSON Schema Draft
     * 2020-12 object
     *
     * The returned object includes the `$schema` keyword
     * identifying the JSON Schema dialect. When a
     * registry is provided, registered schemas are
     * emitted as `$defs` and referenced via `$ref`.
     *
     * @param ValidationSchema<mixed> $schema
     */
    public function export(
        ValidationSchema $schema,
        ?JsonSchemaRegistry $registry = null,
    ): JsonSchema {
        // build the $ref lookup from the registry
        $this->refMap = [];
        if ($registry !== null) {
            foreach ($registry->all() as $name => $regSchema) {
                $this->refMap[spl_object_id($regSchema)] = $name;
            }
        }

        $output = $this->exportSchema($schema);

        // emit $defs for registered schemas
        if ($registry !== null && ! $registry->all()->empty()) {
            $defs = new stdClass();
            foreach ($registry->all() as $name => $regSchema) {
                // asRef: false so the def itself is inlined,
                // but its children can still emit $ref
                $defs->{$name} = $this->exportSchema(
                    schema: $regSchema,
                    asRef: false,
                );
            }
            $output->{'$defs'} = $defs;
        }

        // prepend the $schema keyword
        $wrapper = new stdClass();
        $wrapper->{'$schema'} = 'https://json-schema.org/draft/2020-12/schema';
        foreach (get_object_vars($output) as $key => $value) {
            $wrapper->{$key} = $value;
        }

        // clean up state
        $this->refMap = [];

        return new JsonSchema($wrapper);
    }

    // ================================================================
    //
    // Schema Dispatcher
    //
    // ----------------------------------------------------------------

    /**
     * recursively export a schema to a JSON Schema object
     *
     * When asRef is true (the default) and the schema is
     * registered in the refMap, a `$ref` object is returned
     * instead of the full inline schema. Pass asRef: false
     * when exporting `$defs` entries to avoid circular
     * references.
     *
     * @param ValidationSchema<mixed> $schema
     */
    private function exportSchema(
        ValidationSchema $schema,
        bool $asRef = true,
    ): stdClass {
        // emit $ref for registered schemas
        if ($asRef && $this->refMap !== []) {
            $oid = spl_object_id($schema);
            if (isset($this->refMap[$oid])) {
                $ref = new stdClass();
                $ref->{'$ref'} = '#/$defs/'
                    . $this->refMap[$oid];
                return $ref;
            }
        }

        // resolve wrappers and proxies first
        if ($schema instanceof LazySchema) {
            return $this->exportSchema(
                $schema->resolvedSchema(),
            );
        }

        if ($schema instanceof Codec) {
            return $this->exportSchema(
                $schema->inputSchema(),
            );
        }

        // nullability wrappers
        if ($schema instanceof NullishSchema) {
            return $this->exportNullableWrapper(
                $schema->unwrap(),
                $schema,
            );
        }

        if ($schema instanceof NullableSchema) {
            return $this->exportNullableWrapper(
                $schema->unwrap(),
                $schema,
            );
        }

        if ($schema instanceof OptionalSchema) {
            return $this->exportSchema($schema->unwrap());
        }

        // primitive types
        if ($schema instanceof StringSchema) {
            return $this->applyAll(
                output: (object) ['type' => 'string'],
                schema: $schema,
            );
        }

        if ($schema instanceof IntSchema) {
            return $this->applyAll(
                output: (object) ['type' => 'integer'],
                schema: $schema,
            );
        }

        if ($schema instanceof FloatSchema) {
            return $this->applyAll(
                output: (object) ['type' => 'number'],
                schema: $schema,
            );
        }

        if ($schema instanceof NumberSchema) {
            return $this->applyAll(
                output: (object) ['type' => 'number'],
                schema: $schema,
            );
        }

        if ($schema instanceof BooleanSchema) {
            return $this->applyAll(
                output: (object) ['type' => 'boolean'],
                schema: $schema,
            );
        }

        if ($schema instanceof NullSchema) {
            return $this->applyAll(
                output: (object) ['type' => 'null'],
                schema: $schema,
            );
        }

        if ($schema instanceof MixedSchema) {
            return $this->applyAll(
                output: new stdClass(),
                schema: $schema,
            );
        }

        if ($schema instanceof LiteralSchema) {
            return $this->applyAll(
                output: (object) [
                    'const' => $schema->expectedValue(),
                ],
                schema: $schema,
            );
        }

        // specialized types that map to string in JSON
        if ($schema instanceof UuidSchema) {
            return $this->applyAll(
                output: (object) [
                    'type'   => 'string',
                    'format' => 'uuid',
                ],
                schema: $schema,
            );
        }

        if ($schema instanceof WhenSchema) {
            return $this->applyAll(
                output: (object) [
                    'type'   => 'string',
                    'format' => 'date-time',
                ],
                schema: $schema,
            );
        }

        if ($schema instanceof DateTimeInterfaceSchema) {
            return $this->applyAll(
                output: (object) [
                    'type'   => 'string',
                    'format' => 'date-time',
                ],
                schema: $schema,
            );
        }

        if ($schema instanceof InstanceOfSchema) {
            return $this->applyAll(
                output: (object) [
                    'type'        => 'object',
                    'description' => 'Instance of '
                        . $schema->className(),
                ],
                schema: $schema,
            );
        }

        // collection types
        if ($schema instanceof ArraySchema) {
            return $this->exportArraySchema($schema);
        }

        if ($schema instanceof ObjectSchema) {
            return $this->exportObjectSchema($schema);
        }

        if ($schema instanceof TupleSchema) {
            return $this->exportTupleSchema($schema);
        }

        if ($schema instanceof RecordSchema) {
            return $this->exportRecordSchema($schema);
        }

        // logic types
        if ($schema instanceof DiscriminatedAnyOfSchema) {
            return $this->exportDiscriminatedAnyOfSchema(
                $schema,
            );
        }

        if ($schema instanceof AnyOfSchema) {
            return $this->exportCompositionSchema(
                keyword: 'anyOf',
                schemas: $schema->schemas(),
                schema: $schema,
            );
        }

        if ($schema instanceof AllOfSchema) {
            return $this->exportCompositionSchema(
                keyword: 'allOf',
                schemas: $schema->schemas(),
                schema: $schema,
            );
        }

        if ($schema instanceof OneOfSchema) {
            return $this->exportCompositionSchema(
                keyword: 'oneOf',
                schemas: $schema->schemas(),
                schema: $schema,
            );
        }

        if ($schema instanceof NotSchema) {
            $output = new stdClass();
            $output->not = $this->exportSchema(
                $schema->innerSchema(),
            );

            return $this->applyAll(
                output: $output,
                schema: $schema,
            );
        }

        if ($schema instanceof ConditionalSchema) {
            return $this->exportConditionalSchema($schema);
        }

        if ($schema instanceof EnumSchema) {
            return $this->exportEnumSchema($schema);
        }

        // unknown schema type — true schema
        return new stdClass();
    }

    // ================================================================
    //
    // Complex Schema Exporters
    //
    // ----------------------------------------------------------------

    /**
     * export an array schema with items and constraints
     *
     * @param ArraySchema<mixed> $schema
     */
    private function exportArraySchema(
        ArraySchema $schema,
    ): stdClass {
        $output = new stdClass();
        $output->type = 'array';
        $output->items = $this->exportSchema(
            $schema->elementSchema(),
        );

        return $this->applyAll(
            output: $output,
            schema: $schema,
        );
    }

    /**
     * export a tuple schema with prefixItems
     */
    private function exportTupleSchema(
        TupleSchema $schema,
    ): stdClass {
        $prefixItems = [];
        foreach ($schema->schemas() as $itemSchema) {
            $prefixItems[] = $this->exportSchema($itemSchema);
        }

        $output = new stdClass();
        $output->type = 'array';
        $output->prefixItems = $prefixItems;
        $output->items = false;

        return $this->applyAll(
            output: $output,
            schema: $schema,
        );
    }

    /**
     * export a record schema with propertyNames and
     * additionalProperties
     *
     * @param RecordSchema<array-key, mixed> $schema
     */
    private function exportRecordSchema(
        RecordSchema $schema,
    ): stdClass {
        $output = new stdClass();
        $output->type = 'object';
        $output->propertyNames = $this->exportSchema(
            $schema->keySchema(),
        );
        $output->additionalProperties = $this->exportSchema(
            $schema->valueSchema(),
        );

        return $this->applyAll(
            output: $output,
            schema: $schema,
        );
    }

    /**
     * export an object schema with properties, required,
     * additionalProperties, and object-level constraints
     */
    private function exportObjectSchema(
        ObjectSchema $schema,
    ): stdClass {
        $output = new stdClass();
        $output->type = 'object';

        $properties = new stdClass();
        $required = [];

        foreach ($schema->shape() as $key => $fieldSchema) {
            // optional and nullish fields are not required
            $isOptional = $fieldSchema instanceof OptionalSchema
                || $fieldSchema instanceof NullishSchema;

            if (! $isOptional) {
                $required[] = $key;
            }

            $properties->{$key} = $this->exportSchema(
                $fieldSchema,
            );
        }

        if (get_object_vars($properties) !== []) {
            $output->properties = $properties;
        }

        if ($required !== []) {
            $output->required = $required;
        }

        // unknown key policy → additionalProperties
        $catchall = $schema->maybeCatchallSchema();
        if ($catchall !== null) {
            $output->additionalProperties = $this->exportSchema(
                $catchall,
            );
        } else {
            $policy = $schema->unknownKeyPolicy();
            $output->additionalProperties = match ($policy) {
                UnknownKeyPolicy::Passthrough => true,
                UnknownKeyPolicy::Strip,
                UnknownKeyPolicy::Strict => false,
            };
        }

        return $this->applyAll(
            output: $output,
            schema: $schema,
        );
    }

    /**
     * export a nullable wrapper as anyOf with null branch
     *
     * @param ValidationSchema<mixed> $innerSchema
     * @param ValidationSchema<mixed> $wrapperSchema
     */
    private function exportNullableWrapper(
        ValidationSchema $innerSchema,
        ValidationSchema $wrapperSchema,
    ): stdClass {
        $output = new stdClass();
        $output->anyOf = [
            $this->exportSchema($innerSchema),
            (object) ['type' => 'null'],
        ];

        if ($wrapperSchema instanceof BaseSchema) {
            $output = $this->applyMetadata(
                output: $output,
                schema: $wrapperSchema,
            );
        }

        return $output;
    }

    /**
     * export a composition schema (anyOf, allOf, oneOf)
     *
     * @param list<ValidationSchema<mixed>> $schemas
     * @param ValidationSchema<mixed> $schema
     */
    private function exportCompositionSchema(
        string $keyword,
        array $schemas,
        ValidationSchema $schema,
    ): stdClass {
        $members = [];
        foreach ($schemas as $memberSchema) {
            $members[] = $this->exportSchema($memberSchema);
        }

        $output = new stdClass();
        $output->{$keyword} = $members;

        if ($schema instanceof BaseSchema) {
            $output = $this->applyAll(
                output: $output,
                schema: $schema,
            );
        }

        return $output;
    }

    /**
     * export a discriminated union as anyOf
     */
    private function exportDiscriminatedAnyOfSchema(
        DiscriminatedAnyOfSchema $schema,
    ): stdClass {
        $members = [];
        foreach ($schema->schemaMap() as $memberSchema) {
            $members[] = $this->exportSchema($memberSchema);
        }

        $output = new stdClass();
        $output->anyOf = $members;

        return $this->applyAll(
            output: $output,
            schema: $schema,
        );
    }

    /**
     * export a conditional schema as if/then/else
     */
    private function exportConditionalSchema(
        ConditionalSchema $schema,
    ): stdClass {
        $output = new stdClass();
        $output->{'if'} = $this->exportSchema(
            $schema->ifSchema(),
        );

        $then = $schema->maybeThenSchema();
        if ($then !== null) {
            $output->then = $this->exportSchema($then);
        }

        $else = $schema->maybeElseSchema();
        if ($else !== null) {
            $output->{'else'} = $this->exportSchema($else);
        }

        return $this->applyAll(
            output: $output,
            schema: $schema,
        );
    }

    /**
     * export an enum schema
     */
    private function exportEnumSchema(EnumSchema $schema): stdClass
    {
        $values = $schema->maybeAllowedValues();

        if ($values === null) {
            // PHP BackedEnum mode — extract backing values
            $enumClass = $schema->maybeEnumClass();
            if ($enumClass !== null) {
                $values = array_map(
                    static fn(BackedEnum $case): string|int
                        => $case->value,
                    $enumClass::cases(),
                );
            }
        }

        $output = new stdClass();
        if ($values !== null) {
            $output->enum = array_values($values);
        }

        return $this->applyAll(
            output: $output,
            schema: $schema,
        );
    }

    // ================================================================
    //
    // Constraint and Metadata Application
    //
    // ----------------------------------------------------------------

    /**
     * apply constraints and metadata to the output
     *
     * @param BaseSchema<mixed> $schema
     */
    private function applyAll(
        stdClass $output,
        BaseSchema $schema,
    ): stdClass {
        $output = $this->applyConstraints(
            output: $output,
            schema: $schema,
        );

        return $this->applyMetadata(
            output: $output,
            schema: $schema,
        );
    }

    /**
     * apply metadata to the output
     *
     * Exports title, description, examples, deprecated,
     * readOnly, writeOnly, default, and user metadata.
     *
     * @param BaseSchema<mixed> $schema
     */
    private function applyMetadata(
        stdClass $output,
        BaseSchema $schema,
    ): stdClass {
        $title = $schema->maybeTitle();
        if ($title !== null) {
            $output->title = $title;
        }

        $description = $schema->maybeDescription();
        if ($description !== null) {
            $output->description = $description;
        }

        $examples = $schema->getExamples();
        if ($examples !== []) {
            $output->examples = $examples;
        }

        if ($schema->isDeprecated()) {
            $output->deprecated = true;
        }

        if ($schema->isReadOnly()) {
            $output->readOnly = true;
        }

        if ($schema->isWriteOnly()) {
            $output->writeOnly = true;
        }

        if ($schema->hasDefaultValue()) {
            $output->default = $schema->getDefaultValue();
        }

        // merge user metadata last so it can override
        $metadata = $schema->getMetadata();
        foreach ($metadata as $key => $value) {
            $output->{$key} = $value;
        }

        return $output;
    }

    /**
     * map pipeline steps to JSON Schema keywords
     *
     * @param BaseSchema<mixed> $schema
     */
    private function applyConstraints(
        stdClass $output,
        BaseSchema $schema,
    ): stdClass {
        foreach ($schema->getSteps() as $step) {
            $output = $this->applyConstraint(
                output: $output,
                step: $step,
            );
        }

        return $output;
    }

    /**
     * map a single pipeline step to JSON Schema keywords
     */
    private function applyConstraint(
        stdClass $output,
        PipelineStep $step,
    ): stdClass {
        // string length constraints
        if ($step instanceof StringMinLengthConstraint) {
            $output->minLength = $step->length();
            return $output;
        }

        if ($step instanceof StringMaxLengthConstraint) {
            $output->maxLength = $step->length();
            return $output;
        }

        if ($step instanceof StringExactLengthConstraint) {
            $output->minLength = $step->length();
            $output->maxLength = $step->length();
            return $output;
        }

        // string regex
        if ($step instanceof StringRegexConstraint) {
            $output->pattern = $this->stripPcreDelimiters(
                $step->pattern(),
            );
            return $output;
        }

        // string format constraints
        if ($step instanceof StringEmailConstraint) {
            $output->format = 'email';
            return $output;
        }

        if ($step instanceof StringUrlConstraint) {
            $output->format = 'uri';
            return $output;
        }

        if ($step instanceof StringUuidConstraint) {
            $output->format = 'uuid';
            return $output;
        }

        if ($step instanceof StringIpv4Constraint) {
            $output->format = 'ipv4';
            return $output;
        }

        if ($step instanceof StringIpv6Constraint) {
            $output->format = 'ipv6';
            return $output;
        }

        if ($step instanceof StringDateConstraint) {
            $output->format = 'date';
            return $output;
        }

        if ($step instanceof StringDateTimeConstraint) {
            $output->format = 'date-time';
            return $output;
        }

        if ($step instanceof StringTimeConstraint) {
            $output->format = 'time';
            return $output;
        }

        if ($step instanceof StringDurationConstraint) {
            $output->format = 'duration';
            return $output;
        }

        if ($step instanceof StringHostnameConstraint) {
            $output->format = 'hostname';
            return $output;
        }

        // numeric constraints
        if ($step instanceof NumericGteConstraint) {
            $output->minimum = $step->value();
            return $output;
        }

        if ($step instanceof NumericGtConstraint) {
            $output->exclusiveMinimum = $step->value();
            return $output;
        }

        if ($step instanceof NumericLteConstraint) {
            $output->maximum = $step->value();
            return $output;
        }

        if ($step instanceof NumericLtConstraint) {
            $output->exclusiveMaximum = $step->value();
            return $output;
        }

        if ($step instanceof NumericMultipleOfConstraint) {
            $output->multipleOf = $step->value();
            return $output;
        }

        // array constraints
        if ($step instanceof ArrayMinLengthConstraint) {
            $output->minItems = $step->length();
            return $output;
        }

        if ($step instanceof ArrayMaxLengthConstraint) {
            $output->maxItems = $step->length();
            return $output;
        }

        if ($step instanceof ArrayExactLengthConstraint) {
            $output->minItems = $step->length();
            $output->maxItems = $step->length();
            return $output;
        }

        if ($step instanceof ArrayUniqueItemsConstraint) {
            $output->uniqueItems = true;
            return $output;
        }

        if ($step instanceof ArrayContainsConstraint) {
            $output->contains = $this->exportSchema(
                $step->schema(),
            );
            $min = $step->minContains();
            if ($min !== null) {
                $output->minContains = $min;
            }
            $max = $step->maxContains();
            if ($max !== null) {
                $output->maxContains = $max;
            }
            return $output;
        }

        // object constraints
        if ($step instanceof ObjectMinPropertiesConstraint) {
            $output->minProperties = $step->count();
            return $output;
        }

        if ($step instanceof ObjectMaxPropertiesConstraint) {
            $output->maxProperties = $step->count();
            return $output;
        }

        if ($step instanceof ObjectPropertyNamesConstraint) {
            $output->propertyNames = $this->exportSchema(
                $step->schema(),
            );
            return $output;
        }

        if ($step instanceof ObjectPatternPropertiesConstraint) {
            $patternProperties = new stdClass();
            foreach ($step->patterns() as $pattern => $patternSchema) {
                $patternProperties->{$pattern} = $this->exportSchema(
                    $patternSchema,
                );
            }
            $output->patternProperties = $patternProperties;
            return $output;
        }

        if ($step instanceof ObjectDependentSchemasConstraint) {
            $dependentSchemas = new stdClass();
            foreach ($step->dependencies() as $key => $depSchema) {
                $dependentSchemas->{$key} = $this->exportSchema(
                    $depSchema,
                );
            }
            $output->dependentSchemas = $dependentSchemas;
            return $output;
        }

        if ($step instanceof ObjectDependentRequiredConstraint) {
            $deps = new stdClass();
            foreach ($step->dependencies() as $key => $required) {
                $deps->{$key} = $required;
            }
            $output->dependentRequired = $deps;
            return $output;
        }

        // unknown step types (transforms, normalisers,
        // custom constraints) are skipped
        return $output;
    }

    // ================================================================
    //
    // Helpers
    //
    // ----------------------------------------------------------------

    /**
     * strip PCRE delimiters and flags from a regex pattern
     *
     * Converts a PCRE pattern like `/^[a-z]+$/i` to the
     * inner pattern `^[a-z]+$` for use in JSON Schema's
     * `pattern` keyword (ECMA 262 format).
     */
    private function stripPcreDelimiters(string $pcre): string
    {
        if ($pcre === '') {
            return '';
        }

        $delimiter = $pcre[0];
        $lastPos = strrpos($pcre, $delimiter, offset: 1);

        if ($lastPos === false) {
            return $pcre;
        }

        return substr($pcre, offset: 1, length: $lastPos - 1);
    }
}
