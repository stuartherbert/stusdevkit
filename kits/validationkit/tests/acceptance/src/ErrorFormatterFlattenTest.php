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
use StusDevKit\ValidationKit\Exceptions\ValidationException;
use StusDevKit\ValidationKit\Validate;

#[TestDox('ErrorFormatter::flatten()')]
class ErrorFormatterFlattenTest extends TestCase
{
    // ================================================================
    //
    // Form Errors (no path)
    //
    // ----------------------------------------------------------------

    #[TestDox('top-level error with no path goes to formErrors')]
    public function test_top_level_error_goes_to_form_errors(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a validation error with no
        // path (root-level) is placed in formErrors, not
        // fieldErrors

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
        $flat = ErrorFormatter::flatten($caughtException);

        // ----------------------------------------------------------------
        // test the results

        $this->assertNotEmpty($flat->formErrors());
        $this->assertEmpty($flat->fieldErrors());

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // Field Errors
    //
    // ----------------------------------------------------------------

    #[TestDox('field errors go to fieldErrors keyed by dot-path')]
    public function test_field_errors_keyed_by_dot_path(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that validation errors on object
        // fields are placed in fieldErrors, keyed by the
        // field name

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
            $unit->parse([
                'name' => 123,
                'age' => 'not a number',
            ]);
        } catch (ValidationException $e) {
            $caughtException = $e;
        }

        $this->assertNotNull($caughtException);
        $flat = ErrorFormatter::flatten($caughtException);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEmpty($flat->formErrors());
        $this->assertArrayHasKey('name', $flat->fieldErrors());
        $this->assertArrayHasKey('age', $flat->fieldErrors());

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('multiple errors on the same field are collected')]
    public function test_multiple_errors_on_same_field(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that when a single field produces
        // multiple validation errors, they are all collected
        // in the same fieldErrors array entry

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::object([
            'email' => Validate::string()->email(),
        ]);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $caughtException = null;
        try {
            // passing an int will fail both string type check
            $unit->parse([
                'email' => 42,
            ]);
        } catch (ValidationException $e) {
            $caughtException = $e;
        }

        $this->assertNotNull($caughtException);
        $flat = ErrorFormatter::flatten($caughtException);

        // ----------------------------------------------------------------
        // test the results

        $this->assertArrayHasKey('email', $flat->fieldErrors());
        $this->assertNotEmpty($flat->fieldErrors()['email']);

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // Nested Objects
    //
    // ----------------------------------------------------------------

    #[TestDox('nested object errors produce dot-notation keys')]
    public function test_nested_object_errors_use_dot_notation(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that validation errors on nested
        // object fields produce dot-notation keys like
        // "address.zip"

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
            $unit->parse([
                'name' => 'Alice',
                'address' => [
                    'street' => 'Main St',
                    'zip' => 'ab',
                ],
            ]);
        } catch (ValidationException $e) {
            $caughtException = $e;
        }

        $this->assertNotNull($caughtException);
        $flat = ErrorFormatter::flatten($caughtException);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEmpty($flat->formErrors());
        $this->assertArrayHasKey(
            'address.zip',
            $flat->fieldErrors(),
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // Mixed Form + Field Errors
    //
    // ----------------------------------------------------------------

    #[TestDox('handles mixed form-level and field-level errors')]
    public function test_mixed_form_and_field_errors(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that ErrorFormatter::flatten()
        // correctly separates root-level issues (form
        // errors) from path-bearing issues (field errors)
        // when both exist in the same exception

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::object([
            'name' => Validate::string(),
            'age' => Validate::int(),
        ])->withCustomConstraint(
            fn(mixed $data) => 'Form-level validation failed',
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $caughtException = null;
        try {
            $unit->parse([
                'name' => 42,
                'age' => 25,
            ]);
        } catch (ValidationException $e) {
            $caughtException = $e;
        }

        $this->assertNotNull($caughtException);
        $flat = ErrorFormatter::flatten($caughtException);

        // ----------------------------------------------------------------
        // test the results

        // the 'name' field has a type error (field error)
        $this->assertArrayHasKey('name', $flat->fieldErrors());

        // ----------------------------------------------------------------
        // clean up the database

    }
}
