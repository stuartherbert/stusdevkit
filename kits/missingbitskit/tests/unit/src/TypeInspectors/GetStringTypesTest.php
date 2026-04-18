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
use Stringable;
use StusDevKit\MissingBitsKit\Tests\Fixtures\TypeInspectors\SampleToString;
use StusDevKit\MissingBitsKit\TypeInspectors\GetStringTypes;

#[TestDox(GetStringTypes::class)]
class GetStringTypesTest extends TestCase
{
    // ================================================================
    //
    // Structure
    //
    // ----------------------------------------------------------------

    #[TestDox('::__construct() returns a new instance')]
    public function test_can_instantiate(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the GetStringTypes class can be
        // instantiated as an invokable object

        // ----------------------------------------------------------------
        // perform the change

        $unit = new GetStringTypes();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(GetStringTypes::class, $unit);
    }

    // ================================================================
    //
    // __invoke() - rejects inputs of the wrong type
    //
    // ----------------------------------------------------------------

    /**
     * @return array<string,array{mixed}>
     */
    public static function nonStringProvider(): array
    {
        return [
            'int' => [42],
            'float' => [1.5],
            'true' => [true],
            'false' => [false],
            'null' => [null],
            'array' => [[]],
            'object without __toString' => [new stdClass()],
        ];
    }

    #[TestDox('->__invoke() returns empty array for a non-string, non-Stringable input')]
    #[DataProvider('nonStringProvider')]
    public function test_invoke_rejects_non_string_input(mixed $input): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that __invoke() rejects any value that
        // is neither a PHP string nor an object that PHP would
        // coerce to a string via `Stringable`

        // ----------------------------------------------------------------
        // setup your test

        $unit = new GetStringTypes();
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
    // __invoke() - Stringable coercion
    //
    // ----------------------------------------------------------------

    #[TestDox('->__invoke() coerces a Stringable object and returns the expected type list')]
    public function test_invoke_coerces_stringable_object(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that __invoke() coerces a Stringable
        // object to a string and then produces the same type list
        // it would produce for that string directly. 'mixed' is
        // not emitted here: it is the duck-type marker owned by
        // GetDuckTypes, not by per-type inspectors.

        // ----------------------------------------------------------------
        // setup your test

        $unit = new GetStringTypes();
        $input = new SampleToString();
        $expected = [
            Stringable::class => Stringable::class,
            'string' => 'string',
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actual = $unit($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // from() - plain strings
    //
    // ----------------------------------------------------------------

    /**
     * @return array<string,array{string}>
     */
    public static function plainStringProvider(): array
    {
        return [
            'empty' => [''],
            'single word' => ['hello'],
            'with spaces' => ['hello, world'],
        ];
    }

    #[TestDox('::from() returns just string for a plain string')]
    #[DataProvider('plainStringProvider')]
    public function test_from_returns_expected_types_for_plain_string(string $input): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a string which is not callable and
        // not numeric produces just 'string'. 'mixed' is not
        // emitted here: it is the duck-type marker owned by
        // GetDuckTypes, not by per-type inspectors.

        // ----------------------------------------------------------------
        // setup your test

        $expected = [
            'string' => 'string',
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetStringTypes::from($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // from() - callable strings
    //
    // ----------------------------------------------------------------

    #[TestDox('::from() returns callable and string for a callable string')]
    public function test_from_returns_expected_types_for_callable_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a string which is the name of a
        // callable function is surfaced with 'callable' prepended
        // to the string family

        // ----------------------------------------------------------------
        // setup your test

        $input = 'strlen';
        $expected = [
            'callable' => 'callable',
            'string' => 'string',
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetStringTypes::from($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // from() - numeric strings
    //
    // ----------------------------------------------------------------

    #[TestDox('::from() returns numeric, int, and string for an integer-shaped numeric string')]
    public function test_from_returns_expected_types_for_numeric_int_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a string whose content is an
        // integer value is surfaced with the numeric family
        // alongside the string family

        // ----------------------------------------------------------------
        // setup your test

        $input = '123';
        $expected = [
            'numeric' => 'numeric',
            'int' => 'int',
            'string' => 'string',
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetStringTypes::from($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::from() returns numeric, float, and string for a float-shaped numeric string')]
    public function test_from_returns_expected_types_for_numeric_float_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a string whose content is a
        // decimal value is surfaced with the numeric family
        // alongside the string family

        // ----------------------------------------------------------------
        // setup your test

        $input = '45.6';
        $expected = [
            'numeric' => 'numeric',
            'float' => 'float',
            'string' => 'string',
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetStringTypes::from($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }
}
