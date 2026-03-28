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
use StusDevKit\ValidationKit\Schemas\Collections\RecordSchema;
use StusDevKit\ValidationKit\Schemas\Collections\TupleSchema;
use StusDevKit\ValidationKit\Schemas\Logic\DiscriminatedUnionSchema;
use StusDevKit\ValidationKit\Schemas\Logic\EnumSchema;
use StusDevKit\ValidationKit\Schemas\Logic\IntersectionSchema;
use StusDevKit\ValidationKit\Schemas\Logic\UnionSchema;
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
     * @param BaseSchema<T> $schema
     * @return NullableSchema<T>
     */
    public static function nullable(
        BaseSchema $schema,
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
     * @param BaseSchema<T> $schema
     * @return OptionalSchema<T>
     */
    public static function optional(
        BaseSchema $schema,
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
     * @param BaseSchema<T> $schema
     * @return NullishSchema<T>
     */
    public static function nullish(
        BaseSchema $schema,
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
     * passthrough or with refine() for custom validation.
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
     * @param BaseSchema<TElement> $element
     * - the schema to validate each element against
     * @param ErrorCallback|null $error
     * - optional error callback for type-check failures
     * @return ArraySchema<TElement>
     */
    public static function array(
        BaseSchema $element,
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
     * @param array<string, BaseSchema<mixed>> $shape
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
     * @param BaseSchema<TKey> $key
     * - schema for validating keys
     * @param BaseSchema<TValue> $value
     * - schema for validating values
     * @param ErrorCallback|null $error
     * - optional error callback for type-check failures
     * @return RecordSchema<TKey, TValue>
     */
    public static function record(
        BaseSchema $key,
        BaseSchema $value,
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
     * @param list<BaseSchema<mixed>> $schemas
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
     * create a union validation schema ("or" logic)
     *
     * Validates that the input matches at least one of the
     * given schemas. Schemas are tried in order; the first
     * match wins.
     *
     * @param list<BaseSchema<mixed>> $schemas
     * - the schemas to try
     * @param ErrorCallback|null $error
     * - optional error callback when no schema matches
     */
    public static function union(
        array $schemas,
        ?callable $error = null,
    ): UnionSchema {
        return new UnionSchema(
            schemas: $schemas,
            typeCheckError: $error,
        );
    }

    /**
     * create an intersection validation schema ("and"
     * logic)
     *
     * Validates that the input matches both schemas.
     * Primarily useful for combining two object schemas.
     *
     * @param BaseSchema<mixed> $left
     * @param BaseSchema<mixed> $right
     * @param ErrorCallback|null $error
     * - optional error callback for type-check failures
     */
    public static function intersection(
        BaseSchema $left,
        BaseSchema $right,
        ?callable $error = null,
    ): IntersectionSchema {
        return new IntersectionSchema(
            left: $left,
            right: $right,
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
     * @param list<BaseSchema<mixed>> $schemas
     * - the schemas to choose from
     * @param ErrorCallback|null $error
     * - optional error callback when no schema matches
     */
    public static function discriminatedUnion(
        string $discriminator,
        array $schemas,
        ?callable $error = null,
    ): DiscriminatedUnionSchema {
        return new DiscriminatedUnionSchema(
            discriminator: $discriminator,
            schemas: $schemas,
            typeCheckError: $error,
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
}
