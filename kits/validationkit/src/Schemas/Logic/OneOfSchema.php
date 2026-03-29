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

use StusDevKit\ValidationKit\Internals\ValidationContext;
use StusDevKit\ValidationKit\Schemas\BaseSchema;
use StusDevKit\ValidationKit\ValidationIssue;

/**
 * OneOfSchema validates that the input matches exactly
 * one of the given schemas ("exclusive or" logic).
 *
 * Every schema is tried (no short-circuiting). If exactly
 * one schema passes, its result is used. If zero or more
 * than one schema passes, validation fails.
 *
 * Usage:
 *
 *     use StusDevKit\ValidationKit\Validate;
 *
 *     $stringOrInt = Validate::oneOf([
 *         Validate::string(),
 *         Validate::int(),
 *     ]);
 *     $stringOrInt->parse('hello'); // 'hello'
 *     $stringOrInt->parse(42);      // 42
 *     $stringOrInt->parse(true);    // throws
 *
 * @extends BaseSchema<mixed>
 */
class OneOfSchema extends BaseSchema
{
    /**
     * @param list<BaseSchema<mixed>> $schemas
     * - the schemas to try; exactly one must match
     * @param (callable(mixed): ValidationIssue)|null $typeCheckError
     */
    public function __construct(
        private readonly array $schemas,
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
            type: 'https://stusdevkit.dev/errors/validation/custom',
            input: $data,
            path: [],
            message: 'Input must match exactly one schema,'
                . ' but matched 0',
        );
    }

    // ================================================================
    //
    // BaseSchema Implementation
    //
    // ----------------------------------------------------------------

    protected function expectedType(): string
    {
        return 'oneOf';
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
     * try each schema; exactly one must match
     */
    protected function validateChildren(
        mixed $data,
        ValidationContext $context,
    ): mixed {
        // try every schema — we must count how many pass
        $matchCount = 0;
        $matchedResult = $data;

        foreach ($this->schemas as $schema) {
            $childContext = new ValidationContext(
                $context->path(),
            );
            $result = $schema->parseWithContext(
                data: $data,
                context: $childContext,
            );

            if (! $childContext->hasIssues()) {
                $matchCount++;
                $matchedResult = $result;
            }
        }

        // exactly one schema must match
        if ($matchCount === 1) {
            return $matchedResult;
        }

        // zero or more than one matched — report error
        if ($matchCount === 0) {
            $this->invokeErrorCallback(
                callback: $this->typeCheckError,
                input: $data,
                context: $context,
            );
        } else {
            $this->invokeErrorCallback(
                callback: static fn(mixed $input) => new ValidationIssue(
                    type: 'https://stusdevkit.dev/errors/validation/custom',
                    input: $input,
                    path: [],
                    message: 'Input matches ' . $matchCount
                        . ' schemas in oneOf, but must match'
                        . ' exactly one',
                ),
                input: $data,
                context: $context,
            );
        }

        return $data;
    }

    /**
     * encode against schemas; exactly one must match
     */
    protected function encodeChildren(
        mixed $data,
        ValidationContext $context,
    ): mixed {
        // try every schema — we must count how many pass
        $matchCount = 0;
        $matchedResult = $data;

        foreach ($this->schemas as $schema) {
            $childContext = new ValidationContext(
                $context->path(),
            );
            $result = $schema->encodeWithContext(
                data: $data,
                context: $childContext,
            );

            if (! $childContext->hasIssues()) {
                $matchCount++;
                $matchedResult = $result;
            }
        }

        // exactly one schema must match
        if ($matchCount === 1) {
            return $matchedResult;
        }

        // zero or more than one matched — report error
        if ($matchCount === 0) {
            $this->invokeErrorCallback(
                callback: $this->typeCheckError,
                input: $data,
                context: $context,
            );
        } else {
            $this->invokeErrorCallback(
                callback: static fn(mixed $input) => new ValidationIssue(
                    type: 'https://stusdevkit.dev/errors/validation/custom',
                    input: $input,
                    path: [],
                    message: 'Input matches ' . $matchCount
                        . ' schemas in oneOf, but must match'
                        . ' exactly one',
                ),
                input: $data,
                context: $context,
            );
        }

        return $data;
    }
}
