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

namespace StusDevKit\ValidationKit\Schemas;

use StusDevKit\ValidationKit\Coercions\NoCoercion;
use StusDevKit\ValidationKit\Contracts\Parseable;
use StusDevKit\ValidationKit\Contracts\PipelineStep;
use StusDevKit\ValidationKit\Contracts\ValueCoercion;
use StusDevKit\ValidationKit\Exceptions\ValidationException;
use StusDevKit\ValidationKit\Internals\ValidationContext;
use StusDevKit\ValidationKit\ParseResult;
use StusDevKit\ValidationKit\Traits\HasErrorCallback;
use StusDevKit\ValidationKit\Traits\HasMetadata;
use StusDevKit\ValidationKit\Traits\HasNullability;
use StusDevKit\ValidationKit\Traits\HasTransforms;

/**
 * BaseSchema is the abstract foundation for all validation
 * schemas.
 *
 * It implements the full parse pipeline:
 *
 * 1. Null/missing check with default value support
 * 2. Type coercion (if enabled)
 * 3. Type check (delegated to concrete schemas)
 * 4. Validate children (e.g. object fields)
 * 5. Pipeline steps (normalisers, constraints,
 *    refinements, transforms — in order added)
 * 6. Pipe to another schema
 *
 * Concrete schemas must implement checkType(). Schemas
 * that support type coercion should provide their own
 * coerce() builder method.
 *
 * All builder methods return new instances (immutable
 * schemas).
 *
 * @template-covariant TOutput
 * @implements Parseable<TOutput>
 */
abstract class BaseSchema implements Parseable
{
    use HasErrorCallback;
    use HasMetadata;
    use HasNullability;
    use HasTransforms;

    protected ValueCoercion $coercion;

    /** @var list<PipelineStep> */
    protected array $steps = [];

    public function __construct()
    {
        $this->coercion = new NoCoercion();
    }

    // ================================================================
    //
    // Builder Methods
    //
    // ----------------------------------------------------------------

    /**
     * add a step to the pipeline
     *
     * Returns a new schema instance with the step
     * appended. Steps run in the order they are added,
     * after the type check and validateChildren have
     * passed. Steps with skipOnIssues() returning true
     * cause the pipeline to stop when prior issues exist.
     */
    public function withStep(PipelineStep $step): static
    {
        $clone = clone $this;
        $clone->steps[] = $step;

        return $clone;
    }

    /**
     * add a validation constraint to this schema
     *
     * Convenience method — equivalent to withStep().
     */
    public function withConstraint(PipelineStep $step): static
    {
        return $this->withStep($step);
    }

    /**
     * add a pre-constraint normaliser to this schema
     *
     * Convenience method — equivalent to withStep().
     */
    public function withNormaliser(PipelineStep $step): static
    {
        return $this->withStep($step);
    }

    // ================================================================
    //
    // Parseable Interface
    //
    // ----------------------------------------------------------------

    /**
     * validate the given data and return the validated
     * (and possibly transformed) result
     *
     * @return TOutput
     * @throws ValidationException
     *         if validation fails.
     */
    public function parse(mixed $data): mixed
    {
        $context = new ValidationContext();
        $result = $this->parseWithContext(
            data: $data,
            context: $context,
        );

        if ($context->hasIssues()) {
            // catch() provides a fallback on failure
            if ($this->hasCatch) {
                /** @var TOutput $fallback */
                $fallback = $this->catchFallback;

                return $fallback;
            }

            throw new ValidationException($context->issues());
        }

        /** @var TOutput $validatedResult */
        $validatedResult = $result;

        return $validatedResult;
    }

    /**
     * validate the given data and return a result object
     * instead of throwing
     *
     * @return ParseResult<TOutput>
     */
    public function safeParse(mixed $data): ParseResult
    {
        $context = new ValidationContext();
        $result = $this->parseWithContext(
            data: $data,
            context: $context,
        );

        if ($context->hasIssues()) {
            // catch() provides a fallback on failure
            if ($this->hasCatch) {
                /** @var TOutput $catchFallback */
                $catchFallback = $this->catchFallback;

                return ParseResult::ok($catchFallback);
            }

            /** @var ParseResult<TOutput> $failResult */
            $failResult = ParseResult::fail(
                new ValidationException($context->issues()),
            );

            return $failResult;
        }

        /** @var TOutput $validatedData */
        $validatedData = $result;

        return ParseResult::ok($validatedData);
    }

