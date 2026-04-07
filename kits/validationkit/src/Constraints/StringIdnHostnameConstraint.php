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
 * StringIdnHostnameConstraint checks that a string is a
 * valid internationalised hostname per RFC 5890.
 *
 * Uses PHP's idn_to_ascii() from the intl extension to
 * convert the hostname to its ASCII-compatible encoding.
 * If conversion succeeds, the ASCII form is validated
 * against RFC 952/1123 hostname rules.
 *
 * Usage:
 *
 *     $constraint = new StringIdnHostnameConstraint();
 *
 * @phpstan-type ErrorCallback callable(mixed): ValidationIssue
 */
final class StringIdnHostnameConstraint extends BaseConstraint
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
                message: 'Invalid internationalised hostname',
            );
    }

    // ================================================================
    //
    // ValidationConstraint Interface
    //
    // ----------------------------------------------------------------

    /**
     * check that the string is a valid internationalised
     * hostname
     *
     * Converts the input to ASCII via idn_to_ascii(), then
     * validates the result against RFC 952/1123 hostname
     * rules (labels 1-63 chars, total max 253, alphanumeric
     * and hyphens only, no leading/trailing hyphens).
     *
     * @param string $data
     */
    public function process(
        mixed $data,
        ValidationContext $context,
    ): mixed {
        assert(is_string($data));

        // idn_to_ascii() throws ValueError on empty input
        if ($data === '') {
            $issue = ($this->error)($data);
            $context->addExistingIssue(
                $issue->withPath($context->path()),
            );

            return $data;
        }

        // convert to ASCII-compatible encoding
        $ascii = idn_to_ascii(
            $data,
            IDNA_DEFAULT,
            INTL_IDNA_VARIANT_UTS46,
        );

        if ($ascii === false) {
            $issue = ($this->error)($data);
            $context->addExistingIssue(
                $issue->withPath($context->path()),
            );

            return $data;
        }

        // validate the ASCII form against hostname rules
        if (
            strlen($ascii) > 253
            || preg_match(
                '/^(?!-)[a-zA-Z0-9-]{1,63}(?<!-)(\.'
                . '(?!-)[a-zA-Z0-9-]{1,63}(?<!-))*$/',
                $ascii,
            ) !== 1
        ) {
            $issue = ($this->error)($data);
            $context->addExistingIssue(
                $issue->withPath($context->path()),
            );
        }

        return $data;
    }
}
