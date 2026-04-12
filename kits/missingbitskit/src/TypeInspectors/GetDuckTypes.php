<?php

// Stu's Dev Kit
//
// Building blocks for assembling the things you need to build, in a way
// that will last.
//
// Copyright (c) 2015-2026 Ganbaro Digital Ltd
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

/**
 * return a practical list of data types for any value or variable
 */
class GetDuckTypes
{
    /**
     * @return array<string,string>
     */
    public function __invoke(mixed $item): array
    {
        return static::from($item);
    }

    /**
     * return a practical list of PHP types for any value or variable
     *
     * @param  mixed $item
     *         the item to examine
     * @return array<string,string>
     *         the list of type(s) that this item can be
     */
    public static function from(mixed $item): array
    {
        // we dispatch via `is_*()` checks rather than `gettype()`
        // +`match` for two reasons:
        //
        //   1. `is_*()` avoids allocating the string that gettype()
        //      would have returned.
        //   2. PHPStan narrows `mixed` on `is_*()` but not on a
        //      match-on-gettype(), so the concrete `from()` calls
        //      type-check without casts.
        //
        // we call each inspector's static `from()` directly - no
        // per-call object allocation, one stack frame per dispatch.
        if (is_string($item)) {
            return GetStringTypes::from($item);
        }
        if (is_int($item)) {
            return GetIntegerTypes::from($item);
        }
        if (is_float($item)) {
            return GetFloatTypes::from($item);
        }
        if (is_array($item)) {
            return GetArrayTypes::from($item);
        }
        if (is_object($item)) {
            return GetObjectTypes::from($item);
        }
        if (is_bool($item)) {
            return GetBooleanTypes::from($item);
        }
        if ($item === null) {
            // lowercase 'null' to match PHP's own keyword spelling,
            // so the returned types can be used as type-hint parts
            return [
                'null' => 'null',
                'mixed' => 'mixed',
            ];
        }

        // fallback for the PHP types we have no dedicated inspector
        // for (resource, resource (closed), ...); echo whatever
        // gettype() reports, paired with 'mixed'
        $type = gettype($item);

        // gettype() returns the literal string 'resource (closed)'
        // for closed resources. That is not a valid PHP token and
        // the open/closed distinction is rarely what callers want
        // to reason about, so we collapse it back to 'resource'.
        if ($type === 'resource (closed)') {
            $type = 'resource';
        }

        return [
            $type => $type,
            'mixed' => 'mixed',
        ];
    }
}
