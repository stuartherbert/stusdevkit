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

namespace StusDevKit\ValidationKit\Schemas\Logic;

use StusDevKit\ValidationKit\Contracts\ValidationSchema;
use StusDevKit\ValidationKit\Internals\ValidationContext;
use StusDevKit\ValidationKit\Schemas\BaseSchema;
use StusDevKit\ValidationKit\ValidationIssue;

use function StusDevKit\MissingBitsKit\object_merge;

/**
 * AllOfSchema validates that the input matches all of
 * the given schemas ("and" logic).
 *
 * All schemas are run and all issues from all are
 * collected. Primarily useful for combining object
 * schemas.
 *
 * Usage:
 *
 *     use StusDevKit\ValidationKit\Validate;
 *
 *     $hasName = Validate::object([
 *         'name' => Validate::string(),
 *     ]);
 *     $hasAge = Validate::object([
 *         'age' => Validate::int(),
 *     ]);
 *     $person = Validate::allOf([$hasName, $hasAge]);
 *     $person->parse(['name' => 'Stuart', 'age' => 42]);
 *
 * @extends BaseSchema<mixed>
 */
class AllOfSchema extends BaseSchema
{
    /**
     * schema for unevaluated properties
     *
     * @var ValidationSchema<mixed>|false|null
     */
    private ValidationSchema|false|null $unevaluatedPropertiesSchema = null;

    /**
     * schema for unevaluated items
     *
     * @var ValidationSchema<mixed>|false|null
     */
    private ValidationSchema|false|null $unevaluatedItemsSchema = null;

    /**
     * @param list<ValidationSchema<mixed>> $schemas
     * - the schemas that must all pass
     * @param (callable(mixed): ValidationIssue)|null $typeCheckError
     */
    public function __construct(
        private readonly array $schemas,
        ?callable $typeCheckError = null,
    ) {
        parent::__construct();

        $this->typeCheckError = $typeCheckError
            ?? $this->getDefaultTypeCheckError();
    }

    // ================================================================
    //
    // Default Error Callbacks
    //
    // ----------------------------------------------------------------

    protected function getDefaultTypeCheckError(): callable
    {
        return static fn(mixed $data) => new ValidationIssue(
            type: 'https://stusdevkit.dev/errors/validation/invalid_type',
            input: $data,
            path: [],
            message: 'Expected intersection, received '
                . get_debug_type($data),
        );
    }

    // ================================================================
    //
    // Introspection
    //
    // ----------------------------------------------------------------

    /**
     * return the intersection member schemas
     *
     * @return list<ValidationSchema<mixed>>
     */
    public function schemas(): array
    {
        return $this->schemas;
    }

    /**
     * @return ValidationSchema<mixed>|false|null
     */
    public function maybeUnevaluatedPropertiesSchema(): ValidationSchema|false|null
    {
        return $this->unevaluatedPropertiesSchema;
    }

    /**
     * @param ValidationSchema<mixed>|false $schema
     */
    public function unevaluatedProperties(
        ValidationSchema|false $schema,
    ): static {
        $clone = clone $this;
        $clone->unevaluatedPropertiesSchema = $schema;

        return $clone;
    }

    /**
     * @return ValidationSchema<mixed>|false|null
     */
    public function maybeUnevaluatedItemsSchema(): ValidationSchema|false|null
    {
        return $this->unevaluatedItemsSchema;
    }

    /**
     * @param ValidationSchema<mixed>|false $schema
     */
    public function unevaluatedItems(
        ValidationSchema|false $schema,
    ): static {
        $clone = clone $this;
        $clone->unevaluatedItemsSchema = $schema;

        return $clone;
    }

    // ================================================================
    //
    // BaseSchema Implementation
    //
    // ----------------------------------------------------------------

    protected function checkType(
        mixed $data,
        ValidationContext $context,
    ): bool {
        // child schemas handle type checking
        return true;
    }

    /**
     * validate against all schemas; issues from all are
     * collected into the same context
     *
     * After running all sub-schemas, checks for
     * unevaluated properties or items if those keywords
     * are set.
     */
    protected function validateChildren(
        mixed $data,
        ValidationContext $context,
    ): mixed {
        // clone objects to avoid mutating the original input
        $result = is_object($data) ? clone $data : $data;
        foreach ($this->schemas as $schema) {
            $schemaResult = $schema->parseWithContext(
                data: $data,
                context: $context,
            );

            // merge results when both are the same
            // collection type (array or object)
            if (is_array($result) && is_array($schemaResult)) {
                $result = array_merge($result, $schemaResult);
            } elseif (is_object($result) && is_object($schemaResult)) {
                object_merge($result, $schemaResult);
            } else {
                $result = $schemaResult;
            }
        }

        // check unevaluated properties
        if (
            $this->unevaluatedPropertiesSchema !== null
            && (is_array($data) || is_object($data))
        ) {
            $result = $this->checkUnevaluatedProperties(
                data: $data,
                result: $result,
                context: $context,
                encode: false,
            );
        }

        // check unevaluated items
        if (
            $this->unevaluatedItemsSchema !== null
            && is_array($data)
        ) {
            $result = $this->checkUnevaluatedItems(
                data: $data,
                result: $result,
                context: $context,
                encode: false,
            );
        }

        return $result;
    }

