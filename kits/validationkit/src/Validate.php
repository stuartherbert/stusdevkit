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

namespace StusDevKit\ValidationKit;

use BackedEnum;
use Closure;
use StusDevKit\ValidationKit\Contracts\ValidationSchema;
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
use StusDevKit\ValidationKit\Schemas\Codec;
use StusDevKit\ValidationKit\Schemas\Collections\RecordSchema;
use StusDevKit\ValidationKit\Schemas\Collections\TupleSchema;
use StusDevKit\ValidationKit\Schemas\DevKit\WhenSchema;
use StusDevKit\ValidationKit\Schemas\Logic\AllOfSchema;
use StusDevKit\ValidationKit\Schemas\Logic\AnyOfSchema;
use StusDevKit\ValidationKit\Schemas\Logic\ConditionalSchema;
use StusDevKit\ValidationKit\Schemas\Logic\DiscriminatedAnyOfSchema;
use StusDevKit\ValidationKit\Schemas\Logic\EnumSchema;
use StusDevKit\ValidationKit\Schemas\Logic\NotSchema;
use StusDevKit\ValidationKit\Schemas\Logic\OneOfSchema;
use StusDevKit\ValidationKit\Schemas\UuidSchema;
use StusDevKit\ValidationKit\ValidationIssue;

/**
 * Validate is the main entry point for creating validation
 * schemas. It provides static factory methods for all
 * available schema types.
 *
 * Usage:
 *
 *     use StusDevKit\ValidationKit\Validate;
 *
 *     // primitive schemas
 *     $name = Validate::string()->min(length: 1);
 *     $age = Validate::int()->gte(value: 0);
 *     $price = Validate::float()->positive();
 *     $score = Validate::number()->gte(value: 0);
 *     $active = Validate::boolean();
 *     $nothing = Validate::null();
 *     $anything = Validate::mixed();
 *     $status = Validate::literal('active');
 *
 *     // parse and validate
 *     $result = $name->safeParse($input);
 *     if ($result->succeeded()) {
 *         echo $result->data();
 *     }
 *
 *     // or throw on failure
 *     $validated = $name->parse($input);
 *
 * @phpstan-type ErrorCallback callable(mixed): ValidationIssue
 */
final class Validate
{
    /**
     * Validate is a static factory and should not be
     * instantiated.
     */
    private function __construct()
    {
    }

    // ================================================================
    //
    // Nullability Schema Factories
    //
    // ----------------------------------------------------------------

    /**
     * wrap a schema to allow null values
     *
     * If the input is null, null is returned without
     * delegating to the inner schema.
     *
     * @template T
     * @param ValidationSchema<T> $schema
     * @return NullableSchema<T>
     */
    public static function nullable(
        ValidationSchema $schema,
    ): NullableSchema {
        return new NullableSchema(innerSchema: $schema);
    }

    /**
     * wrap a schema to allow null or missing values
     *
     * If the input is null (or the key is missing in an
     * object schema), null is returned without delegating
     * to the inner schema.
     *
     * @template T
     * @param ValidationSchema<T> $schema
     * @return OptionalSchema<T>
     */
    public static function optional(
        ValidationSchema $schema,
    ): OptionalSchema {
        return new OptionalSchema(innerSchema: $schema);
    }

    /**
     * wrap a schema to allow null or missing values
     *
     * Combines the behaviour of nullable() and optional():
     * the value can be null or missing entirely.
     *
     * @template T
     * @param ValidationSchema<T> $schema
     * @return NullishSchema<T>
     */
    public static function nullish(
        ValidationSchema $schema,
    ): NullishSchema {
        return new NullishSchema(innerSchema: $schema);
    }

    // ================================================================
    //
    // Primitive Schema Factories
    //
    // ----------------------------------------------------------------

    /**
     * create a string validation schema
     *
     * @param ErrorCallback|null $error
     * - optional error callback for type-check failures
     */
    public static function string(
        ?callable $error = null,
    ): StringSchema {
        return new StringSchema(typeCheckError: $error);
    }

