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
use StusDevKit\MissingBitsKit\Tests\Fixtures\TypeInspectors\ChildOfTraitParent;
use StusDevKit\MissingBitsKit\Tests\Fixtures\TypeInspectors\ClassWithNestedTrait;
use StusDevKit\MissingBitsKit\Tests\Fixtures\TypeInspectors\InheritedTrait;
use StusDevKit\MissingBitsKit\Tests\Fixtures\TypeInspectors\NestedTrait;
use StusDevKit\MissingBitsKit\Tests\Fixtures\TypeInspectors\OuterTrait;
use StusDevKit\MissingBitsKit\Tests\Fixtures\TypeInspectors\SampleClass;
use StusDevKit\MissingBitsKit\Tests\Fixtures\TypeInspectors\SampleTrait;
use StusDevKit\MissingBitsKit\TypeInspectors\GetClassTraits;

#[TestDox(GetClassTraits::class)]
class GetClassTraitsTest extends TestCase
{
    // ================================================================
    //
    // Structure
    //
    // ----------------------------------------------------------------

    #[TestDox('Can instantiate GetClassTraits')]
    public function test_can_instantiate(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the GetClassTraits class can be
        // instantiated as an invokable object

        // ----------------------------------------------------------------
        // perform the change

        $unit = new GetClassTraits();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(GetClassTraits::class, $unit);
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

    #[TestDox('__invoke() returns empty array for input that is not a known class/trait name')]
    #[DataProvider('nonClassStringProvider')]
    public function test_invoke_rejects_non_class_input(mixed $input): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that __invoke() rejects any input that
        // is not a string naming a known class or trait

        // ----------------------------------------------------------------
        // setup your test

        $unit = new GetClassTraits();
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

    #[TestDox('from() returns empty array for a class that uses no traits')]
    public function test_from_returns_empty_for_class_without_traits(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a class using no traits produces
        // an empty trait list - GetClassTraits reports traits, not
        // the class itself

        // ----------------------------------------------------------------
        // setup your test

        $expected = [];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetClassTraits::from(stdClass::class);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('from() returns a directly used trait')]
    public function test_from_returns_directly_used_trait(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a class with a trait declared
        // directly via `use` reports that trait

        // ----------------------------------------------------------------
        // setup your test

        $expected = [
            SampleTrait::class => SampleTrait::class,
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetClassTraits::from(SampleClass::class);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('from() returns a trait inherited from a parent class')]
    public function test_from_returns_inherited_trait(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a trait declared on a parent
        // class is still reported when looking up the child class

        // ----------------------------------------------------------------
        // setup your test

        $expected = [
            InheritedTrait::class => InheritedTrait::class,
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetClassTraits::from(ChildOfTraitParent::class);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('from() walks traits used by other traits')]
    public function test_from_walks_traits_used_by_traits(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that when a class uses a trait which
        // itself uses another trait, both traits are reported -
        // the walk is recursive, not just one level deep

        // ----------------------------------------------------------------
        // setup your test

        $expected = [
            OuterTrait::class => OuterTrait::class,
            NestedTrait::class => NestedTrait::class,
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetClassTraits::from(ClassWithNestedTrait::class);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }
}
