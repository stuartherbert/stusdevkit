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
// COPYRIGHT HOLDERS AND CONTRIBUTORS BE LIABLE FOR ANY DIRECT,
// INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
// (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
// SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)
// HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT,
// STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
// ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED
// OF THE POSSIBILITY OF SUCH DAMAGE.

declare(strict_types=1);

namespace StusDevKit\AssertionsKit\Exceptions;

use StusDevKit\ExceptionsKit\Exceptions\Rfc9457ProblemDetailsException;

/**
 * AssertionFailedException is thrown when an assertion
 * check fails.
 *
 * The `extra` array carries `expected` and `actual` fields
 * describing what was expected and what was found. Values
 * must be int or string (per the RFC 9457 extra format).
 *
 * Usage:
 *
 *     throw new AssertionFailedException(
 *         title: 'Failed asserting that a condition is true',
 *         extra: [
 *             'expected' => 'true',
 *             'actual' => 'false',
 *         ],
 *     );
 *
 *     // with a user-provided message:
 *     throw new AssertionFailedException(
 *         title: 'Failed asserting that a condition is true',
 *         extra: [
 *             'expected' => 'true',
 *             'actual' => 'false',
 *         ],
 *         detail: 'user activation flag should be true',
 *     );
 *
 * @phpstan-import-type ProblemReportExtra from Rfc9457ProblemDetailsException
 */
class AssertionFailedException extends Rfc9457ProblemDetailsException
{
    /**
     * @param non-empty-string $title
     * - short description of the assertion that failed
     * @param ProblemReportExtra $extra
     * - additional information, typically including
     *   'expected' and 'actual' fields
     * @param string $detail
     * - user-provided message explaining the context
     *   of the failure; if empty, the title is used
     *   as the exception message instead
     */
    public function __construct(
        string $title,
        array $extra = [],
        string $detail = '',
    ) {
        parent::__construct(
            type: 'https://github.com/stuartherbert/stusdevkit/errors/assertion-failed',
            status: 422,
            title: $title,
            extra: $extra,
            detail: $detail !== '' ? $detail : null,
        );
    }
}
