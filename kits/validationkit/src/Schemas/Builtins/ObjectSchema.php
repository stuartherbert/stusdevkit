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

namespace StusDevKit\ValidationKit\Schemas\Builtins;

use StusDevKit\ValidationKit\Constraints\ObjectDependentSchemasConstraint;
use StusDevKit\ValidationKit\Constraints\ObjectPatternPropertiesConstraint;
use StusDevKit\ValidationKit\Constraints\ObjectPropertyNamesConstraint;
use StusDevKit\ValidationKit\Contracts\ValidationSchema;
use StusDevKit\ValidationKit\Internals\ValidationContext;
use StusDevKit\ValidationKit\Schemas\BaseSchema;
use StusDevKit\ValidationKit\ValidationIssue;

/**
 * ObjectSchema validates associative arrays against a
 * defined shape where each key maps to a schema.
 *
 * This is the PHP equivalent of Zod's z.object(). In PHP,
 * "objects" in the Zod sense are associative arrays.
 *
 * Usage:
 *
 *     use StusDevKit\ValidationKit\Validate;
 *
 *     $userSchema = Validate::object([
 *         'name' => Validate::string()->min(length: 1),
 *         'age' => Validate::int()->gte(value: 0),
 *         'email' => Validate::string()->email(),
 *     ]);
 *
 *     $user = $userSchema->parse([
 *         'name' => 'Stuart',
 *         'age' => 42,
 *         'email' => 'stuart@example.com',
 *     ]);
 *
 *     // shape modification
 *     $withBio = $userSchema->extend([
 *         'bio' => Validate::optional(Validate::string()),
 *     ]);
 *
 *     $nameOnly = $userSchema->pick('name');
 *     $noEmail = $userSchema->omit('email');
 *     $allOptional = $userSchema->partial();
 *
 * @extends BaseSchema<array<string, mixed>>
 */
class ObjectSchema extends BaseSchema
{
    /**
     * how to handle keys in the input that are not in
     * the shape
     *
     * - 'strip': silently remove unknown keys (default)
     * - 'strict': reject unknown keys with an error
     * - 'passthrough': keep unknown keys in the output
     */
    private string $unknownKeyPolicy = 'strip';

    /**
     * if set, unknown keys are validated against this
     * schema instead of being handled by unknownKeyPolicy
     *
     * @var ValidationSchema<mixed>|null
     */
    private ?ValidationSchema $catchallSchema = null;

    /**
     * @param array<string, ValidationSchema<mixed>> $shape
     * - map of key names to their validation schemas
     * @param (callable(mixed): ValidationIssue)|null $typeCheckError
     */
    public function __construct(
        private array $shape,
        ?callable $typeCheckError = null,
    ) {
        parent::__construct();

        $this->typeCheckError = $typeCheckError
            ?? $this->getDefaultTypeCheckErrorCallbackForConstructor();
    }

    // ================================================================
    //
    // Default Error Callbacks
    //
    // ----------------------------------------------------------------

    protected function getDefaultTypeCheckErrorCallbackForConstructor(): callable
    {
        return static fn(mixed $data) => new ValidationIssue(
            type: 'https://stusdevkit.dev/errors/validation/invalid_type',
            input: $data,
            path: [],
            message: 'Expected object (associative array), received '
                . get_debug_type($data),
        );
    }

    // ================================================================
    //
    // Shape Modification Methods
    //
    // ----------------------------------------------------------------

    /**
     * add additional fields to the shape
     *
     * Returns a new schema with the current shape plus
     * the additional fields. Existing fields with the
     * same key are overwritten.
     *
     * @param array<string, ValidationSchema<mixed>> $additionalShape
     */
    public function extend(array $additionalShape): static
    {
        $clone = clone $this;
        $clone->shape = array_merge(
            $clone->shape,
            $additionalShape,
        );

        return $clone;
    }

    /**
     * keep only the specified keys from the shape
     *
     * Returns a new schema with only the listed keys.
     */
    public function pick(string ...$keys): static
    {
        $clone = clone $this;
        $clone->shape = array_intersect_key(
            $clone->shape,
            array_flip($keys),
        );

        return $clone;
    }

    /**
     * remove the specified keys from the shape
     *
     * Returns a new schema without the listed keys.
     */
    public function omit(string ...$keys): static
    {
        $clone = clone $this;
        $clone->shape = array_diff_key(
            $clone->shape,
            array_flip($keys),
        );

        return $clone;
    }

