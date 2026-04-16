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
use StusDevKit\MissingBitsKit\TypeInspectors\GetBooleanTypes;

#[TestDox(GetBooleanTypes::class)]
class GetBooleanTypesTest extends TestCase
{
    // ================================================================
    //
    // Structure
    //
    // ----------------------------------------------------------------

    #[TestDox('Can instantiate GetBooleanTypes')]
    public function test_can_instantiate(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the GetBooleanTypes class can be
        // instantiated as an invokable object

        // ----------------------------------------------------------------
        // perform the change

        $unit = new GetBooleanTypes();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(GetBooleanTypes::class, $unit);
    }

    // ================================================================
    //
    // __invoke() - rejects inputs of the wrong type
    //
    // ----------------------------------------------------------------

    /**
     * @return array<string,array{mixed}>
     */
    public static function nonBooleanProvider(): array
    {
        return [
            'int' => [0],
            'positive int' => [1],
            'float' => [1.5],
            'string "true"' => ['true'],
            'empty string' => [''],
            'null' => [null],
            'array' => [[]],
            'object' => [new stdClass()],
        ];
    }

    #[TestDox('__invoke() returns empty array for non-boolean input')]
    #[DataProvider('nonBooleanProvider')]
    public function test_invoke_rejects_non_boolean_input(mixed $input): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that any input which is not strictly a
        // PHP bool is rejected by the __invoke() type-guard and
        // produces an empty type list - the inspector must not
        // apply PHP's loose boolean coercion (e.g. treating 0 or
        // '' as false)

        // ----------------------------------------------------------------
        // setup your test

        $unit = new GetBooleanTypes();
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

    #[TestDox('from(true) returns true and bool')]
    public function test_from_returns_expected_types_for_true(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that GetBooleanTypes::from(true)
        // returns the literal 'true' type (PHP 8.2+ accepts 'true'
        // as a standalone type hint) and the generic 'bool', in
        // that exact order. 'mixed' is not emitted here: it is the
        // duck-type marker owned by GetDuckTypes, not by per-type
        // inspectors.

        // ----------------------------------------------------------------
        // setup your test

        $expected = [
            'true' => 'true',
            'bool' => 'bool',
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetBooleanTypes::from(true);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('from(false) returns false and bool')]
    public function test_from_returns_expected_types_for_false(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that GetBooleanTypes::from(false)
        // returns the literal 'false' type (PHP 8.2+ accepts
        // 'false' as a standalone type hint) and the generic
        // 'bool', in that exact order. 'mixed' is not emitted
        // here: it is the duck-type marker owned by GetDuckTypes,
        // not by per-type inspectors.

        // ----------------------------------------------------------------
        // setup your test

        $expected = [
            'false' => 'false',
            'bool' => 'bool',
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetBooleanTypes::from(false);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }
}
