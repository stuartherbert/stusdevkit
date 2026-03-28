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

namespace StusDevKit\ValidationKit;

/**
 * ValidationIssue represents a single validation failure.
 *
 * Each issue captures the kind of failure, the value that
 * failed, where in the data structure it occurred, and a
 * human-readable message describing the problem.
 *
 * The `type` field is an RFC 9457-compliant URI identifying
 * the class of error. Each schema and constraint defines
 * its own type URIs. Third parties can use their own URIs
 * for custom constraints.
 *
 * Usage:
 *
 *     $issue = new ValidationIssue(
 *         type: 'https://stusdevkit.dev/errors/validation/too_small',
 *         input: 'ab',
 *         path: ['username'],
 *         message: 'String must be at least 3 characters',
 *     );
 *
 *     // with additional context
 *     $issue = new ValidationIssue(
 *         type: 'https://stusdevkit.dev/errors/validation/too_small',
 *         input: 'ab',
 *         path: ['username'],
 *         message: 'String must be at least 3 characters',
 *         extra: ['minimum' => 3],
 *     );
 *
 * @phpstan-type ValidationIssueExtra array<string, int|string>
 */
final class ValidationIssue
{
    /**
     * short, human-readable summary of the error category
     *
     * Follows RFC 9457 conventions. If not provided,
     * defaults to 'Validation failed'.
     *
     * @var non-empty-string
     */
    public readonly string $title;

    /**
     * @param non-empty-string $type
     * - RFC 9457 URI identifying the class of error
     * @param mixed $input
     * - the value that failed validation
     * @param list<string|int> $path
     * - location in the data structure where the failure
     *   occurred, e.g. ['address', 'zip'] or ['items', 0]
     * @param non-empty-string $message
     * - human-readable description of the failure
     * @param string $title
     * - short summary of the error category; defaults
     *   to 'Validation failed'
     * @param ValidationIssueExtra $extra
     * - additional context about the failure, such as
     *   minimum/maximum values or expected types
     */
    public function __construct(
        public readonly string $type,
        public readonly mixed $input,
        public readonly array $path,
        public readonly string $message,
        string $title = '',
        public readonly array $extra = [],
    ) {
        $this->title = $title !== ''
            ? $title
            : 'Validation failed';
    }

    // ================================================================
    //
    // Copying
    //
    // ----------------------------------------------------------------

    /**
     * return a copy of this issue with the given path
     *
     * Used when a callback returns an issue without path
     * context, and the caller needs to set the path from
     * the current ValidationContext.
     *
     * @param list<string|int> $path
     */
    public function withPath(array $path): self
    {
        return new self(
            type: $this->type,
            input: $this->input,
            path: $path,
            message: $this->message,
            title: $this->title,
            extra: $this->extra,
        );
    }

    // ================================================================
    //
    // Formatting
    //
    // ----------------------------------------------------------------

    /**
     * return the path as a dot-separated string
     *
     * Array indexes are represented with bracket notation.
     *
     * Examples:
     * - ['address', 'zip'] => 'address.zip'
     * - ['items', 0, 'name'] => 'items[0].name'
     * - [] => '' (root level)
     */
    public function pathAsString(): string
    {
        $result = '';

        foreach ($this->path as $segment) {
            if (is_int($segment)) {
                $result .= '[' . $segment . ']';
            } elseif ($result === '') {
                $result = $segment;
            } else {
                $result .= '.' . $segment;
            }
        }

        return $result;
    }
}