    /**
     * alias for parse()
     *
     * @return TOutput
     * @throws ValidationException
     *         if validation fails.
     */
    public function decode(mixed $data): mixed
    {
        return $this->parse($data);
    }

    /**
     * alias for safeParse()
     *
     * @return ParseResult<TOutput>
     */
    public function safeDecode(mixed $data): ParseResult
    {
        return $this->safeParse($data);
    }

    // ================================================================
    //
    // Encode (strict validation without defaults or catch)
    //
    // ----------------------------------------------------------------

    /**
     * validate the given data without applying default()
     * or catch() fallbacks
     *
     * Used by Codec::encode() to ensure the encode path
     * does not silently substitute or swallow values.
     * On non-codec schemas, this is equivalent to strict
     * validation.
     *
     * @return TOutput
     * @throws ValidationException
     *         if validation fails.
     */
    public function encode(mixed $data): mixed
    {
        $context = new ValidationContext();
        $result = $this->encodeWithContext(
            data: $data,
            context: $context,
        );

        if ($context->hasIssues()) {
            throw new ValidationException($context->issues());
        }

        /** @var TOutput $validatedResult */
        $validatedResult = $result;

        return $validatedResult;
    }

    /**
     * validate the given data without applying default()
     * or catch() fallbacks, returning a result object
     * instead of throwing
     *
     * @return ParseResult<TOutput>
     */
    public function safeEncode(mixed $data): ParseResult
    {
        $context = new ValidationContext();
        $result = $this->encodeWithContext(
            data: $data,
            context: $context,
        );

        if ($context->hasIssues()) {
            /** @var ParseResult<TOutput> $failResult */
            $failResult = ParseResult::fail(
                new ValidationException($context->issues()),
            );

            return $failResult;
        }

        /** @var TOutput $validatedData */
        $validatedData = $result;

        return ParseResult::ok($validatedData);
    }

    // ================================================================
    //
    // Internal Parse Pipeline
    //
    // ----------------------------------------------------------------

    /**
     * run the full parse pipeline with a given context
     *
     * This is the internal entry point used by collection
     * schemas to validate child values with path tracking.
     *
     * @internal
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

            if (! $this->acceptsNull()) {
                $this->invokeErrorCallback(
                    callback: $this->typeCheckError,
                    input: $data,
                    context: $context,
                );

                return null;
            }
        }

        return $this->runDecodePipeline(
            data: $data,
            context: $context,
        );
    }

    /**
     * run the encode pipeline with a given context
     *
     * Like parseWithContext(), but skips the default()
     * fallback for null values. Used by encode() and
     * safeEncode() to ensure the encode path does not
     * silently substitute values.
     *
     * @internal
     */
    public function encodeWithContext(
        mixed $data,
        ValidationContext $context,
    ): mixed {
        // step 1: null check — NO default fallback
        if ($data === null && ! $this->acceptsNull()) {
            $this->invokeErrorCallback(
                callback: $this->typeCheckError,
                input: $data,
                context: $context,
            );

            return null;
        }

        return $this->runEncodePipeline(
            data: $data,
            context: $context,
        );
    }

