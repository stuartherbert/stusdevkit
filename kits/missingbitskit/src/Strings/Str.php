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
 *
 * Transformers
 * ============
 *
 * Several of Str's *query* methods - `isEmpty()`, `contains()`, and
 * future siblings - take an optional `$transformer`. A transformer
 * is any `callable(string): string`: it receives one of the
 * method's input strings and returns a replacement the method then
 * works with. (Str's *producer* methods - `trim()`, `identity()`,
 * `toLower()`, `toUpper()` - do not take one; they ARE the kind of
 * thing you'd pass as a transformer.)
 *
 * The point is to normalise the input at the moment of the query,
 * without juggling temporary variables or if/else trees at the call
 * site. The classic example is trimming:
 *
 *     // "empty once we ignore surrounding whitespace?"
 *     Str::isEmpty($input, fn($v) => Str::trim($v));
 *
 *     // "contains $needle, ignoring the needle's own padding?"
 *     Str::contains($haystack, $needle, fn($v) => Str::trim($v));
 *
 * Re-use one transformer everywhere. Because it is just a callable,
 * a project can define its own canonical normaliser once and pass
 * it to every Str call:
 *
 *     $normalise = MyApp\Text::standardForm(...);
 *     Str::isEmpty($input, $normalise);
 *     Str::contains($haystack, $needle, $normalise);
 *
 * That keeps the meaning of "normalised" in one place, and makes it
 * something a human reviewer - or a custom static-analysis rule -
 * can audit across the whole codebase.
 *
 * `Str::identity()` is the no-op transformer: pass `Str::identity(...)`
 * to say "no transformation" explicitly and greppably instead of
 * relying on the `null` default.
 *
 * For Unicode *form* normalisation (NFC/NFD), prefer the system
 * boundary - see the Here Be Dragons below - not a per-call
 * transformer. Transformers are best for lighter, query-local
 * normalisation such as trimming or case-folding.
 *
 * Each method documents which of its inputs the transformer
 * receives, and the hazards that apply (a transformer may be
 * skipped on a short-circuit, so it must be pure; its result is
 * discarded). See the individual method docblocks for the
 * specifics.
 *
 * Here Be Dragons
 * ===============
 *
 * - **"UTF-8 safe" means codepoint-aware, not normalisation-aware.**
 *   These operations compare Unicode codepoints, not *canonically
 *   equivalent* text. Two strings that look identical but use
 *   different codepoint sequences - for example "é" as the single
 *   codepoint U+00E9 versus the pair U+0065 U+0301 - will NOT
 *   match. Normalising text to a single form is firmly the
 *   caller's responsibility, not Str's.
 * - **Normalise once, at your system boundary.** As text enters
 *   your system (HTTP input, database reads, file reads), convert
 *   it to a single normalisation form - NFC is the usual choice -
 *   using PHP's `Normalizer` class from `ext-intl`. Once every
 *   string in play is in the same form, every Str operation just
 *   works. `ext-intl` is a hard requirement of this package for
 *   exactly this reason.
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
     * Search the given haystack of string(s), to see if it contains
     * the given `$needle`.
     *
     * By default it searches for `$needle` exactly as given. Pass a
     * `$transformer` to transform `$needle` before the search - for
     * example, a trimming transformer (`fn($v) => Str::trim($v)`)
     * strips surrounding whitespace so a padded `$needle` can still
     * match. The transformer is applied to the `$needle` only; the
     * `$haystack` is always searched exactly as given.
     *
     * Here Be Dragons
     * ===============
     *
     * - **The transformer applies to the `$needle` only.** The
     *   `$haystack` is never transformed. If you need the haystack
     *   normalised, do it at your system boundary - see this
     *   class's docblock.
     * - **Your `$transformer` is skipped when the haystack is
     *   empty.** contains() answers the empty-haystack case before
     *   it transforms, so a transformer with side effects will not
     *   fire for an empty `$haystack`.
     * - **Keep transformers pure.** Your transformer may not get
     *   called. Any transformer that has side effects will cause
     *   subtle bugs.
     * - **Your transformer's result is temporary.** If you want
     *   access to the result of `$transformer($needle)`, call it
     *   yourself first, and don't pass `$transformer` into
     *   `contains()` at all.
     *
     * @param string|string[] $haystack
     *      the string(s) that we want to search
     * @param string $needle
     *      the string we want to look for inside `$haystack`
     * @param (callable(string): string)|null $transformer
     *      optional transform applied to `$needle` before the
     *      search (e.g. a trimming transformer). `$needle` is the
     *      only value passed to it; the `$haystack` is never
     *      transformed.
     * @return bool
     * - `false` if `$haystack` is empty
     * - `false` if `$needle` is empty (after transforming)
     * - `true` if any string in `$haystack` contains `$needle`
     * - `false` otherwise
     */
    public static function contains(
        string|array $haystack,
        string $needle,
        ?callable $transformer = null,
    ): bool
    {
        // optimisation - is the haystack empty?
        // no need to do any extra work if it is
        //
        // these are not load-bearing checks; the main loop
        // will also return `false` if the haystack is empty
        //
        // however, these checks are cheaper than an empty
        // haystack reaching the main loop
        if (is_array($haystack) && count($haystack) === 0) {
            return false;
        }
        if (is_string($haystack) && strlen($haystack) === 0) {
            return false;
        }

        // (optionally) transform the string we are searching for.
        // e.g. a trimming transformer strips surrounding whitespace
        // from the needle.
        if ($transformer !== null) {
            $needle = $transformer($needle);
        }

        // special case - have we been left with an empty `$needle`?
        // an empty needle can never be meaningfully "contained".
        if (self::isEmpty($needle)) {
            return false;
        }

        // from here on we treat the haystack as a list of strings, so
        // a single-string haystack gets wrapped into a one-element
        // array to give the search loop below a single code path.
        if (is_string($haystack)) {
            $haystack = [ $haystack ];
        }

        // let's go look for our needle
        foreach ($haystack as $strToSearch) {
            // str_contains() is safe to use on UTF-8 data:
            //
            // UTF-8 is self-synchronising; a byte match only
            // lands on a character boundary
            if (str_contains($strToSearch, $needle)) {
                return true;
            }
        }

        // not found
        return false;
    }

    /**
     * Check whether the given haystack of string(s) ends with the
     * given `$needle`.
     *
     * By default it checks for `$needle` exactly as given. Pass a
     * `$transformer` to transform `$needle` before the check - for
     * example, a trimming transformer (`fn($v) => Str::trim($v)`)
     * strips surrounding whitespace so a padded `$needle` can still
     * match. The transformer is applied to the `$needle` only; the
     * `$haystack` is always checked exactly as given.
     *
     * Here Be Dragons
     * ===============
     *
     * - **The transformer applies to the `$needle` only.** The
     *   `$haystack` is never transformed. If you need the haystack
     *   normalised, do it at your system boundary - see this
     *   class's docblock.
     * - **Your `$transformer` is skipped when the haystack is
     *   empty.** endsWith() answers the empty-haystack case before
     *   it transforms, so a transformer with side effects will not
     *   fire for an empty `$haystack`.
     * - **Empty `$needle` is always `false` - unlike
     *   `str_ends_with()`.** PHP's `str_ends_with($h, '')` reports
     *   `true` (every string "ends with" nothing); endsWith()
     *   deliberately returns `false`, because "ends with nothing" is
     *   not a meaningful query. This matches `Str::contains()`, and it
     *   means a trimming transformer that empties the needle yields
     *   `false`.
     * - **Keep transformers pure.** Your transformer may not get
     *   called. Any transformer that has side effects will cause
     *   subtle bugs.
     * - **Your transformer's result is temporary.** If you want
     *   access to the result of `$transformer($needle)`, call it
     *   yourself first, and don't pass `$transformer` into
     *   `endsWith()` at all.
     *
     * @param string|string[] $haystack
     *      the string(s) whose end we want to check
     * @param string $needle
     *      the string we want `$haystack` to end with
     * @param (callable(string): string)|null $transformer
     *      optional transform applied to `$needle` before the
     *      check (e.g. a trimming transformer). `$needle` is the
     *      only value passed to it; the `$haystack` is never
     *      transformed.
     * @return bool
     * - `false` if `$haystack` is empty
     * - `false` if `$needle` is empty (after transforming)
     * - `true` if any string in `$haystack` ends with `$needle`
     * - `false` otherwise
     */
    public static function endsWith(
        string|array $haystack,
        string $needle,
        ?callable $transformer = null,
    ): bool
    {
        // optimisation - is the haystack empty?
        // no need to do any extra work if it is
        //
        // these are not load-bearing checks; the main loop
        // will also return `false` if the haystack is empty
        //
        // however, these checks are cheaper than an empty
        // haystack reaching the main loop
        if (is_array($haystack) && count($haystack) === 0) {
            return false;
        }
        if (is_string($haystack) && strlen($haystack) === 0) {
            return false;
        }

        // (optionally) transform the string we are checking for.
        // e.g. a trimming transformer strips surrounding whitespace
        // from the needle.
        if ($transformer !== null) {
            $needle = $transformer($needle);
        }

        // special case - have we been left with an empty `$needle`?
        //
        // an empty needle is not a meaningful "ends with", so we
        // reject it up front - deliberately diverging from
        // str_ends_with(), which would report `true` here.
        if (self::isEmpty($needle)) {
            return false;
        }

        // from here on we treat the haystack as a list of strings, so
        // a single-string haystack gets wrapped into a one-element
        // array to give the check loop below a single code path.
        if (is_string($haystack)) {
            $haystack = [ $haystack ];
        }

        // let's see if any element ends with our needle
        foreach ($haystack as $strToCheck) {
            // str_ends_with() is safe to use on UTF-8 data:
            //
            // UTF-8 is self-synchronising; a byte match only
            // lands on a character boundary
            if (str_ends_with($strToCheck, $needle)) {
                return true;
            }
        }

        // not found
        return false;
    }

    /**
     * `identity()` returns `$input` unchanged.
     *
     * It is the no-op transformer. Pass `Str::identity(...)` to any
     * Str method's `$transformer` parameter when you want to spell out
     * "leave the input unchanged" explicitly, instead of relying on
     * the `null` default.
     *
     * Originally added so that "no transformation" can be an explicit,
     * grep-able choice at the call site - useful for readers and for
     * automated audits of how Str is being called.
     *
     * @param string $input
     *      the string to return unchanged
     * @return string
     *      `$input`, exactly as given
     */
    public static function identity(string $input): string
    {
        // the no-op transformer - whatever came in goes straight back
        // out, untouched
        return $input;
    }

    /**
     * `isEmpty()` checks whether the given `$input` string is empty.
     *
     * By default it tests `$input` exactly as given: only `null` or a
     * zero-length string counts as empty. Pass a `$transformer` to
     * transform `$input` first - for example, a trimming transformer
     * makes a whitespace-only string count as empty.
     *
     * `isEmpty()` supports `null` `$input` values too, for added
     * convenience.
     *
     * Here Be Dragons
     * ===============
     *
     * - **Your `$transformer` is skipped for `null` and zero-length
     *   input.** `isEmpty()` answers the cheap cases (`null`,
     *   zero-length) before it transforms, so a transformer with side
     *   effects will not fire for those inputs.
     * - **Keep transformers pure.** Your transformer may not get
     *   called. Any transformer that has side effects will cause
     *   subtle bugs.
     * - **Your transformer's result is temporary.** If you want
     *   access to the result of `$transformer($input)`, call it
     *   yourself first, and don't pass `$transformer` into
     *   `isEmpty()` at all.
     *
     * @param string|null $input
     *      the string to check for emptiness
     * @param (callable(string): string)|null $transformer
     *      optional transform applied to `$input` before the
     *      post-transform emptiness check (e.g. a trimming
     *      transformer). `$input` is the only value passed to it.
     * @return bool
     * - `true` if `$input` is null
     * - `true` if `$input`'s length is zero before transforming
     * - `true` if `$input`'s length is zero after transforming
     * - `false` otherwise
     */
    public static function isEmpty(
        string|null $input,
        ?callable $transformer = null,
    ): bool
    {
        // special case - we received a `null`
        if ($input === null) {
            return true;
        }

        // special case - obviously-empty string
        //
        // we answer this before transforming, so a cheap result never
        // pays for an (optionally expensive) transformer call.
        if (strlen($input) === 0) {
            return true;
        }

        // general case - apply the caller's transformer (if any), then
        // re-check. e.g. a trimming transformer turns "  " into "".
        if ($transformer !== null) {
            $transformedInput = $transformer($input);
            if (strlen($transformedInput) === 0) {
                return true;
            }
        }

        // if we get here, we've run out of reasons for a string to be
        // considered empty
        return false;
    }

    /**
     * `length()` counts how many UTF-8 characters (codepoints) are in
     * the given `$input` string.
     *
     * By default it counts `$input` exactly as given. Pass a
     * `$transformer` to transform `$input` first - for example, a
     * trimming transformer counts the length of the trimmed string
     * rather than the padded one.
     *
     * `length()` supports `null` `$input` values too, for added
     * convenience; a `null` has no characters, so it counts as zero.
     *
     * Originally added so that ValidationKit's string-length
     * constraints (and any other caller) share a single UTF-8-aware
     * character count, instead of bare `mb_strlen()` calls that rely
     * on the ambient `mb_internal_encoding()`.
     *
     * Here Be Dragons
     * ===============
     *
     * - **Codepoints, not grapheme clusters.** `length()` counts
     *   Unicode codepoints. An "e" followed by a combining acute
     *   accent (U+0065 U+0301) looks like one character but counts as
     *   2. Normalise at your system boundary - see this class's
     *   docblock - if you need the count a human would expect.
     * - **Your `$transformer` is skipped for `null` and zero-length
     *   input.** length() answers the cheap cases (`null`,
     *   zero-length) before it transforms, so a transformer with side
     *   effects will not fire for those inputs.
     * - **Keep transformers pure.** Your transformer may not get
     *   called. Any transformer that has side effects will cause
     *   subtle bugs.
     * - **Your transformer's result is temporary.** If you want
     *   access to the result of `$transformer($input)`, call it
     *   yourself first, and don't pass `$transformer` into `length()`
     *   at all.
     *
     * @param string|null $input
     *      the string whose characters we want to count
     * @param (callable(string): string)|null $transformer
     *      optional transform applied to `$input` before counting
     *      (e.g. a trimming transformer). `$input` is the only value
     *      passed to it.
     * @return int
     * - `0` if `$input` is null
     * - `0` if `$input`'s length is zero before transforming
     * - otherwise the number of UTF-8 codepoints in `$input` (after
     *   transforming, if a `$transformer` was given)
     */
    public static function length(
        string|null $input,
        ?callable $transformer = null,
    ): int
    {
        // special case - we received a `null`
        // a null has no characters to count
        if ($input === null) {
            return 0;
        }

        // special case - obviously-empty string
        //
        // we answer this before transforming, so a cheap result never
        // pays for an (optionally expensive) transformer call.
        if (strlen($input) === 0) {
            return 0;
        }

        // general case - apply the caller's transformer (if any)
        // before we measure. e.g. a trimming transformer counts the
        // trimmed length rather than the padded one.
        if ($transformer !== null) {
            $input = $transformer($input);
        }

        // count the codepoints, not the bytes
        //
        // we hard-code 'UTF-8' so behaviour does not drift with
        // `mb_internal_encoding()` across deployments.
        return mb_strlen($input, 'UTF-8');
    }

    /**
     * Check whether the given haystack of string(s) starts with the
     * given `$needle`.
     *
     * By default it checks for `$needle` exactly as given. Pass a
     * `$transformer` to transform `$needle` before the check - for
     * example, a trimming transformer (`fn($v) => Str::trim($v)`)
     * strips surrounding whitespace so a padded `$needle` can still
     * match. The transformer is applied to the `$needle` only; the
     * `$haystack` is always checked exactly as given.
     *
     * Here Be Dragons
     * ===============
     *
     * - **The transformer applies to the `$needle` only.** The
     *   `$haystack` is never transformed. If you need the haystack
     *   normalised, do it at your system boundary - see this
     *   class's docblock.
     * - **Your `$transformer` is skipped when the haystack is
     *   empty.** startsWith() answers the empty-haystack case before
     *   it transforms, so a transformer with side effects will not
     *   fire for an empty `$haystack`.
     * - **Empty `$needle` is always `false` - unlike
     *   `str_starts_with()`.** PHP's `str_starts_with($h, '')` reports
     *   `true` (every string "starts with" nothing); startsWith()
     *   deliberately returns `false`, because "starts with nothing" is
     *   not a meaningful query. This matches `Str::contains()`, and it
     *   means a trimming transformer that empties the needle yields
     *   `false`.
     * - **Keep transformers pure.** Your transformer may not get
     *   called. Any transformer that has side effects will cause
     *   subtle bugs.
     * - **Your transformer's result is temporary.** If you want
     *   access to the result of `$transformer($needle)`, call it
     *   yourself first, and don't pass `$transformer` into
     *   `startsWith()` at all.
     *
     * @param string|string[] $haystack
     *      the string(s) whose start we want to check
     * @param string $needle
     *      the string we want `$haystack` to start with
     * @param (callable(string): string)|null $transformer
     *      optional transform applied to `$needle` before the
     *      check (e.g. a trimming transformer). `$needle` is the
     *      only value passed to it; the `$haystack` is never
     *      transformed.
     * @return bool
     * - `false` if `$haystack` is empty
     * - `false` if `$needle` is empty (after transforming)
     * - `true` if any string in `$haystack` starts with `$needle`
     * - `false` otherwise
     */
    public static function startsWith(
        string|array $haystack,
        string $needle,
        ?callable $transformer = null,
    ): bool
    {
        // optimisation - is the haystack empty?
        // no need to do any extra work if it is
        //
        // these are not load-bearing checks; the main loop
        // will also return `false` if the haystack is empty
        //
        // however, these checks are cheaper than an empty
        // haystack reaching the main loop
        if (is_array($haystack) && count($haystack) === 0) {
            return false;
        }
        if (is_string($haystack) && strlen($haystack) === 0) {
            return false;
        }

        // (optionally) transform the string we are checking for.
        // e.g. a trimming transformer strips surrounding whitespace
        // from the needle.
        if ($transformer !== null) {
            $needle = $transformer($needle);
        }

        // special case - have we been left with an empty `$needle`?
        //
        // an empty needle is not a meaningful "starts with", so we
        // reject it up front - deliberately diverging from
        // str_starts_with(), which would report `true` here.
        if (self::isEmpty($needle)) {
            return false;
        }

        // from here on we treat the haystack as a list of strings, so
        // a single-string haystack gets wrapped into a one-element
        // array to give the check loop below a single code path.
        if (is_string($haystack)) {
            $haystack = [ $haystack ];
        }

        // let's see if any element starts with our needle
        foreach ($haystack as $strToCheck) {
            // str_starts_with() is safe to use on UTF-8 data:
            //
            // UTF-8 is self-synchronising; a byte match only
            // lands on a character boundary
            if (str_starts_with($strToCheck, $needle)) {
                return true;
            }
        }

        // not found
        return false;
    }

    /**
     * `toLower()` returns `$input` with every cased character folded
     * to its lowercase form.
     *
     * It is a producer, and satisfies the `callable(string): string`
     * shape - pass `Str::toLower(...)` as the `$transformer` to a Str
     * query method to fold its input to lowercase before the query.
     *
     * Originally added so that ValidationKit's `LowerCaseTransformer`
     * (and any other caller) share a single UTF-8-aware lowercaser,
     * instead of bare `mb_strtolower()` calls that rely on the ambient
     * `mb_internal_encoding()`.
     *
     * Here Be Dragons
     * ===============
     *
     * - **Lowercasing is not case-folding.** `toLower()` does a simple
     *   Unicode lowercase (`mb_strtolower`) - fine for display and for
     *   most case-insensitive comparisons, but NOT the full Unicode
     *   case-folding a watertight caseless match needs for every
     *   script (German "ß", Greek final sigma, and friends will trip
     *   you up). Normalise at your system boundary - see this class's
     *   docblock - if you need that.
     * - Reminder: this handles ASCII and UTF-8 strings, not raw
     *   bytes. See this class's docblock for details.
     *
     * @param string $input
     *      the string to fold to lowercase
     * @return string
     *      `$input` with every cased character folded to lowercase
     */
    public static function toLower(string $input): string
    {
        // fold to lowercase, codepoint-aware
        //
        // we hard-code 'UTF-8' so behaviour does not drift with
        // `mb_internal_encoding()` across deployments.
        return mb_strtolower($input, 'UTF-8');
    }

    /**
     * `toUpper()` returns `$input` with every cased character folded
     * to its uppercase form.
     *
     * It is a producer, and satisfies the `callable(string): string`
     * shape - pass `Str::toUpper(...)` as the `$transformer` to a Str
     * query method to fold its input to uppercase before the query.
     *
     * Originally added so that ValidationKit's `UpperCaseTransformer`
     * (and any other caller) share a single UTF-8-aware uppercaser,
     * instead of bare `mb_strtoupper()` calls that rely on the ambient
     * `mb_internal_encoding()`.
     *
     * Here Be Dragons
     * ===============
     *
     * - **Uppercasing is not case-folding.** `toUpper()` does a simple
     *   Unicode uppercase (`mb_strtoupper`) - fine for display, but
     *   NOT the full Unicode case-folding a watertight caseless match
     *   needs for every script (German "ß" expands to "SS", and other
     *   surprises await). Normalise at your system boundary - see this
     *   class's docblock - if you need that.
     * - Reminder: this handles ASCII and UTF-8 strings, not raw
     *   bytes. See this class's docblock for details.
     *
     * @param string $input
     *      the string to fold to uppercase
     * @return string
     *      `$input` with every cased character folded to uppercase
     */
    public static function toUpper(string $input): string
    {
        // fold to uppercase, codepoint-aware
        //
        // we hard-code 'UTF-8' so behaviour does not drift with
        // `mb_internal_encoding()` across deployments.
        return mb_strtoupper($input, 'UTF-8');
    }

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
     * Here Be Dragons
     * ===============
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
