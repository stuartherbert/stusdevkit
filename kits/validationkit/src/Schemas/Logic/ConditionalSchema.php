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

/**
 * ConditionalSchema implements JSON Schema's if/then/else
 * logic.
 *
 * It evaluates a condition schema against the input. If the
 * condition passes, the `then` schema is applied. If the
 * condition fails, the `else` schema is applied. Both `then`
 * and `else` are optional — when omitted, the data passes
 * through unchanged for that branch.
 *
 * The `if` schema is evaluated in a fresh child context.
 * Its issues do not propagate to the main context — it is
 * purely a condition check. The `then` and `else` schemas
 * are evaluated in the main context, so their issues do
 * propagate.
 *
 * Usage:
 *
 *     use StusDevKit\ValidationKit\Validate;
 *
 *     // if the value is a string, it must be at least 1
 *     // character; otherwise it must be an int
 *     $schema = Validate::conditional(
 *         if: Validate::string(),
 *         then: Validate::string()->min(length: 1),
 *         else: Validate::int(),
 *     );
 *
 * @extends BaseSchema<mixed>
 */
class ConditionalSchema extends BaseSchema
{
    /**
     * @param ValidationSchema<mixed> $if
     * - the condition schema to evaluate
     * @param ValidationSchema<mixed>|null $then
     * - the schema to apply when the condition passes
     * @param ValidationSchema<mixed>|null $else
     * - the schema to apply when the condition fails
     */
    public function __construct(
        private readonly ValidationSchema $if,
        private readonly ?ValidationSchema $then = null,
        private readonly ?ValidationSchema $else = null,
    ) {
        parent::__construct();
    }

    // ================================================================
    //
    // BaseSchema Implementation
    //
    // ----------------------------------------------------------------

    protected function expectedType(): string
    {
        return 'conditional';
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
     * evaluate the if-schema condition, then apply the
     * then or else schema accordingly
     */
    protected function validateChildren(
        mixed $data,
        ValidationContext $context,
    ): mixed {
        // evaluate the condition in a child context
        // so that its issues do not propagate
        $childContext = new ValidationContext(
            $context->path(),
        );
        $this->if->parseWithContext(
            data: $data,
            context: $childContext,
        );

        // apply the appropriate branch
        if (! $childContext->hasIssues()) {
            // condition passed — apply `then` if present
            if ($this->then !== null) {
                return $this->then->parseWithContext(
                    data: $data,
                    context: $context,
                );
            }
        } else {
            // condition failed — apply `else` if present
            if ($this->else !== null) {
                return $this->else->parseWithContext(
                    data: $data,
                    context: $context,
                );
            }
        }

        return $data;
    }

    /**
     * evaluate the if-schema condition, then encode using
     * the then or else schema accordingly
     */
    protected function encodeChildren(
        mixed $data,
        ValidationContext $context,
    ): mixed {
        // evaluate the condition in a child context
        // so that its issues do not propagate
        $childContext = new ValidationContext(
            $context->path(),
        );
        $this->if->encodeWithContext(
            data: $data,
            context: $childContext,
        );

        // apply the appropriate branch
        if (! $childContext->hasIssues()) {
            // condition passed — apply `then` if present
            if ($this->then !== null) {
                return $this->then->encodeWithContext(
                    data: $data,
                    context: $context,
                );
            }
        } else {
            // condition failed — apply `else` if present
            if ($this->else !== null) {
                return $this->else->encodeWithContext(
                    data: $data,
                    context: $context,
                );
            }
        }

        return $data;
    }
}
