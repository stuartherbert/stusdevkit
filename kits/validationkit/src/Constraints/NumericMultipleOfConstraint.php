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

use const PHP_FLOAT_EPSILON;

/**
 * NumericMultipleOfConstraint validates that a number is
 * an exact multiple of the specified value.
 *
 * Handles both integer and floating-point arithmetic
 * correctly, using fmod() with an epsilon tolerance for
 * float comparisons.
 *
 * Works with int, float, and int|float schemas.
 *
 * Usage:
 *
 *     use StusDevKit\ValidationKit\Schemas\Constraints\NumericMultipleOfConstraint;
 *
 *     // with default error message
 *     $constraint = new NumericMultipleOfConstraint(
 *         value: 5,
 *     );
 *
 *     // with custom error callback
 *     $constraint = new NumericMultipleOfConstraint(
 *         value: 0.01,
 *         error: fn($data) => new ValidationIssue(
 *             type: 'https://stusdevkit.dev/errors/validation/not_multiple_of',
 *             input: $data,
 *             path: [],
 *             message: 'Amount must be in whole cents',
 *         ),
 *     );
 *
 * @phpstan-type ErrorCallback callable(mixed): ValidationIssue
 */
final class NumericMultipleOfConstraint implements ValidationConstraint
{
    /** @var ErrorCallback */
    private readonly mixed $error;

    /**
     * @param int|float $value
     * - the divisor; the input must be an exact multiple
     *   of this value
     * @param ErrorCallback|null $error
     * - optional custom error callback; if null, a default
     *   callback is used that creates a ValidationIssue
     *   with 'https://stusdevkit.dev/errors/validation/not_multiple_of'
     */
    public function __construct(
        private readonly int|float $value,
        ?callable $error = null,
    ) {
        $this->error = $error
            ?? static fn(mixed $data) => new ValidationIssue(
                type: 'https://stusdevkit.dev/errors/validation/not_multiple_of',
                input: $data,
                path: [],
                message: 'Number must be a multiple of '
                    . $value,
            );
    }

    // ================================================================
    //
    // ValidationConstraint Implementation
    //
    // ----------------------------------------------------------------

    /**
     * check that the number is an exact multiple of the
     * specified value
     *
     * Uses integer modulo for pure-integer operands and
     * fmod() with PHP_FLOAT_EPSILON tolerance for any
     * floating-point operand.
     *
     * Adds a validation issue to the context if the number
     * is not an exact multiple.
     *
     * @param int|float $data
     */
    public function process(
        mixed $data,
        ValidationContext $context,
    ): mixed {
        assert(is_int($data) || is_float($data));

        // use fmod() when either operand is a float,
        // otherwise use integer modulo for exactness
        $remainder = is_float($data) || is_float($this->value)
            ? fmod((float) $data, (float) $this->value)
            : $data % $this->value;

        // for float remainders, allow a small epsilon
        // tolerance to account for IEEE 754 rounding
        $failed = is_float($remainder)
            ? abs($remainder) > PHP_FLOAT_EPSILON
            : $remainder !== 0;

        if ($failed) {
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
