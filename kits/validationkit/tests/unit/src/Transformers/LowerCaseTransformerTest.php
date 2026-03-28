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

namespace StusDevKit\ValidationKit\Tests\Unit\Transformers;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use StusDevKit\ValidationKit\Contracts\ValueTransformer;
use StusDevKit\ValidationKit\Internals\ValidationContext;
use StusDevKit\ValidationKit\Transformers\LowerCaseTransformer;

#[TestDox('LowerCaseTransformer')]
class LowerCaseTransformerTest extends TestCase
{
    // ================================================================
    //
    // Interface Compliance
    //
    // ----------------------------------------------------------------

    #[TestDox('implements ValueTransformer')]
    public function test_implements_value_transformer(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that LowerCaseTransformer
        // implements the ValueTransformer interface

        // ----------------------------------------------------------------
        // setup your test

        $unit = new LowerCaseTransformer();

        // ----------------------------------------------------------------
        // perform the change

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(
            ValueTransformer::class,
            $unit,
        );
    }

    // ================================================================
    //
    // transform()
    //
    // ----------------------------------------------------------------

    /**
     * @return array<string, array{0: string, 1: string}>
     */
    public static function provideLowerCaseCases(): array
    {
        return [
            'all upper'         => ['HELLO', 'hello'],
            'mixed case'        => ['Hello World', 'hello world'],
            'already lower'     => ['hello', 'hello'],
            'empty string'      => ['', ''],
            'unicode upper'     => ['HÉLLO', 'héllo'],
            'with numbers'      => ['ABC123', 'abc123'],
        ];
    }

    #[DataProvider('provideLowerCaseCases')]
    #[TestDox('converts to lower case')]
    public function test_converts_to_lower_case(
        string $inputValue,
        string $expectedResult,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that LowerCaseTransformer
        // converts strings to lower case with Unicode
        // support via mb_strtolower

        // ----------------------------------------------------------------
        // setup your test

        $unit = new LowerCaseTransformer();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->process(data: $inputValue, context: new ValidationContext());

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expectedResult, $actualResult);
    }
}
