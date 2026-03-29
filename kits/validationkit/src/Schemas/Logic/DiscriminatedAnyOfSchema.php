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
use StusDevKit\ValidationKit\Schemas\Builtins\LiteralSchema;
use StusDevKit\ValidationKit\Schemas\Builtins\ObjectSchema;
use StusDevKit\ValidationKit\ValidationIssue;

/**
 * DiscriminatedAnyOfSchema validates that the input
 * matches one of the given schemas, selected by a
 * discriminator field.
 *
 * This is more efficient than AnyOfSchema because it
 * looks at the discriminator field value to pick the
 * correct schema directly, rather than trying each
 * schema in sequence.
 *
 * Usage:
 *
 *     use StusDevKit\ValidationKit\Validate;
 *
 *     $event = Validate::discriminatedAnyOf('type', [
 *         Validate::object([
 *             'type' => Validate::literal('click'),
 *             'x' => Validate::int(),
 *             'y' => Validate::int(),
 *         ]),
 *         Validate::object([
 *             'type' => Validate::literal('keypress'),
 *             'key' => Validate::string(),
 *         ]),
 *     ]);
 *
 *     $event->parse(['type' => 'click', 'x' => 10, 'y' => 20]);
 *
 * @extends BaseSchema<mixed>
 */
class DiscriminatedAnyOfSchema extends BaseSchema
{
    /**
     * map of discriminator values to their schemas,
     * built at construction time for O(1) lookup
     *
     * @var array<string|int, ValidationSchema<mixed>>
     */
    private readonly array $schemaMap;

    /**
     * @param non-empty-string $discriminator
     * - the key in the input array used to select the
     *   schema
     * @param list<ValidationSchema<mixed>> $schemas
     * - the schemas to choose from; each must be an
     *   ObjectSchema with a literal field for the
     *   discriminator
     * @param (callable(mixed): ValidationIssue)|null $typeCheckError
     */
    public function __construct(
        private readonly string $discriminator,
        array $schemas,
        ?callable $typeCheckError = null,
    ) {
        parent::__construct();

        $this->typeCheckError = $typeCheckError
            ?? $this->getDefaultTypeCheckErrorCallbackForConstructor();

        $this->schemaMap = $this->buildSchemaMap(
            schemas: $schemas,
        );
    }

    // ================================================================
    //
    // Default Error Callbacks
    //
    // ----------------------------------------------------------------

    protected function getDefaultTypeCheckErrorCallbackForConstructor(): callable
    {
        return fn(mixed $data) => new ValidationIssue(
            type: 'https://stusdevkit.dev/errors/validation/invalid_type',
            input: $data,
            path: [],
            message: 'Expected object with discriminator'
                . ' "' . $this->discriminator . '",'
                . ' received '
                . get_debug_type($data),
        );
    }

    // ================================================================
    //
    // BaseSchema Implementation
    //
    // ----------------------------------------------------------------

    protected function expectedType(): string
    {
        return 'discriminated union';
    }

    /**
     * null is allowed through to the child schemas
     */
    protected function acceptsNull(): bool
    {
        return true;
    }

    protected function checkType(
        mixed $data,
        ValidationContext $context,
    ): bool {
        // child schemas handle type checking
        return true;
    }

    /**
     * select the correct schema by discriminator value
     * and return its result
     */
    protected function validateChildren(
        mixed $data,
        ValidationContext $context,
    ): mixed {
        // must be an array to have a discriminator field
        if (! is_array($data)) {
            $this->invokeErrorCallback(
                callback: $this->typeCheckError,
                input: $data,
                context: $context,
            );

            return $data;
        }

        // check discriminator field exists
        if (! array_key_exists($this->discriminator, $data)) {
            $context->addIssue(
                type: 'https://stusdevkit.dev/errors/validation/invalid_union',
                input: $data,
                message: 'Missing discriminator field "'
                    . $this->discriminator . '"',
            );

            return $data;
        }

        // O(1) lookup by discriminator value
        $discriminatorValue = $data[$this->discriminator];
        $key = is_string($discriminatorValue)
            || is_int($discriminatorValue)
            ? $discriminatorValue
            : null;

        $schema = $key !== null
            ? ($this->schemaMap[$key] ?? null)
            : null;

        if ($schema === null) {
            $described = is_string($discriminatorValue)
                ? '"' . $discriminatorValue . '"'
                : get_debug_type($discriminatorValue);
            $context->addIssue(
                type: 'https://stusdevkit.dev/errors/validation/invalid_union',
                input: $data,
                message: 'Unrecognised '
                    . $this->discriminator
                    . ' value: ' . $described,
            );

            return $data;
        }

        return $schema->parseWithContext(
            data: $data,
            context: $context,
        );
    }

    /**
     * select the correct schema by discriminator value
     * and encode using it
     */
    protected function encodeChildren(
        mixed $data,
        ValidationContext $context,
    ): mixed {
        // must be an array to have a discriminator field
        if (! is_array($data)) {
            $this->invokeErrorCallback(
                callback: $this->typeCheckError,
                input: $data,
                context: $context,
            );

            return $data;
        }

        // check discriminator field exists
        if (! array_key_exists($this->discriminator, $data)) {
            $context->addIssue(
                type: 'https://stusdevkit.dev/errors/validation/invalid_union',
                input: $data,
                message: 'Missing discriminator field "'
                    . $this->discriminator . '"',
            );

            return $data;
        }

        // O(1) lookup by discriminator value
        $discriminatorValue = $data[$this->discriminator];
        $key = is_string($discriminatorValue)
            || is_int($discriminatorValue)
            ? $discriminatorValue
            : null;

        $schema = $key !== null
            ? ($this->schemaMap[$key] ?? null)
            : null;

        if ($schema === null) {
            $described = is_string($discriminatorValue)
                ? '"' . $discriminatorValue . '"'
                : get_debug_type($discriminatorValue);
            $context->addIssue(
                type: 'https://stusdevkit.dev/errors/validation/invalid_union',
                input: $data,
                message: 'Unrecognised '
                    . $this->discriminator
                    . ' value: ' . $described,
            );

            return $data;
        }

        return $schema->encodeWithContext(
            data: $data,
            context: $context,
        );
    }

    // ================================================================
    //
    // Helpers
    //
    // ----------------------------------------------------------------

    /**
     * build a map of discriminator values to schemas
     *
     * Inspects each ObjectSchema's shape to find the
     * LiteralSchema for the discriminator field, then
     * uses its expected value as the map key.
     *
     * @param list<ValidationSchema<mixed>> $schemas
     * @return array<string|int, ValidationSchema<mixed>>
     */
    private function buildSchemaMap(array $schemas): array
    {
        $map = [];

        foreach ($schemas as $schema) {
            if (! $schema instanceof ObjectSchema) {
                continue;
            }

            $fieldSchema = $schema->maybeFieldSchema(
                key: $this->discriminator,
            );

            if (! $fieldSchema instanceof LiteralSchema) {
                continue;
            }

            $value = $fieldSchema->expectedValue();
            if (is_string($value) || is_int($value)) {
                $map[$value] = $schema;
            }
        }

        return $map;
    }
}
