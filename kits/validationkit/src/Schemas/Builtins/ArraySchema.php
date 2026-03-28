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

use StusDevKit\ValidationKit\Constraints\ArrayExactLengthConstraint;
use StusDevKit\ValidationKit\Constraints\ArrayMaxLengthConstraint;
use StusDevKit\ValidationKit\Constraints\ArrayMinLengthConstraint;
use StusDevKit\ValidationKit\Internals\ValidationContext;
use StusDevKit\ValidationKit\IssueCode;
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
     * @param BaseSchema<TElement> $elementSchema
     * - the schema to validate each element against
     * @param (callable(mixed): ValidationIssue)|null $typeCheckError
     */
    public function __construct(
        private readonly BaseSchema $elementSchema,
        ?callable $typeCheckError = null,
    ) {
        $this->typeCheckError = $typeCheckError
            ?? $this->getDefaultTypeCheckErrorCallbackForConstructor();
    }

    // ================================================================
    //
    // Default Error Callbacks
    //
    // ----------------------------------------------------------------

    protected function getDefaultTypeCheckErrorCallbackForConstructor(): callable
    {
        return static fn(mixed $data) => new ValidationIssue(
            code: IssueCode::InvalidType,
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
    public function nonempty(?callable $error = null): static
    {
        return $this->min(length: 1, error: $error);
    }

    // ================================================================
    //
    // BaseSchema Implementation
    //
    // ----------------------------------------------------------------

    protected function expectedType(): string
    {
        return 'array';
    }

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

    protected function checkConstraints(
        mixed $data,
        ValidationContext $context,
    ): void {
        assert(is_array($data));

        // run array-level constraints (min, max, length)
        parent::checkConstraints(
            data: $data,
            context: $context,
        );

        // stop if array-level constraints failed — don't
        // validate elements
        if ($context->hasIssues()) {
            return;
        }

        // validate each element
        $index = 0;
        foreach ($data as $element) {
            $childContext = $context->atPath($index);
            $this->elementSchema->parseWithContext(
                data: $element,
                context: $childContext,
            );
            $index++;
        }
    }
}
