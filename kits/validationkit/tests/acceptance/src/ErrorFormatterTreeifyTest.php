<?php

//
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
//

declare(strict_types=1);
namespace StusDevKit\ValidationKit\Tests\Acceptance;

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use StusDevKit\ValidationKit\ErrorFormatting\ErrorFormatter;
use StusDevKit\ValidationKit\ErrorFormatting\TreeError;
use StusDevKit\ValidationKit\Exceptions\ValidationException;
use StusDevKit\ValidationKit\Validate;

#[TestDox('ErrorFormatter::treeify()')]
class ErrorFormatterTreeifyTest extends TestCase
{
    // ================================================================
    //
    // Root-Level Errors
    //
    // ----------------------------------------------------------------

    #[TestDox('root-level errors appear in the getErrors() array')]
    public function test_root_level_errors_in_errors_array(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that validation errors with no
        // path are placed in the root TreeError's getErrors()
        // array

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::string();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $caughtException = null;
        try {
            $unit->parse(42);
        } catch (ValidationException $e) {
            $caughtException = $e;
        }

        $this->assertNotNull($caughtException);
        $tree = ErrorFormatter::treeify($caughtException);

        // ----------------------------------------------------------------
        // test the results

        $this->assertNotEmpty($tree->getErrors());
        $this->assertEmpty($tree->getChildren());

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // Nested Field Errors
    //
    // ----------------------------------------------------------------

    #[TestDox('nested field errors appear as children')]
    public function test_nested_field_errors_as_children(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that validation errors on object
        // fields are placed as child nodes in the tree

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::object([
            'name' => Validate::string(),
            'age' => Validate::int(),
        ]);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $caughtException = null;
        try {
            $unit->parse((object) [
                'name' => 123,
                'age' => 'not a number',
            ]);
        } catch (ValidationException $e) {
            $caughtException = $e;
        }

        $this->assertNotNull($caughtException);
        $tree = ErrorFormatter::treeify($caughtException);

        // ----------------------------------------------------------------
        // test the results

        $nameChild = $tree->maybeGetChild('name');
        $this->assertNotNull($nameChild);
        $this->assertNotEmpty($nameChild->getErrors());

        $ageChild = $tree->maybeGetChild('age');
        $this->assertNotNull($ageChild);
        $this->assertNotEmpty($ageChild->getErrors());

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // Deep Nesting
    //
    // ----------------------------------------------------------------

    #[TestDox('deep nesting navigates via chained maybeGetChild()')]
    public function test_deep_nesting_via_maybe_child(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that deeply nested validation
        // errors can be accessed by chaining maybeGetChild()
        // calls, e.g. address -> zip

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::object([
            'name' => Validate::string(),
            'address' => Validate::object([
                'street' => Validate::string(),
                'zip' => Validate::string()->min(length: 5),
            ]),
        ]);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $caughtException = null;
        try {
            $unit->parse((object) [
                'name' => 'Alice',
                'address' => (object) [
                    'street' => 'Main St',
                    'zip' => 'ab',
                ],
            ]);
        } catch (ValidationException $e) {
            $caughtException = $e;
        }

        $this->assertNotNull($caughtException);
        $tree = ErrorFormatter::treeify($caughtException);

        // ----------------------------------------------------------------
        // test the results

        $addressChild = $tree->maybeGetChild('address');
        $this->assertNotNull($addressChild);

        $zipChild = $addressChild->maybeGetChild('zip');
        $this->assertNotNull($zipChild);
        $this->assertNotEmpty($zipChild->getErrors());

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // hasErrors()
    //
    // ----------------------------------------------------------------

    #[TestDox('hasErrors() returns true when errors exist')]
    public function test_has_errors_returns_true(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that hasErrors() returns true
        // when there are validation errors in the tree,
        // whether at the root or in child nodes

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::object([
            'name' => Validate::string(),
        ]);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $caughtException = null;
        try {
            $unit->parse((object) [
                'name' => 42,
            ]);
        } catch (ValidationException $e) {
            $caughtException = $e;
        }

        $this->assertNotNull($caughtException);
        $tree = ErrorFormatter::treeify($caughtException);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($tree->hasErrors());

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('hasErrors() detects errors in child nodes')]
    public function test_has_errors_detects_child_errors(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that hasErrors() returns true
        // even when errors are only in child nodes, not at
        // the root level

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::object([
            'address' => Validate::object([
                'zip' => Validate::string()->min(length: 5),
            ]),
        ]);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $caughtException = null;
        try {
            $unit->parse((object) [
                'address' => (object) [
                    'zip' => 'ab',
                ],
            ]);
        } catch (ValidationException $e) {
            $caughtException = $e;
        }

        $this->assertNotNull($caughtException);
        $tree = ErrorFormatter::treeify($caughtException);

        // ----------------------------------------------------------------
        // test the results

        // root has no direct errors, but hasErrors()
        // should still return true because child nodes
        // have errors
        $this->assertEmpty($tree->getErrors());
        $this->assertTrue($tree->hasErrors());

        // ----------------------------------------------------------------
        // clean up the database

    }
}
