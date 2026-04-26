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
 * do we have a float type?
 */
class GetFloatTypes
{
    /**
     * do we have a PHP float? If so, what types does it match?
     *
     * @param mixed $input
     *   the value to examine
     *
     *   returns an empty list if `$input` is not a float
     *
     * @return array<string,string>
     */
    public function __invoke(mixed $input): array
    {
        if (!is_float($input)) {
            return [];
        }

        return static::from($input);
    }

    /**
     * we have a PHP float. Return a map of types that it can match.
     *
     * @param  float $item
     *         the item to examine
     * @return array<string,string>
     *         a map of matching PHP pseudo-types
     */
    public static function from(float $item): array
    {
        // 'mixed' is deliberately NOT included here - see
        // GetIntegerTypes::from() for the rationale (mixed is a
        // duck-type marker, owned by GetDuckTypes, not per-type
        // inspectors).
        return [
            'numeric' => 'numeric',
            'float' => 'float',
        ];
    }
}
