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

use StusDevKit\ValidationKit\Internals\ValidationContext;
use StusDevKit\ValidationKit\ValidationIssue;

/**
 * StringIncludesConstraint checks that a string contains
 * the given substring.
 *
 * This constraint is applied after the type check has
 * already confirmed the data is a string. It adds an
 * issue to the context if the needle is not found
 * within the string.
 *
 * Usage:
 *
 *     use StusDevKit\ValidationKit\Validate;
 *
 *     // using the builder method on StringSchema
 *     $schema = Validate::string()->withConstraint(
 *         new StringIncludesConstraint(needle: '@'),
 *     );
 *
 *     // with a custom error callback
 *     $schema = Validate::string()->withConstraint(
 *         new StringIncludesConstraint(
 *             needle: '@',
 *             error: fn($data) => new ValidationIssue(
 *                 type: 'https://stusdevkit.dev/errors/validation/invalid_string',
 *                 input: $data,
 *                 path: [],
 *                 message: 'Must contain an @ symbol',
 *             ),
 *         ),
 *     );
 *
 * @phpstan-type ErrorCallback callable(mixed): ValidationIssue
 */
final class StringIncludesConstraint extends BaseConstraint
{
    /**
     * the substring that must be present in the input
     */
    public readonly string $needle;

    /**
     * error callback invoked when the constraint fails
     *
     * Returns a ValidationIssue describing the failure.
     * A default callback is provided if none is given.
     *
     * @var ErrorCallback
     */
    private mixed $error;

    /**
     * @param string $needle
     * - the substring that must be present in the input
     * @param (callable(mixed): ValidationIssue)|null $error
     * - optional custom error callback; if null, a default
     *   callback producing an InvalidString issue is used
     */
    public function __construct(
        string $needle,
        ?callable $error = null,
    ) {
        $this->needle = $needle;
        $this->error = $error
            ?? static fn(mixed $data) => new ValidationIssue(
                type: 'https://stusdevkit.dev/errors/validation/invalid_string',
                input: $data,
                path: [],
                message: 'String must contain "' . $needle . '"',
            );
    }

    // ================================================================
    //
    // Introspection
    //
    // ----------------------------------------------------------------

    /**
     * return the required substring
     */
    public function needle(): string
    {
        return $this->needle;
    }

    // ================================================================
    //
    // ValidationConstraint Implementation
    //
    // ----------------------------------------------------------------

    /**
     * check that the string contains the required substring
     *
     * Adds an issue to the context if the needle is not
     * found within the input string.
     *
     * @param string $data
     */
    public function process(
        mixed $data,
        ValidationContext $context,
    ): mixed {
        assert(is_string($data));

        if (! str_contains($data, $this->needle)) {
            $issue = ($this->error)($data);
            $context->addExistingIssue(
                $issue->withPath($context->path()),
            );
        }

        return $data;
    }
}
