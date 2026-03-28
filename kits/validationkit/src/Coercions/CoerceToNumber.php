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
 * CoerceToNumber attempts to convert the input to an int
 * or float, preserving the distinction between the two.
 *
 * Coercion rules:
 * - numeric strings with decimal point or scientific
 *   notation → float
 * - other numeric strings → int
 * - booleans → 1 or 0
 * - everything else → returned unchanged
 *
 * Usage:
 *
 *     $coercion = new CoerceToNumber();
 *     $coercion->coerce('42');   // 42 (int)
 *     $coercion->coerce('3.14'); // 3.14 (float)
 *     $coercion->coerce('1e5');  // 100000.0 (float)
 *     $coercion->coerce(true);  // 1
 */
final class CoerceToNumber implements ValueCoercion
{
    public function coerce(mixed $data): mixed
    {
        if (is_string($data) && is_numeric($data)) {
            // preserve int vs float distinction
            if (
                str_contains($data, '.')
                || str_contains($data, 'e')
                || str_contains($data, 'E')
            ) {
                return (float) $data;
            }

            return (int) $data;
        }

        if (is_bool($data)) {
            return $data ? 1 : 0;
        }

        return $data;
    }
}
