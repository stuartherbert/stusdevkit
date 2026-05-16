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

namespace StusDevKit\MissingBitsKit\Tests\Unit\Arrays;

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use StusDevKit\MissingBitsKit\Arrays\ArrayShape;

#[TestDox(ArrayShape::class)]
class ArrayShapeTest extends TestCase
{
    // ================================================================
    //
    // Enum identity
    //
    // ----------------------------------------------------------------

    #[TestDox('is declared as an enum')]
    public function test_is_declared_as_an_enum(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // callers pattern-match on ArrayShape's cases, so the type
        // must be an enum - downgrading to a plain class would
        // silently break every `match` and every type-hint in
        // calling code.

        // ----------------------------------------------------------------
        // setup your test

        $reflection = new ReflectionClass(ArrayShape::class);

        // ----------------------------------------------------------------
        // perform the change

        $actual = $reflection->isEnum();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('lives in the StusDevKit\\MissingBitsKit\\Arrays namespace')]
    public function test_lives_in_the_expected_namespace(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // the published namespace is part of the contract. Callers
        // import ArrayShape by its FQN, so moving it is a breaking
        // change that must go through a major version bump.

        // ----------------------------------------------------------------
        // setup your test

        $expected = 'StusDevKit\\MissingBitsKit\\Arrays';

        // ----------------------------------------------------------------
        // perform the change

        $actual = (new ReflectionClass(ArrayShape::class))->getNamespaceName();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }


    // ================================================================
    //
    // Case set
    //
    // ----------------------------------------------------------------

    #[TestDox('publishes exactly the LIST and MAP cases')]
    public function test_publishes_the_expected_case_set(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // pin the enumerated case set, not just the count. Drift
        // on a single case name shows up here as a named diff
        // rather than "expected 2, got 3".

        // ----------------------------------------------------------------
        // setup your test

        $expected = ['LIST', 'MAP'];

        // ----------------------------------------------------------------
        // perform the change

        $actual = array_map(
            static fn (ArrayShape $case): string => $case->name,
            ArrayShape::cases(),
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // Convenience predicates
    //
    // ----------------------------------------------------------------

    #[TestDox('->isList() returns true for the LIST case')]
    public function test_isList_returns_true_for_list_case(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // isList() is the dedicated shorthand for callers that
        // only care about list-ness. It must return true when
        // (and only when) the case IS the LIST case.

        // ----------------------------------------------------------------
        // setup your test

        $case = ArrayShape::LIST;

        // ----------------------------------------------------------------
        // perform the change

        $actual = $case->isList();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('->isList() returns false for the MAP case')]
    public function test_isList_returns_false_for_map_case(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // pin the negative answer too. A regression that flipped
        // the comparison inside isList() would still pass the
        // positive test above; this catches it.

        // ----------------------------------------------------------------
        // setup your test

        $case = ArrayShape::MAP;

        // ----------------------------------------------------------------
        // perform the change

        $actual = $case->isList();

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($actual);
    }

    #[TestDox('->isMap() returns true for the MAP case')]
    public function test_isMap_returns_true_for_map_case(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // isMap() is the dedicated shorthand for callers that
        // only care about map-ness. It must return true when
        // (and only when) the case IS the MAP case.

        // ----------------------------------------------------------------
        // setup your test

        $case = ArrayShape::MAP;

        // ----------------------------------------------------------------
        // perform the change

        $actual = $case->isMap();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actual);
    }

    #[TestDox('->isMap() returns false for the LIST case')]
    public function test_isMap_returns_false_for_list_case(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // pin the negative answer too. A regression that flipped
        // the comparison inside isMap() would still pass the
        // positive test above; this catches it.

        // ----------------------------------------------------------------
        // setup your test

        $case = ArrayShape::LIST;

        // ----------------------------------------------------------------
        // perform the change

        $actual = $case->isMap();

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($actual);
    }
}