    /**
     * create an integer validation schema
     *
     * @param ErrorCallback|null $error
     * - optional error callback for type-check failures
     */
    public static function int(
        ?callable $error = null,
    ): IntSchema {
        return new IntSchema(typeCheckError: $error);
    }

    /**
     * create a float validation schema
     *
     * @param ErrorCallback|null $error
     * - optional error callback for type-check failures
     */
    public static function float(
        ?callable $error = null,
    ): FloatSchema {
        return new FloatSchema(typeCheckError: $error);
    }

    /**
     * create a number (int|float) validation schema
     *
     * @param ErrorCallback|null $error
     * - optional error callback for type-check failures
     */
    public static function number(
        ?callable $error = null,
    ): NumberSchema {
        return new NumberSchema(typeCheckError: $error);
    }

    /**
     * create a boolean validation schema
     *
     * @param ErrorCallback|null $error
     * - optional error callback for type-check failures
     */
    public static function boolean(
        ?callable $error = null,
    ): BooleanSchema {
        return new BooleanSchema(typeCheckError: $error);
    }

    /**
     * create a null validation schema
     *
     * Validates that the input is exactly null.
     *
     * @param ErrorCallback|null $error
     * - optional error callback for type-check failures
     */
    public static function null(
        ?callable $error = null,
    ): NullSchema {
        return new NullSchema(typeCheckError: $error);
    }

    /**
     * create a mixed validation schema
     *
     * Accepts any value including null. Useful as a
     * passthrough or with withCustomConstraint() for custom
     * validation.
     */
    public static function mixed(): MixedSchema
    {
        return new MixedSchema();
    }

    /**
     * create a literal validation schema
     *
     * Validates that the input is exactly equal to the
     * given value (strict comparison).
     *
     * @param ErrorCallback|null $error
     * - optional error callback for type-check failures
     */
    public static function literal(
        mixed $value,
        ?callable $error = null,
    ): LiteralSchema {
        return new LiteralSchema(
            expectedValue: $value,
            typeCheckError: $error,
        );
    }

    // ================================================================
    //
    // Collection Schema Factories
    //
    // ----------------------------------------------------------------

    /**
     * create an array validation schema
     *
     * Validates that the input is an array and each element
     * matches the given element schema.
     *
     * @template TElement
     * @param ValidationSchema<TElement> $element
     * - the schema to validate each element against
     * @param ErrorCallback|null $error
     * - optional error callback for type-check failures
     * @return ArraySchema<TElement>
     */
    public static function array(
        ValidationSchema $element,
        ?callable $error = null,
    ): ArraySchema {
        return new ArraySchema(
            elementSchema: $element,
            typeCheckError: $error,
        );
    }

    /**
     * create an object validation schema
     *
     * Validates an associative array against a defined
     * shape where each key maps to a schema.
     *
     * @param array<string, ValidationSchema<mixed>> $shape
     * - map of key names to their validation schemas
     * @param ErrorCallback|null $error
     * - optional error callback for type-check failures
     */
    public static function object(
        array $shape,
        ?callable $error = null,
    ): ObjectSchema {
        return new ObjectSchema(
            shape: $shape,
            typeCheckError: $error,
        );
    }

    /**
     * create a record validation schema
     *
     * Validates an associative array where all keys are
     * validated against one schema and all values against
     * another. Unlike object(), the set of keys is not
     * fixed.
     *
     * @template TKey of array-key
     * @template TValue
     * @param ValidationSchema<TKey> $key
     * - schema for validating keys
     * @param ValidationSchema<TValue> $value
     * - schema for validating values
     * @param ErrorCallback|null $error
     * - optional error callback for type-check failures
     * @return RecordSchema<TKey, TValue>
     */
    public static function record(
        ValidationSchema $key,
        ValidationSchema $value,
        ?callable $error = null,
    ): RecordSchema {
        return new RecordSchema(
            keySchema: $key,
            valueSchema: $value,
            typeCheckError: $error,
        );
    }

