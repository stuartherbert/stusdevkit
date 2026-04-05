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

#[TestDox('ErrorFormatter::prettify()')]
class ErrorFormatterPrettifyTest extends TestCase
{
    // ================================================================
    //
    // Basic Output
    //
    // ----------------------------------------------------------------

    #[TestDox('returns a human-readable string')]
    public function test_returns_human_readable_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that ErrorFormatter::prettify()
        // returns a non-empty string describing the
        // validation errors

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
        $actualResult = ErrorFormatter::prettify(
            $caughtException,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertNotEmpty($actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // Header Format
    //
    // ----------------------------------------------------------------

    #[TestDox('single issue header says "1 validation issue"')]
    public function test_single_issue_header(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that when there is exactly one
        // validation issue, the header reads
        // "1 validation issue"

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
        $actualResult = ErrorFormatter::prettify(
            $caughtException,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertStringContainsString(
            '1 validation issue',
            $actualResult,
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('multiple issues header says "N validation issues"')]
    public function test_multiple_issues_header(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that when there are multiple
        // validation issues, the header reads
        // "N validation issues"

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
        $actualResult = ErrorFormatter::prettify(
            $caughtException,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertStringContainsString(
            '2 validation issues',
            $actualResult,
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // Content Format
    //
    // ----------------------------------------------------------------

    #[TestDox('contains field paths and messages')]
    public function test_contains_field_paths_and_messages(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that the prettified output
        // includes both the field path and the error message
        // for each issue

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
            $unit->parse((object) [
                'email' => 42,
            ]);
        } catch (ValidationException $e) {
            $caughtException = $e;
        }

        $this->assertNotNull($caughtException);
        $actualResult = ErrorFormatter::prettify(
            $caughtException,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertStringContainsString('email', $actualResult);
        $this->assertStringContainsString(
            'Expected string',
            $actualResult,
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('single issue format includes the error marker')]
    public function test_single_issue_format(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that each error line includes
        // the error marker character

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
        $actualResult = ErrorFormatter::prettify(
            $caughtException,
        );

        // ----------------------------------------------------------------
        // test the results

        // each error line is prefixed with the cross marker
        $this->assertStringContainsString(
            "\xe2\x9c\x97",
            $actualResult,
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('multiple issues format lists each error separately')]
    public function test_multiple_issues_format(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that when there are multiple
        // validation issues, each one appears on its own
        // line in the output

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
        $actualResult = ErrorFormatter::prettify(
            $caughtException,
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertStringContainsString('name', $actualResult);
        $this->assertStringContainsString('age', $actualResult);

        // each issue is on its own line
        $lines = explode("\n", $actualResult);
        // header + at least 2 error lines
        $this->assertGreaterThanOrEqual(3, count($lines));

        // ----------------------------------------------------------------
        // clean up the database

    }
}
