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
use StusDevKit\ValidationKit\Contracts\ValidationConstraint;
use StusDevKit\ValidationKit\Contracts\ValueCoercion;
use StusDevKit\ValidationKit\Contracts\ValueTransformer;
use StusDevKit\ValidationKit\Exceptions\ValidationException;
use StusDevKit\ValidationKit\Internals\ValidationContext;
use StusDevKit\ValidationKit\IssueCode;
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
 * 3.5. Pre-constraint transformers (trim, etc.)
 * 4. Constraint checks (delegated to concrete schemas)
 * 5. Refinements and super-refinements
 * 6. Transforms
 * 7. Pipe to another schema
 *
 * Concrete schemas must implement checkType() and
 * checkConstraints(). Schemas that support type
 * coercion should provide their own coerce() builder
 * method.
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

    /** @var list<ValueTransformer> */
    protected array $transformers = [];

    /** @var list<ValidationConstraint> */
    protected array $constraints = [];

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
     * add a pre-constraint normaliser to this schema
     *
     * Returns a new schema instance with the normaliser
     * appended to the normaliser list. Normalisers run
     * in the order they are added, after the type check
     * has passed but before constraint checks.
     */
    public function withNormaliser(
        ValueTransformer $transformer,
    ): static {
        $clone = clone $this;
        $clone->transformers[] = $transformer;

        return $clone;
    }

    /**
     * add a validation constraint to this schema
     *
     * Returns a new schema instance with the constraint
     * appended to the constraint list. Constraints are
     * checked in the order they are added, after the
     * type check has passed.
     */
    public function withConstraint(
        ValidationConstraint $constraint,
    ): static {
        $clone = clone $this;
        $clone->constraints[] = $constraint;

        return $clone;
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

            // null is not allowed — invoke the type-check
            // callback (always set by schema constructors)
            $this->invokeErrorCallback(
                callback: $this->typeCheckError,
                input: $data,
                context: $context,
            );

            return null;
        }

        // step 2: coerce
        $data = $this->coercion->coerce($data);

        // step 3: type check
        $typeCheckPassed = $this->checkType(
            data: $data,
            context: $context,
        );

        // if type check failed, skip transformers,
        // constraints, and refinements (they depend on
        // the correct type)
        if (! $typeCheckPassed) {
            return $data;
        }

        // step 3.5: pre-constraint transformers
        foreach ($this->transformers as $transformer) {
            $data = $transformer->transform($data);
        }

        // step 4: constraint checks
        $this->checkConstraints(
            data: $data,
            context: $context,
        );

        // if there are issues from constraints, stop before
        // running refinements/transforms
        if ($context->hasIssues()) {
            return $data;
        }

        // steps 5 + 6: refinements and transforms
        // (interleaved in pipeline order)
        /** @var array{mixed, bool} $pipelineResult */
        $pipelineResult = $this->runPipeline(
            data: $data,
            context: $context,
        );
        $data = $pipelineResult[0];
        $pipelineClean = $pipelineResult[1];

        if (! $pipelineClean) {
            return $data;
        }

        // step 7: pipe to another schema
        if ($this->pipeTarget !== null) {
            $data = $this->pipeTarget->parseWithContext(
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
     * check constraints (min, max, length, etc.)
     *
     * Only called if the type check passed. Iterates over
     * all constraints added via withConstraint() and runs
     * each one.
     *
     * Concrete schemas may override this to add additional
     * logic before or after constraint checks.
     */
    protected function checkConstraints(
        mixed $data,
        ValidationContext $context,
    ): void {
        foreach ($this->constraints as $constraint) {
            $constraint->check(
                data: $data,
                context: $context,
            );
        }
    }

    // ================================================================
    //
    // Pipeline Execution
    //
    // ----------------------------------------------------------------

    /**
     * run the refinement/transform pipeline
     *
     * Entries are executed in the order they were added.
     * If a refinement fails, subsequent entries are still
     * executed (to collect all issues). If any issues exist
     * after refinements, transforms are skipped.
     *
     * @return array{mixed, bool}
     * - [0] the (possibly transformed) data
     * - [1] true if the pipeline completed without issues
     */
    protected function runPipeline(
        mixed $data,
        ValidationContext $context,
    ): array {
        foreach ($this->pipeline as $entry) {
            switch ($entry['type']) {
                case 'refine':
                    /** @var callable(mixed): bool $fn */
                    $fn = $entry['callable'];
                    $passed = $fn($data);
                    if (! $passed) {
                        /** @var non-empty-string $message */
                        $message = $entry['message'];
                        $context->addIssue(
                            code: IssueCode::Custom,
                            input: $data,
                            message: $message,
                        );
                    }
                    break;

                case 'superRefine':
                    /** @var callable(mixed, ValidationContext): void $fn */
                    $fn = $entry['callable'];
                    $fn($data, $context);
                    break;

                case 'transform':
                    // only run transforms if no issues so far
                    if ($context->hasIssues()) {
                        return [$data, false];
                    }
                    /** @var callable(mixed): mixed $fn */
                    $fn = $entry['callable'];
                    $data = $fn($data);
                    break;
            }
        }

        return [$data, ! $context->hasIssues()];
    }
}
