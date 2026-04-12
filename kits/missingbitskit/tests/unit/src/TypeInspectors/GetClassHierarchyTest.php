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
use StusDevKit\MissingBitsKit\Tests\Fixtures\TypeInspectors\SampleParent;
use StusDevKit\MissingBitsKit\Tests\Fixtures\TypeInspectors\ThreeLevelChild;
use StusDevKit\MissingBitsKit\Tests\Fixtures\TypeInspectors\ThreeLevelGrandparent;
use StusDevKit\MissingBitsKit\Tests\Fixtures\TypeInspectors\ThreeLevelParent;
use StusDevKit\MissingBitsKit\TypeInspectors\GetClassHierarchy;

#[TestDox(GetClassHierarchy::class)]
class GetClassHierarchyTest extends TestCase
{
    // ================================================================
    //
    // Structure
    //
    // ----------------------------------------------------------------

    #[TestDox('Can instantiate GetClassHierarchy')]
    public function test_can_instantiate(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the GetClassHierarchy class can be
        // instantiated as an invokable object

        // ----------------------------------------------------------------
        // perform the change

        $unit = new GetClassHierarchy();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(GetClassHierarchy::class, $unit);
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

    #[TestDox('__invoke() returns empty array for input that is not a known class/interface name')]
    #[DataProvider('nonClassStringProvider')]
    public function test_invoke_rejects_non_class_input(mixed $input): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that __invoke() rejects any input that
        // is not a string naming a known class or interface - so
        // non-strings, and strings that do not resolve to anything
        // loaded in the current process, both produce an empty
        // type list

        // ----------------------------------------------------------------
        // setup your test

        $unit = new GetClassHierarchy();
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

    #[TestDox('from() returns only the class itself when it has no parents')]
    public function test_from_returns_only_class_when_no_parents(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a class with no parent class
        // produces a hierarchy list containing only the class
        // itself

        // ----------------------------------------------------------------
        // setup your test

        $expected = [
            stdClass::class => stdClass::class,
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetClassHierarchy::from(stdClass::class);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('from() returns class plus its parent for a 2-deep hierarchy')]
    public function test_from_returns_class_and_parent(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a class extending one parent
        // produces a hierarchy list of the class followed by its
        // parent

        // ----------------------------------------------------------------
        // setup your test

        $expected = [
            SampleClass::class => SampleClass::class,
            SampleParent::class => SampleParent::class,
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetClassHierarchy::from(SampleClass::class);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('from() returns the full chain for a 3-deep hierarchy')]
    public function test_from_returns_full_three_level_hierarchy(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that from() walks the entire parent
        // chain - not just the immediate parent - and returns
        // every ancestor in order (child, parent, grandparent)

        // ----------------------------------------------------------------
        // setup your test

        $expected = [
            ThreeLevelChild::class => ThreeLevelChild::class,
            ThreeLevelParent::class => ThreeLevelParent::class,
            ThreeLevelGrandparent::class => ThreeLevelGrandparent::class,
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actual = GetClassHierarchy::from(ThreeLevelChild::class);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }
}
