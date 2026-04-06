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
use StusDevKit\ValidationKit\Contracts\PipelineStep;
use StusDevKit\ValidationKit\Contracts\ValidationConstraint;
use StusDevKit\ValidationKit\Contracts\ValidationSchema;
use StusDevKit\ValidationKit\Contracts\ValueCoercion;
use StusDevKit\ValidationKit\Contracts\ValueTransformer;
use StusDevKit\ValidationKit\Exceptions\ValidationException;
use StusDevKit\ValidationKit\Internals\ValidationContext;
use StusDevKit\ValidationKit\ParseResult;
use StusDevKit\ValidationKit\Transformers\CustomConstraint;
use StusDevKit\ValidationKit\Transformers\CustomTransform;
use StusDevKit\ValidationKit\ValidationIssue;

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
 * @implements ValidationSchema<TOutput>
 *
 * @phpstan-type ErrorCallback callable(mixed): ValidationIssue
 * @phpstan-type SchemaMetadata array<string, mixed>
 */
abstract class BaseSchema implements ValidationSchema
{
    /**
     * error callback for type-check failures
     *
     * Always set — each schema provides a default in its constructor
     *
     * @var ErrorCallback
     */
    protected mixed $typeCheckError;

    protected ?string $schemaId = null;
    protected ?string $title = null;
    protected ?string $description = null;

    /** @var list<mixed> */
    protected array $examples = [];

    protected bool $deprecated = false;
    protected bool $readOnly = false;
    protected bool $writeOnly = false;

    /** @var SchemaMetadata */
    protected array $metadata = [];

    protected bool $hasDefault = false;
    protected mixed $defaultValue;

    protected ValueCoercion $coercion;

    /** @var list<PipelineStep> */
    protected array $steps = [];

    /** @var ValidationSchema<mixed>|null */
    protected ?ValidationSchema $pipeTarget = null;

    protected bool $hasCatch = false;
    protected mixed $catchFallback;

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
     * provide a default value for null or missing input
     *
     * When the input is null (or the key is missing in an
     * object schema), the default value is used instead.
     * The default value is not validated against the schema.
     */
    public function withDefault(mixed $value): static
    {
        $clone = clone $this;
        $clone->hasDefault = true;
        $clone->defaultValue = $value;

        return $clone;
    }

    /**
     * set the schema identifier ($id)
     *
     * The schema ID is an absolute URI that identifies
     * this schema. It is emitted as the `$id` keyword
     * in JSON Schema exports.
     *
     * @param non-empty-string $id
     */
    public function withSchemaId(string $id): static
    {
        $clone = clone $this;
        $clone->schemaId = $id;

        return $clone;
    }

    /**
     * add a human-readable title to this schema
     *
     * @param non-empty-string $text
     */
    public function withTitle(string $text): static
    {
        $clone = clone $this;
        $clone->title = $text;

        return $clone;
    }

    /**
     * add a human-readable description to this schema
     *
     * @param non-empty-string $text
     */
    public function withDescription(string $text): static
    {
        $clone = clone $this;
        $clone->description = $text;

        return $clone;
    }

    /**
     * add example values to this schema
     *
     * @param list<mixed> $values
     */
    public function withExamples(array $values): static
    {
        $clone = clone $this;
        $clone->examples = $values;

        return $clone;
    }

    /**
     * mark this schema as deprecated
     */
    public function withDeprecated(
        bool $deprecated = true,
    ): static {
        $clone = clone $this;
        $clone->deprecated = $deprecated;

        return $clone;
    }

    /**
     * mark this schema as read-only
     */
    public function withReadOnly(
        bool $readOnly = true,
    ): static {
        $clone = clone $this;
        $clone->readOnly = $readOnly;

        return $clone;
    }

    /**
     * mark this schema as write-only
     */
    public function withWriteOnly(
        bool $writeOnly = true,
    ): static {
        $clone = clone $this;
        $clone->writeOnly = $writeOnly;

        return $clone;
    }

    /**
     * attach arbitrary metadata to this schema
     *
     * Replaces any existing metadata. Metadata does not
     * affect validation behaviour — it is used by tooling
     * such as JSON Schema generation, code generation,
     * and documentation.
     *
     * @param SchemaMetadata $data
     */
    public function withMetadata(array $data): static
    {
        $clone = clone $this;
        $clone->metadata = $data;

        return $clone;
    }

    /**
     * add a step to the pipeline
     *
     * Returns a new schema instance with the step
     * appended. Steps run in the order they are added,
     * after the type check and validateChildren have
     * passed. Steps with skipOnIssues() returning true
     * cause the pipeline to stop when prior issues exist.
     */
    protected function withStep(PipelineStep $step): static
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
    public function withConstraint(
        ValidationConstraint $constraint,
    ): static {
        return $this->withStep($constraint);
    }

    /**
     * add a pre-constraint normaliser to this schema
     *
     * Convenience method — equivalent to withStep().
     */
    public function withNormaliser(
        ValueTransformer $normaliser,
    ): static {
        return $this->withStep($normaliser);
    }

