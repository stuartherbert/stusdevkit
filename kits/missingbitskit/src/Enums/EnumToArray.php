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

namespace StusDevKit\MissingBitsKit\Enums;

/**
 * EnumToArray provides a canonical implementation of
 * {@see \StusDevKit\MissingBitsKit\Arrays\StaticallyArrayable}'s
 * `toArray()` for **backed** enums: it walks the enum's cases and
 * returns a `name => value` map.
 *
 * The trait may only be used in backed enums. Pure enums have no
 * `->value` property and the method body will not compile-check
 * against them. PHPStan enforces this via the
 * `@phpstan-require-implements \BackedEnum` tag.
 *
 * The trait is generic over the enum's backing type: string-backed
 * enums bind `TValue` to `string`, int-backed enums bind it to `int`.
 * Consumers tie the type parameter at the use site with `@use`:
 *
 *     /** @use EnumToArray<string> *\/
 *     use EnumToArray;
 *
 * That keeps `toArray()`'s return type narrow enough to satisfy the
 * enum's `@implements StaticallyArrayable<string, TValue>` promise.
 *
 * @phpstan-require-implements \BackedEnum
 *
 * @template TValue of string|int
 */
trait EnumToArray
{
    /**
     * return a map of every case's name to its backing value.
     *
     * originally added for writing data provider-driven unit tests
     *
     * @return array<string, TValue>
     */
    public static function toArray(): array
    {
        // our return value
        $retval = [];

        foreach (self::cases() as $case) {
            $retval[$case->name] = $case->value;
        }

        return $retval;
    }
}
