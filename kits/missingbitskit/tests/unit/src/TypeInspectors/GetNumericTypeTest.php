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

namespace StusDevKit\MissingBitsKit\Tests\Unit\TypeInspectors;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use stdClass;
use StusDevKit\MissingBitsKit\Tests\Fixtures\TypeInspectors\SampleToString;
use StusDevKit\MissingBitsKit\TypeInspectors\GetNumericType;

#[TestDox(GetNumericType::class)]
class GetNumericTypeTest extends TestCase
{
    // ================================================================
    //
    // Structure
    //
    // ----------------------------------------------------------------

    #[TestDox('Can instantiate GetNumericType')]
    public function test_can_instantiate(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the GetNumericType class can be
        // instantiated as an invokable object

        // ----------------------------------------------------------------
        // perform the change

        $unit = new GetNumericType();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(GetNumericType::class, $unit);
    }

    // ================================================================
    //
    // __invoke() - rejects non-numeric input
    //
    // ----------------------------------------------------------------

    /**
     * @return array<string,array{mixed}>
     */
    public static function nonNumericProvider(): array
    {
        return [
            'plain string' => ['hello'],
            'empty string' => [''],
            'true' => [true],
            'false' => [false],
            'null' => [null],
            'array' => [[]],
            'object' => [new stdClass()],
        ];
    }

    #[TestDox('__invoke() returns empty array for non-numeric input')]
    #[DataProvider('nonNumericProvider')]
    public function test_invoke_rejects_non_numeric_input(mixed $input): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that any input which is not numeric is
        // rejected by the __invoke() guard and produces an empty
        // type list

        // ----------------------------------------------------------------
        // setup your test

        $unit = new GetNumericType();
        $expected = [];

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('__invoke() returns empty array for Stringable whose string is non-numeric')]
    public function test_invoke_rejects_stringable_with_non_numeric_value(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that __invoke coerces a Stringable to a
        // string (SampleToString produces 'some text') and then
        // rejects it because the resulting string is not numeric

        // ----------------------------------------------------------------
        // setup your test

        $unit = new GetNumericType();
        $input = new SampleToString();
        $expected = [];

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // from() - integer input
    //
    // ----------------------------------------------------------------

    /**
     * @return array<string,array{int}>
     */
    public static function integerProvider(): array
    {
        return [
            'zero' => [0],
            'positive' => [42],
            'negative' => [-7],
            'max' => [PHP_INT_MAX],
        ];
    }

    #[TestDox('from() returns numeric, int, and mixed for an integer')]
    #[DataProvider('integerProvider')]
    public function test_from_returns_expected_types_for_integer(int $input): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that passing an integer produces the
        // numeric family list - no 'string' is added because the
        // input was never a string

        // ----------------------------------------------------------------
        // setup your test

        $expected = [
            'numeric' => 'numeric',
            'int' => 'int',
            'mixed' => 'mixed',
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetNumericType::from($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // from() - float input
    //
    // ----------------------------------------------------------------

    /**
     * @return array<string,array{float}>
     */
    public static function floatProvider(): array
    {
        return [
            'zero' => [0.0],
            'positive' => [1.5],
            'negative' => [-3.14],
        ];
    }

    #[TestDox('from() returns numeric, float, and mixed for a float')]
    #[DataProvider('floatProvider')]
    public function test_from_returns_expected_types_for_float(float $input): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that passing a float produces the
        // numeric family list - no 'string' is added because the
        // input was never a string

        // ----------------------------------------------------------------
        // setup your test

        $expected = [
            'numeric' => 'numeric',
            'float' => 'float',
            'mixed' => 'mixed',
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetNumericType::from($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // from() - string input
    //
    // ----------------------------------------------------------------

    #[TestDox('from() returns numeric, int, string, and mixed for an integer-shaped numeric string')]
    public function test_from_returns_expected_types_for_numeric_int_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a string containing an integer
        // value produces the numeric family list plus 'string' -
        // the 'string' marker carries the fact that the original
        // input was a string, not a coerced int

        // ----------------------------------------------------------------
        // setup your test

        $input = '123';
        $expected = [
            'numeric' => 'numeric',
            'int' => 'int',
            'string' => 'string',
            'mixed' => 'mixed',
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetNumericType::from($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('from() returns numeric, float, string, and mixed for a float-shaped numeric string')]
    public function test_from_returns_expected_types_for_numeric_float_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a string containing a decimal
        // value produces the numeric family list plus 'string' -
        // the 'string' marker carries the fact that the original
        // input was a string, not a coerced float

        // ----------------------------------------------------------------
        // setup your test

        $input = '45.6';
        $expected = [
            'numeric' => 'numeric',
            'float' => 'float',
            'string' => 'string',
            'mixed' => 'mixed',
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetNumericType::from($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    /**
     * @return array<string,array{string}>
     */
    public static function nonNumericStringProvider(): array
    {
        return [
            'empty' => [''],
            'plain text' => ['hello'],
            'alphanumeric' => ['abc123'],
        ];
    }

    #[TestDox('from() returns empty array for a non-numeric string')]
    #[DataProvider('nonNumericStringProvider')]
    public function test_from_returns_empty_for_non_numeric_string(string $input): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that strings whose content is not
        // numeric produce an empty type list - the `is_numeric()`
        // check guards the body of from()

        // ----------------------------------------------------------------
        // setup your test

        $expected = [];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetNumericType::from($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }
}
