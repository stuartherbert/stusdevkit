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
use StusDevKit\ValidationKit\Tests\Fixtures\CallableTransformer;
use StusDevKit\ValidationKit\Tests\Fixtures\RejectEverythingConstraint;
use StusDevKit\ValidationKit\Validate;
use StusDevKit\ValidationKit\ValidationIssue;

#[TestDox('Validate::string()')]
class ValidateStringTest extends TestCase
{
    // ================================================================
    //
    // Type Checking
    //
    // ----------------------------------------------------------------

    #[TestDox('accepts a string value')]
    public function test_accepts_string_value(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that Validate::string()->parse()
        // accepts a string value and returns it unchanged

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

        $actualResult = $unit->parse('hello');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('hello', $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    /**
     * @return array<string, array<mixed>>
     */
    public static function provideNonStringValues(): array
    {
        return [
            'int' => [ 42 ],
            'float' => [ 3.14 ],
            'bool true' => [ true ],
            'bool false' => [ false ],
            'array' => [ ['a', 'b'] ],
            'null' => [ null ],
        ];
    }

    #[DataProvider('provideNonStringValues')]
    #[TestDox('rejects non-string values')]
    public function test_rejects_non_string_value(
        mixed $inputValue,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that Validate::string()->parse()
        // throws a ValidationException for non-string input

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
            $unit->parse($inputValue);
        } catch (ValidationException $e) {
            $caughtException = $e;
        }

        // ----------------------------------------------------------------
        // test the results

        $this->assertNotNull($caughtException);
        $this->assertSame(
            [
                [
                    'type'    => 'https://stusdevkit.dev/errors/validation/invalid_type',
                    'path'    => [],
                    'message' => 'Expected string, received '
                        . get_debug_type($inputValue),
                ],
            ],
            $caughtException->issues()->jsonSerialize(),
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

        // ----------------------------------------------------------------
        // test the results

        $this->assertNotNull($caughtException);
        $this->assertSame(
            [
                [
                    'type'    => 'https://stusdevkit.dev/errors/validation/invalid_type',
                    'path'    => [],
                    'message' => 'Expected string, received int',
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
        // successful ParseResult for valid string input

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

        $result = $unit->safeParse('hello');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->succeeded());
        $this->assertFalse($result->failed());
        $this->assertSame('hello', $result->data());
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
        // failed ParseResult for non-string input

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
        $this->assertSame(
            [
                [
                    'type'    => 'https://stusdevkit.dev/errors/validation/invalid_type',
                    'path'    => [],
                    'message' => 'Expected string, received int',
                ],
            ],
            $result->maybeError()->issues()->jsonSerialize(),
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // Length Constraints
    //
    // ----------------------------------------------------------------

    #[TestDox('min() accepts strings at or above minimum length')]
    public function test_min_accepts_valid_length(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that min() passes when the
        // string length meets the minimum

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::string()->min(length: 3);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse('abc');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('abc', $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('min() rejects strings below minimum length')]
    public function test_min_rejects_short_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that min() rejects strings
        // shorter than the minimum and reports TooSmall

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::string()->min(length: 3);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse('ab');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $this->assertSame(
            [
                [
                    'type'    => 'https://stusdevkit.dev/errors/validation/too_small',
                    'path'    => [],
                    'message' => 'String must be at least 3 characters',
                ],
            ],
            $result->maybeError()->issues()->jsonSerialize(),
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('max() accepts strings at or below maximum length')]
    public function test_max_accepts_valid_length(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that max() passes when the
        // string length is within the maximum

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::string()->max(length: 5);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse('hello');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('hello', $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('max() rejects strings above maximum length')]
    public function test_max_rejects_long_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that max() rejects strings
        // longer than the maximum and reports TooBig

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::string()->max(length: 3);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse('toolong');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $this->assertSame(
            [
                [
                    'type'    => 'https://stusdevkit.dev/errors/validation/too_big',
                    'path'    => [],
                    'message' => 'String must be at most 3 characters',
                ],
            ],
            $result->maybeError()->issues()->jsonSerialize(),
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('length() accepts strings of exact length')]
    public function test_length_accepts_exact(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that length() passes when the
        // string is exactly the required length

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::string()->length(length: 5);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse('hello');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('hello', $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('length() rejects strings of different length')]
    public function test_length_rejects_wrong_length(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that length() rejects strings
        // that are not exactly the required length

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::string()->length(length: 5);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse('hi');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $this->assertSame(
            [
                [
                    'type'    => 'https://stusdevkit.dev/errors/validation/too_small',
                    'path'    => [],
                    'message' => 'String must be exactly 5 characters',
                ],
            ],
            $result->maybeError()->issues()->jsonSerialize(),
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // Format Constraints
    //
    // ----------------------------------------------------------------

    #[TestDox('email() accepts a valid email address')]
    public function test_email_accepts_valid(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::string()->email();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse('user@example.com');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('user@example.com', $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('email() rejects an invalid email address')]
    public function test_email_rejects_invalid(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::string()->email();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse('not-an-email');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $this->assertSame(
            [
                [
                    'type'    => 'https://stusdevkit.dev/errors/validation/invalid_string',
                    'path'    => [],
                    'message' => 'Invalid email address',
                ],
            ],
            $result->maybeError()->issues()->jsonSerialize(),
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('url() accepts a valid URL')]
    public function test_url_accepts_valid(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::string()->url();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse('https://example.com');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('https://example.com', $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('url() rejects an invalid URL')]
    public function test_url_rejects_invalid(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::string()->url();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse('not a url');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $this->assertSame(
            [
                [
                    'type'    => 'https://stusdevkit.dev/errors/validation/invalid_string',
                    'path'    => [],
                    'message' => 'Invalid URL',
                ],
            ],
            $result->maybeError()->issues()->jsonSerialize(),
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('uuid() accepts a valid UUID')]
    public function test_uuid_accepts_valid(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::string()->uuid();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse(
            '550e8400-e29b-41d4-a716-446655440000',
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            '550e8400-e29b-41d4-a716-446655440000',
            $actualResult,
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('uuid() rejects an invalid UUID')]
    public function test_uuid_rejects_invalid(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::string()->uuid();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse('not-a-uuid');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $this->assertSame(
            [
                [
                    'type'    => 'https://stusdevkit.dev/errors/validation/invalid_string',
                    'path'    => [],
                    'message' => 'Invalid UUID',
                ],
            ],
            $result->maybeError()->issues()->jsonSerialize(),
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('regex() accepts a matching string')]
    public function test_regex_accepts_matching(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::string()->regex(pattern: '/^[a-z]+$/');

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse('hello');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('hello', $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('regex() rejects a non-matching string')]
    public function test_regex_rejects_non_matching(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::string()->regex(pattern: '/^[a-z]+$/');

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse('HELLO');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $this->assertSame(
            [
                [
                    'type'    => 'https://stusdevkit.dev/errors/validation/invalid_string',
                    'path'    => [],
                    'message' => 'String does not match pattern /^[a-z]+$/',
                ],
            ],
            $result->maybeError()->issues()->jsonSerialize(),
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('includes() accepts a string containing the needle')]
    public function test_includes_accepts_containing(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::string()->includes(needle: 'world');

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse('hello world');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('hello world', $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('includes() rejects a string not containing the needle')]
    public function test_includes_rejects_missing(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::string()->includes(needle: 'world');

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse('hello there');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $this->assertSame(
            [
                [
                    'type'    => 'https://stusdevkit.dev/errors/validation/invalid_string',
                    'path'    => [],
                    'message' => 'String must contain "world"',
                ],
            ],
            $result->maybeError()->issues()->jsonSerialize(),
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('startsWith() accepts a string with the correct prefix')]
    public function test_starts_with_accepts(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::string()->startsWith(prefix: 'hello');

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse('hello world');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('hello world', $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('startsWith() rejects a string without the prefix')]
    public function test_starts_with_rejects(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::string()->startsWith(prefix: 'hello');

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse('world hello');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $this->assertSame(
            [
                [
                    'type'    => 'https://stusdevkit.dev/errors/validation/invalid_string',
                    'path'    => [],
                    'message' => 'String must start with "hello"',
                ],
            ],
            $result->maybeError()->issues()->jsonSerialize(),
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('endsWith() accepts a string with the correct suffix')]
    public function test_ends_with_accepts(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::string()->endsWith(suffix: 'world');

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse('hello world');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('hello world', $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('endsWith() rejects a string without the suffix')]
    public function test_ends_with_rejects(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::string()->endsWith(suffix: 'world');

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse('hello there');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $this->assertSame(
            [
                [
                    'type'    => 'https://stusdevkit.dev/errors/validation/invalid_string',
                    'path'    => [],
                    'message' => 'String must end with "world"',
                ],
            ],
            $result->maybeError()->issues()->jsonSerialize(),
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // Transforms
    //
    // ----------------------------------------------------------------

    #[TestDox('applyTrim() trims whitespace before validation')]
    public function test_apply_trim(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that applyTrim() removes leading
        // and trailing whitespace before constraint checks

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::string()->applyTrim()->min(length: 5);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse('  hello  ');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('hello', $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('applyToLowerCase() converts to lower case')]
    public function test_apply_to_lower_case(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::string()->applyToLowerCase();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse('HELLO');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('hello', $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('applyToUpperCase() converts to upper case')]
    public function test_apply_to_upper_case(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::string()->applyToUpperCase();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse('hello');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('HELLO', $actualResult);

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

        $unit = Validate::string()->withDefault('fallback');

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse(null);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('fallback', $actualResult);

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
            'int' => [ 42, '42' ],
            'float' => [ 3.14, '3.14' ],
            'bool true' => [ true, 'true' ],
            'bool false' => [ false, 'false' ],
        ];
    }

    #[DataProvider('provideCoercibleValues')]
    #[TestDox('coerce() converts non-string values to strings')]
    public function test_coerce_converts_to_string(
        mixed $inputValue,
        string $expectedResult,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that coerce() converts
        // non-string values to strings before validation

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::string()->coerce();

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

    #[TestDox('withCustomTransform() modifies the validated data')]
    public function test_with_transform_modifies_data(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::string()->withCustomTransform(
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

        $actualResult = $unit->parse('hello');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('HELLO', $actualResult);

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

        $unit = Validate::string()->withTransformer(
            new CallableTransformer(
                function (mixed $data) {
                    /** @var string $data */
                    return strtoupper($data);
                },
            ),
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse('hello');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('HELLO', $actualResult);

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

        $unit = Validate::string()->withTransformer(
            new CallableTransformer(
                function (mixed $data) {
                    /** @var string $data */
                    return strtoupper($data);
                },
            ),
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->safeParse('hello');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actualResult->succeeded());
        $this->assertSame('HELLO', $actualResult->data());

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

        $unit = Validate::string()->withTransformer(
            new CallableTransformer(
                function (mixed $data) {
                    /** @var string $data */
                    return strtoupper($data);
                },
            ),
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->encode('hello');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('HELLO', $actualResult);

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

        $unit = Validate::string()->withTransformer(
            new CallableTransformer(
                function (mixed $data) {
                    /** @var string $data */
                    return strtoupper($data);
                },
            ),
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->safeEncode('hello');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actualResult->succeeded());
        $this->assertSame('HELLO', $actualResult->data());

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

        $unit = Validate::string()->withCustomConstraint(
            fn(mixed $data) => $data !== 'forbidden'
                ? null
                : 'Value is forbidden',
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse('forbidden');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $this->assertSame(
            [
                [
                    'type'    => 'https://stusdevkit.dev/errors/validation/custom',
                    'path'    => [],
                    'message' => 'Value is forbidden',
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

        $unit = Validate::string()
            ->withCustomTransform(function (mixed $data) {
                /** @var string $data */
                return strlen($data);
            })
            ->withPipe(Validate::int()->gte(value: 3));

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse('hello');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(5, $actualResult);

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

        $unit = Validate::string()->withCatch('default');

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse(42);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('default', $actualResult);

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

        $unit = Validate::string(
            error: fn(mixed $data) => new ValidationIssue(
                input: $data,
                path: [],
                message: 'Custom: not a string',
                type: 'https://example.com/errors/not-string',
                title: 'Not a string',
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
        $this->assertSame(
            [
                [
                    'type'    => 'https://example.com/errors/not-string',
                    'path'    => [],
                    'message' => 'Custom: not a string',
                ],
            ],
            $result->maybeError()->issues()->jsonSerialize(),
        );

        // type and title are RFC 9457 fields not included
        // in jsonSerialize(), so check them via first()
        $issue = $result->maybeError()->issues()->first();
        $this->assertSame(
            'https://example.com/errors/not-string',
            $issue->type,
        );
        $this->assertSame('Not a string', $issue->title);

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

        $unit = Validate::string()->min(
            length: 5,
            error: fn(mixed $data) => new ValidationIssue(
                input: $data,
                path: [],
                message: 'Name is too short',
                type: 'https://example.com/errors/name-too-short',
                title: 'Name too short',
            ),
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse('ab');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $this->assertSame(
            [
                [
                    'type'    => 'https://example.com/errors/name-too-short',
                    'path'    => [],
                    'message' => 'Name is too short',
                ],
            ],
            $result->maybeError()->issues()->jsonSerialize(),
        );

        // type is an RFC 9457 field not included in
        // jsonSerialize(), so check it via first()
        $issue = $result->maybeError()->issues()->first();
        $this->assertSame(
            'https://example.com/errors/name-too-short',
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
        // type URI and title from the issue type

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

        $result = $unit->safeParse(42);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $this->assertSame(
            [
                [
                    'type'    => 'https://stusdevkit.dev/errors/validation/invalid_type',
                    'path'    => [],
                    'message' => 'Expected string, received int',
                ],
            ],
            $result->maybeError()->issues()->jsonSerialize(),
        );

        // type and title are RFC 9457 fields not included
        // in jsonSerialize(), so check them via first()
        $issue = $result->maybeError()->issues()->first();
        $this->assertSame(
            'https://stusdevkit.dev/errors/validation/invalid_type',
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

        $unit = Validate::string()->withDescription('A user name');

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeDescription();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('A user name', $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('withMetadata() sets the metadata')]
    public function test_with_metadata_sets_metadata(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::string()->withMetadata(['label' => 'Name']);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->getMetadata();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(['label' => 'Name'], $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // Custom Constraints
    //
    // ----------------------------------------------------------------

    // ================================================================
    //
    // date
    //
    // ----------------------------------------------------------------

    #[TestDox('date() accepts a valid date')]
    public function test_date_accepts_valid_date(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that date() accepts a string
        // containing a valid YYYY-MM-DD date

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::string()->date();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse('2024-03-15');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('2024-03-15', $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('date() rejects an invalid date format')]
    public function test_date_rejects_invalid_format(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that date() rejects a string
        // that is not in the YYYY-MM-DD format

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::string()->date();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse('15-03-2024');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $this->assertSame(
            [
                [
                    'type'    => 'https://stusdevkit.dev/errors/validation/invalid_string',
                    'path'    => [],
                    'message' => 'Invalid date',
                ],
            ],
            $result->maybeError()->issues()->jsonSerialize(),
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('date() rejects an invalid calendar date')]
    public function test_date_rejects_invalid_calendar_date(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that date() rejects a string
        // that looks like YYYY-MM-DD but is not a real
        // calendar date

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::string()->date();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse('2024-02-30');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $this->assertSame(
            [
                [
                    'type'    => 'https://stusdevkit.dev/errors/validation/invalid_string',
                    'path'    => [],
                    'message' => 'Invalid date',
                ],
            ],
            $result->maybeError()->issues()->jsonSerialize(),
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // time
    //
    // ----------------------------------------------------------------

    #[TestDox('time() accepts a basic time')]
    public function test_time_accepts_basic_time(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that time() accepts a valid
        // HH:MM:SS time string

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::string()->time();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse('14:30:00');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('14:30:00', $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('time() accepts fractional seconds')]
    public function test_time_accepts_fractional_seconds(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that time() accepts a time
        // string with fractional seconds

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::string()->time();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse('14:30:00.123');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('14:30:00.123', $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('time() accepts UTC timezone')]
    public function test_time_accepts_utc_timezone(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that time() accepts a time
        // string with the Z UTC timezone suffix

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::string()->time();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse('14:30:00Z');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('14:30:00Z', $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('time() accepts offset timezone')]
    public function test_time_accepts_offset_timezone(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that time() accepts a time
        // string with a numeric timezone offset

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::string()->time();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse('14:30:00+05:30');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('14:30:00+05:30', $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('time() rejects an invalid time')]
    public function test_time_rejects_invalid_time(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that time() rejects a time
        // string with an out-of-range hour value

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::string()->time();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse('25:00:00');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $this->assertSame(
            [
                [
                    'type'    => 'https://stusdevkit.dev/errors/validation/invalid_string',
                    'path'    => [],
                    'message' => 'Invalid time',
                ],
            ],
            $result->maybeError()->issues()->jsonSerialize(),
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('time() rejects a non-time string')]
    public function test_time_rejects_non_time_string(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that time() rejects a string
        // that is not a time at all

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::string()->time();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse('not-a-time');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $this->assertSame(
            [
                [
                    'type'    => 'https://stusdevkit.dev/errors/validation/invalid_string',
                    'path'    => [],
                    'message' => 'Invalid time',
                ],
            ],
            $result->maybeError()->issues()->jsonSerialize(),
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // duration
    //
    // ----------------------------------------------------------------

    #[TestDox('duration() accepts a full duration')]
    public function test_duration_accepts_full_duration(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that duration() accepts a full
        // ISO 8601 duration with date and time components

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::string()->duration();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse('P1Y2M3DT4H5M6S');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('P1Y2M3DT4H5M6S', $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('duration() accepts a date-only duration')]
    public function test_duration_accepts_date_only(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that duration() accepts an
        // ISO 8601 duration with only date components

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::string()->duration();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse('P1Y2M3D');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('P1Y2M3D', $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('duration() accepts a time-only duration')]
    public function test_duration_accepts_time_only(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that duration() accepts an
        // ISO 8601 duration with only time components

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::string()->duration();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse('PT1H30M');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('PT1H30M', $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('duration() accepts weeks')]
    public function test_duration_accepts_weeks(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that duration() accepts an
        // ISO 8601 duration using the weeks designator

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::string()->duration();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse('P1W');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('P1W', $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('duration() rejects an empty period')]
    public function test_duration_rejects_empty_period(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that duration() rejects a bare
        // P designator with no date or time components

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::string()->duration();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse('P');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $this->assertSame(
            [
                [
                    'type'    => 'https://stusdevkit.dev/errors/validation/invalid_string',
                    'path'    => [],
                    'message' => 'Invalid duration',
                ],
            ],
            $result->maybeError()->issues()->jsonSerialize(),
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('duration() rejects an empty time')]
    public function test_duration_rejects_empty_time(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that duration() rejects a
        // duration with T but no time components after it

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::string()->duration();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse('PT');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $this->assertSame(
            [
                [
                    'type'    => 'https://stusdevkit.dev/errors/validation/invalid_string',
                    'path'    => [],
                    'message' => 'Invalid duration',
                ],
            ],
            $result->maybeError()->issues()->jsonSerialize(),
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('duration() rejects a non-duration string')]
    public function test_duration_rejects_non_duration(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that duration() rejects a
        // string that is not a duration at all

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::string()->duration();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse('not-a-duration');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $this->assertSame(
            [
                [
                    'type'    => 'https://stusdevkit.dev/errors/validation/invalid_string',
                    'path'    => [],
                    'message' => 'Invalid duration',
                ],
            ],
            $result->maybeError()->issues()->jsonSerialize(),
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // dateTime
    //
    // ----------------------------------------------------------------

    /**
     * @return array<string, array{string}>
     */
    public static function provideValidDateTimes(): array
    {
        return [
            'UTC Z'              => ['2024-03-15T14:30:00Z'],
            'positive offset'    => ['2024-03-15T14:30:00+05:30'],
            'negative offset'    => ['2024-03-15T14:30:00-08:00'],
            'fractional seconds' => ['2024-03-15T14:30:00.123456Z'],
            'lowercase t'        => ['2024-03-15t14:30:00Z'],
            'lowercase z'        => ['2024-03-15T14:30:00z'],
            'midnight'           => ['2024-01-01T00:00:00Z'],
            'end of day'         => ['2024-12-31T23:59:59Z'],
        ];
    }

    #[DataProvider('provideValidDateTimes')]
    #[TestDox('dateTime() accepts valid RFC 3339 date-times')]
    public function test_date_time_accepts_valid(
        string $inputValue,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that dateTime() accepts valid
        // RFC 3339 date-time strings in various formats

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::string()->dateTime();

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

    /**
     * @return array<string, array{string}>
     */
    public static function provideInvalidDateTimes(): array
    {
        return [
            'date only'          => ['2024-03-15'],
            'missing timezone'   => ['2024-03-15T14:30:00'],
            'invalid calendar'   => ['2024-02-30T14:30:00Z'],
            'not a date-time'    => ['not-a-date-time'],
            'month 13'           => ['2024-13-01T00:00:00Z'],
            'day 32'             => ['2024-01-32T00:00:00Z'],
        ];
    }

    #[DataProvider('provideInvalidDateTimes')]
    #[TestDox('dateTime() rejects invalid date-time strings')]
    public function test_date_time_rejects_invalid(
        string $inputValue,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that dateTime() rejects strings
        // that are not valid RFC 3339 date-times

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::string()->dateTime();

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
                    'type'    => 'https://stusdevkit.dev/errors/validation/invalid_string',
                    'path'    => [],
                    'message' => 'Invalid date-time',
                ],
            ],
            $result->maybeError()->issues()->jsonSerialize(),
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // hostname
    //
    // ----------------------------------------------------------------

    #[TestDox('hostname() accepts a simple hostname')]
    public function test_hostname_accepts_simple_hostname(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that hostname() accepts a
        // simple two-label hostname

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::string()->hostname();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse('example.com');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('example.com', $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('hostname() accepts a subdomain hostname')]
    public function test_hostname_accepts_subdomain(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that hostname() accepts a
        // hostname with multiple subdomain labels

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::string()->hostname();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse('sub.example.co.uk');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('sub.example.co.uk', $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('hostname() accepts a single label hostname')]
    public function test_hostname_accepts_single_label(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that hostname() accepts a
        // single-label hostname like localhost

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::string()->hostname();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse('localhost');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('localhost', $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('hostname() rejects a hostname with a leading hyphen')]
    public function test_hostname_rejects_leading_hyphen(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that hostname() rejects a
        // hostname where a label starts with a hyphen

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::string()->hostname();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse('-invalid.com');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $this->assertSame(
            [
                [
                    'type'    => 'https://stusdevkit.dev/errors/validation/invalid_string',
                    'path'    => [],
                    'message' => 'Invalid hostname',
                ],
            ],
            $result->maybeError()->issues()->jsonSerialize(),
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('hostname() rejects a hostname with a trailing hyphen')]
    public function test_hostname_rejects_trailing_hyphen(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that hostname() rejects a
        // hostname where a label ends with a hyphen

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::string()->hostname();

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse('invalid-.com');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $this->assertSame(
            [
                [
                    'type'    => 'https://stusdevkit.dev/errors/validation/invalid_string',
                    'path'    => [],
                    'message' => 'Invalid hostname',
                ],
            ],
            $result->maybeError()->issues()->jsonSerialize(),
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('hostname() rejects a hostname that is too long')]
    public function test_hostname_rejects_too_long(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that hostname() rejects a
        // hostname that exceeds the 253-character limit

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::string()->hostname();

        // build a hostname of 254 characters by repeating
        // labels separated by dots
        $longHostname = str_repeat('a', 63) . '.'
            . str_repeat('b', 63) . '.'
            . str_repeat('c', 63) . '.'
            . str_repeat('d', 62);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        $this->assertSame(254, strlen($longHostname));

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse($longHostname);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $this->assertSame(
            [
                [
                    'type'    => 'https://stusdevkit.dev/errors/validation/invalid_string',
                    'path'    => [],
                    'message' => 'Invalid hostname',
                ],
            ],
            $result->maybeError()->issues()->jsonSerialize(),
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // uriReference
    //
    // ----------------------------------------------------------------

    /**
     * @return array<string, array{string}>
     */
    public static function provideValidUriReferences(): array
    {
        return [
            'absolute URI'      => ['https://example.com/path'],
            'relative path'     => ['/path/to/resource'],
            'relative segment'  => ['../other'],
            'query only'        => ['?query=1'],
            'fragment only'     => ['#fragment'],
            'empty string'      => [''],
        ];
    }

    #[DataProvider('provideValidUriReferences')]
    #[TestDox('uriReference() accepts valid URI references')]
    public function test_uri_reference_accepts_valid(
        string $inputValue,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that uriReference() accepts
        // valid URIs and relative references per RFC 3986

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::string()->uriReference();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse($inputValue);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($inputValue, $actualResult);
    }

    #[TestDox('uriReference() rejects malformed URI')]
    public function test_uri_reference_rejects_malformed(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that uriReference() rejects a
        // structurally invalid URI reference

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::string()->uriReference();

        // ----------------------------------------------------------------
        // perform the change

        // a scheme-like prefix with port :// and spaces
        // causes parse_url to fail
        $result = $unit->safeParse('http:///');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
    }

    // ================================================================
    //
    // idnEmail
    //
    // ----------------------------------------------------------------

    /**
     * @return array<string, array{string}>
     */
    public static function provideValidIdnEmails(): array
    {
        return [
            'ascii email'     => ['user@example.com'],
            'unicode local'   => ['üser@example.com'],
        ];
    }

    #[DataProvider('provideValidIdnEmails')]
    #[TestDox('idnEmail() accepts valid internationalised emails')]
    public function test_idn_email_accepts_valid(
        string $inputValue,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that idnEmail() accepts valid
        // internationalised email addresses per RFC 6531

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::string()->idnEmail();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse($inputValue);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($inputValue, $actualResult);
    }

    /**
     * @return array<string, array{string}>
     */
    public static function provideInvalidIdnEmails(): array
    {
        return [
            'no at sign'    => ['not-an-email'],
            'double at'     => ['user@@example.com'],
            'empty'         => [''],
        ];
    }

    #[DataProvider('provideInvalidIdnEmails')]
    #[TestDox('idnEmail() rejects invalid emails')]
    public function test_idn_email_rejects_invalid(
        string $inputValue,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that idnEmail() rejects strings
        // that are not valid email addresses

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::string()->idnEmail();

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse($inputValue);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
    }

    // ================================================================
    //
    // idnHostname
    //
    // ----------------------------------------------------------------

    /**
     * @return array<string, array{string}>
     */
    public static function provideValidIdnHostnames(): array
    {
        return [
            'ascii hostname'   => ['example.com'],
            'unicode hostname' => ['münchen.de'],
            'chinese hostname' => ['中文.com'],
        ];
    }

    #[DataProvider('provideValidIdnHostnames')]
    #[TestDox('idnHostname() accepts valid internationalised hostnames')]
    public function test_idn_hostname_accepts_valid(
        string $inputValue,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that idnHostname() accepts
        // valid internationalised hostnames per RFC 5890

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::string()->idnHostname();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse($inputValue);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($inputValue, $actualResult);
    }

    /**
     * @return array<string, array{string}>
     */
    public static function provideInvalidIdnHostnames(): array
    {
        return [
            'empty string'   => [''],
            'just a dot'     => ['.'],
            'leading hyphen' => ['-example.com'],
        ];
    }

    #[DataProvider('provideInvalidIdnHostnames')]
    #[TestDox('idnHostname() rejects invalid hostnames')]
    public function test_idn_hostname_rejects_invalid(
        string $inputValue,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that idnHostname() rejects
        // strings that are not valid hostnames

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::string()->idnHostname();

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse($inputValue);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
    }

    // ================================================================
    //
    // iri
    //
    // ----------------------------------------------------------------

    /**
     * @return array<string, array{string}>
     */
    public static function provideValidIris(): array
    {
        return [
            'https URI'     => ['https://example.com/path'],
            'unicode path'  => ['https://example.com/données'],
            'unicode host'  => ['https://münchen.de/'],
            'mailto'        => ['mailto:user@example.com'],
        ];
    }

    #[DataProvider('provideValidIris')]
    #[TestDox('iri() accepts valid IRIs')]
    public function test_iri_accepts_valid(
        string $inputValue,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that iri() accepts valid
        // absolute IRIs per RFC 3987

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::string()->iri();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse($inputValue);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($inputValue, $actualResult);
    }

    /**
     * @return array<string, array{string}>
     */
    public static function provideInvalidIris(): array
    {
        return [
            'relative path'  => ['/just/a/path'],
            'no scheme'      => ['example.com'],
            'empty string'   => [''],
        ];
    }

    #[DataProvider('provideInvalidIris')]
    #[TestDox('iri() rejects non-absolute IRIs')]
    public function test_iri_rejects_invalid(
        string $inputValue,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that iri() rejects strings
        // that are not absolute IRIs (missing scheme)

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::string()->iri();

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse($inputValue);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
    }

    // ================================================================
    //
    // iriReference
    //
    // ----------------------------------------------------------------

    /**
     * @return array<string, array{string}>
     */
    public static function provideValidIriReferences(): array
    {
        return [
            'absolute IRI'    => ['https://example.com/données'],
            'relative path'   => ['/chemin/données'],
            'fragment only'   => ['#fragment'],
            'empty string'    => [''],
        ];
    }

    #[DataProvider('provideValidIriReferences')]
    #[TestDox('iriReference() accepts valid IRI references')]
    public function test_iri_reference_accepts_valid(
        string $inputValue,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that iriReference() accepts
        // both absolute IRIs and relative references

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::string()->iriReference();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse($inputValue);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($inputValue, $actualResult);
    }

    #[TestDox('iriReference() rejects malformed IRI')]
    public function test_iri_reference_rejects_malformed(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that iriReference() rejects a
        // structurally invalid IRI reference

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::string()->iriReference();

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse('http:///');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
    }

    // ================================================================
    //
    // uriTemplate
    //
    // ----------------------------------------------------------------

    /**
     * @return array<string, array{string}>
     */
    public static function provideValidUriTemplates(): array
    {
        return [
            'simple variable'    => ['/users/{id}'],
            'multiple variables' => ['/users/{id}/posts/{postId}'],
            'operator prefix'    => ['/search{?query,lang}'],
            'fragment expansion' => ['/page{#section}'],
            'path expansion'     => ['{/path,file}'],
            'no variables'       => ['/static/path'],
            'explode modifier'   => ['/users/{ids*}'],
            'prefix modifier'    => ['/users/{name:5}'],
        ];
    }

    #[DataProvider('provideValidUriTemplates')]
    #[TestDox('uriTemplate() accepts valid URI templates')]
    public function test_uri_template_accepts_valid(
        string $inputValue,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that uriTemplate() accepts valid
        // RFC 6570 URI templates

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::string()->uriTemplate();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse($inputValue);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($inputValue, $actualResult);
    }

    /**
     * @return array<string, array{string}>
     */
    public static function provideInvalidUriTemplates(): array
    {
        return [
            'unclosed brace'   => ['/users/{id'],
            'empty expression' => ['/users/{}'],
            'nested braces'    => ['/users/{{id}}'],
        ];
    }

    #[DataProvider('provideInvalidUriTemplates')]
    #[TestDox('uriTemplate() rejects invalid URI templates')]
    public function test_uri_template_rejects_invalid(
        string $inputValue,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that uriTemplate() rejects
        // strings that are not valid URI templates

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::string()->uriTemplate();

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse($inputValue);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
    }

    // ================================================================
    //
    // jsonPointer
    //
    // ----------------------------------------------------------------

    /**
     * @return array<string, array{string}>
     */
    public static function provideValidJsonPointers(): array
    {
        return [
            'empty (root)'    => [''],
            'single token'    => ['/foo'],
            'nested tokens'   => ['/foo/bar/0'],
            'escaped tilde'   => ['/foo~0bar'],
            'escaped slash'   => ['/foo~1bar'],
            'numeric index'   => ['/0'],
        ];
    }

    #[DataProvider('provideValidJsonPointers')]
    #[TestDox('jsonPointer() accepts valid JSON Pointers')]
    public function test_json_pointer_accepts_valid(
        string $inputValue,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that jsonPointer() accepts
        // valid RFC 6901 JSON Pointers

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::string()->jsonPointer();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse($inputValue);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($inputValue, $actualResult);
    }

    /**
     * @return array<string, array{string}>
     */
    public static function provideInvalidJsonPointers(): array
    {
        return [
            'no leading slash'  => ['foo/bar'],
            'bare tilde'        => ['/foo~bar'],
            'tilde wrong digit' => ['/foo~2bar'],
        ];
    }

    #[DataProvider('provideInvalidJsonPointers')]
    #[TestDox('jsonPointer() rejects invalid JSON Pointers')]
    public function test_json_pointer_rejects_invalid(
        string $inputValue,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that jsonPointer() rejects
        // strings that are not valid JSON Pointers

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::string()->jsonPointer();

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse($inputValue);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
    }

    // ================================================================
    //
    // relativeJsonPointer
    //
    // ----------------------------------------------------------------

    /**
     * @return array<string, array{string}>
     */
    public static function provideValidRelativeJsonPointers(): array
    {
        return [
            'zero with pointer' => ['0/foo'],
            'index only'        => ['0#'],
            'up one level'      => ['1/bar'],
            'up two levels'     => ['2/baz/0'],
            'zero root'         => ['0'],
        ];
    }

    #[DataProvider('provideValidRelativeJsonPointers')]
    #[TestDox('relativeJsonPointer() accepts valid relative JSON Pointers')]
    public function test_relative_json_pointer_accepts_valid(
        string $inputValue,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that relativeJsonPointer()
        // accepts valid relative JSON Pointers

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::string()->relativeJsonPointer();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse($inputValue);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($inputValue, $actualResult);
    }

    /**
     * @return array<string, array{string}>
     */
    public static function provideInvalidRelativeJsonPointers(): array
    {
        return [
            'missing integer'     => ['/foo'],
            'negative integer'    => ['-1/foo'],
            'leading zero'        => ['01/foo'],
            'just text'           => ['foo'],
        ];
    }

    #[DataProvider('provideInvalidRelativeJsonPointers')]
    #[TestDox('relativeJsonPointer() rejects invalid values')]
    public function test_relative_json_pointer_rejects_invalid(
        string $inputValue,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that relativeJsonPointer()
        // rejects strings that are not valid relative
        // JSON Pointers

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::string()->relativeJsonPointer();

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse($inputValue);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
    }

    // ================================================================
    //
    // isRegex
    //
    // ----------------------------------------------------------------

    /**
     * @return array<string, array{string}>
     */
    public static function provideValidRegexPatterns(): array
    {
        return [
            'simple literal'     => ['abc'],
            'character class'    => ['[a-z]+'],
            'anchored pattern'   => ['^start.*end$'],
            'alternation'        => ['foo|bar'],
            'quantifiers'        => ['a{1,3}'],
            'groups'             => ['(foo)(bar)'],
            'empty pattern'      => [''],
        ];
    }

    #[DataProvider('provideValidRegexPatterns')]
    #[TestDox('isRegex() accepts valid regex patterns')]
    public function test_is_regex_accepts_valid(
        string $inputValue,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that isRegex() accepts strings
        // that are valid PCRE regular expressions

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::string()->isRegex();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse($inputValue);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($inputValue, $actualResult);
    }

    /**
     * @return array<string, array{string}>
     */
    public static function provideInvalidRegexPatterns(): array
    {
        return [
            'unbalanced paren'   => ['(unclosed'],
            'bad quantifier'     => ['*'],
            'unbalanced bracket' => ['[unclosed'],
        ];
    }

    #[DataProvider('provideInvalidRegexPatterns')]
    #[TestDox('isRegex() rejects invalid regex patterns')]
    public function test_is_regex_rejects_invalid(
        string $inputValue,
    ): void {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that isRegex() rejects strings
        // that are not valid PCRE patterns

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::string()->isRegex();

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse($inputValue);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
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

        $unit = Validate::string()
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