    /**
     * make all fields optional
     *
     * Returns a new schema where every field in the shape
     * is wrapped with OptionalSchema.
     */
    public function partial(): static
    {
        $clone = clone $this;
        $newShape = [];
        foreach ($clone->shape as $key => $schema) {
            $newShape[$key] = new OptionalSchema(
                innerSchema: $schema,
            );
        }
        $clone->shape = $newShape;

        return $clone;
    }

    /**
     * make all fields required (undo partial)
     *
     * Returns a new schema where any OptionalSchema
     * wrappers are unwrapped, making all fields required
     * again.
     */
    public function required(): static
    {
        $clone = clone $this;
        $newShape = [];
        foreach ($clone->shape as $key => $schema) {
            $newShape[$key] = $schema instanceof OptionalSchema
                ? $schema->unwrap()
                : $schema;
        }
        $clone->shape = $newShape;

        return $clone;
    }

    /**
     * return an enum of the shape's keys
     *
     * @return list<string>
     */
    public function keyof(): array
    {
        return array_keys($this->shape);
    }

    /**
     * return the schema for a given shape key, or null
     * if the key is not in the shape
     *
     * @return ValidationSchema<mixed>|null
     */
    public function maybeFieldSchema(string $key): ?ValidationSchema
    {
        return $this->shape[$key] ?? null;
    }

    // ================================================================
    //
    // Unknown Key Policy Methods
    //
    // ----------------------------------------------------------------

    /**
     * reject input that contains keys not in the shape
     *
     * An UnrecognizedKeys issue is added for each unknown
     * key found.
     */
    public function strict(): static
    {
        $clone = clone $this;
        $clone->unknownKeyPolicy = 'strict';
        $clone->catchallSchema = null;

        return $clone;
    }

    /**
     * silently remove keys not in the shape (default)
     */
    public function strip(): static
    {
        $clone = clone $this;
        $clone->unknownKeyPolicy = 'strip';
        $clone->catchallSchema = null;

        return $clone;
    }

    /**
     * keep unknown keys in the output unchanged
     */
    public function passthrough(): static
    {
        $clone = clone $this;
        $clone->unknownKeyPolicy = 'passthrough';
        $clone->catchallSchema = null;

        return $clone;
    }

    /**
     * validate unknown keys against the given schema
     *
     * If set, unknown keys are validated against this
     * schema instead of being stripped, rejected, or
     * passed through.
     *
     * @param ValidationSchema<mixed> $schema
     */
    public function catchall(ValidationSchema $schema): static
    {
        $clone = clone $this;
        $clone->catchallSchema = $schema;

        return $clone;
    }

    // ================================================================
    //
    // JSON Schema Constraint Builder Methods
    //
    // ----------------------------------------------------------------

    /**
     * validate that all property names satisfy the given
     * schema
     *
     * @param ValidationSchema<mixed> $schema
     * @param (callable(mixed): ValidationIssue)|null $error
     */
    public function propertyNames(
        ValidationSchema $schema,
        ?callable $error = null,
    ): static {
        return $this->withConstraint(
            new ObjectPropertyNamesConstraint(
                schema: $schema,
                error: $error,
            ),
        );
    }

    /**
     * validate properties whose names match regex patterns
     * against corresponding schemas
     *
     * @param array<string, ValidationSchema<mixed>> $patterns
     * - maps regex patterns to validation schemas
     */
    public function patternProperties(
        array $patterns,
    ): static {
        return $this->withConstraint(
            new ObjectPatternPropertiesConstraint(
                patterns: $patterns,
            ),
        );
    }

    /**
     * apply additional schemas when certain properties
     * are present
     *
     * When a property named in $dependencies exists in
     * the input, the corresponding schema is applied to
     * the entire object.
     *
     * @param array<string, ValidationSchema<mixed>> $dependencies
     * - maps property names to schemas
     */
    public function dependentSchemas(
        array $dependencies,
    ): static {
        return $this->withConstraint(
            new ObjectDependentSchemasConstraint(
                dependencies: $dependencies,
            ),
        );
    }

    // ================================================================
    //
    // BaseSchema Implementation
    //
    // ----------------------------------------------------------------

