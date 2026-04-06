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

use StusDevKit\ValidationKit\Contracts\ValidationSchema;
use StusDevKit\ValidationKit\Internals\ValidationContext;
use StusDevKit\ValidationKit\Schemas\BaseSchema;
use StusDevKit\ValidationKit\ValidationIssue;

/**
 * TupleSchema validates a fixed-length array where each
 * position has its own schema.
 *
 * By default, the tuple enforces exact length — the input
 * must have exactly as many elements as there are prefix
 * schemas. When a rest schema is set via items(), the
 * tuple allows additional elements beyond the prefix,
 * validating each against the rest schema.
 *
 * This is the PHP equivalent of Zod's z.tuple().
 *
 * Usage:
 *
 *     use StusDevKit\ValidationKit\Validate;
 *
 *     // [string, int] tuple
 *     $point = Validate::tuple([
 *         Validate::string(),
 *         Validate::int(),
 *     ]);
 *     $point->parse(['hello', 42]);  // ok
 *     $point->parse(['hello']);       // throws (too few)
 *     $point->parse([42, 'hello']);   // throws (wrong types)
 *
 *     // tuple with rest items
 *     $schema = Validate::tuple([
 *         Validate::string(),
 *     ])->items(Validate::int());
 *     $schema->parse(['hello', 1, 2]);  // ok
 *
 * @extends BaseSchema<list<mixed>>
 */
class TupleSchema extends BaseSchema
{
    /**
     * schema for items beyond the prefix positions
     *
     * When null, extra items are forbidden (exact length
     * enforced). When false, extra items are explicitly
     * forbidden (same behaviour, but set explicitly via
     * JSON Schema import). When a schema, extra items
     * are validated against it.
     *
     * @var ValidationSchema<mixed>|false|null
     */
    private ValidationSchema|false|null $restSchema = null;

    /**
     * schema for unevaluated items
     *
     * @var ValidationSchema<mixed>|false|null
     */
    private ValidationSchema|false|null $unevaluatedItemsSchema = null;

    /**
     * @param list<ValidationSchema<mixed>> $schemas
     * - one schema per tuple position, in order
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
            message: 'Expected tuple (array), received '
                . get_debug_type($data),
        );
    }

    // ================================================================
    //
    // Introspection
    //
    // ----------------------------------------------------------------

    /**
     * return the positional schemas
     *
     * @return list<ValidationSchema<mixed>>
     */
    public function schemas(): array
    {
        return $this->schemas;
    }

    /**
     * set the rest schema for items beyond the prefix
     *
     * In JSON Schema terms, this is the `items` keyword
     * when `prefixItems` is present. Items at positions
     * beyond the prefix are validated against this schema.
     *
     * Pass `false` to explicitly forbid extra items
     * (equivalent to `items: false` in JSON Schema).
     *
     * @param ValidationSchema<mixed>|false $schema
     */
    public function items(
        ValidationSchema|false $schema,
    ): static {
        $clone = clone $this;
        $clone->restSchema = $schema;

        return $clone;
    }

    /**
     * return the rest schema, or null if not set
     *
     * Returns false when extra items are explicitly
     * forbidden, null when the default exact-length
     * behaviour applies, or a schema when extra items
     * are validated.
     *
     * @return ValidationSchema<mixed>|false|null
     */
    public function maybeRestSchema(): ValidationSchema|false|null
    {
        return $this->restSchema;
    }

    /**
     * set how unevaluated items are handled
     *
     * Unevaluated items are those not covered by
     * `prefixItems`, `items`, `contains`, or any
     * composition sub-schema.
     *
     * @param ValidationSchema<mixed>|false $schema
     */
    public function unevaluatedItems(
        ValidationSchema|false $schema,
    ): static {
        $clone = clone $this;
        $clone->unevaluatedItemsSchema = $schema;

        return $clone;
    }

