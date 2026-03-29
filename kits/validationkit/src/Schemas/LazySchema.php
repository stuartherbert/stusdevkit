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

use Closure;
use StusDevKit\ValidationKit\Contracts\ValidationConstraint;
use StusDevKit\ValidationKit\Contracts\ValidationSchema;
use StusDevKit\ValidationKit\Contracts\ValueTransformer;
use StusDevKit\ValidationKit\Exceptions\ValidationException;
use StusDevKit\ValidationKit\Internals\ValidationContext;
use StusDevKit\ValidationKit\ParseResult;

/**
 * LazySchema defers schema resolution until the first
 * validation call, enabling recursive schema definitions.
 *
 * The factory closure is called once on first use and the
 * resulting schema is cached for all subsequent calls.
 *
 * This is necessary because PHP evaluates the right-hand
 * side of an assignment before binding the variable, so
 * a schema cannot reference itself during construction.
 * LazySchema solves this by storing a closure that is
 * only invoked when validation runs, at which point the
 * variable is fully defined.
 *
 * IMPORTANT: the closure must capture the schema variable
 * by reference (`use (&$var)`) because the variable is
 * not yet assigned when the closure is created. Arrow
 * functions (`fn()`) capture by value and will not work.
 *
 * Usage:
 *
 *     use StusDevKit\ValidationKit\Validate;
 *
 *     // recursive tree structure
 *     $treeNode = Validate::object([
 *         'value' => Validate::string(),
 *         'children' => Validate::array(
 *             Validate::lazy(
 *                 function () use (&$treeNode) {
 *                     return $treeNode;
 *                 },
 *             ),
 *         ),
 *     ]);
 *
 *     $treeNode->parse([
 *         'value' => 'root',
 *         'children' => [
 *             [
 *                 'value' => 'child',
 *                 'children' => [],
 *             ],
 *         ],
 *     ]);
 *
 * @template TOutput
 * @implements ValidationSchema<TOutput>
 */
class LazySchema implements ValidationSchema
{
    /**
     * the factory closure that produces the real schema
     *
     * Set to null after the schema has been resolved.
     *
     * @var (Closure(): ValidationSchema<TOutput>)|null
     */
    private ?Closure $factory;

    /**
     * the resolved schema, cached after first use
     *
     * @var ValidationSchema<TOutput>|null
     */
    private ?ValidationSchema $resolved = null;

    /**
     * @param Closure(): ValidationSchema<TOutput> $factory
     * - a closure that returns the real schema; called
     *   once on first validation
     */
    public function __construct(Closure $factory)
    {
        $this->factory = $factory;
    }

    // ================================================================
    //
    // Schema Resolution
    //
    // ----------------------------------------------------------------

    /**
     * resolve the factory closure and cache the result
     *
     * Called automatically on first validation. The factory
     * closure is released after resolution to avoid holding
     * references longer than necessary.
     *
     * @return ValidationSchema<TOutput>
     */
    private function resolve(): ValidationSchema
    {
        if ($this->resolved === null) {
            assert($this->factory !== null);
            $this->resolved = ($this->factory)();
            $this->factory = null;
        }

        return $this->resolved;
    }

