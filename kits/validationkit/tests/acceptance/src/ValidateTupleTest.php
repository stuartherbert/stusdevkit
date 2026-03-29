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

#[TestDox('Validate::tuple()')]
class ValidateTupleTest extends TestCase
{
    // ================================================================
    //
    // Type Checking
    //
    // ----------------------------------------------------------------

    #[TestDox('accepts a valid tuple')]
    public function test_accepts_valid_tuple(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that Validate::tuple()->parse()
        // accepts an array matching the positional schemas
        // and returns it unchanged

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::tuple([
            Validate::string(),
            Validate::int(),
        ]);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse(['hello', 42]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(['hello', 42], $actualResult);

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

        // this test proves that Validate::tuple()->parse()
        // throws a ValidationException for non-array input

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::tuple([
            Validate::string(),
            Validate::int(),
        ]);

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
    // Positional Validation
    //
    // ----------------------------------------------------------------

    #[TestDox('validates each position against its schema')]
    public function test_validates_each_position(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that each position in the tuple
        // is validated against the schema at that position

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::tuple([
            Validate::string(),
            Validate::int(),
            Validate::boolean(),
        ]);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse(['hello', 42, true]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(['hello', 42, true], $actualResult);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('too few elements produces TooSmall error')]
    public function test_too_few_elements(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that when the array has fewer
        // elements than the tuple expects, a TooSmall error
        // is produced

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::tuple([
            Validate::string(),
            Validate::int(),
        ]);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse(['hello']);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $this->assertSame(
            [
                [
                    'type'    => 'https://stusdevkit.dev/errors/validation/too_small',
                    'path'    => [],
                    'message' => 'Tuple must have exactly 2 elements,'
                        . ' received 1',
                ],
            ],
            $result->maybeError()->issues()->jsonSerialize(),
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('too many elements produces TooBig error')]
    public function test_too_many_elements(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that when the array has more
        // elements than the tuple expects, a TooBig error
        // is produced

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::tuple([
            Validate::string(),
            Validate::int(),
        ]);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse(['hello', 42, 'extra']);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $this->assertSame(
            [
                [
                    'type'    => 'https://stusdevkit.dev/errors/validation/too_big',
                    'path'    => [],
                    'message' => 'Tuple must have exactly 2 elements,'
                        . ' received 3',
                ],
            ],
            $result->maybeError()->issues()->jsonSerialize(),
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('position error produces indexed path')]
    public function test_position_error_produces_indexed_path(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that when an element at a specific
        // position fails validation, the issue path includes
        // the position index in bracket notation

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::tuple([
            Validate::string(),
            Validate::int(),
        ]);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse(['hello', 'not an int']);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $this->assertSame(
            [
                [
                    'type'    => 'https://stusdevkit.dev/errors/validation/invalid_type',
                    'path'    => [1],
                    'message' => 'Expected int, received string',
                ],
            ],
            $result->maybeError()->issues()->jsonSerialize(),
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('first position error produces [0] path')]
    public function test_first_position_error_path(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that an error at position 0
        // produces the path '[0]'

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::tuple([
            Validate::string(),
            Validate::int(),
        ]);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse([42, 100]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $this->assertSame(
            [
                [
                    'type'    => 'https://stusdevkit.dev/errors/validation/invalid_type',
                    'path'    => [0],
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

        $unit = Validate::tuple([
            Validate::string(),
            Validate::int(),
        ]);

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
                    'message' => 'Expected tuple (array), received int',
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
        // successful ParseResult for valid tuple input

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::tuple([
            Validate::string(),
            Validate::int(),
        ]);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse(['hello', 42]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->succeeded());
        $this->assertFalse($result->failed());
        $this->assertSame(['hello', 42], $result->data());
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

        $unit = Validate::tuple([
            Validate::string(),
            Validate::int(),
        ]);

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

        $unit = Validate::tuple([
            Validate::string(),
            Validate::int(),
        ])->withDefault(['default', 0]);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse(null);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(['default', 0], $actualResult);

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

        $unit = Validate::tuple([
            Validate::string(),
            Validate::int(),
        ])->withCustomTransform(
            function (mixed $data) {
                /** @var array{string, int} $data */
                return $data[0] . ':' . $data[1];
            },
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse(['key', 42]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('key:42', $actualResult);

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

        $unit = Validate::tuple([Validate::string(), Validate::int()])->withTransformer(
            new CallableTransformer(
                function (mixed $data) {
                    /** @var array{string, int} $data */
                    return $data[0] . ':' . $data[1];
                },
            ),
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse(['key', 42]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('key:42', $actualResult);

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

        $unit = Validate::tuple([Validate::string(), Validate::int()])->withTransformer(
            new CallableTransformer(
                function (mixed $data) {
                    /** @var array{string, int} $data */
                    return $data[0] . ':' . $data[1];
                },
            ),
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->safeParse(['key', 42]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actualResult->succeeded());
        $this->assertSame('key:42', $actualResult->data());

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

        $unit = Validate::tuple([Validate::string(), Validate::int()])->withTransformer(
            new CallableTransformer(
                function (mixed $data) {
                    /** @var array{string, int} $data */
                    return $data[0] . ':' . $data[1];
                },
            ),
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->encode(['key', 42]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('key:42', $actualResult);

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

        $unit = Validate::tuple([Validate::string(), Validate::int()])->withTransformer(
            new CallableTransformer(
                function (mixed $data) {
                    /** @var array{string, int} $data */
                    return $data[0] . ':' . $data[1];
                },
            ),
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->safeEncode(['key', 42]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actualResult->succeeded());
        $this->assertSame('key:42', $actualResult->data());

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('withCustomConstraint() adds custom validation')]
    public function test_with_custom_constraint_adds_custom_validation(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that withCustomConstraint() can
        // reject a value that passes type and positional
        // checks

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::tuple([
            Validate::int(),
            Validate::int(),
        ])->withCustomConstraint(
            function (mixed $data) {
                /** @var array<int, mixed> $data */
                return $data[0] < $data[1]
                    ? null
                    : 'First element must be less than second';
            },
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse([10, 5]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $this->assertSame(
            [
                [
                    'type'    => 'https://stusdevkit.dev/errors/validation/custom',
                    'path'    => [],
                    'message' => 'First element must be less than second',
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

        // this test proves that withPipe() passes the output of
        // this schema to another schema for further
        // validation

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::tuple([
            Validate::string(),
            Validate::int(),
        ])->withCustomTransform(
            function (mixed $data) {
                /** @var array{string, int} $data */
                return $data[0] . ':' . $data[1];
            },
        )->withPipe(
            Validate::string()->min(length: 3),
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse(['key', 42]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('key:42', $actualResult);

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

        $unit = Validate::tuple([
            Validate::string(),
            Validate::int(),
        ])->withCatch(['', 0]);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse(42);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(['', 0], $actualResult);

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

        $unit = Validate::tuple(
            schemas: [
                Validate::string(),
                Validate::int(),
            ],
            error: fn(mixed $data) => new ValidationIssue(
                input: $data,
                path: [],
                message: 'Custom: not a tuple',
                type: 'https://example.com/errors/not-tuple',
                title: 'Not a tuple',
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
                    'type'    => 'https://example.com/errors/not-tuple',
                    'path'    => [],
                    'message' => 'Custom: not a tuple',
                ],
            ],
            $result->maybeError()->issues()->jsonSerialize(),
        );

        $issue = $result->maybeError()->issues()->first();
        $this->assertSame(
            'https://example.com/errors/not-tuple',
            $issue->type,
        );
        $this->assertSame('Not a tuple', $issue->title);

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

        $unit = Validate::tuple([
            Validate::string(),
            Validate::int(),
        ]);

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
                    'message' => 'Expected tuple (array), received int',
                ],
            ],
            $result->maybeError()->issues()->jsonSerialize(),
        );

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

        $unit = Validate::tuple([
            Validate::string(),
            Validate::int(),
        ])->withDescription('A key-value pair');

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeDescription();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('A key-value pair', $actualResult);

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

        $unit = Validate::tuple([
            Validate::string(),
            Validate::int(),
        ])->withMeta(['label' => 'Coordinate']);

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
            ['label' => 'Coordinate'],
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

        $unit = Validate::tuple([
            Validate::string(),
        ])->withConstraint(new RejectEverythingConstraint());

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse(['hello']);

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
