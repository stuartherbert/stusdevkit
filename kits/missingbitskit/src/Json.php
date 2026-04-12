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
namespace StusDevKit\MissingBitsKit;

use StusDevKit\AssertionsKit\Assert;

/**
 * Provides helpful wrappers around PHP's builtins:
 * - `json_encode()`
 * - `json_decode()`
 */
class Json
{
    public const DEFAULT_DECODE_FLAGS = 0;
    public const DEFAULT_ENCODE_FLAGS = 0;
    public const DEFAULT_VALIDATE_FLAGS = 0;

    public const DEFAULT_DEPTH = 512;

    /**
     * convert the given `$input` to a JSON-format string.
     *
     * If the input cannot be converted, an exception is thrown.
     *
     * @param int<1,max> $depth
     */
    public function encode(
        mixed $input,
        int $flags = self::DEFAULT_ENCODE_FLAGS,
        int $depth = self::DEFAULT_DEPTH,
    ): string {
        // make sure exceptions-on-error are enabled
        $flags |= JSON_THROW_ON_ERROR;

        // wrap the PHP builtin
        $retval = json_encode($input, $flags, $depth);

        // keep phpstan happy
        Assert::assertIsString($retval, "json_encode() failed to return a string");

        // all done
        return $retval;
    }

    /**
     * convert the given `$input` JSON string into a PHP value
     *
     * if the input cannot be decoded, an exception is thrown
     *
     * @param int<1,max> $depth
     */
    public function decode(
        string $input,
        ?bool $associative = null,
        int $depth = self::DEFAULT_DEPTH,
        int $flags = self::DEFAULT_DECODE_FLAGS,
    ): mixed
    {
        // make sure exceptions-on-error are enabled
        $flags |= JSON_THROW_ON_ERROR;

        // return the decoded data
        return json_decode($input, $associative, $depth, $flags);
    }

    /**
     * validate the given `$input` to see if it really is a JSON-format
     * string
     *
     * @param int<1,max> $depth
     * @return array{int, string}|array{}
     * - on success, returns empty array
     * - on failure, returns [ json_last_error(), json_last_error_msg() ]
     */
    public function validate(
        string $input,
        int $depth = self::DEFAULT_DEPTH,
        int $flags = self::DEFAULT_VALIDATE_FLAGS,
    ): array
    {
        // @phpstan-ignore argument.type
        if (json_validate($input, $depth, $flags)) {
            return [];
        }

        return [ json_last_error(), json_last_error_msg() ];
    }
}