    /**
     * @return ValidationSchema<mixed>|false|null
     */
    public function maybeUnevaluatedItemsSchema(): ValidationSchema|false|null
    {
        return $this->unevaluatedItemsSchema;
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
     * validate tuple prefix positions and any rest items
     *
     * When no rest schema is set, the tuple enforces
     * exact length. When a rest schema is set, items
     * beyond the prefix are validated against it. When
     * the rest schema is false, extra items are rejected.
     */
    protected function validateChildren(
        mixed $data,
        ValidationContext $context,
    ): mixed {
        assert(is_array($data));

        $prefixCount = count($this->schemas);
        $actualCount = count($data);
        $hasRestSchema = $this->restSchema !== null;

        // check minimum length
        if ($actualCount < $prefixCount) {
            /** @var non-falsy-string $message */
            $message = $hasRestSchema
                ? 'Tuple must have at least '
                    . $prefixCount . ' elements, received '
                    . $actualCount
                : 'Tuple must have exactly '
                    . $prefixCount . ' elements, received '
                    . $actualCount;
            $context->addIssue(
                type: 'https://stusdevkit.dev/errors/validation/too_small',
                input: $data,
                message: $message,
            );

            return $data;
        }

        // when no rest schema is set, enforce exact length
        if (! $hasRestSchema && $actualCount !== $prefixCount) {
            /** @var non-falsy-string $message */
            $message = 'Tuple must have exactly '
                . $prefixCount . ' elements, received '
                . $actualCount;
            $context->addIssue(
                type: 'https://stusdevkit.dev/errors/validation/too_big',
                input: $data,
                message: $message,
            );

            return $data;
        }

        // when rest schema is false, reject extra items
        if (
            $this->restSchema === false
            && $actualCount > $prefixCount
        ) {
            /** @var non-falsy-string $message */
            $message = 'Tuple must have exactly '
                . $prefixCount . ' elements, received '
                . $actualCount;
            $context->addIssue(
                type: 'https://stusdevkit.dev/errors/validation/too_big',
                input: $data,
                message: $message,
            );

            return $data;
        }

        // validate prefix positions
        $values = array_values($data);
        $output = [];
        foreach ($this->schemas as $index => $schema) {
            $context->markEvaluated($index);
            $childContext = $context->atPath($index);
            $output[] = $schema->parseWithContext(
                data: $values[$index],
                context: $childContext,
            );
        }

        // validate rest items against the rest schema
        if (
            $this->restSchema instanceof ValidationSchema
            && $actualCount > $prefixCount
        ) {
            for ($i = $prefixCount; $i < $actualCount; $i++) {
                $context->markEvaluated($i);
                $childContext = $context->atPath($i);
                $output[] = $this->restSchema->parseWithContext(
                    data: $values[$i],
                    context: $childContext,
                );
            }
        }

        // check unevaluated items
        if ($this->unevaluatedItemsSchema !== null) {
            for ($i = 0; $i < $actualCount; $i++) {
                if ($context->isEvaluated($i)) {
                    continue;
                }

                if ($this->unevaluatedItemsSchema === false) {
                    $context->addIssue(
                        type: 'https://stusdevkit.dev/errors/validation/too_big',
                        input: $values[$i],
                        message: 'Unevaluated item at index: '
                            . $i,
                    );
                } else {
                    $childContext = $context->atPath($i);
                    $output[$i] = $this->unevaluatedItemsSchema
                        ->parseWithContext(
                            data: $values[$i],
                            context: $childContext,
                        );
                    $context->markEvaluated($i);
                }
            }
        }

        return $output;
    }

    /**
     * encode prefix positions and any rest items
     */
    protected function encodeChildren(
        mixed $data,
        ValidationContext $context,
    ): mixed {
        assert(is_array($data));

        $prefixCount = count($this->schemas);
        $actualCount = count($data);
        $hasRestSchema = $this->restSchema !== null;

        // check minimum length
        if ($actualCount < $prefixCount) {
            /** @var non-falsy-string $message */
            $message = $hasRestSchema
                ? 'Tuple must have at least '
                    . $prefixCount . ' elements, received '
                    . $actualCount
                : 'Tuple must have exactly '
                    . $prefixCount . ' elements, received '
                    . $actualCount;
            $context->addIssue(
                type: 'https://stusdevkit.dev/errors/validation/too_small',
                input: $data,
                message: $message,
            );

            return $data;
        }

        // when no rest schema is set, enforce exact length
        if (! $hasRestSchema && $actualCount !== $prefixCount) {
            /** @var non-falsy-string $message */
            $message = 'Tuple must have exactly '
                . $prefixCount . ' elements, received '
                . $actualCount;
            $context->addIssue(
                type: 'https://stusdevkit.dev/errors/validation/too_big',
                input: $data,
                message: $message,
            );

            return $data;
        }

        // when rest schema is false, reject extra items
        if (
            $this->restSchema === false
            && $actualCount > $prefixCount
        ) {
            /** @var non-falsy-string $message */
            $message = 'Tuple must have exactly '
                . $prefixCount . ' elements, received '
                . $actualCount;
            $context->addIssue(
                type: 'https://stusdevkit.dev/errors/validation/too_big',
                input: $data,
                message: $message,
            );

            return $data;
        }

        // encode prefix positions
        $values = array_values($data);
        $output = [];
        foreach ($this->schemas as $index => $schema) {
            $context->markEvaluated($index);
            $childContext = $context->atPath($index);
            $output[] = $schema->encodeWithContext(
                data: $values[$index],
                context: $childContext,
            );
        }

        // encode rest items
        if (
            $this->restSchema instanceof ValidationSchema
            && $actualCount > $prefixCount
        ) {
            for ($i = $prefixCount; $i < $actualCount; $i++) {
                $context->markEvaluated($i);
                $childContext = $context->atPath($i);
                $output[] = $this->restSchema->encodeWithContext(
                    data: $values[$i],
                    context: $childContext,
                );
            }
        }

        // check unevaluated items
        if ($this->unevaluatedItemsSchema !== null) {
            for ($i = 0; $i < $actualCount; $i++) {
                if ($context->isEvaluated($i)) {
                    continue;
                }

                if ($this->unevaluatedItemsSchema === false) {
                    $context->addIssue(
                        type: 'https://stusdevkit.dev/errors/validation/too_big',
                        input: $values[$i],
                        message: 'Unevaluated item at index: '
                            . $i,
                    );
                } else {
                    $childContext = $context->atPath($i);
                    $output[$i] = $this->unevaluatedItemsSchema
                        ->encodeWithContext(
                            data: $values[$i],
                            context: $childContext,
                        );
                    $context->markEvaluated($i);
                }
            }
        }

        return $output;
    }
}
