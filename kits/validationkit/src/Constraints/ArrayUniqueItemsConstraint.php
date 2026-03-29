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
use StusDevKit\ValidationKit\Internals\ValidationContext;
use StusDevKit\ValidationKit\ValidationIssue;

/**
 * ArrayUniqueItemsConstraint validates that all elements
 * in the array are unique.
 *
 * Uses strict comparison (===) to detect duplicates via
 * a nested loop comparing each pair of elements.
 *
 * Usage:
 *
 *     use StusDevKit\ValidationKit\Constraints\ArrayUniqueItemsConstraint;
 *
 *     // with default error message
 *     $constraint = new ArrayUniqueItemsConstraint();
 *
 *     // with custom error callback
 *     $constraint = new ArrayUniqueItemsConstraint(
 *         error: fn($data) => new ValidationIssue(
 *             type: 'https://stusdevkit.dev/errors/validation/custom',
 *             input: $data,
 *             path: [],
 *             message: 'Items must be unique',
 *         ),
 *     );
 *
 * @phpstan-type ErrorCallback callable(mixed): ValidationIssue
 */
final class ArrayUniqueItemsConstraint implements ValidationConstraint
{
    /** @var ErrorCallback */
    private readonly mixed $error;

    /**
     * @param ErrorCallback|null $error
     * - optional custom error callback; if null, a default
     *   callback is used that creates a ValidationIssue
     *   with 'https://stusdevkit.dev/errors/validation/custom'
     */
    public function __construct(?callable $error = null)
    {
        $this->error = $error
            ?? static fn(mixed $data) => new ValidationIssue(
                type: 'https://stusdevkit.dev/errors/validation/custom',
                input: $data,
                path: [],
                message: 'Array must contain only unique items',
            );
    }

    // ================================================================
    //
    // ValidationConstraint Implementation
    //
    // ----------------------------------------------------------------

    /**
     * check that all array elements are unique
     *
     * Iterates through every pair of elements using strict
     * comparison (===) to detect duplicates. If any duplicate
     * pair is found, a validation issue is added.
     *
     * @param array<mixed> $data
     */
    public function process(
        mixed $data,
        ValidationContext $context,
    ): mixed {
        assert(is_array($data));

        $values = array_values($data);
        $count = count($values);

        for ($i = 0; $i < $count; $i++) {
            for ($j = $i + 1; $j < $count; $j++) {
                if ($values[$i] === $values[$j]) {
                    $issue = ($this->error)($data);
                    $context->addExistingIssue(
                        $issue->withPath($context->path()),
                    );

                    return $data;
                }
            }
        }

        return $data;
    }

    public function skipOnIssues(): bool
    {
        return false;
    }
}
