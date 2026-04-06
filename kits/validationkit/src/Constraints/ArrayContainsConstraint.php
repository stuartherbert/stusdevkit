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

namespace StusDevKit\ValidationKit\Constraints;

use StusDevKit\ValidationKit\Contracts\ValidationConstraint;
use StusDevKit\ValidationKit\Contracts\ValidationSchema;
use StusDevKit\ValidationKit\Internals\ValidationContext;
use StusDevKit\ValidationKit\ValidationIssue;

/**
 * ArrayContainsConstraint validates that elements in the
 * array match the given schema, with optional bounds on
 * how many matches are required.
 *
 * When neither minContains nor maxContains is set, at
 * least one element must match (default behavior). When
 * bounds are set, the number of matching elements must
 * fall within the specified range.
 *
 * Usage:
 *
 *     use StusDevKit\ValidationKit\Constraints\ArrayContainsConstraint;
 *     use StusDevKit\ValidationKit\Validate;
 *
 *     // with default error message (at least one match)
 *     $constraint = new ArrayContainsConstraint(
 *         schema: Validate::string()->min(length: 1),
 *     );
 *
 *     // with min/max contains bounds
 *     $constraint = new ArrayContainsConstraint(
 *         schema: Validate::string()->min(length: 1),
 *         minContains: 2,
 *         maxContains: 5,
 *     );
 *
 *     // with custom error callback
 *     $constraint = new ArrayContainsConstraint(
 *         schema: Validate::string()->min(length: 1),
 *         error: fn($data) => new ValidationIssue(
 *             type: 'https://stusdevkit.dev/errors/validation/custom',
 *             input: $data,
 *             path: [],
 *             message: 'Must contain a non-empty string',
 *         ),
 *     );
 *
 * @phpstan-type ErrorCallback callable(mixed): ValidationIssue
 */
final class ArrayContainsConstraint implements ValidationConstraint
{
    /** @var ErrorCallback */
    private readonly mixed $error;

    /**
     * @param ValidationSchema<mixed> $schema
     * - the schema that elements must match
     * @param int|null $minContains
     * - minimum number of matching elements required;
     *   if null, defaults to 1 when maxContains is also
     *   null
     * @param int|null $maxContains
     * - maximum number of matching elements allowed;
     *   if null, no upper bound is enforced
     * @param ErrorCallback|null $error
     * - optional custom error callback; if null, a default
     *   callback is used that creates a ValidationIssue
     *   with 'https://stusdevkit.dev/errors/validation/custom'
     */
    public function __construct(
        private readonly ValidationSchema $schema,
        private readonly ?int $minContains = null,
        private readonly ?int $maxContains = null,
        ?callable $error = null,
    ) {
        $this->error = $error
            ?? static fn(mixed $data) => new ValidationIssue(
                type: 'https://stusdevkit.dev/errors/validation/custom',
                input: $data,
                path: [],
                message: self::buildDefaultMessage(
                    minContains: $minContains,
                    maxContains: $maxContains,
                ),
            );
    }

    /**
     * build the default error message based on the bounds
     *
     * @return non-empty-string
     */
    private static function buildDefaultMessage(
        ?int $minContains,
        ?int $maxContains,
    ): string {
        if ($minContains === null && $maxContains === null) {
            return 'Array must contain at least one'
                . ' element matching the schema';
        }

        if ($minContains !== null && $maxContains !== null) {
            return 'Array must contain between '
                . $minContains . ' and ' . $maxContains
                . ' elements matching the schema';
        }

        if ($minContains !== null) {
            return 'Array must contain at least '
                . $minContains
                . ' elements matching the schema';
        }

        return 'Array must contain at most '
            . $maxContains
            . ' elements matching the schema';
    }

    // ================================================================
    //
    // Introspection
    //
    // ----------------------------------------------------------------

    /**
     * return the validation schema
     *
     * @return ValidationSchema<mixed>
     */
    public function schema(): ValidationSchema
    {
        return $this->schema;
    }

    /**
     * return the minimum number of matching elements, or
     * null if no minimum is set
     */
    public function minContains(): ?int
    {
        return $this->minContains;
    }

    /**
     * return the maximum number of matching elements, or
     * null if no maximum is set
     */
    public function maxContains(): ?int
    {
        return $this->maxContains;
    }

    // ================================================================
    //
    // ValidationConstraint Implementation
    //
    // ----------------------------------------------------------------

    /**
     * check that elements matching the schema fall within
     * the expected bounds
     *
     * Iterates through all array elements and counts how
     * many match the schema. Matching indices are marked
     * as evaluated on the context so that
     * unevaluatedItems can identify which items were
     * covered by contains.
     *
     * @param array<mixed> $data
     */
    public function process(
        mixed $data,
        ValidationContext $context,
    ): mixed {
        assert(is_array($data));

        $matchCount = 0;
        $index = 0;

        foreach ($data as $element) {
            $elementContext = new ValidationContext();
            $this->schema->parseWithContext(
                data: $element,
                context: $elementContext,
            );

            if (! $elementContext->hasIssues()) {
                $matchCount++;
                $context->markEvaluated($index);
            }

            $index++;
        }

        $hasIssue = false;

        if (
            $this->minContains === null
            && $this->maxContains === null
        ) {
            // default behavior: require at least one match
            $hasIssue = $matchCount < 1;
        } else {
            if (
                $this->minContains !== null
                && $matchCount < $this->minContains
            ) {
                $hasIssue = true;
            }

            if (
                $this->maxContains !== null
                && $matchCount > $this->maxContains
            ) {
                $hasIssue = true;
            }
        }

        if ($hasIssue) {
            $issue = ($this->error)($data);
            $context->addExistingIssue(
                $issue->withPath($context->path()),
            );
        }

        return $data;
    }

    public function skipOnIssues(): bool
    {
        return false;
    }
}
