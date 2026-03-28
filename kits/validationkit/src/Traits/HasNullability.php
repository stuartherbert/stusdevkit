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

namespace StusDevKit\ValidationKit\Traits;

/**
 * HasNullability provides nullable(), optional(), and
 * default() methods for schemas.
 *
 * - nullable() allows null to pass validation
 * - optional() marks a field as not required in an object
 *   schema (and allows null for standalone schemas)
 * - default() provides a fallback value when the input is
 *   null or missing
 */
trait HasNullability
{
    protected bool $isNullable = false;
    protected bool $isOptional = false;
    protected bool $hasDefault = false;
    protected mixed $defaultValue;

    // ================================================================
    //
    // Builder Methods
    //
    // ----------------------------------------------------------------

    /**
     * allow null to pass validation
     *
     * Returns a new schema instance that accepts null in
     * addition to the schema's normal type.
     */
    public function nullable(): static
    {
        $clone = clone $this;
        $clone->isNullable = true;

        return $clone;
    }

    /**
     * mark this schema as optional
     *
     * For standalone schemas, this behaves like nullable().
     * For fields within an object schema, this means the
     * key does not need to be present in the input.
     */
    public function optional(): static
    {
        $clone = clone $this;
        $clone->isOptional = true;
        $clone->isNullable = true;

        return $clone;
    }

    /**
     * provide a default value for null or missing input
     *
     * When the input is null (or the key is missing in an
     * object schema), the default value is used instead.
     * The default value is not validated against the schema.
     */
    public function default(mixed $value): static
    {
        $clone = clone $this;
        $clone->hasDefault = true;
        $clone->defaultValue = $value;

        return $clone;
    }
}
