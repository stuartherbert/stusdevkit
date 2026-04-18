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

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use StusDevKit\MissingBitsKit\Tests\Fixtures\TypeInspectors\SampleClass;
use StusDevKit\MissingBitsKit\Tests\Fixtures\TypeInspectors\SampleInterface;
use StusDevKit\MissingBitsKit\Tests\Fixtures\TypeInspectors\SampleParent;
use StusDevKit\MissingBitsKit\Tests\Fixtures\TypeInspectors\SampleTrait;
use StusDevKit\MissingBitsKit\TypeInspectors\FlattenClassTypes;

#[TestDox(FlattenClassTypes::class)]
class FlattenClassTypesTest extends TestCase
{
    // ================================================================
    //
    // Empty input
    //
    // ----------------------------------------------------------------

    #[TestDox('::from() returns an empty list for an empty input list')]
    public function test_from_returns_empty_list_for_empty_input(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // a caller passing no type names to expand should get an
        // empty list back - there is nothing to expand, and the
        // helper should not invent entries of its own.

        // ----------------------------------------------------------------
        // perform the change

        $actual = FlattenClassTypes::from([]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame([], $actual);
    }

    // ================================================================
    //
    // Non-class / non-interface inputs
    //
    // ----------------------------------------------------------------

    #[TestDox('::from() drops scalar and built-in type-name strings that are neither classes nor interfaces')]
    public function test_from_drops_non_class_interface_names(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // scalar type names like 'int', 'string', 'array' have no
        // class hierarchy to expand, so the helper drops them. This
        // matches the call site in ResolveParameter, which only
        // expanded inputs that passed class_exists/interface_exists
        // before delegating to GetClassTypes.

        // ----------------------------------------------------------------
        // perform the change

        $actual = FlattenClassTypes::from(['int', 'string', 'array', 'bool']);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame([], $actual);
    }

    // ================================================================
    //
    // Class expansion
    //
    // ----------------------------------------------------------------

    #[TestDox('::from() expands a class to its full hierarchy as a flat list')]
    public function test_from_expands_a_class_to_its_full_hierarchy(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // a single class-string input expands to: itself, its
        // parent classes, its interfaces, its traits, and the
        // universal 'object' token. The helper returns that as a
        // plain list (flat values, not the key => value form
        // GetClassTypes produces), so callers can iterate it
        // directly without array_values() themselves.

        // SampleClass extends SampleParent implements SampleInterface
        // uses SampleTrait - chosen because its hierarchy is stable
        // across PHP versions (unlike built-in classes whose
        // interface surface changes between releases).

        // ----------------------------------------------------------------
        // setup your test

        $expected = [
            SampleClass::class,
            SampleParent::class,
            SampleInterface::class,
            SampleTrait::class,
            'object',
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actual = FlattenClassTypes::from([SampleClass::class]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::from() expands an interface to itself plus the universal object type')]
    public function test_from_expands_an_interface_to_itself_plus_universal_types(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // an interface with no parent interfaces expands to just
        // itself plus 'object'. This test proves that the helper's
        // filter accepts interfaces (not only classes), matching
        // the call site in ResolveParameter which used
        // class_exists() || interface_exists().

        // ----------------------------------------------------------------
        // setup your test

        $expected = [
            SampleInterface::class,
            'object',
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actual = FlattenClassTypes::from([SampleInterface::class]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // Deduplication
    //
    // ----------------------------------------------------------------

    #[TestDox('::from() deduplicates when the same class-string appears more than once')]
    public function test_from_deduplicates_repeated_inputs(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // passing the same class-string twice must not produce two
        // copies of its hierarchy in the output. Deduplication is
        // owned by the helper so that every caller benefits
        // uniformly - the caller that originally inspired this
        // refactor (ResolveParameter) had a quiet duplicate-probe
        // bug in its second pass.

        // ----------------------------------------------------------------
        // setup your test

        $expected = [
            SampleClass::class,
            SampleParent::class,
            SampleInterface::class,
            SampleTrait::class,
            'object',
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actual = FlattenClassTypes::from(
            [SampleClass::class, SampleClass::class],
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::from() deduplicates shared ancestors across distinct inputs, preserving first-seen order')]
    public function test_from_deduplicates_shared_ancestors_preserving_first_seen_order(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // SampleClass and SampleParent share the universal ancestor
        // 'object', and SampleClass's expansion already emits
        // SampleParent. A caller listing both
        // [SampleClass, SampleParent] should see each name exactly
        // once in the output, in the order they were first seen
        // while walking the inputs left-to-right.
        //
        // First-seen order matters because a downstream caller
        // (e.g. a DI resolver) probes types in list order and
        // should see the developer's preferred types before their
        // ancestors.

        // ----------------------------------------------------------------
        // setup your test

        $expected = [
            SampleClass::class,
            SampleParent::class,
            SampleInterface::class,
            SampleTrait::class,
            'object',
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actual = FlattenClassTypes::from(
            [SampleClass::class, SampleParent::class],
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    #[TestDox('::from() preserves first-seen order when the input order is reversed')]
    public function test_from_preserves_first_seen_order_when_input_is_reversed(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this is the companion to the previous test. With the
        // inputs [SampleParent, SampleClass], SampleParent's short
        // expansion runs first and seeds the output list; then
        // SampleClass's expansion contributes only the names that
        // SampleParent did not already supply, appended in the
        // order GetClassTypes produces them.

        // ----------------------------------------------------------------
        // setup your test

        $expected = [
            SampleParent::class,
            'object',
            SampleClass::class,
            SampleInterface::class,
            SampleTrait::class,
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actual = FlattenClassTypes::from(
            [SampleParent::class, SampleClass::class],
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // Mixed class and non-class inputs
    //
    // ----------------------------------------------------------------

    #[TestDox('::from() still expands class inputs that follow a dropped non-class input')]
    public function test_from_keeps_expansions_after_a_dropped_non_class_input(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // a scalar type name sitting in front of a class-string
        // must not short-circuit or otherwise disturb the
        // expansion of the class. The scalar is dropped, and the
        // class is expanded as if it were the first input.

        // ----------------------------------------------------------------
        // setup your test

        $expected = [
            SampleClass::class,
            SampleParent::class,
            SampleInterface::class,
            SampleTrait::class,
            'object',
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actual = FlattenClassTypes::from(['int', SampleClass::class]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expected, $actual);
    }
}
