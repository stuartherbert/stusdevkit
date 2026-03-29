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

/**
 * AnyOfSchema validates that the input matches at least
 * one of the given schemas ("or" logic).
 *
 * Each schema is tried in order. The first schema that
 * passes is used. If none pass, an InvalidUnion issue
 * is reported.
 *
 * Usage:
 *
 *     use StusDevKit\ValidationKit\Validate;
 *
 *     $stringOrInt = Validate::anyOf([
 *         Validate::string(),
 *         Validate::int(),
 *     ]);
 *     $stringOrInt->parse('hello'); // 'hello'
 *     $stringOrInt->parse(42);      // 42
 *     $stringOrInt->parse(true);    // throws
 *
 * @extends BaseSchema<mixed>
 */
class AnyOfSchema extends BaseSchema
{
    /**
     * @param list<ValidationSchema<mixed>> $schemas
     * - the schemas to try, in order
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
            type: 'https://stusdevkit.dev/errors/validation/invalid_union',
            input: $data,
            path: [],
            message: 'Input does not match any schema'
                . ' in the union',
        );
    }

    // ================================================================
    //
    // Introspection
    //
    // ----------------------------------------------------------------

    /**
     * return the union member schemas
     *
     * @return list<ValidationSchema<mixed>>
     */
    public function schemas(): array
    {
        return $this->schemas;
    }

    // ================================================================
    //
    // BaseSchema Implementation
    //
    // ----------------------------------------------------------------

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
     * try each schema in order; the first one that
     * succeeds wins
     */
    protected function validateChildren(
        mixed $data,
        ValidationContext $context,
    ): mixed {
        foreach ($this->schemas as $schema) {
            $childContext = new ValidationContext(
                $context->path(),
            );
            $result = $schema->parseWithContext(
                data: $data,
                context: $childContext,
            );

            if (! $childContext->hasIssues()) {
                return $result;
            }
        }

        // none matched
        $this->invokeErrorCallback(
            callback: $this->typeCheckError,
            input: $data,
            context: $context,
        );

        return $data;
    }

    /**
     * encode against schemas; the first one that succeeds
     * wins
     */
    protected function encodeChildren(
        mixed $data,
        ValidationContext $context,
    ): mixed {
        foreach ($this->schemas as $schema) {
            $childContext = new ValidationContext(
                $context->path(),
            );
            $result = $schema->encodeWithContext(
                data: $data,
                context: $childContext,
            );

            if (! $childContext->hasIssues()) {
                return $result;
            }
        }

        // none matched
        $this->invokeErrorCallback(
            callback: $this->typeCheckError,
            input: $data,
            context: $context,
        );

        return $data;
    }
}
