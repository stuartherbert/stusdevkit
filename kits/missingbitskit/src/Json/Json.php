<?php

//
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
//

declare(strict_types=1);

namespace StusDevKit\MissingBitsKit\Json;

use StusDevKit\AssertionsKit\Assert;

/**
 * Provides error-safe wrappers around PHP's builtins:
 *
 * - `json_encode()`
 * - `json_decode()`
 * - `json_validate()`
 *
 * Originally added to ensure that any `json_encode()` or `json_decode()`
 * failures always throw exceptions.
 */
class Json
{
    /**
     * default `$flags` value for `Json::decode()`
     */
    public const DEFAULT_DECODE_FLAGS = 0;

    /**
     * default `$flags` value for `Json::encode()`
     */
    public const DEFAULT_ENCODE_FLAGS = 0;

    /**
     * default `$flags` value for `Json::validate()`
     */
    public const DEFAULT_VALIDATE_FLAGS = 0;

    /**
     * default value for `$depth` parameters
     *
     * matches the default value that's hard-coded into the
     * PHP builtin functions
     */
    public const DEFAULT_DEPTH = 512;

    private function __construct()
    {
    }

    /**
     * convert the given `$input` to a JSON-format string.
     *
     * If the input cannot be converted, a `\JsonException` is thrown.
     *
     * Here Be Dragons
     * ===============
     *
     * **`JSON_THROW_ON_ERROR` is always on — and you cannot turn it
     * off.**
     *
     * Every call ORs `JSON_THROW_ON_ERROR` into whatever `$flags`
     * the caller passed, so `encode(..., flags: 0)` is **not** a
     * way back to the PHP-builtin "return `false` on failure"
     * behaviour. That is the whole point of the wrapper: failures
     * always throw, never silently sneak a `false` through into
     * downstream string-typed code. Callers who want the raw
     * `json_encode()` semantics should call `json_encode()`
     * directly and accept the type-hint fight with static analysis.
     *
     * @param mixed $input
     *   the PHP value to encode
     * @param int $flags
     *   bitmask of `JSON_*` encode flags (e.g. `JSON_PRETTY_PRINT`,
     *   `JSON_UNESCAPED_SLASHES`). `JSON_THROW_ON_ERROR` is always
     *   added on top.
     * @param int<1,max> $depth
     *   maximum nesting depth; exceeding it throws
     *   `\JsonException`.
     *
     * @throws \JsonException
     *   when `$input` cannot be encoded (e.g. it contains a
     *   resource or a circular reference), or when nesting
     *   exceeds `$depth`.
     */
    public static function encode(
        mixed $input,
        int $flags = self::DEFAULT_ENCODE_FLAGS,
        int $depth = self::DEFAULT_DEPTH,
    ): string {
        // make sure exceptions-on-error are enabled
        $flags |= JSON_THROW_ON_ERROR;

        // wrap the PHP builtin
        $retval = json_encode($input, $flags, $depth);

        // with JSON_THROW_ON_ERROR forced on above, json_encode()
        // either returns a string or throws - the `false` branch
        // of its declared return type is unreachable here.
        //
        // keep phpstan happy
        Assert::assertIsString($retval, "json_encode() failed to return a string");

        // all done
        return $retval;
    }

    /**
     * convert the given `$input` JSON string into a PHP value.
     *
     * If the input cannot be decoded, a `\JsonException` is thrown.
     *
     * Here Be Dragons
     * ===============
     *
     * **`JSON_THROW_ON_ERROR` is always on — and you cannot turn it
     * off.**
     *
     * Every call ORs `JSON_THROW_ON_ERROR` into whatever `$flags`
     * the caller passed. Invalid JSON surfaces as a thrown
     * `\JsonException`, not as a silent `null` return — a
     * deliberate departure from the raw `json_decode()` contract
     * that catches callers used to the builtin's behaviour.
     *
     * **`$associative` has a THREE-valued footgun: `null`, `true`,
     * `false`.**
     *
     * `null` (the default) and `false` both return JSON objects as
     * `\stdClass` instances — `null` is "use the PHP default", and
     * that default happens to be `\stdClass`. Only `true` returns
     * associative arrays. A reader who assumes `false` means "turn
     * associative mode off" gets it right for the wrong reason;
     * passing `true` is the only way to opt *in*. The wrapper does
     * not flip this default, because doing so would put it out of
     * sync with `json_decode()`.
     *
     * @param string $input
     *   the JSON string to decode
     * @param ?bool $associative
     *   `true` returns JSON objects as associative arrays; `null`
     *   (the default) and `false` both return JSON objects as
     *   `\stdClass` instances, matching `json_decode()`.
     * @param int<1,max> $depth
     *   maximum nesting depth; exceeding it throws
     *   `\JsonException`.
     * @param int $flags
     *   bitmask of `JSON_*` decode flags (e.g.
     *   `JSON_BIGINT_AS_STRING`, `JSON_OBJECT_AS_ARRAY`).
     *   `JSON_THROW_ON_ERROR` is always added on top.
     *
     * @throws \JsonException
     *   when `$input` is not a valid JSON document, or when
     *   nesting exceeds `$depth`.
     */
    public static function decode(
        string $input,
        ?bool $associative = null,
        int $depth = self::DEFAULT_DEPTH,
        int $flags = self::DEFAULT_DECODE_FLAGS,
    ): mixed {
        // make sure exceptions-on-error are enabled
        $flags |= JSON_THROW_ON_ERROR;

        // return the decoded data
        return json_decode($input, $associative, $depth, $flags);
    }

    /**
     * validate the given `$input` to see if it really is a
     * JSON-format string.
     *
     * Unlike `encode()` and `decode()`, this method never throws
     * on a malformed document: it reports the verdict in the
     * return value instead, so callers can branch on validity
     * without setting up a `try`/`catch`. `null` means the input
     * is valid; a `JsonValidationError` carries the failure
     * reason.
     *
     * The recommended idiom is to compare the return value
     * explicitly:
     *
     * ```
     * if (Json::validate($input) === null) {
     *     // $input is valid JSON
     * }
     * ```
     *
     * Here Be Dragons
     * ===============
     *
     * **`$flags` is NOT a general `JSON_*` bitmask.**
     *
     * Unlike `encode()` and `decode()`, `json_validate()` only
     * understands `0` or `JSON_INVALID_UTF8_IGNORE`. Passing any
     * other `JSON_*` constant is a silent no-op at best and a
     * type error at static-analysis time - the narrow type below
     * is what the PHP builtin actually accepts.
     *
     * @param string $input
     *   the string to inspect
     * @param int<1,max> $depth
     *   maximum nesting depth; a document that nests deeper than
     *   this is reported as invalid.
     * @param 0|JSON_INVALID_UTF8_IGNORE $flags
     *   either `0` (the default) or `JSON_INVALID_UTF8_IGNORE`.
     *   No other `JSON_*` constant is accepted by the underlying
     *   `json_validate()` builtin.
     */
    public static function validate(
        string $input,
        int $depth = self::DEFAULT_DEPTH,
        int $flags = self::DEFAULT_VALIDATE_FLAGS,
    ): ?JsonValidationError {
        // happy path: nothing to report
        if (json_validate($input, $depth, $flags)) {
            return null;
        }

        // json_validate() populates json_last_error() /
        // json_last_error_msg() on failure - this is documented
        // PHP behaviour and the ONLY way to get the reason out
        // of the builtin. It is NOT the same surface as the
        // thrown \JsonException from json_decode(); those two
        // report through different channels on purpose.
        return new JsonValidationError(
            code: json_last_error(),
            message: json_last_error_msg(),
        );
    }
}
