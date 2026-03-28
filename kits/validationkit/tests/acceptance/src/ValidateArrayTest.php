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

#[TestDox('Validate::array()')]
class ValidateArrayTest extends TestCase
{
    // ================================================================
    //
    // Type Checking
    //
    // ----------------------------------------------------------------

    #[TestDox('accepts an array value')]
    public function test_accepts_array_value(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that Validate::array()->parse()
        // accepts a valid array and returns it unchanged

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::array(Validate::string());

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse(['a', 'b', 'c']);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(['a', 'b', 'c'], $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    /**
     * @return array<string, array<mixed>>
     */
    public static function provideNonArrayValues(): array
    {
        return [
            'string' => [ 'hello' ],
            'int' => [ 42 ],
            'null' => [ null ],
            'bool true' => [ true ],
            'float' => [ 3.14 ],
        ];
    }

    #[DataProvider('provideNonArrayValues')]
    #[TestDox('rejects non-array values')]
    public function test_rejects_non_array_value(
        mixed $inputValue,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that Validate::array()->parse()
        // throws a ValidationException for non-array input

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::array(Validate::string());

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
    // Element Validation
    //
    // ----------------------------------------------------------------

    #[TestDox('validates each element against the element schema')]
    public function test_validates_each_element(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that each element in the array
        // is validated against the element schema

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::array(Validate::string());

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse(['a', 'b']);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(['a', 'b'], $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('element error produces indexed path')]
    public function test_element_error_produces_indexed_path(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that when an element fails
        // validation, the issue path includes the element
        // index in bracket notation

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::array(Validate::string());

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse(['a', 42, 'c']);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $issue = $result->maybeError()->issues()[0];
        $this->assertSame(IssueCode::InvalidType, $issue->code);
        $this->assertSame('[1]', $issue->pathAsString());

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('multiple element errors produce separate indexed paths')]
    public function test_multiple_element_errors(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that when multiple elements fail,
        // each produces its own issue with the correct index

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::array(Validate::string());

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse([42, 'b', 99]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $issues = $result->maybeError()->issues();
        $this->assertCount(2, $issues);
        $this->assertSame('[0]', $issues[0]->pathAsString());
        $this->assertSame('[2]', $issues[1]->pathAsString());

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('nested array of objects produces correct path')]
    public function test_nested_array_of_objects_path(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that when an array contains objects
        // and a nested field fails, the path includes both
        // the array index and the field name

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::array(
            Validate::object([
                'name' => Validate::string(),
            ]),
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse([
            ['name' => 'Alice'],
            ['name' => 42],
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $issue = $result->maybeError()->issues()[0];
        $this->assertSame('[1].name', $issue->pathAsString());

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // Length Constraints
    //
    // ----------------------------------------------------------------

    #[TestDox('min() accepts arrays at or above minimum length')]
    public function test_min_accepts_valid_length(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that min() passes when the array
        // length meets the minimum

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::array(Validate::string())->min(length: 2);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse(['a', 'b']);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(['a', 'b'], $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('min() rejects arrays below minimum length')]
    public function test_min_rejects_short_array(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that min() rejects arrays with
        // fewer elements than the minimum and reports
        // TooSmall

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::array(Validate::string())->min(length: 3);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse(['a']);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $issue = $result->maybeError()->issues()[0];
        $this->assertSame(IssueCode::TooSmall, $issue->code);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('max() accepts arrays at or below maximum length')]
    public function test_max_accepts_valid_length(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that max() passes when the array
        // length is within the maximum

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::array(Validate::string())->max(length: 3);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse(['a', 'b', 'c']);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(['a', 'b', 'c'], $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('max() rejects arrays above maximum length')]
    public function test_max_rejects_long_array(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that max() rejects arrays with
        // more elements than the maximum and reports TooBig

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::array(Validate::string())->max(length: 2);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse(['a', 'b', 'c']);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $issue = $result->maybeError()->issues()[0];
        $this->assertSame(IssueCode::TooBig, $issue->code);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('length() accepts arrays of exact length')]
    public function test_length_accepts_exact(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that length() passes when the
        // array has exactly the required number of elements

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::array(Validate::string())->length(length: 2);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse(['a', 'b']);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(['a', 'b'], $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('length() rejects arrays of different length')]
    public function test_length_rejects_wrong_length(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that length() rejects arrays that
        // do not have exactly the required number of elements

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::array(Validate::string())->length(length: 3);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse(['a']);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $issue = $result->maybeError()->issues()[0];
        $this->assertSame(IssueCode::TooSmall, $issue->code);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('nonempty() rejects empty arrays')]
    public function test_nonempty_rejects_empty(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that nonempty() rejects an empty
        // array and reports TooSmall

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::array(Validate::string())->nonempty();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse([]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $issue = $result->maybeError()->issues()[0];
        $this->assertSame(IssueCode::TooSmall, $issue->code);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('nonempty() accepts non-empty arrays')]
    public function test_nonempty_accepts_non_empty(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that nonempty() passes when the
        // array has at least one element

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::array(Validate::string())->nonempty();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse(['a']);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(['a'], $actualResult);

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

        $unit = Validate::array(Validate::string());

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

        // ----------------------------------------------------------------
        // test the results

        $this->assertNotNull($caughtException);
        $this->assertCount(1, $caughtException->issues());

        $issue = $caughtException->issues()[0];
        $this->assertSame(IssueCode::InvalidType, $issue->code);
        $this->assertSame(42, $issue->input);
        $this->assertSame([], $issue->path);
        $this->assertStringContainsString(
            'Expected array',
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
        // successful ParseResult for valid array input

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::array(Validate::string());

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse(['a', 'b']);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->succeeded());
        $this->assertFalse($result->failed());
        $this->assertSame(['a', 'b'], $result->data());
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
        // failed ParseResult for non-array input

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::array(Validate::string());

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse(42);

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

        $unit = Validate::array(Validate::string())->nullable();

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

        $unit = Validate::array(Validate::string())->optional();

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

        $unit = Validate::array(Validate::string())->default(['x']);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse(null);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(['x'], $actualResult);

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

        $unit = Validate::array(Validate::string())->transform(
            function (mixed $data) {
                /** @var array<string> $data */
                return array_map('strtoupper', $data);
            },
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse(['hello', 'world']);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(['HELLO', 'WORLD'], $actualResult);

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

        $unit = Validate::array(Validate::string())->refine(
            fn(mixed $data) => count((array) $data) % 2 === 0,
            'Array must have an even number of elements',
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse(['a', 'b', 'c']);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $issue = $result->maybeError()->issues()[0];
        $this->assertSame(IssueCode::Custom, $issue->code);
        $this->assertSame(
            'Array must have an even number of elements',
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

        $unit = Validate::array(Validate::string())
            ->transform(fn(mixed $data) => count((array) $data))
            ->pipe(Validate::int()->gte(value: 2));

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse(['a', 'b', 'c']);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(3, $actualResult);

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

        $unit = Validate::array(Validate::string())->catch([]);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse(42);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame([], $actualResult);

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

        $unit = Validate::array(
            Validate::string(),
            error: fn(mixed $data) => new ValidationIssue(
                code: IssueCode::InvalidType,
                input: $data,
                path: [],
                message: 'Custom: not an array',
                type: 'https://example.com/errors/not-array',
                title: 'Not an array',
            ),
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse(42);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $issue = $result->maybeError()->issues()[0];
        $this->assertSame('Custom: not an array', $issue->message);
        $this->assertSame(
            'https://example.com/errors/not-array',
            $issue->type,
        );
        $this->assertSame('Not an array', $issue->title);

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

        $unit = Validate::array(Validate::string())->min(
            length: 3,
            error: fn(mixed $data) => new ValidationIssue(
                code: IssueCode::TooSmall,
                input: $data,
                path: [],
                message: 'Need at least 3 items',
                type: 'https://example.com/errors/too-few-items',
                title: 'Too few items',
            ),
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse(['a']);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $issue = $result->maybeError()->issues()[0];
        $this->assertSame('Need at least 3 items', $issue->message);
        $this->assertSame(
            'https://example.com/errors/too-few-items',
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

        $unit = Validate::array(Validate::string());

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse(42);

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

        $unit = Validate::array(Validate::string())
            ->describe('A list of tags');

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeDescription();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('A list of tags', $actualResult);

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

        $unit = Validate::array(Validate::string())
            ->meta(['label' => 'Tags']);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->metadata();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(['label' => 'Tags'], $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }
}
