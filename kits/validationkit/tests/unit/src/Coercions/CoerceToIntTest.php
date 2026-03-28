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

namespace StusDevKit\ValidationKit\Tests\Unit\Coercions;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use StusDevKit\ValidationKit\Coercions\CoerceToInt;

#[TestDox('CoerceToInt')]
class CoerceToIntTest extends TestCase
{
    // ================================================================
    //
    // Successful Coercions
    //
    // ----------------------------------------------------------------

    /**
     * @return array<string, array{0: mixed, 1: int}>
     */
    public static function provideCoercibleValues(): array
    {
        return [
            'numeric string "42"'       => ['42', 42],
            'numeric string "0"'        => ['0', 0],
            'numeric string "-7"'       => ['-7', -7],
            'float 3.0 (no fraction)'   => [3.0, 3],
            'float -5.0 (no fraction)'  => [-5.0, -5],
            'float 0.0'                 => [0.0, 0],
            'bool true'                 => [true, 1],
            'bool false'                => [false, 0],
        ];
    }

    #[DataProvider('provideCoercibleValues')]
    #[TestDox('coerces to int')]
    public function test_coerces_to_int(
        mixed $inputValue,
        int $expectedResult,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that CoerceToInt converts
        // compatible values to integers

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CoerceToInt();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->coerce($inputValue);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expectedResult, $actualResult);
    }

    // ================================================================
    //
    // Non-Coercible Values
    //
    // ----------------------------------------------------------------

    /**
     * @return array<string, array{0: mixed}>
     */
    public static function provideNonCoercibleValues(): array
    {
        return [
            'non-numeric string'            => ['hello'],
            'string with decimal "3.14"'    => ['3.14'],
            'float with fraction 3.5'       => [3.5],
            'null'                          => [null],
            'array'                         => [['a']],
        ];
    }

    #[DataProvider('provideNonCoercibleValues')]
    #[TestDox('returns non-coercible value unchanged')]
    public function test_returns_non_coercible_unchanged(
        mixed $inputValue,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that CoerceToInt returns values
        // unchanged when they cannot be safely converted
        // to int

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CoerceToInt();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->coerce($inputValue);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($inputValue, $actualResult);
    }
}
