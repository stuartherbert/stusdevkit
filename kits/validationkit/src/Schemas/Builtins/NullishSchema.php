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

use StusDevKit\ValidationKit\Contracts\ValidationSchema;
use StusDevKit\ValidationKit\Internals\ValidationContext;
use StusDevKit\ValidationKit\Schemas\BaseSchema;

/**
 * NullishSchema wraps another schema to allow null or
 * missing values. It combines the behaviour of both
 * OptionalSchema and NullableSchema: the value can be
 * null (nullable) or missing entirely (optional).
 *
 * Usage:
 *
 *     use StusDevKit\ValidationKit\Validate;
 *
 *     // a string that can be null or missing
 *     $schema = Validate::nullish(Validate::string());
 *     $schema->parse('hello'); // 'hello'
 *     $schema->parse(null);    // null
 *
 * @template TInner
 * @extends BaseSchema<TInner|null>
 */
class NullishSchema extends BaseSchema
{
    /**
     * @param ValidationSchema<TInner> $innerSchema
     */
    public function __construct(
        private readonly ValidationSchema $innerSchema,
    ) {
        parent::__construct();
    }

    // ================================================================
    //
    // Accessors
    //
    // ----------------------------------------------------------------

    /**
     * return the inner schema
     *
     * @return ValidationSchema<TInner>
     */
    public function unwrap(): ValidationSchema
    {
        return $this->innerSchema;
    }

    // ================================================================
    //
    // BaseSchema Implementation
    //
    // ----------------------------------------------------------------

    protected function acceptsNull(): bool
    {
        return true;
    }

    protected function expectedType(): string
    {
        return 'nullish';
    }

    protected function checkType(
        mixed $data,
        ValidationContext $context,
    ): bool {
        // the inner schema handles type checking
        return true;
    }

    protected function validateChildren(
        mixed $data,
        ValidationContext $context,
    ): mixed {
        // null is accepted by this schema
        if ($data === null) {
            return null;
        }

        // delegate to the inner schema
        return $this->innerSchema->parseWithContext(
            data: $data,
            context: $context,
        );
    }
}
