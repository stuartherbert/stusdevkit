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

use DateTimeInterface;
use Exception;
use StusDevKit\DateTimeKit\When;
use StusDevKit\ValidationKit\Contracts\ValueCoercion;

/**
 * CoerceToWhen attempts to convert the input to a When
 * instance using When::from().
 *
 * Coercion rules:
 * - DateTimeInterface instances → When
 * - parseable date strings → When
 * - integer timestamps → When
 * - everything else → returned unchanged
 *
 * Usage:
 *
 *     $coercion = new CoerceToWhen();
 *     $coercion->coerce('2026-01-15');  // → When
 *     $coercion->coerce(1700000000);   // → When
 */
final class CoerceToWhen implements ValueCoercion
{
    public function coerce(mixed $data): mixed
    {
        if ($data instanceof DateTimeInterface) {
            return When::from($data);
        }

        if (is_string($data)) {
            try {
                return When::from($data);
            } catch (Exception) {
                return $data;
            }
        }

        if (is_int($data)) {
            return When::from($data);
        }

        return $data;
    }
}
