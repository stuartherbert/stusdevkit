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
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use StusDevKit\DateTimeKit\When;
use StusDevKit\ValidationKit\Exceptions\ValidationException;
use StusDevKit\ValidationKit\IssueCode;
use StusDevKit\ValidationKit\Tests\Fixtures\RejectEverythingConstraint;
use StusDevKit\ValidationKit\Validate;
use StusDevKit\ValidationKit\ValidationIssue;

#[TestDox('Validate::when()')]
class ValidateWhenTest extends TestCase
{
    // ================================================================
    //
    // Type Checking
    //
    // ----------------------------------------------------------------

    #[TestDox('accepts a When instance')]
    public function test_accepts_when_instance(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that Validate::when()->parse()
        // accepts a When instance and returns it unchanged

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::when();
        $inputDate = When::from('2026-01-15');

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse($inputDate);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($inputDate, $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    /**
     * @return array<string, array<mixed>>
     */
    public static function provideNonWhenValues(): array
    {
        return [
            'string' => [ 'not a date' ],
            'int' => [ 42 ],
            'null' => [ null ],
        ];
    }

    #[DataProvider('provideNonWhenValues')]
    #[TestDox('rejects non-When values')]
    public function test_rejects_non_when_value(
        mixed $inputValue,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that Validate::when()->safeParse()
        // reports an InvalidDate issue for non-When input

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::when();

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
        $this->assertSame(
            [
                [
                    'code'    => IssueCode::InvalidDate,
                    'path'    => [],
                    'message' => 'Expected When, received '
                        . get_debug_type($inputValue),
                ],
            ],
            $result->maybeError()->issues()->jsonSerialize(),
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('rejects a plain DateTimeImmutable')]
    public function test_rejects_plain_datetime_immutable(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that Validate::when() requires
        // a When instance specifically, not just any
        // DateTimeImmutable

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::when();
        $inputDate = new DateTimeImmutable('2026-01-15T12:00:00Z');

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse($inputDate);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $this->assertSame(
            [
                [
                    'code'    => IssueCode::InvalidDate,
                    'path'    => [],
                    'message' => 'Expected When, received '
                        . 'DateTimeImmutable',
                ],
            ],
            $result->maybeError()->issues()->jsonSerialize(),
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // Coercion
    //
    // ----------------------------------------------------------------

    #[TestDox('coerce() converts ISO 8601 string to When')]
    public function test_coerce_converts_iso_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that coerce() converts an ISO 8601
        // string into a When instance

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::when()->coerce();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse('2026-01-15T12:00:00Z');

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(When::class, $actualResult);
        $this->assertSame(
            '2026-01-15T12:00:00+00:00',
            $actualResult->format(DateTimeInterface::ATOM),
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('coerce() converts date string to When')]
    public function test_coerce_converts_date_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that coerce() converts a plain
        // date string into a When instance

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::when()->coerce();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse('2026-01-15');

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(When::class, $actualResult);
        $this->assertStringStartsWith(
            '2026-01-15',
            $actualResult->format(DateTimeInterface::ATOM),
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('coerce() converts integer timestamp to When')]
    public function test_coerce_converts_int_timestamp(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that coerce() converts a Unix
        // timestamp integer into a When instance

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::when()->coerce();
        $timestamp = 1700000000;

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse($timestamp);

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(When::class, $actualResult);
        $this->assertSame(
            $timestamp,
            $actualResult->getTimestamp(),
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('coerce() converts DateTimeImmutable to When')]
    public function test_coerce_converts_datetime_immutable(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that coerce() converts a plain
        // DateTimeImmutable into a When instance

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::when()->coerce();
        $inputDate = new DateTimeImmutable('2026-01-15T12:00:00Z');

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse($inputDate);

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(When::class, $actualResult);
        $this->assertSame(
            '2026-01-15T12:00:00+00:00',
            $actualResult->format(DateTimeInterface::ATOM),
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // Constraints
    //
    // ----------------------------------------------------------------

    #[TestDox('min() accepts dates on or after the minimum')]
    public function test_min_accepts_valid_date(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that min() passes when the date
        // is on or after the minimum date

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $minDate = When::from('2026-01-01T00:00:00Z');
        $unit = Validate::when()->min(date: $minDate);
        $inputDate = When::from('2026-06-15T12:00:00Z');

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse($inputDate);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($inputDate, $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('min() rejects dates before the minimum')]
    public function test_min_rejects_early_date(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that min() rejects dates before
        // the minimum and reports TooSmall

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $minDate = When::from('2026-06-01T00:00:00Z');
        $unit = Validate::when()->min(date: $minDate);
        $inputDate = When::from('2026-01-15T12:00:00Z');

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse($inputDate);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $this->assertSame(
            [
                [
                    'code'    => IssueCode::TooSmall,
                    'path'    => [],
                    'message' => 'Date must be on or after '
                        . '2026-06-01T00:00:00+00:00',
                ],
            ],
            $result->maybeError()->issues()->jsonSerialize(),
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('max() accepts dates on or before the maximum')]
    public function test_max_accepts_valid_date(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that max() passes when the date
        // is on or before the maximum date

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $maxDate = When::from('2026-12-31T23:59:59Z');
        $unit = Validate::when()->max(date: $maxDate);
        $inputDate = When::from('2026-06-15T12:00:00Z');

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse($inputDate);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($inputDate, $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('max() rejects dates after the maximum')]
    public function test_max_rejects_late_date(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that max() rejects dates after
        // the maximum and reports TooBig

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $maxDate = When::from('2026-06-01T00:00:00Z');
        $unit = Validate::when()->max(date: $maxDate);
        $inputDate = When::from('2026-12-15T12:00:00Z');

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse($inputDate);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $this->assertSame(
            [
                [
                    'code'    => IssueCode::TooBig,
                    'path'    => [],
                    'message' => 'Date must be on or before '
                        . '2026-06-01T00:00:00+00:00',
                ],
            ],
            $result->maybeError()->issues()->jsonSerialize(),
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

        $unit = Validate::when();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $caughtException = null;
        try {
            $unit->parse('not a date');
        } catch (ValidationException $e) {
            $caughtException = $e;
        }

        // ----------------------------------------------------------------
        // test the results

        $this->assertNotNull($caughtException);
        $this->assertCount(1, $caughtException->issues());
        $this->assertSame(
            [
                [
                    'code'    => IssueCode::InvalidDate,
                    'path'    => [],
                    'message' => 'Expected When, received string',
                ],
            ],
            $caughtException->issues()->jsonSerialize(),
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
        // successful ParseResult for valid When input

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::when();
        $inputDate = When::from('2026-01-15T12:00:00Z');

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse($inputDate);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->succeeded());
        $this->assertFalse($result->failed());
        $this->assertSame($inputDate, $result->data());
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
        // failed ParseResult for non-When input

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::when();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse('not a date');

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

        // this test proves that default() returns the
        // fallback value when the input is null

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $fallbackDate = When::from('2026-01-01T00:00:00Z');
        $unit = Validate::when()->default($fallbackDate);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse(null);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($fallbackDate, $actualResult);

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

        // this test proves that transform() can modify
        // the validated When value

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::when()->transform(
            function (mixed $data) {
                /** @var When $data */
                return $data->format('Y-m-d');
            },
        );
        $inputDate = When::from('2026-01-15T12:00:00Z');

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse($inputDate);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('2026-01-15', $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('refine() adds custom validation')]
    public function test_refine_adds_custom_validation(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that refine() can reject a value
        // that passes type and constraint checks

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::when()->refine(
            function (mixed $data) {
                /** @var When $data */
                return $data->format('N') !== '7';
            },
            'Date must not be a Sunday',
        );

        // 2026-03-29 is a Sunday
        $inputDate = When::from('2026-03-29T12:00:00Z');

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse($inputDate);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $this->assertSame(
            [
                [
                    'code'    => IssueCode::Custom,
                    'path'    => [],
                    'message' => 'Date must not be a Sunday',
                ],
            ],
            $result->maybeError()->issues()->jsonSerialize(),
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

        $unit = Validate::when()
            ->transform(
                function (mixed $data) {
                    /** @var When $data */
                    return $data->format('Y');
                },
            )
            ->pipe(Validate::string()->min(length: 4));

        $inputDate = When::from('2026-01-15T12:00:00Z');

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse($inputDate);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('2026', $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('catch() provides fallback on validation failure')]
    public function test_catch_provides_fallback(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that catch() returns the fallback
        // value when validation fails

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $fallbackDate = When::from('1970-01-01T00:00:00Z');
        $unit = Validate::when()->catch($fallbackDate);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse('not a date');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($fallbackDate, $actualResult);

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
        // with IssueCode::InvalidDate

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::when(
            error: fn(mixed $data) => new ValidationIssue(
                code: IssueCode::InvalidDate,
                input: $data,
                path: [],
                message: 'Custom: not a valid When',
                type: 'https://example.com/errors/not-when',
                title: 'Not a When',
            ),
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse('not a date');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $this->assertSame(
            [
                [
                    'code'    => IssueCode::InvalidDate,
                    'path'    => [],
                    'message' => 'Custom: not a valid When',
                ],
            ],
            $result->maybeError()->issues()->jsonSerialize(),
        );

        $issue = $result->maybeError()->issues()->first();
        $this->assertSame(
            'https://example.com/errors/not-when',
            $issue->type,
        );
        $this->assertSame('Not a When', $issue->title);

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
        // type URI and title from IssueCode::InvalidDate

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::when();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse('not a date');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $this->assertSame(
            [
                [
                    'code'    => IssueCode::InvalidDate,
                    'path'    => [],
                    'message' => 'Expected When, received string',
                ],
            ],
            $result->maybeError()->issues()->jsonSerialize(),
        );

        $issue = $result->maybeError()->issues()->first();
        $this->assertSame(
            'https://stusdevkit.dev/errors/validation/invalid_date',
            $issue->type,
        );
        $this->assertSame('Invalid date', $issue->title);

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

        // this test proves that describe() stores a
        // description on the schema

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::when()->describe('An event date');

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeDescription();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('An event date', $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('meta() sets the metadata')]
    public function test_meta_sets_metadata(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that meta() stores arbitrary
        // metadata on the schema

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::when()->meta(
            ['format' => 'When'],
        );

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
            ['format' => 'When'],
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

        $unit = Validate::when()
            ->withConstraint(new RejectEverythingConstraint());

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse(When::from('2026-01-15'));

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $this->assertSame(
            [
                [
                    'code'    => IssueCode::Custom,
                    'path'    => [],
                    'message' => 'rejected by custom constraint',
                ],
            ],
            $result->error()->issues()->jsonSerialize(),
        );

        // ----------------------------------------------------------------
        // clean up the database

    }
}
