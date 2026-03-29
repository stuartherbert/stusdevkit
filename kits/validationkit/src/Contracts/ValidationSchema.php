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

namespace StusDevKit\ValidationKit\Contracts;

use StusDevKit\ValidationKit\Exceptions\ValidationException;
use StusDevKit\ValidationKit\Internals\ValidationContext;
use StusDevKit\ValidationKit\ParseResult;

/**
 * ValidationSchema is the core contract for all
 * validation schemas.
 *
 * It defines the complete public API that end-users can
 * call, and the internal API that all schemas must
 * implement in order to participate in schema composition.
 *
 * Every validation schema supports:
 *
 * - Decoding: parse/safeParse (or decode/safeDecode)
 *   validate input data and return the validated result.
 * - Encoding: encode/safeEncode validate data without
 *   applying default() or catch() fallbacks.
 * - Builder methods: withCustomTransform,
 *   withCustomConstraint, withPipe, withCatch,
 *   withDefault, etc. configure the validation pipeline.
 * - Metadata: withDescription and withMeta attach
 *   non-validation metadata for tooling.
 * - Internal composition: parseWithContext and
 *   encodeWithContext allow parent schemas to validate
 *   child schemas with path tracking.
 *
 * @template-covariant TOutput
 */
interface ValidationSchema
{
    // ================================================================
    //
    // Core Validation
    //
    // ----------------------------------------------------------------

    /**
     * validate the given data and return the validated
     * (and possibly transformed) result
     *
     * @return TOutput
     * @throws ValidationException if validation fails.
     */
    public function parse(mixed $data): mixed;

    /**
     * validate the given data and return a result object
     * instead of throwing
     *
     * @return ParseResult<TOutput>
     */
    public function safeParse(mixed $data): ParseResult;

    /**
     * alias for parse()
     *
     * @return TOutput
     * @throws ValidationException if validation fails.
     */
    public function decode(mixed $data): mixed;

    /**
     * alias for safeParse()
     *
     * @return ParseResult<TOutput>
     */
    public function safeDecode(mixed $data): ParseResult;

    /**
     * validate the given data without applying withDefault()
     * or withCatch() fallbacks
     *
     * @return TOutput
     * @throws ValidationException if validation fails.
     */
    public function encode(mixed $data): mixed;

    /**
     * validate the given data without applying withDefault()
     * or withCatch() fallbacks, returning a result object
     * instead of throwing
     *
     * @return ParseResult<TOutput>
     */
    public function safeEncode(mixed $data): ParseResult;

    // ================================================================
    //
    // Builder Methods
    //
    // ----------------------------------------------------------------

    /**
     * add a validation constraint to this schema
     */
    public function withConstraint(
        ValidationConstraint $constraint,
    ): static;

    /**
     * add a pre-constraint normaliser to this schema
     */
    public function withNormaliser(
        ValueTransformer $normaliser,
    ): static;

    /**
     * add a data transformation step
     *
     * The transformer receives the validated data and
     * returns the transformed value. Transformers are
     * skipped when prior pipeline steps have produced
     * issues.
     */
    public function withTransformer(
        ValueTransformer $transformer,
    ): static;

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
    public function withCustomTransform(callable $fn): static;

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
    public function withCustomConstraint(callable $fn): static;

    /**
     * chain the output to another schema
     *
     * After this schema validates and transforms the data,
     * the result is passed to the target schema for further
     * validation.
     *
     * @param ValidationSchema<mixed> $schema
     */
    public function withPipe(self $schema): static;

    /**
     * provide a fallback value on validation failure
     *
     * If validation fails, the fallback value is returned
     * instead of throwing an exception.
     */
    public function withCatch(mixed $fallback): static;

    /**
     * provide a default value for null or missing input
     *
     * The default value is NOT validated against the
     * schema.
     */
    public function withDefault(mixed $value): static;

    // ================================================================
    //
    // Metadata
    //
    // ----------------------------------------------------------------

    /**
     * add a human-readable description to this schema
     *
     * @param non-empty-string $text
     */
    public function withDescription(string $text): static;

    /**
     * attach arbitrary metadata to this schema
     *
     * @param array<string, mixed> $data
     */
    public function withMeta(array $data): static;

    /**
     * return the description, or null if none was set
     */
    public function maybeDescription(): ?string;

    /**
     * return the metadata
     *
     * @return array<string, mixed>
     */
    public function metadata(): array;

    // ================================================================
    //
    // Internal Composition
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
    ): mixed;

    /**
     * run the encode pipeline with a given context
     *
     * Like parseWithContext(), but skips the withDefault()
     * fallback for null values.
     *
     * @internal
     */
    public function encodeWithContext(
        mixed $data,
        ValidationContext $context,
    ): mixed;
}