    /**
     * add a data transformation step
     *
     * Convenience method — equivalent to withStep().
     */
    public function withTransformer(
        ValueTransformer $transformer,
    ): static {
        return $this->withStep($transformer);
    }

    /**
     * add a custom data transformation step
     *
     * The callable receives the validated data and returns
     * the transformed value. Transforms are skipped when
     * prior pipeline steps have produced issues.
     *
     * For reusable transforms, prefer withTransformer()
     * with a ValueTransformer object instead.
     *
     * @param callable(mixed): mixed $fn
     */
    public function withCustomTransform(callable $fn): static
    {
        return $this->withStep(
            new CustomTransform($fn),
        );
    }

    /**
     * add a custom validation rule
     *
     * The callable receives the validated data and returns
     * null on success or an error message string on failure.
     * A non-null return creates a Custom issue with the
     * returned message.
     *
     * For reusable constraints, prefer withConstraint()
     * with a ValidationConstraint object instead.
     *
     * @param callable(mixed): ?string $fn
     */
    public function withCustomConstraint(callable $fn): static
    {
        return $this->withStep(
            new CustomConstraint($fn),
        );
    }

    /**
     * chain the output to another schema
     *
     * After this schema validates and transforms the data,
     * the result is passed to the target schema for further
     * validation.
     *
     * @param ValidationSchema<mixed> $schema
     */
    public function withPipe(ValidationSchema $schema): static
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
    public function withCatch(mixed $fallback): static
    {
        $clone = clone $this;
        $clone->hasCatch = true;
        $clone->catchFallback = $fallback;

        return $clone;
    }

    // ================================================================
    //
    // ValidationSchema Interface
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
            // withCatch() provides a fallback on failure
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
            // withCatch() provides a fallback on failure
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
     * validate the given data without applying
     * withDefault() or withCatch() fallbacks
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
     * validate the given data without applying
     * withDefault() or withCatch() fallbacks, returning
     * a result object instead of throwing
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
     * Like parseWithContext(), but skips the withDefault()
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

    // ================================================================
    //
    // Error Callback Helpers
    //
    // ----------------------------------------------------------------

    /**
     * invoke an error callback and add the resulting issue
     * to the validation context
     *
     * The issue's path is replaced with the context's
     * current path, so callbacks do not need to know their
     * position in the data structure.
     *
     * @param ErrorCallback $callback
     */
    protected function invokeErrorCallback(
        callable $callback,
        mixed $input,
        ValidationContext $context,
    ): void {
        $issue = $callback($input);
        $context->addExistingIssue(
            $issue->withPath($context->path()),
        );
    }

    // ================================================================
    //
    // Metadata Getters
    //
    // ----------------------------------------------------------------

    /**
     * return the schema ID, or null if none was set
     */
    public function maybeSchemaId(): ?string
    {
        return $this->schemaId;
    }

    /**
     * return the title, or null if none was set
     */
    public function maybeTitle(): ?string
    {
        return $this->title;
    }

    /**
     * return the description, or null if none was set
     */
    public function maybeDescription(): ?string
    {
        return $this->description;
    }

    /**
     * return the example values
     *
     * @return list<mixed>
     */
    public function getExamples(): array
    {
        return $this->examples;
    }

    /**
     * is this schema deprecated?
     */
    public function isDeprecated(): bool
    {
        return $this->deprecated;
    }

    /**
     * is this schema read-only?
     */
    public function isReadOnly(): bool
    {
        return $this->readOnly;
    }

    /**
     * is this schema write-only?
     */
    public function isWriteOnly(): bool
    {
        return $this->writeOnly;
    }

    /**
     * return the metadata
     *
     * @return SchemaMetadata
     */
    public function getMetadata(): array
    {
        return $this->metadata;
    }

    // ================================================================
    //
    // Introspection
    //
    // ----------------------------------------------------------------

    /**
     * return the pipeline steps
     *
     * Returns the ordered list of normalisers, constraints,
     * and transforms that have been added to this schema.
     *
     * @return list<PipelineStep>
     */
    public function getSteps(): array
    {
        return $this->steps;
    }

    /**
     * does this schema have a default value?
     */
    public function hasDefaultValue(): bool
    {
        return $this->hasDefault;
    }

    /**
     * return the default value
     *
     * Only meaningful when hasDefaultValue() is true.
     */
    public function getDefaultValue(): mixed
    {
        return $this->defaultValue;
    }

    /**
     * does this schema have a catch fallback?
     */
    public function hasCatchFallback(): bool
    {
        return $this->hasCatch;
    }

    /**
     * return the catch fallback value
     *
     * Only meaningful when hasCatchFallback() is true.
     */
    public function getCatchFallback(): mixed
    {
        return $this->catchFallback;
    }

    /**
     * return the pipe target schema, or null if none
     *
     * @return ValidationSchema<mixed>|null
     */
    public function maybePipeTarget(): ?ValidationSchema
    {
        return $this->pipeTarget;
    }
}
