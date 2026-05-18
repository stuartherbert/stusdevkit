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
 * ArrayShape is a value object describing how a PHP array is being
 * used: as an ordered list of values, or as a key/value map.
 *
 * PHP's single `array` type serves both roles, but algorithms that
 * compare, hash, or serialise arrays usually want different
 * behaviour for each. ArrayShape gives that discrimination a name
 * the caller can pattern-match on.
 *
 * Create instances of this enum via
 * {@see GetArrayShape::from()}. This enum does not create itself.
 *
 * Pure (unbacked) on purpose: callers only ever pattern-match on
 * the cases, so the backing value would be dead weight. A later
 * release can promote to a backed enum without breaking any
 * existing caller if a real diagnostic / serialisation need
 * surfaces.
 *
 * Originally extracted from the internal array-handling logic
 * of {@see StusDevKit\MissingBitsKit\DataInspectors\GetNormalisedForComparison}.
 */
enum ArrayShape
{
    /**
     * the array is being used as an ordered list of values. Every
     * key is an int. The key values themselves carry no information
     * the value sequence does not already - they are positions, not
     * identities.
     *
     * An empty array is reported as LIST. The PHP standard library
     * agrees - `array_is_list([])` returns `true` - and treating
     * empty as a list means callers do not need a third "neither"
     * case.
     */
    case LIST;

    /**
     * the array is being used as a key/value map. At least one key
     * is a string. The keys ARE the identity of each entry, so
     * algorithms that canonicalise or compare must preserve them.
     */
    case MAP;

    /**
     * convenience predicate for callers that only care about
     * list-ness.
     *
     * @return bool
     * - `true` if this ArrayShape is a LIST
     * - `false` otherwise
     */
    public function isList(): bool
    {
        return $this === self::LIST;
    }

    /**
     * convenience predicate for callers that only care about
     * map-ness.
     *
     * @return bool
     * - `true` if this ArrayShape is a MAP
     * - `false` otherwise
     */
    public function isMap(): bool
    {
        return $this === self::MAP;
    }
}
