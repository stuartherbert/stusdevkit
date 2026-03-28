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
use StusDevKit\ValidationKit\Validate;
use StusDevKit\ValidationKit\ValidationIssue;

#[TestDox('Validate::int()')]
class ValidateIntTest extends TestCase
{
    // ================================================================
    //
    // Type Checking
    //
    // ----------------------------------------------------------------

    #[TestDox('accepts an int value')]
    public function test_accepts_int_value(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that Validate::int()->parse()
        // accepts an int value and returns it unchanged

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::int();

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

    /**
     * @return array<string, array<mixed>>
     */
    public static function provideNonIntValues(): array
    {
        return [
            'float' => [ 3.14 ],
            'string' => [ 'hello' ],
            'bool true' => [ true ],
            'bool false' => [ false ],
            'null' => [ null ],
            'array' => [ [1, 2] ],
        ];
    }

    #[DataProvider('provideNonIntValues')]
    #[TestDox('rejects non-int values')]
    public function test_rejects_non_int_value(
        mixed $inputValue,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that Validate::int()->parse()
        // throws a ValidationException for non-int input

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::int();

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

        $unit = Validate::int();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $caughtException = null;
        try {
            $unit->parse('not an int');
        } catch (ValidationException $e) {
            $caughtException = $e;
        }

        // ----------------------------------------------------------------
        // test the results

        $this->assertNotNull($caughtException);
        $this->assertCount(1, $caughtException->issues());

        $issue = $caughtException->issues()[0];
        $this->assertSame(IssueCode::InvalidType, $issue->code);
        $this->assertSame('not an int', $issue->input);
        $this->assertSame([], $issue->path);
        $this->assertStringContainsString(
            'Expected int',
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
        // successful ParseResult for valid int input

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::int();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse(42);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->succeeded());
        $this->assertFalse($result->failed());
        $this->assertSame(42, $result->data());
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
        // failed ParseResult for non-int input

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::int();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse('not an int');

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
    // Constraints
    //
    // ----------------------------------------------------------------

    #[TestDox('gt() accepts values above the threshold')]
    public function test_gt_accepts_above_threshold(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that gt() passes when the
        // value is strictly greater than the threshold

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::int()->gt(value: 5);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse(6);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(6, $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('gt() rejects values at or below the threshold')]
    public function test_gt_rejects_at_or_below_threshold(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that gt() rejects values that
        // are equal to or less than the threshold and
        // reports TooSmall

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::int()->gt(value: 5);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse(5);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $issue = $result->maybeError()->issues()[0];
        $this->assertSame(IssueCode::TooSmall, $issue->code);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('gte() accepts values at or above the threshold')]
    public function test_gte_accepts_at_or_above_threshold(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that gte() passes when the
        // value is greater than or equal to the threshold

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::int()->gte(value: 5);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse(5);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(5, $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('gte() rejects values below the threshold')]
    public function test_gte_rejects_below_threshold(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that gte() rejects values
        // that are below the threshold and reports TooSmall

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::int()->gte(value: 5);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse(4);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $issue = $result->maybeError()->issues()[0];
        $this->assertSame(IssueCode::TooSmall, $issue->code);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('lt() accepts values below the threshold')]
    public function test_lt_accepts_below_threshold(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that lt() passes when the
        // value is strictly less than the threshold

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::int()->lt(value: 10);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse(9);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(9, $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('lt() rejects values at or above the threshold')]
    public function test_lt_rejects_at_or_above_threshold(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that lt() rejects values that
        // are equal to or greater than the threshold and
        // reports TooBig

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::int()->lt(value: 10);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse(10);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $issue = $result->maybeError()->issues()[0];
        $this->assertSame(IssueCode::TooBig, $issue->code);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('lte() accepts values at or below the threshold')]
    public function test_lte_accepts_at_or_below_threshold(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that lte() passes when the
        // value is less than or equal to the threshold

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::int()->lte(value: 10);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse(10);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(10, $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('lte() rejects values above the threshold')]
    public function test_lte_rejects_above_threshold(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that lte() rejects values
        // that are above the threshold and reports TooBig

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::int()->lte(value: 10);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse(11);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $issue = $result->maybeError()->issues()[0];
        $this->assertSame(IssueCode::TooBig, $issue->code);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('positive() accepts positive values')]
    public function test_positive_accepts_positive(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that positive() passes when
        // the value is greater than zero

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::int()->positive();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse(1);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(1, $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('positive() rejects zero and negative values')]
    public function test_positive_rejects_zero_and_negative(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that positive() rejects zero
        // (positive means strictly > 0)

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::int()->positive();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse(0);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $issue = $result->maybeError()->issues()[0];
        $this->assertSame(IssueCode::TooSmall, $issue->code);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('negative() accepts negative values')]
    public function test_negative_accepts_negative(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that negative() passes when
        // the value is less than zero

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::int()->negative();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse(-1);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(-1, $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('negative() rejects zero and positive values')]
    public function test_negative_rejects_zero_and_positive(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that negative() rejects zero
        // (negative means strictly < 0)

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::int()->negative();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse(0);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $issue = $result->maybeError()->issues()[0];
        $this->assertSame(IssueCode::TooBig, $issue->code);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('multipleOf() accepts multiples of the given value')]
    public function test_multiple_of_accepts_multiples(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that multipleOf() passes when
        // the value is evenly divisible by the divisor

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::int()->multipleOf(value: 3);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse(9);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(9, $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('multipleOf() rejects non-multiples')]
    public function test_multiple_of_rejects_non_multiples(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that multipleOf() rejects values
        // that are not evenly divisible by the divisor and
        // reports NotMultipleOf

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::int()->multipleOf(value: 3);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse(7);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $issue = $result->maybeError()->issues()[0];
        $this->assertSame(IssueCode::NotMultipleOf, $issue->code);

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // Nullable, Optional, Default
    //
    // ----------------------------------------------------------------

    #[TestDox('nullable() allows null')]
    public function test_nullable_allows_null(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::int()->nullable();

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

    #[TestDox('optional() allows null')]
    public function test_optional_allows_null(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::int()->optional();

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

    #[TestDox('default() provides fallback for null')]
    public function test_default_provides_fallback(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::int()->default(0);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse(null);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(0, $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // Coercion
    //
    // ----------------------------------------------------------------

    /**
     * @return array<string, array<mixed>>
     */
    public static function provideCoercibleValues(): array
    {
        return [
            'string "42"' => [ '42', 42 ],
            'float 3.0' => [ 3.0, 3 ],
            'bool true' => [ true, 1 ],
            'bool false' => [ false, 0 ],
        ];
    }

    #[DataProvider('provideCoercibleValues')]
    #[TestDox('coerce() converts compatible values to int')]
    public function test_coerce_converts_to_int(
        mixed $inputValue,
        int $expectedResult,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that coerce() converts
        // compatible values to integers before validation

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::int()->coerce();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse($inputValue);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expectedResult, $actualResult);

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

        $unit = Validate::int()->transform(
            function (mixed $data) {
                /** @var int $data */
                return $data * 2;
            },
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse(5);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(10, $actualResult);

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

        $unit = Validate::int()->refine(
            fn(mixed $data) => $data !== 13,
            'Unlucky number',
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse(13);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $issue = $result->maybeError()->issues()[0];
        $this->assertSame(IssueCode::Custom, $issue->code);
        $this->assertSame('Unlucky number', $issue->message);

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

        $unit = Validate::int()
            ->transform(function (mixed $data) {
                /** @var int $data */
                return (string) $data;
            })
            ->pipe(Validate::string()->min(length: 2));

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse(42);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('42', $actualResult);

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

        $unit = Validate::int()->catch(0);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse('not a number');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(0, $actualResult);

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

        $unit = Validate::int(
            error: fn(mixed $data) => new ValidationIssue(
                code: IssueCode::InvalidType,
                input: $data,
                path: [],
                message: 'Custom: not an integer',
                type: 'https://example.com/errors/not-int',
                title: 'Not an integer',
            ),
        );

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
        $issue = $result->maybeError()->issues()[0];
        $this->assertSame('Custom: not an integer', $issue->message);
        $this->assertSame(
            'https://example.com/errors/not-int',
            $issue->type,
        );
        $this->assertSame('Not an integer', $issue->title);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('custom constraint error callback is used on failure')]
    public function test_custom_constraint_error_callback(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a custom error callback
        // on a constraint produces a custom ValidationIssue

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::int()->gte(
            value: 18,
            error: fn(mixed $data) => new ValidationIssue(
                code: IssueCode::TooSmall,
                input: $data,
                path: [],
                message: 'Must be at least 18',
                type: 'https://example.com/errors/too-young',
                title: 'Too young',
            ),
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse(16);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $issue = $result->maybeError()->issues()[0];
        $this->assertSame('Must be at least 18', $issue->message);
        $this->assertSame(
            'https://example.com/errors/too-young',
            $issue->type,
        );

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
        // type URI and title from IssueCode

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::int();

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
        $issue = $result->maybeError()->issues()[0];
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

        $unit = Validate::int()->describe('A user age');

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeDescription();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('A user age', $actualResult);

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

        $unit = Validate::int()->meta(['label' => 'Age']);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->metadata();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(['label' => 'Age'], $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }
}
