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

namespace StusDevKit\ValidationKit\Tests\Unit\ErrorFormatting;

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use StusDevKit\ValidationKit\ErrorFormatting\TreeError;

/**
 * Contract + behaviour tests for TreeError.
 *
 * TreeError is a read-only recursive node: it holds a list
 * of error messages at this level plus a map of child
 * TreeErrors keyed by field name or integer array index.
 * Tests pin the class shape and verify getter behaviour,
 * `maybeGetChild()` lookup semantics, and the recursive
 * `hasErrors()` walk.
 */
#[TestDox(TreeError::class)]
class TreeErrorTest extends TestCase
{
    // ================================================================
    //
    // Class identity
    //
    // ----------------------------------------------------------------

    #[TestDox('lives in the StusDevKit\\ValidationKit\\ErrorFormatting namespace')]
    public function test_lives_in_the_expected_namespace(): void
    {
        $expected = 'StusDevKit\\ValidationKit\\ErrorFormatting';

        $actual = (new ReflectionClass(TreeError::class))
            ->getNamespaceName();

        $this->assertSame($expected, $actual);
    }

    #[TestDox('is declared as a final class')]
    public function test_is_declared_final(): void
    {
        // TreeError is a recursive DTO - extending it to
        // override `hasErrors()` would break the pure-value
        // contract and could produce infinite recursion if
        // the override forgot to delegate. `final` protects
        // callers.

        $reflection = new ReflectionClass(TreeError::class);

        $actual = $reflection->isFinal()
            && (! $reflection->isInterface())
            && (! $reflection->isTrait())
            && (! $reflection->isEnum());

        $this->assertTrue($actual);
    }

    #[TestDox('declares the expected public method set')]
    public function test_declares_expected_public_methods(): void
    {
        // the surface area is pinned by enumeration. Naming
        // the full set here catches accidental additions
        // (e.g. a setter) and removals (e.g. renaming
        // maybeGetChild()) before they ship.

        $expected = [
            '__construct',
            'getChildren',
            'getErrors',
            'hasErrors',
            'maybeGetChild',
        ];
        $reflection = new ReflectionClass(TreeError::class);

        $methodNames = array_values(array_map(
            static fn ($m) => $m->getName(),
            $reflection->getMethods(ReflectionMethod::IS_PUBLIC),
        ));
        sort($methodNames);

        $this->assertSame($expected, $methodNames);
    }

    // ================================================================
    //
    // ->getErrors() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('->getErrors() defaults to []')]
    public function test_getErrors_default_empty(): void
    {
        // both constructor parameters have default values of
        // [], so `new TreeError()` with no arguments must
        // yield a fully-empty node.

        $unit = new TreeError();

        $actual = $unit->getErrors();

        $this->assertSame([], $actual);
    }

    #[TestDox('->getErrors() returns the list passed into the constructor')]
    public function test_getErrors_returns_constructor_input(): void
    {
        $expected = [
            'Required field missing',
            'Unknown property',
        ];

        $unit = new TreeError(errors: $expected);

        $actual = $unit->getErrors();

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // ->getChildren() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('->getChildren() defaults to []')]
    public function test_getChildren_default_empty(): void
    {
        $unit = new TreeError();

        $actual = $unit->getChildren();

        $this->assertSame([], $actual);
    }

    #[TestDox('->getChildren() preserves the exact map it was constructed with')]
    public function test_getChildren_returns_constructor_input(): void
    {
        // the accessor is a pure getter - children are not
        // sorted, dedup'd, or reparented. Pinning the exact
        // map guarantees the caller's mental model of their
        // data shape is preserved.

        $zip = new TreeError(errors: ['Invalid ZIP']);
        $city = new TreeError(errors: ['City required']);

        $expected = [
            'zip' => $zip,
            'city' => $city,
        ];

        $unit = new TreeError(children: $expected);

        $actual = $unit->getChildren();

        $this->assertSame($expected, $actual);
    }

    // ================================================================
    //
    // ->maybeGetChild() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('->maybeGetChild() returns null when the string key is not present')]
    public function test_maybeGetChild_missing_string_key(): void
    {
        // `maybeGet*` is this codebase's signal for
        // "returns null instead of throwing when absent".
        // Callers exploring an unknown data shape rely on
        // this to probe without a try/catch.

        $unit = new TreeError();

        $actual = $unit->maybeGetChild('address');

        $this->assertNull($actual);
    }

    #[TestDox('->maybeGetChild() returns null when the integer index is not present')]
    public function test_maybeGetChild_missing_int_index(): void
    {
        $unit = new TreeError();

        $actual = $unit->maybeGetChild(3);

        $this->assertNull($actual);
    }

    #[TestDox('->maybeGetChild() returns the child when the string key is present')]
    public function test_maybeGetChild_string_key_hit(): void
    {
        $child = new TreeError(errors: ['Invalid ZIP']);

        $unit = new TreeError(
            children: ['address' => $child],
        );

        $actual = $unit->maybeGetChild('address');

        $this->assertSame($child, $actual);
    }

    #[TestDox('->maybeGetChild() returns the child when the integer index is present')]
    public function test_maybeGetChild_int_index_hit(): void
    {
        // array indexes are int-keyed in this tree because
        // the input data's positional structure carries
        // through. A TupleSchema failure at index 2 must
        // appear under the integer key 2, not the string
        // key "2".

        $child = new TreeError(errors: ['Item invalid']);

        $unit = new TreeError(children: [2 => $child]);

        $actual = $unit->maybeGetChild(2);

        $this->assertSame($child, $actual);
    }

    // ================================================================
    //
    // ->hasErrors() behaviour
    //
    // ----------------------------------------------------------------

    #[TestDox('->hasErrors() returns false for an empty node')]
    public function test_hasErrors_empty_node(): void
    {
        $unit = new TreeError();

        $actual = $unit->hasErrors();

        $this->assertFalse($actual);
    }

    #[TestDox('->hasErrors() returns true when this node carries any errors')]
    public function test_hasErrors_direct_errors(): void
    {
        $unit = new TreeError(errors: ['Required field missing']);

        $actual = $unit->hasErrors();

        $this->assertTrue($actual);
    }

    #[TestDox('->hasErrors() returns true when a descendant carries any errors')]
    public function test_hasErrors_nested_errors(): void
    {
        // the walk is recursive so that UI consumers can
        // ask "is there anything to show under this
        // subtree?" without manually traversing. Burying
        // an error two levels deep must still return true.

        $leaf = new TreeError(errors: ['Invalid ZIP']);
        $mid = new TreeError(children: ['zip' => $leaf]);

        $unit = new TreeError(children: ['address' => $mid]);

        $actual = $unit->hasErrors();

        $this->assertTrue($actual);
    }

    #[TestDox('->hasErrors() returns false when the subtree has children but no error messages anywhere')]
    public function test_hasErrors_empty_subtree(): void
    {
        // a node can have children that are themselves
        // empty - the walk must keep probing rather than
        // short-circuiting on child-count alone.

        $emptyChild = new TreeError();

        $unit = new TreeError(
            children: ['address' => $emptyChild],
        );

        $actual = $unit->hasErrors();

        $this->assertFalse($actual);
    }
}
