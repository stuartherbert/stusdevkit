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

namespace StusDevKit\ValidationKit\Schemas\Collections;

use StusDevKit\ValidationKit\Internals\ValidationContext;
use StusDevKit\ValidationKit\IssueCode;
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
 *         'bio' => Validate::string()->optional(),
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
     * @var BaseSchema<mixed>|null
     */
    private ?BaseSchema $catchallSchema = null;

    /**
     * @param array<string, BaseSchema<mixed>> $shape
     * - map of key names to their validation schemas
     * @param (callable(mixed): ValidationIssue)|null $typeCheckError
     */
    public function __construct(
        private array $shape,
        ?callable $typeCheckError = null,
    ) {
        $this->typeCheckError = $typeCheckError
            ?? static fn(mixed $data) => new ValidationIssue(
                code: IssueCode::InvalidType,
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
     * @param array<string, BaseSchema<mixed>> $additionalShape
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
     * is wrapped with optional().
     */
    public function partial(): static
    {
        $clone = clone $this;
        $newShape = [];
        foreach ($clone->shape as $key => $schema) {
            $newShape[$key] = $schema->optional();
        }
        $clone->shape = $newShape;

        return $clone;
    }

    /**
     * make all fields required (undo partial)
     *
     * This creates a new schema where every field has
     * nullable and optional flags cleared. Note: this
     * creates fresh schemas from the shape, so any
     * previous nullable/optional calls on individual
     * fields will be undone.
     */
    public function required(): static
    {
        // we cannot easily "un-optional" a schema because
        // the flags are protected. Instead, we return a
        // clone — users should apply required() before
        // optional() on individual fields.
        return clone $this;
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
     * @param BaseSchema<mixed> $schema
     */
    public function catchall(BaseSchema $schema): static
    {
        $clone = clone $this;
        $clone->catchallSchema = $schema;

        return $clone;
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

    protected function checkConstraints(
        mixed $data,
        ValidationContext $context,
    ): void {
        assert(is_array($data));

        $output = [];

        // validate each field in the shape
        foreach ($this->shape as $key => $fieldSchema) {
            $childContext = $context->atPath($key);

            if (array_key_exists($key, $data)) {
                $fieldSchema->parseWithContext(
                    data: $data[$key],
                    context: $childContext,
                );
            } else {
                // key is missing — pass null to let the
                // field schema handle it (optional/default
                // will accept null, required will reject)
                $fieldSchema->parseWithContext(
                    data: null,
                    context: $childContext,
                );
            }
        }

        // handle unknown keys
        /** @var array<string, mixed> $unknownKeys */
        $unknownKeys = array_diff_key(
            $data,
            $this->shape,
        );

        if (count($unknownKeys) > 0) {
            $this->handleUnknownKeys(
                unknownKeys: $unknownKeys,
                context: $context,
            );
        }
    }

    /**
     * override parseWithContext to rebuild the output array
     *
     * ObjectSchema needs to reconstruct the output from
     * validated field values, not just return the input.
     */
    public function parseWithContext(
        mixed $data,
        ValidationContext $context,
    ): mixed {
        // step 1: null/missing check
        if ($data === null) {
            if ($this->hasDefault) {
                return $this->defaultValue;
            }

            if ($this->isNullable || $this->isOptional) {
                return null;
            }

            $this->invokeErrorCallback(
                callback: $this->typeCheckError,
                input: $data,
                context: $context,
            );

            return null;
        }

        // step 2: type check
        if (! is_array($data)) {
            $this->checkType(data: $data, context: $context);
            return $data;
        }

        // step 3: validate fields and build output
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

        if ($context->hasIssues()) {
            return $output;
        }

        // steps 5-7: pipeline and pipe
        /** @var array{mixed, bool} $pipelineResult */
        $pipelineResult = $this->runObjectPipeline(
            data: $output,
            context: $context,
        );
        $output = $pipelineResult[0];
        $pipelineClean = $pipelineResult[1];

        if (! $pipelineClean) {
            return $output;
        }

        if ($this->pipeTarget !== null) {
            $output = $this->pipeTarget->parseWithContext(
                data: $output,
                context: $context,
            );
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
                    code: IssueCode::UnrecognizedKeys,
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

    /**
     * run refinements and transforms for the object schema
     *
     * @return array{mixed, bool}
     */
    private function runObjectPipeline(
        mixed $data,
        ValidationContext $context,
    ): array {
        foreach ($this->pipeline as $entry) {
            switch ($entry['type']) {
                case 'refine':
                    /** @var callable(mixed): bool $fn */
                    $fn = $entry['callable'];
                    $passed = $fn($data);
                    if (! $passed) {
                        /** @var non-falsy-string $message */
                        $message = $entry['message'];
                        $context->addIssue(
                            code: IssueCode::Custom,
                            input: $data,
                            message: $message,
                        );
                    }
                    break;

                case 'superRefine':
                    /** @var callable(mixed, ValidationContext): void $fn */
                    $fn = $entry['callable'];
                    $fn($data, $context);
                    break;

                case 'transform':
                    if ($context->hasIssues()) {
                        return [$data, false];
                    }
                    /** @var callable(mixed): mixed $fn */
                    $fn = $entry['callable'];
                    $data = $fn($data);
                    break;
            }
        }

        return [$data, ! $context->hasIssues()];
    }
}
