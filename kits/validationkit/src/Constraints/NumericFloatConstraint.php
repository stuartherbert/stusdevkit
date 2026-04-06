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
 * NumericFloatConstraint validates that a number fits
 * within the IEEE 754 single-precision (32-bit) float
 * range.
 *
 * This corresponds to the OpenAPI `format: float`
 * annotation on `type: number` schemas.
 *
 * The valid range is approximately
 * -3.4028235E+38 to 3.4028235E+38. Values outside
 * this range (but within double-precision range) are
 * rejected.
 *
 * Usage:
 *
 *     $constraint = new NumericFloatConstraint();
 *
 * @phpstan-type ErrorCallback callable(mixed): ValidationIssue
 */
final class NumericFloatConstraint implements ValidationConstraint
{
    /**
     * maximum magnitude for an IEEE 754 single-precision
     * float (FLT_MAX)
     */
    private const FLOAT32_MAX = 3.4028235E+38;

    /** @var ErrorCallback */
    private readonly mixed $error;

    /**
     * @param ErrorCallback|null $error
     * - custom error callback; if null, a default is used
     */
    public function __construct(?callable $error = null)
    {
        $this->error = $error
            ?? static fn(mixed $data) => new ValidationIssue(
                type: 'https://stusdevkit.dev/errors/validation/out_of_range',
                input: $data,
                path: [],
                message: 'Value must fit in an IEEE 754'
                    . ' single-precision (32-bit) float',
            );
    }

    // ================================================================
    //
    // ValidationConstraint Implementation
    //
    // ----------------------------------------------------------------

    /**
     * check that the number fits in the IEEE 754
     * single-precision range
     *
     * Zero, subnormal values, and values within
     * +/-3.4028235E+38 are accepted. NAN and INF are
     * rejected (they are outside the finite float32
     * range).
     *
     * @param int|float $data
     */
    public function process(
        mixed $data,
        ValidationContext $context,
    ): mixed {
        assert(is_int($data) || is_float($data));

        $abs = abs((float) $data);

        // reject NAN, INF, and values exceeding float32 max
        if (
            is_nan((float) $data)
            || $abs > self::FLOAT32_MAX
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
