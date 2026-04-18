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
use StusDevKit\MissingBitsKit\Tests\Fixtures\TypeInspectors\SampleClass;
use StusDevKit\MissingBitsKit\Tests\Fixtures\TypeInspectors\SampleInterface;
use StusDevKit\MissingBitsKit\Tests\Fixtures\TypeInspectors\SampleParent;
use StusDevKit\MissingBitsKit\Tests\Fixtures\TypeInspectors\SampleTrait;
use StusDevKit\MissingBitsKit\TypeInspectors\GetClassTypes;

#[TestDox(GetClassTypes::class)]
class GetClassTypesTest extends TestCase
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

        // this test proves that the GetClassTypes class can be
        // instantiated as an invokable object

        // ----------------------------------------------------------------
        // perform the change

        $unit = new GetClassTypes();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(GetClassTypes::class, $unit);
    }

    // ================================================================
    //
    // __invoke() - rejects inputs of the wrong type
    //
    // ----------------------------------------------------------------

    /**
     * @return array<string,array{mixed}>
     */
    public static function nonClassStringProvider(): array
    {
        return [
            'int' => [42],
            'float' => [1.5],
            'true' => [true],
            'false' => [false],
            'null' => [null],
            'array' => [[]],
            'object' => [new stdClass()],
            'unknown class name' => ['ClassThatDoesNotExist'],
            'empty string' => [''],
        ];
    }

    #[TestDox('->__invoke() returns empty array for input that is not a known class/interface/trait name')]
    #[DataProvider('nonClassStringProvider')]
    public function test_invoke_rejects_non_class_input(mixed $input): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that __invoke() rejects any input that
        // is not a string naming a known class, interface, or
        // trait in the current process

        // ----------------------------------------------------------------
        // setup your test

        $unit = new GetClassTypes();
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

    #[TestDox('::from() returns class and object for a simple class')]
    public function test_from_returns_expected_types_for_simple_class(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a class with no parents,
        // interfaces, or traits produces just the class name plus
        // the universal 'object' type-hint token.
        //
        // 'mixed' is deliberately NOT in the output. mixed is a
        // duck-type marker meaning "any value" - true of every
        // PHP value, not a satisfaction claim about classes - so
        // it adds no information here.

        // ----------------------------------------------------------------
        // setup your test

        $expected = [
            stdClass::class => stdClass::class,
            'object' => 'object',
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetClassTypes::from(stdClass::class);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::from() returns class, parent, interface, trait, and object for a class with all three')]
    public function test_from_returns_full_class_surface(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that when a class extends a parent,
        // implements an interface, and uses a trait, every branch
        // of its type surface appears in the returned type list,
        // ending with the universal 'object' token.
        //
        // 'mixed' is deliberately NOT in the output - see
        // test_from_returns_expected_types_for_simple_class for
        // the rationale.

        // ----------------------------------------------------------------
        // setup your test

        $expected = [
            SampleClass::class => SampleClass::class,
            SampleParent::class => SampleParent::class,
            SampleInterface::class => SampleInterface::class,
            SampleTrait::class => SampleTrait::class,
            'object' => 'object',
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetClassTypes::from(SampleClass::class);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }
}