    /**
     * run the shared validation pipeline
     *
     * Contains the common steps used by parseWithContext():
     * coerce, type check, validate children, pipeline
     * steps, and pipe.
     *
     * @internal
     */
    protected function runDecodePipeline(
        mixed $data,
        ValidationContext $context,
    ): mixed {
        // step 2: coerce
        $data = $this->coercion->coerce($data);

        // step 3: type check
        $typeCheckPassed = $this->checkType(
            data: $data,
            context: $context,
        );

        // if type check failed, skip the rest of the
        // pipeline (it depends on the correct type)
        if (! $typeCheckPassed) {
            return $data;
        }

        // step 4: validate children (e.g. object fields)
        $data = $this->validateChildren(
            data: $data,
            context: $context,
        );

        if ($context->hasIssues()) {
            return $data;
        }

        // step 5: pipeline steps (normalisers, constraints,
        // refinements, transforms — in order added)
        foreach ($this->steps as $step) {
            if ($step->skipOnIssues() && $context->hasIssues()) {
                break;
            }
            $data = $step->process(
                data: $data,
                context: $context,
            );
        }

        if ($context->hasIssues()) {
            return $data;
        }

        // step 6: pipe to another schema
        if ($this->pipeTarget !== null) {
            $data = $this->pipeTarget->parseWithContext(
                data: $data,
                context: $context,
            );
        }

        return $data;
    }

    /**
     * run the encode validation pipeline
     *
     * Like runDecodePipeline(), but calls encodeChildren()
     * instead of validateChildren() so that child schemas
     * use their encode path.
     *
     * @internal
     */
    protected function runEncodePipeline(
        mixed $data,
        ValidationContext $context,
    ): mixed {
        // step 2: coerce
        $data = $this->coercion->coerce($data);

        // step 3: type check
        $typeCheckPassed = $this->checkType(
            data: $data,
            context: $context,
        );

        // if type check failed, skip the rest of the
        // pipeline (it depends on the correct type)
        if (! $typeCheckPassed) {
            return $data;
        }

        // step 4: encode children
        $data = $this->encodeChildren(
            data: $data,
            context: $context,
        );

        if ($context->hasIssues()) {
            return $data;
        }

        // step 5: pipeline steps (normalisers, constraints,
        // refinements, transforms — in order added)
        foreach ($this->steps as $step) {
            if ($step->skipOnIssues() && $context->hasIssues()) {
                break;
            }
            $data = $step->process(
                data: $data,
                context: $context,
            );
        }

        if ($context->hasIssues()) {
            return $data;
        }

        // step 6: pipe to another schema
        if ($this->pipeTarget !== null) {
            $data = $this->pipeTarget->encodeWithContext(
                data: $data,
                context: $context,
            );
        }

        return $data;
    }

    // ================================================================
    //
    // Abstract Methods (implemented by concrete schemas)
    //
    // ----------------------------------------------------------------

    /**
     * return a human-readable name for the expected type
     *
     * Used in default error messages (e.g. "Expected string,
     * received int").
     *
     * @return non-empty-string
     */
    abstract protected function expectedType(): string;

    /**
     * check that the input matches the expected type
     *
     * Must add issues to the context if the type check
     * fails. Return true if the type check passed, false
     * otherwise.
     */
    abstract protected function checkType(
        mixed $data,
        ValidationContext $context,
    ): bool;

    /**
     * does this schema accept null as a valid value?
     *
     * Override to return true in schemas where null is a
     * valid input (e.g. MixedSchema, NullSchema). The
     * default is false — null triggers the type-check
     * error callback.
     */
    protected function acceptsNull(): bool
    {
        return false;
    }

    /**
     * validate child values and optionally rebuild the
     * data
     *
     * Called after the type check and normalisers have
     * passed but before constraint checks. Override this
     * in schemas that need to validate nested structures
     * (e.g. ObjectSchema validates shape fields).
     *
     * The returned value replaces the data for the rest
     * of the pipeline.
     */
    protected function validateChildren(
        mixed $data,
        ValidationContext $context,
    ): mixed {
        return $data;
    }

    /**
     * encode child values and optionally rebuild the data
     *
     * Called by the encode pipeline instead of
     * validateChildren(). Override this in schemas that
     * need to propagate encode context to nested
     * structures (e.g. ObjectSchema encodes shape fields).
     *
     * By default, delegates to validateChildren() since
     * most schemas have no children.
     */
    protected function encodeChildren(
        mixed $data,
        ValidationContext $context,
    ): mixed {
        return $this->validateChildren(
            data: $data,
            context: $context,
        );
    }
}
