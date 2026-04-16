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
use StusDevKit\MissingBitsKit\TypeInspectors\GetIntegerTypes;

#[TestDox(GetIntegerTypes::class)]
class GetIntegerTypesTest extends TestCase
{
    // ================================================================
    //
    // Structure
    //
    // ----------------------------------------------------------------

    #[TestDox('Can instantiate GetIntegerTypes')]
    public function test_can_instantiate(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the GetIntegerTypes class can be
        // instantiated as an invokable object

        // ----------------------------------------------------------------
        // perform the change

        $unit = new GetIntegerTypes();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(GetIntegerTypes::class, $unit);
    }

    // ================================================================
    //
    // __invoke() - rejects inputs of the wrong type
    //
    // ----------------------------------------------------------------

    /**
     * @return array<string,array{mixed}>
     */
    public static function nonIntegerProvider(): array
    {
        return [
            'float' => [1.5],
            'numeric string' => ['123'],
            'plain string' => ['hello'],
            'true' => [true],
            'false' => [false],
            'null' => [null],
            'array' => [[]],
            'object' => [new stdClass()],
        ];
    }

    #[TestDox('__invoke() returns empty array for non-integer input')]
    #[DataProvider('nonIntegerProvider')]
    public function test_invoke_rejects_non_integer_input(mixed $input): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that any input which is not strictly a
        // PHP int is rejected by the __invoke() type-guard and
        // produces an empty type list - no loose-typing coercion
        // (e.g. treating '123' as an integer) is applied

        // ----------------------------------------------------------------
        // setup your test

        $unit = new GetIntegerTypes();
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
    // from()
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
            'min' => [PHP_INT_MIN],
        ];
    }

    #[TestDox('from() returns numeric and int for any integer')]
    #[DataProvider('integerProvider')]
    public function test_from_returns_expected_types(int $input): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that GetIntegerTypes::from() returns
        // the same 'numeric', 'int' list regardless of the
        // specific integer value. 'mixed' is not emitted here:
        // it is the duck-type marker owned by GetDuckTypes, not
        // by per-type inspectors.

        // ----------------------------------------------------------------
        // setup your test

        $expected = [
            'numeric' => 'numeric',
            'int' => 'int',
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetIntegerTypes::from($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }
}
