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
use StusDevKit\MissingBitsKit\Tests\Fixtures\TypeInspectors\BaseInterface;
use StusDevKit\MissingBitsKit\Tests\Fixtures\TypeInspectors\ChildOfInterfaceParent;
use StusDevKit\MissingBitsKit\Tests\Fixtures\TypeInspectors\ClassWithExtendedInterface;
use StusDevKit\MissingBitsKit\Tests\Fixtures\TypeInspectors\ExtendedInterface;
use StusDevKit\MissingBitsKit\Tests\Fixtures\TypeInspectors\InheritedInterface;
use StusDevKit\MissingBitsKit\Tests\Fixtures\TypeInspectors\SampleClass;
use StusDevKit\MissingBitsKit\Tests\Fixtures\TypeInspectors\SampleInterface;
use StusDevKit\MissingBitsKit\TypeInspectors\GetClassInterfaces;

#[TestDox(GetClassInterfaces::class)]
class GetClassInterfacesTest extends TestCase
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

        // this test proves that the GetClassInterfaces class can
        // be instantiated as an invokable object

        // ----------------------------------------------------------------
        // perform the change

        $unit = new GetClassInterfaces();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(GetClassInterfaces::class, $unit);
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

    #[TestDox('->__invoke() returns empty array for input that is not a known class/interface name')]
    #[DataProvider('nonClassStringProvider')]
    public function test_invoke_rejects_non_class_input(mixed $input): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that __invoke() rejects any input that
        // is not a string naming a known class or interface

        // ----------------------------------------------------------------
        // setup your test

        $unit = new GetClassInterfaces();
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

    #[TestDox('::from() returns empty array when it implements nothing')]
    public function test_from_returns_empty_array_when_no_interfaces(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a class implementing no
        // interfaces returns an empty array

        // ----------------------------------------------------------------
        // setup your test

        $expected = [];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetClassInterfaces::from(stdClass::class);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::from() returns a directly implemented interface')]
    public function test_from_returns_direct_interface(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that an interface declared on the
        // class itself appears in the return value

        // ----------------------------------------------------------------
        // setup your test

        $expected = [
            SampleInterface::class => SampleInterface::class,
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetClassInterfaces::from(SampleClass::class);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::from() returns an interface inherited from a parent class')]
    public function test_from_returns_inherited_interface(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that when a parent class declares an
        // interface, the child class still reports that interface

        // ----------------------------------------------------------------
        // setup your test

        $expected = [
            InheritedInterface::class => InheritedInterface::class,
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetClassInterfaces::from(ChildOfInterfaceParent::class);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::from() walks a 2-deep interface hierarchy')]
    public function test_from_walks_interface_hierarchy(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that when a class implements an
        // interface that itself extends another interface, both
        // interfaces are reported

        // ----------------------------------------------------------------
        // setup your test

        $expected = [
            ExtendedInterface::class => ExtendedInterface::class,
            BaseInterface::class => BaseInterface::class,
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetClassInterfaces::from(ClassWithExtendedInterface::class);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }
}