    /**
     * encode against all schemas
     */
    protected function encodeChildren(
        mixed $data,
        ValidationContext $context,
    ): mixed {
        // clone objects to avoid mutating the original input
        $result = is_object($data) ? clone $data : $data;
        foreach ($this->schemas as $schema) {
            $schemaResult = $schema->encodeWithContext(
                data: $data,
                context: $context,
            );

            // merge results when both are the same
            // collection type (array or object)
            if (is_array($result) && is_array($schemaResult)) {
                $result = array_merge($result, $schemaResult);
            } elseif (is_object($result) && is_object($schemaResult)) {
                object_merge($result, $schemaResult);
            } else {
                $result = $schemaResult;
            }
        }

        // check unevaluated properties
        if (
            $this->unevaluatedPropertiesSchema !== null
            && (is_array($data) || is_object($data))
        ) {
            $result = $this->checkUnevaluatedProperties(
                data: $data,
                result: $result,
                context: $context,
                encode: true,
            );
        }

        // check unevaluated items
        if (
            $this->unevaluatedItemsSchema !== null
            && is_array($data)
        ) {
            $result = $this->checkUnevaluatedItems(
                data: $data,
                result: $result,
                context: $context,
                encode: true,
            );
        }

        return $result;
    }

    // ================================================================
    //
    // Unevaluated Helpers
    //
    // ----------------------------------------------------------------

    /**
     * check for unevaluated properties in object data
     */
    private function checkUnevaluatedProperties(
        mixed $data,
        mixed $result,
        ValidationContext $context,
        bool $encode,
    ): mixed {
        assert(
            $this->unevaluatedPropertiesSchema !== null,
        );

        /** @var array<string, mixed> $properties */
        $properties = is_object($data)
            ? get_object_vars($data)
            : $data;

        foreach ($properties as $key => $value) {
            if ($context->isEvaluated($key)) {
                continue;
            }

            if ($this->unevaluatedPropertiesSchema === false) {
                $context->addIssue(
                    type: 'https://stusdevkit.dev/errors/validation/unrecognized_keys',
                    input: $value,
                    message: 'Unevaluated property: '
                        . $key,
                );
            } else {
                $childContext = $context->atPath($key);
                $validatedValue = $encode
                    ? $this->unevaluatedPropertiesSchema
                        ->encodeWithContext(
                            data: $value,
                            context: $childContext,
                        )
                    : $this->unevaluatedPropertiesSchema
                        ->parseWithContext(
                            data: $value,
                            context: $childContext,
                        );
                $context->markEvaluated($key);

                if (is_object($result)) {
                    $result->$key = $validatedValue;
                } elseif (is_array($result)) {
                    $result[$key] = $validatedValue;
                }
            }
        }

        return $result;
    }

    /**
     * check for unevaluated items in array data
     *
     * @param array<array-key, mixed> $data
     */
    private function checkUnevaluatedItems(
        array $data,
        mixed $result,
        ValidationContext $context,
        bool $encode,
    ): mixed {
        assert(is_array($result));
        assert(
            $this->unevaluatedItemsSchema !== null,
        );

        foreach ($data as $index => $value) {
            if ($context->isEvaluated($index)) {
                continue;
            }

            if ($this->unevaluatedItemsSchema === false) {
                $context->addIssue(
                    type: 'https://stusdevkit.dev/errors/validation/too_big',
                    input: $value,
                    message: 'Unevaluated item at index: '
                        . $index,
                );
            } else {
                $childContext = $context->atPath($index);
                $validatedValue = $encode
                    ? $this->unevaluatedItemsSchema
                        ->encodeWithContext(
                            data: $value,
                            context: $childContext,
                        )
                    : $this->unevaluatedItemsSchema
                        ->parseWithContext(
                            data: $value,
                            context: $childContext,
                        );
                $context->markEvaluated($index);
                $result[$index] = $validatedValue;
            }
        }

        return $result;
    }
}
