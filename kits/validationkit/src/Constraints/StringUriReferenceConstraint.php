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
 * StringUriReferenceConstraint checks that a string is a
 * valid URI or URI relative reference per RFC 3986.
 *
 * Accepts both absolute URIs (e.g. "https://example.com")
 * and relative references (e.g. "/path/to/resource",
 * "../other", "?query=1", "#fragment").
 *
 * Usage:
 *
 *     $constraint = new StringUriReferenceConstraint();
 *
 * @phpstan-type ErrorCallback callable(mixed): ValidationIssue
 */
final class StringUriReferenceConstraint implements ValidationConstraint
{
    /** @var ErrorCallback */
    private mixed $error;

    /**
     * @param ErrorCallback|null $error
     * - custom error callback; if null, a default is used
     */
    public function __construct(?callable $error = null)
    {
        $this->error = $error
            ?? static fn(mixed $data) => new ValidationIssue(
                type: 'https://stusdevkit.dev/errors/validation/invalid_string',
                input: $data,
                path: [],
                message: 'Invalid URI reference',
            );
    }

    // ================================================================
    //
    // ValidationConstraint Interface
    //
    // ----------------------------------------------------------------

    /**
     * check that the string is a valid URI reference
     *
     * Uses PHP's parse_url() to validate well-formedness.
     * parse_url() accepts both absolute URIs and relative
     * references, returning false only for seriously
     * malformed input.
     *
     * @param string $data
     */
    public function process(
        mixed $data,
        ValidationContext $context,
    ): mixed {
        assert(is_string($data));

        // empty string is a valid relative reference
        // (current document)
        if ($data === '') {
            return $data;
        }

        // parse_url returns false for malformed URIs
        if (parse_url($data) === false) {
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