    // ================================================================
    //
    // Core Validation (delegated to resolved schema)
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
        return $this->resolve()->parse($data);
    }

    /**
     * validate the given data and return a result object
     * instead of throwing
     *
     * @return ParseResult<TOutput>
     */
    public function safeParse(mixed $data): ParseResult
    {
        return $this->resolve()->safeParse($data);
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
        return $this->resolve()->decode($data);
    }

    /**
     * alias for safeParse()
     *
     * @return ParseResult<TOutput>
     */
    public function safeDecode(mixed $data): ParseResult
    {
        return $this->resolve()->safeDecode($data);
    }

    /**
     * validate the given data without applying withDefault()
     * or withCatch() fallbacks
     *
     * @return TOutput
     * @throws ValidationException
     *         if validation fails.
     */
    public function encode(mixed $data): mixed
    {
        return $this->resolve()->encode($data);
    }

    /**
     * validate the given data without applying withDefault()
     * or withCatch() fallbacks, returning a result object
     * instead of throwing
     *
     * @return ParseResult<TOutput>
     */
    public function safeEncode(mixed $data): ParseResult
    {
        return $this->resolve()->safeEncode($data);
    }

    // ================================================================
    //
    // Builder Methods
    //
    // Each builder resolves the inner schema, applies the
    // modification, and wraps the result in a new LazySchema
    // so that the return type is correctly `static`.
    //
    // ----------------------------------------------------------------

    /**
     * create a new LazySchema that wraps an already-resolved
     * schema
     *
     * @param ValidationSchema<TOutput> $schema
     * @return static
     */
    private function wrapResolved(ValidationSchema $schema): static
    {
        $new = clone $this;
        $new->resolved = $schema;
        $new->factory = null;

        return $new;
    }

    /**
     * add a validation constraint to this schema
     */
    public function withConstraint(
        ValidationConstraint $constraint,
    ): static {
        return $this->wrapResolved(
            $this->resolve()->withConstraint($constraint),
        );
    }

    /**
     * add a pre-constraint normaliser to this schema
     */
    public function withNormaliser(
        ValueTransformer $normaliser,
    ): static {
        return $this->wrapResolved(
            $this->resolve()->withNormaliser($normaliser),
        );
    }

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
    ): static {
        return $this->wrapResolved(
            $this->resolve()->withTransformer($transformer),
        );
    }

    /**
     * add a custom data transformation step
     *
     * @param callable(mixed): mixed $fn
     */
    public function withCustomTransform(callable $fn): static
    {
        return $this->wrapResolved(
            $this->resolve()->withCustomTransform($fn),
        );
    }

    /**
     * add a custom validation rule
     *
     * @param callable(mixed): ?string $fn
     */
    public function withCustomConstraint(callable $fn): static
    {
        return $this->wrapResolved(
            $this->resolve()->withCustomConstraint($fn),
        );
    }

    /**
     * chain the output to another schema
     *
     * @param ValidationSchema<mixed> $schema
     */
    public function withPipe(ValidationSchema $schema): static
    {
        return $this->wrapResolved(
            $this->resolve()->withPipe($schema),
        );
    }

    /**
     * provide a fallback value on validation failure
     */
    public function withCatch(mixed $fallback): static
    {
        return $this->wrapResolved(
            $this->resolve()->withCatch($fallback),
        );
    }

    /**
     * provide a default value for null or missing input
     */
    public function withDefault(mixed $value): static
    {
        return $this->wrapResolved(
            $this->resolve()->withDefault($value),
        );
    }

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
    public function withDescription(string $text): static
    {
        return $this->wrapResolved(
            $this->resolve()->withDescription($text),
        );
    }

    /**
     * return the description, or null if none was set
     */
    public function maybeDescription(): ?string
    {
        return $this->resolve()->maybeDescription();
    }

    /**
     * attach arbitrary metadata to this schema
     *
     * @param array<string, mixed> $data
     */
    public function withMetadata(array $data): static
    {
        return $this->wrapResolved(
            $this->resolve()->withMetadata($data),
        );
    }

    /**
     * return the metadata
     *
     * @return array<string, mixed>
     */
    public function getMetadata(): array
    {
        return $this->resolve()->getMetadata();
    }

    // ================================================================
    //
    // Internal Composition (delegated to resolved schema)
    //
    // ----------------------------------------------------------------

    /**
     * run the full parse pipeline with a given context
     *
     * @internal
     */
    public function parseWithContext(
        mixed $data,
        ValidationContext $context,
    ): mixed {
        return $this->resolve()->parseWithContext(
            data: $data,
            context: $context,
        );
    }

    /**
     * run the encode pipeline with a given context
     *
     * @internal
     */
    public function encodeWithContext(
        mixed $data,
        ValidationContext $context,
    ): mixed {
        return $this->resolve()->encodeWithContext(
            data: $data,
            context: $context,
        );
    }
}
