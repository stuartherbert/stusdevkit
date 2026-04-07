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

use DateTimeImmutable;
use StusDevKit\ValidationKit\Internals\ValidationContext;
use StusDevKit\ValidationKit\ValidationIssue;

/**
 * StringDateTimeConstraint checks that a string is a valid
 * date-time in RFC 3339 / ISO 8601 format.
 *
 * Accepts the format YYYY-MM-DDTHH:MM:SS with optional
 * fractional seconds and a required timezone designator
 * (Z or +/-HH:MM), as specified by RFC 3339 section 5.6.
 *
 * Usage:
 *
 *     $constraint = new StringDateTimeConstraint();
 *     // or with custom error
 *     $constraint = new StringDateTimeConstraint(
 *         error: fn($data) => new ValidationIssue(...),
 *     );
 *
 * @phpstan-type ErrorCallback callable(mixed): ValidationIssue
 */
final class StringDateTimeConstraint extends BaseConstraint
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
                message: 'Invalid date-time',
            );
    }

    // ================================================================
    //
    // ValidationConstraint Interface
    //
    // ----------------------------------------------------------------

    /**
     * check that the string is a valid date-time
     *
     * Uses PHP's DateTimeImmutable to parse and validate
     * the string, then checks the formatted output matches
     * the input to catch invalid calendar dates like
     * 2024-02-30T00:00:00Z.
     *
     * @param string $data
     */
    public function process(
        mixed $data,
        ValidationContext $context,
    ): mixed {
        assert(is_string($data));

        // check the format matches RFC 3339 date-time:
        // YYYY-MM-DDTHH:MM:SS[.fractional](Z|+/-HH:MM)
        if (
            preg_match(
                '/^\d{4}-\d{2}-\d{2}[Tt]\d{2}:\d{2}:\d{2}'
                . '(\.\d+)?'
                . '([Zz]|[+-]\d{2}:\d{2})$/',
                $data,
            ) !== 1
        ) {
            $issue = ($this->error)($data);
            $context->addExistingIssue(
                $issue->withPath($context->path()),
            );

            return $data;
        }

        // use DateTimeImmutable to validate that the
        // date and time components are real calendar
        // values (e.g. reject 2024-02-30T00:00:00Z)
        $parsed = DateTimeImmutable::createFromFormat(
            'Y-m-d\TH:i:sP',
            // normalise lowercase t/z to uppercase for
            // consistent parsing
            str_replace(
                ['t', 'z'],
                ['T', 'Z'],
                // strip fractional seconds for parsing,
                // because the format string doesn't include
                // them (PHP's 'v'/'u' specifiers are
                // unreliable for variable-length fractions)
                preg_replace('/\.\d+/', '', $data) ?? $data,
            ),
        );

        if ($parsed === false) {
            $issue = ($this->error)($data);
            $context->addExistingIssue(
                $issue->withPath($context->path()),
            );

            return $data;
        }

        // confirm the parsed date matches the input date
        // to catch values like month 13 or day 32 that
        // DateTimeImmutable silently rolls over
        $inputDate = substr($data, 0, 10);
        $parsedDate = $parsed->format('Y-m-d');
        if ($inputDate !== $parsedDate) {
            $issue = ($this->error)($data);
            $context->addExistingIssue(
                $issue->withPath($context->path()),
            );
        }

        return $data;
    }
}
