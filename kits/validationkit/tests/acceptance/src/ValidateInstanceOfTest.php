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

use DateTimeImmutable;
use DateTimeInterface;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use StusDevKit\ValidationKit\Exceptions\ValidationException;
use StusDevKit\ValidationKit\IssueCode;
use StusDevKit\ValidationKit\Tests\Fixtures\RejectEverythingConstraint;
use StusDevKit\ValidationKit\Validate;
use StusDevKit\ValidationKit\ValidationIssue;

#[TestDox('Validate::instanceOf()')]
class ValidateInstanceOfTest extends TestCase
{
    // ================================================================
    //
    // Type Checking
    //
    // ----------------------------------------------------------------

    #[TestDox('accepts an instance of the given class')]
    public function test_accepts_instance_of_class(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that Validate::instanceOf()->parse()
        // accepts an object that is an instance of the given
        // class or interface

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::instanceOf(DateTimeInterface::class);
        $inputValue = new DateTimeImmutable();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse($inputValue);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($inputValue, $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('rejects a non-object value')]
    public function test_rejects_non_object(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that Validate::instanceOf()->parse()
        // throws a ValidationException when given a non-object
        // value such as a string

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::instanceOf(DateTimeInterface::class);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse('not an object');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $issue = $result->maybeError()->issues()->first();
        $this->assertSame(IssueCode::InvalidType, $issue->code);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('rejects an instance of the wrong class')]
    public function test_rejects_wrong_class(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that Validate::instanceOf()->parse()
        // throws a ValidationException when given an object
        // that does not implement the required class

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::instanceOf(DateTimeInterface::class);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse(new \stdClass());

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $issue = $result->maybeError()->issues()->first();
        $this->assertSame(IssueCode::InvalidType, $issue->code);
        $this->assertStringContainsString(
            'Expected instance of DateTimeInterface',
            $issue->message,
        );

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
        // when validation fails

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::instanceOf(DateTimeInterface::class);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $caughtException = null;
        try {
            $unit->parse('not an object');
        } catch (ValidationException $e) {
            $caughtException = $e;
        }

        // ----------------------------------------------------------------
        // test the results

        $this->assertNotNull($caughtException);
        $this->assertCount(1, $caughtException->issues());

        $issue = $caughtException->issues()->first();
        $this->assertSame(IssueCode::InvalidType, $issue->code);
        $this->assertSame('not an object', $issue->input);
        $this->assertSame([], $issue->path);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('safeParse() returns successful result for valid input')]
    public function test_safe_parse_returns_success(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that safeParse() returns a
        // successful ParseResult for a valid instance

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::instanceOf(DateTimeInterface::class);
        $inputValue = new DateTimeImmutable();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse($inputValue);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->succeeded());
        $this->assertFalse($result->failed());
        $this->assertSame($inputValue, $result->data());
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
        // failed ParseResult for a non-matching instance

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::instanceOf(DateTimeInterface::class);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse(new \stdClass());

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

        $fallbackValue = new DateTimeImmutable(
            '2026-01-01T00:00:00Z',
        );
        $unit = Validate::instanceOf(
            DateTimeInterface::class,
        )->default($fallbackValue);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse(null);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($fallbackValue, $actualResult);

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

        $unit = Validate::instanceOf(
            DateTimeInterface::class,
        )->transform(
            function (mixed $data) {
                /** @var \DateTimeInterface $data */
                return $data->format('Y-m-d');
            },
        );
        $inputValue = new DateTimeImmutable('2026-03-28T12:00:00Z');

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse($inputValue);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('2026-03-28', $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('refine() adds custom validation')]
    public function test_refine_adds_custom_validation(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that refine() can reject a value
        // that passes the instanceOf type check

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::instanceOf(
            DateTimeInterface::class,
        )->refine(
            function (mixed $data) {
                /** @var \DateTimeInterface $data */
                return $data->getTimestamp() > 0;
            },
            'Date must be after Unix epoch',
        );

        $inputValue = new DateTimeImmutable('1969-12-31T23:59:59Z');

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse($inputValue);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $issue = $result->maybeError()->issues()->first();
        $this->assertSame(IssueCode::Custom, $issue->code);
        $this->assertSame(
            'Date must be after Unix epoch',
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
        // of this schema to another schema for further
        // validation

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::instanceOf(DateTimeInterface::class)
            ->transform(
                function (mixed $data) {
                    /** @var \DateTimeInterface $data */
                    return $data->getTimestamp();
                },
            )
            ->pipe(Validate::int()->gte(value: 0));

        $inputValue = new DateTimeImmutable('2026-03-28T12:00:00Z');

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse($inputValue);

        // ----------------------------------------------------------------
        // test the results

        $this->assertIsInt($actualResult);
        $this->assertGreaterThan(0, $actualResult);

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

        $fallbackValue = new DateTimeImmutable(
            '1970-01-01T00:00:00Z',
        );
        $unit = Validate::instanceOf(
            DateTimeInterface::class,
        )->catch($fallbackValue);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse('not an object');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($fallbackValue, $actualResult);

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
        // on the type check produces a custom ValidationIssue

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::instanceOf(
            DateTimeInterface::class,
            error: fn(mixed $data) => new ValidationIssue(
                code: IssueCode::InvalidType,
                input: $data,
                path: [],
                message: 'Custom: must be a date',
                type: 'https://example.com/errors/not-date',
                title: 'Not a date object',
            ),
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse('not an object');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $issue = $result->maybeError()->issues()->first();
        $this->assertSame(
            'Custom: must be a date',
            $issue->message,
        );
        $this->assertSame(
            'https://example.com/errors/not-date',
            $issue->type,
        );
        $this->assertSame('Not a date object', $issue->title);

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
        // type URI and title from IssueCode::InvalidType

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::instanceOf(DateTimeInterface::class);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse('not an object');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $issue = $result->maybeError()->issues()->first();
        $this->assertSame(
            'https://stusdevkit.dev/errors/validation/invalid_type',
            $issue->type,
        );
        $this->assertSame('Invalid type', $issue->title);
        $this->assertSame(IssueCode::InvalidType, $issue->code);

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

        $unit = Validate::instanceOf(
            DateTimeInterface::class,
        )->describe('A date-time value');

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeDescription();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('A date-time value', $actualResult);

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

        $unit = Validate::instanceOf(
            DateTimeInterface::class,
        )->meta(['label' => 'Timestamp']);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->metadata();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            ['label' => 'Timestamp'],
            $actualResult,
        );

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

        $unit = Validate::instanceOf(DateTimeImmutable::class)
            ->withConstraint(new RejectEverythingConstraint());

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse(new DateTimeImmutable());

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
