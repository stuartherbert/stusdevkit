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

use DateTimeImmutable;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use stdClass;
use StusDevKit\MissingBitsKit\TypeInspectors\GetArrayTypes;

#[TestDox(GetArrayTypes::class)]
class GetArrayTypesTest extends TestCase
{
    // ================================================================
    //
    // Structure
    //
    // ----------------------------------------------------------------

    #[TestDox('Can instantiate GetArrayTypes')]
    public function test_can_instantiate(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the GetArrayTypes class can be
        // instantiated as an invokable object

        // ----------------------------------------------------------------
        // perform the change

        $unit = new GetArrayTypes();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(GetArrayTypes::class, $unit);
    }

    // ================================================================
    //
    // __invoke() - rejects inputs of the wrong type
    //
    // ----------------------------------------------------------------

    /**
     * @return array<string,array{mixed}>
     */
    public static function nonArrayProvider(): array
    {
        return [
            'int' => [42],
            'float' => [1.5],
            'string' => ['hello'],
            'true' => [true],
            'false' => [false],
            'null' => [null],
            'object' => [new stdClass()],
        ];
    }

    #[TestDox('__invoke() returns empty array for non-array input')]
    #[DataProvider('nonArrayProvider')]
    public function test_invoke_rejects_non_array_input(mixed $input): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that any input which is not strictly a
        // PHP array is rejected by the __invoke() type-guard and
        // produces an empty type list

        // ----------------------------------------------------------------
        // setup your test

        $unit = new GetArrayTypes();
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
    // from() - non-callable arrays
    //
    // ----------------------------------------------------------------

    /**
     * @return array<string,array{array<array-key,mixed>}>
     */
    public static function nonCallableArrayProvider(): array
    {
        return [
            'empty' => [[]],
            'list of ints' => [[1, 2, 3]],
            'associative' => [['a' => 1, 'b' => 2]],
            'mixed content' => [['x', 1, 2.5, null]],
        ];
    }

    /**
     * @param array<array-key,mixed> $input
     */
    #[TestDox('from() returns array and mixed for a non-callable array')]
    #[DataProvider('nonCallableArrayProvider')]
    public function test_from_returns_expected_types_for_non_callable_array(array $input): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a plain PHP array - one that is
        // not also a valid callable - produces just 'array' and
        // 'mixed' regardless of the array's contents

        // ----------------------------------------------------------------
        // setup your test

        $expected = [
            'array' => 'array',
            'mixed' => 'mixed',
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetArrayTypes::from($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // from() - callable arrays
    //
    // ----------------------------------------------------------------

    #[TestDox('from() returns callable, array, and mixed for a callable array')]
    public function test_from_returns_expected_types_for_callable_array(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that an array of the shape
        // `[ClassName, 'methodName']` is also recognised as a
        // callable, so 'callable' is prepended to the array
        // family list

        // ----------------------------------------------------------------
        // setup your test

        $input = [DateTimeImmutable::class, 'createFromFormat'];
        $expected = [
            'callable' => 'callable',
            'array' => 'array',
            'mixed' => 'mixed',
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetArrayTypes::from($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }
}
