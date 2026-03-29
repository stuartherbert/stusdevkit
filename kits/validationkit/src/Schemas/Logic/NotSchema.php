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
 * NotSchema validates that the input does NOT match the
 * given schema (negation logic).
 *
 * The inner schema is tried. If it passes, NotSchema
 * rejects the input. If it fails, the data is considered
 * valid.
 *
 * Usage:
 *
 *     use StusDevKit\ValidationKit\Validate;
 *
 *     $notString = Validate::not(Validate::string());
 *     $notString->parse(42);      // 42
 *     $notString->parse(true);    // true
 *     $notString->parse('hello'); // throws
 *
 * @extends BaseSchema<mixed>
 */
class NotSchema extends BaseSchema
{
    /**
     * @param ValidationSchema<mixed> $schema
     * - the schema that must NOT match
     * @param (callable(mixed): ValidationIssue)|null $typeCheckError
     */
    public function __construct(
        private readonly ValidationSchema $schema,
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
            type: 'https://stusdevkit.dev/errors/validation/custom',
            input: $data,
            path: [],
            message: 'Value must not match the excluded'
                . ' schema',
        );
    }

    // ================================================================
    //
    // Introspection
    //
    // ----------------------------------------------------------------

    /**
     * return the negated schema
     *
     * @return ValidationSchema<mixed>
     */
    public function innerSchema(): ValidationSchema
    {
        return $this->schema;
    }

    // ================================================================
    //
    // BaseSchema Implementation
    //
    // ----------------------------------------------------------------

    /**
     * null is allowed through to the child schema
     */
    protected function acceptsNull(): bool
    {
        return true;
    }

    protected function checkType(
        mixed $data,
        ValidationContext $context,
    ): bool {
        // child schema handles type checking
        return true;
    }

    /**
     * negate the inner schema's result: if the inner
     * schema passes, report an error
     */
    protected function validateChildren(
        mixed $data,
        ValidationContext $context,
    ): mixed {
        // try the inner schema in a child context
        $childContext = new ValidationContext(
            $context->path(),
        );
        $this->schema->parseWithContext(
            data: $data,
            context: $childContext,
        );

        // if the inner schema passed, the data is invalid
        // for NotSchema
        if (! $childContext->hasIssues()) {
            $this->invokeErrorCallback(
                callback: $this->typeCheckError,
                input: $data,
                context: $context,
            );
        }

        // return data unchanged regardless
        return $data;
    }

    /**
     * negate the inner schema's encode result
     */
    protected function encodeChildren(
        mixed $data,
        ValidationContext $context,
    ): mixed {
        // try the inner schema in a child context
        $childContext = new ValidationContext(
            $context->path(),
        );
        $this->schema->encodeWithContext(
            data: $data,
            context: $childContext,
        );

        // if the inner schema passed, the data is invalid
        // for NotSchema
        if (! $childContext->hasIssues()) {
            $this->invokeErrorCallback(
                callback: $this->typeCheckError,
                input: $data,
                context: $context,
            );
        }

        // return data unchanged regardless
        return $data;
    }
}
