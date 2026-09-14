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

namespace StusDevKit\CollectionsKit\Traits;

use StusDevKit\MissingBitsKit\Strings\Str;
use StusDevKit\MissingBitsKit\Strings\StrTrimLocation;

/**
 * StringTransformations provides in-place string transformation
 * methods for collections that hold string values.
 *
 * Use this trait in any collection class that extends
 * CollectionOfAnything and stores strings.
 */
trait StringTransformations
{
    // ================================================================
    //
    // Data transformation
    //
    // ----------------------------------------------------------------

    /**
     * Trims all strings in the collection using `Str::trim()`, so
     * the full Unicode whitespace set (NBSP and friends) is stripped
     * by default, not just ASCII whitespace.
     *
     * @param string $characters
     *      the set of characters to trim from the front and end of
     *      every string in the collection. Defaults to
     *      `Str::TRIM_DEFAULT_CHARS` (the Unicode White_Space set plus
     *      NUL).
     * @return static
     */
    public function applyTrim(
        string $characters = Str::TRIM_DEFAULT_CHARS,
    ): static {
        $this->data = array_map(
            fn(string $value) => Str::trim(
                input: $value,
                trimChars: $characters,
                trimLocation: StrTrimLocation::TRIM_START_AND_END,
            ),
            $this->data,
        );

        return $this;
    }

    /**
     * Left-trims all strings in the collection using `Str::trim()`,
     * so the full Unicode whitespace set (NBSP and friends) is
     * stripped from the front by default, not just ASCII whitespace.
     *
     * @param string $characters
     *      the set of characters to trim from the front of every
     *      string in the collection. Defaults to
     *      `Str::TRIM_DEFAULT_CHARS` (the Unicode White_Space set plus
     *      NUL).
     * @return static
     */
    public function applyLtrim(
        string $characters = Str::TRIM_DEFAULT_CHARS,
    ): static {
        $this->data = array_map(
            fn(string $value) => Str::trim(
                input: $value,
                trimChars: $characters,
                trimLocation: StrTrimLocation::TRIM_START,
            ),
            $this->data,
        );

        return $this;
    }

    /**
     * Right-trims all strings in the collection using `Str::trim()`,
     * so the full Unicode whitespace set (NBSP and friends) is
     * stripped from the end by default, not just ASCII whitespace.
     *
     * @param string $characters
     *      the set of characters to trim from the end of every
     *      string in the collection. Defaults to
     *      `Str::TRIM_DEFAULT_CHARS` (the Unicode White_Space set plus
     *      NUL).
     * @return static
     */
    public function applyRtrim(
        string $characters = Str::TRIM_DEFAULT_CHARS,
    ): static {
        $this->data = array_map(
            fn(string $value) => Str::trim(
                input: $value,
                trimChars: $characters,
                trimLocation: StrTrimLocation::TRIM_END,
            ),
            $this->data,
        );

        return $this;
    }
}
