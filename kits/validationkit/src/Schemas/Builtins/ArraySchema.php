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

use StusDevKit\ValidationKit\Constraints\ArrayContainsConstraint;
use StusDevKit\ValidationKit\Constraints\ArrayExactLengthConstraint;
use StusDevKit\ValidationKit\Constraints\ArrayMaxLengthConstraint;
use StusDevKit\ValidationKit\Constraints\ArrayMinLengthConstraint;
use StusDevKit\ValidationKit\Constraints\ArrayUniqueItemsConstraint;
use StusDevKit\ValidationKit\Contracts\ValidationSchema;
use StusDevKit\ValidationKit\Internals\ValidationContext;
use StusDevKit\ValidationKit\Schemas\BaseSchema;
use StusDevKit\ValidationKit\ValidationIssue;

/**
 * ArraySchema validates that the input is a sequential
 * array (list) and validates each element against an
 * element schema.
 *
 * Usage:
 *
 *     use StusDevKit\ValidationKit\Validate;
 *
 *     // array of strings
 *     $tags = Validate::array(Validate::string());
 *     $tags->parse(['php', 'validation']); // ok
 *     $tags->parse(['php', 42]);           // throws
 *
 *     // with length constraints
 *     $tags = Validate::array(Validate::string())
 *         ->min(length: 1)
 *         ->max(length: 10);
 *
 *     // nonempty shorthand
 *     $tags = Validate::array(Validate::string())
 *         ->nonempty();
 *
 * @template TElement
 * @extends BaseSchema<list<TElement>>
 * @phpstan-type ErrorCallback callable(mixed): ValidationIssue
 */
class ArraySchema extends BaseSchema
{
    /**
     * @param ValidationSchema<TElement> $elementSchema
     * - the schema to validate each element against
     * @param (callable(mixed): ValidationIssue)|null $typeCheckError
     */
    public function __construct(
        private readonly ValidationSchema $elementSchema,
        ?callable $typeCheckError = null,
    ) {
        parent::__construct();

        $this->typeCheckError = $typeCheckError
            ?? $this->getDefaultTypeCheckError();
    }

    // ================================================================
    //
    // Default Error Callbacks
    //
    // ----------------------------------------------------------------

    protected function getDefaultTypeCheckError(): callable
    {
        return static fn(mixed $data) => new ValidationIssue(
            type: 'https://stusdevkit.dev/errors/validation/invalid_type',
            input: $data,
            path: [],
            message: 'Expected array, received '
                . get_debug_type($data),
        );
    }

    // ================================================================
    //
    // Constraint Builder Methods
    //
    // ----------------------------------------------------------------

    /**
     * require the array to have at least the given number
     * of elements
     *
     * @param ErrorCallback|null $error
     */
    public function min(int $length, ?callable $error = null): static
    {
        return $this->withConstraint(
            new ArrayMinLengthConstraint(
                length: $length,
                error: $error,
            ),
        );
    }

    /**
     * require the array to have at most the given number
     * of elements
     *
     * @param ErrorCallback|null $error
     */
    public function max(int $length, ?callable $error = null): static
    {
        return $this->withConstraint(
            new ArrayMaxLengthConstraint(
                length: $length,
                error: $error,
            ),
        );
    }

    /**
     * require the array to have exactly the given number
     * of elements
     *
     * @param ErrorCallback|null $error
     */
    public function length(int $length, ?callable $error = null): static
    {
        return $this->withConstraint(
            new ArrayExactLengthConstraint(
                length: $length,
                error: $error,
            ),
        );
    }

    /**
     * require the array to have at least one element
     *
     * Shorthand for min(length: 1).
     *
     * @param ErrorCallback|null $error
     */
    public function notEmpty(?callable $error = null): static
    {
        return $this->min(length: 1, error: $error);
    }

    /**
     * require the array to contain elements matching the
     * given schema, with optional bounds on how many
     * matches are required
     *
     * When neither minContains nor maxContains is set, at
     * least one element must match.
     *
     * @param ValidationSchema<mixed> $schema
     * @param ErrorCallback|null $error
     */
    public function contains(
        ValidationSchema $schema,
        ?int $minContains = null,
        ?int $maxContains = null,
        ?callable $error = null,
    ): static {
        return $this->withConstraint(
            new ArrayContainsConstraint(
                schema: $schema,
                minContains: $minContains,
                maxContains: $maxContains,
                error: $error,
            ),
        );
    }

    /**
     * require the array to contain only unique items
     *
     * Uses strict comparison (===) to detect duplicates.
     *
     * @param ErrorCallback|null $error
     */
    public function uniqueItems(?callable $error = null): static
    {
        return $this->withConstraint(
            new ArrayUniqueItemsConstraint(error: $error),
        );
    }

    // ================================================================
    //
    // Introspection
    //
    // ----------------------------------------------------------------

    /**
     * return the element schema
     *
     * @return ValidationSchema<TElement>
     */
    public function elementSchema(): ValidationSchema
    {
        return $this->elementSchema;
    }

    // ================================================================
    //
    // BaseSchema Implementation
    //
    // ----------------------------------------------------------------

    protected function checkType(
        mixed $data,
        ValidationContext $context,
    ): bool {
        if (is_array($data)) {
            return true;
        }

        $this->invokeErrorCallback(
            callback: $this->typeCheckError,
            input: $data,
            context: $context,
        );

        return false;
    }

    /**
     * validate each element against the element schema
     */
    protected function validateChildren(
        mixed $data,
        ValidationContext $context,
    ): mixed {
        assert(is_array($data));

        $output = [];
        $index = 0;
        foreach ($data as $element) {
            $childContext = $context->atPath($index);
            $output[] = $this->elementSchema->parseWithContext(
                data: $element,
                context: $childContext,
            );
            $index++;
        }

        return $output;
    }

    /**
     * encode each element using the encode pipeline
     */
    protected function encodeChildren(
        mixed $data,
        ValidationContext $context,
    ): mixed {
        assert(is_array($data));

        $output = [];
        $index = 0;
        foreach ($data as $element) {
            $childContext = $context->atPath($index);
            $output[] = $this->elementSchema->encodeWithContext(
                data: $element,
                context: $childContext,
            );
            $index++;
        }

        return $output;
    }
}
