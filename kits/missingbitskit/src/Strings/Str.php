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
namespace StusDevKit\MissingBitsKit\Strings;

/**
 * Str provides a UTF-8 safe set of string operations. (If you're
 * unsure, they also work on regular ASCII strings.)
 *
 * Use this instead of PHP's built-in string operations on all
 * text received from external inputs.
 *
 * (For the avoidance of doubt, data stored in your database
 * also counts as an external input!)
 *
 * If you're working with strings that you *know* contain
 * raw byte streams, fall back to calling PHP's built-in
 * `str_xxx()` family of functions.
 *
 * Originally added to provide a centralised set of string
 * operations across StusDevKit.
 */
class Str
{
    /**
     * `TRIM_DEFAULT_CHARS` is the default set of characters that
     * `Str::trim()` (and its callers) remove from a string when no
     * explicit `$trimChars` is given.
     *
     * The set is the Unicode `White_Space` property plus NUL. It
     * covers every character a UTF-8-aware text utility should
     * treat as "padding" by default:
     *
     * - ASCII whitespace (space, tab, LF, CR, VT, FF) plus NUL
     * - NEL (U+0085) and NBSP (U+00A0)
     * - OGHAM SPACE MARK (U+1680)
     * - EN QUAD (U+2000) through HAIR SPACE (U+200A)
     * - LINE SEPARATOR (U+2028) and PARAGRAPH SEPARATOR (U+2029)
     * - NARROW NBSP (U+202F), MEDIUM MATHEMATICAL SPACE (U+205F)
     * - IDEOGRAPHIC SPACE (U+3000)
     *
     * Originally added so that `Str::trim()` would strip the same
     * "invisible whitespace" characters that arrive in user input
     * from copy-paste, word processors, and web forms - characters
     * that PHP's byte-level `trim()` default silently leaves
     * behind.
     */
    public const TRIM_DEFAULT_CHARS =
        "\u{0000}"   // NUL
        . "\u{0009}" // HORIZONTAL TAB
        . "\u{000A}" // LINE FEED
        . "\u{000B}" // VERTICAL TAB
        . "\u{000C}" // FORM FEED
        . "\u{000D}" // CARRIAGE RETURN
        . "\u{0020}" // SPACE
        . "\u{0085}" // NEXT LINE (NEL)
        . "\u{00A0}" // NO-BREAK SPACE
        . "\u{1680}" // OGHAM SPACE MARK
        . "\u{2000}" // EN QUAD
        . "\u{2001}" // EM QUAD
        . "\u{2002}" // EN SPACE
        . "\u{2003}" // EM SPACE
        . "\u{2004}" // THREE-PER-EM SPACE
        . "\u{2005}" // FOUR-PER-EM SPACE
        . "\u{2006}" // SIX-PER-EM SPACE
        . "\u{2007}" // FIGURE SPACE
        . "\u{2008}" // PUNCTUATION SPACE
        . "\u{2009}" // THIN SPACE
        . "\u{200A}" // HAIR SPACE
        . "\u{2028}" // LINE SEPARATOR
        . "\u{2029}" // PARAGRAPH SEPARATOR
        . "\u{202F}" // NARROW NO-BREAK SPACE
        . "\u{205F}" // MEDIUM MATHEMATICAL SPACE
        . "\u{3000}" // IDEOGRAPHIC SPACE
    ;

    /**
     * `trim()` strips any of the individual UTF-8 characters in
     * `$trimChars` from one or both ends of `$input`. It stops
     * walking each end as soon as it encounters a character that
     * isn't in `$trimChars`.
     *
     * Exactly *where* `trim()` removes the characters is determined
     * by the given `$trimLocation`.
     *
     * Originally added to support `Str::isEmpty()`.
     *
     * Here Be Dragons:
     *
     * - Reminder: this handles ASCII and UTF-8 strings, not
     *   raw bytes. See this class's docblock for details.
     * - **Invalid UTF-8 is undefined behaviour.** This method
     *   trusts what you hand it. Validate at your system boundary
     *   before calling.
     * - **No range syntax.** `Str::trim("hello", "a..z")` strips
     *   the literal characters `a`, `.`, and `z` - NOT the range
     *   a-z. (PHP's byte-level `trim()` family supports `..`
     *   ranges; we use `mb_trim()` et al, which do not.)
     * - **Empty `$trimChars` is a silent no-op.** If you pass an
     *   empty string, `$input` is returned unchanged. No
     *   characters means nothing to trim.
     *
     * @param string $input
     *      the string to trim
     * @param string $trimChars
     *      the set of UTF-8 codepoints to remove from `$input`.
     *      Defaults to `self::TRIM_DEFAULT_CHARS` (Unicode
     *      whitespace plus NUL).
     * @param StrTrimLocation $trimLocation
     *      where do you want to trim the string?
     * @return string
     * - `$input` unchanged if `$trimLocation` is
     *   `StrTrimLocation::TRIM_NONE`.
     * - `$input` unchanged if `$trimChars` is the empty string.
     * - `$input` unchanged if it contains no leading or trailing
     *   characters drawn from `$trimChars`.
     * - otherwise, a new string with the requested side(s) of
     *   `$input` stripped of any contiguous run of characters
     *   drawn from `$trimChars`.
     */
    public static function trim(
        string $input,
        string $trimChars = self::TRIM_DEFAULT_CHARS,
        StrTrimLocation $trimLocation = StrTrimLocation::TRIM_START_AND_END,
    ): string
    {
        // delegate to PHP's multibyte-safe trim family
        //
        // we hard-code 'UTF-8' so behaviour does not drift with
        // `mb_internal_encoding()` across deployments.
        return match($trimLocation) {
            StrTrimLocation::TRIM_NONE => $input,
            StrTrimLocation::TRIM_START => mb_ltrim($input, $trimChars, 'UTF-8'),
            StrTrimLocation::TRIM_END => mb_rtrim($input, $trimChars, 'UTF-8'),
            StrTrimLocation::TRIM_START_AND_END => mb_trim($input, $trimChars, 'UTF-8'),
        };
    }
}
