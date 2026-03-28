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
use StusDevKit\ValidationKit\Coercions\CoerceToNumber;

#[TestDox('CoerceToNumber')]
class CoerceToNumberTest extends TestCase
{
    // ================================================================
    //
    // Coercions to int
    //
    // ----------------------------------------------------------------

    /**
     * @return array<string, array{0: string, 1: int}>
     */
    public static function provideIntCoercibleValues(): array
    {
        return [
            'numeric string "42"'   => ['42', 42],
            'numeric string "0"'    => ['0', 0],
            'numeric string "-7"'   => ['-7', -7],
        ];
    }

    #[DataProvider('provideIntCoercibleValues')]
    #[TestDox('coerces whole-number strings to int')]
    public function test_coerces_whole_number_strings_to_int(
        string $inputValue,
        int $expectedResult,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that CoerceToNumber converts
        // numeric strings without decimal points or
        // scientific notation to int

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CoerceToNumber();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->coerce($inputValue);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expectedResult, $actualResult);
    }

    // ================================================================
    //
    // Coercions to float
    //
    // ----------------------------------------------------------------

    /**
     * @return array<string, array{0: string, 1: float}>
     */
    public static function provideFloatCoercibleValues(): array
    {
        return [
            'decimal string "3.14"'         => ['3.14', 3.14],
            'scientific notation "1e5"'      => ['1e5', 100000.0],
            'upper scientific "2E3"'         => ['2E3', 2000.0],
            'negative decimal "-0.5"'        => ['-0.5', -0.5],
        ];
    }

    #[DataProvider('provideFloatCoercibleValues')]
    #[TestDox('coerces decimal/scientific strings to float')]
    public function test_coerces_decimal_strings_to_float(
        string $inputValue,
        float $expectedResult,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that CoerceToNumber converts
        // numeric strings with decimal points or scientific
        // notation to float, preserving the int/float
        // distinction

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CoerceToNumber();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->coerce($inputValue);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expectedResult, $actualResult);
    }

    // ================================================================
    //
    // Boolean Coercions
    //
    // ----------------------------------------------------------------

    /**
     * @return array<string, array{0: bool, 1: int}>
     */
    public static function provideBoolValues(): array
    {
        return [
            'bool true'     => [true, 1],
            'bool false'    => [false, 0],
        ];
    }

    #[DataProvider('provideBoolValues')]
    #[TestDox('coerces booleans to int')]
    public function test_coerces_booleans_to_int(
        bool $inputValue,
        int $expectedResult,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that CoerceToNumber converts
        // booleans to int (not float)

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CoerceToNumber();

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
            'non-numeric string'    => ['hello'],
            'null'                  => [null],
            'array'                 => [['a']],
        ];
    }

    #[DataProvider('provideNonCoercibleValues')]
    #[TestDox('returns non-coercible value unchanged')]
    public function test_returns_non_coercible_unchanged(
        mixed $inputValue,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that CoerceToNumber returns values
        // unchanged when they cannot be converted

        // ----------------------------------------------------------------
        // setup your test

        $unit = new CoerceToNumber();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->coerce($inputValue);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($inputValue, $actualResult);
    }
}