    /**
     * create a tuple validation schema
     *
     * Validates a fixed-length array where each position
     * has its own schema.
     *
     * @param list<ValidationSchema<mixed>> $schemas
     * - one schema per tuple position, in order
     * @param ErrorCallback|null $error
     * - optional error callback for type-check failures
     */
    public static function tuple(
        array $schemas,
        ?callable $error = null,
    ): TupleSchema {
        return new TupleSchema(
            schemas: $schemas,
            typeCheckError: $error,
        );
    }

    // ================================================================
    //
    // Logic Schema Factories
    //
    // ----------------------------------------------------------------

    /**
     * create an anyOf validation schema ("or" logic)
     *
     * Validates that the input matches at least one of the
     * given schemas. Schemas are tried in order; the first
     * match wins.
     *
     * @param list<ValidationSchema<mixed>> $schemas
     * - the schemas to try
     * @param ErrorCallback|null $error
     * - optional error callback when no schema matches
     */
    public static function anyOf(
        array $schemas,
        ?callable $error = null,
    ): AnyOfSchema {
        return new AnyOfSchema(
            schemas: $schemas,
            typeCheckError: $error,
        );
    }

    /**
     * create an allOf validation schema ("and" logic)
     *
     * Validates that the input matches all of the given
     * schemas. Primarily useful for combining object
     * schemas.
     *
     * @param list<ValidationSchema<mixed>> $schemas
     * - the schemas that must all pass
     * @param ErrorCallback|null $error
     * - optional error callback for type-check failures
     */
    public static function allOf(
        array $schemas,
        ?callable $error = null,
    ): AllOfSchema {
        return new AllOfSchema(
            schemas: $schemas,
            typeCheckError: $error,
        );
    }

    /**
     * create a discriminated union validation schema
     *
     * Validates that the input matches one of the given
     * schemas, selected by a discriminator field value.
     * More efficient than union() for object schemas with
     * a common type field.
     *
     * @param non-empty-string $discriminator
     * - the key used to select the schema
     * @param list<ValidationSchema<mixed>> $schemas
     * - the schemas to choose from
     * @param ErrorCallback|null $error
     * - optional error callback when no schema matches
     */
    public static function discriminatedAnyOf(
        string $discriminator,
        array $schemas,
        ?callable $error = null,
    ): DiscriminatedAnyOfSchema {
        return new DiscriminatedAnyOfSchema(
            discriminator: $discriminator,
            schemas: $schemas,
            typeCheckError: $error,
        );
    }

    /**
     * create a oneOf validation schema
     *
     * Validates that the input matches exactly one of the
     * given schemas. If zero or more than one match,
     * validation fails.
     *
     * @param list<ValidationSchema<mixed>> $schemas
     * - the schemas to check against
     * @param ErrorCallback|null $error
     * - optional error callback when validation fails
     */
    public static function oneOf(
        array $schemas,
        ?callable $error = null,
    ): OneOfSchema {
        return new OneOfSchema(
            schemas: $schemas,
            typeCheckError: $error,
        );
    }

    /**
     * create a not validation schema
     *
     * Validates that the input does NOT match the given
     * schema. If the schema accepts the data, validation
     * fails.
     *
     * @param ValidationSchema<mixed> $schema
     * - the schema that must not match
     * @param ErrorCallback|null $error
     * - optional error callback when validation fails
     */
    public static function not(
        ValidationSchema $schema,
        ?callable $error = null,
    ): NotSchema {
        return new NotSchema(
            schema: $schema,
            typeCheckError: $error,
        );
    }

