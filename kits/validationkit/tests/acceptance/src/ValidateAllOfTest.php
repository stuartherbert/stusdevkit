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
use StusDevKit\ValidationKit\Exceptions\ValidationException;
use StusDevKit\ValidationKit\Tests\Fixtures\CallableTransformer;
use StusDevKit\ValidationKit\Tests\Fixtures\RejectEverythingConstraint;
use StusDevKit\ValidationKit\Validate;
use StusDevKit\ValidationKit\ValidationIssue;

#[TestDox('Validate::allOf()')]
class ValidateAllOfTest extends TestCase
{
    // ================================================================
    //
    // Type Checking
    //
    // ----------------------------------------------------------------

    #[TestDox('accepts input that passes all schemas')]
    public function test_accepts_input_passing_all_schemas(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that allOf() accepts input that
        // satisfies all of the given schemas

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::allOf(
            schemas: [
                Validate::object([
                    'name' => Validate::string(),
                ]),
                Validate::object([
                    'age' => Validate::int(),
                ]),
            ],
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse([
            'name' => 'Stuart',
            'age' => 42,
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            ['name' => 'Stuart', 'age' => 42],
            $actualResult,
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('accepts more than two schemas')]
    public function test_accepts_more_than_two_schemas(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that allOf() works with three or
        // more schemas, not just two

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::allOf(
            schemas: [
                Validate::object([
                    'name' => Validate::string(),
                ]),
                Validate::object([
                    'age' => Validate::int(),
                ]),
                Validate::object([
                    'email' => Validate::string()->email(),
                ]),
            ],
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse([
            'name' => 'Stuart',
            'age' => 42,
            'email' => 'stuart@example.com',
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            [
                'name' => 'Stuart',
                'age' => 42,
                'email' => 'stuart@example.com',
            ],
            $actualResult,
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('fails when any of three or more schemas fails')]
    public function test_fails_when_any_of_three_schemas_fails(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that allOf() rejects input when
        // any one of three or more schemas fails

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::allOf(
            schemas: [
                Validate::object([
                    'name' => Validate::string(),
                ]),
                Validate::object([
                    'age' => Validate::int(),
                ]),
                Validate::object([
                    'email' => Validate::string()->email(),
                ]),
            ],
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse([
            'name' => 'Stuart',
            'age' => 42,
            'email' => 'not-an-email',
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());

        $this->assertSame(
            [
                [
                    'type' => 'https://stusdevkit.dev/errors/validation/invalid_string',
                    'path' => ['email'],
                    'message' => 'Invalid email address',
                ],
            ],
            $result->maybeError()->issues()->jsonSerialize(),
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('merges object results from all schemas')]
    public function test_merges_object_results(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that intersection() merges the
        // array results from both object schemas into a
        // single combined array

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::allOf(
            schemas: [
                Validate::object([
                    'name' => Validate::string(),
                ]),
                Validate::object([
                    'age' => Validate::int(),
                ]),
            ],
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse([
            'name' => 'Stuart',
            'age' => 42,
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertIsArray($actualResult);
        $this->assertArrayHasKey('name', $actualResult);
        $this->assertArrayHasKey('age', $actualResult);
        $this->assertSame('Stuart', $actualResult['name']);
        $this->assertSame(42, $actualResult['age']);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('fails when first schema fails')]
    public function test_fails_when_first_schema_fails(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that allOf() reports issues when
        // the first schema rejects the input

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::allOf(
            schemas: [
                Validate::object([
                    'name' => Validate::string(),
                ]),
                Validate::object([
                    'age' => Validate::int(),
                ]),
            ],
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse([
            'name' => 123,
            'age' => 42,
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());

        $this->assertSame(
            [
                [
                    'type' => 'https://stusdevkit.dev/errors/validation/invalid_type',
                    'path' => ['name'],
                    'message' => 'Expected string, received int',
                ],
            ],
            $result->maybeError()->issues()->jsonSerialize(),
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('fails when second schema fails')]
    public function test_fails_when_second_schema_fails(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that allOf() reports issues when
        // the second schema rejects the input

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::allOf(
            schemas: [
                Validate::object([
                    'name' => Validate::string(),
                ]),
                Validate::object([
                    'age' => Validate::int(),
                ]),
            ],
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse([
            'name' => 'Stuart',
            'age' => 'not-an-int',
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());

        $this->assertSame(
            [
                [
                    'type' => 'https://stusdevkit.dev/errors/validation/invalid_type',
                    'path' => ['age'],
                    'message' => 'Expected int, received string',
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
        // ValidationException when any schema in the
        // allOf fails

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::allOf(
            schemas: [
                Validate::object([
                    'name' => Validate::string(),
                ]),
                Validate::object([
                    'age' => Validate::int(),
                ]),
            ],
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
                'name' => 'Stuart',
                'age' => 'not-an-int',
            ]);
        } catch (ValidationException $e) {
            $caughtException = $e;
        }

        // ----------------------------------------------------------------
        // test the results

        $this->assertNotNull($caughtException);

        $this->assertSame(
            [
                [
                    'type' => 'https://stusdevkit.dev/errors/validation/invalid_type',
                    'path' => ['age'],
                    'message' => 'Expected int, received string',
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
        // successful ParseResult when both schemas pass

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::allOf(
            schemas: [
                Validate::object([
                    'name' => Validate::string(),
                ]),
                Validate::object([
                    'age' => Validate::int(),
                ]),
            ],
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse([
            'name' => 'Stuart',
            'age' => 42,
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->succeeded());
        $this->assertFalse($result->failed());
        $this->assertSame(
            ['name' => 'Stuart', 'age' => 42],
            $result->data(),
        );
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
        // failed ParseResult when any schema fails

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::allOf(
            schemas: [
                Validate::object([
                    'name' => Validate::string(),
                ]),
                Validate::object([
                    'age' => Validate::int(),
                ]),
            ],
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse([
            'name' => 'Stuart',
            'age' => 'not-an-int',
        ]);

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
                    'type' => 'https://stusdevkit.dev/errors/validation/invalid_type',
                    'path' => ['age'],
                    'message' => 'Expected int, received string',
                ],
            ],
            $result->maybeError()->issues()->jsonSerialize(),
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

        $unit = Validate::allOf(
            schemas: [
                Validate::object([
                    'name' => Validate::string(),
                ]),
                Validate::object([
                    'age' => Validate::int(),
                ]),
            ],
        )->withDefault(['name' => 'default', 'age' => 0]);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse(null);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            ['name' => 'default', 'age' => 0],
            $actualResult,
        );

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

        $unit = Validate::allOf(
            schemas: [
                Validate::object([
                    'name' => Validate::string(),
                ]),
                Validate::object([
                    'age' => Validate::int(),
                ]),
            ],
        )->withCustomTransform(
            function (mixed $data) {
                /** @var array{name: string, age: int} $data */
                return $data['name'] . ':' . $data['age'];
            },
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse([
            'name' => 'Stuart',
            'age' => 42,
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('Stuart:42', $actualResult);

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

        $unit = Validate::allOf(schemas: [Validate::object(['name' => Validate::string()]), Validate::object(['age' => Validate::int()])])->withTransformer(
            new CallableTransformer(
                function (mixed $data) {
                    /** @var array{name: string, age: int} $data */
                    return $data['name'] . ':' . $data['age'];
                },
            ),
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse(['name' => 'Stuart', 'age' => 42]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('Stuart:42', $actualResult);

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

        $unit = Validate::allOf(schemas: [Validate::object(['name' => Validate::string()]), Validate::object(['age' => Validate::int()])])->withTransformer(
            new CallableTransformer(
                function (mixed $data) {
                    /** @var array{name: string, age: int} $data */
                    return $data['name'] . ':' . $data['age'];
                },
            ),
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->safeParse(['name' => 'Stuart', 'age' => 42]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actualResult->succeeded());
        $this->assertSame('Stuart:42', $actualResult->data());

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

        $unit = Validate::allOf(schemas: [Validate::object(['name' => Validate::string()]), Validate::object(['age' => Validate::int()])])->withTransformer(
            new CallableTransformer(
                function (mixed $data) {
                    /** @var array{name: string, age: int} $data */
                    return $data['name'] . ':' . $data['age'];
                },
            ),
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->encode(['name' => 'Stuart', 'age' => 42]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('Stuart:42', $actualResult);

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

        $unit = Validate::allOf(schemas: [Validate::object(['name' => Validate::string()]), Validate::object(['age' => Validate::int()])])->withTransformer(
            new CallableTransformer(
                function (mixed $data) {
                    /** @var array{name: string, age: int} $data */
                    return $data['name'] . ':' . $data['age'];
                },
            ),
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->safeEncode(['name' => 'Stuart', 'age' => 42]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actualResult->succeeded());
        $this->assertSame('Stuart:42', $actualResult->data());

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('withCustomConstraint() adds custom validation')]
    public function test_with_custom_constraint_adds_custom_validation(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that withCustomConstraint() can
        // reject a value that passes both intersection schemas

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::allOf(
            schemas: [
                Validate::object([
                    'name' => Validate::string(),
                ]),
                Validate::object([
                    'age' => Validate::int(),
                ]),
            ],
        )->withCustomConstraint(
            function (mixed $data) {
                /** @var array<string, mixed> $data */
                return $data['age'] >= 18
                    ? null
                    : 'Must be at least 18';
            },
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse([
            'name' => 'Stuart',
            'age' => 10,
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $issue = $result->maybeError()->issues()->first();
        $this->assertSame('https://stusdevkit.dev/errors/validation/custom', $issue->type);
        $this->assertSame('Must be at least 18', $issue->message);

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('withPipe() chains to another schema')]
    public function test_with_pipe_chains_schemas(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that withPipe() passes the output
        // of the intersection to another schema for further
        // validation

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::allOf(
            schemas: [
                Validate::object([
                    'name' => Validate::string(),
                ]),
                Validate::object([
                    'age' => Validate::int(),
                ]),
            ],
        )
            ->withCustomTransform(function (mixed $data) {
                /** @var array<string, mixed> $data */
                return $data['name'];
            })
            ->withPipe(Validate::string()->min(length: 3));

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse([
            'name' => 'Stuart',
            'age' => 42,
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('Stuart', $actualResult);

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

        $unit = Validate::allOf(
            schemas: [
                Validate::object([
                    'name' => Validate::string(),
                ]),
                Validate::object([
                    'age' => Validate::int(),
                ]),
            ],
        )->withCatch(['name' => 'unknown', 'age' => 0]);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse([
            'name' => 'Stuart',
            'age' => 'not-an-int',
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            ['name' => 'unknown', 'age' => 0],
            $actualResult,
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    // ================================================================
    //
    // Custom Error Callbacks
    //
    // ----------------------------------------------------------------

    #[TestDox('custom error callback is used on intersection failure')]
    public function test_custom_error_callback(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a custom error callback on
        // the intersection produces a custom ValidationIssue
        // when null is passed without nullable/optional

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::allOf(
            schemas: [
                Validate::object([
                    'name' => Validate::string(),
                ]),
                Validate::object([
                    'age' => Validate::int(),
                ]),
            ],
            error: fn(mixed $data) => new ValidationIssue(
                input: $data,
                path: [],
                message: 'Custom: not an intersection',
                type: 'https://example.com/errors/intersection',
                title: 'Not an intersection',
            ),
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse(null);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $issue = $result->maybeError()->issues()->first();
        $this->assertSame(
            'Custom: not an intersection',
            $issue->message,
        );
        $this->assertSame(
            'https://example.com/errors/intersection',
            $issue->type,
        );
        $this->assertSame('Not an intersection', $issue->title);

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

        $unit = Validate::allOf(
            schemas: [
                Validate::object([
                    'name' => Validate::string(),
                ]),
                Validate::object([
                    'age' => Validate::int(),
                ]),
            ],
        )->withDescription('A person with name and age');

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeDescription();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            'A person with name and age',
            $actualResult,
        );

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

        $unit = Validate::allOf(
            schemas: [
                Validate::object([
                    'name' => Validate::string(),
                ]),
                Validate::object([
                    'age' => Validate::int(),
                ]),
            ],
        )->withMeta(['label' => 'Person']);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->metadata();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(['label' => 'Person'], $actualResult);

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

        $unit = Validate::allOf(
            schemas: [
                Validate::object([
                    'name' => Validate::string(),
                ]),
                Validate::object([
                    'age' => Validate::int(),
                ]),
            ],
        )->withConstraint(new RejectEverythingConstraint());

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse([
            'name' => 'Stuart',
            'age' => 42,
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $issue = $result->error()->issues()->first();
        $this->assertSame('https://stusdevkit.dev/errors/validation/custom', $issue->type);
        $this->assertSame(
            'rejected by custom constraint',
            $issue->message,
        );

        // ----------------------------------------------------------------
        // clean up the database

    }
}
