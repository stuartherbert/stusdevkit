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
 * StringHostnameConstraint checks that a string is a valid
 * hostname per RFC 952/1123 rules.
 *
 * Validates total length, label lengths, and label
 * character rules.
 *
 * Usage:
 *
 *     $constraint = new StringHostnameConstraint();
 *     // or with custom error
 *     $constraint = new StringHostnameConstraint(
 *         error: fn($data) => new ValidationIssue(...),
 *     );
 *
 * @phpstan-type ErrorCallback callable(mixed): ValidationIssue
 */
final class StringHostnameConstraint extends BaseConstraint
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
                message: 'Invalid hostname',
            );
    }

    // ================================================================
    //
    // ValidationConstraint Interface
    //
    // ----------------------------------------------------------------

    /**
     * check that the string is a valid hostname
     *
     * @param string $data
     */
    public function process(
        mixed $data,
        ValidationContext $context,
    ): mixed {
        assert(is_string($data));

        // check total length: 1-253 characters
        $length = strlen($data);
        if ($length < 1 || $length > 253) {
            $issue = ($this->error)($data);
            $context->addExistingIssue(
                $issue->withPath($context->path()),
            );

            return $data;
        }

        // split into labels and validate each one
        $labels = explode('.', $data);
        foreach ($labels as $label) {
            $labelLength = strlen($label);

            // each label must be 1-63 characters and match
            // RFC 952/1123 hostname label rules
            if (
                $labelLength < 1
                || $labelLength > 63
                || preg_match('/^[a-zA-Z0-9]([a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?$/', $label) !== 1
            ) {
                $issue = ($this->error)($data);
                $context->addExistingIssue(
                    $issue->withPath($context->path()),
                );

                return $data;
            }
        }

        return $data;
    }
}
