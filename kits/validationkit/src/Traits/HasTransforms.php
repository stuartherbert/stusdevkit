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

use StusDevKit\ValidationKit\Internals\ValidationContext;
use StusDevKit\ValidationKit\Schemas\BaseSchema;
use StusDevKit\ValidationKit\Transformers\CallableTransform;
use StusDevKit\ValidationKit\Transformers\RefineStep;
use StusDevKit\ValidationKit\Transformers\SuperRefineStep;

/**
 * HasTransforms provides transform(), refine(),
 * superRefine(), pipe(), and catch() methods for schemas.
 *
 * - transform() modifies validated data (skips on issues)
 * - refine() adds custom validation logic
 * - superRefine() adds advanced validation with multiple
 *   issues
 * - pipe() chains to another schema
 * - catch() provides a fallback on validation failure
 */
trait HasTransforms
{
    /** @var BaseSchema<mixed>|null */
    protected ?BaseSchema $pipeTarget = null;

    protected bool $hasCatch = false;
    protected mixed $catchFallback;

    protected bool $isReadonly = false;

    // ================================================================
    //
    // Builder Methods
    //
    // ----------------------------------------------------------------

    /**
     * add a data transformation step
     *
     * The callable receives the validated data and returns
     * the transformed value. Transforms are skipped when
     * prior pipeline steps have produced issues.
     *
     * @param callable(mixed): mixed $fn
     */
    public function transform(callable $fn): static
    {
        return $this->withStep(
            new CallableTransform($fn),
        );
    }

    /**
     * add a custom validation rule
     *
     * The callable receives the validated data and returns
     * true if valid, false if not. A false return creates
     * a Custom issue with the given message.
     *
     * @param callable(mixed): bool $fn
     * @param non-empty-string $message
     * - the error message if the refinement fails
     */
    public function refine(callable $fn, string $message): static
    {
        return $this->withStep(
            new RefineStep(
                callable: $fn,
                message: $message,
            ),
        );
    }

    /**
     * add an advanced custom validation rule
     *
     * The callable receives the validated data and a
     * ValidationContext. Use the context to add multiple
     * custom issues.
     *
     * @param callable(mixed, ValidationContext): void $fn
     */
    public function superRefine(callable $fn): static
    {
        return $this->withStep(
            new SuperRefineStep($fn),
        );
    }

    /**
     * chain the output to another schema
     *
     * After this schema validates and transforms the data,
     * the result is passed to the target schema for further
     * validation.
     *
     * @param BaseSchema<mixed> $schema
     */
    public function pipe(BaseSchema $schema): static
    {
        $clone = clone $this;
        $clone->pipeTarget = $schema;

        return $clone;
    }

    /**
     * provide a fallback value on validation failure
     *
     * If validation fails, the fallback value is returned
     * instead of throwing an exception.
     */
    public function catch(mixed $fallback): static
    {
        $clone = clone $this;
        $clone->hasCatch = true;
        $clone->catchFallback = $fallback;

        return $clone;
    }

    /**
     * mark the output as readonly
     *
     * This is a metadata flag that signals to consumers
     * the output should not be modified.
     */
    public function readonly(): static
    {
        $clone = clone $this;
        $clone->isReadonly = true;

        return $clone;
    }
}
