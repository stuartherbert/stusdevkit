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

use Stringable;

/**
 * get a full list of types that a string might satisfy
 */
class GetStringTypes
{
    /**
     * get a full list of types that a string might satisfy
     *
     * @param mixed $input
     *        the item to be inspected
     *
     * @return array<string,string>
     *         the list of PHP types that `$input` can satisfy
     *
     *         returns an empty list if `$input` is not a string
     *         or not Stringable
     */
    public function __invoke(mixed $input): array
    {
        // our return type
        $retval = [];

        // special case
        if (is_object($input) && $input instanceof Stringable) {
            $retval['Stringable'] = 'Stringable';
            $retval['string'] = 'string';

            // we don't want this to match against `callable` et al
            // so terminate this here
            return $retval;
        }

        if (!is_string($input)) {
            return $retval;
        }

        return [
            ...$retval,
            ...static::from($input),
        ];
    }

    /**
     * get a full list of PHP pseudo-types that a string might satisfy
     *
     * @param  string $item
     *         the item to examine
     * @return array<string,string>
     *         the list of type(s) that this item can be
     */
    public static function from(string $item): array
    {
        // our return list
        $retval = [];

        // special case - strings can be callables too
        if (is_callable($item)) {
            $retval['callable'] = 'callable';
        }

        // special case - strings can be numbers too
        $retval = [
            ...$retval,
            ...GetNumericTypes::from($item),
        ];

        // add in the basic types. 'mixed' is deliberately NOT
        // included here - see GetIntegerTypes::from() for the
        // rationale (mixed is a duck-type marker, owned by
        // GetDuckTypes, not per-type inspectors).
        $retval = [
            ...$retval,
            'string' => 'string',
        ];

        // all done
        return $retval;
    }
}
