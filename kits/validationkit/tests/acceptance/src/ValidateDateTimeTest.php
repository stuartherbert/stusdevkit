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
use StusDevKit\ValidationKit\Exceptions\ValidationException;
use StusDevKit\ValidationKit\Tests\Fixtures\CallableTransformer;
use StusDevKit\ValidationKit\Tests\Fixtures\RejectEverythingConstraint;
use StusDevKit\ValidationKit\Validate;
use StusDevKit\ValidationKit\ValidationIssue;

#[TestDox('Validate::dateTime()')]
class ValidateDateTimeTest extends TestCase
{
    // ================================================================
    //
    // Type Checking
    //
    // ----------------------------------------------------------------

    #[TestDox('accepts a DateTimeImmutable value')]
    public function test_accepts_datetime_immutable(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that Validate::dateTime()->parse()
        // accepts a DateTimeImmutable instance and returns
        // it unchanged

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::dateTime();
        $inputDate = new DateTimeImmutable('2026-03-28T12:00:00Z');

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

    #[TestDox('accepts a DateTime value')]
    public function test_accepts_datetime(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that Validate::dateTime()->parse()
        // accepts a DateTime instance and returns it unchanged

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::dateTime();
        $inputDate = new \DateTime('2026-03-28T12:00:00Z');

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
    public static function provideNonDateTimeValues(): array
    {
        return [
            'string' => [ 'not a date' ],
            'int' => [ 42 ],
            'null' => [ null ],
        ];
    }

    #[DataProvider('provideNonDateTimeValues')]
    #[TestDox('rejects non-DateTimeInterface values')]
    public function test_rejects_non_datetime_value(
        mixed $inputValue,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that Validate::dateTime()->parse()
        // throws a ValidationException for non-DateTimeInterface
        // input

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::dateTime();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $this->expectException(ValidationException::class);
        $unit->parse($inputValue);

        // ----------------------------------------------------------------
        // test the results

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

        $minDate = new DateTimeImmutable('2026-01-01T00:00:00Z');
        $unit = Validate::dateTime()->min(date: $minDate);
        $inputDate = new DateTimeImmutable('2026-06-15T12:00:00Z');

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

        $minDate = new DateTimeImmutable('2026-06-01T00:00:00Z');
        $unit = Validate::dateTime()->min(date: $minDate);
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
                    'type'    => 'https://stusdevkit.dev/errors/validation/too_small',
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

        $maxDate = new DateTimeImmutable('2026-12-31T23:59:59Z');
        $unit = Validate::dateTime()->max(date: $maxDate);
        $inputDate = new DateTimeImmutable('2026-06-15T12:00:00Z');

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

        $maxDate = new DateTimeImmutable('2026-06-01T00:00:00Z');
        $unit = Validate::dateTime()->max(date: $maxDate);
        $inputDate = new DateTimeImmutable('2026-12-15T12:00:00Z');

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
                    'type'    => 'https://stusdevkit.dev/errors/validation/too_big',
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
    // Coercion
    //
    // ----------------------------------------------------------------

    #[TestDox('coerce() converts ISO 8601 string to DateTimeImmutable')]
    public function test_coerce_converts_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that coerce() converts an ISO 8601
        // string into a DateTimeImmutable instance

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::dateTime()->coerce();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse('2026-03-28T12:00:00Z');

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(
            DateTimeImmutable::class,
            $actualResult,
        );
        $this->assertSame(
            '2026-03-28T12:00:00+00:00',
            $actualResult->format(DateTimeInterface::ATOM),
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('coerce() converts int timestamp to DateTimeImmutable')]
    public function test_coerce_converts_int_timestamp(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that coerce() converts a Unix
        // timestamp integer into a DateTimeImmutable instance

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::dateTime()->coerce();
        $timestamp = 1774958400; // 2026-03-28T12:00:00Z

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse($timestamp);

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(
            DateTimeImmutable::class,
            $actualResult,
        );
        $this->assertSame(
            $timestamp,
            $actualResult->getTimestamp(),
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

        $unit = Validate::dateTime();

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
                    'type'    => 'https://stusdevkit.dev/errors/validation/invalid_date',
                    'path'    => [],
                    'message' => 'Expected DateTimeInterface,'
                        . ' received string',
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
        // successful ParseResult for valid DateTimeInterface
        // input

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::dateTime();
        $inputDate = new DateTimeImmutable('2026-03-28T12:00:00Z');

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
        // failed ParseResult for non-DateTimeInterface input

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::dateTime();

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

    #[TestDox('withDefault() provides fallback for null')]
    public function test_with_default_provides_fallback(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $fallbackDate = new DateTimeImmutable('2026-01-01T00:00:00Z');
        $unit = Validate::dateTime()->withDefault($fallbackDate);

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

    #[TestDox('withCustomTransform() modifies the validated data')]
    public function test_with_transform_modifies_data(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::dateTime()->withCustomTransform(
            function (mixed $data) {
                /** @var \DateTimeInterface $data */
                return $data->format('Y-m-d');
            },
        );
        $inputDate = new DateTimeImmutable('2026-03-28T12:00:00Z');

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse($inputDate);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('2026-03-28', $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('withTransformer() transforms data via parse()')]
    public function test_with_transformer_transforms_via_parse(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // withTransformer() accepts a ValueTransformer object
        // and adds it to the pipeline

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::dateTime()->withTransformer(
            new CallableTransformer(
                function (mixed $data) {
                    /** @var \DateTimeInterface $data */
                    return $data->format('Y-m-d');
                },
            ),
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse(new DateTimeImmutable('2026-03-28T12:00:00Z'));

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('2026-03-28', $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('withTransformer() transforms data via safeParse()')]
    public function test_with_transformer_transforms_via_safe_parse(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // withTransformer() accepts a ValueTransformer object
        // and adds it to the pipeline

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::dateTime()->withTransformer(
            new CallableTransformer(
                function (mixed $data) {
                    /** @var \DateTimeInterface $data */
                    return $data->format('Y-m-d');
                },
            ),
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->safeParse(new DateTimeImmutable('2026-03-28T12:00:00Z'));

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actualResult->succeeded());
        $this->assertSame('2026-03-28', $actualResult->data());

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('withTransformer() transforms data via encode()')]
    public function test_with_transformer_transforms_via_encode(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // withTransformer() accepts a ValueTransformer object
        // and adds it to the pipeline

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::dateTime()->withTransformer(
            new CallableTransformer(
                function (mixed $data) {
                    /** @var \DateTimeInterface $data */
                    return $data->format('Y-m-d');
                },
            ),
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->encode(new DateTimeImmutable('2026-03-28T12:00:00Z'));

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('2026-03-28', $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('withTransformer() transforms data via safeEncode()')]
    public function test_with_transformer_transforms_via_safe_encode(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // withTransformer() accepts a ValueTransformer object
        // and adds it to the pipeline

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::dateTime()->withTransformer(
            new CallableTransformer(
                function (mixed $data) {
                    /** @var \DateTimeInterface $data */
                    return $data->format('Y-m-d');
                },
            ),
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->safeEncode(new DateTimeImmutable('2026-03-28T12:00:00Z'));

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actualResult->succeeded());
        $this->assertSame('2026-03-28', $actualResult->data());

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('withCustomConstraint() adds custom validation')]
    public function test_with_custom_constraint_adds_custom_validation(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that withCustomConstraint() can
        // reject a value that passes type and constraint
        // checks

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::dateTime()->withCustomConstraint(
            function (mixed $data) {
                /** @var \DateTimeInterface $data */
                return $data->format('N') !== '7'
                    ? null
                    : 'Date must not be a Sunday';
            },
        );

        // 2026-03-29 is a Sunday
        $inputDate = new DateTimeImmutable('2026-03-29T12:00:00Z');

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
                    'type'    => 'https://stusdevkit.dev/errors/validation/custom',
                    'path'    => [],
                    'message' => 'Date must not be a Sunday',
                ],
            ],
            $result->maybeError()->issues()->jsonSerialize(),
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('withPipe() chains to another schema')]
    public function test_with_pipe_chains_schemas(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that withPipe() passes the output
        // of this schema to another schema for further
        // validation

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::dateTime()
            ->withCustomTransform(
                function (mixed $data) {
                    /** @var \DateTimeInterface $data */
                    return $data->format('Y');
                },
            )
            ->withPipe(Validate::string()->min(length: 4));

        $inputDate = new DateTimeImmutable('2026-03-28T12:00:00Z');

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

    #[TestDox('withCatch() provides fallback on validation failure')]
    public function test_with_catch_provides_fallback(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $fallbackDate = new DateTimeImmutable('1970-01-01T00:00:00Z');
        $unit = Validate::dateTime()->withCatch($fallbackDate);

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
        // with 'https://stusdevkit.dev/errors/validation/invalid_date'

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::dateTime(
            error: fn(mixed $data) => new ValidationIssue(
                input: $data,
                path: [],
                message: 'Custom: not a valid date',
                type: 'https://example.com/errors/not-date',
                title: 'Not a date',
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
                    'type'    => 'https://example.com/errors/not-date',
                    'path'    => [],
                    'message' => 'Custom: not a valid date',
                ],
            ],
            $result->maybeError()->issues()->jsonSerialize(),
        );

        $issue = $result->maybeError()->issues()->first();
        $this->assertSame(
            'https://example.com/errors/not-date',
            $issue->type,
        );
        $this->assertSame('Not a date', $issue->title);

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
        // type URI and title from 'https://stusdevkit.dev/errors/validation/invalid_date'

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::dateTime();

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
                    'type'    => 'https://stusdevkit.dev/errors/validation/invalid_date',
                    'path'    => [],
                    'message' => 'Expected DateTimeInterface,'
                        . ' received string',
                ],
            ],
            $result->maybeError()->issues()->jsonSerialize(),
        );

        $issue = $result->maybeError()->issues()->first();
        $this->assertSame(
            'https://stusdevkit.dev/errors/validation/invalid_date',
            $issue->type,
        );
        $this->assertSame('Validation failed', $issue->title);

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // Metadata
    //
    // ----------------------------------------------------------------

    #[TestDox('withDescription() sets the description')]
    public function test_with_description_sets_description(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::dateTime()->withDescription('A birth date');

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeDescription();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('A birth date', $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('withMeta() sets the metadata')]
    public function test_with_meta_sets_metadata(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::dateTime()->withMeta(
            ['format' => 'ISO 8601'],
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
            ['format' => 'ISO 8601'],
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

        $unit = Validate::dateTime()
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
        $this->assertSame(
            [
                [
                    'type'    => 'https://stusdevkit.dev/errors/validation/custom',
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
