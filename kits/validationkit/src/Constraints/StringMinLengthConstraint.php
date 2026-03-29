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
 * StringMinLengthConstraint validates that a string has
 * at least the specified number of characters.
 *
 * Uses mb_strlen() for multibyte-safe length checking.
 *
 * Usage:
 *
 *     use StusDevKit\ValidationKit\Schemas\Constraints\StringMinLengthConstraint;
 *
 *     // with default error message
 *     $constraint = new StringMinLengthConstraint(
 *         length: 3,
 *     );
 *
 *     // with custom error callback
 *     $constraint = new StringMinLengthConstraint(
 *         length: 3,
 *         error: fn($data) => new ValidationIssue(
 *             type: 'https://stusdevkit.dev/errors/validation/too_small',
 *             input: $data,
 *             path: [],
 *             message: 'Username is too short',
 *         ),
 *     );
 *
 * @phpstan-type ErrorCallback callable(mixed): ValidationIssue
 */
final class StringMinLengthConstraint implements ValidationConstraint
{
    /** @var ErrorCallback */
    private readonly mixed $error;

    /**
     * @param int $length
     * - the minimum number of characters required
     * @param ErrorCallback|null $error
     * - optional custom error callback; if null, a default
     *   callback is used that creates a ValidationIssue
     *   with 'https://stusdevkit.dev/errors/validation/too_small'
     */
    public function __construct(
        private readonly int $length,
        ?callable $error = null,
    ) {
        $this->error = $error
            ?? static fn(mixed $data) => new ValidationIssue(
                type: 'https://stusdevkit.dev/errors/validation/too_small',
                input: $data,
                path: [],
                message: 'String must be at least '
                    . $length . ' characters',
            );
    }

    // ================================================================
    //
    // Introspection
    //
    // ----------------------------------------------------------------

    /**
     * return the minimum length
     */
    public function length(): int
    {
        return $this->length;
    }

    // ================================================================
    //
    // ValidationConstraint Implementation
    //
    // ----------------------------------------------------------------

    /**
     * check that the string meets the minimum length
     *
     * Adds a validation issue to the context if the string
     * has fewer characters than required.
     *
     * @param string $data
     */
    public function process(
        mixed $data,
        ValidationContext $context,
    ): mixed {
        assert(is_string($data));

        if (mb_strlen($data) < $this->length) {
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
