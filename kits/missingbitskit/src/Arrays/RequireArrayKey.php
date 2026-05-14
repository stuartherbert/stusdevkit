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
namespace StusDevKit\MissingBitsKit\Arrays;

use StusDevKit\ExceptionsKit\Exceptions\InvalidArgumentException;

/**
 * Type guarantee. Use this to prove that a value can be used as a
 * PHP array key.
 *
 * Type guarantees are type guards that throw on failure, so that
 * you don't have to check the return value.
 *
 * We use type guarantees to ensure that a wider type (e.g. `mixed`)
 * is compatible with a narrower pseudo-type (e.g. `array-key`).
 *
 * This type guarantee can be used in two ways:
 *
 * 1. statically, via `RequireArrayKey::check()`
 * 2. invoked, by `$instance = new RequireArrayKey() ; $instance()`
 *
 * Here Be Dragons
 * ===============
 *
 * RequireArrayKey uses strict type checking. We don't support
 * type-coercion here.
 *
 * If the given `$input` is a type that can be coerced into being
 * an array-key, RequireArrayKey will throw an exception.
 */
class RequireArrayKey
{
    /**
     * Type guarantee. Throws an exception if the given `$input` isn't
     * compatible with PHP's array-key pseudo type.
     *
     * @phpstan-assert array-key $input
     *
     * @param mixed $input
     *     the value to type-check
     * @throws InvalidArgumentException
     */
    public function __invoke(mixed $input): void
    {
        $this->check($input);
    }

    /**
     * Type guarantee. Throws an exception if the given `$input` isn't
     * compatible with PHP's array-key pseudo type.
     *
     * @phpstan-assert array-key $input
     *
     * @param mixed $input
     *     the value to type-check
     * @throws InvalidArgumentException
     */
    public static function check(mixed $input): void
    {
        if (!IsArrayKey::check($input)) {
            throw new InvalidArgumentException(
                detail: 'input is not a supported PHP array-key',
                extra: [
                    'expected_type' => 'array-key',
                    'actual_type' => get_debug_type($input),
                ],
            );
        }
    }
}