    protected function expectedType(): string
    {
        return 'object';
    }

    protected function checkType(
        mixed $data,
        ValidationContext $context,
    ): bool {
        if (is_array($data)) {
            return true;
        }

        $this->invokeErrorCallback(
            callback: $this->typeCheckError,
            input: $data,
            context: $context,
        );

        return false;
    }

    /**
     * validate shape fields and rebuild the output array
     *
     * Each field in the shape is validated against its
     * schema. Unknown keys are handled according to the
     * unknownKeyPolicy or catchall schema.
     */
    protected function validateChildren(
        mixed $data,
        ValidationContext $context,
    ): mixed {
        assert(is_array($data));

        $output = [];

        foreach ($this->shape as $key => $fieldSchema) {
            $childContext = $context->atPath($key);

            $fieldValue = array_key_exists($key, $data)
                ? $data[$key]
                : null;

            $validatedValue = $fieldSchema->parseWithContext(
                data: $fieldValue,
                context: $childContext,
            );

            $output[$key] = $validatedValue;
        }

        // handle unknown keys
        /** @var array<string, mixed> $unknownKeys */
        $unknownKeys = array_diff_key($data, $this->shape);

        if (count($unknownKeys) > 0) {
            $this->handleUnknownKeys(
                unknownKeys: $unknownKeys,
                context: $context,
            );

            // passthrough or catchall: include unknown keys
            // in the output
            if ($this->unknownKeyPolicy === 'passthrough') {
                $output = array_merge($output, $unknownKeys);
            } elseif ($this->catchallSchema !== null) {
                foreach ($unknownKeys as $key => $value) {
                    $childContext = $context->atPath($key);
                    $validatedValue = $this->catchallSchema
                        ->parseWithContext(
                            data: $value,
                            context: $childContext,
                        );
                    $output[$key] = $validatedValue;
                }
            }
        }

        return $output;
    }

    /**
     * encode child values using the encode pipeline
     *
     * Like validateChildren(), but calls
     * encodeWithContext() on each field schema so that
     * codecs run their encode path (output → input).
     */
    protected function encodeChildren(
        mixed $data,
        ValidationContext $context,
    ): mixed {
        assert(is_array($data));

        $output = [];

        foreach ($this->shape as $key => $fieldSchema) {
            $childContext = $context->atPath($key);

            $fieldValue = array_key_exists($key, $data)
                ? $data[$key]
                : null;

            $validatedValue = $fieldSchema->encodeWithContext(
                data: $fieldValue,
                context: $childContext,
            );

            $output[$key] = $validatedValue;
        }

        // handle unknown keys
        /** @var array<string, mixed> $unknownKeys */
        $unknownKeys = array_diff_key($data, $this->shape);

        if (count($unknownKeys) > 0) {
            $this->handleUnknownKeys(
                unknownKeys: $unknownKeys,
                context: $context,
            );

            // passthrough or catchall: include unknown keys
            // in the output
            if ($this->unknownKeyPolicy === 'passthrough') {
                $output = array_merge($output, $unknownKeys);
            } elseif ($this->catchallSchema !== null) {
                foreach ($unknownKeys as $key => $value) {
                    $childContext = $context->atPath($key);
                    $validatedValue = $this->catchallSchema
                        ->encodeWithContext(
                            data: $value,
                            context: $childContext,
                        );
                    $output[$key] = $validatedValue;
                }
            }
        }

        return $output;
    }

    // ================================================================
    //
    // Helpers
    //
    // ----------------------------------------------------------------

    /**
     * handle keys in the input that are not in the shape
     *
     * @param array<string, mixed> $unknownKeys
     */
    private function handleUnknownKeys(
        array $unknownKeys,
        ValidationContext $context,
    ): void {
        if ($this->catchallSchema !== null) {
            // catchall handles unknown keys — validation
            // happens in parseWithContext
            return;
        }

        switch ($this->unknownKeyPolicy) {
            case 'strict':
                $keyNames = implode(', ', array_keys($unknownKeys));
                $context->addIssue(
                    type: 'https://stusdevkit.dev/errors/validation/unrecognized_keys',
                    input: $unknownKeys,
                    message: 'Unrecognized keys: ' . $keyNames,
                );
                break;

            case 'strip':
            case 'passthrough':
                // no error needed
                break;
        }
    }
}
