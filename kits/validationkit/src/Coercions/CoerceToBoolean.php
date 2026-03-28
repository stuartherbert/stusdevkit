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

namespace StusDevKit\ValidationKit\Coercions;

use StusDevKit\ValidationKit\Contracts\ValueCoercion;

/**
 * CoerceToBoolean attempts to convert the input to a
 * boolean.
 *
 * Coercion rules:
 * - strings 'true', '1', 'yes' → true
 * - strings 'false', '0', 'no', '' → false
 *   (case-insensitive)
 * - integers and floats → bool via cast
 * - everything else → returned unchanged
 *
 * The lookup table can be replaced entirely by passing
 * custom string mappings to the constructor. Use
 * CoerceToBoolean::DEFAULT_STRINGS as a starting point and
 * merge your own values in if you want to keep the
 * built-in mappings.
 *
 * Usage:
 *
 *     $coercion = new CoerceToBoolean();
 *     $coercion->coerce('true'); // true
 *     $coercion->coerce('0');    // false
 *     $coercion->coerce(1);     // true
 *
 *     // replace the lookup table entirely
 *     $coercion = new CoerceToBoolean(
 *         strings: ['on' => true, 'off' => false],
 *     );
 *     $coercion->coerce('on');    // true
 *     $coercion->coerce('true');  // 'true' (unchanged)
 */
final class CoerceToBoolean implements ValueCoercion
{
    /** @var array<string, bool> */
    // @phpstan-ignore classConstant.value
    public const array DEFAULT_STRINGS = [
        'true'  => true,
        '1'     => true,
        'yes'   => true,
        'false' => false,
        '0'     => false,
        'no'    => false,
        ''      => false,
    ];

    /** @var array<string, bool> */
    private readonly array $lookup;

    /**
     * @param array<string, bool> $strings
     * - the string-to-boolean lookup table; defaults to
     *   DEFAULT_STRINGS if not provided
     */
    public function __construct(
        // @phpstan-ignore parameter.defaultValue
        array $strings = self::DEFAULT_STRINGS,
    ) {
        $this->lookup = $strings;
    }

    public function coerce(mixed $data): mixed
    {
        if (is_string($data)) {
            $lower = strtolower($data);

            return $this->lookup[$lower] ?? $data;
        }

        if (is_int($data) || is_float($data)) {
            return (bool) $data;
        }

        return $data;
    }
}
