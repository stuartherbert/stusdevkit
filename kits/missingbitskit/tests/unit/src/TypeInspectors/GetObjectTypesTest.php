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

use Closure;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use stdClass;
use StusDevKit\MissingBitsKit\Tests\Fixtures\TypeInspectors\SampleClass;
use StusDevKit\MissingBitsKit\Tests\Fixtures\TypeInspectors\SampleInterface;
use StusDevKit\MissingBitsKit\Tests\Fixtures\TypeInspectors\SampleInvokable;
use StusDevKit\MissingBitsKit\Tests\Fixtures\TypeInspectors\SampleParent;
use StusDevKit\MissingBitsKit\Tests\Fixtures\TypeInspectors\SampleTrait;
use StusDevKit\MissingBitsKit\TypeInspectors\GetObjectTypes;

#[TestDox(GetObjectTypes::class)]
class GetObjectTypesTest extends TestCase
{
    // ================================================================
    //
    // Structure
    //
    // ----------------------------------------------------------------

    #[TestDox('Can instantiate GetObjectTypes')]
    public function test_can_instantiate(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the GetObjectTypes class can be
        // instantiated as an invokable object

        // ----------------------------------------------------------------
        // perform the change

        $unit = new GetObjectTypes();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(GetObjectTypes::class, $unit);
    }

    // ================================================================
    //
    // __invoke() - rejects inputs of the wrong type
    //
    // ----------------------------------------------------------------

    /**
     * @return array<string,array{mixed}>
     */
    public static function nonObjectProvider(): array
    {
        return [
            'int' => [42],
            'float' => [1.5],
            'string' => ['hello'],
            'true' => [true],
            'false' => [false],
            'null' => [null],
            'array' => [[]],
        ];
    }

    #[TestDox('__invoke() returns empty array for non-object input')]
    #[DataProvider('nonObjectProvider')]
    public function test_invoke_rejects_non_object_input(mixed $input): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that any input which is not an object
        // is rejected by the __invoke() type-guard and produces
        // an empty type list

        // ----------------------------------------------------------------
        // setup your test

        $unit = new GetObjectTypes();
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

    #[TestDox('from() returns class name and object for a plain object')]
    public function test_from_returns_expected_types_for_plain_object(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that an object with no parents,
        // interfaces, or traits produces just the class name plus
        // 'object'. 'mixed' is not emitted here: it is the
        // duck-type marker owned by GetDuckTypes, not by per-type
        // inspectors.

        // ----------------------------------------------------------------
        // setup your test

        $input = new stdClass();
        $expected = [
            stdClass::class => stdClass::class,
            'object' => 'object',
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetObjectTypes::from($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('from() returns full class surface for an object with parent, interface, and trait')]
    public function test_from_returns_full_class_surface(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that every branch of an object's type
        // surface is reported - parent, interface, trait - ending
        // with the universal 'object' token. 'mixed' is not
        // emitted here: it is the duck-type marker owned by
        // GetDuckTypes, not by per-type inspectors.

        // ----------------------------------------------------------------
        // setup your test

        $input = new SampleClass();
        $expected = [
            SampleClass::class => SampleClass::class,
            SampleParent::class => SampleParent::class,
            SampleInterface::class => SampleInterface::class,
            SampleTrait::class => SampleTrait::class,
            'object' => 'object',
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetObjectTypes::from($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('from() adds callable for a Closure instance')]
    public function test_from_adds_callable_for_closure(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a Closure - callable at the
        // instance level but whose class name 'Closure' is not a
        // global function - is still reported as 'callable'

        // ----------------------------------------------------------------
        // setup your test

        $input = fn(): int => 1;
        $expected = [
            Closure::class => Closure::class,
            'callable' => 'callable',
            'object' => 'object',
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetObjectTypes::from($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('from() adds callable for an object that defines __invoke()')]
    public function test_from_adds_callable_for_invokable_object(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that an object whose class declares
        // __invoke() is reported as 'callable' - the
        // class-name-based is_callable() check would miss this, so
        // GetObjectTypes must look at the instance

        // ----------------------------------------------------------------
        // setup your test

        $input = new SampleInvokable();
        $expected = [
            SampleInvokable::class => SampleInvokable::class,
            'callable' => 'callable',
            'object' => 'object',
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetObjectTypes::from($input);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }
}
