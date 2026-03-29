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
 * StringTimeConstraint checks that a string is a valid
 * time in RFC 3339 format.
 *
 * Accepts HH:MM:SS with optional fractional seconds and
 * timezone offset (Z or +/-HH:MM).
 *
 * Usage:
 *
 *     $constraint = new StringTimeConstraint();
 *     // or with custom error
 *     $constraint = new StringTimeConstraint(
 *         error: fn($data) => new ValidationIssue(...),
 *     );
 *
 * @phpstan-type ErrorCallback callable(mixed): ValidationIssue
 */
final class StringTimeConstraint implements ValidationConstraint
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
                message: 'Invalid time',
            );
    }

    // ================================================================
    //
    // ValidationConstraint Interface
    //
    // ----------------------------------------------------------------

    /**
     * check that the string is a valid time
     *
     * @param string $data
     */
    public function process(
        mixed $data,
        ValidationContext $context,
    ): mixed {
        assert(is_string($data));

        // check the format matches HH:MM:SS with optional
        // fractional seconds and timezone offset
        if (preg_match('/^\d{2}:\d{2}:\d{2}(\.\d+)?(Z|[+-]\d{2}:\d{2})?$/i', $data) !== 1) {
            $issue = ($this->error)($data);
            $context->addExistingIssue(
                $issue->withPath($context->path()),
            );

            return $data;
        }

        // extract hours, minutes, and seconds for range
        // validation
        $parts = explode(':', $data);
        $hours = (int) $parts[0];
        $minutes = (int) $parts[1];
        $seconds = (int) $parts[2];

        // validate ranges: hours 0-23, minutes 0-59,
        // seconds 0-60 (60 for leap seconds per RFC 3339)
        if (
            $hours < 0 || $hours > 23
            || $minutes < 0 || $minutes > 59
            || $seconds < 0 || $seconds > 60
        ) {
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
