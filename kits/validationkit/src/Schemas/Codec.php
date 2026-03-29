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

use BadMethodCallException;
use Closure;
use StusDevKit\ValidationKit\Contracts\PipelineStep;
use StusDevKit\ValidationKit\Contracts\ValidationSchema;
use StusDevKit\ValidationKit\Exceptions\ValidationException;
use StusDevKit\ValidationKit\Internals\ValidationContext;
use StusDevKit\ValidationKit\ParseResult;

/**
 * Codec is a bidirectional schema that bridges an input
 * type and an output type.
 *
 * A codec validates and transforms data in both directions:
 *
 * - decode(): input type → output type
 * - encode(): output type → input type
 *
 * Each direction validates the data against the appropriate
 * schema before and after transformation.
 *
 * All validation logic (constraints, transforms, refines)
 * belongs on the input and output schemas. The codec itself
 * only orchestrates the decode/encode pipeline.
 *
 * Usage:
 *
 *     use Ramsey\Uuid\Uuid;
 *     use Ramsey\Uuid\UuidInterface;
 *     use StusDevKit\ValidationKit\Validate;
 *
 *     $codec = Validate::codec(
 *         input: Validate::string(),
 *         output: Validate::uuid(),
 *         decode: fn(string $s)
 *             => Uuid::fromString($s),
 *         encode: fn(UuidInterface $u)
 *             => $u->toString(),
 *     );
 *
 *     // decode: string → UuidInterface
 *     $uuid = $codec->decode(
 *         '550e8400-e29b-41d4-a716-446655440000',
 *     );
 *
 *     // encode: UuidInterface → string
 *     $str = $codec->encode(Uuid::uuid7());
 *
 * @template TInput
 * @template TOutput
 * @extends BaseSchema<TOutput>
 */
class Codec extends BaseSchema
{
    /**
     * @param ValidationSchema<TInput> $inputSchema
     * - validates the serialised (input) representation
     * @param ValidationSchema<TOutput> $outputSchema
     * - validates the native (output) representation
     * @param Closure(TInput): TOutput $decoder
     * - transforms input type to output type
     * @param Closure(TOutput): TInput $encoder
     * - transforms output type to input type
     */
    public function __construct(
        private readonly ValidationSchema $inputSchema,
        private readonly ValidationSchema $outputSchema,
        private readonly Closure $decoder,
        private readonly Closure $encoder,
    ) {
        parent::__construct();
    }

    // ================================================================
    //
    // Encode (output → input)
    //
    // ----------------------------------------------------------------

    /**
     * encode the output type back to the input type
     *
     * Validates against the output schema, transforms via
     * the encoder, then validates against the input schema.
     *
     * This method bypasses all pipeline features on the
     * codec (default, catch, transform, refine, pipe).
     * Only the inner schemas' validation applies.
     *
     * @return TInput
     * @throws ValidationException
     *         if validation fails at any step.
     */
    public function encode(mixed $data): mixed
    {
        /** @var TOutput $validated */
        $validated = $this->outputSchema->encode($data);

        /** @var TInput $encoded */
        $encoded = ($this->encoder)($validated);

        return $this->inputSchema->encode($encoded);
    }

    /**
     * encode the output type back to the input type,
     * returning a result object instead of throwing
     *
     * @return ParseResult<TInput>
     */
    public function safeEncode(mixed $data): ParseResult
    {
        $outputResult = $this->outputSchema->safeEncode($data);
        if ($outputResult->failed()) {
            /** @var ParseResult<TInput> $failResult */
            $failResult = ParseResult::fail(
                $outputResult->error(),
            );

            return $failResult;
        }

        /** @var TOutput $validated */
        $validated = $outputResult->data();

        /** @var TInput $encoded */
        $encoded = ($this->encoder)($validated);

        return $this->inputSchema->safeEncode($encoded);
    }

    // ================================================================
    //
    // Blocked Methods
    //
    // These methods are not supported on Codec schemas.
    // All validation logic belongs on the input and output
    // schemas.
    //
    // ----------------------------------------------------------------

    /**
     * @throws BadMethodCallException always.
     */
    public function withStep(PipelineStep $step): never
    {
        throw new BadMethodCallException(
            'withStep() is not supported on Codec schemas'
            . ' — add steps to the input or output schema'
            . ' instead',
        );
    }

