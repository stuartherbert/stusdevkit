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
use StusDevKit\ValidationKit\Schemas\BaseSchema;
use StusDevKit\ValidationKit\ValidationIssue;

/**
 * RecordSchema validates associative arrays where both
 * keys and values are validated against their respective
 * schemas. Unlike ObjectSchema, the set of keys is not
 * fixed — any key that passes the key schema is allowed.
 *
 * This is the PHP equivalent of Zod's z.record().
 *
 * Usage:
 *
 *     use StusDevKit\ValidationKit\Validate;
 *
 *     // string keys, int values
 *     $scores = Validate::record(
 *         Validate::string(),
 *         Validate::int()->gte(value: 0),
 *     );
 *     $scores->parse(['alice' => 100, 'bob' => 85]); // ok
 *     $scores->parse(['alice' => -1]);                // throws
 *
 * @template TKey of array-key
 * @template TValue
 * @extends BaseSchema<array<TKey, TValue>>
 */
class RecordSchema extends BaseSchema
{
    /**
     * @param BaseSchema<TKey> $keySchema
     * - schema for validating keys (typically StringSchema)
     * @param BaseSchema<TValue> $valueSchema
     * - schema for validating values
     * @param (callable(mixed): ValidationIssue)|null $typeCheckError
     */
    public function __construct(
        private readonly BaseSchema $keySchema,
        private readonly BaseSchema $valueSchema,
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
            message: 'Expected record (associative array), received '
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
        return 'record';
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
     * validate each key and value against their schemas
     */
    protected function validateChildren(
        mixed $data,
        ValidationContext $context,
    ): mixed {
        assert(is_array($data));

        $output = [];
        foreach ($data as $key => $value) {
            // validate the key
            $keyContext = $context->atPath($key);
            $this->keySchema->parseWithContext(
                data: $key,
                context: $keyContext,
            );

            // validate the value
            $valueContext = $context->atPath($key);
            $validatedValue = $this->valueSchema->parseWithContext(
                data: $value,
                context: $valueContext,
            );

            $output[$key] = $validatedValue;
        }

        return $output;
    }

    /**
     * encode each key and value using the encode pipeline
     */
    protected function encodeChildren(
        mixed $data,
        ValidationContext $context,
    ): mixed {
        assert(is_array($data));

        $output = [];
        foreach ($data as $key => $value) {
            // encode the key
            $keyContext = $context->atPath($key);
            $this->keySchema->encodeWithContext(
                data: $key,
                context: $keyContext,
            );

            // encode the value
            $valueContext = $context->atPath($key);
            $encodedValue = $this->valueSchema->encodeWithContext(
                data: $value,
                context: $valueContext,
            );

            $output[$key] = $encodedValue;
        }

        return $output;
    }
}