    /**
     * create a conditional validation schema
     *
     * Evaluates the if schema as a condition. If it passes,
     * the then schema is applied. If it fails, the else
     * schema is applied. Both then and else are optional.
     *
     * @param ValidationSchema<mixed> $if
     * - the condition schema
     * @param ValidationSchema<mixed>|null $then
     * - schema to apply when condition passes
     * @param ValidationSchema<mixed>|null $else
     * - schema to apply when condition fails
     */
    public static function conditional(
        ValidationSchema $if,
        ?ValidationSchema $then = null,
        ?ValidationSchema $else = null,
    ): ConditionalSchema {
        return new ConditionalSchema(
            if: $if,
            then: $then,
            else: $else,
        );
    }

    /**
     * create an enum validation schema
     *
     * In string literal mode, validates that the input is
     * one of the listed values. In PHP enum mode, validates
     * the backing value and returns the enum case.
     *
     * @param list<string|int>|class-string<BackedEnum> $valuesOrEnumClass
     * - either a list of allowed values or a BackedEnum
     *   class name
     * @param ErrorCallback|null $error
     * - optional error callback for invalid values
     */
    public static function enum(
        array|string $valuesOrEnumClass,
        ?callable $error = null,
    ): EnumSchema {
        return new EnumSchema(
            valuesOrEnumClass: $valuesOrEnumClass,
            typeCheckError: $error,
        );
    }

    // ================================================================
    //
    // Specialized Schema Factories
    //
    // ----------------------------------------------------------------

    /**
     * create a UUID validation schema
     *
     * Validates that the input is a valid UUID string in
     * standard 8-4-4-4-12 format (case-insensitive).
     *
     * @param ErrorCallback|null $error
     * - optional error callback for type-check failures
     */
    public static function uuid(
        ?callable $error = null,
    ): UuidSchema {
        return new UuidSchema(typeCheckError: $error);
    }

    /**
     * create a DateTime validation schema
     *
     * Validates that the input is a DateTimeInterface
     * instance. With coerce(), also accepts ISO 8601
     * strings and Unix timestamps.
     *
     * @param ErrorCallback|null $error
     * - optional error callback for type-check failures
     */
    public static function dateTime(
        ?callable $error = null,
    ): DateTimeInterfaceSchema {
        return new DateTimeInterfaceSchema(typeCheckError: $error);
    }

    /**
     * create a When validation schema
     *
     * Validates that the input is a When instance. With
     * coerce(), also accepts date strings, other
     * DateTimeInterface instances, and Unix timestamps.
     *
     * @param ErrorCallback|null $error
     * - optional error callback for type-check failures
     */
    public static function when(
        ?callable $error = null,
    ): WhenSchema {
        return new WhenSchema(typeCheckError: $error);
    }

    /**
     * create an instanceof validation schema
     *
     * Validates that the input is an instance of the given
     * class or interface.
     *
     * @template T of object
     * @param class-string<T> $class
     * @param ErrorCallback|null $error
     * - optional error callback for type-check failures
     * @return InstanceOfSchema<T>
     */
    public static function instanceOf(
        string $class,
        ?callable $error = null,
    ): InstanceOfSchema {
        return new InstanceOfSchema(
            className: $class,
            typeCheckError: $error,
        );
    }

    // ================================================================
    //
    // Codec Factories
    //
    // ----------------------------------------------------------------

    /**
     * create a bidirectional codec schema
     *
     * A codec bridges an input type and an output type,
     * providing validated decode (input → output) and
     * encode (output → input) operations.
     *
     * @template TInput
     * @template TOutput
     * @param ValidationSchema<TInput> $input
     * - schema that validates the serialised (input)
     *   representation
     * @param ValidationSchema<TOutput> $output
     * - schema that validates the native (output)
     *   representation
     * @param Closure(TInput): TOutput $decode
     * - transforms input type to output type
     * @param Closure(TOutput): TInput $encode
     * - transforms output type to input type
     * @return Codec<TInput, TOutput>
     */
    public static function codec(
        ValidationSchema $input,
        ValidationSchema $output,
        Closure $decode,
        Closure $encode,
    ): Codec {
        return new Codec(
            inputSchema: $input,
            outputSchema: $output,
            decoder: $decode,
            encoder: $encode,
        );
    }
}
