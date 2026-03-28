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

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use StusDevKit\ValidationKit\Exceptions\ValidationException;
use StusDevKit\ValidationKit\IssueCode;
use StusDevKit\ValidationKit\Tests\Fixtures\RejectEverythingConstraint;
use StusDevKit\ValidationKit\Validate;
use StusDevKit\ValidationKit\ValidationIssue;

#[TestDox('Validate::literal()')]
class ValidateLiteralTest extends TestCase
{
    // ================================================================
    //
    // Type Checking - Accepts Exact Value
    //
    // ----------------------------------------------------------------

    #[TestDox('accepts exact string literal')]
    public function test_accepts_exact_string_literal(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that Validate::literal() accepts
        // a value that exactly matches the expected string

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::literal(value: 'active');

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse('active');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('active', $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('rejects different string value')]
    public function test_rejects_different_string_value(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that Validate::literal() rejects
        // a value that does not match the expected string

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::literal(value: 'active');

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $this->expectException(ValidationException::class);
        $unit->parse('inactive');

        // ----------------------------------------------------------------
        // test the results

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('rejects same value with different type (strict)')]
    public function test_rejects_same_value_different_type(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that Validate::literal() uses
        // strict comparison, so int 42 does not match
        // string '42'

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::literal(value: 42);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $this->expectException(ValidationException::class);
        $unit->parse('42');

        // ----------------------------------------------------------------
        // test the results

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('accepts exact int literal')]
    public function test_accepts_exact_int_literal(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that Validate::literal() works
        // correctly with integer values

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::literal(value: 42);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse(42);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(42, $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('accepts exact bool literal')]
    public function test_accepts_exact_bool_literal(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that Validate::literal() works
        // correctly with boolean values

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::literal(value: true);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse(true);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('rejects wrong bool literal')]
    public function test_rejects_wrong_bool_literal(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that Validate::literal(true)
        // rejects false, and vice versa

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::literal(value: true);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $this->expectException(ValidationException::class);
        $unit->parse(false);

        // ----------------------------------------------------------------
        // test the results

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('accepts exact null literal')]
    public function test_accepts_exact_null_literal(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that Validate::literal(null)
        // accepts null as the expected literal value

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::nullable(Validate::literal(value: null));

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse(null);

        // ----------------------------------------------------------------
        // test the results

        $this->assertNull($actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // parse() and safeParse()
    //
    // ----------------------------------------------------------------

    #[TestDox('parse() throws ValidationException on failure')]
    public function test_parse_throws_on_failure(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that parse() throws a
        // ValidationException with correct issue details
        // when the literal does not match

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::literal(value: 'active');

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $caughtException = null;
        try {
            $unit->parse('inactive');
        } catch (ValidationException $e) {
            $caughtException = $e;
        }

        // ----------------------------------------------------------------
        // test the results

        $this->assertNotNull($caughtException);
        $this->assertCount(1, $caughtException->issues());

        $issue = $caughtException->issues()->first();
        $this->assertSame(IssueCode::InvalidLiteral, $issue->code);
        $this->assertSame('inactive', $issue->input);
        $this->assertSame([], $issue->path);
        $this->assertStringContainsString(
            'Expected "active"',
            $issue->message,
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('safeParse() returns successful result for valid input')]
    public function test_safe_parse_returns_success(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that safeParse() returns a
        // successful ParseResult when the literal matches

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::literal(value: 'active');

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse('active');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->succeeded());
        $this->assertFalse($result->failed());
        $this->assertSame('active', $result->data());
        $this->assertNull($result->maybeError());

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('safeParse() returns failed result for invalid input')]
    public function test_safe_parse_returns_failure(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that safeParse() returns a
        // failed ParseResult when the literal does not match

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::literal(value: 'active');

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse('inactive');

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($result->succeeded());
        $this->assertTrue($result->failed());
        $this->assertNull($result->maybeData());
        $this->assertInstanceOf(
            ValidationException::class,
            $result->maybeError(),
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // Default
    //
    // ----------------------------------------------------------------

    #[TestDox('default() provides fallback for null')]
    public function test_default_provides_fallback(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::literal(value: 'active')
            ->default('active');

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse(null);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('active', $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // Transform, Refine, Pipe, Catch
    //
    // ----------------------------------------------------------------

    #[TestDox('transform() modifies the validated data')]
    public function test_transform_modifies_data(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::literal(value: 'active')->transform(
            function (mixed $data) {
                /** @var string $data */
                return strtoupper($data);
            },
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse('active');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('ACTIVE', $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('refine() adds custom validation')]
    public function test_refine_adds_custom_validation(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that refine() can add custom
        // validation logic after the literal check passes

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        // a contrived example: the literal matches but we
        // add a refinement that always fails
        $unit = Validate::literal(value: 'active')->refine(
            fn(mixed $data) => false,
            'Custom refinement failed',
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse('active');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $issue = $result->maybeError()->issues()->first();
        $this->assertSame(IssueCode::Custom, $issue->code);
        $this->assertSame(
            'Custom refinement failed',
            $issue->message,
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('pipe() chains to another schema')]
    public function test_pipe_chains_schemas(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that pipe() passes the output
        // of the literal schema to another schema for
        // further validation

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::literal(value: 'active')
            ->transform(function (mixed $data) {
                /** @var string $data */
                return strlen($data);
            })
            ->pipe(Validate::int()->gte(value: 3));

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse('active');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(6, $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('catch() provides fallback on validation failure')]
    public function test_catch_provides_fallback(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::literal(value: 'active')
            ->catch('unknown');

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse('inactive');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('unknown', $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // Custom Error Callbacks
    //
    // ----------------------------------------------------------------

    #[TestDox('custom type-check error callback is used on failure')]
    public function test_custom_type_check_error_callback(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a custom error callback
        // on the literal check produces a custom
        // ValidationIssue

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::literal(
            value: 'active',
            error: fn(mixed $data) => new ValidationIssue(
                code: IssueCode::InvalidLiteral,
                input: $data,
                path: [],
                message: 'Custom: must be active',
                type: 'https://example.com/errors/not-active',
                title: 'Not active',
            ),
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse('inactive');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $issue = $result->maybeError()->issues()->first();
        $this->assertSame('Custom: must be active', $issue->message);
        $this->assertSame(
            'https://example.com/errors/not-active',
            $issue->type,
        );
        $this->assertSame('Not active', $issue->title);

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // Issue Fields
    //
    // ----------------------------------------------------------------

    #[TestDox('issues carry default type URI and title')]
    public function test_issues_carry_default_type_and_title(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that ValidationIssues created by
        // default error callbacks carry the correct default
        // type URI and title from IssueCode::InvalidLiteral

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::literal(value: 'active');

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse('inactive');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $issue = $result->maybeError()->issues()->first();
        $this->assertSame(
            'https://stusdevkit.dev/errors/validation/invalid_literal',
            $issue->type,
        );
        $this->assertSame('Invalid literal value', $issue->title);
        $this->assertSame(IssueCode::InvalidLiteral, $issue->code);

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // Metadata
    //
    // ----------------------------------------------------------------

    #[TestDox('describe() sets the description')]
    public function test_describe_sets_description(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::literal(value: 'active')
            ->describe('Account status');

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeDescription();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('Account status', $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('meta() sets the metadata')]
    public function test_meta_sets_metadata(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::literal(value: 'active')
            ->meta(['label' => 'Status']);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->metadata();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(['label' => 'Status'], $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // Custom Constraints
    //
    // ----------------------------------------------------------------

    #[TestDox('withConstraint() adds custom constraint to pipeline')]
    public function test_with_constraint_adds_custom_constraint(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that withConstraint() correctly
        // wires a custom ValidationConstraint into the
        // schema's validation pipeline

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::literal(value: 'hello')
            ->withConstraint(new RejectEverythingConstraint());

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse('hello');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $issue = $result->error()->issues()->first();
        $this->assertSame(IssueCode::Custom, $issue->code);
        $this->assertSame(
            'rejected by custom constraint',
            $issue->message,
        );

        // ----------------------------------------------------------------
        // clean up the database

    }
}
