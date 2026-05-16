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

namespace StusDevKit\MissingBitsKit\Arrays;

/**
 * GetArrayShape reports whether a PHP array is being used as a
 * list (every key is an int) or as a map (at least one key is a
 * string). Result is returned as an {@see ArrayShape} enum value.
 *
 * Originally added so the helper class
 * {@see StusDevKit\MissingBitsKit\NormaliseForComparison}
 * can handle list-shaped inputs (whose int keys are mere positions
 * and can be dropped) differently to map-shaped inputs (whose
 * keys ARE the identity and must be preserved).
 *
 * Extends PHP's built-in `array_is_list()`. That function returns
 * true only for keys exactly 0..n-1 with no gaps, which is the
 * wrong intuition when the caller wants to know "is this a sequence
 * of values, or a key/value map?". GetArrayShape accepts everything
 * `array_is_list()` accepts AND treats gappy / non-zero-start
 * int-keyed arrays (e.g. the leftovers from `array_filter`) as
 * lists too.
 *
 * Here Be Dragons
 * ===============
 *
 * GetArrayShape is providing an educated guess. Native PHP arrays
 * are untyped at runtime, and their array keys can cause surprises.
 *
 * For the strongest correctness guarantees, you should avoid using
 * native PHP arrays, and instead use one of the classes from the
 * CollectionsKit instead, such as
 * {@see StusDevKit\CollectionsKit\Lists\ListOfIntegers}
 * or {@see StusDevKit\CollectionsKit\Dictionaries\DictOfIntegers}.
 *
 * - **Empty array reports as LIST.** An empty array is technically
 *   both list and map. We pick LIST so callers don't need a third
 *   "neither" case, and to match PHP's own `array_is_list([])`
 *   returning `true`.
 *
 * - **PHP coerces numeric-string keys to ints.** A literal
 *   `["10" => 'x']` is stored with the int key `10`. To
 *   GetArrayShape it is indistinguishable from `[10 => 'x']` -
 *   both report as LIST. If you need the source key type
 *   preserved, do not pass through a PHP array.
 */
class GetArrayShape
{
    /**
     * inspect an array and return whether it is being used as a
     * list or a map.
     *
     * @param array<mixed> $input
     *      the array to inspect
     * @return ArrayShape
     *      how this shape is being used
     */
    public static function from(array $input): ArrayShape
    {
        // fast path: PHP's standard library already detects true
        // lists (keys 0..n-1, no gaps) in O(1) for packed arrays
        // via the internal HASH_FLAG_PACKED flag - that's the
        // overwhelmingly common literal-`[a, b, c]` case. Defer
        // to it, then fall through to a manual walk for the
        // looser shapes (gaps, non-zero starts) that still count
        // as lists for our purposes.
        if (array_is_list($input)) {
            return ArrayShape::LIST;
        }

        // general case: walk the keys until we see a string. As
        // soon as we do, we know this is a map - the keys are no
        // longer mere positions, so they must be carrying
        // identity. Stopping on first sighting avoids walking the
        // whole array for the typical map shape.
        foreach ($input as $key => $_) {
            if (is_string($key)) {
                return ArrayShape::MAP;
            }
        }

        // if we get here, every key was an int but the keys
        // weren't 0..n-1 contiguous - a gappy or non-zero-start
        // int-keyed array. Still a list for our purposes.
        return ArrayShape::LIST;
    }
}