    /**
     * @throws BadMethodCallException always.
     */
    public function withConstraint(PipelineStep $step): never
    {
        throw new BadMethodCallException(
            'withConstraint() is not supported on Codec'
            . ' schemas — add constraints to the input or'
            . ' output schema instead',
        );
    }

    /**
     * @throws BadMethodCallException always.
     */
    public function withNormaliser(PipelineStep $step): never
    {
        throw new BadMethodCallException(
            'withNormaliser() is not supported on Codec'
            . ' schemas — add normalisers to the input or'
            . ' output schema instead',
        );
    }

    /**
     * @throws BadMethodCallException always.
     */
    public function transform(callable $fn): never
    {
        throw new BadMethodCallException(
            'transform() is not supported on Codec schemas'
            . ' — add transforms to the input or output'
            . ' schema instead',
        );
    }

    /**
     * @throws BadMethodCallException always.
     */
    public function refine(
        callable $fn,
        string $message,
    ): never {
        throw new BadMethodCallException(
            'refine() is not supported on Codec schemas'
            . ' — add refinements to the input or output'
            . ' schema instead',
        );
    }

    /**
     * @throws BadMethodCallException always.
     */
    public function superRefine(callable $fn): never
    {
        throw new BadMethodCallException(
            'superRefine() is not supported on Codec schemas'
            . ' — add refinements to the input or output'
            . ' schema instead',
        );
    }

    /**
     * @throws BadMethodCallException always.
     */
    public function pipe(ValidationSchema $schema): never
    {
        throw new BadMethodCallException(
            'pipe() is not supported on Codec schemas',
        );
    }

    /**
     * @throws BadMethodCallException always.
     */
    public function catch(mixed $fallback): never
    {
        throw new BadMethodCallException(
            'catch() is not supported on Codec schemas',
        );
    }

    /**
     * @throws BadMethodCallException always.
     */
    public function default(mixed $value): never
    {
        throw new BadMethodCallException(
            'default() is not supported on Codec schemas'
            . ' — add defaults to the input or output'
            . ' schema instead',
        );
    }

    // ================================================================
    //
    // Internal Parse Pipeline
    //
    // ----------------------------------------------------------------

    /**
     * run the codec encode pipeline with a given context
     *
     * Overrides the BaseSchema encode pipeline. Validates
     * against the output schema, encodes via the encoder,
     * then validates against the input schema.
     *
     * @internal
     */
    public function encodeWithContext(
        mixed $data,
        ValidationContext $context,
    ): mixed {
        // validate against output schema
        $validated = $this->outputSchema->encodeWithContext(
            data: $data,
            context: $context,
        );

        if ($context->hasIssues()) {
            return $data;
        }

        // encode: output → input
        /** @var TInput $encoded */
        $encoded = ($this->encoder)($validated);

        // validate encoded result against input schema
        return $this->inputSchema->encodeWithContext(
            data: $encoded,
            context: $context,
        );
    }

    /**
     * run the codec decode pipeline
     *
     * Overrides the BaseSchema pipeline entirely. The codec
     * first tries the output schema (pass-through for data
     * that is already the output type), then falls back to
     * the input schema → decoder → output schema path.
     *
     * @internal
     */
    public function parseWithContext(
        mixed $data,
        ValidationContext $context,
    ): mixed {
        // if data is already the output type, validate
        // and pass through
        $outputResult = $this->outputSchema->safeParse($data);
        if ($outputResult->succeeded()) {
            return $outputResult->data();
        }

        // validate against input schema
        $validated = $this->inputSchema->parseWithContext(
            data: $data,
            context: $context,
        );

        if ($context->hasIssues()) {
            return $data;
        }

        // decode: input → output
        /** @var TOutput $decoded */
        $decoded = ($this->decoder)($validated);

        // validate decoded result against output schema
        return $this->outputSchema->parseWithContext(
            data: $decoded,
            context: $context,
        );
    }

    // ================================================================
    //
    // BaseSchema Implementation
    //
    // ----------------------------------------------------------------

    /**
     * not used — parseWithContext() is overridden
     */
    protected function checkType(
        mixed $data,
        ValidationContext $context,
    ): bool {
        return false;
    }
}
