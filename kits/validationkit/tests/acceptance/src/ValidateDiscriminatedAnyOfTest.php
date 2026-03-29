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

#[TestDox('Validate::discriminatedAnyOf()')]
class ValidateDiscriminatedAnyOfTest extends TestCase
{
    // ================================================================
    //
    // Type Checking
    //
    // ----------------------------------------------------------------

    #[TestDox('routes to correct schema by discriminator field')]
    public function test_routes_by_discriminator_first_schema(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that discriminatedUnion() routes
        // the input to the correct schema based on the
        // discriminator field value

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::discriminatedAnyOf('type', [
            Validate::object([
                'type' => Validate::literal('a'),
                'x' => Validate::int(),
            ]),
            Validate::object([
                'type' => Validate::literal('b'),
                'y' => Validate::string(),
            ]),
        ]);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse([
            'type' => 'a',
            'x' => 42,
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            ['type' => 'a', 'x' => 42],
            $actualResult,
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('matches second schema by discriminator value')]
    public function test_routes_by_discriminator_second_schema(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that discriminatedUnion() can
        // route to the second schema when the discriminator
        // value matches it

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::discriminatedAnyOf('type', [
            Validate::object([
                'type' => Validate::literal('a'),
                'x' => Validate::int(),
            ]),
            Validate::object([
                'type' => Validate::literal('b'),
                'y' => Validate::string(),
            ]),
        ]);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse([
            'type' => 'b',
            'y' => 'hello',
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            ['type' => 'b', 'y' => 'hello'],
            $actualResult,
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('fails when discriminator field is missing')]
    public function test_fails_when_discriminator_missing(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that discriminatedUnion() reports
        // an error when the input does not contain the
        // discriminator field

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::discriminatedAnyOf('type', [
            Validate::object([
                'type' => Validate::literal('a'),
                'x' => Validate::int(),
            ]),
            Validate::object([
                'type' => Validate::literal('b'),
                'y' => Validate::string(),
            ]),
        ]);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse([
            'x' => 42,
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $this->assertSame(
            [
                [
                    'type'    => 'https://stusdevkit.dev/errors/validation/invalid_union',
                    'path'    => [],
                    'message' => 'Missing discriminator field "type"',
                ],
            ],
            $result->maybeError()->issues()->jsonSerialize(),
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('fails when no schema matches discriminator value')]
    public function test_fails_when_no_schema_matches(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that discriminatedUnion() reports
        // an error when the discriminator value does not
        // match any of the available schemas

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::discriminatedAnyOf('type', [
            Validate::object([
                'type' => Validate::literal('a'),
                'x' => Validate::int(),
            ]),
            Validate::object([
                'type' => Validate::literal('b'),
                'y' => Validate::string(),
            ]),
        ]);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse([
            'type' => 'c',
            'z' => true,
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $this->assertSame(
            [
                [
                    'type'    => 'https://stusdevkit.dev/errors/validation/invalid_union',
                    'path'    => [],
                    'message' => 'Unrecognised type value: "c"',
                ],
            ],
            $result->maybeError()->issues()->jsonSerialize(),
        );

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('fails when input is not an array')]
    public function test_fails_when_input_is_not_array(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that discriminatedUnion() reports
        // an error when the input is not an array

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::discriminatedAnyOf('type', [
            Validate::object([
                'type' => Validate::literal('a'),
                'x' => Validate::int(),
            ]),
            Validate::object([
                'type' => Validate::literal('b'),
                'y' => Validate::string(),
            ]),
        ]);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse('not-an-array');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $this->assertSame(
            [
                [
                    'type'    => 'https://stusdevkit.dev/errors/validation/invalid_type',
                    'path'    => [],
                    'message' => 'Expected object with discriminator'
                        . ' "type", received string',
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
        // ValidationException when the discriminated union
        // cannot match the input

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::discriminatedAnyOf('type', [
            Validate::object([
                'type' => Validate::literal('a'),
                'x' => Validate::int(),
            ]),
            Validate::object([
                'type' => Validate::literal('b'),
                'y' => Validate::string(),
            ]),
        ]);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $caughtException = null;
        try {
            $unit->parse('not-an-array');
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
                    'message' => 'Expected object with discriminator'
                        . ' "type", received string',
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
        // successful ParseResult when the input matches
        // a discriminated union member

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::discriminatedAnyOf('type', [
            Validate::object([
                'type' => Validate::literal('a'),
                'x' => Validate::int(),
            ]),
            Validate::object([
                'type' => Validate::literal('b'),
                'y' => Validate::string(),
            ]),
        ]);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse([
            'type' => 'a',
            'x' => 42,
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->succeeded());
        $this->assertFalse($result->failed());
        $this->assertSame(
            ['type' => 'a', 'x' => 42],
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
        // failed ParseResult when no discriminated union
        // member matches

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::discriminatedAnyOf('type', [
            Validate::object([
                'type' => Validate::literal('a'),
                'x' => Validate::int(),
            ]),
            Validate::object([
                'type' => Validate::literal('b'),
                'y' => Validate::string(),
            ]),
        ]);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse('not-an-array');

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
                    'message' => 'Expected object with discriminator'
                        . ' "type", received string',
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

        $unit = Validate::discriminatedAnyOf('type', [
            Validate::object([
                'type' => Validate::literal('a'),
                'x' => Validate::int(),
            ]),
            Validate::object([
                'type' => Validate::literal('b'),
                'y' => Validate::string(),
            ]),
        ])->withDefault(['type' => 'a', 'x' => 0]);

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
            ['type' => 'a', 'x' => 0],
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

    #[TestDox('withTransform() modifies the validated data')]
    public function test_with_transform_modifies_data(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::discriminatedAnyOf('type', [
            Validate::object([
                'type' => Validate::literal('a'),
                'x' => Validate::int(),
            ]),
            Validate::object([
                'type' => Validate::literal('b'),
                'y' => Validate::string(),
            ]),
        ])->withTransform(
            function (mixed $data) {
                /** @var array<string, mixed> $data */
                return $data['type'];
            },
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse([
            'type' => 'a',
            'x' => 42,
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('a', $actualResult);

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

        $unit = Validate::discriminatedAnyOf('type', [
            Validate::object([
                'type' => Validate::literal('a'),
                'x' => Validate::int(),
            ]),
            Validate::object([
                'type' => Validate::literal('b'),
                'y' => Validate::string(),
            ]),
        ])->withTransformer(
            new CallableTransformer(
                function (mixed $data) {
                    /** @var array<string, mixed> $data */
                    return $data['type'];
                },
            ),
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse(['type' => 'a', 'x' => 42]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('a', $actualResult);

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

        $unit = Validate::discriminatedAnyOf('type', [
            Validate::object([
                'type' => Validate::literal('a'),
                'x' => Validate::int(),
            ]),
            Validate::object([
                'type' => Validate::literal('b'),
                'y' => Validate::string(),
            ]),
        ])->withTransformer(
            new CallableTransformer(
                function (mixed $data) {
                    /** @var array<string, mixed> $data */
                    return $data['type'];
                },
            ),
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->safeParse(['type' => 'a', 'x' => 42]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actualResult->succeeded());
        $this->assertSame('a', $actualResult->data());

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

        $unit = Validate::discriminatedAnyOf('type', [
            Validate::object([
                'type' => Validate::literal('a'),
                'x' => Validate::int(),
            ]),
            Validate::object([
                'type' => Validate::literal('b'),
                'y' => Validate::string(),
            ]),
        ])->withTransformer(
            new CallableTransformer(
                function (mixed $data) {
                    /** @var array<string, mixed> $data */
                    return $data['type'];
                },
            ),
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->encode(['type' => 'a', 'x' => 42]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('a', $actualResult);

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

        $unit = Validate::discriminatedAnyOf('type', [
            Validate::object([
                'type' => Validate::literal('a'),
                'x' => Validate::int(),
            ]),
            Validate::object([
                'type' => Validate::literal('b'),
                'y' => Validate::string(),
            ]),
        ])->withTransformer(
            new CallableTransformer(
                function (mixed $data) {
                    /** @var array<string, mixed> $data */
                    return $data['type'];
                },
            ),
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->safeEncode(['type' => 'a', 'x' => 42]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actualResult->succeeded());
        $this->assertSame('a', $actualResult->data());

        // ----------------------------------------------------------------
        // clean up the database

    }

    #[TestDox('withRefine() adds custom validation')]
    public function test_with_refine_adds_custom_validation(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that withRefine() can reject a value
        // that passes the discriminated union type check

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::discriminatedAnyOf('type', [
            Validate::object([
                'type' => Validate::literal('a'),
                'x' => Validate::int(),
            ]),
            Validate::object([
                'type' => Validate::literal('b'),
                'y' => Validate::string(),
            ]),
        ])->withRefine(
            function (mixed $data) {
                /** @var array<string, mixed> $data */
                return $data['x'] > 0;
            },
            'x must be positive',
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse([
            'type' => 'a',
            'x' => -1,
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $this->assertSame(
            [
                [
                    'type'    => 'https://stusdevkit.dev/errors/validation/custom',
                    'path'    => [],
                    'message' => 'x must be positive',
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
        // of the discriminated union to another schema for
        // further validation

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::discriminatedAnyOf('type', [
            Validate::object([
                'type' => Validate::literal('a'),
                'x' => Validate::int(),
            ]),
            Validate::object([
                'type' => Validate::literal('b'),
                'y' => Validate::string(),
            ]),
        ])
            ->withTransform(function (mixed $data) {
                /** @var array<string, mixed> $data */
                return $data['type'];
            })
            ->withPipe(Validate::string()->min(length: 1));

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse([
            'type' => 'a',
            'x' => 42,
        ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('a', $actualResult);

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

        $unit = Validate::discriminatedAnyOf('type', [
            Validate::object([
                'type' => Validate::literal('a'),
                'x' => Validate::int(),
            ]),
            Validate::object([
                'type' => Validate::literal('b'),
                'y' => Validate::string(),
            ]),
        ])->withCatch(['type' => 'a', 'x' => 0]);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->parse('not-an-array');

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(
            ['type' => 'a', 'x' => 0],
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

    #[TestDox('custom error callback is used on failure')]
    public function test_custom_error_callback(): void
    {
        // ----------------------------------------------------------------
        // explain your test

        // this test proves that a custom error callback on
        // the discriminated union produces a custom
        // ValidationIssue when the input is not an array

        // ----------------------------------------------------------------
        // shorthand

        // ----------------------------------------------------------------
        // setup your test

        $unit = Validate::discriminatedAnyOf(
            discriminator: 'type',
            schemas: [
                Validate::object([
                    'type' => Validate::literal('a'),
                    'x' => Validate::int(),
                ]),
                Validate::object([
                    'type' => Validate::literal('b'),
                    'y' => Validate::string(),
                ]),
            ],
            error: fn(mixed $data) => new ValidationIssue(
                input: $data,
                path: [],
                message: 'Custom: invalid event',
                type: 'https://example.com/errors/invalid-event',
                title: 'Invalid event',
            ),
        );

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse('not-an-array');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $this->assertSame(
            [
                [
                    'type'    => 'https://example.com/errors/invalid-event',
                    'path'    => [],
                    'message' => 'Custom: invalid event',
                ],
            ],
            $result->maybeError()->issues()->jsonSerialize(),
        );

        $issue = $result->maybeError()->issues()->first();
        $this->assertSame(
            'https://example.com/errors/invalid-event',
            $issue->type,
        );
        $this->assertSame('Invalid event', $issue->title);

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

        $unit = Validate::discriminatedAnyOf('type', [
            Validate::object([
                'type' => Validate::literal('a'),
                'x' => Validate::int(),
            ]),
            Validate::object([
                'type' => Validate::literal('b'),
                'y' => Validate::string(),
            ]),
        ]);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse('not-an-array');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result->failed());
        $this->assertSame(
            [
                [
                    'type'    => 'https://stusdevkit.dev/errors/validation/invalid_type',
                    'path'    => [],
                    'message' => 'Expected object with discriminator'
                        . ' "type", received string',
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

        $unit = Validate::discriminatedAnyOf('type', [
            Validate::object([
                'type' => Validate::literal('a'),
                'x' => Validate::int(),
            ]),
            Validate::object([
                'type' => Validate::literal('b'),
                'y' => Validate::string(),
            ]),
        ])->withDescription('An event by type');

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->maybeDescription();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame('An event by type', $actualResult);

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

        $unit = Validate::discriminatedAnyOf('type', [
            Validate::object([
                'type' => Validate::literal('a'),
                'x' => Validate::int(),
            ]),
            Validate::object([
                'type' => Validate::literal('b'),
                'y' => Validate::string(),
            ]),
        ])->withMeta(['label' => 'Event']);

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $unit->metadata();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame(['label' => 'Event'], $actualResult);

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

        $unit = Validate::discriminatedAnyOf('type', [
            Validate::object([
                'type' => Validate::literal('a'),
                'x' => Validate::int(),
            ]),
            Validate::object([
                'type' => Validate::literal('b'),
                'y' => Validate::string(),
            ]),
        ])->withConstraint(new RejectEverythingConstraint());

        // ----------------------------------------------------------------
        // mock out any integrations

        // ----------------------------------------------------------------
        // pre-test checks

        // ----------------------------------------------------------------
        // perform the change

        $result = $unit->safeParse([
            'type' => 'a',
            'x' => 42,
        ]);

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
            $result->maybeError()->issues()->jsonSerialize(),
        );

        // ----------------------------------------------------------------
        // clean up the database

    }
}
