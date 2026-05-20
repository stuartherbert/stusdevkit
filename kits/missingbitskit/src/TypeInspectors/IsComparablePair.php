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
namespace StusDevKit\MissingBitsKit\TypeInspectors;

use DateTimeInterface;

/**
 * Predicate. Use this to determine whether two values can be
 * meaningfully ordered against each other using PHP's relational
 * operators (`<`, `>`, `<=`, `>=`).
 *
 * Originally added for AssertApi's comparison assertions
 * (`assertGreaterThan()` and friends): they accept an
 * `int|float|string|DateTimeInterface` on each side, and must
 * reject a scalar paired with a DateTime before handing the two
 * values to `>`.
 *
 * This predicate can be used in two ways:
 *
 * 1. statically, via `IsComparablePair::check()`
 * 2. invoked, by
 *    `$instance = new IsComparablePair() ; $instance($a, $b)`
 *
 * Here Be Dragons
 * ===============
 *
 * This predicate rules out one specific footgun: PHP reports ANY
 * object as greater than ANY scalar, so `$dateTime > 5` is `true`
 * regardless of the date - the comparison is decided by type, not
 * by value. A scalar-vs-DateTime pair is therefore reported as
 * NOT comparable.
 *
 * It does NOT police the subtler within-scalar surprises. Two
 * scalars always pass, even though PHP's relational operators
 * still apply their own coercion between them: a non-numeric
 * string compared against a number is byte-compared, and a `bool`
 * drags the other operand through truthiness. If those matter to
 * your caller, guard for them yourself.
 */
class IsComparablePair
{
    /**
     * Predicate. Can `$first` and `$second` be meaningfully ordered
     * against each other using PHP's relational operators?
     *
     * @param mixed $first
     *      one of the two values to check
     * @param mixed $second
     *      the other value to check
     * @return bool
     * - `true` when both values are scalar, or both values are
     *   `DateTimeInterface` instances
     * - `false` otherwise - most importantly when a scalar is
     *   paired with a `DateTimeInterface`
     */
    public function __invoke(mixed $first, mixed $second): bool
    {
        return $this->check(first: $first, second: $second);
    }

    /**
     * Predicate. Can `$first` and `$second` be meaningfully ordered
     * against each other using PHP's relational operators?
     *
     * @param mixed $first
     *      one of the two values to check
     * @param mixed $second
     *      the other value to check
     * @return bool
     * - `true` when both values are scalar, or both values are
     *   `DateTimeInterface` instances
     * - `false` otherwise - most importantly when a scalar is
     *   paired with a `DateTimeInterface`
     */
    public static function check(mixed $first, mixed $second): bool
    {
        // special case: a pair of scalars
        //
        // PHP's relational operators order any two scalars - numbers
        // numerically, strings lexically or numerically - so a
        // scalar pair can always be ordered.
        if (is_scalar($first) && is_scalar($second)) {
            return true;
        }

        // special case: a pair of points in time
        //
        // PHP special-cases the relational operators on
        // DateTimeInterface, ordering the two instances
        // chronologically, so a DateTime pair can always be ordered.
        if ($first instanceof DateTimeInterface
            && $second instanceof DateTimeInterface) {
            return true;
        }

        // general case: anything else is not an orderable pair
        //
        // the footgun this rules out: PHP reports any object as
        // greater than any scalar, so pairing a DateTime with a
        // number would order them by type, not by value.
        return false;
    }
}
