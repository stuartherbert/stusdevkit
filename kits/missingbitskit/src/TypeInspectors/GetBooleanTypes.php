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

/**
 * get the list of types that a PHP boolean can satisfy
 */
class GetBooleanTypes
{
    /**
     * do we have a PHP bool? If so, what types does it match?
     *
     * @return array<string,string>
     */
    public function __invoke(mixed $input): array
    {
        if (!is_bool($input)) {
            return [];
        }

        return static::from($input);
    }

    /**
     * we have a PHP bool. Return a map of types that it can match.
     *
     * @param  bool $item
     *         the item to examine
     * @return array<string,string>
     *         a map of matching PHP pseudo-types
     */
    public static function from(bool $item): array
    {
        // we return 'bool' (the PHP parameter-type-hint spelling),
        // not gettype()'s 'boolean', so callers can use the return
        // value directly as a type hint.

        $retval = [];
        if ($item) {
            $retval['true'] = 'true';
        }
        else {
            $retval['false'] = 'false';
        }

        return [
            ...$retval,
            'bool' => 'bool',
            'mixed' => 'mixed',
        ];
    }
}
